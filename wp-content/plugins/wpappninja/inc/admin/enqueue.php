<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add some CSS on the whole administration
 *
 * @since 1.0
 */
add_action( 'admin_print_styles', '_wpappninja_admin_print_styles' );
function _wpappninja_admin_print_styles() {

	$current_screen = get_current_screen();
	$css_ext        = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.css' : '.min.css';
	$js_ext         = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.js' : '.min.js';

	wp_register_style(
		'wpappninja-admin',
		WPAPPNINJA_ASSETS_CSS_URL . 'admin' . $css_ext,
		array(),
		WPAPPNINJA_VERSION
	);
	
	wp_register_style(
		'wpappninja-admin-date',
		WPAPPNINJA_ASSETS_CSS_URL . 'jquery.datetimepicker.min.css',
		array(),
		WPAPPNINJA_VERSION
	);
		
	wp_register_script(
		'wpappninja-admin-js-date',
		WPAPPNINJA_ASSETS_JS_URL . 'jquery.datetimepicker.full.min.js',
		array('jquery'),
		WPAPPNINJA_VERSION
	);
	
	wp_register_script(
		'wpappninja-admin-js',
		WPAPPNINJA_ASSETS_JS_URL . 'admin' . $js_ext,
		array('wp-color-picker', 'jquery', 'jquery-ui-core', 'jquery-ui-draggable'),
		WPAPPNINJA_VERSION,
		true
	);

	wp_register_script(
		'wpappninja-admin-js-push',
		WPAPPNINJA_ASSETS_JS_URL . 'admin-push' . $js_ext,
		array('jquery'),
		WPAPPNINJA_VERSION,
		true
	);

	wp_register_script(
		'wpappninja-stats-highcharts',
		'https://code.highcharts.com/highcharts.js',
		array('jquery'),
		WPAPPNINJA_VERSION,
		false
	);

	$publication = 'admin_page_wpappninja_publish';
	$statistics = 'toplevel_page_wpappninja_stats';

	/*
	 * Scripts loaded in /wp-admin/options-general.php?page=wpappninja_settings
	 */
	if ( isset( $_GET['page'] ) && in_array($_GET['page'], array(WPAPPNINJA_PUSH_SLUG, WPAPPNINJA_AUTO_SLUG, WPAPPNINJA_SLUG, WPAPPNINJA_PUBLISH_SLUG, WPAPPNINJA_HOME_SLUG, WPAPPNINJA_QRCODE_SLUG, WPAPPNINJA_THEME_SLUG, WPAPPNINJA_UPDATE_SLUG, WPAPPNINJA_PROMOTE_SLUG, WPAPPNINJA_ADSERVER_SLUG, WPAPPNINJA_PWA_SLUG,WPAPPNINJA_STATS_SLUG, WPAPPNINJA_PREVIEW_SLUG, WPAPPNINJA_CERT_SLUG))) {
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wpappninja-admin' );
		wp_enqueue_style( 'wpappninja-admin-date' );
		wp_enqueue_script( 'wpappninja-admin-js-date' );
		wp_enqueue_script( 'wp-color-picker' );
	}
	
	/*
	 * Scripts loaded in /wp-admin/options-general.php?page=wpappninja_settings
	 */
	if ( isset( $_GET['page'] ) && in_array($_GET['page'], array(WPAPPNINJA_SLUG))) {
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'wpappninja-admin-js' );
	}

	/*
	 * Scripts loaded in /wp-admin/options-general.php?page=wpappninja_push
	 */
	if ( isset( $_GET['page'] ) && in_array($_GET['page'], array(WPAPPNINJA_PUSH_SLUG))) {
		wp_enqueue_media();
		wp_enqueue_style( 'wpappninja-admin' );
		wp_enqueue_script( 'wpappninja-admin-js-push' );
		wp_enqueue_script( 'wpappninja-admin-js-date' );
		wp_enqueue_style( 'wpappninja-admin-date' );
	}
	
	/*
	 * Scripts loaded in /wp-admin/options-general.php?page=wpappninja_stats
	 */
	if ( isset( $_GET['page'] ) && in_array($_GET['page'], array(WPAPPNINJA_STATS_SLUG))) {
		wp_enqueue_style( 'wpappninja-admin' );
		wp_enqueue_script( 'wpappninja-stats-highcharts' );
	}
}

/*if ( function_exists('register_sidebar') ) { register_sidebar(array( 'name' => 'WPMobile.App (before content)', 'id' => 'wpappninja-before', 'description' => 'Appears as the sidebar on top of the page', 'before_widget' => '<li><div class="item-content">', 'after_widget' => '</li></div>', 'before_title' => '<div class="content-block-title">', 'after_title' => '</div>', )); }
if ( function_exists('register_sidebar') ) { register_sidebar(array( 'name' => 'WPMobile.App (after content)', 'id' => 'wpappninja-after', 'description' => 'Appears as the sidebar on bottom of the page', 'before_widget' => '<li><div class="item-content">', 'after_widget' => '</li></div>', 'before_title' => '<div class="content-block-title">', 'after_title' => '</div>', )); }*/