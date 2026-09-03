<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Career No Results — an empty-state block. On first page load it's shown
 * only if there are literally zero published jobs site-wide; when a Job
 * Search/Filters/Listing AJAX request comes back with zero matches,
 * ccjp-frontend.js reveals every .ccjp-no-results element on the page (and
 * hides them again once results come back).
 */
class CCJP_Widget_No_Results extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-no-results';
	}

	public function get_title() {
		return __( 'No Results', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-not-found';
	}

	public function get_keywords() {
		return array( 'careers', 'empty', 'no results' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'No jobs found.', 'careers-portal' ),
			)
		);

		$this->add_control(
			'message',
			array(
				'label'   => __( 'Message', 'careers-portal' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Try changing your search criteria or removing some filters.', 'careers-portal' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();

		$counts        = wp_count_posts( 'career_job' );
		$has_open_jobs = $counts && ! empty( $counts->publish );
		$hidden_style  = ( $has_open_jobs && ! $this->is_editing() ) ? ' style="display:none;"' : '';
		?>
		<div class="ccjp-no-results"<?php echo $hidden_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<h3 class="ccjp-no-results__heading"><?php echo esc_html( $settings['heading'] ); ?></h3>
			<?php if ( '' !== $settings['message'] ) : ?>
				<p class="ccjp-no-results__message"><?php echo esc_html( $settings['message'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
