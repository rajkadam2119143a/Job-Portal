<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Career Related Jobs — jobs sharing category/department/location with the
 * current job (CCJP_Job_Query's "related" source), meant for the bottom of
 * the Single Job template.
 */
class CCJP_Widget_Related_Jobs extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-related-jobs';
	}

	public function get_title() {
		return __( 'Related Jobs', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return array( 'careers', 'related', 'jobs' );
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
				'default' => __( 'You may also be interested in', 'careers-portal' ),
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => __( 'Number of Jobs', 'careers-portal' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 12,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'careers-portal' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors'      => array(
					'{{WRAPPER}} .ccjp-job-listing__grid' => '--ccjp-columns: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_job_card_style_controls();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$job_id   = $this->get_current_job_id();

		if ( ! $job_id ) {
			if ( ! $this->is_editing() ) {
				return;
			}
		}

		$query = CCJP_Job_Query::query(
			array(
				'source'            => 'related',
				'reference_post_id' => $job_id,
				'posts_per_page'    => (int) $settings['number'],
			)
		);

		if ( ! $query->have_posts() && ! $this->is_editing() ) {
			return;
		}
		?>
		<div class="ccjp-related-jobs">
			<?php if ( '' !== $settings['heading'] ) : ?>
				<h2 class="ccjp-related-jobs__heading"><?php echo esc_html( $settings['heading'] ); ?></h2>
			<?php endif; ?>

			<div class="ccjp-job-listing__grid">
				<?php
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						echo CCJP_Job_Card_Renderer::render( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					wp_reset_postdata();
				} else {
					echo CCJP_Job_Card_Renderer::render_placeholder(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
		<?php
	}
}
