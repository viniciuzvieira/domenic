<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'THIM_Our_Team' ) ) {
	return;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_our_team( $atts ) {

	$instance = shortcode_atts( array(
		'cat_id'          => 'all',
		'number_post'     => '5',
		'layout'          => 'base',
		'show_pagination' => true,
		'text_link'       => '',
		'link'            => '',
		'link_member'     => false,
		'columns'         => '1',
		'css_animation'   => '',
        'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	if ( ! empty( $instance['layout'] ) ) {
		$layout = $instance['layout'] . '.php';
	} else {
		$layout = 'base.php';
	}

	$widget_template       = THIM_DIR . 'inc/widgets/our-team/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/our-team/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-our-team">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-our-team', 'thim_shortcode_our_team' );


