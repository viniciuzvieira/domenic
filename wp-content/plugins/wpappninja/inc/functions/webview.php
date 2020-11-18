<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/*
 * Search if an item is in webview mode
 *
 * @since 3.8.6
 */
function wpappninja_is_webview($id) {
	$webview = get_wpappninja_option('webview_selective', array());
	
	if (in_array($id, $webview)) {
		return true;
	}
	
	return false;
}

/*
 * Display mode.
 *
 * @since 5.1.9
 */
function wpappninja_webview_mode($id) {

	if (get_wpappninja_option('speed') == '1') {
		return "4";
	}

	/*$rules = get_wpappninja_option('webview_rules', array());

	if (isset($rules[$id]) && $rules[$id] != "") {
		return $rules[$id];
	}*/

	return get_wpappninja_option('webview', '0');
}
