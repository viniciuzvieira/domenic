<?php

/**
 * Class Thim_Plugins_Manager.
 *
 * @since 0.5.0
 */
class Thim_Plugins_Manager extends Thim_Admin_Sub_Page {
	/**
	 * @since 1.1.0
	 *
	 * @var null
	 */
	private static $plugins = null;

	/**
	 * @since 0.8.5
	 *
	 * @var string
	 */
	public $key_page = 'plugins';

	/**
	 * @var string
	 *
	 * @since 0.4.0
	 */
	public static $page_key = 'plugins';

	/**
	 * @var null
	 *
	 * @since 0.5.0
	 */
	private static $all_plugins_require = null;

	/**
	 * Is writable.
	 *
	 * @since 0.5.0
	 *
	 * @var bool
	 */
	private static $is_writable = null;

	/**
	 * Add notice.
	 *
	 * @since 0.5.0
	 *
	 * @param string $content
	 * @param string $type
	 */
	public static function add_notification( $content = '', $type = 'success' ) {
		Thim_Dashboard::add_notification( array(
			'content' => $content,
			'type'    => $type,
			'page'    => self::$page_key,
		) );
	}

	/**
	 * Get url plugin actions.
	 *
	 * @since 0.8.8
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public static function get_url_plugin_actions( $args ) {
		$args = wp_parse_args( $args, array(
			'slug'          => '',
			'plugin-action' => '',
			'network'       => '',
		) );

		$args['action'] = 'thim_plugins';

		$url = admin_url( 'admin.php' );
		$url = add_query_arg( $args, $url );

		return $url;
	}

	/**
	 * Get link delete plugin.
	 *
	 * @since 1.0.3
	 *
	 * @param $plugin_file
	 *
	 * @return string
	 */
	public static function get_url_delete_plugin( $plugin_file ) {
		$url = wp_nonce_url( 'plugins.php?action=delete-selected&checked[]=' . $plugin_file . '&plugin_status=all', 'bulk-plugins' );

		return network_admin_url( $url );
	}

	/**
	 * Thim_Plugins_Manager constructor.
	 *
	 * @since 0.4.0
	 */
	protected function __construct() {
		parent::__construct();

		$this->init_hooks();
	}

	/**
	 * Get link download plugin by slug.
	 *
	 * @since 1.4.1
	 *
	 * @param $slug
	 *
	 * @return bool|string
	 */
	public static function get_link_download_plugin( $slug ) {
//		if ( ! Thim_Product_Registration::is_active() && ! Thim_Free_Theme::is_free() ) {
//			$envato_id = Thim_Theme_Manager::get_data( 'envato_item_id', false );
//			if ( $envato_id ) {
//				return false;
//			}
//		}

		$site_key = Thim_Product_Registration::get_site_key();
		$code     = thim_core_generate_code_by_site_key( $site_key );
		$url      = sprintf( '%s/download-plugin/?plugin=%s&code=%s',
			Thim_Admin_Config::get( 'host_downloads' ),
			$slug,
			$code
		);

		return apply_filters( 'thim_core_get_link_download_plugin', $url );
	}

	/**
	 * Init hooks.
	 *
	 * @since 0.4.0
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_thim_plugins_manager', array( $this, 'handle_ajax_plugin_request_actions' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'thim_dashboard_sub_pages', array( $this, 'add_sub_page' ) );
		add_action( 'admin_action_thim_plugins', array( $this, 'handle_plugin_actions' ) );
		add_action( 'thim_core_dashboard_init', array( $this, 'notification' ) );
		add_filter( 'thim_core_url_download_private_plugin', array( $this, 'filter_link_download_plugin' ), 10, 2 );
		add_action( 'thim_core_pre_install_plugin', array( $this, 'raise_limit_plugin_installation' ) );
		add_action( 'thim_core_pre_upgrade_plugin', array( $this, 'raise_limit_plugin_installation' ) );
	}

	/**
	 * Raise limit for plugin installation (install/upgrade)
	 *
	 * @since 1.4.8
	 */
	public function raise_limit_plugin_installation() {
		thim_core_set_time_limit();
		wp_raise_memory_limit( 'thim_core_admin' );
	}

	/**
	 * Get link download plugin.
	 *
	 * @since 1.4.1
	 *
	 * @param             $source
	 * @param Thim_Plugin $plugin
	 *
	 * @return string
	 */
	public function filter_link_download_plugin( $source, $plugin ) {
//        if ( ! Thim_Product_Registration::is_active() && ! Thim_Free_Theme::is_free() ) {
//            $envato_id = Thim_Theme_Manager::get_data( 'envato_item_id', false );
//            if ( $envato_id ) {
//                return $source;
//            }
//        }

		return self::get_link_download_plugin( $plugin->get_slug() );
	}

