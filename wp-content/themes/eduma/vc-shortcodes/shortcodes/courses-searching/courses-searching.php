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
function thim_shortcode_courses_searching( $atts ) {

	$instance = shortcode_atts( array(
		'layout'      => 'base',
		'title'       => esc_html__( 'Search Courses', 'eduma' ),
		'description' => esc_html__( 'Description for search course.', 'eduma' ),
		'placeholder' => esc_html__( 'What do you want to learn today?', 'eduma' ),
        'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

    if ( thim_is_new_learnpress( '3.0' ) ) {
        $layout = $instance['layout'] . '-v3.php';
    } else if ( thim_is_new_learnpress( '2.0' ) ) {
        $layout = $instance['layout'] . '-v2.php';
    } else {
        $layout = $instance['layout'] . '-v1.php';
    }

	$widget_template       = THIM_DIR . 'inc/widgets/courses-searching/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/courses-searching/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-courses-searching">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-courses-searching', 'thim_shortcode_courses_searching' );


