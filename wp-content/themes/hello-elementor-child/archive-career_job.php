<?php
/**
 * Careers Marketplace — the real job archive. Replaces the Elementor Theme
 * Builder archive template. Search/filter state is read straight from
 * $_GET using the exact same field names CCJP_Widget_Job_Listing's
 * resolve_query_args() reads (keyword, category, department, location,
 * job_type, work_mode, experience, date_posted, ccjp_page), and results
 * are wrapped in the same data-ccjp-listing / .ccjp-job-listing__grid
 * markup contract the plugin's own AJAX search/load-more JS
 * (ccjp-frontend.js) already knows how to intercept — so search and
 * filtering work with zero new JavaScript.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$args = array(
	'source'         => 'all',
	'posts_per_page' => 12,
	'paged'          => isset( $_GET['ccjp_page'] ) ? absint( $_GET['ccjp_page'] ) : 1, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	'order'          => ( isset( $_GET['sort'] ) && 'oldest' === $_GET['sort'] ) ? 'oldest' : 'newest', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
);

foreach ( CCJP_Job_Query::TAXONOMIES as $key => $taxonomy ) {
	$args[ $key ] = bccl_read_filter_param( $key );
}
$args['keyword']     = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$args['experience']  = bccl_read_filter_param( 'experience' );
$args['date_posted'] = isset( $_GET['date_posted'] ) ? absint( $_GET['date_posted'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$query = CCJP_Job_Query::query( $args );

$categories  = get_terms( array( 'taxonomy' => 'career_category', 'hide_empty' => false ) );
$job_types   = get_terms( array( 'taxonomy' => 'career_job_type', 'hide_empty' => false ) );
$work_modes  = get_terms( array( 'taxonomy' => 'career_work_mode', 'hide_empty' => false ) );
$locations   = get_terms( array( 'taxonomy' => 'career_location', 'hide_empty' => false ) );
$departments = get_terms( array( 'taxonomy' => 'career_department', 'hide_empty' => false ) );
$experiences = array( '0-2 Years', '2-5 Years', '5-8 Years', '8+ Years' );

$base_url = get_post_type_archive_link( 'career_job' );
?>

<div class="bccl-careers-hero">
	<div class="bccl-container">
		<span class="bccl-eyebrow"><?php esc_html_e( 'Careers Marketplace', 'hello-elementor-child' ); ?></span>
		<h1 style="margin-top:16px; font-family:var(--ccjp-font-display); font-weight:600; font-size:clamp(28px,4vw,40px); line-height:1.15;">
			<?php esc_html_e( 'Search every open role, across every business.', 'hello-elementor-child' ); ?>
		</h1>
		<p class="bccl-lede" style="margin-top:16px;"><?php esc_html_e( 'Real filters, real business units, no dead-end listings.', 'hello-elementor-child' ); ?></p>

		<form id="bcclCareersFilterForm" class="bccl-search-panel ccjp-job-search-form" method="get" action="<?php echo esc_url( $base_url ); ?>" style="margin-top:32px; grid-template-columns:1.6fr 1fr auto;">
			<div class="bccl-field">
				<label for="careersQ"><?php esc_html_e( 'Role or keyword', 'hello-elementor-child' ); ?></label>
				<input type="text" id="careersQ" name="keyword" value="<?php echo esc_attr( $args['keyword'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. Software Engineer, Sub-editor, Sales', 'hello-elementor-child' ); ?>" />
			</div>
			<div class="bccl-field">
				<label for="careersUnit"><?php esc_html_e( 'Business unit', 'hello-elementor-child' ); ?></label>
				<select id="careersUnit" name="department">
					<option value=""><?php esc_html_e( 'All businesses', 'hello-elementor-child' ); ?></option>
					<?php foreach ( $departments as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $args['department'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<button type="submit" class="bccl-btn bccl-btn-primary"><?php esc_html_e( 'Search', 'hello-elementor-child' ); ?></button>
		</form>
		<p class="bccl-caption" style="margin-top:12px;"><?php esc_html_e( 'Looking for a specific city? Use the Location filter in the sidebar below.', 'hello-elementor-child' ); ?></p>

		<?php
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_args = array_diff_key( wp_unslash( $_GET ), array_flip( array( 'category', 'ccjp_page' ) ) );
			?>
			<div class="bccl-chip-row" id="bcclChipRow">
				<?php foreach ( $categories as $term ) : ?>
					<?php
					$is_active = ( $args['category'] === $term->slug );
					$chip_url  = $is_active
						? add_query_arg( $current_args, $base_url )
						: add_query_arg( array_merge( $current_args, array( 'category' => $term->slug ) ), $base_url );
					?>
					<a
						href="<?php echo esc_url( $chip_url ); ?>"
						class="bccl-chip<?php echo $is_active ? ' is-active' : ''; ?>"
						data-chip-slug="<?php echo esc_attr( $term->slug ); ?>"
					><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php /* Real, form-associated (not nested) so it rides along with every other filter's AJAX submission — see bcclChipRow's click handler in site.js. */ ?>
			<input type="hidden" name="category" id="bcclCategoryInput" form="bcclCareersFilterForm" value="<?php echo esc_attr( $args['category'] ); ?>" />
		<?php endif; ?>
	</div>
