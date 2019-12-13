<?php

class Thim_One_Course_Instructors_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'one-course-instructors',
			esc_html__( 'Thim: 1 Course Instructors', 'eduma' ),
			array(
				'description'   => esc_html__( 'Show carousel slider instructors of one course feature.', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-one-course-instructors'
			),
			array(),
			array(
				'visible_item'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Visible instructors', 'eduma' ),
					'default' => '3'
				),
				'show_pagination' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Pagination', 'eduma' ),
					'default' => 'yes',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'auto_play'       => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
					'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
					'default'     => '0'
				),
			),

			THIM_DIR . 'inc/widgets/one-course-instructors/'
		);
	}


	/**
	 * Initialize the CTA widget
	 */


	function get_template_name( $instance ) {
		if ( thim_is_new_learnpress( '2.0' ) ) {
			return 'base-v2';
		} else if( thim_is_new_learnpress( '1.0' ) ) {
			return 'base-v1';
		} else{
			return 'base';
		}
	}

	function get_style_name( $instance ) {
		return false;
	}

}

function thim_one_course_instructors_register_widget() {
	register_widget( 'Thim_One_Course_Instructors_Widget' );

}

add_action( 'widgets_init', 'thim_one_course_instructors_register_widget' );

