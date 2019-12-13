<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Course_Categories_El extends Widget_Base {

	public function get_name() {
		return 'thim-course-categories';
	}

	public function get_title() {
		return esc_html__( 'Thim: Course Categories', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-course-categories';
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
				'label' => __( 'Course Categories', 'eduma' )
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

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'slider'     => esc_html__( 'Slider', 'eduma' ),
					'base'       => esc_html__( 'List Categories', 'eduma' ),
					'tab-slider' => esc_html__( 'Tab Slider', 'eduma' )
				],
				'default' => 'base'
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Limit categories', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 15,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label'        => esc_html__( 'Show Pagination?', 'eduma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'eduma' ),
				'label_off'    => esc_html__( 'No', 'eduma' ),
				'return_value' => 'yes',
				'default'      => ''
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label'        => esc_html__( 'Show Navigation?', 'eduma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'eduma' ),
				'label_off'    => esc_html__( 'No', 'eduma' ),
				'return_value' => 'yes',
				'default'      => 'yes'
			]
		);

		$this->add_control(
			'item_visible',
			[
				'label'   => esc_html__( 'Items Visible', 'eduma' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => 1,
						'max'  => 8,
						'step' => 1
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				]
			]
		);

		$this->add_control(
			'auto_play',
			[
				'label'       => esc_html__( 'Auto play speed (in ms)', 'eduma' ),
				'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'step'        => 100
			]
		);

		$this->add_control(
			'list-options',
			[
				'label'     => esc_html__( 'List Categories Layout Options', 'eduma' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout' => [ 'base' ]
				)
			]
		);

		$this->add_control(
			'show_counts',
			[
				'label'     => esc_html__( 'Show Course Count?', 'eduma' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'layout' => [ 'base' ]
				)
			]
		);

		$this->add_control(
			'hierarchical',
			[
				'label'     => esc_html__( 'Show hierarchy?', 'eduma' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'layout' => [ 'base' ]
				)
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'          => $settings['title'],
			'layout'         => $settings['layout'],
			'slider-options' => array(
				'limit'              => $settings['limit'],
				'show_navigation'    => $settings['show_navigation'],
				'auto_play'          => $settings['auto_play'],
				'show_pagination'    => $settings['show_pagination'],
				'responsive-options' => array(
					'item_visible'               => $settings['item_visible']['size'],
					'item_small_desktop_visible' => 6,
					'item_tablet_visible'        => 4,
					'item_mobile_visible'        => 2
				)
			),
			'list-options'   => array(
				'show_counts'  => $settings['show_counts'],
				'hierarchical' => $settings['hierarchical']
			)
		);

		$args                 = array();
		$args['before_title'] = '<h3 class="widget-title">';
		$args['after_title']  = '</h3>';

		$layout = $settings['layout'];

		if ( thim_is_new_learnpress( '3.0' ) ) {
			$layout .= '-v3';
		} else if ( thim_is_new_learnpress( '2.0' ) ) {
			$layout .= '-v2';
		} else {
			$layout .= '-v1';
		}

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance,
			'args'     => $args
		), $layout );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Course_Categories_El() );