<?php

/**
 * Class Thim_Product_Registration.
 *
 * @package   Thim_Core
 * @since     0.2.1
 */
class Thim_Product_Registration extends Thim_Singleton {
	/**
	 * @since 0.2.1
	 *
	 * @var string
	 */
	public static $key_callback_request = 'tc_callback_registration';

	/**
	 * Premium themes.
	 *
	 * @since 0.9.0
	 *
	 * @var null
	 */
	private static $themes = null;

	/**
	 * Deregister product registration.
	 *
	 * @since 1.5.0
	 *
	 * @return true|WP_Error
	 */
	public static function deregister() {
		if ( ! self::is_active() ) {
			return true;
		}

		$allow_deregister = apply_filters( 'thim_core_allow_deregister_activation', true );
		if ( ! $allow_deregister ) {
			return new WP_Error( 'not_allowed', __( 'Can not deregister activation.', 'thim-core' ) );
		}

		$site_key = self::get_site_key();
		$code     = thim_core_generate_code_by_site_key( $site_key );

		$url     = Thim_Admin_Config::get( 'host_downloads' ) . '/deregister';
		$request = Thim_Remote_Helper::post(
			$url,
			array(
				'body' => array(
					'code' => $code,
				),
			),
			true
		);

		if ( is_wp_error( $request ) ) {
			return $request;
		}

		if ( ! isset( $request->success ) ) {
			return new WP_Error( 'something_went_wrong', __( 'Something went wrong!', 'thim-core' ) );
		}

		$result = $request->success;
		if ( ! $result ) {
			$message = isset( $request->data ) ? $request->data : '';

			return new WP_Error( 'deregister_wrong', $message );
		}

		self::destroy_active();

		return true;
	}

	/**
	 * Double check theme update before inject update theme.
	 *
	 * @since 1.1.1
	 */
	public static function double_check_theme_update() {
		$instance = self::instance();

		$instance->check_theme_update( true );
	}

	/**
	 * Get product registration data.
	 *
	 * @since 0.9.0
	 *
	 * @return array();
	 */
	public static function get_themes() {
		if ( self::$themes === null ) {
			self::$themes = get_site_option( 'thim_core_product_registration_themes' );
		}

		self::$themes = (array) self::$themes;

		foreach ( self::$themes as $key => $theme ) {
			if ( is_numeric( $key ) ) {
				unset( self::$themes[ $key ] );
			}
		}

		return self::$themes;
	}

	/**
	 * Set product registration data.
	 *
	 * @since 0.9.0
	 *
	 * @param array $data
	 */
	private static function _set_themes( $data = array() ) {
		self::$themes = $data;

		update_site_option( 'thim_core_product_registration_themes', $data );
	}

	/**
	 * Get registration data by theme.
	 *
	 * @since 0.9.0
	 *
	 * @param $field
	 * @param null $theme
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public static function get_data_by_theme( $field, $default = false, $theme = null ) {
		if ( ! $theme ) {
			$theme = Thim_Theme_Manager::get_current_theme();
		}

		$registration_data = self::get_themes();

		if ( ! $registration_data ) {
			return $default;
		}

		$theme_data = isset( $registration_data[ $theme ] ) ? $registration_data[ $theme ] : false;

		if ( ! $theme_data ) {
			return $default;
		}

		return isset( $theme_data[ $field ] ) ? $theme_data[ $field ] : $default;
	}

	/**
	 * Get filed data by theme.
	 *
	 * @since 0.9.0
	 *
	 * @param $theme
	 * @param $field
	 * @param $value
	 */
	public static function set_data_by_theme( $field, $value, $theme = null ) {
		if ( ! $theme ) {
			$theme = Thim_Theme_Manager::get_current_theme();
		}

		$registration_data = self::get_themes();

		$theme_data           = isset( $registration_data[ $theme ] ) ? $registration_data[ $theme ] : array();
		$theme_data           = (array) $theme_data;
		$theme_data[ $field ] = $value;

		$registration_data[ $theme ] = $theme_data;

		self::_set_themes( $registration_data );
	}

	/**
	 * Save item id.
	 *
	 * @since 0.7.0
	 *
	 * @param $item_id
	 */
	private static function save_item_id( $item_id ) {
		self::set_data_by_theme( 'envato_item_id', $item_id );
		self::set_time_activation_successful();
	}

