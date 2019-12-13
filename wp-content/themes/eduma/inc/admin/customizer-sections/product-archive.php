<?php
/**
 * Section Archive
 *
 * @package Eduma
 */

thim_customizer()->add_section(
	array(
		'id'       => 'product_archive',
		'panel'    => 'product',
		'title'    => esc_html__( 'Archive Pages', 'eduma' ),
		'priority' => 10,
	)
);

thim_customizer()->add_field(
	array(
		'id'       => 'thim_woo_cate_layout',
		'type'     => 'radio-image',
		'label'    => esc_html__( 'Archive Layouts', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you to choose a layout for all products archive pages.', 'eduma' ),
		'section'  => 'product_archive',
		'priority' => 12,
		'default'  => 'full-content',
		'choices'  => array(
			'sidebar-left'  => THIM_URI . 'images/layout/sidebar-left.jpg',
			'full-content'    => THIM_URI . 'images/layout/body-full.jpg',
			'sidebar-right' => THIM_URI . 'images/layout/sidebar-right.jpg',
		),
	)
);

thim_customizer()->add_field(
	array(
		'type'     => 'select',
		'id'       => 'thim_woo_cate_display_layout',
		'label'    => esc_html__( 'Layout Type', 'eduma' ),
		'tooltip'  => esc_html__( 'Choose the layout type for archive.', 'eduma' ),
		'default'  => 'grid',
		'priority' => 13,
		'multiple' => 0,
		'section'  => 'product_archive',
		'choices'  => array(
			'tab' => esc_html__( 'Tabs', 'eduma' ),
			'grid' => esc_html__( 'Grid/List', 'eduma' ),
		),
	)
);

thim_customizer()->add_field(
    array(
        'type'     => 'select',
        'id'       => 'thim_woo_cate_style_heading_title',
        'label'    => esc_html__( 'Style Heading title', 'eduma' ),
        'tooltip'  => esc_html__( 'Select style for Heading title.', 'eduma' ),
        'default'  => '',
        'priority' => 14,
        'multiple' => 0,
        'section'  => 'product_archive',
        'choices'  => array(
            'style_heading_1' => esc_html__( 'Style Heading 1', 'eduma' ),
            'style_heading_2' => esc_html__( 'Style Heading 2', 'eduma' ),
        ),
    )
);

// Enable or disable breadcrumbs
thim_customizer()->add_field(
	array(
		'id'       => 'thim_woo_cate_hide_breadcrumbs',
		'type'     => 'switch',
		'label'    => esc_html__( 'Hide Breadcrumbs?', 'eduma' ),
		'tooltip'  => esc_html__( 'Check this box to hide/show breadcrumbs.', 'eduma' ),
		'section'  => 'product_archive',
		'default'  => false,
		'priority' => 15,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

// Enable or disable title
thim_customizer()->add_field(
	array(
		'id'       => 'thim_woo_cate_hide_title',
		'type'     => 'switch',
		'label'    => esc_html__( 'Hide Title', 'eduma' ),
		'tooltip'  => esc_html__( 'Check this box to hide/show title.', 'eduma' ),
		'section'  => 'product_archive',
		'default'  => false,
		'priority' => 18,
		'choices'  => array(
			true  => esc_html__( 'On', 'eduma' ),
			false => esc_html__( 'Off', 'eduma' ),
		),
	)
);

thim_customizer()->add_field(
	array(
		'type'     => 'text',
		'id'       => 'thim_woo_cate_sub_title',
		'label'    => esc_html__( 'Sub Heading', 'eduma' ),
		'tooltip'  => esc_html__( 'Allows you can setup sub heading.', 'eduma' ),
		'section'  => 'product_archive',
		'priority' => 20,
	)
);

thim_customizer()->add_field(
	array(
		'type'      => 'image',
		'id'        => 'thim_woo_cate_top_image',
		'label'     => esc_html__( 'Top Image', 'eduma' ),
		'priority'  => 30,
		'transport' => 'postMessage',
		'section'  => 'product_archive',
		'default'     => THIM_URI . "images/bg-page.jpg",
	)
);

// Page Title Background Color
thim_customizer()->add_field(
	array(
		'id'          => 'thim_woo_cate_bg_color',
		'type'        => 'color',
		'label'       => esc_html__( 'Background Color', 'eduma' ),
		'tooltip'     => esc_html__( 'If you do not use background image, then can use background color for page title on heading top. ', 'eduma' ),
		'section'     => 'product_archive',
		'default'     => 'rgba(0,0,0,0.5)',
		'priority'    => 35,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'choice'   => 'color',
				'element'  => '.top_site_main>.overlay-top-header',
				'property' => 'background',
			)
		)
	)
);

thim_customizer()->add_field(
	array(
		'id'          => 'thim_woo_cate_title_color',
		'type'        => 'color',
		'label'       => esc_html__( 'Title Color', 'eduma' ),
		'tooltip'     => esc_html__( 'Allows you can select a color make text color for title.', 'eduma' ),
		'section'     => 'product_archive',
		'default'     => '#ffffff',
		'priority'    => 40,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'choice'   => 'color',
				'element'  => '.top_site_main h1, .top_site_main h2',
				'property' => 'color',
			)
		)
	)
);

thim_customizer()->add_field(
	array(
		'id'          => 'thim_woo_cate_sub_title_color',
		'type'        => 'color',
		'label'       => esc_html__( 'Sub Title Color', 'eduma' ),
		'tooltip'     => esc_html__( 'Allows you can select a color make sub title color page title.', 'eduma' ),
		'section'     => 'product_archive',
		'default'     => '#999',
		'priority'    => 45,
		'choices' => array ('alpha'     => true),
		'transport' => 'postMessage',
		'js_vars'   => array(
			array(
				'choice'   => 'color',
				'element'  => '.top_site_main .banner-description',
				'property' => 'color',
			)
		)
	)
);