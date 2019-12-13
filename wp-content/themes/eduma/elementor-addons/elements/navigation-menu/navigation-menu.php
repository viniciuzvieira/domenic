<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Navigation_Menu_El extends Widget_Base {

	public function get_name() {
		return 'thim-navigation-menu';
	}

	public function get_title() {
		return esc_html__( 'Thim: Navigation Menu', 'eduma' );
	}

	public function get_icon() {
		return '';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	public function get_navigation_menu() {
		$menus        = wp_get_nav_menus();
		$options      = array();
		$options['0'] = esc_html__( '&mdash; Select &mdash;', 'eduma' );

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Navigation Menu', 'eduma' )
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
			'menu',
			[
				'label'   => esc_html__( 'Select Menu', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_navigation_menu(),
				'default' => '0'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$nav_menu = ! empty( $settings['menu'] ) ? wp_get_nav_menu_object( $settings['menu'] ) : false;

		if ( ! $nav_menu ) {
			return;
		}

		$title = ! empty( $settings['title'] ) ? $settings['title'] : '';

		echo '<div class="widget widget_nav_menu">';

		if ( $title ) {
			echo '<h4 class="widget-title">' . $title . '</h4>';
		}

		$nav_menu_args = array(
			'fallback_cb' => '',
			'menu'        => $nav_menu
		);

		wp_nav_menu($nav_menu_args);

		echo '</div>';
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Navigation_Menu_El() );
