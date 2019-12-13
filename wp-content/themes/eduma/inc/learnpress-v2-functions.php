<?php
/**
 * Custom functions for LearnPress 2.x
 *
 * @package thim
 */


if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {

		remove_action( 'learn_press_before_main_content', 'learn_press_breadcrumb' );
		remove_action( 'learn_press_after_the_title', 'learn_press_course_thumbnail', 10 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_begin_meta', 10 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_instructor', 15 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_students', 20 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_price', 25 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_end_meta', 30 );
		//remove_action( 'wp_logout', '_learn_press_redirect_logout_redirect' );
		remove_action( 'learn_press_before_main_content', 'learn_press_search_form' );
		remove_action( 'learn_press/after_course_item_content', 'learn_press_course_item_edit_link' );
		remove_action( 'learn_press/after_course_item_content', 'learn_press_course_nav_items' );

		if ( thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) && class_exists( 'LP_Addon_Course_Review' ) ) {
			$addon_review = LP_Addon_Course_Review::instance();
			remove_action( 'learn_press_after_the_title', array( $addon_review, 'print_rate' ), 10 );
			remove_action( 'learn_press_content_learning_summary', array( $addon_review, 'print_review' ), 80 );
			remove_action( 'learn_press_content_learning_summary', array( $addon_review, 'add_review_button' ), 5 );
			remove_action( 'learn_press_content_landing_summary', array( $addon_review, 'print_review' ), 80 );
		}

		if ( thim_plugin_active( 'learnpress-wishlist/learnpress-wishlist.php' && class_exists( 'LP_Addon_Wishlist' ) ) && is_user_logged_in() ) {
			$addon_wishlist = LP_Addon_Wishlist::instance();
			remove_action( 'learn_press_content_learning_summary', array( $addon_wishlist, 'wishlist_button' ), 100 );
		}

		if ( thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' && class_exists( 'LP_Addon_BBPress_Course_Forum' ) ) ) {
			$addon_bbpress = LP_Addon_BBPress_Course_Forum::instance();
			remove_action( 'learn_press_after_single_course_summary', array( $addon_bbpress, 'forum_link' ) );
		}

		if ( thim_plugin_active( 'learnpress-certificates/learnpress-certificates.php' && class_exists( 'LP_Addon_Certificates' ) ) ) {
			$addon_certificate = LP_Addon_Certificates::instance();
			remove_action( 'learn_press_content_learning_summary', array( $addon_certificate, 'popup_cert' ), 70 );
			remove_action( 'learn_press_after_course_buttons', array( $addon_certificate, 'popup_cert' ), 70 );
			add_action( 'thim_end_curriculum_button', array( $addon_certificate, 'popup_cert' ) );
		}

		//Single course
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_meta_start_wrapper', 15 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_price', 25 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_students', 30 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_instructor', 30 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_meta_end_wrapper', 35 );
		//remove_action( 'learn_press_content_landing_summary', 'learn_press_single_course_content_lesson', 40 );
		//remove_action( 'learn_press_content_landing_summary', 'learn_press_single_course_content_item', 40 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_progress', 60 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_tabs', 50 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_buttons', 70 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_students_list', 75 );

		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_meta_start_wrapper', 10 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_status', 15 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_instructor', 20 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_students', 25 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_meta_end_wrapper', 30 );
		//remove_action( 'learn_press_content_learning_summary', 'learn_press_single_course_content_lesson', 35 );
		//remove_action( 'learn_press_content_learning_summary', 'learn_press_single_course_content_item', 40 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_progress', 45 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_tabs', 50 );
		//remove_action( 'learn_press_content_learning_summary', 'learn_press_course_remaining_time', 55 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_buttons', 65 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_students_list', 75 );

		//remove_filter( 'learn_press_course_tabs', '_learn_press_default_course_tabs', 5 );

		//Profile page
		remove_action( 'learn_press_user_profile_summary', 'learn_press_output_user_profile_info', 5, 3 );


//		add_action( 'learn_press_content_learning_summary', 'learn_press_single_course_content_lesson', 35 );
//		add_action( 'learn_press_content_learning_summary', 'learn_press_single_course_content_item', 40 );
//		add_action( 'learn_press_content_learning_summary', 'learn_press_course_tabs', 50 );
//		add_action( 'learn_press_content_learning_summary', 'learn_press_course_remaining_time', 55 );
//		add_action( 'learn_press_content_learning_summary', 'learn_press_course_curriculum_popup', 60 );


//		add_action( 'learn_press_content_landing_summary', 'learn_press_single_course_content_lesson', 40 );
//		add_action( 'learn_press_content_landing_summary', 'learn_press_single_course_content_item', 40 );
//		add_action( 'learn_press_content_landing_summary', 'learn_press_course_progress', 60 );
//		add_action( 'learn_press_content_landing_summary', 'learn_press_course_tabs', 50 );
//		add_action( 'learn_press_content_landing_summary', 'learn_press_course_curriculum_popup', 65 );


//		remove_action( 'learn_press_before_main_content', 'learn_press_breadcrumb', 10 );
//		remove_action( 'learn_press_after_the_title', 'learn_press_course_thumbnail', 10 );
//		remove_action( 'learn_press_courses_loop_item_title', 'learn_press_courses_loop_item_title', 10 );
//		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_thumbnail', 10 );
//		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_price', 15 );
//		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_students', 20 );
//		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_instructor', 25 );
//		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_introduce', 30 );
//
//
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_thumbnail', 5 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_title', 10 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_meta_start_wrapper', 15 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_price', 25 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_students', 30 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_meta_end_wrapper', 35 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_enroll_button', 45 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_single_course_description', 55 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_progress', 60 );
//		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_curriculum', 65 );
//
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_thumbnail', 5 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_meta_start_wrapper', 10 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_status', 15 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_instructor', 20 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_students', 25 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_meta_end_wrapper', 30 );
//
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_single_course_description', 35 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_progress', 45 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_finish_button', 50 );
//		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_curriculum', 55 );
//
//		remove_all_actions( 'learn_press_after_single_course_summary', 100 );
//
//		remove_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_sidebar', 45 );
//		remove_action( 'learn_press_single_quiz_sidebar', 'learn_press_single_quiz_timer', 5 );
//		remove_action( 'learn_press_single_quiz_sidebar', 'learn_press_single_quiz_buttons', 10 );
//		remove_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_questions_nav', 25 );
//		remove_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_questions', 30 );
//		add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_questions_nav', 21 );
//		add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_buttons', 22 );
//		add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_questions', 25 );
//		add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_timer', 12 );
//
//
//		// Remove register page from BuddyPress
//		remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );
//
//		remove_action( 'learn_press_after_question_wrap', array( 'LP_Question_Factory', 'show_hint' ), 100, 2 );

	}
}

