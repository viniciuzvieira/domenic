<?php

vc_map( array(
	'name'        => esc_html__( 'Thim: Portfolio', 'eduma' ),
	'base'        => 'thim-portfolio',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Thim Widget Portfolio By thimpress.com', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-portfolio',
	'params'      => array(
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Select Category', 'eduma' ),
			'param_name' => 'portfolio_category',
			'value'      => thim_sc_get_portfolio_categories(),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Hide Filters?', 'eduma' ),
			'param_name'  => 'filter_hiden',
			//'value'       => array( esc_html__( '', 'eduma' ) => 'yes' ),
			'std'         => false,
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Select a filter position', 'eduma' ),
			'param_name'  => 'filter_position',
			'value'       => array(
				esc_html__( 'Left', 'eduma' )   => 'left',
				esc_html__( 'Center', 'eduma' ) => 'center',
				esc_html__( 'Right', 'eduma' )  => 'right',
			),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Select a column', 'eduma' ),
			'param_name'  => 'column',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( 'One', 'eduma' )    => 'one',
				esc_html__( 'Two', 'eduma' )    => 'two',
				esc_html__( 'Three', 'eduma' )  => 'three',
				esc_html__( 'Four', 'eduma' )   => 'four',
				esc_html__( 'Five', 'eduma' )   => 'five',
			),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Gutter', 'eduma' ),
			'param_name'  => 'gutter',
			//'value'       => array( esc_html__( '', 'eduma' ) => 'yes' ),
			'std'         => false,
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Select a item size', 'eduma' ),
			'param_name'  => 'item_size',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )    => '',
				esc_html__( 'Multigrid', 'eduma' ) => 'multigrid',
				esc_html__( 'Masonry', 'eduma' )   => 'masonry',
				esc_html__( 'Same size', 'eduma' ) => 'same',
			),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Select a paging', 'eduma' ),
			'param_name'  => 'paging',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )          => '',
				esc_html__( 'Show All', 'eduma' )        => 'all',
				esc_html__( 'Limit Items', 'eduma' )     => 'limit',
				esc_html__( 'Paging', 'eduma' )          => 'paging',
				esc_html__( 'Infinite Scroll', 'eduma' ) => 'infinite_scroll',
			),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Select style items', 'eduma' ),
			'param_name'  => 'style-item',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )                   => '',
				esc_html__( 'Caption Hover Effects 01', 'eduma' ) => 'style01',
				esc_html__( 'Caption Hover Effects 02', 'eduma' ) => 'style02',
				esc_html__( 'Caption Hover Effects 03', 'eduma' ) => 'style03',
				esc_html__( 'Caption Hover Effects 04', 'eduma' ) => 'style04',
				esc_html__( 'Caption Hover Effects 05', 'eduma' ) => 'style05',
				esc_html__( 'Caption Hover Effects 06', 'eduma' ) => 'style06',
				esc_html__( 'Caption Hover Effects 07', 'eduma' ) => 'style07',
				esc_html__( 'Caption Hover Effects 08', 'eduma' ) => 'style08',
			),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Enter a number view', 'eduma' ),
			'param_name'  => 'num_per_view',
			'value'       => '',
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Read More?', 'eduma' ),
			'param_name'  => 'show_readmore',
			//'value'       => array( esc_html__( '', 'eduma' ) => 'yes' ),
			'std'         => false,
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