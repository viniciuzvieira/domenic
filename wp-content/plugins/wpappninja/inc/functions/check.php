<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Check if the app is paid.
 *
 * @since 4.0.5
 */
function wpappninja_is_paid() {

	if (!wpappninja_is_store_ready()) {
		return false;
	}

	// is paid
	if (!get_wpappninja_option('ispaid')) {
		$ispaid = false;
		if (!get_transient('wpappninjaispaid')) {
			set_transient('wpappninjaispaid', '1', 60);
			$response = wp_remote_get( 'https://api.wpmobile.app/check.php?url=' . urlencode(home_url()) );
			if( is_array($response) ) {
				if ($response['body'] == '1') {
					$options = get_option(WPAPPNINJA_SLUG);
					$options['ispaid'] = "1";
					update_option('wpappninja_app_published', true);
					update_option(WPAPPNINJA_SLUG, $options);
					$ispaid = true;
					wpappninja_get_package(true);
				}
			}
		}
	} else {
		$ispaid = true;
		wpappninja_get_package();
	}
	
	return $ispaid;
}

/**
 * Check if the app need to be updated.
 *
 * @since 4.0.5
 */
function wpappninja_need_update() {
	if (!wpappninja_is_paid()) {
		return false;
	}
	
	return get_option('wpappninja_need_update', false);
}

/**
 * Dismiss the requiered update.
 *
 * @since 4.0.5
 */
function wpappninja_dismiss_update() {
	update_option('wpappninja_need_update', false);

	if (isset($_GET['pack'])) {
		update_option('wpappninja_nb_downloads', round($_GET['pack']));

		$response = wp_remote_get( 'https://api.wpmobile.app/check.php?url=' . urlencode(home_url()) );
		if( is_array($response) ) {
			if ($response['body'] == '1') {
				$options = get_option(WPAPPNINJA_SLUG);
				$options['ispaid'] = "1";
				update_option('wpappninja_app_published', true);
				update_option(WPAPPNINJA_SLUG, $options);
				$ispaid = true;
			}
		}
	}
}

/**
 * Check if the app is store ready.
 *
 * @since 4.0.5
 */
function wpappninja_is_store_ready() {
	$app_data = get_wpappninja_option('app');
	$category = array('Books','Business','Catalogs','Education','Entertainment','Finance','Food &amp; Drink','Games','Health &amp; Fitness','Lifestyle','Magazines &amp; Newspapers','Medical','Music','Navigation','News','Photo &amp; Video','Productivity','Reference','Shopping','Social Networking','Sports','Travel','Utilities','Weather');
	$loc = array('fr-FR', 'en-US', 'de-DE', 'es-ES', 'it-IT', 'pt-PT');
	$current_user = wp_get_current_user();
	
	$app_user_name = isset($app_data['user']['name']) ? $app_data['user']['name'] : $current_user->user_firstname.' '.$current_user->user_lastname;
	$app_user_mail = isset($app_data['user']['mail']) ? $app_data['user']['mail'] : $current_user->user_email;
	$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();
	$app_url_home = isset($app_data['url']['home']) ? esc_url($app_data['url']['home']) : home_url() . '/';
	$app_store_locale = isset($app_data['store']['locale']) ? $app_data['store']['locale'] : "";
	$app_store_category = isset($app_data['store']['category']) ? $app_data['store']['category'] : "";
	$app_logo = isset($app_data['logo']) ? esc_url($app_data['logo']) : "";
	$app_store_intro = isset($app_data['store']['intro']) ? stripslashes($app_data['store']['intro']) : "";
	$app_store_text = isset($app_data['store']['text']) ? stripslashes($app_data['store']['text']) : "";
	$app_store_keywords = isset($app_data['store']['keywords']) ? $app_data['store']['keywords'] : "";
	$splashscreen = isset($app_data['splashscreen']) ? esc_url($app_data['splashscreen']) : "";
	
	if ($app_name != "" && $app_url_home != "" && $app_logo != "") {

		if (!get_option('wpappninja_store_ready')) {
			update_option('wpappninja_store_ready', true);
		}

		return true;
	}
	
	return false;
}

/**
 * Add an icon based on check.
 *
 * @since 4.0.5
 */
function wpappninja_check_icon() {

	return '';
	
	if ( isset( $_GET['page'] ) && 'wpappninja_publish' === $_GET['page'] ) {
		return '';
	}
	
	if (wpappninja_need_update() || (!wpappninja_is_paid() && wpappninja_is_store_ready())) {
		return ' 🔥';
	}
	
	return '';
}

/**
 * Get the number of installations.
 *
 * @since 4.1.1
 */
function wpappninja_get_install($onlyIOS = false) {
	global $wpdb;

	if ($onlyIOS) {
		$sub = $wpdb->get_results("SELECT COUNT(device_id) as sub FROM {$wpdb->prefix}wpappninja_installs WHERE device_type = '1'");
	} else {
		$sub = $wpdb->get_results("SELECT COUNT(device_id) as sub FROM {$wpdb->prefix}wpappninja_installs");
	}

	return $sub[0]->sub;
}

/**
 * Get the current plan.
 *
 * @since 4.1.7
 */
function wpappninja_get_allowed_install() {

	if (get_option('wpappninja_nb_downloads')) {
		return get_option('wpappninja_nb_downloads');
	}

	$response = wp_remote_get( 'https://api.wpmobile.app/check_allowed.php?url=' . urlencode(home_url()) );
	if( is_array($response) ) {
		if ($response['body'] != '') {
			update_option('wpappninja_nb_downloads', $response['body']);
			return $response['body'];
		}
	}

	return '0';
}

