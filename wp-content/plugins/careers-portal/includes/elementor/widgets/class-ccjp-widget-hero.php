<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Careers Hero — heading/subheading + an optional keyword+location search
 * bar. Background/overlay/typography/colors are exposed through Elementor's
 * own native control groups (Group_Control_Background, Group_Control_Typography
 * with global font/color inheritance) rather than any hardcoded CSS, so the
 * "visual design" stays 100% Elementor-editable per the architecture rule.
 */
class CCJP_Widget_Hero extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-hero';
	}

	public function get_title() {
		return __( 'Careers Hero', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_keywords() {
		return array( 'careers', 'hero', 'job', 'search' );
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
				'dynamic' => array( 'active' => true ),
				'default' => __( 'Find Your Next Opportunity', 'careers-portal' ),
			)
		);

		$this->add_control(
			'subheading',
			array(
				'label'   => __( 'Subheading', 'careers-portal' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
				'default' => __( 'Explore open positions and find a role where you can make an impact.', 'careers-portal' ),
			)
		);

		$this->add_control(
			'search_enabled',
			array(
				'label'        => __( 'Show Search', 'careers-portal' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'careers-portal' ),
				'label_off'    => __( 'Hide', 'careers-portal' ),
			)
		);

		$this->add_control(
			'search_placeholder',
			array(
				'label'     => __( 'Search Placeholder', 'careers-portal' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Search jobs by keyword', 'careers-portal' ),
				'condition' => array( 'search_enabled' => 'yes' ),
			)
		);

		$this->add_control(
			'location_placeholder',
			array(
				'label'     => __( 'Location Placeholder', 'careers-portal' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Location', 'careers-portal' ),
				'condition' => array( 'search_enabled' => 'yes' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'careers-portal' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Search Jobs', 'careers-portal' ),
				'condition' => array( 'search_enabled' => 'yes' ),
			)
		);

		$this->add_control(
			'results_page',
			array(
				'label'       => __( 'Results Page', 'careers-portal' ),
				'type'        => Controls_Manager::URL,
				'description' => __( 'Where the search submits to. Leave empty to use the Jobs archive.', 'careers-portal' ),
				'condition'   => array( 'search_enabled' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_background',
			array(
				'label' => __( 'Background', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'selector' => '{{WRAPPER}} .ccjp-hero',
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ccjp-hero__overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

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
				'selectors' => array(
					'{{WRAPPER}} .ccjp-hero__heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ),
				'selector' => '{{WRAPPER}} .ccjp-hero__heading',
			)
		);

		$this->add_control(
			'subheading_color',
			array(
				'label'     => __( 'Subheading Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_TEXT ),
				'selectors' => array(
					'{{WRAPPER}} .ccjp-hero__subheading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subheading_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_TEXT ),
				'selector' => '{{WRAPPER}} .ccjp-hero__subheading',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();

		$results_url = ! empty( $settings['results_page']['url'] )
			? $settings['results_page']['url']
			: get_post_type_archive_link( 'career_job' );

		?>
		<div class="ccjp-hero">
			<div class="ccjp-hero__overlay"></div>
			<div class="ccjp-hero__content">
				<?php if ( '' !== $settings['heading'] ) : ?>
					<h1 class="ccjp-hero__heading"><?php echo wp_kses_post( $settings['heading'] ); ?></h1>
				<?php endif; ?>

				<?php if ( '' !== $settings['subheading'] ) : ?>
					<p class="ccjp-hero__subheading"><?php echo wp_kses_post( $settings['subheading'] ); ?></p>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['search_enabled'] ) : ?>
					<form class="ccjp-hero__search ccjp-job-search-form" action="<?php echo esc_url( $results_url ); ?>" method="get">
						<input
							type="text"
							name="keyword"
							class="ccjp-hero__search-input"
							placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>"
						/>
						<input
							type="text"
							name="location"
							class="ccjp-hero__search-location"
							placeholder="<?php echo esc_attr( $settings['location_placeholder'] ); ?>"
						/>
						<button type="submit" class="ccjp-hero__search-button">
							<?php echo esc_html( $settings['button_text'] ); ?>
						</button>
					</form>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
