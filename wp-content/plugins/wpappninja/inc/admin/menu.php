<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add submenu in menu "Settings"
 *
 * @since 1.0
 */
add_action( 'admin_menu', '_wpappninja_settings_menu' );
function _wpappninja_settings_menu() {
	
	$count = wpappninja_check_icon();

	if (get_option('wpappninja_app_published') || 1>0) {
		$slug = WPAPPNINJA_HOME_SLUG;
	} else {
		$slug = WPAPPNINJA_PUBLISH_SLUG;
	}
	
	if (wpappninja_is_store_ready() || 1>0) {

		$function = "_wpappninja_display_home_page";
		if (get_wpappninja_option('nomoreqrcode', '0') == '1') {
			$function = "_wpappninja_display_newhome_page";
		}
	    add_menu_page(
    	    WPAPPNINJA_NAME,
    	    WPAPPNINJA_NAME . $count,
    	    wpappninja_get_right(),
    	    $slug,
    	    $function,
    	    'dashicons-smartphone',
    	    null 
    	);
	} else {
	    add_menu_page(
    	    WPAPPNINJA_NAME,
    	    WPAPPNINJA_NAME . $count,
    	    wpappninja_get_right(),
    	    $slug,
    	    '_wpappninja_display_publish_page',
    	    'dashicons-smartphone',
    	    null 
    	);
	}
	add_submenu_page( $slug, __('Preview', 'wpappninja'), __('Preview', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_PREVIEW_SLUG, '_wpappninja_display_preview_page' );

	if (get_wpappninja_option('nomoreqrcode', '0') == '0') {
		add_submenu_page( $slug, __('Configuration', 'wpappninja'), __('Configuration', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_PUBLISH_SLUG, '_wpappninja_display_publish_page' );
	} else {
		add_submenu_page( null, __('Configuration', 'wpappninja'), __('Configuration', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_PUBLISH_SLUG, '_wpappninja_display_publish_page' );
	}
	add_submenu_page( null, __('Design', 'wpappninja'), __('Design', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_AUTO_SLUG, '_wpappninja_display_auto_page' );
	add_submenu_page( $slug, __('Statistics', 'wpappninja'), __('Statistics', 'wpappninja'), wpappninja_get_right("stats"), WPAPPNINJA_STATS_SLUG, '_wpappninja_display_stats_page' );
	add_submenu_page( $slug, __('Notifications', 'wpappninja'), __('Notifications', 'wpappninja'), wpappninja_get_right("push"), WPAPPNINJA_PUSH_SLUG, '_wpappninja_display_push_page' );

	if (get_wpappninja_option('nomoreqrcode', '0') == '0') {
		add_submenu_page( $slug, __('QR Code', 'wpappninja'), __('QR Code', 'wpappninja'), wpappninja_get_right("qrcode"), WPAPPNINJA_QRCODE_SLUG, '_wpappninja_display_qrcode_page' );
		add_submenu_page( $slug, __('Adserver', 'wpappninja'), __('Advertising', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_ADSERVER_SLUG, '_wpappninja_display_adserver_page' );
	} else {
		add_submenu_page(null, __('QR Code', 'wpappninja'), __('QR Code', 'wpappninja'), wpappninja_get_right("qrcode"), WPAPPNINJA_QRCODE_SLUG, '_wpappninja_display_qrcode_page' );
		add_submenu_page( null, __('Adserver', 'wpappninja'), __('Advertising', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_ADSERVER_SLUG, '_wpappninja_display_adserver_page' );
	}

	add_submenu_page( null, __('Promote', 'wpappninja'), __('Promote', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_PROMOTE_SLUG, '_wpappninja_display_promote_page' );
	add_submenu_page( null, __('Publication', 'wpappninja'), __('Publication', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_PUBLISH_SLUG, '_wpappninja_display_publish_page' );
	add_submenu_page( null, __('Settings', 'wpappninja'), __('Settings', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_SLUG, '_wpappninja_display_options_page' );
	add_submenu_page( null, __('iOS Certificate', 'wpappninja'), __('iOS Certificate', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_CERT_SLUG, '_wpappninja_display_cert_page' );
	add_submenu_page( null, __('Update', 'wpappninja'), __('Update', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_UPDATE_SLUG, '_wpappninja_display_update_page' );
	add_submenu_page( null, __('Progressive Web App', 'wpappninja'), __('Progressive Web App', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_PWA_SLUG, '_wpappninja_display_pwa_page' );
	add_submenu_page( null, __('Theme', 'wpappninja'), __('Theme', 'wpappninja'), wpappninja_get_right(), WPAPPNINJA_THEME_SLUG, '_wpappninja_display_theme_page' );
}
