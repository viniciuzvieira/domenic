<?php

global $post;
global $wpdb;
$limit           = $instance['limit'];
$columns         = $instance['grid-options']['columns'];
$view_all_course = ( $instance['view_all_courses'] && '' != $instance['view_all_courses'] ) ? $instance['view_all_courses'] : false;
$sort            = $instance['order'];
$featured        = ! empty( $instance['featured'] ) ? true : false;

$query   = $wpdb->prepare( "
					SELECT DISTINCT p.ID 
					FROM {$wpdb->posts} p
                    WHERE p.post_type = %s
						AND p.post_status = %s
					ORDER BY p.post_date desc
                ", LP_COURSE_CPT, 'publish'
);
$courses = $wpdb->get_col( $query );

if ( $sort == 'category' && $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'course_category' ) ) {
		$query   = $wpdb->prepare( "
					SELECT DISTINCT p.ID 
					FROM {$wpdb->posts} p
					LEFT JOIN {$wpdb->term_relationships} as termrela ON (p.ID = termrela.object_id)
                    LEFT JOIN {$wpdb->term_taxonomy} as termtax ON (termrela.term_taxonomy_id = termtax.term_taxonomy_id)
                    WHERE p.post_type = %s
						AND p.post_status = %s
						AND termtax.term_id = %s
					ORDER BY p.post_date desc
                ", LP_COURSE_CPT, 'publish', $instance['cat_id']
		);
		$post_in = $wpdb->get_col( $query );
		$courses = array_intersect( $courses, $post_in );
	}
}

if ( $featured ) {
	$query = $wpdb->prepare( "
					SELECT DISTINCT p.ID 
					FROM {$wpdb->posts} p
                    LEFT JOIN {$wpdb->postmeta} as pmeta ON p.ID=pmeta.post_id AND pmeta.meta_key = %s
                    WHERE p.post_type = %s
						AND p.post_status = %s
						AND pmeta.meta_value = %s
                ", '_lp_featured', LP_COURSE_CPT, 'publish', 'yes' );

	$post_in = $wpdb->get_col( $query );
	$courses = array_intersect( $courses, $post_in );
}

if ( $sort == 'popular' ) {
	$query = $wpdb->prepare( "
					SELECT DISTINCT p.ID, COUNT(*) AS number_enrolled 
					FROM {$wpdb->prefix}learnpress_user_items ui
					INNER JOIN {$wpdb->posts} p ON p.ID = ui.item_id
					WHERE ui.item_type = %s
						AND ( ui.status = %s OR ui.status = %s )
						AND p.post_status = %s
					ORDER BY number_enrolled %s
				", LP_COURSE_CPT, 'enrolled', 'finished', 'publish', 'DESC'
	);

	$post_in = $wpdb->get_col( $query );
	$courses = array_intersect_assoc( $courses, $post_in );
}

//order courses
if ( $sort != 'popular' ) {
	$str_ids = implode( ", ", $courses );
	$query   = $wpdb->prepare( "
					SELECT DISTINCT p.ID, DATE(p.post_date) as date
					FROM {$wpdb->posts} p
					WHERE p.id IN (%s)
					ORDER BY date DESC
					LIMIT 0,%d
				", $str_ids, $limit
	);

	$courses = $wpdb->get_col( $query );
}


echo '<div class="thim-course-megamenu">';

foreach ( $courses as $course_id ) {
	$post = get_post( $course_id );
	setup_postdata( $post );
	$course = learn_press_get_course( $course_id );
	?>
	<div class="lpr_course <?php echo 'course-grid-' . $columns; ?>">
		<div class="course-item">
			<?php
			echo '<div class="course-thumbnail">';
			echo '<a class="thumb" href="' . esc_url( get_the_permalink() ) . '" >';
			echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_course_thumbnail_width', 450 ), apply_filters( 'thim_course_thumbnail_height', 450 ), get_the_title() );
			echo '</a>';
			echo '</div>';
			?>
			<div class="thim-course-content">
				<h2 class="course-title">
					<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
				</h2>

				<div class="course-meta">
					<?php learn_press_courses_loop_item_price(); ?>
				</div>
				<?php
				echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>';
				?>
			</div>
		</div>
	</div>
	<?php
}

echo '</div>';