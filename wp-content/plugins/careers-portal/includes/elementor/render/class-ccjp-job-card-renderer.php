<?php
defined( 'ABSPATH' ) || exit;

/**
 * Shared job-card markup used by the Job Listing, Job Card and Featured
 * Jobs widgets, so all three stay visually and structurally identical and
 * Elementor style controls only ever need to target one set of classes
 * (.ccjp-job-card, .ccjp-job-card__*).
 */
class CCJP_Job_Card_Renderer {

	/**
	 * @param int   $job_id
	 * @param array $options {
	 *     @type string $view_button_text
	 * }
	 */
	public static function render( $job_id, array $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'view_button_text' => __( 'View & apply', 'careers-portal' ),
			)
		);

		$permalink = get_permalink( $job_id );
		$title     = get_the_title( $job_id );
		$meta      = CCJP_Job_Data::get_meta_items( $job_id );
		$featured  = CCJP_Job_Data::is_featured( $job_id );
		$closed    = CCJP_Job_Data::is_closed( $job_id );
		$job_code  = (string) CCJP_Job_Data::get( $job_id, 'ccjp_job_id' );
		$category  = CCJP_Job_Data::get_primary_term_name( $job_id, 'career_category' );
		$skills    = CCJP_Job_Data::get_list_field( $job_id, 'ccjp_skills' );

		// department / location·work-mode / job type / experience / relative date --
		// exactly the 5 items the reference design's JobMeta component shows, in
		// this order, never configurable per instance.
		$location_bits = array_filter( array( $meta['location']['value'], $meta['work_mode']['value'] ) );
		$meta_row      = array(
			'department' => $meta['department']['value'],
			'location'   => implode( ' · ', $location_bits ),
			'job_type'   => $meta['job_type']['value'],
			'experience' => $meta['experience']['value'],
			'posted'     => self::relative_date( get_post_time( 'U', true, $job_id ) ),
		);

		ob_start();
		?>
		<article class="ccjp-job-card<?php echo $featured ? ' ccjp-job-card--featured' : ''; ?><?php echo $closed ? ' ccjp-job-card--closed' : ''; ?>">
			<div class="ccjp-job-card__head">
				<div class="ccjp-job-card__heading-group">
					<div class="ccjp-job-card__title-row">
						<h3 class="ccjp-job-card__title">
							<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
						</h3>
						<?php if ( $featured ) : ?>
							<span class="ccjp-job-card__badge"><?php esc_html_e( 'Featured', 'careers-portal' ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( '' !== $job_code || '' !== $category ) : ?>
						<p class="ccjp-job-card__subtitle"><?php echo esc_html( trim( $job_code . ( '' !== $job_code && '' !== $category ? ' · ' : '' ) . $category ) ); ?></p>
					<?php endif; ?>
				</div>
				<a class="ccjp-job-card__link-arrow" href="<?php echo esc_url( $permalink ); ?>">
					<?php echo esc_html( $options['view_button_text'] ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
				</a>
			</div>

			<ul class="ccjp-job-card__meta">
				<?php foreach ( $meta_row as $key => $value ) : ?>
					<?php if ( '' === $value ) { continue; } ?>
					<li class="ccjp-job-card__meta-item ccjp-job-card__meta-item--<?php echo esc_attr( $key ); ?>">
						<?php echo self::meta_icon( $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php echo esc_html( $value ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( ! empty( $skills ) ) : ?>
				<ul class="ccjp-job-card__tags">
					<?php foreach ( array_slice( $skills, 0, 5 ) as $skill ) : ?>
						<li><?php echo esc_html( $skill ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</article>
		<?php
		return ob_get_clean();
	}

	/**
	 * Bucketed relative date matching the reference design exactly: "Today",
	 * "Yesterday", "N days ago" under a week, "Last week" under two, then
	 * weeks and finally months.
	 */
	private static function relative_date( $timestamp ) {
		$days = (int) round( ( time() - $timestamp ) / DAY_IN_SECONDS );
		if ( $days <= 0 ) {
			return __( 'Today', 'careers-portal' );
		}
		if ( 1 === $days ) {
			return __( 'Yesterday', 'careers-portal' );
		}
		if ( $days < 7 ) {
			/* translators: %d is a number of days. */
			return sprintf( __( '%d days ago', 'careers-portal' ), $days );
		}
		if ( $days < 14 ) {
			return __( 'Last week', 'careers-portal' );
		}
		if ( $days < 60 ) {
			/* translators: %d is a number of weeks. */
			return sprintf( __( '%d weeks ago', 'careers-portal' ), (int) round( $days / 7 ) );
		}
		/* translators: %d is a number of months. */
		return sprintf( __( '%d months ago', 'careers-portal' ), (int) round( $days / 30 ) );
	}

	/**
	 * Small hand-written glyphs for the meta row — no icon-font/library
	 * dependency, just enough to read as "department / location / etc" at
	 * a glance. Falls back to no icon for any key not listed here.
	 */
	private static function meta_icon( $key ) {
		$paths = array(
			'department' => '<rect x="4" y="3" width="16" height="18" rx="1" /><path d="M9 21v-4h6v4M9 7h1M9 11h1M14 7h1M14 11h1" />',
			'location'   => '<path d="M12 21s7-6.3 7-11.5A7 7 0 1 0 5 9.5C5 14.7 12 21 12 21z" /><circle cx="12" cy="9.5" r="2.5" />',
			'job_type'   => '<path d="M9 6V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V6" /><rect x="3" y="6" width="18" height="14" rx="2" /><path d="M3 12h18" />',
			'experience' => '<circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 3" />',
			'posted'     => '<rect x="3" y="5" width="18" height="16" rx="2" /><path d="M3 10h18M8 3v4M16 3v4" />',
		);
		if ( ! isset( $paths[ $key ] ) ) {
			return '';
		}
		return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[ $key ] . '</svg>';
	}

	/**
	 * A meaningful, non-fatal placeholder for the Elementor editor when no
	 * real job exists yet to preview against.
	 */
	public static function render_placeholder( array $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'view_button_text' => __( 'View & apply', 'careers-portal' ),
			)
		);
		ob_start();
		?>
		<article class="ccjp-job-card ccjp-job-card--placeholder">
			<div class="ccjp-job-card__head">
				<div class="ccjp-job-card__heading-group">
					<div class="ccjp-job-card__title-row">
						<h3 class="ccjp-job-card__title"><?php esc_html_e( 'Job Title Preview', 'careers-portal' ); ?></h3>
					</div>
					<p class="ccjp-job-card__subtitle"><?php esc_html_e( 'JR00000 · Category', 'careers-portal' ); ?></p>
				</div>
				<span class="ccjp-job-card__link-arrow"><?php echo esc_html( $options['view_button_text'] ); ?></span>
			</div>
			<ul class="ccjp-job-card__meta">
				<li class="ccjp-job-card__meta-item"><?php esc_html_e( 'Department', 'careers-portal' ); ?></li>
				<li class="ccjp-job-card__meta-item"><?php esc_html_e( 'Location', 'careers-portal' ); ?></li>
				<li class="ccjp-job-card__meta-item"><?php esc_html_e( 'Full Time', 'careers-portal' ); ?></li>
			</ul>
		</article>
		<?php
		return ob_get_clean();
	}
}
