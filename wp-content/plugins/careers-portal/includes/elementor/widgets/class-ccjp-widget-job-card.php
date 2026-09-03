<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Careers Job Card — a single, standalone card (Current Job or a specific
 * Job picked in the editor). Job Listing/Featured Jobs render the same
 * markup through CCJP_Job_Card_Renderer directly for performance when
 * looping many cards; this widget exists for placing one card by itself.
 */
class CCJP_Widget_Job_Card extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-job-card';
	}

	public function get_title() {
		return __( 'Job Card', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-post-info';
	}

	public function get_keywords() {
		return array( 'careers', 'job', 'card' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'job_source',
			array(
				'label'   => __( 'Job', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => array(
					'current'  => __( 'Current Job (dynamic)', 'careers-portal' ),
					'specific' => __( 'Specific Job', 'careers-portal' ),
				),
			)
		);

		$this->add_control(
			'specific_job',
			array(
				'label'     => __( 'Choose Job', 'careers-portal' ),
				'type'      => Controls_Manager::SELECT2,
				'options'   => $this->job_options(),
				'condition' => array( 'job_source' => 'specific' ),
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'   => __( 'Show Short Description', 'careers-portal' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_apply_button',
			array(
				'label'   => __( 'Show Apply Button', 'careers-portal' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'view_button_text',
			array(
				'label'   => __( 'View Button Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'View Job', 'careers-portal' ),
			)
		);

		$this->add_control(
			'apply_button_text',
			array(
				'label'     => __( 'Apply Button Text', 'careers-portal' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Apply Now', 'careers-portal' ),
				'condition' => array( 'show_apply_button' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => __( 'Card', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array( 'name' => 'card_background', 'selector' => '{{WRAPPER}} .ccjp-job-card' )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array( 'name' => 'card_border', 'selector' => '{{WRAPPER}} .ccjp-job-card' )
		);

		$this->add_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'careers-portal' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ccjp-job-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array( 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .ccjp-job-card' )
		);

		$this->add_control(
			'card_padding',
			array(
				'label'      => __( 'Padding', 'careers-portal' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ccjp-job-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_typography',
			array(
				'label' => __( 'Typography', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_PRIMARY ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-card__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ),
				'selector' => '{{WRAPPER}} .ccjp-job-card__title',
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => __( 'Meta Text Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_TEXT ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-card__meta-item' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_TEXT ),
				'selector' => '{{WRAPPER}} .ccjp-job-card__meta-item',
			)
		);

		$this->end_controls_section();
	}

	private function job_options() {
		$jobs    = get_posts(
			array(
				'post_type'      => 'career_job',
				'post_status'    => array( 'publish', 'job_closed' ),
				'posts_per_page' => 200,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);
		$options = array();
		foreach ( $jobs as $job ) {
			$options[ $job->ID ] = $job->post_title;
		}
		return $options;
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();

		$job_id = 'specific' === $settings['job_source'] && ! empty( $settings['specific_job'] )
			? (int) $settings['specific_job']
			: $this->get_current_job_id();

		if ( ! $job_id ) {
			echo CCJP_Job_Card_Renderer::render_placeholder( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'view_button_text'  => $settings['view_button_text'],
					'apply_button_text' => $settings['apply_button_text'],
				)
			);
			return;
		}

		echo CCJP_Job_Card_Renderer::render( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$job_id,
			array(
				'show_excerpt'      => 'yes' === $settings['show_excerpt'],
				'show_apply_button' => 'yes' === $settings['show_apply_button'],
				'view_button_text'  => $settings['view_button_text'],
				'apply_button_text' => $settings['apply_button_text'],
			)
		);
	}
}
