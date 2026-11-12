<?php
/**
 * Departments page — real career_department terms. Loaded via the
 * template_include filter in functions.php.
 */

defined( 'ABSPATH' ) || exit;

get_header();
bccl_render_taxonomy_grid(
	'career_department',
	__( 'Browse by Department', 'hello-elementor-child' ),
	__( 'Find open roles by business unit, with live open-role counts.', 'hello-elementor-child' )
);
get_footer();
