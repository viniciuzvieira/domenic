<?php

vc_map( array(
	'name'        => esc_html__( 'Thim: One Course Instructors', 'eduma' ),
	'base'        => 'thim-one-course-instructors',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display course instructors.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-one-course-instructors',
	'params'      => array(

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Visible instructors', 'eduma' ),
			'param_name'  => 'visible_item',
			'std'         => '3',
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Pagination', 'eduma' ),
			'param_name'  => 'show_pagination',
			'std'         => true,
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'slider',
			),
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
			'param_name'  => 'auto_play',
			'value'       => '',
			'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
			'std'         => '0',
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