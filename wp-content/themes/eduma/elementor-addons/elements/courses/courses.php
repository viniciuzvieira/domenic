<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Courses_El extends Widget_Base {

	public function get_name() {
		return 'thim-courses';
	}

	public function get_title() {
		return esc_html__( 'Thim: Courses', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-courses';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	// Get list courses category
	function thim_get_course_categories() {
		global $wpdb;
		$query = $wpdb->get_results( $wpdb->prepare(
			"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
			'course_category', 0
		) );

		$cats        = array();
		$cats['all'] = esc_html__( 'All', 'eduma' );

		if ( ! empty( $query ) ) {
			foreach ( $query as $key => $value ) {
				$cats[ $value->term_id ] = $value->name;
			}
		}

		return $cats;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Courses', 'eduma' )
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Heading', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' )
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'slider'       => esc_html__( 'Slider', 'eduma' ),
					'grid'         => esc_html__( 'Grid', 'eduma' ),
					'grid1'        => esc_html__( 'Grid New', 'eduma' ),
					'list-sidebar' => esc_html__( 'List Sidebar', 'eduma' ),
					'megamenu'     => esc_html__( 'Mega Menu', 'eduma' ),
					'tabs'         => esc_html__( 'Category Tabs', 'eduma' ),
					'tabs-slider'  => esc_html__( 'Category Tabs Slider', 'eduma' )
				],
				'default' => 'slider'
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order By', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'popular'  => esc_html__( 'Popular', 'eduma' ),
					'latest'   => esc_html__( 'Latest', 'eduma' ),
					'category' => esc_html__( 'Category', 'eduma' )
				],
				'default' => 'latest'
			]
		);

		$this->add_control(
			'cat_id',
			[
				'label'     => esc_html__( 'Select Category', 'eduma' ),
				'type'      => Controls_Manager::SELECT2,
				'options'   => $this->thim_get_course_categories(),
				'condition' => array(
					'order' => [ 'category' ]
				)
			]
		);

		$this->add_control(
			'thumbnail_width',
			[
				'label'      => __( 'Thumbnail Width', 'eduma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'max'  => 800,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 400,
				]
			]
		);

		$this->add_control(
			'thumbnail_height',
			[
				'label'      => __( 'Thumbnail Height', 'eduma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'max'  => 800,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 300,
				]
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Limit Number Courses', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'featured',
			[
				'label'        => esc_html__( 'Display Featured Courses?', 'eduma' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'eduma' ),
				'label_off'    => esc_html__( 'No', 'eduma' ),
				'return_value' => 'yes',
				'default'      => ''
			]
		);

		$this->add_control(
			'view_all_courses',
			[
				'label'     => esc_html__( 'View All Text', 'eduma' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'layout' => [ 'grid', 'grid1', 'tabs-slider' ]
				)
			]
		);

		$this->add_control(
			'view_all_position',
			[
				'label'       => __( 'View All Position', 'eduma' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'top'    => [
						'title' => __( 'Top', 'eduma' ),
						'icon'  => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'eduma' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'     => 'top',
				'toggle'      => false,
				'label_block' => false,
				'condition'   => array(
					'layout' => [ 'grid', 'grid1', 'tabs-slider' ]
				)
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider-options',
			[
				'label'     => esc_html__( 'Slider Options', 'eduma' ),
				'condition' => array(
					'layout' => [ 'slider' ]
				)
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
				'label'   => esc_html__( 'Limit Number Courses', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 6,
				'step'    => 1
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

		$this->end_controls_section();

		$this->start_controls_section(
			'grid-options',
			[
				'label'     => esc_html__( 'Grid Options', 'eduma' ),
				'condition' => array(
					'layout' => [ 'grid', 'grid1' ]
				)
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 6,
				'step'    => 1
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tab-options',
			[
				'label'     => esc_html__( 'Tab Options', 'eduma' ),
				'condition' => array(
					'layout' => [ 'tabs', 'tabs-slider' ]
				)
			]
		);

		$this->add_control(
			'limit_tab',
			[
				'label'   => esc_html__( 'Limit Items Per Tab', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'cat_id_tab',
			[
				'label'       => esc_html__( 'Select Category Tabs', 'eduma' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->thim_get_course_categories(),
				'multiple'    => true,
				'default'     => 'all'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'             => $settings['title'],
			'order'             => $settings['order'],
			'cat_id'            => $settings['cat_id'],
			'layout'            => $settings['layout'],
			'thumbnail_width'   => $settings['thumbnail_width']['size'],
			'thumbnail_height'  => $settings['thumbnail_height']['size'],
			'limit'             => $settings['limit'],
			'view_all_courses'  => $settings['view_all_courses'],
			'view_all_position' => $settings['view_all_position'],
			'slider-options'    => array(
				'show_pagination' => $settings['show_pagination'],
				'show_navigation' => $settings['show_navigation'],
				'item_visible'    => $settings['item_visible'],
				'auto_play'       => $settings['auto_play']
			),
			'grid-options'      => array(
				'columns' => $settings['columns']
			),
			'tabs-options'      => array(
				'limit_tab'  => $settings['limit_tab'],
				'cat_id_tab' => $settings['cat_id_tab']
			),
			'featured'          => $settings['featured']
		);

		$layout = $settings['layout'];

		if ( thim_is_new_learnpress( '3.0' ) ) {
			$layout .= '-v3';
		} else if ( thim_is_new_learnpress( '2.0' ) ) {
			$layout .= '-v2';
		} else if ( thim_is_new_learnpress( '1.0' ) ) {
			$layout .= '-v1';
		}

		$args                 = array();
		$args['before_title'] = '<h3 class="widget-title">';
		$args['after_title']  = '</h3>';

		thim_get_widget_template( $this->get_base(), array( 'instance' => $instance, 'args' => $args ), $layout );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Courses_El() );