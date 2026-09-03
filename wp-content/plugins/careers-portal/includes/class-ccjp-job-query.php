<?php
defined( 'ABSPATH' ) || exit;

/**
 * Single shared WP_Query builder for career_job, used by every Elementor
 * widget (Job Listing, Featured Jobs, Related Jobs, Job Search/Filters AJAX)
 * so query logic lives in one place instead of being duplicated per widget.
 */
class CCJP_Job_Query {

	const TAXONOMIES = array(
		'category'   => 'career_category',
		'department' => 'career_department',
		'location'   => 'career_location',
		'job_type'   => 'career_job_type',
		'work_mode'  => 'career_work_mode',
	);

	public static function query( array $args = array() ) {
		$defaults = array(
			'source'            => 'all',
			'reference_post_id' => 0,
			'posts_per_page'    => 9,
			'paged'             => 1,
			'order'             => 'newest',
			'keyword'           => '',
			'category'          => '',
			'department'        => '',
			'location'          => '',
			'job_type'          => '',
			'work_mode'         => '',
			'experience'        => '',
			'date_posted'       => '',
			'featured_only'     => false,
			'exclude'           => array(),
			'include_closed'    => false,
		);
		$args = wp_parse_args( $args, $defaults );

		$query_args = array(
			'post_type'           => 'career_job',
			'post_status'         => $args['include_closed'] ? array( 'publish', 'job_closed' ) : array( 'publish' ),
			'posts_per_page'      => (int) $args['posts_per_page'],
			'paged'               => max( 1, (int) $args['paged'] ),
			'post__not_in'        => array_map( 'intval', (array) $args['exclude'] ),
			'ignore_sticky_posts' => true,
		);

		switch ( $args['order'] ) {
			case 'oldest':
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'ASC';
				break;
			case 'title':
				$query_args['orderby'] = 'title';
				$query_args['order']   = 'ASC';
				break;
			case 'random':
				$query_args['orderby'] = 'rand';
				break;
			case 'newest':
			default:
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'DESC';
				break;
		}

		$tax_query = array();

		foreach ( self::TAXONOMIES as $key => $taxonomy ) {
			if ( ! empty( $args[ $key ] ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => is_numeric( $args[ $key ] ) ? 'term_id' : 'slug',
					'terms'    => $args[ $key ],
				);
			}
		}

		$dynamic_current = array(
			'current_category'   => 'category',
			'current_department' => 'department',
			'current_location'   => 'location',
			'current_job_type'   => 'job_type',
			'current_work_mode'  => 'work_mode',
		);

		if ( isset( $dynamic_current[ $args['source'] ] ) ) {
			$key     = $dynamic_current[ $args['source'] ];
			$ref_id  = $args['reference_post_id'] ? (int) $args['reference_post_id'] : self::resolve_reference_post_id();
			$term_ids = $ref_id ? wp_get_post_terms( $ref_id, self::TAXONOMIES[ $key ], array( 'fields' => 'ids' ) ) : array();

			if ( ! empty( $term_ids ) && ! is_wp_error( $term_ids ) ) {
				$tax_query[] = array(
					'taxonomy' => self::TAXONOMIES[ $key ],
					'field'    => 'term_id',
					'terms'    => $term_ids,
				);
			} else {
				// No terms to match against — force an empty result rather
				// than silently falling back to "all jobs".
				$query_args['post__in'] = array( 0 );
			}
		} elseif ( 'related' === $args['source'] ) {
			$ref_id = $args['reference_post_id'] ? (int) $args['reference_post_id'] : self::resolve_reference_post_id();
			$or     = array( 'relation' => 'OR' );

			if ( $ref_id ) {
				foreach ( self::TAXONOMIES as $taxonomy ) {
					$term_ids = wp_get_post_terms( $ref_id, $taxonomy, array( 'fields' => 'ids' ) );
					if ( ! empty( $term_ids ) && ! is_wp_error( $term_ids ) ) {
						$or[] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => $term_ids,
						);
					}
				}
				$query_args['post__not_in'][] = $ref_id;
			}

			if ( count( $or ) > 1 ) {
				$tax_query[] = $or;
			} else {
				$query_args['post__in'] = array( 0 );
			}
		} elseif ( 'featured' === $args['source'] ) {
			$args['featured_only'] = true;
		}

