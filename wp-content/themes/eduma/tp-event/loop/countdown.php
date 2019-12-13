<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( defined( 'TP_EVENT_VER' ) ) {
	if ( version_compare( TP_EVENT_VER, '2.0', '>=' ) ) {
		$current_time = date( 'Y-m-d H:i' );
		$time         = tp_event_get_time( 'Y-m-d H:i', null, false ); ?>
		<div class="entry-countdown">
			<?php $date = new DateTime( date( 'Y-m-d H:i', strtotime( $time ) ) ); ?>
			<div class="tp_event_counter" data-time="<?php echo esc_attr( $date->format( 'M j, Y H:i:s O' ) ) ?>"></div>
		</div>
		<?php
	} else {
		?>
		<div class="entry-countdown">
			<div class="tp_event_counter" data-time="<?php echo esc_attr( tp_event_get_time( 'M j, Y H:i:s O', null, false ) ) ?>">
			</div>
		</div>

		<p style="clear:both"></p>
		<?php
	}
}