add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks', 15 );

if ( ! function_exists( 'thim_learnpress_page_title' ) ) {
	function thim_learnpress_page_title( $echo = true ) {
		$title = '';
		if ( get_post_type() == 'lp_course' && ! is_404() && ! is_search() || learn_press_is_courses() || learn_press_is_course_taxonomy() ) {
			if ( learn_press_is_course_taxonomy() ) {
				$title = learn_press_single_term_title( '', false );
			} else {
				$title = esc_html__( 'All Courses', 'eduma' );
			}
		}
		if ( get_post_type() == 'lp_quiz' && ! is_404() && ! is_search() ) {
			if ( is_tax() ) {
				$title = learn_press_single_term_title( '', false );
			} else {
				$title = esc_html__( 'Quiz', 'eduma' );
			}
		}
		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}
}

/**
 * Breadcrumb for LearnPress
 */
if ( ! function_exists( 'thim_learnpress_breadcrumb' ) ) {
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

			if ( get_post_type() == 'lp_course' ) {
				// All courses
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';
			} else {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '" title="' . esc_attr( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '"><span itemprop="name">' . esc_html( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '</span></a></li>';
			}

			// Single post (Only display the first category)
			if ( isset( $categories[0] ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $categories[0] ) ) . '" title="' . esc_attr( $categories[0]->name ) . '"><span itemprop="name">' . esc_html( $categories[0]->name ) . '</span></a></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';

		} else if ( learn_press_is_course_taxonomy() || learn_press_is_course_tag() ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';

			// Category page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( learn_press_single_term_title( '', false ) ) . '">' . esc_html( learn_press_single_term_title( '', false ) ) . '</span></li>';
		} else if ( ! empty( $_REQUEST['s'] ) && ! empty( $_REQUEST['ref'] ) && ( $_REQUEST['ref'] == 'course' ) ) {
			// All courses
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a></li>';

			// Search result
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Search results for:', 'eduma' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'eduma' ) . ' ' . esc_html( get_search_query() ) . '</span></li>';
		} else {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'All courses', 'eduma' ) . '">' . esc_html__( 'All courses', 'eduma' ) . '</span></li>';
		}

		echo '</ul>';
	}
}

//learn_press_is_courses() || learn_press_is_course_taxonomy()

/**
 * Display co instructors
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_co_instructors' ) ) {
	function thim_co_instructors( $course_id, $author_id ) {
		if ( ! $course_id ) {
			return;
		}

		if ( thim_plugin_active( 'learnpress-co-instructor/learnpress-co-instructor.php' ) ) {
			$instructors = get_post_meta( $course_id, '_lp_co_teacher' );
			$instructors = array_diff( $instructors, array( $author_id ) );
			if ( $instructors ) {
				foreach ( $instructors as $instructor ) {
					//Check if instructor not exist
					$user = get_userdata( $instructor );
					if ( $user === false ) {
						break;
					}
					$lp_info = get_the_author_meta( 'lp_info', $instructor );
					$link    = learn_press_user_profile_link( $instructor );
					?>
                    <div class="thim-about-author thim-co-instructor" itemprop="contributor" itemscope
                         itemtype="http://schema.org/Person">
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
                                        <p class="job"
                                           itemprop="jobTitle"><?php echo esc_html( $lp_info['major'] ); ?></p>
									<?php endif; ?>
                                </div>
                                <ul class="thim-author-social">
									<?php if ( isset( $lp_info['facebook'] ) && $lp_info['facebook'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['facebook'] ); ?>" class="facebook"><i
                                                        class="fa fa-facebook"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['twitter'] ) && $lp_info['twitter'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['twitter'] ); ?>" class="twitter"><i
                                                        class="fa fa-twitter"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['google'] ) && $lp_info['google'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['google'] ); ?>"
                                               class="google-plus"><i class="fa fa-google-plus"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['linkedin'] ) && $lp_info['linkedin'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['linkedin'] ); ?>" class="linkedin"><i
                                                        class="fa fa-linkedin"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( isset( $lp_info['youtube'] ) && $lp_info['youtube'] ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $lp_info['youtube'] ); ?>" class="youtube"><i
                                                        class="fa fa-youtube"></i></a>
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


if ( ! function_exists( 'thim_course_wishlist_button' ) ) {
	function thim_course_wishlist_button( $course_id = null ) {
		if ( ! thim_plugin_active( 'learnpress-wishlist/learnpress-wishlist.php' ) ) {
			return;
		}
		LP_Addon_Wishlist::instance()->wishlist_button( $course_id );

	}
}

/**
 * Display ratings count
 */

if ( ! function_exists( 'thim_course_ratings_count' ) ) {
	function thim_course_ratings_count( $course_id = null ) {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}
		if ( ! $course_id ) {
			$course_id = get_the_ID();
		}
		$ratings = learn_press_get_course_rate_total( $course_id ) ? learn_press_get_course_rate_total( $course_id ) : 0;
		echo '<div class="course-comments-count">';
		echo '<div class="value"><i class="fa fa-comment"></i>';
		echo esc_html( $ratings );
		echo '</div>';
		echo '</div>';
	}
}

/**
 * Create ajax handle for courses searching
 */
