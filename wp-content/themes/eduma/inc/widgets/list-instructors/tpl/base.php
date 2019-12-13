<?php
$html = '';

$autoplay     = isset( $instance['slider-options']['auto_play'] ) ? $instance['slider-options']['auto_play'] : 0;

$co_instructors = thim_get_all_courses_instructors();
$visible_item = 3;
if ( $instance['visible_item'] && $instance['visible_item'] != '' ) {
    $visible_item = (int) $instance['visible_item'];
}

if( count($co_instructors) < $visible_item ) {
    $visible_item = count($co_instructors);
}

$pagination = ( !empty($instance['show_pagination']) && $instance['show_pagination'] !== 'no' ) ? 1 : 0;

if ( ! empty( $co_instructors ) ) {
    $html = '<div class="thim-carousel-wrapper thim-carousel-list-instructors" data-visible="'.$visible_item.'" data-navigation="1" data-pagination="'.$pagination.'" data-autoplay="' . esc_attr( $autoplay ) . '">';
    foreach ( $co_instructors as $key => $instructor ) {
        $text_review = ( $instructor["count_rate"] > 1 ) ? $instructor["count_rate"] . ' Reviews' : $instructor["count_rate"] . ' Review';
        $lp_info = get_the_author_meta( 'lp_info', $instructor["user_id"] );
        $link    = learn_press_user_profile_link( $instructor["user_id"] );
        $html .= '<div class="instructor-item"><div class="wrap-item">';
        $html .= '<div class="avatar_item">' . get_avatar( $instructor["user_id"], 450 ) . '</div>';
        $html .= '<div class="instructor-info">';
        $html .= '<h4 class="name" ><a href="'.$link.'">' . get_the_author_meta( 'display_name', $instructor["user_id"] ) . '</a></h4>';
        if( isset($lp_info['major']) ){
            $html .= '<p class="job">' . $lp_info['major'] . '</p>';
        }
        $html .= '<div class="description">' . thim_author_bio_excerpt( $instructor["user_id"] ) . '</div>';
        $html .= '<div class="info_ins">';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-6 col-xs-6 reviews"><span class="lnr lnr-star"></span> (' . $text_review . ')</div>';
        $html .= '<div class="col-sm-6 col-xs-6 students"><span class="lnr lnr-users"></span> ' . $instructor["students"] . ' ' . esc_html__( 'Students', 'eduma' ) . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';


        $html .= '</div></div>';
    }
    $html .= '</div>';
}

echo  ent2ncr($html);
