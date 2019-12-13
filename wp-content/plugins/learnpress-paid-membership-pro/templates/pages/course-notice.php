<?php
$is_required = learn_press_pmpro_check_require_template();
if ( empty($is_required) ) {
	return;
}
$current_user   = $is_required['current_user'];
$post           = $is_required['post'];
$user_id        = $is_required['user_id'];
$user           = $is_required['user'];
$levels_page_id = $is_required['levels_page_id'];
$all_levels     = $is_required['all_levels'];
$all_levels_id  = $is_required['all_levels_id'];
$course         = $is_required['course'];
$levels         = $is_required['levels'];
$list_courses   = $is_required['list_courses'];

$redirect   = add_query_arg( 'course_id', $course->get_id(), get_the_permalink( $levels_page_id ) );
$redirect   = apply_filters( 'learn_press_pmpro_redirect_levels_page', $redirect, $course, $levels_page_id, $current_user );
$wrap_class = array();
$html       = '';

$buy_through_membership      = LP()->settings->get('buy_through_membership');
$buy_through_membership_text = LP()->settings->get('button_buy_membership');
if ( empty( $buy_through_membership_text ) ) {
	$buy_through_membership_text = __( 'Buy Membership', 'learnpress-paid-membership-pro' );
}
$buy_through_membership_text = apply_filters( 'learn_press_buy_through_membership_text', $buy_through_membership_text, $redirect, $course, $levels_page_id, $current_user );

do_action( 'learn_press_pmpro_before_course_notice' );
if ( empty( $buy_through_membership ) || $buy_through_membership === 'no' ) :
	$learn_press_active_filter = true;

else :
	$wrap_class[] = 'pmpro-no-buy-course';
	?>
	<script>
		(function ($) {
			$(document).ready(function () {
				$('form.purchase-course, form.enroll-course').remove();
			});
		})(jQuery)
	</script>
	<?php
endif;

$wrap_class[] = 'learn-press-pmpro-buy-membership purchase-course';
$html         = '<a class="button purchase-button" href="' . esc_url( $redirect ) . '">' . $buy_through_membership_text . '</a>';
$wrap_class   = apply_filters( 'learn_press_pmpro_wrap_template_attributes', $wrap_class, $course, $levels_page_id, $current_user );
$html         = apply_filters( 'learn_press_pmpro_template_course_notice', $html, $redirect, $course, $levels_page_id, $current_user );
?>
	<div id="learn-press-pmpro-notice" class="<?php echo implode( ' ', $wrap_class ); ?>">
		<?php
		do_action( 'learn_press_pmpro_before_template_course_notice' );
		echo $html;
		do_action( 'learn_press_pmpro_after_template_course_notice' );
		?>
	</div>

<?php
do_action( 'learn_press_pmpro_after_course_notice' );
