<?php

class Thim_Login_Form_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'login-form',
			esc_html__( 'Thim: Login Form', 'eduma' ),
			array(
				'description'   => esc_html__( 'Add login form', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-login-form'
			),
			array(),
			array(
				'captcha' => array(
					'type'        => 'radio',
					'label'       => esc_html__( 'Use Captcha?', 'eduma' ),
					'description' => esc_html__( 'Use captcha in register and login form', 'eduma' ),
					'default'     => 'no',
					'options'     => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'term'          => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Terms of Service link', 'eduma' ),
					'description' => esc_html__( 'Leave empty to disable this field.', 'eduma' ),
					'default'     => '',
				)
			),

			THIM_DIR . 'inc/widgets/login-form/'
		);
	}

	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}

}

function thim_login_form_widget() {
	register_widget( 'Thim_Login_Form_Widget' );
}

add_action( 'widgets_init', 'thim_login_form_widget' );