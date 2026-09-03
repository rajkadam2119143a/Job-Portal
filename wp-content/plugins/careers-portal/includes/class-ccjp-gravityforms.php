<?php
defined( 'ABSPATH' ) || exit;

/**
 * Bridges Gravity Forms (the actual application intake form, field set
 * matching the company's real ATS) into the plugin's existing
 * career_application pipeline. Gravity Forms owns the raw submission —
 * validation, the resume upload, and the full field set — the plugin only
 * creates/links a career_application post so the existing status pipeline,
 * wp-admin screens and taxonomy filters keep working the same way
 * regardless of intake mechanism.
 *
 * Safe if Gravity Forms is inactive: form creation and the submission hook
 * both no-op via class_exists()/function_exists() checks; nothing here
 * assumes GF is present.
 */
class CCJP_GravityForms {

	const FORM_OPTION = 'ccjp_gf_form_id';

	/** Field IDs, fixed so the submission handler can address them directly. */
	const FIELD_EMAIL              = 1;
	const FIELD_NAME               = 2;
	const FIELD_MOBILE             = 3;
	const FIELD_RESUME             = 4;
	const FIELD_COVER_NOTE         = 5;
	const FIELD_CURRENT_COMPANY    = 6;
	const FIELD_CURRENT_DESIGNATION = 7;
	const FIELD_CURRENT_CTC        = 8;
	const FIELD_NOTICE_PERIOD      = 9;
	const FIELD_SKILLS             = 10;
	const FIELD_SALARY_EXPECTATION = 11;
	const FIELD_WORK_EXPERIENCE    = 12;
	const FIELD_CURRENT_LOCATION   = 13;
	const FIELD_PREFERRED_LOCATION = 14;
	const FIELD_FIRST_NAME         = 15;
	const FIELD_LAST_NAME          = 16;
	const FIELD_MOBILE_2           = 17;
	const FIELD_PERSONAL_EMAIL     = 18;
	const FIELD_DOB                = 19;
	const FIELD_RELATIVE_EMPLOYED  = 20;
	const FIELD_WORKED_BEFORE      = 21;
	const FIELD_ATTENDED_SELECTION = 22;
	const FIELD_LEGAL_PROCEEDINGS  = 23;
	const FIELD_ADDRESS            = 24;
	const FIELD_EDUCATION          = 25;
	const FIELD_DISCLAIMER         = 26;
	const FIELD_JOB_ID             = 27;
	const FIELD_JOB_TITLE          = 28;

	public function __construct() {
		// Generic (not form-ID-specific) since the form ID is only known
		// once created — filtered inside by comparing against our stored ID.
		add_action( 'gform_after_submission', array( $this, 'handle_submission' ), 10, 2 );
	}

	public static function get_form_id() {
		return (int) get_option( self::FORM_OPTION );
	}

	public static function maybe_create_form() {
		if ( ! class_exists( 'GFAPI' ) ) {
			return;
		}

		$form_id = self::get_form_id();
		if ( $form_id && GFAPI::get_form( $form_id ) ) {
			return;
		}

		$result = GFAPI::add_form( self::form_meta() );
		if ( ! is_wp_error( $result ) ) {
			update_option( self::FORM_OPTION, $result );
		}
	}

	/**
	 * Renders the application form for a given job — the current
	 * career_job's ID and title are passed as pre-populated hidden field
	 * values (not a query string), so it works regardless of URL structure.
	 */
	public static function render( $job_id ) {
		if ( ! function_exists( 'gravity_form' ) ) {
			echo '<p class="ccjp-application-form__error">' .
				esc_html__( 'Gravity Forms is required to accept applications.', 'careers-portal' ) .
				'</p>';
			return;
		}

		$form_id = self::get_form_id();
		if ( ! $form_id ) {
			return;
		}

		gravity_form(
			$form_id,
			false,
			false,
			false,
			array(
				'ccjp_job_id'    => (string) $job_id,
				'ccjp_job_title' => get_the_title( $job_id ),
			),
			false,
			0,
			true
		);
	}

