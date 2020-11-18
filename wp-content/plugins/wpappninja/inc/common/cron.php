<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/*
 * Define and execute cron every three minutes.
 *
 * @since 1.0
 */

add_filter( 'cron_schedules', 'wpappninja_cron_every_three_minutes' );
function wpappninja_cron_every_three_minutes( $schedules ) {
	$schedules['wpappninja_every_three_minutes'] = array(
		'interval'  => 180,
		'display'   => __( 'Every 3 Minutes', 'wpappninja' )
	);
 
	return $schedules;
}


/**
 * Send all awaiting notification via a cron
 *
 * @since 1.0
 */
add_action('wpappninjacron', 'wpappninja_cron');
function wpappninja_cron() {

	if (get_transient("wpappninjabotworking")) {
		return;
	}

	set_transient( "wpappninjabotworking", true, 60*5 );

	$nbCron = 0;
	foreach (_get_cron_array() as $cron) {
		if (key($cron) == 'wpappninjacron'){
			$nbCron++;
		}
	}
				
	if ($nbCron != 1) {
		wp_clear_scheduled_hook( 'wpappninjacron' );
		wp_schedule_event( time(), 'wpappninja_every_three_minutes', 'wpappninjacron' );
	}


	global $wpdb;

	// fix ghost language
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_ids SET `lang` = %s WHERE `lang` = %s", "en", ""));


	//$wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_push_perso WHERE {$wpdb->prefix}wpappninja_push_perso.user_id NOT IN (SELECT {$wpdb->prefix}wpappninja_ids.device_id FROM {$wpdb->prefix}wpappninja_ids)");
	
	$query = $wpdb->get_results($wpdb->prepare("SELECT `id`, `id_post`, `titre`, `message`, `image`, `lang`, `category` FROM {$wpdb->prefix}wpappninja_push WHERE `send_date` < %s AND `send_date` > %s AND sended = '0' ORDER BY `id` DESC", current_time('timestamp'), current_time('timestamp') - 1200));
	foreach($query as $obj) {
		
		$default = $obj->lang;
		//if ($default == 'en') {$default = '';}

		if ($obj->id_post > 0) {
			if (get_post_status($obj->id_post) != 'publish') {
				continue;
			}
		}

		$savedRole = "";
		if (preg_match('#^role___#', $obj->category)) {
			$savedRole = preg_replace('#^role___#', '', $obj->category);
			$obj->category = '@';
		}

		if ($default == 'all') {
			if ($obj->category != "") {
				$row = $wpdb->get_results($wpdb->prepare("SELECT registration_id, wpapp_perso.`category` as maincategory FROM {$wpdb->prefix}wpappninja_ids as wpapp_ids JOIN {$wpdb->prefix}wpappninja_push_perso as wpapp_perso ON wpapp_ids.device_id = wpapp_perso.user_id WHERE wpapp_perso.`category` LIKE %s", "%" . $obj->category . "%"));
			} else {
				$row = $wpdb->get_results($wpdb->prepare("SELECT registration_id FROM {$wpdb->prefix}wpappninja_ids WHERE `lang` != %s", "xxx"));
			}
		} else {
			if ($obj->category != "") {
				$row = $wpdb->get_results($wpdb->prepare("SELECT registration_id, wpapp_perso.`category` as maincategory FROM {$wpdb->prefix}wpappninja_ids as wpapp_ids JOIN {$wpdb->prefix}wpappninja_push_perso as wpapp_perso ON wpapp_ids.device_id = wpapp_perso.user_id WHERE wpapp_perso.`category` LIKE %s AND (`lang` = %s OR `lang` = %s)", "%" . $obj->category . "%", $obj->lang, $default));
			} else {
				$row = $wpdb->get_results($wpdb->prepare("SELECT registration_id FROM {$wpdb->prefix}wpappninja_ids WHERE `lang` = %s OR `lang` = %s", $obj->lang, $default));
			}
		}



		$ids = array();
		foreach($row as $r) {

			if ($savedRole != "") {

				$user_data = get_user_by('email', $r->maincategory);
				foreach ($user_data->roles as $v => $role) {
					if ($role == $savedRole) {
						$ids[] = $r->registration_id;
					}
				}
				
			} else {
				$ids[] = $r->registration_id;
			}
		}
		
		if ($obj->id_post > 0) {
			$permalink = get_permalink($obj->id_post);
		} else {
			$permalink = site_url(); 
		}

		if (preg_match('#^http#', $obj->id_post)) {
			$permalink = $obj->id_post;
		}

		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push SET `sended` = %s WHERE `id` = %s", '1', $obj->id));

		$log = wpappninja_send_push($ids, html_entity_decode(stripslashes($obj->titre)), html_entity_decode(stripslashes($obj->message)), html_entity_decode(stripslashes($obj->image)), $obj->id_post, $permalink, $obj->category, $obj->id);
		
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push SET `log` = %s, `sended` = %s WHERE `id` = %s", $log, '1', $obj->id));
		
		// stats
		$android = 0;
		$ios = 0;
		foreach ($ids as $id) {
			if (substr($id, 0, 5) == "_IOS_") {
				$ios++;
			} else {
				$android++;
			}
		}

		$pushidstats = md5($obj->titre.$obj->category);
		wpappninja_stats_log('push/' . $pushidstats, $ios, true, $obj->lang);
		wpappninja_stats_log('push/' . $pushidstats, $android, false, $obj->lang);
	}

	delete_transient("wpappninjabotworking");
}

/**
 * Push the number of installations.
 *
 * @since 4.2.0
 */
//add_action('wpappninjacronnbinstall', '_wpappninjacronnbinstall');
function _wpappninjacronnbinstall() {

}
