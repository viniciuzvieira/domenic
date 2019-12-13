<?php
global $post;

$limit           = $instance['limit'];
$columns         = $instance['grid-options']['columns'];
$view_all_course = ( $instance['view_all_courses'] && '' != $instance['view_all_courses'] ) ? $instance['view_all_courses'] : false;
$sort            = $instance['order'];

$condition = array(
	'post_type'           => 'lpr_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);

if ( $sort == 'category' && $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'course_category' ) ) {
		$condition['tax_query'] = array(
			array(
				'taxonomy' => 'course_category',
				'field'    => 'term_id',
				'terms'    => $instance['cat_id']
			),
		);
	}
}

if ( $sort == 'popular' ) {
	global $wpdb;
	$the_query = $wpdb->get_results( $wpdb->prepare(
		"
		SELECT      p.ID, pm.meta_value AS student, pm2.meta_value AS setting
		FROM        $wpdb->posts AS p
		LEFT JOIN  $wpdb->postmeta AS pm ON p.ID = pm.post_id AND pm.meta_key = %s
		LEFT JOIN  $wpdb->postmeta AS pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
		WHERE 		p.post_type = %s AND p.post_status = %s",
		'_lpr_course_user',
		'_lpr_course_student',
		'lpr_course',
		'publish'
	) );
	$temp      = array();
	foreach ( $the_query as $course ) {
		if ( unserialize( $course->student ) ) {
			$course->student = count( unserialize( $course->student ) );
		} else {
			$course->student = 0;
		}
		$temp[ $course->ID ] = $course->setting + $course->student;
	}
	arsort( $temp );
	$condition['post__in'] = array_keys( $temp );
	$condition['orderby']  = 'post__in';
}


$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	?>
	<div class="thim-course-megamenu">
		<?php
		while ( $the_query->have_posts() ) : $the_query->the_post();
			?>
			<div class="lpr_course <?php echo 'course-grid-' . $columns; ?>">
				<div class="course-item">
					<?php
					echo '<div class="course-thumbnail">';
					echo '<a href="' . esc_url( get_the_permalink() ) . '" >';
					echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_course_thumbnail_width', 450 ), apply_filters('thim_course_thumbnail_height', 450), get_the_title() );
					echo '</a>';
					echo '</div>';
					?>
					<div class="thim-course-content">
						<h2 class="course-title">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
						</h2>

						<div class="course-meta">
							<?php learn_press_course_price(); ?>
						</div>
						<?php
						echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>';
						?>
					</div>
				</div>
			</div>
			<?php
		endwhile;
		?>
	</div>
	<?php

endif;

wp_reset_postdata();
