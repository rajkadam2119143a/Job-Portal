<?php
defined( 'ABSPATH' ) || exit;

/**
 * Career Teams — public-facing listing of the career_category taxonomy.
 * ("Team" is this site's public label for what the data model calls
 * Category — same taxonomy, same admin-managed terms.)
 */
class CCJP_Widget_Categories extends CCJP_Widget_Taxonomy_Listing_Base {

	public function get_name() {
		return 'ccjp-teams';
	}

	public function get_title() {
		return __( 'Teams', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-product-categories';
	}

	public function get_keywords() {
		return array( 'careers', 'teams', 'categories' );
	}

	protected function taxonomy() {
		return 'career_category';
	}

	protected function default_heading() {
		return __( 'Explore Teams', 'careers-portal' );
	}
}
