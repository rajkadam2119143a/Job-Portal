<?php
defined( 'ABSPATH' ) || exit;

/**
 * Patches the WP-admin gaps around custom post statuses on the two Careers
 * Portal post types (the classic Publish-box dropdown, Quick/Bulk Edit,
 * list-table view-link counts and state badges never surface non-core
 * statuses by default), adds a Status column on Applications, and shows an
 * informational (non-fatal) notice if Elementor Pro isn't active.
 */
class CCJP_Admin {

	const JOB_POST_TYPE         = 'career_job';
	const APPLICATION_POST_TYPE = 'career_application';

	public function __construct() {
		add_action( 'admin_footer-post.php', array( $this, 'inject_status_dropdown_js' ) );
		add_action( 'admin_footer-post-new.php', array( $this, 'inject_status_dropdown_js' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_list_table_assets' ) );

		add_filter( 'views_edit-' . self::JOB_POST_TYPE, array( $this, 'job_status_views' ) );
		add_filter( 'views_edit-' . self::APPLICATION_POST_TYPE, array( $this, 'application_status_views' ) );

		add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );

		add_filter( 'manage_' . self::APPLICATION_POST_TYPE . '_posts_columns', array( $this, 'application_columns' ) );
		add_action( 'manage_' . self::APPLICATION_POST_TYPE . '_posts_custom_column', array( $this, 'render_application_column' ), 10, 2 );

		add_action( 'admin_notices', array( $this, 'maybe_notice_elementor_pro_missing' ) );
	}

	/**
	 * Gap #1: the classic Edit screen's Publish-box status <select> only
	 * ever renders WP's own built-in statuses. Injected in the footer since
	 * that's after the Publish metabox has already rendered the select.
	 */
	public function inject_status_dropdown_js() {
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->post_type, array( self::JOB_POST_TYPE, self::APPLICATION_POST_TYPE ), true ) ) {
			return;
		}

		if ( self::JOB_POST_TYPE === $screen->post_type ) {
			$statuses    = array( 'job_closed' => __( 'Closed', 'careers-portal' ) );
			$default_key = null;
		} else {
			$statuses    = CCJP_Post_Statuses::application_statuses();
			$default_key = CCJP_Post_Statuses::default_application_status();
		}

