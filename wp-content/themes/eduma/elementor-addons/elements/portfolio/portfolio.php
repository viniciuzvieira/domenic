<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Portfolio_El extends Widget_Base {

	public function get_name() {
		return 'thim-portfolio';
	}

	public function get_title() {
		return esc_html__( 'Thim: Portfolio', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-portfolio';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	function thim_get_portfolio_categories() {
		$portfolio_category = get_terms( 'portfolio_category', array(
			'hide_empty' => 0,
			'orderby'    => 'ASC',
			'parent'     => 0
		) );
		$cate               = array();
		$cate[]             = esc_html__( 'All', 'eduma' );
		if ( is_array( $portfolio_category ) ) {
			foreach ( $portfolio_category as $cat ) {
				$cate[ $cat->term_id ] = $cat->name;
			}
		}

		return $cate;
	}

	protected function _register_controls() {
		wp_enqueue_script( 'thim-portfolio-appear', THIM_URI . 'assets/js/jquery.appear.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'thim-portfolio-widget', THIM_URI . 'assets/js/portfolio.js', array(
			'jquery',
			'thim-main'
		), '', true );

		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Portfolio', 'eduma' )
			]
		);

		$this->add_control(
			'portfolio_category',
			[
				'label'   => esc_html__( 'Select Category', 'eduma' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->thim_get_portfolio_categories(),
				'default' => 0
			]
		);

		$this->add_control(
			'filter_hiden',
			[
				'label'   => esc_html__( 'Draggable', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->add_control(
			'filter_position',
			[
				'label'   => esc_html__( 'Filter Position', 'eduma' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'eduma' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'eduma' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'eduma' ),
						'icon'  => 'fa fa-align-right',
					]
				],
				'default' => 'center',
			]
		);

		$this->add_control(
			'column',
			[
				'label'   => esc_html__( 'Column', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'one'   => esc_html__( '1', 'eduma' ),
					'two'   => esc_html__( '2', 'eduma' ),
					'three' => esc_html__( '3', 'eduma' ),
					'four'  => esc_html__( '4', 'eduma' ),
					'five'  => esc_html__( '5', 'eduma' )
				],
				'default' => 'three'
			]
		);

		$this->add_control(
			'gutter',
			[
				'label'   => esc_html__( 'Gutter', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->add_control(
			'item_size',
			[
				'label'   => esc_html__( 'Item Size', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'multigrid' => esc_html__( 'Multigrid', 'eduma' ),
					'masonry'   => esc_html__( 'Masonry', 'eduma' ),
					'same'      => esc_html__( 'Same size', 'eduma' )
				],
				'default' => 'masonry'
			]
		);

		$this->add_control(
			'paging',
			[
				'label'   => esc_html__( 'Paging', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'all'             => esc_html__( 'Show All', 'eduma' ),
					'limit'           => esc_html__( 'Limit Items', 'eduma' ),
					'paging'          => esc_html__( 'Paging', 'eduma' ),
					'infinite_scroll' => esc_html__( 'Infinite Scroll', 'eduma' )
				],
				'default' => 'all'
			]
		);

		$this->add_control(
			'style-item',
			[
				'label'   => esc_html__( 'Item Style', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'style01' => esc_html__( 'Caption Hover Effects 01', 'eduma' ),
					'style02' => esc_html__( 'Caption Hover Effects 02', 'eduma' ),
					'style03' => esc_html__( 'Caption Hover Effects 03', 'eduma' ),
					'style04' => esc_html__( 'Caption Hover Effects 04', 'eduma' ),
					'style05' => esc_html__( 'Caption Hover Effects 05', 'eduma' ),
					'style06' => esc_html__( 'Caption Hover Effects 06', 'eduma' ),
					'style07' => esc_html__( 'Caption Hover Effects 07', 'eduma' ),
					'style08' => esc_html__( 'Caption Hover Effects 08', 'eduma' )
				],
				'default' => 'style01'
			]
		);

		$this->add_control(
			'num_per_view',
			[
				'label'       => esc_html__( 'Enter a number view', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true
			]
		);

		$this->add_control(
			'show_readmore',
			[
				'label'   => esc_html__( 'Show Read More?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'portfolio_category' => $settings['portfolio_category'],
			'filter_position'    => $settings['filter_position'],
			'column'             => $settings['column'],
			'gutter'             => $settings['gutter'],
			'item_size'          => $settings['item_size'],
			'paging'             => $settings['paging'],
			'style-item'         => $settings['style-item'],
			'num_per_view'       => $settings['num_per_view'],
			'filter_hiden'       => $settings['filter_hiden'],
			'show_readmore'       => $settings['show_readmore'],
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance
		) );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Portfolio_El() );
