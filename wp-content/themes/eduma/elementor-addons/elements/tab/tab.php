<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Tab_El extends Widget_Base {

	public function get_name() {
		return 'thim-tab';
	}

	public function get_title() {
		return esc_html__( 'Thim: Tab', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-tab-event';
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
				'label' => esc_html__( 'Tabs', 'eduma' )
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base'       => esc_html__( 'Default', 'eduma' ),
					'step'         => esc_html__( 'Step', 'eduma' )
				],
				'default' => 'base'
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Tab Title', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$repeater->add_control(
			'bg_title',
			[
				'label' => esc_html__( 'Background Title', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'content',
			[
				'label'       => esc_html__( 'Content', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'         => esc_html__( 'Link', 'eduma' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'eduma' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$this->add_control(
			'tab',
			[
				'label'       => esc_html__( 'Tab List', 'eduma' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'separator'   => 'before'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'layout' => $settings['layout'],
			'tab' => $settings['tab'],
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance
		), $settings['layout'] );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Tab_El() );
