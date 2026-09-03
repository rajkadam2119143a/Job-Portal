<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Career Job Count — "48 Open Positions", scoped to All jobs or the
 * current taxonomy context (so it works on a Category/Department/Location
 * archive template as well as the homepage).
 */
class CCJP_Widget_Job_Count extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-job-count';
	}

	public function get_title() {
		return __( 'Job Count', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_keywords() {
		return array( 'careers', 'count', 'jobs' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'scope',
			array(
				'label'   => __( 'Scope', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'contextual',
				'options' => array(
					'all'        => __( 'All Open Jobs', 'careers-portal' ),
					'contextual' => __( 'Current Archive Context', 'careers-portal' ),
				),
			)
		);

		$this->add_control(
			'template',
			array(
				'label'       => __( 'Text Template', 'careers-portal' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( '{count} Open Positions', 'careers-portal' ),
				'description' => __( 'Use {count} as a placeholder for the number.', 'careers-portal' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Typography', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'Text Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_TEXT ),
				'selectors' => array( '{{WRAPPER}} .ccjp-job-count' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_TEXT ),
				'selector' => '{{WRAPPER}} .ccjp-job-count',
			)
		);

		$this->end_controls_section();
	}

	private function count( $settings ) {
		$args = array(
			'source'         => 'all',
			'posts_per_page' => -1,
		);

		if ( 'contextual' === $settings['scope'] ) {
			$queried = get_queried_object();
			if ( $queried instanceof WP_Term ) {
				foreach ( CCJP_Job_Query::TAXONOMIES as $key => $taxonomy ) {
					if ( $taxonomy === $queried->taxonomy ) {
						$args[ $key ] = $queried->term_id;
						break;
					}
				}
			}
		}

		$query = CCJP_Job_Query::query( $args );
		return (int) $query->found_posts;
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$count    = $this->count( $settings );

		$text = str_replace( '{count}', number_format_i18n( $count ), $settings['template'] );
		?>
		<div class="ccjp-job-count"><?php echo esc_html( $text ); ?></div>
		<?php
	}
}
