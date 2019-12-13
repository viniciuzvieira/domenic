<?php
/**
 * Custom functions for LearnPress 0.9.x
 *
 * @package thim
 */

/**
 * Remove old LearnPress template hooks
 */
function thim_remove_learnpress_hooks() {

	remove_action( 'learn_press_after_the_title', 'learn_press_course_thumbnail', 10 );
	remove_action( 'learn_press_entry_footer_archive', 'learn_press_course_price' );
	remove_action( 'learn_press_after_the_title', 'learn_press_print_rate', 10 );

	remove_action( 'learn_press_course_landing_content', 'learn_press_course_price', 30 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_course_students', 40 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_course_payment_form', 40 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_course_enroll_button', 50 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_course_status_message', 50 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_course_content', 60 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_course_curriculum', 70 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_print_review', 80 );

	remove_action( 'learn_press_course_learning_content', 'learn_press_add_review_button', 5 );
	remove_action( 'learn_press_course_learning_content', 'learn_press_course_instructor', 10 );
	remove_action( 'learn_press_course_learning_content', 'learn_press_course_content', 20 );
	remove_action( 'learn_press_course_learning_content', 'learn_press_course_students' );
	remove_action( 'learn_press_course_learning_content', 'learn_press_course_curriculum', 20 );

	remove_action( 'learn_press_course_content_course', 'learn_press_course_content_course_title' );
	remove_action( 'learn_press_course_content_course', 'learn_press_course_content_course_description' );

	remove_action( 'learn_press_course_lesson_quiz_before_title', 'learn_press_course_lesson_quiz_before_title', 10 );

	remove_action( 'learn_press_course_content_lesson', 'learn_press_course_content_lesson_action' );
	remove_action( 'learn_press_course_content_lesson', 'learn_press_course_content_next_prev_lesson' );

	remove_all_actions( 'learn_press_add_profile_tab', 10 );

	remove_action( 'learn_press_content_quiz_sidebar', 'learn_press_single_quiz_time_counter' );
	remove_action( 'learn_press_quiz_questions_after_question_title_element', 'learn_press_quiz_hint' );
	remove_action( 'learn_press_after_single_quiz_summary', 'learn_press_single_quiz_questions' );
	remove_action( 'learn_press_after_question_content', 'learn_press_after_question_content' );

	remove_action( 'learn_press_entry_footer_archive', 'learn_press_course_wishlist_button', 10 );
	remove_action( 'learn_press_course_landing_content', 'learn_press_course_wishlist_button', 10 );
	remove_action( 'learn_press_course_learning_content', 'learn_press_course_wishlist_button', 10 );
	remove_action( 'learn_press_after_wishlist_course_title', 'learn_press_course_wishlist_button', 10 );
	remove_filter( 'learn_press_profile_tabs', 'learn_press_wishlist_tab', 10 );

	remove_action( 'learn_press_course_landing_content', 'learn_press_forum_link', 80 );
	remove_action( 'learn_press_course_learning_content', 'learn_press_forum_link', 30 );

	// Remove register page from BuddyPress
	remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );

}

add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks' );

/**
 * Remove Rev Slider Metabox
 */
if ( is_admin() ) {
	function thim_remove_revolution_slider_meta_boxes() {
		remove_meta_box( 'mymetabox_revslider_0', 'lpr_course', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'lpr_lesson', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'lpr_quiz', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'lpr_question', 'normal' );
	}

	add_action( 'do_meta_boxes', 'thim_remove_revolution_slider_meta_boxes' );
}

/**
 * @param $template
 *
 * @return string
 */
function thim_redirect_search_to_archive( $template ) {

	if ( !empty( $_REQUEST['ref'] ) && ( $_REQUEST['ref'] == 'course' ) ) {
		$template = learn_press_locate_template( 'archive-course.php' );
	}

	return $template;
}

add_filter( 'template_include', 'thim_redirect_search_to_archive' );

/**
 * Create ajax handle for courses searching
 */
function thim_courses_searching_callback() {
	ob_start();
	$keyword = $_REQUEST['keyword'];
	if ( $keyword ) {
		$keyword   = strtoupper( $keyword );
		$arr_query = array(
			'post_type'           => 'lpr_course',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			's'                   => $keyword
		);
		$search    = new WP_Query( $arr_query );

		$newdata = array();
		foreach ( $search->posts as $post ) {
			$newdata[] = array(
				'id'    => $post->ID,
				'title' => $post->post_title,
				'guid'  => get_permalink( $post->ID ),
			);
		}

		ob_end_clean();
		if ( count( $search->posts ) ) {
			echo json_encode( $newdata );
		} else {
			$newdata[] = array(
				'id'    => '',
				'title' => '<i>' . esc_html__( 'No course found', 'eduma' ) . '</i>',
				'guid'  => '#',
			);
			echo json_encode( $newdata );
		}
		wp_reset_postdata();
	}
	die();
}

add_action( 'wp_ajax_nopriv_courses_searching', 'thim_courses_searching_callback' );
add_action( 'wp_ajax_courses_searching', 'thim_courses_searching_callback' );

/**
 * @param $user
 */
