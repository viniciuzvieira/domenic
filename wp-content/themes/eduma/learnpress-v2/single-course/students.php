<?php
/**
 * Template for displaying the students of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.1.4
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$course = LP()->global['course'];
if ( !$course || !$course->is_require_enrollment() ) {
	return;
}

$count = $course->count_users_enrolled( 'append' ) ? $course->count_users_enrolled( 'append' ) : 0;
?>
<div class="course-students">
	<label><?php esc_html_e( 'Students', 'eduma' ); ?></label>

	<div class="value"><i class="fa fa-group"></i>
		<?php echo esc_html( $count ); ?>
	</div>

</div>