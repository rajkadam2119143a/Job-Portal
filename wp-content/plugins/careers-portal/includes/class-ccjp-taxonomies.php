<?php
defined( 'ABSPATH' ) || exit;

class CCJP_Taxonomies {

	public static function definitions() {
		return array(
			'career_category'   => array(
				'slug'          => 'careers/category',
				'hierarchical'  => true,
				'name'          => __( 'Categories', 'careers-portal' ),
				'singular_name' => __( 'Category', 'careers-portal' ),
			),
			'career_department' => array(
				'slug'          => 'careers/department',
				'hierarchical'  => true,
				'name'          => __( 'Departments', 'careers-portal' ),
				'singular_name' => __( 'Department', 'careers-portal' ),
			),
			'career_location'   => array(
				'slug'          => 'careers/location',
				'hierarchical'  => true,
				'name'          => __( 'Locations', 'careers-portal' ),
				'singular_name' => __( 'Location', 'careers-portal' ),
			),
			'career_job_type'   => array(
				'slug'          => 'careers/job-type',
				'hierarchical'  => false,
				'name'          => __( 'Job Types', 'careers-portal' ),
				'singular_name' => __( 'Job Type', 'careers-portal' ),
			),
			'career_work_mode'  => array(
				'slug'          => 'careers/work-mode',
				'hierarchical'  => false,
				'name'          => __( 'Work Modes', 'careers-portal' ),
				'singular_name' => __( 'Work Mode', 'careers-portal' ),
			),
		);
	}

	public static function register() {
		foreach ( self::definitions() as $key => $cfg ) {
			register_taxonomy(
				$key,
				array( 'career_job' ),
				array(
					'labels'             => array(
						'name'          => $cfg['name'],
						'singular_name' => $cfg['singular_name'],
						'menu_name'     => $cfg['name'],
					),
					'public'             => true,
					'publicly_queryable' => true,
					'hierarchical'       => $cfg['hierarchical'],
					'show_ui'            => true,
					'show_admin_column'  => true,
					'show_in_menu'       => true,
					'show_in_nav_menus'  => true,
					'show_in_rest'       => false,
					'query_var'          => true,
					'rewrite'            => array(
						'slug'         => $cfg['slug'],
						'with_front'   => false,
						'hierarchical' => $cfg['hierarchical'],
					),
					// Scoped to the Careers Manager role rather than the
					// default manage_categories, so managing these terms
					// doesn't also grant control over unrelated blog
					// categories/tags.
					'capabilities'       => array(
						'manage_terms' => 'manage_career_terms',
						'edit_terms'   => 'manage_career_terms',
						'delete_terms' => 'manage_career_terms',
						'assign_terms' => 'assign_career_terms',
					),
				)
			);
		}
	}
}
