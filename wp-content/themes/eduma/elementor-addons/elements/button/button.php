<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Thim_Button_El extends Widget_Base {

    public function get_name() {
        return 'thim-button';
    }

    public function get_title() {
        return esc_html__( 'Thim: Button', 'eduma' );
    }

    public function get_icon() {
        return 'thim-widget-icon thim-widget-icon-button';
    }


    public function get_base() {
        return basename( __FILE__, '.php' );
    }


    protected function _register_controls() {

        $this->start_controls_section(
            'section_tabs',
            [
                'label' => __( 'Content', 'eduma' )
            ]
        );


        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Button Text', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'READ MORE', 'eduma' ),
                'label_block' => false
            ]
        );

        $this->add_control(
            'url',
            [
                'label'         => esc_html__( 'Destination URL', 'eduma' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'https://your-link.com', 'eduma' ),
                'default'       => '#'
            ]
        );

        $this->add_control(
            'new_window',
            [
                'label'   => esc_html__( 'Open in New Window?', 'eduma' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'icon',
            [
                'label' => __( 'Icons', 'eduma' ),
            ]
        );

        $this->add_control(
            'icon',
            [
                'label'       => esc_html__( 'Select Icon:', 'eduma' ),
                'type'        => Controls_Manager::ICON,
                'placeholder' => esc_html__( 'Choose...', 'eduma' ),
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label'     => __( 'Icon Font Size (px)', 'eduma' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 14,
                'min'       => 0,
                'step'      => 1,
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label'     => esc_html__( 'Icon Position', 'eduma' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''       => esc_html__( 'Select', 'eduma' ),
                    'before' => esc_html__( 'Before text', 'eduma' ),
                    'after'  => esc_html__( 'After text', 'eduma' ),
                ],
                'default'   => '',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'layout',
            [
                'label' => __( 'Layouts', 'eduma' ),
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label'     => esc_html__( 'Button Size', 'eduma' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'normal' => esc_html__( 'Normal', 'eduma' ),
                    'small'  => esc_html__( 'Small', 'eduma' ),
                    'medium' => esc_html__( 'Medium', 'eduma' ),
                    'large'  => esc_html__( 'Large', 'eduma' ),
                ],
                'default'   => 'normal',
            ]
        );

        $this->add_control(
            'rounding',
            [
                'label'     => esc_html__( 'Rounding', 'eduma' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''             => esc_html__( 'None', 'eduma' ),
                    'tiny-rounded' => esc_html__( 'Tiny Rounded', 'eduma' ),
                    'very-rounded' => esc_html__( 'Very Rounded', 'eduma' ),
                ],
            ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
            'styles_group',
            [
                'label' => esc_html__( 'Style', 'eduma' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'custom_style',
            [
                'label'   => esc_html__( 'Style', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'default'        => esc_html__( 'Default', 'eduma' ),
                    'custom_style'   => esc_html__( 'Custom Style', 'eduma' )
                ],
                'default' => 'default'
            ]
        );


        $this->add_control(
            'font_size',
            [
                'label'     => esc_html__( 'Font Size', 'eduma' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '14',
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'font_weight',
            [
                'label'     => esc_html__( 'Font Weight', 'eduma' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''       => esc_html__( 'Choose...', 'eduma' ),
                    'normal' => esc_html__( 'Normal', 'eduma' ),
                    'bold'   => esc_html__( 'Bold', 'eduma' ),
                    '100'    => esc_html__( '100', 'eduma' ),
                    '200'    => esc_html__( '200', 'eduma' ),
                    '300'    => esc_html__( '300', 'eduma' ),
                    '400'    => esc_html__( '400', 'eduma' ),
                    '500'    => esc_html__( '500', 'eduma' ),
                    '600'    => esc_html__( '600', 'eduma' ),
                    '700'    => esc_html__( '700', 'eduma' ),
                    '800'    => esc_html__( '800', 'eduma' ),
                    '900'    => esc_html__( '900', 'eduma' ),
                ],
                'default'   => 'normal',
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label'     => esc_html__( 'Border Width', 'eduma' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '0',
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__( 'Select Color', 'eduma' ),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__( 'Border Color', 'eduma' ),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__( 'Background Color', 'eduma' ),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'eduma' ),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'hover_border_color',
            [
                'label' => esc_html__( 'Hover Border Color', 'eduma' ),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->add_control(
            'hover_bg_color',
            [
                'label' => esc_html__( 'Hover Background Color', 'eduma' ),
                'type'  => Controls_Manager::COLOR,
                'condition' => [
                    'custom_style' => [ 'custom_style' ]
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Map variables between Elementor and SiteOrigin

        $instance = array(
            'title'            => $settings['title'],
            'url'              => $settings['url'],
            'new_window'       => $settings['new_window'],
            'custom_style'     => $settings['custom_style'],
            'style_options'             => array(
                'font_size'             => $settings['font_size'],
                'font_weight'           => $settings['font_weight'],
                'border_width'          => $settings['border_width'],
                'color'                 => $settings['color'],
                'border_color'          => $settings['border_color'],
                'bg_color'              => $settings['bg_color'],
                'hover_color'           => $settings['hover_color'],
                'hover_border_color'    => $settings['hover_border_color'],
                'hover_bg_color'        => $settings['hover_bg_color'],
            ),
            'icon'              => $settings['icon'],
            'icon_size'         => $settings['icon_size'],
            'icon_position'     => $settings['icon_position'],
        );

//        $instance['icon'] = array(
//            'icon'              => $settings['icon'],
//            'icon_size'         => $settings['icon_size'],
//            'icon_position'     => $settings['icon_position'],
//        );
//
        $instance['layout'] = array(
            'button_size'       => $settings['button_size'],
            'rounding'          => $settings['rounding'],
        );



        thim_get_widget_template( $this->get_base(), array(
            'instance' => $instance
        ) );

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Button_El() );