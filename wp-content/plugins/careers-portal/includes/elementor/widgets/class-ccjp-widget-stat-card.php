<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Career Stat Card — a small "icon + live number + label" tile (Roles
 * published, Office locations, Departments hiring, Currently open) for the
 * homepage hero's stat grid. Numbers are computed live, never hardcoded.
 */
class CCJP_Widget_Stat_Card extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-stat-card';
	}

	public function get_title() {
		return __( 'Stat Card', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-number-field';
	}

	public function get_keywords() {
		return array( 'careers', 'stat', 'count', 'number' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'Number Source', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'open_jobs',
				'options' => array(
					'open_jobs'     => __( 'Open Jobs', 'careers-portal' ),
					'featured_jobs' => __( 'Featured Jobs', 'careers-portal' ),
					'locations'     => __( 'Locations (with open jobs)', 'careers-portal' ),
					'departments'   => __( 'Departments (with open jobs)', 'careers-portal' ),
					'categories'    => __( 'Categories (with open jobs)', 'careers-portal' ),
					'custom'        => __( 'Custom Number', 'careers-portal' ),
				),
			)
		);

		$this->add_control(
			'custom_number',
			array(
				'label'     => __( 'Custom Number', 'careers-portal' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'source' => 'custom' ),
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Open Positions', 'careers-portal' ),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'          => __( 'Icon', 'careers-portal' ),
				'type'           => Controls_Manager::ICONS,
				'default'        => array( 'value' => 'fas fa-briefcase', 'library' => 'fa-solid' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Style', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => __( 'Number Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_SECONDARY ),
				'selectors' => array( '{{WRAPPER}} .ccjp-stat-card__number' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ),
				'selector' => '{{WRAPPER}} .ccjp-stat-card__number',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Label Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_TEXT ),
				'selectors' => array( '{{WRAPPER}} .ccjp-stat-card__label' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_PRIMARY ),
				'selectors' => array( '{{WRAPPER}} .ccjp-stat-card__icon' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	private function compute_number( $source ) {
		switch ( $source ) {
			case 'featured_jobs':
				return CCJP_Job_Query::query( array( 'source' => 'featured', 'posts_per_page' => -1 ) )->found_posts;
			case 'locations':
				return $this->count_terms_with_jobs( 'career_location' );
			case 'departments':
				return $this->count_terms_with_jobs( 'career_department' );
			case 'categories':
				return $this->count_terms_with_jobs( 'career_category' );
			case 'open_jobs':
			default:
				return CCJP_Job_Query::query( array( 'source' => 'all', 'posts_per_page' => -1 ) )->found_posts;
		}
	}

	private function count_terms_with_jobs( $taxonomy ) {
		$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
		if ( is_wp_error( $terms ) ) {
			return 0;
		}
		$count = 0;
		foreach ( $terms as $term ) {
			if ( CCJP_Taxonomy_Card_Renderer::open_job_count( $term ) > 0 ) {
				++$count;
			}
		}
		return $count;
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();

		$number = 'custom' === $settings['source']
			? $settings['custom_number']
			: number_format_i18n( $this->compute_number( $settings['source'] ) );
		?>
		<div class="ccjp-stat-card">
			<?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
				<div class="ccjp-stat-card__icon">
					<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="ccjp-stat-card__number"><?php echo esc_html( $number ); ?></div>
			<div class="ccjp-stat-card__label"><?php echo esc_html( $settings['label'] ); ?></div>
		</div>
		<?php
	}
}
