<?php

thim_customizer()->add_panel(
	array(
		'id'       => 'widgets',
		'priority' => 100,
		'title'    => esc_html__( 'Widgets', 'mag-wp' ),
		'icon'     => 'dashicons-welcome-widgets-menus'
	)
);