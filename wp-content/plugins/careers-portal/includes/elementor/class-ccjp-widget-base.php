<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Shared behaviour for every Careers Portal Elementor widget: the common
 * category, the functional (never visual) frontend asset enqueue, and the
 * "what job are we in the context of" resolution used by every widget that
 * needs a current-job (Job Meta, Apply Button, Related Jobs, Job Listing's
 * current_* query sources).
 */
abstract class CCJP_Widget_Base extends Widget_Base {

	public function get_categories() {
		return array( 'careers-portal' );
	}

	public function get_current_job_id() {
		return CCJP_Job_Query::resolve_reference_post_id();
	}

	public function is_editing() {
		return CCJP_Job_Query::is_elementor_editing();
	}

	/**
	 * Enqueues the plugin's namespaced functional frontend assets. Cheap to
	 * call from every widget's render() — wp_enqueue_* is idempotent, so
	 * a page using five Careers widgets still only loads the files once.
	 */
	protected function enqueue_frontend_assets() {
		wp_enqueue_style( 'ccjp-frontend', CCJP_PLUGIN_URL . 'assets/frontend/css/ccjp-frontend.css', array(), CCJP_VERSION );
		wp_enqueue_script( 'ccjp-frontend', CCJP_PLUGIN_URL . 'assets/frontend/js/ccjp-frontend.js', array(), CCJP_VERSION, true );
		wp_localize_script(
			'ccjp-frontend',
			'CCJP_DATA',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ccjp_public' ),
			)
		);
	}

	/**
	 * Shared "Job Cards" style section — used by every widget that renders
	 * one or more .ccjp-job-card elements (Job Card, Job Listing, Featured
	 * Jobs, Related Jobs) so the card's Background/Border/Radius/Shadow/
	 * Padding/typography controls exist in exactly one place instead of
	 * being duplicated per widget.
	 */
	protected function register_job_card_style_controls() {
		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => __( 'Job Cards', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array( 'name' => 'card_background', 'types' => array( 'classic' ), 'selector' => '{{WRAPPER}} .ccjp-job-card' )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array( 'name' => 'card_border', 'selector' => '{{WRAPPER}} .ccjp-job-card' )
		);

		$this->add_responsive_control(
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

		$this->add_responsive_control(
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

		$this->add_control(
			'card_title_color',
			array(
				'label'     => __( 'Title Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_SECONDARY ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-card__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_title_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ),
				'selector' => '{{WRAPPER}} .ccjp-job-card__title',
			)
		);

		$this->add_control(
			'card_meta_color',
			array(
				'label'     => __( 'Meta Text Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_TEXT ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-card__meta-item, {{WRAPPER}} .ccjp-job-card__excerpt' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}
}
