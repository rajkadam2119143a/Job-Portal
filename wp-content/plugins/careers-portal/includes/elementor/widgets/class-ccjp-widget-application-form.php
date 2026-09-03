<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Career Application Form — embeds the "Career Application" Gravity Forms
 * form (see CCJP_GravityForms), pre-populated with the current job's ID
 * and title via hidden fields. Gravity Forms owns validation, the resume
 * upload and the field-level styling/layout for this widget; CCJP_GravityForms
 * is what turns a submitted entry into a career_application post. This
 * widget only supplies a heading and the job context — same widget
 * slug/class as before so nothing referencing it elsewhere breaks.
 */
class CCJP_Widget_Application_Form extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-application-form';
	}

	public function get_title() {
		return __( 'Application Form', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_keywords() {
		return array( 'careers', 'apply', 'application', 'form', 'gravity forms' );
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
				'default' => __( 'Apply for this Role', 'careers-portal' ),
			)
		);

		if ( ! class_exists( 'GFAPI' ) ) {
			$this->add_control(
				'gf_missing_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => '<div class="elementor-panel-alert elementor-panel-alert-warning">' .
						esc_html__( 'Gravity Forms is not active — this widget needs it to accept applications.', 'careers-portal' ) .
						'</div>',
				)
			);
		} elseif ( ! CCJP_GravityForms::get_form_id() ) {
			$this->add_control(
				'gf_form_missing_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => '<div class="elementor-panel-alert elementor-panel-alert-warning">' .
						esc_html__( 'The "Career Application" Gravity Forms form has not been created yet.', 'careers-portal' ) .
						'</div>',
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_heading',
			array(
				'label' => __( 'Heading', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Heading Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_PRIMARY ),
				'selectors' => array( '{{WRAPPER}} .ccjp-application-form__heading' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ),
				'selector' => '{{WRAPPER}} .ccjp-application-form__heading',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$job_id   = $this->get_current_job_id();

		if ( ! $job_id ) {
			if ( $this->is_editing() ) {
				echo '<div class="ccjp-application-form ccjp-application-form--placeholder" id="ccjp-apply"><p>' .
					esc_html__( 'Application Form preview — place this widget on a Single Job template.', 'careers-portal' ) .
					'</p></div>';
			}
			return;
		}

		if ( CCJP_Job_Data::is_closed( $job_id ) ) {
			?>
			<div class="ccjp-application-form ccjp-application-form--closed" id="ccjp-apply">
				<p><?php esc_html_e( 'This position is no longer accepting applications.', 'careers-portal' ); ?></p>
			</div>
			<?php
			return;
		}
		?>
		<div class="ccjp-application-form" id="ccjp-apply">
			<?php if ( '' !== $settings['heading'] ) : ?>
				<h2 class="ccjp-application-form__heading"><?php echo esc_html( $settings['heading'] ); ?></h2>
			<?php endif; ?>

			<?php CCJP_GravityForms::render( $job_id ); ?>
		</div>
		<?php
	}
}
