<?php

/**
 * Class Thim_For_Developer.
 *
 * @since 0.4.0
 */
class Thim_For_Developer extends Thim_Admin_Sub_Page {
    /**
     * @var string
     *
     * @since 0.8.5
     */
    public $key_page = 'developer';

    /**
     * Get url download.
     *
     * @since 0.5.0
     *
     * @param $package
     *
     * @return string
     */
    public static function get_url_download( $package ) {
        return Thim_Exporter::get_url_export( $package );
    }

    /**
     * Thim_For_Developer constructor.
     *
     * @since 0.5.0
     */
    protected function __construct() {
        if ( ! TP::is_debug() ) {//Please don't remove it.
            return;
        }

        parent::__construct();

        $this->init_hooks();
    }

    /**
     * Init hooks.
     *
     * @since 0.5.0
     */
    private function init_hooks() {
        add_action( 'thim_exporter_package', array( $this, 'handle_export' ) );
        add_filter( 'thim_dashboard_sub_pages', array( $this, 'add_sub_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'export_wp_filename', array( $this, 'rename_export_file_name' ) );

        add_action( 'thim_core_for_developers_before_boxes', array( $this, 'add_box_content' ), 10 );
        add_action( 'thim_core_for_developers_before_boxes', array( $this, 'add_box_settings' ), 20 );
        add_action( 'thim_core_for_developers_before_boxes', array( $this, 'add_box_theme_options' ), 30 );
        add_action( 'thim_core_for_developers_before_boxes', array( $this, 'add_box_php_info' ), 40 );
        add_action( 'thim_core_for_developers_before_boxes', array( $this, 'add_box_docs' ), 50 );

        add_filter( 'thim_core_get_link_download_plugin', array( $this, 'get_link_download_plugin' ) );
        add_filter( 'thim_core_registration_site_key', array( $this, 'get_site_key' ) );
        add_filter( 'thim_core_envato_api_get_theme_metadata', array( $this, 'envato_api_get_theme_metadata' ), 10, 2 );
        add_filter( 'thim_core_get_link_download_theme', array( $this, 'get_link_download_theme' ), 10, 2 );
    }

    /**
     * Filter get link download theme.
     *
     * @since 1.4.3
     *
     * @param $data
     * @param $theme
     *
     * @return string
     */
    public function get_link_download_theme( $data, $theme ) {
        if ( ! TP::is_debug() ) {//Please don't remove it.
            return $data;
        }

        $site_key = Thim_Product_Registration::get_site_key();
        $code     = thim_core_generate_code_by_site_key( $site_key );
        $url      = Thim_Admin_Config::get( 'host_downloads' ) . "/download-theme-package/?code=$code&theme=$theme&debug=yes";

        return $url;
    }

    /**
     * Filter get theme metadata from envato.
     *
     * @since 1.4.3
     *
     * @param $metadata
     */
    public function envato_api_get_theme_metadata( $metadata, $item_id ) {
        $url      = Thim_ADmin_config::get( 'host_downloads' ) . "/theme-update/?item-id=$item_id";
        $response = Thim_Remote_Helper::get( $url, array(), true );

        if ( ! empty( $response->data ) ) {
            $metadata['version'] = $response->data;
        }

        return $metadata;
    }

    /**
     * Filter get site key.
     *
     * @since 1.4.3
     *
     * @param $site_key
     *
     * @return mixed
     */
    public function get_site_key( $site_key ) {
        if ( defined( 'THIM_SITE_KEY' ) ) {
            return THIM_SITE_KEY;
        }

        return $site_key;
    }

    /**
     * Filter get link download plugin.
     *
     * @since 1.4.3
     *
     * @param $url
     *
     * @return string
     */
    public function get_link_download_plugin( $url ) {
        $url = add_query_arg( 'debug', 'yes', $url );

        return $url;
    }

    /**
     * Add box developers docs.
     *
     * @since 1.3.0
     */
    public function add_box_docs() {
        Thim_Template_Helper::template( 'developers/docs.php', array(), true );
    }

    /**
     * Add box export php information.
     *
     * @since 1.3.0
     */
    public function add_box_php_info() {
        Thim_Template_Helper::template( 'developers/php-info.php', array(), true );
    }

    /**
     * Add box export content.xml
     *
     * @since 1.3.0
     */
    public function add_box_content() {
        Thim_Template_Helper::template( 'developers/content.php', array(), true );
    }

    /**
     * Add box export settings.dat
     *
     * @since 1.3.0
     */
    public function add_box_settings() {
        Thim_Template_Helper::template( 'developers/settings.php', array(), true );
    }

    /**
     * Add box export theme_options.dat
     *
     * @since 1.3.0
     */
    public function add_box_theme_options() {
        Thim_Template_Helper::template( 'developers/theme-options.php', array(), true );
    }

    /**
     * Rename export file name (xml file)
     *
     * @since 1.1.1
     *
     * @param $filename string
     *
     * @return string
     */
    public function rename_export_file_name( $filename ) {
        if ( ! isset( $_GET['thim_export'] ) ) {
            return $filename;
        }

        return 'content.xml';
    }

    /**
     * Enqueue scripts.
     *
     * @since 0.8.9
     */
    public function enqueue_scripts() {
        if ( ! $this->is_myself() ) {
            return;
        }

        add_thickbox();
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
        $sub_pages['developer'] = array(
            'title' => __( 'For Developers', 'thim-core' )
        );

        return $sub_pages;
    }

    /**
     * Handle export.
     *
     * @param $package
     *
     * @since 0.5.0
     */
    public function handle_export( $package ) {
        switch ( $package ) {
            case 'content':
                Thim_Export_Service::content();
                break;

            case 'settings':
                Thim_Export_Service::settings();
                break;

            case 'theme_options':
                Thim_Export_Service::theme_options();
                break;

            case 'php_info':
                Thim_Export_Service::php_info();
                break;
        }
    }
}