	public function handle_submission( $entry, $form ) {
		if ( (int) $form['id'] !== self::get_form_id() ) {
			return;
		}

		$job_id = absint( rgar( $entry, (string) self::FIELD_JOB_ID ) );
		if ( ! $job_id || 'career_job' !== get_post_type( $job_id ) ) {
			return;
		}

		$first_name = trim( rgar( $entry, (string) self::FIELD_FIRST_NAME ) );
		$last_name  = trim( rgar( $entry, (string) self::FIELD_LAST_NAME ) );
		if ( '' === $first_name && '' === $last_name ) {
			$name_parts = explode( ' ', trim( rgar( $entry, (string) self::FIELD_NAME ) ), 2 );
			$first_name = $name_parts[0] ?? '';
			$last_name  = $name_parts[1] ?? '';
		}

		$email = rgar( $entry, (string) self::FIELD_EMAIL );
		$job_title = get_the_title( $job_id );

		$application_id = wp_insert_post(
			array(
				'post_type'   => 'career_application',
				/* translators: 1: candidate name, 2: job title. */
				'post_title'  => sprintf( __( '%1$s — %2$s', 'careers-portal' ), trim( $first_name . ' ' . $last_name ), $job_title ),
				'post_status' => CCJP_Post_Statuses::default_application_status(),
			)
		);

		if ( is_wp_error( $application_id ) || ! $application_id ) {
			return;
		}

		update_field( 'ccjp_first_name', $first_name, $application_id );
		update_field( 'ccjp_last_name', $last_name, $application_id );
		update_field( 'ccjp_email', $email, $application_id );
		update_field( 'ccjp_phone', rgar( $entry, (string) self::FIELD_MOBILE ), $application_id );
		update_field( 'ccjp_current_location', rgar( $entry, (string) self::FIELD_CURRENT_LOCATION ), $application_id );
		update_field( 'ccjp_years_of_experience', rgar( $entry, (string) self::FIELD_WORK_EXPERIENCE ), $application_id );
		update_field( 'ccjp_current_company', rgar( $entry, (string) self::FIELD_CURRENT_COMPANY ), $application_id );
		update_field( 'ccjp_notice_period', rgar( $entry, (string) self::FIELD_NOTICE_PERIOD ), $application_id );
		update_field( 'ccjp_cover_letter', rgar( $entry, (string) self::FIELD_COVER_NOTE ), $application_id );
		update_field( 'ccjp_job', $job_id, $application_id );
		update_field( 'ccjp_privacy_agreement', ! empty( rgar( $entry, self::FIELD_DISCLAIMER . '.1' ) ), $application_id );

		// The full field set (CTC, education history, the Yes/No screening
		// questions, etc.) lives in the Gravity Forms entry itself — no
		// need to duplicate GF's own storage into ACF. The entry ID + the
		// resume URL are kept as plain postmeta for quick access from a
		// later admin "view application" screen.
		update_post_meta( $application_id, '_ccjp_gf_entry_id', $entry['id'] );
		update_post_meta( $application_id, '_ccjp_gf_resume_url', rgar( $entry, (string) self::FIELD_RESUME ) );

		$reference = 'APP-' . gmdate( 'Y' ) . '-' . str_pad( $application_id, 5, '0', STR_PAD_LEFT );
		update_post_meta( $application_id, '_ccjp_reference', $reference );

		$this->send_notifications( $application_id, $job_id, $first_name, $email );
	}

