<?php
/**
 * Section Settings
 *
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'event_setting',
		'panel'    => 'event',
		'title'    => esc_html__( 'Settings', 'eduma' ),
		'priority' => 20,
	)
);

// Enable or disable quick view
thim_customizer()->add_field(
	array(
		'id'       => 'thim_event_display_year',
		'type'     => 'switch',
		'label'    => esc_html__( 'Show Year', 'eduma' ),
		'tooltip'  => esc_html__( 'Show year on date of all place display events.', 'eduma' ),
		'section'  => 'event_setting',
		'default'  => false,
		'priority' => 10,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

// Enable or disable quick view
thim_customizer()->add_field(
	array(
		'id'       => 'thim_event_disable_book_event',
		'type'     => 'switch',
		'label'    => esc_html__( 'Disable booking tickets', 'eduma' ),
		'tooltip'  => esc_html__( 'Disable booking tickets on single event.', 'eduma' ),
		'section'  => 'event_setting',
		'default'  => false,
		'priority' => 15,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);