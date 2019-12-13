<?php

vc_map( array(
	'name'        => esc_html__( 'Thim: Tab', 'eduma' ),
	'base'        => 'thim-tab',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display Tab.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-timetable',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
		),

        array(
            'type'        => 'dropdown',
            'admin_label' => true,
            'heading'     => esc_html__( 'Layout', 'eduma' ),
            'param_name'  => 'layout',
            'value'       => array(
                esc_html__( 'Default', 'eduma' )        => '',
                esc_html__( 'Step', 'eduma' )        => 'step',
            ),
        ),

		array(
			'type'        => 'param_group',
			'admin_label' => false,
			'heading'     => esc_html__( 'Tab Items', 'eduma' ),
			'param_name'  => 'tab',
			'params'      => array(
				array(
					'type'       => 'textfield',
                    'admin_label' => true,
					'value'      => '',
					'heading'    => esc_html__( 'Title', 'eduma' ),
					'std'        => esc_html__( 'Title', 'eduma' ),
					'param_name' => 'title',
				),
                array(
                    'type'        => 'colorpicker',
                    'admin_label' => false,
                    'heading'     => esc_html__( 'Background Title', 'eduma' ),
                    'param_name'  => 'bg_title',
                    'value'       => '',
                    'description' => esc_html__( 'Select the color background for title.', 'eduma' ),
                ),
                array(
                    'type'        => 'textarea',
                    'admin_label' => false,
                    'heading'     => esc_html__( 'Content', 'eduma' ),
                    'param_name'  => 'content',
                    'std'         => esc_html__( 'Write a short description, that will describe the title or something informational and useful.', 'eduma' ),
                ),
                array(
                    'type'        => 'textfield',
                    'admin_label' => false,
                    'heading'     => esc_html__( 'Link', 'eduma' ),
                    'param_name'  => 'link',
                    'value'       => '',
                    'description' => '',
                ),

			)
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