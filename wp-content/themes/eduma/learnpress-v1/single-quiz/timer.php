<?php
/**
 * Template for displaying the countdown timer
 *
 * @author  ThimPress
 * @package LearnPress
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$quiz = LP()->quiz;
$user = LP()->user;
if ( !$quiz ) {
	return;
}
$remaining_time = $user->get_quiz_status() != 'started' ? $quiz->duration : $user->get_quiz_time_remaining( $quiz->id );
?>

<div class="quiz-clock">
	<div class="quiz-total">
		<i class="fa fa-bullhorn"></i>
		<div class="quiz-text"><?php echo '<span class="number">'.thim_quiz_questions( get_the_ID() ) . '</span> '; ?><?php echo thim_quiz_questions( get_the_ID() ) > 1 ? esc_html__( 'Questions', 'eduma' ) : esc_html__( 'Question', 'eduma' ); ?></div>
	</div>
	<div class="quiz-countdown quiz-timer<?php echo !$user->get_quiz_status( $quiz->id ) ? ' hide-if-js' : ''; ?> ">
		<i class="fa fa-clock-o"></i>
		<span class="quiz-text"><?php echo esc_html__( 'Time', 'eduma' ); ?></span>
		<span id="quiz-countdown-value"><?php echo $remaining_time > 59 ? date( 'G:i:s', $remaining_time ) : date( 'i:s', $remaining_time ); ?></span>
		<span class="quiz-countdown-label quiz-text">
			<?php
			echo apply_filters(
					'learn_press_quiz_time_label',
					$remaining_time > 59 ? esc_html__( '(h:m:s)', 'eduma' ) : esc_html__( '(m:s)', 'eduma' )
			);
			?>
		</span>
	</div>
</div>

