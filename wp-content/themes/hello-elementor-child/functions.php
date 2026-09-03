<?php
/**
 * Hello Elementor Child theme functions.
 *
 * Deliberately minimal: this theme exists only to host Elementor Pro's
 * Theme Builder templates (header, footer, archive/single/taxonomy for
 * career_job). No page layout, typography, colors or spacing are defined
 * here — that is 100% Elementor's job, per the Careers Portal architecture.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'ccjp_child_enqueue_styles' ) ) {
	function ccjp_child_enqueue_styles() {
		wp_enqueue_style(
			'hello-elementor-child-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( 'hello-elementor-theme-style' ),
			wp_get_theme()->get( 'Version' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'ccjp_child_enqueue_styles', 20 );
