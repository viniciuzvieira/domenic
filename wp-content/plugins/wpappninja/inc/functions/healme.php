<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Check and repair installation.
 *
 * @since 5.2
 */
function wpappninja_heal_me() {


	$response = wp_remote_get( 'https://api.wpmobile.app/healme.php?i=' . $_SERVER['REMOTE_ADDR'] );
	if( is_array($response) ) {
		if ($response['body'] != '1') {
			echo "*" . $response['body'] . "*";
			exit();
		}
	}


	

	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$wpdb->show_errors();

	$charset_collate = $wpdb->get_charset_collate();

	$output = "=== CONFIG ===";
	$output .= "\r\n";

     if (wpappninja_is_paid()) {
     	$output .= "PAID";
     	$output .= "\r\n";
     } else {
     	$output .= "NOT PAID";
     	$output .= "\r\n";
     }

     if (wpappninja_need_update()) {
     	$output .= "NEED UPDATE";
     	$output .= "\r\n";
     } else {
     	$output .= "DO NOT NEED UPDATE";
     	$output .= "\r\n";
     }

     if (wpappninja_is_store_ready()) {
     	$output .= "STORE READY";
     	$output .= "\r\n";
     } else {
     	$output .= "NOT STORE READY";
     	$output .= "\r\n";
     }

	$output .= "\r\n";

	$output .= "=== CRON ===";
	$output .= "\r\n";

	$nbCron = 0;


					
	foreach (_get_cron_array() as $cron) {
		if (key($cron) == 'wpappninjacron'){
			$nbCron++;
		}
	}
				
	if ($nbCron == 1) {
		$output .= 'OK';
	} else {
		$output .= 'REPAIRED';
		wp_clear_scheduled_hook( 'wpappninjacron' );
		wp_schedule_event( time(), 'wpappninja_every_three_minutes', 'wpappninjacron' );
	}

	$output .= "\r\n";
	$output .= "\r\n";

	$output .= "=== NOTIF ===";
	$output .= "\r\n";

	if (isset($_GET['bypass']) && ($_GET['bypass'] == "1" OR $_GET['bypass'] == "0")) {
		update_option('wpappninja_bypass_notif', $_GET['bypass']);
	}

	$output .= "\r\n";
	if (get_option('wpappninja_bypass_notif', '0') == "1") {
		$output .= "BYPASS ON";
		$output .= "\r\n";
	} else {
		$output .= "BYPASS OFF";
		$output .= "\r\n";
	}

	$data = @openssl_x509_parse(@file_get_contents(get_option('wpappninja_pem_file', '')));
	$validTo = time();
	if (is_array($data)) {
		$validTo = date('Y-m-d H:i:s', $data['validTo_time_t']);
	}
	$output .= "IOS CERT VALID " . $validTo;
	$output .= "\r\n";

	$query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(id) as ios FROM {$wpdb->prefix}wpappninja_ids WHERE registration_id LIKE %s", '_IOS_%'));
	foreach($query as $obj) {
		$output .= "iOS: " . $obj->ios;
		$output .= "\r\n";
	}

	$query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(id) as android FROM {$wpdb->prefix}wpappninja_ids WHERE registration_id NOT LIKE %s", '_IOS_%'));
	foreach($query as $obj) {
		$output .= "Android: " . $obj->android;
		$output .= "\r\n";
	}

	$query = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpappninja_push WHERE sended != %s ORDER BY send_date DESC LIMIT %d", '2', 10));
	foreach($query as $obj) {
		$output .= print_r($obj, true);
		$output .= "\r\n";
	}

	$output .= "\r\n";

	$output .= "=== NETWORK ===";
	$output .= "\r\n";
	$output .= "PAXK: " . get_option('wpappninja_packagenameInt', 'NOT SET');
	$output .= "\r\n";
	$output .= "\r\n";

    $response = wp_remote_get( 'https://api.wpmobile.app/packagenameInt.php?url=' . urlencode(home_url()) );
    if( is_array($response) ) {
        if ($response['body'] != '') {
            update_option('wpappninja_packagenameInt', $response['body']);
            $paxk = $response['body'];
        }
    }

	$output .= "PAXK: " . get_option('wpappninja_packagenameInt', 'NOT SET');
	$output .= "\r\n";
	$output .= "PAXK: " . $paxk;
	$output .= "\r\n";
	$output .= "\r\n";

    $output .= print_r($response, TRUE);
	$output .= "\r\n";
	$output .= "\r\n";

	$output .= "=== OPTIONS ===";
	$output .= "\r\n";

	// options
	if (get_option( WPAPPNINJA_SLUG , false) === false) {
		add_option( WPAPPNINJA_SLUG,
			array( 'version' => WPAPPNINJA_VERSION )
		);

		$output .= "Options: KO" . PHP_EOL;
	} else {
		$output .= "Options: OK" . PHP_EOL;
	}

	$output .= print_r(get_option( WPAPPNINJA_SLUG ), true);

	$output .= "\r\n";
	$output .= "\r\n";

	$output .= "=== DATABASE ===";
	$output .= "\r\n";
	
	// store the id for push notifications
	$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_ids (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		registration_id varchar(255) NOT NULL DEFAULT '',
		device_id varchar(255) NOT NULL DEFAULT '',
		lang varchar(5) NOT NULL DEFAULT '',
		welcome varchar(2) NOT NULL DEFAULT '',
		UNIQUE KEY id (id),
		UNIQUE KEY registration_id (registration_id(128))
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";

	// push history
	$sql2 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_push (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		gmt int(20) NOT NULL DEFAULT 0,
		http varchar(3) NOT NULL DEFAULT '',
		log text(1024) NOT NULL DEFAULT '',
		send_date int(20) NOT NULL DEFAULT 0,
		sended varchar(1) NOT NULL DEFAULT '0',
		id_post varchar(255) NOT NULL DEFAULT '0',
		titre varchar(255) NOT NULL DEFAULT '',
		message varchar(255) NOT NULL DEFAULT '',
		image varchar(255) NOT NULL DEFAULT '',
		category varchar(255) NOT NULL DEFAULT '',
		lang varchar(5) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql2 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";

	// custom category
	$sql8 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_push_perso (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id varchar(255) NOT NULL DEFAULT '',
		category varchar(255) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql8 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";

	// custom home
	$sql9 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_home_perso (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id varchar(255) NOT NULL DEFAULT '',
		category varchar(255) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql9 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";
	
	// log of stats
	$sql3 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_stats (	
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id varchar(32) NOT NULL DEFAULT '',
		action varchar(32) NOT NULL DEFAULT '',
		value varchar(32) NOT NULL DEFAULT '',
		nb mediumint(6) NOT NULL DEFAULT 1,
		date int(32) NOT NULL DEFAULT 0,
		UNIQUE KEY id (id),
		KEY user_id (user_id),
		KEY action (action)
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql3 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";
	
	// info about users for stats
	$sql4 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_stats_users (	
		id varchar(32) NOT NULL DEFAULT '',
		continent varchar(2) NOT NULL DEFAULT '',
		country varchar(32) NOT NULL DEFAULT '',
		city varchar(32) NOT NULL DEFAULT '',
		platform varchar(10) NOT NULL DEFAULT '',
		lang varchar(5) NOT NULL DEFAULT '',
		UNIQUE KEY id (id),
		KEY platform (platform),
		KEY lang (lang)
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql4 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";

	$sql5 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_installs (
		device_id varchar(255) NOT NULL DEFAULT '',
		device_type int(1) NOT NULL DEFAULT 0,
		UNIQUE KEY device_id (device_id(128))
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql5 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";

	// adserver
	$sql6 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_adserver (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		html varchar(1024) NOT NULL DEFAULT '',
		format varchar(20) NOT NULL DEFAULT 'top',
		logo varchar(255) NOT NULL DEFAULT '',
		title varchar(100) NOT NULL DEFAULT '',
		text varchar(255) NOT NULL DEFAULT 0,
		color varchar(7) NOT NULL DEFAULT '#333333',
		link varchar(255) NOT NULL DEFAULT '',
		start int(20) NOT NULL DEFAULT 0,
		stop int(20) NOT NULL DEFAULT 0,
		click int(20) NOT NULL DEFAULT 0,
		display int(20) NOT NULL DEFAULT 0,
		lang varchar(2) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql6 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";

	// qrcode
	$sql7 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_qrcode (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		link varchar(255) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	$output .= print_r(dbDelta( $sql7 ), true) . "\r\n";

	$output .= print_r( $wpdb->last_query, true );
	$output .= print_r( $wpdb->last_result, true );
	if($wpdb->last_error !== '') {
    	$output .= $wpdb->print_error();
    	$output .= print_r( $wpdb->last_error, true );
	}

	$output .= "\r\n\r\n";


	$output .= "=== PLUGINS ===";
	$output .= "\r\n";
	$output .= print_r(get_option('active_plugins'), true);

	return $output;
}