	/**
	 * Handle plugin actions like install, activate, deactivate.
	 *
	 * @since 0.8.8
	 */
	public function handle_plugin_actions() {
		$action   = isset( $_GET['plugin-action'] ) ? $_GET['plugin-action'] : false;
		$slug     = isset( $_GET['slug'] ) ? $_GET['slug'] : false;
		$network  = ! empty( $_GET['network'] ) ? true : false;
		$is_wporg = ! empty( $_GET['wporg'] ) ? true : false;

		if ( ! $action || ! $slug ) {
			return;
		}

		$plugin = new Thim_Plugin( $slug, $is_wporg );

		if ( $action == 'install' ) {
			$plugin->install();

			// Activate after install.
			$link_activate = self::get_url_plugin_actions( array(
				'slug'          => $slug,
				'plugin-action' => 'activate',
				'network'       => $network,
			) );

			thim_core_redirect( $link_activate );
		}

		if ( $action == 'activate' ) {
			$plugin->activate( false, $network );
		}

		if ( $action == 'deactivate' ) {
			$plugin->deactivate();
		}

		thim_core_redirect( admin_url( 'plugins.php' ) );
	}

	/**
	 * Add sub page.
	 *
	 * @since 0.8.5
	 *
	 * @param $sub_pages
	 *
	 * @return mixed
	 */
	public function add_sub_page( $sub_pages ) {
		$sub_pages['plugins'] = array(
			'title' => __( 'Plugins', 'thim-core' ),
		);

		return $sub_pages;
	}

	/**
	 * Handle ajax plugin request action.
	 *
	 * @since 0.4.0
	 */
	public function handle_ajax_plugin_request_actions() {
		$slug   = isset( $_POST['slug'] ) ? $_POST['slug'] : false;
		$action = isset( $_POST['plugin_action'] ) ? $_POST['plugin_action'] : false;

		$plugins = self::get_plugins();
		foreach ( $plugins as $plugin ) {
			if ( $plugin->get_slug() == $slug ) {
				$result = false;

				$next_action = 'activate';

				switch ( $action ) {
					case 'install':
						$result = $plugin->install();
						break;

					case 'activate':
						$result      = $plugin->activate( null );
						$next_action = 'deactivate';
						break;

					case 'deactivate':
						$result = $plugin->deactivate();
						break;

					case 'update':
						$result    = $plugin->update();
						$is_active = $plugin->is_active();
						if ( $is_active ) {
							$next_action = 'deactivate';
						} else {
							$next_action = 'activate';
						}
						break;
				}

				if ( $result ) {
					wp_send_json_success( array(
						'messages' => $plugin->get_messages(),
						'action'   => $next_action,
						'text'     => ucfirst( $next_action ),
						'slug'     => $plugin->get_slug(),
						'version'  => $plugin->get_current_version(),
						'info'     => $plugin->get_info(),
						'status'   => $plugin->get_status(),
					) );
				}

				wp_send_json_error( array(
					'messages' => $plugin->get_messages(),
				) );
			}
		}

		wp_send_json_error();
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 0.4.0
	 *
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'thim-plugins', THIM_CORE_ADMIN_URI . '/assets/js/plugins/thim-plugins.js', array( 'jquery' ), THIM_CORE_VERSION );
		$this->localize_script();

