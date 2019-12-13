<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Heading_El extends Widget_Base {

	public function get_name() {
		return 'thim-heading';
	}

	public function get_title() {
		return esc_html__( 'Thim: Heading', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-heading';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Content', 'eduma' )
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true
			]
		);

		$this->add_control(
			'title_uppercase',
			[
				'label'        => __( 'Title Uppercase?', 'eduma' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'uppercase',
				'default'      => '',
				/*'selectors'    => [
					'{{WRAPPER}} .sc_heading > h3' => 'text-transform: {{VALUE}};',
				],*/
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => esc_html__( 'HTML Tag', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''   => esc_html__( 'Select tag', 'eduma' ),
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
			'sub_heading',
			[
				'label'       => esc_html__( 'Sub Title', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'clone_title',
			[
				'label'   => esc_html__( 'Clone Title?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->add_control(
			'line',
			[
				'label'   => esc_html__( 'Show Separator?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'text_align',
			[
				'label'   => esc_html__( 'Text Alignment', 'eduma' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'text-left'   => [
						'title' => esc_html__( 'Left', 'eduma' ),
						'icon'  => 'fa fa-align-left',
					],
					'text-center' => [
						'title' => esc_html__( 'Center', 'eduma' ),
						'icon'  => 'fa fa-align-center',
					],
					'text-right'  => [
						'title' => esc_html__( 'Right', 'eduma' ),
						'icon'  => 'fa fa-align-right',
					]
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_settings',
			[
				'label' => esc_html__( 'Style', 'eduma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'textcolor',
			[
				'label' => esc_html__( 'Title Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'sub_heading_color',
			[
				'label'       => esc_html__( 'Sub Title Color', 'eduma' ),
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'type'        => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'bg_line',
			[
				'label' => esc_html__( 'Separator Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'css_animation',
			[
				'label'   => esc_html__( 'Animation', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''              => esc_html__( 'Choose...', 'eduma' ),
					'top-to-bottom' => esc_html__( 'Top to bottom', 'eduma' ),
					'bottom-to-top' => esc_html__( 'Bottom to top', 'eduma' ),
					'left-to-right' => esc_html__( 'Left to right', 'eduma' ),
					'right-to-left' => esc_html__( 'Right to left', 'eduma' ),
					'appear'        => esc_html__( 'Appear from center', 'eduma' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'font_heading',
			[
				'label'        => esc_html__( 'Custom Title Typography?', 'eduma' ),
				'type'         => Controls_Manager::SWITCHER,
				'separator'    => 'before',
				'return_value' => 'custom',
				'default'      => ''
			]
		);

		$this->add_control(
			'custom_font_size',
			[
				'label'     => esc_html__( 'Font Size', 'eduma' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '14',
				'condition' => [
					'font_heading' => [ 'custom' ]
				]
			]
		);

		$this->add_control(
			'custom_font_weight',
			[
				'label'     => esc_html__( 'Font Weight', 'eduma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
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
				'default'   => '',
				'condition' => [
					'font_heading' => [ 'custom' ]
				]
			]
		);

		$this->add_control(
			'custom_font_style',
			[
				'label'     => esc_html__( 'Font Style', 'eduma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''        => esc_html__( 'Choose...', 'eduma' ),
					'italic'  => esc_html__( 'Italic', 'eduma' ),
					'oblique' => esc_html__( 'Oblique', 'eduma' ),
					'initial' => esc_html__( 'Initial', 'eduma' ),
					'inherit' => esc_html__( 'Inherit', 'eduma' ),
					'normal'  => esc_html__( 'Normal', 'eduma' ),
				],
				'default'   => '',
				'condition' => [
					'font_heading' => [ 'custom' ]
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'               => $settings['title'],
			'title_uppercase'     => $settings['title_uppercase'],
			'size'                => $settings['size'],
			'sub_heading'         => $settings['sub_heading'],
			'clone_title'         => $settings['clone_title'],
			'line'                => $settings['line'],
			'text_align'          => $settings['text_align'],
			'textcolor'           => $settings['textcolor'],
			'sub_heading_color'   => $settings['sub_heading_color'],
			'bg_line'             => $settings['bg_line'],
			'css_animation'       => $settings['css_animation'],
			'font_heading'        => $settings['font_heading'],
			'custom_font_heading' => array(
				'custom_font_size'   => $settings['custom_font_size'],
				'custom_font_weight' => $settings['custom_font_weight'],
				'custom_font_style'  => $settings['custom_font_style'],
			)
		);

		thim_get_widget_template( $this->get_base(), array( 'instance' => $instance ) );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Heading_El() );