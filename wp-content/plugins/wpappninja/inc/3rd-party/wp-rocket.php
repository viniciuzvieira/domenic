<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Disable lazy loading
 *
 * @since 3.0.8
 */
function wpappninja_wprocket_fix() {

	if (!defined('DONOTLAZYLOAD')) {
		define('DONOTLAZYLOAD', TRUE);
	}
	
	add_filter( 'do_rocket_generate_caching_files', '__return_false' );
}