	/**
	 * Set time activation successful.
	 *
	 * @since 0.8.0
	 *
	 * @param $time
	 */
	private static function set_time_activation_successful( $time = null ) {
		if ( ! $time ) {
			$time = time();
		}

		self::set_data_by_theme( 'time_activate_successful', $time );
	}

	/**
	 * Set time activation successful.
	 *
	 * @since 0.8.0
	 *
	 * @return int
	 */
	public static function get_time_activation_successful() {
		$time = self::get_data_by_theme( 'time_activate_successful' );

		if ( empty( $time ) ) {
			$time = time();
			self::set_time_activation_successful( $time );
		}

		return (int) $time;
	}

	/**
	 * Get item id.
	 *
	 * @since 0.7.0
	 *
	 * @param $stylesheet
	 *
	 * @return bool|string
	 */
	public static function get_item_id( $stylesheet = null ) {
		$option = self::get_data_by_theme( 'envato_item_id', false, $stylesheet );

		return $option;
	}

	/**
	 * Get personal token.
	 *
	 * @since 0.7.0
	 *
	 * @param $stylesheet
	 *
	 * @return bool|string
	 */
	public static function get_token( $stylesheet = null ) {
		$type = self::get_type_activation( $stylesheet );
		if ( $type != 'personal' ) {
			return self::get_access_token( $stylesheet );
		}

		return self::get_data_by_theme( 'envato_personal_token', false, $stylesheet );
	}

	/**
	 * Save refresh token.
	 *
	 * @since 1.3.0
	 *
	 * @param $site_key
	 */
	public static function save_site_key( $site_key ) {
		self::set_data_by_theme( 'site_key', $site_key );
	}

	/**
	 * Get refresh token.
	 *
	 * @since 1.3.0
	 *
	 * @param $stylesheet
	 *
	 * @return bool|string
	 */
	public static function get_site_key( $stylesheet = null ) {
		$option = self::get_data_by_theme( 'site_key', false, $stylesheet );

		return apply_filters( 'thim_core_registration_site_key', $option, $stylesheet );
	}

	/**
	 * Save refresh token.
	 *
	 * @since 0.7.0
	 *
	 * @param $token
	 */
	private static function save_refresh_token( $token ) {
		self::set_data_by_theme( 'envato_refresh_token', $token );
	}

	/**
	 * Get refresh token.
	 *
	 * @since 0.7.0
	 *
	 * @param $stylesheet
	 *
	 * @return bool|string
	 */
	public static function get_refresh_token( $stylesheet = null ) {
		$option = self::get_data_by_theme( 'envato_refresh_token', false, $stylesheet );

		return $option;
	}

	/**
	 * Save refresh token.
	 *
	 * @since 0.7.0
	 *
	 * @param $token
	 * @param $stylesheet
	 */
	private static function save_access_token( $token, $stylesheet = null ) {
		self::set_data_by_theme( 'envato_access_token', $token, $stylesheet );
	}

	/**
	 * Get refresh token.
	 *
	 * @since 0.7.0
	 *
	 * @param $stylesheet
	 *
	 * @return bool|string
	 */
	public static function get_access_token( $stylesheet = null ) {
		$option = self::get_data_by_theme( 'envato_access_token', false, $stylesheet );

		return $option;
	}

	/**
	 * Set type activation.
	 *
	 * @since 0.8.9
	 *
	 * @param $type
	 */
	private static function set_type_activation( $type ) {
		self::set_data_by_theme( 'envato_type_activation', $type );
	}

	/**
	 * Get type activation.
	 *
	 * @since 0.8.9
	 *
	 * @param $stylesheet
	 *
	 * @return mixed
	 */
	public static function get_type_activation( $stylesheet = null ) {
		$option = self::get_data_by_theme( 'envato_type_activation', 'personal', $stylesheet );

		return $option;
	}

	/**
	 * Get active theme from envato.
	 *
	 * @since 0.2.1
	 *
	 * @return bool
	 */
	public static function is_active() {
		$site_key  = self::get_site_key();
		$is_active = ! empty( $site_key );

		return apply_filters( 'thim_core_production_registration_is_active', $is_active );
	}

	/**
	 * Destroy active theme from envato.
	 *
	 * @since 0.8.0
	 */
	public static function destroy_active() {
		self::save_site_key( false );
	}