		if ( ! empty( $tax_query ) ) {
			if ( count( $tax_query ) > 1 ) {
				$tax_query['relation'] = 'AND';
			}
			$query_args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		$meta_query = array();

		if ( $args['featured_only'] ) {
			$meta_query[] = array(
				'key'     => 'ccjp_featured',
				'value'   => '1',
				'compare' => '=',
			);
		}

		if ( ! empty( $args['experience'] ) ) {
			$meta_query[] = array(
				'key'   => 'ccjp_experience',
				'value' => $args['experience'],
			);
		}

		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		if ( ! empty( $args['date_posted'] ) && is_numeric( $args['date_posted'] ) ) {
			$query_args['date_query'] = array(
				array( 'after' => (int) $args['date_posted'] . ' days ago' ),
			);
		}

		$keyword = trim( (string) $args['keyword'] );
		if ( '' !== $keyword ) {
			$query_args['s'] = $keyword;
			add_filter( 'posts_join', array( __CLASS__, 'extend_search_join' ), 10, 2 );
			add_filter( 'posts_search', array( __CLASS__, 'extend_search_sql' ), 10, 2 );
			add_filter( 'posts_groupby', array( __CLASS__, 'extend_search_groupby' ), 10, 2 );
		}

		$query = new WP_Query( $query_args );

		if ( '' !== $keyword ) {
			remove_filter( 'posts_join', array( __CLASS__, 'extend_search_join' ), 10 );
			remove_filter( 'posts_search', array( __CLASS__, 'extend_search_sql' ), 10 );
			remove_filter( 'posts_groupby', array( __CLASS__, 'extend_search_groupby' ), 10 );
		}

		return $query;
	}

	/**
	 * Resolves "the job we're currently in the context of" for dynamic
	 * query sources (current_*, related) and for widget preview
	 * placeholders: the queried object on a single Job page/template, or
	 * the most recent published Job as an editor-preview fallback.
	 */
	public static function resolve_reference_post_id() {
		$queried_id = get_queried_object_id();
		if ( $queried_id && 'career_job' === get_post_type( $queried_id ) ) {
			return $queried_id;
		}

		if ( self::is_elementor_editing() ) {
			$latest = get_posts(
				array(
					'post_type'      => 'career_job',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'fields'         => 'ids',
				)
			);
			if ( ! empty( $latest ) ) {
				return (int) $latest[0];
			}
		}

		return 0;
	}

	public static function is_elementor_editing() {
		if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
			return false;
		}
		$plugin = \Elementor\Plugin::$instance;
		return ( isset( $plugin->editor ) && $plugin->editor->is_edit_mode() )
			|| ( isset( $plugin->preview ) && $plugin->preview->is_preview_mode() );
	}

	/**
	 * Extends keyword search to also match a handful of ACF meta fields
	 * (skills, short description) alongside WP's native title/content
	 * search, scoped strictly to career_job queries via post_type check.
	 */
	public static function extend_search_join( $join, $query ) {
		global $wpdb;
		if ( 'career_job' !== $query->get( 'post_type' ) || ! $query->get( 's' ) ) {
			return $join;
		}
		$join .= " LEFT JOIN {$wpdb->postmeta} ccjp_kw_meta ON ccjp_kw_meta.post_id = {$wpdb->posts}.ID AND ccjp_kw_meta.meta_key IN ('ccjp_skills','ccjp_short_description')";
		return $join;
	}

	public static function extend_search_sql( $search, $query ) {
		global $wpdb;
		if ( 'career_job' !== $query->get( 'post_type' ) || empty( $search ) ) {
			return $search;
		}
		$term = $query->get( 's' );
		if ( ! $term ) {
			return $search;
		}

		$like  = '%' . $wpdb->esc_like( $term ) . '%';
		$extra = $wpdb->prepare( ' OR (ccjp_kw_meta.meta_value LIKE %s)', $like );
		$pos   = strrpos( $search, '))' );

		if ( false !== $pos ) {
			$search = substr( $search, 0, $pos ) . $extra . substr( $search, $pos );
		}

		return $search;
	}

	public static function extend_search_groupby( $groupby, $query ) {
		global $wpdb;
		if ( 'career_job' !== $query->get( 'post_type' ) || ! $query->get( 's' ) ) {
			return $groupby;
		}
		if ( false === strpos( $groupby, "{$wpdb->posts}.ID" ) ) {
			$groupby = $groupby ? $groupby . ", {$wpdb->posts}.ID" : "{$wpdb->posts}.ID";
		}
		return $groupby;
	}
}
