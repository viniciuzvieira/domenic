<?php
/**
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       1.0
 */

defined( 'ABSPATH' ) || exit();
global $post;
$result = $user->get_quiz_results( $post->ID );
?>

<div class="quiz-results">

	<h3 class="box-title"><?php echo get_the_title( $post->ID ); ?></h3>

	<div class="result-summary">
		<div class="result-field correct">
			<span><?php esc_html_e( 'Correct', 'eduma' ); ?></span>
			<span class="value"><?php echo esc_html( $result->results['correct'] ); ?></span>
		</div>
		<div class="result-field wrong">
			<span><?php esc_html_e( 'Incorrect', 'eduma' ); ?></span>
			<span class="value"><?php echo esc_html( $result->results['wrong'] ); ?></span>
		</div>
		<div class="result-field empty">
			<span><?php esc_html_e( 'Skipped', 'eduma' ); ?></span>
			<span class="value"><?php echo esc_html( $result->results['empty'] ); ?></span>
		</div>
		<div class="result-field points">
			<span><?php esc_html_e( 'Points', 'eduma' ); ?></span>
			<span class="value"><?php printf( '%d/%d', $result->results['mark'],  $result->results['quiz_mark'] ) ; ?></span>
		</div>
	</div>
</div>
