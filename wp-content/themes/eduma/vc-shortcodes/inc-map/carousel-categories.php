<?php

$defaults = array(
	'title'      => esc_html__( 'Carousel Categories', 'eduma' ),
	'visible'    => '1',
	'post_limit' => '4',
	'auto_play'  => '0',
);

vc_map( array(
	'name'        => esc_html__( 'Thim: Carousel Categories', 'eduma' ),
	'base'        => 'thim-carousel-categories',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display categories with Carousel', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-carousel-categories',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Heading', 'eduma' ),
			'param_name'  => 'title',
			'std'         => $defaults['title'],
		),

		array(
			'type'        => 'autocomplete',
			'heading'     => __( 'Categories', 'eduma' ),
			'param_name'  => 'cat_id',
			'settings'    => array(
				'multiple'       => true,
				'min_length'     => 1,
				'unique_values'  => true,
				'display_inline' => true,
				'values'         => thim_sc_get_categories_autocomplete(),
			),
			'description' => __( 'Select categories', 'eduma' ),
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Visible items', 'eduma' ),
			'param_name'  => 'visible',
			'std'         => $defaults['visible'],
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Posts per category', 'eduma' ),
			'param_name'  => 'post_limit',
			'std'         => $defaults['post_limit'],
			'description' => esc_html__( 'Posts limit display on each category', 'eduma' ),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Navigation', 'eduma' ),
			'param_name'  => 'show_nav',
			'value'       => array(
				esc_html__( 'Yes', 'eduma' ) => 'yes',
			),
			'std'         => 'yes',
			'save_always' => true,
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Pagination', 'eduma' ),
			'param_name'  => 'show_pagination',
			'value'       => array(
				esc_html__( 'Yes', 'eduma' ) => 'yes',
			),
			'save_always' => true,
		),

		array(
			'type'        => 'number',
			'admin_label' => true,
			'heading'     => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
			'param_name'  => 'auto_play',
			'std'         => $defaults['auto_play'],
			'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
			'group'       => esc_html__( 'Slider Settings', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Link View All', 'eduma' ),
			'param_name'  => 'link_view_all',
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Text View All', 'eduma' ),
			'param_name'  => 'text_view_all',
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