<?php
/**
 * BCCL Careers homepage. Always used for the site's front page regardless
 * of the Settings → Reading configuration (front-page.php has top priority
 * in the template hierarchy for is_front_page()).
 */

defined( 'ABSPATH' ) || exit;

get_header();

$timeline   = bccl_timeline();
$businesses = bccl_businesses();
$pillars    = bccl_pillars();
$voices     = bccl_voices();
$future     = bccl_future();
$jobs_url   = get_post_type_archive_link( 'career_job' );
$legacy_url = class_exists( 'CCJP_Pages' ) ? CCJP_Pages::get_page_url( 'legacy' ) : '';

$featured_query = null;
$total_open     = 0;
if ( class_exists( 'CCJP_Job_Query' ) ) {
	$featured_query = CCJP_Job_Query::query(
		array(
			'source'         => 'featured',
			'posts_per_page' => 3,
		)
	);
	if ( ! $featured_query->have_posts() ) {
		$featured_query = CCJP_Job_Query::query(
			array(
				'source'         => 'all',
				'order'          => 'newest',
				'posts_per_page' => 3,
			)
		);
	}
	$total_open = (int) CCJP_Job_Query::query(
		array(
			'source'         => 'all',
			'posts_per_page' => -1,
		)
	)->found_posts;
}
?>

