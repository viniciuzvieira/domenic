<?php
/**
 * Template for displaying all error messages from queue
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! $messages ){
	return;
}

?>
<?php foreach ( $messages as $message ) : ?>
	<div class="message message-error learn-press-error">
		<?php echo wp_kses_post( $message ); ?>
	</div>
<?php endforeach; ?>