	private function send_notifications( $application_id, $job_id, $first_name, $email ) {
		$job_title = get_the_title( $job_id );
		$reference = get_post_meta( $application_id, '_ccjp_reference', true );

		$recruiter_email = apply_filters( 'ccjp_notification_email', get_option( 'admin_email' ) );
		if ( $recruiter_email ) {
			wp_mail(
				$recruiter_email,
				sprintf(
					/* translators: 1: job title, 2: application reference. */
					__( 'New Application: %1$s (%2$s)', 'careers-portal' ),
					$job_title,
					$reference
				),
				sprintf(
					/* translators: 1: job title, 2: reference, 3: edit link. */
					__( "A new application was submitted for %1\$s.\n\nApplication ID: %2\$s\n\nReview it here: %3\$s", 'careers-portal' ),
					$job_title,
					$reference,
					admin_url( 'post.php?post=' . $application_id . '&action=edit' )
				)
			);
		}

		if ( $email && apply_filters( 'ccjp_send_candidate_confirmation', true ) ) {
			wp_mail(
				$email,
				sprintf(
					/* translators: %s is the job title. */
					__( 'Application Received: %s', 'careers-portal' ),
					$job_title
				),
				sprintf(
					/* translators: 1: candidate first name, 2: job title, 3: reference. */
					__( "Hi %1\$s,\n\nThank you for applying for %2\$s. Our recruitment team will review your application and get back to you soon.\n\nApplication ID: %3\$s", 'careers-portal' ),
					$first_name,
					$job_title,
					$reference
				)
			);
		}
	}

