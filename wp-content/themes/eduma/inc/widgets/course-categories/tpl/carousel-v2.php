<?php

$limit      = (int) $instance['slider-options']['limit'];
$pagination = $instance['slider-options']['show_pagination'] ? 1 : 0;
$navigation = $instance['slider-options']['show_navigation'] ? 1 : 0;
//$item_visible = (int) $instance['slider-options']['item_visible'];
$item_visible = 1;
$taxonomy     = 'course_category';
$autoplay     = isset( $instance['auto_play'] ) ? $instance['auto_play'] : 0;

$args_cat = array(
	'taxonomy' => $taxonomy
);
$args_cat = apply_filters( 'thim_query_slider_categories', $args_cat );

$cat_course = get_categories( $args_cat );

$demo_image_source_category = get_template_directory_uri() . '/images/demo_images/demo_image_category.jpg';

$html = '';
if ( $cat_course ) {
	$index = 1;
	$html  = '<div class="">';
	$html .= '<div class="thim-carousel-wrapper" data-visible="' . $item_visible . '" data-pagination="' . $pagination . '" data-navigation="' . $navigation . '" data-autoplay="' . esc_attr( $autoplay ) . '">';
	foreach ( $cat_course as $key => $value ) {

		$top_image   = get_term_meta( $value->term_id, 'thim_learnpress_top_image', true );
		$description = term_description( $value->term_id );

		$posts_array = get_posts(
			array(
				'posts_per_page' => 10,
				'post_type'      => 'lp_course',
				'tax_query'      => array(
					array(
						'taxonomy' => 'course_category',
						'field'    => 'term_id',
						'terms'    => $value->term_id,
					)
				)
			)
		);

		//xxx($posts_array);

		$img = '<a href="' . esc_url( get_term_link( (int) $value->term_id, $taxonomy ) ) . '">';
		if ( $top_image && '' != $top_image['id'] ) {
			$img .= thim_get_feature_image( $top_image['id'], 'full', 450, 450, $value->name );
		} else {
			$img .= thim_get_feature_image( null, 'full', 450, 450, $value->name );
		}
		$img .= '</a>';

		$html .= '<div class="item">';
		$html .= '<div class="image">';
		$html .= $img;
		$html .= '</div>';
		$html .= '<div class="content-wrapper">';
		$html .= '<h3 class="title"><a href="' . esc_url( get_term_link( (int) $value->term_id, $taxonomy ) ) . '">' . $value->name . '</a></h3>';
		if ( !empty( $description ) ) {
			$html .= '<div class="desc">' . $description . '</div>';
		}
		if ( !empty( $posts_array ) ) {
			$html .= '<div class="list-course-items">';
			$html .= '<label>' . esc_html__( 'Course', 'eduma' ) . '</label>';
			foreach ( $posts_array as $k => $v ) {
				$html .= '<a class="course-link" href="' . get_the_permalink( $v->ID ) . '">' . $v->post_title . '</a>';
			}
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= '</div>';
		if ( $index == $limit ) {
			break;
		}
		$index ++;
	}
	$html .= '</div></div>';
}

?>
<?php if ( $instance['title'] ) {
	echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
} ?>
<?php
echo ent2ncr( $html );
?>
