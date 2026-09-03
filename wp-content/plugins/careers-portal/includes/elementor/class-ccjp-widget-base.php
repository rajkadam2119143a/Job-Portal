<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Widget_Base;

/**
 * Shared behaviour for every Careers Portal Elementor widget: the common
 * category, the functional (never visual) frontend asset enqueue, and the
 * "what job are we in the context of" resolution used by every widget that
 * needs a current-job (Job Meta, Apply Button, Related Jobs, Job Listing's
 * current_* query sources).
 */
abstract class CCJP_Widget_Base extends Widget_Base {

	public function get_categories() {
		return array( 'careers-portal' );
	}

	public function get_current_job_id() {
		return CCJP_Job_Query::resolve_reference_post_id();
	}

	public function is_editing() {
		return CCJP_Job_Query::is_elementor_editing();
	}

	/**
	 * Enqueues the plugin's namespaced functional frontend assets. Cheap to
	 * call from every widget's render() — wp_enqueue_* is idempotent, so
	 * a page using five Careers widgets still only loads the files once.
	 */
	protected function enqueue_frontend_assets() {
		wp_enqueue_style( 'ccjp-frontend', CCJP_PLUGIN_URL . 'assets/frontend/css/ccjp-frontend.css', array(), CCJP_VERSION );
		wp_enqueue_script( 'ccjp-frontend', CCJP_PLUGIN_URL . 'assets/frontend/js/ccjp-frontend.js', array(), CCJP_VERSION, true );
		wp_localize_script(
			'ccjp-frontend',
			'CCJP_DATA',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ccjp_public' ),
			)
		);
	}
}
