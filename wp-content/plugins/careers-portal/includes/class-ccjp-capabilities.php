<?php
defined( 'ABSPATH' ) || exit;

class CCJP_Capabilities {

	const ROLE_SLUG = 'careers_manager';

	/**
	 * The single authoritative list of every custom capability this plugin
	 * defines. Used both at activation and re-synced on every version bump,
	 * since add_role() silently no-ops if the role already exists (so a
	 * future update that adds a capability would otherwise never reach
	 * existing installs).
	 */
	public static function all_caps() {
		return array(
			// Top-level gate capability (frontend admin panel, later phases).
			'manage_careers',

			// career_job (13 auto-derived + 1 explicit create override).
			'edit_career_job',
			'read_career_job',
			'delete_career_job',
			'edit_career_jobs',
			'edit_others_career_jobs',
			'delete_career_jobs',
			'publish_career_jobs',
			'read_private_career_jobs',
			'delete_private_career_jobs',
			'delete_published_career_jobs',
			'delete_others_career_jobs',
			'edit_private_career_jobs',
			'edit_published_career_jobs',
			'create_career_jobs',

			// career_application (13 auto-derived + 1 explicit create override).
			'edit_career_application',
			'read_career_application',
			'delete_career_application',
			'edit_career_applications',
			'edit_others_career_applications',
			'delete_career_applications',
			'publish_career_applications',
			'read_private_career_applications',
			'delete_private_career_applications',
			'delete_published_career_applications',
			'delete_others_career_applications',
			'edit_private_career_applications',
			'edit_published_career_applications',
			'create_career_applications',

			// Taxonomy management, scoped away from manage_categories.
			'manage_career_terms',
			'assign_career_terms',
		);
	}

	public function __construct() {
		add_action( 'admin_init', array( $this, 'maybe_sync_caps_on_version_bump' ) );
	}

	public function maybe_sync_caps_on_version_bump() {
		if ( get_option( 'ccjp_caps_version' ) !== CCJP_VERSION ) {
			self::grant_all( self::ensure_role() );
			self::grant_all( get_role( 'administrator' ) );
			update_option( 'ccjp_caps_version', CCJP_VERSION );
		}
	}

	/**
	 * Creates the Careers Manager role if it doesn't already exist.
	 * add_role() is a no-op when the slug already exists, so this only
	 * actually runs on first-ever install.
	 */
	public static function ensure_role() {
		$role = get_role( self::ROLE_SLUG );
		if ( ! $role ) {
			add_role(
				self::ROLE_SLUG,
				__( 'Careers Manager', 'careers-portal' ),
				array(
					'read'         => true,
					'upload_files' => true,
				)
			);
			$role = get_role( self::ROLE_SLUG );
		}
		return $role;
	}

	/**
	 * WP_Role::add_cap() is always idempotent, so this is safe to call on
	 * every activation and every version-bump sync.
	 */
	public static function grant_all( $role ) {
		if ( ! $role ) {
			return;
		}
		foreach ( self::all_caps() as $cap ) {
			$role->add_cap( $cap );
		}
	}
}
