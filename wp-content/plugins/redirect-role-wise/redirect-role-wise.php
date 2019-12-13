<?php
/**
 * Plugin Name: Redirect Role Wise
 * Plugin URI: https://wordpress.org/plugins/redirect-role-wise
 * Description: Using the plugin we can redirect users to different pages according to their role
 * Author: Kirti Nirkhiwale - Gyrix Technolabs
 * Author URI: http://gyrix.co/
 * Requires at least: 4.0
 * Tested up to: 4.8.0
 * Version: 1.2
 */
if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly
}
if(!defined('RRW_GYRIXTEMPLATEPATH'))
{
	define('RRW_GYRIXTEMPLATEPATH', plugin_dir_path(__FILE__));
	define('RRW_GYRIXTEMPLATEURL', plugin_dir_url(__FILE__));
}
// Include main file of plugin
include_once( dirname(__FILE__).'/includes/redirect-user.php' );

/**
 * Main instance of plugin.
*/

// to do when activate plugin
function rrw_gyrix_register()
{
	if(is_admin() || current_user_can('manage_opions'))
	{
		//when plugin is activited this function runs
		register_post_type( 'rrw_gyrix_urls',
		// CPT Options
			array(
				'labels' => array(
					'name' => __( 'User Redirection Links' ),
					'singular_name' => __( 'User Redirection Links' )
				),
				'public' => true,
				'has_archive' => true,
				'show_in_menu' => false,
				'rewrite' => array('slug' => 'user-redirection-links'),
			)
		);
	}
	rrw_hooks_gyrix::rrw_get_instance();
	
}
add_action('init', 'rrw_gyrix_register');
 
function rrw_pluginprefix_install()
{
	if(is_admin() || current_user_can('manage_opions'))
	{
	    // trigger our function that registers the custom post type
	    rrw_gyrix_register();
	 
	    // clear the permalinks after the post type has been registered
	    flush_rewrite_rules();
	}
}
register_activation_hook(__FILE__, 'rrw_pluginprefix_install');

// to do when de-activate plugin
function rrw_gyrix_plugin_deactivation() 
{	
    flush_rewrite_rules(); 
}
register_deactivation_hook( __FILE__, 'rrw_gyrix_plugin_deactivation' );

class rrw_hooks_gyrix 
{
	private static $instance;

    static function rrw_get_instance()
	{
		if (!isset(self::$instance))
	    {
	        self::$instance = new self();
	    }
	    return self::$instance;
	}

	public function __construct()
	{	
		//Create CPT			
		$obj_gyrix_manager = new rrw_gyrix_manager;
	}
	
}
