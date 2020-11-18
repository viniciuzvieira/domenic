<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/*
 * 1 march warning about the sdk update
 *
 * @since 1.0
 */

add_action('init', 'wpmobile_check_new_sdk_form');
function wpmobile_check_new_sdk_form() {

	if (isset($_POST['wpmobilefixsdk']) && check_admin_referer( 'wpmobilefixsdk' )) {

		wpmobile_fix_sdk();
	}

	if (isset($_POST['enablenewsdk']) && check_admin_referer( 'enablenewsdk' )) {

		wpmobile_set_sdk();
	}

	if (isset($_POST['wpmobile_hide_alerts']) && check_admin_referer('wpmobile_hide_alerts')) {

		set_transient('wpmobile_hide_alerts', '1', 60 * 60 * 24 * 30);
	}
}


add_action('admin_notices', 'wpmobile_info_sdk');
function wpmobile_info_sdk() {

	if (get_transient('wpmobile_hide_alerts')) {
		return;
	}

	if (wpmobile_need_sdk_update() && ((isset( $_GET['page'] ) && in_array($_GET['page'], array(WPAPPNINJA_SETTINGS_SLUG,WPAPPNINJA_PREVIEW_SLUG,WPAPPNINJA_PUSH_SLUG,WPAPPNINJA_QRCODE_SLUG,WPAPPNINJA_CERT_SLUG,WPAPPNINJA_STATS_SLUG,WPAPPNINJA_PUBLISH_SLUG,WPAPPNINJA_PROMOTE_SLUG,WPAPPNINJA_ADSERVER_SLUG,WPAPPNINJA_AUTO_SLUG,WPAPPNINJA_HOME_SLUG,WPAPPNINJA_UPDATE_SLUG,WPAPPNINJA_PWA_SLUG,WPAPPNINJA_THEME_SLUG))) || !get_transient('wpmobile_sdk_alert'))) {

		set_transient('wpmobile_sdk_alert', 'true', 60*60*72);

		$class = 'notice notice-info';
		$message = __('<h4 style="font-size:18px;margin-top:0">🚀 New WPMobile.App SDK available</h4>&bull; 3x faster<br/>&bull; Embed the last Apple & Google framework<br/>&bull; GDPR ready (no more 3rd library)', 'wpappninja' );

		if (!wpmobile_need_change_sdk_update()) {
			$message .= "<br/><br/><form action='' onsubmit='return confirm(\"".__('This action cant be undone. Are you sure?', 'wpappninja')."\");' method='post'>". wp_nonce_field( 'enablenewsdk', '_wpnonce', true, false )."<input type='hidden' name='enablenewsdk' /><input style='background: #f4f4f4;color: #4a4a4a;border: 1px solid #eee;border-radius: 11px;padding: 15px;box-shadow: 0 0 1px #ccc;font-weight: 700;cursor: pointer;' type='submit' value='".__('Enable the new SDK', 'wpappninja')."' /></form>";
		}

		$message .= "<form style='text-align: right;' action='' method='post'>". wp_nonce_field( 'wpmobile_hide_alerts', '_wpnonce', true, false )."<input type='hidden' name='wpmobile_hide_alerts' /><input style='background: transparent;color: #4a4a4a;border: 0px solid #eee;padding: 0px;text-decoration:underline;cursor: pointer;' type='submit' value='".__('Dismiss 1 month', 'wpappninja')."' /></form>";

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
	}	
}

