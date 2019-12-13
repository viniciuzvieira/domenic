<?php
/**
 * Section Copyright
 *
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'copyright',
		'title'    => esc_html__( 'Copyright', 'eduma' ),
		'panel'    => 'footer',
		'priority' => 50,
	)
);

// Copyright Background Color
thim_customizer()->add_field(
	array(
		'id'        => 'thim_copyright_bg_color',
		'type'      => 'color',
		'label'     => esc_html__( 'Background Color', 'eduma' ),
		'tooltip'   => esc_html__( 'Allows you to choose background color for your copyright area. ', 'eduma' ),
		'section'   => 'copyright',
		'default'   => '#111',
		'priority'  => 15,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'function' => 'css',
				'element'  => 'footer#colophon .copyright-area',
				'property' => 'background-color',
			)
		)
	)
);

thim_customizer()->add_field(
	array(
		'id'        => 'thim_copyright_text_color',
		'type'      => 'color',
		'label'     => esc_html__( 'Color', 'eduma' ),
		'tooltip'   => esc_html__( 'Allows you to choose color for your copyright area. ', 'eduma' ),
		'section'   => 'copyright',
		'default'   => '#999',
		'priority'  => 20,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'function' => 'css',
				'element'  => 'footer#colophon .copyright-area,footer#colophon .copyright-area ul li a',
				'property' => 'color',
			)
		)
	)
);

thim_customizer()->add_field(
	array(
		'id'        => 'thim_copyright_border_color',
		'type'      => 'color',
		'label'     => esc_html__( 'Border Color', 'eduma' ),
		'tooltip'   => esc_html__( 'Allows you to choose border color for your copyright area. ', 'eduma' ),
		'section'   => 'copyright',
		'default'   => '#222',
		'priority'  => 20,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'function' => 'css',
				'element'  => 'footer#colophon .copyright-area .copyright-content',
				'property' => 'border-top-color',
			)
		)
	)
);

// Enter Text For Copyright
thim_customizer()->add_field(
	array(
		'type'            => 'textarea',
		'id'              => 'thim_copyright_text',
		'label'           => esc_html__( 'Copyright Text', 'eduma' ),
		'tooltip'         => esc_html__( 'Enter the text that displays in the copyright bar. HTML markup can be used.', 'eduma' ),
		'section'         => 'copyright',
		'default'         => '<a href="' . esc_url( 'https://themeforest.net/item/education-wordpress-theme-education-wp/14058034' ) . '" target="_blank">Education WordPress Theme</a> by <a href="' . esc_url( 'http://www.thimpress.com' ) . '" target="_blank">ThimPress.</a> Powered by WordPress.',
		'priority'        => 100,
		'transport'       => 'postMessage',
		'js_vars'         => array(
			array(
				'element'  => 'footer#colophon .text-copyright',
				'function' => 'html',
			),
		)
	)
);