<div class="bccl-hero">
	<div class="bccl-container bccl-hero-grid">
		<div class="bccl-hero-copy bccl-reveal">
			<span class="bccl-eyebrow"><?php esc_html_e( 'Bennett, Coleman & Co. Ltd. — The Times Group', 'hello-elementor-child' ); ?></span>
			<h1 class="bccl-display bccl-display--lg">
				<?php esc_html_e( "180+ years of shaping India's story.", 'hello-elementor-child' ); ?>
				<em><?php esc_html_e( 'Join us in writing its next chapter.', 'hello-elementor-child' ); ?></em>
			</h1>
			<p class="bccl-lede"><?php esc_html_e( 'From the first press run in 1838 to AI-assisted newsrooms today, Bennett, Coleman & Co. Ltd. informs, entertains and connects India — in print, on screen, on air and online. Build what comes next, with us.', 'hello-elementor-child' ); ?></p>
			<div class="bccl-hero-ctas">
				<a href="<?php echo esc_url( $jobs_url ? $jobs_url : '#' ); ?>" class="bccl-btn bccl-btn-primary"><?php esc_html_e( 'Explore open roles', 'hello-elementor-child' ); ?></a>
				<a href="<?php echo esc_url( $legacy_url ? $legacy_url : '#' ); ?>" class="bccl-btn bccl-btn-secondary"><?php esc_html_e( 'Discover our legacy', 'hello-elementor-child' ); ?></a>
			</div>
			<div class="bccl-hero-stats">
				<div class="bccl-stat"><b data-count="180" data-suffix="+">0</b><span><?php esc_html_e( 'Years in Indian media', 'hello-elementor-child' ); ?></span></div>
				<div class="bccl-stat"><b data-count="9">0</b><span><?php esc_html_e( 'Businesses under one roof', 'hello-elementor-child' ); ?></span></div>
				<div class="bccl-stat"><b data-count="<?php echo esc_attr( max( $total_open, 0 ) ); ?>">0</b><span><?php esc_html_e( 'Open roles right now', 'hello-elementor-child' ); ?></span></div>
				<div class="bccl-stat"><b data-count="1" data-suffix="st">0</b><span><?php esc_html_e( 'Newsroom built for the AI era', 'hello-elementor-child' ); ?></span></div>
			</div>
		</div>

		<div class="bccl-collage bccl-reveal" aria-hidden="true">
			<div class="bccl-collage-card bccl-card-a">
				<div class="mast">The Times of India</div>
				<div class="rule"></div>
				<div class="lines"><span></span><span></span><span></span></div>
			</div>
			<div class="bccl-collage-card bccl-card-b">
				<div class="mast">Economic Times</div>
				<div class="rule"></div>
				<div class="lines"><span></span><span></span><span></span></div>
			</div>
			<div class="bccl-collage-card bccl-card-c">
				<span class="chip"><i></i>LIVE · TIMES INTERNET</span>
				<div class="mast" style="font-size:20px;">AI Newsroom<br />Console</div>
				<div class="rule"></div>
				<div class="lines"><span></span><span></span><span></span></div>
			</div>
		</div>
	</div>

	<div class="bccl-ticker-wrap" role="marquee" aria-label="<?php esc_attr_e( 'Highlights from our history', 'hello-elementor-child' ); ?>">
		<div class="bccl-ticker">
			<?php foreach ( array_merge( $timeline, $timeline ) as $t ) : // duplicated once for a seamless CSS loop ?>
				<span><b><?php echo esc_html( $t['yr'] ); ?></b> <?php echo esc_html( $t['title'] ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<section class="bccl-section" id="legacy-teaser">
	<div class="bccl-container">
		<div class="bccl-section-head bccl-reveal">
			<span class="bccl-eyebrow"><?php esc_html_e( 'Our Legacy', 'hello-elementor-child' ); ?></span>
			<h2><?php esc_html_e( 'A newsroom for every era.', 'hello-elementor-child' ); ?></h2>
			<p><?php esc_html_e( 'Since 1838, this organisation has reinvented how India gets its news — on paper, on air, on screen and now, in code. A quick look at a very long story.', 'hello-elementor-child' ); ?></p>
		</div>
		<div class="bccl-legacy-teaser-row">
			<?php foreach ( array( $timeline[0], $timeline[2], $timeline[6], $timeline[7] ) as $t ) : ?>
				<div class="bccl-legacy-mini bccl-reveal">
					<div class="yr"><?php echo esc_html( $t['yr'] ); ?></div>
					<h4><?php echo esc_html( $t['title'] ); ?></h4>
					<p><?php echo esc_html( $t['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<div style="margin-top:32px;">
			<a href="<?php echo esc_url( $legacy_url ? $legacy_url : '#' ); ?>" class="bccl-link-arrow">
				<?php esc_html_e( 'Explore the full 180-year timeline', 'hello-elementor-child' ); ?>
				<?php echo bccl_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</div>
</section>

<section class="bccl-section bccl-section--alt" id="businesses">
	<div class="bccl-container">
		<div class="bccl-section-head bccl-reveal">
			<span class="bccl-eyebrow"><?php esc_html_e( 'What We Build', 'hello-elementor-child' ); ?></span>
			<h2><?php esc_html_e( 'One group. Nine businesses. A single mission.', 'hello-elementor-child' ); ?></h2>
			<p><?php esc_html_e( 'Every masthead, channel, app and stage we run exists to help India read, watch, listen and decide better. Explore where your craft fits.', 'hello-elementor-child' ); ?></p>
		</div>
		<div class="bccl-biz-grid">
			<?php foreach ( $businesses as $b ) : ?>
				<div class="bccl-biz-card bccl-reveal">
					<div class="bccl-biz-icon"><?php echo bccl_icon( $b['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<h4><?php echo esc_html( $b['title'] ); ?></h4>
					<p><?php echo esc_html( $b['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="bccl-section" id="life">
	<div class="bccl-container">
		<div class="bccl-section-head bccl-reveal">
			<span class="bccl-eyebrow"><?php esc_html_e( 'Life at BCCL', 'hello-elementor-child' ); ?></span>
			<h2><?php esc_html_e( 'Where journalists, technologists and storytellers build together.', 'hello-elementor-child' ); ?></h2>
			<p><?php esc_html_e( 'A newsroom culture, a product culture and a creative studio culture — sharing one building, one mission and, most days, one very good canteen.', 'hello-elementor-child' ); ?></p>
		</div>
		<div class="bccl-pillar-grid">
			<?php foreach ( $pillars as $p ) : ?>
				<div class="bccl-pillar bccl-reveal">
					<div class="bccl-pillar-icon"><?php echo bccl_icon( $p['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<h4><?php echo esc_html( $p['title'] ); ?></h4>
					<p><?php echo esc_html( $p['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="bccl-voice-row">
			<?php foreach ( $voices as $v ) : ?>
				<div class="bccl-voice-card bccl-reveal">
					<p class="bccl-voice-quote">&ldquo;<?php echo esc_html( $v['quote'] ); ?>&rdquo;</p>
					<div class="bccl-voice-who">
						<span class="bccl-voice-avatar" style="background:<?php echo esc_attr( $v['bg'] ); ?>;"><?php echo esc_html( $v['initials'] ); ?></span>
						<div><b><?php echo esc_html( $v['who'] ); ?></b><span><?php echo esc_html( $v['role'] ); ?></span></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<span class="bccl-caption"><?php esc_html_e( 'Employee stories shown are illustrative placeholders — swap in verified voices from your People & Comms team before launch.', 'hello-elementor-child' ); ?></span>
	</div>
</section>

<section class="bccl-section bccl-section--alt">
	<div class="bccl-container bccl-ct-grid">
		<div class="bccl-ct-copy bccl-reveal">
			<span class="bccl-eyebrow"><?php esc_html_e( 'Creative Meets Technology', 'hello-elementor-child' ); ?></span>
			<h2><?php esc_html_e( 'Journalism, design, data and AI — in the same room.', 'hello-elementor-child' ); ?></h2>
			<p style="color:var(--ccjp-color-slate);"><?php esc_html_e( "A story doesn't stop at the byline. Reporters work beside product designers, data scientists and ML engineers to decide how a story is told, ranked, recommended and read — across a portfolio of apps used by hundreds of millions of Indians.", 'hello-elementor-child' ); ?></p>
			<ul class="bccl-ct-list">
				<li><?php esc_html_e( 'Editorial judgement, shaped by product thinking', 'hello-elementor-child' ); ?></li>
				<li><?php esc_html_e( 'Design systems built for scale and for trust', 'hello-elementor-child' ); ?></li>
				<li><?php esc_html_e( 'Data science and AI embedded inside the newsroom, not bolted on', 'hello-elementor-child' ); ?></li>
				<li><?php esc_html_e( 'Marketing and advertising craft built on real audience insight', 'hello-elementor-child' ); ?></li>
			</ul>
		</div>
		<div class="bccl-orbit-wrap bccl-reveal" aria-hidden="true">
			<svg viewBox="0 0 400 400">
				<circle cx="200" cy="200" r="150" fill="none" stroke="var(--ccjp-color-mist)" stroke-width="1" stroke-dasharray="2 6" />
				<g class="bccl-orbit-center">
					<circle cx="200" cy="200" r="54" fill="var(--ccjp-color-ink)" />
					<text x="200" y="196" text-anchor="middle" font-size="13">Times</text>
					<text x="200" y="212" text-anchor="middle" font-size="13">Group</text>
				</g>
				<g class="bccl-orbit-node"><circle cx="200" cy="50" r="38" fill="#fff" stroke="var(--ccjp-color-mist)" stroke-width="1.5" /><text x="200" y="46" text-anchor="middle">Journalism</text><text x="200" y="58" text-anchor="middle">&amp; Editorial</text></g>
				<g class="bccl-orbit-node"><circle cx="330" cy="130" r="38" fill="#fff" stroke="var(--ccjp-color-mist)" stroke-width="1.5" /><text x="330" y="126" text-anchor="middle">Product</text><text x="330" y="138" text-anchor="middle">&amp; UX</text></g>
				<g class="bccl-orbit-node"><circle cx="330" cy="270" r="38" fill="#fff" stroke="var(--ccjp-color-mist)" stroke-width="1.5" /><text x="330" y="266" text-anchor="middle">Data</text><text x="330" y="278" text-anchor="middle">Science</text></g>
				<g class="bccl-orbit-node"><circle cx="200" cy="350" r="38" fill="#fff" stroke="var(--ccjp-color-mist)" stroke-width="1.5" /><text x="200" y="346" text-anchor="middle">AI &amp;</text><text x="200" y="358" text-anchor="middle">ML</text></g>
				<g class="bccl-orbit-node"><circle cx="70" cy="270" r="38" fill="#fff" stroke="var(--ccjp-color-mist)" stroke-width="1.5" /><text x="70" y="266" text-anchor="middle">Engineering</text></g>
				<g class="bccl-orbit-node"><circle cx="70" cy="130" r="38" fill="#fff" stroke="var(--ccjp-color-mist)" stroke-width="1.5" /><text x="70" y="126" text-anchor="middle">Marketing</text><text x="70" y="138" text-anchor="middle">&amp; Sales</text></g>
			</svg>
		</div>
	</div>
</section>

<section class="bccl-section bccl-section--dark">
	<div class="bccl-container">
		<div class="bccl-section-head bccl-reveal">
			<span class="bccl-eyebrow bccl-eyebrow--onDark"><?php esc_html_e( 'Future of Media', 'hello-elementor-child' ); ?></span>
			<h2><?php esc_html_e( 'Built for how India will read, watch and listen next.', 'hello-elementor-child' ); ?></h2>
			<p><?php esc_html_e( "The next 180 years start with what we ship this year. Four bets we're building around.", 'hello-elementor-child' ); ?></p>
		</div>
		<div class="bccl-future-grid">
			<?php foreach ( $future as $f ) : ?>
				<div class="bccl-future-card bccl-reveal">
					<span class="bccl-future-num"><?php echo esc_html( $f['n'] ); ?></span>
					<h4><?php echo esc_html( $f['title'] ); ?></h4>
					<p><?php echo esc_html( $f['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="bccl-section" id="marketplace-teaser">
	<div class="bccl-container">
		<div class="bccl-section-head bccl-reveal">
			<span class="bccl-eyebrow"><?php esc_html_e( 'Careers Marketplace', 'hello-elementor-child' ); ?></span>
			<h2><?php esc_html_e( 'Find where you fit.', 'hello-elementor-child' ); ?></h2>
			<p><?php esc_html_e( 'Search every open role, across every business and city, in one place.', 'hello-elementor-child' ); ?></p>
		</div>

		<form class="bccl-search-panel bccl-reveal" action="<?php echo esc_url( $jobs_url ? $jobs_url : '#' ); ?>" method="get">
			<div class="bccl-field">
				<label for="teaserQ"><?php esc_html_e( 'Role or keyword', 'hello-elementor-child' ); ?></label>
				<input type="text" id="teaserQ" name="keyword" placeholder="<?php esc_attr_e( 'e.g. Product Designer, Correspondent, Data Scientist', 'hello-elementor-child' ); ?>" />
			</div>
			<div class="bccl-field">
				<label for="teaserLoc"><?php esc_html_e( 'Location', 'hello-elementor-child' ); ?></label>
				<select id="teaserLoc" name="location">
					<option value=""><?php esc_html_e( 'All locations', 'hello-elementor-child' ); ?></option>
					<?php foreach ( get_terms( array( 'taxonomy' => 'career_location', 'hide_empty' => false ) ) as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="bccl-field">
				<label for="teaserDept"><?php esc_html_e( 'Business unit', 'hello-elementor-child' ); ?></label>
				<select id="teaserDept" name="department">
					<option value=""><?php esc_html_e( 'All businesses', 'hello-elementor-child' ); ?></option>
					<?php foreach ( get_terms( array( 'taxonomy' => 'career_department', 'hide_empty' => false ) ) as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<button type="submit" class="bccl-btn bccl-btn-primary"><?php esc_html_e( 'Search roles', 'hello-elementor-child' ); ?></button>
		</form>

		<div class="ccjp-job-listing__grid" style="--ccjp-columns:3; --ccjp-gap:16px; margin-top:32px;">
			<?php
			if ( $featured_query && $featured_query->have_posts() ) {
				while ( $featured_query->have_posts() ) {
					$featured_query->the_post();
					echo CCJP_Job_Card_Renderer::render( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				wp_reset_postdata();
			}
			?>
		</div>

		<div style="margin-top:32px;">
			<a href="<?php echo esc_url( $jobs_url ? $jobs_url : '#' ); ?>" class="bccl-link-arrow">
				<?php esc_html_e( 'View all open roles', 'hello-elementor-child' ); ?>
				<?php echo bccl_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</div>
</section>

<section class="bccl-section--tight">
	<div class="bccl-container">
		<div class="bccl-join-panel bccl-reveal">
			<div class="bccl-join-copy">
				<span class="bccl-eyebrow bccl-eyebrow--onDark"><?php esc_html_e( 'Stay Connected', 'hello-elementor-child' ); ?></span>
				<h3><?php esc_html_e( 'Not the right role today? Join our talent community.', 'hello-elementor-child' ); ?></h3>
				<p><?php esc_html_e( "We'll let you know when a role that matches your interests opens up. No spam, just relevant openings.", 'hello-elementor-child' ); ?></p>
			</div>
			<form class="bccl-join-form" id="bcclJoinForm">
				<label for="bcclJoinEmail" class="screen-reader-text"><?php esc_html_e( 'Email address', 'hello-elementor-child' ); ?></label>
				<input type="email" id="bcclJoinEmail" placeholder="you@example.com" required />
				<button type="submit" class="bccl-btn bccl-btn-primary"><?php esc_html_e( 'Notify me', 'hello-elementor-child' ); ?></button>
			</form>
		</div>
		<p class="bccl-form-msg" id="bcclJoinMsg" role="status"><?php esc_html_e( "Thanks — we'll be in touch when a matching role opens.", 'hello-elementor-child' ); ?></p>
	</div>
</section>

<?php get_footer(); ?>
