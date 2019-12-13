<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Countdown_Box_El extends Widget_Base {

	public function get_name() {
		return 'thim-countdown-box';
	}

	public function get_title() {
		return esc_html__( 'Thim: Countdown Box', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-countdown-box';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		wp_enqueue_script( 'jquery-classycountdown', THIM_URI . 'inc/widgets/countdown-box/js/jquery.classycountdown.js', array( 'jquery' ), null );
		wp_enqueue_script( 'jquery-throttle', THIM_URI . 'inc/widgets/countdown-box/js/jquery.throttle.js', array( 'jquery' ), null );
		wp_enqueue_script( 'jquery-knob', THIM_URI . 'inc/widgets/countdown-box/js/jquery.knob.js', array( 'jquery' ), null );

		$this->start_controls_section(
			'config',
			[
				'label' => __( 'Countdown', 'eduma' )
			]
		);

		$this->add_control(
			'text_days',
			[
				'label'       => __( 'Text Days', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false
			]
		);

		$this->add_control(
			'text_hours',
			[
				'label'       => __( 'Text Hours', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false
			]
		);

		$this->add_control(
			'text_minutes',
			[
				'label'       => __( 'Text Minutes', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false
			]
		);

		$this->add_control(
			'text_seconds',
			[
				'label'       => __( 'Text Seconds', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false
			]
		);

		$this->add_control(
			'countdown_due_time',
			[
				'label'       => esc_html__( 'Countdown Due Date', 'eduma' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => date( "Y-m-d", strtotime( "+ 1 day" ) ),
				'description' => esc_html__( 'Set the due date and time', 'eduma' ),
				'separator'   => 'after'
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base'         => esc_html__( 'Default', 'eduma' ),
					'pie'          => esc_html__( 'Pie', 'eduma' ),
					'pie-gradient' => esc_html__( 'Pie Gradient', 'eduma' )
				],
				'default' => 'base',
			]
		);

		$this->add_control(
			'style_color',
			[
				'label'   => __( 'Style', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'white' => esc_html__( 'White', 'eduma' ),
					'black' => esc_html__( 'Black', 'eduma' )
				],
				'default' => 'white',
			]
		);

		$this->add_control(
			'text_align',
			[
				'label'   => __( 'Text Alignment', 'eduma' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'text-left'   => [
						'title' => __( 'Left', 'eduma' ),
						'icon'  => 'fa fa-align-left',
					],
					'text-center' => [
						'title' => __( 'Center', 'eduma' ),
						'icon'  => 'fa fa-align-center',
					],
					'text-right'  => [
						'title' => __( 'Right', 'eduma' ),
						'icon'  => 'fa fa-align-right',
					]
				],
				'default' => 'text-left',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'text_days'    => $settings['text_days'],
			'text_hours'   => $settings['text_hours'],
			'text_minutes' => $settings['text_minutes'],
			'text_seconds' => $settings['text_seconds'],
			'due-time'     => $settings['countdown_due_time'],
			'layout'       => $settings['layout'],
			'style_color'  => $settings['style_color'],
			'text_align'   => $settings['text_align'],
		);

		thim_get_widget_template( $this->get_base(), array( 'instance' => $instance ), $settings['layout'] );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Countdown_Box_El() );