<?php

$counters_value  = $counters_label = $jugas_animation = $icon = $label = $box_style = $text_number = $border_color = $counter_style = $view_more_button = $view_more_text = $view_more_link = '';
$jugas_animation .= thim_getCSSAnimation( $instance['css_animation'] );

if ( !empty( $instance['text_number'] ) ) {
	$text_number = $instance['text_number'];
}
if ( !empty ( $instance['view_more_text'] ) ) {
	$view_more_text = $instance['view_more_text'];
}

if ( !empty ( $instance['view_more_link'] ) ) {
	$view_more_link = $instance['view_more_link'];
}

if ( $instance['counters_label'] <> '' ) {
	$counters_label = $instance['counters_label'];
}

if ( $instance['border_color'] <> '' ) {
	$border_color = 'style="border-color:' . $instance['border_color'] . '"';
}

if ( $instance['counter_color'] <> '' ) {
	$box_style = 'color:' . $instance['counter_color'] . ';';
}
if ( $instance['background_color'] <> '' ) {
	$box_style .= ' background-color:' . $instance['background_color'] . ';';
}

if ( $instance['counters_value'] <> '' ) {
	$counters_value = $instance['counters_value'];
}
if ( $instance['icon'] == '' ) {
	$instance['icon'] = 'none';
}
if ( $instance['icon'] != 'none' ) {
	if ( thim_plugin_active( 'js_composer/js_composer.php' ) ) {
		$icon = '<i class="' . $instance['icon'] . '"></i>';
	} else {
		$icon = '<i class="fa fa-' . $instance['icon'] . '"></i>';
	}
}
if ( $instance['style'] <> '' ) {
	$counter_style = $instance['style'];
}
echo '<div class="counter-box ' . $jugas_animation . ' ' . $counter_style . '" style="' . $box_style . '">';
if ( $icon ) {
	echo '<div class="icon-counter-box" ' . $border_color . '>' . $icon . '</div>';
}
if ( $counters_label != '' ) {
	$label = '<div class="counter-box-content">' . $counters_label . '</div>';
}

if ( '' != $view_more_text && '' != $view_more_link ) {
	$view_more_button = '<a class="view-more" href="' . $view_more_link . '">' . $view_more_text . '<i class="fa fa-chevron-right"></i></a>';
}

if ( $counters_value != '' ) {
	echo '<div class="content-box-percentage">
		<div class="wrap-percentage">
		<div class="display-percentage" data-percentage="' . $counters_value . '">'
		. $counters_value . '</div><div class="text_number">' . $text_number . '</div></div>';
	echo '<div class="counter-content-container">' . $label . $view_more_button . '</div></div>';
}

echo '</div>';


?>