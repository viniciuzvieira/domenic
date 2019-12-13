<?php

class Thim_List_Instructors_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'list-instructors',
			esc_html__( 'Thim: List Instructors', 'eduma' ),
			array(
				'description'   => esc_html__( 'Show carousel slider instructors.', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-list-instructors'
			),
			array(),
			array(
                'layout'            => array(
                    'type'          => 'select',
                    'label'         => esc_html__( 'Widget Layout', 'eduma' ),
                    'options'       => array(
                        'base'       => esc_html__( 'Default', 'eduma' ),
                        'new'         => esc_html__( 'New', 'eduma' ),
                    ),
                    'default'       => 'base',
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args'     => array( 'layout_type' )
                    ),
                ),
				'visible_item'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Visible instructors', 'eduma' ),
					'default' => '3'
				),
				'show_pagination' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Pagination', 'eduma' ),
					'default' => 'yes',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'auto_play'       => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
					'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
					'default'     => '0'
				),
                'panel' => array(
                    'type' => 'repeater',
                    'label' => esc_html__('Panel List', 'eduma'),
                    'item_name' => esc_html__('Panel', 'eduma'),
                    'fields' => array(
                        'panel_img' => array(
                            "type"        => "media",
                            "label"       => esc_html__( "Upload Image:", 'eduma' ),
                            "description" => esc_html__( "Upload the custom image.", 'eduma' ),
                        ),
                        'panel_id'      => array(
                            'type'    => 'select',
                            'label'   => esc_html__( 'Select Category', 'eduma' ),
                            'default' => '',
                            'options' => $this->thim_get_instructors(),
                        ),
                    ),
                    'state_handler' => array(
                        'layout_type[new]'  => array( 'show' ),
                        'layout_type[base]'     => array( 'hide' ),
                    ),
                ),
			),

			THIM_DIR . 'inc/widgets/list-instructors/'
		);
	}


	/**
	 * Initialize the CTA widget
	 */

    // Get list category
    function thim_get_instructors() {
        $co_instructors = thim_get_all_courses_instructors();
        $ins[''] = esc_html__( 'Select', 'eduma' );
        if ( !empty( $co_instructors ) ) {
            foreach ( $co_instructors as $key => $value ) {
                $ins[$value["user_id"]] = get_the_author_meta( 'display_name', $value["user_id"] );
            }
        }

        return $ins;
    }


	function get_template_name( $instance ) {
        if ( $instance['layout'] && '' != $instance['layout'] ) {
            return $instance['layout'];
        } else {
            return 'base';
        }
	}

	function get_style_name( $instance ) {
		return false;
	}

}

function thim_list_instructors_register_widget() {
	register_widget( 'Thim_List_Instructors_Widget' );

}

add_action( 'widgets_init', 'thim_list_instructors_register_widget' );

