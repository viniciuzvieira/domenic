<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Accordion_El extends Widget_Base {

	public function get_name() {
		return 'thim-accordion';
	}

	public function get_title() {
		return esc_html__( 'Thim: Accordion', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-accordion';
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
				'label' => esc_html__( 'Accordion', 'eduma' )
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'panel_title',
			[
				'label'       => esc_html__( 'Panel Title', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$repeater->add_control(
			'panel_body',
			[
				'label'       => esc_html__( 'Panel Body', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'panel',
			[
				'label'       => esc_html__( 'Panel List', 'eduma' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ panel_title }}}',
				'separator'   => 'before'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title' => $settings['title'],
			'panel' => $settings['panel'],
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance
		) );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Accordion_El() );
