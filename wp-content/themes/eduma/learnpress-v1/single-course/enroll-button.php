<?php
/**
 * Template for displaying the enroll button
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;

if ( ! $course->is_required_enroll() ) {
	return;
}


$course_status = LP()->user->get_course_status( $course->ID );
$user          = LP()->user;

//var_dump($user->has( 'enrolled-course', $course->id ));
// only show enroll button if user had not enrolled
$purchase_button_text = apply_filters( 'learn_press_purchase_button_text', __( 'Take This Course', 'eduma' ) );
$enroll_button_text   = apply_filters( 'learn_press_enroll_button_loading_text', __( 'Enroll', 'eduma' ) );
?>
<?php if ( !$user->has( 'enrolled-course', $course->id ) ): ?>

	<?php if ( $user->has( 'purchased-course', $course->id ) ) : ?>

		<?php if ( $user->can( 'enroll-course', $course->id ) ) : ?>

			<form name="enroll-course" class="form-purchase-course enroll-course" method="post" enctype="multipart/form-data">
				<?php do_action( 'learn_press_before_enroll_button' ); ?>

				<input type="hidden" name="lp-ajax" value="enroll-course" />
				<input type="hidden" name="enroll-course" value="<?php echo esc_attr( $course->id ); ?>" />
				<input type="hidden" name="_wp_http_referer" value="<?php echo get_the_permalink(); ?>" />
				<button class="button enroll-button thim-enroll-course-button"><?php echo esc_html( $enroll_button_text ); ?></button>

				<?php do_action( 'learn_press_after_enroll_button' ); ?>
			</form>

		<?php endif; ?>

	<?php elseif ( $user->can( 'purchase-course', $course->id ) ) : ?>

		<?php if ( LP()->cart && LP()->cart->has_item( $course->id ) ) : ?>
			<a class="button view-cart-button" href="<?php echo learn_press_get_page_link( 'cart' ); ?>"><?php esc_html_e( 'View cart', 'eduma' ); ?></a>
		<?php else: ?>

			<form name="purchase-course" class="form-purchase-course purchase-course" method="post" enctype="multipart/form-data">
				<?php do_action( 'learn_press_before_purchase_button' ); ?>
				<input type="hidden" name="_wp_http_referer" value="<?php echo get_the_permalink(); ?>" />
				<input type="hidden" name="add-course-to-cart" value="<?php echo esc_attr( $course->id ); ?>" />
				<button class="button purchase-button thim-enroll-course-button"><?php echo esc_html( $purchase_button_text ); ?></button>
				<a class="button view-cart-button hide-if-js" href="<?php echo learn_press_get_page_link( 'cart' ); ?>" data-loading-text="<?php esc_attr_e('Processing', 'eduma'); ?>"><?php esc_html_e( 'View cart', 'eduma' ); ?></a>
				<?php do_action( 'learn_press_after_purchase_button' ); ?>
			</form>

		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>

