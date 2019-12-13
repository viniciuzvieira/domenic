<?php

global $post;
$limit        = $instance['limit'];
$item_visible = $instance['slider-options']['item_visible'];
$pagination   = $instance['slider-options']['show_pagination'] ? $instance['slider-options']['show_pagination'] : 0;
$navigation   = $instance['slider-options']['show_navigation'] ? $instance['slider-options']['show_navigation'] : 0;
$autoplay =  isset( $instance['slider-options']['auto_play'] ) ? $instance['slider-options']['auto_play'] : 0;
$condition    = array(
	'post_type'           => 'lp_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);
$sort         = $instance['order'];

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
	$the_query = $wpdb->get_col(
			$wpdb->prepare( "
			SELECT p.ID, if(pm.meta_value, pm.meta_value, 0) + (select count(course_id) from {$wpdb->prefix}learnpress_user_courses where course_id=p.ID) as students
			FROM {$wpdb->posts} p
			LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id  AND pm.meta_key = %s
			LEFT JOIN {$wpdb->prefix}learnpress_user_courses AS uc ON p.ID = uc.course_id
			WHERE p.post_type = %s and p.post_status='publish'
			ORDER BY students DESC
		", '_lp_students', 'lp_course' )
	);

	$condition['post__in'] = $the_query;
	$condition['orderby']  = 'post__in';
}

$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}


	?>
	<div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="<?php echo esc_attr( $item_visible ); ?>"
	     data-pagination="<?php echo esc_attr( $pagination ); ?>" data-navigation="<?php echo esc_attr( $navigation ); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>">
		<?php
		//$index = 1;
		while ( $the_query->have_posts() ) : $the_query->the_post();


			?>
			<div class="course-item">
				<?php
				echo '<div class="course-thumbnail">';
				echo '<a href="' . esc_url( get_the_permalink() ) . '" >';
				echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_course_thumbnail_width', 450 ), apply_filters( 'thim_course_thumbnail_height', 450 ), get_the_title() );
				echo '</a>';
				thim_course_wishlist_button( $post->ID );
				echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>';
				echo '</div>';
				?>
				<div class="thim-course-content">
					<?php
					learn_press_course_instructor();
					?>
					<h2 class="course-title">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
					</h2>

					<div class="course-meta">
						<?php learn_press_course_students(); ?>
						<?php thim_course_ratings_count(); ?>
						<div class="course-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
							<?php
							$course      = LP_Course::get_course( $post->ID );
							$is_required = $course->is_required_enroll();
							?>
							<?php if ( $course->is_free() || ! $is_required ) : ?>
								<div class="value free-course" itemprop="price" content="<?php esc_attr_e( 'Free', 'eduma' ); ?>">
									<?php esc_html_e( 'Free', 'eduma' ); ?>
								</div>
							<?php else: $price = learn_press_format_price( $course->get_price(), true ); ?>
								<div class="value " itemprop="price" content="<?php echo esc_attr( $price ); ?>">
									<?php echo esc_html( $price ); ?>
								</div>
							<?php endif; ?>
							<meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency(); ?>" />

						</div>
					</div>
				</div>
			</div>
			<?php
			//$index++ ;
		endwhile;
		?>
	</div>
	<?php
endif;
wp_reset_postdata();

?>
