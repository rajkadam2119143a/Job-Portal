<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Career Application Success — reads ?application_id={reference} from the
 * URL (set by CCJP_Applications' post-submit redirect) and confirms the
 * candidate's name, job title and reference number.
 */
class CCJP_Widget_Application_Success extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-application-success';
	}

	public function get_title() {
		return __( 'Application Success', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-check-circle-o';
	}

	public function get_keywords() {
		return array( 'careers', 'application', 'success', 'confirmation' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Application Submitted Successfully', 'careers-portal' ),
			)
		);

		$this->add_control(
			'message',
			array(
				'label'   => __( 'Message', 'careers-portal' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Thank you for applying. Our recruitment team will review your application.', 'careers-portal' ),
			)
		);

		$this->add_control(
			'careers_button_text',
			array(
				'label'   => __( '"Back to Careers" Button Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Back to Careers', 'careers-portal' ),
			)
		);

		$this->add_control(
			'jobs_button_text',
			array(
				'label'   => __( '"View More Jobs" Button Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'View More Jobs', 'careers-portal' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings  = $this->get_settings_for_display();
		$reference = isset( $_GET['application_id'] ) ? sanitize_text_field( wp_unslash( $_GET['application_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$application_id = $reference ? CCJP_Applications::get_by_reference( $reference ) : 0;
		$job_title      = '';
		$candidate_name = '';

		if ( $application_id ) {
			$job_id         = (int) CCJP_Job_Data::get( $application_id, 'ccjp_job' );
			$job_title      = $job_id ? get_the_title( $job_id ) : '';
			$candidate_name = trim(
				CCJP_Job_Data::get( $application_id, 'ccjp_first_name' ) . ' ' . CCJP_Job_Data::get( $application_id, 'ccjp_last_name' )
			);
		}

		$careers_url = CCJP_Pages::get_page_url( 'careers' );
		$jobs_url    = get_post_type_archive_link( 'career_job' );
		?>
		<div class="ccjp-application-success">
			<h1 class="ccjp-application-success__heading"><?php echo esc_html( $settings['heading'] ); ?></h1>

			<?php if ( '' !== $settings['message'] ) : ?>
				<p class="ccjp-application-success__message"><?php echo esc_html( $settings['message'] ); ?></p>
			<?php endif; ?>

			<?php if ( $application_id ) : ?>
				<dl class="ccjp-application-success__details">
					<?php if ( $job_title ) : ?>
						<div class="ccjp-application-success__detail">
							<dt><?php esc_html_e( 'Position', 'careers-portal' ); ?></dt>
							<dd><?php echo esc_html( $job_title ); ?></dd>
						</div>
					<?php endif; ?>
					<div class="ccjp-application-success__detail">
						<dt><?php esc_html_e( 'Application ID', 'careers-portal' ); ?></dt>
						<dd><?php echo esc_html( $reference ); ?></dd>
					</div>
					<div class="ccjp-application-success__detail">
						<dt><?php esc_html_e( 'Submitted', 'careers-portal' ); ?></dt>
						<dd><?php echo esc_html( get_the_date( '', $application_id ) ); ?></dd>
					</div>
				</dl>
			<?php endif; ?>

			<div class="ccjp-application-success__actions">
				<?php if ( $careers_url ) : ?>
					<a class="ccjp-application-success__button" href="<?php echo esc_url( $careers_url ); ?>">
						<?php echo esc_html( $settings['careers_button_text'] ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $jobs_url ) : ?>
					<a class="ccjp-application-success__button" href="<?php echo esc_url( $jobs_url ); ?>">
						<?php echo esc_html( $settings['jobs_button_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