	private static function form_meta() {
		$yes_no = array(
			array( 'text' => __( 'Yes', 'careers-portal' ), 'value' => 'Yes' ),
			array( 'text' => __( 'No', 'careers-portal' ), 'value' => 'No' ),
		);

		return array(
			'title'       => 'Career Application',
			'description' => '',
			'labelPlacement' => 'top_label',
			'button'      => array( 'type' => 'text', 'text' => __( 'Submit', 'careers-portal' ) ),
			'fields'      => array(
				array(
					'id'         => self::FIELD_EMAIL,
					'type'       => 'email',
					'label'      => __( 'E-mail address', 'careers-portal' ),
					'isRequired' => true,
				),
				array(
					'id'         => self::FIELD_NAME,
					'type'       => 'text',
					'label'      => __( 'Name', 'careers-portal' ),
					'isRequired' => true,
				),
				array(
					'id'         => self::FIELD_MOBILE,
					'type'       => 'phone',
					'label'      => __( 'Mobile', 'careers-portal' ),
					'phoneFormat' => 'international',
					'isRequired' => true,
				),
				array(
					'id'                => self::FIELD_RESUME,
					'type'              => 'fileupload',
					'label'             => __( 'CV / Resume', 'careers-portal' ),
					'isRequired'        => true,
					'allowedExtensions' => 'pdf,doc,docx',
					'maxFileSize'       => CCJP_Applications::max_resume_size_mb(),
				),
				array(
					'id'    => self::FIELD_COVER_NOTE,
					'type'  => 'textarea',
					'label' => __( 'Enter a cover note here', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_CURRENT_COMPANY,
					'type'  => 'text',
					'label' => __( 'Current Company', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_CURRENT_DESIGNATION,
					'type'  => 'text',
					'label' => __( 'Current Designation', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_CURRENT_CTC,
					'type'  => 'text',
					'label' => __( 'Current CTC', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_NOTICE_PERIOD,
					'type'  => 'number',
					'label' => __( 'Notice Period (in months)', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_SKILLS,
					'type'  => 'text',
					'label' => __( 'Select Your Skills', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_SALARY_EXPECTATION,
					'type'  => 'text',
					'label' => __( 'Salary Expectation', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_WORK_EXPERIENCE,
					'type'  => 'text',
					'label' => __( 'Work Experience', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_CURRENT_LOCATION,
					'type'  => 'text',
					'label' => __( 'Current Location', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_PREFERRED_LOCATION,
					'type'  => 'text',
					'label' => __( 'Preferred Location', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_FIRST_NAME,
					'type'  => 'text',
					'label' => __( 'First Name', 'careers-portal' ),
				),
				array(
					'id'    => self::FIELD_LAST_NAME,
					'type'  => 'text',
					'label' => __( 'Last Name', 'careers-portal' ),
				),
				array(
					'id'          => self::FIELD_MOBILE_2,
					'type'        => 'phone',
					'label'       => __( 'Mobile No.', 'careers-portal' ),
					'phoneFormat' => 'international',
					'isRequired'  => true,
				),
				array(
					'id'    => self::FIELD_PERSONAL_EMAIL,
					'type'  => 'email',
					'label' => __( 'Personal Email Address', 'careers-portal' ),
				),
				array(
					'id'         => self::FIELD_DOB,
					'type'       => 'date',
					'label'      => __( 'Date of Birth', 'careers-portal' ),
					'dateType'   => 'datepicker',
				),
				array(
					'id'      => self::FIELD_RELATIVE_EMPLOYED,
					'type'    => 'select',
					'label'   => __( 'Do you have any relative currently employed / engaged directly or indirectly with the Company, or its Subsidiaries / Group Companies / Business Units / Affiliated entities?', 'careers-portal' ),
					'choices' => $yes_no,
				),
				array(
					'id'      => self::FIELD_WORKED_BEFORE,
					'type'    => 'select',
					'label'   => __( 'Have you ever worked in any Times Group Company earlier?', 'careers-portal' ),
					'choices' => $yes_no,
				),
				array(
					'id'      => self::FIELD_ATTENDED_SELECTION,
					'type'    => 'select',
					'label'   => __( 'Have you attended any selection process at BCCL / or any other Times Group Company before?', 'careers-portal' ),
					'choices' => $yes_no,
				),
				array(
					'id'      => self::FIELD_LEGAL_PROCEEDINGS,
					'type'    => 'select',
					'label'   => __( 'Are there any pending legal proceedings / suits against your name?', 'careers-portal' ),
					'choices' => $yes_no,
				),
				array(
					'id'    => self::FIELD_ADDRESS,
					'type'  => 'textarea',
					'label' => __( 'Your Complete Address with Pincode', 'careers-portal' ),
				),
				array(
					'id'            => self::FIELD_EDUCATION,
					'type'          => 'list',
					'label'         => __( 'Education Information', 'careers-portal' ),
					'enableColumns' => true,
					'choices'       => array(
						array( 'text' => __( 'Level of Education', 'careers-portal' ) ),
						array( 'text' => __( 'Education / Training', 'careers-portal' ) ),
						array( 'text' => __( 'Branch of Study', 'careers-portal' ) ),
						array( 'text' => __( 'Institute / Location', 'careers-portal' ) ),
						array( 'text' => __( 'Others (Please Specify)', 'careers-portal' ) ),
						array( 'text' => __( 'Highest Education', 'careers-portal' ) ),
					),
				),
				array(
					'id'         => self::FIELD_DISCLAIMER,
					'type'       => 'checkbox',
					'label'      => __( 'Disclaimer', 'careers-portal' ),
					'isRequired' => true,
					'inputs'     => array(
						array(
							'id'    => self::FIELD_DISCLAIMER . '.1',
							'label' => __( 'I hereby confirm that all information provided in my application is true, complete and accurate to the best of my knowledge. I understand that any misrepresentation or omission may impact my candidature or employment.', 'careers-portal' ),
						),
					),
					'choices'    => array(
						array(
							'text'  => __( 'I hereby confirm that all information provided in my application is true, complete and accurate to the best of my knowledge. I understand that any misrepresentation or omission may impact my candidature or employment.', 'careers-portal' ),
							'value' => '1',
						),
					),
				),
				array(
					'id'               => self::FIELD_JOB_ID,
					'type'             => 'hidden',
					'label'            => 'Job ID',
					'inputName'        => 'ccjp_job_id',
					'allowsPrepopulate' => true,
				),
				array(
					'id'               => self::FIELD_JOB_TITLE,
					'type'             => 'hidden',
					'label'            => 'Job Title',
					'inputName'        => 'ccjp_job_title',
					'allowsPrepopulate' => true,
				),
			),
		);
	}
}
