<?php
/**
 * Template for displaying countdown of the quiz
 *
 * @package LearnPress/Templates
 * @author  ThimPress
 * @version 1.0
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
$duration = $quiz->get_duration_html();
$remaining_time = $quiz->duration;
?>

<div class="quiz-clock">
	<div class="quiz-total">
		<i class="fa fa-bullhorn"></i>
		<div class="quiz-text"><?php echo '<span class="number">'.thim_quiz_questions( $quiz->id ) . '</span> '; ?><?php echo thim_quiz_questions( $quiz->id ) > 1 ? esc_html__( 'Questions', 'eduma' ) : esc_html__( 'Question', 'eduma' ); ?></div>
	</div>
	<?php if ( strpos( $duration, ':' ) == true ) { ?>
	<div class="quiz-countdown quiz-timer">
		<i class="fa fa-clock-o"></i>
		<span class="quiz-text"><?php echo esc_html__( 'Time', 'eduma' ); ?></span>
		<div id="quiz-countdown" class="quiz-countdown" data-value="100">
			<div class="countdown"><span><?php echo $quiz->get_duration_html(); ?></span></div>
		</div>
		<span class="quiz-countdown-label quiz-text">
			<?php
			echo $remaining_time > 3599 ? esc_html__( '(h:m:s)', 'eduma' ) : esc_html__( '(m:s)', 'eduma' );
			?>
		</span>
	</div>
	<?php }?>
</div>