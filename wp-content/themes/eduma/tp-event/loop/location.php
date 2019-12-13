<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( tp_event_location() ):
	if ( function_exists( 'tp_event_get_location_map' ) ) {
		echo '<div class="entry-location">';
		tp_event_get_location_map();
		echo '</div>';
	}
endif;