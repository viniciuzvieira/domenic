<?php

// remove some wp head junk
remove_action('wp_head', 'feed_links_extra', 3 );
remove_action('wp_head', 'feed_links', 2 );
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0 );
remove_action('wp_head', 'start_post_rel_link', 10, 0 );
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action('wp_head', 'wp_generator');
remove_action('wp_head','jetpack_og_tags');
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );


if ( ! isset( $content_width ) ) $content_width = 1400;

// sidebars
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => __('WPMobile.App (before content)', 'wpappninja'),
		'id' => 'wpappninja-before', 
		'description' => __('Appears as the sidebar on top of the page', 'wpappninja'), 
		'before_widget' => '<div class="list-block">',
		'after_widget' => '</div>', 
		'before_title' => '<div class="content-block-title" style="margin-left: 0;">',
		'after_title' => '</div>',
		));
}
if ( function_exists('register_sidebar') ) {
	register_sidebar(array( 
		'name' => __('WPMobile.App (after content)', 'wpappninja'),
		'id' => 'wpappninja-after',
		'description' => __('Appears as the sidebar on bottom of the page', 'wpappninja'),
		'before_widget' => '<div class="list-block">', 
		'after_widget' => '</div>', 
		'before_title' => '<div class="content-block-title" style="margin-left: 0;">',
		'after_title' => '</div>',
		)); 
}

// body class
add_filter('body_class', 'wpappninja_theme_body_class');
function wpappninja_theme_body_class($classes) {
	$classes[] = 'theme-wpappninja';
	return $classes;
}

// scripts && css
add_action('wp_enqueue_scripts', 'wpappninja_theme_styles');
function wpappninja_theme_styles() { 

	wp_register_style( 'main-style', get_template_directory_uri() . '/style.css', array(), null, null );

	wp_enqueue_script( 'framework7-myapp', get_template_directory_uri() . '/js/my-app.js', array('jquery'), '1', true);
	wp_enqueue_style( 'main-style' );    

	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : "";
	
	$isIOS = false;
	if (preg_match('#ios|iPhone|iPad|iPod#i', $ua)) {
		$isIOS = true;
	}
	
	wp_register_style( 'framework7-ios', get_template_directory_uri() . '/css/framework7.ios.min.css', array(), null, null );
	wp_register_style( 'framework7-md', get_template_directory_uri() . '/css/framework7.material.min.css', array(), null, null );
	if ($isIOS) {
		wp_enqueue_style( 'framework7-ios' );
	} else {
		wp_enqueue_style( 'framework7-md' );
	}
}
