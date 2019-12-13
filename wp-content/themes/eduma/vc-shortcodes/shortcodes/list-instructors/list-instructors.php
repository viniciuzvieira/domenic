<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode List Instructors
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_list_instructors( $atts ) {

	$instance = shortcode_atts( array(
		'title'           => '',
		'visible_item'    => '4',
        'layout'          => '',
        'panel'             => '',
		'show_pagination' => '',
        'el_class' => '',
	), $atts );

    $instance['panel'] = (array) vc_param_group_parse_atts( $instance['panel'] );

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

    if ( isset($instance['layout']) && '' != $instance['layout'] ) {
        $layout = $instance['layout'];
    } else {
        $layout = 'base';
    }

	$widget_template       = THIM_DIR . 'inc/widgets/list-instructors/tpl/' . $layout . '.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/list-instructors/' . $layout . '.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-list-instructors">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-list-instructors', 'thim_shortcode_list_instructors' );


