<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Careers Job Meta — a configurable list of the current job's meta fields
 * (Location, Department, Job Type, Work Mode, Experience, Job ID, Posted,
 * Deadline…), meant for the Single Job template's summary card/header.
 * Reads through CCJP_Job_Data so the field list stays in one place.
 */
class CCJP_Widget_Job_Meta extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-job-meta';
	}

	public function get_title() {
		return __( 'Job Meta', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-post-info';
	}

	public function get_keywords() {
		return array( 'careers', 'job', 'meta' );
	}

	private function field_labels() {
		return array(
			'job_id'     => __( 'Job ID', 'careers-portal' ),
			'department' => __( 'Department', 'careers-portal' ),
			'category'   => __( 'Category', 'careers-portal' ),
			'location'   => __( 'Location', 'careers-portal' ),
			'country'    => __( 'Country', 'careers-portal' ),
			'job_type'   => __( 'Job Type', 'careers-portal' ),
			'work_mode'  => __( 'Work Mode', 'careers-portal' ),
			'experience' => __( 'Experience', 'careers-portal' ),
			'salary'     => __( 'Salary', 'careers-portal' ),
			'posted'     => __( 'Posted Date', 'careers-portal' ),
			'deadline'   => __( 'Deadline', 'careers-portal' ),
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'fields',
			array(
				'label'    => __( 'Fields to Show', 'careers-portal' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $this->field_labels(),
				'default'  => array( 'location', 'department', 'job_type', 'work_mode', 'experience', 'posted', 'deadline' ),
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'   => __( 'Show Labels', 'careers-portal' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'list',
				'options' => array(
					'list'   => __( 'Vertical List', 'careers-portal' ),
					'inline' => __( 'Inline', 'careers-portal' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Typography', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Label Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_TEXT ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-meta__label' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => __( 'Value Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_PRIMARY ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-meta__value' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_TEXT ),
				'selector' => '{{WRAPPER}} .ccjp-job-meta__value',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$job_id   = $this->get_current_job_id();

		$fields = ! empty( $settings['fields'] ) ? $settings['fields'] : array();
		$labels = $this->field_labels();

		if ( ! $job_id ) {
			?>
			<ul class="ccjp-job-meta ccjp-job-meta--<?php echo esc_attr( $settings['layout'] ); ?> ccjp-job-meta--placeholder">
				<?php foreach ( $fields as $key ) : ?>
					<li class="ccjp-job-meta__item">
						<?php if ( 'yes' === $settings['show_labels'] ) : ?>
							<span class="ccjp-job-meta__label"><?php echo esc_html( $labels[ $key ] ?? $key ); ?>:</span>
						<?php endif; ?>
						<span class="ccjp-job-meta__value"><?php esc_html_e( '—', 'careers-portal' ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
			return;
		}

		$meta = CCJP_Job_Data::get_meta_items( $job_id );
		?>
		<ul class="ccjp-job-meta ccjp-job-meta--<?php echo esc_attr( $settings['layout'] ); ?>">
			<?php foreach ( $fields as $key ) : ?>
				<?php if ( empty( $meta[ $key ]['value'] ) ) { continue; } ?>
				<li class="ccjp-job-meta__item ccjp-job-meta__item--<?php echo esc_attr( $key ); ?>">
					<?php if ( 'yes' === $settings['show_labels'] ) : ?>
						<span class="ccjp-job-meta__label"><?php echo esc_html( $meta[ $key ]['label'] ); ?>:</span>
					<?php endif; ?>
					<span class="ccjp-job-meta__value"><?php echo esc_html( $meta[ $key ]['value'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
