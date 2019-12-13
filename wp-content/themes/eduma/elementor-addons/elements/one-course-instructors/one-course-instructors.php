<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Thim_One_Course_Instructors_El extends Widget_Base {

    public function get_name() {
        return 'thim-one-course-instructors';
    }

    public function get_title() {
        return esc_html__( 'Thim: 1 Course Instructors', 'eduma' );
    }

    public function get_icon() {
        return 'thim-widget-icon thim-widget-icon-one-course-instructors';
    }

    public function get_categories() {
        return [ 'thim-elements' ];
    }

    public function get_base() {
        return basename( __FILE__, '.php' );
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content',
            [
                'label' => esc_html__( 'Instructor', 'eduma' )
            ]
        );

        $this->add_control(
            'visible_item',
            [
                'label'   => esc_html__( 'Visible instructors', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'step'    => 1
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'   => esc_html__( 'Show Pagination', 'eduma' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => true
            ]
        );

        $this->add_control(
            'auto_play',
            [
                'label'   => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Map variables between Elementor and SiteOrigin
        $instance = array(
            'visible_item'     => $settings['visible_item'],
            'show_pagination'  => $settings['show_pagination'],
            'auto_play'        => $settings['auto_play'],
        );

        thim_get_widget_template( $this->get_base(), array(
            'instance' => $instance
        ) );
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_One_Course_Instructors_El() );
