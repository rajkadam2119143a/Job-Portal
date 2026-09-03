<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Base_Tag;
use Elementor\Modules\DynamicTags\Module as Dynamic_Tags_Module;

/**
 * "Career Job Field" dynamic tag — lets an Elementor editor pull any Job
 * field into a Heading/Text/Button/Dynamic HTML/Attribute anywhere, without
 * the plugin needing to hardcode that value into a template.
 *
 * Only exposes fields this plugin's data model actually has (see
 * CCJP_Job_Data) — no placeholder options for fields (Team, City, State,
 * Reference Number) the schema doesn't define.
 */
class CCJP_Tag_Job_Field extends Base_Tag {

	const GROUP = 'ccjp-group';

	public function get_name() {
		return 'ccjp-job-field';
	}

	public function get_title() {
		return __( 'Career Job Field', 'careers-portal' );
	}

	public function get_group() {
		return self::GROUP;
	}

	public function get_categories() {
		return array( Dynamic_Tags_Module::TEXT_CATEGORY, Dynamic_Tags_Module::NUMBER_CATEGORY );
	}

	public function get_content_type() {
		return 'plain';
	}

	protected function fields() {
		return array(
			'job_id'                 => __( 'Job ID', 'careers-portal' ),
			'department'             => __( 'Department', 'careers-portal' ),
			'category'               => __( 'Category', 'careers-portal' ),
			'location'               => __( 'Location', 'careers-portal' ),
			'country'                => __( 'Country', 'careers-portal' ),
			'job_type'               => __( 'Job Type', 'careers-portal' ),
			'work_mode'              => __( 'Work Mode', 'careers-portal' ),
			'experience'             => __( 'Experience', 'careers-portal' ),
			'salary'                 => __( 'Salary', 'careers-portal' ),
			'deadline'               => __( 'Application Deadline', 'careers-portal' ),
			'short_summary'          => __( 'Short Summary', 'careers-portal' ),
			'contact_email'          => __( 'Contact Email', 'careers-portal' ),
			'status'                 => __( 'Status', 'careers-portal' ),
		);
	}

	protected function register_controls() {
		$this->add_control(
			'field',
			array(
				'label'   => __( 'Field', 'careers-portal' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->fields(),
				'default' => 'job_id',
			)
		);
	}

	public function get_content( array $options = array() ) {
		$job_id = CCJP_Job_Query::resolve_reference_post_id();
		if ( ! $job_id ) {
			return '';
		}

		$field = $this->get_settings( 'field' );

		switch ( $field ) {
			case 'department':
				return CCJP_Job_Data::get_primary_term_name( $job_id, 'career_department' );
			case 'category':
				return CCJP_Job_Data::get_primary_term_name( $job_id, 'career_category' );
			case 'location':
				return CCJP_Job_Data::get_primary_term_name( $job_id, 'career_location' );
			case 'job_type':
				return CCJP_Job_Data::get_primary_term_name( $job_id, 'career_job_type' );
			case 'work_mode':
				return CCJP_Job_Data::get_primary_term_name( $job_id, 'career_work_mode' );
			case 'short_summary':
				return (string) CCJP_Job_Data::get( $job_id, 'ccjp_short_description' );
			case 'status':
				return CCJP_Job_Data::get_status_label( $job_id );
			case 'deadline':
				$ts = CCJP_Job_Data::get_deadline_timestamp( $job_id );
				return $ts ? date_i18n( get_option( 'date_format' ), $ts ) : '';
			case 'job_id':
			case 'country':
			case 'experience':
			case 'salary':
			case 'contact_email':
			default:
				$meta_key = 'ccjp_' . $field;
				return (string) CCJP_Job_Data::get( $job_id, $meta_key );
		}
	}
}
