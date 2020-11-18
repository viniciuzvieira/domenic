<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Send push notification
 *
 * array $ids Id of devices to push
 * string $title Title
 * string $content Text
 * string $image Url of image
 * int $postID Post ID (-1 for home, 0 for welcome, > 0 for post)
 * string $permalink (home if not a post)
 *
 * @return String $response Log
 * @since 1.0
 */
function wpappninja_send_push($ids, $title, $content, $image, $postID, $permalink, $custom_category, $pushID) {

	// no push for non premium account
	if (!wpappninja_is_premium()) {
		//return;
	}

	if ($postID == "0") {
		$postID = get_home_url() . "/?wpappninja_read_enhanced=-welcome";
	}

	// if speed
	if (get_wpappninja_option('speed') == '1') {

		$postID = get_home_url() . "/wpmobileapp-shortcode/?wpappninja_read_push=" . $pushID;
		if (get_wpappninja_option('redirection_type', '1') == '1' && strpos($permalink, "http") !== false) {
			$postID = wpappninja_cache_friendly($permalink);
		}

		$permalink = $postID;
	}

	if ($postID == "-1") {
		$postID = get_home_url() . "/?wpappninja_read_enhanced=-" . $pushID;
	}



	
	$query_postID = parse_url($postID, PHP_URL_QUERY);
	if ($query_postID) {
		$postID .= '&wpmobile_from_push=' . uniqid();
	} else {
    	$postID .= '?wpmobile_from_push=' . uniqid();
    }


	// url link
	//$postID = preg_replace('#/#', '__sla_sh__', preg_replace('#:#', '__dot__', $postID));

	// clear cache
	wpappninja_clear_cache();
	
	global $wpdb;
	$_wpappninja_ids = $ids;

	// remove slashes
	$title = stripslashes($title);
	$content = stripslashes($content);
	
	// test muted category
	$silent = array();
	/*if (is_array(get_wpappninja_option('silent'))) {
		$silent = get_wpappninja_option('silent');
	}*/
	$categories = get_the_category($postID);
	$catID = '';

	$response	= '';
	
	// ios notifications
	$apnsServer = 'ssl://gateway.push.apple.com:2195';
	$privateKeyPassword = get_wpappninja_option('package', '');
	$certFile = get_option('wpappninja_pem_file', '');

	$ioscontent = wpappninja_nice_cut($title . ' ' . $content, 150);
	if (get_wpappninja_option('iosjusttitle', 'off') == 'on') {
		$ioscontent = wpappninja_nice_cut($title, 150);
	}

	// connect to apple
	if (get_option('wpappninja_bypass_notif', '0') == '1') {

		$messageBody['aps'] = array('postID' => strval($postID), 'alert' => $ioscontent, 'sound' => 'default', 'badge' => 1);
		$messageBody['priority'] = "high";
		$messageBody['speed'] = get_wpappninja_option('speed', '0');
		$messageBody['silent'] = strval($catID);
		$messageBody['postID'] = strval($postID);
		$messageBody['title'] = wpappninja_nice_cut(wpappninja_get_appname(true), 30);
		//$messageBody['permalink'] = $permalink;
		
		$payload = json_encode($messageBody);

		$bypass = wp_remote_post( "https://my.wpmobile.app/bypass-push.php", array(
			'method' => 'POST',
			'timeout' => 600,
			'redirection' => 1,
			'body' => array( 'certFile' => file_get_contents($certFile), 'payload' => $payload, 'ids' => json_encode($ids) ),
		    )
		);

		if ( is_wp_error( $bypass ) ) {
			$error_message = $bypass->get_error_message();
  			$response .= "(iOS) ERROR BYPASS: $error_message";
		} else {
			$response .= "BYPASS: " . $bypass['body'];
		}

		foreach ($ids as $key => $ios_id) {
			if (substr($ios_id, 0, 5) == "_IOS_") { // ios style
				unset($ids[$key]); // remove the id
			}
		}

	} elseif ($certFile != '' && get_option('wpappninja_bypass_notif', '0') == '0') {
		$stream = stream_context_create();
		stream_context_set_option($stream,'ssl','passphrase',$privateKeyPassword);
		stream_context_set_option($stream,'ssl','local_cert',$certFile);
		$connectionType = STREAM_CLIENT_CONNECT;
		$connection = @stream_socket_client($apnsServer, $errorNumber, $errorString, 30, $connectionType, $stream);
		
		if ($connection) {
			stream_set_blocking($connection, 0);
			stream_set_write_buffer($connection, 0);
		
			$messageBody['aps'] = array('alert' => $ioscontent, 'sound' => 'default', 'badge' => 1);
			$messageBody['priority'] = "high";
			$messageBody['speed'] = get_wpappninja_option('speed', '0');
			$messageBody['silent'] = strval($catID);
			$messageBody['postID'] = strval($postID);
			$messageBody['title'] = wpappninja_nice_cut(wpappninja_get_appname(true), 30);
			//$messageBody['permalink'] = $permalink;
		
			$payload = json_encode($messageBody);

			$response .= print_r($payload, true);
		
			$nbsendios = 0;
			foreach ($ids as $key => $ios_id) {
				if (substr($ios_id, 0, 5) == "_IOS_") { // ios style

					$nbsendios++;

					unset($ids[$key]); // remove the id

					$notification = pack("C", 1) . pack("N", $key) . pack("N", time() + (86400 * 30)) . pack("n", 32) . pack('H*', substr($ios_id, 5)) . pack("n", strlen($payload)) . $payload;

					// OpenSSL Error messages: error:1409F07F:SSL routines:SSL3_WRITE_PENDING:bad write retry
					$openssl_retry = 0;
					while(!@fwrite($connection, $notification, strlen($notification))) {
						$openssl_retry++;

						if ($openssl_retry > 3) {
							break; // fail
						}

						sleep(4);
						@fwrite($connection, $notification, strlen($notification));
					}
					
					$ios_error = wpappninja_check_ios_error($connection, $_wpappninja_ids);					
					if (is_array($ios_error)) {
						fclose($connection);
						$resent = wpappninja_send_push($ios_error[0], $title, $content, $image, $postID, $permalink, $custom_category, $pushID);
						return $ios_error[1] . '<br/>' . $resent;
					}
				}
			}
			
			sleep(3);
			$ios_error = wpappninja_check_ios_error($connection, $_wpappninja_ids);
			fclose($connection);
			if (is_array($ios_error)) {
				$resent = wpappninja_send_push($ios_error[0], $title, $content, $image, $postID, $permalink, $custom_category, $pushID);
				return $ios_error[1] . '<br/>' . $resent;
			}
		
			$response .= '(IOS) OK : ' . $nbsendios . '<br/>';
		} else {
			// unset ios id
			foreach ($ids as $key => $ios_id) {
				if (substr($ios_id, 0, 5) == "_IOS_") { // ios style
					unset($ids[$key]); // remove the id
				}
			}
			$response .= '(IOS) Connection error = ' . $errorNumber . ' ' . $errorString . '<br/>';
		}
	} else {
		$response .= '(IOS) PEM Certificate is missing<br/>';
	}
	
	// Android notifications
	if(count($ids) > 0 && get_wpappninja_option('apipush', '') != '' && (get_wpappninja_option('sdk2019') != '1' || get_option('wpappninja_google_json', '') == '')) {
		$intro = wpappninja_nice_cut($content, 99);
		$msg = wpappninja_nice_cut($content, 254);
		
		if ($image == '') {
			$image = ' ';
		}
		
		/*if ($image != ' ') {
			if (wpappninja_get_http_response($image) != '200') {
				$image = wpappninja_get_image($postID);
			}
		}*/

		$url 		= 'https://android.googleapis.com/gcm/send';
		$color 		= wpappninja_get_hex_color();
		$mini_id 	= array_chunk($ids, 1000);

		for($i=0;$i<count($mini_id);$i++) {

			$message = array(
							"silent" => strval($catID), 
							"url" => $permalink,
							"color" => $color,
							"image" => $image,
							"id" => $postID,
							"title" => $title,
							"info" => $intro,
							"msg" => $msg,
							"speed" => get_wpappninja_option('speed', '0'),
							"icon" => get_wpappninja_option('customiconnotif', 'icon_notif'),
						);

			$fields = array(
							'time_to_live' => 2019200, 
							'priority' => 'high',
							'registration_ids' => $mini_id[$i],
							'data' => $message
						);

			$headers = array('Authorization' => 'key=' . get_wpappninja_option('apipush'), 'Content-Type' => 'application/json');

			$result = wp_remote_post( $url, array(
					'method' => 'POST',
					'timeout' => 45,
					'redirection' => 5,
					'headers' => $headers,
					'body' => json_encode($fields)
				)
			);

			$response .= print_r($message, true);
			
			if ( is_wp_error( $result ) ) {
				$response .= '(Android) ' . $result->get_error_message();
				$ko = true;
			} else {
				$ko = false;
				if (!$resul_a = json_decode($result['body'], TRUE)) {
					$response .= '(Android) ' . strip_tags($result['body']) . '<br/>';
					$ko = true;
				} else {
					if ((string)$resul_a['failure'] != '0') {
						$response .= '(Android) <b>'.(string)$resul_a['failure'].' Bad Token</b><br/>';
					}
					$response .= '(Android) OK : ' . (string)$resul_a['success'] . '<br/>';
				}
			}
				
			// remove errors id
			if (!$ko) {
				$increment = 0;
				foreach($resul_a['results'] as $wrong) {
					if (isset($wrong['error'])) {
						if ($wrong['error'] == 'NotRegistered' || $wrong['error'] == 'InvalidRegistration' || $wrong['error'] == 'MismatchSenderId' || $wrong['error'] == 'InvalidParameters') {
							//$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_ids WHERE registration_id = %s", $mini_id[$i][$increment]));
						}
					}
					$increment++;
				}
			}
		}
	} elseif(count($ids) > 0 && get_wpappninja_option('apipush', '') != '' && get_wpappninja_option('sdk2019') == '1') {

		$intro = wpappninja_nice_cut($content, 99);
		$msg = wpappninja_nice_cut($content, 254);
		
		$url    = 'https://fcm.googleapis.com/fcm/send';

		$mini_id 	= array_chunk($ids, 1000);

		foreach($mini_id as $id) {

			$message = array(
							"title" => $title,
							"body" => $msg,
						);


			if (get_option('wpmobile_enable_new_fcm')) {

				$fields = array(
							'priority' => 'high',
							'registration_ids' => $id,
							'data' =>  array(
											"title" => $title,
											"body" => $msg,
											"link" => $postID,
										),
							//'notification' => $message
						);
			} else {
				$fields = array(
							'priority' => 'high',
							'registration_ids' => $id,
							'notification' => $message
						);
			}

			$headers = array('Authorization' => 'key=' . get_wpappninja_option('apipush'), 'Content-Type' => 'application/json');

			$result = wp_remote_post( $url, array(
					'method' => 'POST',
					'timeout' => 45,
					'redirection' => 5,
					'headers' => $headers,
					'body' => json_encode($fields)
				)
			);

			$response .= print_r($message, true);
			
			if ( is_wp_error( $result ) ) {
				$response .= '(Android) ' . $result->get_error_message();
				$ko = true;
			} else {
				$ko = false;
				if (!$resul_a = json_decode($result['body'], TRUE)) {
					$response .= '(Android) ' . strip_tags($result['body']) . '<br/>';
					$ko = true;
				} else {
					if ((string)$resul_a['failure'] != '0') {
						$response .= '(Android) <b>'.(string)$resul_a['failure'].' Bad Token</b><br/>';
					}
					$response .= '(Android) OK : ' . (string)$resul_a['success'] . '<br/>';
				}
			}
				
			// remove errors id
			if (!$ko) {
				$increment = 0;
				foreach($resul_a['results'] as $wrong) {
					if (isset($wrong['error'])) {
						if ($wrong['error'] == 'NotRegistered' || $wrong['error'] == 'InvalidRegistration' || $wrong['error'] == 'MismatchSenderId' || $wrong['error'] == 'InvalidParameters') {
							//$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_ids WHERE registration_id = %s", $mini_id[$i][$increment]));
						}
					}
					$increment++;
				}
			}
		}
	} else {
		if (get_wpappninja_option('apipush', '') == '') {
			$response .= '(Android) No API KEY';
		} else {
			$response .= '-END-';
		}
	}
	
	return $response;
}

