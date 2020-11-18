<?php
// If uninstall not called from WordPress exit
defined( 'WP_UNINSTALL_PLUGIN' ) or die( 'Cheatin&#8217; uh?' );

// Delete options
if (defined('WPAPPNINJA_SLUG')) {
	delete_option(WPAPPNINJA_SLUG);
}
delete_option('wpappninja');
delete_option('wpappninja_primary');
delete_option('wpappninja_secondary');
delete_option('wpappninja_pem_file');
delete_option('wpappninja_stats_box');
delete_option('wpappninja_nb_downloads');
delete_option('wpappninja_follow_tuto');
delete_option('wpappninja_app_published');
delete_option('wpappninja_android_deeplinking');
delete_option('wpappninja_pwa');
delete_option('wpappninja_backup');
delete_option('wpappninja_start_premium_feature');
delete_option('wpappninja_store_ready');
delete_option('wpappninja_need_update');
delete_option('wpappninja_old_ios_deeplinking');
delete_option('wpappninjaadminnoticeupdatedwv');
delete_option('wpappninja_pwa_menu');
delete_option('wpappninja_progressive_app');

// Delete post meta
delete_post_meta_by_key('_wpappninja_senddate');
delete_post_meta_by_key('_wpappninja_lang');
delete_post_meta_by_key('_wpappninja_set');
delete_post_meta_by_key('_wpappninja_send_type');
delete_post_meta_by_key('_wpappninja_sended');
delete_post_meta_by_key('_wpappninja_arrayids');

// Unregister CRON
wp_clear_scheduled_hook( 'wpappninjacron' );
wp_clear_scheduled_hook( 'wpappninjacronnbinstall' );

// Delete tables
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_push");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_ids");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_stats");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_stats_users");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_adserver");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_home_perso");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_installs");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_push_perso");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_qrcode");

// Delete transient
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}options WHERE `option_name` LIKE %s", '_transient_wpappninja_%'));
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}options WHERE `option_name` LIKE %s", '_transient_is_wpappninja_%'));
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}options WHERE `option_name` LIKE %s", '_transient_is_wpappninja_%'));
