<?php

/**
 * Class Thim_Importer_AJAX
 *
 * @package   Thim_Core_Admin
 * @since     0.3.1
 */
class Thim_Importer_AJAX {
    /**
     * @var string
     *
     * @since 0.6.0
     */
    private static $key_option_current_demo = 'thim_importer_current_demo';

    /**
     * Handle ajax uninstall.
     *
     * @since 0.7.0
     *
     * @return bool
     */
    public function handle_ajax_uninstall() {
        Thim_Importer_Service::reset_data_demo();

        return $this->_send_response_success( null );
    }

    /**
     * Handle post data.
     *
     * @since 0.3.1
     *
     * @return bool
     */
    public function handle_ajax() {
        try {
            $packages = ! empty( $_POST['packages'] ) ? $_POST['packages'] : false;

            if ( $packages ) {
                $demo = ! empty( $_POST['demo'] ) ? $_POST['demo'] : false;
                if ( ! $demo ) {
                    throw Thim_Error::create( __( 'Something went wrong!', 'thim-core' ), 4 );
                }

                $demos = Thim_Importer::get_demo_data();
                if ( ! isset( $demos[ $demo ] ) ) {
                    throw Thim_Error::create( __( 'Demo not found!', 'thim-core' ), 4 );
                }

                $this->check_retry_import();

                return $this->_initializeImporter( $demos[ $demo ], $packages );
            }

            $current_step = $this->_get_key_current_step();

            return $this->_step_by_step( $current_step );
        } catch ( Thim_Error $exception ) {
            return $this->_send_response_error( $exception->getMessage(), $exception->getErrorCode(), $exception->getHowTo() );
        } catch ( Exception $exception ) {
            return $this->_send_response_error( $exception->getMessage(), $exception->getCode() );
        }
    }

    /**
     * Check retry import.
     *
     * @since 1.2.0
     */
    private function check_retry_import() {
        $retry = isset( $_POST['retry'] ) ? $_POST['retry'] : false;

        if ( ! $retry || $retry === 'false' ) {
            return;
        }

        $current_posts_pp       = get_option( 'thim_importer_posts_pp', 100 );
        $current_attachments_pp = get_option( 'thim_importer_attachments_pp', 10 );

        update_option( 'thim_importer_posts_pp', max( intval( $current_posts_pp / 2 ), 10 ) );
        update_option( 'thim_importer_attachments_pp', max( intval( $current_attachments_pp / 2 ), 1 ) );
    }

    /**
     * Initialize import.
     *
     * @since 0.3.1
     *
     * @param array $demo
     * @param array $packages
     *
     * @return bool
     */
    private function _initializeImporter( $demo, $packages = array() ) {
        do_action( 'thim_core_importer_start_import_demo', $demo );

        $packages = (array) $packages;
        $packages = apply_filters( 'thim_core_importer_prepare_packages', $packages );
        $this->_save_current_demo_data( $demo, $packages );
        update_option( 'thim_importer_prepare_wp_import', false );

        $this->_send_response_success( array(
            'next' => $this->_get_key_current_step()
        ) );

        return true;
    }

    /**
     * Prepare demo content.
     *
     * @since 0.5.0
     */
    private function _prepare_demo_content() {
        $dir = $this->_get_dir_current_demo();
        $xml = $dir . '/content.xml';

        Thim_Importer_Service::analyze_content( $xml );
    }

    /**
     * Store temporarily demo data.
     *
     * @since 0.3.1
     *
     * @param array $demo
     * @param array $packages
     *
     * @return bool
     */
    private function _save_current_demo_data( $demo, $packages ) {
        $theme_slug = get_option( 'stylesheet' );

        return update_option( self::$key_option_current_demo, array(
            'theme'                  => $theme_slug,
            'demo'                   => $demo['key'],
            'revsliders'             => isset( $demo['revsliders'] ) ? $demo['revsliders'] : array(),
            'packages'               => $packages,
            'dir'                    => $demo['dir'],
            'plugins_required'       => isset( $demo['plugins_required'] ) ? $demo['plugins_required'] : false,
            'plugins_required_count' => count( $demo['plugins_required'] ),
            'current_step'           => 0,
        ) );
    }

