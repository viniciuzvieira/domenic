<?php

class Thim_Gallery_Post_Widget extends Thim_Widget {
	function __construct() {
		$options = $this->thim_get_post_categories();
		parent::__construct(
			'gallery-posts',
			esc_attr__( 'Thim: Gallery Posts ', 'eduma' ),
			array(
				'description'   => esc_attr__( 'Display gallery posts', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-gallery-posts'
			),
			array(),
			array(
				'cat'     => array(
					'type'    => 'select',
					'label'   => esc_attr__( 'Select Category', 'eduma' ),
					'options' => $options
				),
                'layout' => array(
                    'type'    => 'select',
                    'label'   => esc_html__( 'Layout', 'eduma' ),
                    'options' => array(
                        '' => 'Default',
                        'isotope' => 'Isotope',
                    ),
                ),
				'columns' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Columns', 'eduma' ),
					'options' => array(
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'default' => '4'
				),
				'filter'  => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Filter', 'eduma' ),
					'default' => true,
				),
				'limit'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Limit', 'eduma' ),
					'default' => '8'
				),
			)
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	function get_template_name( $instance ) {
	    if( isset($instance['layout']) && $instance['layout'] != '' ) {
	        return $instance['layout'];
        } else {
            return 'base';
        }
	}

	function get_style_name( $instance ) {
		return false;
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

function thim_gallery_posts_widget() {
	register_widget( 'Thim_Gallery_Post_Widget' );
}

add_action( 'widgets_init', 'thim_gallery_posts_widget' );