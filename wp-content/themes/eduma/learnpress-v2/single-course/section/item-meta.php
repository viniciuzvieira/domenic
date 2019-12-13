<?php
/**
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       2.1.6
 */

defined( 'ABSPATH' ) || exit();

$user        = learn_press_get_current_user();
$course      = LP()->global['course'];
$course_item = $course->get_item( $item->ID );
$theme_options_data = get_theme_mods();
$style_content = isset($theme_options_data['thim_layout_content_page']) ? $theme_options_data['thim_layout_content_page'] : 'normal';

$is_enrolled      = $user->has_enrolled_course( $course->id );
$require_enrolled = $course->is_require_enrollment();


$status      = $user->get_course_status( $course->id );
$item_status = $user->get_item_status( $item->ID );

$item_preview = $item_status !== 'completed' && !$is_enrolled && $require_enrolled && $course_item->is_preview();

$security = wp_create_nonce( sprintf( 'complete-item-%d-%d-%d', $user->id, $course->id, $item->ID ) );

$result     = $user->get_quiz_results( $item->ID );
$has_result = false;
if ( in_array( $item_status, array( 'completed', 'started' ) ) ) {
	$has_result = true;
}
?>
<div class="course-item-meta">
	<?php do_action( 'learn_press_before_item_meta', $item ); ?>
	<?php if ( $item_preview ): ?>
		<a title="<?php esc_html_e( 'Previews', 'eduma' ); ?>" class="lesson-preview button-load-item" href="<?php echo $course->get_item_link( $item->ID ); ?>" data-id="<?php echo $item->ID; ?>" data-complete-nonce="<?php echo wp_create_nonce( 'learn-press-complete-' . $item->post_type . '-' . $item->ID ); ?>">
            <i class="fa fa-eye" aria-hidden="true"></i>
            <?php
            if( $style_content == 'new-1' ) {
                _e( 'Preview', 'eduma' );
            }
            ?>
        </a>
	<?php endif; ?>
	<?php
	if ( $user->can_view_item( $item->ID, $course->id ) !== false ) {
		if ( $item->post_type == 'lp_quiz' ) {

			$passing_grade_type = get_post_meta( $item->ID, '_lp_passing_grade_type', true );
			?>

			<?php
			if ( $course->is( 'final-quiz', $item->ID ) ) {
				?><span class="item-loop-meta-text item-final"><?php _e( 'Final Quiz', 'learnpress' ); ?></span><?php
			}
			if ( $item_status == 'completed' ) {
				$grade = $user->get_quiz_graduation( $course_item->id, $course->id );
				if ( $grade === 'passed' ) {
					?>
				<span class="lp-icon item-status item-status-passed" title="<?php esc_attr_e( 'Passed', 'learnpress' ); ?>"></span><?php
				} else {
					?>
				<span class="lp-icon item-status item-status-failed" title="<?php esc_attr_e( 'Failed', 'learnpress' ); ?>"></span><?php
				}
			} elseif ( $item_status == 'viewed' ) {
				?>
			<span class="lp-icon item-status item-status-viewed" title="<?php esc_attr_e( 'Viewed', 'learnpress' ); ?>"></span><?php
			} elseif ( $item_status == 'started' ) {
				?>
			<span class="lp-icon item-status item-status-started" title="<?php esc_attr_e( 'In Progress', 'learnpress' ); ?>"></span><?php
			}
		} else {
			if ( $item_status == 'completed' ) {
				?>
				<span class="lp-icon item-status item-status-passed" title="<?php esc_attr_e( 'Completed', 'learnpress' ); ?>"></span>
				<?php
			} elseif ( $item_status == 'viewed' ) { ?>
				<span class="lp-icon item-status item-status-viewed" title="<?php esc_attr_e( 'Viewed', 'learnpress' ); ?>"></span>
				<?php
			}
		}
		if ( !$item_status ) {
			?>
			<span class="lp-icon item-status"></span>
			<?php
		}
	}
	?>
	<?php do_action( 'learn_press_after_item_meta', $item ); ?>
</div>
<?php if ( $item->post_type == 'lp_quiz' ) : ?>
	<div class="meta"><?php echo thim_quiz_questions( $item->ID ) . ' ' . esc_html__( 'questions', 'eduma' ); ?></div>
<?php else: ?>
	<div class="meta"><?php echo thim_lesson_duration( $item->ID ); ?></div>
<?php endif ?>
