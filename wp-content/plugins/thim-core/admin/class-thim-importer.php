<?php

/**
 * Class Thim_Importer.
 *
 * @package   Thim_Core
 * @since     0.1.0
 */
class Thim_Importer extends Thim_Admin_Sub_Page {
	/**
	 * @since 0.8.5
	 *
	 * @var string
	 */
	public $key_page = 'importer';

	/**
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $key_option_demo_installed = 'thim_importer_demo_installed';

	/**
	 * Get key demo installed.
	 *
	 * @since 0.6.0
	 *
	 * @return bool|string
	 */
	public static function get_key_demo_installed() {
		$option = get_option( self::$key_option_demo_installed );

		if ( empty( $option ) ) {
			return false;
		}

		return $option;
	}

	/**
	 * Update key demo installed.
	 *
	 * @since 0.8.1
	 *
	 * @param string|bool $demo
	 *
	 * @return bool
	 */
	public static function update_key_demo_installed( $demo = '' ) {
		return update_option( self::$key_option_demo_installed, $demo );
	}

	/**
	 * Get packages import.
	 *
	 * @since 0.3.0
	 *
	 * @return array
	 */
	public static function get_import_packages() {
		$packages = array(
			'theme_options' => array(
				'title'       => esc_attr__( 'Theme Options', 'thim-core' ),
				'description' => esc_attr__( 'Import theme options and rewrite all current settings.', 'thim-core' ),
			),
			'main_content'  => array(
				'title'       => esc_attr__( 'Main Content', 'thim-core' ),
				'description' => esc_attr__( 'Import posts, pages, comments, menus, custom fields, terms and custom posts.', 'thim-core' ),
			),
			'media'         => array(
				'title'       => esc_attr__( 'Media File', 'thim-core' ),
				'description' => esc_attr__( 'Download media files.', 'thim-core' ),
				'required'    => 'main_content',
			),
			'widgets'       => array(
				'title'       => esc_attr__( 'Widgets', 'thim-core' ),
				'description' => esc_attr__( 'Import widgets data.', 'thim-core' ),
			),
			'revslider'     => array(
				'title'       => esc_attr__( 'Slider Revolution', 'thim-core' ),
				'description' => esc_attr__( 'Import Slider Revolution.', 'thim-core' ),
			),
		);

		return apply_filters( 'thim_core_importer_packages', $packages );
	}

	/**
	 * Get demo data.
	 *
	 * @since 0.2.0
	 *
	 * @return array
	 */
	public static function get_demo_data() {

		$THEME_URI  = get_template_directory_uri();
		$THEME_PATH = get_template_directory();

		$file_demo_data = $THEME_PATH . '/inc/data/demos.php';
		if ( ! file_exists( $file_demo_data ) ) {
			return array();
		}

		$demo_data = include $file_demo_data;

		if ( ! is_array( $demo_data ) ) {
			return array();
		}

		$base_uri_demo_data  = apply_filters( 'thim_core_importer_base_uri_demo_data', $THEME_URI . '/inc/data/demos/' );
		$base_path_demo_data = $THEME_PATH . '/inc/data/demos/';

		foreach ( $demo_data as $key => $demo ) {
			$demo_data[ $key ]['key']        = $key;
			$demo_data[ $key ]['screenshot'] = $base_uri_demo_data . $key . '/screenshot.jpg';
			if ( isset( $demo['thumbnail_url'] ) ) {
				$demo_data[ $key ]['screenshot'] = $demo['thumbnail_url'];
			}
			$demo_data[ $key ]['dir'] = $base_path_demo_data . $key;

			$plugins_require = isset( $demo['plugins_required'] ) ? $demo['plugins_required'] : false;
			if ( ! $plugins_require ) {
				continue;
			}

			if ( ! is_array( $plugins_require ) ) {
				continue;
			}

			$plugins_required_ = array();

			$plugins_require_all = Thim_Plugins_Manager::get_slug_plugins_require_all();
			$plugins_require     = array_merge( $plugins_require_all, $plugins_require );
			$plugins_require     = array_unique( $plugins_require );

			foreach ( $plugins_require as $slug ) {
				$plugin = Thim_Plugins_Manager::get_plugin_by_slug( $slug );

				if ( ! $plugin ) {
					continue;
				}

				if ( $plugin->get_status() === 'active' ) {
					continue;
				}

				array_push( $plugins_required_, $plugin->to_array() );
			}

			$demo_data[ $key ]['plugins_required'] = $plugins_required_;
		}

		return $demo_data;
	}

	/**
	 * Thim_Importer constructor.
	 *
	 * @since 0.2.0
	 */
	protected function __construct() {
		parent::__construct();

		$this->init_hooks();
	}

	/**
	 * Get arguments for template.
	 *
	 * @since 0.8.5
	 *
	 * @return array
	 */
	protected function get_template_args() {
		$demo_data = self::get_demo_data();

		return array(
			'$demo_data'      => $demo_data,
			'$demo_installed' => self::get_key_demo_installed(),
		);
	}

