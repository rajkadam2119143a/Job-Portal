<?php
defined( 'ABSPATH' ) || exit;

/**
 * Creates and tracks the "Apply Popup" Elementor Pro Theme Builder popup
 * template — the Application Form widget embedded so the Apply Button can
 * open it as a modal instead of just scrolling to an anchor on the same
 * page. Safe if Elementor Pro's Popup module isn't available: creation
 * just no-ops, and the Apply Button widget falls back to its anchor/page
 * open methods either way.
 */
class CCJP_Popups {

	const OPTION = 'ccjp_apply_popup_id';

	public static function get_apply_popup_id() {
		return (int) get_option( self::OPTION );
	}

	public static function maybe_create_apply_popup() {
		if ( ! class_exists( '\ElementorPro\Modules\Popup\Document' ) ) {
			return;
		}

		$popup_id = self::get_apply_popup_id();
		if ( $popup_id && get_post( $popup_id ) ) {
			return;
		}

		// Reuse one if it already exists (e.g. created before the option
		// was recorded) rather than creating a duplicate.
		$existing = get_posts( array(
			'post_type'      => 'elementor_library',
			'meta_key'       => '_elementor_template_type', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => 'popup', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'post_status'    => 'any',
			'posts_per_page' => 1,
		) );

		if ( $existing ) {
			update_option( self::OPTION, $existing[0]->ID );
			return;
		}

		$post_id = wp_insert_post( array(
			'post_type'   => 'elementor_library',
			'post_title'  => __( 'Apply Popup', 'careers-portal' ),
			'post_status' => 'publish',
		) );

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return;
		}

		$field_id = 'ccjpapp1';
		$elements = array(
			array(
				'id'       => 'ccjpapc1',
				'elType'   => 'container',
				'settings' => array(),
				'elements' => array(
					array(
						'id'         => $field_id,
						'elType'     => 'widget',
						'widgetType' => 'ccjp-application-form',
						'settings'   => array( 'heading' => __( 'Apply for this Role', 'careers-portal' ) ),
						'elements'   => array(),
					),
				),
				'isInner'  => false,
			),
		);

		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $elements ) ) );
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_template_type', 'popup' );
		update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '' );
		update_post_meta( $post_id, '_elementor_page_settings', array(
			'popup_width'           => array( 'unit' => 'px', 'size' => 640 ),
			'popup_position'        => 'center center',
			'popup_overlay_close'   => 'yes',
			'popup_close_on_esc'    => 'yes',
			'popup_default_display' => 'this_device',
		) );

		wp_set_object_terms( $post_id, 'popup', 'elementor_library_type' );

		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			\Elementor\Core\Files\CSS\Post::create( $post_id )->update();
		}

		update_option( self::OPTION, $post_id );
	}
}
