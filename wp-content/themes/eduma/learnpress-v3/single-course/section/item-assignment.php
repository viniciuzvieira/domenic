<?php
/**
 * Template for displaying assignment item in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/assignments/single-course/item-assignment.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Assignments/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit(); ?>

<?php echo $item->get_title( 'display' ); ?>