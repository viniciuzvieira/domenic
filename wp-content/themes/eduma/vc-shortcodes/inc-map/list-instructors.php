<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: List Instructors', 'eduma' ),
	'base'        => 'thim-list-instructors',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display List Instructors.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-one-course-instructors',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Heading', 'eduma' ),
			'param_name'  => 'title',
			'description' => esc_html__( 'Write the heading.', 'eduma' )
		),

        array(
            'type'        => 'dropdown',
            'admin_label' => true,
            'heading'     => esc_html__( 'Layout', 'eduma' ),
            'param_name'  => 'layout',
            'value'       => array(
                esc_html__( 'Default', 'eduma' )        => '',
                esc_html__( 'New', 'eduma' )        => 'new',
            ),
        ),

        array(
            'type'        => 'param_group',
            'admin_label' => false,
            'heading'     => esc_html__( 'Tab Items', 'eduma' ),
            'param_name'  => 'panel',
            'params'      => array(
                array(
                    'type'        => 'attach_image',
                    'admin_label' => false,
                    'heading'     => esc_html__( 'Image', 'eduma' ),
                    'param_name'  => 'panel_img',
                    'std'         => '14',
                    'description' => esc_html__( 'Select image', 'eduma' ),
                ),
                array(
                    'type'       => 'dropdown',
                    'heading'    => esc_html__( 'Select Instructor', 'eduma' ),
                    'param_name' => 'panel_id',
                    'value'      => thim_sc_get_instructors(),
                ),
            ),
            'dependency'  => array(
                'element' => 'layout',
                'value'   => 'new',
            ),
            'group'       => esc_html__( 'Tab Settings', 'eduma' ),
        ),

		array(
			'type'        => 'number',
			'admin_label' => false,
			'heading'     => esc_html__( 'Visible Items', 'eduma' ),
			'param_name'  => 'visible_item',
			'std'         => '4',
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => false,
			'heading'     => esc_html__( 'Show Pagination', 'eduma' ),
			'param_name'  => 'show_pagination',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( 'Yes', 'eduma' )    => 'yes',
				esc_html__( 'No', 'eduma' )     => 'no',
			),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
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