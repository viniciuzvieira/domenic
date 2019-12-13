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
function thim_shortcode_list_post( $atts ) {

	$instance = shortcode_atts( array(
		'title'            => '',
		'cat_id'           => '',
		'image_size'       => 'none',
		'img_w'       => '',
		'img_h'       => '',
		'show_description' => false,
		'number_posts'     => '4',
		'layout'           => 'base',
		'display_feature'  => '',
		'item_vertical'    => '0',
		'orderby'          => 'recent',
		'order'            => 'asc',
		'link'             => '',
		'text_link'        => '',
		'style'            => '',
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

	$widget_template       = THIM_DIR . 'inc/widgets/list-post/tpl/' . $layout;
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/list-post/' . $layout;
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-list-post">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-list-post', 'thim_shortcode_list_post' );


