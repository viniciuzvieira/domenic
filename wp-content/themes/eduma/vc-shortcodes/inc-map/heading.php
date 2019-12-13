<?php

vc_map( array(
	'name'        => esc_html__( 'Thim Heading', 'eduma' ),
	'base'        => 'thim-heading',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display heading.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-heading',
	'params'      => array(
		//Title
		array(
			'type'        => 'textarea',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
			'description' => esc_html__( 'Write the title for the heading.', 'eduma' )
		),
        array(
            'type'        => 'textarea',
            'admin_label' => true,
            'heading'     => esc_html__( 'Main Title', 'eduma' ),
            'param_name'  => 'main_title',
            'value'       => '',
            'description' => esc_html__( 'Write the Main title for the heading.', 'eduma' )
        ),
        array(
            'type'        => 'checkbox',
            'admin_label' => false,
            'heading'     => esc_html__( 'Title Uppercase?', 'eduma' ),
            'param_name'  => 'title_uppercase',
            'std'         => true,
            'description' => esc_html__( 'Title Uppercase?', 'eduma' ),
        ),
		//Title color
		array(
			'type'        => 'colorpicker',
			'admin_label' => false,
			'heading'     => esc_html__( 'Heading color ', 'eduma' ),
			'param_name'  => 'textcolor',
			'value'       => '',
			'description' => esc_html__( 'Select the title color.', 'eduma' ),
			'group'       => esc_html__( 'Heading Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => false,
			'heading'     => esc_html__( 'Heading tag', 'eduma' ),
			'param_name'  => 'size',
			'value'       => array(
				__( 'Select tag', 'eduma' ) => '',
				'h2' => 'h2',
				'h3' => 'h3',
				'h4' => 'h4',
				'h5' => 'h5',
				'h6' => 'h6',
			),
			'description' => esc_html__( 'Choose heading element.', 'eduma' ),
			'group'       => esc_html__( 'Heading Settings', 'eduma' ),
		),

		//Use custom or default title?
		array(
			'type'        => 'dropdown',
			'admin_label' => false,
			'heading'     => esc_html__( 'Use custom or default title?', 'eduma' ),
			'param_name'  => 'title_custom',
			'value'       => array(
				__( 'Default', 'eduma' ) => '',
				__( 'Custom', 'eduma' )  => 'custom',
			),
			'description' => esc_html__( 'If you select default you will use default title which customized in typography.', 'eduma' ),
			'group'       => esc_html__( 'Heading Settings', 'eduma' ),
		),

		array(
			'type'        => 'number',
			'admin_label' => false,
			'heading'     => esc_html__( 'Font size ', 'eduma' ),
			'param_name'  => 'font_size',
			'min'         => 0,
			'value'       => '',
			'suffix'      => 'px',
			'description' => esc_html__( 'Custom title font size.', 'eduma' ),
			'std'         => '14',
			'dependency'  => array(
				'element' => 'title_custom',
				'value'   => 'custom',
			),
			'group'       => esc_html__( 'Heading Settings', 'eduma' ),
		),
		array(
			'type'        => 'dropdown',
			'admin_label' => false,
			'heading'     => esc_html__( 'Font Weight ', 'eduma' ),
			'param_name'  => 'font_weight',
			'value'       => array(
				__( 'Custom font weight', 'eduma' ) => '',
				__( 'Normal', 'eduma' )             => 'normal',
				__( 'Bold', 'eduma' )               => 'bold',
				__( '100', 'eduma' )                => '100',
				__( '200', 'eduma' )                => '200',
				__( '300', 'eduma' )                => '300',
				__( '400', 'eduma' )                => '400',
				__( '500', 'eduma' )                => '500',
				__( '600', 'eduma' )                => '600',
				__( '700', 'eduma' )                => '700',
				__( '800', 'eduma' )                => '800',
				__( '900', 'eduma' )                => '900',
			),
			'description' => esc_html__( 'Custom title font weight.', 'eduma' ),
			'dependency'  => array(
				'element' => 'title_custom',
				'value'   => 'custom',
			),
			'group'       => esc_html__( 'Heading Settings', 'eduma' ),
		),

		array(
			'type'        => 'dropdown',
			'admin_label' => false,
			'heading'     => esc_html__( 'Font style ', 'eduma' ),
			'param_name'  => 'font_style',
			'value'       => array(
				__( 'Choose the title font style', 'eduma' ) => '',
				__( 'Italic', 'eduma' )                      => 'italic',
				__( 'Oblique', 'eduma' )                     => 'oblique',
				__( 'Initial', 'eduma' )                     => 'initial',
				__( 'Inherit', 'eduma' )                     => 'inherit',
				__( 'Normal', 'eduma' )                      => 'normal',
			),
			'description' => esc_html__( 'Custom title font style.', 'eduma' ),
			'dependency'  => array(
				'element' => 'title_custom',
				'value'   => 'custom',
			),
			'group'       => esc_html__( 'Heading Settings', 'eduma' ),
		),

		// Description
		array(
			'type'        => 'textarea',
			'heading'     => esc_html__( 'Sub heading', 'eduma' ),
			'param_name'  => 'sub_heading',
			'value'       => '',
			'description' => esc_html__( 'Enter sub heading.', 'eduma' )
		),
		//Description color
		array(
			'type'        => 'colorpicker',
			'admin_label' => false,
			'heading'     => esc_html__( 'Sub heading color ', 'eduma' ),
			'param_name'  => 'sub_heading_color',
			'value'       => '',
			'description' => esc_html__( 'Select the sub heading color.', 'eduma' ),
		),

        array(
            'type'        => 'checkbox',
            'admin_label' => false,
            'heading'     => esc_html__( 'Clone Title?', 'eduma' ),
            'param_name'  => 'clone_title',
            //'value'       => array( '' => 'yes' ),
            'std'         => false,
            'description' => esc_html__( 'Clone Title.', 'eduma' ),
        ),
		//Show separator?
		array(
			'type'        => 'checkbox',
			'admin_label' => false,
			'heading'     => esc_html__( 'Show Separator?', 'eduma' ),
			'param_name'  => 'line',
			//'value'       => array( esc_html__( '', 'eduma' ) => 'yes' ),
			'std'         => true,
			'description' => esc_html__( 'Tick it to show the separator between title and description.', 'eduma' ),
		),
		//Separator color
		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Separator color', 'eduma' ),
			'param_name'  => 'bg_line',
			'value'       => '',
			'description' => esc_html__( 'Choose the separator color.', 'eduma' ),
		),

		//Alignment
		array(
			'type'        => 'dropdown',
			'admin_label' => false,
			'heading'     => esc_html__( 'Text alignment', 'eduma' ),
			'param_name'  => 'text_align',
			'value'       => array(
				'Choose the text alignment'     => '',
				__( 'Text at left', 'eduma' )   => 'text-left',
				__( 'Text at center', 'eduma' ) => 'text-center',
				__( 'Text at right', 'eduma' )  => 'text-right',
			),
		),
		//Animation
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'eduma' ),
			'param_name'  => 'css_animation',
			'admin_label' => false,
			'value'       => array(
				__( 'No', 'eduma' )                 => '',
				__( 'Top to bottom', 'eduma' )      => 'top-to-bottom',
				__( 'Bottom to top', 'eduma' )      => 'bottom-to-top',
				__( 'Left to right', 'eduma' )      => 'left-to-right',
				__( 'Right to left', 'eduma' )      => 'right-to-left',
				__( 'Appear from center', 'eduma' ) => 'appear'
			),
			'description' => esc_html__( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'eduma' )
		),

        // Extra class
        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'Extra class', 'eduma' ),
            'param_name'  => 'el_class',
            'value'       => '',
            'description' => esc_html__( 'Add extra class name that will be applied to the icon box, and you can use this class for your customizations.', 'eduma' ),
        ),
	)
) );