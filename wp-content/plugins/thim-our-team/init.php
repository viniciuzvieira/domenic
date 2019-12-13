<?php
/*
Plugin Name: Thim Our Team
Plugin URI: https://thimpress.com
Description: A plugin that allows you to show off your team members.
Author: ThimPress
Version: 1.3.1
Author URI: https://thimpress.com
Text Domain: thim-our-team
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'THIM_OUR_TEAM_VERSION' ) ) {
	define( 'THIM_OUR_TEAM_VERSION', '1.3' );
}

if ( ! defined( 'OUR_TEAM_PLUGIN_URL' ) ) {
	define( 'OUR_TEAM_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
}

if ( ! defined( 'OUR_TEAM_PLUGIN_PATH' ) ) {
	define( 'OUR_TEAM_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

require_once 'thim-our-team.php';