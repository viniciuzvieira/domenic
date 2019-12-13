<?php
/**
 * Template for displaying buttons of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/buttons.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php do_action( 'learn-press/before-course-buttons' ); ?>
<div class="thim-curriculum-buttons">
<?php
/**
 * @see learn_press_course_purchase_button - 10
 * @see learn_press_course_enroll_button - 10
 * @see learn_press_course_retake_button - 10
 */
do_action( 'learn-press/course-buttons' );
?>
</div>
<?php do_action( 'learn-press/after-course-buttons' ); ?>
