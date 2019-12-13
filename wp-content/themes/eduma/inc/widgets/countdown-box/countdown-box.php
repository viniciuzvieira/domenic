<?php

class Thim_Countdown_Box_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'countdown-box',
			esc_html__( 'Thim: Countdown Box', 'eduma' ),
			array(
				'description'   => esc_html__( 'Countdown Box', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-countdown-box'
			),
			array(),
			array(
				'text_days'    => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Text Days', 'eduma' ),
					'default' => esc_html__( 'days', 'eduma' ),
				),
				'text_hours'   => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Text Hours', 'eduma' ),
					'default' => esc_html__( 'hours', 'eduma' ),
				),
				'text_minutes' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Text Minutes', 'eduma' ),
					'default' => esc_html__( 'minutes', 'eduma' ),
				),
				'text_seconds' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Text Seconds', 'eduma' ),
					'default' => esc_html__( 'seconds', 'eduma' ),
				),
				'time_year'    => array(
					'type'  => 'text',
					'label' => esc_html__( 'Year', 'eduma' ),
				),
				'time_month'   => array(
					'type'  => 'text',
					'label' => esc_html__( 'Month', 'eduma' ),
				),
				'time_day'     => array(
					'type'  => 'text',
					'label' => esc_html__( 'Day', 'eduma' ),
				),
				'time_hour'    => array(
					'type'  => 'text',
					'label' => esc_html__( 'Hour', 'eduma' ),
				),
				'layout'       => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Layout', 'eduma' ),
					'options' => array(
						''             => esc_html__( 'Default', 'eduma' ),
						'pie'          => esc_html__( 'Pie', 'eduma' ),
						'pie-gradient' => esc_html__( 'Pie Gradient', 'eduma' ),
					),
				),
				'style_color'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Style Color', 'eduma' ),
					'options' => array(
						'white' => esc_html__( 'White', 'eduma' ),
						'black' => esc_html__( 'Black', 'eduma' ),
					),
				),
				'text_align'   => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Text Align', 'eduma' ),
					'options' => array(
						'text-left'   => esc_html__( 'Text Left', 'eduma' ),
						'text-center' => esc_html__( 'Text Center', 'eduma' ),
						'text-right'  => esc_html__( 'Text Right', 'eduma' ),
					),
				)
			),
			THIM_DIR . 'inc/widgets/countdown-box/'
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	function get_template_name( $instance ) {
		if ( isset( $instance['layout'] ) && '' != $instance['layout'] ) {
			return $instance['layout'];
		} else {
			return 'base';
		}
	}

	function get_style_name( $instance ) {
		return false;
	}
}

function thim_countdown_box_widget() {
	register_widget( 'Thim_Countdown_Box_Widget' );
}

add_action( 'widgets_init', 'thim_countdown_box_widget' );