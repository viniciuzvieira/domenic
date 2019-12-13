<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_List_Instructors_El extends Widget_Base {

	public function get_name() {
		return 'thim-list-instructors';
	}

	public function get_title() {
		return esc_html__( 'Thim: List Instructors', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-one-course-instructors';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	// Get list event categories
	function thim_get_instructors() {
		$co_instructors = thim_get_all_courses_instructors();
		$ins['']        = esc_html__( 'Select', 'eduma' );
		if ( ! empty( $co_instructors ) ) {
			foreach ( $co_instructors as $key => $value ) {
				$ins[ $value["user_id"] ] = get_the_author_meta( 'display_name', $value["user_id"] );
			}
		}

		return $ins;
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'List Instructors', 'eduma' )
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base' => esc_html__( 'Default', 'eduma' ),
					'new'  => esc_html__( 'New', 'eduma' )
				],
				'default' => 'base'
			]
		);

		$this->add_control(
			'visible_item',
			[
				'label'   => esc_html__( 'Visible Instructors', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 0,
				'step'    => 1
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label'   => esc_html__( 'Show Pagination?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'auto_play',
			[
				'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
				'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'step'        => 100
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'panel_img',
			[
				'label'   => esc_html__( 'Avatar', 'eduma' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				]
			]
		);

		$repeater->add_control(
			'panel_id',
			[
				'label'   => esc_html__( 'Instructor', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->thim_get_instructors(),
				'default' => ''
			]
		);

		$this->add_control(
			'panel',
			[
				'label'     => esc_html__( 'Select Instructor', 'eduma' ),
				'type'      => Controls_Manager::REPEATER,
				'separator' => 'before',
				'fields'    => $repeater->get_controls(),
				'condition' => [
					'layout' => [ 'new' ]
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'layout'          => $settings['layout'],
			'visible_item'    => $settings['visible_item'],
			'show_pagination' => $settings['show_pagination'],
			'auto_play'       => $settings['auto_play'],
			'panel'           => $settings['panel'],
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance
		), $settings['layout'] );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_List_Instructors_El() );