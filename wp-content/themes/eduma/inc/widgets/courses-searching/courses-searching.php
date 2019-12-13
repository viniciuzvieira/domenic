<?php

/**
 * Class Courses_Searching_Widget
 *
 * Widget Name: Courses Searching
 *
 * Author: Ken
 */
class Thim_Courses_Searching_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'courses-searching',
			esc_html__( 'Thim: Courses Searching', 'eduma' ),
			array(
				'description'   => esc_html__( 'Search courses', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-courses-searching'
			),
			array(),
			array(
				'layout'           => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Search Layout', 'eduma' ),
					'options'       => array(
						'default' 		=> esc_html__( 'Default', 'eduma' ),
						'overlay' 		=> esc_html__( 'Overlay', 'eduma' ),
					),
					'default'       => 'default',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'layout_type' )
					),
				),
				'title'            			=> array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Title', 'eduma' ),
					'default'               => esc_html__( 'Search Courses', 'eduma' ),
					'description'           => esc_html__( 'Provide the title for search course widget.', 'eduma' ),
					'state_handler' => array(
						'layout_type[default]' => array( 'show' ),
						'layout_type[overlay]' => array( 'hide' ),
					),
				),
				'description'              	=> array(
					'type'                  => 'textarea',
					'label'                 => esc_html__( 'Description', 'eduma' ),
					'default'               => esc_html__('Description for search course widget.', 'eduma'),
					'description'           => esc_html__( 'Provide the description for search course widget.', 'eduma' ),
					'state_handler' => array(
						'layout_type[default]' => array( 'show' ),
						'layout_type[overlay]' => array( 'hide' ),
					),
				),
				'placeholder'  => array(
					'type'    => 'text',
					'default' => esc_html__( 'What do you want to learn today?', 'eduma' ),
					'label'   => esc_html__( 'Placeholder Input', 'eduma' ),
				),
				
			)
		);
	}

	function get_template_name( $instance ) {
		if ( $instance['layout'] && 'overlay' == $instance['layout'] ) {
			$layout = $instance['layout'];
		}else{
			$layout = 'base';
		}
		
		if ( thim_is_new_learnpress( '2.0' ) ) {
			$layout .= '-v2';
		} else if ( thim_is_new_learnpress( '1.0' ) ) {
			$layout .= '-v1';
		}

		return $layout;
		
	}

	function get_style_name( $instance ) {
		return false;
	}
}

/**
 * Register widget
 */
function thim_courses_searching_register_widget() {
	register_widget( 'Thim_Courses_Searching_Widget' );
}

add_action( 'widgets_init', 'thim_courses_searching_register_widget' );