    /**
     * Update current demo data
     *
     * @since 0.4.0
     *
     * @param $args
     *
     * @return bool
     */
    public static function update_current_demo_data( $args ) {
        return update_option( self::$key_option_current_demo, $args );
    }

    /**
     * Get option current demo.
     *
     * @since 0.3.1
     *
     * @return array
     */
    public static function get_current_demo_data() {
        return get_option( self::$key_option_current_demo );
    }

    /**
     * Get dir current demo.
     *
     * @since 0.3.1
     *
     * @return string
     */
    private function _get_dir_current_demo() {
        $current_demo = self::get_current_demo_data();

        $base_dir = apply_filters( 'thim_core_importer_base_path_demo_data', false, $current_demo );
        if ( ! $base_dir ) {
            return $current_demo['dir'];
        }

        return apply_filters( 'thim_core_importer_directory_current_demo', $base_dir . $current_demo['demo'], $current_demo['demo'] );
    }

    /**
     * Get current demo data directory.
     *
     * @since 1.7.8
     *
     * @return string
     */
    public function get_current_demo_data_directory() {
        return $this->_get_dir_current_demo();
    }

    /**
     * Get selected packages.
     *
     * @since 0.3.1
     *
     * @return array
     */
    private function _get_selected_packages() {
        $current_demo = self::get_current_demo_data();

        return ! empty( $current_demo['packages'] ) ? $current_demo['packages'] : array();
    }

    /**
     * Get index current step.
     *
     * @since 0.3.1
     *
     * @return int
     */
    private function _get_index_current_step() {
        $current_demo = self::get_current_demo_data();

        return ! empty( $current_demo['current_step'] ) ? intval( $current_demo['current_step'] ) : 0;
    }

    /**
     * Increase index current step
     *
     * @since 0.3.1
     */
    private function _increase_index_current_step() {
        $current_demo                 = self::get_current_demo_data();
        $current_demo['current_step'] = $this->_get_index_current_step() + 1;
        self::update_current_demo_data( $current_demo );
    }

    /**
     * Get key current step.
     *
     * @since 0.3.1
     *
     * @return bool|mixed
     */
    private function _get_key_current_step() {
        $index    = $this->_get_index_current_step();
        $packages = $this->_get_selected_packages();

        if ( $index < count( $packages ) ) {
            return $packages[ $index ];
        }

        return false;
    }

    /**
     * Next step and get key step.
     *
     * @since 0.3.1
     *
     * @return bool|mixed
     */
    private function _get_key_next_step() {
        $this->_increase_index_current_step();
        $index    = $this->_get_index_current_step();
        $packages = $this->_get_selected_packages();

        if ( $index < count( $packages ) ) {
            return $packages[ $index ];
        }

        $this->_finish();

        return false;
    }

    /**
     * Finish process import.
     *
     * @since 0.5.0
     */
    private function _finish() {
        /**
         * Delete post hello world.
         */
        wp_trash_post( 1 );

        $this->_update_settings();

        /**
         * Remap menu locations.
         */
        $thim_wp_import = Thim_WP_Import_Service::instance( false );
        $thim_wp_import->set_menu_locations();

        /**
         * Update option demo installed.
         */
        $demo_data = self::get_current_demo_data();
        $demo_key  = isset( $demo_data['demo'] ) ? $demo_data['demo'] : false;
        if ( ! $demo_data ) {
            return;
        }

        Thim_Importer::update_key_demo_installed( $demo_key );
    }

    /**
     * Update site settings.
     *
     * @since 0.5.0
     */
    private function _update_settings() {
        $dir           = $this->_get_dir_current_demo();
        $settings_file = $dir . '/settings.dat';

        Thim_Importer_Service::settings( $settings_file );
    }

    /**
     * Call step import.
     *
     * @since 0.3.1
     *
     * @param $step
     *
     * @return mixed
     * @throws Exception
     */
    private function _step_by_step( $step ) {
        do_action( 'thim_core_importer_start_step', $step );

        $callback_function = 'step_' . $step;

        $callable = apply_filters( "thim_core_importer_step_$step", array( $this, $callback_function ) );

        if ( is_callable( $callable ) ) {
            return call_user_func( $callable );
        }

        throw Thim_Error::create( __( 'Something went wrong!', 'thim-core' ), 3 );
    }