	/**
	 * Get url auth.
	 *
	 * @since 0.2.1
	 *
	 * @return string
	 */
	public static function get_url_auth() {
		$base_url = Thim_Admin_Config::get( 'host_envato_app' ) . '/register';

		return $base_url;
	}

	/**
	 * Get verify callback url.
	 *
	 * @since 0.2.1
	 *
	 * @param $return
	 *
	 * @return string
	 */
	public static function get_url_verify_callback( $return = false ) {
		$url = Thim_Dashboard::get_link_main_dashboard(
			array(
				self::$key_callback_request => 1,
			)
		);

		if ( $return ) {
			$url = add_query_arg(
				array(
					'return' => urlencode( $return ),
				),
				$url
			);
		}

		return $url;
	}

	/**
	 * Get url link download theme from envato.
	 *
	 * @since 0.7.0
	 *
	 * @param $stylesheet
	 *
	 * @return WP_Error|string
	 */
	public static function get_url_download_theme( $stylesheet = null ) {
		$refresh_token = self::get_refresh_token( $stylesheet );
		$item_id       = self::get_item_id( $stylesheet );

		return Thim_Envato_API::get_url_download_item( $item_id, $refresh_token );
	}

	/**
	 * Get link review of theme on themeforest.
	 *
	 * @sicne
	 *
	 * @return string
	 */
	public static function get_link_reviews() {
		$link       = 'https://themeforest.net/downloads';
		$theme_data = Thim_Theme_Manager::get_metadata();
		$item_id    = $theme_data['envato_item_id'];

		if ( ! empty( $item_id ) ) {
			$link .= sprintf( '#item-%s', $item_id );
		}

		return $link;
	}

	/**
	 * Thim_Product_Registration constructor.
	 *
	 * @since 0.2.1
	 */
	protected function __construct() {
		$this->init_hooks();
		$this->upgrader();
	}

	/**
	 * Upgrader.
	 *
	 * @since 0.9.0
	 */
	private function upgrader() {
		Thim_Auto_Upgrader::instance();
	}

