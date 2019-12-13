<?php

if ( !class_exists( 'Thim_Social_Widget' ) ) {
	class Thim_Social_Widget extends Thim_Widget {

		function __construct() {

			parent::__construct(
				'social',
				esc_html__( 'Thim: Social Links', 'eduma' ),
				array(
					'description'   => esc_html__( 'Social Links', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-social'
				),
				array(),
				array(
					'title'          => array(
						'type'  => 'text',
						'label' => esc_html__( 'Title', 'eduma' )
					),
					'style'    => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Style', 'eduma' ),
						'options' => array(
							''  => esc_html__( 'Default', 'eduma' ),
							'style-2' => esc_html__( 'Style 2', 'eduma' ),
							'style-3' => esc_html__( 'Style 3', 'eduma' ),
                            'style-4' => esc_html__( 'Style 4', 'eduma' ),
						),
					),
					'show_label' => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Show Label', 'eduma' ),
						'default' => false
					),
					'link_face'      => array(
						'type'  => 'text',
						'label' => esc_html__( 'Facebook Url', 'eduma' )
					),
					'link_twitter'   => array(
						'type'  => 'text',
						'label' => esc_html__( 'Twitter Url', 'eduma' )
					),
					'link_google'    => array(
						'type'  => 'text',
						'label' => esc_html__( 'Google Url', 'eduma' )
					),
					'link_dribble'   => array(
						'type'  => 'text',
						'label' => esc_html__( 'Dribble Url', 'eduma' )
					),
					'link_linkedin'  => array(
						'type'  => 'text',
						'label' => esc_html__( 'Linkedin Url', 'eduma' )
					),
					'link_pinterest' => array(
						'type'  => 'text',
						'label' => esc_html__( 'Pinterest Url', 'eduma' )
					),
					'link_instagram' => array(
						'type'  => 'text',
						'label' => esc_html__( 'Instagram Url', 'eduma' )
					),
					'link_youtube'   => array(
						'type'  => 'text',
						'label' => esc_html__( 'Youtube Url', 'eduma' )
					),
					'link_snapchat'  => array(
						'type'  => 'text',
						'label' => esc_html__( 'Snapchat Url', 'eduma' )
					),
					'link_target'    => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Link Target', 'eduma' ),
						'options' => array(
							'_self'  => esc_html__( 'Same window', 'eduma' ),
							'_blank' => esc_html__( 'New window', 'eduma' ),
						),
					),

				),
				THIM_DIR . 'inc/widgets/social/'
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
}

function thim_social_register_widget() {
	register_widget( 'Thim_Social_Widget' );
}

add_action( 'widgets_init', 'thim_social_register_widget' );