if ( !function_exists( 'thim_extra_user_profile_fields' ) ) {
	function thim_extra_user_profile_fields( $user ) {
		$user_info = get_the_author_meta( 'lp_info', $user->ID );
		?>
		<h3><?php esc_html_e( 'LearnPress Profile', 'eduma' ); ?></h3>

		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="lp_major"><?php esc_html_e( 'Major', 'eduma' ); ?></label>
				</th>
				<td>
					<input id="lp_major" class="regular-text" type="text" value="<?php echo isset( $user_info['major'] ) ? $user_info['major'] : ''; ?>" name="lp_info[major]">
				</td>
			</tr>
			<tr>
				<th>
					<label for="lp_facebook"><?php esc_html_e( 'Facebook Account', 'eduma' ); ?></label>
				</th>
				<td>
					<input id="lp_facebook" class="regular-text" type="text" value="<?php echo isset( $user_info['facebook'] ) ? $user_info['facebook'] : ''; ?>" name="lp_info[facebook]">
				</td>
			</tr>
			<tr>
				<th>
					<label for="lp_twitter"><?php esc_html_e( 'Twitter Account', 'eduma' ); ?></label>
				</th>
				<td>
					<input id="lp_twitter" class="regular-text" type="text" value="<?php echo isset( $user_info['twitter'] ) ? $user_info['twitter'] : ''; ?>" name="lp_info[twitter]">
				</td>
			</tr>
			<tr>
				<th>
					<label for="lp_google"><?php esc_html_e( 'Google Plus Account', 'eduma' ); ?></label>
				</th>
				<td>
					<input id="lp_google" class="regular-text" type="text" value="<?php echo isset( $user_info['google'] ) ? $user_info['google'] : ''; ?>" name="lp_info[google]">
				</td>
			</tr>
			<tr>
				<th>
					<label for="lp_linkedin"><?php esc_html_e( 'LinkedIn Plus Account', 'eduma' ); ?></label>
				</th>
				<td>
					<input id="lp_linkedin" class="regular-text" type="text" value="<?php echo isset( $user_info['linkedin'] ) ? $user_info['linkedin'] : ''; ?>" name="lp_info[linkedin]">
				</td>
			</tr>
			<tr>
				<th>
					<label for="lp_youtube"><?php esc_html_e( 'Youtube Account', 'eduma' ); ?></label>
				</th>
				<td>
					<input id="lp_youtube" class="regular-text" type="text" value="<?php echo isset( $user_info['youtube'] ) ? $user_info['youtube'] : ''; ?>" name="lp_info[youtube]">
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
}

add_action( 'show_user_profile', 'thim_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'thim_extra_user_profile_fields' );

function thim_save_extra_user_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	update_user_meta( $user_id, 'lp_info', $_POST['lp_info'] );
}

add_action( 'personal_options_update', 'thim_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'thim_save_extra_user_profile_fields' );


/**
 * Update LearnPress features
 */
function thim_update_learnpress_features() {
	remove_post_type_support( 'lpr_course', 'comments' );
	add_post_type_support( 'lpr_course', 'excerpt' );
}

add_action( 'init', 'thim_update_learnpress_features', 100 );

/**
 * Enqueue custom script for quiz
 */
function thim_enqueue_quiz_scripts() {

	wp_print_scripts( 'learn-press-js' );
	wp_print_scripts( 'lpr-alert-js' );
	wp_print_scripts( 'lpr-time-circle-js' );
	wp_print_scripts( 'tojson' );
	wp_print_scripts( 'block-ui' );
	wp_print_scripts( 'jquery-cookie' );
	wp_print_scripts( 'jquery-ui-sortable' );
	wp_print_scripts( 'single-quiz' );
	wp_print_scripts( 'framework-bootstrap' );
	wp_print_scripts( 'thim-main' );
	wp_print_scripts( 'thim-custom-script' );
	wp_print_styles( 'thim-css-style' );
	wp_print_styles( 'thim-rtl' );
	wp_print_styles( 'thim-awesome' );
	wp_print_styles( 'dashicons' );
	wp_print_styles( 'thim-style' );
	thim_enqueue_quiz_fonts();
}

add_action( 'thim_quiz_scripts', 'thim_enqueue_quiz_scripts' );

/**
 * Enqueue google font for quiz
 */
function thim_enqueue_quiz_fonts() {
	global $wp_styles;
	if ( isset( $wp_styles->queue ) ) {
		foreach ( $wp_styles->queue as $queued_style ) {
			if ( strpos( $queued_style, 'tf-google-webfont' ) !== false ) {
				wp_print_styles( $queued_style );
			}
		}
	}
}

/**
 * Display ratings count
 */

if ( !function_exists( 'thim_course_ratings_count' ) ) {
	function thim_course_ratings_count() {

		if ( !thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		$ratings = learn_press_get_course_rate_total( get_the_ID() ) ? learn_press_get_course_rate_total( get_the_ID() ) : 0;
		echo '<div class="course-comments-count">';
		echo '<div class="value"><i class="fa fa-comment"></i>';
		echo esc_html( $ratings );
		echo '</div>';
		echo '</div>';
	}
}

/**
 * Display rating stars
 *
 * @param $rate
 */
function thim_print_rating( $rate ) {

	?>
	<div class="review-stars-rated">
		<ul class="review-stars">
			<li><span class="fa fa-star-o"></span></li>
			<li><span class="fa fa-star-o"></span></li>
			<li><span class="fa fa-star-o"></span></li>
			<li><span class="fa fa-star-o"></span></li>
			<li><span class="fa fa-star-o"></span></li>
		</ul>
		<ul class="review-stars filled" style="<?php echo esc_attr( 'width: ' . ( $rate * 20 ) . '%' ) ?>">
			<li><span class="fa fa-star"></span></li>
			<li><span class="fa fa-star"></span></li>
			<li><span class="fa fa-star"></span></li>
			<li><span class="fa fa-star"></span></li>
			<li><span class="fa fa-star"></span></li>
		</ul>
	</div>
	<?php
}

/**
 * Display table detailed rating
 *
 * @param $course_id
 * @param $total
 */
function thim_detailed_rating( $course_id, $total ) {
	global $wpdb;
	$query = $wpdb->get_results( $wpdb->prepare(
		"
		SELECT cm2.meta_value AS rating, COUNT(*) AS quantity FROM $wpdb->posts AS p
		INNER JOIN $wpdb->comments AS c ON p.ID = c.comment_post_ID
		INNER JOIN $wpdb->users AS u ON u.ID = c.user_id
		INNER JOIN $wpdb->commentmeta AS cm1 ON cm1.comment_id = c.comment_ID AND cm1.meta_key=%s
		INNER JOIN $wpdb->commentmeta AS cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key=%s
		WHERE p.ID=%d AND c.comment_type=%s
		GROUP BY cm2.meta_value",
		'_lpr_review_title',
		'_lpr_rating',
		$course_id,
		'review'
	), OBJECT_K
	);
	?>
	<div class="detailed-rating">
		<?php for ( $i = 5; $i >= 1; $i -- ) : ?>
			<div class="stars">
				<div class="key"><?php ($i === 1) ? printf( esc_html__( '%s star', 'eduma' ), $i ) : printf( esc_html__( '%s stars', 'eduma' ), $i ); ?></div>
				<div class="bar">
					<div class="full_bar">
						<div style="<?php echo ( $total && !empty( $query[$i]->quantity ) ) ? esc_attr( 'width: ' . ( $query[$i]->quantity / $total * 100 ) . '%' ) : 'width: 0%'; ?>"></div>
					</div>
				</div>
				<div class="value"><?php echo empty( $query[$i]->quantity ) ? '0' : esc_html( $query[$i]->quantity ); ?></div>
			</div>
		<?php endfor; ?>
	</div>
	<?php
}

/**
 * Display review button
 *
 * @param $course_id
 */
function thim_review_button( $course_id ) {

	if ( !get_current_user_id() ) {
		return;
	}

	if ( learn_press_is_enrolled_course() || get_post_meta( $course_id, '_lpr_course_enrolled_require', true ) == 'no' ) {
		if ( !learn_press_get_user_rate() ) {
			?>
			<div class="add-review">
				<h3 class="title"><?php esc_html_e( 'Leave A Review', 'eduma' ); ?></h3>

				<p class="description"><?php esc_html_e( 'Please provide as much detail as you can to justify your rating and to help others.', 'eduma' ); ?></p>
				<?php do_action( 'learn_press_before_review_fields' ); ?>
				<form method="post">
					<div>
						<label for="review-title"><?php esc_html_e( 'Title', 'eduma' ); ?>
							<span class="required">*</span></label>
						<input required type="text" id="review-title" name="review-course-title" />
					</div>
					<div>

						<label><?php esc_html_e( 'Rating', 'eduma' ); ?>
							<span class="required">*</span></label>

						<div class="review-stars-rated">
							<ul class="review-stars">
								<li><span class="fa fa-star-o"></span></li>
								<li><span class="fa fa-star-o"></span></li>
								<li><span class="fa fa-star-o"></span></li>
								<li><span class="fa fa-star-o"></span></li>
								<li><span class="fa fa-star-o"></span></li>
							</ul>
							<ul class="review-stars filled" style="width: 100%">
								<li><span class="fa fa-star"></span></li>
								<li><span class="fa fa-star"></span></li>
								<li><span class="fa fa-star"></span></li>
								<li><span class="fa fa-star"></span></li>
								<li><span class="fa fa-star"></span></li>
							</ul>
						</div>
					</div>
					<div>
						<label for="review-content"><?php esc_html_e( 'Comment', 'eduma' ); ?>
							<span class="required">*</span></label>
						<textarea required id="review-content" name="review-course-content"></textarea>
					</div>
					<input type="hidden" id="review-course-value" name="review-course-value" value="5" />
					<input type="hidden" id="comment_post_ID" name="comment_post_ID" value="<?php echo get_the_ID(); ?>" />
					<button type="submit"><?php esc_html_e( 'Submit Review', 'eduma' ); ?></button>
				</form>
				<?php do_action( 'learn_press_after_review_fields' ); ?>
			</div>
			<?php
		}
	}
}

/**
 * Process review
 */
function thim_process_review() {

	if ( !thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
		return;
	}

	$user_id     = get_current_user_id();
	$course_id   = isset ( $_POST['comment_post_ID'] ) ? $_POST['comment_post_ID'] : 0;
	$user_review = learn_press_get_user_rate( $course_id, $user_id );
	if ( !$user_review && $course_id ) {
		$review_title   = isset ( $_POST['review-course-title'] ) ? $_POST['review-course-title'] : 0;
		$review_content = isset ( $_POST['review-course-content'] ) ? $_POST['review-course-content'] : 0;
		$review_rate    = isset ( $_POST['review-course-value'] ) ? $_POST['review-course-value'] : 0;
		learn_press_save_course_review( $course_id, $review_rate, $review_title, $review_content );
	}
}

add_action( 'learn_press_before_main_content', 'thim_process_review' );

/**
 * Display course ratings
 */
function thim_course_ratings() {

	if ( !thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
		return;
	}

	$course_id   = get_the_ID();
	$course_rate = learn_press_get_course_rate( $course_id );
	$ratings     = learn_press_get_course_rate_total( $course_id );
	?>
	<div class="course-review">
		<label><?php esc_html_e( 'Review', 'eduma' ); ?></label>

		<div class="value">
			<?php thim_print_rating( $course_rate ); ?>
			<span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'eduma' ), number_format_i18n( $ratings ) ) : esc_html_e( '(0 review)', 'eduma' ); ?></span>
		</div>
	</div>
	<?php
}

/**
 * Display course review
 */
function thim_course_review() {
	$course_id     = get_the_ID();
	$course_review = learn_press_get_course_review( $course_id, isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1, 5, true );
	$course_rate   = learn_press_get_course_rate( $course_id );
	$total         = learn_press_get_course_rate_total( $course_id );
	$reviews       = $course_review['reviews'];

	?>
	<div class="course-rating">
		<h3><?php esc_html_e( 'Reviews', 'eduma' ); ?></h3>

		<div class="average-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
			<p class="rating-title"><?php esc_html_e( 'Average Rating', 'eduma' ); ?></p>

			<div class="rating-box">
				<div class="average-value" itemprop="ratingValue"><?php echo ( $course_rate ) ? esc_html( round( $course_rate, 1 ) ) : 0; ?></div>
				<div class="review-star">
					<?php thim_print_rating( $course_rate ); ?>
				</div>
				<div class="review-amount" itemprop="ratingCount">
					<?php $total ? printf( _n( '%1$s rating', '%1$s ratings', $total, 'eduma' ), number_format_i18n( $total ) ) : esc_html_e( '0 rating', 'eduma' ); ?>
				</div>
			</div>
		</div>
		<div class="detailed-rating">
			<p class="rating-title"><?php esc_html_e( 'Detailed Rating', 'eduma' ); ?></p>

			<div class="rating-box">
				<?php thim_detailed_rating( $course_id, $total ); ?>
			</div>
		</div>
	</div>

	<div class="course-review">
		<div id="course-reviews" class="content-review">
			<ul class="course-reviews-list">
				<?php foreach ( $reviews as $review ) : ?>
					<li>
						<div class="review-container" itemprop="review" itemscope itemtype="http://schema.org/Review">
							<div class="review-author">
								<?php echo get_avatar( $review->ID, 70 ); ?>
							</div>
							<div class="review-text">
								<h4 class="author-name" itemprop="author"><?php echo esc_html( $review->display_name ); ?></h4>
								<div class="review-star">
									<?php thim_print_rating( $review->rate ); ?>
								</div>
								<p class="review-title"><?php echo esc_html( $review->title ); ?></p>
								<div class="description" itemprop="reviewBody">
									<p><?php echo esc_html( $review->content ); ?></p>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php if ( empty( $course_review['finish'] ) && $total ) : ?>
		<div class="review-load-more">
			<span id="course-review-load-more" data-paged="<?php echo esc_attr( $course_review['paged'] ); ?>"><i class="fa fa-angle-double-down"></i></span>
		</div>
	<?php endif; ?>
	<?php thim_review_button( $course_id ); ?>
	<?php
}

/**
 * Breadcrumb for LearnPress
 */
if ( !function_exists( 'thim_learnpress_breadcrumb' ) ) {
	function thim_learnpress_breadcrumb() {

		// Do not display on the homepage
		if ( is_front_page() || is_404() ) {
			return;
		}

		// Get the query & post information
		global $post;

		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';

		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_html( get_home_url() ) . '" title="' . esc_attr__( 'Home', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'eduma' ) . '</span></a></li>';

		if ( is_single() ) {

			$categories = get_the_terms( $post, 'course_category' );

			if ( get_post_type() == 'lpr_course' ) {
				// All courses
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lpr_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';
			} else {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( get_post_meta( $post->ID, '_lpr_course', true ) ) ) . '" title="' . esc_attr( get_the_title( get_post_meta( $post->ID, '_lpr_course', true ) ) ) . '"><span itemprop="name">' . esc_html( get_the_title( get_post_meta( $post->ID, '_lpr_course', true ) ) ) . '</span></a></li>';
			}

			// Single post (Only display the first category)
			if ( isset( $categories[0] ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $categories[0] ) ) . '" title="' . esc_attr( $categories[0]->name ) . '"><span itemprop="name">' . esc_html( $categories[0]->name ) . '</span></a></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';

		} else if ( is_tax( 'course_category' ) || is_tax( 'course_tag' ) ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lpr_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';

			// Category page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( single_term_title( '', false ) ) . '">' . esc_html( single_term_title( '', false ) ) . '</span></li>';
		} else if ( !empty( $_REQUEST['s'] ) && !empty( $_REQUEST['ref'] ) && ( $_REQUEST['ref'] == 'course' ) ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lpr_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';

			// Search result
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Search results for:', 'eduma' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'eduma' ) . ' ' . esc_html( get_search_query() ) . '</span></li>';
		} else {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'All courses', 'eduma' ) . '">' . esc_html__( 'All courses', 'eduma' ) . '</span></li>';
		}

		echo '</ul>';
	}
}


