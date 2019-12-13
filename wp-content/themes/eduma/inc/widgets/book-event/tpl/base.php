<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'WPEMS_VER' ) ) {
	if ( version_compare( WPEMS_VER, '2.0', '>=' ) ) {
		wpems_get_template( 'loop/booking-form.php', array( 'event_id' => get_the_ID() ) );
	}else{
		if ( version_compare( get_option( 'event_auth_version' ), '1.0.4', '>=' ) ) {
			tpe_auth_addon_get_template( 'form-book-event.php', array( 'event_id' => get_the_ID() ) );
		} else {
			WPEMS_Authentication()->loader->load_module( '\WPEMS_Auth\Events\Event' )->book_event_template();
		}
	}
} else if ( defined( 'TP_EVENT_VER' ) ) {
	if ( version_compare( TP_EVENT_VER, '2.0', '>=' ) ) {
		tp_event_get_template( 'loop/booking-form.php', array( 'event_id' => get_the_ID() ) );
	}else{
		if ( version_compare( get_option( 'event_auth_version' ), '1.0.4', '>=' ) ) {
			tpe_auth_addon_get_template( 'form-book-event.php', array( 'event_id' => get_the_ID() ) );
		} else {
			TP_Event_Authentication()->loader->load_module( '\TP_Event_Auth\Events\Event' )->book_event_template();
		}
	}
}else{
	return;
}


