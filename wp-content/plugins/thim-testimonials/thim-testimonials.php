<?php
/*
	Plugin Name: Thim Testimonials
	Plugin URI: https://thimpress.com
	Description: A plugin that allows you to show off your testimonials.
	Author: ThimPress
	Version: 1.3.1
	Author URI: https://thimpress.com
	Text Domain: thim-testimonials
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! defined( 'THIM_TESTIMONIALS_VERSION' ) ) {
    define( 'THIM_TESTIMONIALS_VERSION', '1.3' );
}

if( ! defined( 'TESTIMONIALS_PLUGIN_URL' ) ) {
    define( 'TESTIMONIALS_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ));
}

if( ! defined( 'TESTIMONIALS_PLUGIN_PATH' ) ) {
    define( 'TESTIMONIALS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ));
}

require_once TESTIMONIALS_PLUGIN_PATH . '/init.php';
