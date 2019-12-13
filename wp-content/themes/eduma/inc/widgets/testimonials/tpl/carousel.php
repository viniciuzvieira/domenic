<?php
$link           = $regency = '';
$link_to_single = ! empty( $instance['link_to_single'] ) ? true : false;
$limit          = ( $instance['limit'] && '' <> $instance['limit'] ) ? (int) $instance['limit'] : 10;
$item_visible   = ( $instance['item_visible'] && '' <> $instance['item_visible'] ) ? (int) $instance['item_visible'] : 1;
$autoplay       = isset( $instance['carousel-options']['autoplay'] ) ? $instance['carousel-options']['autoplay'] : 0;
$item_time      = ( $instance['pause_time'] && '' <> $instance['pause_time'] ) ? (int) $instance['pause_time'] : 5000;
$navigation     = isset( $instance['carousel-options']['show_navigation'] ) ? $instance['carousel-options']['show_navigation'] : 0;
$pagination     = isset( $instance['carousel-options']['show_pagination'] ) ? $instance['carousel-options']['show_pagination'] : 0;

$testomonial_args = array(
	'post_type'           => 'testimonials',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true
);

$testimonial = new WP_Query( $testomonial_args );

if ( $testimonial->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	$html = '<div class="thim-testimonial-carousel thim-carousel-wrapper" data-time="' . $item_time . '" data-visible="' . $item_visible . '"
	     data-pagination="' . esc_attr( $pagination ) . '" data-navigation="' . esc_attr( $navigation ) . '" data-autoplay="' . esc_attr( $autoplay ) . '">';
	while ( $testimonial->have_posts() ) : $testimonial->the_post();
		$link = get_post_meta( get_the_ID(), 'website_url', true );

		$html .= '<div class="item">';
		$html .= '<div class="content">';
		$html .= '<div class="description">' . get_the_content() . '</div>';

		if ( $link_to_single ) {
			$html .= '<h3 class="title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
		} elseif ( $link <> '' ) {
			$html .= '<h3 class="title"><span class="line"></span><a href="' . $link . '">' . get_the_title() . '</a></h3>';
		} else {
			$html .= '<h3 class="title"><span class="line"></span>' . get_the_title() . '</h3>';
		}

		$html .= '</div></div>';

	endwhile;
	$html .= '</div>';
}

wp_reset_postdata();
echo ent2ncr( $html );
?>


