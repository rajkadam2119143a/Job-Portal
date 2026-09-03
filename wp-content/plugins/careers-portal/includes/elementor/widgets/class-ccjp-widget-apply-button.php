<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Career Apply Button — knows the current career_job from context and
 * links to it. Only exposes Open Methods that are fully functional today
 * (scroll-to-anchor / dedicated page); Modal and Slide Panel are left out
 * rather than presented as working — they arrive once the Application Form
 * widget (a later phase) actually has a panel to open.
 */
class CCJP_Widget_Apply_Button extends CCJP_Widget_Base {

	public function get_name() {
		return 'ccjp-apply-button';
	}

	public function get_title() {
		return __( 'Apply Button', 'careers-portal' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_keywords() {
		return array( 'careers', 'apply', 'button' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => __( 'Content', 'careers-portal' ) )
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Button Text', 'careers-portal' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Apply Now', 'careers-portal' ),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label' => __( 'Icon', 'careers-portal' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'     => __( 'Icon Position', 'careers-portal' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => array(
					'left'  => __( 'Before', 'careers-portal' ),
					'right' => __( 'After', 'careers-portal' ),
				),
				'condition' => array( 'icon[value]!' => '' ),
			)
		);

		$this->add_control(
			'open_method',
			array(
				'label'       => __( 'Open Method', 'careers-portal' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'anchor',
				'options'     => array(
					'anchor'   => __( 'Scroll to Application Form (same page)', 'careers-portal' ),
					'job_page' => __( 'Dedicated Job Page', 'careers-portal' ),
				),
				'description' => __( 'Modal and slide-panel opening arrive with the Application Form widget.', 'careers-portal' ),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'careers-portal' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array( 'title' => __( 'Left', 'careers-portal' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => __( 'Center', 'careers-portal' ), 'icon' => 'eicon-text-align-center' ),
					'right'  => array( 'title' => __( 'Right', 'careers-portal' ), 'icon' => 'eicon-text-align-right' ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Button', 'careers-portal' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => __( 'Size', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => array(
					'sm' => __( 'Small', 'careers-portal' ),
					'md' => __( 'Medium', 'careers-portal' ),
					'lg' => __( 'Large', 'careers-portal' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array( 'default' => Global_Typography::TYPOGRAPHY_ACCENT ),
				'selector' => '{{WRAPPER}} .ccjp-apply-button',
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab( 'button_style_normal', array( 'label' => __( 'Normal', 'careers-portal' ) ) );

		$this->add_control(
			'bg_color',
			array(
				'label'     => __( 'Background Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_ACCENT ),
				'selectors' => array( '{{WRAPPER}} .ccjp-apply-button' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ccjp-apply-button' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_style_hover', array( 'label' => __( 'Hover', 'careers-portal' ) ) );

		$this->add_control(
			'bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ccjp-apply-button:hover' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'text_color_hover',
			array(
				'label'     => __( 'Text Color', 'careers-portal' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ccjp-apply-button:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'careers-portal' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ccjp-apply-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'careers-portal' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ccjp-apply-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->enqueue_frontend_assets();
		$settings = $this->get_settings_for_display();
		$job_id   = $this->get_current_job_id();

		$href = '#ccjp-apply';
		if ( $job_id ) {
			$href = 'job_page' === $settings['open_method']
				? get_permalink( $job_id )
				: get_permalink( $job_id ) . '#ccjp-apply';
		} elseif ( ! $this->is_editing() ) {
			return;
		}

		$this->add_render_attribute(
			'button',
			'class',
			array( 'ccjp-apply-button', 'ccjp-apply-button--' . $settings['size'] )
		);
		$this->add_render_attribute( 'button', 'href', esc_url( $href ) );
		if ( $job_id ) {
			$this->add_render_attribute( 'button', 'data-ccjp-job-id', $job_id );
		}
		?>
		<a <?php echo $this->get_render_attribute_string( 'button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( ! empty( $settings['icon']['value'] ) && 'left' === $settings['icon_position'] ) : ?>
				<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
			<?php endif; ?>
			<span class="ccjp-apply-button__text"><?php echo esc_html( $settings['text'] ); ?></span>
			<?php if ( ! empty( $settings['icon']['value'] ) && 'right' === $settings['icon_position'] ) : ?>
				<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
			<?php endif; ?>
		</a>
		<?php
	}
}
