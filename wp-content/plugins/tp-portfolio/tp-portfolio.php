<?php
/*
Plugin Name: Thim Portfolio
Plugin URI: http://thimpress.com
Description: A plugin that allows you to show off your portfolio.
Author: ThimPress
Version: 1.8
Author URI: http://thimpress.com
Requires at least: 3.8
Tested up to: 5.1.0

Text Domain: tp-portfolio
Domain Path: /languages/
*/

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! defined( 'TP_PORTFOLIO_PLUGIN_FILE' ) ) {
	define( 'TP_PORTFOLIO_PLUGIN_FILE', __FILE__ );
	require_once dirname( __FILE__ ) . '/inc/constants.php';
}

if ( ! class_exists( 'Thim_Portfolio' ) ) {
	/**
	 * Class Thim_Portfolio.
	 */
	class Thim_Portfolio {

		/**
		 * Current version of the plugin
		 *
		 * @var string
		 */
		public $version = THIM_PORTFOLIO_VERSION;

		/**
		 * The single instance of the class
		 *
		 * @var Thim_Portfolio object
		 */
		private static $_instance = null;

		/**
		 * Thim_Portfolio constructor.
		 */
		public function __construct() {
			// Prevent duplicate unwanted hooks
			if ( self::$_instance ) {
				return;
			}
			self::$_instance = $this;

			// include files
			$this->includes();
			// hooks
			$this->init_hooks();
		}

		/**
		 * Includes files.
		 */
		public function includes() {

			require_once 'inc/functions.php';
			require_once 'inc/aq_resizer.php';

			require_once 'inc/class-tp-post-types.php';

			// include own metabox
			require_once 'libraries/meta-boxes/thim-meta-box.php';
			require_once 'libraries/meta-boxes/init.php';
		}

		/**
		 * Init hooks.
		 */
		public function init_hooks() {
			// Load text domain
			add_action( 'plugins_loaded', array( $this, 'text_domain' ) );
		}

		/**
		 * Load text domain.
		 */
		public function text_domain() {
			// Get mo file
			$text_domain = 'tp-portfolio';
			$locale      = apply_filters( 'plugin_locale', get_locale(), $text_domain );
			$mo_file     = $text_domain . '-' . $locale . '.mo';
			// Check mo file global
			$mo_global = WP_LANG_DIR . '/plugins/' . $mo_file;
			// Load translate file
			if ( file_exists( $mo_global ) ) {
				load_textdomain( $text_domain, $mo_global );
			} else {
				load_textdomain( $text_domain, CORE_PLUGIN_PATH . '/languages/' . $mo_file );
			}
		}

		/**
		 * Main plugin instance.
		 *
		 * @return Thim_Portfolio
		 */
		public static function instance() {
			if ( ! self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}

Thim_Portfolio::instance();