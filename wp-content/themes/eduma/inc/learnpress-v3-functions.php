<?php
/**
 * Custom functions for LearnPress 3.x
 *
 * @package thim
 */


if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {
		remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_begin_meta', 10 );
		remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_price', 20 );
		remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_instructor', 25 );
		remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_end_meta', 30 );
		remove_action( 'learn-press/after-courses-loop-item', 'learn_press_course_loop_item_buttons', 35 );
		remove_action( 'learn-press/after-courses-loop-item', 'learn_press_course_loop_item_user_progress', 40 );
		remove_action( 'learn-press/before-main-content', 'learn_press_breadcrumb', 10 );
		remove_action( 'learn-press/before-main-content', 'learn_press_search_form', 15 );
		remove_action( 'learn-press/content-landing-summary', 'learn_press_course_meta_start_wrapper', 5 );
		remove_action( 'learn-press/content-landing-summary', 'learn_press_course_students', 10 );
		remove_action( 'learn-press/content-landing-summary', 'learn_press_course_meta_end_wrapper', 15 );
		remove_action( 'learn-press/content-landing-summary', 'learn_press_course_price', 25 );
		remove_action( 'learn-press/content-landing-summary', 'learn_press_course_buttons', 30 );
		remove_action( 'learn-press/content-landing-summary', 'learn_press_course_instructor', 35 );
		remove_action( 'learn-press/course-section-item/before-lp_lesson-meta', 'learn_press_item_meta_duration', 5 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_item_meta_duration', 10 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_quiz_meta_questions', 5 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_meta_start_wrapper', 10 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_remaining_time', 30 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_students', 15 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_meta_end_wrapper', 20 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_progress', 25 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_buttons', 40 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_instructor', 45 );
		remove_action( 'learn-press/course-buttons', 'learn_press_course_continue_button', 25 );
		remove_action( 'learn-press/parse-course-item', 'learn_press_control_displaying_course_item' );
		remove_action( 'learn-press/before-profile-nav', 'learn_press_profile_mobile_menu', 5 );
		//		remove_action( 'learn-press/quiz-buttons', 'learn_press_course_finish_button', 50 );

		remove_action( 'learn-press/section-summary', 'learn_press_curriculum_section_content', 10 );

		add_action( 'learn-press/parse-course-item', function () {
			remove_action( 'wp_print_scripts', 'learn_press_content_item_script' );
		}, 10 );
		add_action( 'wp_enqueue_scripts', function () {
			wp_dequeue_style( 'learn-press' );
		}, 10000 );

		add_action( 'init', function () {
			if ( thim_plugin_active( 'learnpress-wishlist/learnpress-wishlist.php' ) && class_exists( 'LP_Addon_Wishlist' ) && is_user_logged_in() && thim_is_version_addons_wishlist( '3' ) ) {
				$instance_addon = LP_Addon_Wishlist::instance();
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'wishlist_button' ), 100 );
				add_action( 'thim_after_course_info', array( $instance_addon, 'wishlist_button' ), 10 );
				add_action( 'thim_inner_thumbnail_course', array( $instance_addon, 'wishlist_button' ), 10 );
			}
			if ( thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) && class_exists( 'LP_Addon_bbPress' ) && thim_is_version_addons_bbpress( '3' ) ) {
				$instance_addon = LP_Addon_bbPress::instance();
				remove_action( 'learn-press/single-course-summary', array( $instance_addon, 'forum_link' ), 0 );
			}
			if ( thim_plugin_active( 'learnpress-woo-payment/learnpress-woo-payment.php' ) && class_exists( 'LP_Addon_Woo_Payment' ) && thim_is_version_addons_woo( 3 ) ) {
				$instance_addon = LP_Addon_Woo_Payment::instance();
				remove_action( 'learn-press/before-course-buttons', array(
					$instance_addon,
					'purchase_course_notice'
				) );
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'after_course_buttons' ) );
				//add_action( 'learn-press/before-single-course', array( $instance_addon, 'purchase_course_notice' ) );
				//add_action( 'learn-press/before-single-course', array( $instance_addon, 'after_course_buttons' ) );
			}
			if ( thim_plugin_active( 'learnpress-paid-membership-pro/learnpress-paid-memberships-pro.php' ) && thim_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php' ) && class_exists( 'LP_Addon_Paid_Memberships_Pro' ) ) {
				$instance_addon = LP_Addon_Paid_Memberships_Pro::instance();
				remove_action( 'learn-press/before-course-buttons', array(
					$instance_addon,
					'add_buy_membership_button'
				), 10 );
				add_action( 'thim_single_course_payment', array(
					$instance_addon,
					'learn_press_before_course_buttons'
				), 8 );
			}
			if ( thim_plugin_active( 'learnpress-assignments/learnpress-assignments.php' ) && class_exists( 'LP_Addon_Assignment' ) ) {
				$instance_addon = LP_Addon_Assignment::instance();
				remove_action( 'learn-press/course-section-item/before-lp_assignment-meta', array(
					$instance_addon,
					'learnpress_assignment_show_duration'
				), 10 );
				add_action( 'learn-press/course-section-item/before-lp_assignment-meta', 'thim_assignment_show_duration', 10 );
				if ( ! function_exists( 'thimthim_assignment_show_duration_assignment_show_duration' ) ) {
					function thim_assignment_show_duration( $item ) {
						$duration = get_post_meta( $item->get_id(), '_lp_duration', true );
						if ( absint( $duration ) > 1 ) {
							$duration .= 's';
						}
						echo '<span class="meta duration">' . $duration . '</span>';
					}
				}
			}

		}, 99 );

		//remove_action( 'learn-press/course-section-item/before-lp_lesson-meta', 'learn_press_item_meta_duration', 5 );
		//add_action( 'learn-press/course-section-item/after-lp_lesson-meta', 'learn_press_item_meta_duration', 5 );

		add_action( 'thim_single_course_payment', 'learn_press_course_price', 5 );
		add_action( 'thim_single_course_payment', 'learn_press_course_external_button', 10 );
		add_action( 'thim_single_course_payment', 'learn_press_course_purchase_button', 15 );
		add_action( 'thim_single_course_payment', 'learn_press_course_enroll_button', 20 );
		add_action( 'thim_single_course_meta', 'learn_press_course_instructor', 5 );
		add_action( 'thim_single_course_meta', 'learn_press_course_categories', 15 );
		add_action( 'thim_single_course_meta', 'thim_course_forum_link', 20 );
		add_action( 'thim_single_course_meta', 'thim_course_ratings', 25 );
		add_action( 'thim_single_course_meta', 'learn_press_course_progress', 30 );
		add_action( 'thim_begin_curriculum_button', 'learn_press_course_remaining_time', 1 );
		add_action( 'thim_begin_curriculum_button', 'learn_press_course_buttons', 10 );
		remove_action( 'learn-press/course-buttons', 'learn_press_course_external_button', 5 );
		remove_action( 'learn-press/course-buttons', 'learn_press_course_purchase_button', 10 );
		remove_action( 'learn-press/course-buttons', 'learn_press_course_enroll_button', 15 );
		remove_action( 'learn-press/after-checkout-order-review', 'learn_press_order_comment', 5 );
		remove_action( 'learn-press/after-checkout-order-review', 'learn_press_order_payment', 10 );
		add_action( 'learn-press/checkout-order-review', 'learn_press_order_comment', 10 );
		add_action( 'learn-press/checkout-order-review', 'learn_press_order_payment', 15 );
		remove_action( 'learn-press/before-checkout-form', 'learn_press_checkout_form_login', 5 );
		remove_action( 'learn-press/before-checkout-form', 'learn_press_checkout_form_register', 10 );

		//action for new demo
		if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'new-1' ) {
			remove_action( 'learn_press_before_main_content', '_learn_press_print_messages', 50 );
			add_action( 'thim_before_sidebar_course', '_learn_press_print_messages', 5 );
			remove_action( 'thim_begin_curriculum_button', 'learn_press_course_remaining_time', 1 );
			add_action( 'thim_before_sidebar_course', 'learn_press_course_remaining_time', 10 );

		}
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

		if ( thim_plugin_active( 'learnpress-co-instructor/learnpress-co-instructor.php' ) && thim_is_version_addons_instructor( '3' ) ) {
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

									<?php if ( isset( $lp_info['instagram'] ) && $lp_info['instagram'] ) : ?>
										<li>
											<a href="<?php echo esc_url( $lp_info['instagram'] ); ?>" class="instagram"><i
													class="fa fa-instagram"></i></a>
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

/**
 * Display ratings count
 */

if ( ! function_exists( 'thim_course_ratings_count' ) ) {
	function thim_course_ratings_count( $course_id = null ) {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) || ! thim_is_version_addons_review( '3' ) ) {
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
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings' ) ) {
	function thim_course_ratings() {

		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) || ! thim_is_version_addons_review( '3' ) ) {
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
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) || ! thim_is_version_addons_review( '3' ) ) {
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
			<ul class="review-stars filled"
			    style="<?php echo esc_attr( 'width: calc(' . ( $rate * 20 ) . '% - 2px)' ) ?>">
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

		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) || ! thim_is_version_addons_review( '3' ) ) {
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
 * Display price html
 */

if ( ! function_exists( 'thim_course_loop_price_html' ) ) {
	function thim_course_loop_price_html( $course ) {
		$class = ( $course->has_sale_price() ) ? ' has-origin' : '';
		if ( $course->is_free() ) {
			$class .= ' free-course';
		}
		?>
		<div class="course-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
			<?php if ( $price_html = $course->get_price_html() ) { ?>
				<div class="value <?php echo $class; ?>" itemprop="price">
					<?php if ( $course->get_origin_price() != $course->get_price() ) { ?>
						<?php $origin_price_html = $course->get_origin_price_html(); ?>
						<span class="course-origin-price"><?php echo $origin_price_html; ?></span>
					<?php } ?>
					<?php echo $price_html; ?>
				</div>
				<meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency(); ?>" />
			<?php } ?>
		</div>
		<?php
	}
}

/**
 * Display thumbnail course
 */

if ( ! function_exists( 'thim_courses_loop_item_thumbnail' ) ) {
	function thim_courses_loop_item_thumbnail( $course = null ) {
		$course = LP_Global::course();
		echo '<div class="course-thumbnail">';
		echo '<a class="thumb" href="' . esc_url( get_the_permalink( $course->get_id() ) ) . '" >';
		echo thim_get_feature_image( get_post_thumbnail_id( $course->get_id() ), 'full', apply_filters( 'thim_course_thumbnail_width', 400 ), apply_filters( 'thim_course_thumbnail_height', 320 ), $course->get_title() );
		echo '</a>';
		do_action( 'thim_inner_thumbnail_course' );
		echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course->get_id() ) ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>';
		echo '</div>';
	}
}
add_action( 'thim_courses_loop_item_thumb', 'thim_courses_loop_item_thumbnail' );

