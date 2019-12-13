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
function thim_shortcode_courses( $atts ) {

	$instance = shortcode_atts( array(
		'title'               => '',
		'limit'               => '8',
		'featured'            => false,
		'layout'              => 'slider',
		'thumbnail_width'     => 400,
		'thumbnail_height'    => 300,
		'order'               => '',
		'cat_id'              => '',
		'slider_pagination'   => '',
		'slider_navigation'   => '',
		'slider_item_visible' => '',
		'slider_auto_play'    => '0',
		'grid_columns'        => '4',
		'view_all_courses'    => '',
		'view_all_position'   => '',
		'limit_tab'           => '4',
		'cat_id_tab'          => '',
		'css_animation'       => '',
        'el_class'           => '',
	), $atts );

	$instance['slider-options']['show_pagination'] = $instance['slider_pagination'];
	$instance['slider-options']['show_navigation'] = $instance['slider_navigation'];
	$instance['slider-options']['item_visible']    = $instance['slider_item_visible'];
	$instance['slider-options']['auto_play']       = $instance['slider_auto_play'];

	$instance['grid-options']['columns'] = $instance['grid_columns'];

	$instance['tabs-options']['limit_tab']  = $instance['limit_tab'];
	$instance['tabs-options']['cat_id_tab'] = explode( ',', $instance['cat_id_tab'] );

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

	$widget_template       = THIM_DIR . 'inc/widgets/courses/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/courses/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}
	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-courses">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

add_shortcode( 'thim-courses', 'thim_shortcode_courses' );


