<?php
/**
 * Template for displaying the content of current question
 *
 * @author  ThimPress
 * @package LearnPress
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $quiz;
if ( !LP()->user->has( 'completed-quiz', $quiz->id ) ) {
	return;
}

if( !is_user_logged_in() ){
	learn_press_display_message( sprintf( __( 'You are not logged in! Please <a href="%s">login</a> to save the results. The results will be deleted after your session destroyed', 'eduma' ), learn_press_get_login_url() ), 'error' );
}

$history = LP()->user->get_quiz_results( $quiz->id );

?>
<div class="quiz-results">
	<h3 class="result-title"><?php esc_html_e( 'Your result', 'eduma' ); ?></h3>

	<div class="result-summary">
		<div class="result-field correct">
			<span><?php echo apply_filters( 'learn_press_quiz_result_correct_text', esc_html__( 'Correct', 'eduma' ) ); ?></span>
			<span class="value"><?php echo esc_attr($history->results['correct']); ?></span>
		</div>
		<div class="result-field wrong">
			<span><?php echo apply_filters( 'learn_press_quiz_result_wrong_text', esc_html__( 'Incorrect', 'eduma' ) ); ?></span>
			<span class="value"><?php echo esc_attr($history->results['wrong']); ?></span>
		</div>
		<div class="result-field empty">
			<span><?php echo apply_filters( 'learn_press_quiz_result_empty_text', esc_html__( 'Skipped', 'eduma' ) ); ?></span>
			<span class="value"><?php echo esc_attr($history->results['empty']); ?></span>
		</div>
		<div class="result-field points">
			<span><?php esc_html_e( 'Points', 'eduma' ); ?></span>
			<span class="value"><?php printf( '%d/%d', $history->results['mark'],  $history->results['quiz_mark'] ) ; ?></span>
		</div>
		<div class="result-field time">
			<span><?php echo apply_filters( 'learn_press_quiz_result_time_text', esc_html__( 'Time', 'eduma' ) ); ?></span>
			<span class="value"><?php echo learn_press_seconds_to_time( $history->results['user_time'] ); ?></span>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?php
//learn_press_debug($history);