/**
 * Show thumbnail single course
 */
if ( ! function_exists( 'thim_course_thumbnail_item' ) ) {
	function thim_course_thumbnail_item() {
		learn_press_get_template( 'single-course/thumbnail.php' );
	}
}
if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) != 'new-1' ) {
	add_action( 'learn-press/single-course-summary', 'thim_course_thumbnail_item', 2 );
}


/**
 * Display the link to course forum
 */
if ( ! function_exists( 'thim_course_forum_link' ) ) {
	function thim_course_forum_link() {

		if ( ( thim_plugin_active( 'bbpress/bbpress.php' ) && thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) ) && thim_is_version_addons_bbpress( '3' ) ) {
			LP_Addon_bbPress::instance()->forum_link();
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

		$course_skill_level = get_post_meta( $course_id, 'thim_course_skill_level', true );
		$course_language    = get_post_meta( $course_id, 'thim_course_language', true );
		$course_duration    = get_post_meta( $course_id, 'thim_course_duration', true );

		?>
		<div class="thim-course-info">
			<h3 class="title"><?php esc_html_e( 'Course Features', 'eduma' ); ?></h3>
			<ul>
				<li class="lectures-feature">
					<i class="fa fa-files-o"></i>
					<span class="label"><?php esc_html_e( 'Lectures', 'eduma' ); ?></span>
					<span class="value"><?php echo $course->get_curriculum_items( 'lp_lesson' ) ? count( $course->get_curriculum_items( 'lp_lesson' ) ) : 0; ?></span>
				</li>
				<li class="quizzes-feature">
					<i class="fa fa-puzzle-piece"></i>
					<span class="label"><?php esc_html_e( 'Quizzes', 'eduma' ); ?></span>
					<span class="value"><?php echo $course->get_curriculum_items( 'lp_quiz' ) ? count( $course->get_curriculum_items( 'lp_quiz' ) ) : 0; ?></span>
				</li>
				<?php if ( ! empty( $course_duration ) ): ?>
					<li class="duration-feature">
						<i class="fa fa-clock-o"></i>
						<span class="label"><?php esc_html_e( 'Duration', 'eduma' ); ?></span>
						<span class="value"><?php echo $course_duration; ?></span>
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
					<?php $user_count = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0; ?>
					<span class="value"><?php echo esc_html( $user_count ); ?></span>
				</li>
				<?php thim_course_certificate( $course_id ); ?>
				<li class="assessments-feature">
					<i class="fa fa-check-square-o"></i>
					<span class="label"><?php esc_html_e( 'Assessments', 'eduma' ); ?></span>
					<span class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'evaluate_lesson' ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'Self', 'eduma' ); ?></span>
				</li>
			</ul>
			<?php do_action( 'thim_after_course_info' ); ?>
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

	if ( thim_plugin_active( 'learnpress-certificates/learnpress-certificates.php' ) && thim_is_version_addons_certificates( '3' ) ) {
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
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) || ! thim_is_version_addons_review( '3' ) ) {
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
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) || ! thim_is_version_addons_review( '3' ) ) {
			return;
		}

		if ( ! get_current_user_id() ) {
			return;
		}
		$user = learn_press_get_current_user();
		if ( $user->has_course_status( $course_id, array( 'enrolled', 'completed', 'finished' ) ) ) {
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
						<input type="hidden" id="comment_post_ID" name="comment_post_ID"
						       value="<?php echo get_the_ID(); ?>" />
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

		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) || ! thim_is_version_addons_review( '3' ) ) {
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
 * Display related courses
 */
if ( ! function_exists( 'thim_related_courses' ) ) {
	function thim_related_courses() {
		$related_courses    = thim_get_related_courses( 5 );
		$theme_options_data = get_theme_mods();
		$style_content      = isset( $theme_options_data['thim_layout_content_page'] ) ? $theme_options_data['thim_layout_content_page'] : 'normal';

		if ( $related_courses ) {
			$layout_grid = get_theme_mod( 'thim_learnpress_cate_layout_grid', '' );
			$cls_layout  = ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) ? ' cls_courses_2' : ' ';
			?>
			<div class="thim-ralated-course <?php echo $cls_layout; ?>">

				<?php if ( $style_content == 'new-1' ) { ?>
					<div class="sc_heading clone_title  text-left">
						<h2 class="title"><?php esc_html_e( 'You May Like', 'eduma' ); ?></h2>
						<div class="clone"><?php esc_html_e( 'You May Like', 'eduma' ); ?></div>
					</div>
				<?php } else { ?>
					<h3 class="related-title">
						<?php esc_html_e( 'You May Like', 'eduma' ); ?>
					</h3>
				<?php } ?>

				<div class="thim-course-grid">
					<div class="thim-carousel-wrapper" data-visible="3" data-itemtablet="2" data-itemmobile="1"
					     data-pagination="1">
						<?php foreach ( $related_courses as $course_item ) : ?>
							<?php
							$course                     = learn_press_get_course( $course_item->ID );
							$is_required                = $course->is_required_enroll();
                            $course_des                 = get_post_meta( $course_item->ID, '_lp_coming_soon_msg', true );
                            $course_item_excerpt_length = get_theme_mod( 'thim_learnpress_excerpt_length', 25 );
							?>
							<article class="lpr_course">
								<div class="course-item">
									<div class="course-thumbnail">
										<a class="thumb" href="<?php echo get_the_permalink( $course_item->ID ); ?>">
											<?php
											if ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) {
												echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 320, 220, get_the_title( $course_item->ID ) );
											} else {
												echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 450, 450, get_the_title( $course_item->ID ) );
											}
											?>
										</a>
										<?php do_action( 'thim_inner_thumbnail_course' ); ?>
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

                                        <?php if ( thim_plugin_active( 'learnpress-coming-soon-courses/learnpress-coming-soon-courses.php' ) && learn_press_is_coming_soon( $course_item->ID ) ): ?>
                                            <?php if ( intval( $course_item_excerpt_length ) && $course_des ): ?>
                                                <div class="course-description">
                                                    <?php echo wp_trim_words( $course_des, $course_item_excerpt_length ); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="message message-warning learn-press-message coming-soon-message">
                                                <?php esc_html_e( 'Coming soon', 'eduma' ) ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="course-meta">
                                                <?php
                                                $count_student = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0;
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
                                                            if ( $sale_price ) {
                                                                echo '<span class="course-origin-price">' . $origin_price . '</span>';
                                                            }
                                                            ?>
                                                            <?php echo $price; ?>
                                                        </div>
                                                        <meta itemprop="priceCurrency"
                                                              content="<?php echo learn_press_get_currency(); ?>" />
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>

                                        <?php endif; ?>
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
				$tag_ids[] = $individual_tag->term_id;
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
					'field'    => 'term_id',
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
 * Add format icon before curriculum items
 *
 * @param $lesson_or_quiz
 * @param $enrolled
 */
