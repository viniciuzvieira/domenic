<?php
/**
 * Section Header Sub Menu
 * 
 * @package Eduma
 */

thim_customizer()->add_section(
    array(
        'id'             => 'header_sub_menu',
        'title'          => esc_html__( 'Sub Menu', 'eduma' ),
        'panel'			 => 'header',
        'priority'       => 40,
    )
);

// Background Header
thim_customizer()->add_field(
    array(
        'id'          => 'thim_sub_menu_bg_color',
        'type'        => 'color',
        'label'       => esc_html__( 'Background Color', 'eduma' ),
        'tooltip'     => esc_html__( 'Allows you can select a color make background color sub menu on header  . ', 'eduma' ),
        'section'     => 'header_sub_menu',
        'default'     => '#ffffff',
        'priority'    => 16,
        'choices' => array ('alpha'     => true),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.navigation .width-navigation .navbar-nav > li .sub-menu',
                'property' => 'background-color',
            )
        )
    )
);

// Text Color
thim_customizer()->add_field(
    array(
        'id'          => 'thim_sub_menu_border_color',
        'type'        => 'color',
        'label'       => esc_html__( 'Border Color', 'eduma' ),
        'tooltip'     => esc_html__( 'Allows you can select border color for sub menu.', 'eduma' ),
        'section'     => 'header_sub_menu',
        'default'     => 'rgba(43,43,43,0)',
        'priority'    => 17,
        'choices' => array ('alpha'     => true),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '
                                .navigation .width-navigation .navbar-nav > li .sub-menu li > a,
                                .navigation .width-navigation .navbar-nav > li .sub-menu li > span,
                                .navigation .width-navigation .navbar-nav > li .sub-menu.megacol > li :last-child > a,
                                .navigation .width-navigation .navbar-nav > li .sub-menu.megacol > li :last-child > span
                                ',
                'property' => 'border-bottom-color',
            )
        )
    )
);

// Text Color
thim_customizer()->add_field(
    array(
        'id'          => 'thim_sub_menu_text_color',
        'type'        => 'color',
        'label'       => esc_html__( 'Text Color', 'eduma' ),
        'tooltip'     => esc_html__( 'Allows you can select a color make text color sub menu on header.', 'eduma' ),
        'section'     => 'header_sub_menu',
        'default'     => '#999',
        'priority'    => 17,
        'choices' => array ('alpha'     => true),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '.navigation .width-navigation .navbar-nav > li .sub-menu li > a, .navigation .width-navigation .navbar-nav > li .sub-menu li > span',
                'property' => 'color',
            )
        )
    )
);

// Text Hover Color
thim_customizer()->add_field(
    array(
        'id'          => 'thim_sub_menu_text_color_hover',
        'type'        => 'color',
        'label'       => esc_html__( 'Text Hover Color', 'eduma' ),
        'tooltip'     => esc_html__( 'Allows you can select color for text link when hover text link sub menu on header.', 'eduma' ),
        'section'     => 'header_sub_menu',
        'default'     => '#333',
        'priority'    => 18,
        'choices' => array ('alpha'     => true),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'function'   => 'style',
                'element'  => '
                                .navigation .width-navigation .navbar-nav > li .sub-menu li > a:hover,
                                .navigation .width-navigation .navbar-nav > li .sub-menu li > span:hover,
                                .navigation .width-navigation .navbar-nav > li .sub-menu > li.current-menu-item > a,
                                .navigation .width-navigation .navbar-nav > li .sub-menu > li.current-menu-item span
                                ',
                'property' => 'color',
            )
        )
    )
);


