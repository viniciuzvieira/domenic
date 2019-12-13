<?php
/*
Plugin Name: LearnPress - Paid Membership Pro Integration
Plugin URI: http://thimpress.com/learnpress
Description: Paid Membership Pro add-on for LearnPress.
Author: ThimPress
Version: 3.1.9
Author URI: http://thimpress.com
Tags: learnpress, lms
Text Domain: learnpress-paid-membership-pro
Domain Path: /languages/
*/

defined( 'ABSPATH' ) || exit;

define( 'LP_ADDON_PMPRO_VER', '3.1.8' );
define( 'LP_ADDON_PMPRO_REQUIRE_VER', '3.0.0' );
define( 'LP_ADDON_PMPRO_PATH', dirname( __FILE__ ) );
define( 'LP_ADDON_PMPRO_URL', plugin_dir_url( __FILE__ ) );
define( 'LP_ADDON_PMPRO_TEMP', LP_ADDON_PMPRO_PATH . DIRECTORY_SEPARATOR.'templates' );

/**
 * Class LP_Addon_Paid_Memberships_Pro_Preload
 */
class LP_Addon_Paid_Memberships_Pro_Preload {

	/**
	 * LP_Addon_Paid_Memberships_Pro_Preload constructor.
	 */
	public function __construct() {
		add_action( 'learn-press/ready', array( $this, 'load' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Load addon
	 */
	public function load() {
		LP_Addon::load( 'LP_Addon_Paid_Memberships_Pro', 'inc/load.php', __FILE__ );
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
							__( '<strong>%s</strong> addon version %s requires %s version %s or higher is <strong>installed</strong> and <strong>activated</strong>.', 'learnpress-paid-membership-pro' ),
							__( 'LearnPress Paid Memberships Pro', 'learnpress-paid-membership-pro' ),
							LP_ADDON_PMPRO_VER,
							sprintf( '<a href="%s" target="_blank"><strong>%s</strong></a>', admin_url( 'plugin-install.php?tab=search&type=term&s=learnpress' ), __( 'LearnPress', 'learnpress-paid-membership-pro' ) ),
							LP_ADDON_PMPRO_REQUIRE_VER
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

new LP_Addon_Paid_Memberships_Pro_Preload();