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
function thim_shortcode_link( $atts ) {

	$instance = shortcode_atts( array(
		'text'        => '',
		'link'        => '',
		'description' => '',
        'el_class' => '',
	), $atts );

	$instance['content'] = $instance['description'];

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/link/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/link/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}
	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-link">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

add_shortcode( 'thim-link', 'thim_shortcode_link' );


