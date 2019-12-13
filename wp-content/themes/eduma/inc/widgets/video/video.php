<?php

class Thim_Video_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'video',
			esc_html__( 'Thim: Video', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display video youtube or vimeo.', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-video'
			),
			array(),
			array(
				'layout'         => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Layout', 'eduma' ),
					'options'       => array(
						'base'    => esc_html__( 'Basic', 'eduma' ),
						'popup' => esc_html__( 'Popup', 'eduma' ),
                        'image-popup' => esc_html__( 'Image Popup', 'eduma' ),
					),
					'default'       => 'base',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'layout' )
					),
				),
				'video_width'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Width video', 'eduma' ),
					'description' => esc_html__( 'Enter width of video. Example 100% or 600. ', 'eduma' )
				),
				'video_height'   => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Height video', 'eduma' ),
					'description' => esc_html__( 'Enter height of video. Example 100% or 600.', 'eduma' )
				),
				'video_type'     => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Video Source', 'eduma' ),
					'options'       => array(
						'vimeo'   => esc_html__( 'Vimeo', 'eduma' ),
						'youtube' => esc_html__( 'Youtube', 'eduma' ),
					),
					'default'       => 'vimeo',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'video_type' )
					),
				),
				'external_video' => array(
					'type'          => 'text',
					'label'         => esc_html__( 'Vimeo Video ID', 'eduma' ),
					'description'   => esc_html__( 'Enter vimeo video ID . Example if link video https://player.vimeo.com/video/61389324 then video ID is 61389324 ', 'eduma' ),
					'hide'          => true,
					'state_handler' => array(
						'video_type[vimeo]'   => array( 'show' ),
						'video_type[youtube]' => array( 'hide' ),
					),
				),
				'youtube_id'     => array(
					'type'          => 'text',
					'label'         => esc_html__( 'Youtube Video ID', 'eduma' ),
					'description'   => esc_html__( 'Enter Youtube video ID . Example if link video https://www.youtube.com/watch?v=orl1nVy4I6s then video ID is orl1nVy4I6s ', 'eduma' ),
					'hide'          => true,
					'state_handler' => array(
						'video_type[vimeo]'   => array( 'hide' ),
						'video_type[youtube]' => array( 'show' ),
					),
				),
				'poster' => array(
					'type'          => 'media',
					'label'         => esc_html__( 'Poster', 'eduma' ),
					'description'   => esc_html__( 'Poster background display on popup video', 'eduma' ),
					'hide'          => true,
					'library'       => 'image',
					'state_handler' => array(
						'layout[popup]'   => array( 'show' ),
						'layout[base]' => array( 'hide' ),
					),
				),
				'title' => array(
					'type'          => 'text',
					'label'         => esc_html__( 'Title', 'eduma' ),
					'description'   => esc_html__( 'Title display on popup video', 'eduma' ),
					'hide'          => true,
					'state_handler' => array(
						'layout[popup]'   => array( 'show' ),
						'layout[base]' => array( 'hide' ),
					),
				),
				'description' => array(
					'type'          => 'textarea',
					'label'         => esc_html__( 'Description', 'eduma' ),
					'description'   => esc_html__( 'Description display on popup video', 'eduma' ),
					'hide'          => true,
					'state_handler' => array(
						'layout[popup]'   => array( 'show' ),
						'layout[base]' => array( 'hide' ),
					),
				),
			)
		);
	}

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
}

function thim_video_register_widget() {
	register_widget( 'Thim_Video_Widget' );
}

add_action( 'widgets_init', 'thim_video_register_widget' );