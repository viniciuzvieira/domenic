<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Detect is an Apple reviewer is checking the app.
 *
 * @since 3.8.6
 */
function wpappninja_is_apple_reviewer() {

	// force reviewer
	if (isset($_SERVER['HTTP_X_WPAPPNINJA_IS_REVIEWER'])) {
		return true;
	}
	
	// get the ip
	$ip = "";
	if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	if (ip2long($ip) <= ip2long("17.255.255.255") && ip2long("17.0.0.0") <= ip2long($ip)) {
		return true;
	}
	
	return false;
}

/**
 * Fake the version for Apple reviewers.
 *
 * @since 5.2
 */
add_filter( 'pre_get_wpappninja_option_version_app', 'wpappninja_version_app_reviewers' );
function wpappninja_version_app_reviewers( $v ) {

	// not ready
	return "999";

	if (wpappninja_is_apple_reviewer()) {
	    return "1";
	}

	return $v;
}

/**
 * Hide the website for Apple reviewers.
 *
 * @since 5.2
 */
add_action( 'send_headers', 'wpappninja_website_off_reviewers' );
function wpappninja_website_off_reviewers() {

	if (get_option('wpappninja_off_apple', '0') == '1' && wpappninja_is_apple_reviewer() && !isset($_SERVER['HTTP_X_WPAPPNINJA']) && !defined('DOING_WPAPPNINJA_API') && !isset($_GET['wpappninja_read_enhanced'])) {

		header( "HTTP/1.1 410 Gone" );
		exit();
	}
}