if ( ! function_exists( 'thim_courses_searching_callback' ) ) {
	function thim_courses_searching_callback() {
		ob_start();
		$keyword = $_REQUEST['keyword'];
		if ( $keyword ) {
			$keyword   = strtoupper( $keyword );
			$arr_query = array(
				'post_type'           => 'lp_course',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				's'                   => $keyword,
				'posts_per_page'      => '-1'
			);

			$search = new WP_Query( $arr_query );

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
}

add_action( 'wp_ajax_nopriv_courses_searching', 'thim_courses_searching_callback' );
add_action( 'wp_ajax_courses_searching', 'thim_courses_searching_callback' );

/**
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings' ) ) {
	function thim_course_ratings() {

		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
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
}

if ( ! function_exists( 'thim_print_rating' ) ) {
	function thim_print_rating( $rate ) {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

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
}

/**
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings_meta' ) ) {
    function thim_course_ratings_meta() {

        if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
            return;
        }

        $course_id   = get_the_ID();
        $course_rate = learn_press_get_course_rate( $course_id );
        $ratings     = learn_press_get_course_rate_total( $course_id );
        ?>
        <div class="course-review">
            <label><?php esc_html_e( 'Review', 'eduma' ); ?></label>

            <div class="value">
                <?php echo $course_rate; ?> <?php esc_html_e( 'Stars', 'eduma' ); ?>
                <span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'eduma' ), number_format_i18n( $ratings ) ) : esc_html_e( '(0 review)', 'eduma' ); ?></span>
            </div>
        </div>
        <?php
    }
}

/**
 * Display number students
 */
if ( ! function_exists( 'thim_course_number_students' ) ) {
    function thim_course_number_students() {

        $course = LP()->global['course'];
        $user_count = $course->count_users_enrolled( 'append' ) ? $course->count_users_enrolled( 'append' ) : 0;
        ?>
        <div class="course-students">
            <label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
            <div class="value">
                <?php echo $user_count;?> <?php esc_html_e( 'Enrolled', 'eduma' ); ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Display last update Course
 */
if ( ! function_exists( 'thim_course_last_update' ) ) {
    function thim_course_last_update() {
        ?>
        <div class="course-last-update">
            <label><?php esc_html_e( 'Last update', 'eduma' ); ?></label>
            <div class="value">
                <?php the_modified_date();?>
            </div>
        </div>
        <?php
    }
}


/**
 * Display related courses
 */
if ( ! function_exists( 'thim_related_courses' ) ) {
	function thim_related_courses() {
		$related_courses = thim_get_related_courses( 5 );
        $theme_options_data = get_theme_mods();
        $style_content = isset($theme_options_data['thim_layout_content_page']) ? $theme_options_data['thim_layout_content_page'] : 'normal';

        if ( $related_courses ) {
			$ids = wp_list_pluck( $related_courses, 'ID' );
			_learn_press_count_users_enrolled_courses( $ids );
            $layout_grid = get_theme_mod('thim_learnpress_cate_layout_grid', '');
            $cls_layout = ($layout_grid!='' && $layout_grid!='layout_courses_1') ? ' cls_courses_2' : ' ';
			?>
            <div class="thim-ralated-course <?php echo $cls_layout;?>">

                <?php if( $style_content == 'new-1' ) {?>
                    <div class="sc_heading clone_title  text-left">
                        <h2 class="title"><?php esc_html_e( 'You May Like', 'eduma' ); ?></h2>
                        <div class="clone"><?php esc_html_e( 'You May Like', 'eduma' ); ?></div>
                    </div>
                <?php } else {?>
                    <h3 class="related-title">
                        <?php esc_html_e( 'You May Like', 'eduma' ); ?>
                    </h3>
                <?php }?>

                <div class="thim-course-grid">
                    <div class="thim-carousel-wrapper" data-visible="3" data-itemtablet="2" data-itemmobile="1" data-pagination="1">
                        <?php foreach ( $related_courses as $course_item ) : ?>
                            <?php
                            $course      = LP_Course::get_course( $course_item->ID );
                            $is_required = $course->is_required_enroll();
                            ?>
                            <article class="lpr_course">
                                <div class="course-item">
                                    <div class="course-thumbnail">
                                        <a class="thumb" href="<?php echo get_the_permalink( $course_item->ID ); ?>">
                                            <?php
                                            if ( $layout_grid!='' && $layout_grid!='layout_courses_1' ) {
                                                echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 320, 220, get_the_title( $course_item->ID ) );
                                            } else {
                                                echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 450, 450, get_the_title( $course_item->ID ) );
                                            }
                                            ?>
                                        </a>
                                        <?php thim_course_wishlist_button( $course_item->ID ); ?>
                                        <?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course_item->ID ) ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
                                    </div>
                                    <div class="thim-course-content">
                                        <div class="course-author">
                                            <?php echo get_avatar( $course_item->post_author, 40 ); ?>
                                            <div class="author-contain">
                                                <div class="value">
                                                    <a href="<?php echo esc_url( learn_press_user_profile_link( $course_item->post_author ) ); ?>">
                                                        <?php echo get_the_author_meta( 'display_name', $course_item->post_author ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="course-title">
                                            <a rel="bookmark"
                                               href="<?php echo get_the_permalink( $course_item->ID ); ?>"><?php echo esc_html( $course_item->post_title ); ?></a>
                                        </h2> <!-- .entry-header -->
                                        <div class="course-meta">
                                            <?php
                                            $count_student = $course->count_users_enrolled( 'append' ) ? $course->count_users_enrolled( 'append' ) : 0;
                                            ?>
                                            <div class="course-students">
                                                <label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
                                                <?php do_action( 'learn_press_begin_course_students' ); ?>

                                                <div class="value"><i class="fa fa-group"></i>
                                                    <?php echo esc_html( $count_student ); ?>
                                                </div>
                                                <?php do_action( 'learn_press_end_course_students' ); ?>

                                            </div>
                                            <?php thim_course_ratings_count( $course_item->ID ); ?>
                                            <?php if ( $price = $course->get_price_html() ) {

                                                $origin_price = $course->get_origin_price_html();
                                                $sale_price   = $course->get_sale_price();
                                                $sale_price   = isset( $sale_price ) ? $sale_price : '';
                                                $class        = '';
                                                if ( $course->is_free() || ! $is_required ) {
                                                    $class .= ' free-course';
                                                    $price = esc_html__( 'Free', 'eduma' );
                                                }

                                                ?>

                                                <div class="course-price" itemprop="offers" itemscope
                                                     itemtype="http://schema.org/Offer">
                                                    <div class="value<?php echo $class; ?>" itemprop="price">
                                                        <?php
                                                        if ( $sale_price !== '' ) {
                                                            echo '<span class="course-origin-price">' . $origin_price . '</span>';
                                                        }
                                                        ?>
                                                        <?php echo $price; ?>
                                                    </div>
                                                    <meta itemprop="priceCurrency"
                                                          content="<?php echo learn_press_get_currency(); ?>"/>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'thim_get_related_courses' ) ) {
	function thim_get_related_courses( $limit ) {
		if ( ! $limit ) {
			$limit = 3;
		}
		$course_id = get_the_ID();

		$tag_ids = array();
		$tags    = get_the_terms( $course_id, 'course_tag' );

		if ( $tags ) {
			foreach ( $tags as $individual_tag ) {
				$tag_ids[] = $individual_tag->slug;
			}
		}

		$args = array(
			'posts_per_page'      => $limit,
			'paged'               => 1,
			'ignore_sticky_posts' => 1,
			'post__not_in'        => array( $course_id ),
			'post_type'           => 'lp_course'
		);

		if ( $tag_ids ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'course_tag',
					'field'    => 'slug',
					'terms'    => $tag_ids
				)
			);
		}
		$related = array();
		if ( $posts = new WP_Query( $args ) ) {
			global $post;
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$related[] = $post;
			}
		}
		wp_reset_query();

		return $related;
	}
}


/**
 * Display the link to course forum
 */
if ( ! function_exists( 'thim_course_forum_link' ) ) {
	function thim_course_forum_link() {

		if ( thim_plugin_active( 'bbpress/bbpress.php' ) && thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) ) {
			LP_Addon_BBPress_Course_Forum::instance()->forum_link();
		}
	}
}


