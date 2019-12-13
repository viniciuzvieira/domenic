<?php

$defaults = array(
	'title'  => esc_html__( 'Enter your heading.', 'eduma' ),
	'column' => '1',
);

vc_map( array(
	'name'        => esc_html__( 'Thim: Multiple Images', 'eduma' ),
	'base'        => 'thim-multiple-images',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Add multiple images', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-multiple-images',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Heading', 'eduma' ),
			'param_name'  => 'title',
		),

		array(
			'type'        => 'attach_images',
			'admin_label' => true,
			'heading'     => esc_html__( 'Image', 'eduma' ),
			'description' => esc_html__( 'Select image from media library.', 'eduma' ),
			'param_name'  => 'image',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Image size', 'eduma' ),
			'param_name'  => 'image_size',
			'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full"', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Images url', 'eduma' ),
			'param_name'  => 'image_link',
			'description' => esc_html__( 'Enter URL if you want this image to have a link. These links are separated by comma (Ex: #,#,#,#)', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Column', 'eduma' ),
			'param_name'  => 'column',
			'description' => esc_html__( 'Number of columns display', 'eduma' ),
			'value'       => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
			),
			'std'         => $defaults['column']
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Link Target', 'eduma' ),
			'param_name'  => 'link_target',
			'description' => esc_html__( 'Select Custom Font Weight', 'eduma' ),
			'value'       => array(
				esc_html__( 'Select' )               => '',
				esc_html__( 'Same window', 'eduma' ) => '_self',
				esc_html__( 'New window', 'eduma' )  => '_blank',
			),
		),

        // Extra class
        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'Extra class', 'eduma' ),
            'param_name'  => 'el_class',
            'value'       => '',
            'description' => esc_html__( 'Add extra class name that will be applied to the icon box, and you can use this class for your customizations.', 'eduma' ),
        ),
	)
) );