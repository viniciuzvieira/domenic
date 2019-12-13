<?php
/**
 * Template for displaying progress of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/progress.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
$user   = LP_Global::user();

if ( ! $course || ! $user ) {
	return;
}

if ( ! $user->has_enrolled_course( $course->get_id() ) ) {
	return;
}

$course_data       = $user->get_course_data( $course->get_id() );
$course_results    = $course_data->get_results( false );
$passing_condition = $course->get_passing_condition();
$current           = round( $course_results['result'], 2 );
$passed            = $current >= $passing_condition;
?>

<div class="learn-press-course-results-progress-">

    <div class="items-progress course-progress" style="display: none">

        <div class="lp-course-progress" data-value="<?php echo $course_results['completed_items']; ?>" data-passing-condition="<?php echo $course_results['count_items']; ?>">
            <?php if ( false !== ( $heading = apply_filters( 'learn-press/course/items-completed-heading', __( 'Items completed', 'eduma' ) ) ) ) { ?>
                <label class="lp-course-progress-heading"><?php esc_html( $heading ); ?>
                    <span class="value result">
                        <?php printf( __( '%d of %d items', 'eduma' ), $course_results['completed_items'], $course->count_items('', true) ); ?>
                    </span>
                </label>
            <?php } ?>
            <div class="lp-progress-bar value">
                <div class="lp-progress-value percentage-sign" style="width: <?php echo $course_results['count_items'] ? absint( $course_results['completed_items'] / $course_results['count_items'] * 100 ) : 0; ?>%;">
                </div>
            </div>
        </div>

    </div>

    <div class="course-progress">
        <div class="lp-course-progress<?php echo $passed ? ' passed' : ''; ?>" data-value="<?php echo $current; ?>" data-passing-condition="<?php echo $passing_condition; ?>">
		<?php if ( false !== ( $heading = apply_filters( 'learn-press/course/result-heading', __( 'Course results', 'eduma' ) ) ) ) { ?>
            <label class="lp-course-progress-heading"><?php echo $heading; ?>
                <span class="value result"><b class="number">
                        <?php echo $current; ?></b>%
                </span>
            </label>
		<?php } ?>

        <div class="lp-progress-bar value">
            <div class="lp-progress-value percentage-sign"
                 style="width: <?php echo $current; ?>%;">
            </div>
        </div>
        </div>
    </div>

</div>