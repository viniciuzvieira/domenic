<?php

/**
 * Class Courses_Widget
 *
 * Widget Name: Courses
 *
 * Author: Ken
 */
if (!class_exists('Thim_Courses_Widget')) {
	class Thim_Courses_Widget extends Thim_Widget {
		function __construct() {
			$list_cat     = $this->thim_get_course_categories();
			$list_all_cat = array( 'all' => esc_html__( 'All', 'eduma' ) ) + $list_cat;
			parent::__construct(
				'courses',
				esc_html__( 'Thim: Courses', 'eduma' ),
				array(
					'description'   => esc_html__( 'Display courses', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-courses'
				),
				array(),
				array(
					'title' => array(
						'type'                  => 'text',
						'label'                 => esc_html__( 'Heading Text', 'eduma' ),
						'default'               => esc_html__( 'Latest Courses', 'eduma' ),
						'allow_html_formatting' => true
					),

					'order'             => array(
						'type'          => 'select',
						'label'         => esc_html__( 'Order By', 'eduma' ),
						'options'       => array(
							'popular'  => esc_html__( 'Popular', 'eduma' ),
							'latest'   => esc_html__( 'Latest', 'eduma' ),
							'category' => esc_html__( 'Category', 'eduma' )
						),
						'default'       => 'latest',
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'order' )
						),
					),
					'cat_id'            => array(
						'type'          => 'select',
						'label'         => esc_html__( 'Select Category', 'eduma' ),
						'default'       => 'all',
						'hide'          => true,
						'options'       => $list_all_cat,
						'state_handler' => array(
							'order[category]' => array( 'show' ),
							'order[popular]'  => array( 'hide' ),
							'order[latest]'   => array( 'hide' ),
						),
					),
					'layout'            => array(
						'type'          => 'select',
						'label'         => esc_html__( 'Widget Layout', 'eduma' ),
						'options'       => array(
							'slider'       => esc_html__( 'Slider', 'eduma' ),
							'grid'         => esc_html__( 'Grid', 'eduma' ),
                            'grid1'         => esc_html__( 'Grid New', 'eduma' ),
							'list-sidebar' => esc_html__( 'List Sidebar', 'eduma' ),
							'megamenu'     => esc_html__( 'Mega Menu', 'eduma' ),
							'tabs'         => esc_html__( 'Category Tabs', 'eduma' ),
                            'tabs-slider'         => esc_html__( 'Category Tabs Slider', 'eduma' ),
						),
						'default'       => 'slider',
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'layout_type' )
						),
					),
                    'thumbnail_width' => array(
                        'type'                  => 'slider',
                        'label'                 => esc_html__( 'Thumbnail Width', 'eduma' ),
                        'default' => 400,
                        'min' => 100,
                        'max' => 800,
                        'integer' => true,
                        'state_handler' => array(
                            'layout_type[slider]'       => array( 'show' ),
                            'layout_type[grid]'         => array( 'show' ),
                            'layout_type[grid1]'        => array( 'show' ),
                            'layout_type[list-sidebar]' => array( 'hide' ),
                            'layout_type[tabs]'         => array( 'show' ),
                            'layout_type[tabs-slider]'  => array( 'show' ),
                            'layout_type[megamenu]'     => array( 'hide' ),
                        ),
                    ),
                    'thumbnail_height' => array(
                        'type'                  => 'slider',
                        'label'                 => esc_html__( 'Thumbnail Height', 'eduma' ),
                        'default' => 300,
                        'min' => 100,
                        'max' => 800,
                        'integer' => true,
                        'state_handler' => array(
                            'layout_type[slider]'       => array( 'show' ),
                            'layout_type[grid]'         => array( 'show' ),
                            'layout_type[grid1]'        => array( 'show' ),
                            'layout_type[list-sidebar]' => array( 'hide' ),
                            'layout_type[tabs]'         => array( 'show' ),
                            'layout_type[tabs-slider]'  => array( 'show' ),
                            'layout_type[megamenu]'     => array( 'hide' ),
                        ),
                    ),
					'limit'             => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Limit number course', 'eduma' ),
						'default' => '8'
					),
					'featured'          => array(
						'type'        => 'checkbox',
						'label'       => esc_html__( 'Featured', 'eduma' ),
						'description' => esc_html__( 'Only display featured courses', 'eduma' ),
						'default'     => false
					),
					'view_all_courses'  => array(
						'type'          => 'text',
						'label'         => esc_html__( 'Text View All Courses', 'eduma' ),
						'default'       => '',
						'hide'          => true,
						'state_handler' => array(
							'layout_type[slider]'       => array( 'hide' ),
							'layout_type[grid]'         => array( 'show' ),
                            'layout_type[grid1]'         => array( 'show' ),
							'layout_type[list-sidebar]' => array( 'hide' ),
                            'layout_type[tabs]'         => array( 'hide' ),
                            'layout_type[tabs-slider]'  => array( 'show' ),
						),
					),
					'view_all_position' => array(
						'type'          => 'select',
						'label'         => esc_html__( 'View All Position', 'eduma' ),
						'options'       => array(
							'top'    => esc_html__( 'Top', 'eduma' ),
							'bottom' => esc_html__( 'Bottom', 'eduma' ),
						),
						'default'       => 'top',
						'hide'          => true,
						'state_handler' => array(
							'layout_type[slider]'       => array( 'hide' ),
							'layout_type[grid]'         => array( 'show' ),
                            'layout_type[grid1]'         => array( 'show' ),
							'layout_type[list-sidebar]' => array( 'hide' ),
							'layout_type[tabs]'         => array( 'hide' ),
                            'layout_type[tabs-slider]'  => array( 'show' ),
							'layout_type[megamenu]'     => array( 'hide' ),
						),
					),
					'slider-options'    => array(
						'type'          => 'section',
						'label'         => esc_html__( 'Slider Layout Options', 'eduma' ),
						'hide'          => true,
						"class"         => "clear-both",
						'state_handler' => array(
							'layout_type[slider]'       => array( 'show' ),
							'layout_type[grid]'         => array( 'hide' ),
                            'layout_type[grid1]'         => array( 'hide' ),
							'layout_type[list-sidebar]' => array( 'hide' ),
							'layout_type[tabs]'         => array( 'hide' ),
                            'layout_type[tabs-slider]'  => array( 'hide' ),
							'layout_type[megamenu]'     => array( 'hide' ),
						),
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'courses_slider_opt' )
						),
						'fields'        => array(
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
							'item_visible'    => array(
								'type'    => 'select',
								'label'   => esc_html__( 'Items Visible', 'eduma' ),
								'options' => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
								),
								'default' => '4'
							),
							'auto_play'       => array(
								'type'        => 'number',
								'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
								'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
								'default'     => '0'
							),
						),

					),

					'grid-options' => array(
						'type'          => 'section',
						'label'         => esc_html__( 'Grid Layout Options', 'eduma' ),
						'hide'          => true,
						"class"         => "clear-both",
						'state_handler' => array(
							'layout_type[slider]'       => array( 'hide' ),
							'layout_type[grid]'         => array( 'show' ),
                            'layout_type[grid1]'         => array( 'show' ),
							'layout_type[list-sidebar]' => array( 'hide' ),
							'layout_type[megamenu]'     => array( 'hide' ),
							'layout_type[tabs]'         => array( 'hide' ),
                            'layout_type[tabs-slider]'  => array( 'hide' ),
						),
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'courses_grid_opt' )
						),
						'fields'        => array(
							'columns' => array(
								'type'    => 'select',
								'label'   => esc_html__( 'Columns', 'eduma' ),
								'options' => array(
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
								),
								'default' => '4'
							),

						),

					),

					'tabs-options' => array(
						'type'          => 'section',
						'label'         => esc_html__( 'Tabs Options', 'eduma' ),
						'hide'          => true,
						"class"         => "clear-both",
						'state_handler' => array(
							'layout_type[slider]'       => array( 'hide' ),
							'layout_type[grid]'         => array( 'hide' ),
                            'layout_type[grid1]'         => array( 'hide' ),
							'layout_type[list-sidebar]' => array( 'hide' ),
							'layout_type[tabs]'         => array( 'show' ),
                            'layout_type[tabs-slider]'  => array( 'show' ),
						),
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'courses_tabs_opt' )
						),
						'fields'        => array(
							'limit_tab'  => array(
								'type'    => 'number',
								'label'   => esc_html__( 'Limit Items Per Tab', 'eduma' ),
								'default' => '4'
							),
							'cat_id_tab' => array(
								'type'     => 'select',
								'label'    => esc_html__( 'Select Category Tabs', 'eduma' ),
								'default'  => 'all',
								'multiple' => true,
								'hide'     => true,
								'options'  => $list_cat,
							),
						),

					),


				)
			);
		}

		function get_template_name( $instance ) {
			if ( $instance['layout'] && '' != $instance['layout'] ) {
				$layout = $instance['layout'];
			}
            if ( thim_is_new_learnpress( '3.0' ) ) {
                $layout .= '-v3';
            } else if ( thim_is_new_learnpress( '2.0' ) ) {
				$layout .= '-v2';
			} else if ( thim_is_new_learnpress( '1.0' ) ) {
				$layout .= '-v1';
			}
			return $layout;
		}

		function get_style_name( $instance ) {
			return false;
		}

		// Get list category
		function thim_get_course_categories( $cats = false ) {
			global $wpdb;
			$query = $wpdb->get_results( $wpdb->prepare(
				"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
				'course_category', 0
			) );

			if ( empty( $cats ) ) {
				$cats = array();
			}
			if ( !empty( $query ) ) {
				foreach ( $query as $key => $value ) {
					$cats[$value->term_id] = $value->name;
				}
			}

			return $cats;
		}

	}
}


function thim_courses_register_widget() {
	register_widget( 'Thim_Courses_Widget' );
}

add_action( 'widgets_init', 'thim_courses_register_widget' );