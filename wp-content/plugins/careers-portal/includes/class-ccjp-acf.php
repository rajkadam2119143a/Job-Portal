<?php
defined( 'ABSPATH' ) || exit;

/**
 * Points ACF's Local JSON store at this plugin's own acf-json/ folder, so
 * field groups created by hand in Custom Fields -> Add New stay editable
 * through the ACF admin UI while the JSON stays versioned with the plugin.
 *
 * Field groups themselves are NOT registered here via
 * acf_add_local_field_group() — they are built in wp-admin per the schema
 * documented in the plugin's Phase 1 plan, and ACF writes/reads the JSON
 * automatically once these filters point at the right folder.
 */
class CCJP_ACF {

	public function __construct() {
		add_filter( 'acf/settings/save_json', array( $this, 'save_json_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'load_json_paths' ) );

		add_action( 'admin_notices', array( $this, 'maybe_notice_acf_missing' ) );
		add_action( 'admin_notices', array( $this, 'maybe_notice_acf_json_not_writable' ) );
	}

	public function save_json_path( $path ) {
		return CCJP_PLUGIN_DIR . 'acf-json';
	}

	public function load_json_paths( $paths ) {
		$paths[] = CCJP_PLUGIN_DIR . 'acf-json';
		return $paths;
	}

	public function maybe_notice_acf_missing() {
		if ( class_exists( 'ACF' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p>' .
			esc_html__( 'Careers Portal requires Advanced Custom Fields to manage Job and Application field data. Please install and activate it.', 'careers-portal' ) .
			'</p></div>';
	}

	public function maybe_notice_acf_json_not_writable() {
		if ( ! get_option( 'ccjp_acf_json_not_writable' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-error"><p>' .
			esc_html__( 'Careers Portal: the acf-json folder inside the plugin is not writable. ACF field group changes will not sync to disk.', 'careers-portal' ) .
			'</p></div>';
	}
}
