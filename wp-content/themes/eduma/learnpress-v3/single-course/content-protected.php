<?php
/**
 * Template for displaying message for course content protected.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/content-protected.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<div class="message message-error learn-press-message error">

    <?php
    if( $can_view_item && $can_view_item == 'not-enrolled' ){
        echo sprintf( __( 'This content is protected, please enroll course to view this content!', 'eduma' ) );
        learn_press_course_enroll_button();
    } else{
        echo sprintf( __( 'This content is protected, please <a href="%s">login</a> and enroll course to view this content!', 'eduma' ), thim_get_login_page_url() );
    }
    ?>

</div>