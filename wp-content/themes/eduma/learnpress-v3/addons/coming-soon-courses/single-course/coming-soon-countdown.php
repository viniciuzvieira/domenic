<?php
/**
 * Template for displaying coming soon countdown
 *
 * @author  ThimPress
 * @version 1.0
 */
$course = LP()->global['course'];
if ( !( learn_press_is_coming_soon( $course->get_id() ) && learn_press_is_show_coming_soon_countdown( $course->get_id() ) ) ) {
	return;
}

?>
<div class="countdown learnpress-course-coming-soon course-content_coming_soon" data-time="<?php echo esc_attr( $datetime->format( DATE_ATOM ) ) ?>" data-speed="500" data-timezone="<?php echo $timezone; ?>"></div>
