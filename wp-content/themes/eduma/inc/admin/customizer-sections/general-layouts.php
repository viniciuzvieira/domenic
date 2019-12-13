<?php
/**
 * Section Layout
 *
 * @package Hair_Salon
 */

thim_customizer()->add_section(
	array(
		'id'       => 'content_layout',
		'panel'    => 'general',
		'title'    => esc_html__( 'Layouts', 'eduma' ),
		'priority' => 20,
	)
);

//---------------------------------------------Site-Content---------------------------------------------//

// Select Theme Content Layout
thim_customizer()->add_field(
	array(
		'id'       => 'thim_box_layout',
		'type'     => 'radio-image',
		'label'    => esc_html__( 'Site Layout', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you to choose a layout for your site.', 'eduma' ),
		'section'  => 'content_layout',
		'priority' => 10,
		'default'  => 'wide',
		'choices'  => array(
			'wide'  => THIM_URI . 'images/layout/content-full.jpg',
			'boxed' => THIM_URI . 'images/layout/content-boxed.jpg',
		),
	)
);

//------------------------------------------------Page---------------------------------------------//

// Select All Page Layout
thim_customizer()->add_field(
	array(
		'id'       => 'thim_page_layout',
		'type'     => 'radio-image',
		'label'    => esc_html__( 'Page Layouts', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you to choose a layout to display for all pages on your site.', 'eduma' ),
		'section'  => 'content_layout',
		'priority' => 66,
		'default'  => 'full-content',
		'choices'  => array(
			'sidebar-left'  => THIM_URI . 'images/layout/sidebar-left.jpg',
			'full-content'  => THIM_URI . 'images/layout/body-full.jpg',
			'sidebar-right' => THIM_URI . 'images/layout/sidebar-right.jpg',
		),
	)
);


// Select All Content Page Layout
thim_customizer()->add_field(
    array(
        'type'            => 'select',
        'id'              => 'thim_layout_content_page',
        'label'           => esc_html__( 'Layout Content', 'eduma' ),
        'default'         => 'normal',
        'section'  => 'content_layout',
        'priority'        => 70,
        'choices'         => array(
            'normal'    => esc_html__( 'Normal', 'eduma' ),
            'new-1'  => esc_html__( 'New Demo', 'eduma' ),
        ),
    )
);

// Select All Page Layout
thim_customizer()->add_field(
	array(
		'type'            => 'select',
		'id'              => 'thim_size_body',
		'label'           => esc_html__( 'Size Body', 'eduma' ),
		'default'         => 'normal',
		'section'  => 'content_layout',
		'priority'        => 80,
		'choices'         => array(
			'normal'    => esc_html__( 'Normal', 'eduma' ),
			'wide'  => esc_html__( 'Wide', 'eduma' ),
		),
	)
);