/**
 * Add some meta data for a course
 *
 * @param $meta_box
 */
if ( ! function_exists( 'thim_add_course_meta' ) ) {
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
			'name' => esc_html__( 'Skill Levels', 'eduma' ),
			'id'   => 'thim_course_skill_level',
			'type' => 'text',
			'desc' => esc_html__( 'A possible level with this course', 'eduma' ),
			'std'  => esc_html__( 'All levels', 'eduma' )
		);
		$fields[]           = array(
			'name' => esc_html__( 'Languages', 'eduma' ),
			'id'   => 'thim_course_language',
			'type' => 'text',
			'desc' => esc_html__( 'Language\'s used for studying', 'eduma' ),
			'std'  => esc_html__( 'English', 'eduma' )
		);
		$fields[]           = array(
			'name' => esc_html__( 'Media Intro', 'eduma' ),
			'id'   => 'thim_course_media_intro',
			'type' => 'textarea',
			'desc' => esc_html__( 'Enter media intro', 'eduma' ),
		);
		$meta_box['fields'] = $fields;

		return $meta_box;
	}

}

add_filter( 'learn_press_course_settings_meta_box_args', 'thim_add_course_meta' );


if ( ! function_exists( 'thim_add_lesson_meta' ) ) {
	function thim_add_lesson_meta( $meta_box ) {
		$fields             = $meta_box['fields'];
		$fields[]           = array(
			'name' => esc_html__( 'Media', 'eduma' ),
			'id'   => '_lp_lesson_video_intro',
			'type' => 'textarea',
			'desc' => esc_html__( 'Add an embed link like video, PDF, slider...', 'eduma' ),
		);
		$meta_box['fields'] = $fields;

		return $meta_box;
	}
}
add_filter( 'learn_press_lesson_meta_box_args', 'thim_add_lesson_meta' );


/**
 * Display course info
 */
if ( ! function_exists( 'thim_course_info' ) ) {
	function thim_course_info() {
		$course    = LP()->global['course'];
		$course_id = get_the_ID();

		$course_duration    = get_post_meta( $course_id, 'thim_course_duration', true );
		$course_skill_level = get_post_meta( $course_id, 'thim_course_skill_level', true );
		$course_language    = get_post_meta( $course_id, 'thim_course_language', true );

		?>
        <div class="thim-course-info">
            <h3 class="title"><?php esc_html_e( 'Course Features', 'eduma' ); ?></h3>
            <ul>
                <li class="lectures-feature">
                    <i class="fa fa-files-o"></i>
                    <span class="label"><?php esc_html_e( 'Lectures', 'eduma' ); ?></span>
                    <span class="value"><?php echo $course->get_lessons() ? count( $course->get_lessons() ) : 0; ?></span>
                </li>
                <li class="quizzes-feature">
                    <i class="fa fa-puzzle-piece"></i>
                    <span class="label"><?php esc_html_e( 'Quizzes', 'eduma' ); ?></span>
                    <span class="value"><?php echo $course->get_quizzes() ? count( $course->get_quizzes() ) : 0; ?></span>
                </li>
				<?php if ( ! empty( $course_duration ) ): ?>
                    <li class="duration-feature">
                        <i class="fa fa-clock-o"></i>
                        <span class="label"><?php esc_html_e( 'Duration', 'eduma' ); ?></span>
                        <span class="value"><?php echo esc_html( $course_duration ); ?></span>
                    </li>
				<?php endif; ?>
				<?php if ( ! empty( $course_skill_level ) ): ?>
                    <li class="skill-feature">
                        <i class="fa fa-level-up"></i>
                        <span class="label"><?php esc_html_e( 'Skill level', 'eduma' ); ?></span>
                        <span class="value"><?php echo esc_html( $course_skill_level ); ?></span>
                    </li>
				<?php endif; ?>
				<?php if ( ! empty( $course_language ) ): ?>
                    <li class="language-feature">
                        <i class="fa fa-language"></i>
                        <span class="label"><?php esc_html_e( 'Language', 'eduma' ); ?></span>
                        <span class="value"><?php echo esc_html( $course_language ); ?></span>
                    </li>
				<?php endif; ?>
                <li class="students-feature">
                    <i class="fa fa-users"></i>
                    <span class="label"><?php esc_html_e( 'Students', 'eduma' ); ?></span>
					<?php $user_count = $course->count_users_enrolled( 'append' ) ? $course->count_users_enrolled( 'append' ) : 0; ?>
                    <span class="value"><?php echo esc_html( $user_count ); ?></span>
                </li>
				<?php thim_course_certificate( $course_id ); ?>
                <li class="assessments-feature">
                    <i class="fa fa-check-square-o"></i>
                    <span class="label"><?php esc_html_e( 'Assessments', 'eduma' ); ?></span>
                    <span class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'evaluate_lesson' ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'Self', 'eduma' ); ?></span>
                </li>
            </ul>
			<?php thim_course_wishlist_button(); ?>
        </div>
		<?php
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
        <li class="cert-feature">
            <i class="fa fa-rebel"></i>
            <span class="label"><?php esc_html_e( 'Certificate', 'eduma' ); ?></span>
            <span class="value"><?php echo ( get_post_meta( $course_id, '_lp_cert', true ) ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'No', 'eduma' ); ?></span>
        </li>
		<?php
	}
}