/**
 * Check if the socket return an error.
 * If yes, delete the id and restart the loop.
 *
 * @since 3.8.5
 */
function wpappninja_check_ios_error($fp, $ids) {
	$apple_error_response = fread($fp, 6);
	if ($apple_error_response) {
		$error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);

        if ($error_response['status_code'] == '0') {
            $error_response['status_code'] = 'No errors encountered';
        } else if ($error_response['status_code'] == '1') {
            $error_response['status_code'] = 'Processing error';
        } else if ($error_response['status_code'] == '2') {
            $error_response['status_code'] = 'Missing device token';
        } else if ($error_response['status_code'] == '3') {
            $error_response['status_code'] = 'Missing topic';
        } else if ($error_response['status_code'] == '4') {
            $error_response['status_code'] = 'Missing payload';
        } else if ($error_response['status_code'] == '5') {
            $error_response['status_code'] = 'Invalid token size';
        } else if ($error_response['status_code'] == '6') {
            $error_response['status_code'] = 'Invalid topic size';
        } else if ($error_response['status_code'] == '7') {
            $error_response['status_code'] = 'Invalid payload size';
        } else if ($error_response['status_code'] == '8') {
            $error_response['status_code'] = 'Invalid token';
        } else if ($error_response['status_code'] == '255') {
            $error_response['status_code'] = 'None (unknown)';
        } else {
            $error_response['status_code'] = $error_response['status_code'] . '-Not listed';
        }
		
        foreach ($ids as $key => $id) {
			if (substr($id, 0, 5) == "_IOS_") {
				unset($ids[$key]);
				if ($key == $error_response['identifier']) {
					//global $wpdb;
					//$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_ids WHERE registration_id = %s", $id));
					return array(0 => $ids, 1 => '(IOS) <b>'.$error_response['status_code'].'...</b>');
				}
			}
		}
	}
	return null;
}

