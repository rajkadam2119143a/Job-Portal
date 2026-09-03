<?php
defined( 'ABSPATH' ) || exit;

/**
 * Elementor integration bootstrap. Everything in this class only ever runs
 * once Elementor itself has fired 'elementor/loaded' — if Elementor isn't
 * installed/active, this class is still instantiated (from CCJP_Plugin)
 * but never does anything beyond registering a hook that will simply never
 * fire, so core plugin functionality stays safe either way.
 */
class CCJP_Elementor {

	/** @var string[] Widget class names, registered in this order. */
	const WIDGETS = array(
		'CCJP_Widget_Hero',
		'CCJP_Widget_Job_Search',
		'CCJP_Widget_Job_Filters',
		'CCJP_Widget_Job_Listing',
		'CCJP_Widget_Job_Card',
		'CCJP_Widget_Job_Meta',
		'CCJP_Widget_Apply_Button',
		'CCJP_Widget_Featured_Jobs',
		'CCJP_Widget_Job_Count',
		'CCJP_Widget_No_Results',
		'CCJP_Widget_Categories',
		'CCJP_Widget_Departments',
		'CCJP_Widget_Locations',
		'CCJP_Widget_Related_Jobs',
		'CCJP_Widget_Application_Form',
		'CCJP_Widget_Application_Success',
		'CCJP_Widget_Job_Description',
	);

	/**
	 * Elementor's own bootstrap (includes/plugin.php) fires 'elementor/loaded'
	 * synchronously the moment that file is require'd — NOT on any WP hook —
	 * which happens while WordPress is still including each active plugin's
	 * main file, i.e. before 'plugins_loaded' fires at all. Since this class
	 * is instantiated from CCJP_Plugin on 'plugins_loaded', and this
	 * plugin's folder happens to sort before elementor's in the active_plugins
	 * list, elementor/elementor.php (and therefore 'elementor/loaded') had
	 * already fired by the time we got here in some request orders and not
	 * others — a plain add_action('elementor/loaded', ...) would silently
	 * never run, and every widget would register as missing with no error.
	 * did_action() covers both orders: call immediately if it already fired,
	 * otherwise hook normally.
	 */
	public function __construct() {
		if ( did_action( 'elementor/loaded' ) ) {
			$this->on_elementor_loaded();
		} else {
			add_action( 'elementor/loaded', array( $this, 'on_elementor_loaded' ) );
		}
	}

	public function on_elementor_loaded() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_dynamic_tags' ) );
	}

	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'careers-portal',
			array(
				'title' => __( 'Careers Portal', 'careers-portal' ),
				'icon'  => 'eicon-form-horizontal',
			)
		);
	}

	public function register_widgets( $widgets_manager ) {
		foreach ( self::WIDGETS as $class ) {
			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}

	public function register_dynamic_tags( $dynamic_tags ) {
		$dynamic_tags->register_group(
			CCJP_Tag_Job_Field::GROUP,
			array( 'title' => __( 'Careers Portal', 'careers-portal' ) )
		);
		$dynamic_tags->register( new CCJP_Tag_Job_Field() );
	}
}
