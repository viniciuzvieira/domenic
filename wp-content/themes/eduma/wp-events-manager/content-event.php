<?php
$display_year = get_theme_mod( 'thim_event_display_year', false );
$class        = 'item-event';
$time_format  = get_option( 'time_format' );
$time_start   = wpems_event_start( $time_format );
$time_end     = wpems_event_end( $time_format );

$location   = wpems_event_location();
$date_show  = wpems_get_time( 'd' );
$dayweek_show  = wpems_get_time( 'l' );
$month_show = wpems_get_time( 'F' );
if ( $display_year ) {
	$month_show .= ', ' . wpems_get_time( 'Y' );
}
?>
<div <?php post_class( $class ); ?>>
	<div class="event-wrapper">

		<div class="meta">
			<div class="time">
				<i class="fa fa-clock-o"></i>
				<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"><?php echo esc_html( $time_start ) . ' - ' . esc_html( $time_end ); ?></a>
			</div>
		</div>
	</div>

</div>