if ( ! function_exists( 'thim_add_format_icon' ) ) {
	function thim_add_format_icon( $item ) {
		$format = get_post_format( $item->get_id() );
		if ( get_post_type( $item->get_id() ) == 'lp_quiz' ) {
			echo '<span class="course-format-icon"><i class="fa fa-puzzle-piece"></i></span>';
		} elseif ( get_post_type( $item->get_id() ) == 'lp_assignment' ) {
			echo '<span class="course-format-icon"><i class="fa fa-book"></i></span>';
		} elseif ( $format == 'video' ) {
			echo '<span class="course-format-icon"><i class="fa fa-play-circle"></i></span>';
		} else {
			echo '<span class="course-format-icon"><i class="fa fa-file-o"></i></span>';
		}
	}
}

add_action( 'learn_press_before_section_item_title', 'thim_add_format_icon', 10, 1 );

/**
 * @param LP_Quiz|LP_Lesson $item
 */
if ( ! function_exists( 'thim_item_meta_duration' ) ) {
	function thim_item_meta_duration( $item ) {
		$duration = $item->get_duration();

		if ( is_a( $duration, 'LP_Duration' ) && $duration->get() ) {
			$format = array(
				'day'    => _x( '%s day', 'duration', 'eduma' ),
				'hour'   => _x( '%s hour', 'duration', 'eduma' ),
				'minute' => _x( '%s min', 'duration', 'eduma' ),
				'second' => _x( '%s sec', 'duration', 'eduma' ),
			);
			echo '<span class="meta duration">' . $duration->to_timer( $format, true ) . '</span>';
		} elseif ( is_string( $duration ) && strlen( $duration ) ) {
			echo '<span class="meta duration">' . $duration . '</span>';
		}
	}
}
add_action( 'learn-press/course-section-item/before-lp_lesson-meta', 'thim_item_meta_duration', 5 );

/**
 * @param LP_Quiz|LP_Lesson $item
 */
