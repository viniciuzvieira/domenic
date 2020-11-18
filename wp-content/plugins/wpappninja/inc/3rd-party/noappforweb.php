<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Disable app for web
 *
 */
    
if (isset($_COOKIE['HTTP_X_WPAPPNINJA']) && preg_match('#macintosh#i', $_SERVER['HTTP_USER_AGENT'])) {
            
    $_SERVER['HTTP_USER_AGENT'] = "Mozilla/5.0 (iPad; CPU OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B329 Safari/8536.25";
}
    
if ((isset($_COOKIE['HTTP_X_WPAPPNINJA']) && !preg_match('#android|ios|wpmobile|wpapp|iphone|ipad|ipod#i', $_SERVER['HTTP_USER_AGENT'])) || is_admin()) {
    
    setcookie("HTTP_X_WPAPPNINJA", "", time() - 3600);
    unset($_COOKIE['HTTP_X_WPAPPNINJA']);
    unset($_SERVER['HTTP_X_WPAPPNINJA']);
}
