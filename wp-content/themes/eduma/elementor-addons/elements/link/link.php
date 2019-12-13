<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Thim_Link_El extends Widget_Base {

    public function get_name() {
        return 'thim-link';
    }

    public function get_title() {
        return esc_html__( 'Thim: Link', 'eduma' );
    }

    public function get_icon() {
        return 'thim-widget-icon thim-widget-icon-link';
    }


    public function get_base() {
        return basename( __FILE__, '.php' );
    }


    protected function _register_controls() {
        $this->start_controls_section(
            'link_item',
            [
                'label' => esc_html__( 'Link Item', 'eduma' )
            ]
        );

        $this->add_control(
            'text',
            [
                'label'       => esc_html__( 'Title on here', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false
            ]
        );

        $this->add_control(
            'link',
            [
                'label'         => esc_html__( 'Link of title', 'eduma' ),
                'description'   => esc_html__( 'Leave empty to disable this field.', 'eduma' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '#'
            ]
        );

        $this->add_control(
            'content',
            [
                'label'       => esc_html__( 'Content', 'eduma' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Add your a short description here', 'eduma' ),
                'label_block' => true
            ]
        );
        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Map variables between Elementor and SiteOrigin

        $instance = array(
            'text'              => $settings['text'],
            'link'              => $settings['link'],
            'content'           => $settings['content'],
        );


        thim_get_widget_template( $this->get_base(), array(
            'instance' => $instance
        ) );

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Link_El() );