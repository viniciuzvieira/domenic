<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Thim_Twitter_El extends Widget_Base {

    public function get_name() {
        return 'thim-twitter';
    }

    public function get_title() {
        return esc_html__( 'Thim: Twitter', 'eduma' );
    }

    public function get_icon() {
        return 'thim-widget-icon thim-widget-icon-twitter';
    }


    public function get_base() {
        return basename( __FILE__, '.php' );
    }


    protected function _register_controls() {
        $this->start_controls_section(
            'twitter-item',
            [
                'label' => esc_html__( 'Twitter', 'eduma' )
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'base'  => esc_html__( 'Default', 'eduma' ),
                    'slider' => esc_html__( 'Slider', 'eduma' ),
                ],
                'default'     => 'base'
            ]
        );

        $this->add_control(
            'username',
            [
                'label'       => esc_html__( 'Username', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => 'press_thim'
            ]
        );

        $this->add_control(
            'display',
            [
                'label'       => esc_html__( 'Tweets Display', 'eduma' ),
                'type'        => Controls_Manager::NUMBER,
                'label_block' => false,
                'default'     => '1'
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Map variables between Elementor and SiteOrigin

        $instance = array(
            'title'              => $settings['title'],
            'layout'             => $settings['layout'],
            'username'           => $settings['username'],
            'display'            => $settings['display'],
        );

        $args                 = array();
        $args['before_title'] = '<h3 class="widget-title">';
        $args['after_title']  = '</h3>';

        if ( !empty( $instance['layout'] ) ) {
            $layout = $instance['layout'];
        } else {
            $layout = 'base';
        }

        thim_get_widget_template( $this->get_base(), array(
            'instance' => $instance,
            'args'     => $args
        ), $layout );

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Twitter_El() );