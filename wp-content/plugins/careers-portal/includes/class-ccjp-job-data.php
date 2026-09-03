<?php
defined( 'ABSPATH' ) || exit;

/**
 * Central place to read a career_job's fields (ACF meta + taxonomy terms +
 * native post data) into plain values. Every widget/renderer/shortcode
 * reads job data through here instead of calling get_field() directly, so
 * the field-name-to-meaning mapping lives in exactly one place.
 */
class CCJP_Job_Data {

	public static function get( $job_id, $field ) {
		return function_exists( 'get_field' ) ? get_field( $field, $job_id ) : get_post_meta( $job_id, $field, true );
	}

	public static function is_featured( $job_id ) {
		return (bool) self::get( $job_id, 'ccjp_featured' );
	}

	public static function get_status_label( $job_id ) {
		$status = get_post_status( $job_id );
		$object = get_post_status_object( $status );
		return $object ? $object->label : $status;
	}

	public static function is_closed( $job_id ) {
		return 'job_closed' === get_post_status( $job_id );
	}

	/**
	 * Splits a "one item per line" ACF Textarea field into a clean array —
	 * the shared parsing rule for Responsibilities, Qualifications, Skills,
	 * Good to Have and Benefits.
	 */
	public static function get_list_field( $job_id, $field ) {
		$raw = (string) self::get( $job_id, $field );
		if ( '' === trim( $raw ) ) {
			return array();
		}
		return array_values( array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) );
	}

	public static function get_term_names( $job_id, $taxonomy ) {
		$terms = get_the_terms( $job_id, $taxonomy );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return array();
		}
		return wp_list_pluck( $terms, 'name' );
	}

	public static function get_primary_term_name( $job_id, $taxonomy ) {
		$names = self::get_term_names( $job_id, $taxonomy );
		return $names ? $names[0] : '';
	}

	public static function get_deadline_timestamp( $job_id ) {
		$raw = self::get( $job_id, 'ccjp_deadline' ); // ACF Ymd return format.
		return $raw ? strtotime( $raw ) : 0;
	}

	/**
	 * Ordered label => value map used by the Job Card and Job Meta widgets.
	 * Both consume the same map so "what a job's meta looks like" is
	 * defined once; each widget's Elementor controls just choose which
	 * keys to display and in what layout.
	 */
	public static function get_meta_items( $job_id ) {
		$deadline_ts = self::get_deadline_timestamp( $job_id );

		return array(
			'job_id'     => array(
				'label' => __( 'Job ID', 'careers-portal' ),
				'value' => (string) self::get( $job_id, 'ccjp_job_id' ),
			),
			'department' => array(
				'label' => __( 'Department', 'careers-portal' ),
				'value' => self::get_primary_term_name( $job_id, 'career_department' ),
			),
			'category'   => array(
				'label' => __( 'Category', 'careers-portal' ),
				'value' => self::get_primary_term_name( $job_id, 'career_category' ),
			),
			'location'   => array(
				'label' => __( 'Location', 'careers-portal' ),
				'value' => self::get_primary_term_name( $job_id, 'career_location' ),
			),
			'country'    => array(
				'label' => __( 'Country', 'careers-portal' ),
				'value' => (string) self::get( $job_id, 'ccjp_country' ),
			),
			'job_type'   => array(
				'label' => __( 'Job Type', 'careers-portal' ),
				'value' => self::get_primary_term_name( $job_id, 'career_job_type' ),
			),
			'work_mode'  => array(
				'label' => __( 'Work Mode', 'careers-portal' ),
				'value' => self::get_primary_term_name( $job_id, 'career_work_mode' ),
			),
			'experience' => array(
				'label' => __( 'Experience', 'careers-portal' ),
				'value' => (string) self::get( $job_id, 'ccjp_experience' ),
			),
			'salary'     => array(
				'label' => __( 'Salary', 'careers-portal' ),
				'value' => (string) self::get( $job_id, 'ccjp_salary' ),
			),
			'posted'     => array(
				'label' => __( 'Posted', 'careers-portal' ),
				'value' => get_the_date( '', $job_id ),
			),
			'deadline'   => array(
				'label' => __( 'Deadline', 'careers-portal' ),
				'value' => $deadline_ts ? date_i18n( get_option( 'date_format' ), $deadline_ts ) : '',
			),
		);
	}
}
