<?php
/**
 * Section Header Sticky Menu
 *
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'header_sticky_menu',
		'title'    => esc_html__( 'Sticky Menu', 'eduma' ),
		'panel'    => 'header',
		'priority' => 35,
	)
);

// Enable or Disable
thim_customizer()->add_field(
	array(
		'id'       => 'thim_header_sticky',
		'type'     => 'switch',
		'label'    => esc_html__( 'Sticky On Scroll', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you can show or hide sticky header menu on your site . ', 'eduma' ),
		'section'  => 'header_sticky_menu',
		'default'  => true,
		'priority' => 10,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

// Select Style
thim_customizer()->add_field(
	array(
		'id'       => 'thim_config_att_sticky',
		'type'     => 'select',
		'label'    => esc_html__( 'Config sticky menu', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you can config sticky menu style.', 'eduma' ),
		'section'  => 'header_sticky_menu',
		'default'  => 'sticky_custom',
		'priority' => 10,
		'multiple' => 0,
		'choices'  => array(
			'sticky_same'   => esc_html__( 'The same with main menu', 'eduma' ),
			'sticky_custom' => esc_html__( 'Custom', 'eduma' )
		),
	)
);

// Background Header
thim_customizer()->add_field(
	array(
		'id'              => 'thim_sticky_bg_main_menu_color',
		'type'            => 'color',
		'label'           => esc_html__( 'Background Color', 'eduma' ),
		'tooltip'         => esc_html__( 'Allows you can select a color make background color for header sticky menu . ', 'eduma' ),
		'section'         => 'header_sticky_menu',
		'default'         => '#fff',
		'priority'        => 16,
		'choices' => array ('alpha'     => true),
		'transport'       => 'postMessage',
		'js_vars'         => array(
			array(
				'element'  => '.site-header.bg-custom-sticky.affix, .site-header.header_v2.bg-custom-sticky.affix .width-navigation',
				'property' => 'background-color',
			)
		),
		'active_callback' => array(
			array(
				'setting'  => 'thim_config_att_sticky',
				'operator' => '===',
				'value'    => 'sticky_custom',
			),
		),
	)
);

// Text Color
thim_customizer()->add_field(
	array(
		'id'              => 'thim_sticky_main_menu_text_color',
		'type'            => 'color',
		'label'           => esc_html__( 'Text Color', 'eduma' ),
		'tooltip'         => esc_html__( 'Allows you can select a color make text color on header sticky menu . ', 'eduma' ),
		'section'         => 'header_sticky_menu',
		'default'         => '#333',
		'priority'        => 18,
		'choices' => array ('alpha'     => true),
		'transport'       => 'postMessage',
		'js_vars'         => array(
			array(
				'choice'   => 'style',
				'element'  => '.site-header.bg-custom-sticky.affix .navbar-nav > li > a,
								.site-header.bg-custom-sticky.affix .navbar-nav > li > span,
								.site-header.bg-custom-sticky.affix .navbar-nav li.menu-right li a,
								.site-header.bg-custom-sticky.affix .navbar-nav li.menu-right li span,
								.site-header.bg-custom-sticky.affix .navbar-nav li.menu-right li div,
								.site-header.bg-custom-sticky.affix .navbar-nav li.menu-right .search-form:after,
								.site-header.affix .thim-course-search-overlay .search-toggle,
								.site-header.affix .widget_shopping_cart .minicart_hover .cart-items-number
								',
				'property' => 'color',
			),
			array(
				'function' => 'style',
				'element'  => '.site-header.affix .menu-mobile-effect.navbar-toggle span.icon-bar',
				'property' => 'background-color',
			)
		),
		'active_callback' => array(
			array(
				'setting'  => 'thim_config_att_sticky',
				'operator' => '===',
				'value'    => 'sticky_custom',
			),
		),
	)
);

// Text Hover Color
thim_customizer()->add_field(
	array(
		'id'              => 'thim_sticky_main_menu_text_hover_color',
		'type'            => 'color',
		'label'           => esc_html__( 'Text Hover Color', 'eduma' ),
		'tooltip'         => esc_html__( 'Allows you can select color for text link when hover text link on header sticky menu. ', 'eduma' ),
		'section'         => 'header_sticky_menu',
		'default'         => '#333',
		'priority'        => 19,
		'choices' => array ('alpha'     => true),
		'transport'       => 'postMessage',
		'js_vars'         => array(
			array(
				'function' => 'style',
				'element'  => '.site-header.bg-custom-sticky.affix .navbar-nav > li > a:hover,
								.site-header.bg-custom-sticky.affix .navbar-nav > li > span:hover,
								.site-header.bg-custom-sticky.affix .navbar-nav li.menu-right li a:hover,
								.site-header.bg-custom-sticky.affix .navbar-nav li.menu-right li span:hover
								',
				'property' => 'color',
			)
		),
		'active_callback' => array(
			array(
				'setting'  => 'thim_config_att_sticky',
				'operator' => '===',
				'value'    => 'sticky_custom',
			),
		),
	)
);