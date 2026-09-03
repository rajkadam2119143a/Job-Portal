<?php
defined( 'ABSPATH' ) || exit;

class CCJP_Post_Statuses {

	/**
	 * Application pipeline, in order. Keys are the registered post_status
	 * slugs; the first entry is the default status for a newly created
	 * career_application post.
	 */
	public static function application_statuses() {
		return array(
			'app_new'          => __( 'New', 'careers-portal' ),
			'app_under_review' => __( 'Under Review', 'careers-portal' ),
			'app_shortlisted'  => __( 'Shortlisted', 'careers-portal' ),
			'app_interview'    => __( 'Interview', 'careers-portal' ),
			'app_offer'        => __( 'Offer', 'careers-portal' ),
			'app_hired'        => __( 'Hired', 'careers-portal' ),
			'app_rejected'     => __( 'Rejected', 'careers-portal' ),
		);
	}

	public static function default_application_status() {
		$statuses = array_keys( self::application_statuses() );
		return $statuses[0];
	}

	public static function register() {
		self::register_job_status();
		self::register_application_statuses();
	}

	private static function register_job_status() {
		register_post_status(
			'job_closed',
			array(
				'label'                     => _x( 'Closed', 'job status', 'careers-portal' ),
				'label_count'               => _n_noop(
					'Closed <span class="count">(%s)</span>',
					'Closed <span class="count">(%s)</span>',
					'careers-portal'
				),
				// Closed jobs keep resolving at their permalink (no dead links
				// for anyone who bookmarked/shared it) but are excluded from
				// default listings/search.
				'public'                    => true,
				'publicly_queryable'        => true,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'internal'                  => false,
			)
		);
	}

	private static function register_application_statuses() {
		foreach ( self::application_statuses() as $slug => $label ) {
			register_post_status(
				$slug,
				array(
					'label'                     => $label,
					// translators: %s is the number of applications in this status.
					'label_count'               => _n_noop(
						$label . ' <span class="count">(%s)</span>',
						$label . ' <span class="count">(%s)</span>',
						'careers-portal'
					),
					'public'                    => false,
					'publicly_queryable'        => false,
					'exclude_from_search'       => true,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					// Must stay false or WP hides the status from admin dropdowns entirely.
					'internal'                  => false,
				)
			);
		}
	}
}
