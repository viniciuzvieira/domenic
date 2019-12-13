<?php
/**
 * Section Header Toolbar
 */

thim_customizer()->add_section(
	array(
		'id'       => 'header_toolbar',
		'title'    => esc_html__( 'Toolbar', 'eduma' ),
		'panel'    => 'header',
		'priority' => 25,
	)
);

// Enable or disable top bar
thim_customizer()->add_field(
	array(
		'id'       => 'thim_toolbar_show',
		'type'     => 'switch',
		'label'    => esc_html__( 'Show Toolbar', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you to enable or disable Toolbar.', 'eduma' ),
		'section'  => 'header_toolbar',
		'default'  => true,
		'priority' => 10,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

thim_customizer()->add_field(
	array(
		'id'        => 'thim_toolbar',
		'type'      => 'typography',
		'label'     => esc_html__( 'Font size', 'eduma' ),
		'tooltip'   => esc_html__( 'Allows you to select font size for toolbar. ', 'eduma' ),
		'section'   => 'header_toolbar',
		'priority'  => 20,
		'default'   => array(
			'font-size'      => '12px',
		),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'choice'   => 'font-size',
				'element'  => '#toolbar',
				'property' => 'font-size',
			),
		),
	)
);

// Topbar background color
thim_customizer()->add_field(
	array(
		'id'          => 'thim_bg_color_toolbar',
		'type'        => 'color',
		'label'       => esc_html__( 'Background Color', 'eduma' ),
		'tooltip'     => esc_html__( 'Allows you to choose a background color for widget on toolbar. ', 'eduma' ),
		'section'     => 'header_toolbar',
		'default'     => '#111',
		'priority'    => 20,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'element'  => '
							#toolbar,
							.site-header.header_v2,
							.site-header.bg-custom-sticky.affix.header_v2
							',
				'property' => 'background-color',
			)
		)
	)
);

thim_customizer()->add_field(
	array(
		'id'          => 'thim_text_color_toolbar',
		'type'        => 'color',
		'label'       => esc_html__( 'Text Color', 'eduma' ),
		'tooltip'     => esc_html__( 'Allows you to choose a color for widget on toolbar. ', 'eduma' ),
		'section'     => 'header_toolbar',
		'default'     => '#ababab',
		'priority'    => 25,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'element'  => '#toolbar',
				'property' => 'color',
			),
			array(
				'element'  => '#toolbar .widget_form-login .thim-link-login a:first-child:not(:last-child)',
				'property' => 'border-right-color',
			)
		)
	)
);

thim_customizer()->add_field(
	array(
		'id'          => 'thim_link_color_toolbar',
		'type'        => 'color',
		'label'       => esc_html__( 'Link Color', 'eduma' ),
		'tooltip'     => esc_html__( 'Allows you to choose a link color for widget on toolbar. ', 'eduma' ),
		'section'     => 'header_toolbar',
		'default'     => '#fff',
		'priority'    => 25,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'element'  => '#toolbar a, #toolbar span.value',
				'property' => 'color',
			)
		)
	)
);

thim_customizer()->add_field(
    array(
        'id'          => 'thim_link_hover_color_toolbar',
        'type'        => 'color',
        'label'       => esc_html__( 'Link Hover Color', 'eduma' ),
        'tooltip'     => esc_html__( 'Allows you to choose a link hover color for widget on toolbar. ', 'eduma' ),
        'section'     => 'header_toolbar',
        'default'     => '#fff',
        'priority'    => 30,
        'choices' => array ('alpha'     => true),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '#toolbar a:hover, #toolbar .widget_nav_menu .menu > li > a:hover',
                'property' => 'color',
            )
        )
    )
);