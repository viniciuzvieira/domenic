<?php
/*
Shortcodes Visual Composer for theme Eduma.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THIM_SC_PATH', THIM_DIR . 'vc-shortcodes' );
define( 'THIM_SC_URL', plugin_dir_url( __FILE__ ) );

// Map shortcodes to Visual Composer
require_once( THIM_DIR . 'vc-shortcodes/vc-map.php' );

// Register new parameters for shortcodes
require_once( THIM_DIR . 'vc-shortcodes/vc-functions.php' );

// Register shortcodes
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/heading/heading.php' );

if ( thim_plugin_active( 'learnpress/learnpress.php' ) ) {
    require_once( THIM_DIR . 'vc-shortcodes/shortcodes/courses/courses.php' );
    require_once( THIM_DIR . 'vc-shortcodes/shortcodes/course-categories/course-categories.php' );
    require_once( THIM_DIR . 'vc-shortcodes/shortcodes/one-course-instructors/one-course-instructors.php' );
    require_once( THIM_DIR . 'vc-shortcodes/shortcodes/list-instructors/list-instructors.php' );
}

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/carousel-posts/carousel-posts.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/testimonials/testimonials.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/our-team/our-team.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/counters-box/counters-box.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/countdown-box/countdown-box.php' );


require_once( THIM_DIR . 'vc-shortcodes/shortcodes/gallery-images/gallery-images.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/gallery-posts/gallery-posts.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/google-map/google-map.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/login-form/login-form.php' );

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/timetable/timetable.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/tab/tab.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/video/video.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/portfolio/portfolio.php' );



require_once( THIM_DIR . 'vc-shortcodes/shortcodes/icon-box/icon-box.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/image-box/image-box.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/single-images/single-images.php' );
require_once( THIM_DIR . 'vc-shortcodes/shortcodes/social/social.php' );

if ( thim_plugin_active( 'tp-event/tp-event.php' ) || thim_plugin_active( 'tp-event/event.php' ) || thim_plugin_active( 'wp-events-manager/wp-events-manager.php' ) ) {
	require_once( THIM_DIR . 'vc-shortcodes/shortcodes/tab-event/tab-event.php' );
	require_once( THIM_DIR . 'vc-shortcodes/shortcodes/list-events/list-events.php' );
}

if ( thim_plugin_active( 'learnpress-collections/learnpress-collections.php' ) ) {
	require_once( THIM_DIR . 'vc-shortcodes/shortcodes/courses-collection/courses-collection.php' );
}

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/courses-searching/courses-searching.php' );

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/button/button.php' );

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/list-post/list-post.php' );

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/carousel-categories/carousel-categories.php' );

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/link/link.php' );

require_once( THIM_DIR . 'vc-shortcodes/shortcodes/multiple-images/multiple-images.php' );

if ( thim_plugin_active( 'thim-twitter/thim-twitter.php' ) ) {
	require_once( THIM_DIR . 'vc-shortcodes/shortcodes/twitter/twitter.php' );
}

