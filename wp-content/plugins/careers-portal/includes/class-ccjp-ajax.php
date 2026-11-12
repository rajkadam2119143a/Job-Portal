<?php
defined( 'ABSPATH' ) || exit;

/**
 * Public AJAX endpoints for the Careers Portal widgets. This is the one
 * place Job Search, Job Filters and Job Listing's "Load More" all post to,
 * so the query/render logic isn't duplicated per widget's JS.
 *
 * Read-only public data (job search results) — nonce is checked leniently
 * rather than hard-failing, since a stale nonce on a cached page shouldn't
 * break search. State-changing actions (application submission) get strict
 * nonce + capability enforcement when that widget is built.
 */
class CCJP_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_ccjp_query_jobs', array( $this, 'query_jobs' ) );
		add_action( 'wp_ajax_nopriv_ccjp_query_jobs', array( $this, 'query_jobs' ) );
	}

	public function query_jobs() {
		$args = array(
			'source'         => $this->sanitize_key_field( 'source', 'all' ),
			'keyword'        => isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '',
			'category'       => $this->sanitize_key_field( 'category' ),
			'department'     => $this->sanitize_key_field( 'department' ),
			'location'       => $this->sanitize_key_field( 'location' ),
			'job_type'       => $this->sanitize_multi_or_single( 'job_type' ),
			'work_mode'      => $this->sanitize_multi_or_single( 'work_mode' ),
			'experience'     => $this->sanitize_multi_or_single( 'experience' ),
			'date_posted'    => isset( $_POST['date_posted'] ) ? absint( $_POST['date_posted'] ) : '',
			'order'          => $this->sanitize_key_field( 'order', 'newest' ),
			'posts_per_page' => isset( $_POST['per_page'] ) ? max( 1, absint( $_POST['per_page'] ) ) : 9,
			'paged'          => isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1,
		);

		if ( ! empty( $_POST['reference_post_id'] ) ) {
			$args['reference_post_id'] = absint( $_POST['reference_post_id'] );
		}

		$meta_keys = isset( $_POST['meta_keys'] ) && is_array( $_POST['meta_keys'] )
			? array_map( 'sanitize_key', wp_unslash( $_POST['meta_keys'] ) )
			: array( 'department', 'location', 'job_type', 'work_mode', 'experience' );

		$query = CCJP_Job_Query::query( $args );

		$cards = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$cards[] = CCJP_Job_Card_Renderer::render(
					get_the_ID(),
					array( 'meta_keys' => $meta_keys )
				);
			}
			wp_reset_postdata();
		}

		wp_send_json_success(
			array(
				'html'        => implode( '', $cards ),
				'found'       => (int) $query->found_posts,
				'maxPages'    => (int) $query->max_num_pages,
				'currentPage' => (int) $args['paged'],
			)
		);
	}

	private function sanitize_key_field( $post_key, $default = '' ) {
		if ( empty( $_POST[ $post_key ] ) ) {
			return $default;
		}
		return sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) );
	}

	/**
	 * Like sanitize_key_field(), but also accepts an array (checkbox-group
	 * filters submit as job_type[]=a&job_type[]=b, which PHP already
	 * collects into $_POST['job_type'] = array('a','b')) — single-select
	 * callers (the older dropdown-based Job Search/Filters widgets) keep
	 * getting a plain sanitized string back, unchanged.
	 */
	private function sanitize_multi_or_single( $post_key, $default = '' ) {
		if ( empty( $_POST[ $post_key ] ) ) {
			return $default;
		}
		$raw = wp_unslash( $_POST[ $post_key ] );
		if ( is_array( $raw ) ) {
			$clean = array_map( 'sanitize_text_field', $raw );
			return array_values( array_filter( $clean, 'strlen' ) );
		}
		return sanitize_text_field( $raw );
	}
}
