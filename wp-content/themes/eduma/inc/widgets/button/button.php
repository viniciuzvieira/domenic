<?php

/**
 * Widget Name: Button.
 * Author: ThimPress.
 */
class Thim_Button_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'button',
			esc_html__( 'Thim: Button', 'eduma' ),
			array(
				'description'   => esc_html__( 'Add Button', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-button'
			),
			array(),
			array(
				'title'         => array(
					'type'    => 'text',
					'default' => esc_html__( 'READ MORE', 'eduma' ),
					'label'   => esc_html__( 'Button Text', 'eduma' ),
				),
				'url'           => array(
					'type'                  => 'text',
					'default'               => '#',
					'label'                 => esc_html__( 'Destination URL', 'eduma' ),
					'allow_html_formatting' => true
				),
				'new_window'    => array(
					'type'    => 'checkbox',
					'default' => false,
					'label'   => esc_html__( 'Open in New Window', 'eduma' ),
				),
				'custom_style'  => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Style', 'eduma' ),
					'options'       => array(
						'default'      => esc_html__( 'Default', 'eduma' ),
						'custom_style' => esc_html__( 'Custom Style', 'eduma' ),
					),
					'default'       => 'default',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'custom_style' )
					),
				),
				'style_options' => array(
					'type'          => 'section',
					'label'         => esc_html__( 'Style Options', 'eduma' ),
					'hide'          => true,
					'state_handler' => array(
						'custom_style[custom_style]' => array( 'show' ),
						'custom_style[default]'      => array( 'hide' ),
					),
					'fields'        => array(
						// Customize Icon Color
						'font_size'          => array(
							'type'        => 'number',
							'class'       => 'color-mini',
							'label'       => esc_html__( 'Font Size ', 'eduma' ),
							'suffix'      => 'px',
							'default'     => '14',
							'description' => esc_html__( 'Select font size.', 'eduma' ),
						),
						'font_weight'        => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Font Weight', 'eduma' ),
							'class'       => 'color-mini',
							'options'     => array(
								'normal' => esc_html__( 'Normal', 'eduma' ),
								'bold'   => esc_html__( 'Bold', 'eduma' ),
								'100'    => esc_html__( '100', 'eduma' ),
								'200'    => esc_html__( '200', 'eduma' ),
								'300'    => esc_html__( '300', 'eduma' ),
								'400'    => esc_html__( '400', 'eduma' ),
								'500'    => esc_html__( '500', 'eduma' ),
								'600'    => esc_html__( '600', 'eduma' ),
								'700'    => esc_html__( '700', 'eduma' ),
								'800'    => esc_html__( '800', 'eduma' ),
								'900'    => esc_html__( '900', 'eduma' )
							),
							'description' => esc_html__( 'Select Font Weight', 'eduma' ),
						),
						'border_width'       => array(
							'type'        => 'text',
							'class'       => 'color-mini',
							'label'       => esc_html__( 'Border Width ', 'eduma' ),
							'suffix'      => 'px',
							'default'     => '0',
							'description' => esc_html__( 'Enter border width.', 'eduma' ),
						),
						'color'              => array(
							'type'        => 'color',
							'class'       => 'color-mini',
							'label'       => esc_html__( 'Select Color:', 'eduma' ),
							'description' => esc_html__( 'Select the color.', 'eduma' ),
						),
						'border_color'       => array(
							'type'        => 'color',
							'label'       => esc_html__( 'Border Color:', 'eduma' ),
							'description' => esc_html__( 'Select the border color.', 'eduma' ),
							'class'       => 'color-mini',
						),
						'bg_color'           => array(
							'type'        => 'color',
							'label'       => esc_html__( 'Background Color:', 'eduma' ),
							'description' => esc_html__( 'Select the background color.', 'eduma' ),
							'class'       => 'color-mini',
						),
						'hover_color'        => array(
							'type'        => 'color',
							'label'       => esc_html__( 'Hover Color:', 'eduma' ),
							'description' => esc_html__( 'Select the color hover.', 'eduma' ),
							'class'       => 'color-mini',
						),
						'hover_border_color' => array(
							'type'        => 'color',
							'label'       => esc_html__( 'Hover Border Color:', 'eduma' ),
							'description' => esc_html__( 'Select the hover border color.', 'eduma' ),
							'class'       => 'color-mini',
						),
						'hover_bg_color'     => array(
							'type'        => 'color',
							'label'       => esc_html__( 'Hover Background Color:', 'eduma' ),
							'description' => esc_html__( 'Select the hover background color.', 'eduma' ),
							'class'       => 'color-mini',
						),
					)
				),
				'icon'          => array(
					'type'   => 'section',
					'label'  => esc_html__( 'Icon', 'eduma' ),
					'hide'   => true,
					'fields' => array(
						'icon'          => array(
							'type'        => 'icon',
							'class'       => '',
							'label'       => esc_html__( 'Select Icon:', 'eduma' ),
							'description' => esc_html__( 'Select the icon from the list.', 'eduma' ),
							'class_name'  => 'font-awesome',
						),
						// Resize the icon
						'icon_size'     => array(
							'type'        => 'number',
							'class'       => '',
							'label'       => esc_html__( 'Icon Size ', 'eduma' ),
							'suffix'      => 'px',
							'default'     => '14',
							'description' => esc_html__( 'Select the icon font size.', 'eduma' ),
							'class_name'  => 'font-awesome'
						),
						'icon_position' => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Icon Position', 'eduma' ),
							'class'       => 'color-mini',
							'options'     => array(
								''       => esc_html__( 'Select', 'eduma' ),
								'before' => esc_html__( 'Before text', 'eduma' ),
								'after'  => esc_html__( 'After text', 'eduma' ),
							),
							'description' => esc_html__( 'Select icon position', 'eduma' ),
						),
					),

				),
				'layout'        => array(
					'type'   => 'section',
					'label'  => esc_html__( 'Layout', 'eduma' ),
					'hide'   => true,
					'fields' => array(
						'button_size' => array(
							'type'    => 'select',
							'class'   => '',
							'label'   => esc_html__( 'Button Size', 'eduma' ),
							'options' => array(
								'normal' => esc_html__( 'Normal', 'eduma' ),
								'small'  => esc_html__( 'Small', 'eduma' ),
								'medium' => esc_html__( 'Medium', 'eduma' ),
								'large'  => esc_html__( 'Large', 'eduma' ),
							),
						),
						'rounding'    => array(
							'type'    => 'select',
							'class'   => '',
							'label'   => esc_html__( 'Rounding', 'eduma' ),
							'options' => array(
								''             => esc_html__( 'None', 'eduma' ),
								'tiny-rounded' => esc_html__( 'Tiny Rounded', 'eduma' ),
								'very-rounded' => esc_html__( 'Very Rounded', 'eduma' ),
							),
						),
					)
				),
			),
			THIM_DIR . 'inc/widgets/button/'
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}
}

function thim_button_register_widget() {
	register_widget( 'Thim_Button_Widget' );
}

add_action( 'widgets_init', 'thim_button_register_widget' );