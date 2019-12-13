<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode Image Box
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_image_box( $atts ) {

    $instance = shortcode_atts( array(
        'title' => '',
        'link' => '',
        'image' => '',
        'layout' => '',
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

    $instance['image_box'] = $instance['image'];
    $widget_template       = THIM_DIR . 'inc/widgets/image-box/tpl/' . $layout;
    $child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/image-box/' . $layout;
    if ( file_exists( $child_widget_template ) ) {
        $widget_template = $child_widget_template;
    }

    ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
    echo '<div class="thim-widget-image-box">';
    include $widget_template;
    echo '</div>';
    if($instance['el_class']) echo '</div>';
    $html_output = ob_get_contents();
    ob_end_clean();

    return $html_output;
}

add_shortcode( 'thim-image-box', 'thim_shortcode_image_box' );
