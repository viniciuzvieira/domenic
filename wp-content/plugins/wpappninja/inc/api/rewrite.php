<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Rewrite rules
 *
 * @since 1.0
 */
add_action('wp', 'wpappninja_api_rewrite');
function wpappninja_api_rewrite() {

	$get_pagename = isset($_GET['pagename']) ? sanitize_text_field($_GET['pagename']) : "";
	$get_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : "";
	$ios_specific = wpappninja_is_ios() ? "ios" : "";
	$apple_review = wpappninja_is_apple_reviewer() ? "review" : "";


	if ( $get_pagename == 'wpappninja' || 
		 preg_match( '#/android_json/(.*)$#', $_SERVER['REQUEST_URI'], $match ) ||
    	 preg_match( '#/wpappninja/1.0/(.*)$#', $_SERVER['REQUEST_URI'], $match )) {
		
		$type = '';
		if ($get_pagename == 'wpappninja' && $get_type != '') {
			$type = $get_type;
		} else if (is_array($match)) {
			$type = $match[1];
		}
		
		// debug
		if (isset($_GET['debug'])) {
			define('WPAPPNINJA_API_DEBUG', true);
		}
		
		// stats
		if (get_wpappninja_option('speed') != '1') {
			wpappninja_stats_log($type, 1);
		}
			
		$request 			= explode( '/', $type );

		if ($request[0] == 'read' AND ($request[1] == 'http:' OR $request[1] == 'https:')) {
			$request 			= explode( '/', $type, 2);
		}

		$cacheMe			= false;
		$transient_name 	= 'wpappninja_' . $type . wpappninja_get_lang() . $ios_specific . $apple_review;
		$output 			= get_transient($transient_name);
	
		if (!$output || defined('WPAPPNINJA_API_DEBUG')) {
			if (!isset($request[1])) {$request[1] = '';}
			if (!isset($request[2])) {$request[2] = '';}
	
			switch ( $request[0] ) {
				case 'version':
					$output = json_encode(array('version_app' => get_wpappninja_option('version_app', "1")));
					break;

				case 'cronjob':
					$output = wpappninja_cron();
					break;

				case 'category':
					$cacheMe = true;
					$output = wpappninja_category();
					break;
			
				case 'bycat':
					$cacheMe = true;
					if ($request[1] == '') {$request[1] = 0;}
					$output = wpappninja_recent($request[2], $request[1]);
					break;
				
				case 'recent':
					if ($request[2] != 'notif') {
						$cacheMe = true;
					}
					if ($request[2] == '') {$request[2] = 0;}
					$output = wpappninja_recent($request[1], $request[2]);
					break;
				
				case 'custom':
					if ($request[2] == '') {$request[2] = 0;}
					$output = wpappninja_recent($request[2], 0, false, $request[1]);
					break;
				
				case 'favoris':
					if ($request[2] == '') {$request[2] = 0;}
					$output = wpappninja_favoris($request[2], $request[1]);
					break;
				
				case 'similaires':
					if ($request[1] == '') {$request[1] = 0;}
					$output = wpappninja_recent($request[2], $request[1], false, '', true);
					break;
				
				case 'search':
					if ($request[2] == '') {$request[2] = 0;}
					$output = wpappninja_recent($request[2], 0, $request[1]);
					break;
				
				case 'comment':
					$cacheMe = true;
					if ($request[2] == '') {$request[2] = 0;}
					$output = wpappninja_comment($request[1], $request[2]);
					break;
				
				case 'read':
					$cacheMe = true;
					$output = wpappninja_read($request[1]);
					break;
				
				case 'redirection':
					$output = wpappninja_redirection($request[1]);
					break;
				
				case 'form':
					$output = wpappninja_form($request[1]);
					break;
		
				case 'register':
					$output = wpappninja_push_register();
					break;
				
				case 'unregister':
					$output = wpappninja_push_unregister();
					break;

				case 'googlejson':
					$output = @file_get_contents(get_option('wpappninja_google_json', ''));
					break;

				case 'addcss':
					

					$amauricss = $_GET['amauricss'];

					if (preg_match('#^[\.\,a-zA-Z0-9\{\}\#\_\-\:\;\(\)\ \!]+$#', $amauricss)) {

						$options = get_option( WPAPPNINJA_SLUG );
						$options['customcss'] .= $amauricss;
						update_option( WPAPPNINJA_SLUG, $options );
					}

					break;
					
				case 'store':
					$app_data = get_wpappninja_option('app');
					$app_data['installs'] = wpappninja_get_install();
					$app_data['version'] = WPAPPNINJA_VERSION;
					$app_data['sdk2019'] = get_wpappninja_option('sdk2019', '0');

					$app_data['googlejson'] = get_option('wpappninja_google_json', '');

					if (!isset($app_data['logo']) OR $app_data['logo'] == "") {$app_data['logo'] = "https://wpmobile.app/FFFFFF-0.png";}

					if (!isset($app_data['splashscreen']) OR $app_data['splashscreen'] == "" OR preg_match('#/wpappninja/assets/images/os/empty\.png$#', $app_data['splashscreen'])) {$app_data['splashscreen'] = "https://my.wpmobile.app/_launchscreen.php?c=" . str_replace('#', '', $app_data['theme']['primary']) . "&l=" . $app_data['logo'];}

					if (!isset($app_data['name']) OR $app_data['name'] == "") {$app_data['name'] = $_SERVER['SERVER_NAME'];}

					$output = json_encode(stripslashes_deep($app_data));
					break;
					
				case 'updated':
					wpappninja_dismiss_update();
					break;

				case 'getinstall':
					$output = wpappninja_get_install();
					break;

				case 'register_install':
					$output = wpappninja_add_install();
					break;

				case 'published':
					update_option('wpmobile_enable_new_fcm', true);
					$output = wpappninja_published();
					break;

				case 'cancel':
					$output = wpappninja_cancel_payment();
					break;

				case 'adserver':
					$output = wpappninja_adserver_click($request[1]);
					break;

				case 'healme':
					$output = wpappninja_heal_me();
					break;

				case 'pwa':
			        $pwa = sanitize_text_field($_GET['pwa']);
        			update_option('wpappninja_pwa_home', $pwa);
					break;

				case 'apple_403':
					if ($_GET['v'] == 'on') {
						update_option('wpappninja_off_apple', '1');
					} else {
						update_option('wpappninja_off_apple', '0');
					}
					break;
			}
			
			if ($cacheMe && !defined('WPAPPNINJA_API_DEBUG') && 1<0) {
				set_transient( $transient_name, $output, 60*60 );
			}
		}
		
		wpappninja_headers_cache();
		header('HTTP/1.1 200 OK');
		header("X-WPAPPNINJA-VERSION: " . get_wpappninja_option('version_app', '1'));
		
		if (defined('WPAPPNINJA_API_DEBUG')) {
			header('Content-Type: text/html; charset=utf-8');
		} else {
			header('Content-Type: application/json; charset=utf-8');
			echo $output;
		}
		
		exit(0);
	}
}