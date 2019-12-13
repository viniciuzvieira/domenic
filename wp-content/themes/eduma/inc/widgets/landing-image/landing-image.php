<?php

class Thim_Landing_Image_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'landing-image',
			esc_html__( 'Thim: Landing Image', 'eduma' ),
			array(
				'description'   => esc_html__( 'Add heading text', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-landing-image'
			),
			array(),
			array(
				'title'       => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Title', 'eduma' ),
					'allow_html_formatting' => true
				),
				'image'       => array(
					'type'        => 'media',
					'label'       => esc_html__( 'Image', 'eduma' ),
					'description' => esc_html__( 'Select image from media library.', 'eduma' )
				),
				'link'        => array(
					'type'  => 'text',
					'label' => esc_html__( 'Link', 'eduma' ),
				),
				'link_target' => array(
					"type"    => "select",
					"label"   => esc_html__( "Link Target", 'eduma' ),
					"options" => array(
						"_self"  => esc_html__( "Same window", 'eduma' ),
						"_blank" => esc_html__( "New window", 'eduma' ),
					),
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

function thim_landing_image_register_widget() {
	register_widget( 'Thim_Landing_Image_Widget' );
}

add_action( 'widgets_init', 'thim_landing_image_register_widget' );