add_action('admin_notices', 'wpmobile_alert_sdk');
function wpmobile_alert_sdk() {

	if (get_transient('wpmobile_hide_alerts')) {
		return;
	}

	if (wpmobile_need_change_sdk_update() && ((isset( $_GET['page'] ) && in_array($_GET['page'], array(WPAPPNINJA_SETTINGS_SLUG,WPAPPNINJA_PREVIEW_SLUG,WPAPPNINJA_PUSH_SLUG,WPAPPNINJA_QRCODE_SLUG,WPAPPNINJA_CERT_SLUG,WPAPPNINJA_STATS_SLUG,WPAPPNINJA_PUBLISH_SLUG,WPAPPNINJA_PROMOTE_SLUG,WPAPPNINJA_ADSERVER_SLUG,WPAPPNINJA_AUTO_SLUG,WPAPPNINJA_HOME_SLUG,WPAPPNINJA_UPDATE_SLUG,WPAPPNINJA_PWA_SLUG,WPAPPNINJA_THEME_SLUG))) || !get_transient('wpmobile_sdk_alert'))) {

		set_transient('wpmobile_sdk_alert', 'true', 60*60*72);

		$deprecated = "";
		if (get_wpappninja_option('speed') != '1') {$deprecated .= "Current app theme, ";}
		
		if (get_wpappninja_option('adbuddiz', '') != '' || 
		get_wpappninja_option('adbuddiz_ios', '') != '') {$deprecated .= "AdBuddiz, ";}

		if (get_wpappninja_option('admob_float', '') != '' || 
		get_wpappninja_option('admob_float_ios', '') != '' || 
		get_wpappninja_option('admob_splash', '') != '' || 
		get_wpappninja_option('admob_splash_ios', '') != '' || 
		get_wpappninja_option('admob_t', '') != '' || 
		get_wpappninja_option('admob_t_ios', '') != '' || 
		get_wpappninja_option('admob_b', '') != '' || 
		get_wpappninja_option('admob_b_ios', '') != '') {$deprecated .= "AdMob, ";}

		if (get_wpappninja_option('ga', '') != '') {$deprecated .= "Google Analytics, ";}

		$class = 'notice notice-error';

		$message .= sprintf(__('<h4 style="font-size:18px;margin-top:0">⚠ Features deprecated in the new SDK</h4>From February 28 you can continue to use <b>%s</b> but you will not be able to update the application.', 'wpappninja'), $deprecated);

		$message .= "<br/><br/><form onsubmit='return confirm(\"".__('This action cant be undone. Are you sure?', 'wpappninja')."\");' action='' method='post'>". wp_nonce_field( 'wpmobilefixsdk', '_wpnonce', true, false )."<input type='hidden' name='wpmobilefixsdk' /><input style='background: #f4f4f4;color: #4a4a4a;border: 1px solid #eee;border-radius: 11px;padding: 15px;box-shadow: 0 0 1px #ccc;font-weight: 700;cursor: pointer;' type='submit' value='".__('Disable deprecated features', 'wpappninja')."' /></form>";

		//if ($deprecated != "") {

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
		//}
	}	
}

function wpmobile_need_sdk_update() {

	if (get_wpappninja_option('sdk2019') == '1') {

		return false;
	}

	return true;
}

function wpmobile_need_change_sdk_update() {

	if (wpappninja_is_paid() && (

		get_wpappninja_option('speed') != '1' || 
		get_wpappninja_option('adbuddiz', '') != '' || 
		get_wpappninja_option('adbuddiz_ios', '') != '' || 
		get_wpappninja_option('admob_float', '') != '' || 
		get_wpappninja_option('admob_float_ios', '') != '' || 
		get_wpappninja_option('admob_splash', '') != '' || 
		get_wpappninja_option('admob_splash_ios', '') != '' || 
		get_wpappninja_option('admob_t', '') != '' || 
		get_wpappninja_option('admob_t_ios', '') != '' || 
		get_wpappninja_option('admob_b', '') != '' || 
		get_wpappninja_option('admob_b_ios', '') != '' || 
		get_wpappninja_option('ga', '') != '')

	) {

		return true;
	}

	return false;
}

function wpmobile_fix_sdk() {

	$options            = get_option( WPAPPNINJA_SLUG );

	if ($options['speed'] != '1') {
		$options['wpappninja_main_theme'] = 'WPMobile.App';
		$options['speed'] = "1";
		$options['webview'] = "4";
		$options['speed_notheme'] = "0";
		$options['nospeed_notheme'] = "0";
		$options['appify'] = "0";
	}
	$options['adbuddiz'] = "";
	$options['adbuddiz_ios'] = "";
	$options['admob_float'] = "";
	$options['admob_float_ios'] = "";
	$options['admob_b'] = "";
	$options['admob_b_ios'] = "";
	$options['admob_t'] = "";
	$options['admob_t_ios'] = "";
	$options['admob_splash'] = "";
	$options['admob_splash_ios'] = "";
	$options['ga'] = "";

	update_option( WPAPPNINJA_SLUG, $options );
}

function wpmobile_set_sdk() {

	$options            = get_option( WPAPPNINJA_SLUG );

	if ($options['speed'] != '1') {
		$options['wpappninja_main_theme'] = 'WPMobile.App';
		$options['speed'] = "1";
		$options['webview'] = "4";
		$options['speed_notheme'] = "0";
		$options['nospeed_notheme'] = "0";
		$options['appify'] = "0";
	}
	$options['nomoreqrcode'] = "1";
	$options['adbuddiz'] = "";
	$options['adbuddiz_ios'] = "";
	$options['admob_float'] = "";
	$options['admob_float_ios'] = "";
	$options['admob_b'] = "";
	$options['admob_b_ios'] = "";
	$options['admob_t'] = "";
	$options['admob_t_ios'] = "";
	$options['admob_splash'] = "";
	$options['admob_splash_ios'] = "";
	$options['sdk2019'] = "1";
	$options['ga'] = "";

	update_option( WPAPPNINJA_SLUG, $options );
}

function wpmobile_can_sdk_update() {

	if (get_wpappninja_option('nomoreqrcode') != '1' && current_time('timestamp') > 1551384000) { // 28 feb 20.00

		return false;
	}

	return true;
}
