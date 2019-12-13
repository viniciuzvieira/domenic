<?php
/**
 * Template for displaying coming soon countdown.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/coming-soon-courses/single-course/countdown.php.
 *
 * @author ThimPress
 * @package LearnPress/Coming-Soon-Courses/Templates
 * @version 3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

$course    = learn_press_get_course();
$course_id = $course->get_id();
if ( ! ( learn_press_is_coming_soon( $course_id ) && learn_press_is_show_coming_soon_countdown( $course_id ) ) ) {
	return;
}
$end_time_timestamp = learn_press_get_coming_soon_end_time( $course_id );
$current_time       = current_time( 'timestamp' );
$timestamp_remain   = $end_time_timestamp - $current_time;
$time_remain        = gmdate( "d H:i:s", $timestamp_remain );
?>

<div class="countdown learnpress-course-coming-soon course-content_coming_soon"
     data-showtext="<?php echo esc_attr( $showtext ) ?>" data-time-remain="<?php echo esc_attr( $time_remain ); ?>"
     data-timestamp-remain="<?php echo esc_attr( $timestamp_remain ); ?>"
     data-time="<?php echo esc_attr( $datetime->format( DATE_ATOM ) ) ?>" data-speed="500"
     data-timezone="<?php echo $timezone; ?>"></div>