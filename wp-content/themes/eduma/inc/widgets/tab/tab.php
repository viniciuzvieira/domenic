<?php
if ( !class_exists( 'Thim_Tab_Widget' ) ) {
	class Thim_Tab_Widget extends Thim_Widget {

		function __construct() {

			parent::__construct(
				'tab',
				esc_html__( 'Thim: Tab', 'eduma' ),
				array(
					'description'   => esc_html__( 'Add tab', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'dashicons dashicons-welcome-learn-more'
				),
				array(),
				array(
                    'layout'            => array(
                        'type'          => 'select',
                        'label'         => esc_html__( 'Widget Layout', 'eduma' ),
                        'options'       => array(
                            ''       => esc_html__( 'Default', 'eduma' ),
                            'step'         => esc_html__( 'Step', 'eduma' ),
                        ),
                        'state_emitter' => array(
                            'callback' => 'select',
                            'args'     => array( 'layout_type' )
                        ),
                    ),
					'tab' => array(
						'type'      => 'repeater',
						'label'     => esc_html__( 'Tab', 'eduma' ),
						'item_name' => esc_html__( 'Tab', 'eduma' ),
						'fields'    => array(
							'title'   => array(
								"type"                  => "text",
								"label"                 => esc_html__( "Tab Title", 'eduma' ),
								"default"               => esc_html__( "Tab Title", 'eduma' ),
								"allow_html_formatting" => array(
									'a'      => array(
										'href'   => true,
										'target' => true,
										'class'  => true,
										'alt'    => true,
										'title'  => true,
									),
									'br'     => array(),
									'em'     => array(),
									'strong' => array(),
									'span'   => array(),
									'i'      => array(
										'class' => true,
									),
									'b'      => array(),
								)
							),
                            'bg_title'   => array(
                                'type'  => 'color',
                                'hide'  => true,
                                'label' => esc_html__( 'Background Title', 'eduma' ),
                                'state_handler' => array(
                                    'layout_type[step]'         => array( 'show' ),
                                ),
                            ),
							'content' => array(
								"type"                  => "textarea",
								"label"                 => esc_html__( "Content", 'eduma' ),
								"allow_html_formatting" => true
							),
                            'link'   => array(
                                "type"                  => "text",
                                "label"                 => esc_html__( "Link", 'eduma' ),
                            ),
						),
					),
				),
				THIM_DIR . 'inc/widgets/tab/'
			);
		}

		/**
		 * Initialize the CTA widget
		 */

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
}
function thim_tab_register_widget() {
	register_widget( 'Thim_Tab_Widget' );
}

add_action( 'widgets_init', 'thim_tab_register_widget' );