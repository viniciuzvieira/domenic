<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_login_form( $atts ) {

	$instance = shortcode_atts( array(
		'captcha'  => '',
		'term'     => '',
		'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$instance['captcha'] = ( ! empty( $instance['captcha'] ) ) ? 'yes' : 'no';

	$widget_template       = THIM_DIR . 'inc/widgets/login-form/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/login-form/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
	if ( $instance['el_class'] ) {
		echo '<div class="' . $instance['el_class'] . '">';
	}
	echo '<div class="thim-widget-login-form">';
	include $widget_template;
	echo '</div>';
	if ( $instance['el_class'] ) {
		echo '</div>';
	}
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-login-form', 'thim_shortcode_login_form' );
