<?php
/**
 * Template for displaying content of quiz's question
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user   = learn_press_get_current_user();
$course = LP()->global['course'];
$quiz   = isset( $item ) ? $item : LP()->global['course-item'];
if ( !$quiz ) {
	return;
}
$question_id = $user->get_current_quiz_question( $quiz->id, $course->id );//$quiz->get_current_question();

if ( !$question_id ) {
	return;
}
$question = LP_Question_Factory::get_question( $question_id );

if ( $user->has_quiz_status( 'started', $quiz->id, $course->id )  ) {
	$position = $quiz->get_question_position( $user->get_current_quiz_question( $quiz->id, $course->id ), $user->id );
	if ( $position === false ) {
		$position = 1;
	} else {
		$position ++;
	}
	echo '<p class="index-question hide-if-js">' . esc_html__( 'Question', 'eduma' ) . ' ' . '<span class="number">' . $position . '&#47;' . $quiz->get_total_questions() . '</span></p>';
}

?>
<?php if ( false !== ( $title = apply_filters( 'learn_press_quiz_question_title', $question->get_title() ) ) ): ?>
	<h4 class="quiz-question-title"><?php echo $title; ?></h4>
<?php endif; ?>
<div class="quiz-question-content quiz-question-nav">
	<div method="post" name="quiz-question-content" class="lp-question-wrap">
		<?php
		$content = apply_filters( 'learn_press_quiz_question_content', $question->get_content() );
		if ( !empty( $content ) ) :
			?>
			<div class="question-content">
				<?php echo $content; ?>
			</div>
		<?php endif; ?>
		<?php
		$question->render( array( 'quiz_id' => $quiz->id, 'course_id' => $course->id ) );
		?>
		<?php learn_press_get_template( 'content-question/hint.php', array( 'quiz' => $quiz ) ); ?>
	</div>
</div>
