<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Hide php errors on the app
 *
 * @since 9.0
 */

if ((isset($_SERVER['HTTP_X_WPAPPNINJA']) || isset($_GET['wpappninja_simul4']) || isset($_COOKIE['HTTP_X_WPAPPNINJA']) && !isset($_SERVER['HTTP_X_WPMOBILE_DEBUG']))) {

	ini_set('display_errors','off');
}
