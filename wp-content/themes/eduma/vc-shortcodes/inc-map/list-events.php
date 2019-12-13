<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: List Events', 'eduma' ),
	'base'        => 'thim-list-events',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display List Events.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-list-event',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Number events display', 'eduma' ),
			'param_name'  => 'number_posts',
			'std'         => '2',
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Layout', 'eduma' ),
			'param_name'  => 'layout',
			'value'       => array(
				esc_html__( 'Default', 'eduma' )  => 'base',
				esc_html__( 'Slider', 'eduma' )   => 'slider',
				esc_html__( 'Layout 2', 'eduma' ) => 'layout-2',
				esc_html__( 'Layout 3', 'eduma' ) => 'layout-3',
                esc_html__( 'Layout 4', 'eduma' ) => 'layout-4',
			),
		),

        array(
            'type'       => 'dropdown_multiple',
            'heading'    => esc_html__( 'Select Category', 'eduma' ),
            'param_name' => 'cat_id',
            'value'      => thim_sc_get_event_categories( array( 'All' => esc_html__( 'all', 'eduma' ) ) ),
        ),

        array(
            'type'       => 'dropdown_multiple',
            'heading'     => esc_html__( 'Select Status', 'eduma' ),
            'param_name'  => 'status',
            'std'        => '',
            'value'       => array(
                esc_html__( 'Upcoming', 'eduma' )  => 'upcoming',
                esc_html__( 'Happening', 'eduma' )   => 'happening',
                esc_html__( 'Expired', 'eduma' ) => 'expired',
            ),
        ),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text View All', 'eduma' ),
			'param_name'  => 'text_link',
			'std'         => esc_html__( 'View All', 'eduma' ),
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