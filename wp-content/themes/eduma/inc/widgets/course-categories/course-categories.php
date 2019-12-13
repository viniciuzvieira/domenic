<?php

/**
 * Class Course_Categories_Widget
 *
 * Widget Name: Course Categories
 *
 * Author: Ken
 */
class Thim_Course_Categories_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'course-categories',
			esc_html__( 'Thim: Course Categories', 'eduma' ),
			array(
				'description'   => esc_html__( 'Show course categories', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-course-categories'
			),
			array(),
			array(
				'title'              => array(
					'type'  => 'text',
					'label' => esc_html__( 'Title', 'eduma' ),
				),
				'layout'             => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Layout', 'eduma' ),
					'options'       => array(
						'slider'     => esc_html__( 'Slider', 'eduma' ),
						'base'       => esc_html__( 'List Categories', 'eduma' ),
						'tab-slider' => esc_html__( 'Tab Slider', 'eduma' ),
					),
					'default'       => 'base',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'layout_type' )
					),
				),
				'slider-options'     => array(
					'type'          => 'section',
					'label'         => esc_html__( 'Slider Layout Options', 'eduma' ),
					'hide'          => true,
					"class"         => "clear-both",
					'state_handler' => array(
						'layout_type[slider]'     => array( 'show' ),
						'layout_type[tab-slider]' => array( 'show' ),
						'layout_type[list]'       => array( 'hide' ),
					),
					'fields'        => array(
						'limit'           => array(
							'type'    => 'number',
							'label'   => esc_html__( 'Limit categories', 'eduma' ),
							'default' => '15'
						),
						'show_pagination' => array(
							'type'    => 'checkbox',
							'label'   => esc_html__( 'Show Pagination', 'eduma' ),
							'default' => false
						),
						'show_navigation' => array(
							'type'    => 'checkbox',
							'label'   => esc_html__( 'Show Navigation', 'eduma' ),
							'default' => true
						),
						'auto_play'       => array(
							'type'        => 'number',
							'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
							'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
							'default'     => '0'
						),
						'responsive-options' => array(
							'type'   => 'section',
							'label'  => esc_html__( 'Responsive Options', 'eduma' ),
							'hide'   => true,
							'fields' => array(
								'item_visible'               => array(
									'type'    => 'select',
									'label'   => esc_html__( 'Items Visible', 'eduma' ),
									'options' => array(
										'1' => '1',
										'2' => '2',
										'3' => '3',
										'4' => '4',
										'5' => '5',
										'6' => '6',
										'7' => '7',
										'8' => '8',
									),
									'default' => '7'
								),
								'item_small_desktop_visible' => array(
									'type'    => 'select',
									'label'   => esc_html__( 'Items Small Desktop Visible', 'eduma' ),
									'options' => array(
										'1' => '1',
										'2' => '2',
										'3' => '3',
										'4' => '4',
										'5' => '5',
										'6' => '6',
										'7' => '7',
										'8' => '8',
									),
									'default' => '6'
								),
								'item_tablet_visible'        => array(
									'type'    => 'select',
									'label'   => esc_html__( 'Items Tablet Visible', 'eduma' ),
									'options' => array(
										'1' => '1',
										'2' => '2',
										'3' => '3',
										'4' => '4',
										'5' => '5',
										'6' => '6',
										'7' => '7',
										'8' => '8',
									),
									'default' => '4'
								),
								'item_mobile_visible'        => array(
									'type'    => 'select',
									'label'   => esc_html__( 'Items Mobile Visible', 'eduma' ),
									'options' => array(
										'1' => '1',
										'2' => '2',
										'3' => '3',
										'4' => '4',
										'5' => '5',
										'6' => '6',
										'7' => '7',
										'8' => '8',
									),
									'default' => '2'
								),
							)
						),
					),
				),
				'list-options' => array(
					'type'          => 'section',
					'label'         => esc_html__( 'List Layout Options', 'eduma' ),
					'hide'          => true,
					"class"         => "clear-both",
					'state_handler' => array(
						'layout_type[base]'       => array( 'show' ),
						'layout_type[slider]'     => array( 'hide' ),
						'layout_type[tab-slider]' => array( 'hide' ),
					),
					'fields'        => array(
						'show_counts'  => array(
							'type'    => 'checkbox',
							'label'   => esc_html__( 'Show course count', 'eduma' ),
							'default' => false,
						),
						'hierarchical' => array(
							'type'    => 'checkbox',
							'label'   => esc_html__( 'Show hierarchy', 'eduma' ),
							'default' => false,
						),
					),

				),
			)
		);
	}

	function get_template_name( $instance ) {
		if ( ! empty( $instance['layout'] ) && ( 'slider' == $instance['layout'] || 'tab-slider' == $instance['layout'] ) ) {
			$layout = $instance['layout'];
		} else {
			$layout = 'base';
		}

		if ( thim_is_new_learnpress( '3.0' ) ) {
			$layout .= '-v3';
		} elseif ( thim_is_new_learnpress( '2.0' ) ) {
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
function thim_course_categories_register_widget() {
	register_widget( 'Thim_Course_Categories_Widget' );
}

add_action( 'widgets_init', 'thim_course_categories_register_widget' );