/**
 * Add a install.
 *
 * @since 4.1.5
 */
function wpappninja_add_install() {

	// no new install while testing
	if (!get_option('wpappninja_app_published')) {
		return;
	}

	global $wpdb;

	$device = sanitize_text_field($_POST['id']);

	// log installation
	$device_sha = sha1($device);
	$install = $wpdb->get_results($wpdb->prepare("SELECT `device_id` FROM {$wpdb->prefix}wpappninja_installs WHERE `device_id` = %s", $device_sha));
	if (count($install) == 0) {
		wpappninja_stats_log('install', 1);
			
		$device_type = 1;
		if (!wpappninja_is_ios()) {
			$device_type = 0;
		}

		$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_installs (`device_id`, `device_type`) VALUES (%s, %d)", $device_sha, $device_type));
	}
}

/**
 * Mark the app as published.
 *
 * @since 4.1.8
 */
function wpappninja_published() {
	update_option('wpappninja_app_published', true);

	if (isset($_GET['isios'])) {
		update_option('wpappninja_old_ios_deeplinking', false);
	}
	
	wpappninja_get_package(true);
}

/**
 * Check the settings.
 *
 * @since 4.2.0
 */
function wpappninja_is_ready_to_start() {

	return true;

	$ready = true;
	$available_lang = get_wpappninja_option('lang_exclude', array());
	if (
    	(count(wpappninja_get_menu_reloaded('fr')) == 0 && in_array('fr', $available_lang)) ||
    	(count(wpappninja_get_menu_reloaded('de')) == 0 && in_array('de', $available_lang)) ||
    	(count(wpappninja_get_menu_reloaded('en')) == 0 && in_array('en', $available_lang)) ||
    	(count(wpappninja_get_menu_reloaded('it')) == 0 && in_array('it', $available_lang)) ||
    	(count(wpappninja_get_menu_reloaded('pt')) == 0 && in_array('pt', $available_lang)) ||
    	(count(wpappninja_get_menu_reloaded('es')) == 0 && in_array('es', $available_lang))
   	   ) {$ready = false;}

	/*if (
	    (get_wpappninja_option('mentions_fr', '') == '' && in_array('fr', $available_lang)) || 
	    (get_wpappninja_option('mentions_en', '') == '' && in_array('en', $available_lang)) || 
	    (get_wpappninja_option('mentions_de', '') == '' && in_array('de', $available_lang)) || 
	    (get_wpappninja_option('mentions_es', '') == '' && in_array('es', $available_lang)) || 
	    (get_wpappninja_option('mentions_it', '') == '' && in_array('it', $available_lang)) || 
	    (get_wpappninja_option('mentions_pt', '') == '' && in_array('pt', $available_lang))
	   ) {$ready = false;}*/

	return $ready;
}

/**
 * Alert need an higher pack.
 *
 * @since 4.3.3
 */
function wpappninja_alert_pack() {

	if (wpappninja_is_paid() && get_option('wpappninja_app_published') && wpappninja_get_install() > wpappninja_get_allowed_install()) {

		$class = 'notice notice-error';
		if (preg_match('#fr#', get_locale())) {
			$url = 'https://wpmobile.app/prix/?source=' . home_url('/');
		} else {
			$url = 'https://wpmobile.app/en/price/?source=' . home_url('/');
		}
		$message = '<b>WPMobile.App - ' . __('Downloads:', 'wpappninja') . ' ' . wpappninja_get_install() . ' ' . __('Allowed on your plan:', 'wpappninja') . ' ' . wpappninja_get_allowed_install() . '</b> <a target="_blank" href="' . $url . '">' . strtolower(__('UPDATE MY PLAN', 'wpappninja')) . '</a>';

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
}
add_action( 'admin_notices', 'wpappninja_alert_pack' );

/**
 * Cancel the payment.
 */
function wpappninja_cancel_payment() {
	$options = get_option(WPAPPNINJA_SLUG);
	$options['ispaid'] = "0";
	$options['package'] = "";
	$options['appstore_package'] = "";
	update_option(WPAPPNINJA_SLUG, $options);
}

/**
 * Check if we are in the app.
 *
 * @since 7.0.8
 */
function is_wpappninja() {

	if (isset($_SERVER['HTTP_X_WPAPPNINJA']) || defined( 'WPAPPNINJA_READ_ENHANCED' ) || isset($_GET['wpappninja_simul4']) || isset($_COOKIE['HTTP_X_WPAPPNINJA']) || isset($_GET['wpmobile_homepage'])) {

		return true;
	}

	return false;

}

function is_wpmobile_pwa_ready() {

	$pwa_data = get_option('wpappninja_progressive_app');
  
	if (isset($pwa_data['version']) && isset($pwa_data['name']) && isset($pwa_data['logo'])) {
		return true;
	}

	return false;
}

function is_wpmobileapp_ready() {

	if (isset($_GET['fakepwa'])) {
		return true;
	}

	if (is_admin()) {
		return false;
	}

	$isready = get_wpappninja_option('appify', '0');

	if (isset($_SERVER['HTTP_X_BETA_WPMOBILE'])) {
		$isready = "1";
	}

	if (is_wpappninja() && $isready == '1') {

		return true;
	}

	return false;

}