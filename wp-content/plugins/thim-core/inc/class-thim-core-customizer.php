<?php

/**
 * Class Thim_Core_Customizer
 *
 * @package   Thim_Core
 * @since     0.1.0
 */
if ( ! class_exists( 'Thim_Core_Customizer' ) ) {

    class Thim_Core_Customizer extends Thim_Singleton {
        /**
         * @var string
         *
         * @since 0.1.0
         */
        public static $key_stylesheet_uri = 'thim_core_stylesheet';

        /**
         * @var string
         *
         * @since 1.0.1
         */
        public static $stylesheet_dir = null;

        /**
         * @var string
         *
         * @since 1.0.3
         */
        public static $stylesheet_uri = null;

        /**
         * Thim_Integrate_Kirki constructor.
         *
         * @since 0.1.0
         */
        protected function __construct() {
            $this->init();
            $this->init_hooks();
        }

        /**
         * Prepare variables.
         *
         * @since 1.0.1
         */
        private function prepare() {
            /**
             * Get uploads dir.
             */
            $wp_upload_dir = wp_upload_dir();
            $path_uploads  = $wp_upload_dir['basedir'];
            $uri_uploads   = $wp_upload_dir['baseurl'];

            $container = '/tc_stylesheets';

            self::$stylesheet_dir = $path_uploads . $container;
            $uri                  = $uri_uploads . $container;
            self::$stylesheet_uri = str_replace( 'http://', '//', $uri );
        }

        /**
         * Get directory contain stylesheet option.
         *
         * @since 1.1.1
         *
         * @return string
         */
        public function get_directory() {
            if ( self::$stylesheet_dir === null ) {
                $this->prepare();
            }

            return self::$stylesheet_dir;
        }

        /**
         * Get url stylesheet option.
         *
         * @since 1.1.1
         *
         * @return string
         */
        private function get_url() {
            if ( self::$stylesheet_uri === null ) {
                $this->prepare();
            }

            return self::$stylesheet_uri;
        }

        /**
         * Init class.
         *
         * @since 0.1.0
         */
        private function init() {
            $this->include_kirki();
            Thim_Kirki_Extended::instance();
        }

        /**
         * Init hooks.
         *
         * @since 0.1.0
         */
        private function init_hooks() {
            add_filter( 'kirki/config', array( $this, 'config' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_stylesheet_uri' ), 2000 );

            add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'customize_save_after', array( $this, 'after_save_customize' ) );
            add_action( 'customize_save', array( $this, 'before_save_customize' ) );

            add_filter( 'customize_save_response', array( $this, 'customize_save_response' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_preview' ), 100 );

            add_action( 'wp_loaded', array( $this, 'customizer_register' ) );

            add_action( 'after_setup_theme', array( $this, 'add_section_documentation' ) );
            add_action( 'after_setup_theme', array( $this, 'add_section_notice_php_version' ) );
        }

        /**
         * Add section notice PHP version < 5.4.
         *
         * @since 1.2.0
         */
        public function add_section_notice_php_version() {
            $php_version = phpversion();
            if ( version_compare( $php_version, '5.4', '>=' ) ) {
                return;
            }

            $guide = sprintf( __( 'Your server is running PHP versions %s. It is recommended to be on at least PHP 5.6 and preferably PHP 7.x to process all the theme\'s functionalities. See %s', 'thim-core' ),
                $php_version,
                '<a href="https://goo.gl/WRBYv3" target="_blank">' . __( 'How to update your PHP version.', 'thim-core' ) . '</a>'
            );

            $this->add_section(
                array(
                    'id'       => 'tc-theme-notice-php-version',
                    'title'    => esc_html__( 'Upgrade PHP version', 'thim-core' ),
                    'priority' => 0,
                    'icon'     => 'dashicons-warning',
                )
            );

            $this->add_field(
                array(
                    'id'       => 'tc-theme-notice-php-version',
                    'type'     => 'custom',
                    'default'  => $guide,
                    'priority' => 1,
                    'section'  => 'tc-theme-notice-php-version',
                )
            );
        }

        /**
         * Add section documentation.
         *
         * @since 1.0.0
         */
        public function add_section_documentation() {
            $documentation = apply_filters( 'thim_core_customize_section_documentation', false );

            if ( ! $documentation ) {
                return;
            }

            $this->add_section(
                array(
                    'id'       => 'tc-theme-documentation',
                    'title'    => esc_html__( 'Support and Documentation', 'thim-core' ),
                    'priority' => 1,
                    'icon'     => 'dashicons-book',
                )
            );

            $this->add_field(
                array(
                    'id'       => 'tc-theme-documentation',
                    'type'     => 'custom',
                    'default'  => $documentation,
                    'priority' => 1,
                    'section'  => 'tc-theme-documentation',
                )
            );
        }

        /**
         * Include Kirki.
         *
         * @since 0.1.0
         */
        private function include_kirki() {
            if ( class_exists( 'Kirki' ) ) {
                add_action( 'admin_init', function () {
                    $url_deactivate = Thim_Plugins_Manager::get_url_plugin_actions( array(
                        'plugin-action' => 'deactivate',
                        'slug'          => 'kirki',
                    ) );

                    Thim_Notification::add_notification( array(
                        'id'          => 'conflict_kirki',
                        'type'        => 'warning',
                        'content'     => sprintf( __( 'Kirki Toolkit plugin is already included in Thim Core so you need to <a href="%s">deactivate the Kirki Toolkit plugin</a>.', 'thim-core' ), $url_deactivate ),
                        'dismissible' => false,
                        'global'      => true,
                    ) );
                } );

                return;
            }

            include_once THIM_CORE_INC_PATH . '/includes/kirki-v3/kirki.php';
        }

        /**
         * Register hook register customizer.
         *
         * @since 0.1.0
         */
        public function customizer_register() {
            do_action( 'thim_customizer_register' );
        }

        /**
         * Filter config kirki.
         *
         * @param $config
         *
         * @return array
         * @since 0.1.0
         */
        public function config( $config ) {
            $config = wp_parse_args( array(
                'logo_image'  => THIM_CORE_ASSETS_URI . '/images/logo.png',
                'description' => esc_html__( 'Designed by ThimPress.', 'thim-core' ),
                'url_path'    => THIM_CORE_INC_URI . '/includes/kirki-v3/',
            ), $config );

            return $config;
        }


        /**
         * Add panel.
         *
         * @param array $panel
         *
         * @since 0.1.0
         */
        public function add_panel( array $panel ) {
            Kirki::add_panel( $panel['id'], $panel );
        }

        /**
         * Add section.
         *
         * @param array $section
         *
         * @since 0.1.0
         */
        public function add_section( array $section ) {
            Kirki::add_section( $section['id'], $section );
        }

        /**
         * Add field.
         *
         * @param array $field
         *
         * @since 0.1.0
         */
        public function add_field( array $field ) {
            if ( ! array_key_exists( 'settings', $field ) ) {
                $field['settings'] = $field['id'];
            }

            if ( $field['type'] == 'code' ) {
                $field['label'] = isset( $field['label'] ) ? $field['label'] : '';
            }

            if ( isset( $field['alpha'] ) ) {
                unset( $field['alpha'] );

                $choices          = isset( $field['$choices'] ) ? $field['$choices'] : array();
                $choices['alpha'] = true;

                $field['choices'] = $choices;
            }

            //Fix new line js_var
            if ( ! empty( $field['js_vars'] ) && is_array( $field['js_vars'] ) ) {
                $js_vars     = $field['js_vars'];
                $new_js_vars = array();

                foreach ( $js_vars as $js_var ) {
                    $element           = isset( $js_var['element'] ) ? $js_var['element'] : '';
                    $str               = preg_replace( '#\s+#', ' ', trim( $element ) );
                    $js_var['element'] = $str;

                    $new_js_vars[] = $js_var;
                }

                $field['js_vars'] = $new_js_vars;
            }

            Kirki::add_field( 'global', $field );
        }

        /**
         * Add group fields.
         *
         * @param array $group
         *
         * @since 0.1.0
         */
        public function add_group( array $group ) {
            $section  = $group['section'];
            $groups   = $group['groups'];
            $priority = isset( $group['priority'] ) ? $group['priority'] : 10;

            foreach ( $groups as $group ) {
                $fields   = $group['fields'];
                $group_id = $group['id'];

                /**
                 * Header
                 */
                $filed_title = array(
                    'id'       => $group_id,
                    'type'     => 'tc_group',
                    'section'  => $section,
                    'label'    => $group['label'],
                    'priority' => $priority,
                    'fields'   => $fields,
                );
                //$this->add_field( $filed_title );

                /**
                 * Body
                 */
                foreach ( $fields as $field ) {
                    $update_field             = $field;
                    $update_field['section']  = $section;
                    $update_field['priority'] = $priority;
                    $update_field['hide']     = true;

                    $this->add_field( $update_field );
                }
            }
        }

        /**
         * Get SASS variables from customizer.
         *
         * @return array
         * @since 0.1.0
         */
        public static function get_sass_variables() {
            $variables = array();
            $prefix    = TP::$prefix;

            $fields = Kirki::$fields;

            /**
             * Fixes get old values.
             */
            global $thim_customizer_options;
            $thim_customizer_options = get_theme_mods();

            foreach ( $fields as $field_id => $field ) {
                $type          = $field['type'];
                $excluded_type = array(
                    'repeater',
                    'kirki-generic',
                    'kirki-sortable',
                    'kirki-code',
                    'kirki-editor',
                    'kirki-dropdown-pages',
                    'kirki-custom',
                );

                if ( in_array( $type, $excluded_type ) ) {//Excluded
                    continue;
                }

                $default_value = $field['default'];
                $values        = self::get_option( $field_id, $default_value );

                /**
                 * Add double quote if the field is text.
                 */
                $string_type = array(
                    'kirki-image',
                    'image',
                    'upload',
                    'cropped_image',
                    'kirki-radio-image',
                );
                if ( in_array( $type, $string_type ) ) {
                    $values = str_replace( 'https://', '//', $values );
                    $values = str_replace( 'http://', '//', $values );

                    $values = '"' . $values . '"';
                }

                if ( is_array( $values ) ) {
                    foreach ( $values as $key => $val ) {
                        if ( 'subsets' === $key ) {//Excluded subsets
                            continue;
                        }

                        if ( 'variant' === $key ) {
                            if ( 'regular' === $val ) {
                                $val = '400normal';
                            }

                            if ( 'italic' === $val ) {
                                $val = '400italic';
                            }

                            $font_weight = intval( $val );

                            if ( 0 === $font_weight ) {
                                $font_weight = 400;
                            }

                            $font_style = str_replace( $font_weight, '', $val );

                            if ( empty( $font_style ) ) {
                                $font_style = 'normal';
                            }

                            $key = $field_id;
                            $key = $prefix . $key;

                            $variables[ $key . '_font_weight' ] = $font_weight;
                            $variables[ $key . '_font_style' ]  = $font_style;
                            continue;
                        }

                        $key = $field_id . '_' . $key;
                        $key = $prefix . $key;
                        $key = str_replace( '-', '_', $key );

                        $variables[ $key ] = $val;
                    }
                } else {
                    if ( empty( $values ) ) {
                        $values = '""';
                    }
                    $variables[ $prefix . $field_id ] = $values;
                }
            }

            $variables = apply_filters( 'tc_variables_compile_scss_theme', $variables );

            return $variables;
        }

        /**
         * Get options customizer.
         *
         * @return array
         * @since 0.1.0
         */
        public static function get_options() {
            global $thim_customizer_options;

            if ( empty( $thim_customizer_options ) ) {
                $thim_customizer_options = get_theme_mods();
            }

            return (array) $thim_customizer_options;
        }

        /**
         * Get option customizer by key.
         *
         * @param string $key
         * @param        $default
         *
         * @return mixed|null
         * @since 0.1.0
         */
        public static function get_option( $key, $default = false ) {
            $thim_customizer_options = self::get_options();

            if ( ! array_key_exists( $key, $thim_customizer_options ) ) {
                return $default;
            }

            return $thim_customizer_options[ $key ];
        }

        /**
         * Enqueue scripts in Customize.
         *
         * @since 0.1.0
         */
        public function enqueue_scripts() {
            wp_enqueue_style( 'thim_core_customizer_panel', THIM_CORE_ASSETS_URI . '/css/customizer/panel.css', array(), THIM_CORE_VERSION );
            wp_enqueue_script( 'thim_core_customizer', THIM_CORE_ASSETS_URI . '/js/customizer.js', array( 'jquery' ), THIM_CORE_VERSION );
        }

        /**
         * Enqueue scripts for preview.
         *
         * @since 0.1.0
         */
        public function enqueue_scripts_preview() {
            global $wp_customize;
            if ( ! $wp_customize ) {
                return;
            }

            wp_enqueue_style( 'thim_core_customizer_preview', THIM_CORE_ASSETS_URI . '/css/customizer/preview.css', array(), THIM_CORE_VERSION );
        }

        /**
         * Hook before save customize.
         *
         * @since 0.1.0
         */
        public function before_save_customize() {
            $this->save_default_values();
        }

        /**
         * Save default values to theme mods.
         *
         * @since 0.1.0
         */
        private function save_default_values() {
            $fields = Kirki::$fields;

            foreach ( $fields as $field_id => $field ) {
                $option  = self::get_option( $field_id, null );
                $default = $field['default'];

                if ( null === $option ) {
                    set_theme_mod( $field_id, $default );
                }
            }
        }

        /**
         * Handle after saving customize.
         *
         * @since 0.1.0
         */
        public function after_save_customize() {
            $file_sass_options = apply_filters( 'thim_core_config_sass', array() );

            if ( empty( $file_sass_options ) ) {
                return;
            }

            wp_raise_memory_limit( 'thim_core_admin' );
            $file_sass_options = wp_parse_args( $file_sass_options, array(
                'dir'  => '',
                'name' => 'options.scss',
            ) );

            $variables_sass = self::get_sass_variables();

            try {
                $compiler = Thim_Compile_SASS::instance();

                $css = $compiler->compile_scss( $file_sass_options, $variables_sass );

                $stylesheet_uri = $this->save_file_theme_options( $css );
                if ( TP::is_debug() ) {
                    $stylesheet_uri = $this->save_file_dev_theme_options( $css );
                }

                $this->update_stylesheet_uri( $stylesheet_uri );

                if ( is_wp_error( $css ) ) {
                    Thim_Core_Customizer::message_customize_error( $css->get_error_message() );
                }
            } catch ( Exception $e ) {
                Thim_Core_Customizer::message_customize_error( $e->getMessage() );
            }
        }

        /**
         * Filter response after saving customizer.
         *
         * @param $response
         *
         * @return object
         * @since 0.1.0
         */
        public function customize_save_response( $response ) {
            $message = esc_html__( 'Save customizer successfully!', 'thim-core' );
            $message = apply_filters( 'thim_core_message_response_save_customize', $message );

            $r        = new stdClass();
            $r->error = apply_filters( 'thim_core_error_save_customize', false );
            $r->msg   = $message;

            if ( TP::is_debug() ) {
                $r->info               = array(
                    'mem' => @memory_get_usage( true ) / 1048576,
                    'php' => @phpversion(),
                );
                $r->stylesheet_options = self::get_stylesheet_uri();
                $r->theme_mods         = get_theme_mods();
            }

            //Add custom information
            $response['thim'] = $r;

            return $response;
        }

        /**
         * Add filter notify error in response when save customize.
         *
         * @param $error
         *
         * @return true
         * @since 0.1.0
         */
        public static function notify_error_customize( $error ) {
            return add_filter( 'thim_core_error_save_customize', function () use ( $error ) {
                return $error;
            } );
        }

        /**
         * Add filter message in response when save customize.
         *
         * @param $message
         *
         * @return true
         * @since 0.1.0
         */
        public static function message_customize( $message ) {
            return add_filter( 'thim_core_message_response_save_customize', function () use ( $message ) {
                return $message;
            } );
        }

        /**
         * Add filter message error in response when save customize.
         *
         * @param $message
         *
         * @return bool
         * @since 0.1.0
         */
        public static function message_customize_error( $message ) {
            return self::notify_error_customize( true ) && self::message_customize( $message );
        }

        /**
         * Get uri stylesheet.
         *
         * @return bool|mixed
         * @since 0.1.0
         */
        public static function get_stylesheet_uri() {
            $option = self::get_option( self::$key_stylesheet_uri );

            if ( empty( $option ) ) {
                return false;
            }

            return $option;
        }

        /**
         * Update uri stylesheet.
         *
         * @param string $uri
         *
         * @since 0.1.0
         */
        public function update_stylesheet_uri( $uri ) {
            set_theme_mod( self::$key_stylesheet_uri, $uri );
        }

        /**
         * Get file name custom css theme.
         *
         * @since 1.0.1
         *
         * @return string
         */
        private function get_file_name_custom_css_theme() {
            $key_theme = Thim_Theme_Manager::get_current_theme();

            $rand = time();
            $name = "$key_theme.$rand.css";

            return apply_filters( 'thim_core_file_name_custom_css_theme', $name );
        }

        /**
         * Get file name custom css theme (For developer).
         *
         * @since 1.1.1
         *
         * @return string
         */
        public static function get_file_dev_name_custom_css_theme() {
            $key_theme = Thim_Theme_Manager::get_current_theme();

            $name = "$key_theme.demo.css";

            return $name;
        }

        /**
         * Save file stylesheet.
         *
         * @param $content
         *
         * @return string
         * @since 0.1.0
         */
        private function save_file_theme_options( $content ) {
            $file_name = $this->get_file_name_custom_css_theme();

            /**
             * Remove old file.
             */
            $old_file = get_option( TP::$prefix . 'custom_css_name' );
            if ( ! empty( $old_file ) ) {
                Thim_File_Helper::remove_file( trailingslashit( $this->get_directory() ) . $old_file );
            }

            /**
             * Put file.
             */
            Thim_File_Helper::put_file( $this->get_directory(), $file_name, $content );
            /**
             * Then save file name.
             */
            update_option( TP::$prefix . 'custom_css_name', $file_name );

            /**
             * Return uri file.
             */
            return trailingslashit( $this->get_url() ) . $file_name;
        }

        /**
         * Save file theme options (For developer).
         *
         * @since 1.1.1
         *
         * @param $content
         *
         * @return string
         */
        private function save_file_dev_theme_options( $content ) {
            $file_name = self::get_file_dev_name_custom_css_theme();
            Thim_File_Helper::put_file( $this->get_directory(), $file_name, $content );

            /**
             * Return uri file.
             */
            return trailingslashit( $this->get_url() ) . $file_name;
        }

        /**
         * Enqueue stylesheet (theme options) uri.
         *
         * @since 0.1.0
         */
        public function enqueue_stylesheet_uri() {
            $stylesheet = self::get_stylesheet_uri();

            if ( ! $stylesheet ) {
                $stylesheet = apply_filters( 'thim_style_default_uri', trailingslashit( get_stylesheet_directory_uri() ) . 'inc/data/default.css' );
            }

            if ( ! $stylesheet ) {
                return;
            }

            $stylesheet_depends = apply_filters( 'thim_core_stylesheet_options_depends', array( 'thim-style' ) );
            $version            = apply_filters( 'thim_core_stylesheet_options_version', false );

            wp_enqueue_style( 'thim-style-options', $stylesheet, $stylesheet_depends, $version );
        }
    }
}
