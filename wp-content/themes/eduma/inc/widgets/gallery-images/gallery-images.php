<?php

class Thim_Gallery_Images_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'gallery-images',
			esc_html__( 'Thim: Gallery Images', 'eduma' ),
			array(
				'description'   => esc_html__( 'Add gallery image', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-gallery-images'
			),
			array(),
			array(
				'title'               => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Heading', 'eduma' ),
					'allow_html_formatting' => true
				),
				'image' => array(
					'type'        => 'multimedia',
					'label'       => esc_html__( 'Image', 'eduma' ),
					'description' => esc_html__( 'Select image from media library.', 'eduma' )
				),

				'image_size'      => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image size', 'eduma' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full"', 'eduma' )
				),
				'image_link'      => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image Link', 'eduma' ),
					'description' => esc_html__( 'Enter URL if you want this image to have a link. These links are separated by comma (Ex: #,#,#,#)', 'eduma' )
				),
				'number'          => array(
					'type'    => 'number',
					'default' => '4',
					'label'   => esc_html__( 'Visible Items', 'eduma' ),
				),
				'item_tablet'          => array(
						'type'    => 'number',
						'default' => '2',
						'label'   => esc_html__( 'Tablet Items', 'eduma' ),
				),
				'item_mobile'          => array(
						'type'    => 'number',
						'default' => '1',
						'label'   => esc_html__( 'Mobile Items', 'eduma' ),
				),
				'have_color' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Color Image', 'eduma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => esc_html__( 'Yes', 'eduma' ),
						'no'  => esc_html__( 'No', 'eduma' ),
					)
				),
                'show_pagination' => array(
                    'type'    => 'radio',
                    'label'   => esc_html__( 'Show Pagination', 'eduma' ),
                    'default' => 'no',
                    'options' => array(
                        'yes' => esc_html__( 'Yes', 'eduma' ),
                        'no'  => esc_html__( 'No', 'eduma' ),
                    )
                ),
				'show_navigation' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Navigation', 'eduma' ),
					'default' => 'no',
					'options' => array(
						'yes' => esc_html__( 'Yes', 'eduma' ),
						'no'  => esc_html__( 'No', 'eduma' ),
					)
				),
                'auto_play'       => array(
                    'type'        => 'number',
                    'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
                    'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
                    'default'     => '0'
                ),
				'link_target'     => array(
					"type"    => "select",
					"label"   => esc_html__( "Link Target", 'eduma' ),
					"options" => array(
						"_self"  => esc_html__( "Same window", 'eduma' ),
						"_blank" => esc_html__( "New window", 'eduma' ),
					),
				),

				'css_animation' => array(
					"type"    => "select",
					"label"   => esc_html__( "CSS Animation", 'eduma' ),
					"options" => array(
						""              => esc_html__( "No", 'eduma' ),
						"top-to-bottom" => esc_html__( "Top to bottom", 'eduma' ),
						"bottom-to-top" => esc_html__( "Bottom to top", 'eduma' ),
						"left-to-right" => esc_html__( "Left to right", 'eduma' ),
						"right-to-left" => esc_html__( "Right to left", 'eduma' ),
						"appear"        => esc_html__( "Appear from center", 'eduma' )
					),
				),
			),
			THIM_DIR . 'inc/widgets/gallery-images/'
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


function thim_gallery_images_widget() {
	register_widget( 'Thim_Gallery_Images_Widget' );
}

add_action( 'widgets_init', 'thim_gallery_images_widget' );