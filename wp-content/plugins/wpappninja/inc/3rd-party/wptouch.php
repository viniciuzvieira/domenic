<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

add_filter( 'wptouch_should_init_pro', 'wpmobileapp_disable_wptouch');
function wpmobileapp_disable_wptouch() {
    
    if (is_wpappninja()) {
    	return false;
    }

    return true;
}
