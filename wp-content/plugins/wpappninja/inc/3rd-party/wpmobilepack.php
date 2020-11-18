<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

if (isset($_SERVER['HTTP_X_WPAPPNINJA']) || defined( 'WPAPPNINJA_READ_ENHANCED' ) || isset($_GET['wpappninja_simul4']) || isset($_GET['is_wppwa']) || isset($_GET['wpappninja_cache']) || isset($_COOKIE['HTTP_X_WPAPPNINJA'])) {

	remove_action('plugins_loaded', 'wmobilepack_frontend_init');
	remove_action('plugins_loaded', 'wmobilepack_admin_init');


}