/**
 * Page title for LearnPress
 */
if ( !function_exists( 'thim_learnpress_page_title' ) ) {
	function thim_learnpress_page_title( $echo = true ) {
		$title = '';
		if ( get_post_type() == 'lpr_course' ) {
			if ( is_tax() ) {
				$title = single_term_title( '', false );
			} else {
				$title = esc_html__( 'All Courses', 'eduma' );
			}
		}
		if ( get_post_type() == 'lpr_quiz' ) {
			if ( is_tax() ) {
				$title = single_term_title( '', false );
			} else {
				$title = esc_html__( 'Quiz', 'eduma' );
			}
		}
		if( $echo ) {
			echo $title;
		}else{
			return $title;
		}
	}
}

/**
 * Get lesson duration in hours
 *
 * @param $lesson_id
 *
 * @return string
 */
if ( !function_exists( 'thim_lesson_duration' ) ) {
	function thim_lesson_duration( $lesson_id ) {

		$duration = get_post_meta( $lesson_id, '_lpr_lesson_duration', true );
		$hour     = floor( $duration / 60 );
		if ( $hour == 0 ) {
			$hour = '';
		} else {
			$hour = $hour . esc_html__( 'h', 'eduma' );
		}
		$minute = $duration % 60;
		$minute = $minute . esc_html__( 'm', 'eduma' );

		return $hour . $minute;
	}
}

