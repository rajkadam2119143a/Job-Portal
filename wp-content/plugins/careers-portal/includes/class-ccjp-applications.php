<?php
defined( 'ABSPATH' ) || exit;

/**
 * Handles the actual application submission: nonce + field validation,
 * resume upload (to a protected, non-public folder, size/type checked
 * against filterable limits), career_application creation, and the two
 * notification emails. This is the one place "Apply Now" traffic goes
 * through — registered for both logged-in and guest visitors, since
 * candidates aren't expected to have an account.
 *
 * Uses a plain POST to admin-post.php (not AJAX) so file upload works with
 * zero JavaScript; the browser navigates to the result either way.
 */
class CCJP_Applications {

	const ACTION       = 'ccjp_submit_application';
	const RESUME_DIR   = 'careers-portal-resumes';
	const NONCE_ACTION = 'ccjp_apply';

	public function __construct() {
		add_action( 'admin_post_' . self::ACTION, array( $this, 'handle_submit' ) );
		add_action( 'admin_post_nopriv_' . self::ACTION, array( $this, 'handle_submit' ) );
	}

	public static function max_resume_size_mb() {
		return (int) apply_filters( 'ccjp_max_resume_size_mb', 5 );
	}

	public static function get_by_reference( $reference ) {
		$reference = sanitize_text_field( $reference );
		if ( '' === $reference ) {
			return 0;
		}

		$posts = get_posts(
			array(
				'post_type'      => 'career_application',
				'post_status'    => array_keys( CCJP_Post_Statuses::application_statuses() ),
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => '_ccjp_reference',
						'value' => $reference,
					),
				),
			)
		);

		return ! empty( $posts ) ? (int) $posts[0] : 0;
	}

	public static function allowed_resume_mimes() {
		return apply_filters(
			'ccjp_allowed_resume_mimes',
			array(
				'pdf'  => 'application/pdf',
				'doc'  => 'application/msword',
				'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			)
		);
	}

	public function handle_submit() {
		$job_id = isset( $_POST['ccjp_job_id'] ) ? absint( $_POST['ccjp_job_id'] ) : 0;

		if ( ! $job_id || 'career_job' !== get_post_type( $job_id ) ) {
			$this->fail( 0, 'invalid_job' );
		}

		if ( CCJP_Job_Data::is_closed( $job_id ) ) {
			$this->fail( $job_id, 'invalid_job' );
		}

		$nonce = isset( $_POST['ccjp_apply_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ccjp_apply_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION . '_' . $job_id ) ) {
			$this->fail( $job_id, 'invalid_nonce' );
		}

		$data = $this->collect_fields();

		if ( empty( $data['first_name'] ) || empty( $data['last_name'] ) || empty( $data['email'] ) || empty( $data['phone'] ) ) {
			$this->fail( $job_id, 'missing_fields' );
		}

		if ( ! is_email( $data['email'] ) ) {
			$this->fail( $job_id, 'invalid_email' );
		}

		if ( empty( $_POST['ccjp_privacy_agreement'] ) ) {
			$this->fail( $job_id, 'privacy_required' );
		}

		if ( empty( $_FILES['resume'] ) || UPLOAD_ERR_NO_FILE === $_FILES['resume']['error'] ) {
			$this->fail( $job_id, 'missing_resume' );
		}

		if ( UPLOAD_ERR_OK !== $_FILES['resume']['error'] ) {
			$this->fail( $job_id, 'upload_failed' );
		}

		$max_bytes = self::max_resume_size_mb() * MB_IN_BYTES;
		if ( $_FILES['resume']['size'] > $max_bytes ) {
			$this->fail( $job_id, 'resume_too_large' );
		}

		$filetype = wp_check_filetype_and_ext( $_FILES['resume']['tmp_name'], $_FILES['resume']['name'] );
		$allowed  = self::allowed_resume_mimes();
		if ( empty( $filetype['ext'] ) || ! isset( $allowed[ $filetype['ext'] ] ) || $filetype['type'] !== $allowed[ $filetype['ext'] ] ) {
			$this->fail( $job_id, 'invalid_resume_type' );
		}

		$attachment_id = $this->upload_resume( $job_id );
		if ( is_wp_error( $attachment_id ) ) {
			$this->fail( $job_id, 'upload_failed' );
		}

		$application_id = $this->create_application( $job_id, $data, $attachment_id );

		$this->send_notifications( $application_id, $job_id, $data );

		$success_url = ! empty( $_POST['ccjp_redirect_to'] )
			? esc_url_raw( wp_unslash( $_POST['ccjp_redirect_to'] ) )
			: CCJP_Pages::get_page_url( 'application-success' );

		$reference = get_post_meta( $application_id, '_ccjp_reference', true );

		wp_safe_redirect(
			add_query_arg(
				array(
					'application_id' => rawurlencode( $reference ),
				),
				$success_url ? $success_url : home_url( '/' )
			)
		);
		exit;
	}

	private function collect_fields() {
		$fields = array(
			'first_name'         => 'sanitize_text_field',
			'last_name'          => 'sanitize_text_field',
			'email'              => 'sanitize_email',
			'phone'              => 'sanitize_text_field',
			'current_location'   => 'sanitize_text_field',
			'years_of_experience' => 'sanitize_text_field',
			'current_company'    => 'sanitize_text_field',
			'notice_period'      => 'sanitize_text_field',
			'linkedin'           => 'esc_url_raw',
			'portfolio'          => 'esc_url_raw',
			'cover_letter'       => 'sanitize_textarea_field',
		);

		$data = array();
		foreach ( $fields as $key => $sanitizer ) {
			$data[ $key ] = isset( $_POST[ $key ] ) ? call_user_func( $sanitizer, wp_unslash( $_POST[ $key ] ) ) : '';
		}
		return $data;
	}

	private function upload_resume( $job_id ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		add_filter( 'upload_dir', array( $this, 'protected_upload_dir' ) );

		$overrides = array(
			'test_form' => false,
			'mimes'     => self::allowed_resume_mimes(),
		);
		$uploaded  = wp_handle_upload( $_FILES['resume'], $overrides );

		remove_filter( 'upload_dir', array( $this, 'protected_upload_dir' ) );

		if ( empty( $uploaded['file'] ) ) {
			return new WP_Error( 'ccjp_upload_failed', $uploaded['error'] ?? 'Upload failed' );
		}

		$this->ensure_protected_directory( dirname( $uploaded['file'] ) );

		$attachment = array(
			'guid'           => $uploaded['url'],
			'post_mime_type' => $uploaded['type'],
			// translators: %s is the candidate's uploaded filename.
			'post_title'     => sprintf( __( 'Resume — %s', 'careers-portal' ), basename( $uploaded['file'] ) ),
			'post_content'   => '',
			'post_status'    => 'private',
			'post_parent'    => $job_id,
		);

		$attachment_id = wp_insert_attachment( $attachment, $uploaded['file'] );
		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		$metadata = wp_generate_attachment_metadata( $attachment_id, $uploaded['file'] );
		wp_update_attachment_metadata( $attachment_id, $metadata );

		return $attachment_id;
	}

	/**
	 * Scoped to only this upload call (added/removed around wp_handle_upload)
	 * — moves resumes out of the default publicly-served media path into a
	 * dedicated folder that .htaccess then blocks direct HTTP access to.
	 * There is no authenticated "view/download resume" endpoint yet (that
	 * belongs with the admin Application Detail widget in the next phase);
	 * until then the file is safely stored but not retrievable via URL.
	 */
	public function protected_upload_dir( $dirs ) {
		$dirs['subdir'] = '/' . self::RESUME_DIR . $dirs['subdir'];
		$dirs['path']   = $dirs['basedir'] . $dirs['subdir'];
		$dirs['url']    = $dirs['baseurl'] . $dirs['subdir'];
		return $dirs;
	}

	private function ensure_protected_directory( $dir ) {
		$htaccess = trailingslashit( $dir ) . '.htaccess';
		$expected = "<IfModule mod_authz_core.c>\n\tRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n\tDeny from all\n</IfModule>\n";
		if ( ! file_exists( $htaccess ) || file_get_contents( $htaccess ) !== $expected ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_get_contents
			file_put_contents( $htaccess, $expected ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		}
		$index = trailingslashit( $dir ) . 'index.php';
		if ( ! file_exists( $index ) ) {
			file_put_contents( $index, "<?php\n// Silence is golden.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		}
	}

	private function create_application( $job_id, array $data, $attachment_id ) {
		$job_title = get_the_title( $job_id );
		$name      = trim( $data['first_name'] . ' ' . $data['last_name'] );

		$application_id = wp_insert_post(
			array(
				'post_type'   => 'career_application',
				// translators: 1: candidate name, 2: job title.
				'post_title'  => sprintf( __( '%1$s — %2$s', 'careers-portal' ), $name, $job_title ),
				'post_status' => CCJP_Post_Statuses::default_application_status(),
			)
		);

		if ( is_wp_error( $application_id ) ) {
			return 0;
		}

		update_field( 'ccjp_first_name', $data['first_name'], $application_id );
		update_field( 'ccjp_last_name', $data['last_name'], $application_id );
		update_field( 'ccjp_email', $data['email'], $application_id );
		update_field( 'ccjp_phone', $data['phone'], $application_id );
		update_field( 'ccjp_current_location', $data['current_location'], $application_id );
		update_field( 'ccjp_years_of_experience', $data['years_of_experience'], $application_id );
		update_field( 'ccjp_current_company', $data['current_company'], $application_id );
		update_field( 'ccjp_notice_period', $data['notice_period'], $application_id );
		update_field( 'ccjp_linkedin', $data['linkedin'], $application_id );
		update_field( 'ccjp_portfolio', $data['portfolio'], $application_id );
		update_field( 'ccjp_cover_letter', $data['cover_letter'], $application_id );
		update_field( 'ccjp_job', $job_id, $application_id );
		update_field( 'ccjp_privacy_agreement', true, $application_id );
		update_field( 'ccjp_resume', $attachment_id, $application_id );

		$reference = 'APP-' . gmdate( 'Y' ) . '-' . str_pad( $application_id, 5, '0', STR_PAD_LEFT );
		update_post_meta( $application_id, '_ccjp_reference', $reference );

		return $application_id;
	}

	private function send_notifications( $application_id, $job_id, array $data ) {
		if ( ! $application_id ) {
			return;
		}

		$job_title = get_the_title( $job_id );
		$reference = get_post_meta( $application_id, '_ccjp_reference', true );
		$name      = trim( $data['first_name'] . ' ' . $data['last_name'] );

		$recruiter_email = apply_filters( 'ccjp_notification_email', get_option( 'admin_email' ) );
		if ( $recruiter_email ) {
			wp_mail(
				$recruiter_email,
				sprintf(
					/* translators: 1: job title, 2: candidate name. */
					__( 'New Application: %1$s — %2$s', 'careers-portal' ),
					$job_title,
					$name
				),
				sprintf(
					/* translators: 1: candidate name, 2: job title, 3: email, 4: phone, 5: reference, 6: edit link. */
					__( "%1\$s has applied for %2\$s.\n\nEmail: %3\$s\nPhone: %4\$s\nApplication ID: %5\$s\n\nReview it here: %6\$s", 'careers-portal' ),
					$name,
					$job_title,
					$data['email'],
					$data['phone'],
					$reference,
					admin_url( 'post.php?post=' . $application_id . '&action=edit' )
				)
			);
		}

		if ( apply_filters( 'ccjp_send_candidate_confirmation', true ) ) {
			wp_mail(
				$data['email'],
				sprintf(
					/* translators: %s is the job title. */
					__( 'Application Received: %s', 'careers-portal' ),
					$job_title
				),
				sprintf(
					/* translators: 1: candidate first name, 2: job title, 3: application reference. */
					__( "Hi %1\$s,\n\nThank you for applying for %2\$s. Our recruitment team will review your application and get back to you soon.\n\nApplication ID: %3\$s", 'careers-portal' ),
					$data['first_name'],
					$job_title,
					$reference
				)
			);
		}
	}

	private function fail( $job_id, $code ) {
		$job_url = $job_id ? get_permalink( $job_id ) : home_url( '/' );
		wp_safe_redirect(
			add_query_arg( 'ccjp_apply_error', $code, $job_url . '#ccjp-apply' )
		);
		exit;
	}
}
