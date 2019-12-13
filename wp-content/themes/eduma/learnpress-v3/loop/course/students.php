<?php
/**
 * Template for displaying course students within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/students.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
$count = $course->get_users_enrolled();
?>

<div class="course-students">
    <label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
    <div class="value"><i class="fa fa-group"></i>
        <?php echo esc_html( $count ); ?>
    </div>
    <span><?php echo $count > 1 ? sprintf( __( 'students', 'learnpress' ) ) : sprintf( __( 'student', 'learnpress' ) );?></span>
</div>
