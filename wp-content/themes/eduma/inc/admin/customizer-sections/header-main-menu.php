<?php
/**
 * Section Header Main Menu
 *
 * @package Hair_Salon
 */

thim_customizer()->add_section(
	array(
		'id'       => 'header_main_menu',
		'title'    => esc_html__( 'Main Menu', 'eduma' ),
		'panel'    => 'header',
		'priority' => 30,
	)
);

// Select All Fonts For Main Menu
thim_customizer()->add_field(
	array(
		'id'        => 'thim_main_menu',
		'type'      => 'typography',
		'label'     => esc_html__( 'Fonts', 'eduma' ),
		'tooltip'   => esc_html__( 'Allows you to select all font font properties for header. ', 'eduma' ),
		'section'   => 'header_main_menu',
		'priority'  => 10,
		'default'   => array(
			'variant'   => '600',
			'font-size' => '14px',
			//'line-height'    => '1.6em',
			//'text-transform' => 'uppercase',
		),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'choice'   => 'variant',
				'element'  => '.navigation .width-navigation .navbar-nav > li > a, .navigation .width-navigation .navbar-nav > li > span',
				'property' => 'font-weight',
			),
			array(
				'choice'   => 'font-size',
				'element'  => '.navigation .width-navigation .navbar-nav > li > a, .navigation .width-navigation .navbar-nav > li > span',
				'property' => 'font-size',
			),
		)
	)
);

// Background Header
thim_customizer()->add_field(
    array(
        'id'        => 'thim_bg_main_menu_color',
        'type'      => 'color',
        'label'     => esc_html__( 'Background Color', 'eduma' ),
        'tooltip'   => esc_html__( 'Allows you can choose background color for your header. ', 'eduma' ),
        'section'   => 'header_main_menu',
        'default'   => 'rgba(255,255,255,0)',
        'priority'  => 20,
        'choices' => array ('alpha'     => true),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'function' => 'css',
                'element'  => '.site-header, .site-header.header_v2 .width-navigation',
                'property' => 'background-color',
            )
        )
    )
);

// Text color
thim_customizer()->add_field(
	array(
		'id'        => 'thim_main_menu_text_color',
		'type'      => 'color',
		'label'     => esc_html__( 'Text Color', 'eduma' ),
		'tooltip'   => esc_html__( 'Allows you to select color for text.', 'eduma' ),
		'section'   => 'header_main_menu',
		'default'   => '#ffffff',
		'priority'  => 25,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'element'  => '
                            .navigation .width-navigation .navbar-nav > li > a,
                            .navigation .width-navigation .navbar-nav > li > span,
                            .thim-course-search-overlay .search-toggle,
                            .widget_shopping_cart .minicart_hover .cart-items-number,
                            .menu-right .search-form:after
                            ',
				'property' => 'color',
			),
			array(
				'element'  => '.menu-mobile-effect.navbar-toggle span.icon-bar',
				'property' => 'background-color',
			)
		)
	)
);

// Text Link Hover
thim_customizer()->add_field(
	array(
		'id'        => 'thim_main_menu_text_hover_color',
		'type'      => 'color',
		'label'     => esc_html__( 'Text Color Hover', 'eduma' ),
		'tooltip'   => esc_html__( 'Allows you to select color for text link when hover text link.', 'eduma' ),
		'section'   => 'header_main_menu',
		'default'   => '#ffffff',
		'priority'  => 30,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'function' => 'style',
				'element'  => '
								.navigation .width-navigation .navbar-nav > li > a:hover,
								.navigation .width-navigation .navbar-nav > li > span:hover
								',
				'property' => 'color',
			),
			array(
				'element'  => 'body.page-template-landing-page .navigation .navbar-nav #magic-line',
				'property' => 'background-color',
			)
		)
	)
);