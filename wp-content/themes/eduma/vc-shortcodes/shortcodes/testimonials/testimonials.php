<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'THIM_Testimonials' ) ) {
	return;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_testimonials( $atts ) {

	$instance = shortcode_atts( array(
		'title'             => '',
		'layout'            => 'default',
		'limit'             => '7',
		'item_visible'      => '5',
		'pause_time'        => '5000',
		'autoplay'          => false,
		'mousewheel'        => false,
		'show_pagination'   => false,
		'show_navigation'   => true,
		'carousel_autoplay' => '0',
		'link_to_single'    => false,
        'el_class' => '',
	), $atts );


	$instance['carousel-options']['auto_play']       = $instance['carousel_autoplay'];
	$instance['carousel-options']['show_pagination'] = $instance['show_pagination'];
	$instance['carousel-options']['show_navigation'] = $instance['show_navigation'];

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	//$widget_layout = ( $instance['layout'] === 'carousel' ) ? 'carousel.php' : 'base.php';
    if ( isset($instance['layout']) && 'default' != $instance['layout'] ) {
        $widget_layout = $instance['layout'] . '.php';
    } else {
        $widget_layout = 'base.php';
    }

	$widget_template       = THIM_DIR . 'inc/widgets/testimonials/tpl/' . $widget_layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/testimonials/' . $widget_layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-testimonials">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-testimonials', 'thim_shortcode_testimonials' );


