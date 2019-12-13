<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_List_Post_El extends Widget_Base {

	public function get_name() {
		return 'thim-list-post';
	}

	public function get_title() {
		return esc_html__( 'Thim: List Post', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-list-post';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

    // list image size
    function get_image_sizes( $size = '' ) {

        global $_wp_additional_image_sizes;

        $sizes                        = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach ( $get_intermediate_image_sizes as $_size ) {

            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

                $sizes[$_size]['width']  = get_option( $_size . '_size_w' );
                $sizes[$_size]['height'] = get_option( $_size . '_size_h' );
                $sizes[$_size]['crop']   = (bool) get_option( $_size . '_crop' );

            } elseif ( isset( $_wp_additional_image_sizes[$_size] ) ) {

                $sizes[$_size] = array(
                    'width'  => $_wp_additional_image_sizes[$_size]['width'],
                    'height' => $_wp_additional_image_sizes[$_size]['height'],
                    'crop'   => $_wp_additional_image_sizes[$_size]['crop']
                );

            }

        }

        // Get only 1 size if found
        if ( $size ) {

            if ( isset( $sizes[$size] ) ) {
                return $sizes[$size];
            } else {
                return false;
            }

        }

        return $sizes;
    }

    //Get list post categories
    function thim_get_post_categories( $parent = 0, $taxonomy = 'category', $child_prefix = '--', $level = 0, $force = false ) {
        global $wpdb;
        static $taxonomies = false, $count = 0;
        if ( !$taxonomies || $force) {
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
                    $options[$tax->term_id] = str_repeat( $child_prefix, $level - 1 ) . $tax->name;
                    // Check $count for safe :)
                    if ( $count < 500 && $child = $this->thim_get_post_categories( $tax->term_id, $taxonomy, $child_prefix, $level ) ) {
                        foreach ( $child as $k => $v ) {
                            $options[$k] = $v;
                        }
                    }
                    $count ++;
                }else{

                }
            }
        }

        return $options;
    }


	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {

        $list_image_size            = $this->get_image_sizes();
        $image_size                 = array();
        $image_size['none']         = esc_html__( 'No Image', 'eduma' );
        $image_size['custom_image'] = esc_html__( 'Custom Image', 'eduma' );
        if ( is_array( $list_image_size ) && !empty( $list_image_size ) ) {
            foreach ( $list_image_size as $key => $value ) {
                if ( $value['width'] && $value['height'] ) {
                    $image_size[$key] = $value['width'] . 'x' . $value['height'];
                } else {
                    $image_size[$key] = $key;
                }
            }
        }

		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'List Post', 'eduma' )
			]
		);

        $this->add_control(
            'title',
            [
                'label'   => esc_html__( 'Title', 'eduma' ),
                'default' => esc_html__( 'From Blog', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'cat_id',
            [
                'label'   => esc_html__( 'Select Category', 'eduma' ),
                'default' => 'none',
                'type'    => Controls_Manager::SELECT,
                'options' => $this->thim_get_post_categories(),
            ]
        );

        $this->add_control(
            'number_posts',
            [
                'label'   => esc_html__( 'Number Post', 'eduma' ),
                'default' => '4',
                'type'    => Controls_Manager::NUMBER,
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label'   => esc_html__( 'Show Description', 'eduma' ),
                'default' => true,
                'type'    => Controls_Manager::SWITCHER,
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
                    'random'  => esc_html__( 'Random', 'eduma' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Order by', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'asc'  => esc_html__( 'ASC', 'eduma' ),
                    'desc' => esc_html__( 'DESC', 'eduma' )
                ],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'base',
                'options' => [
                    'base' => esc_html__( 'Default', 'eduma' ),
                    'grid' => esc_html__( 'Grid', 'eduma' ),
                ],
            ]
        );

        $this->add_control(
            'display_feature',
            [
                'label'   => esc_html__( 'Show feature posts', 'eduma' ),
                'default' => false,
                'type'    => Controls_Manager::SWITCHER,
                'condition' => [
                    'layout' => [ 'grid' ]
                ]
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label'   => esc_html__( 'Select Image Size', 'eduma' ),
                'default' => 'none',
                'type'    => Controls_Manager::SELECT,
                'options' => $image_size,
                'condition' => [
                    'layout' => [ 'base' ]
                ]
            ]
        );

        $this->add_control(
            'img_w',
            [
                'label'   => esc_html__( 'Image width', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'condition' => [
                    'layout' => [ 'grid' ]
                ]
            ]
        );

        $this->add_control(
            'img_h',
            [
                'label'   => esc_html__( 'Image height', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
                'condition' => [
                    'layout' => [ 'grid' ]
                ]
            ]
        );

        $this->add_control(
            'item_vertical',
            [
                'label'         => esc_html__( 'Items vertical', 'eduma' ),
                'description'   => esc_html__( 'Items display with vertical. Enter 0 if doesn\'t show vertical', 'eduma' ),
                'default'       => '0',
                'type'    => Controls_Manager::NUMBER,
                'condition' => [
                    'layout' => [ 'grid' ]
                ]
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link All Posts', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'text_link',
            [
                'label' => esc_html__( 'Text All Posts', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => esc_html__( 'Style', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'base',
                'options' => [
                    ''         => esc_html__( 'No Style', 'eduma' ),
                    'homepage' => esc_html__( 'Home Page', 'eduma' ),
                    'sidebar'  => esc_html__( 'Sidebar', 'eduma' ),
                    'home-new'  => esc_html__( 'Home New', 'eduma' ),
                ],
            ]
        );


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'             => $settings['title'],
			'cat_id'            => $settings['cat_id'],
			'number_posts'      => $settings['number_posts'],
			'show_description'  => $settings['show_description'],
			'orderby'           => $settings['orderby'],
			'order'             => $settings['order'],
			'layout'            => $settings['layout'],
			'display_feature'   => $settings['display_feature'],
			'image_size'        => $settings['image_size'],
			'img_w'             => $settings['img_w'],
			'img_h'             => $settings['img_h'],
			'item_vertical'     => $settings['item_vertical'],
			'link'              => $settings['link'],
			'text_link'         => $settings['text_link'],
			'style'             => $settings['style'],
		);

        $args                 = array();
        $args['before_title'] = '<h3 class="widget-title">';
        $args['after_title']  = '</h3>';

		thim_get_widget_template( $this->get_base(), array( 'instance' => $instance, 'args'     => $args ), $settings['layout'] );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_List_Post_El() );