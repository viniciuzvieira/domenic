<?php
/**
 * Template for displaying Enroll button in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/buttons/enroll.php.
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

<?php if ( ! isset( $course ) ) {
	$course = learn_press_get_course();
}

$checkout_redirect = add_query_arg( 'enroll-course', $course->get_id(), $course->get_permalink() );
$login_redirect    = add_query_arg( 'redirect_to', $checkout_redirect, thim_get_login_page_url() );
?>

<div class="lp-course-buttons">

	<?php do_action( 'learn-press/before-enroll-form' ); ?>

	<form name="enroll-course" class="enroll-course form-purchase-course" method="post" enctype="multipart/form-data">

		<?php do_action( 'learn-press/before-enroll-button' ); ?>

		<input type="hidden" name="enroll-course" value="<?php echo esc_attr( $course->get_id() ); ?>" />
		<input type="hidden" name="enroll-course-nonce"
		       value="<?php echo esc_attr( LP_Nonce_Helper::create_course( 'enroll' ) ); ?>" />

		<button class="lp-button button button-enroll-course">
			<?php echo esc_html( apply_filters( 'learn-press/enroll-course-button-text', __( 'Take this course', 'eduma' ) ) ); ?>
		</button>

		<input type="hidden" name="redirect_to" value="<?php echo esc_url( $login_redirect ); ?>">

		<?php do_action( 'learn-press/after-enroll-button' ); ?>

	</form>

	<?php do_action( 'learn-press/after-enroll-form' ); ?>

</div>
