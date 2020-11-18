<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Set the right for admin functions.
 *
 * @since 5.1.9
 */
function wpappninja_get_right($type = "all")
{
	if ($type == "push") {
		return get_wpappninja_option('rightpush', 'manage_options');
	}

	if ($type == "stats") {
		return get_wpappninja_option('rightstats', 'manage_options');
	}

	if ($type == "qrcode") {
		return get_wpappninja_option('rightqrcode', 'manage_options');
	}

	return get_wpappninja_option('right', 'manage_options');
}