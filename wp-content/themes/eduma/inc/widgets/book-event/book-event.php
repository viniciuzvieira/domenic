<?php

class Thim_Book_Event_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'book-event',
			esc_html__( 'Thim: Book Event ', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display Book Form Event', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-book-event'
			),
			array(),
			array(
				'title'        => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Title', 'eduma' ),
					'allow_html_formatting' => true
				),

			)
		);
	}

	function get_template_name( $instance ) {
		return 'base';

	}

	function get_style_name( $instance ) {
		return false;
	}
}

function thim_book_event_widget() {
	register_widget( 'Thim_Book_Event_Widget' );
}

add_action( 'widgets_init', 'thim_book_event_widget' );