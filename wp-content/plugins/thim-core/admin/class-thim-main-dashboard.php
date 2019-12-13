<?php

/**
 * Class Thim_System_Status.
 *
 * @since 0.8.5
 */
class Thim_Main_Dashboard extends Thim_Admin_Sub_Page {
    /**
     * @var string
     *
     * @since 0.8.5
     */
    public $key_page = 'dashboard';

    /**
     * @var array
     *
     * @since 0.8.9
     */
    public static $boxes = null;

    /**
     * Get all boxes.
     *
     * @since 0.8.9
     */
    public static function all_boxes() {
        if ( self::$boxes === null ) {
            $theme_data = Thim_Theme_Manager::get_metadata();

            self::$boxes = array(
                'appearance'    => array(
                    'id'    => 'appearance',
                    'title' => __( 'Appearance', 'thim-core' ),
                ),
                'documentation' => array(
                    'id'    => 'documentation',
                    'title' => __( 'Help & Support', 'thim-core' ),
                ),
            );

            if ( ! Thim_Free_Theme::is_free() ) {
                $envato_id = ! empty( $theme_data['envato_item_id'] ) ? $theme_data['envato_item_id'] : false;
                if ( $envato_id ) {
                    self::$boxes['updates'] = array(
                        'id'    => 'updates',
                        'title' => __( 'Updates', 'thim-core' ),
                    );
                }
            } else {
                self::$boxes['updates-lite'] = array(
                    'id'    => 'updates-lite',
                    'title' => __( 'Updates', 'thim-core' ),
                );
            }

            if ( ! Thim_Subscribe::is_subscribed() ) {
                self::$boxes['subscribe'] = array(
                    'id'    => 'subscribe',
                    'title' => __( 'Stay In The Loop', 'thim-core' ),
                );
            }

            $changelog_file = $theme_data['changelog_file'];
            if ( $changelog_file ) {
                self::$boxes['changelog'] = array(
                    'id'    => 'changelog',
                    'title' => __( 'Changelog', 'thim-core' ),
                );
            }
        }

        return apply_filters( 'thim_dashboard_all_boxes', self::$boxes );
    }

    /**
     * Thim_System_Status constructor.
     *
     * @since 0.8.5
     */
    protected function __construct() {
        parent::__construct();

        $this->init_hooks();
    }

    /**
     * Initialize hooks.
     *
     * @since 0.8.5
     */
    private function init_hooks() {
        add_action( 'wp_ajax_thim_dashboard_order_boxes', array( $this, 'handle_order_boxes_dashboard' ) );
        add_filter( 'thim_dashboard_sub_pages', array( $this, 'add_sub_page' ) );
        add_action( 'thim_dashboard_registration_box', array( $this, 'render_registration_box' ), 10 );
        add_action( 'thim_dashboard_boxes_left', array( $this, 'render_boxes_left' ) );
        add_action( 'thim_dashboard_boxes_right', array( $this, 'render_boxes_right' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Render registration box.
     *
     * @since 1.0.1
     */
    public function render_registration_box() {
        if ( Thim_Product_Registration::is_active() ) {
            return;
        }

        if ( Thim_Free_Theme::is_free() ) {
            return;
        }

        $envato_id = Thim_Theme_Manager::get_data( 'envato_item_id', false );
        if ( ! $envato_id ) {
            return;
        }

        Thim_Dashboard::get_template( 'partials/registration.php' );
    }

    /**
     * Handle ajax order.
     *
     * @since 0.8.9
     */
    public function handle_order_boxes_dashboard() {
        $post_data = wp_parse_args( $_POST, array(
            'left'  => array(),
            'right' => array(),
        ) );

        update_option( 'thim_dashboard_order_boxes', $post_data );

        wp_send_json_success( $post_data );
    }

    /**
     * Set global temporary list boxes
     *
     * @since 0.8.0
     */
    private function all_boxes_temp() {
        global $render_boxes;

        if ( $render_boxes === null ) {
            $render_boxes = self::all_boxes();
        }
    }

    /**
     * Render box with key.
     *
     * @since 0.8.9
     *
     * @param $key
     */
    private function render_box( $key ) {
        $this->all_boxes_temp();
        global $render_boxes;

        if ( empty( $render_boxes[ $key ] ) ) {
            return;
        }

        $box  = $render_boxes[ $key ];
        $args = wp_parse_args( $box, array(
            'id'       => '',
            'title'    => '',
            'lock'     => false,
            'template' => '',
        ) );

        if ( empty( $args['template'] ) ) {
            $args['template'] = $args['id'];
        }

        Thim_Dashboard::get_template( 'boxes/master.php', $args );

        /**
         * Only once render box.
         */
        unset( $render_boxes[ $key ] );
    }

    /**
     * Render boxes with order.
     *
     * @since 0.8.9
     *
     * @param $boxes
     */
    private function render_boxes( $boxes ) {
        foreach ( $boxes as $box ) {
            $this->render_box( $box );
        }
    }

    /**
     * Render boxes on the left.
     *
     * @since 0.8.9
     */
    public function render_boxes_left() {
        $boxes_default = array(
            'updates',
            'updates-lite',
            'changelog',
        );

        $order = (array) get_option( 'thim_dashboard_order_boxes', array() );
        $boxes = isset( $order['left'] ) ? $order['left'] : $boxes_default;

        $this->render_boxes( $boxes );
    }

    /**
     * Render boxes on the right.
     *
     * @since 0.8.9
     */
    public function render_boxes_right() {
        $boxes_default = array(
            'appearance',
            'subscribe',
            'documentation',
        );

        $order = (array) get_option( 'thim_dashboard_order_boxes', array() );
        $boxes = isset( $order['right'] ) ? $order['right'] : $boxes_default;

        $this->render_boxes( $boxes );

        /**
         * Render others boxes have not rendered.
         */
        global $render_boxes;
        foreach ( $render_boxes as $key => $box ) {
            $this->render_box( $key );
        }
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
        $sub_pages['dashboard'] = array(
            'title' => __( 'Dashboard', 'thim-core' ),
        );

        return $sub_pages;
    }

    /**
     * Enqueue scripts.
     *
     * @since 0.9.0
     */
    public function enqueue_scripts() {
        if ( ! self::is_myself() ) {
            return;
        }
    }
}
