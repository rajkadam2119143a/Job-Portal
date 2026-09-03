<?php
defined( 'ABSPATH' ) || exit;

/**
 * Reserves the handful of URLs the plugin's widgets actually need to exist
 * somewhere (a hub for "all teams", "all locations", and the application
 * success confirmation) with blank pages for a later phase to build in
 * Elementor. Purely editorial pages (About, Life at Company) are NOT
 * created here — those have no dynamic-data dependency, so manufacturing
 * them would be the plugin dictating page structure rather than providing
 * functionality; the site owner creates those like any other WP page.
 *
 * Runs from CCJP_Activator::activate() and again from CCJP_Plugin's
 * version-bump hook (idempotent — checked by path before every insert) so
 * a plugin update that adds a new required page reaches sites that only
 * update, not reactivate.
 */
class CCJP_Pages {

	const SLUG = 'careers';

	public function __construct() {
		// Intentionally empty: page creation only happens at activation/
		// version-bump time, not on every request.
	}

	public static function required_pages() {
		return array(
			self::SLUG              => array(
				'title'  => __( 'Careers', 'careers-portal' ),
				'parent' => '',
			),
			'teams'                  => array(
				'title'  => __( 'Teams', 'careers-portal' ),
				'parent' => self::SLUG,
			),
			'locations'              => array(
				'title'  => __( 'Locations', 'careers-portal' ),
				'parent' => self::SLUG,
			),
			'application-success'    => array(
				'title'  => __( 'Application Submitted', 'careers-portal' ),
				'parent' => self::SLUG,
			),
		);
	}

	public static function maybe_create_careers_page() {
		self::maybe_create_pages();
	}

	public static function maybe_create_pages() {
		$created_ids = array();

		foreach ( self::required_pages() as $slug => $config ) {
			$existing = self::find_page_by_slug( $slug );
			if ( $existing ) {
				$created_ids[ $slug ] = $existing;
				continue;
			}

			$parent_id = 0;
			if ( '' !== $config['parent'] && isset( $created_ids[ $config['parent'] ] ) ) {
				$parent_id = $created_ids[ $config['parent'] ];
			}

			$post_id = wp_insert_post(
				array(
					'post_type'      => 'page',
					'post_title'     => $config['title'],
					'post_name'      => $slug,
					'post_parent'    => $parent_id,
					'post_status'    => 'publish',
					'post_content'   => '',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
				)
			);

			if ( ! is_wp_error( $post_id ) && $post_id ) {
				$created_ids[ $slug ] = $post_id;
			}
		}

		return $created_ids;
	}

	/**
	 * Finds a page by its own slug regardless of nesting depth.
	 * get_page_by_path() requires the FULL parent-prefixed path (e.g.
	 * "careers/teams") for a child page — a bare slug silently finds
	 * nothing, which previously caused this method to "not find" pages
	 * that already existed and create duplicates instead.
	 */
	private static function find_page_by_slug( $slug ) {
		$posts = get_posts(
			array(
				'post_type'      => 'page',
				'name'           => $slug,
				'post_status'    => 'any',
				'posts_per_page' => 1,
			)
		);
		return ! empty( $posts ) ? (int) $posts[0]->ID : 0;
	}

	public static function get_page_url( $slug ) {
		$page_id = self::find_page_by_slug( $slug );
		return $page_id ? get_permalink( $page_id ) : '';
	}
}
