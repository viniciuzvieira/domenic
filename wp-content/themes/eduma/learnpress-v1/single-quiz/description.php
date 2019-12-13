<?php
/**
 * Displaying the description of single quiz
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$quiz = LP()->quiz;
$user = LP()->user;
if ( $user->get_quiz_status( $quiz->id ) == 'started' ) {
	return;
}
?>

<div class="quiz-description" id="learn-press-quiz-description">

	<?php do_action( 'learn_press_begin_single_quiz_description' ); ?>

	<div class="quiz-content">
	<?php the_content(); ?>
	</div>

	<?php do_action( 'learn_press_end_single_quiz_description' ); ?>

</div>