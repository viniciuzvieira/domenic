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

<div class="forum-link">
    <label><?php esc_html_e( 'Connect', 'eduma' ); ?></label>
    <div class="value">
        <a href="<?php echo get_the_permalink( $forum_id ); ?>"><?php esc_html_e( 'Forum', 'eduma' ); ?></a>
    </div>
</div>