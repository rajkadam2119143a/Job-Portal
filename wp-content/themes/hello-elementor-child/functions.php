<?php
/**
 * Hello Elementor Child theme functions — BCCL Careers redesign.
 *
 * This theme now owns full page structure (header, footer, front page,
 * job archive/single, "Our Legacy", and taxonomy-grid templates) instead
 * of Elementor Pro's Theme Builder. Elementor and the Careers Portal
 * plugin's 18 widgets stay installed/active for any page the site owner
 * still wants to hand-build (About/Contact); they simply aren't used for
 * the templates this theme now provides files for. Nothing is deleted from
 * the database — any Elementor Theme Builder documents previously assigned
 * to header/footer/single/archive locations just become inactive, since
 * those locations are only ever invoked from inside the parent theme's own
 * header.php/footer.php/index.php, which this child theme now overrides.
 */

defined( 'ABSPATH' ) || exit;

define( 'BCCL_THEME_VERSION', '1.0.5' );
define( 'BCCL_THEME_DIR', get_stylesheet_directory() );
define( 'BCCL_THEME_URI', get_stylesheet_directory_uri() );

require_once BCCL_THEME_DIR . '/inc/bccl-content.php';

if ( ! function_exists( 'bccl_enqueue_assets' ) ) {
	function bccl_enqueue_assets() {
		wp_enqueue_style(
			'bccl-fonts',
			'https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;0,6..72,700;1,6..72,400;1,6..72,500&family=Open+Sans:wght@400;600;700&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'hello-elementor-child-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( 'hello-elementor-theme-style' ),
			wp_get_theme()->get( 'Version' )
		);

		/*
		 * The plugin's component CSS/JS normally only loads when an
		 * Elementor widget renders one of its components
		 * (CCJP_Widget_Base::enqueue_frontend_assets()). This theme calls
		 * the plugin's render/query classes directly, bypassing Elementor,
		 * so it has to enqueue the same assets itself. wp_enqueue_* is
		 * idempotent by handle, so this is harmless on any page where a
		 * widget also triggers the same enqueue.
		 */
		if ( defined( 'CCJP_PLUGIN_URL' ) ) {
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

		// Theme chrome + marketing sections. Loaded after ccjp-frontend so
		// it can reuse the same --ccjp-* design tokens defined there.
		wp_enqueue_style( 'bccl-site', BCCL_THEME_URI . '/assets/css/site.css', array( 'ccjp-frontend' ), BCCL_THEME_VERSION );
		wp_enqueue_script( 'bccl-site', BCCL_THEME_URI . '/assets/js/site.js', array(), BCCL_THEME_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'bccl_enqueue_assets', 20 );

/**
 * Fallback primary/footer nav so the header and footer read correctly
 * before a real menu is assigned in Appearance → Menus. `menu-1`/`menu-2`
 * are registered by the parent (Hello Elementor) theme but were never
 * assigned a menu, since Elementor's Theme Builder handled navigation
 * until now.
 */
function bccl_nav_fallback( $args = array() ) {
	$items = array(
		array( 'label' => __( 'Home', 'hello-elementor-child' ), 'url' => home_url( '/' ) ),
		array( 'label' => __( 'Our Legacy', 'hello-elementor-child' ), 'url' => class_exists( 'CCJP_Pages' ) ? CCJP_Pages::get_page_url( 'legacy' ) : '#' ),
		array( 'label' => __( 'Our Businesses', 'hello-elementor-child' ), 'url' => home_url( '/#businesses' ) ),
		array( 'label' => __( 'Life at BCCL', 'hello-elementor-child' ), 'url' => home_url( '/#life' ) ),
		array( 'label' => __( 'Careers Marketplace', 'hello-elementor-child' ), 'url' => get_post_type_archive_link( 'career_job' ) ?: '#' ),
	);

	$wrap_class = isset( $args['menu_class'] ) ? $args['menu_class'] : '';
	echo '<ul class="' . esc_attr( $wrap_class ) . '">';
	foreach ( $items as $item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
	}
	echo '</ul>';
}

/**
 * Real footer link columns, built from the plugin's auto-created pages
 * (CCJP_Pages) and the job archive, rather than hardcoded URLs/IDs.
 */
function bccl_footer_links( $slugs ) {
	if ( ! class_exists( 'CCJP_Pages' ) ) {
		return array();
	}
	$links = array();
	foreach ( $slugs as $slug => $label ) {
		$url = CCJP_Pages::get_page_url( $slug );
		if ( $url ) {
			$links[] = array( 'label' => $label, 'url' => $url );
		}
	}
	return $links;
}

/**
 * Force this theme's templates to win for the job single/archive and the
 * slug-keyed pages below.
 *
 * Elementor Pro's Theme Builder does NOT only inject itself from inside the
 * parent theme's own header.php/footer.php (as it does for those two
 * locations) — for "Single" and "Archive" conditions matching career_job it
 * hooks `template_include` directly, independent of theme cooperation, so
 * a plain archive-career_job.php/single-career_job.php file existing in
 * this theme is not enough by itself; Elementor's own callback still wins
 * on priority. Running this filter at PHP_INT_MAX guarantees it runs last
 * and overrides whatever Elementor decided. (No Theme Builder documents are
 * deleted — they simply stop being served for these locations.)
 */
add_filter(
	'template_include',
	function ( $template ) {
		if ( is_singular( 'career_job' ) ) {
			$found = locate_template( 'single-career_job.php' );
			if ( $found ) {
				return $found;
			}
		}

		if ( is_post_type_archive( 'career_job' ) ) {
			$found = locate_template( 'archive-career_job.php' );
			if ( $found ) {
				return $found;
			}
		}

		$slug_map = array(
			'legacy'      => 'page-legacy.php',
			'teams'       => 'page-teams.php',
			'departments' => 'page-departments.php',
			'locations'   => 'page-locations.php',
		);
		foreach ( $slug_map as $slug => $file ) {
			if ( is_page( $slug ) ) {
				$found = locate_template( $file );
				if ( $found ) {
					return $found;
				}
			}
		}

		return $template;
	},
	PHP_INT_MAX
);
