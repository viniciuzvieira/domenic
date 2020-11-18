<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Disable the brute force feature in the app.
 *
 * @since 9.0
 */
add_filter( 'secupress.plugin.bruteforce.edgecase', 'wpmobile_manage_bruteforce_edgecase', 1 );
function wpmobile_manage_bruteforce_edgecase( $value ) {

	if ( isset($_SERVER['HTTP_X_WPAPPNINJA']) || defined( 'WPAPPNINJA_READ_ENHANCED' ) || isset($_GET['wpappninja_simul4']) || isset($_GET['is_wppwa']) || isset($_GET['wpappninja_cache']) || isset($_COOKIE['HTTP_X_WPAPPNINJA']) ) {
		return true;
	}
	
	return $value;
}
