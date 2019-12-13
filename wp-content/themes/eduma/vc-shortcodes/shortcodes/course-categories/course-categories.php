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
function thim_shortcode_course_categories( $atts ) {

	$instance = shortcode_atts( array(
		'title'                             => '',
		'layout'                            => 'list',
		'slider_limit'                      => '15',
		'slider_show_pagination'            => false,
		'slider_show_navigation'            => true,
		'slider_item_visible'               => '7',
		'slider_item_small_desktop_visible' => '6',
		'slider_item_tablet_visible'        => '4',
		'slider_item_mobile_visible'        => '2',
		'slider_auto_play'                  => '0',
		'list_show_counts'                  => false,
		'list_hierarchical'                 => false,
		'el_class'                          => '',
	), $atts );


	$instance['slider-options']['limit']                                            = $instance['slider_limit'];
	$instance['slider-options']['show_pagination']                                  = $instance['slider_show_pagination'];
	$instance['slider-options']['show_navigation']                                  = $instance['slider_show_navigation'];
	$instance['slider-options']['auto_play']                                        = $instance['slider_auto_play'];
	$instance['slider-options']['responsive-options']['item_visible']               = $instance['slider_item_visible'];
	$instance['slider-options']['responsive-options']['item_small_desktop_visible'] = $instance['slider_item_small_desktop_visible'];
	$instance['slider-options']['responsive-options']['item_tablet_visible']        = $instance['slider_item_tablet_visible'];
	$instance['slider-options']['responsive-options']['item_mobile_visible']        = $instance['slider_item_mobile_visible'];
	$instance['list-options']['show_counts']                                        = $instance['list_show_counts'];
	$instance['list-options']['hierarchical']                                       = $instance['list_hierarchical'];

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

	$widget_template       = THIM_DIR . 'inc/widgets/course-categories/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/course-categories/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
	if ( $instance['el_class'] ) {
		echo '<div class="' . $instance['el_class'] . '">';
	}
	echo '<div class="thim-widget-course-categories">';
	include $widget_template;
	echo '</div>';
	if ( $instance['el_class'] ) {
		echo '</div>';
	}
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-course-categories', 'thim_shortcode_course_categories' );


