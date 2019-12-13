<?php
/**
 * Template for displaying content of the quiz
 *
 * @author ThimPress
 */

error_reporting( 0 );

$user   = learn_press_get_current_user();
$course = LP()->global['course'];
$quiz   = isset( $item ) ? $item : LP()->global['course-item'];
if ( !$quiz ) {
	return;
}

$have_questions = $quiz->get_questions();
$can_view_item  = $user->can( 'view-item', $quiz->id, $course->id );

$user_finish_course = $user->has( 'finished-course', $course->id );

if ( $user_finish_course ) {
	learn_press_display_message( __( 'You can\'t start quiz because you have finished this course.', 'eduma' ), 'notice' );
	//return;
}

?>
<div class="content-item-quiz single-quiz">
	<?php
	if ( $user_finish_course ) :
		learn_press_get_template( 'content-quiz/description.php', array() );
	else: ?>
		<div id="content-item-<?php echo $quiz->id; ?>">

			<div id="quiz-<?php echo $quiz->id; ?>" class="learn-press-content-item-summary">

				<?php if ( $user->has_quiz_status( array( 'completed' ), $quiz->id, $course->id ) ): ?>
					<?php learn_press_get_template( 'content-quiz/description.php', array() ); ?>
					<?php learn_press_get_template( 'content-quiz/countdown-simple.php', array() ); ?>
					<?php //learn_press_get_template( 'content-quiz/intro.php' ); ?>
					<?php learn_press_get_template( 'content-quiz/result.php', array() ); ?>

				<?php elseif ( $user->has( 'quiz-status', 'started', $quiz->id, $course->id ) ): ?>
					<?php learn_press_get_template( 'content-quiz/countdown-simple.php', array() ); ?>
					<?php if ( $have_questions ): ?>
						<?php learn_press_get_template( 'content-quiz/question-content.php', array() ); ?>
					<?php endif; ?>
				<?php else: ?>

					<?php learn_press_get_template( 'content-quiz/description.php', array() ); ?>
					<?php learn_press_get_template( 'content-quiz/countdown-simple.php', array() ); ?>
					<?php learn_press_get_template( 'content-quiz/intro.php', array() ); ?>

				<?php endif; ?>

				<?php if ( $have_questions ) { ?>
					<?php learn_press_get_template( 'content-quiz/buttons.php', array() ); ?>
				<?php } ?>

			</div>

		</div>
		<?php if ( $have_questions ) { ?>
			<?php learn_press_get_template( 'content-quiz/questions.php', array() ); ?>
			<?php learn_press_get_template( 'content-quiz/history.php', array() ); ?>
		<?php } else { ?>
			<?php learn_press_display_message( __( 'No questions', 'eduma' ) ); ?>
		<?php } ?>

		<?php LP_Assets::add_var( 'LP_Quiz_Params', wp_json_encode( $quiz->get_settings( $user->id, $course->id ) ), '__all' ); ?>
	<?php endif; ?>

</div>