/**
 * Display course review
 */
if ( ! function_exists( 'thim_course_review' ) ) {
	function thim_course_review() {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		$course_id     = get_the_ID();
		$course_review = learn_press_get_course_review( $course_id, isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1, 5, true );
		$course_rate   = learn_press_get_course_rate( $course_id );
		$total         = learn_press_get_course_rate_total( $course_id );
		$reviews       = $course_review['reviews'];

		?>
        <div class="course-rating">
            <h3><?php esc_html_e( 'Reviews', 'eduma' ); ?></h3>

            <div class="average-rating" itemprop="aggregateRating" itemscope=""
                 itemtype="http://schema.org/AggregateRating">
                <p class="rating-title"><?php esc_html_e( 'Average Rating', 'eduma' ); ?></p>

                <div class="rating-box">
                    <div class="average-value"
                         itemprop="ratingValue"><?php echo ( $course_rate ) ? esc_html( round( $course_rate, 1 ) ) : 0; ?></div>
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
                            <div class="review-container" itemprop="review" itemscope
                                 itemtype="http://schema.org/Review">
                                <div class="review-author">
									<?php echo get_avatar( $review->ID, 70 ); ?>
                                </div>
                                <div class="review-text">
                                    <h4 class="author-name"
                                        itemprop="author"><?php echo esc_html( $review->display_name ); ?></h4>

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
                <span id="course-review-load-more" data-paged="<?php echo esc_attr( $course_review['paged'] ); ?>"><i
                            class="fa fa-angle-double-down"></i></span>
            </div>
		<?php endif; ?>
		<?php thim_review_button( $course_id ); ?>
		<?php
	}
}

/**
 * Display review button
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_review_button' ) ) {
	function thim_review_button( $course_id ) {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		if ( ! get_current_user_id() ) {
			return;
		}
		if ( LP()->user->has( 'enrolled-course', $course_id ) || get_post_meta( $course_id, '_lp_required_enroll', true ) == 'no' ) {
			if ( ! learn_press_get_user_rate( $course_id ) ) {
				?>
                <div class="add-review">
                    <h3 class="title"><?php esc_html_e( 'Leave A Review', 'eduma' ); ?></h3>

                    <p class="description"><?php esc_html_e( 'Please provide as much detail as you can to justify your rating and to help others.', 'eduma' ); ?></p>
					<?php do_action( 'learn_press_before_review_fields' ); ?>
                    <form method="post">
                        <div>
                            <label for="review-title"><?php esc_html_e( 'Title', 'eduma' ); ?>
                                <span class="required">*</span></label>
                            <input required type="text" id="review-title" name="review-course-title"/>
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
                        <input type="hidden" id="review-course-value" name="review-course-value" value="5"/>
                        <input type="hidden" id="comment_post_ID" name="comment_post_ID"
                               value="<?php echo get_the_ID(); ?>"/>
                        <button type="submit"><?php esc_html_e( 'Submit Review', 'eduma' ); ?></button>
                    </form>
					<?php do_action( 'learn_press_after_review_fields' ); ?>
                </div>
				<?php
			}
		}
	}
}

/**
 * Process review
 */
if ( ! function_exists( 'thim_process_review' ) ) {
	function thim_process_review() {

		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		$user_id     = get_current_user_id();
		$course_id   = isset ( $_POST['comment_post_ID'] ) ? $_POST['comment_post_ID'] : 0;
		$user_review = learn_press_get_user_rate( $course_id, $user_id );
		if ( ! $user_review && $course_id ) {
			$review_title   = isset ( $_POST['review-course-title'] ) ? $_POST['review-course-title'] : 0;
			$review_content = isset ( $_POST['review-course-content'] ) ? $_POST['review-course-content'] : 0;
			$review_rate    = isset ( $_POST['review-course-value'] ) ? $_POST['review-course-value'] : 0;
			learn_press_add_course_review( array(
				'title'     => $review_title,
				'content'   => $review_content,
				'rate'      => $review_rate,
				'user_id'   => $user_id,
				'course_id' => $course_id
			) );
		}
	}
}
add_action( 'learn_press_before_main_content', 'thim_process_review' );


/**
 * Display table detailed rating
 *
 * @param $course_id
 * @param $total
 */
