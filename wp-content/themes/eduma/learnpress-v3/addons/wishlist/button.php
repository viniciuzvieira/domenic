<?php
/**
 * Template for displaying button to toggle course wishlist on/off
 *
 * @author ThimPress
 */
defined( 'ABSPATH' ) || exit();
$class = learn_press_user_wishlist_has_course($course_id) ? 'course-wishlisted' : 'course-wishlist';
echo '<div class="course-wishlist-box">';
printf(
		'<span class="fa fa-heart %s" data-id="%s" data-nonce="%s" title="%s"><span class="text">'.esc_html__('Wishlist','eduma').'</span></span>',
		$class,
		$course_id,
		wp_create_nonce( 'course-toggle-wishlist' ),
		$title
);
echo '</div>';
