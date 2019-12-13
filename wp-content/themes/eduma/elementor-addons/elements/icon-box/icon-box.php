<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Icon_Box_El extends Widget_Base {

	public function get_name() {
		return 'thim-icon-box';
	}

	public function get_title() {
		return esc_html__( 'Thim: Icon Box', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-icon-box';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'title_group',
			[
				'label' => __( 'Title', 'eduma' )
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => __( 'Size Heading', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h2' => esc_html__( 'h2', 'eduma' ),
					'h3' => esc_html__( 'h3', 'eduma' ),
					'h4' => esc_html__( 'h4', 'eduma' ),
					'h5' => esc_html__( 'h5', 'eduma' ),
					'h6' => esc_html__( 'h6', 'eduma' ),
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'font_heading',
			[
				'label'        => __( 'Custom Font Heading?', 'eduma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'eduma' ),
				'label_off'    => __( 'No', 'eduma' ),
				'return_value' => 'custom',
				'default'      => '',
			]
		);

		$this->add_control(
			'custom_font_size_heading',
			[
				'label'     => __( 'Font Size (px)', 'eduma' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 14,
				'min'       => 1,
				'step'      => 1,
				'condition' => [
					'font_heading' => [ 'custom' ]
				]
			]
		);

		$this->add_control(
			'custom_font_weight_heading',
			[
				'label'     => __( 'Custom Font Weight', 'eduma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					"normal" => esc_html__( "Normal", 'eduma' ),
					"bold"   => esc_html__( "Bold", 'eduma' ),
					'100'    => esc_html__( '100', 'eduma' ),
					'200'    => esc_html__( '200', 'eduma' ),
					'300'    => esc_html__( '300', 'eduma' ),
					'400'    => esc_html__( '400', 'eduma' ),
					'500'    => esc_html__( '500', 'eduma' ),
					'600'    => esc_html__( '600', 'eduma' ),
					'700'    => esc_html__( '700', 'eduma' ),
					'800'    => esc_html__( '800', 'eduma' ),
					'900'    => esc_html__( '900', 'eduma' ),
				],
				'default'   => 'normal',
				'condition' => [
					'font_heading' => [ 'custom' ]
				]
			]
		);

        $this->add_control(
            'custom_mg_top',
            [
                'label'   => esc_html__( 'Margin Top (px)', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '',
                'min'     => 0,
                'step'    => 1
            ]
        );

        $this->add_control(
            'custom_mg_bt',
            [
                'label'   => esc_html__( 'Margin Bottom (px)', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '',
                'min'     => 0,
                'step'    => 1
            ]
        );

		$this->add_control(
			'line_after_title',
			[
				'label'   => __( 'Show Separator?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'options' => [
					true  => __( 'Yes', 'eduma' ),
					false => __( 'No', 'eduma' ),
				],
				'default' => false
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'desc_group',
			[
				'label' => __( 'Description', 'eduma' ),
			]
		);

		$this->add_control(
			'content',
			[
				'label'       => __( 'Add Description', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);


		$this->add_control(
			'custom_font_size_des',
			[
				'label'   => __( 'Font Size (px)', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 14,
				'min'     => 0,
				'step'    => 1
			]
		);

		$this->add_control(
			'custom_font_weight_des',
			[
				'label'   => __( 'Font Weight', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''       => esc_html__( 'Choose...', 'eduma' ),
					'normal' => esc_html__( 'Normal', 'eduma' ),
					'bold'   => esc_html__( 'Bold', 'eduma' ),
					'100'    => esc_html__( '100', 'eduma' ),
					'200'    => esc_html__( '200', 'eduma' ),
					'300'    => esc_html__( '300', 'eduma' ),
					'400'    => esc_html__( '400', 'eduma' ),
					'500'    => esc_html__( '500', 'eduma' ),
					'600'    => esc_html__( '600', 'eduma' ),
					'700'    => esc_html__( '700', 'eduma' ),
					'800'    => esc_html__( '800', 'eduma' ),
					'900'    => esc_html__( '900', 'eduma' ),
				],
				'default' => ''
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'read_more_group',
			[
				'label' => __( 'Link', 'eduma' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'         => __( 'URL', 'eduma' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'eduma' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$this->add_control(
			'read_more',
			[
				'label'   => __( 'Apply to', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					"complete_box" => esc_html__( "Complete Box", 'eduma' ),
					"title"        => esc_html__( "Box Title", 'eduma' ),
					"more"         => esc_html__( "Display Read More", 'eduma' ),
				],
				'default' => 'complete_box'
			]
		);

		$this->add_control(
			'read_text',
			[
				'label'       => __( 'Read More Text', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'default'     => esc_html__( 'Read More', 'eduma' ),
				'label_block' => true,
				'condition'   => [
					'read_more' => [ 'more' ]
				]
			]
		);

		$this->add_control(
			'read_more_text_color',
			[
				'label'     => __( 'Text Color Read More', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'read_more' => [ 'more' ]
				]
			]
		);

		$this->add_control(
			'border_read_more_text',
			[
				'label'     => __( 'Border Color Read More Text:', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'read_more' => [ 'more' ]
				]
			]
		);

		$this->add_control(
			'bg_read_more_text',
			[
				'label'     => __( 'Background Color Read More Text:', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'read_more' => [ 'more' ]
				]
			]
		);

		$this->add_control(
			'read_more_text_color_hover',
			[
				'label'     => __( 'Text Hover Color Read More', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'read_more' => [ 'more' ]
				]
			]
		);

		$this->add_control(
			'bg_read_more_text_hover',
			[
				'label'     => __( 'Background Hover Color Read More Text:', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'read_more' => [ 'more' ]
				]
			]
		);

		$this->add_control(
			'link_to_icon',
			[
				'label'   => __( 'Show Link To Icon', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'options' => [
					'no'  => __( 'Yes', 'eduma' ),
					'yes' => __( 'No', 'eduma' ),
				],
				'default' => 'no'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_group',
			[
				'label' => __( 'Icon', 'eduma' ),
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label'   => __( 'Icon Type', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					"font-awesome"  => esc_html__( "Font Awesome Icon", 'eduma' ),
					"font-7-stroke" => esc_html__( "Font 7 stroke Icon", 'eduma' ),
					"font-flaticon" => esc_html__( "Font Flat Icon", 'eduma' ),
					"custom"        => esc_html__( "Custom Image", 'eduma' )
				],
				'default' => 'font-awesome'
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Select Icon:', 'eduma' ),
				'type'        => Controls_Manager::ICON,
				'placeholder' => esc_html__( 'Choose...', 'eduma' ),
				'condition'   => [
					'icon_type' => [ 'font-awesome' ]
				]
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'     => __( 'Icon Font Size (px)', 'eduma' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 14,
				'min'       => 0,
				'step'      => 1,
				'condition' => [
					'icon_type' => [ 'font-awesome' ]
				]
			]
		);

		$this->add_control(
			'flat_icon',
			[
				'label'       => esc_html__( 'Select Icon:', 'eduma' ),
				'type'        => Controls_Manager::ICON,
				'placeholder' => esc_html__( 'Choose...', 'eduma' ),
				'options'     => \Thim_Elementor_Extend_Icons::get_font_flaticon(),
				'exclude'     => array_keys(Control_Icon::get_icons()),
				'condition'   => [
					'icon_type' => [ 'font-flaticon' ]
				]
			]
		);

		$this->add_control(
			'flat_icon_size',
			[
				'label'     => __( 'Icon Font Size (px)', 'eduma' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 14,
				'min'       => 0,
				'step'      => 1,
				'condition' => [
					'icon_type' => [ 'font-flaticon' ]
				]
			]
		);

		$this->add_control(
			'stroke_icon',
			[
				'label'       => esc_html__( 'Select Icon:', 'eduma' ),
				'type'        => Controls_Manager::ICON,
				'placeholder' => esc_html__( 'Choose...', 'eduma' ),
				'options'     => \Thim_Elementor_Extend_Icons::get_font_7_stroke(),
				'exclude'     => array_keys(Control_Icon::get_icons()),
				'condition'   => [
					'icon_type' => [ 'font-7-stroke' ]
				]
			]
		);

		$this->add_control(
			'stroke_icon_size',
			[
				'label'     => __( 'Icon Font Size (px)', 'eduma' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 14,
				'min'       => 0,
				'step'      => 1,
				'condition' => [
					'icon_type' => [ 'font-7-stroke' ]
				]
			]
		);

		$this->add_control(
			'icon_img',
			[
				'label'     => esc_html__( 'Choose Image', 'eduma' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => [ 'custom' ]
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_group',
			[
				'label' => esc_html__( 'Layout Options', 'eduma' ),
			]
		);

		$this->add_control(
			'box_icon_style',
			[
				'label'   => esc_html__( 'Icon Shape', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					""       => esc_html__( "None", 'eduma' ),
					"circle" => esc_html__( "Circle", 'eduma' )
				],
				'default' => 'circle'
			]
		);

		$this->add_control(
			'pos',
			[
				'label'   => esc_html__( 'Box Style:', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					"left"  => esc_html__( "Icon at Left", 'eduma' ),
					"right" => esc_html__( "Icon at Right", 'eduma' ),
					"top"   => esc_html__( "Icon at Top", 'eduma' )
				],
				'default' => 'top'
			]
		);

		$this->add_control(
			'text_align_sc',
			[
				'label'   => esc_html__( 'Text Align Shortcode:', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					"text-left"   => esc_html__( "Text Left", 'eduma' ),
					"text-right"  => esc_html__( "Text Right", 'eduma' ),
					"text-center" => esc_html__( "Text Center", 'eduma' )
				],
				'default' => 'text-left'
			]
		);

		$this->add_control(
			'style_box',
			[
				'label'   => esc_html__( 'Type Icon Box', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					""             => esc_html__( "Default", 'eduma' ),
					"overlay"      => esc_html__( "Overlay", 'eduma' ),
					"contact_info" => esc_html__( "Contact Info", 'eduma' ),
					"image_box"    => esc_html__( "Image Box", 'eduma' ),
				],
				'default' => ''
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'color_group',
			[
				'label' => esc_html__( 'Style', 'eduma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Title Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'color_description',
			[
				'label' => __( 'Description Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color:', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label' => esc_html__( 'Icon Border Color:', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label' => esc_html__( 'Icon Background Color:', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => esc_html__( 'Hover Icon Color:', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icon_border_color_hover',
			[
				'label' => esc_html__( 'Hover Icon Border Color:', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'icon_bg_color_hover',
			[
				'label' => esc_html__( 'Hover Icon Background Color:', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'widget_background',
			[
				'label'     => esc_html__( 'Widget Background', 'eduma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					"none"     => esc_html__( "None", 'eduma' ),
					"bg_color" => esc_html__( "Background Color", 'eduma' ),
					"bg_video" => esc_html__( "Video Background", 'eduma' )
				],
				'default'   => 'none',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'bg_box_color',
			[
				'label'     => esc_html__( 'Background Color:', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'widget_background' => [ 'bg_color' ]
				]
			]
		);

		$this->add_control(
			'self_video',
			[
				'label'       => esc_html__( 'Select video', 'elementor' ),
				'description' => esc_html__( 'Select an uploaded video in mp4 format. Other formats, such as webm and ogv will work in some browsers. You can use an online service such as \'http://video.online-convert.com/convert-to-mp4\' to convert your videos to mp4.', 'eduma' ),
				'type'        => Controls_Manager::MEDIA,
				'media_type'  => 'video',
				'condition'   => [
					'widget_background' => [ 'bg_video' ]
				],
			]
		);

		$this->add_control(
			'self_poster',
			[
				'label'     => esc_html__( 'Select cover image', 'eduma' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'widget_background' => [ 'bg_video' ]
				]
			]
		);

		$this->add_control(
			'css_animation',
			[
				'label'     => esc_html__( 'CSS Animation', 'eduma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					""              => esc_html__( "No", 'eduma' ),
					"top-to-bottom" => esc_html__( "Top to bottom", 'eduma' ),
					"bottom-to-top" => esc_html__( "Bottom to top", 'eduma' ),
					"left-to-right" => esc_html__( "Left to right", 'eduma' ),
					"right-to-left" => esc_html__( "Right to left", 'eduma' ),
					"appear"        => esc_html__( "Appear from center", 'eduma' )
				],
				'default'   => '',
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dimension',
			[
				'label' => esc_html__( 'Dimension', 'eduma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'width_icon_box',
			[
				'label'      => esc_html__( 'Width Box Icon (px)', 'eduma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				]
			]
		);

		$this->add_control(
			'height_icon_box',
			[
				'label'      => esc_html__( 'Height Box Icon (px)', 'eduma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array();

		$instance['title_group'] = array(
			'title'            => $settings['title'],
			'color_title'      => $settings['color_title'],
			'size'             => $settings['size'],
			'font_heading'     => $settings['font_heading'],
			'custom_heading'   => array(
				'custom_font_size'   => $settings['custom_font_size_heading'],
				'custom_font_weight' => $settings['custom_font_weight_heading'],
				'custom_mg_bt'       => $settings['custom_mg_bt'],
				'custom_mg_top'      => $settings['custom_mg_top']
			),
			'line_after_title' => $settings['line_after_title']
		);

		$instance['desc_group'] = array(
			'content'              => $settings['content'],
			'custom_font_size_des' => $settings['custom_font_size_des'],
			'custom_font_weight'   => $settings['custom_font_weight_des'],
			'color_description'    => $settings['color_description']
		);

		$instance['read_more_group'] = array(
			'link'                   => $settings['link']['url'],
			'target'                 => ! empty( $settings['link']['is_external'] ) ? '_blank' : '_self',
			'read_more'              => $settings['read_more'],
			'link_to_icon'           => $settings['link_to_icon'],
			'button_read_more_group' => array(
				'read_text'                  => $settings['read_text'],
				'read_more_text_color'       => $settings['read_more_text_color'],
				'border_read_more_text'      => $settings['border_read_more_text'],
				'bg_read_more_text'          => $settings['bg_read_more_text'],
				'read_more_text_color_hover' => $settings['read_more_text_color_hover'],
				'bg_read_more_text_hover'    => $settings['bg_read_more_text_hover']
			)
		);

		$instance['icon_type']                    = $settings['icon_type'];
		$instance['font_awesome_group']           = array(
			'icon'      => $settings['icon'],
			'icon_size' => $settings['icon_size']
		);
		$instance['font_flaticon_group']           = array(
			'icon'      => $settings['flat_icon'],
			'icon_size' => $settings['flat_icon_size']
		);
		$instance['font_7_stroke_group']           = array(
			'icon'      => $settings['stroke_icon'],
			'icon_size' => $settings['stroke_icon_size']
		);
		$instance['font_image_group']['icon_img'] = $settings['icon_img']['id'];

		$instance['width_icon_box']  = $settings['width_icon_box']['size'];
		$instance['height_icon_box'] = $settings['height_icon_box']['size'];

		$instance['color_group'] = array(
			'icon_color'              => $settings['icon_color'],
			'icon_border_color'       => $settings['icon_border_color'],
			'icon_bg_color'           => $settings['icon_bg_color'],
			'icon_hover_color'        => $settings['icon_hover_color'],
			'icon_border_color_hover' => $settings['icon_border_color_hover'],
			'icon_bg_color_hover'     => $settings['icon_bg_color_hover']
		);

		$instance['layout_group'] = array(
			'box_icon_style' => $settings['box_icon_style'],
			'pos'            => $settings['pos'],
			'text_align_sc'  => $settings['text_align_sc'],
			'style_box'      => $settings['style_box']
		);

		$instance['widget_background'] = $settings['widget_background'];
		$instance['bg_box_color']      = $settings['bg_box_color'];
		$instance['self_video']        = $settings['self_video'];
		$instance['self_poster']       = $settings['self_poster'];
		$instance['css_animation']     = $settings['css_animation'];

		thim_get_widget_template( $this->get_base(), array( 'instance' => $instance ) );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Icon_Box_El() );
