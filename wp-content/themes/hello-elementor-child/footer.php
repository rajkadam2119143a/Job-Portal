<?php
/**
 * Custom BCCL Careers footer — replaces the parent theme's footer.php
 * entirely; see header.php for why the Elementor Theme Builder footer
 * template is no longer invoked here.
 */

defined( 'ABSPATH' ) || exit;

$bccl_jobs_url = get_post_type_archive_link( 'career_job' );

$bccl_careers_links = bccl_footer_links(
	array(
		'legacy'      => __( 'Our Legacy', 'hello-elementor-child' ),
		'departments' => __( 'Our Businesses', 'hello-elementor-child' ),
		'teams'       => __( 'Life at BCCL', 'hello-elementor-child' ),
	)
);

$bccl_candidate_links = bccl_footer_links(
	array(
		'application-success' => __( 'Application FAQs', 'hello-elementor-child' ),
		'contact'             => __( 'Contact Recruiting', 'hello-elementor-child' ),
	)
);
?>
</main>

<footer class="bccl-footer">
	<div class="bccl-container">
		<div class="bccl-footer-top">
			<div class="bccl-footer-brand">
				<span class="bccl-wordmark-mark" style="background:var(--ccjp-color-paper); color:var(--ccjp-color-ink);" aria-hidden="true">T</span>
				<p><?php esc_html_e( "Bennett, Coleman & Co. Ltd. \u{2014} India's most influential media and technology organisation, publisher of The Times of India since 1838.", 'hello-elementor-child' ); ?></p>
				<div class="bccl-social-row">
					<a href="#" aria-label="LinkedIn"><?php echo bccl_icon( 'briefcase' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</div>
			</div>

			<div class="bccl-footer-col">
				<h4><?php esc_html_e( 'Careers', 'hello-elementor-child' ); ?></h4>
				<ul>
					<?php if ( $bccl_jobs_url ) : ?>
						<li><a href="<?php echo esc_url( $bccl_jobs_url ); ?>"><?php esc_html_e( 'Careers Marketplace', 'hello-elementor-child' ); ?></a></li>
					<?php endif; ?>
					<?php foreach ( $bccl_careers_links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="bccl-footer-col">
				<h4><?php esc_html_e( 'Explore BCCL', 'hello-elementor-child' ); ?></h4>
				<ul>
					<?php
					$bccl_about_url = class_exists( 'CCJP_Pages' ) ? CCJP_Pages::get_page_url( 'about' ) : '';
					?>
					<li><a href="<?php echo esc_url( $bccl_about_url ? $bccl_about_url : '#' ); ?>"><?php esc_html_e( 'About the Group', 'hello-elementor-child' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Our Newsrooms', 'hello-elementor-child' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Editorial Standards', 'hello-elementor-child' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Sustainability', 'hello-elementor-child' ); ?></a></li>
				</ul>
			</div>

			<div class="bccl-footer-col">
				<h4><?php esc_html_e( 'Candidates', 'hello-elementor-child' ); ?></h4>
				<ul>
					<?php foreach ( $bccl_candidate_links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
					<li><a href="#"><?php esc_html_e( 'Interview Process', 'hello-elementor-child' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Accessibility', 'hello-elementor-child' ); ?></a></li>
				</ul>
			</div>

			<div class="bccl-footer-col">
				<h4><?php esc_html_e( 'Legal', 'hello-elementor-child' ); ?></h4>
				<ul>
					<li><a href="#"><?php esc_html_e( 'Privacy Policy', 'hello-elementor-child' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Terms of Use', 'hello-elementor-child' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Equal Opportunity Statement', 'hello-elementor-child' ); ?></a></li>
				</ul>
			</div>
		</div>

		<div class="bccl-footer-bottom">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Bennett, Coleman &amp; Co. Ltd. All rights reserved.</span>
			<div class="bccl-footer-legal">
				<a href="#"><?php esc_html_e( 'Sitemap', 'hello-elementor-child' ); ?></a>
				<a href="#"><?php esc_html_e( 'Cookie Preferences', 'hello-elementor-child' ); ?></a>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
