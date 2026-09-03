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
	 *     @type array  $meta_keys           Which CCJP_Job_Data::get_meta_items() keys to show, in order.
	 *     @type bool   $show_excerpt        Show the short description.
	 *     @type bool   $show_apply_button   Show the Apply button alongside View Job.
	 *     @type string $view_button_text
	 *     @type string $apply_button_text
	 * }
	 */
	public static function render( $job_id, array $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'meta_keys'         => array( 'department', 'location', 'job_type', 'work_mode', 'experience' ),
				'show_excerpt'      => true,
				'show_apply_button' => true,
				'view_button_text'  => __( 'View Job', 'careers-portal' ),
				'apply_button_text' => __( 'Apply Now', 'careers-portal' ),
			)
		);

		$permalink = get_permalink( $job_id );
		$title     = get_the_title( $job_id );
		$meta      = CCJP_Job_Data::get_meta_items( $job_id );
		$featured  = CCJP_Job_Data::is_featured( $job_id );
		$closed    = CCJP_Job_Data::is_closed( $job_id );

		ob_start();
		?>
		<article class="ccjp-job-card<?php echo $featured ? ' ccjp-job-card--featured' : ''; ?><?php echo $closed ? ' ccjp-job-card--closed' : ''; ?>">
			<?php if ( $featured ) : ?>
				<span class="ccjp-job-card__badge"><?php esc_html_e( 'Featured', 'careers-portal' ); ?></span>
			<?php endif; ?>

			<h3 class="ccjp-job-card__title">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
			</h3>

			<ul class="ccjp-job-card__meta">
				<?php foreach ( $options['meta_keys'] as $key ) : ?>
					<?php if ( empty( $meta[ $key ]['value'] ) ) { continue; } ?>
					<li class="ccjp-job-card__meta-item ccjp-job-card__meta-item--<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $meta[ $key ]['value'] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( $options['show_excerpt'] ) : ?>
				<p class="ccjp-job-card__excerpt"><?php echo esc_html( (string) CCJP_Job_Data::get( $job_id, 'ccjp_short_description' ) ); ?></p>
			<?php endif; ?>

			<div class="ccjp-job-card__actions">
				<a class="ccjp-job-card__view-btn" href="<?php echo esc_url( $permalink ); ?>">
					<?php echo esc_html( $options['view_button_text'] ); ?>
				</a>
				<?php if ( $options['show_apply_button'] && ! $closed ) : ?>
					<a class="ccjp-job-card__apply-btn" href="<?php echo esc_url( $permalink . '#ccjp-apply' ); ?>">
						<?php echo esc_html( $options['apply_button_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}

	/**
	 * A meaningful, non-fatal placeholder for the Elementor editor when no
	 * real job exists yet to preview against.
	 */
	public static function render_placeholder( array $options = array() ) {
		$options = wp_parse_args(
			$options,
			array(
				'view_button_text'  => __( 'View Job', 'careers-portal' ),
				'apply_button_text' => __( 'Apply Now', 'careers-portal' ),
			)
		);
		ob_start();
		?>
		<article class="ccjp-job-card ccjp-job-card--placeholder">
			<h3 class="ccjp-job-card__title"><?php esc_html_e( 'Job Title Preview', 'careers-portal' ); ?></h3>
			<ul class="ccjp-job-card__meta">
				<li class="ccjp-job-card__meta-item"><?php esc_html_e( 'Department', 'careers-portal' ); ?></li>
				<li class="ccjp-job-card__meta-item"><?php esc_html_e( 'Location', 'careers-portal' ); ?></li>
				<li class="ccjp-job-card__meta-item"><?php esc_html_e( 'Full Time', 'careers-portal' ); ?></li>
			</ul>
			<p class="ccjp-job-card__excerpt"><?php esc_html_e( 'Short job description preview text appears here.', 'careers-portal' ); ?></p>
			<div class="ccjp-job-card__actions">
				<span class="ccjp-job-card__view-btn"><?php echo esc_html( $options['view_button_text'] ); ?></span>
				<span class="ccjp-job-card__apply-btn"><?php echo esc_html( $options['apply_button_text'] ); ?></span>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}
}
