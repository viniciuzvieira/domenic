<?php

class Thim_Icon_Box_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'icon-box',
			esc_html__( 'Thim: Icon Box', 'eduma' ),
			array(
				'description'   => esc_html__( 'Add icon box', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-icon-box'
			),
			array(),
			$this->get_list_field(),
			THIM_DIR . 'inc/widgets/icon-box/'
		);
	}

	function get_list_field() {
		$list_field = array(
			'title_group'     => array(
				'type'   => 'section',
				'label'  => esc_html__( 'Title Options', 'eduma' ),
				'hide'   => true,
				'fields' => array(
					'title'            => array(
						'type'                  => 'text',
						'label'                 => esc_html__( 'Title', 'eduma' ),
						"default"               => esc_html__( "This is an icon box.", 'eduma' ),
						"description"           => esc_html__( "Provide the title for this icon box.", 'eduma' ),
						'allow_html_formatting' => array(
							'i' => array(
								'class' => array()
							)
						)
					),
					'color_title'      => array(
						'type'  => 'color',
						'label' => esc_html__( 'Color Title', 'eduma' ),
					),
					'size'             => array(
						"type"        => "select",
						"label"       => esc_html__( "Size Heading", 'eduma' ),
						"default"     => "h3",
						"options"     => array(
							"h2" => esc_html__( "h2", 'eduma' ),
							"h3" => esc_html__( "h3", 'eduma' ),
							"h4" => esc_html__( "h4", 'eduma' ),
							"h5" => esc_html__( "h5", 'eduma' ),
							"h6" => esc_html__( "h6", 'eduma' )
						),
						"description" => esc_html__( "Select size heading.", 'eduma' )
					),
					'font_heading'     => array(
						"type"          => "select",
						"label"         => esc_html__( "Font Heading", 'eduma' ),
						"options"       => array(
							"default" => esc_html__( "Default", 'eduma' ),
							"custom"  => esc_html__( "Custom", 'eduma' )
						),
						"description"   => esc_html__( "Select Font heading.", 'eduma' ),
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'custom_font_heading' )
						)
					),
					'custom_heading'   => array(
						'type'          => 'section',
						'label'         => esc_html__( 'Custom Heading Option', 'eduma' ),
						'hide'          => true,
						'state_handler' => array(
							'custom_font_heading[custom]'  => array( 'show' ),
							'custom_font_heading[default]' => array( 'hide' ),
						),
						'fields'        => array(
							'custom_font_size'   => array(
								"type"        => "number",
								"label"       => esc_html__( "Font Size", 'eduma' ),
								"suffix"      => "px",
								"default"     => "14",
								"description" => esc_html__( "custom font size", 'eduma' ),
								"class"       => "color-mini"
							),
							'custom_font_weight' => array(
								"type"        => "select",
								"label"       => esc_html__( "Custom Font Weight", 'eduma' ),
								"class"       => "color-mini",
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
							),
							'custom_mg_top'      => array(
								"type"   => "number",
								"class"  => "color-mini",
								"label"  => esc_html__( "Margin Top", "eduma" ),
								"value"  => 0,
								"suffix" => "px",
							),
							'custom_mg_bt'       => array(
								"type"   => "number",
								"class"  => "color-mini",
								"label"  => esc_html__( "Margin Bottom Value", "eduma" ),
								"value"  => 0,
								"suffix" => "px",
							),
						)
					),
					'line_after_title' => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Show Separator', 'eduma' ),
						'default' => false
					),
				),
			),
			'desc_group'      => array(
				'type'   => 'section',
				'label'  => esc_html__( 'Description', 'eduma' ),
				'hide'   => true,
				'fields' => array(
					'content'              => array(
						"type"                  => "textarea",
						"label"                 => esc_html__( "Add description", 'eduma' ),
						"default"               => esc_html__( "Write a short description, that will describe the title or something informational and useful.", 'eduma' ),
						"description"           => esc_html__( "Provide the description for this icon box.", 'eduma' ),
						'allow_html_formatting' => true
					),
					'custom_font_size_des' => array(
						"type"        => "number",
						"label"       => esc_html__( "Custom Font Size", 'eduma' ),
						"suffix"      => "px",
						"default"     => "",
						"description" => esc_html__( "Custom font size", 'eduma' ),
						"class"       => "color-mini",
					),
					'custom_font_weight'   => array(
						"type"        => "select",
						"label"       => esc_html__( "Custom Font Weight", 'eduma' ),
						"class"       => "color-mini",
						"options"     => array(
							""     => esc_html__( "Normal", 'eduma' ),
							"bold" => esc_html__( "Bold", 'eduma' ),
							"100"  => esc_html__( "100", 'eduma' ),
							"200"  => esc_html__( "200", 'eduma' ),
							"300"  => esc_html__( "300", 'eduma' ),
							"400"  => esc_html__( "400", 'eduma' ),
							"500"  => esc_html__( "500", 'eduma' ),
							"600"  => esc_html__( "600", 'eduma' ),
							"700"  => esc_html__( "700", 'eduma' ),
							"800"  => esc_html__( "800", 'eduma' ),
							"900"  => esc_html__( "900", 'eduma' )
						),
						"description" => esc_html__( "Select Custom Font Weight", 'eduma' ),
					),
					'color_description'    => array(
						"type"  => "color",
						"label" => esc_html__( "Color Description", 'eduma' ),
						"class" => "color-mini",
					),
				),
			),
			'read_more_group' => array(
				'type'   => 'section',
				'label'  => esc_html__( 'Link Icon Box', 'eduma' ),
				'hide'   => true,
				'fields' => array(
					// Add link to existing content or to another resource
					'link'                   => array(
						"type"        => "text",
						"label"       => esc_html__( "Add Link", 'eduma' ),
						"description" => esc_html__( "Provide the link that will be applied to this icon box.", 'eduma' ),
						'allow_html_formatting' => true
					),
					'target'                 => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Target Link', 'eduma' ),
						'default' => '_self',
						'options' => array(
							'_blank'  => esc_html__( 'Blank', 'eduma' ),
							'_self'   => esc_html__( 'Self', 'eduma' ),
							'_parent' => esc_html__( 'Parent', 'eduma' ),
						)
					),
					// Select link option - to box or with read more text
					'read_more'              => array(
						"type"          => "select",
						"label"         => esc_html__( "Apply link to:", 'eduma' ),
						"options"       => array(
							"complete_box" => esc_html__( "Complete Box", 'eduma' ),
							"title"        => esc_html__( "Box Title", 'eduma' ),
							"more"         => esc_html__( "Display Read More", 'eduma' )
						),
						"description"   => esc_html__( "Select whether to use color for icon or not.", 'eduma' ),
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'read_more_op' )
						)
					),
					'link_to_icon'           => array(
						'type'    => 'radio',
						'label'   => esc_html__( 'Show Link To Icon', 'eduma' ),
						'default' => 'no',
						'options' => array(
							'no'  => esc_html__( 'No', 'eduma' ),
							'yes' => esc_html__( 'Yes', 'eduma' ),
						)
					),
					// Link to traditional read more
					'button_read_more_group' => array(
						'type'          => 'section',
						'label'         => esc_html__( 'Option Button Read More', 'eduma' ),
						'hide'          => true,
						'state_handler' => array(
							'read_more_op[more]'         => array( 'show' ),
							'read_more_op[complete_box]' => array( 'hide' ),
							'read_more_op[title]'        => array( 'hide' ),
						),
						'fields'        => array(
							'read_text'                  => array(
								"type"        => "text",
								"label"       => esc_html__( "Read More Text", 'eduma' ),
								"default"     => esc_html__( "Read More", 'eduma' ),
								"description" => esc_html__( "Customize the read more text.", 'eduma' ),
							),
							'read_more_text_color'       => array(
								"type"        => "color",
								"class"       => "",
								"label"       => esc_html__( "Text Color Read More", 'eduma' ),
								"description" => esc_html__( "Select whether to use text color for Read More Text or default.", 'eduma' ),
								"class"       => "color-mini",
							),
							'border_read_more_text'      => array(
								"type"        => "color",
								"label"       => esc_html__( "Border Color Read More Text:", 'eduma' ),
								"description" => esc_html__( "Select whether to use border color for Read More Text or none.", 'eduma' ),
								"class"       => "color-mini",
							),
							'bg_read_more_text'          => array(
								"type"        => "color",
								"class"       => "mini",
								"label"       => esc_html__( "Background Color Read More Text:", 'eduma' ),
								"description" => esc_html__( "Select whether to use background color for Read More Text or default.", 'eduma' ),
								"class"       => "color-mini",
							),
							'read_more_text_color_hover' => array(
								"type"        => "color",
								"class"       => "",
								"label"       => esc_html__( "Text Hover Color Read More", 'eduma' ),
								"description" => esc_html__( "Select whether to use text color for Read More Text or default.", 'eduma' ),
								"class"       => "color-mini",
							),

							'bg_read_more_text_hover' => array(
								"type"        => "color",
								"label"       => esc_html__( "Background Hover Color Read More Text:", 'eduma' ),
								"description" => esc_html__( "Select whether to use background color when hover Read More Text or default.", 'eduma' ),
								"class"       => "color-mini",
							),

						)
					),
				),
			),
			'icon_type'       => array(
				"type"          => "select",
				"class"         => "",
				"label"         => esc_html__( "Icon to display:", 'eduma' ),
				"default"       => "font-awesome",
				"options"       => array(
					"font-awesome"  => esc_html__( "Font Awesome Icon", 'eduma' ),
					"font-7-stroke" => esc_html__( "Font 7 stroke Icon", 'eduma' ),
                    "font-flaticon" => esc_html__( "Font Flat Icon", 'eduma' ),
					"custom"        => esc_html__( "Custom Image", 'eduma' ),
				),
				'state_emitter' => array(
					'callback' => 'select',
					'args'     => array( 'icon_type_op' )
				)
			),

			'font_7_stroke_group' => array(
				'type'          => 'section',
				'label'         => esc_html__( 'Font 7 Stroke Icon', 'eduma' ),
				'hide'          => true,
				'state_handler' => array(
					'icon_type_op[font-awesome]'  => array( 'hide' ),
                    'icon_type_op[font-flaticon]'  => array( 'hide' ),
					'icon_type_op[custom]'        => array( 'hide' ),
					'icon_type_op[font-7-stroke]' => array( 'show' ),
				),
				'fields'        => array(
					'icon'      => array(
						"type"        => "icon-7-stroke",
						"class"       => "",
						"label"       => esc_html__( "Select Icon:", 'eduma' ),
						"description" => esc_html__( "Select the icon from the list.", 'eduma' ),
						"class_name"  => 'font-7-stroke',
					),
					// Resize the icon
					'icon_size' => array(
						"type"        => "number",
						"class"       => "",
						"label"       => esc_html__( "Icon Font Size ", 'eduma' ),
						"suffix"      => "px",
						"default"     => "14",
						"description" => esc_html__( "Select the icon font size.", 'eduma' ),
						"class_name"  => 'font-7-stroke'
					),
				),
			),
			'font_awesome_group'  => array(
				'type'          => 'section',
				'label'         => esc_html__( 'Font Awesome Icon', 'eduma' ),
				'hide'          => true,
				'state_handler' => array(
					'icon_type_op[font-awesome]'  => array( 'show' ),
					'icon_type_op[custom]'        => array( 'hide' ),
					'icon_type_op[font-7-stroke]' => array( 'hide' ),
                    'icon_type_op[font-flaticon]'  => array( 'hide' ),
				),
				'fields'        => array(
					'icon'      => array(
						"type"        => "icon",
						"class"       => "",
						"label"       => esc_html__( "Select Icon:", 'eduma' ),
						"description" => esc_html__( "Select the icon from the list.", 'eduma' ),
						"class_name"  => 'font-awesome',
					),
					// Resize the icon
					'icon_size' => array(
						"type"        => "number",
						"class"       => "",
						"label"       => esc_html__( "Icon Font Size ", 'eduma' ),
						"suffix"      => "px",
						"default"     => "14",
						"description" => esc_html__( "Select the icon font size.", 'eduma' ),
						"class_name"  => 'font-awesome'
					),
				),
			),
            'font_flaticon_group' => array(
                'type'          => 'section',
                'label'         => esc_html__( 'Font Flat Icon', 'eduma' ),
                'hide'          => true,
                'state_handler' => array(
                    'icon_type_op[font-awesome]'  => array( 'hide' ),
                    'icon_type_op[custom]'        => array( 'hide' ),
                    'icon_type_op[font-7-stroke]' => array( 'hide' ),
                    'icon_type_op[font-flaticon]'  => array( 'show' ),
                ),
                'fields'        => array(
                    'icon'      => array(
                        "type"        => "icon-youniverse",
                        "class"       => "",
                        "label"       => esc_html__( "Select Icon:", 'eduma' ),
                        "description" => esc_html__( "Select the icon from the list.", 'eduma' ),
                        "class_name"  => 'font-flaticon',
                    ),
                    // Resize the icon
                    'icon_size' => array(
                        "type"        => "number",
                        "class"       => "",
                        "label"       => esc_html__( "Icon Font Size ", 'eduma' ),
                        "suffix"      => "px",
                        "default"     => "14",
                        "description" => esc_html__( "Select the icon font size.", 'eduma' ),
                        "class_name"  => 'font-flaticon'
                    ),
                ),
            ),
			'font_image_group'    => array(
				'type'          => 'section',
				'label'         => esc_html__( 'Custom Image Icon', 'eduma' ),
				'hide'          => true,
				'state_handler' => array(
					'icon_type_op[font-awesome]'  => array( 'hide' ),
					'icon_type_op[custom]'        => array( 'show' ),
					'icon_type_op[font-7-stroke]' => array( 'hide' ),
                    'icon_type_op[font-flaticon]'  => array( 'hide' ),
				),
				'fields'        => array(
					// Play with icon selector
					'icon_img' => array(
						"type"        => "media",
						"label"       => esc_html__( "Upload Image Icon:", 'eduma' ),
						"description" => esc_html__( "Upload the custom image icon.", 'eduma' ),
						"class_name"  => 'custom',
					),
				),
			),
			// // Resize the icon
			'width_icon_box'      => array(
				"type"    => "number",
				"class"   => "",
				"default" => "100",
				"label"   => esc_html__( "Width Box Icon", 'eduma' ),
				"suffix"  => "px",
			),
            'height_icon_box'      => array(
                "type"    => "number",
                "class"   => "",
                "label"   => esc_html__( "Height Box Icon", 'eduma' ),
                "suffix"  => "px",
            ),
			'color_group'         => array(
				'type'   => 'section',
				'label'  => esc_html__( 'Color Options', 'eduma' ),
				'hide'   => true,
				'fields' => array(
					// Customize Icon Color
					'icon_color'              => array(
						"type"        => "color",
						"class"       => "color-mini",
						"label"       => esc_html__( "Select Icon Color:", 'eduma' ),
						"description" => esc_html__( "Select the icon color.", 'eduma' ),
					),
					'icon_border_color'       => array(
						"type"        => "color",
						"label"       => esc_html__( "Icon Border Color:", 'eduma' ),
						"description" => esc_html__( "Select the color for icon border.", 'eduma' ),
						"class"       => "color-mini",
					),
					'icon_bg_color'           => array(
						"type"        => "color",
						"label"       => esc_html__( "Icon Background Color:", 'eduma' ),
						"description" => esc_html__( "Select the color for icon background.", 'eduma' ),
						"class"       => "color-mini",
					),
					'icon_hover_color'        => array(
						"type"        => "color",
						"label"       => esc_html__( "Hover Icon Color:", 'eduma' ),
						"description" => esc_html__( "Select the color hover for icon.", 'eduma' ),
						"class"       => "color-mini",
					),
					'icon_border_color_hover' => array(
						"type"        => "color",
						"label"       => esc_html__( "Hover Icon Border Color:", 'eduma' ),
						"description" => esc_html__( "Select the color hover for icon border.", 'eduma' ),
						"class"       => "color-mini",
					),
					// Give some background to icon
					'icon_bg_color_hover'     => array(
						"type"        => "color",
						"label"       => esc_html__( "Hover Icon Background Color:", 'eduma' ),
						"description" => esc_html__( "Select the color hover for icon background .", 'eduma' ),
						"class"       => "color-mini",
					),
				)
			),
			'layout_group'        => array(
				'type'   => 'section',
				'label'  => esc_html__( 'Layout Options', 'eduma' ),
				'hide'   => true,
				'fields' => array(
					'box_icon_style' => array(
						"type"    => "select",
						"class"   => "",
						"label"   => esc_html__( "Icon Shape", 'eduma' ),
						"options" => array(
							""       => esc_html__( "None", 'eduma' ),
							"circle" => esc_html__( "Circle", 'eduma' ),
						),
						"std"     => "circle",
					),
					'pos'            => array(
						"type"        => "select",
						"class"       => "",
						"label"       => esc_html__( "Box Style:", 'eduma' ),
						"default"     => "top",
						"options"     => array(
							"left"  => esc_html__( "Icon at Left", 'eduma' ),
							"right" => esc_html__( "Icon at Right", 'eduma' ),
							"top"   => esc_html__( "Icon at Top", 'eduma' ),
						),
						"description" => esc_html__( "Select icon position. Icon box style will be changed according to the icon position.", 'eduma' ),
					),
					'text_align_sc'  => array(
						"type"    => "select",
						"class"   => "",
						"label"   => esc_html__( "Text Align Shortcode:", 'eduma' ),
						"options" => array(
							"text-left"   => esc_html__( "Text Left", 'eduma' ),
							"text-right"  => esc_html__( "Text Right", 'eduma' ),
							"text-center" => esc_html__( "Text Center", 'eduma' ),
						)
					),
					'style_box'      => array(
						"type"    => "select",
						"label"   => esc_html__( "Type Icon Box", 'eduma' ),
						"options" => array(
							""             => esc_html__( "Default", 'eduma' ),
							"overlay"      => esc_html__( "Overlay", 'eduma' ),
							"contact_info" => esc_html__( "Contact Info", 'eduma' ),
							"image_box" => esc_html__( "Image Box", 'eduma' ),
						),
					)
				),
			),

			'widget_background' => array(
				"type"          => "select",
				"label"         => esc_html__( "Widget Background", 'eduma' ),
				"default"       => "none",
				"options"       => array(
					"none"     => esc_html__( "None", 'eduma' ),
                    "bg_color"     => esc_html__( "Background Color", 'eduma' ),
					"bg_video" => esc_html__( "Video Background", 'eduma' ),
				),
				'state_emitter' => array(
					'callback' => 'select',
					'args'     => array( 'bg_video_style' )
				)
			),
            'bg_box_color'        => array(
                'type'        => 'color',
                'label'       => esc_html__( 'Background Color:', 'eduma' ),
                'description' => esc_html__( 'Select the color background for box.', 'eduma' ),
                'state_handler' => array(
                    'bg_video_style[bg_video]' => array( 'hide' ),
                    'bg_video_style[none]'     => array( 'hide' ),
                    'bg_video_style[bg_color]'     => array( 'show' ),
                )
            ),
			'self_video'        => array(
				'type'          => 'media',
				'fallback'      => true,
				'label'         => esc_html__( 'Select video', 'eduma' ),
				'description'   => esc_html__( "Select an uploaded video in mp4 format. Other formats, such as webm and ogv will work in some browsers. You can use an online service such as <a href='http://video.online-convert.com/convert-to-mp4' target='_blank'>online-convert.com</a> to convert your videos to mp4.", 'eduma' ),
				'default'       => '',
				'library'       => 'video',
				'state_handler' => array(
					'bg_video_style[bg_video]' => array( 'show' ),
					'bg_video_style[none]'     => array( 'hide' ),
                    'bg_video_style[bg_color]'     => array( 'hide' ),
				)
			),
			'self_poster'       => array(
				'type'          => 'media',
				'label'         => esc_html__( 'Select cover image', 'eduma' ),
				'default'       => '',
				'library'       => 'image',
				'state_handler' => array(
					'bg_video_style[bg_video]' => array( 'show' ),
					'bg_video_style[none]'     => array( 'hide' ),
                    'bg_video_style[bg_color]'     => array( 'hide' ),
				)
			),
			'css_animation'     => array(
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
			)
		);

		return $list_field;
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

function thim_icon_box_register_widget() {
	register_widget( 'Thim_Icon_Box_Widget' );
}

add_action( 'widgets_init', 'thim_icon_box_register_widget' );