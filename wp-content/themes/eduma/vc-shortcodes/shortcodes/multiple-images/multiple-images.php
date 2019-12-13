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
function thim_shortcode_multiple_images( $atts ) {

	$instance = shortcode_atts( array(
		'title'       => '',
		'image'       => '',
		'image_size'  => '',
		'image_link'  => '',
		'column'      => '1',
		'link_target' => '',
        'el_class' => '',
	), $atts );

	//$instance['image']          = $instance['font_size'];

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/multiple-images/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/multiple-images/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}
	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-multiple-images">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

add_shortcode( 'thim-multiple-images', 'thim_shortcode_multiple_images' );