if ( ! function_exists( 'thim_detailed_rating' ) ) {
	function thim_detailed_rating( $course_id, $total ) {
		global $wpdb;
		$query = $wpdb->get_results( $wpdb->prepare(
			"
		SELECT cm2.meta_value AS rating, COUNT(*) AS quantity FROM $wpdb->posts AS p
		INNER JOIN $wpdb->comments AS c ON p.ID = c.comment_post_ID
		INNER JOIN $wpdb->users AS u ON u.ID = c.user_id
		INNER JOIN $wpdb->commentmeta AS cm1 ON cm1.comment_id = c.comment_ID AND cm1.meta_key=%s
		INNER JOIN $wpdb->commentmeta AS cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key=%s
		WHERE p.ID=%d AND c.comment_type=%s AND c.comment_approved=%s
		GROUP BY cm2.meta_value",
			'_lpr_review_title',
			'_lpr_rating',
			$course_id,
			'review',
			'1'
		), OBJECT_K
		);
		?>
        <div class="detailed-rating">
			<?php for ( $i = 5; $i >= 1; $i -- ) : ?>
                <div class="stars">
                    <div class="key"><?php ( $i === 1 ) ? printf( esc_html__( '%s star', 'eduma' ), $i ) : printf( esc_html__( '%s stars', 'eduma' ), $i ); ?></div>
                    <div class="bar">
                        <div class="full_bar">
                            <div style="<?php echo ( $total && ! empty( $query[ $i ]->quantity ) ) ? esc_attr( 'width: ' . ( $query[ $i ]->quantity / $total * 100 ) . '%' ) : 'width: 0%'; ?>"></div>
                        </div>
                    </div>
                    <div class="value"><?php echo empty( $query[ $i ]->quantity ) ? '0' : esc_html( $query[ $i ]->quantity ); ?></div>
                </div>
			<?php endfor; ?>
        </div>
		<?php
	}
}


/**
 * Filter profile title
 *
 * @param $tab_title
 * @param $key
 *
 * @return string
 */
function thim_tab_profile_filter_title( $tab_title, $key ) {
	switch ( $key ) {
		case 'courses':
			$tab_title = '<i class="fa fa-book"></i><span class="text">' . esc_html__( 'Courses', 'eduma' ) . '</span>';
			break;
		case 'quizzes':
			$tab_title = '<i class="fa fa-check-square-o"></i><span class="text">' . esc_html__( 'Quiz Results', 'eduma' ) . '</span>';
			break;
		case 'orders':
			$tab_title = '<i class="fa fa-shopping-cart"></i><span class="text">' . esc_html__( 'Orders', 'eduma' ) . '</span>';
			break;
		case 'wishlist':
			$tab_title = '<i class="fa fa-heart-o"></i><span class="text">' . esc_html__( 'Wishlist', 'eduma' ) . '</span>';
			break;
		case 'gradebook':
			$tab_title = '<i class="fa fa-book"></i><span class="text">' . esc_html__( 'Gradebook', 'eduma' ) . '</span>';
			break;
		case 'settings':
			$tab_title = '<i class="fa fa-cog"></i><span class="text">' . esc_html__( 'Settings', 'eduma' ) . '</span>';
			break;
		case 'edit':
			$tab_title = '<i class="fa fa-user"></i><span class="text">' . esc_html__( 'Account', 'eduma' ) . '</span>';
			break;
	}

	return $tab_title;
}

add_filter( 'learn_press_profile_edit_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_courses_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_quizzes_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_orders_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_wishlist_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_gradebook_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_settings_tab_title', 'thim_tab_profile_filter_title', 100, 2 );


/**
 * Add format icon before curriculum items
 *
 * @param $lesson_or_quiz
 * @param $enrolled
 */
if ( ! function_exists( 'thim_add_format_icon' ) ) {
	function thim_add_format_icon( $item ) {
		$format = get_post_format( $item->item_id );
		if ( get_post_type( $item->item_id ) == 'lp_quiz' ) {
			echo '<span class="course-format-icon"><i class="fa fa-puzzle-piece"></i></span>';
		} elseif ( $format == 'video' ) {
			echo '<span class="course-format-icon"><i class="fa fa-play-circle"></i></span>';
		} else {
			echo '<span class="course-format-icon"><i class="fa fa-file-o"></i></span>';
		}
	}
}

add_action( 'learn_press_before_section_item_title', 'thim_add_format_icon', 10, 1 );


/**
 * Get number of lessons of a quiz
 *
 * @param $quiz_id
 *
 * @return string
 */
if ( ! function_exists( 'thim_quiz_questions' ) ) {
	function thim_quiz_questions( $quiz_id ) {
		$questions = learn_press_get_quiz_questions( $quiz_id );
		if ( $questions ) {
			return count( $questions );
		}

		return 0;
	}
}

/**
 * Get lesson duration in hours
 *
 * @param $lesson_id
 *
 * @return string
 */

if ( ! function_exists( 'thim_lesson_duration' ) ) {
	function thim_lesson_duration( $lesson_id ) {

		$duration_text = get_post_meta( $lesson_id, '_lp_duration', true );
		$duration      = learn_press_get_course_duration_support();

		$duration_keys = array_keys( $duration );

		if ( preg_match_all( '!([0-9]+)\s*(' . join( '|', $duration_keys ) . ')?!', $duration_text, $matches ) ) {

			$value = $matches[1][0];

			$unit = in_array( $matches[2][0], $duration_keys ) ? $matches[2][0] : '';

		} else {

			$value = absint( $duration_text );

			$unit = '';

		}

		if ( $unit ) {
			switch ($unit) {
				case'minute':
					$unit = __('minute(s)', 'eduma');
					break;
				case 'hour':
					$unit = __('hour(s)', 'eduma');
					break;
				case 'day':
					$unit = __('day(s)', 'eduma');
					break;
				case 'week':
					$unit = __('week(s)', 'eduma');
					break;
				default:
					$unit = __('unknown', 'eduma');
			}
		}
		return  ( $value ) ? ($value . ' ' . $unit): '';
	}
}

/**
 * Breadcrumb for Courses Collection
 */
if ( ! function_exists( 'thim_courses_collection_breadcrumb' ) ) {
	function thim_courses_collection_breadcrumb() {

		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';

		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_html( get_home_url() ) . '" title="' . esc_attr__( 'Home', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'eduma' ) . '</span></a></li>';

		if ( is_single() ) {
			if ( get_post_type() == 'lp_collection' ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_collection' ) ) . '" title="' . esc_attr__( 'Collections', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Collections', 'eduma' ) . '</span></a></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';
		} else {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html__( 'Collections', 'eduma' ) . '</span></li>';
		}

		echo '</ul>';
	}
}


