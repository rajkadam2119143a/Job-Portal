<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Careers Job Filters — Category/Department/Location/Job Type/Work Mode
 * options are pulled live from their taxonomy terms (never hardcoded);
 * Experience and Date Posted are the fixed enumerations the data model
 * itself defines (ACF Experience Level choices / a fixed "posted within"
 * scale), not admin-managed taxonomies, so those two are the only fixed
 * option lists here.
 */
class CCJP_Widget_Job_Filters extends CCJP_Widget_Base {

	const TAXONOMY_FILTERS = array(
		'category'   => 'career_category',
		'department' => 'career_department',
		'location'   => 'career_location',
		'job_type'   => 'career_job_type',
		'work_mode'  => 'career_work_mode',
	);

	public function get_name() {
		return 'ccjp-job-filters';
	}

	public function get_title() {
		return __( 'Job Filters', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-filter';
	}

	public function get_keywords() {
		return array( 'careers', 'filter', 'job' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Filter Heading', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Filter Jobs', 'careers-portal' ),
			)
		);

		foreach ( array(
			'category'    => __( 'Category', 'careers-portal' ),
			'department'  => __( 'Department', 'careers-portal' ),
			'location'    => __( 'Location', 'careers-portal' ),
			'job_type'    => __( 'Job Type', 'careers-portal' ),
			'work_mode'   => __( 'Work Mode', 'careers-portal' ),
			'experience'  => __( 'Experience', 'careers-portal' ),
			'date_posted' => __( 'Date Posted', 'careers-portal' ),
		) as $key => $label ) {
			$this->add_control(
				'show_' . $key,
				array(
					// translators: %s is the filter name, e.g. "Category".
					'label'   => sprintf( __( 'Show %s Filter', 'careers-portal' ), $label ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);
		}

		$this->add_control(
			'apply_button_text',
			array(
				'label'   => __( 'Apply Button Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Apply Filters', 'careers-portal' ),
			)
		);

		$this->add_control(
			'reset_button_text',
			array(
				'label'   => __( 'Reset Button Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Clear All', 'careers-portal' ),
			)
		);

		$this->add_control(
			'results_page',
			array(
				'label'       => __( 'Results Page', 'careers-portal' ),
				'type'        => Controls_Manager::URL,
				'description' => __( 'Where the filters submit to. Leave empty to use the Jobs archive.', 'careers-portal' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();

		$results_url = ! empty( $settings['results_page']['url'] )
			? $settings['results_page']['url']
			: get_post_type_archive_link( 'career_job' );
		?>
		<form class="ccjp-job-filters ccjp-job-search-form" action="<?php echo esc_url( $results_url ); ?>" method="get">
			<?php if ( '' !== $settings['heading'] ) : ?>
				<h3 class="ccjp-job-filters__heading"><?php echo esc_html( $settings['heading'] ); ?></h3>
			<?php endif; ?>

			<?php foreach ( self::TAXONOMY_FILTERS as $key => $taxonomy ) : ?>
				<?php if ( 'yes' === $settings[ 'show_' . $key ] ) : ?>
					<div class="ccjp-job-filters__group ccjp-job-filters__group--<?php echo esc_attr( $key ); ?>">
						<?php $this->render_term_select( $key, $taxonomy ); ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ( 'yes' === $settings['show_experience'] ) : ?>
				<div class="ccjp-job-filters__group ccjp-job-filters__group--experience">
					<select name="experience" class="ccjp-job-filters__select">
						<option value=""><?php esc_html_e( 'All Experience Levels', 'careers-portal' ); ?></option>
						<?php foreach ( array( '0-2 Years', '2-5 Years', '5-8 Years', '8+ Years' ) as $level ) : ?>
							<option value="<?php echo esc_attr( $level ); ?>"><?php echo esc_html( $level ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_date_posted'] ) : ?>
				<div class="ccjp-job-filters__group ccjp-job-filters__group--date-posted">
					<select name="date_posted" class="ccjp-job-filters__select">
						<?php
						$date_options = array(
							''   => __( 'Any Time', 'careers-portal' ),
							'1'  => __( 'Last 24 Hours', 'careers-portal' ),
							'7'  => __( 'Last 7 Days', 'careers-portal' ),
							'14' => __( 'Last 14 Days', 'careers-portal' ),
							'30' => __( 'Last 30 Days', 'careers-portal' ),
						);
						foreach ( $date_options as $value => $label ) :
							?>
							<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<div class="ccjp-job-filters__actions">
				<button type="submit" class="ccjp-job-filters__apply-button">
					<?php echo esc_html( $settings['apply_button_text'] ); ?>
				</button>
				<a href="<?php echo esc_url( $results_url ); ?>" class="ccjp-job-filters__reset-button">
					<?php echo esc_html( $settings['reset_button_text'] ); ?>
				</a>
			</div>
		</form>
		<?php
	}

	private function render_term_select( $field_name, $taxonomy ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);
		if ( is_wp_error( $terms ) ) {
			$terms = array();
		}
		$taxonomy_object = get_taxonomy( $taxonomy );
		$all_label       = $taxonomy_object
			? sprintf(
				/* translators: %s is the plural taxonomy label, e.g. "Departments". */
				__( 'All %s', 'careers-portal' ),
				$taxonomy_object->labels->name
			)
			: __( 'All', 'careers-portal' );
		?>
		<select name="<?php echo esc_attr( $field_name ); ?>" class="ccjp-job-filters__select">
			<option value=""><?php echo esc_html( $all_label ); ?></option>
			<?php foreach ( $terms as $term ) : ?>
				<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}
