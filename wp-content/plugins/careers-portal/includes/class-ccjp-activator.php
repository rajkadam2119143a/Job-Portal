<?php
defined( 'ABSPATH' ) || exit;

class CCJP_Activator {

	public static function activate() {
		// init hasn't fired yet inside the activation hook, so register
		// everything explicitly before flushing rewrite rules at the end.
		CCJP_Post_Statuses::register();
		CCJP_Post_Types::register();
		CCJP_Taxonomies::register();

		CCJP_Capabilities::grant_all( CCJP_Capabilities::ensure_role() );
		CCJP_Capabilities::grant_all( get_role( 'administrator' ) );
		update_option( 'ccjp_caps_version', CCJP_VERSION );

		wp_mkdir_p( CCJP_PLUGIN_DIR . 'acf-json' );
		if ( wp_is_writable( CCJP_PLUGIN_DIR . 'acf-json' ) ) {
			delete_option( 'ccjp_acf_json_not_writable' );
		} else {
			update_option( 'ccjp_acf_json_not_writable', 1 );
		}

		CCJP_Pages::maybe_create_careers_page();
		CCJP_GravityForms::maybe_create_form();
		if ( did_action( 'elementor/loaded' ) ) {
			CCJP_Popups::maybe_create_apply_popup();
		}

		flush_rewrite_rules();
		update_option( 'ccjp_version', CCJP_VERSION );
	}

	/**
	 * Deliberately non-destructive: only cleans up the rewrite-rules
	 * footprint. Does NOT remove the careers_manager role, strip
	 * Administrator's capabilities, delete Job/Application posts or
	 * taxonomy terms, delete acf-json files, or delete the /careers/ page.
	 * Reactivating must fully restore function with no data loss.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