/**
 * Get number of lessons of a quiz
 *
 * @param $quiz_id
 *
 * @return string
 */
function thim_quiz_questions( $quiz_id ) {
	$questions = learn_press_get_quiz_questions( $quiz_id );
	if ( $questions ) {
		return count( $questions );
	}

	return 0;
}

/**
 * Add format icon before curriculum items
 *
 * @param $lesson_or_quiz
 * @param $enrolled
 */
if ( !function_exists( 'thim_add_format_icon' ) ) {
	function thim_add_format_icon( $lesson_or_quiz, $viewable ) {
		$format = get_post_format( $lesson_or_quiz );

		if ( get_post_type( $lesson_or_quiz ) == 'lpr_quiz' ) {
			echo '<span class="course-format-icon"><i class="fa fa-puzzle-piece"></i></span>';
		} elseif ( $format == 'video' ) {
			echo '<span class="course-format-icon"><i class="fa fa-play-circle"></i></span>';
		} else {
			echo '<span class="course-format-icon"><i class="fa fa-file-o"></i></span>';
		}
	}
}

add_action( 'learn_press_course_lesson_quiz_before_title', 'thim_add_format_icon', 10, 2 );

/**
 * Display related courses
 */
if ( !function_exists( 'thim_related_courses' ) ) {
	function thim_related_courses() {
		$related_courses = learn_press_get_related_courses( null, array( 'posts_per_page' => 3 ) );
		if ( $related_courses ) {
			?>
			<div class="thim-ralated-course">
				<h3 class="related-title"><?php esc_html_e( 'You May Like', 'eduma' ); ?></h3>

				<div class="thim-course-grid">
					<?php foreach ( $related_courses as $course ) : ?>
						<article class="course-grid-3 lpr_course">
							<div class="course-item">
								<div class="course-thumbnail">
									<a href="<?php echo get_the_permalink( $course->ID ); ?>">
										<?php
										echo thim_get_feature_image( get_post_thumbnail_id( $course->ID ), 'full', 450, 450, $course->post_title );
										?>
									</a>
									<?php thim_course_wishlist_button( $course->ID ); ?>
									<?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course->ID ) ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
								</div>
								<div class="thim-course-content">
									<div class="course-author">
										<?php echo get_avatar( $course->post_author, 40 ); ?>
										<div class="author-contain">
											<div class="value">
												<a href="<?php echo esc_url( apply_filters( 'learn_press_instructor_profile_link', '#', $user_id = null, $course->ID ) ); ?>">
													<?php echo get_the_author_meta( 'display_name', $course->post_author ); ?>
												</a>
											</div>
										</div>
									</div>
									<h2 class="course-title">
										<a rel="bookmark" href="<?php echo get_the_permalink( $course->ID ); ?>"><?php echo esc_html( $course->post_title ); ?></a>
									</h2> <!-- .entry-header -->
									<div class="course-meta">
										<div class="course-students">
											<div class="value">
												<i class="fa fa-group"></i>
												<?php echo learn_press_count_students_enrolled( $course->ID ); ?>
											</div>
										</div>
										<?php if ( thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) : ?>
											<div class="course-comments-count">
												<div class="value">
													<i class="fa fa-comment"></i>
													<?php echo learn_press_get_course_rate_total( $course->ID ) ? learn_press_get_course_rate_total( $course->ID ) : 0; ?>
												</div>
											</div>
										<?php endif; ?>
										<div class="course-price">
											<div class="value <?php echo learn_press_is_free_course( $course->ID ) ? 'free-course' : ''; ?>">
												<?php echo learn_press_get_course_price( $course->ID, true ); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}
	}
}