    /**
     * Step install and activate plugins.
     *
     * @since 0.3.0
     *
     * @throws Exception
     */
    public function step_plugins() {
        $current_demo     = self::get_current_demo_data();
        $plugins_required = $current_demo['plugins_required'];

        if ( empty( $plugins_required ) ) {
            return $this->next_step();
        }

        $plugin_required = isset( $plugins_required[0] ) ? $plugins_required[0] : false;
        if ( ! $plugin_required ) {
            return $this->next_step();
        }

        $extend_data               = array();
        $count_all_plugins         = $current_demo['plugins_required_count'];
        $remain_plugins            = count( $plugins_required );
        $percentage                = intval( 100 - $remain_plugins / $count_all_plugins * 100 );
        $extend_data['percentage'] = min( 100, $percentage );

        $plugin_slug = isset( $plugin_required['slug'] ) ? $plugin_required['slug'] : false;
        $plugin      = Thim_Plugins_Manager::get_plugin_by_slug( $plugin_slug );

        if ( $plugin ) {
            $status = $plugin->get_status();
            if ( $status === 'not_installed' ) {
                $install = $plugin->install();

                if ( $install ) {
                    $extend_data['installed'] = $plugin->get_slug();

                    return $this->_try_step( $extend_data );
                } else {
                    $messages = $plugin->get_messages();
                    $string   = implode( '. ', $messages );
                    thim_add_log( sprintf( __( 'Installing %s plugin failed. %s', 'thim-core' ), $plugin->get_name(), $string ), 'plugin_installation' );

                    $slug = $plugin->get_slug();
                    if ( strpos( $slug, '-demo-data' ) !== false ) {
                        //Ignore install failed.
                        throw Thim_Error::create( sprintf( __( 'Installing %s plugin failed. %s', 'thim-core' ), $plugin->get_name(), $string ), 9, __( 'Please try again or install manually the plugin on tab <strong>Plugins</strong>.', 'thim-core' ) );
                    }
                }
            } else if ( $status == 'inactive' ) {
                $result = $plugin->activate( true );
                if ( ! $result ) {
                    $messages = $plugin->get_messages();
                    $string   = implode( '. ', $messages );
                    throw Thim_Error::create( sprintf( __( 'Activating %s plugin failed. %s', 'thim-core' ), $plugin->get_name(), $string ), 9, __( 'Please try again or install manually the plugin on tab <strong>Plugins</strong>.', 'thim-core' ) );
                }

                $extend_data = array( 'activated' => $plugin->get_slug() );
            }
        }

        array_splice( $plugins_required, 0, 1 );
        $current_demo['plugins_required'] = $plugins_required;
        self::update_current_demo_data( $current_demo );

        $remain_plugins            = count( $plugins_required );
        $percentage                = intval( 100 - $remain_plugins / $count_all_plugins * 100 );
        $extend_data['percentage'] = min( 100, $percentage );

        return $this->_try_step( $extend_data );
    }

    /**
     * Step import main content.
     *
     * @since 0.3.0
     */
    public function step_main_content() {
        $prepare_wp_import = get_option( 'thim_importer_prepare_wp_import', false );
        if ( ! $prepare_wp_import ) {
            $this->_prepare_demo_content();
            update_option( 'thim_importer_prepare_wp_import', true );

            return $this->_try_step();
        }
        $packages         = $this->_get_selected_packages();
        $fetch_attachment = array_search( 'media', $packages ) !== false;
        $thim_wp_import   = Thim_WP_Import_Service::instance();
        $response         = $thim_wp_import->import_posts( $fetch_attachment );
        if ( $response['has_posts'] && ( $response['has_posts'] !== 'attachment' ) ) {
            return $this->_try_step( $response );
        }

        /**
         * Fix issue while importing missing some menu items
         */
        $thim_wp_import->backfill_parents();
        $thim_wp_import->backfill_attachment_urls();
        $thim_wp_import->remap_featured_images();

        return $this->next_step( $response );
    }

