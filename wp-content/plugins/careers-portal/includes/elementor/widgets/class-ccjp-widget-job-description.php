<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Career Job Description — the ACF-sourced sections beyond the native
 * post_content (which the theme-post-content widget already covers as
 * "About the Role"): Responsibilities, Qualifications, Skills, Good to
 * Have, Benefits, Additional Information. Without this widget the Single
 * Job template only ever showed the title and post_content.
 */
class CCJP_Widget_Job_Description extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-job-description';
	}

	public function get_title() {
		return __( 'Job Description', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-text';
	}

	public function get_keywords() {
		return array( 'careers', 'job', 'description', 'responsibilities', 'qualifications', 'skills', 'benefits' );
	}

	private function sections() {
		return array(
			'responsibilities'      => array(
				'label' => __( 'Responsibilities', 'careers-portal' ),
				'field' => 'ccjp_responsibilities',
				'list'  => true,
			),
			'qualifications'        => array(
				'label' => __( 'Qualifications & Experience', 'careers-portal' ),
				'field' => 'ccjp_qualifications',
				'list'  => true,
			),
			'skills'                => array(
				'label' => __( 'Required Skills', 'careers-portal' ),
				'field' => 'ccjp_skills',
				'list'  => true,
			),
			'good_to_have'          => array(
				'label' => __( 'Good to Have', 'careers-portal' ),
				'field' => 'ccjp_good_to_have',
				'list'  => true,
			),
			'benefits'              => array(
				'label' => __( 'What We Offer', 'careers-portal' ),
				'field' => 'ccjp_benefits',
				'list'  => true,
			),
			'additional_information' => array(
				'label' => __( 'Additional Information', 'careers-portal' ),
				'field' => 'ccjp_additional_information',
				'list'  => false,
			),
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$labels = array();
		foreach ( $this->sections() as $key => $section ) {
			$labels[ $key ] = $section['label'];
		}

		$this->add_control(
			'sections',
			array(
				'label'    => __( 'Sections to Show', 'careers-portal' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $labels,
				'default'  => array_keys( $labels ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_heading',
			array(
				'label' => __( 'Section Headings', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Heading Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_PRIMARY ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-description__heading' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ),
				'selector' => '{{WRAPPER}} .ccjp-job-description__heading',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_body',
			array(
				'label' => __( 'Body Text', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_TEXT ),
				'selectors' => array(
					'{{WRAPPER}} .ccjp-job-description__list li, {{WRAPPER}} .ccjp-job-description__paragraph' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'body_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_TEXT ),
				'selector' => '{{WRAPPER}} .ccjp-job-description__list li, {{WRAPPER}} .ccjp-job-description__paragraph',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$job_id   = $this->get_current_job_id();

		$selected = ! empty( $settings['sections'] ) ? $settings['sections'] : array();
		$sections = $this->sections();

		if ( ! $job_id ) {
			if ( $this->is_editing() ) {
				echo '<div class="ccjp-job-description ccjp-job-description--placeholder">';
				foreach ( $selected as $key ) {
					if ( ! isset( $sections[ $key ] ) ) {
						continue;
					}
					echo '<h2 class="ccjp-job-description__heading">' . esc_html( $sections[ $key ]['label'] ) . '</h2>';
					echo '<p class="ccjp-job-description__paragraph">' . esc_html__( 'Preview content appears here once a job exists.', 'careers-portal' ) . '</p>';
				}
				echo '</div>';
			}
			return;
		}

		echo '<div class="ccjp-job-description">';
		foreach ( $selected as $key ) {
			if ( ! isset( $sections[ $key ] ) ) {
				continue;
			}
			$section = $sections[ $key ];

			if ( $section['list'] ) {
				$items = CCJP_Job_Data::get_list_field( $job_id, $section['field'] );
				if ( empty( $items ) ) {
					continue;
				}
				echo '<h2 class="ccjp-job-description__heading">' . esc_html( $section['label'] ) . '</h2>';
				echo '<ul class="ccjp-job-description__list">';
				foreach ( $items as $item ) {
					echo '<li>' . esc_html( $item ) . '</li>';
				}
				echo '</ul>';
			} else {
				$text = (string) CCJP_Job_Data::get( $job_id, $section['field'] );
				if ( '' === trim( $text ) ) {
					continue;
				}
				echo '<h2 class="ccjp-job-description__heading">' . esc_html( $section['label'] ) . '</h2>';
				echo '<p class="ccjp-job-description__paragraph">' . esc_html( $text ) . '</p>';
			}
		}
		echo '</div>';
	}
}
