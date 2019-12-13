<?php

$defaults = array(
	'layout'            => 'default',
	'limit'             => '7',
	'item_visible'      => '5',
	'autoplay'          => false,
	'mousewheel'        => false,
	'show_pagination'   => false,
	'show_navigation'   => true,
	'carousel_autoplay' => '0',
	'link_to_single'    => false,
);

vc_map( array(
	'name'        => esc_html__( 'Thim: Testimonial', 'eduma' ),
	'base'        => 'thim-testimonials',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display testimonials.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-testimonials',
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
				esc_html__( 'Select', 'eduma' )   => '',
				esc_html__( 'Default', 'eduma' )  => 'default',
                esc_html__( 'Slider', 'eduma' )  => 'slider',
                esc_html__( 'Slider 2', 'eduma' )  => 'slider-2',
				esc_html__( 'Carousel', 'eduma' ) => 'carousel',
			),
			'std'         => $defaults['layout'],
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Limit Posts', 'eduma' ),
			'param_name'  => 'limit',
			'std'         => $defaults['limit'],
		),

        array(
            'type'        => 'number',
            'admin_label' => false,
            'heading'     => esc_html__( 'Items padding', 'eduma' ),
            'param_name'  => 'activepadding',
            'std'         => '0',
            'dependency'  => array(
                'element' => 'layout',
                'value'   => array( 'slider', 'slider-2' ),
            ),
        ),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Items visible', 'eduma' ),
			'param_name'  => 'item_visible',
			'std'         => $defaults['item_visible'],
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Time', 'eduma' ),
			'param_name'  => 'pause_time',
			'std'         => 5000,
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Auto play', 'eduma' ),
			'param_name'  => 'autoplay',
			'std'         => $defaults['autoplay'],
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'default', 'slider', 'slider-2' ),
			),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Mousewheel Scroll', 'eduma' ),
			'param_name'  => 'mousewheel',
			'std'         => $defaults['mousewheel'],
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'default', 'slider', 'slider-2' ),
			),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Pagination', 'eduma' ),
			'param_name'  => 'show_pagination',
			'std'         => $defaults['show_pagination'],
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'carousel',
			),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Show Navigation', 'eduma' ),
			'param_name'  => 'show_navigation',
			'std'         => $defaults['show_navigation'],
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'carousel',
			),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
			'param_name'  => 'carousel_autoplay',
			'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
			'std'         => $defaults['carousel_autoplay'],
			'dependency'  => array(
				'element' => 'layout',
				'value'   => 'carousel',
			),
		),

		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Link To Single', 'eduma' ),
			'param_name'  => 'link_to_single',
			'std'         => $defaults['link_to_single'],
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