    /**
     * Step import media file.
     *
     * @since 0.3.1
     */
    public function step_media() {
        $thim_wp_import = Thim_WP_Import_Service::instance();
        $response       = $thim_wp_import->import_posts( true );
        if ( $response['has_posts'] ) {
            return $this->_try_step( $response );
        }

        return $this->next_step( $response );
    }

    /**
     * Step import widgets.
     *
     * @since 0.3.1
     */
    public function step_widgets() {
        $dir          = $this->_get_dir_current_demo();
        $widget_file  = $dir . '/widget/widget_data.json';
        $widget_logic = $dir . '/widget/widget_logic_options.txt';

        Thim_Importer_Service::widget( $widget_file, $widget_logic );

        return $this->next_step();
    }

    /**
     * Step import Slider Revolution
     *
     * @since 0.4.0
     * @return bool
     */
    public function step_revslider() {
        $demo_data  = self::get_current_demo_data();
        $revsliders = isset( $demo_data['revsliders'] ) ? $demo_data['revsliders'] : array();

        Thim_Importer_Service::revslider( $revsliders );

        return $this->next_step();
    }

    /**
     * Step import theme options.
     *
     * @since 0.3.1
     */
    public function step_theme_options() {
        $dir          = $this->_get_dir_current_demo();
        $setting_file = $dir . '/theme_options.dat';
        Thim_Importer_Service::theme_options( $setting_file );

        return $this->next_step();
    }

    /**
     * Next step and return response success.
     *
     * @since 0.3.1
     *
     * @param mixed $ext
     *
     * @return bool
     */
    public function next_step( $ext = null ) {
        $done = $this->_get_key_current_step();
        $next = $this->_get_key_next_step();
        do_action( 'thim_core_importer_next_step', $done, $next, $ext );

        return $this->_send_response_success( array(
            'done' => $done,
            'next' => $next,
            'ext'  => $ext,
        ) );
    }

    /**
     * Next step and return response success.
     *
     * @since 0.3.1
     *
     * @param mixed $ext
     *
     * @return bool
     */
    private function _try_step( $ext = null ) {
        $current = $this->_get_key_current_step();
        do_action( 'thim_core_importer_try_step', $current, $ext );

        return $this->_send_response_success( array(
            'next' => $current,
            'ext'  => $ext,
        ) );
    }

    /**
     * Send response error.
     *
     * @since 0.3.1
     *
     * @param string $msg
     * @param string $code
     * @param string $how_to
     * @param bool $safe
     *
     * @return bool
     */
    private function _send_response_error( $msg, $code, $how_to = '', $safe = true ) {
        $data = array(
            'title'  => $msg,
            'how_to' => $how_to,
            'code'   => $code,
        );

        if ( ! $safe ) {
            wp_send_json_error( $data );
        }

        $response = array(
            'success' => false,
        );

        if ( isset( $data ) ) {
            if ( is_wp_error( $data ) ) {
                $result = array();
                foreach ( $data->errors as $code => $messages ) {
                    foreach ( $messages as $message ) {
                        $result[] = array(
                            'code'    => $code,
                            'message' => $message,
                        );
                    }
                }

                $response['data'] = $result;
            } else {
                $response['data'] = $data;
            }
        }

        $this->_send_response( $response );

        return true;
    }

    /**
     * Send response success.
     *
     * @since 0.3.1
     *
     * @param $data
     * @param $safe
     *
     * @return bool
     */
    private function _send_response_success( $data, $safe = true ) {
        if ( ! $safe ) {
            wp_send_json_success( $data );
        }
        $response = array(
            'success' => true,
        );

        if ( isset( $data ) ) {
            $response['data'] = $data;
        }

        $this->_send_response( $response );

        return true;
    }

    /**
     * Send response.
     *
     * @since 0.4.0
     *
     * @param $data
     */
    private function _send_response( $data ) {
        echo '<!-- THIM_IMPORT_START -->';
        header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        echo wp_json_encode( $data );
        echo '<!-- THIM_IMPORT_END -->';
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            wp_die();
        } else {
            die;
        }
    }
}
