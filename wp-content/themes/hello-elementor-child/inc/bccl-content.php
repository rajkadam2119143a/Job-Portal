<?php
/**
 * Static BCCL/Times Group brand content: the 180-year legacy timeline, the
 * business-unit grid, culture pillars/voices and "Future of Media" bets.
 *
 * There is no CPT/ACF/options-page data source for any of this in the
 * Careers Portal plugin (confirmed: no timeline, business-unit or culture
 * content model exists anywhere in the install) — it is real BCCL/Times
 * Group copy, not filler, kept here as plain arrays so it's easy to find
 * and edit in one place, and easy to promote to an ACF Options Page later
 * if the content team wants it editable from wp-admin.
 */

defined( 'ABSPATH' ) || exit;

function bccl_timeline() {
	return array(
		array(
			'yr'    => '1838',
			'title' => 'Bombay Times and Journal of Commerce begins',
			'mast'  => 'Bombay Times',
			'text'  => 'A twice-weekly paper starts publishing in Bombay — the earliest thread of what becomes Bennett, Coleman & Co. Ltd.',
		),
		array(
			'yr'    => '1850',
			'title' => 'Becomes a daily newspaper',
			'mast'  => 'Bombay Times',
			'text'  => 'Circulation and appetite grow, and the paper moves from a weekly rhythm to a daily one.',
		),
		array(
			'yr'    => '1861',
			'title' => 'Renamed The Times of India',
			'mast'  => 'The Times of India',
			'text'  => 'Following a merger with two other publications, the paper is renamed and takes on a national voice.',
		),
		array(
			'yr'    => '1892',
			'title' => 'Bennett, Coleman & Co. Ltd. acquires the paper',
			'mast'  => 'B, C & Co. Ltd.',
			'text'  => 'The company that carries the masthead today begins its stewardship of The Times of India.',
		),
		array(
			'yr'    => '1946',
			'title' => 'Passes into Indian ownership',
			'mast'  => 'The Times of India',
			'text'  => 'The paper enters a new era of Indian ownership that carries it through independence and the decades that follow.',
		),
		array(
			'yr'    => '20th c.',
			'title' => 'Diversification begins',
			'mast'  => 'ET · Mirchi · Filmfare',
			'text'  => 'The group expands beyond the front page — The Economic Times, Radio Mirchi, Filmfare and Femina all become part of the family.',
		),
		array(
			'yr'    => '2000s',
			'title' => 'Times Internet is founded',
			'mast'  => 'Times Internet',
			'text'  => 'The group goes digital-first, building a portfolio of apps and platforms that now reach hundreds of millions of Indians.',
		),
		array(
			'yr'    => 'Today',
			'title' => 'An AI-assisted newsroom',
			'mast'  => 'AI Newsroom',
			'text'  => 'Data science and AI sit inside the newsroom and product teams — shaping how stories are told, ranked and recommended.',
		),
	);
}

function bccl_businesses() {
	return array(
		array(
			'icon'  => 'print',
			'title' => 'Print & Journalism',
			'text'  => 'The Times of India, The Economic Times and regional dailies read by millions every morning.',
		),
		array(
			'icon'  => 'broadcast',
			'title' => 'Television & Broadcast',
			'text'  => 'Times Now and ET Now bring news and business to screens across the country, 24 hours a day.',
		),
		array(
			'icon'  => 'radio',
			'title' => 'Radio',
			'text'  => 'Radio Mirchi keeps India company on the commute, with one of the country\'s largest FM networks.',
		),
		array(
			'icon'  => 'digital',
			'title' => 'Digital & Technology',
			'text'  => 'Times Internet builds the apps, platforms and infrastructure behind the group\'s digital reach.',
		),
		array(
			'icon'  => 'magazine',
			'title' => 'Magazines & Entertainment',
			'text'  => 'Filmfare, Femina and Junglee Pictures shape culture, cinema and lifestyle conversation.',
		),
		array(
			'icon'  => 'events',
			'title' => 'Events & Experiences',
			'text'  => 'Times Litfest and large-format live IPs turn stories into stages people show up for.',
		),
		array(
			'icon'  => 'education',
			'title' => 'Education',
			'text'  => 'Bennett University extends the group\'s legacy of knowledge into the classroom.',
		),
		array(
			'icon'  => 'ai',
			'title' => 'Data, AI & Innovation',
			'text'  => 'Audience intelligence and applied AI power personalisation, product and newsroom tooling.',
		),
	);
}

