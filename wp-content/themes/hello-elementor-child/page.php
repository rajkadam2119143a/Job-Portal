<?php
/**
 * Generic page template (About, Contact, Application Submitted, and any
 * other plain WP page) — simple centered content wrapper in the new visual
 * system.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="bccl-section--tight">
		<div class="bccl-container" style="max-width:760px;">
			<span class="bccl-eyebrow"><?php esc_html_e( 'BCCL Careers', 'hello-elementor-child' ); ?></span>
			<h1 class="bccl-display" style="font-size:clamp(28px,4vw,44px); margin-top:16px;"><?php the_title(); ?></h1>
			<div class="bccl-job-detail-body" style="margin-top:24px;">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
