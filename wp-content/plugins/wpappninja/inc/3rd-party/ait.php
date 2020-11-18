<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Fix a bug with AIT plugins
 *
 * @since 9.0
 */

if (isset($_SERVER['HTTP_X_WPAPPNINJA']) || isset($_GET['wpappninja_simul4']) || isset($_COOKIE['HTTP_X_WPAPPNINJA'])) {

	if (!defined('WPMOBILENOAIT')) {

		if (!function_exists('aitPath')) {

			function aitPath($dir, $path='') {
				return false;
			}
		}

		if (!function_exists('aitUrl')) {

			function aitUrl($dir, $path='') {
				return false;
			}
		}
	}
}