	/**
	 * Init hooks.
	 *
	 * @since 0.2.1
	 */
	private function init_hooks() {
		add_action( 'admin_init', array( $this, 'handle_callback_verify' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_ajax_thim_core_update_theme', array( $this, 'ajax_update_theme' ) );
		add_action( 'thim_core_background_check_update_theme', array( $this, 'background_check_update_theme' ), 1 );
		add_action( 'thim_core_list_modals', array( $this, 'add_modal_activate_theme' ) );
		add_action( 'thim_core_dashboard_init', array( $this, 'handle_deregister' ) );
		add_action( 'template_redirect', array( $this, 'handle_connect_check_activation' ) );
		add_action( 'thim_core_check_product_registration', array( $this, 'schedule_check_product_registration' ) );
		add_action( 'thim_core_dashboard_init', array( $this, 'update_manual_site_key' ) );
	}

	/**
	 * Update site key manually.
	 *
	 * @since 1.6.0
	 */
	public function update_manual_site_key() {
		if ( ! isset( $_REQUEST['tc-site-key'] ) ) {
			return;
		}

		$site_key = $_REQUEST['tc-site-key'];
		self::save_site_key( $site_key );
	}

	/**
	 * Schedule check product registration.
	 *
	 * @since 1.5.0
	 */
	public function schedule_check_product_registration() {
		if ( TP::is_debug() ) {
			return;
		}

		$url      = Thim_Admin_Config::get( 'host_downloads' ) . '/check-site-key/';
		$site_key = self::get_site_key();
		$code     = thim_core_generate_code_by_site_key( $site_key );

		$response = Thim_Remote_Helper::post(
			$url,
			array(
				'body' => array(
					'code' => $code,
				),
			),
			true
		);

		if ( ! isset( $response->success ) || $response->success !== false ) {
			return;
		}

		$data = isset( $response->data ) ? $response->data : false;
		if ( ! $data ) {
			return;
		}

		$code = isset( $data->code ) ? $data->code : false;
		if ( $code === 'invalid' ) {
			self::destroy_active();
		}
	}

	/**
	 * Handle request check activation.
	 *
	 * @since 1.4.10
	 */
	public function handle_connect_check_activation() {
		$check = isset( $_REQUEST['thim-core-check-activation'] );

		if ( ! $check ) {
			return;
		}

		$site_key = ! empty( $_REQUEST['site-key'] ) ? $_REQUEST['site-key'] : false;
		if ( ! $site_key ) {
			wp_send_json_error(
				__( 'Site key is empty.', 'thim-core' )
			);
		}

		if ( ! self::is_active() ) {
			wp_send_json_error(
				__( 'Site has not been activate theme.', 'thim-core' )
			);
		}

		$my_site_key = self::get_site_key();
		if ( $my_site_key !== $site_key ) {
			wp_send_json_error(
				__( 'Site key is invalid.', 'thim-core' )
			);
		}

		wp_send_json_success( __( 'Ok!', 'thim-core' ) );
	}

	/**
	 * Handle deregister.
	 *
	 * @since 1.4.2
	 */
	public function handle_deregister() {
		if ( ! isset( $_REQUEST['thim-core-deregister'] ) ) {
			return;
		}

		$result = self::deregister();

		if ( is_wp_error( $result ) ) {
			$link = Thim_Dashboard::get_link_main_dashboard();
			$link = add_query_arg(
				array(
					'thim-core-error' => $result->get_error_code(),
				),
				$link
			);
			thim_core_redirect( $link );

			return;
		}

		$link = Thim_Dashboard::get_link_main_dashboard();
		thim_core_redirect( $link );
	}

	/**
	 * Add modal activate theme.
	 *
	 * @since 1.3.4
	 */
	public function add_modal_activate_theme() {
		if ( Thim_Free_Theme::is_free() ) {
			return;
		}

		if ( self::is_active() ) {
			return;
		}

		Thim_Modal::render_modal( array(
			'id'       => 'tc-modal-activate-theme',
			'template' => 'registration/activate-modal.php',
		) );
	}

	/**
	 * Handle ajax update theme.
	 *
	 * @since 1.1.0
	 */
	public function ajax_update_theme() {
		check_ajax_referer( 'thim_core_update_theme', 'nonce' );

		$theme_data = Thim_Theme_Manager::get_metadata();
		$theme      = $theme_data['template'];

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Theme_Upgrader( $skin );
		$results  = $upgrader->bulk_upgrade( array( $theme ) );
		$messages = $skin->get_upgrade_messages();

		if ( ! $results || ! isset( $results[ $theme ] ) ) {
			wp_send_json_error( $messages );
		}

		$result = $results[ $theme ];
		if ( ! $result ) {
			wp_send_json_error( array( __( 'Something went wrong! Please try again later.', 'thim-core' ) ) );
		}

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_messages() );
		}

		$theme_data = Thim_Theme_Manager::get_metadata( true );
		$theme      = $theme_data['version'];

		wp_send_json_success( $theme );
	}

	/**
	 * Check update theme in background.
	 *
	 * @since 1.1.0
	 */
	public function background_check_update_theme() {
		$force = isset( $_GET['force-check'] );

		$this->check_theme_update( $force );
	}

	/**
	 * Notice review for theme on themeforest.
	 *
	 * @since 0.8.9
	 */
	public function notice_review_theme() {
		if ( ! self::is_active() ) {
			return;
		}

		$start  = self::get_time_activation_successful();
		$now    = time();
		$period = $now - $start;
		if ( $period / 86400 < 7 ) {// If activated great than 7 days then notice
			return;
		}

		$link_review = self::get_link_reviews();

		Thim_Notification::add_notification(
			array(
				'id'          => 'review_theme',
				'type'        => 'success',
				'content'     => sprintf( __( 'If you are happy with this theme, please <a href="%s" target="_blank">leave us a 5-star rating</a> on ThemeForest to support and encourage us.', 'thim-core' ), $link_review ),
				'dismissible' => true,
				'global'      => false,
			)
		);
	}

