<?php
/**
 * Template for displaying instructor of course within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/instructor.php.
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
?>

<div class="course-author" itemscope itemtype="http://schema.org/Person">
    <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?>
    <div class="author-contain">
        <div class="value" itemprop="name">
            <?php echo $course->get_instructor_html(); ?>
        </div>
    </div>
</div>