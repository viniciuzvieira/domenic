<?php
/*
Plugin Name: LearnPress - bbPress Integration
Plugin URI: http://thimpress.com/learnpress
Description: Using the forum for courses provided by bbPress.
Author: ThimPress
Version: 3.0.3
Author URI: http://thimpress.com
Tags: learnpress, lms, add-on, bbpress
Text Domain: learnpress-bbpress
Domain Path: /languages/
*/

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

define( 'LP_ADDON_BBPRESS_FILE', __FILE__ );
define( 'LP_ADDON_BBPRESS_VER', '3.0.3' );
define( 'LP_ADDON_BBPRESS_REQUIRE_VER', '3.0.0' );


/**
 * Class LP_Addon_bbPress_Preload
 */
class LP_Addon_bbPress_Preload {

	/**
	 * LP_Addon_bbPress_Preload constructor.
	 */
	public function __construct() {
		add_action( 'learn-press/ready', array( $this, 'load' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Load addon
	 */
	public function load() {
		LP_Addon::load( 'LP_Addon_bbPress', 'inc/load.php', __FILE__ );
		remove_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Admin notice
	 */
	public function admin_notices() {
		?>
        <div class="error">
            <p><?php echo wp_kses(
					sprintf(
						__( '<strong>%s</strong> addon version %s requires %s version %s or higher is <strong>installed</strong> and <strong>activated</strong>.', 'learnpress-bbpress' ),
						__( 'LearnPress bbPress', 'learnpress-bbpress' ),
						LP_ADDON_BBPRESS_VER,
						sprintf( '<a href="%s" target="_blank"><strong>%s</strong></a>', admin_url( 'plugin-install.php?tab=search&type=term&s=learnpress' ), __( 'LearnPress', 'learnpress-bbpress' ) ),
						LP_ADDON_BBPRESS_REQUIRE_VER
					),
					array(
						'a'      => array(
							'href'  => array(),
							'blank' => array()
						),
						'strong' => array()
					)
				); ?>
            </p>
        </div>
		<?php
	}
}

new LP_Addon_bbPress_Preload();