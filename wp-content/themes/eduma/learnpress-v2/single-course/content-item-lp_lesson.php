<?php
/**
 * Template for display content of lesson
 *
 * @author  ThimPress
 * @version 2.1.9.3
 */

error_reporting(0);

global $lp_query, $wp_query;
$user          = learn_press_get_current_user();
$course        = LP()->global['course'];
$item          = LP()->global['course-item'];
$security      = wp_create_nonce( sprintf( 'complete-item-%d-%d-%d', $user->id, $course->id, $item->ID ) );
$can_view_item = $user->can( 'view-item', $item->id, $course->id );

$block_option = get_post_meta( $course->id, '_lp_block_lesson_content', true );
$duration     = $course->get_user_duration_html( $user->id, true );
$user_data = get_userdata( $user->ID );
$admin     = false;
if ( $user_data && in_array( 'administrator', $user_data->roles ) ) {
	$admin = true;
}

if ( ! $admin && $course->is_expired() <= 0 && ( $block_option == 'yes' ) && ( get_post_meta( $item->id, '_lp_preview', true ) !== 'yes' ) ) {
	learn_press_get_template( 'content-lesson/block-content.php' );
} else {
	?>
	<div class="learn-press-content-item-summary">
		<?php learn_press_get_template( 'content-lesson/description.php' ); ?>

		<?php if ( $user->has_completed_lesson( $item->ID, $course->id ) ) { ?>
			<button class="complete-lesson-button completed" disabled="disabled"> <?php _e( 'Completed', 'eduma' ); ?></button>
		<?php } else if ( ! $user->has( 'finished-course', $course->id ) && ! in_array( $can_view_item, array(
				'preview',
				'no-required-enroll'
			) )
		) { ?>

			<form method="post" name="learn-press-form-complete-lesson" class="learn-press-form">
				<input type="hidden" name="id" value="<?php echo $item->id; ?>"/>
				<input type="hidden" name="course_id" value="<?php echo $course->id; ?>"/>
				<input type="hidden" name="security" value="<?php echo esc_attr( $security ); ?>"/>
				<input type="hidden" name="type" value="lp_lesson"/>
				<input type="hidden" name="lp-ajax" value="complete-item"/>
				<button class="complete-lesson-button button-complete-item button-complete-lesson"><?php echo __( 'Complete', 'learnpress' ); ?></button>

			</form>
		<?php } ?>
	</div>
<?php }?>

<?php LP_Assets::enqueue_script( 'learn-press-course-lesson' ); ?>

<?php LP_Assets::add_var( 'LP_Lesson_Params', wp_json_encode( $item->get_settings( $user->id, $course->id ) ), '__all' ); ?>

