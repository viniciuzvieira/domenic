<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: Carousel Posts', 'eduma' ),
	'base'        => 'thim-carousel-posts',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display posts with carousel.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-carousel-posts',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
		),

		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Select Category', 'eduma' ),
			'param_name' => 'cat_id',
			'std'        => 'all',
			'value'      => thim_sc_get_categories(),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Layout', 'eduma' ),
			'param_name'  => 'layout',
			'value'       => array(
				esc_html__( 'Default', 'eduma' )  => 'base',
				esc_html__( 'Layout 2', 'eduma' ) => 'layout-2',
				esc_html__( 'Layout 3', 'eduma' ) => 'layout-3',
			),
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Posts visible', 'eduma' ),
			'param_name'  => 'visible_post',
			'std'         => '3',
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Number posts', 'eduma' ),
			'param_name'  => 'number_posts',
			'std'         => '6',
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Navigation', 'eduma' ),
			'param_name'  => 'show_nav',
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
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
			'param_name'  => 'auto_play',
			'value'       => '',
			'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
			'std'         => '0',
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Order by', 'eduma' ),
			'param_name'  => 'orderby',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )  => '',
				esc_html__( 'Popular', 'eduma' ) => 'popular',
				esc_html__( 'Recent', 'eduma' )  => 'recent',
				esc_html__( 'Title', 'eduma' )   => 'title',
				esc_html__( 'Random', 'eduma' )  => 'random',
			),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Order', 'eduma' ),
			'param_name'  => 'order',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( 'ASC', 'eduma' )    => 'asc',
				esc_html__( 'DESC', 'eduma' )   => 'desc',
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