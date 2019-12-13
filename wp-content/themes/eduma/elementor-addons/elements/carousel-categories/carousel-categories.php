<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Carousel_Categories_El extends Widget_Base {

	public function get_name() {
		return 'thim-carousel-categories';
	}

	public function get_title() {
		return esc_html__( 'Thim: Carousel Categories', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-carousel-categories';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

    function thim_get_categories() {
        $args         = array(
            'orderby' => 'id',
            'parent'  => 0
        );
        $items        = array();
        $categories   = get_categories( $args );
        if ( isset( $categories ) ) {
            foreach ( $categories as $key => $cat ) {
                $items[ $cat->cat_ID ] = $cat->cat_name;
                $childrens             = get_term_children( $cat->term_id, $cat->taxonomy );
                if ( $childrens ) {
                    foreach ( $childrens as $key => $children ) {
                        $child                    = get_term_by( 'id', $children, $cat->taxonomy );
                        $items[ $child->term_id ] = '--' . $child->name;

                    }
                }
            }
        }

        return $items;
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
                'default'     => esc_html__( 'Carousel Categories', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false
			]
		);

		$this->add_control(
			'cat_id',
			[
				'label'   => esc_html__( 'Select Category', 'eduma' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->thim_get_categories(),
				'multiple' => true,
				'default' => 'all'
			]
		);

        $this->add_control(
            'visible',
            [
                'label'   => esc_html__( 'Visible items', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
                'step'    => 1
            ]
        );

        $this->add_control(
            'post_limit',
            [
                'label'   => esc_html__( 'Posts limit display on each category', 'eduma' ),
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
                'default'      => true
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'        => esc_html__( 'Show Pagination?', 'eduma' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => false
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
            'link_view_all',
            [
                'label' => esc_html__( 'Link View All', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true
            ]
        );

        $this->add_control(
            'text_view_all',
            [
                'label' => esc_html__( 'Text View All', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true
            ]
        );



        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
            'title' => $settings['title'],
            'cat_id' => $settings['cat_id'],
            'visible' => $settings['visible'],
            'post_limit' => $settings['post_limit'],
            'show_nav' => $settings['show_nav'],
            'show_pagination' => $settings['show_pagination'],
            'auto_play' => $settings['auto_play'],
            'link_view_all' => $settings['link_view_all'],
            'text_view_all' => $settings['text_view_all'],
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

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Carousel_Categories_El() );