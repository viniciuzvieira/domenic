<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_portfolio( $atts ) {

	$instance = shortcode_atts( array(
		'portfolio_category' => '',
		'filter_hiden'       => false,
		'filter_position'    => 'center',
		'column'             => 'three',
		'gutter'             => 1,
		'item_size'          => '',
		'paging'             => 'all',
		'style-item'         => 'style01',
		'num_per_view'       => '',
		'show_readmore'      => false,
        'el_class' => '',
	), $atts );


	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/portfolio/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/portfolio/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}

	ob_start();
    if($instance['el_class']) echo '<div class="'.$instance['el_class'].'">';
	echo '<div class="thim-widget-portfolio">';
	include $widget_template;
	echo '</div>';
    if($instance['el_class']) echo '</div>';
	$html_output = ob_get_contents();
	ob_end_clean();

	wp_enqueue_script( 'thim-portfolio-appear', THIM_URI . 'assets/js/jquery.appear.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'thim-portfolio-widget', THIM_URI . 'assets/js/portfolio.js', array(
		'jquery',
		'thim-main'
	), '', true );

	return $html_output;
}

add_shortcode( 'thim-portfolio', 'thim_shortcode_portfolio' );


