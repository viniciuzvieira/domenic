<?php
if ( class_exists( 'THIM_Testimonials' ) ) {
	class Thim_Testimonials_Widget extends Thim_Widget {
		function __construct() {
			parent::__construct(
				'testimonials',
				esc_html__( 'Thim: Testimonials', 'eduma' ),
				array(
					'description'   => '',
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-testimonials'
				),
				array(),
				array(
					'title'            => array(
						'type'                  => 'text',
						'label'                 => esc_html__( 'Heading Text', 'eduma' ),
						'default'               => esc_html__( 'Testimonials', 'eduma' ),
						'allow_html_formatting' => true
					),
					'layout'           => array(
						'type'          => 'select',
						'label'         => esc_html__( 'Widget Layout', 'eduma' ),
						'options'       => array(
							'default'  => esc_html__( 'Default', 'eduma' ),
                            'slider'  => esc_html__( 'Slider', 'eduma' ),
                            'slider-2'  => esc_html__( 'Slider 2', 'eduma' ),
							'carousel' => esc_html__( 'Carousel Slider', 'eduma' ),
						),
						'default'       => 'default',
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'layout_type' )
						),
					),
					'limit'            => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Limit Posts', 'eduma' ),
						'default' => '7'
					),
                    'activepadding'     => array(
                        'type'    => 'number',
                        'label'   => esc_html__( 'Item padding', 'eduma' ),
                        'desc'    => esc_html__( 'Enter number', 'eduma' ),
                        'default' => '0',
                        'state_handler' => array(
                            'layout_type[default]'  => array( 'hide' ),
                            'layout_type[slider]'  => array( 'show' ),
                            'layout_type[slider-2]'  => array( 'show' ),
                            'layout_type[carousel]' => array( 'hide' ),
                        ),
                    ),
					'item_visible'     => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Item visible', 'eduma' ),
						'desc'    => esc_html__( 'Enter odd number', 'eduma' ),
						'default' => '5'
					),
					'pause_time'     => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Time', 'eduma' ),
						'desc'    => esc_html__( 'Enter time number', 'eduma' ),
						'default' => '5000'
					),
					'autoplay'         => array(
						'type'          => 'checkbox',
						'label'         => esc_html__( 'Auto play', 'eduma' ),
						'default'       => false,
						'state_handler' => array(
							'layout_type[default]'  => array( 'show' ),
                            'layout_type[slider]'  => array( 'show' ),
                            'layout_type[slider-2]'  => array( 'show' ),
							'layout_type[carousel]' => array( 'hide' ),
						),
					),
					'mousewheel'       => array(
						'type'          => 'checkbox',
						'label'         => esc_html__( 'Mousewheel Scroll', 'eduma' ),
						'default'       => false,
						'state_handler' => array(
							'layout_type[default]'  => array( 'show' ),
                            'layout_type[slider]'  => array( 'show' ),
                            'layout_type[slider-2]'  => array( 'show' ),
							'layout_type[carousel]' => array( 'hide' ),
						),
					),
					'carousel-options' => array(
						'type'          => 'section',
						'label'         => esc_html__( 'Carousel Options', 'eduma' ),
						'hide'          => true,
						"class"         => "clear-both",
						'state_handler' => array(
							'layout_type[carousel]' => array( 'show' ),
							'layout_type[default]'  => array( 'hide' ),
                            'layout_type[slider]'  => array( 'hide' ),
                            'layout_type[slider-2]'  => array( 'hide' ),
						),
						'fields'        => array(
							'show_pagination' => array(
								'type'    => 'checkbox',
								'label'   => esc_html__( 'Show Pagination', 'eduma' ),
								'default' => false
							),
							'show_navigation' => array(
								'type'    => 'checkbox',
								'label'   => esc_html__( 'Show Navigation', 'eduma' ),
								'default' => true
							),
							'autoplay'       => array(
								'type'        => 'number',
								'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
								'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
								'default'     => '0'
							),
						),

					),

					'link_to_single'         => array(
						'type'          => 'checkbox',
						'label'         => esc_html__( 'Link To Single', 'eduma' ),
						'default'       => false,
						'description'   => esc_html__( 'Enable link to single testimonial', 'eduma' ),
					),

				),
				THIM_DIR . 'inc/widgets/testimonials/'
			);
		}

		/**
		 * Initialize the CTA widget
		 */


		function get_template_name( $instance ) {
			if ( isset( $instance['layout'] ) && $instance['layout'] != 'default' ) {
				return $instance['layout'];
			} else {
				return 'base';
			}
		}

		function get_style_name( $instance ) {
			return false;
		}

	}

	function thim_testimonials_register_widget() {
		register_widget( 'Thim_Testimonials_Widget' );
	}

	add_action( 'widgets_init', 'thim_testimonials_register_widget' );
}