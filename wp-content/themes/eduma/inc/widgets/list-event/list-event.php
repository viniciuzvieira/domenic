<?php
if ( !class_exists( 'Thim_List_Event_Widget' ) ) {
	class Thim_List_Event_Widget extends Thim_Widget {

		function __construct() {
            $list_cat     = $this->thim_get_event_categories();
            $list_all_cat = array( 'all' => esc_html__( 'All', 'eduma' ) ) + $list_cat;
			parent::__construct(
				'list-event',
				esc_html__( 'Thim: List Events ', 'eduma' ),
				array(
					'description'   => esc_html__( 'Display list events', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-list-event'
				),
				array(),
				array(
					'title'        => array(
						'type'                  => 'text',
						'label'                 => esc_html__( 'Title', 'eduma' ),
						'allow_html_formatting' => true
					),
					'layout'       => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Layout', 'eduma' ),
						'options' => array(
							'base'     => esc_html__( 'Default', 'eduma' ),
							'slider'   => esc_html__( 'Slider', 'eduma' ),
							'layout-2' => esc_html__( 'Layout 2', 'eduma' ),
							'layout-3' => esc_html__( 'Layout 3', 'eduma' ),
                            'layout-4' => esc_html__( 'Layout 4', 'eduma' ),
						),
						'default' => 'base'
					),
                    'cat_id'            => array(
                        'type'          => 'select',
                        'multiple'      => true,
                        'label'         => esc_html__( 'Select Category', 'eduma' ),
                        'default'       => 'all',
                        'options'       => $list_all_cat,
                    ),
                    'status'            => array(
                        'type'          => 'select',
                        'multiple'      => true,
                        'label'         => esc_html__( 'Select Status', 'eduma' ),
                        'options' => array(
                            'upcoming'     => esc_html__( 'Upcoming', 'eduma' ),
                            'happening'   => esc_html__( 'Happening', 'eduma' ),
                            'expired' => esc_html__( 'Expired', 'eduma' ),
                        ),
                    ),
					'number_posts' => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Number posts', 'eduma' ),
						'default' => '2'
					),
					'text_link'    => array(
						'type'                  => 'text',
						'label'                 => esc_html__( 'Text Link All', 'eduma' ),
						'default'               => esc_html__( 'View All', 'eduma' ),
						'allow_html_formatting' => true
					),

				),
				THIM_DIR . 'inc/widgets/list-event/'
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

        // Get list category
        function thim_get_event_categories( $cats = false ) {
            global $wpdb;
            $query = $wpdb->get_results( $wpdb->prepare(
                "
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  ",
                'tp_event_category'
            ) );

            if ( empty( $cats ) ) {
                $cats = array();
            }
            if ( !empty( $query ) ) {
                foreach ( $query as $key => $value ) {
                    $cats[$value->term_id] = $value->name;
                }
            }

            return $cats;
        }
	}
}


function thim_list_event_widget() {
	register_widget( 'Thim_List_Event_Widget' );
}

add_action( 'widgets_init', 'thim_list_event_widget' );