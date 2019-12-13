<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Video_El extends Widget_Base {

	public function get_name() {
		return 'thim-video';
	}

	public function get_title() {
		return esc_html__( 'Thim: Video', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-video';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Video', 'eduma' )
			]
		);

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'base'    => esc_html__( 'Basic', 'eduma' ),
                    'popup' => esc_html__( 'Popup', 'eduma' ),
                    'image-popup' => esc_html__( 'Image Popup', 'eduma' ),
                ],
                'default' => 'base'
            ]
        );

        $this->add_control(
            'video_width',
            [
                'label'   => esc_html__( 'Width video', 'eduma' ),
                'description' => esc_html__( 'Enter width of video. Example 100% or 600. ', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
            ]
        );

        $this->add_control(
            'video_height',
            [
                'label'   => esc_html__( 'Height video', 'eduma' ),
                'description' => esc_html__( 'Enter height of video. Example 100% or 600. ', 'eduma' ),
                'type'    => Controls_Manager::NUMBER,
            ]
        );

        $this->add_control(
            'video_type',
            [
                'label'   => esc_html__( 'Video Source', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'vimeo'   => esc_html__( 'Vimeo', 'eduma' ),
                    'youtube' => esc_html__( 'Youtube', 'eduma' ),
                ],
                'default' => 'vimeo'
            ]
        );


		$this->add_control(
			'external_video',
			[
				'label'       => esc_html__( 'Vimeo Video ID', 'eduma' ),
                'description' => esc_html__( 'Enter vimeo video ID . Example if link video https://player.vimeo.com/video/61389324 then video ID is 61389324 ', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
				'label_block' => true,
                'condition' => [
                    'video_type' => [ 'vimeo' ]
                ]
			]
		);


        $this->add_control(
            'youtube_id',
            [
                'label'       => esc_html__( 'Youtube Video ID', 'eduma' ),
                'description' => esc_html__( 'Enter Youtube video ID . Example if link video https://www.youtube.com/watch?v=orl1nVy4I6s then video ID is orl1nVy4I6s ', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'video_type' => [ 'youtube' ]
                ]
            ]
        );

        $this->add_control(
            'poster',
            [
                'label'         => esc_html__( 'Poster', 'eduma' ),
                'description'   => esc_html__( 'Poster background display on popup video', 'eduma' ),
                'type'        => Controls_Manager::MEDIA,
                'condition' => [
                    'layout' => [ 'popup', 'image-popup' ]
                ]
            ]
        );

        $this->add_control(
            'title',
            [
                'label'         => esc_html__( 'Title', 'eduma' ),
                'description'   => esc_html__( 'Title display on popup video', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'layout' => [ 'popup', 'image-popup' ]
                ]
            ]
        );

        $this->add_control(
            'description',
            [
                'label'         => esc_html__( 'Description', 'eduma' ),
                'description'   => esc_html__( 'Description display on popup video', 'eduma' ),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'layout' => [ 'popup', 'image-popup' ]
                ]
            ]
        );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'layout'            => $settings['layout'],
			'video_width'       => $settings['video_width'],
			'video_height'      => $settings['video_height'],
			'video_type'        => $settings['video_type'],
			'external_video'    => $settings['external_video'],
			'youtube_id'        => $settings['youtube_id'],
			'poster'            => $settings['poster']['id'],
			'title'             => $settings['title'],
			'description'       => $settings['description'],
		);

		thim_get_widget_template( $this->get_base(), array( 'instance' => $instance ), $settings['layout'] );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Video_El() );