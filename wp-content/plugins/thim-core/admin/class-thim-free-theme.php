<?php

/**
 * Class Thim_Free_Theme
 *
 * @since 1.3.0
 */
class Thim_Free_Theme extends Thim_Singleton {

    /**
     * Check is free theme.
     *
     * @since 1.3.0
     *
     * @return bool
     */
    public static function is_free() {
        return ! ! get_theme_support( 'thim-core-lite' );
    }

    /**
     * Get theme id.
     *
     * @since 1.7.1
     */
    public static function get_theme_id() {
        return apply_filters( 'thim_core_my_theme_id', false );
    }

	/**
	 *
	 */
    public static function get_themes() {

    }

    /**
     * Get check update themes.
     *
     * @since 1.1.0
     *
     * @return array
     */
    public static function get_update_themes() {
        $update = get_option( 'thim_core_check_update_my_themes', array() );

        return wp_parse_args( $update, array(
            'last_checked' => false,
            'themes'       => array(),
        ) );
    }

    /**
     * Check can update.
     *
     * @since 1.7.1
     *
     * @return bool
     */
    public static function can_update() {
        $theme_data      = Thim_Theme_Manager::get_metadata();
        $template        = $theme_data['template'];
        $current_version = $theme_data['version'];

        $update_themes = self::get_update_themes();
        $themes        = $update_themes['themes'];

        $data = isset( $themes[ $template ] ) ? $themes[ $template ] : false;
        if ( ! $data ) {
            return false;
        }

        /**
         * Double check update.
         */
        return version_compare( $data['version'], $current_version, '>' );
    }

    /**
     * Thim_Free_Theme constructor.
     *
     * @since 1.3.0
     */
    protected function __construct() {
        $this->hooks();
    }

    /**
     * Add hooks.
     *
     * @since 1.3.0
     */
    private function hooks() {
        add_action( 'thim_core_background_check_update_theme_lite', array( $this, 'background_check_update_theme' ) );
        add_filter( 'thim_core_get_link_download_theme', array( $this, 'get_link_download_theme' ), 10, 2 );
        add_filter( 'thim_core_can_update_theme', array( $this, 'can_update_theme' ) );
    }

    /**
     * Filter can update theme.
     *
     * @since 1.7.1
     *
     * @param $can_update bool
     *
     * @return bool
     */
    public function can_update_theme( $can_update ) {
        if ( ! self::is_free() ) {
            return $can_update;
        }

        return self::can_update();
    }

    /**
     * Get link download theme.
     *
     * @since 1.7.1
     *
     * @param $return false
     * @param $slug
     *
     * @return bool|string
     */
    public function get_link_download_theme( $return, $slug ) {
        if ( ! self::is_free() ) {
            return $return;
        }

        $update_themes = Thim_Free_Theme::get_update_themes();
        $themes        = $update_themes['themes'];

        $theme = isset( $themes[ $slug ] ) ? $themes[ $slug ] : false;

        if ( empty( $theme ) || empty( $theme['package'] ) || ! $theme['update'] ) {
            return $return;
        }

        return $theme['package'];
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
     * Check theme update.
     *
     * @since 1.7.1
     *
     * @param $force
     */
    public function check_theme_update( $force ) {
        $update_themes = self::get_update_themes();

        $last_checked = $update_themes['last_checked'];
        $now          = time();
        $timeout      = 12 * 3600;

        if ( ! $force && $last_checked && $now - $last_checked < $timeout ) {
            return;
        }

        $theme_data      = Thim_Theme_Manager::get_metadata();
        $slug            = $theme_data['template'];
        $current_version = $theme_data['version'];
        $id              = self::get_theme_id();

        $checker    = new Thim_Check_Update_My_Theme( $slug, $current_version, $id );
        $can_update = $checker->can_update();

        $update_themes['last_checked'] = $now;
        $data                          = $checker->get_theme_data();

        $themes   = (array) $update_themes['themes'];
        $template = $theme_data['template'];
        if ( $data ) {
            $themes[ $template ] = array(
                'update'       => $can_update,
                'theme'        => $template,
                'name'         => $data['name'],
                'description'  => $data['description'],
                'version'      => $data['version'],
                'author'       => $data['author'],
                'author_url'   => $data['author_profile'],
                'url'          => $data['homepage'],
                'package'      => $data['download_link'],
                'icon'         => $data['icon'],
                'rating_count' => 100,
                'rating'       => 5
            );
        } else {
            unset( $themes[ $template ] );
        }

        $update_themes['themes'] = $themes;

        update_option( 'thim_core_check_update_my_themes', $update_themes );
    }
}