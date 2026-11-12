<?php
/**
 * Custom BCCL Careers header — replaces the parent (Hello Elementor)
 * theme's header.php entirely. Because this file never calls
 * elementor_theme_do_location( 'header' ), whatever Elementor Pro Theme
 * Builder document used to be assigned to the "header" location is no
 * longer invoked here; it stays saved in the database, simply unused.
 */

defined( 'ABSPATH' ) || exit;

$bccl_jobs_url = get_post_type_archive_link( 'career_job' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="bccl-skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'hello-elementor-child' ); ?></a>

<header class="ccjp-header-row" id="bcclHeader">
	<div class="bccl-header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bccl-wordmark" aria-label="<?php esc_attr_e( 'BCCL Careers, home', 'hello-elementor-child' ); ?>">
			<span class="bccl-wordmark-mark" aria-hidden="true">T</span>
			<span class="bccl-wordmark-text"><b><?php esc_html_e( 'BCCL Careers', 'hello-elementor-child' ); ?></b><span><?php esc_html_e( 'The Times Group', 'hello-elementor-child' ); ?></span></span>
		</a>

		<nav aria-label="<?php esc_attr_e( 'Primary', 'hello-elementor-child' ); ?>">
			<?php
			if ( has_nav_menu( 'menu-1' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'container'      => false,
						'menu_class'     => 'bccl-nav__list',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				bccl_nav_fallback( array( 'menu_class' => 'bccl-nav__list' ) );
			}
			?>
		</nav>

		<div class="bccl-header-actions">
			<a class="bccl-btn bccl-btn-primary" style="padding:10px 18px; font-size:13px;" href="<?php echo esc_url( $bccl_jobs_url ? $bccl_jobs_url : '#' ); ?>">
				<?php esc_html_e( 'Open Roles', 'hello-elementor-child' ); ?>
			</a>
			<button class="bccl-icon-btn bccl-hamburger" id="bcclOpenMobileNav" aria-label="<?php esc_attr_e( 'Open menu', 'hello-elementor-child' ); ?>" aria-expanded="false" aria-controls="bcclMobileNav">
				<?php echo bccl_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
	</div>
</header>

<div class="bccl-mobile-nav" id="bcclMobileNav">
	<div class="bccl-mobile-nav-top">
		<span class="bccl-wordmark-text">
			<b style="color:#fff;"><?php esc_html_e( 'BCCL Careers', 'hello-elementor-child' ); ?></b>
			<span style="color:var(--ccjp-color-ash);"><?php esc_html_e( 'The Times Group', 'hello-elementor-child' ); ?></span>
		</span>
		<button class="bccl-icon-btn" id="bcclCloseMobileNav" aria-label="<?php esc_attr_e( 'Close menu', 'hello-elementor-child' ); ?>">
			<?php echo bccl_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>
	<?php
	if ( has_nav_menu( 'menu-1' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
	} else {
		bccl_nav_fallback();
	}
	?>
	<a class="bccl-btn bccl-btn-primary bccl-btn-block" href="<?php echo esc_url( $bccl_jobs_url ? $bccl_jobs_url : '#' ); ?>">
		<?php esc_html_e( 'View Open Roles', 'hello-elementor-child' ); ?>
	</a>
</div>

<main id="main">
