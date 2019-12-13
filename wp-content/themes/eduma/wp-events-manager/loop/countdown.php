<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( class_exists( 'WPEMS' ) ) {
    $current_time = date( 'Y-m-d H:i' );
    $time         = wpems_get_time( 'Y-m-d H:i', null, false );
    if( $time > $current_time ) {
        ?>
        <div class="entry-countdown">
            <div class="tp_event_counter"
                 data-time="<?php echo esc_attr(wpems_get_time('M j, Y H:i:s O', null, false)); ?>">
            </div>
        </div>
        <?php
    }
} else {
	if ( version_compare( TP_EVENT_VER, '2.0', '>=' ) ) {
		$current_time = date( 'Y-m-d H:i' );
		$time         = tp_event_get_time( 'Y-m-d H:i', null, false );
		if( $time > $current_time ) {
            ?>
            <div class="entry-countdown">
                <?php $date = new DateTime(date('Y-m-d H:i', strtotime($time))); ?>
                <div class="tp_event_counter"
                     data-time="<?php echo esc_attr($date->format('M j, Y H:i:s O')); ?>"></div>
            </div>
            <?php
        }
	} else {
        $current_time = date( 'Y-m-d H:i' );
        $time         = tp_event_get_time( 'Y-m-d H:i', null, false );
        if( $time > $current_time ) {
            ?>
            <div class="entry-countdown">
                <div class="tp_event_counter"
                     data-time="<?php echo esc_attr(tp_event_get_time('M j, Y H:i:s O', null, false)); ?>">
                </div>
            </div>

            <p style="clear:both"></p>
            <?php
        }
	}
}
