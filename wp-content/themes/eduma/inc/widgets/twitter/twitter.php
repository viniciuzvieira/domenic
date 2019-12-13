<?php

if ( !class_exists( 'Thim_Twitter_Widget' ) ) {
    class Thim_Twitter_Widget extends Thim_Widget {

        function __construct() {

            parent::__construct(
                'twitter',
                esc_html__( 'Thim: Twitter', 'eduma' ),
                array(
                    'description'   => esc_html__( 'Show Twitter', 'eduma' ),
                    'help'          => '',
                    'panels_groups' => array( 'thim_widget_group' ),
                    'panels_icon'   => 'thim-widget-icon thim-widget-icon-social'
                ),
                array(),
                array(
                    'title'          => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Title', 'eduma' )
                    ),
                    'layout'    => array(
                        'type'    => 'select',
                        'label'   => esc_html__( 'Style', 'eduma' ),
                        'options' => array(
                            ''  => esc_html__( 'Default', 'eduma' ),
                            'slider' => esc_html__( 'Slider', 'eduma' ),
                        ),
                    ),
                    'username'          => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Username', 'eduma' )
                    ),
                    'display'   => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Display', 'eduma' )
                    ),

                ),
                THIM_DIR . 'inc/widgets/twitter/'
            );
        }

        /**
         * Initialize the CTA widget
         */


        function get_template_name( $instance ) {
            if ( !empty( $instance['layout'] ) ) {
                return $instance['layout'];
            } else {
                return 'base';
            }
        }

        function get_style_name( $instance ) {
            return false;
        }
    }
}

function thim_twitter_register_widget() {
    register_widget( 'Thim_Twitter_Widget' );
}

add_action( 'widgets_init', 'thim_twitter_register_widget' );