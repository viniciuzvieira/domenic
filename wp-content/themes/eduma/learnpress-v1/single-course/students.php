<?php
/**
 * Template for displaying the students of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;
$count = $course->count_users_enrolled( 'append' ) ? $course->count_users_enrolled( 'append' ) : 0;
?>
<div class="course-students">
	<label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
	<?php do_action( 'learn_press_begin_course_students' ); ?>

	<div class="value"><i class="fa fa-group"></i>
		<?php echo esc_html( $count ); ?>
	</div>
	<?php do_action( 'learn_press_end_course_students' ); ?>

</div>