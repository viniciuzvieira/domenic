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
function thim_shortcode_button( $atts ) {

	$instance = shortcode_atts( array(
		'title'              => '',
		'url'                => '8',
		'new_window'         => false,
		'custom_style'       => 'default',
		'font_size'          => '14',
		'font_weight'        => '',
		'border_width'       => '',
		'color'              => '',
		'border_color'       => '',
		'bg_color'           => '',
		'hover_color'        => '',
		'hover_border_color' => '',
		'hover_bg_color'     => '',
		'icon'               => '',
		'icon_size'          => '14',
		'icon_position'      => '',
		'button_size'        => 'normal',
		'rounding'           => '',
        'el_class'           => '',
	), $atts );

	$instance['style_options']['font_size']          = $instance['font_size'];
	$instance['style_options']['font_weight']        = $instance['font_weight'];
	$instance['style_options']['border_width']       = $instance['border_width'];
	$instance['style_options']['color']              = $instance['color'];
	$instance['style_options']['border_color']       = $instance['border_color'];
	$instance['style_options']['bg_color']           = $instance['bg_color'];
	$instance['style_options']['hover_color']        = $instance['hover_color'];
	$instance['style_options']['hover_border_color'] = $instance['hover_border_color'];
	$instance['style_options']['hover_bg_color']     = $instance['hover_bg_color'];

	$new_icon                          = str_replace( 'fa fa-', '', $instance['icon'] );
	$instance['icon']                  = array();
	$instance['icon']['icon']          = $new_icon;
	$instance['icon']['icon_size']     = $instance['icon_size'];
	$instance['icon']['icon_position'] = $instance['icon_position'];

	$instance['layout']['button_size'] = $instance['button_size'];
	$instance['layout']['rounding']    = $instance['rounding'];

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/button/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/button/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}
	ob_start();
	if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-button">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

add_shortcode( 'thim-button', 'thim_shortcode_button' );


