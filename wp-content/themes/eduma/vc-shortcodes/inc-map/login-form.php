<?php

vc_map( array(
	'name'        => esc_html__( 'Thim: Login Form', 'eduma' ),
	'base'        => 'thim-login-form',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display Login Form.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-login-form',
	'params'      => array(
		array(
			'type'        => 'checkbox',
			'admin_label' => true,
			'heading'     => esc_html__( 'Use Captcha?', 'eduma' ),
			'param_name'  => 'captcha',
			'desc'        => esc_html__( 'Use captcha in register and login form', 'eduma' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Terms of Service link', 'eduma' ),
			'param_name'  => 'term',
			'value'       => '',
			'description' => esc_html__( 'Leave empty to disable this field.', 'eduma' ),
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