/**
 * Add some meta data for a course
 *
 * @param $meta_box
 */
if ( !function_exists( 'thim_add_course_meta' ) ) {
	function thim_add_course_meta( $meta_box ) {
		$fields             = $meta_box['fields'];
		$fields[]           = array(
			'name' => esc_html__( 'Duration Info', 'eduma' ),
			'id'   => 'thim_course_duration',
			'type' => 'text',
			'desc' => esc_html__( 'Display duration info', 'eduma' ),
			'std'  => esc_html__( '50 hours', 'eduma' )
		);
		$fields[]           = array(
			'name' => esc_html__( 'Skill Level', 'eduma' ),
			'id'   => 'thim_course_skill_level',
			'type' => 'text',
			'desc' => esc_html__( 'A possible level with this course', 'eduma' ),
			'std'  => esc_html__( 'All levels', 'eduma' )
		);
		$fields[]           = array(
			'name' => esc_html__( 'Language', 'eduma' ),
			'id'   => 'thim_course_language',
			'type' => 'text',
			'desc' => esc_html__( 'Language\'s used for studying', 'eduma' ),
			'std'  => esc_html__( 'English', 'eduma' )
		);
		$meta_box['fields'] = $fields;

		return $meta_box;
	}

}

add_filter( 'learn_press_course_settings_meta_box_args', 'thim_add_course_meta' );

