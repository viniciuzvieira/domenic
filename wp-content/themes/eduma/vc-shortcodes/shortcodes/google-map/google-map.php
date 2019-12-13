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
function thim_shortcode_google_map( $atts ) {

	$instance = shortcode_atts( array(
		'title'                => '',
		'display_by'           => 'address',
		'location_lat'         => '41.868626',
		'location_lng'         => '-74.104301',
		'map_center'           => '',
		'api_key'              => '',
		'settings_height'      => '480',
		'settings_zoom'        => '12',
		'settings_scroll_zoom' => true,
		'settings_draggable'   => true,
		'marker_at_center'     => true,
		'marker_icon'          => '',
        'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$mrkr_src = wp_get_attachment_image_src( $instance['marker_icon'] );
	$api_key  = ( ! empty( $instance['api_key'] ) ) ? $instance['api_key'] : '';
	$map_id   = md5( $instance['map_center'] );
	$height   = $instance['settings_height'];
	$map_data = array(
		'display_by'       => ( isset( $instance['display_by'] ) && $instance['display_by'] != 'address' ) ? $instance['display_by'] : 'address',
		'lat'              => isset( $instance['location_lat'] ) ? $instance['location_lat'] : 41.956750,
		'lng'              => isset( $instance['location_lng'] ) ? $instance['location_lng'] : - 74.545448,
		'address'          => $instance['map_center'],
		'zoom'             => $instance['settings_zoom'],
		'scroll-zoom'      => $instance['settings_scroll_zoom'],
		'draggable'        => $instance['settings_draggable'],
		'marker-icon'      => ! empty( $mrkr_src ) ? $mrkr_src[0] : '',
		'marker-at-center' => $instance['marker_at_center'],
		'api-key'          => $api_key
	);

	wp_enqueue_script( 'thim-google-map', THIM_URI . 'inc/widgets/google-map/js/js-google-map.js', array( 'jquery' ), true );

	$widget_template       = THIM_DIR . 'inc/widgets/google-map/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/google-map/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-google-map">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-google-map', 'thim_shortcode_google_map' );


