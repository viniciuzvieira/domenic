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
function thim_shortcode_gallery_images( $atts ) {

	$instance = shortcode_atts( array(
		'title'           => '',
		'image'           => '',
		'image_size'      => '',
		'image_link'      => '',
        'have_color'      => '',
		'number'          => '4',
		'item_tablet'     => '2',
		'item_mobile'     => '1',
		'show_pagination' => '',
		'link_target'     => '',
		'css_animation'   => '',
        'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/gallery-images/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/gallery-images/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-gallery-images">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-gallery-images', 'thim_shortcode_gallery_images' );