/**
 * Display course info
 */
if ( !function_exists( 'thim_course_info' ) ) {
	function thim_course_info() {
		$course_id = get_the_ID();
		?>
		<div class="thim-course-info">
			<h3 class="title"><?php esc_html_e( 'Course Features', 'eduma' ); ?></h3>
			<ul>
				<li>
					<i class="fa fa-files-o"></i>
					<span class="label"><?php esc_html_e( 'Lectures', 'eduma' ); ?></span>
					<span class="value"><?php echo count( learn_press_get_lessons( $course_id ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-puzzle-piece"></i>
					<span class="label"><?php esc_html_e( 'Quizzes', 'eduma' ); ?></span>
					<span class="value"><?php echo count( learn_press_get_quizzes( $course_id ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-clock-o"></i>
					<span class="label"><?php esc_html_e( 'Duration', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( get_post_meta( $course_id, 'thim_course_duration', true ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-level-up"></i>
					<span class="label"><?php esc_html_e( 'Skill level', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( get_post_meta( $course_id, 'thim_course_skill_level', true ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-language"></i>
					<span class="label"><?php esc_html_e( 'Language', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( get_post_meta( $course_id, 'thim_course_language', true ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-users"></i>
					<span class="label"><?php esc_html_e( 'Students', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( learn_press_count_students_enrolled( $course_id ) ); ?></span>
				</li>
				<?php thim_course_certificate( $course_id ); ?>
				<li>
					<i class="fa fa-check-square-o"></i>
					<span class="label"><?php esc_html_e( 'Assessments', 'eduma' ); ?></span>
					<span class="value"><?php echo ( get_post_meta( $course_id, '_lpr_course_final', true ) == 'yes' ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'Self', 'eduma' ); ?></span>
				</li>
			</ul>
			<?php thim_course_wishlist_button(); ?>
		</div>
		<?php
	}

}

/**
 * Update profile tabs
 *
 * @param $user
 */
function thim_add_profile_tab( $user ) {
	$content = '';

	$other_tabs = apply_filters(
		'learn_press_profile_tabs',
		array(
			20 => array(
				'tab_id'      => 'user_courses',
				'tab_name'    => '<i class="fa fa-book"></i><span class="text">' . esc_html__( 'Courses', 'eduma' ) . '</span>',
				'tab_content' => apply_filters( 'learn_press_user_courses_tab_content', $content, $user )
			),
			30 => array(
				'tab_id'      => 'user_quizzes',
				'tab_name'    => '<i class="fa fa-check-square-o"></i><span class="text">' . esc_html__( 'Quiz Results', 'eduma' ) . '</span>',
				'tab_content' => apply_filters( 'learn_press_user_quizzes_tab_content', $content, $user )
			),
		),
		$user
	);

	if ( function_exists( 'learn_press_course_wishlist_button' ) ) {
		$other_tabs[40] = array(
			'tab_id'      => 'user_wishlist',
			'tab_name'    => '<i class="fa fa-heart-o"></i><span class="text">' . esc_html__( 'Wishlist', 'eduma' ) . '</span>',
			'tab_content' => apply_filters( 'learn_press_user_wishlist_tab_content', $content, $user )
		);
	}

	ksort( $other_tabs );

	if ( !$user ) {
		echo '<p class="message message-error">' . esc_html__( 'This user is not available!', 'eduma' ) . '</p>';
	} else {
		$tabs  = $tabs_content = '';
		$class = 'active';
		foreach ( $other_tabs as $tab ) {
			$tabs .= '<li class="' . $class . '"><a href="#' . $tab['tab_id'] . '" data-toggle="tab">' . $tab['tab_name'] . '</a></li>';
			$tabs_content .= '<div class="tab-pane ' . $class . '" id="' . $tab['tab_id'] . '">' . $tab['tab_content'] . '</div>';
			if ( $class == 'active' ) {
				$class = '';
			}
		}
		printf(
			'<div class="profile-container">
			<div class="user-tab dff">%s</div>
			<div class="profile-tabs">
				<ul class="nav nav-tabs" role="tablist">%s</ul>
				<div class="tab-content">%s</div>
			</div>
		</div>',
			apply_filters( 'learn_press_user_info_tab_content', $content, $user ), $tabs, $tabs_content
		);
	}
}

add_action( 'learn_press_add_profile_tab', 'thim_add_profile_tab', 100 );

/**
 * Add question hint
 *
 * @param $id
 */
function thim_add_question_hint( $id ) {
	global $post;
	$post = get_post( $id );
	$hint = $post->post_content;
	if ( !empty( $hint ) ) :
		setup_postdata( $post );
		?>
		<div class="question-hint">
			<p class="quiz-hint">
				<span class="quiz-hint-toggle">
					<i class="fa fa-question-circle"></i>
					<?php esc_html_e( 'Hint', 'eduma' ); ?>
				</span>
			</p>

			<div class="quiz-hint-content">
				<?php the_content(); ?>
			</div>
		</div>
		<?php
	endif;
	wp_reset_postdata();

}

add_action( 'learn_press_after_question_title', 'thim_add_question_hint' );

/**
 * Add question index
 *
 * @param $id
 */
function thim_add_question_index( $id ) {
	$index = 1;
	if ( is_singular( 'lpr_quiz' ) ) {
		$quiz = get_the_ID();
	} else {
		if ( isset( $_REQUEST['quiz_id'] ) && $_REQUEST['quiz_id'] ) {
			$quiz = $_REQUEST['quiz_id'];
		} else {
			return;
		}
	}
	$quiz  = get_post_meta( $quiz, '_lpr_quiz_questions', true );
	$quiz  = array_keys( $quiz );
	$index = array_search( $id, $quiz ) + 1;
	echo '<p class="index-question">' . esc_html__( 'Question', 'eduma' ) . ' ' . '<span class="number">' . $index . '&#47;' . count( $quiz ) . '</span></p>';
}

add_action( 'learn_press_before_question_title', 'thim_add_question_index' );

/**
 *
 */
function thim_course_content_lesson_action() {
	if ( learn_press_user_has_completed_lesson() ) {
		echo '<p class="message-success">' . esc_html__( 'You have completed this lesson.', 'eduma' ) . '</p>';
	} else {
		$course_id = learn_press_get_course_by_lesson( get_the_ID() );
		if ( !learn_press_user_has_finished_course( $course_id ) && learn_press_user_has_enrolled_course( $course_id ) ) {
			printf( '<button class="complete-lesson-button" data-id="%d">%s</button>', esc_attr( get_the_ID() ), esc_html__( 'Complete Lesson', 'eduma' ) );
		}
	}
}

add_action( 'learn_press_course_content_lesson', 'thim_course_content_lesson_action', 10 );
add_action( 'learn_press_course_content_lesson', 'learn_press_course_content_next_prev_lesson', 15 );

/**
 * Check answer
 *
 * @param $id
 * @param $answers
 *
 * @return string
 */
function thim_check_answer( $id, $answers ) {
	$question = LPR_Question_Type::instance( $id );
	if ( $question && isset( $answers[$id] ) ) {
		$check = $question->check( array( 'answer' => $answers[$id] ) );
		if ( $check['correct'] ) {
			return 'correct';
		} else {
			return 'incorrect';
		}
	} else {
		return 'skipped';
	}
}

/**
 * Wishlist button for LearnPress
 *
 * @param $course_id
 */
function thim_course_wishlist_button( $course_id = null ) {
	if ( function_exists( 'learn_press_course_wishlist_button' ) ) {
		if ( get_current_user_id() ) {
			echo '<div class="course-wishlist-box">';
			learn_press_course_wishlist_button( $course_id );
			echo '</div>';
		}
	}
}

/**
 * Display the link to course forum
 */
function thim_course_forum_link() {

	if ( thim_plugin_active( 'bbpress/bbpress.php' ) && thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) ) {
		/*
		do_action( 'learn_press_before_course_forum' );
		if ( learn_press_is_connect_forum( get_the_ID() ) ) {
			printf(
				'<div class="forum-link">
					<label>%s</label>
					<div class="value">
						<a href="%s">%s</a>
					</div>
				</div>',
				esc_html__( 'Connect', 'eduma' ),
				learn_press_get_forum_link( get_the_ID() ),
				apply_filters( 'learn_press_forum_link_text', esc_html__( 'Forum', 'eduma' ) )
			);
		}
		do_action( 'learn_press_after_course_forum' );*/
	}
}

/**
 * Display co instructors
 *
 * @param $course_id
 */
if ( !function_exists( 'thim_co_instructors' ) ) {
	function thim_co_instructors( $course_id, $author_id ) {
		if ( !$course_id ) {
			return;
		}
		//var_dump(the_author_meta('ID'));

		if ( thim_plugin_active( 'learnpress-co-instructor/learnpress-co-instructor.php' ) ) {
			$instructors = get_post_meta( $course_id, '_lpr_co_teacher' );
			$instructors = array_diff( $instructors, array( $author_id ) );
			if ( $instructors ) {
				foreach ( $instructors as $instructor ) {
					$lp_info = get_the_author_meta( 'lp_info', $instructor );
					$link    = apply_filters( 'learn_press_instructor_profile_link', '#', $instructor, '' );
					?>
					<div class="thim-about-author thim-co-instructor" itemprop="contributor" itemscope itemtype="http://schema.org/Person">
						<div class="author-wrapper">
							<div class="author-avatar">
								<?php echo get_avatar( $instructor, 110 ); ?>
							</div>
							<div class="author-bio">
								<div class="author-top">
									<a itemprop="url" class="name" href="<?php echo esc_url( $link ); ?>">
										<span itemprop="name"><?php echo get_the_author_meta( 'display_name', $instructor ); ?></span>
									</a>
									<?php if ( isset( $lp_info['major'] ) && $lp_info['major'] ) : ?>
										<p class="job" itemprop="jobTitle"><?php echo esc_html( $lp_info['major'] ); ?></p>
									<?php endif; ?>
								</div>
								<ul class="thim-author-social">
									<?php if ( isset( $lp_info['facebook'] ) && $lp_info['facebook'] ) : ?>
										<li>
											<a href="<?php echo esc_url( $lp_info['facebook'] ); ?>" class="facebook"><i class="fa fa-facebook"></i></a>
										</li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['twitter'] ) && $lp_info['twitter'] ) : ?>
										<li>
											<a href="<?php echo esc_url( $lp_info['twitter'] ); ?>" class="twitter"><i class="fa fa-twitter"></i></a>
										</li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['google'] ) && $lp_info['google'] ) : ?>
										<li>
											<a href="<?php echo esc_url( $lp_info['google'] ); ?>" class="google-plus"><i class="fa fa-google-plus"></i></a>
										</li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['linkedin'] ) && $lp_info['linkedin'] ) : ?>
										<li>
											<a href="<?php echo esc_url( $lp_info['linkedin'] ); ?>" class="linkedin"><i class="fa fa-linkedin"></i></a>
										</li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['youtube'] ) && $lp_info['youtube'] ) : ?>
										<li>
											<a href="<?php echo esc_url( $lp_info['youtube'] ); ?>" class="youtube"><i class="fa fa-youtube"></i></a>
										</li>
									<?php endif; ?>
								</ul>

							</div>
							<div class="author-description" itemprop="description">
								<?php echo get_the_author_meta( 'description', $instructor ); ?>
							</div>
						</div>
					</div>
					<?php
				}
			}
		}
	}

}

/**
 * Display feature certificate
 *
 * @param $course_id
 */
function thim_course_certificate( $course_id ) {

	if ( thim_plugin_active( 'learnpress-certificates/learnpress-certificates.php' ) ) {
		?>
		<li>
			<i class="fa fa-rebel"></i>
			<span class="label"><?php esc_html_e( 'Certificate', 'eduma' ); ?></span>
			<span class="value"><?php echo ( get_post_meta( $course_id, '_lpr_course_certificate', true ) ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'No', 'eduma' ); ?></span>
		</li>
		<?php
	}
}

/**
 * Get number of courses by search key
 *
 * @param $search_key
 *
 * @return int
 */
if ( !function_exists( 'thim_get_courses_by_search_key' ) ) {
	function thim_get_courses_by_search_key( $search_key ) {
		$query = new WP_Query( array(
			'post_type'           => 'lpr_course',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => - 1,
			's'                   => $search_key
		) );

		if ( !empty( $query->post_count ) ) {
			return $query->post_count;
		}

		return 0;
	}
}

function thim_require_login_to_take_course( $can_take, $user_id, $course_id, $payment_method ) {
	if ( !is_user_logged_in() ) {
		$login_url = thim_get_login_page_url();
		learn_press_send_json(
			array(
				'result'   => 'success',
				'redirect' => $login_url . '?redirect_to=' . htmlentities( urlencode( get_permalink( $course_id ) ) )
			)
		);
	}
	return $can_take;
}

add_filter( 'learn_press_before_take_course', 'thim_require_login_to_take_course', 4, 4 );