		?>
		<script>
		( function () {
			var select = document.getElementById( 'post_status' );
			if ( ! select ) {
				return;
			}
			var statuses = <?php echo wp_json_encode( $statuses ); ?>;
			Object.keys( statuses ).forEach( function ( slug ) {
				if ( ! select.querySelector( 'option[value="' + slug + '"]' ) ) {
					var opt = document.createElement( 'option' );
					opt.value = slug;
					opt.textContent = statuses[ slug ];
					select.appendChild( opt );
				}
			} );
			<?php if ( $default_key ) : ?>
			if ( select.value === 'draft' || select.value === 'auto-draft' ) {
				select.value = <?php echo wp_json_encode( $default_key ); ?>;
				var display = document.getElementById( 'post-status-display' );
				if ( display ) {
					display.textContent = <?php echo wp_json_encode( $statuses[ $default_key ] ); ?>;
				}
			}
			<?php endif; ?>
		} )();
		</script>
		<?php
	}

	/**
	 * Gap #2: Quick Edit and Bulk Edit on edit.php read from their own
	 * select[name="_status"] node(s) inside the hidden #inline-edit
	 * template — injecting options into that template once (rather than
	 * per opened row) is enough, since WP clones the template's DOM
	 * (including our added <option> elements) into each row.
	 */
	public function enqueue_list_table_assets( $hook ) {
		if ( 'edit.php' !== $hook ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->post_type, array( self::JOB_POST_TYPE, self::APPLICATION_POST_TYPE ), true ) ) {
			return;
		}

		wp_enqueue_style( 'ccjp-admin', CCJP_PLUGIN_URL . 'assets/admin/css/ccjp-admin.css', array(), CCJP_VERSION );
		wp_enqueue_script( 'ccjp-admin', CCJP_PLUGIN_URL . 'assets/admin/js/ccjp-admin.js', array(), CCJP_VERSION, true );

		$statuses = self::JOB_POST_TYPE === $screen->post_type
			? array( 'job_closed' => __( 'Closed', 'careers-portal' ) )
			: CCJP_Post_Statuses::application_statuses();

		wp_localize_script(
			'ccjp-admin',
			'CCJP_ADMIN',
			array(
				'postType' => $screen->post_type,
				'statuses' => $statuses,
			)
		);
	}

	/**
	 * Gap #3: edit.php's "All | Published | Draft…" view links don't
	 * surface custom statuses even though wp_count_posts() already counts
	 * them — add our own links with correct hrefs and counts.
	 */
	public function job_status_views( $views ) {
		return $this->append_status_views( $views, self::JOB_POST_TYPE, array( 'job_closed' => __( 'Closed', 'careers-portal' ) ) );
	}

	public function application_status_views( $views ) {
		return $this->append_status_views( $views, self::APPLICATION_POST_TYPE, CCJP_Post_Statuses::application_statuses() );
	}

	private function append_status_views( $views, $post_type, $statuses ) {
		$counts         = wp_count_posts( $post_type );
		$current_status = isset( $_GET['post_status'] ) ? sanitize_key( wp_unslash( $_GET['post_status'] ) ) : '';

		foreach ( $statuses as $slug => $label ) {
			$count = isset( $counts->$slug ) ? (int) $counts->$slug : 0;
			if ( 0 === $count && $current_status !== $slug ) {
				continue;
			}

			$url   = add_query_arg(
				array(
					'post_type'   => $post_type,
					'post_status' => $slug,
				),
				admin_url( 'edit.php' )
			);
			$class = $current_status === $slug ? ' class="current"' : '';

			$views[ $slug ] = sprintf(
				'<a href="%1$s"%2$s>%3$s <span class="count">(%4$d)</span></a>',
				esc_url( $url ),
				$class,
				esc_html( $label ),
				$count
			);
		}

		return $views;
	}

	/**
	 * Gap #4: no visual state badge next to the title for non-core
	 * statuses (unlike WP's own "— Draft", "— Pending" badges).
	 */
	public function add_display_post_states( $states, $post ) {
		if ( ! in_array( $post->post_type, array( self::JOB_POST_TYPE, self::APPLICATION_POST_TYPE ), true ) ) {
			return $states;
		}

		$core_statuses = array( 'publish', 'draft', 'pending', 'private', 'future', 'trash', 'auto-draft' );
		if ( in_array( $post->post_status, $core_statuses, true ) || isset( $states[ $post->post_status ] ) ) {
			return $states;
		}

		$status_object = get_post_status_object( $post->post_status );
		if ( $status_object ) {
			$states[ $post->post_status ] = $status_object->label;
		}

		return $states;
	}

	/**
	 * Color-coded Status column on the Applications list table, since
	 * without it the pipeline stage isn't visible without opening each row.
	 */
	public function application_columns( $columns ) {
		$new = array();
		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;
			if ( 'title' === $key ) {
				$new['ccjp_status'] = __( 'Status', 'careers-portal' );
			}
		}
		return $new;
	}

	public function render_application_column( $column, $post_id ) {
		if ( 'ccjp_status' !== $column ) {
			return;
		}

		$status_slug   = get_post_status( $post_id );
		$status_object = get_post_status_object( $status_slug );
		$label         = $status_object ? $status_object->label : $status_slug;

		printf(
			'<span class="ccjp-status ccjp-status--%1$s">%2$s</span>',
			esc_attr( $status_slug ),
			esc_html( $label )
		);
	}

	public function maybe_notice_elementor_pro_missing() {
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		// Only nag on Careers Portal screens, not every wp-admin page.
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->post_type, array( self::JOB_POST_TYPE, self::APPLICATION_POST_TYPE ), true ) ) {
			return;
		}

		echo '<div class="notice notice-info is-dismissible"><p>' .
			esc_html__( 'Elementor Pro is recommended for the Careers Portal frontend. Install and activate it before building the public-facing pages.', 'careers-portal' ) .
			'</p></div>';
	}
}
