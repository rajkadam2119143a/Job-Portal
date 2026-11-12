<?php
/**
 * Plugin Name:       Careers Portal
 * Description:       Foundational data layer (custom post types, taxonomies,
 *                     post statuses, capabilities and ACF field schema) for
 *                     the Careers/Job Portal system. The visual frontend is
 *                     built entirely in Elementor Pro on top of this plugin.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Text Domain:       careers-portal
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'CCJP_VERSION', '1.2.5' );
define( 'CCJP_MIN_WP', '6.0' );
define( 'CCJP_MIN_PHP', '7.4' );
define( 'CCJP_PLUGIN_FILE', __FILE__ );
define( 'CCJP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCJP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CCJP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Version guard before anything else loads. WP's own "Requires at
 * least"/"Requires PHP" headers only block activation from the Plugins
 * screen — they don't protect against manual file upload or multisite
 * network activation on an old host, so we check again here.
 */
if ( version_compare( PHP_VERSION, CCJP_MIN_PHP, '<' )
	|| version_compare( $GLOBALS['wp_version'], CCJP_MIN_WP, '<' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>' .
				esc_html__( 'Careers Portal requires PHP 7.4+ and WordPress 6.0+. The plugin has not been loaded.', 'careers-portal' ) .
				'</p></div>';
		}
	);
	return;
}

require_once CCJP_PLUGIN_DIR . 'includes/class-ccjp-autoloader.php';
CCJP_Autoloader::register();

register_activation_hook( __FILE__, array( 'CCJP_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CCJP_Activator', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'CCJP_Plugin', 'instance' ) );
