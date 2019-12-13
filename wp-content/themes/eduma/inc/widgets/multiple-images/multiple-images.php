<?php

class Thim_Multiple_Images_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'multiple-images',
			esc_html__( 'Thim: Multiple Images', 'eduma' ),
			array(
				'description'   => esc_html__( 'Add multiple images', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-multiple-images'
			),
			array(),
			array(
				'title' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Heading', 'eduma' ),
					'description' => esc_html__( 'Enter your heading.', 'eduma' )
				),

				'image' => array(
					'type'        => 'multimedia',
					'label'       => esc_html__( 'Image', 'eduma' ),
					'description' => esc_html__( 'Select image from media library.', 'eduma' )
				),

				'image_size'  => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image size', 'eduma' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full"', 'eduma' )
				),
				'image_link'  => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image Link', 'eduma' ),
					'description' => esc_html__( 'Enter URL if you want this image to have a link. These links are separated by comma (Ex: #,#,#,#)', 'eduma' )
				),
				'column'      => array(
					'type'    => 'select',
					'default' => '1',
					'label'   => esc_html__( 'Column', 'eduma' ),
					'options' => array(
						'1' => esc_html__( '1', 'eduma' ),
						'2' => esc_html__( '2', 'eduma' ),
						'3' => esc_html__( '3', 'eduma' ),
						'4' => esc_html__( '4', 'eduma' ),
						'5' => esc_html__( '5', 'eduma' ),
					),
				),
				'link_target' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Link Target', 'eduma' ),
					'options' => array(
						'_self'  => esc_html__( 'Same window', 'eduma' ),
						'_blank' => esc_html__( 'New window', 'eduma' ),
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


function thim_multiple_images_widget() {
	register_widget( 'Thim_Multiple_Images_Widget' );
}

add_action( 'widgets_init', 'thim_multiple_images_widget' );