<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: Courses Searching', 'eduma' ),
	'base'        => 'thim-courses-searching',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display courses search box.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-courses-searching',
	'params'      => array(


		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Layout', 'eduma' ),
			'param_name'  => 'layout',
			'value'       => array(
				esc_html__( 'Default', 'eduma' ) => 'base',
				esc_html__( 'Overlay', 'eduma' ) => 'overlay',
			),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'std'         => esc_html__( 'Search Courses', 'eduma' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'base',
			),
		),

		array(
			'type'        => 'textarea',
			'admin_label' => true,
			'heading'     => esc_html__( 'Description', 'eduma' ),
			'param_name'  => 'description',
			'std'         => esc_html__( 'Description for search course.', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Placeholder Input', 'eduma' ),
			'param_name'  => 'placeholder',
			'std'         => esc_html__( 'What do you want to learn today?', 'eduma' ),
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