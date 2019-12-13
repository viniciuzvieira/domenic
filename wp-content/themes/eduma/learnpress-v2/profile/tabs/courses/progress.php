<?php
/**
 * Progress bar in profile page
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

$course = learn_press_get_course( $course_id );

learn_press_setup_user_course_data( $user->id, $course_id );

// Check if this page is LP Profile
global $wp_query;

$queried_object = $wp_query->queried_object;
$profile_page   = LP()->settings->get( 'profile_page_id', false);

if ( !empty($queried_object) && !empty( $queried_object->ID ) && !empty($profile_page) && $queried_object->ID == absint( $profile_page ) ) {
    $profile_page = true;
}
if ( !$user->has_course_status( $course_id, array( 'enrolled', 'finished' ) ) && !$profile_page ) { // Always active if this page is LP Profile
	return;
}

$force             = isset( $force ) ? $force : false;
$num_of_decimal    = 0;
$result            = ( $user->get_course_info2( $course_id ) );
$current           = $course->evaluate_course_results( null, $force );
$current           = absint( $current );
$passing_condition = round( $course->passing_condition, $num_of_decimal );

if ( empty( $result['results'] ) ) {
    $result['results'] = 0;
}
?>

<div class="learn-press-course-results-progress">
	<div class="course-progress">
		<div class="lp-course-progress">
			<label class="progress-heading"><?php esc_html_e('Learning progress ', 'eduma'); ?><span class="value result"><b class="number"><?php echo $current; ?></b>%</span></label>
			<div class="lp-progress-bar value">
				<div class="lp-progress-value percentage-sign" style="width:<?php echo $current; ?>%;">
				</div>
			</div>
		</div>
	</div>
</div>