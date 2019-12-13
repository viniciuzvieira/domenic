<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Our_Team_El extends Widget_Base {

	public function get_name() {
		return 'thim-our-team';
	}

	public function get_title() {
		return esc_html__( 'Thim: Our Team', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-our-team';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	function thim_get_team_categories() {
		global $wpdb;
		$query = $wpdb->get_results( $wpdb->prepare(
			"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
			'our_team_category', 0
		) );

		$cats        = array();
		$cats['all'] = esc_html__( 'All', 'eduma' );
		if ( ! empty( $query ) ) {
			foreach ( $query as $key => $value ) {
				$cats[ $value->term_id ] = $value->name;
			}
		}

		return $cats;
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Our Team', 'eduma' )
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base'   => esc_html__( 'Default', 'eduma' ),
					'slider' => esc_html__( 'Slider', 'eduma' )
				],
				'default' => 'base'
			]
		);

		$this->add_control(
			'cat_id',
			[
				'label'   => esc_html__( 'Select Category', 'eduma' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->thim_get_team_categories(),
				'default' => 'all'
			]
		);

		$this->add_control(
			'number_post',
			[
				'label'   => esc_html__( 'Number Posts', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'columns',
			[
				'label'       => esc_html__( 'Columns', 'eduma' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => [
					'1' => esc_html__( '1', 'eduma' ),
					'2' => esc_html__( '2', 'eduma' ),
					'3' => esc_html__( '3', 'eduma' ),
					'4' => esc_html__( '4', 'eduma' )
				],
				'default'     => '4'
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label'     => esc_html__( 'Show Pagination?', 'eduma' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'layout' => [ 'slider' ]
				)
			]
		);

		$this->add_control(
			'link_member',
			[
				'label'   => esc_html__( 'Enable Link To Member?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'text_link',
			[
				'label'       => esc_html__( 'CTA Button Text', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'link',
			[
				'label'         => esc_html__( 'CTA Button Link', 'eduma' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'eduma' ),
				'show_external' => false,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'cat_id'        => $settings['cat_id'],
			'number_post'   => $settings['number_post'],
			'text_link'     => $settings['text_link'],
			'link'          => $settings['link']['url'],
			'link_member'   => $settings['link_member'],
			'columns'       => $settings['columns'],
			'css_animation' => ''
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance
		), $settings['layout'] );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Our_Team_El() );
