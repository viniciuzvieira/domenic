<?php
/**
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       2.1.6
 */

defined( 'ABSPATH' ) || exit();

$course = LP()->global['course'];
$user   = learn_press_get_current_user();
if ( !$course ) {
	return;
}
$status = $user->get( 'course-status', $course->id );
$is_enrolled = $user->has( 'enrolled-course', $course->id );

if ( !$is_enrolled ) {
	return;
}
$force             = isset( $force ) ? $force : false;
$num_of_decimal    = 0;
$result            = $course->evaluate_course_results( null, $force );
$current           = absint( $result );
$passing_condition = round( $course->passing_condition, $num_of_decimal );
$passed            = $current >= $passing_condition;
$heading           = apply_filters( 'learn_press_course_progress_heading', $status == 'finished' ? esc_html__( 'Your result', 'eduma' ) : esc_html__( 'Learning progress', 'eduma' ) );
$course_items      = sizeof( $course->get_curriculum_items() );
$completed_items   = $course->count_completed_items();
$course_results    = $course->evaluate_course_results();
?>

<div class="items-progress" style="display: none">
	<span class="number"><?php printf( __('%d of %d items', 'eduma'), $completed_items, $course_items); ?></span>
	<div class="lp-course-progress" data-passing-condition="<?php echo $passing_condition; ?>">
		<div class="lp-progress-bar">
			<div class="lp-progress-value"
			     style="width: <?php echo $course_items ? absint($completed_items / $course_items * 100) : '0'; ?>%;">
			</div>
		</div>
	</div>

</div>

<div class="course-progress">
	<div class="lp-course-progress<?php echo $passed ? ' passed' : ''; ?>" data-value="<?php echo $current; ?>" data-passing-condition="<?php echo $passing_condition; ?>">
		<?php if ( $heading !== false ): ?>
			<label class="lp-course-progress-heading"><?php echo $heading; ?>
				<span class="value result"><b class="number"><?php echo esc_html($current); ?></b>%</span></label>
		<?php endif; ?>
		<div class="lp-progress-bar value">
			<div class="lp-progress-value percentage-sign" style="width: <?php echo esc_attr( $result ); ?>%;">
			</div>
		</div>
	</div>
</div>