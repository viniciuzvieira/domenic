<?php
/**
 * Section Advance features
 *
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'advanced',
		'panel'    => 'general',
		'priority' => 90,
		'title'    => esc_html__( 'Extra Features', 'eduma' ),
	)
);

// Feature: RTL
thim_customizer()->add_field(
	array(
		'type'     => 'switch',
		'id'       => 'thim_rtl_support',
		'label'    => esc_html__( 'RTL Support', 'eduma' ),
		'section'  => 'advanced',
		'default'  => true,
		'priority' => 10,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

// Feature: Auto Login
thim_customizer()->add_field(
    array(
        'type'     => 'switch',
        'id'       => 'thim_auto_login',
        'label'    => esc_html__( 'Auto Login', 'eduma' ),
        'section'  => 'advanced',
        'default'  => true,
        'priority' => 15,
        'choices'  => array(
            true  => esc_html__( 'On', 'eduma' ),
            false => esc_html__( 'Off', 'eduma' ),
        ),
    )
);

// Feature: Smoothscroll
thim_customizer()->add_field(
	array(
		'type'     => 'switch',
		'id'       => 'thim_smooth_scroll',
		'label'    => esc_html__( 'Smooth Scrolling', 'eduma' ),
		'tooltip'  => esc_html__( 'Turn on to enable smooth scrolling.', 'eduma' ),
		'section'  => 'advanced',
		'default'  => false,
		'priority' => 20,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

thim_customizer()->add_field(
	array(
		'type'     => 'switch',
		'id'       => 'thim_remove_query_string',
		'label'    => esc_html__( 'Remove Query String', 'eduma' ),
		'section'  => 'advanced',
		'default'  => false,
		'priority' => 25,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

// Feature: Back To Top
thim_customizer()->add_field(
	array(
		'type'     => 'switch',
		'id'       => 'thim_show_to_top',
		'label'    => esc_html__( 'Back To Top', 'eduma' ),
		'tooltip'  => esc_html__( 'Turn on to enable the Back To Top script which adds the scrolling to top functionality.', 'eduma' ),
		'section'  => 'advanced',
		'default'  => true,
		'priority' => 40,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

// Feature: Preload
thim_customizer()->add_field( array(
	'type'     => 'radio-image',
	'id'       => 'thim_preload',
	'section'  => 'advanced',
	'label'    => esc_html__( 'Preloading', 'eduma' ),
	'default'  => 'style_3',
	'priority' => 70,
	'choices'  => array(
		''                => THIM_URI . 'images/preloading/off.jpg',
		'style_1'          => THIM_URI . 'images/preloading/style-1.gif',
		'style_2'          => THIM_URI . 'images/preloading/style-2.gif',
		'style_3'          => THIM_URI . 'images/preloading/style-3.gif',
		'wave'            => THIM_URI . 'images/preloading/wave.gif',
		'rotating-plane'  => THIM_URI . 'images/preloading/rotating-plane.gif',
		'double-bounce'   => THIM_URI . 'images/preloading/double-bounce.gif',
		'wandering-cubes' => THIM_URI . 'images/preloading/wandering-cubes.gif',
		'spinner-pulse'   => THIM_URI . 'images/preloading/spinner-pulse.gif',
		'chasing-dots'    => THIM_URI . 'images/preloading/chasing-dots.gif',
		'three-bounce'    => THIM_URI . 'images/preloading/three-bounce.gif',
		'cube-grid'       => THIM_URI . 'images/preloading/cube-grid.gif',
		'image'           => THIM_URI . 'images/preloading/custom-image.jpg',
	),
) );

// Feature: Preload Image Upload
thim_customizer()->add_field( array(
	'type'            => 'image',
	'id'              => 'thim_preload_image',
	'label'           => esc_html__( 'Preloading Custom Image', 'eduma' ),
	'section'         => 'advanced',
	'priority'        => 80,
	'active_callback' => array(
		array(
			'setting'  => 'thim_preload',
			'operator' => '===',
			'value'    => 'image',
		),
	),
) );

// Feature: Preload Colors
thim_customizer()->add_field( array(
	'type'            => 'multicolor',
	'id'              => 'thim_preload_style',
	'label'           => esc_html__( 'Preloading Color', 'eduma' ),
	'section'         => 'advanced',
	'priority'        => 90,
	'choices'         => array(
		'background' => esc_html__( 'Background color', 'eduma' ),
		'color'      => esc_html__( 'Icon color', 'eduma' ),
	),
	'default'         => array(
		'background' => '#ffffff',
		'color'      => '#ffb606',
	),
	'active_callback' => array(
		array(
			'setting'  => 'thim_preload',
			'operator' => '!=',
			'value'    => '',
		),
	),
) );

