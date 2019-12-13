<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
if ( class_exists( 'THIM_Portfolio' ) ) {
	class Thim_Portfolio_Widget extends Thim_Widget {

		function __construct() {
			$portfolio_category = get_terms( 'portfolio_category', array(
				'hide_empty' => 0,
				'orderby'    => 'ASC',
				'parent'     => 0
			) );
			$cate               = array();
			$cate[]             = esc_html__( 'All', 'eduma' );
			if ( is_array( $portfolio_category ) ) {
				foreach ( $portfolio_category as $cat ) {
					$cate[$cat->term_id] = $cat->name;
				}
			}

			parent::__construct(
				'portfolio',
				esc_html__( 'Thim: Portfolio', 'eduma' ),
				array(
					'description'   => esc_html__( 'Thim Widget Portfolio By thimpress.com', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-portfolio'
				),
				array(),
				array(
					'portfolio_category' => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Select a category', 'eduma' ),
						'default' => esc_html__( 'All', 'eduma' ),
						'options' => $cate
					),
					'filter_hiden'       => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Hide Filters?', 'eduma' ),
						'default' => false,
					),
					'filter_position'    => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Select a filter position', 'eduma' ),
						'default' => 'center',
						'options' => array(
							'left'   => esc_html__( 'Left', 'eduma' ),
							'center' => esc_html__( 'Center', 'eduma' ),
							'right'  => esc_html__( 'Right', 'eduma' )
						)
					),
					'column'             => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Select a column', 'eduma' ),
						'options' => array(
							'one'   => esc_html__( 'One', 'eduma' ),
							'two'   => esc_html__( 'Two', 'eduma' ),
							'three' => esc_html__( 'Three', 'eduma' ),
							'four'  => esc_html__( 'Four', 'eduma' ),
							'five'  => esc_html__( 'Five', 'eduma' ),
						)
					),
					'gutter'             => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Gutter', 'eduma' ),
						'default' => false
					),
					'item_size'          => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Select a item size', 'eduma' ),
						'options' => array(
							'multigrid' => esc_html__( 'Multigrid', 'eduma' ),
							'masonry'   => esc_html__( 'Masonry', 'eduma' ),
							'same'      => esc_html__( 'Same size', 'eduma' ),
						)
					),
					'paging'             => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Select a paging', 'eduma' ),
						'options' => array(
							'all'             => esc_html__( 'Show All', 'eduma' ),
							'limit'           => esc_html__( 'Limit Items', 'eduma' ),
							'paging'          => esc_html__( 'Paging', 'eduma' ),
							'infinite_scroll' => esc_html__( 'Infinite Scroll', 'eduma' ),
						)
					),
					'style-item'         => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Select style items', 'eduma' ),
						'default' => 'style01',
						'options' => array(
							'style01' => esc_html__( 'Caption Hover Effects 01', 'eduma' ),
							'style02' => esc_html__( 'Caption Hover Effects 02', 'eduma' ),
							'style03' => esc_html__( 'Caption Hover Effects 03', 'eduma' ),
							'style04' => esc_html__( 'Caption Hover Effects 04', 'eduma' ),
							'style05' => esc_html__( 'Caption Hover Effects 05', 'eduma' ),
							'style06' => esc_html__( 'Caption Hover Effects 06', 'eduma' ),
							'style07' => esc_html__( 'Caption Hover Effects 07', 'eduma' ),
							'style08' => esc_html__( 'Caption Hover Effects 08', 'eduma' ),
						)
					),
					'num_per_view'       => array(
						'type'  => 'text',
						'label' => esc_html__( 'Enter a number view', 'eduma' ),
					),
					'show_readmore'      => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Show Read More?', 'eduma' ),
						'default' => false
					)
				),
				THIM_DIR . 'inc/widgets/portfolio/'
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

		function enqueue_frontend_scripts() {
			wp_enqueue_script( 'thim-portfolio-appear', THIM_URI . 'assets/js/jquery.appear.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'thim-portfolio-widget', THIM_URI . 'assets/js/portfolio.js', array(
				'jquery',
				'thim-main'
			), '', true );
		}
	}

	function thim_portfolio_register_widget() {
		register_widget( 'Thim_Portfolio_Widget' );
	}

	add_action( 'widgets_init', 'thim_portfolio_register_widget' );
}