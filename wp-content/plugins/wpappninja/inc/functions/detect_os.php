<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Detect the OS.
 *
 * @since 3.8.4
 */

function wpappninja_is_ios() {
	
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
	
	// for push notifications
	if (isset($_POST['regId']) && substr($_POST['regId'], 0, 5) == "_IOS_") {
		return true;
	}
	
	if (isset($_SERVER['HTTP_X_WPAPPNINJA_IOS']) || preg_match('#ios|iPhone|iPad|iPod#i', $ua) || preg_match('/\/ninja\.wpapp\.demo.*OS Version 9./', $ua)) {
		return true;
	}
	
	return false;
}


function wpappninja_isIOS() {
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : "";

	$isIOS = false;
	if (preg_match('#ios|iPhone|iPad|iPod#i', $ua)) {
		$isIOS = true;
	}

	return $isIOS;
}

function wpappninja_is_pwa() {

	if (get_wpappninja_option('agressive_anti_cache') == '1') {
		return false;
	}

	if (isset($_GET['wpappninja_simul4'])) {
		return false;
	}

	if (get_wpappninja_option('speed') != '1' || get_wpappninja_option('webview') != '4' || get_wpappninja_option('nospeed_notheme') != '0') {
		return false;
	}

	if (get_wpappninja_option('wpappninja_pwa') != 'off' && isset($_GET['is_wppwa'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_REFERER']) && preg_match('/\/wpappninja\/assets\/3rd-party\/sw\.php/', $_SERVER['HTTP_REFERER'])) {
		return true;
	}

	if (get_wpappninja_option('wpappninja_pwa') != 'off' && is_wpappninja()) {
	//if (is_wpappninja()) {
		return true;
	}

	/*if (get_option('wpappninja_cache') == '1' && is_wpappninja()) {
		return true;
	}*/

	return false;
}