<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Google_Map_El extends Widget_Base {

	public function get_name() {
		return 'thim-google-map';
	}

	public function get_title() {
		return esc_html__( 'Thim: Google Maps', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-google-map';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		wp_enqueue_script( 'thim-google-map', THIM_URI . 'inc/widgets/google-map/js/js-google-map.js', array( 'jquery' ), true );

		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Google Maps', 'eduma' )
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'api_key',
			[
				'label'       => esc_html__( 'Google Map API Key', 'eduma' ),
				'description' => esc_html__( 'Enter your Google Map API Key. Refer on https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'display_by',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'address'  => esc_html__( 'Address', 'eduma' ),
					'location' => esc_html__( 'Coordinates', 'eduma' )
				],
				'default' => 'address'
			]
		);

		$this->add_control(
			'map_center',
			[
				'label'       => esc_html__( 'Location', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'condition'   => array(
					'display_by' => [ 'address' ]
				)
			]
		);

		$this->add_control(
			'lat',
			[
				'label'       => esc_html__( 'Lat', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '41.868626',
				'label_block' => false,
				'condition'   => array(
					'display_by' => [ 'location' ]
				)
			]
		);

		$this->add_control(
			'lng',
			[
				'label'       => esc_html__( 'Lng', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '-74.104301',
				'label_block' => false,
				'condition'   => array(
					'display_by' => [ 'location' ]
				)
			]
		);

		$this->add_control(
			'height',
			[
				'label'   => esc_html__( 'Height (px)', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 480,
				'min'     => 0,
				'step'    => 1
			]
		);

		$this->add_control(
			'zoom',
			[
				'label'     => esc_html__( 'Zoom Level', 'eduma' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 21,
						'step' => 1,
					]
				],
				'default'   => [
					'unit' => 'px',
					'size' => 12,
				]
			]
		);

		$this->add_control(
			'scroll_zoom',
			[
				'label'   => esc_html__( 'Scroll To Zoom', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'draggable',
			[
				'label'   => esc_html__( 'Draggable', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'markers',
			[
				'label' => esc_html__( 'Markers', 'eduma' )
			]
		);

		$this->add_control(
			'marker_at_center',
			[
				'label'   => esc_html__( 'Show marker at map center', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'marker_icon',
			[
				'label'       => esc_html__( 'Marker Icon', 'eduma' ),
				'description' => esc_html__( 'Replaces the default map marker with your own image.', 'eduma' ),
				'type'        => Controls_Manager::MEDIA
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'place', [
				'label'       => esc_html__( 'Place', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true
			]
		);

		$this->add_control(
			'marker_positions',
			[
				'label'       => esc_html__( 'Marker Positions', 'eduma' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ place }}}'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'      => $settings['title'],
			'display_by' => $settings['display_by'],
			'location'   => array(
				'lat' => $settings['lat'],
				'lng' => $settings['lng']
			),
			'map_center' => $settings['map_center'],
			'api_key'    => $settings['api_key'],
			'settings'   => array(
				'height'      => $settings['height'],
				'zoom'        => $settings['zoom']['size'],
				'draggable'   => $settings['draggable'],
				'scroll_zoom' => $settings['scroll_zoom']
			),
			'markers'    => array(
				'marker_at_center' => $settings['marker_at_center'],
				'marker_icon'      => $settings['marker_icon']['id'],
				'marker_positions' => $settings['marker_positions']
			)
		);

		$map_id   = md5( $settings['map_center'] );
		$height   = $settings['height'];
		$map_data = array(
			'display_by'       => $settings['display_by'],
			'lat'              => $settings['lat'],
			'lng'              => $settings['lng'],
			'address'          => $settings['map_center'],
			'zoom'             => $settings['zoom']['size'],
			'scroll-zoom'      => $settings['scroll_zoom'],
			'draggable'        => $settings['draggable'],
			'marker-icon'      => $settings['marker_icon']['url'],
			'marker-at-center' => $settings['marker_at_center'],
			'marker-positions' => ! empty( $settings['marker_positions'] ) ? json_encode( $settings['marker_positions'] ) : '',
			'api-key'          => ! empty( $settings['api_key'] ) ? $settings['api_key'] : ''
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance,
			'map_id'   => $map_id,
			'height'   => $height,
			'map_data' => $map_data
		) );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Google_Map_El() );