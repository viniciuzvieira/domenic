<?php
/**
 * Template for displaying the history for the quiz
 *
 * @author  ThimPress
 * @package LearnPress
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$user   = learn_press_get_current_user();
$course = learn_press_get_the_course();
$quiz   = LP()->global['course-item'];

if ( !$quiz->retake_count || !$user->has( 'completed-quiz', $quiz->id, $course->id ) ) {
	return;
}

$limit   = 10;
$history = $user->get_quiz_history( $quiz->id, $course->id );
reset( $history );
$history_count = sizeof( $history );
$view_id       = !empty( $_REQUEST['history_id'] ) ? $_REQUEST['history_id'] : key( $history );
$heading       = sprintf( esc_html__( 'Other results (Latest %d items)', 'eduma' ), $limit );
?>

<?php
if ( $history_count > 1 ) {
	$position = 0;
	?>
	<?php if ( $heading ) { ?>
		<h3 class="quiz-history-title"><?php echo $heading; ?></h3>
	<?php } ?>
	<div class="lp-group-content-wrap" id="lp-quiz-history">
		<table class="quiz-history">
			<thead>
			<tr>
				<th width="50" align="right">#</th>
				<th><?php esc_html_e( 'Time', 'eduma' ); ?></th>
				<th><?php esc_html_e( 'Result', 'eduma' ); ?></th>
			</tr>
			</thead>
			<?php foreach ( $history as $item ) {
				if ( $item->history_id == $view_id ) continue;
				$results = $user->evaluate_quiz_results( $quiz->id, $item );
				$position ++; ?>
				<tr>
					<td align="right"><?php echo $position; ?></td>
					<td>
						<?php echo date_i18n( get_option( 'date_format' ), strtotime( $item->start ) ); ?>
						<div><?php echo date_i18n( get_option( 'time_format' ), strtotime( $item->start ) ); ?></div>
					</td>
					<td>
						<?php $mark_percent = !empty( $results['mark_percent'] ) ? $results['mark_percent'] : 0; ?>
						<?php printf( "%d%%", $mark_percent ); ?>
					</td>
				</tr>
				<?php if ( $position >= $limit ) break;
			} ?>
		</table>
	</div>
	<?php

} else {
	learn_press_display_message( __( 'No history found!', 'eduma' ), 'notice');
}