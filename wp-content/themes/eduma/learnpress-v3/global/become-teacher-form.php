<?php
/**
 * Template for displaying the form let user fill out their information to become a teacher.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/global/become-teacher-form.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
$user = learn_press_get_current_user( false );
?>

<div id="learn-press-become-teacher-form" class="become-teacher-form learn-press-form <?php if( $user && ! $user instanceof LP_User_Guest && !learn_press_become_teacher_sent() ) echo 'allow'; else echo 'block-fields';?>">

    <h4 class="teacher-title"><?php esc_html_e( 'Register to become a teacher', 'eduma' ); ?></h4>

    <?php if ( ! $user || $user instanceof LP_User_Guest ) { ?>
        <p class="message message-info"><?php printf( __( 'You have to <a class="login" href="%s">login</a> to fill out this form', 'eduma' ), add_query_arg( 'redirect_to', get_permalink(), thim_get_login_page_url() ) ); ?></p>
    <?php } ?>

    <form name="become-teacher-form" method="post" enctype="multipart/form-data" action="">

		<?php do_action( 'learn-press/before-become-teacher-form' ); ?>

		<?php do_action( 'learn-press/become-teacher-form' ); ?>

		<?php do_action( 'learn-press/after-become-teacher-form' ); ?>

    </form>

</div>
