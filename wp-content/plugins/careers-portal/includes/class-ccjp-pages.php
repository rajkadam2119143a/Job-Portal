<?php
defined( 'ABSPATH' ) || exit;

/**
 * Reserves the URLs the plugin's widgets and the site-wide navigation need
 * to exist somewhere: hubs for "all teams"/"all departments"/"all
 * locations", the application success confirmation, and (since the global
 * header links to them) About/Contact. About/Contact get a minimal
 * placeholder paragraph rather than staying fully blank, since a nav link
 * to an empty-looking page reads as broken; the site owner is expected to
 * replace that placeholder copy, not the page itself.
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
				'title'   => __( 'Careers', 'careers-portal' ),
				'parent'  => '',
			),
			'legacy'                 => array(
				'title'   => __( 'Our Legacy', 'careers-portal' ),
				'parent'  => '',
			),
			'teams'                  => array(
				'title'   => __( 'Teams', 'careers-portal' ),
				'parent'  => self::SLUG,
			),
			'departments'            => array(
				'title'   => __( 'Departments', 'careers-portal' ),
				'parent'  => self::SLUG,
			),
			'locations'              => array(
				'title'   => __( 'Locations', 'careers-portal' ),
				'parent'  => self::SLUG,
			),
			'about'                  => array(
				'title'   => __( 'About', 'careers-portal' ),
				'parent'  => self::SLUG,
				'content' => __( 'Tell candidates who you are, what you build, and why it matters. Replace this placeholder with your own About Us content.', 'careers-portal' ),
			),
			'contact'                => array(
				'title'   => __( 'Contact', 'careers-portal' ),
				'parent'  => self::SLUG,
				'content' => __( 'Have a question before applying? Replace this placeholder with your real contact details or a contact form.', 'careers-portal' ),
			),
			'application-success'    => array(
				'title'   => __( 'Application Submitted', 'careers-portal' ),
				'parent'  => self::SLUG,
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
					'post_content'   => isset( $config['content'] ) ? '<!-- wp:paragraph --><p>' . esc_html( $config['content'] ) . '</p><!-- /wp:paragraph -->' : '',
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