/**
 * Send notification when a post is published
 *
 * @since 1.0
 * @deprecated 3.0.4
 */
//add_action('transition_post_status', 'wpappninja_send_on_publish', 10, 3 );
function wpappninja_send_on_publish($new_status, $old_status, $post) {

	// no push for non premium account
	if (!wpappninja_is_premium()) {
		return;
	}

	if ( 'publish' !== $new_status or 'publish' === $old_status )
		return;

	wpappninja_clear_cache();

	if ( 'post' !== $post->post_type )
		return;

	if (get_wpappninja_option('autopush', '1') != '1')
		return;
	
	global $wpdb;
	$id_post = $post->ID;
	
	$list_auto_push = get_wpappninja_option('list_auto_push', array());
	if (in_array($id_post, $list_auto_push)) {
		return;
	}
	
	$list_auto_push[] = $id_post;
	
	$options = get_option( WPAPPNINJA_SLUG );
	$options['list_auto_push'] = $list_auto_push;
	update_option( WPAPPNINJA_SLUG, $options );

	$title = $post->post_title;				
	$content = mb_substr(wp_strip_all_tags($post->post_content), 0, 255);
	$image = wpappninja_get_image($id_post);

	$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_push (`id_post`, `titre`, `message`, `image`, `send_date`) VALUES (%s, %s, %s, %s, %s)", $id_post, $title, $content, $image, current_time('timestamp')));
}
 