	/**
	 * Init hooks.
	 *
	 * @since 0.3.0
	 */
	private function init_hooks() {
		add_action( 'tc_after_dashboard_wrapper', array( $this, 'add_modal_import' ) );
		add_action( 'tc_after_dashboard_wrapper', array( $this, 'add_modal_uninstall_demo' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_thim_importer', array( $this, 'handle_ajax' ) );
		add_action( 'wp_ajax_thim_importer_uninstall', array( $this, 'handle_ajax_uninstall' ) );
		add_filter( 'thim_dashboard_sub_pages', array( $this, 'add_sub_page' ) );
		add_action( 'admin_init', array( $this, 'reset_import_demo' ) );
		add_filter( 'thim_importer_memory_limit', array( $this, 'memory_limit_importer' ) );
		add_action( 'admin_action_thim_importer_demo_data', array( $this, 'show_current_demo_data' ) );
		add_action( 'thim_import_import_post', array( $this, 'import_mail_chimp' ) );
	}

	/**
	 * Update option form default MailChimp
	 *
	 * @since 0.6.0
	 *
	 * @param $post
	 */
	public function import_mail_chimp( $post ) {
		if ( $post['post_type'] === 'mc4wp-form' ) {
			$post_id = $post['post_id'];

			update_option( 'mc4wp_default_form_id', $post_id );
		}
	}

	/**
	 * Show current demo data.
	 *
	 * @since 1.4.8
	 */
	public function show_current_demo_data() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		$current = Thim_Importer_AJAX::get_current_demo_data();
		print_r( $current );
	}

	/**
	 * Set memory limit for importer.
	 *
	 * @since 1.4.6
	 *
	 * @param $current
	 *
	 * @return string
	 */
	public function memory_limit_importer( $current ) {
		$current_limit_int = wp_convert_hr_to_bytes( $current );

		if ( $current_limit_int > 268435456 ) {
			return $current;
		}

		return '256M';
	}

	/**
	 * Reset config import demo.
	 *
	 * @since 1.3.1
	 */
	public function reset_import_demo() {
		if ( ! $this->is_myself() ) {
			return;
		}

		$detect = isset( $_GET['reset-importer'] );
		if ( ! $detect ) {
			return;
		}

		delete_option( 'thim_importer_posts_pp' );
		delete_option( 'thim_importer_attachments_pp' );
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
		$sub_pages['importer'] = array(
			'title' => __( 'Import Demo', 'thim-core' ),
		);

		return $sub_pages;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 0.3.0
	 *
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_myself() ) {
			return;
		}

		wp_enqueue_script( 'thim-importer', THIM_CORE_ADMIN_URI . '/assets/js/importer/importer.js', array( 'wp-util', 'jquery', 'backbone', 'underscore' ), THIM_CORE_VERSION );

		$this->localize_script();
	}

	/**
	 * Add modal importer.
	 *
	 * @since 0.3.0
	 */
	public function add_modal_import() {
		if ( ! $this->is_myself() ) {
			return;
		}

		Thim_Dashboard::get_template( 'partials/importer-modal.php' );
	}

	/**
	 * Add modal uninstall demo.
	 *
	 * @since 0.8.1
	 */
	public function add_modal_uninstall_demo() {
		if ( ! $this->is_myself() ) {
			return;
		}

		Thim_Dashboard::get_template( 'partials/importer-uninstall-modal.php' );
	}

	/**
	 * Handle ajax import demo.
	 *
	 * @since 0.3.1
	 */
	public function handle_ajax() {
		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			wp_raise_memory_limit( 'thim_importer' );
		}

		$importer_ajax = new Thim_Importer_AJAX();
		$importer_ajax->handle_ajax();
	}

	/**
	 * Handle ajax uninstall demo.
	 *
	 * @since 0.6.0
	 */
	public function handle_ajax_uninstall() {
		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			wp_raise_memory_limit( 'thim_importer' );
		}

		$importer_ajax = new Thim_Importer_AJAX();
		$importer_ajax->handle_ajax_uninstall();
	}

	/**
	 * Localize script.
	 *
	 * @since 0.3.0
	 */
	public function localize_script() {
		$demos = self::get_demo_data();
		$nonce = wp_create_nonce( 'thim-importer' );

		wp_localize_script( 'thim-importer', 'thim_importer_data', array(
			'nonce'                => $nonce,
			'url_ajax'             => admin_url( 'admin-ajax.php' ),
			'admin_ajax_action'    => admin_url( 'admin-ajax.php' ),
			'admin_ajax_uninstall' => admin_url( 'admin-ajax.php' ),
			'details_error'        => array(
				'title'     => __( 'The import demo content failed!', 'thim-core' ),
				'try_again' => __( 'Import failed. The system will automatically adjust some configurations. Please give it one more try. Good luck!', 'thim-core' ),
				'code'      => array(
					'request' => '#001_REQUEST_ERROR',
					'server'  => '#002_SERVER_ERROR',
				),
			),
			'uninstall_successful' => __( 'Uninstall demo content successful :]', 'thim-core' ),
			'uninstall_failed'     => __( 'Uninstall demo content failed. Please try again :]', 'thim-core' ),
			'confirm_close'        => __( 'Do you really want to close?', 'thim-core' ),
			'something_went_wrong' => __( 'Some thing went wrong. Please try again :]', 'thim-core' ),
		) );

		wp_localize_script( 'thim-importer', 'thim_importer', array(
			'demos'     => $demos,
			'installed' => self::get_key_demo_installed(),
		) );
	}
}