// Tab certificates profile page
if ( thim_plugin_active( 'learnpress-certificates/learnpress-certificates.php' && class_exists( 'LP_Addon_Certificates' ) ) ) {
	if ( ! function_exists( 'thim_update_certificates_tab' ) ) {
		function thim_update_certificates_tab( $tabs, $user ) {
			$certificate                          = LP_Addon_Certificates::instance();
			$tabs[ $certificate->get_tab_slug() ] = array(
				'title'    => '<i class="fa fa-rebel"></i><span class="text">' . esc_html__( 'Certificates', 'eduma' ) . '</span>',
				'callback' => array( $certificate, 'certificates_tab_content' )
			);

			return $tabs;
		}
	}
	add_filter( 'learn_press_user_profile_tabs', 'thim_update_certificates_tab', 200, 2 );
}

/**
 * @param $user
 */
if ( ! function_exists( 'thim_extra_user_profile_fields' ) ) {
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
                    <input id="lp_major" class="regular-text" type="text"
                           value="<?php echo isset( $user_info['major'] ) ? $user_info['major'] : ''; ?>"
                           name="lp_info[major]">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="lp_facebook"><?php esc_html_e( 'Facebook Account', 'eduma' ); ?></label>
                </th>
                <td>
                    <input id="lp_facebook" class="regular-text" type="text"
                           value="<?php echo isset( $user_info['facebook'] ) ? $user_info['facebook'] : ''; ?>"
                           name="lp_info[facebook]">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="lp_twitter"><?php esc_html_e( 'Twitter Account', 'eduma' ); ?></label>
                </th>
                <td>
                    <input id="lp_twitter" class="regular-text" type="text"
                           value="<?php echo isset( $user_info['twitter'] ) ? $user_info['twitter'] : ''; ?>"
                           name="lp_info[twitter]">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="lp_google"><?php esc_html_e( 'Google Plus Account', 'eduma' ); ?></label>
                </th>
                <td>
                    <input id="lp_google" class="regular-text" type="text"
                           value="<?php echo isset( $user_info['google'] ) ? $user_info['google'] : ''; ?>"
                           name="lp_info[google]">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="lp_linkedin"><?php esc_html_e( 'LinkedIn Plus Account', 'eduma' ); ?></label>
                </th>
                <td>
                    <input id="lp_linkedin" class="regular-text" type="text"
                           value="<?php echo isset( $user_info['linkedin'] ) ? $user_info['linkedin'] : ''; ?>"
                           name="lp_info[linkedin]">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="lp_youtube"><?php esc_html_e( 'Youtube Account', 'eduma' ); ?></label>
                </th>
                <td>
                    <input id="lp_youtube" class="regular-text" type="text"
                           value="<?php echo isset( $user_info['youtube'] ) ? $user_info['youtube'] : ''; ?>"
                           name="lp_info[youtube]">
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

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_user_meta( $user_id, 'lp_info', $_POST['lp_info'] );
}