function thim_item_quiz_meta_duration( $item ) {
	$duration = $item->get_duration();

	if ( is_a( $duration, 'LP_Duration' ) && $duration->get() ) {
		$format = array(
			'day'    => _x( '%s day', 'duration', 'eduma' ),
			'hour'   => _x( '%s hour', 'duration', 'eduma' ),
			'minute' => _x( '%s min', 'duration', 'eduma' ),
			'second' => _x( '%s sec', 'duration', 'eduma' ),
		);
		echo '<span class="meta duration">' . $duration->to_timer( $format, true ) . '</span>';
	} elseif ( is_string( $duration ) && strlen( $duration ) ) {
		echo '<span class="meta duration">' . $duration . '</span>';
	}
}

//add_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'thim_item_quiz_meta_duration', 10 );

/**
 * @param LP_Quiz $item
 */
function thim_item_quiz_meta_questions( $item ) {
	$count = $item->count_questions();
	echo '<span class="meta count-questions">' . sprintf( $count ? _n( '%d question', '%d questions', $count, 'eduma' ) : __( '%d question', 'eduma' ), $count ) . '</span>';
}

add_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'thim_item_quiz_meta_questions', 5 );

/**
 * Add class course item
 */
if ( ! function_exists( 'thim_add_class_course_item' ) ) {
	function thim_add_class_course_item( $defaults, $item_type, $item_id, $course_id ) {
		$item_type  = str_replace( 'lp_', '', $item_type );
		$defaults[] = 'course-' . $item_type;

		return $defaults;
	}
}
add_filter( 'learn-press/course-item-class', 'thim_add_class_course_item', 1000, 4 );

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

/*
 * Before Curiculumn on item page
 */
if ( ! function_exists( 'thim_before_curiculumn_item_func' ) ) {
	function thim_before_curiculumn_item_func() {
		$args = array();
		$args = wp_parse_args( $args, apply_filters( 'learn_press_breadcrumb_defaults', array(
			'delimiter'   => '<i class="fa-angle-right fa"></i>',
			'wrap_before' => '<nav class="thim-font-heading learn-press-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
		) ) );

		$breadcrumbs = new LP_Breadcrumb();


		$args['breadcrumb'] = $breadcrumbs->generate();

		learn_press_get_template( 'global/breadcrumb.php', $args );
	}
}
add_action( 'thim_before_curiculumn_item', 'thim_before_curiculumn_item_func' );

/*
 * Add media for lesson
 */
if ( ! function_exists( 'thim_content_item_lesson_media' ) ) {
	function thim_content_item_lesson_media() {
		$item          = LP_Global::course_item();
		$user          = LP_Global::user();
		$course_item   = LP_Global::course_item();
		$course        = LP_Global::course();
		$can_view_item = $user->can_view_item( $course_item->get_id(), $course->get_id() );
		$media_intro   = get_post_meta( $item->get_id(), '_lp_lesson_video_intro', true );
		if ( ! empty( $media_intro ) && ! $course_item->is_blocked() && $can_view_item ) {
			?>
			<div class="learn-press-video-intro">
				<div class="video-content">
					<?php echo $media_intro; ?>
				</div>
			</div>
			<?php
		}
	}
}
add_action( 'learn-press/before-course-item-content', 'thim_content_item_lesson_media', 5 );

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
		case 'assignment':
			$tab_title = '<i class="fa fa-book"></i><span class="text">' . esc_html__( 'Assignments', 'eduma' ) . '</span>';
			break;
		case 'settings':
			$tab_title = '<i class="fa fa-cog"></i><span class="text">' . esc_html__( 'Settings', 'eduma' ) . '</span>';
			break;
		case 'certificates':
			$tab_title = '<i class="fa fa-bookmark-o"></i><span class="text">' . esc_html__( 'Certificates', 'eduma' ) . '</span>';
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
add_filter( 'learn_press_profile_certificates_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_assignment_tab_title', 'thim_tab_profile_filter_title', 100, 2 );

/**
 * Change tabs profile
 */
if ( ! function_exists( 'thim_change_tabs_course_profile' ) ) {
	function thim_change_tabs_course_profile( $defaults ) {
		unset( $defaults['dashboard'] );
		$defaults['settings']['priority']      = 15;
		$defaults['courses']['priority']       = 3;
		$defaults['orders']['priority']        = 13;
		$defaults['order-details']['priority'] = 14;

		return $defaults;
	}
}
add_filter( 'learn-press/profile-tabs', 'thim_change_tabs_course_profile', 1000 );


if ( ! function_exists( 'show_pass_text' ) ) {
	function show_pass_text() {
		$user   = learn_press_get_current_user();
		$course = LP()->global['course'];
		$grade  = $user->get_course_grade( $course->get_id() );
		if ( $grade == 'passed' ) {
			echo '<div class="message message-success learn-press-success">' . ( __( 'You have finished this course.', 'eduma' ) ) . '</div>';
		}
	}
}
add_action( 'thim_begin_curriculum_button', 'show_pass_text', 5 );

if ( ! function_exists( 'thim_checkout_link_login_register' ) ) {
	function thim_checkout_link_login_register() {
		if ( is_user_logged_in() ) {
			return;
		}
		$redirect      = ( ! empty( $_SERVER['HTTPS'] ) ? "https" : "http" ) . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$link_login    = thim_get_login_page_url() . '?redirect_to=' . esc_attr( $redirect );
		$link_register = thim_get_register_url() . '&redirect_to=' . esc_attr( $redirect );
		if ( LP()->checkout()->is_enable_login() || LP()->checkout()->is_enable_register() ) {
			echo '<div class="message message-notice">';
			if ( LP()->checkout()->is_enable_login() ) {
				printf( __( 'You can <a href="%1$s">login</a> now. ', 'eduma' ), esc_url( $link_login ) );
			}
			if ( LP()->checkout()->is_enable_register() ) {
				printf( __( 'Don\'t have an account? Click <a href="%1$s">register now</a>', 'eduma' ), esc_url( $link_register ) );
			}
			echo '</div>';
		}
	}
}
add_action( 'learn-press/before-checkout-form', 'thim_checkout_link_login_register', 5 );

