<?php
/**
 * Template for displaying introduction of quiz.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/intro.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
$quiz   = LP_Global::course_item_quiz();
$count  = $quiz->get_retake_count();

?>

<div class="quiz-clock">
    <div class="quiz-total">
        <i class="fa fa-bullhorn"></i>
        <div class="quiz-text"><?php echo '<span class="number">' . $quiz->count_questions() . '</span> '; ?><?php echo $quiz->count_questions() > 1 ? esc_html__( 'Questions', 'eduma' ) : esc_html__( 'Question', 'eduma' ); ?></div>
    </div>
    <?php if ( strpos( $quiz->get_duration_html(), ':' ) == true ) { ?>
        <div class="quiz-countdown quiz-timer">
            <i class="fa fa-clock-o"></i>
            <span class="quiz-text"><?php echo esc_html__( 'Time', 'eduma' ); ?></span>
            <div id="quiz-countdown" class="quiz-countdown" data-value="100">
                <div class="countdown"><span><?php echo $quiz->get_duration_html(); ?></span></div>
            </div>
            <span class="quiz-countdown-label quiz-text">

		</span>
        </div>
    <?php }?>
</div>