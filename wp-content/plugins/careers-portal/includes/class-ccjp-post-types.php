<?php
defined( 'ABSPATH' ) || exit;

class CCJP_Post_Types {

	const JOB_MENU_ICON = 'dashicons-businessperson';
	// 25 collides with the core Comments menu.
	const JOB_MENU_POSITION = 26;

	private static $saving_title = false;

	public static function register() {
		self::register_job();
		self::register_application();

		// Populate the application's post_title from its ACF fields once ACF
		// has finished writing postmeta (raw save_post fires too early for
		// that). Registered here rather than in the constructor because
		// CCJP_Post_Types is driven statically off the `init` action.
		add_action( 'acf/save_post', array( __CLASS__, 'maybe_set_application_title' ), 20 );
	}

	private static function register_job() {
		$labels = array(
			'name'               => __( 'Jobs', 'careers-portal' ),
			'singular_name'      => __( 'Job', 'careers-portal' ),
			'menu_name'          => __( 'Careers Portal', 'careers-portal' ),
			'add_new'            => __( 'Add New', 'careers-portal' ),
			'add_new_item'       => __( 'Add New Job', 'careers-portal' ),
			'edit_item'          => __( 'Edit Job', 'careers-portal' ),
			'new_item'           => __( 'New Job', 'careers-portal' ),
			'view_item'          => __( 'View Job', 'careers-portal' ),
			'view_items'         => __( 'View Jobs', 'careers-portal' ),
			'search_items'       => __( 'Search Jobs', 'careers-portal' ),
			'not_found'          => __( 'No jobs found.', 'careers-portal' ),
			'not_found_in_trash' => __( 'No jobs found in Trash.', 'careers-portal' ),
			'all_items'          => __( 'All Jobs', 'careers-portal' ),
			'archives'           => __( 'Job Archives', 'careers-portal' ),
		);

		register_post_type(
			'career_job',
			array(
				'labels'              => $labels,
				'description'         => __( 'Open, draft and closed job requisitions.', 'careers-portal' ),
				'public'              => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'show_in_rest'        => false,
				'menu_icon'           => self::JOB_MENU_ICON,
				'menu_position'       => self::JOB_MENU_POSITION,
				'hierarchical'        => false,
				// No 'custom-fields' support: ACF renders its own metabox
				// regardless, and the raw Custom Fields metabox would just
				// duplicate it with raw meta keys.
				'supports'            => array( 'title', 'editor', 'revisions' ),
				'has_archive'         => 'careers/jobs',
				'rewrite'             => array(
					'slug'       => 'careers/job',
					'with_front' => false,
					'feeds'      => false,
					'pages'      => true,
				),
				'query_var'           => 'career_job',
				'capability_type'     => array( 'career_job', 'career_jobs' ),
				'map_meta_cap'        => true,
				'capabilities'        => array(
					// get_post_type_capabilities() does not derive
					// create_posts from capability_type; without this it
					// silently falls back to edit_posts.
					'create_posts' => 'create_career_jobs',
				),
			)
		);
	}

	private static function register_application() {
		$labels = array(
			'name'          => __( 'Applications', 'careers-portal' ),
			'singular_name' => __( 'Application', 'careers-portal' ),
			'add_new'       => __( 'Add New', 'careers-portal' ),
			'add_new_item'  => __( 'Add New Application', 'careers-portal' ),
			'edit_item'     => __( 'Edit Application', 'careers-portal' ),
			'new_item'      => __( 'New Application', 'careers-portal' ),
			'view_item'     => __( 'View Application', 'careers-portal' ),
			'search_items'  => __( 'Search Applications', 'careers-portal' ),
			'not_found'     => __( 'No applications found.', 'careers-portal' ),
			'all_items'     => __( 'All Applications', 'careers-portal' ),
		);

		register_post_type(
			'career_application',
			array(
				'labels'              => $labels,
				'description'         => __( 'Candidate applications submitted against a Job.', 'careers-portal' ),
				'public'              => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				// Nests as a submenu under the Jobs top-level menu.
				'show_in_menu'        => 'edit.php?post_type=career_job',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => false,
				'hierarchical'        => false,
				'supports'            => array( 'title' ),
				'has_archive'         => false,
				'rewrite'             => false,
				'query_var'           => false,
				'capability_type'     => array( 'career_application', 'career_applications' ),
				'map_meta_cap'        => true,
				'capabilities'        => array(
					'create_posts' => 'create_career_applications',
				),
			)
		);
	}

	/**
	 * Auto-fills the application's post_title from its ACF fields so the
	 * list table never shows "Auto Draft" — candidates never type a title.
	 */
	public static function maybe_set_application_title( $post_id ) {
		if ( self::$saving_title || get_post_type( $post_id ) !== 'career_application' ) {
			return;
		}

		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		$first_name = trim( (string) get_field( 'ccjp_first_name', $post_id ) );
		$last_name  = trim( (string) get_field( 'ccjp_last_name', $post_id ) );
		$job_id     = get_field( 'ccjp_job', $post_id );
		$job_title  = $job_id ? get_the_title( $job_id ) : '';

		$candidate_name = trim( $first_name . ' ' . $last_name );
		if ( '' === $candidate_name && '' === $job_title ) {
			return;
		}

		$title = $candidate_name;
		if ( $job_title ) {
			$title = $candidate_name ? $candidate_name . ' — ' . $job_title : $job_title;
		}

		if ( '' === $title || get_the_title( $post_id ) === $title ) {
			return;
		}

		self::$saving_title = true;
		wp_update_post(
			array(
				'ID'         => $post_id,
				'post_title' => $title,
			)
		);
		self::$saving_title = false;
	}
}
