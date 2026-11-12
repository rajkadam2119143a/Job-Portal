<?php
// Generated child theme functions — enqueues the parent's stylesheet
// first, then this child theme's own on top, the standard WordPress
// child-theme pattern (WEBSITE-ARCHITECTURE.md §4/§10).
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
} );
