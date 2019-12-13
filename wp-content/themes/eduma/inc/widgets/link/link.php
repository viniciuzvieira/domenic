<?php

class Thim_Link_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'link',
			esc_html__( 'Thim: Link', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display link and description', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-link'
			),
			array(),
			array(
				'text'    => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Title', 'eduma' ),
					'default'               => esc_html__( "Title on here", 'eduma' ),
					'allow_html_formatting' => true
				),
				'link'    => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Link of title', 'eduma' ),
					'allow_html_formatting' => true
				),
				'content' => array(
					"type"                  => "textarea",
					"label"                 => esc_html__( "Add description", 'eduma' ),
					"default"               => esc_html__( "Write a short description, that will describe the title or something informational and useful.", 'eduma' ),
					'allow_html_formatting' => true
				),
			)
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

function thim_link_register_widget() {
	register_widget( 'Thim_Link_Widget' );
}

add_action( 'widgets_init', 'thim_link_register_widget' );