<?php
/**
 * Section Sidebar
 * 
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'sidebar',
		'panel'    => 'general',
		'priority' => 120,
		'title'    => esc_html__( 'Sidebar', 'eduma' ),
	)
);

// Feature: RTL
thim_customizer()->add_field(
	array(
		'type'     => 'switch',
		'id'       => 'thim_sticky_sidebar',
		'label'    => esc_html__( 'Sticky Sidebar', 'eduma' ),
		'section'  => 'sidebar',
		'default'  => true,
		'priority' => 10,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);