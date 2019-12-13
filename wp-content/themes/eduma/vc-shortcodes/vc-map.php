<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THIM_DIR_SHORTCODES_MAP', THIM_DIR . 'vc-shortcodes/inc-map/' );

/**
 * Mapping shortcodes
 */
function ts_map_vc_shortcodes() {

	include_once( THIM_DIR_SHORTCODES_MAP . 'carousel-posts.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'countdown-box.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'counters-box.php' );

    if ( thim_plugin_active( 'learnpress/learnpress.php' ) ) {

        include_once( THIM_DIR_SHORTCODES_MAP . 'course-categories.php' );

        include_once( THIM_DIR_SHORTCODES_MAP . 'courses.php' );

        include_once( THIM_DIR_SHORTCODES_MAP . 'courses-searching.php' );

        if ( thim_plugin_active( 'learnpress-collections/learnpress-collections.php' ) ) {
            include_once( THIM_DIR_SHORTCODES_MAP . 'courses-collection.php' );
        }

        if ( thim_plugin_active( 'learnpress-co-instructor/learnpress-co-instructor.php' ) ) {


            include_once( THIM_DIR_SHORTCODES_MAP . 'one-course-instructors.php' );

            include_once( THIM_DIR_SHORTCODES_MAP . 'list-instructors.php' );

        }

    }

	include_once( THIM_DIR_SHORTCODES_MAP . 'gallery-images.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'gallery-posts.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'google-map.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'heading.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'list-events.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'login-form.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'testimonials.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'timetable.php' );

    include_once( THIM_DIR_SHORTCODES_MAP . 'tab.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'video.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'our-team.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'icon-box.php' );

    include_once( THIM_DIR_SHORTCODES_MAP . 'image-box.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'single-images.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'social.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'portfolio.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'button.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'list-post.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'carousel-categories.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'link.php' );

	include_once( THIM_DIR_SHORTCODES_MAP . 'multiple-images.php' );

	if ( thim_plugin_active( 'thim-twitter/thim-twitter.php' ) ) {
		include_once( THIM_DIR_SHORTCODES_MAP . 'twitter.php' );
	}

}

add_action( 'vc_before_init', 'ts_map_vc_shortcodes' );
