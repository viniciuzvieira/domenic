<?php

vc_map( array(
	'name'        => esc_html__( 'Thim: Timetable', 'eduma' ),
	'base'        => 'thim-timetable',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display Timetable.', 'eduma' ),
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
			'type'        => 'param_group',
			'admin_label' => true,
			'heading'     => esc_html__( 'Timetable Items', 'eduma' ),
			'param_name'  => 'panel',
			'params'      => array(
				array(
					'type'       => 'textfield',
					'value'      => '',
					'heading'    => esc_html__( 'Course Title', 'eduma' ),
					'std'        => esc_html__( 'Course Title', 'eduma' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'textfield',
					'value'      => '',
					'heading'    => esc_html__( 'Time Activity', 'eduma' ),
					'std'        => esc_html__( '8:00 AM - 10:00 AM', 'eduma' ),
					'param_name' => 'time',
				),

				array(
					'type'       => 'textfield',
					'value'      => '',
					'heading'    => esc_html__( 'Teacher Name', 'eduma' ),
					'std'        => esc_html__( 'Mr John Doe', 'eduma' ),
					'param_name' => 'teacher',
				),

				array(
					'type'       => 'textfield',
					'value'      => '',
					'heading'    => esc_html__( 'Location', 'eduma' ),
					'std'        => esc_html__( 'Playground', 'eduma' ),
					'param_name' => 'location',
				),

				array(
					'type'        => 'colorpicker',
					'admin_label' => true,
					'heading'     => esc_html__( 'Background Color', 'eduma' ),
					'param_name'  => 'background',
				),

				array(
					'type'        => 'colorpicker',
					'admin_label' => true,
					'heading'     => esc_html__( 'Background Hover Color', 'eduma' ),
					'param_name'  => 'background_hover',
				),

				array(
					'type'        => 'dropdown',
					'admin_label' => true,
					'heading'     => esc_html__( 'Color Style', 'eduma' ),
					'param_name'  => 'color_style',
					'value'       => array(
						esc_html__( 'Select', 'eduma' ) => '',
						esc_html__( 'Light', 'eduma' )  => 'light',
						esc_html__( 'Dark', 'eduma' )   => 'dark',
						esc_html__( 'Gray', 'eduma' )   => 'gray',
					),
					'description' => esc_html__( "Select Color Style.", 'eduma' ),
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