function bccl_pillars() {
	return array(
		array(
			'icon'  => 'learning',
			'title' => 'Learning & Growth',
			'text'  => 'Structured mentorship, craft academies and tuition support for the skills you want next.',
		),
		array(
			'icon'  => 'innovation',
			'title' => 'Innovation Labs',
			'text'  => 'Small teams, real budgets, and room to prototype the next product or format.',
		),
		array(
			'icon'  => 'diversity',
			'title' => 'Diversity & Inclusion',
			'text'  => 'Employee resource groups and hiring practices built for a genuinely representative newsroom.',
		),
		array(
			'icon'  => 'mobility',
			'title' => 'Cross-functional Careers',
			'text'  => 'Move from editorial to product, from sales to data — internal mobility is the norm, not the exception.',
		),
	);
}

function bccl_voices() {
	return array(
		array(
			'initials' => 'AR',
			'bg'       => '#DB2F20',
			'quote'    => 'I moved from reporting to product design without leaving the building — that kind of mobility is rare.',
			'who'      => 'Employee Name',
			'role'     => 'Product Design, Times Internet',
		),
		array(
			'initials' => 'MS',
			'bg'       => '#202124',
			'quote'    => 'Our newsroom AI tools were built with journalists in the room from day one, not bolted on afterwards.',
			'who'      => 'Employee Name',
			'role'     => 'Applied AI, Times Group',
		),
		array(
			'initials' => 'KP',
			'bg'       => '#5F6368',
			'quote'    => '180 years of archives, and still the fastest-moving team I\'ve worked on.',
			'who'      => 'Employee Name',
			'role'     => 'Correspondent, The Times of India',
		),
	);
}

function bccl_future() {
	return array(
		array(
			'n'     => '01',
			'title' => 'AI-assisted newsroom tools',
			'text'  => 'From transcription to translation to story recommendations — AI as a colleague, not a replacement.',
		),
		array(
			'n'     => '02',
			'title' => 'Product & data-led decisions',
			'text'  => 'Every major editorial and product call is informed by audience data at national scale.',
		),
		array(
			'n'     => '03',
			'title' => 'New storytelling formats',
			'text'  => 'Audio, vertical video and interactive formats built for how India actually consumes media today.',
		),
		array(
			'n'     => '04',
			'title' => 'Digital transformation at scale',
			'text'  => 'Modernising a 180-year-old institution\'s technology stack, one platform at a time.',
		),
	);
}

/**
 * Small hand-drawn 24x24 line icons (stroke, currentColor) — no icon-font
 * dependency, matching the set already used by CCJP_Job_Card_Renderer.
 */
