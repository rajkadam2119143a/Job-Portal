<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Careers Job Search — keyword + optional Location/Department/Category
 * fields. Submits as a normal GET form (works with no JS, on any page,
 * archive, or template) and is additionally progressively enhanced by
 * ccjp-frontend.js to AJAX-update a Job Listing widget in place when one is
 * present on the same page.
 */
class CCJP_Widget_Job_Search extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-job-search';
	}

	public function get_title() {
		return __( 'Job Search', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_keywords() {
		return array( 'careers', 'search', 'job' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'keyword_placeholder',
			array(
				'label'   => __( 'Keyword Placeholder', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search by job title, keyword or skill', 'careers-portal' ),
			)
		);

		$this->add_control(
			'show_location',
			array(
				'label'     => __( 'Show Location Field', 'careers-portal' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_department',
			array(
				'label'   => __( 'Show Department Field', 'careers-portal' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'   => __( 'Show Category Field', 'careers-portal' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search Jobs', 'careers-portal' ),
			)
		);

		$this->add_control(
			'results_page',
			array(
				'label'       => __( 'Results Page', 'careers-portal' ),
				'type'        => Controls_Manager::URL,
				'description' => __( 'Where the search submits to. Leave empty to use the Jobs archive.', 'careers-portal' ),
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
		<form class="ccjp-job-search ccjp-job-search-form" action="<?php echo esc_url( $results_url ); ?>" method="get">
			<input
				type="text"
				name="keyword"
				class="ccjp-job-search__field"
				placeholder="<?php echo esc_attr( $settings['keyword_placeholder'] ); ?>"
			/>

			<?php if ( 'yes' === $settings['show_location'] ) : ?>
				<?php $this->render_term_select( 'location', 'career_location', __( 'All Locations', 'careers-portal' ) ); ?>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_department'] ) : ?>
				<?php $this->render_term_select( 'department', 'career_department', __( 'All Departments', 'careers-portal' ) ); ?>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_category'] ) : ?>
				<?php $this->render_term_select( 'category', 'career_category', __( 'All Categories', 'careers-portal' ) ); ?>
			<?php endif; ?>

			<button type="submit" class="ccjp-job-search__button">
				<?php echo esc_html( $settings['button_text'] ); ?>
			</button>
		</form>
		<?php
	}

	private function render_term_select( $field_name, $taxonomy, $all_label ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);
		if ( is_wp_error( $terms ) ) {
			$terms = array();
		}
		?>
		<select name="<?php echo esc_attr( $field_name ); ?>" class="ccjp-job-search__field ccjp-job-search__select">
			<option value=""><?php echo esc_html( $all_label ); ?></option>
			<?php foreach ( $terms as $term ) : ?>
				<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}
