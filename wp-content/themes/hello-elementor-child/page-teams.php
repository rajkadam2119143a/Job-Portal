<?php
/**
 * Teams page — real career_category terms (publicly labeled "Teams",
 * matching CCJP_Widget_Categories's own naming). Loaded via the
 * template_include filter in functions.php.
 */

defined( 'ABSPATH' ) || exit;

get_header();
bccl_render_taxonomy_grid(
	'career_category',
	__( 'Explore Teams', 'hello-elementor-child' ),
	__( 'Every team behind BCCL\'s journalism, products and platforms — with live open-role counts.', 'hello-elementor-child' )
);
get_footer();
