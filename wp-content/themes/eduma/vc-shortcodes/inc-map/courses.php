<?php

vc_map( array(

	'name'        => esc_html__( 'Thim: Courses', 'eduma' ),
	'base'        => 'thim-courses',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display courses.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-courses',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Heading text', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Limit', 'eduma' ),
			'param_name'  => 'limit',
			'min'         => 1,
			'max'         => 20,
			'std'         => '8',
			'description' => esc_html__( 'Limit number courses.', 'eduma' )
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Featured', 'eduma' ),
			'param_name'  => 'featured',
			'description' => esc_html__( 'Only display featured courses', 'eduma' ),
			'std'         => false,
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Order By', 'eduma' ),
			'param_name'  => 'order',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )   => '',
				esc_html__( 'Popular', 'eduma' )  => 'popular',
				esc_html__( 'Latest', 'eduma' )   => 'latest',
				esc_html__( 'Category', 'eduma' ) => 'category',
			),
			'description' => esc_html__( 'Select order by.', 'eduma' ),
		),

		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Select Category', 'eduma' ),
			'param_name' => 'cat_id',
			'value'      => thim_sc_get_course_categories( array( 'All' => esc_html__( 'all', 'eduma' ) ) ),
			'dependency' => array(
				'element' => 'order',
				'value'   => 'category',
			),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'Layout', 'eduma' ),
			'param_name'  => 'layout',
			'value'       => array(
				esc_html__( 'Select', 'eduma' )        => '',
				esc_html__( 'Slider', 'eduma' )        => 'slider',
				esc_html__( 'Grid', 'eduma' )          => 'grid',
                esc_html__( 'Grid 1', 'eduma' )  => 'grid1',
				esc_html__( 'Category Tabs', 'eduma' ) => 'tabs',
				esc_html__( 'Mega Menu', 'eduma' )     => 'megamenu',
				esc_html__( 'List Sidebar', 'eduma' )  => 'list-sidebar',
                esc_html__( 'Category Tabs Slider', 'eduma' )  => 'tabs-slider',
			),
		),

        array(
            'type'        => 'number',
            'admin_label' => true,
            'heading'     => esc_html__( 'Thumbnail Width', 'eduma' ),
            'param_name'  => 'thumbnail_width',
            'min'         => 100,
            'max'         => 800,
            'std'         => 400,
            'description' => esc_html__( 'Set width for thumbnail course.', 'eduma' ),
            'dependency'  => array(
                'element' => 'layout',
                'value'   => array( 'slider', 'grid', 'grid1', 'tabs', 'tabs-slider' ),
            ),
        ),

        array(
            'type'        => 'number',
            'admin_label' => true,
            'heading'     => esc_html__( 'Thumbnail Height', 'eduma' ),
            'param_name'  => 'thumbnail_height',
            'min'         => 100,
            'max'         => 800,
            'std'         => 300,
            'description' => esc_html__( 'Set height for thumbnail course.', 'eduma' ),
            'dependency'  => array(
                'element' => 'layout',
                'value'   => array( 'slider', 'grid', 'grid1', 'tabs', 'tabs-slider' ),
            ),
        ),

		//Slider Options
		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Items Visible', 'eduma' ),
			'param_name'  => 'slider_item_visible',
			'min'         => 1,
			'max'         => 20,
			'std'         => '4',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'slider',
			),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
			'param_name'  => 'slider_auto_play',
			'std'         => '0',
			'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'slider',
			),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Pagination', 'eduma' ),
			'param_name'  => 'slider_pagination',
			'std'         => false,
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'slider',
			),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Navigation', 'eduma' ),
			'param_name'  => 'slider_navigation',
			'std'         => true,
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'slider',
			),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		//Grid options
		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Grid Columns', 'eduma' ),
			'param_name'  => 'grid_columns',
			'min'         => 1,
			'max'         => 20,
			'std'         => '4',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array('grid', 'grid1'),
			),
			'group'       => esc_html__( 'Grid Settings', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text View All Courses', 'eduma' ),
			'param_name'  => 'view_all_courses',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array('grid', 'grid1', 'tabs-slider'),
			),
			'group'       => esc_html__( 'Grid Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => true,
			'heading'     => esc_html__( 'View All Position', 'eduma' ),
			'param_name'  => 'view_all_position',
			'value'       => array(
				esc_html__( 'Select', 'eduma' ) => '',
				esc_html__( 'Top', 'eduma' )    => 'top',
				esc_html__( 'Bottom', 'eduma' ) => 'bottom',
			),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array('grid', 'grid1', 'tabs-slider'),
			),
			'group'       => esc_html__( 'Grid Settings', 'eduma' ),
		),

		//Tabs options
		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Limit Tab', 'eduma' ),
			'param_name'  => 'limit_tab',
			'min'         => 1,
			'max'         => 20,
			'std'         => '4',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array('tabs', 'tabs-slider'),
			),
			'group'       => esc_html__( 'Tabs Settings', 'eduma' ),
		),

		array(
			'type'       => 'dropdown_multiple',
			'heading'    => esc_html__( 'Select Category Tabs', 'eduma' ),
			'param_name' => 'cat_id_tab',
			'std'        => 'all',
			'value'      => thim_sc_get_course_categories(),
			'dependency' => array(
				'element' => 'layout',
				'value'   => array('tabs', 'tabs-slider'),
			),
			'group'      => esc_html__( 'Tabs Settings', 'eduma' ),
		),

		//Animation
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'eduma' ),
			'param_name'  => 'css_animation',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'No', 'eduma' )                 => '',
				esc_html__( 'Top to bottom', 'eduma' )      => 'top-to-bottom',
				esc_html__( 'Bottom to top', 'eduma' )      => 'bottom-to-top',
				esc_html__( 'Left to right', 'eduma' )      => 'left-to-right',
				esc_html__( 'Right to left', 'eduma' )      => 'right-to-left',
				esc_html__( 'Appear from center', 'eduma' ) => 'appear'
			),
			'description' => esc_html__( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'eduma' )
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