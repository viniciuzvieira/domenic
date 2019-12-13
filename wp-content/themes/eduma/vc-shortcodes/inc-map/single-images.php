<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: Single Image', 'eduma' ),
	'base'        => 'thim-single-images',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display single images.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-single-images',
	'params'      => array(
		array(
			'type'        => 'attach_image',
			'admin_label' => true,
			'heading'     => esc_html__( 'Image', 'eduma' ),
			'param_name'  => 'image',
			'description' => esc_html__( 'Select image from media library.', 'eduma' )
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Image size', 'eduma' ),
			'param_name'  => 'image_size',
			'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Image Link', 'eduma' ),
			'param_name'  => 'image_link',
			'description' => esc_html__( 'Enter URL if you want this image to have a link.', 'eduma' ),
		),

		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Link Target', 'eduma' ),
			'param_name' => 'link_target',
			'value'      => array(
				esc_html__( 'Same window', 'eduma' ) => '_self',
				esc_html__( 'New window', 'eduma' )  => '_blank',
			)
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Image alignment', 'eduma' ),
			'param_name'  => 'image_alignment',
			'description' => esc_html__( 'Select image alignment.', 'eduma' ),
			'value'       => array(
				esc_html__( 'Align Left', 'eduma' )   => 'left',
				esc_html__( 'Align Center', 'eduma' ) => 'center',
				esc_html__( 'Align Right', 'eduma' )  => 'right',
			)
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'eduma' ),
			'param_name'  => 'css_animation',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'No', 'eduma' )                 => '',
				esc_html__( 'Top to bottom', 'eduma' )      => 'top-to-bottom',
				esc_html__( 'Bottom to top', 'eduma' )      => 'bottom-to-top',
				esc_html__( 'Left to right', 'eduma' )      => 'left-to-right',
				esc_html__( 'Right to left', 'eduma' )      => 'right-to-left',
				esc_html__( 'Appear from center', 'eduma' ) => 'appear'
			),
			'description' => esc_html__( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'eduma' )
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