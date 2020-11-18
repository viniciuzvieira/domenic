<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Disable WPTouch on API
 *
 * @since 4.0.3
 */

add_filter('autoptimize_filter_js_exclude','wpmobileapp_whitelist');
function wpmobileapp_whitelist($s) {
	return $s . ", jquery.js, jquery-migrate.min.js, framework7.min.js, app-ui.js";
}
