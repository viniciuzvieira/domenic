<?php

class Thim_Our_Team_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'our-team',
			esc_html__( 'Thim: Our Team', 'eduma' ),
			array(
				'description'   => '',
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-our-team'
			),
			array(),
			array(
				'cat_id'      => array(
					'type'     => 'select',
					'label'    => esc_html__( 'Select Category', 'eduma' ),
					'default'  => 'all',
					'multiple' => false,
					'options'  => $this->thim_get_team_categories()
				),
				'number_post' => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number Posts', 'eduma' ),
					'default' => '5'
				),
				'layout'      => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Layout', 'eduma' ),
					'options'       => array(
						'base'   => esc_html__( 'Default', 'eduma' ),
						'slider' => esc_html__( 'Slider', 'eduma' ),
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'layout' )
					),
				),
				'columns'     => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Columns', 'eduma' ),
					'options' => array(
						'1' => esc_html__( '1', 'eduma' ),
						'2' => esc_html__( '2', 'eduma' ),
						'3' => esc_html__( '3', 'eduma' ),
						'4' => esc_html__( '4', 'eduma' )
					),
				),

				'show_pagination' => array(
					'type'          => 'radio',
					'label'         => esc_html__( 'Show Pagination', 'eduma' ),
					'default'       => 'yes',
					'options'       => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					),
					'hide'          => true,
					'state_handler' => array(
						'layout[slider]' => array( 'show' ),
						'layout[base]'   => array( 'hide' ),
					),
				),

				'text_link'     => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Text Link', 'eduma' ),
					'description' => esc_html__( 'Provide the text link that will be applied to box our team.', 'eduma' )
				),
				'link'          => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Link Join Team', 'eduma' ),
					'description' => esc_html__( 'Provide the link that will be applied to box our team', 'eduma' )
				),
				'link_member'   => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Enable Link To Member', 'eduma' ),
					'default' => false
				),
				'css_animation' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'CSS Animation', 'eduma' ),
					'options' => array(
						''              => esc_html__( 'No', 'eduma' ),
						'top-to-bottom' => esc_html__( 'Top to bottom', 'eduma' ),
						'bottom-to-top' => esc_html__( 'Bottom to top', 'eduma' ),
						'left-to-right' => esc_html__( 'Left to right', 'eduma' ),
						'right-to-left' => esc_html__( 'Right to left', 'eduma' ),
						'appear'        => esc_html__( 'Appear from center', 'eduma' )
					),
				)

			),
			THIM_DIR . 'inc/widgets/our-team/'
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	// Get list category
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

	function get_template_name( $instance ) {
		if ( ! empty( $instance['layout'] ) ) {
			return $instance['layout'];
		} else {
			return 'base';
		}
	}

	function get_style_name( $instance ) {
		return false;
	}
}

function thim_our_team_register_widget() {
	register_widget( 'Thim_Our_Team_Widget' );
}

add_action( 'widgets_init', 'thim_our_team_register_widget' );