<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Disable AMP for WP
 *
 */
if (isset($_SERVER['HTTP_X_WPAPPNINJA']) || defined( 'WPAPPNINJA_READ_ENHANCED' ) || isset($_GET['wpappninja_simul4']) || isset($_GET['is_wppwa']) || isset($_GET['wpappninja_cache']) || isset($_COOKIE['HTTP_X_WPAPPNINJA'])) {
    
    add_filter('amp_is_enabled', '__return_false', 100);
}
