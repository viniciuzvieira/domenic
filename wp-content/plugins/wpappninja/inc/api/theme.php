<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Load a custom theme
 *
 * @since 5.0.6
 */
if (isset($_SERVER['HTTP_REFERER']) && preg_match('#wpappninja_simul4#', $_SERVER['HTTP_REFERER'])) {
	$_GET['wpappninja_simul4'] = "true";
}

if (!isset($_GET['wpappninja_read_enhanced']) && (isset($_SERVER['HTTP_X_WPAPPNINJA']) || isset($_COOKIE['HTTP_X_WPAPPNINJA']) || isset($_GET['wpappninja_simul4']) || isset($_GET['is_wppwa'])) && !defined('DOING_WPAPPNINJA_API') && (wpappninja_webview_mode(0) == '4' || isset($_GET['wpappninja_simul4']))) {
 

	if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != "No theme") {

		add_filter('theme_root', 'wpappninja_get_theme_root', 10);
		add_filter('stylesheet_directory_uri', 'wpappninja_get_theme_root_css', 10);
		add_filter('template_directory_uri', 'wpappninja_get_theme_root_css', 10);


		add_action( 'setup_theme', 'wpappninja_switch_theme' );
		function wpappninja_switch_theme() {
			add_filter('template', 'wpappninja_get_theme_template');
			add_filter('option_template', 'wpappninja_get_theme_template');
			add_filter('stylesheet', 'wpappninja_get_theme_template');
			add_filter('option_stylesheet', 'wpappninja_get_theme_template');
		}

		remove_filter ('the_content', 'wptexturize');
		add_filter( 'the_content', 'wpappninja_remove_theme_shortcode', PHP_INT_MAX );
		function wpappninja_remove_theme_shortcode( $content ) {
    		$content = preg_replace('/(^|[\r\n\s<>])(\[[\/ÀÁÅÃÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿa-zA-Z0-9\s=\'":\|%,#;\-_\.]+\])([\r\n\s<>]|$)/m', "$1$3", $content);
			return $content;
		}

		add_action('after_setup_theme', 'wpappninja_remove_admin_bar');
		function wpappninja_remove_admin_bar() {
			if (get_wpappninja_option('speed', '0') == "1") {
			  show_admin_bar(false);
			}
		}

	}
}

function wpappninja_get_theme_root() {

	$name = get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App');


	$themes = wp_get_themes(array('allowed' => 'site'));
	foreach ($themes as $k => $theme) {
		if ($theme->Name == $name) {
			return WP_CONTENT_DIR . '/themes';
		}
	}
	
	return WPAPPNINJA_PATH . 'themes';

}

function wpappninja_get_theme_root_css() {

	$name = get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App');


	$themes = wp_get_themes(array('allowed' => 'site'));
	foreach ($themes as $k => $theme) {
		if ($theme->Name == $name) {
			return get_theme_root_uri($name) . '/' . $k;
		}
	}

	if (get_wpappninja_option('speed', '0') == "1") {
		return WPAPPNINJA_URL . 'themes/wpappninja-full';
	}

	return WPAPPNINJA_URL . 'themes/wpappninja';
}
function wpappninja_get_theme_template() {

	$name = get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App');


	$themes = wp_get_themes(array('allowed' => 'site'));
	foreach ($themes as $k => $theme) {
		if ($theme->Name == $name) {
			return $k;
		}
	}

	if (get_wpappninja_option('speed', '0') == "1") {
		return 'wpappninja-full';
	}

	return 'wpappninja';
}

if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App' && isset($_GET['wpappninja_read_push'])) {

	remove_action('template_redirect', 'redirect_canonical');
	add_filter( 'the_title', '__return_false' );
	add_filter( 'the_content', 'wpappninja_show_push', PHP_INT_MAX );
}
