<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: Gallery Images', 'eduma' ),
	'base'        => 'thim-gallery-images',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display Gallery Images.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-gallery-images',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Heading', 'eduma' ),
			'param_name'  => 'title',
			'description' => esc_html__( 'Write the heading.', 'eduma' )
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
			'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full"', 'eduma' )
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Image Link', 'eduma' ),
			'param_name'  => 'image_link',
			'description' => esc_html__( 'Enter URL if you want this image to have a link. These links are separated by comma (Ex: #,#,#,#)', 'eduma' )
		),

        array(
            'type'        => 'dropdown',
            'admin_label' => true,
            'heading'     => esc_html__( 'Have Color?', 'eduma' ),
            'param_name'  => 'have_color',
            'value'       => array(
                esc_html__( 'Select', 'eduma' ) => '',
                esc_html__( 'Yes', 'eduma' )    => 'yes',
                esc_html__( 'No', 'eduma' )     => 'no',
            ),
            'std'         => 'yes',
        ),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Visible Items', 'eduma' ),
			'param_name'  => 'number',
			'std'         => '4',
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Tablet Items', 'eduma' ),
			'param_name'  => 'item_tablet',
			'std'         => '2',
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Mobile Items', 'eduma' ),
			'param_name'  => 'item_mobile',
			'std'         => '1',
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Pagination', 'eduma' ),
			'param_name'  => 'show_pagination',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( 'Yes', 'eduma' )    => 'yes',
				esc_html__( 'No', 'eduma' )     => 'no',
			),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

        array(
            'type'        => 'dropdown',
            'admin_label' => true,
            'heading'     => esc_html__( 'Show Navigation', 'eduma' ),
            'param_name'  => 'show_navigation',
            'value'       => array(
                esc_html__( 'Select', 'eduma' ) => '',
                esc_html__( 'Yes', 'eduma' )    => 'yes',
                esc_html__( 'No', 'eduma' )     => 'no',
            ),
            'group'       => esc_html__( 'Slider Settings', 'eduma' ),
        ),

        array(
            'type'        => 'number',
            'admin_label' => true,
            'heading'     => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
            'param_name'  => 'auto_play',
            'std'         => '0',
            'group'       => esc_html__( 'Slider Settings', 'eduma' ),
            'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' )
        ),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Link Target', 'eduma' ),
			'param_name'  => 'link_target',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )      => '',
				esc_html__( 'Same window', 'eduma' ) => '_self',
				esc_html__( 'New window', 'eduma' )  => '_blank',
			),
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