		if ( ! $this->is_myself() ) {
			return;
		}
		wp_enqueue_script( 'thim-isotope', THIM_CORE_ADMIN_URI . '/assets/js/plugins/isotope.pkgd.min.js', array( 'jquery' ), THIM_CORE_VERSION );
		wp_enqueue_script( 'thim-plugins-manager', THIM_CORE_ADMIN_URI . '/assets/js/plugins/plugins-manager.js', array(
			'thim-plugins',
			'thim-isotope',
			'updates',
			'thim-core-admin'
		), THIM_CORE_VERSION );
	}

	/**
	 * Localize script.
	 *
	 * @since 0.4.0
	 */
	public function localize_script() {
		wp_localize_script( 'updates', '_wpUpdatesItemCounts', array(
			'totals' => wp_get_update_data(),
		) );

		wp_localize_script( 'thim-plugins', 'thim_plugins_manager', array(
			'admin_ajax_action' => admin_url( 'admin-ajax.php' ),
		) );
	}

	/**
	 * Get all plugins require.
	 *
	 * @since 1.1.0
	 *
	 * @return Thim_Plugin[]
	 */
	public static function get_plugins() {
		if ( self::$plugins == null ) {
			$all_plugins = self::get_all_plugins();

			$plugins = array();
			foreach ( $all_plugins as $index => $plugin ) {
				$thim_plugin = new Thim_Plugin();
				$thim_plugin->set_args( $plugin );

				$plugins[] = $thim_plugin;
			}

			self::$plugins = $plugins;
		}

		return self::$plugins;
	}

	/**
	 * Get all plugins.
	 *
	 * @since 0.4.0
	 *
	 * @return array
	 */
	private static function get_all_plugins() {
		if ( self::$all_plugins_require == null ) {
			$plugins = array();

			$plugins = apply_filters( 'thim_core_get_all_plugins_require', $plugins );

			foreach ( $plugins as $index => $plugin ) {
				$plugin = wp_parse_args( $plugin, array(
					'required' => false,
					'add-on'   => false,
					'silent'   => true,
				) );

				$plugins[ $index ] = $plugin;
			}

			uasort( $plugins, function ( $first, $second ) {
				if ( $first['required'] === $second['required'] ) {
					return 0;
				}

				if ( $first['required'] ) {
					return - 1;
				}

				return 1;
			} );

			self::$all_plugins_require = apply_filters( 'thim_core_list_plugins_required', $plugins );
		}

		return self::$all_plugins_require;
	}

	/**
	 * Get external plugins (outside wp.org).
	 *
	 * @since 1.1.0
	 *
	 * @return Thim_Plugin[]
	 */
	public static function get_external_plugins() {
		$required_plugins = self::get_plugins();

		$plugins = array();
		foreach ( $required_plugins as $thim_plugin ) {
			if ( $thim_plugin->is_wporg() ) {
				continue;
			}

			$plugins[] = $thim_plugin;
		}

		return $plugins;
	}

	/**
	 * Get required plugins inactive or not installed.
	 *
	 * @since 0.8.7
	 *
	 * @return Thim_Plugin[]
	 */
	public static function get_required_plugins_inactive() {
		$required_plugins = self::get_plugins();

		$plugins = array();
		foreach ( $required_plugins as $thim_plugin ) {
			if ( $thim_plugin->is_active() || $thim_plugin->is_add_on() ) {
				continue;
			}

			$plugins[] = $thim_plugin;
		}

		return $plugins;
	}

	/**
	 * Get all add ons.
	 *
	 * @since 0.8.6
	 *
	 * @return Thim_Plugin[]
	 */
	public static function get_all_add_ons() {
		$all_plugins = self::get_plugins();

		$plugins = array();
		foreach ( $all_plugins as $plugin ) {
			if ( ! $plugin->is_add_on() ) {
				continue;
			}

			$plugins[] = $plugin;
		}

		return $plugins;
	}

	/**
	 * Get list slug plugins require all demo.
	 *
	 * @since 0.5.0
	 *
	 * @return array
	 */
	public static function get_slug_plugins_require_all() {
		$all_plugins = self::get_plugins();

		$plugins_require_all = array();
		foreach ( $all_plugins as $index => $plugin ) {
			if ( $plugin->is_required() ) {
				array_push( $plugins_require_all, $plugin->get_slug() );
			}
		}

		return $plugins_require_all;
	}

	/**
	 * Get plugin by slug.
	 *
	 * @since 0.5.0
	 *
	 * @param $slug
	 *
	 * @return false|Thim_Plugin
	 */
	public static function get_plugin_by_slug( $slug ) {
		$all_plugins = self::get_plugins();

		if ( count( $all_plugins ) === 0 ) {
			return false;
		}

		foreach ( $all_plugins as $plugin ) {
			if ( $plugin->get_slug() == $slug ) {
				return $plugin;
			}
		}

		return false;
	}

	/**
	 * Check permission plugins directory.
	 *
	 * @since 0.5.0
	 */
	private static function check_permission() {
		self::$is_writable = wp_is_writable( WP_PLUGIN_DIR );
	}

	/**
	 * Get permission writable plugins directory.
	 *
	 * @since 0.8.2
	 */
	public static function get_permission() {
		if ( is_null( self::$is_writable ) ) {
			self::check_permission();
		}

		return self::$is_writable;
	}

	/**
	 * Notice waring.
	 *
	 * @since 0.5.0
	 * @since 1.3.1
	 */
	public function notification() {
		if ( ! self::get_permission() ) {
			Thim_Dashboard::add_notification( array(
				'content' => '<strong>Important!</strong> Please check permission directory <code>' . WP_PLUGIN_DIR . '</code>. Please follow <a href="https://goo.gl/guirO5" target="_blank">the guide</a>.',
				'type'    => 'error',
			) );
		}
	}
}
