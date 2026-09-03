<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Career Featured Jobs — thin wrapper over CCJP_Job_Query with the source
 * forced to "featured" (ccjp_featured = true), sharing the same card
 * renderer as Job Listing so featured cards look identical to regular ones.
 */
class CCJP_Widget_Featured_Jobs extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-featured-jobs';
	}

	public function get_title() {
		return __( 'Featured Jobs', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-star';
	}

	public function get_keywords() {
		return array( 'careers', 'featured', 'jobs' );
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
				'default' => __( 'Featured Opportunities', 'careers-portal' ),
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => __( 'Number of Jobs', 'careers-portal' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 24,
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
					'{{WRAPPER}} .ccjp-job-listing__grid' => '--ccjp-columns: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'newest',
				'options' => array(
					'newest' => __( 'Newest', 'careers-portal' ),
					'oldest' => __( 'Oldest', 'careers-portal' ),
					'title'  => __( 'Title', 'careers-portal' ),
					'random' => __( 'Random', 'careers-portal' ),
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => __( 'Gap Between Cards', 'careers-portal' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 100 ),
					'em' => array( 'min' => 0, 'max' => 10 ),
				),
				'default'    => array( 'size' => 24, 'unit' => 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ccjp-job-listing__grid' => '--ccjp-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();

		$query = CCJP_Job_Query::query(
			array(
				'source'         => 'featured',
				'posts_per_page' => (int) $settings['number'],
				'order'          => $settings['order'],
			)
		);
		?>
		<div class="ccjp-featured-jobs">
			<?php if ( '' !== $settings['heading'] ) : ?>
				<h2 class="ccjp-featured-jobs__heading"><?php echo esc_html( $settings['heading'] ); ?></h2>
			<?php endif; ?>

			<div class="ccjp-job-listing__grid">
				<?php
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						echo CCJP_Job_Card_Renderer::render( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					wp_reset_postdata();
				} elseif ( $this->is_editing() ) {
					echo CCJP_Job_Card_Renderer::render_placeholder(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
		<?php
	}
}