function bccl_icon( $name ) {
	$paths = array(
		'print'      => '<path d="M6 9V4h12v5"/><rect x="4" y="9" width="16" height="8" rx="1.5"/><rect x="7" y="14" width="10" height="6"/>',
		'broadcast'  => '<circle cx="12" cy="12" r="2.4"/><path d="M8.5 8.5a5 5 0 0 0 0 7"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/><path d="M5.5 5.5a9.5 9.5 0 0 0 0 13"/><path d="M18.5 5.5a9.5 9.5 0 0 1 0 13"/>',
		'radio'      => '<circle cx="12" cy="14.5" r="4"/><path d="M12 10.5V6"/><circle cx="12" cy="6" r="1"/><path d="M4 20h16"/>',
		'digital'    => '<rect x="3" y="4" width="18" height="12" rx="1.5"/><line x1="8" y1="20" x2="16" y2="20"/><line x1="12" y1="16" x2="12" y2="20"/>',
		'magazine'   => '<rect x="4" y="3" width="16" height="18" rx="1.5"/><line x1="7" y1="7.5" x2="17" y2="7.5"/><line x1="7" y1="11" x2="17" y2="11"/><line x1="7" y1="14.5" x2="13" y2="14.5"/>',
		'events'     => '<path d="M4 21v-7a8 8 0 0 1 16 0v7"/><line x1="4" y1="21" x2="20" y2="21"/><line x1="12" y1="6" x2="12" y2="3"/>',
		'education'  => '<path d="M2 9l10-5 10 5-10 5-10-5z"/><path d="M6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/>',
		'ai'         => '<circle cx="12" cy="12" r="3"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/>',
		'learning'   => '<path d="M4 6l8-3 8 3-8 3-8-3z"/><path d="M4 6v6c0 2 3.6 4 8 4s8-2 8-4V6"/>',
		'innovation' => '<path d="M9 18h6"/><path d="M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.6.4 1 1.1 1 1.9V16h5v-.2c0-.8.4-1.5 1-1.9A6 6 0 0 0 12 3z"/>',
		'diversity'  => '<circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path d="M3 20c0-2.8 2.2-5 5-5s5 2.2 5 5"/><path d="M11 20c0-2.8 2.2-5 5-5s5 2.2 5 5"/>',
		'mobility'   => '<path d="M4 12h16"/><path d="M14 6l6 6-6 6"/><path d="M10 6L4 12l6 6"/>',
		'pin'        => '<path d="M12 21s-6.5-6-6.5-11A6.5 6.5 0 0 1 18.5 10c0 5-6.5 11-6.5 11z"/><circle cx="12" cy="10" r="2.2"/>',
		'briefcase'  => '<rect x="3" y="7" width="18" height="12" rx="1.5"/><path d="M8 7V5.5A1.5 1.5 0 0 1 9.5 4h5A1.5 1.5 0 0 1 16 5.5V7"/>',
		'clock'      => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'arrow'      => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
		'menu'       => '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>',
		'close'      => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
		'search'     => '<circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[ $name ] . '</svg>';
}

/**
 * Reads a filter param that may arrive as a single value (search-bar
 * dropdowns/chips, e.g. ?location=mumbai) or as a checkbox group
 * (?job_type[]=full-time&job_type[]=contract, which PHP already collects
 * into $_GET['job_type'] = array(...)) — CCJP_Job_Query::query() accepts
 * either form transparently for taxonomy args and for experience.
 */
function bccl_read_filter_param( $key ) {
	if ( ! isset( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}
	$raw = wp_unslash( $_GET[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( is_array( $raw ) ) {
		return array_values( array_filter( array_map( 'sanitize_text_field', $raw ), 'strlen' ) );
	}
	return sanitize_text_field( $raw );
}

/**
 * Shared "grid of real taxonomy cards" section used by the Teams,
 * Departments and Locations pages — name, description and live open-role
 * count all come from CCJP_Taxonomy_Card_Renderer, so these pages always
 * match whatever terms actually exist in this install.
 */
function bccl_render_taxonomy_grid( $taxonomy, $heading, $intro ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		)
	);
	?>
	<section class="bccl-section--tight">
		<div class="bccl-container">
			<div class="bccl-section-head">
				<span class="bccl-eyebrow"><?php esc_html_e( 'Careers Marketplace', 'hello-elementor-child' ); ?></span>
				<h1 class="bccl-display" style="font-size:clamp(28px,4vw,44px);"><?php echo esc_html( $heading ); ?></h1>
				<p><?php echo esc_html( $intro ); ?></p>
			</div>
			<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
				<div class="ccjp-taxonomy-listing__grid ccjp-job-listing__grid ccjp-category-grid" style="--ccjp-columns:3; --ccjp-gap:16px;">
					<?php
					foreach ( $terms as $term ) {
						echo CCJP_Taxonomy_Card_Renderer::render( $term ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</div>
			<?php else : ?>
				<p class="bccl-lede"><?php esc_html_e( 'No entries yet — check back soon.', 'hello-elementor-child' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

