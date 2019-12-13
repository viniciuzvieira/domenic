<?php

/**
 * Class Courses_Widget
 *
 * Widget Name: Courses Collection
 *
 * Author: Anh Minh
 */
class Thim_Courses_Collection_Widget extends Thim_Widget {
	function __construct() {

		parent::__construct(
			'courses-collection',
			esc_html__( 'Thim: Courses Collection', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display list courses collection', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-courses-collection'
			),
			array(),
			array(
				'title'         => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Heading Text', 'eduma' ),
					'default'               => esc_html__( 'Collection Courses', 'eduma' ),
					'allow_html_formatting' => true
				),
				'layout'        => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Layout', 'eduma' ),
					'default' => 'base',
					'options' => array(
						''       => esc_html__( 'Default', 'eduma' ),
						'slider' => esc_html__( 'Slider', 'eduma' ),
					)
				),
				'limit'         => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Limit collections', 'eduma' ),
					'default' => '8'
				),
				'columns'       => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Columns', 'eduma' ),
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
					),
					'default' => '3'
				),
				'feature_items' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Feature Items', 'eduma' ),
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
					),
					'default' => '2'
				),


			)
		);
	}

	function get_template_name( $instance ) {
		if ( isset( $instance['layout'] ) && $instance['layout'] != '' ) {
			if ( thim_is_new_learnpress( '3.0' ) ) {
				return $instance['layout'] . '-v3';
			} elseif ( thim_is_new_learnpress( '2.0' ) ) {
				return $instance['layout'] . '-v2';
			} else {
				return $instance['layout'];
			}
		} else {
			if ( thim_is_new_learnpress( '3.0' ) ) {
				return 'base-v3';
			} elseif ( thim_is_new_learnpress( '2.0' ) ) {
				return 'base-v2';
			} else {
				return 'base';
			}
		}
	}

	function get_style_name( $instance ) {
		return false;
	}

	// Get list category
	function thim_get_course_categories() {
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

		$cats        = array();
		$cats['all'] = esc_html__( 'All', 'eduma' );
		if ( ! empty( $query ) ) {
			foreach ( $query as $key => $value ) {
				$cats[ $value->term_id ] = $value->name;
			}
		}

		return $cats;
	}

}

function thim_courses_collection_register_widget() {
	register_widget( 'Thim_Courses_Collection_Widget' );
}

add_action( 'widgets_init', 'thim_courses_collection_register_widget' );