/**
 * Register and unregister for push
 *
 * @since 1.0
 */
function wpappninja_push_register() {

	//print_r($_POST);

	// ugly hack en attendant mieux (tgv direction paris :)
	global $wpdb;

	if (isset($_GET['wpmobile_sdk2019_id']) && isset($_GET['wpmobile_sdk2019_token'])) {

		$_POST['u'] = $_GET['wpmobile_sdk2019_id'];
		$_POST['regId'] = $_GET['wpmobile_sdk2019_token'];


		$user_id = $_POST['u'];
		$user_bdd_id = $wpdb->get_row($wpdb->prepare("SELECT `id` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `user_id` = %s", $user_id));

		if (!isset($user_bdd_id->id)) {
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push_perso (`user_id`) VALUES (%s)", $user_id));

			$lastid = $wpdb->insert_id;
		} else {
			$lastid = $user_bdd_id->id;
		}

		//if (canGetWPMobileCookie()) {
			setcookie( "HTTP_X_WPAPPNINJA_ID", $lastid, time() + 8640000, COOKIEPATH, COOKIE_DOMAIN );
		//}
	}

	// no push while testing
	if (!get_option('wpappninja_app_published')) {
		return;
	}
	
	if (!isset($_POST['regId']) || !isset($_POST['u'])) {
		return;
	}
	

	$lang = wpappninja_get_lang();

	$id = sanitize_text_field($_POST['regId']);
	$device = sanitize_text_field($_POST['u']);

	if (substr($id, 0, 5) == "_IOS_" && isset($_SERVER['HTTP_X_WPAPPNINJA_ID'])) {
		$device = $_SERVER['HTTP_X_WPAPPNINJA_ID'];
	}

	// log installation
	$device_sha = sha1($device);

	echo "DEVICE SHA1: " . $device_sha;

	$install = $wpdb->get_results($wpdb->prepare("SELECT `device_id` FROM {$wpdb->prefix}wpappninja_installs WHERE `device_id` = %s", $device_sha));

	if (count($install) == 0) {
		wpappninja_stats_log('install', 1);
			
		$device_type = 0; // android
		if (substr($id, 0, 5) == "_IOS_") {
			$device_type = 1;
		}

		$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_installs (`device_id`, `device_type`) VALUES (%s, %d)", $device_sha, $device_type));
	}

	if (get_wpappninja_option('project', '352104594960') == '352104594960' && substr($id, 0, 5) != "_IOS_") {
		//return;
	}
	
	if ($id != '' AND $device != '') {		
		$registered = $wpdb->get_results($wpdb->prepare("SELECT `device_id` FROM {$wpdb->prefix}wpappninja_ids WHERE `device_id` = %s", $device));

		if (isset($registered[0]) && $registered[0]->device_id != "") {
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_ids SET `registration_id` = %s, `lang` = %s WHERE `device_id` = %s", $id, $lang, $device));

			echo "UPDATE";
		} else {
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_ids (`registration_id`, `device_id`, `lang`) VALUES (%s, %s, %s)", $id, $device, $lang));

			echo "INSERT";
		}
		
		if (get_wpappninja_option('send_welcome_push') === "1") {
			$welcome = $wpdb->get_results($wpdb->prepare("SELECT `welcome` FROM {$wpdb->prefix}wpappninja_ids WHERE `device_id` = %s", $device));
		
			foreach ( $welcome as $welc ) {
			
				if ($welc->welcome == '') {
					$titre = get_wpappninja_option('welcome_titre_speed');
					$welcome = get_wpappninja_option('welcome_speed');
		
					if ($welcome != '' AND $titre != '') {
						wpappninja_send_push(array($id), $titre, $welcome, ' ', 0, wpappninja_get_home(), '', 'welcome');
						$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_ids SET `welcome` = %s WHERE `device_id` = %s", 'ok', $device));
					}
				}
			}
		}
	}
}

