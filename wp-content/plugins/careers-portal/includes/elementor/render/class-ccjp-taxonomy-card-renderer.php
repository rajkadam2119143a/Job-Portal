<?php
defined( 'ABSPATH' ) || exit;

/**
 * Shared card markup for a single taxonomy term (used by the Teams,
 * Departments and Locations listing widgets) — name, open-role count, and
 * a link to that term's archive.
 */
class CCJP_Taxonomy_Card_Renderer {

	public static function render( WP_Term $term, array $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'link_text' => __( 'View Jobs', 'careers-portal' ),
			)
		);

		$count = self::open_job_count( $term );
		$link  = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			$link = '#';
		}

		ob_start();
		?>
		<a class="ccjp-taxonomy-card" href="<?php echo esc_url( $link ); ?>">
			<span class="ccjp-taxonomy-card__name"><?php echo esc_html( $term->name ); ?></span>
			<span class="ccjp-taxonomy-card__count">
				<?php
				printf(
					/* translators: %d is the number of open jobs. */
					esc_html( _n( '%d open role', '%d open roles', $count, 'careers-portal' ) ),
					(int) $count
				);
				?>
			</span>
			<span class="ccjp-taxonomy-card__link"><?php echo esc_html( $options['link_text'] ); ?></span>
		</a>
		<?php
		return ob_get_clean();
	}

	public static function open_job_count( WP_Term $term ) {
		$args = array(
			'source'         => 'all',
			'posts_per_page' => -1,
		);

		foreach ( CCJP_Job_Query::TAXONOMIES as $key => $taxonomy ) {
			if ( $taxonomy === $term->taxonomy ) {
				$args[ $key ] = $term->term_id;
				break;
			}
		}

		$term_query = CCJP_Job_Query::query( $args );
		return (int) $term_query->found_posts;
	}
}
