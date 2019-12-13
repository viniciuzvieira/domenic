<?php
/**
 * Template for displaying the questions navigation
 *
 * @author  ThimPress
 * @package LearnPress
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$quiz = LP()->quiz;

if ( !$quiz || !$quiz->has( 'questions' ) ) {
	return;
}

$status = LP()->user->get_quiz_status( $quiz->id );

?>

<?php if( $status != 'completed' ){ ?>

	<?php if ( $quiz->show_check_answer == 'yes' ): ?>
	<div class="quiz-question-answer">
		<button type="button" data-nav="check" class="check_answer check-question hide-if-js">
			<?php echo apply_filters( 'learn_press_button_check_question_text', esc_html__( 'Check Answer', 'eduma' ) ); ?>
		</button>
	</div>
	<?php endif; ?>

	<div class="quiz-question-nav-buttons">

		<button type="button" data-nav="prev" class="prev-question hide-if-js">
			<?php echo apply_filters( 'learn_press_button_back_question_text', esc_html__( 'Back', 'eduma' ) ); ?>
		</button>

		<button type="button" data-nav="next" class="next-question hide-if-js">
			<?php echo apply_filters( 'learn_press_quiz_question_nav_button_next_text', esc_html__( 'Next', 'eduma' ) ); ?>
		</button>

	</div>

<?php }?>