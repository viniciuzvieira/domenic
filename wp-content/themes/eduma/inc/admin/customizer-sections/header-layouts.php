<?php
/**
 * Section Header Layout
 *
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'header_layout',
		'title'    => esc_html__( 'Layouts', 'eduma' ),
		'panel'    => 'header',
		'priority' => 20,
	)
);

// Select Header Layout
thim_customizer()->add_field(
	array(
		'id'       => 'thim_header_style',
		'type'     => 'radio-image',
		'label'    => esc_html__( 'Layout', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you can select header layout for header on your site. ', 'eduma' ),
		'section'  => 'header_layout',
		'default'  => 'header_v1',
		'priority' => 10,
		'choices'  => array(
			'header_v1' => THIM_URI . 'images/header/header_v1_thumb.jpg',
			'header_v2' => THIM_URI . 'images/header/header_v2_thumb.jpg',
			'header_v3' => THIM_URI . 'images/header/header_v3_thumb.jpg',
			'header_v4' => THIM_URI . 'images/header/header_v4_thumb.jpg',
            'header_v5' => THIM_URI . 'images/header/header_v5_thumb.jpg',
		),
	)
);

// Select Header Size
thim_customizer()->add_field(
	array(
		'id'       => 'thim_header_size',
		'type'     => 'select',
		'label'    => esc_html__( 'Size', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you can select size layout for header layout. ', 'eduma' ),
		'section'  => 'header_layout',
		'priority' => 15,
		'multiple' => 0,
		'default'  => 'default',
		'choices'  => array(
			'default' => esc_html__( 'Default', 'eduma' ),
			'full_width' => esc_html__( 'Full width', 'eduma' ),
		),
	)
);

// Select Header Position
thim_customizer()->add_field(
	array(
		'id'       => 'thim_header_position',
		'type'     => 'select',
		'label'    => esc_html__( 'Position', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you can select position layout for header layout. ', 'eduma' ),
		'section'  => 'header_layout',
		'priority' => 20,
		'multiple' => 0,
		'default'  => 'header_overlay',
		'choices'  => array(
			'header_default' => esc_html__( 'Default', 'eduma' ),
			'header_overlay' => esc_html__( 'Overlay', 'eduma' ),
		),
	)
);