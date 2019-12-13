<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: Social', 'eduma' ),
	'base'        => 'thim-social',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display social icon.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-social',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Style', 'eduma' ),
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Default', 'eduma' ) => '',
				esc_html__( 'Style 2', 'eduma' ) => 'style-2',
				esc_html__( 'Style 3', 'eduma' ) => 'style-3',
                esc_html__( 'Style 4', 'eduma' ) => 'style-4',
			),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show label', 'eduma' ),
			'param_name'  => 'show_label',
			'std'         => false,
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Facebook Url', 'eduma' ),
			'param_name'  => 'link_face',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Twitter Url', 'eduma' ),
			'param_name'  => 'link_twitter',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Google Url', 'eduma' ),
			'param_name'  => 'link_google',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Dribble Url', 'eduma' ),
			'param_name'  => 'link_dribble',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Linkedin Url', 'eduma' ),
			'param_name'  => 'link_linkedin',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Pinterest Url', 'eduma' ),
			'param_name'  => 'link_pinterest',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Instagram Url', 'eduma' ),
			'param_name'  => 'link_instagram',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Youtube Url', 'eduma' ),
			'param_name'  => 'link_youtube',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Snapchat Url', 'eduma' ),
			'param_name'  => 'link_snapchat',
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