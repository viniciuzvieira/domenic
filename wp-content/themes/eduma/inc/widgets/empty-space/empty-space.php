<?php
class Thim_Empty_Space_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'empty-space',
			esc_html__( 'Thim: Empty Space', 'eduma' ),
			array(
				'description' => esc_html__( 'Add space width custom height', 'eduma' ),
				'help'        => '',
				'panels_groups' => array('thim_widget_group'),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-empty-space'
			),
			array(),
			array(
				'height' => array(
					'type'  => 'number',
					'label' => esc_html__( 'Height', 'eduma' ),
					'default'=>'30',
					'desc'  => esc_html__( "Enter empty space height.", 'eduma' ),
					'suffix'     => 'px',
				)
  			),
			THIM_DIR . 'inc/widgets/empty-space/'
		);
	}

	/**
	 * Initialize the CTA widget
	 */
	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}
}
function thim_empty_space_register_widget() {
	register_widget( 'Thim_Empty_Space_Widget' );
}

add_action( 'widgets_init', 'thim_empty_space_register_widget' );