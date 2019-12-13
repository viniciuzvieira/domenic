<?php
$panel_list = $instance['panel'] ? $instance['panel'] : '';
$pagination = ( !empty( $instance['show_pagination'] ) && $instance['show_pagination'] !== 'no' ) ? 1 : 0;
$autoplay   = isset( $instance['slider-options']['auto_play'] ) ? $instance['slider-options']['auto_play'] : 0;
$html       = '<div class="thim-instructors-new">';
$heading    = !( empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Popular Courses', 'eduma' );

if ( !empty( $panel_list ) ) {
	$html .= '<div class="thim-carousel-wrapper" data-visible="1" data-navigation="1" data-itemtablet="1" data-pagination="' . $pagination . '" data-autoplay="' . esc_attr( $autoplay ) . '">';
	foreach ( $panel_list as $key => $panel ) {
		if ( $panel['panel_id'] ) {
			$courses = learn_press_get_course_of_user_instructor( array( 'user_id' => $panel["panel_id"], 'limit' => 2 ) );
			$img_id = is_array($panel['panel_img']) ? $panel['panel_img']['id'] : $panel['panel_img'];

			$html    .= '<div class="instructor-item">';
			$html    .= '<div class="instructor-image">';
			$html    .= thim_get_feature_image( $img_id );
			$html    .= '</div>';
			$html    .= '<div class="instructor-info">';
			$html    .= '<h4><a href="' . learn_press_user_profile_link( $panel["panel_id"] ) . '">' . get_the_author_meta( 'display_name', $panel['panel_id'] ) . '</a></h4>';
			$html    .= '<div class="des">' . get_the_author_meta( 'description', $panel['panel_id'] ) . '</div>';
			if ( $courses ) {
				$html .= '<div class="list-courses">';
				$html .= '<h3>' . $heading . '</h3>';
				foreach ( $courses["rows"] as $key => $course ) {
					$html .= '<div class="course-instructor">';
					$html .= thim_get_feature_image( get_post_thumbnail_id( $course->ID ), 'full', '100', '80' );
					$html .= '<h5><a href="' . get_permalink( $course->ID ) . '">' . get_the_title( $course->ID ) . '</a></h5>';
					$html .= '</div>';
				}
				$html .= '</div>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}
	}
	$html .= '</div>';
}
$html .= '</div>';

echo ent2ncr( $html );
