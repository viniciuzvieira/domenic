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
function thim_shortcode_countdown_box( $atts ) {

	$instance = shortcode_atts( array(
		'text_days'    => esc_html__( 'days', 'eduma' ),
		'text_hours'   => esc_html__( 'hours', 'eduma' ),
		'text_minutes' => esc_html__( 'minutes', 'eduma' ),
		'text_seconds' => esc_html__( 'seconds', 'eduma' ),
		'time_year'    => '',
		'time_month'   => '',
		'time_day'     => '',
		'time_hour'    => '',
        'layout'       => '',
		'style_color'  => '',
		'text_align'   => '',
        'el_class'           => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

    if ( isset($instance['layout']) && '' != $instance['layout'] ) {
        $layout = $instance['layout'];
    } else {
        $layout = 'base';
    }

	$widget_template       = THIM_DIR . 'inc/widgets/countdown-box/tpl/' . $layout . '.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/countdown-box/' . $layout . '.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-countdown-box">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-countdown-box', 'thim_shortcode_countdown_box' );


