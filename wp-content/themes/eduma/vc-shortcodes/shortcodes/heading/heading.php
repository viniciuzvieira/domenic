<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_heading($atts)
{
    $instance = shortcode_atts(array(
        'title' => '',
        'main_title' => '',
        'title_uppercase' => '',
        'size' => 'h3',
        'textcolor' => '',
        'font_size' => '',
        'font_weight' => '',
        'font_style' => '',
        'title_custom' => '',
        'sub_heading' => '',
        'sub_heading_color' => '',
        'line' => '',
        'clone_title' => '',
        'bg_line' => '',
        'css_animation' => '',
        'text_align' => '',
        'el_class' => '',
    ), $atts);

    $instance['font_heading'] = $instance['title_custom'];
    $instance['custom_font_heading'] = array(
        'custom_font_size' => $instance['font_size'],
        'custom_font_weight' => $instance['font_weight'],
        'custom_font_style' => $instance['font_style'],
    );

    $widget_template = THIM_DIR . 'inc/widgets/heading/tpl/base.php';
    $child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/heading/base.php';
    if (file_exists($child_widget_template)) {
        $widget_template = $child_widget_template;
    }

    ob_start();
    if ($instance['el_class']) echo '<div class="' . $instance['el_class'] . '">';
    echo '<div class="thim-widget-heading">';
    include $widget_template;
    echo '</div>';
    if ($instance['el_class']) echo '</div>';
    $html_output = ob_get_contents();
    ob_end_clean();

    return $html_output;
}

add_shortcode('thim-heading', 'thim_shortcode_heading');


