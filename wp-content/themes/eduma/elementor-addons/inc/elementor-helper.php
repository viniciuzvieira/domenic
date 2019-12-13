<?php

namespace Elementor;

function thim_elementor_init() {
	// Creating a new category
	Plugin::instance()->elements_manager->add_category(
		'thim-elements',
		[
			'title' => esc_html__( 'Thim Elements', 'eduma' ),
			'icon'  => 'font'
		]
	);

}

add_action( 'elementor/init', 'Elementor\thim_elementor_init' );
