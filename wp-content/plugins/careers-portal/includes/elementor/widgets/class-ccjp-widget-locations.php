<?php
defined( 'ABSPATH' ) || exit;

/**
 * Career Locations — public-facing listing of the career_location
 * taxonomy.
 */
class CCJP_Widget_Locations extends CCJP_Widget_Taxonomy_Listing_Base {

	public function get_name() {
		return 'ccjp-locations';
	}

	public function get_title() {
		return __( 'Locations', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_keywords() {
		return array( 'careers', 'locations' );
	}

	protected function taxonomy() {
		return 'career_location';
	}

	protected function default_heading() {
		return __( 'Find Opportunities Near You', 'careers-portal' );
	}
}
