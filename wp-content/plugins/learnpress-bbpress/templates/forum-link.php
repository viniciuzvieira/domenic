<?php
/**
 * Template for displaying forum link in single course page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/bbpress/forum-link.php.
 *
 * @author ThimPress
 * @package LearnPress/bbPress/Templates
 * @version 3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;
?>

<p><?php _e( 'Course Forum:', 'learnpress-bbpress' ); ?>
    <a class="learn-press-course-forum-link"
       href="<?php echo get_permalink( $forum_id ); ?>"><?php echo get_the_title( $forum_id ); ?></a>
</p>