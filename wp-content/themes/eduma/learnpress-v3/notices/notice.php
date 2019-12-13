<?php
/**
 * Template for displaying all notice messages from queue.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/notices/notice.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php if ( ! $messages ) {
	return;
} ?>

<?php foreach ( $messages as $message ) { ?>

    <div class="message message-warning learn-press-message">

		<?php echo $message; ?>

    </div>

<?php } ?>
