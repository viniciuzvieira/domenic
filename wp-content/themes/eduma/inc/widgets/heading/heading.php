<?php
if (!class_exists('Thim_Heading_Widget')) {
	class Thim_Heading_Widget extends Thim_Widget {

		function __construct() {
			parent::__construct(
				'heading',
				esc_html__( 'Thim: Heading', 'eduma' ),
				array(
					'description'   => esc_html__( 'Add heading text', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-heading'
				),
				array(),
				array(
					'title'               => array(
						'type'                  => 'text',
						'label'                 => esc_html__( 'Heading Text', 'eduma' ),
						'default'               => esc_html__( "Default value", 'eduma' ),
						'allow_html_formatting' => true
					),
                    'title_uppercase'                => array(
                        'type'    => 'checkbox',
                        'label'   => esc_html__( 'Title Uppercase?', 'eduma' ),
                        'default' => true
                    ),
					'textcolor'           => array(
						'type'  => 'color',
						'label' => esc_html__( 'Text Heading color', 'eduma' ),
					),
					'size'                => array(
						"type"    => "select",
						"label"   => esc_html__( "Size Heading", 'eduma' ),
						"options" => array(
							"h2" => esc_html__( "h2", 'eduma' ),
							"h3" => esc_html__( "h3", 'eduma' ),
							"h4" => esc_html__( "h4", 'eduma' ),
							"h5" => esc_html__( "h5", 'eduma' ),
							"h6" => esc_html__( "h6", 'eduma' )
						),
						"default" => "h3"
					),
					'sub_heading'         => array(
						'type'  => 'textarea',
						'label' => esc_html__( 'Sub Heading', 'eduma' ),
						'allow_html_formatting' => true
					),
					'sub_heading_color'   => array(
						'type'  => 'color',
						'label' => esc_html__( 'Sub heading color', 'eduma' ),
					),
					'clone_title'                => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Clone Title', 'eduma' ),
						'default' => false
					),
					'font_heading'        => array(
						"type"          => "select",
						"label"         => esc_html__( "Font Heading", 'eduma' ),
						"default"       => "default",
						"options"       => array(
							"default" => esc_html__( "Default", 'eduma' ),
							"custom"  => esc_html__( "Custom", 'eduma' )
						),
						"description"   => esc_html__( "Select Font heading.", 'eduma' ),
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'font_heading_type' )
						)
					),
					'custom_font_heading' => array(
						'type'          => 'section',
						'label'         => esc_html__( 'Custom Font Heading', 'eduma' ),
						'hide'          => true,
						'state_handler' => array(
							'font_heading_type[custom]'  => array( 'show' ),
							'font_heading_type[default]' => array( 'hide' ),
						),
						'fields'        => array(
							'custom_font_size'   => array(
								"type"        => "number",
								"label"       => esc_html__( "Font Size", 'eduma' ),
								"suffix"      => "px",
								"default"     => "14",
								"description" => esc_html__( "custom font size", 'eduma' ),
								"class"       => "color-mini",
							),
							'custom_font_weight' => array(
								"type"        => "select",
								"label"       => esc_html__( "Custom Font Weight", 'eduma' ),
								"options"     => array(
									"normal" => esc_html__( "Normal", 'eduma' ),
									"bold"   => esc_html__( "Bold", 'eduma' ),
									"100"    => esc_html__( "100", 'eduma' ),
									"200"    => esc_html__( "200", 'eduma' ),
									"300"    => esc_html__( "300", 'eduma' ),
									"400"    => esc_html__( "400", 'eduma' ),
									"500"    => esc_html__( "500", 'eduma' ),
									"600"    => esc_html__( "600", 'eduma' ),
									"700"    => esc_html__( "700", 'eduma' ),
									"800"    => esc_html__( "800", 'eduma' ),
									"900"    => esc_html__( "900", 'eduma' )
								),
								"description" => esc_html__( "Select Custom Font Weight", 'eduma' ),
								"class"       => "color-mini",
							),
							'custom_font_style'  => array(
								"type"        => "select",
								"label"       => esc_html__( "Custom Font Style", 'eduma' ),
								"options"     => array(
									"inherit" => esc_html__( "inherit", 'eduma' ),
									"initial" => esc_html__( "initial", 'eduma' ),
									"italic"  => esc_html__( "italic", 'eduma' ),
									"normal"  => esc_html__( "normal", 'eduma' ),
									"oblique" => esc_html__( "oblique", 'eduma' )
								),
								"description" => esc_html__( "Select Custom Font Style", 'eduma' ),
								"class"       => "color-mini",
							),
						),
					),
					'line'                => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Show Separator', 'eduma' ),
						'default' => true
					),
					'bg_line'             => array(
						'type'  => 'color',
						'label' => esc_html__( 'Color Line', 'eduma' ),
					),
					'text_align'          => array(
						"type"    => "select",
						"label"   => esc_html__( "Text Align", 'eduma' ),
						"options" => array(
							"text-left"   => esc_html__( "Text Left", 'eduma' ),
							"text-center" => esc_html__( "Text Center", 'eduma' ),
							"text-right"  => esc_html__( "Text Right", 'eduma' ),
						),
					),
					'css_animation'       => array(
						"type"    => "select",
						"label"   => esc_html__( "CSS Animation", 'eduma' ),
						"options" => array(
							""              => esc_html__( "No", 'eduma' ),
							"top-to-bottom" => esc_html__( "Top to bottom", 'eduma' ),
							"bottom-to-top" => esc_html__( "Bottom to top", 'eduma' ),
							"left-to-right" => esc_html__( "Left to right", 'eduma' ),
							"right-to-left" => esc_html__( "Right to left", 'eduma' ),
							"appear"        => esc_html__( "Appear from center", 'eduma' )
						),
					),
				),
				THIM_DIR . 'inc/widgets/heading/'
			);
		}

		/**
		 * Initialize the CTA widget
		 */

		function get_template_name( $instance ) {
			return 'base';
		}

		function get_style_name( $instance ) {
			return false;
		}

	}
}


function thim_heading_register_widget() {
	register_widget( 'Thim_Heading_Widget' );
}

add_action( 'widgets_init', 'thim_heading_register_widget' );