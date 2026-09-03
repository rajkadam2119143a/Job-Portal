<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Shared implementation behind the Teams, Departments and Locations
 * listing widgets — each is a thin subclass fixing the taxonomy + labels,
 * so they show up as distinct, discoverable entries in the Elementor panel
 * (per the widget list) without duplicating the query/render logic.
 */
abstract class CCJP_Widget_Taxonomy_Listing_Base extends CCJP_Widget_Base {

	abstract protected function taxonomy();

	abstract protected function default_heading();

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
				'default' => $this->default_heading(),
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'   => __( 'Card Link Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'View Jobs', 'careers-portal' ),
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'   => __( 'Hide Terms With No Open Jobs', 'careers-portal' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'careers-portal' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors'      => array(
					'{{WRAPPER}} .ccjp-taxonomy-listing__grid' => '--ccjp-columns: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => __( 'Gap Between Cards', 'careers-portal' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default'    => array( 'size' => 24, 'unit' => 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ccjp-taxonomy-listing__grid' => '--ccjp-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$taxonomy = $this->taxonomy();

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);
		if ( is_wp_error( $terms ) ) {
			$terms = array();
		}
		?>
		<div class="ccjp-taxonomy-listing">
			<?php if ( '' !== $settings['heading'] ) : ?>
				<h2 class="ccjp-taxonomy-listing__heading"><?php echo esc_html( $settings['heading'] ); ?></h2>
			<?php endif; ?>

			<div class="ccjp-taxonomy-listing__grid ccjp-job-listing__grid">
				<?php foreach ( $terms as $term ) : ?>
					<?php
					if ( 'yes' === $settings['hide_empty'] && 0 === CCJP_Taxonomy_Card_Renderer::open_job_count( $term ) ) {
						continue;
					}
					echo CCJP_Taxonomy_Card_Renderer::render( $term, array( 'link_text' => $settings['link_text'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