function wpappninja_push_unregister() {
	
	return;


	if (!isset($_POST['u'])) {
		return;
	}
	
	global $wpdb;	
	$device = sanitize_text_field($_POST['u']);
	$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_ids WHERE `device_id` = %s", $device));
	$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_push_perso WHERE `user_id` = %s", $device));
}

/**
 * Function to send custom push.
 *
 * @since 4.1.2
 */
function wpmobileapp_push($title, $message, $image, $link, $lang_2letters = 'all', $send_timestamp = '', $user_email = '') {

	global $wpdb;

	$title = strip_tags($title);
    $title = preg_replace('/(<(script|style)\b[^>]*>).*?(<\/\2>)/is', "$1$3", $title);

	$lang_array = wpappninja_available_lang();

	// 0 for homepage or an url
	$post_id = $link;
	if (!preg_match('#^http#', $link)) {
		$post_id = "0";
	}

	// message without html
	$content = strip_tags($message);
    $content = preg_replace('/(<(script|style)\b[^>]*>).*?(<\/\2>)/is', "$1$3", $content);
	$content = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $content));
	$content = trim(preg_replace('/\s+/', ' ', $content));

	// image need to be an url or ' '
	if (!preg_match('#^http#', $image)) {
		$image = ' ';
	}

	// send within 10 minutes after this timestamp
	$timestamp = $send_timestamp;
	if ($timestamp == "") {
		$timestamp = current_time('timestamp');
	}

	// user email or category
	$category = $user_email;

	$lang = $lang_2letters;

	/*if ($lang == 'all') {

		foreach($lang_array as $name => $code) {
			
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push (`id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `category`) VALUES (%s, %s, %s, %s, %s, %s, %s)", $post_id, $title, $content, $image, $timestamp, $code, $category));
				$ids[] = $wpdb->insert_id;
		}
	*///} else {
		
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push (`id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `category`) VALUES (%s, %s, %s, %s, %s, %s, %s)", $post_id, $title, $content, $image, $timestamp, $lang, $category));
	//}

}

function wpappninja_send_custom_push() {
	
}

/**
 * Add a notification when a note is added on woocommerce.
 *
 */
add_action('woocommerce_new_customer_note', 'wpmobileapp_woo_note', 10, 1 );
function wpmobileapp_woo_note($array) {

    if (get_wpappninja_option('wpmobile_auto_wc') == '1') {

        $order_id = $array['order_id'];
        $title = $array['customer_note'];

        $order = new WC_Order( $order_id );
        $user_email = $order->get_billing_email();

        if ($user_email != "" && $title != "") {
            $message = '#' . $order_id . ' ' . __( 'Order updates', 'woocommerce' );
            $image = ' ';
            $link = $order->get_view_order_url();

            wpmobileapp_push($title, $message, $image, $link, $lang_2letters = 'all', $send_timestamp = '', $user_email);
        }
    }
}

// auto push when a mail is sended
add_filter( 'wp_mail', 'wpmobileapp_send_push_mail', 1 );
function wpmobileapp_send_push_mail( $args ) {

	if (get_wpappninja_option('wpmobile_auto_mail') == '1') {

		if (is_email($args['to']) && $args['subject'] != "" && $args['message'] != "") {
			wpmobileapp_push($args['subject'], strip_tags($args['message']), "", "", 'all', '', $args['to']);
		}
	}
	
	return $args;
}

add_action( 'new_to_publish', 'wpmobileapp_send_push_post', 10, 1 );
add_action( 'draft_to_publish', 'wpmobileapp_send_push_post', 10, 1 );
add_action( 'auto-draft_to_publish', 'wpmobileapp_send_push_post', 10, 1 );
add_action( 'private_to_publish', 'wpmobileapp_send_push_post', 10, 1 );
add_action( 'trash_to_publish', 'wpmobileapp_send_push_post', 10, 1 );
add_action( 'pending_to_publish', 'wpmobileapp_send_push_post', 10, 1 );
add_action( 'future_to_publish', 'wpmobileapp_send_push_post', 10, 1 );

function wpmobileapp_send_push_post($post) {

	if (get_wpappninja_option('wpmobile_auto_post') == '1' && !get_transient("wpmobile_push_slow_down")) {

		set_transient( 'wpmobile_push_slow_down', true, 30 );

		$posttype = get_post_type($post);

		if (in_array($posttype, array('post'))) {

			$ID = $post->ID;
		    $title = $post->post_title;
	    	$permalink = get_permalink( $ID );
	    	$image = wpappninja_get_image($ID);
	    	$content = get_the_excerpt($ID);

			if ($title != "" && $permalink != "") {

				// check if already sended
				$already_sent = get_option('wpmobile_auto_push_sent', array());

				if (!in_array($ID, $already_sent)) {

					$already_sent[] = $ID;
					update_option('wpmobile_auto_push_sent', $already_sent);

					wpmobileapp_push($title, $content, $image, $permalink, 'all', '', '');
				}
			}
		}
	}
}

add_action( 'publish_post', 'wpmobileapp_send_push_post_update', PHP_INT_MAX, 2 );
function wpmobileapp_send_push_post_update($ID, $post) {

	if (get_wpappninja_option('wpmobile_auto_post_update') == '1' && !get_transient("wpmobile_push_slow_down")) {

		set_transient( 'wpmobile_push_slow_down', true, 30 );

	    $title = $post->post_title;
	    $permalink = get_permalink( $ID );
	    $image = wpappninja_get_image($ID);
	    $content = get_the_excerpt($ID);


		$posttype = get_post_type($post);

		if (in_array($posttype, array('post'))) {

			if ($title != "" && $permalink != "") {

				$already_sent = get_option('wpmobile_auto_push_sent', array());

				//if (!in_array($ID, $already_sent)) {

					$already_sent[] = $ID;
					update_option('wpmobile_auto_push_sent', $already_sent);

					wpmobileapp_push($title, $content, $image, $permalink, 'all', '', '');
				//}
			}
		}
	}

}

add_action('woocommerce_order_status_changed', 'wpmobileapp_send_push_wc', 10, 3);
function wpmobileapp_send_push_wc($order_id,$old_status,$new_status) {

    $wc_translation = array(
    	'pending'    => _x( 'Pending payment', 'Order status', 'wpappninja' ),
    	'processing' => _x( 'Processing', 'Order status', 'wpappninja' ),
    	'on-hold'    => _x( 'On hold', 'Order status', 'wpappninja' ),
    	'completed'  => _x( 'Completed', 'Order status', 'wpappninja' ),
    	'cancelled'  => _x( 'Cancelled', 'Order status', 'wpappninja' ),
    	'refunded'   => _x( 'Refunded', 'Order status', 'wpappninja' ),
    	'failed'     => _x( 'Failed', 'Order status', 'wpappninja' ),
    );

    if ($wc_translation[$old_status] != "") {
    	$old_status = $wc_translation[$old_status];
    }

    if ($wc_translation[$new_status] != "") {
    	$new_status = $wc_translation[$new_status];
    }

	if (get_wpappninja_option('wpmobile_auto_wc') == '1') {

		$title = sprintf( __( 'Order status changed from %s to %s', 'wpappninja' ), $old_status, $new_status );

		$order = new WC_Order( $order_id );
		$user_email = $order->get_billing_email();

		if ($user_email != "" && $title != "") {
			$message = '#' . $order_id . ' ' . __( 'Order updates', 'wpappninja' );
			$image = ' ';
			$link = $order->get_view_order_url();

			wpmobileapp_push($title, $message, $image, $link, 'all', '', $user_email);
		}
	}
}

add_action('bp_notification_after_save', 'wpmobileapp_send_push_bp');
function wpmobileapp_send_push_bp( BP_Notifications_Notification $n ) {

	if (get_wpappninja_option('wpmobile_auto_bp') == '1') {

		$user_id = $n->user_id;
		$user_info = get_userdata($user_id);
		$user_email = $user_info->user_email;

		$bp           = buddypress();
		$notification = $n;

		if ( isset( $bp->{ $notification->component_name }->notification_callback ) && is_callable( $bp->{ $notification->component_name }->notification_callback ) ) {
			
			$content = call_user_func( $bp->{ $notification->component_name }->notification_callback, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'array', $notification->id );
		}

		$title = $content['text'];

		if ($user_email != "" && $title != "") {
			$message = "";
			$image = ' ';
			$link = $content['link'];

			wpmobileapp_push($title, $message, $image, $link, 'all', '', $user_email);
		}
	}
}


add_action('gform_notification', 'wpmobileapp_send_push_gravity', 10, 3);
function wpmobileapp_send_push_gravity( $notification, $form, $entry ) {

    if (get_wpappninja_option('wpmobile_auto_gravity') == '1') {

        $user_email = $notification['to'];
        $title = $content['subject'];

        if ($user_email != "" && $title != "") {
            $message = "";
            $image = " ";
            $link = "";

            wpmobileapp_push($title, $message, $image, $link, 'all', '', $user_email);
        }
    }
    
    return $notification;
}


add_action('peepso_notifications_data_before_add', 'wpmobileapp_send_push_peepso');
function wpmobileapp_send_push_peepso( $array ) {

    if (get_wpappninja_option('wpmobile_auto_peepso') == '1') {

        
        $user_id = $array['not_user_id'];
        $user_info = get_userdata($user_id);
        $user_email = $user_info->user_email;
        
        $from_id = $array['not_from_user_id'];
        $from_user_info = get_userdata($from_id);
        $from_login = $from_user_info->user_login;
        
        $title = $from_login . ' ' . $array['not_message'];

        if ($user_email != "" && $title != "") {
            $message = "";
            $image = " ";
            $link = get_home_url() . "/profile/";

            wpmobileapp_push($title, $message, $image, $link, 'all', '', $user_email);
        }
    }
    
    return $array;
}
