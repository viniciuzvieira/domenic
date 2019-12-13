<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Counters_Box_El extends Widget_Base {

	public function get_name() {
		return 'thim-counters-box';
	}

	public function get_title() {
		return esc_html__( 'Thim: Counters Box', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-counters-box';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Counters Box', 'eduma' )
			]
		);

		$this->add_control(
			'counters_label',
			[
				'label'       => esc_html__( 'Counters Label', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'counters_value',
			[
				'label'   => esc_html__( 'Counters Value', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 20,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'text_number',
			[
				'label'       => esc_html__( 'Text Number', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'view_more_text',
			[
				'label'       => esc_html__( 'View More Text', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'view_more_link',
			[
				'label'         => esc_html__( 'View More Link', 'plugin-domain' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'eduma' ),
				'show_external' => false,
				'default'       => [
					'url' => ''
				]
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Select Icon:', 'eduma' ),
				'type'        => Controls_Manager::ICON,
				'placeholder' => esc_html__( 'Choose...', 'eduma' )
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Counter Style', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					"home-page"     => esc_html__( "Home Page", 'eduma' ),
					"about-us"      => esc_html__( "Page About Us", 'eduma' ),
					"number-left"   => esc_html__( "Number Left", 'eduma' ),
					"text-gradient" => esc_html__( "Text Gradient", 'eduma' )
				],
				'default' => 'home-page'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style-tab',
			[
				'label' => esc_html__( 'Style', 'eduma' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Border Color Icon', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'counter_color',
			[
				'label' => esc_html__( 'Counters Icon Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'counters_label'   => $settings['counters_label'],
			'counters_value'   => $settings['counters_value'],
			'text_number'      => $settings['text_number'],
			'view_more_text'   => $settings['view_more_text'],
			'view_more_link'   => $settings['view_more_link']['url'],
			'icon'             => $settings['icon'],
			'style'            => $settings['style'],
			'background_color' => $settings['background_color'],
			'border_color'     => $settings['border_color'],
			'counter_color'    => $settings['counter_color'],
			'css_animation'    => ''
		);

		$args                 = array();
		$args['before_title'] = '<h3 class="widget-title">';
		$args['after_title']  = '</h3>';

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance,
			'args'     => $args
		) );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Counters_Box_El() );