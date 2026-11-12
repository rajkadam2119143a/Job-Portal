<?php
/**
 * "Our Legacy" page — the 180-year interactive timeline. Loaded via the
 * `template_include` filter in functions.php for the auto-created `legacy`
 * page (see CCJP_Pages::required_pages()).
 */

defined( 'ABSPATH' ) || exit;

get_header();

$timeline = bccl_timeline();
$jobs_url = get_post_type_archive_link( 'career_job' );
?>

<div class="bccl-legacy-hero">
	<div class="bccl-container">
		<span class="bccl-eyebrow bccl-eyebrow--onDark"><?php esc_html_e( 'Our Legacy', 'hello-elementor-child' ); ?></span>
		<h1 class="bccl-display" style="color:#fff; margin-top:16px;"><?php esc_html_e( '180+ years of ink, airwaves and code.', 'hello-elementor-child' ); ?></h1>
		<p class="bccl-lede" style="margin-top:16px;"><?php esc_html_e( "Bombay's first daily became India's most-read English newspaper, then a nine-business media and technology group. Scroll through the story — and the people who are writing its next lines.", 'hello-elementor-child' ); ?></p>
	</div>
</div>

<div class="bccl-timeline-progress"><i id="bcclTimelineProgressBar"></i></div>

<section class="bccl-section--tight">
	<div class="bccl-container">
		<div class="bccl-timeline-hint">
			<span><?php esc_html_e( 'Scroll to explore the timeline', 'hello-elementor-child' ); ?></span>
			<?php echo bccl_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div class="bccl-timeline-scroller" id="bcclTimelineScroller">
			<?php foreach ( $timeline as $t ) : ?>
				<div class="bccl-timeline-node bccl-reveal">
					<div class="bccl-timeline-yr"><?php echo esc_html( $t['yr'] ); ?></div>
					<h3><?php echo esc_html( $t['title'] ); ?></h3>
					<p><?php echo esc_html( $t['text'] ); ?></p>
					<div class="bccl-timeline-card">
						<div>
							<div class="mast"><?php echo esc_html( $t['mast'] ); ?></div>
							<div class="rule"></div>
							<div class="lines"><span></span><span></span></div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="bccl-footnote"><?php esc_html_e( "Milestones above are compiled from BCCL's public historical record and are provided here as representative content — please verify exact dates and details against the Times Group archives before treating this page as an official record.", 'hello-elementor-child' ); ?></p>

		<div class="bccl-legacy-numbers">
			<div class="bccl-stat bccl-reveal"><b data-count="1838">0</b><span><?php esc_html_e( 'The year Bombay Times began publishing', 'hello-elementor-child' ); ?></span></div>
			<div class="bccl-stat bccl-reveal"><b data-count="1861">0</b><span><?php esc_html_e( 'The year it was renamed The Times of India', 'hello-elementor-child' ); ?></span></div>
			<div class="bccl-stat bccl-reveal"><b data-count="9">0</b><span><?php esc_html_e( 'Businesses the group runs today', 'hello-elementor-child' ); ?></span></div>
			<div class="bccl-stat bccl-reveal"><b data-count="<?php echo esc_attr( gmdate( 'Y' ) ); ?>">0</b><span><?php esc_html_e( 'An AI-assisted newsroom, still being written', 'hello-elementor-child' ); ?></span></div>
		</div>
	</div>
</section>

<section class="bccl-section bccl-section--dark">
	<div class="bccl-container" style="text-align:center; display:flex; flex-direction:column; align-items:center; gap:24px;">
		<h2 style="max-width:640px;"><?php esc_html_e( 'Every era added a new way to tell India\'s story. AI and data are simply the latest chapter — and you could help write it.', 'hello-elementor-child' ); ?></h2>
		<a href="<?php echo esc_url( $jobs_url ? $jobs_url : '#' ); ?>" class="bccl-btn bccl-btn-primary"><?php esc_html_e( 'Explore open roles', 'hello-elementor-child' ); ?></a>
	</div>
</section>

<?php get_footer(); ?>
