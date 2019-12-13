<?php

vc_map( array(
	'name'        => esc_html__( 'Thim: Countdown Box', 'eduma' ),
	'base'        => 'thim-countdown-box',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display Countdown Box.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-countdown-box',
	'params'      => array(

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text Days', 'eduma' ),
			'param_name'  => 'text_days',
			'std'         => esc_html__( 'days', 'eduma' ),
			'group'       => esc_html__( 'Text Settings', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text Hours', 'eduma' ),
			'param_name'  => 'text_hours',
			'std'         => esc_html__( 'hours', 'eduma' ),
			'group'       => esc_html__( 'Text Settings', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text Minutes', 'eduma' ),
			'param_name'  => 'text_minutes',
			'std'         => esc_html__( 'minutes', 'eduma' ),
			'group'       => esc_html__( 'Text Settings', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text Seconds', 'eduma' ),
			'param_name'  => 'text_seconds',
			'std'         => esc_html__( 'seconds', 'eduma' ),
			'group'       => esc_html__( 'Text Settings', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Year', 'eduma' ),
			'param_name'  => 'time_year',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Month', 'eduma' ),
			'param_name'  => 'time_month',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Day', 'eduma' ),
			'param_name'  => 'time_day',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Hour', 'eduma' ),
			'param_name'  => 'time_hour',
		),

        array(
            'type'        => 'dropdown',
            'admin_label' => true,
            'heading'     => esc_html__( 'Layout', 'eduma' ),
            'param_name'  => 'layout',
            'value'       => array(
                esc_html__( 'Default', 'eduma' )         => '',
                esc_html__( 'Pie', 'eduma' )   => 'pie',
                esc_html__( 'Pie Gradient', 'eduma' )   => 'pie-gradient',

            ),
        ),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Style Color', 'eduma' ),
			'param_name'  => 'style_color',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( 'White', 'eduma' )  => 'white',
				esc_html__( 'Black', 'eduma' )  => 'black',
			),
			'group'       => esc_html__( 'Text Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text alignment', 'eduma' ),
			'param_name'  => 'text_align',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )         => '',
				esc_html__( 'Text at left', 'eduma' )   => 'text-left',
				esc_html__( 'Text at center', 'eduma' ) => 'text-center',
				esc_html__( 'Text at right', 'eduma' )  => 'text-right',
			),
			'group'       => esc_html__( 'Text Settings', 'eduma' ),
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