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
function thim_shortcode_one_course_instructors( $atts ) {

	$instance = shortcode_atts( array(
		'visible_item'    => '3',
		'show_pagination' => true,
		'auto_play'       => '0',
        'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	if ( thim_is_new_learnpress( '2.0' ) ) {
		$layout = 'base-v2.php';
	} else {
		$layout = 'base-v1.php';
	}

	$widget_template       = THIM_DIR . 'inc/widgets/one-course-instructors/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/one-course-instructors/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-one-course-instructors">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-one-course-instructors', 'thim_shortcode_one_course_instructors' );


