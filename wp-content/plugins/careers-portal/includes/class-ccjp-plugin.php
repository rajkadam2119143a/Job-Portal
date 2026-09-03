<?php
defined( 'ABSPATH' ) || exit;

final class CCJP_Plugin {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		load_plugin_textdomain( 'careers-portal', false, dirname( CCJP_PLUGIN_BASENAME ) . '/languages' );

		add_action( 'init', array( 'CCJP_Post_Statuses', 'register' ), 5 );
		add_action( 'init', array( 'CCJP_Post_Types', 'register' ), 6 );
		add_action( 'init', array( 'CCJP_Taxonomies', 'register' ), 7 );

		new CCJP_Capabilities();
		new CCJP_ACF();
		new CCJP_Admin();
		new CCJP_Pages();
		new CCJP_Elementor();
		new CCJP_Ajax();
		new CCJP_Applications();
		new CCJP_GravityForms();

		add_action( 'init', array( $this, 'maybe_flush_on_version_bump' ), 20 );
	}

	/**
	 * Catches the case where a plugin update changes rewrite rules
	 * (post types/taxonomies/statuses) without the admin remembering to
	 * deactivate/reactivate.
	 */
	public function maybe_flush_on_version_bump() {
		if ( get_option( 'ccjp_version' ) !== CCJP_VERSION ) {
			CCJP_Pages::maybe_create_pages();
			CCJP_GravityForms::maybe_create_form();
			flush_rewrite_rules();
			update_option( 'ccjp_version', CCJP_VERSION );
		}
	}
}
