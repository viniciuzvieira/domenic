<?php

$defaults = array(
	'text' => esc_html__( 'Title on here', 'eduma' ),
);

vc_map( array(
	'name'        => esc_html__( 'Thim: Link', 'eduma' ),
	'base'        => 'thim-link',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display link and description', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-link',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'text',
			'std'         => $defaults['text'],
			'save_always' => true,
		),

		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Link of title', 'eduma' ),
			'param_name'  => 'link',
			'save_always' => true,
		),

		array(
			'type'        => 'textarea',
			'heading'     => esc_html__( 'Add description', 'eduma' ),
			'param_name'  => 'description',
			'value'       => esc_html__( 'Write a short description, that will describe the title or something informational and useful.', 'eduma' ),
			'save_always' => true,
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