<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Carousel_Post_El extends Widget_Base {

	public function get_name() {
		return 'thim-carousel-post';
	}

	public function get_title() {
		return esc_html__( 'Thim: Carousel Posts', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-carousel-posts';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	//Get list post categories
	function thim_get_post_categories( $parent = 0, $taxonomy = 'category', $child_prefix = '--', $level = 0, $force = false ) {
		global $wpdb;
		static $taxonomies = false, $count = 0;
		if ( ! $taxonomies || $force ) {
			$query      = $wpdb->prepare( "
			SELECT t.term_id, t.name, tt.parent
			FROM {$wpdb->terms} t
			INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
			WHERE tt.taxonomy = %s
		", $taxonomy );
			$taxonomies = $wpdb->get_results( $query, OBJECT_K );
		}

		$options        = array();
		$options['all'] = esc_html__( 'All', 'eduma' );
		$level ++;
		if ( $taxonomies ) {
			foreach ( $taxonomies as $tax_id => $tax ) {
				if ( $tax->parent == $parent ) {
					$options[ $tax->term_id ] = str_repeat( $child_prefix, $level - 1 ) . $tax->name;
					// Check $count for safe :)
					if ( $count < 500 && $child = $this->thim_get_post_categories( $tax->term_id, $taxonomy, $child_prefix, $level ) ) {
						foreach ( $child as $k => $v ) {
							$options[ $k ] = $v;
						}
					}
					$count ++;
				}
			}
		}

		return $options;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => __( 'Carousel Posts', 'eduma' )
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Heading', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => false
			]
		);

		$this->add_control(
			'cat_id',
			[
				'label'   => esc_html__( 'Select Category', 'eduma' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->thim_get_post_categories(),
				'default' => 'all'
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base'     => esc_html__( 'Default', 'eduma' ),
					'layout-2' => esc_html__( 'Layout 2', 'eduma' ),
					'layout-3' => esc_html__( 'Layout 3', 'eduma' )
				],
				'default' => 'base'
			]
		);

		$this->add_control(
			'visible_post',
			[
				'label'   => esc_html__( 'Posts Visible', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'number_posts',
			[
				'label'   => esc_html__( 'Number Posts', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'show_nav',
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
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'popular' => esc_html__( 'Popular', 'eduma' ),
					'recent'  => esc_html__( 'Date', 'eduma' ),
					'title'   => esc_html__( 'Title', 'eduma' ),
					'random'  => esc_html__( 'Random', 'eduma' )
				],
				'default' => 'recent'
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'asc'  => esc_html__( 'ASC', 'eduma' ),
					'desc' => esc_html__( 'DESC', 'eduma' )
				],
				'default' => 'desc'
			]
		);


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'           => $settings['title'],
			'layout'          => $settings['layout'],
			'cat_id'          => $settings['cat_id'],
			'visible_post'    => $settings['visible_post'],
			'number_posts'    => $settings['number_posts'],
			'show_nav'        => $settings['show_nav'],
			'show_pagination' => $settings['show_pagination'],
			'orderby'         => $settings['orderby'],
			'order'           => $settings['order'],
		);

		$args                 = array();
		$args['before_title'] = '<h3 class="widget-title">';
		$args['after_title']  = '</h3>';

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance,
			'args'     => $args
		), $settings['layout'] );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Carousel_Post_El() );