<?php
/**
 * The template for display the content of single course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;

$is_required = $course->is_required_enroll();
$user        = LP()->user;
$is_enrolled = $user->has( 'enrolled-course', $course->id );

do_action( 'learn_press_before_single_course' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/CreativeWork">

	<?php do_action( 'learn_press_before_single_course_summary' ); ?>

	<?php if ( $is_required && !$is_enrolled ) : ?>

		<?php if ( $user->has( 'purchased-course', $course->id ) ) : ?>

			<?php if ( ! $user->can( 'enroll-course', $course->id ) ) : ?>

				<?php learn_press_display_message( apply_filters( 'learn_press_user_purchased_course_message', esc_html__( 'You have already purchased this course. Please wait for approve', 'eduma' ), $course, $user ) , 'notice' ); ?>

			<?php endif; ?>

		<?php elseif ( $user->can( 'purchase-course', $course->id ) ) : ?>

			<?php if ( LP()->cart && LP()->cart->has_item( $course->id ) ) : ?>

				<?php learn_press_display_message( esc_html__( 'This course is already added to your cart', 'eduma' ), 'notice' ); ?>

			<?php else: ?>

			<?php endif; ?>

		<?php else: ?>

			<?php learn_press_display_message( apply_filters( 'learn_press_user_can_not_purchase_course_message', esc_html__( 'Sorry, you can not purchase this course', 'eduma' ), $course, $user ), 'error' ); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php
	the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' );
	?>

	<div class="course-meta">
		<?php learn_press_course_instructor(); ?>
		<?php learn_press_course_categories(); ?>
		<?php thim_course_forum_link(); ?>
		<?php thim_course_ratings(); ?>
		<?php learn_press_course_progress(); ?>
	</div>

	<div class="course-payment">
		<?php
		if ( ! $is_enrolled ) {
			learn_press_course_price();
			learn_press_course_enroll_button();
		}
		else {
			do_action( 'thim_end_course_payment' );
		}
		?>
	</div>

	<?php learn_press_get_template( 'single-course/thumbnail.php' ); ?>

	<div class="course-summary">

		<?php if ( $is_enrolled || !$is_required ) { ?>

			<?php learn_press_get_template( 'single-course/content-learning.php' ); ?>

		<?php } else { ?>

			<?php learn_press_get_template( 'single-course/content-landing.php' ); ?>

		<?php } ?>
	</div>

	<?php do_action( 'learn_press_after_single_course_summary' ); ?>

</article><!-- #post-## -->

<?php do_action( 'learn_press_after_single_course' ); ?>
