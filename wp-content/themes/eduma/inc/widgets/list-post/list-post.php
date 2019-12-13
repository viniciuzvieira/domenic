<?php

class Thim_List_Post_Widget extends Thim_Widget {
	function __construct() {
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
		$options = $this->thim_get_post_categories();
		parent::__construct(
			'list-post',
			esc_html__( 'Thim: List Posts', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display list posts', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-list-post'

			),
			array(),
			array(
				'title'            => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'eduma' ),
					'default' => esc_html__( 'From Blog', 'eduma' )
				),
				'cat_id'           => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Select Category', 'eduma' ),
					'default' => 'none',
					'options' => $options,
				),
				'number_posts'     => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number Post', 'eduma' ),
					'default' => '4'
				),
				'show_description' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Description', 'eduma' ),
					'default' => 'yes',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'orderby'          => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Order by', 'eduma' ),
					'options' => array(
						'popular' => esc_html__( 'Popular', 'eduma' ),
						'recent'  => esc_html__( 'Date', 'eduma' ),
						'title'   => esc_html__( 'Title', 'eduma' ),
						'random'  => esc_html__( 'Random', 'eduma' ),
					),
				),
				'order'            => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Order by', 'eduma' ),
					'options' => array(
						'asc'  => esc_html__( 'ASC', 'eduma' ),
						'desc' => esc_html__( 'DESC', 'eduma' )
					),
				),
				'layout'           => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Layout', 'eduma' ),
					'default'       => 'base',
					'options'       => array(
						'base' => esc_html__( 'Default', 'eduma' ),
						'grid' => esc_html__( 'Grid', 'eduma' ),
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'layout' )
					),
				),
				'display_feature' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show feature posts', 'eduma' ),
					'default' => 'no',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					),
					'state_handler' => array(
						'layout[grid]' => array( 'show' ),
						'layout[base]' => array( 'hide' ),
					),
				),
				'image_size'       => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Select Image Size', 'eduma' ),
					'default' => 'none',
					'options' => $image_size,
					'state_handler' => array(
						'layout[grid]' => array( 'hide' ),
						'layout[base]' => array( 'show' ),
					),
				),
				'img_w'            => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Image width', 'eduma' ),
					'state_handler' => array(
						'layout[grid]' => array( 'show' ),
						'layout[base]' => array( 'hide' ),
					),
				),
				'img_h'            => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Image height', 'eduma' ),
					'state_handler' => array(
						'layout[grid]' => array( 'show' ),
						'layout[base]' => array( 'hide' ),
					),
				),
				'item_vertical'    => array(
					'type'          => 'number',
					'label'         => esc_html__( 'Items vertical', 'eduma' ),
					'description'   => esc_html__( 'Items display with vertical. Enter 0 if doesn\'t show vertical', 'eduma' ),
					'default'       => '0',
					'hide'          => true,
					'state_handler' => array(
						'layout[grid]' => array( 'show' ),
						'layout[base]' => array( 'hide' ),
					),
				),
				'link'             => array(
					'type'  => 'text',
					'label' => esc_html__( 'Link All Posts', 'eduma' ),
				),
				'text_link'        => array(
					'type'  => 'text',
					'label' => esc_html__( 'Text All Posts', 'eduma' ),
				),
				'style'            => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Style', 'eduma' ),
					'options' => array(
						''         => esc_html__( 'No Style', 'eduma' ),
						'homepage' => esc_html__( 'Home Page', 'eduma' ),
						'sidebar'  => esc_html__( 'Sidebar', 'eduma' ),
                        'home-new'  => esc_html__( 'Home New', 'eduma' ),
					),
				),
			),
			THIM_DIR . 'inc/widgets/list-post/'
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	function get_template_name( $instance ) {
		if ( !empty( $instance['layout'] ) ) {
			return $instance['layout'];
		} else {
			return 'base';
		}
	}

	function get_style_name( $instance ) {
		return false;
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
}

function thim_list_post_register_widget() {
	register_widget( 'Thim_List_Post_Widget' );
}

add_action( 'widgets_init', 'thim_list_post_register_widget' );