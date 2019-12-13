<?php
/**
 * Template for displaying header of single course popup.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/header.php.
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

<div id="course-item-content-header">

    <div id="popup-header">
        <div class="courses-searching">
            <input type="text" value="" name="s" placeholder="<?php esc_attr_e( 'Search courses', 'eduma' ) ?>" class="thim-s form-control courses-search-input" autocomplete="off" />
            <input type="hidden" value="course" name="ref" />
            <button type="submit"><i class="fa fa-search"></i></button>
            <span class="widget-search-close"></span>
            <ul class="courses-list-search"></ul>
        </div>

        <a href="<?php echo $course->get_permalink(); ?>" class="back_course"><i class="fa fa-close"></i></a>

        <a class="toggle-content-item" href=""></a>
    </div>


</div>
