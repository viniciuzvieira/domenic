<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Social_El extends Widget_Base {

	public function get_name() {
		return 'thim-social';
	}

	public function get_title() {
		return esc_html__( 'Thim: Social', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-social';
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
				'label' => esc_html__( 'Social', 'eduma' )
			]
		);

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => esc_html__( 'Styles', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''  => esc_html__( 'Default', 'eduma' ),
                    'style-2' => esc_html__( 'Style 2', 'eduma' ),
                    'style-3' => esc_html__( 'Style 3', 'eduma' ),
                    'style-4' => esc_html__( 'Style 4', 'eduma' ),
                ],
            ]
        );

        $this->add_control(
            'show_label',
            [
                'label'   => esc_html__( 'Show Label', 'eduma' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => false
            ]
        );

        $this->add_control(
            'link_face',
            [
                'label'       => esc_html__( 'Facebook Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_twitter',
            [
                'label'       => esc_html__( 'Twitter Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_google',
            [
                'label'       => esc_html__( 'Google Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_dribble',
            [
                'label'       => esc_html__( 'Dribble Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_linkedin',
            [
                'label'       => esc_html__( 'Linkedin Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_pinterest',
            [
                'label'       => esc_html__( 'Pinterest Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_instagram',
            [
                'label'       => esc_html__( 'Instagram Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_youtube',
            [
                'label'       => esc_html__( 'Youtube Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_snapchat',
            [
                'label'       => esc_html__( 'Snapchat Url', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_target',
            [
                'label'   => esc_html__( 'Link Target', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '_self'  => esc_html__( 'Same window', 'eduma' ),
                    '_blank' => esc_html__( 'New window', 'eduma' ),
                ],
            ]
        );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'            => $settings['title'],
			'style'       => $settings['style'],
			'show_label'      => $settings['show_label'],
			'link_face'        => $settings['link_face'],
			'link_twitter'    => $settings['link_twitter'],
			'link_google'        => $settings['link_google'],
			'link_dribble'            => $settings['link_dribble'],
			'link_linkedin'             => $settings['link_linkedin'],
			'link_pinterest'       => $settings['link_pinterest'],
			'link_instagram'       => $settings['link_instagram'],
			'link_youtube'       => $settings['link_youtube'],
			'link_snapchat'       => $settings['link_snapchat'],
			'link_target'       => $settings['link_target'],
		);

        $args                 = array();
        $args['before_title'] = '<h3 class="widget-title">';
        $args['after_title']  = '</h3>';

        thim_get_widget_template( $this->get_base(), array(
            'instance' => $instance,
            'args'     => $args
        ) );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Social_El() );