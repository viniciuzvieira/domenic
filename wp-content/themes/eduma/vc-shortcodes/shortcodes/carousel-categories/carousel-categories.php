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
function thim_shortcode_carousel_categories( $atts ) {

	$instance = shortcode_atts( array(
		'title'              => '',
		'cat_id'             => '',
		'visible'            => '1',
		'post_limit'         => '6',
		'show_nav'           => '',
		'show_pagination'    => '',
		'auto_play'          => '0',
		'link_view_all'      => '',
		'text_view_all'      => '',
		'share_use_page_url' => '',
        'el_class'           => '',
	), $atts );


	if ( ! empty( $instance['cat_id'] ) ) {
		$instance['cat_id'] = explode( ',', $instance['cat_id'] );
	}

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/carousel-categories/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/carousel-categories/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}
	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-carousel-categories">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

add_shortcode( 'thim-carousel-categories', 'thim_shortcode_carousel_categories' );


