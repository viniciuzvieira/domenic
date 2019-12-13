<?php

global $post, $wpdb;
$random     = rand( 1, 99 );
$limit_tab  = $instance['tabs-options']['limit_tab'] ? $instance['tabs-options']['limit_tab'] : 4;
$cat_id_tab = $instance['tabs-options']['cat_id_tab'] ? $instance['tabs-options']['cat_id_tab'] : array();
$limit      = $instance['limit'];
$featured   = ! empty( $instance['featured'] ) ? true : false;
$sort       = $instance['order'];
$thumb_w    = ( ! empty( $instance['thumbnail_width'] ) && '' != $instance['thumbnail_width'] ) ? $instance['thumbnail_width'] : apply_filters( 'thim_course_thumbnail_width', 450 );
$thumb_h    = ( ! empty( $instance['thumbnail_height'] ) && '' != $instance['thumbnail_height'] ) ? $instance['thumbnail_height'] : apply_filters( 'thim_course_thumbnail_height', 400 );

if ( ! empty( $cat_id_tab ) ) {
	foreach ( $cat_id_tab as $value ) {
		$array[ $value ] = 1;
		$html[ $value ]  = '';

		$condition[ $value ]              = array(
			'post_type'           => 'lp_course',
			'posts_per_page'      => $limit_tab,
			'ignore_sticky_posts' => true,
		);
		$condition[ $value ]['tax_query'] = array(
			array(
				'taxonomy' => 'course_category',
				'field'    => 'term_id',
				'terms'    => $value
			),
		);

		if ( $featured ) {
			$condition[ $value ]['meta_query'] = array(
				array(
					'key'   => '_lp_featured',
					'value' => 'yes',
				)
			);
		}

		if ( $sort == 'popular' ) {

			$query = $wpdb->prepare( "
	  SELECT ID, a+IF(b IS NULL, 0, b) AS students FROM(
		SELECT p.ID as ID, IF(pm.meta_value, pm.meta_value, 0) as a, (
	SELECT COUNT(*)
  FROM (SELECT COUNT(item_id), item_id, user_id FROM {$wpdb->prefix}learnpress_user_items GROUP BY item_id, user_id) AS Y
  GROUP BY item_id
  HAVING item_id = p.ID
) AS b
FROM {$wpdb->posts} p
LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id  AND pm.meta_key = %s
WHERE p.post_type = %s AND p.post_status = %s
GROUP BY ID
) AS Z
ORDER BY students DESC
 ", '_lp_students', 'lp_course', 'publish' );

			$post_in = $wpdb->get_col( $query );

			$condition[ $value ]['post__in'] = $post_in;
			$condition[ $value ]['orderby']  = 'post__in';
		}

		$the_query[ $value ] = new WP_Query( $condition[ $value ] );

		if ( $the_query[ $value ]->have_posts() ) :
			?>
			<?php
			ob_start();
			while ( $the_query[ $value ]->have_posts() ) : $the_query[ $value ]->the_post(); ?>
				<div class="lpr_course <?php echo 'course-grid-' . $limit_tab; ?>">
					<div class="course-item">
						<div class="course-thumbnail">
							<a href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>">
								<?php echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', $thumb_w, $thumb_h, get_the_title() ); ?>
							</a>
							<?php do_action( 'thim_inner_thumbnail_course' ); ?>
							<a class="course-readmore" href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>"><?php echo esc_html__( 'Read More', 'eduma' ); ?></a>
						</div>
						<div class="thim-course-content">
							<?php if ( thim_plugin_active( 'learnpress-coming-soon-courses/learnpress-coming-soon-courses.php' ) && learn_press_is_coming_soon( get_the_ID() ) ) { ?>
								<?php if ( '' !== get_post_meta( get_the_ID(), '_lp_coming_soon_msg', true ) ) { ?>
									<?php $message = strip_tags( get_post_meta( get_the_ID(), '_lp_coming_soon_msg', true ) ); ?>
									<div class="message message-warning learn-press-message coming-soon-message"> <?php echo $message; ?></div>
								<?php } ?>
							<?php } else { ?>
								<?php learn_press_courses_loop_item_instructor(); ?>
								<?php
								the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
								?>
								<div class="course-meta">
									<?php learn_press_courses_loop_item_instructor(); ?>
									<?php thim_course_ratings(); ?>
									<?php learn_press_courses_loop_item_students(); ?>
									<?php thim_course_ratings_count(); ?>
									<?php learn_press_courses_loop_item_price(); ?>
								</div>

								<?php learn_press_courses_loop_item_price(); ?>
								<div class="course-readmore">
									<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php
			endwhile;
			$html[ $value ] .= ob_get_contents();
			ob_end_clean();
			?>

		<?php
		endif;
		wp_reset_postdata();
	}
} else {
	return;
}

if ( $instance['title'] ) {
	echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
}

$list_tab = $content_tab = '';
if ( ! empty( $cat_id_tab ) ) {
	foreach ( $cat_id_tab as $k => $tab ) {
		$term = get_term_by( 'id', $tab, 'course_category' );
		if ( $k == 0 ) {
			$list_tab    .= '<li class="active"><a href="#tab-course-' . $random . '-' . $tab . '" data-toggle="tab">' . $term->name . '</a></li>';
			$content_tab .= '<div role="tabpanel" class="tab-pane fade in active" id="tab-course-' . $random . '-' . $tab . '">';
			$content_tab .= $html[ $tab ];
			$content_tab .= '</div>';
		} else {
			$list_tab    .= '<li><a href="#tab-course-' . $random . '-' . $tab . '" data-toggle="tab">' . $term->name . '</a></li>';
			$content_tab .= '<div role="tabpanel" class="tab-pane fade" id="tab-course-' . $random . '-' . $tab . '">';
			$content_tab .= $html[ $tab ];
			$content_tab .= '</div>';
		}
	}
}
?>
<div class="thim-category-tabs thim-course-grid">
	<ul class="nav nav-tabs">
		<?php echo ent2ncr( $list_tab ); ?>
	</ul>
	<div class="tab-content thim-list-event">
		<?php echo ent2ncr( $content_tab ); ?>
	</div>
</div>
