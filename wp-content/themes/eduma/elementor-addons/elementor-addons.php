<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THIM_EL_PATH', THIM_DIR . 'elementor-addons/' );
define( 'THIM_EL_ADDONS_PATH', THIM_EL_PATH . 'elements/' );

require_once THIM_EL_PATH . 'inc/elementor-helper.php';
require_once THIM_EL_PATH . 'inc/class-elementor-extend-icons.php';

function thim_add_new_elements() {
	require_once THIM_EL_PATH . 'inc/helper.php';

	//Load elements
	require THIM_EL_ADDONS_PATH . 'heading/heading.php';
	require THIM_EL_ADDONS_PATH . 'icon-box/icon-box.php';
	require THIM_EL_ADDONS_PATH . 'countdown-box/countdown-box.php';
	require THIM_EL_ADDONS_PATH . 'carousel-post/carousel-post.php';
	require THIM_EL_ADDONS_PATH . 'counters-box/counters-box.php';
	require THIM_EL_ADDONS_PATH . 'google-map/google-map.php';
	require THIM_EL_ADDONS_PATH . 'gallery-images/gallery-images.php';
	require THIM_EL_ADDONS_PATH . 'accordion/accordion.php';
	require THIM_EL_ADDONS_PATH . 'tab/tab.php';
	require THIM_EL_ADDONS_PATH . 'link/link.php';
	require THIM_EL_ADDONS_PATH . 'gallery-posts/gallery-posts.php';
	require THIM_EL_ADDONS_PATH . 'login-form/login-form.php';
	require THIM_EL_ADDONS_PATH . 'navigation-menu/navigation-menu.php';
    require THIM_EL_ADDONS_PATH . 'video/video.php';
    require THIM_EL_ADDONS_PATH . 'list-post/list-post.php';
    require THIM_EL_ADDONS_PATH . 'carousel-categories/carousel-categories.php';
    require THIM_EL_ADDONS_PATH . 'social/social.php';
    require THIM_EL_ADDONS_PATH . 'image-box/image-box.php';


    if ( class_exists( 'THIM_Testimonials' ) ) {
		require THIM_EL_ADDONS_PATH . 'testimonials/testimonials.php';
	}

	if ( class_exists( 'Thim_Portfolio' ) ) {
		require THIM_EL_ADDONS_PATH . 'portfolio/portfolio.php';
	}

	if ( class_exists( 'THIM_Our_Team' ) ) {
		require THIM_EL_ADDONS_PATH . 'our-team/our-team.php';
	}

	if ( class_exists( 'WPEMS' ) ) {
		require THIM_EL_ADDONS_PATH . 'list-event/list-event.php';
	}

	if ( class_exists( 'LearnPress' ) ) {
		require THIM_EL_ADDONS_PATH . 'courses/courses.php';
		require THIM_EL_ADDONS_PATH . 'course-categories/course-categories.php';

		if ( class_exists( 'LP_Addon_Collections' ) ) {
            require THIM_EL_ADDONS_PATH . 'courses-collection/courses-collection.php';
        }
		if ( class_exists( 'LP_Addon_Co_Instructor' ) ) {
			require THIM_EL_ADDONS_PATH . 'list-instructors/list-instructors.php';
            require THIM_EL_ADDONS_PATH . 'one-course-instructors/one-course-instructors.php';
        }
	}
}

add_action( 'elementor/widgets/widgets_registered', 'thim_add_new_elements' );