<?php

/**
 * Export meta data for front page displays settings
 */
function thim_export_front_page_displays_settings() {

	$page_for_posts = get_option( 'page_for_posts' );
	$page_on_front  = get_option( 'page_on_front' );

	delete_post_meta_by_key( 'thim_page_for_posts' );
	delete_post_meta_by_key( 'thim_page_on_front' );

	if ( $page_for_posts ) {
		update_post_meta( $page_for_posts, 'thim_page_for_posts', 1 );
	}
	if ( $page_on_front ) {
		update_post_meta( $page_on_front, 'thim_page_on_front', 1 );
	}
}

add_action( 'export_wp', 'thim_export_front_page_displays_settings' );

/**
 * Export meta data for menu location settings
 */
function thim_export_menu_location_settings() {
	$menu_locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! $menu_locations ) {
		return;
	}

	foreach ( $menu_locations as $location => $term ) {
		$objects = get_objects_in_term( $term, 'nav_menu' );
		if ( ! empty( $objects[0] ) ) {
			delete_post_meta_by_key( 'thim_object_in_location_' . $location );
			update_post_meta( $objects[0], 'thim_object_in_location_' . $location, $location );
		}
	}
}

add_action( 'export_wp', 'thim_export_menu_location_settings' );
