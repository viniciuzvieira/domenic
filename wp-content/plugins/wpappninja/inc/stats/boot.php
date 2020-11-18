<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

// PERSONNAL DATA AND IP ARE NEVER STORED, JUST USED
// TO GET DETAILS INFO (CITY, BROWSER, ...)
// NO COOKIE - NO RESELLING

/**
 * Log actions.
 *
 * @since 4.0
 */
function wpappninja_stats_log($action, $nb = 1, $isIOS = false, $lang = '') {

	// no stats for non premium account
	if (!wpappninja_is_premium()) {
		return;
	}

	// no stats while testing
	if (!get_option('wpappninja_app_published')) {
		return;
	}
	
	global $wpdb;
	
	// requete rewrite
	$request = explode('/', $action);
	$action = isset($request[0]) ? $request[0] : $action;
	$value = isset($request[1]) ? $request[1] : "";

	$value = preg_replace('#__sla_sh__#', '/', preg_replace('#__dot__#', ':', $value));


	if (get_wpappninja_option('speed') == '1' && $action == 'read') {
		$value = get_the_ID();
		if (isset($_GET['wpapp_shortcode'])) {
			$value = $_GET['wpapp_shortcode'];
		}
	}

	// exclude hack
	if ($value < 0) {
		return;
	}
	
	// exclude pagination on cat/recent
	if ($action == 'bycat' && isset($request[2]) ||
	    $action == 'recent' && intval($value) > 0) {
		return;
	}
	
	// remove the values for recent
	if ($action == 'recent') {
		$value = '';
	}
	
	// exclude useless action
	$exclude = array('healme', 'apple_403', 'version', 'update', 'adserver', 'cancel', 'published', 'register_install', 'getinstall', 'updated', 'category', 'store', 'unregister', 'register', 'redirection', 'similaires', 'favoris', 'custom', 'cronjob');
	if (in_array($action, $exclude)) {
		return;
	}
	
	// uniq id (except for push)
	if ($action == 'push') {
		$id = ($isIOS) ? 'bot_ios' : 'bot_android';
		$id .= '_' . $lang;
	} else {
		$id = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
	}
	
	// deeplink
	if (preg_match('#^http#', $value)) {

		$value = wpappninja_url_to_postid($value);
		if ($value === 0) {

			$value = wpappninja_url_to_catid($value);
			if ($value === 0) {
				return;
			}
		}
	}
	
	// log action
	if ($action != "weglot") {
		$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_stats (`user_id`, `action`, `value`, `nb`, `date`) VALUES (%s, %s, %s, %d, %s)", $id, $action, $value, $nb, current_time('timestamp')));
	}
	
	// add user
	wpappninja_stats_user($id, $lang);
}

/**
 * Get info about user.
 *
 * @since 4.0
 */
function wpappninja_stats_user($id, $lang = '') {
	
	global $wpdb;
	
	// already parsed?
	$u = $wpdb->get_results($wpdb->prepare("SELECT id FROM {$wpdb->prefix}wpappninja_stats_users WHERE `id` = %s", $id));
	if (isset($u[0])) {
		return;
	}
	
	// fake user for bot
	if ($id == 'bot_android_' . $lang) {
		$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_stats_users (`id`, `platform`, `lang`) VALUES (%s, %s, %s)", $id, 'android', ''));
		return;
	} else if ($id == 'bot_ios_' . $lang) {
		$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_stats_users (`id`, `platform`, `lang`) VALUES (%s, %s, %s)", $id, 'ios', ''));
		return;
	}
	
	// Info from http://freegeoip.net/ # DEPRECATED
	//$ferank_request = wp_remote_get('http://freegeoip.net/json/' . $_SERVER['REMOTE_ADDR']);
					
	//if (!is_wp_error( $ferank_request )) {
	//	$ferank = json_decode($ferank_request['body'], TRUE);
		
		// platform
		$platform = wpappninja_is_ios() ? "ios" : "android";

		$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_stats_users (`id`, `continent`, `country`, `city`, `platform`, `lang`) VALUES (%s, %s, %s, %s, %s, %s)", $id, "", "", "", $platform, wpappninja_get_lang()));
	//}
}