if ( ! function_exists( 'thim_get_all_courses_instructors' ) ) {
	function thim_get_all_courses_instructors() {
		$teacher       = array();
		$users_by_role = get_users( array( 'role' => 'lp_teacher' ) );
		if ( $users_by_role ) {
			foreach ( $users_by_role as $user ) {
				$teacher[] = $user->ID;
			}
		}
		$result = array();
		if ( $teacher ) {
			foreach ( $teacher as $id ) {
				$user_curd        = new LP_User_CURD();
				$query_list_table = $user_curd->query_own_courses( $id, array(
					'limit'  => 9999,
					'status' => 'publish'
				) );
				$own_courses      = $query_list_table->get_items();
				$count_students   = $count_rate = 0;

				foreach ( $own_courses as $course_id ) {
					$course_curd     = new LP_Course_CURD();
					$number_students = $course_curd->get_user_enrolled( $course_id );

					$count_students += count( $number_students );
					if ( thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
						$rate = learn_press_get_course_rate_total( $course_id );
					} else {
						$rate = 0;
					}

					$count_rate = $rate ? $rate + $count_rate : $count_rate;
				}
				$result[] = array(
					'user_id'    => $id,
					'students'   => $count_students,
					'count_rate' => $count_rate
				);
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'thim_hooks_for_lp3' ) ) {
	function thim_hooks_for_lp3() {
		add_action( 'thim_single_course_before_meta', 'thim_course_thumbnail_item', 5 );
		remove_action( 'learn-press/content-landing-summary', 'learn_press_course_tabs', 20 );
		remove_action( 'learn-press/content-learning-summary', 'learn_press_course_tabs', 35 );

		if ( ! function_exists( 'thim_course_landing_summary_start_wrapper' ) ) {
			function thim_course_landing_summary_start_wrapper() {
				echo '<div id="course-landing"><div class="menu_content_course">';
			}
		}
		add_action( 'learn-press/content-landing-summary', 'thim_course_landing_summary_start_wrapper', 5 );
		add_action( 'learn-press/content-learning-summary', 'thim_course_landing_summary_start_wrapper', 5 );

		if ( ! function_exists( 'thim_course_landing_summary_content' ) ) {
			function thim_course_landing_summary_content() {
				learn_press_get_template( 'single-course/tabs/tabs-2.php' );
			}
		}
		add_action( 'learn-press/content-landing-summary', 'thim_course_landing_summary_content', 15 );
		add_action( 'learn-press/content-learning-summary', 'thim_course_landing_summary_content', 15 );

		if ( ! function_exists( 'thim_course_landing_summary_end_wrapper' ) ) {
			function thim_course_landing_summary_end_wrapper() {
				echo '</div></div>';
			}
		}
		add_action( 'learn-press/content-landing-summary', 'thim_course_landing_summary_end_wrapper', 50 );
		add_action( 'learn-press/content-learning-summary', 'thim_course_landing_summary_end_wrapper', 50 );

		//		remove_action( 'thim_single_course_meta', 'thim_course_forum_link', 20 );
	}
}
if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'new-1' ) {
	add_action( 'after_setup_theme', 'thim_hooks_for_lp3', 99 );
}


if ( ! function_exists( 'thim_curriculum_section_content' ) ) {
	function thim_curriculum_section_content( $section ) {
		learn_press_get_template( 'single-course/section/content.php', array( 'section' => $section ) );
	}
}
add_action( 'learn-press/section-summary', 'thim_curriculum_section_content', 10 );

if ( ! function_exists( 'thim_course_tabs_content' ) ) {
	function thim_course_tabs_content( $defaults ) {
		$arr = array();
		//xxx($defaults);
		$course             = learn_press_get_course();
		$user               = learn_press_get_current_user();
		$theme_options_data = get_theme_mods();
		$group_tab          = isset( $theme_options_data['group_tabs_course'] ) ? $theme_options_data['group_tabs_course'] : array(
			'description',
			'curriculum',
			'instructor',
			'announcements',
			'students-list',
			'review'
		);

		//active tab
		$request_tab = ! empty( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : '';
		$has_active  = false;
		if ( $request_tab != '' ) {
			foreach ( $defaults as $k => $v ) {
				$v['id'] = ! empty( $v['id'] ) ? $v['id'] : 'tab-' . $k;

				if ( $request_tab === $v['id'] ) {
					$v['current'] = true;
					$has_active   = $k;
				}
				$defaults[ $k ] = $v;
			}
		} else {
			/**
			 * Active Curriculum tab if user has enrolled course
			 */
			if ( $course && $user->has_course_status( $course->get_id(), array(
					'enrolled',
					'finished'
				) ) && ! empty( $defaults['curriculum'] ) && array_keys( $group_tab, "curriculum" )
			) {
				$defaults['curriculum']['current'] = true;
				$has_active                        = 'curriculum';
			}
		}
		foreach ( $defaults as $k => $v ) {
			switch ( $k ) {
				case 'overview':
					$v['icon']  = 'fa-bookmark';
					$new_prioty = array_keys( $group_tab, "description" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'description' && ! $has_active ) {
							$v['current'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[ $k ]     = $v;
					}
					break;
				case 'curriculum':
					$v['icon']  = 'fa-cube';
					$new_prioty = array_keys( $group_tab, "curriculum" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'curriculum' && ! $has_active ) {
							$v['current'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[ $k ]     = $v;
					}
					break;
				case 'instructor':
					$v['icon']  = 'fa-user';
					$new_prioty = array_keys( $group_tab, "instructor" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'instructor' && ! $has_active ) {
							$v['current'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[ $k ]     = $v;
					}
					break;
				case 'announcements':
					$v['icon']  = 'fa-envelope';
					$new_prioty = array_keys( $group_tab, "announcements" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'announcements' && ! $has_active ) {
							$v['current'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[ $k ]     = $v;
					}
					break;
				case 'students-list':
					$v['icon']  = 'fa-list';
					$new_prioty = array_keys( $group_tab, "students-list" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'students-list' && ! $has_active ) {
							$v['current'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[ $k ]     = $v;
					}
					break;
				case 'reviews':
					$v['icon']  = 'fa-comments';
					$new_prioty = array_keys( $group_tab, "review" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'review' && ! $has_active ) {
							$v['current'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[ $k ]     = $v;
					}
					break;
			}
		}

		return $arr;
	}
}
add_filter( 'learn-press/course-tabs', 'thim_course_tabs_content', 9999 );

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
					<label for="lp_instagram"><?php esc_html_e( 'Instagram Account', 'eduma' ); ?></label>
				</th>
				<td>
					<input id="lp_instagram" class="regular-text" type="text"
					       value="<?php echo isset( $user_info['instagram'] ) ? $user_info['instagram'] : ''; ?>"
					       name="lp_info[instagram]">
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
		$message = __( 'Your change is saved', 'eduma' );
	} else {
		$message = __( 'Error on update your profile info', 'eduma' );
	}
	$current_url = learn_press_get_current_url();
	learn_press_add_message( $message );
	wp_redirect( $current_url );
	exit();
}

remove_action( 'learn_press_update_user_profile_basic-information', 'learn_press_update_user_profile_basic_information' );
add_action( 'learn_press_update_user_profile_basic-information', 'thim_update_user_profile_basic_information' );

if ( ! function_exists( 'thim_landing_tabs' ) ) {
	function thim_landing_tabs() {
		learn_press_get_template( 'single-course/tabs/tabs-landing.php' );
	}
}
add_action( 'learn-press/content-landing-summary', 'thim_landing_tabs', 22 );


#
# set redirect url in section
#
add_action( 'init', 'eduma_before_mo_openid_login_validate', 9 );
if ( ! function_exists( 'eduma_before_mo_openid_login_validate' ) ) {
	function eduma_before_mo_openid_login_validate() {
		if ( isset( $_REQUEST['option'] ) && ( strpos( $_REQUEST['option'], 'oauthredirect' ) !== false || strpos( $_REQUEST['option'], 'getmosociallogin' ) !== false ) ) {
			$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
			if ( ! session_id() ) {
				session_start();
			}
			if ( $redirect_to ) {
				$_SESSION['eduma_redirect_to'] = $redirect_to;
			}
		}
	}
}

#
# Mark flag $_SESSION['eduma_do_redirect_to'] for redirect
#
add_action( 'miniorange_collect_attributes_for_authenticated_user', 'eduma_callback_action_miniorange_collect_attributes_for_authenticated_user', 10, 2 );
function eduma_callback_action_miniorange_collect_attributes_for_authenticated_user( $user, $mo_openid_redirect_url ) {
	$_SESSION['eduma_do_redirect_to'] = true;

	return $user;
}

#
# Do redirect if flag $_SESSION['eduma_do_redirect_to'] == true
#
add_filter( 'wp_redirect', 'eduma_redirect_after_mo_login', 10 );
function eduma_redirect_after_mo_login( $redirect_url ) {
	if ( isset( $_SESSION['mo_login'] ) && $_SESSION['mo_login'] == false
	     && isset( $_SESSION['eduma_do_redirect_to'] ) && $_SESSION['eduma_do_redirect_to'] == true
	     && isset( $_SESSION['eduma_redirect_to'] )
	) {
		$redirect_url = $_SESSION['eduma_redirect_to'];
		unset( $_SESSION['eduma_redirect_to'] );
		unset( $_SESSION['eduma_do_redirect_to'] );
	}
	if ( is_user_logged_in() ) {
		if ( ! session_id() ) {
			return $redirect_url;
		}
		if ( isset( $_SESSION['eduma_redirect_to'] ) ) {
			$redirect_url = $_SESSION['eduma_redirect_to'];
			unset( $_SESSION['eduma_redirect_to'] );
			if ( isset( $_SESSION['eduma_do_redirect_to'] ) ) {
				unset( $_SESSION['eduma_do_redirect_to'] );
			}
		}
	}

	return $redirect_url;
}


#
# Rewrite javascript function moOpenIdLogin to add redirect url after login
#
add_action( 'learn_press_after_single_course', 'thim_action_callback_learn_press_after_single_course', 100 ); // work for LearnPress 2
add_action( 'learn-press/after-single-course', 'thim_action_callback_learn_press_after_single_course', 100 ); // work for LearnPress 3
if ( ! function_exists( 'thim_action_callback_learn_press_after_single_course' ) ) {
	function thim_action_callback_learn_press_after_single_course() {
		if ( is_user_logged_in() || ! learn_press_is_course() ) {
			return;
		}
		$course = learn_press_get_course();
		if ( ! is_object( $course ) ) {
			return;
		}
		$is_free_course = $course->is_free();
		?>
		<script type="text/javascript">
            function moOpenIdLogin(app_name, is_custom_app) {
				<?php

				if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
					$http = "https://";
				} else {
					$http = "http://";
				}
				?>
                var base_url = '<?php echo site_url();?>';
                var request_uri = '<?php echo $_SERVER['REQUEST_URI'];?>';
                var http = '<?php echo $http;?>';
                var http_host = '<?php echo $_SERVER['HTTP_HOST'];?>';
                if (is_custom_app == 'false') {
                    if (request_uri.indexOf('wp-login.php') != -1) {
                        var redirect_url = base_url + '/?option=getmosociallogin&app_name=';

                    } else {
                        var redirect_url = http + http_host + request_uri;
                        if (redirect_url.indexOf('?') != -1) {
                            redirect_url = redirect_url + '&option=getmosociallogin&app_name=';
                        } else {
                            redirect_url = redirect_url + '?option=getmosociallogin&app_name=';
                        }
                    }
                } else {
                    var current_url = window.location.href;
                    var cname = 'redirect_current_url';
                    var d = new Date();
                    d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
                    var expires = 'expires=' + d.toUTCString();
                    document.cookie = cname + '=' + current_url + ';' + expires + ';path=/';   //path = root path(/)
                    if (request_uri.indexOf('wp-login.php') != -1) {
                        var redirect_url = base_url + '/?option=oauthredirect&app_name=';
                    } else {
                        var redirect_url = http + http_host + request_uri;
                        if (redirect_url.indexOf('?') != -1)
                            redirect_url = redirect_url + '&option=oauthredirect&app_name=';
                        else
                            redirect_url = redirect_url + '?option=oauthredirect&app_name=';
                    }
                }
                var redirect_to = jQuery('#loginform input[name="redirect_to"]').val();
                redirect_url = redirect_url + app_name + '&redirect_to=' + encodeURIComponent(redirect_to);
                window.location.href = redirect_url;
            }
		</script>
		<?php
	}
}

/*
 * Hide ads Learnpress
 */
if ( get_theme_mod( 'thim_learnpress_hidden_ads', false ) ) {
	remove_action( 'admin_footer', 'learn_press_footer_advertisement', - 10 );
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

if ( ! function_exists( 'thim_content_item_edit_link' ) ) {
	function thim_content_item_edit_link() {
		$course      = LP_Global::course();
		$course_item = LP_Global::course_item();
		$user        = LP_Global::user();
		if ( $user->can_edit_item( $course_item->get_id(), $course->get_id() ) ): ?>
			<p class="edit-course-item-link">
				<a href="<?php echo get_edit_post_link( $course_item->get_id() ); ?>"><i
						class="fa fa-pencil-square-o"></i> <?php _e( 'Edit item', 'eduma' ); ?>
				</a>
			</p>
		<?php endif;
	}
}
add_action( 'learn-press/after-course-item-content', 'thim_content_item_edit_link', 3 );

/*
 * Disable function feature login/register form in profile page
 */
remove_action( 'learn-press/user-profile', 'learn_press_profile_dashboard_not_logged_in', 5 );
remove_action( 'learn-press/user-profile', 'learn_press_profile_login_form', 10 );
remove_action( 'learn-press/user-profile', 'learn_press_profile_register_form', 15 );

add_action( 'learn-press/user-profile', 'thim_profile_dashboard_not_logged_in', 5 );
if ( ! function_exists( 'thim_profile_dashboard_not_logged_in' ) ) {
	function thim_profile_dashboard_not_logged_in() {
		$profile = LP_Global::profile();
		if ( ! $profile->get_user()->is_guest() ) {
			return;
		}
		learn_press_get_template( 'profile/not-logged-in.php' );
	}
}

remove_action( 'learn-press/before-become-teacher-form', 'learn_press_become_teacher_heading', 10 );
remove_action( 'learn-press/become-teacher-form', 'learn_press_become_teacher_form_fields', 5 );
add_action( 'learn-press/become-teacher-form', 'thim_become_teacher_form_fields', 5 );
if ( ! function_exists( 'thim_become_teacher_form_fields' ) ) {
	function thim_become_teacher_form_fields() {
		include_once LP_PLUGIN_PATH . 'inc/admin/meta-box/class-lp-meta-box-helper.php';
		learn_press_get_template( 'global/become-teacher-form/form-fields.php', array( 'fields' => learn_press_get_become_a_teacher_form_fields() ) );
	}
}
remove_action( 'learn-press/after-become-teacher-form', 'learn_press_become_teacher_button', 10 );
add_action( 'learn-press/after-become-teacher-form', 'thim_become_teacher_button', 10 );
if ( ! function_exists( 'thim_become_teacher_button' ) ) {
	function thim_become_teacher_button() {
		learn_press_get_template( 'global/become-teacher-form/button.php' );
	}
}

/**
 * Show popular Courses
 */
if ( ! function_exists( 'thim_show_popular_courses' ) ) {
	function thim_show_popular_courses() {
		$show_popular = get_theme_mod( 'thim_learnpress_cate_show_popular' );
		if ( $show_popular && is_post_type_archive( 'lp_course' ) ) {
			//Get layout Grid/List Courses
			$layout_grid = get_theme_mod( 'thim_learnpress_cate_layout_grid', '' );
			$cls_layout  = ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) ? ' cls_courses_2' : '';

			$condition = array(
				'post_type'           => 'lp_course',
				'posts_per_page'      => 6,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'   => '_lp_featured',
						'value' => 'yes',
					)
				)
			);
			$the_query = new WP_Query( $condition );
			if ( $the_query->have_posts() ) :
				?>
				<div class="feature_box_before_archive<?php echo $cls_layout; ?>">
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
								<div class="course-item">
									<?php
									// @since 3.0.0
									do_action( 'learn-press/before-courses-loop-item' );
									?>

									<?php
									// @thim
									do_action( 'thim_courses_loop_item_thumb' );
									?>
									<div class="thim-course-content">
										<?php learn_press_courses_loop_item_instructor(); ?>
										<?php
										//thim_courses_loop_item_author();
										//do_action( 'learn_press_before_the_title' );
										the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
										do_action( 'learn_press_after_the_title' );
										?>
										<div class="course-meta">
											<?php learn_press_courses_loop_item_instructor(); ?>
											<?php thim_course_ratings(); ?>
											<?php learn_press_courses_loop_item_students(); ?>
											<?php thim_course_ratings_count(); ?>
											<?php learn_press_courses_loop_item_price(); ?>
										</div>

										<div class="course-description">
											<?php
											do_action( 'learn_press_before_course_content' );
											echo thim_excerpt( 25 );
											do_action( 'learn_press_after_course_content' );
											?>
										</div>
										<?php learn_press_courses_loop_item_price(); ?>
										<div class="course-readmore">
											<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
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

if ( ! function_exists( 'thim_course_wishlist_button' ) ) {
	function thim_course_wishlist_button( $course_id = null ) {
		if ( ! class_exists( 'LP_Addon_Wishlist' ) ) {
			return;
		}

		LP_Addon_Wishlist::instance()->wishlist_button( $course_id );
	}
}


/*
 * Add real students enrolled meta for orderby attribute in the query
 * */
add_action( 'init', 'thim_add_real_student_enrolled_meta' );

if ( ! function_exists( 'thim_add_real_student_enrolled_meta' ) ) {
	function thim_add_real_student_enrolled_meta( $lp ) {
		if ( isset( $_POST['course_orderby'] ) && 'most-members' == $_POST['course_orderby'] ) {
			$arg = array(
				'post_type'           => 'lp_course',
				'post_status'         => 'publish',
				'posts_per_page'      => - 1,
				'ignore_sticky_posts' => true
			);

			$course_query = new WP_Query( $arg );

			if ( $course_query->have_posts() ) {
				while ( $course_query->have_posts() ) {
					$course_query->the_post();

					$course = learn_press_get_course( get_the_ID() );

					update_post_meta( get_the_ID(), 'thim_real_student_enrolled', $course->get_users_enrolled() );
				}

				wp_reset_postdata();
			}
		}
	}
}


/*
 * Change query get courses
 * */
add_action( 'pre_get_posts', 'thim_course_order_query', 15 );

if ( ! function_exists( 'thim_course_order_query' ) ) {
	function thim_course_order_query( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( ! is_post_type_archive( 'lp_course' ) && ! is_tax( 'course_category' ) ) {
			return;
		}

		// Sort
		if ( isset( $_POST['course_orderby'] ) ) {
			switch ( $_POST['course_orderby'] ) {
				case 'alphabetical':
					$query->set( 'orderby', 'title' );
					$query->set( 'order', 'ASC' );

					break;
				case 'most-members':
					$query->set( 'orderby', 'meta_value_num' );
					$query->set( 'meta_key', 'thim_real_student_enrolled' );
					$query->set( 'order', 'DESC' );

					break;
				default:
					$query->set( 'orderby', 'date' );
					$query->set( 'order', 'DESC' );
			}
		}

		// Pagination
		if ( isset( $_POST['course_paged'] ) ) {
			$query->set( 'paged', $_POST['course_paged'] );
		}

		// Filter by categories
		if ( isset( $_POST['course_cate_filter'] ) && is_array( $_POST['course_cate_filter'] ) ) {
			$query->set( 'tax_query', array(
				array(
					'taxonomy' => 'course_category',
					'field'    => 'term_id',
					'terms'    => $_POST['course_cate_filter'],
				)
			) );
		}

		// Filter by instructor
		if ( isset( $_POST['course_instructor_filter'] ) && is_array( $_POST['course_instructor_filter'] ) ) {
			$query->set( 'author__in', $_POST['course_instructor_filter'] );
		}

		// Filter by price
		// TODO query courses has sale price
		if ( isset( $_POST['course_price_filter'] ) ) {
			switch ( $_POST['course_price_filter'] ) {
				case 'free':
					$query->set( 'meta_query', array(
						array(
							'key'     => '_lp_price',
							'compare' => 'NOT EXISTS'
						)
					) );
					break;
				case 'paid':
					$query->set( 'meta_query', array(
						array(
							'key'     => '_lp_price',
							'compare' => 'EXISTS'
						)
					) );
					break;
				case 'all':
					break;

				default:

			}
		}
	}
}

/*
 * Courses filter template
 */

if ( ! function_exists( 'thim_display_course_filter' ) ) {
	function thim_display_course_filter() {
		if ( ! get_theme_mod( 'thim_display_course_filter', false ) ) {
			return;
		}

		// Only display in the courses and course category pages
		if ( ! learn_press_is_courses() && ! learn_press_is_course_category() ) {
			return;
		}

		$total_course = wp_count_posts( 'lp_course' );

		if ( ! $total_course->publish ) {
			return;
		}
		?>
		<aside class="thim-course-filter-wrapper">
			<form action="" name="thim-course-filter" method="POST" class="thim-course-filter">
				<?php
				// Filter courses by categories
				if ( get_theme_mod( 'thim_filter_by_cate', false ) ) {
					// Check total terms and empty terms
					if ( ! wp_count_terms( 'course_category' ) ) {
						return;
					}

					$course_term = get_terms( apply_filters( 'thim_display_cate_filter', array( 'taxonomy' => 'course_category' ) ) );
					if ( is_wp_error( $course_term ) ) {
						return;
					}

					$current_term_object = get_queried_object();
					$current_term_id     = $current_term_object && 'course_category' === $current_term_object->taxonomy ? $current_term_object->term_id : '';
					?>

					<h4 class="filter-title"><?php echo esc_html_x( 'Course categories', 'Course filter widget', 'eduma' ); ?></h4>

					<ul class="list-cate-filter">
						<?php
						foreach ( $course_term as $term ) {
							$input_id = $term->slug . '_' . $term->term_id;
							?>
							<li class="term-item">
								<?php if ( checked( $current_term_id, $term->term_id, false ) ): ?>
									<input type="checkbox" name="course-cate-filter" id="<?php esc_attr_e( $input_id ); ?>" class="filtered" value="<?php esc_attr_e( $term->term_id ); ?>" checked>
								<?php else: ?>
									<input type="checkbox" name="course-cate-filter" id="<?php esc_attr_e( $input_id ); ?>" value="<?php esc_attr_e( $term->term_id ); ?>">
								<?php endif; ?>
								<label for="<?php esc_attr_e( $input_id ) ?>">
									<?php esc_html_e( $term->name ); ?>
									<span><?php printf( esc_html__( '(%s)', 'eduma' ), $term->count ); ?></span>
								</label>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}

				// Filter courses by instructors
				if ( get_theme_mod( 'thim_filter_by_instructor', false ) ) {
					// TODO can create custom query to optimize speed
					$course_query = new WP_Query( array(
						'post_type'      => 'lp_course',
						'posts_per_page' => - 1,
					) );
					?>
					<h4 class="filter-title"><?php echo esc_html_x( 'Instructors', 'Course filter widget', 'eduma' ); ?></h4>
					<?php
					if ( $course_query->have_posts() ) {
						global $post;
						$list_instructor = array();
						?>
						<ul class="list-instructor-filter">
							<?php
							while ( $course_query->have_posts() ) {
								$course_query->the_post();

								$instructor_id = $post->post_author;

								if ( ! array_key_exists( $instructor_id, $list_instructor ) ) {
									$list_instructor[ $instructor_id ] = 1;
								} else {
									$list_instructor[ $instructor_id ] += 1;
								}
							}
							wp_reset_postdata();

							foreach ( $list_instructor as $instructor_id => $total ) {
								?>
								<li class="instructor-item">
									<input type="checkbox" name="course-instructor-filter" id="instructor-id_<?php esc_attr_e( $instructor_id ) ?>" value="<?php esc_attr_e( $instructor_id ); ?>">
									<label for="instructor-id_<?php esc_attr_e( $instructor_id ) ?>">
										<?php echo get_the_author_meta( 'display_name', $instructor_id ) ?>
										<span><?php printf( esc_html__( '(%s)', 'eduma' ), $total ); ?></span>
									</label>
								</li>
								<?php
							}
							?>
						</ul>
						<?php
					}
				}

				// Filter by price
				if ( get_theme_mod( 'thim_filter_by_price' ) ) {
					$paid_course_query = new WP_Query( array(
						'post_type'      => 'lp_course',
						'posts_per_page' => - 1,
						'meta_key'       => '_lp_price',
						'meta_compare'   => 'EXISTS'
					) );

					$number_paid_course = $paid_course_query->post_count;
					$number_free_course = $total_course->publish - $number_paid_course;
					?>
					<h4 class="filter-title"><?php echo esc_html_x( 'Price', 'Course filter widget', 'eduma' ); ?></h4>

					<ul class="list-price-filter">
						<li class="price-item">
							<input type="radio" id="price-filter_all" name="course-price-filter" value="all" checked>
							<label for="price-filter_all">
								<?php esc_html_e( 'All', 'eduma' ); ?>
								<span><?php printf( esc_html__( '(%s)', 'eduma' ), $total_course->publish ); ?></span>
							</label>
						</li>
						<li class="price-item">
							<input type="radio" id="price-filter_free" name="course-price-filter" value="free">
							<label for="price-filter_free">
								<?php esc_html_e( 'Free', 'eduma' ); ?>
								<span><?php printf( esc_html__( '(%s)', 'eduma' ), $number_free_course ); ?></span>
							</label>
						</li>
						<li class="price-item">
							<input type="radio" id="price-filter_paid" name="course-price-filter" value="paid">
							<label for="price-filter_paid">
								<?php esc_html_e( 'Paid', 'eduma' ); ?>
								<span><?php printf( esc_html__( '(%s)', 'eduma' ), $number_paid_course ); ?></span>
							</label>
						</li>
					</ul>
					<?php
				}
				?>

				<div class="filter-submit">
					<button type="submit"><?php esc_html_e( 'Filter Results', 'eduma' ) ?></button>
				</div>
			</form>
		</aside>
		<?php
	}
}

add_action( 'thim_before_sidebar_course', 'thim_display_course_filter' );