	/**
	 * Get check update themes.
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public static function get_update_themes() {
		$update = get_option( 'thim_core_check_update_themes', array() );

		return wp_parse_args( $update, array(
			'last_checked' => false,
			'themes'       => array(),
		) );
	}

	/**
	 * Check update theme from envato.
	 *
	 * @since 1.1.0
	 *
	 * @param $force bool
	 */
	private function check_theme_update( $force = false ) {
		$update_themes = self::get_update_themes();
		$last_checked  = $update_themes['last_checked'];
		$now           = time();
		$timeout       = 12 * 3600;

		if ( ! $force && $last_checked && $now - $last_checked < $timeout ) {
			return;
		}

		$theme_data      = Thim_Theme_Manager::get_metadata();
		$item_id         = $theme_data['envato_item_id'];
		$current_version = $theme_data['version'];

		$checker                       = new Thim_Theme_Envato_Check_Update( $item_id, $current_version );
		$update_themes['last_checked'] = $now;
		$data                          = $checker->get_theme_data();

		$themes   = (array) $update_themes['themes'];
		$template = $theme_data['template'];
		if ( $data ) {
			$themes[ $template ] = array(
				'update'       => $checker->can_update(),
				'theme'        => $template,
				'name'         => $data['theme_name'],
				'description'  => $data['description'],
				'version'      => $data['version'],
				'icon'         => $data['icon'],
				'author'       => $data['author_name'],
				'author_url'   => $data['author_url'],
				'rating'       => $data['rating'],
				'rating_count' => $data['rating_count'],
				'url'          => $data['url'],
				'package'      => '',
			);
		} else {
			unset( $themes[ $template ] );
		}

		$update_themes['themes'] = $themes;

		update_option( 'thim_core_check_update_themes', $update_themes );
	}

	/**
	 * Handle callback from server verify.
	 *
	 * @since 0.2.1
	 */
	public function handle_callback_verify() {
		$detect_request = isset( $_GET[ self::$key_callback_request ] );

		if ( ! $detect_request ) {
			return;
		}

		$error = isset( $_GET['error'] ) ? $_GET['error'] : false;
		if ( $error ) {
			$error_description = isset( $_GET['error_description'] ) ? $_GET['error_description'] : __( 'Something went wrong! Please try again later.', 'thim-core' );
			if ( $error == 'api_error' ) {
				$error_description = __( 'Envato API system has occurred error. Please try again later!', 'thim-core' );
			}

			Thim_Notification::add_notification( array(
				'id'      => 'activate_theme',
				'type'    => 'error',
				'content' => $error_description,
			) );

			return;
		}

		$queries = wp_parse_args( $_GET, array(
			'refresh_token' => '',
			'access_token'  => '',
			'site_key'      => '',
			'item_id'       => '',
			'redirect'      => '',
		) );

		$refresh_token = $queries['refresh_token'];
		$access_token  = $queries['access_token'];
		$item_id       = $queries['item_id'];
		$site_key      = $queries['site_key'];
		self::save_refresh_token( $refresh_token );
		self::save_access_token( $access_token );
		self::save_site_key( $site_key );
		self::save_item_id( $item_id );
		self::set_type_activation( 'oath' );

		Thim_Notification::add_notification( array(
			'id'      => 'activate_theme',
			'type'    => 'success',
			'content' => __( 'Activate theme successful!', 'thim-core' ),
		) );

		$redirect = $queries['redirect'];
		if ( ! empty( $redirect ) ) {
			thim_core_redirect( $redirect );
		}

		thim_core_redirect( Thim_Dashboard::get_link_main_dashboard() );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param $page_now
	 *
	 * @since 0.7.0
	 */
	public function enqueue_scripts( $page_now ) {
		if ( strpos( $page_now, Thim_Dashboard::$prefix_slug . 'dashboard' ) === false ) {
			return;
		}

		wp_enqueue_script( 'thim-theme-update', THIM_CORE_ADMIN_URI . '/assets/js/theme-update.js', array( 'jquery' ), THIM_CORE_VERSION );

		$this->_localize_script();
	}

	/**
	 * Localize script.
	 *
	 * @since 0.7.0
	 */
	private function _localize_script() {
		$nonce           = wp_create_nonce( 'thim_core_update_theme' );
		$link_deregister = Thim_Dashboard::get_link_main_dashboard(
			array(
				'thim-core-deregister' => true,
			)
		);

		wp_localize_script( 'thim-theme-update', 'thim_theme_update', array(
			'admin_ajax'     => admin_url( 'admin-ajax.php' ),
			'action'         => 'thim_core_update_theme',
			'nonce'          => $nonce,
			'url_deregister' => $link_deregister,
			'i18l'           => array(
				'confirm_deregister' => __( 'Are you sure to remove theme activation??', 'thim-core' ),
				'updating'           => __( 'Updating...', 'thim-core' ),
				'updated'            => __( 'Updated!', 'thim-core' ),
				'wrong'              => __( 'Some thing went wrong. Please try again later!', 'thim-core' ),
				'warning_leave'      => __( 'The update process will cause errors if you leave this page!', 'thim-core' ),
			),
		) );
	}
}
