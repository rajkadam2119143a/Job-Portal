<?php
defined( 'ABSPATH' ) || exit;

class CCJP_Autoloader {

	public static function register() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	public static function autoload( $class ) {
		if ( strpos( $class, 'CCJP_' ) !== 0 ) {
			return;
		}

		$file = 'class-' . strtolower( str_replace( '_', '-', $class ) ) . '.php';

		$dirs = array(
			CCJP_PLUGIN_DIR . 'includes/',
			CCJP_PLUGIN_DIR . 'includes/elementor/',
			CCJP_PLUGIN_DIR . 'includes/elementor/widgets/',
			CCJP_PLUGIN_DIR . 'includes/elementor/dynamic-tags/',
			CCJP_PLUGIN_DIR . 'includes/elementor/render/',
		);

		foreach ( $dirs as $dir ) {
			if ( file_exists( $dir . $file ) ) {
				require_once $dir . $file;
				return;
			}
		}
	}
}
