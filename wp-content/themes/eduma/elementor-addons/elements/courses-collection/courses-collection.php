<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Thim_Courses_Collection_El extends Widget_Base {

    public function get_name() {
        return 'thim-courses-collection';
    }

    public function get_title() {
        return esc_html__( 'Thim: Courses Collection', 'eduma' );
    }

    public function get_icon() {
        return 'thim-widget-icon thim-widget-icon-courses-collection';
    }


    public function get_base() {
        return basename( __FILE__, '.php' );
    }


    protected function _register_controls() {
        $this->start_controls_section(
            'content',
            [
                'label' => __( 'Courses collection', 'eduma' )
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Heading Text', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
                'label_block' => false
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''        => esc_html__( 'Default', 'eduma' ),
                    'slider'  => esc_html__( 'Slider', 'eduma' )
                ],
                'default' => ''
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'   => esc_html__( 'Limit collections', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 8,
                'min'     => 1,
                'step'    => 1
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'       => esc_html__( 'Columns', 'eduma' ),
                'type'        => Controls_Manager::SELECT,
                'label_block' => false,
                'options'     => [
                    '1' => esc_html__( '1', 'eduma' ),
                    '2' => esc_html__( '2', 'eduma' ),
                    '3' => esc_html__( '3', 'eduma' ),
                    '4' => esc_html__( '4', 'eduma' )
                ],
                'default'     => '4'
            ]
        );

        $this->add_control(
            'feature_items',
            [
                'label'       => esc_html__( 'Feature Items', 'eduma' ),
                'type'        => Controls_Manager::SELECT,
                'label_block' => false,
                'options'     => [
                    '1' => esc_html__( '1', 'eduma' ),
                    '2' => esc_html__( '2', 'eduma' ),
                    '3' => esc_html__( '3', 'eduma' ),
                    '4' => esc_html__( '4', 'eduma' )
                ],
                'default'     => '2'
            ]
        );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Map variables between Elementor and SiteOrigin

        $instance = array(
            'title'                => $settings['title'],
            'url'                  => $settings['layout'],
            'new_window'           => $settings['limit'],
            'custom_style'         => $settings['columns'],
            'feature_items'   => $settings['feature_items'],
        );

        $args                 = array();
        $args['before_title'] = '<h3 class="widget-title">';
        $args['after_title']  = '</h3>';

        if ( isset( $settings['layout'] ) && $settings['layout'] != '' ) {
            if ( thim_is_new_learnpress( '3.0' ) ) {
                $layout = $settings['layout'] . '-v3';
            } elseif ( thim_is_new_learnpress( '2.0' ) ) {
                $layout = $settings['layout'] . '-v2';
            } else {
                $layout = $settings['layout'];
            }
        } else {
            if ( thim_is_new_learnpress( '3.0' ) ) {
                $layout = 'base-v3';
            } elseif ( thim_is_new_learnpress( '2.0' ) ) {
                $layout = 'base-v2';
            } else {
                $layout = 'base';
            }
        };

        thim_get_widget_template( $this->get_base(), array(
            'instance' => $instance,
            'args'     => $args
        ), $layout );

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Courses_Collection_El() );