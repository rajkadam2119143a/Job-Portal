<?php
/**
 * Single job page. Replaces the Elementor Theme Builder single template.
 *
 * NOTE — application submission: the plugin ships an Application Form
 * widget/CPT (CCJP_Widget_Application_Form, CCJP_Applications) and an
 * Elementor "Apply" popup (class-ccjp-popups.php), but which one actually
 * captured applications on the old Elementor-built single template wasn't
 * verified here. Rather than guess and risk silently breaking the apply
 * flow, the Apply button below links to the job's contact email (falling
 * back to the real Contact page) — replace with the real application
 * entry point once that's confirmed.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$job_id        = get_the_ID();
	$meta          = CCJP_Job_Data::get_meta_items( $job_id );
	$closed        = CCJP_Job_Data::is_closed( $job_id );
	$job_code      = (string) CCJP_Job_Data::get( $job_id, 'ccjp_job_id' );
	$category      = CCJP_Job_Data::get_primary_term_name( $job_id, 'career_category' );
	$short_desc    = (string) CCJP_Job_Data::get( $job_id, 'ccjp_short_description' );
	$salary        = (string) CCJP_Job_Data::get( $job_id, 'ccjp_salary' );
	$contact_email = (string) CCJP_Job_Data::get( $job_id, 'ccjp_contact_email' );
	$deadline_ts   = CCJP_Job_Data::get_deadline_timestamp( $job_id );

	$sections = array(
		'responsibilities' => __( 'Responsibilities', 'hello-elementor-child' ),
		'qualifications'   => __( 'Qualifications', 'hello-elementor-child' ),
		'skills'           => __( 'Skills', 'hello-elementor-child' ),
		'good_to_have'     => __( 'Good to Have', 'hello-elementor-child' ),
		'benefits'         => __( 'Benefits', 'hello-elementor-child' ),
	);
	?>

	<div class="bccl-job-hero">
		<div class="bccl-container">
			<span class="bccl-eyebrow"><?php echo esc_html( $category ? $category : __( 'Open Role', 'hello-elementor-child' ) ); ?></span>
			<h1 class="bccl-display" style="font-size:clamp(28px,4vw,44px); margin-top:16px;"><?php the_title(); ?></h1>
			<?php if ( '' !== $job_code || '' !== $category ) : ?>
				<p class="bccl-lede" style="margin-top:8px; font-size:15px;"><?php echo esc_html( trim( $job_code . ( '' !== $job_code && '' !== $category ? ' · ' : '' ) . $category ) ); ?></p>
			<?php endif; ?>
			<ul class="ccjp-job-card__meta" style="margin-top:24px;">
				<?php foreach ( array( 'department', 'location', 'job_type', 'experience', 'posted' ) as $key ) : ?>
					<?php $value = isset( $meta[ $key ]['value'] ) ? $meta[ $key ]['value'] : ''; ?>
					<?php if ( '' === $value ) { continue; } ?>
					<li class="ccjp-job-card__meta-item"><span><?php echo esc_html( $value ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<section class="bccl-section--tight">
		<div class="bccl-container">
			<div class="bccl-job-detail-grid">
				<div class="bccl-job-detail-body">
					<?php if ( '' !== $short_desc ) : ?>
						<p><?php echo esc_html( $short_desc ); ?></p>
					<?php endif; ?>

					<?php
					foreach ( $sections as $field_suffix => $label ) :
						$items = CCJP_Job_Data::get_list_field( $job_id, 'ccjp_' . $field_suffix );
						if ( empty( $items ) ) {
							continue;
						}
						?>
						<h2><?php echo esc_html( $label ); ?></h2>
						<ul>
							<?php foreach ( $items as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endforeach; ?>

					<?php
					$additional = (string) CCJP_Job_Data::get( $job_id, 'ccjp_additional_information' );
					if ( '' !== $additional ) :
						?>
						<h2><?php esc_html_e( 'Additional Information', 'hello-elementor-child' ); ?></h2>
						<p><?php echo esc_html( $additional ); ?></p>
					<?php endif; ?>
				</div>

				<aside class="bccl-job-detail-sidebar">
					<div class="bccl-job-detail-panel">
						<?php if ( '' !== $salary ) : ?>
							<p class="ccjp-job-meta__label"><?php esc_html_e( 'Salary', 'hello-elementor-child' ); ?></p>
							<p class="ccjp-job-meta__value" style="margin-bottom:16px;"><?php echo esc_html( $salary ); ?></p>
						<?php endif; ?>
						<?php if ( $deadline_ts ) : ?>
							<p class="ccjp-job-meta__label"><?php esc_html_e( 'Apply by', 'hello-elementor-child' ); ?></p>
							<p class="ccjp-job-meta__value" style="margin-bottom:16px;"><?php echo esc_html( date_i18n( get_option( 'date_format' ), $deadline_ts ) ); ?></p>
						<?php endif; ?>

						<?php
						$has_gf_form = class_exists( 'CCJP_GravityForms' ) && function_exists( 'gravity_form' ) && CCJP_GravityForms::get_form_id();
						?>
						<?php if ( $closed ) : ?>
							<span class="bccl-btn bccl-btn-secondary bccl-btn-block" style="opacity:.6; cursor:not-allowed;"><?php esc_html_e( 'Applications Closed', 'hello-elementor-child' ); ?></span>
						<?php elseif ( $has_gf_form ) : ?>
							<button type="button" class="bccl-btn bccl-btn-primary bccl-btn-block" data-bccl-modal-open="bcclApplyModal"><?php esc_html_e( 'Apply Now', 'hello-elementor-child' ); ?></button>
						<?php else : ?>
							<?php
							$apply_url = $contact_email
								? 'mailto:' . antispambot( $contact_email )
								: ( class_exists( 'CCJP_Pages' ) ? CCJP_Pages::get_page_url( 'contact' ) : '#' );
							?>
							<a class="bccl-btn bccl-btn-primary bccl-btn-block" href="<?php echo esc_url( $apply_url ); ?>"><?php esc_html_e( 'Apply Now', 'hello-elementor-child' ); ?></a>
						<?php endif; ?>
					</div>
				</aside>
			</div>
		</div>
	</section>

	<?php if ( ! $closed && $has_gf_form ) : ?>
		<div class="bccl-modal-overlay" id="bcclApplyModal">
			<div class="bccl-modal" role="dialog" aria-modal="true" aria-labelledby="bcclApplyModalTitle">
				<button type="button" class="bccl-modal-close" data-bccl-modal-close aria-label="<?php esc_attr_e( 'Close', 'hello-elementor-child' ); ?>">
					<?php echo bccl_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<div class="bccl-modal-header">
					<span class="bccl-eyebrow"><?php esc_html_e( 'Apply Now', 'hello-elementor-child' ); ?></span>
					<h2 id="bcclApplyModalTitle"><?php the_title(); ?></h2>
				</div>
				<div class="bccl-modal-body">
					<?php CCJP_GravityForms::render( $job_id ); ?>
					<p style="text-align:right; margin-top:-8px;">
						<button type="button" class="bccl-modal-cancel" data-bccl-modal-close><?php esc_html_e( 'Cancel', 'hello-elementor-child' ); ?></button>
					</p>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php
	$related = CCJP_Job_Query::query(
		array(
			'source'            => 'related',
			'reference_post_id' => $job_id,
			'posts_per_page'    => 3,
			// Belt-and-suspenders alongside the 'related' source's own
			// post__not_in exclusion — this list should never include the
			// job whose own page it's rendered on.
			'exclude'           => array( $job_id ),
		)
	);
	$related_ids = array();
	if ( $related->have_posts() ) {
		while ( $related->have_posts() ) {
			$related->the_post();
			// Second, template-level guard: never render the current job as
			// one of its own "related" cards, regardless of what the query
			// returned.
			if ( (int) get_the_ID() !== (int) $job_id ) {
				$related_ids[] = get_the_ID();
			}
		}
		wp_reset_postdata();
	}
	if ( ! empty( $related_ids ) ) :
		?>
		<section class="bccl-section bccl-section--alt">
			<div class="bccl-container">
				<div class="bccl-section-head"><h2><?php esc_html_e( 'Related roles', 'hello-elementor-child' ); ?></h2></div>
				<div class="ccjp-job-listing__grid" style="--ccjp-columns:3; --ccjp-gap:16px;">
					<?php foreach ( $related_ids as $related_id ) : ?>
						<?php echo CCJP_Job_Card_Renderer::render( $related_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