</div>

<section class="bccl-section--tight">
	<div class="bccl-container">
		<div class="bccl-careers-layout">
			<aside class="bccl-filter-card" aria-label="<?php esc_attr_e( 'Filter roles', 'hello-elementor-child' ); ?>">
				<h4><?php esc_html_e( 'Refine results', 'hello-elementor-child' ); ?></h4>

				<?php if ( ! empty( $locations ) && ! is_wp_error( $locations ) ) : ?>
					<fieldset class="bccl-filter-group">
						<legend><?php esc_html_e( 'Location', 'hello-elementor-child' ); ?></legend>
						<?php foreach ( $locations as $term ) : ?>
							<label class="bccl-check-row">
								<input type="checkbox" name="location[]" value="<?php echo esc_attr( $term->slug ); ?>" form="bcclCareersFilterForm" <?php checked( in_array( $term->slug, (array) $args['location'], true ) ); ?> />
								<?php echo esc_html( $term->name ); ?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				<?php endif; ?>

				<fieldset class="bccl-filter-group">
					<legend><?php esc_html_e( 'Experience level', 'hello-elementor-child' ); ?></legend>
					<?php foreach ( $experiences as $exp ) : ?>
						<label class="bccl-check-row">
							<input type="checkbox" name="experience[]" value="<?php echo esc_attr( $exp ); ?>" form="bcclCareersFilterForm" <?php checked( in_array( $exp, (array) $args['experience'], true ) ); ?> />
							<?php echo esc_html( $exp ); ?>
						</label>
					<?php endforeach; ?>
				</fieldset>

				<?php if ( ! empty( $job_types ) && ! is_wp_error( $job_types ) ) : ?>
					<fieldset class="bccl-filter-group">
						<legend><?php esc_html_e( 'Employment type', 'hello-elementor-child' ); ?></legend>
						<?php foreach ( $job_types as $term ) : ?>
							<label class="bccl-check-row">
								<input type="checkbox" name="job_type[]" value="<?php echo esc_attr( $term->slug ); ?>" form="bcclCareersFilterForm" <?php checked( in_array( $term->slug, (array) $args['job_type'], true ) ); ?> />
								<?php echo esc_html( $term->name ); ?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				<?php endif; ?>

				<?php if ( ! empty( $work_modes ) && ! is_wp_error( $work_modes ) ) : ?>
					<fieldset class="bccl-filter-group">
						<legend><?php esc_html_e( 'Work mode', 'hello-elementor-child' ); ?></legend>
						<?php foreach ( $work_modes as $term ) : ?>
							<label class="bccl-check-row">
								<input type="checkbox" name="work_mode[]" value="<?php echo esc_attr( $term->slug ); ?>" form="bcclCareersFilterForm" <?php checked( in_array( $term->slug, (array) $args['work_mode'], true ) ); ?> />
								<?php echo esc_html( $term->name ); ?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				<?php endif; ?>

				<div class="bccl-field">
					<label for="filterDatePosted"><?php esc_html_e( 'Date posted', 'hello-elementor-child' ); ?></label>
					<select id="filterDatePosted" name="date_posted" form="bcclCareersFilterForm" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit();">
						<option value=""><?php esc_html_e( 'Any time', 'hello-elementor-child' ); ?></option>
						<option value="1" <?php selected( (string) $args['date_posted'], '1' ); ?>><?php esc_html_e( 'Last 24 hours', 'hello-elementor-child' ); ?></option>
						<option value="7" <?php selected( (string) $args['date_posted'], '7' ); ?>><?php esc_html_e( 'Last 7 days', 'hello-elementor-child' ); ?></option>
						<option value="14" <?php selected( (string) $args['date_posted'], '14' ); ?>><?php esc_html_e( 'Last 14 days', 'hello-elementor-child' ); ?></option>
						<option value="30" <?php selected( (string) $args['date_posted'], '30' ); ?>><?php esc_html_e( 'Last 30 days', 'hello-elementor-child' ); ?></option>
					</select>
				</div>

				<a href="<?php echo esc_url( $base_url ); ?>" class="bccl-link-arrow"><?php esc_html_e( 'Clear all filters', 'hello-elementor-child' ); ?></a>
			</aside>

			<div>
				<div class="bccl-results-head">
					<p class="bccl-results-count"><b id="bcclResultsCount"><?php echo esc_html( number_format_i18n( $query->found_posts ) ); ?></b> <?php esc_html_e( 'roles found', 'hello-elementor-child' ); ?></p>
					<div class="bccl-sort-field">
						<label for="sortSelect"><?php esc_html_e( 'Sort', 'hello-elementor-child' ); ?></label>
						<select id="sortSelect" name="sort" form="bcclCareersFilterForm" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit();">
							<option value="newest" <?php selected( $args['order'], 'newest' ); ?>><?php esc_html_e( 'Newest', 'hello-elementor-child' ); ?></option>
							<option value="oldest" <?php selected( $args['order'], 'oldest' ); ?>><?php esc_html_e( 'Oldest', 'hello-elementor-child' ); ?></option>
						</select>
					</div>
				</div>

				<div
					id="ccjp-listing-careers"
					class="ccjp-job-listing"
					data-ccjp-listing="1"
					data-ccjp-settings="<?php echo esc_attr( wp_json_encode( $args ) ); ?>"
					data-ccjp-pagination="numbered"
					data-ccjp-found="<?php echo esc_attr( (int) $query->found_posts ); ?>"
				>
					<div class="ccjp-job-listing__grid ccjp-job-list--rows" style="--ccjp-gap:16px;">
						<?php
						if ( $query->have_posts() ) {
							while ( $query->have_posts() ) {
								$query->the_post();
								echo CCJP_Job_Card_Renderer::render( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							wp_reset_postdata();
						}
						?>
					</div>

					<?php
					/*
					 * Always present in the DOM (not a PHP if/else on
					 * $query->have_posts()) so the AJAX success handler in
					 * ccjp-frontend.js — which only ever toggles an
					 * existing element's display, it can't conjure one up —
					 * has something to reveal when a filter change (not
					 * just the initial page load) drops the result count to
					 * zero. Only the inline display:none differs based on
					 * the current server-rendered state.
					 */
					?>
					<div class="bccl-empty-state ccjp-job-listing__empty" <?php echo $query->have_posts() ? 'style="display:none;"' : ''; ?>>
						<?php echo bccl_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<p><strong><?php esc_html_e( 'No roles match those filters yet.', 'hello-elementor-child' ); ?></strong><br /><?php esc_html_e( 'Try clearing a filter, or join our talent community to hear about new roles first.', 'hello-elementor-child' ); ?></p>
					</div>

					<?php if ( $query->max_num_pages > 1 ) : ?>
						<nav class="ccjp-job-listing__pagination" style="margin-top:32px;">
							<?php
							echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								array(
									'base'      => add_query_arg( 'ccjp_page', '%#%' ),
									'format'    => '',
									'current'   => max( 1, $args['paged'] ),
									'total'     => $query->max_num_pages,
									'add_args'  => false,
									'prev_text' => __( 'Prev', 'hello-elementor-child' ),
									'next_text' => __( 'Next', 'hello-elementor-child' ),
								)
							);
							?>
						</nav>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="bccl-pathway-grid">
			<div class="bccl-pathway-card">
				<h4><?php esc_html_e( 'Early Careers', 'hello-elementor-child' ); ?></h4>
				<p><?php esc_html_e( "Campus hires, trainee correspondents and design/engineering apprenticeships — start here if you're early in your journey.", 'hello-elementor-child' ); ?></p>
			</div>
			<div class="bccl-pathway-card">
				<h4><?php esc_html_e( 'Experienced Professionals', 'hello-elementor-child' ); ?></h4>
				<p><?php esc_html_e( "Bring your craft — editorial, product, data, sales or studio — into India's largest media and technology network.", 'hello-elementor-child' ); ?></p>
			</div>
			<div class="bccl-pathway-card">
				<h4><?php esc_html_e( 'Internal Mobility', 'hello-elementor-child' ); ?></h4>
				<p><?php esc_html_e( 'Already at BCCL? Most of our senior leaders moved teams at least once. Talk to your manager about what\'s next.', 'hello-elementor-child' ); ?></p>
			</div>
		</div>

		<div class="bccl-resource-row">
			<a class="bccl-resource-pill" href="#"><?php esc_html_e( 'Application FAQs', 'hello-elementor-child' ); ?></a>
			<a class="bccl-resource-pill" href="#"><?php esc_html_e( 'Interview tips', 'hello-elementor-child' ); ?></a>
			<a class="bccl-resource-pill" href="#"><?php esc_html_e( 'Accessibility & accommodations', 'hello-elementor-child' ); ?></a>
			<a class="bccl-resource-pill" href="<?php echo esc_url( class_exists( 'CCJP_Pages' ) ? CCJP_Pages::get_page_url( 'contact' ) : '#' ); ?>"><?php esc_html_e( 'Contact recruiting', 'hello-elementor-child' ); ?></a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
