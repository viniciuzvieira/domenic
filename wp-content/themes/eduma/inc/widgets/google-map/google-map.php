<?php

class Thim_Google_Map_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'google-map',
			esc_html__( 'Thim: Google Maps', 'eduma' ),
			array(
				'description'   => esc_html__( 'A Google Maps widget.', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-google-map'
			),
			array(),
			array(
				'title'      => array(
					'type'  => 'text',
					'label' => esc_html__( 'Title', 'eduma' ),
				),
				'display_by' => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Get Map By', 'eduma' ),
					'options'       => array(
						'address'  => esc_html__( 'Address', 'eduma' ),
						'location' => esc_html__( 'Coordinates', 'eduma' ),
					),
					'default'       => 'address',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'display_by' )
					),
				),
				'location'   => array(
					'type'          => 'section',
					'label'         => esc_html__( 'Coordinates', 'eduma' ),
					'hide'          => true,
					"class"         => "clear-both",
					'state_handler' => array(
						'display_by[address]'  => array( 'hide' ),
						'display_by[location]' => array( 'show' ),
					),
					'fields'        => array(
						'lat' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Lat', 'eduma' ),
							'default' => '41.868626',
						),
						'lng' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Lng', 'eduma' ),
							'default' => '-74.104301',
						),
					),
				),

				'map_center' => array(
					'type'          => 'textarea',
					'rows'          => 2,
					'label'         => esc_attr__( 'Map center', 'eduma' ),
					'description'   => esc_attr__( 'The name of a place, town, city, or even a country. Can be an exact address too.', 'eduma' ),
					'state_handler' => array(
						'display_by[address]'  => array( 'show' ),
						'display_by[location]' => array( 'hide' ),
					),
				),

				'api_key'      => array(
					'type'  => 'text',
					'label' => esc_html__( 'Google Map API Key', 'eduma' ),
					'description' =>  esc_html__( 'Enter your Google Map API Key. Refer on https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key', 'eduma' )
				),
				
				'settings'   => array(
					'type'        => 'section',
					'label'       => esc_html__( 'Settings', 'eduma' ),
					'hide'        => false,
					'description' => esc_html__( 'Set map display options.', 'eduma' ),
					'fields'      => array(
						'height'      => array(
							'type'    => 'text',
							'default' => 480,
							'label'   => esc_html__( 'Height', 'eduma' )
						),
						'zoom'        => array(
							'type'        => 'slider',
							'label'       => esc_html__( 'Zoom level', 'eduma' ),
							'description' => esc_html__( 'A value from 0 (the world) to 21 (street level).', 'eduma' ),
							'min'         => 0,
							'max'         => 21,
							'default'     => 12,
							'integer'     => true,

						),
						'scroll_zoom' => array(
							'type'        => 'checkbox',
							'default'     => true,
							'state_name'  => 'interactive',
							'label'       => esc_html__( 'Scroll to zoom', 'eduma' ),
							'description' => esc_html__( 'Allow scrolling over the map to zoom in or out.', 'eduma' )
						),
						'draggable'   => array(
							'type'        => 'checkbox',
							'default'     => true,
							'state_name'  => 'interactive',
							'label'       => esc_html__( 'Draggable', 'eduma' ),
							'description' => esc_html__( 'Allow dragging the map to move it around.', 'eduma' )
						)
					)
				),
				'markers'    => array(
					'type'        => 'section',
					'label'       => esc_html__( 'Markers', 'eduma' ),
					'hide'        => true,
					'description' => esc_html__( 'Use markers to identify points of interest on the map.', 'eduma' ),
					'fields'      => array(
						'marker_at_center' => array(
							'type'    => 'checkbox',
							'default' => true,
							'label'   => esc_html__( 'Show marker at map center', 'eduma' )
						),
						'marker_icon'      => array(
							'type'        => 'media',
							'default'     => '',
							'label'       => esc_html__( 'Marker Icon', 'eduma' ),
							'description' => esc_html__( 'Replaces the default map marker with your own image.', 'eduma' )
						),

						'marker_positions' => array(
							'type'      => 'repeater',
							'label'     => esc_html__( 'Marker positions', 'eduma' ),
							'item_name' => esc_html__( 'Marker', 'eduma' ),
							'fields'    => array(
								'place' => array(
									'type'  => 'textarea',
									'rows'  => 2,
									'label' => esc_html__( 'Place', 'eduma' )
								)
							)
						)
					)
				),
			)
		);
	}

	function enqueue_frontend_scripts() {
		wp_enqueue_script( 'thim-google-map', THIM_URI . 'inc/widgets/google-map/js/js-google-map.js', array( 'jquery' ), true );
	}

	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}

	function get_template_variables( $instance, $args ) {
		$settings = $instance['settings'];
		$markers  = $instance['markers'];
		$mrkr_src = wp_get_attachment_image_src( $instance['markers']['marker_icon'] );
		$api_key = ( !empty( $instance['api_key'] ) ) ? $instance['api_key'] : '';
		{
			return array(
				'map_id'   => md5( $instance['map_center'] ),
				'height'   => $settings['height'],
				'map_data' => array(
					'display_by'       => ( isset( $instance['display_by'] ) && $instance['display_by'] != 'address' ) ? $instance['display_by'] : 'address',
					'lat'              => isset( $instance['location']['lat'] ) ? $instance['location']['lat'] : 41.956750,
					'lng'              => isset( $instance['location']['lng'] ) ? $instance['location']['lng'] : - 74.545448,
					'address'          => $instance['map_center'],
					'zoom'             => $settings['zoom'],
					'scroll-zoom'      => $settings['scroll_zoom'],
					'draggable'        => $settings['draggable'],
					'marker-icon'      => ! empty( $mrkr_src ) ? $mrkr_src[0] : '',
					//	'markers-draggable' => isset( $markers['markers_draggable'] ) ? $markers['markers_draggable'] : '',
					'marker-at-center' => $markers['marker_at_center'],
					'marker-positions' => isset( $markers['marker_positions'] ) ? json_encode( $markers['marker_positions'] ) : '',
					'api-key'           => $api_key
				)
			);
		}
	}
}

function thim_google_map_widget() {
	register_widget( 'Thim_Google_Map_Widget' );
}

add_action( 'widgets_init', 'thim_google_map_widget' );