add_action( 'personal_options_update', 'thim_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'thim_save_extra_user_profile_fields' );

function thim_update_user_profile_basic_information() {
	$user_id     = learn_press_get_current_user_id();
	$update_data = array(
		'ID'           => $user_id,
		'first_name'   => filter_input( INPUT_POST, 'first_name', FILTER_SANITIZE_STRING ),
		'last_name'    => filter_input( INPUT_POST, 'last_name', FILTER_SANITIZE_STRING ),
		'display_name' => filter_input( INPUT_POST, 'display_name', FILTER_SANITIZE_STRING ),
		'nickname'     => filter_input( INPUT_POST, 'nickname', FILTER_SANITIZE_STRING ),
		'description'  => filter_input( INPUT_POST, 'description', FILTER_SANITIZE_STRING ),
	);
	update_user_meta( $user_id, 'lp_info', $_POST['lp_info'] );
	$res = wp_update_user( $update_data );
	if ( $res ) {
		$message = __( 'Your change is saved', 'learnpress' );
	} else {
		$message = __( 'Error on update your profile info', 'learnpress' );
	}
	$current_url = learn_press_get_current_url();
	learn_press_add_message( $message );
	wp_redirect( $current_url );
	exit();
}

remove_action( 'learn_press_update_user_profile_basic-information', 'learn_press_update_user_profile_basic_information' );
add_action( 'learn_press_update_user_profile_basic-information', 'thim_update_user_profile_basic_information' );

//Redirect when take course
if ( thim_get_login_page_url() ) {
	remove_action( 'learn_press_before_purchase_course_handler', '_learn_press_before_purchase_course_handler', 10, 2 );
	add_action( 'learn_press_before_purchase_course_handler', 'thim_redirect_url_lp_enroll', 10, 2 );
}
if ( ! function_exists( 'thim_redirect_url_lp_enroll' ) ) {
	function thim_redirect_url_lp_enroll() {
		if ( ! is_user_logged_in() && empty( $_REQUEST['checkout'] ) ) {
			global $post;
			$redirect = apply_filters( 'learn_press_purchase_course_login_redirect', thim_get_login_page_url() );
			if ( $redirect ) {
				if ( $post->ID && get_option( 'permalink_structure' ) ) {
					$link = thim_get_login_page_url() . '?redirect_to=' . urlencode( get_the_permalink( $post->ID ) . '?purchase-course=' . $post->ID );
				} else {
					$link = thim_get_login_page_url();
				}

				if ( $link !== false ) {
					wp_redirect( $link );
					exit();
				}
			}

		} else {
			global $post;
			$user     = learn_press_get_current_user();
			$redirect = false;
			if ( $user->has_finished_course( $post->ID ) ) {
				learn_press_add_message( __( 'You have already finished course', 'eduma' ) );
				$redirect = true;
			} elseif ( $user->has_purchased_course( $post->ID ) ) {
				learn_press_add_message( __( 'You have already enrolled in this course', 'eduma' ) );
				$redirect = true;
			}
			if ( $redirect ) {
				wp_redirect( get_the_permalink( $post->ID ) );
				exit();
			}
		}
	}
}

//Remove fields coming soon meta box
//add_filter( 'rwmb_show_course_coming_soon', '__return_false' );
add_filter( 'rwmb_show_course_video', '__return_false' );

function thim_pmpro_paypal_button_image() {
	return THIM_URI . 'images/paypal.png';
}

add_filter( 'pmpro_paypal_button_image', 'thim_pmpro_paypal_button_image' );

if ( get_theme_mod( 'thim_learnpress_hidden_ads', false ) ) {
	remove_action( 'admin_footer', 'learn_press_advertise_in_admin', - 10 );
}

if ( thim_plugin_active( 'learnpress-paid-membership-pro/learnpress-paid-membership-pro.php' ) ) {
	$addon_paid_membership = get_plugin_data( WP_PLUGIN_DIR . '/learnpress-paid-membership-pro/learnpress-paid-membership-pro.php' );
	if ( ! empty( $addon_paid_membership['Version'] ) && version_compare( $addon_paid_membership['Version'], '2.1.0', '>=' ) ) {
		add_shortcode( 'lp_pmpro_courses', 'thim_learn_press_page_levels_short_code' );
		function thim_learn_press_page_levels_short_code() {
			global $pmpro_page_name;
			if ( function_exists( 'pmpro_loadTemplate' ) ) {
				echo pmpro_loadTemplate( 'levels', 'local', 'pages' );
			}
		}
	}
}

//Remove learn_press_js_template default
remove_action( 'wp_head', 'learn_press_js_template' );

if( !function_exists('show_pass_text') ) {
	function show_pass_text() {
		$user = learn_press_get_current_user();
		$course = LP()->global['course'];
		$grade = $user->get_course_grade( $course->id );
		if($grade == 'passed')
			echo '<div class="message message-success learn-press-success">' . ( __( 'You have finished this course.', 'eduma' ) ) . '</div>';
	}
}
add_action( 'thim_begin_curriculum_button', 'show_pass_text', 90 );


/**
 * Show popular Courses
 */
if ( ! function_exists( 'thim_show_popular_courses' ) ) {
    function thim_show_popular_courses() {
        $show_popular = get_theme_mod('thim_learnpress_cate_show_popular');
        if( $show_popular && is_post_type_archive( 'lp_course' ) ) {
            //Get layout Grid/List Courses
            $layout_grid = get_theme_mod('thim_learnpress_cate_layout_grid', '');
            $cls_layout = ($layout_grid!='' && $layout_grid!='layout_courses_1') ? ' cls_courses_2' : '';

            $condition    = array(
                'post_type'           => 'lp_course',
                'posts_per_page'      => 6,
                'ignore_sticky_posts' => true,
                'meta_query' => array(
                    array(
                        'key'   => '_lp_featured',
                        'value' => 'yes',
                    )
                )
            );
            $the_query = new WP_Query( $condition );
            if ( $the_query->have_posts() ) :
                ?>
                <div class="feature_box_before_archive<?php echo $cls_layout;?>">
                    <div class="container">
                        <div class="thim-widget-heading thim-widget-heading-base">
                            <div class="sc_heading clone_title  text-center">
                                <h2 class="title"><?php esc_html_e( 'Popular Courses', 'eduma' ); ?></h2>
                                <div class="clone"><?php esc_html_e( 'Popular Courses', 'eduma' ); ?></div>
                            </div>
                        </div>
                        <div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="4"
                             data-pagination="true" data-navigation="false" data-autoplay="false">
                            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                <?php _learn_press_count_users_enrolled_courses( array( get_the_ID() ) ); ?>
                                <div class="course-item">
                                    <?php
                                    echo '<div class="course-thumbnail">';
                                    echo '<a class="thumb" href="' . esc_url( get_the_permalink() ) . '" >';
                                    echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', apply_filters( 'thim_course_thumbnail_width', 320 ), apply_filters( 'thim_course_thumbnail_height', 220 ), get_the_title() );
                                    echo '</a>';
                                    thim_course_wishlist_button( get_the_ID() );
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
                                            <?php learn_press_courses_loop_item_price(); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                endif;
        }
    }
}
add_action( 'thim_before_site_content', 'thim_show_popular_courses' );

/**
 * Show popular Courses
 */
if ( ! function_exists( 'thim_show_content_archive_courses' ) ) {
    function thim_show_content_archive_courses() {
        if ( is_post_type_archive( 'lp_course' ) && $courses_page = get_option( 'learn_press_courses_page_id' ) ) : ?>
            <div class="content_page_courses">
                <?php echo get_post_field( 'post_content', $courses_page ); ?>
            </div>
        <?php endif;
    }
}
add_action( 'thim_after_site_content', 'thim_show_content_archive_courses' );


if( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'new-1' ) {
    remove_action( 'learn_press_before_main_content', '_learn_press_print_messages', 50 );
    add_action( 'thim_before_sidebar_course', '_learn_press_print_messages', 10 );
}

if( !function_exists( 'thim_get_all_courses_instructors' ) && thim_plugin_active( 'learnpress-co-instructor/learnpress-co-instructor.php' ) ) {
    function thim_get_all_courses_instructors() {
        $teacher = array();
        $users_by_role = get_users( array( 'role' => 'lp_teacher' ) );
        if ( $users_by_role ) {
            foreach ( $users_by_role as $user ) {
                $teacher[] = $user->ID;
            }
        }
        $result = array();
        if ( $teacher ) {
            foreach ( $teacher as $id ) {
                $courses = learn_press_get_course_of_user_instructor(array('user_id' => $id));
                $count_students = $count_rate = 0;
                foreach ( $courses["rows"] as $key => $course ) {
                    //$user_count = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0;
                    //$curd = new LP_Abstract_Course();
                    $course = new LP_Course( $course->ID );
                    $students = $course->get_users_enrolled();
                    $count_students = $students;
                    $rate = learn_press_get_course_rate_total( $course->ID );
                    $count_rate = $rate ? $rate + $count_rate : $count_rate;
                }
                $result[] = array(
                    'user_id' => $id,
                    'students' => $count_students,
                    'count_rate' => $count_rate
                );
            }
        }
        return $result;
    }
}