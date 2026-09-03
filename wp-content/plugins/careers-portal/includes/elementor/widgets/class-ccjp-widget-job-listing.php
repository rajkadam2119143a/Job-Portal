<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Careers Job Listing — the core widget: WP_Query against career_job via
 * CCJP_Job_Query, rendered with CCJP_Job_Card_Renderer, columns controlled
 * by Elementor's own responsive control (no hardcoded breakpoints), and
 * results additionally filterable via GET params so a Job Search/Filters
 * widget elsewhere on the page (or a plain shared link) works without JS.
 */
class CCJP_Widget_Job_Listing extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-job-listing';
	}

	public function get_title() {
		return __( 'Job Listing', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return array( 'careers', 'jobs', 'listing', 'query' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_query',
			array( 'label' => __( 'Query', 'careers-portal' ) )
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'Source', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array(
					'all'                => __( 'All Jobs', 'careers-portal' ),
					'featured'           => __( 'Featured Jobs', 'careers-portal' ),
					'current_category'   => __( 'Current Category', 'careers-portal' ),
					'current_department' => __( 'Current Department', 'careers-portal' ),
					'current_location'   => __( 'Current Location', 'careers-portal' ),
					'current_job_type'   => __( 'Current Job Type', 'careers-portal' ),
					'current_work_mode'  => __( 'Current Work Mode', 'careers-portal' ),
					'related'            => __( 'Related Jobs', 'careers-portal' ),
				),
			)
		);

		$this->add_control(
			'featured_only',
			array(
				'label'   => __( 'Featured Only', 'careers-portal' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		foreach ( CCJP_Job_Query::TAXONOMIES as $key => $taxonomy ) {
			$this->add_control(
				'filter_' . $key,
				array(
					// translators: %s is the taxonomy label, e.g. "Department".
					'label'   => sprintf( __( '%s (optional filter)', 'careers-portal' ), get_taxonomy( $taxonomy )->labels->singular_name ),
					'type'    => Controls_Manager::SELECT2,
					'options' => $this->term_options( $taxonomy ),
					'default' => '',
				)
			);
		}

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

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Posts Per Page', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '9',
				'options' => array(
					'6'      => '6',
					'9'      => '9',
					'12'     => '12',
					'18'     => '18',
					'24'     => '24',
					'custom' => __( 'Custom', 'careers-portal' ),
				),
			)
		);

		$this->add_control(
			'posts_per_page_custom',
			array(
				'label'     => __( 'Custom Posts Per Page', 'careers-portal' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 9,
				'min'       => 1,
				'condition' => array( 'posts_per_page' => 'custom' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'           => __( 'Columns', 'careers-portal' ),
				'type'            => Controls_Manager::SELECT,
				'default'         => '3',
				'tablet_default'  => '2',
				'mobile_default'  => '1',
				'options'         => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'       => array(
					'{{WRAPPER}} .ccjp-job-listing__grid' => '--ccjp-columns: {{VALUE}};',
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

		$this->add_control(
			'pagination_type',
			array(
				'label'   => __( 'Pagination Type', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'numbered',
				'options' => array(
					'none'     => __( 'None', 'careers-portal' ),
					'numbered' => __( 'Numbered Pagination', 'careers-portal' ),
					'load_more' => __( 'Load More', 'careers-portal' ),
				),
			)
		);

		$this->add_control(
			'load_more_text',
			array(
				'label'     => __( 'Load More Button Text', 'careers-portal' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Load More Jobs', 'careers-portal' ),
				'condition' => array( 'pagination_type' => 'load_more' ),
			)
		);

		$this->end_controls_section();

		$this->register_job_card_style_controls();
	}

	private function term_options( $taxonomy ) {
		$terms   = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
		$options = array( '' => __( 'Any', 'careers-portal' ) );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name;
			}
		}
		return $options;
	}

	/**
	 * Merges the widget's own settings with GET params from a Job
	 * Search/Filters form elsewhere on the page (or a plain shared link),
	 * so filtering works with zero JavaScript.
	 */
	private function resolve_query_args( array $settings ) {
		$per_page = 'custom' === $settings['posts_per_page']
			? (int) $settings['posts_per_page_custom']
			: (int) $settings['posts_per_page'];

		$args = array(
			'source'            => $settings['source'],
			'reference_post_id' => $this->get_current_job_id(),
			'order'             => $settings['order'],
			'posts_per_page'    => $per_page,
			'featured_only'     => 'yes' === $settings['featured_only'],
			'paged'             => isset( $_GET['ccjp_page'] ) ? absint( $_GET['ccjp_page'] ) : 1, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		);

		foreach ( CCJP_Job_Query::TAXONOMIES as $key => $taxonomy ) {
			$args[ $key ] = ! empty( $settings[ 'filter_' . $key ] ) ? $settings[ 'filter_' . $key ] : '';
			if ( ! empty( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$args[ $key ] = sanitize_text_field( wp_unslash( $_GET[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}

		if ( ! empty( $_GET['keyword'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['keyword'] = sanitize_text_field( wp_unslash( $_GET['keyword'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		if ( ! empty( $_GET['experience'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['experience'] = sanitize_text_field( wp_unslash( $_GET['experience'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		if ( ! empty( $_GET['date_posted'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['date_posted'] = absint( $_GET['date_posted'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		return $args;
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$args     = $this->resolve_query_args( $settings );
		$query    = CCJP_Job_Query::query( $args );

		$widget_id = 'ccjp-listing-' . $this->get_id();
		?>
		<div
			id="<?php echo esc_attr( $widget_id ); ?>"
			class="ccjp-job-listing"
			data-ccjp-listing="1"
			data-ccjp-settings="<?php echo esc_attr( wp_json_encode( $args ) ); ?>"
			data-ccjp-pagination="<?php echo esc_attr( $settings['pagination_type'] ); ?>"
			data-ccjp-found="<?php echo esc_attr( (int) $query->found_posts ); ?>"
		>
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

			<?php if ( ! $query->have_posts() && ! $this->is_editing() ) : ?>
				<p class="ccjp-job-listing__empty"><?php esc_html_e( 'No opportunities found. Try changing your search criteria.', 'careers-portal' ); ?></p>
			<?php endif; ?>

			<?php if ( 'numbered' === $settings['pagination_type'] && $query->max_num_pages > 1 ) : ?>
				<nav class="ccjp-job-listing__pagination">
					<?php
					echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'base'      => add_query_arg( 'ccjp_page', '%#%' ),
							'format'    => '',
							'current'   => max( 1, $args['paged'] ),
							'total'     => $query->max_num_pages,
							'add_args'  => false,
							'prev_text' => __( 'Prev', 'careers-portal' ),
							'next_text' => __( 'Next', 'careers-portal' ),
						)
					);
					?>
				</nav>
			<?php elseif ( 'load_more' === $settings['pagination_type'] && $query->max_num_pages > $args['paged'] ) : ?>
				<div class="ccjp-job-listing__load-more">
					<button type="button" class="ccjp-job-listing__load-more-button" data-page="<?php echo esc_attr( $args['paged'] ); ?>" data-max-pages="<?php echo esc_attr( $query->max_num_pages ); ?>">
						<?php echo esc_html( $settings['load_more_text'] ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
