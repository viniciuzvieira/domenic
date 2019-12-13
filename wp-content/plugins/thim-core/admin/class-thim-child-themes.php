<?php

/**
 * Class Thim_Child_Themes.
 *
 * @since 1.1.1
 */
class Thim_Child_Themes extends Thim_Admin_Sub_Page {

	/**
	 * @since 1.1.1
	 *
	 * @var string
	 */
	public $key_page = 'child-themes';

	/**
	 * @var Thim_Child_Theme[]
	 *
	 * @since 1.2.0
	 */
	private static $child_themes = null;

	/**
	 * Get list child themes
	 *
	 * @since 1.2.0
	 *
	 * @return Thim_Child_Theme[]
	 */
	public static function child_themes() {
		if ( self::$child_themes === null ) {
			$themes = array();

			$input_child_themes = Thim_Theme_Manager::get_data( 'child_themes' );
			if ( empty( $input_child_themes ) || ! is_array( $input_child_themes ) ) {
				self::$child_themes = array();

				return self::$child_themes;
			}

			foreach ( $input_child_themes as $args ) {
				$themes[] = new Thim_Child_Theme( $args );
			}

			self::$child_themes = $themes;
		}

		return self::$child_themes;
	}

	/**
	 * Get data list child themes.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_data_child_themes() {
		$child_themes = self::child_themes();
		$themes       = array_map(
			function ( $theme ) {
				$data = $theme->toArray();

				unset( $data['source'] );

				return $data;
			},
			$child_themes
		);

		return $themes;
	}

	/**
	 * Thim_Child_Themes constructor.
	 *
	 * @since 1.1.1
	 */
	protected function __construct() {
		parent::__construct();

		$this->init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.1.1
	 */
	private function init_hooks() {
		add_filter( 'thim_dashboard_sub_pages', array( $this, 'add_sub_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'switch_theme', array( $this, 'switch_theme_update_mods' ) );
		add_action( 'wp_ajax_thim_child_themes_action', array( $this, 'handle_ajax_action' ) );
	}

	/**
	 * Handle ajax action.
	 *
	 * @since 1.2.0
	 */
	public function handle_ajax_action() {
		$slug   = isset( $_REQUEST['slug'] ) ? $_REQUEST['slug'] : false;
		$action = isset( $_REQUEST['thim_action'] ) ? $_REQUEST['thim_action'] : false;

		if ( empty( $slug ) || empty( $action ) ) {
			wp_send_json_error( __( 'Something went wrong!', 'thim-core' ) );
		}

		$result = new WP_Error( 'thim_core_them_not_found', __( 'This theme not exist!', 'thim-core' ) );

		$themes = self::child_themes();
		foreach ( $themes as $theme ) {
			$theme_slug   = $theme->get( 'slug' );
			$theme_status = $theme->get_status();

			if ( $slug == $theme_slug ) {
				switch ( $action ) {
					case 'install':
						if ( $theme_status != 'not_installed' ) {
							wp_send_json_error( __( 'This theme has already installed!', 'thim-core' ) );
						}

						$result = $theme->install();

						break;

					case 'activate':
						if ( $theme_status == 'not_installed' ) {
							wp_send_json_error( __( 'This theme has not installed yet!', 'thim-core' ) );
						}
						$result = $theme->activate();

						break;
				}
			}
		}

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		Thim_Theme_Manager::flush();
		$themes = self::get_data_child_themes();

		wp_send_json_success( $themes );
	}

	/**
	 * Update theme mods when switch theme.
	 *
	 * @since 1.0.3
	 */
	public function switch_theme_update_mods() {
		if ( ! thim_core_is_child_theme() ) {
			return;
		}

		$child_mods = get_theme_mods();
		if ( ! empty( $child_mods ) ) {
			//return;
		}

		if ( get_theme_mod( 'thim_core_extend_parent_theme', false ) ) {
			return;
		}

		$mods = get_option( 'theme_mods_' . get_option( 'template' ) );

		if ( false === $mods ) {
			return;
		}

		foreach ( (array) $mods as $mod => $value ) {
			set_theme_mod( $mod, $value );
		}

		set_theme_mod( 'thim_core_extend_parent_theme', true );
	}

	/**
	 * Add sub page.
	 *
	 * @since 1.1.1
	 *
	 * @param $sub_pages array
	 *
	 * @return array
	 */
	public function add_sub_page( $sub_pages ) {
		if ( ! current_user_can( 'switch_themes' ) && ! current_user_can( 'edit_theme_options' ) ) {
			return $sub_pages;
		}

		$theme_data   = Thim_Theme_Manager::get_metadata();
		$child_themes = $theme_data['child_themes'];

		if ( empty( $child_themes ) ) {
			return $sub_pages;
		}

		$sub_pages[ $this->key_page ] = array(
			'title' => __( 'Child Themes', 'thim-core' ),
		);

		return $sub_pages;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.2.0
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_myself() ) {
			return;
		}

		wp_enqueue_script( 'thim-child-themes', THIM_CORE_ADMIN_URI . '/assets/js/child-themes.js', array( 'wp-util', 'jquery', 'backbone', 'underscore' ), THIM_CORE_VERSION );
		$this->localize_script();
	}

	/**
	 * Localize script.
	 *
	 * @since 1.2.0
	 */
	private function localize_script() {
		$data = $this->get_data_template();
		wp_localize_script( 'thim-child-themes', 'tc_child_themes', $data );
	}

	/**
	 * Get data template.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	private function get_data_template() {
		$themes = self::get_data_child_themes();

		return array(
			'themes'      => $themes,
			'url_ajax'    => admin_url( 'admin-ajax.php' ),
			'ajax_action' => 'thim_child_themes_action'
		);
	}
}
