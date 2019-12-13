<?php
/**
 * Template for displaying progress of current quiz user are doing.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/progress.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.8
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user        = LP_Global::user();
$quiz        = LP_Global::course_item_quiz();
$question    = LP_Global::quiz_question();
$course_data = $user->get_course_data( get_the_ID() );
$quiz_item   = $course_data->get_item_quiz( $quiz->get_id() );
$quiz_data   = $user->get_quiz_data( $quiz->get_id() );

if ( $quiz_data->is_review_questions() ) {
	return;
}

if ( false === $quiz->get_duration() ) {
	return;
}

$result  = $quiz_data->get_results();
$percent = $quiz_data->get_questions_answered( true );
?>

<div class="quiz-progress quiz-clock">
    <div class="progress-items">
        <div class="progress-item quiz-current-question quiz-total">
            <i class="fa fa-bullhorn"></i>
            <span class="progress-number quiz-text">
                <?php _e( 'Question', 'eduma' ); ?>
                <span class="number">
                    <?php echo sprintf( __( '%d/%d', 'eduma' ), $quiz->get_question_index( $question ? $question->get_id() : $quiz_data->get_current_question(), 1 ), $quiz_data->get_total_questions() ); ?>
                </span>
            </span>
        </div>
        <div class="progress-item quiz-countdown quiz-timer">
            <i class="fa fa-clock-o"></i>
            <span class="quiz-text"><?php echo esc_html__( 'Time', 'eduma' ); ?></span>
            <span id="quiz-countdown" class="progress-number"> --:--:-- </span>
            <span class="quiz-countdown-label quiz-text"></span>
        </div>
    </div>
</div>