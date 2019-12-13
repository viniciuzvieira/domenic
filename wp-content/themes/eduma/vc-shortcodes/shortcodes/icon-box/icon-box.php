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
function thim_shortcode_icon_box( $atts ) {

	$icon_box = shortcode_atts( array(
		'title'                      => '',
		'title_color'                => '',
		'title_size'                 => 'h3',
		'title_font_heading'         => '',
		'title_custom_font_size'     => '',
		'title_custom_font_weight'   => '',
		'title_custom_mg_top'        => '',
		'title_custom_mg_bt'         => '',
		'line_after_title'           => false,
		'desc_content'               => '',
		'custom_font_size_desc'      => '',
		'custom_font_weight_desc'    => '',
		'color_desc'                 => '',
		'read_more_link'             => '',
		'read_more_link_to'          => '',
		'read_more_target'           => '',
		'link_to_icon'               => false,
		'read_more_text'             => '',
		'read_more_text_color'       => '',
		'read_more_border_color'     => '',
		'read_more_bg_color'         => '',
		'read_more_text_hover_color' => '',
		'read_more_bg_hover_color'   => '',
		'icon_type'                  => '',
		'font_awesome_icon'          => '',
		'font_awesome_icon_size'     => '',
        'font_ionicons'              => '',
		'custom_image_icon'          => '',
		'width_icon_box'             => '100',
        'height_icon_box'             => '',
		'icon_color'                 => '',
		'icon_border_color'          => '',
		'icon_bg_color'              => '',
		'icon_hover_color'           => '',
		'icon_border_hover_color'    => '',
		'icon_bg_hover_color'        => '',
		'layout_box_icon_style'      => '',
		'layout_pos'                 => 'top',
		'layout_text_align_sc'       => '',
		'layout_style_box'           => '',
		'css_animation'              => '',
        'widget_background'              => 'none',
        'bg_box_color'              => '',
        'el_class' => '',
	), $atts );

	$instance = array(
		'title_group' => array(
			'title'            => $icon_box['title'],
			'color_title'      => $icon_box['title_color'],
			'size'             => $icon_box['title_size'],
			'font_heading'     => $icon_box['title_font_heading'],
			'custom_heading'   => array(
				'custom_font_size'   => $icon_box['title_custom_font_size'],
				'custom_font_weight' => $icon_box['title_custom_font_weight'],
				'custom_mg_top'      => $icon_box['title_custom_mg_top'],
				'custom_mg_bt'       => $icon_box['title_custom_mg_bt'],
			),
			'line_after_title' => $icon_box['line_after_title'] ? 1 : '',
		),

		'desc_group' => array(
			'content'              => $icon_box['desc_content'],
			'custom_font_size_des' => $icon_box['custom_font_size_desc'],
			'custom_font_weight'   => $icon_box['custom_font_weight_desc'],
			'color_description'    => $icon_box['color_desc'],
		),

		'read_more_group' => array(
			'link'                   => $icon_box['read_more_link'],
			'read_more'              => $icon_box['read_more_link_to'],
			'target'                 => $icon_box['read_more_target'],
			'link_to_icon'           => $icon_box['link_to_icon'],
			'button_read_more_group' => array(
				'read_text'                  => $icon_box['read_more_text'],
				'read_more_text_color'       => $icon_box['read_more_text_color'],
				'border_read_more_text'      => $icon_box['read_more_border_color'],
				'bg_read_more_text'          => $icon_box['read_more_bg_color'],
				'read_more_text_color_hover' => $icon_box['read_more_text_hover_color'],
				'bg_read_more_text_hover'    => $icon_box['read_more_bg_hover_color'],
			),
		),

		'icon_type' => $icon_box['icon_type'],

		'font_awesome_group' => array(
			'icon'      => str_replace( 'fa fa-', '', $icon_box['font_awesome_icon'] ),
			'icon_size' => $icon_box['font_awesome_icon_size'],
		),

        'font_ionicons_group' => array(
            'icon'      => $icon_box['font_ionicons'],
            'icon_size' => $icon_box['font_awesome_icon_size'],
        ),

		'font_image_group' => array(
			'icon_img' => $icon_box['custom_image_icon'],
		),

		'width_icon_box' => $icon_box['width_icon_box'],
        'height_icon_box' => $icon_box['height_icon_box'],

		'color_group' => array(
			'icon_color'              => $icon_box['icon_color'],
			'icon_border_color'       => $icon_box['icon_border_color'],
			'icon_bg_color'           => $icon_box['icon_bg_color'],
			'icon_hover_color'        => $icon_box['icon_hover_color'],
			'icon_border_color_hover' => $icon_box['icon_border_hover_color'],
			'icon_bg_color_hover'     => $icon_box['icon_bg_hover_color'],
		),

		'layout_group'      => array(
			'box_icon_style' => $icon_box['layout_box_icon_style'],
			'pos'            => $icon_box['layout_pos'],
			'text_align_sc'  => $icon_box['layout_text_align_sc'],
			'style_box'      => $icon_box['layout_style_box'],
		),
		'widget_background' => 'none',
		'self_video'        => '',
		'self_poster'       => '',
		'css_animation'     => '',
        'el_class'          => '',
        'widget_background' => $icon_box['widget_background'],
        'bg_box_color' => $icon_box['bg_box_color'],

	);

	$widget_template       = THIM_DIR . 'inc/widgets/icon-box/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/icon-box/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-icon-box">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-icon-box', 'thim_shortcode_icon_box' );


