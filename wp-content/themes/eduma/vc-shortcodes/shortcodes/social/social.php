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
function thim_shortcode_social( $atts ) {

	$instance = shortcode_atts( array(
		'title'          => '',
		'show_label'     => '',
		'style'          => '',
		'link_face'      => '',
		'link_twitter'   => '',
		'link_google'    => '',
		'link_dribble'   => '',
		'link_linkedin'  => '',
		'link_pinterest' => '',
		'link_instagram' => '',
		'link_youtube'   => '',
		'link_snapchat'  => '',
		'link_target'    => '_self',
        'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/social/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/social/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-social">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-social', 'thim_shortcode_social' );


