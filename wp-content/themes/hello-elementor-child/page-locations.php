<?php
/**
 * Locations page — real career_location terms. Loaded via the
 * template_include filter in functions.php.
 */

defined( 'ABSPATH' ) || exit;

get_header();
bccl_render_taxonomy_grid(
	'career_location',
	__( 'Find Opportunities Near You', 'hello-elementor-child' ),
	__( 'Every city we hire in, with live open-role counts.', 'hello-elementor-child' )
);
get_footer();
