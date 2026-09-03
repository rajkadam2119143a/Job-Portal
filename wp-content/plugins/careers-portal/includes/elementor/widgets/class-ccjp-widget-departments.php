<?php
defined( 'ABSPATH' ) || exit;

/**
 * Career Departments — public-facing listing of the career_department
 * taxonomy.
 */
class CCJP_Widget_Departments extends CCJP_Widget_Taxonomy_Listing_Base {

	public function get_name() {
		return 'ccjp-departments';
	}

	public function get_title() {
		return __( 'Departments', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-product-categories';
	}

	public function get_keywords() {
		return array( 'careers', 'departments' );
	}

	protected function taxonomy() {
		return 'career_department';
	}

	protected function default_heading() {
		return __( 'Browse by Department', 'careers-portal' );
	}
}
