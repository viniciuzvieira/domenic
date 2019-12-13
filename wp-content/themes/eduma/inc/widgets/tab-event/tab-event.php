<?php
class Thim_Tab_Event_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'tab-event',
			esc_html__( 'Thim: Tab events ', 'eduma' ),
			array(
				'description' => esc_html__( 'Show all event with tab', 'eduma' ),
				'help'        => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-tab-event'
			),
			array(),
			array(
				'title'               => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Title', 'eduma' ),
					'allow_html_formatting' => true
				),

			),
			THIM_DIR . 'inc/widgets/tab-event/'
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

function thim_tab_event_widget() {
	register_widget( 'Thim_Tab_Event_Widget' );
}

add_action( 'widgets_init', 'thim_tab_event_widget' );