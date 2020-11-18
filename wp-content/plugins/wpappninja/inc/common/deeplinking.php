<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Test if we need deeplinking.
 *
 * @since 5.1.1
 */
function wpappninja_deeplink_ready() {

	if (isset($_GET['wpappninja_simul4'])) {
		return false;
	}

	if (isset($_SERVER['HTTP_X_WPAPPNINJA_BYPASS'])) {
		return false;
	}

	if (get_wpappninja_option('speed', '0') == '1') {
		return false;
	}

	if (get_wpappninja_option('nodeeplink') == '1') {
		return false;
	}
	
	// not wpappninja
	$get_pagename = isset($_GET['pagename']) ? sanitize_text_field($_GET['pagename']) : "";
	if (!isset($_SERVER['HTTP_X_WPAPPNINJA']) && $get_pagename != 'wpappninja') {
		return false;
	}
	
	// not ios and android do not support deeplinking
	if (!get_option('wpappninja_android_deeplinking', false) && !wpappninja_is_ios()) {
		return false;
	}
	
	return true;
}

/**
 * Start the deeplinking process.
 *
 * @since 5.1.1
 */
add_action('init', 'wpappninja_deeplink_start');
function wpappninja_deeplink_start() {

	if (!is_wpappninja()) {
		return;
	}

	ob_start("wpappninja_deeplink_process");
}

/**
 * Convert all internal link to the app version.
 *
 * @since 5.1.1
 */
function wpappninja_deeplink_process($buffer) {

	if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App' && get_wpappninja_option('speed') == '1' && get_wpappninja_option('webview') == '4' && get_wpappninja_option('wpm_lazyload', '1') == '1') {
		$regex = "/<img\s+(?:[^>]*?\s+)?(?:src)\s*=\s*[\"\'](?:\/*)([^\"\']*)([\"\'])/Ui";
		$buffer = preg_replace_callback($regex, function ($matches) {

			$separator = $matches[2];

			if (preg_match('#data-nolazy#', $matches[0])) {
				return $matches[0];
			}

			if (isset($_GET['shortcode_preview'])) {
				return preg_replace('/src=/', 'src='.$separator.'data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='.$separator.' data-noload=', $matches[0]);

			} else {
				return preg_replace('/src=/', 'src='.$separator.'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'.$separator.' data-src=', $matches[0]);
			}
			
		}, $buffer);
	}

	$regex = "/<a\s+(?:[^>]*?\s+)?(?:href)\s*=\s*[\"\'](?:\/*)([^\"\']*)[\"\']/Ui";
	$content = preg_replace_callback($regex, function ($matches) {

		$scheme_http = parse_url( home_url(), PHP_URL_SCHEME );

		if (wpappninja_deeplink_ready()) {
			$scheme = wpappninja_deeplink_scheme();
		} else {
			$scheme = $scheme_http;
		}
		$url = $matches[1];

		// correct scheme
		
    	$url = set_url_scheme( $url, $scheme_http );

		// internal link?
		if (preg_match('/^('.preg_quote(get_site_url(''), "/").'|\/[^\/])/', $url)) {
			if (!preg_match('#^http#', $url)) {

				$home_url_domain = parse_url( home_url(), PHP_URL_SCHEME ) . '://' . parse_url( home_url(), PHP_URL_HOST ) . '/';

				$url = $home_url_domain . preg_replace('#^/#', '', $url);
			}

			$url = wpappninja_cache_friendly($url);

			return preg_replace('/' . preg_quote($matches[1], '/') . '/', $scheme . '://' . preg_replace('#^http[s]?://#', '', $url), $matches[0]);
		}
	
		return $matches[0];
	}, $buffer);


	if (isset($_GET['shortcode_preview'])) {
		$content = preg_replace('/(\:[ ]?url[ ]?\()([^\)]+)([ ]?\))/', '$1data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==$3', $content);

		$content = preg_replace('/srcset=/', 'srcsetnoset=', $content);
	}

	return $content;
}

/**
 * Build the mobile scheme
 *
 * @since 5.1.1
 */
function wpappninja_deeplink_scheme() {

	// package name is the scheme base
	$package = get_wpappninja_option('package');

	// old ios support
	if (wpappninja_is_ios() && get_option('wpappninja_old_ios_deeplinking')) {
		$package = 'ninja.wpapp.' . preg_replace('/\.wpapp$/', '', $package);
	}

	// hack for the simulator
	if (isset($_SERVER['HTTP_X_WPAPPNINJA_DEMO']) && $_SERVER['HTTP_X_WPAPPNINJA_DEMO'] == "1") {
		$package = 'demowpapp';
	}

	// no package?
	if ($package == "") {
		$package = wpappninja_fake_package();
	}

	// build the scheme
	$scheme = preg_replace('#\.#', '', $package);

	return $scheme;
}

/**
 * Fix a bug if the webview didn't send the header.
 *
 * @since 5.1.6
 */
add_action('init', 'wpappninja_fix_header', 1);
function wpappninja_fix_header() {

	global $wpdb;

	// lang selector
	if (isset($_GET['wpmobileapp_locale']) && strlen($_GET['wpmobileapp_locale']) == 2) {
		//if (canGetWPMobileCookie()) {
			$_COOKIE['WPAPPNINJA_LOCALE'] = strtolower($_GET['wpmobileapp_locale']);
			setcookie( "WPAPPNINJA_LOCALE", strtolower($_GET['wpmobileapp_locale']), time() + 864000, COOKIEPATH, COOKIE_DOMAIN);
		//}
	}

	if (isset($_GET['wpappninja_simul4'])) {
		$_SERVER['HTTP_X_WPAPPNINJA_ID'] = "dummy";
	}

	if (wpappninja_is_pwa()) {
		$_SERVER['HTTP_X_WPAPPNINJA'] = "1";
		$_COOKIE['HTTP_X_WPAPPNINJA'] = "1";
	}

	if (isset($_GET['wpappninja'])) {
		$_SERVER['HTTP_X_WPAPPNINJA'] = "1";
		$_COOKIE['HTTP_X_WPAPPNINJA'] = "1";
		return;
	}

	if (isset($_SERVER['HTTP_X_WPAPPNINJA_BYPASS'])) {
		$_SERVER['HTTP_X_WPAPPNINJA'] = "0";
		$_COOKIE['HTTP_X_WPAPPNINJA'] = "0";
		return;
	}

	/*if (wpappninja_is_pwa()) {
		$_SERVER['HTTP_X_WPAPPNINJA'] = "1";
	}*/

	if (isset($_SERVER['HTTP_X_WPAPPNINJA_IOS']) || isset($_SERVER['HTTP_X_WPAPPNINJA_DEMO']) || isset($_SERVER['HTTP_X_WPAPPNINJA_LOCALE'])) {
		$_SERVER['HTTP_X_WPAPPNINJA'] = "1";
	}

	if (isset($_SERVER['HTTP_X_WPAPPNINJA_DEMO'])) {
		
		if (canGetWPMobileCookie()) {
			setcookie( "HTTP_X_WPAPPNINJA_DEMO", $_SERVER['HTTP_X_WPAPPNINJA_DEMO'], time() + 864000, COOKIEPATH, COOKIE_DOMAIN);
		}

		$_COOKIE['HTTP_X_WPAPPNINJA_DEMO'] = $_SERVER['HTTP_X_WPAPPNINJA_DEMO'];
	} elseif (isset($_COOKIE['HTTP_X_WPAPPNINJA_DEMO'])) {
		$_SERVER['HTTP_X_WPAPPNINJA_DEMO'] = $_COOKIE['HTTP_X_WPAPPNINJA_DEMO'];
	}

	if (isset($_SERVER['HTTP_X_WPAPPNINJA'])) {

		if (canGetWPMobileCookie()) {
			setcookie( "HTTP_X_WPAPPNINJA", $_SERVER['HTTP_X_WPAPPNINJA'], time() + 864000, COOKIEPATH, COOKIE_DOMAIN );
		}
		
		$_COOKIE['HTTP_X_WPAPPNINJA'] = $_SERVER['HTTP_X_WPAPPNINJA'];
	} elseif (isset($_COOKIE['HTTP_X_WPAPPNINJA'])) {
		$_SERVER['HTTP_X_WPAPPNINJA'] = $_COOKIE['HTTP_X_WPAPPNINJA'];
	}

	if (isset($_GET['iswpappninjaconfigurator'])) {
		$_SERVER['HTTP_X_WPAPPNINJA'] = "1";
	}

	if (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID'])) {

		$cookie_id = $_COOKIE['HTTP_X_WPAPPNINJA_ID'];
		$user_bdd_id = $wpdb->get_row($wpdb->prepare("SELECT `id`, `user_id` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $cookie_id));

		if (isset($user_bdd_id->user_id) && isset($_SERVER['HTTP_X_WPAPPNINJA_ID']) && $user_bdd_id->user_id != $_SERVER['HTTP_X_WPAPPNINJA_ID']) {
			$_COOKIE['HTTP_X_WPAPPNINJA_ID'] = "";
		}

		if (!isset($user_bdd_id->id)) {
			$_COOKIE['HTTP_X_WPAPPNINJA_ID'] = "";
		}
	}

	if (isset($_SERVER['HTTP_X_WPAPPNINJA_ID']) && (!isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) || $_COOKIE['HTTP_X_WPAPPNINJA_ID'] == "")) {
		$user_id = $_SERVER['HTTP_X_WPAPPNINJA_ID'];
		$user_bdd_id = $wpdb->get_row($wpdb->prepare("SELECT `id` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `user_id` = %s", $user_id));

		if (!isset($user_bdd_id->id)) {
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push_perso (`user_id`) VALUES (%s)", $user_id));

			$lastid = $wpdb->insert_id;
		} else {
			$lastid = $user_bdd_id->id;
		}

		if (canGetWPMobileCookie()) {
			setcookie( "HTTP_X_WPAPPNINJA_ID", $lastid, time() + 8640000, COOKIEPATH, COOKIE_DOMAIN );
		}

		$_COOKIE['HTTP_X_WPAPPNINJA_ID'] = $lastid;
	}

	/*if (!isset($_SERVER['HTTP_X_WPAPPNINJA_ID']) && (!isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) || $_COOKIE['HTTP_X_WPAPPNINJA_ID'] == "") && is_wpappninja()) {
		$user_id = "RANDOM_" . uniqid();
		$user_bdd_id = $wpdb->get_row($wpdb->prepare("SELECT `id` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `user_id` = %s", $user_id));

		if (!isset($user_bdd_id->id)) {
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push_perso (`user_id`) VALUES (%s)", $user_id));

			$lastid = $wpdb->insert_id;
		} else {
			$lastid = $user_bdd_id->id;
		}

		setcookie( "HTTP_X_WPAPPNINJA_ID", $lastid, time() + 8640000, COOKIEPATH, COOKIE_DOMAIN );
		$_COOKIE['HTTP_X_WPAPPNINJA_ID'] = $lastid;
	}*/

	if (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID'])) {

		$wpm_user = wp_get_current_user();
		if ( $wpm_user->exists() ) {

			$email = $wpm_user->user_email;

			$wma_user_id = $_COOKIE['HTTP_X_WPAPPNINJA_ID'];
			$wma_bdd_id = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $wma_user_id));

			if (isset($wma_bdd_id->category)) {

				$category_u = $wma_bdd_id->category;
				$cat_e = explode(',', $category_u);
                
                $cat_e = array_filter($cat_e , function ($item){
                    return !preg_match('/@/i', $item);
                });

				if (!in_array($email, $cat_e)) {
					$cat_e[] = $email;
				}

				$cat_i = implode(',', array_filter($cat_e));

				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push_perso SET category = %s WHERE `id` = %s", $cat_i, $wma_user_id));
			}
        } else {
            
            /***/
            $wma_user_id = $_COOKIE['HTTP_X_WPAPPNINJA_ID'];
            $wma_bdd_id = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $wma_user_id));

            if (isset($wma_bdd_id->category)) {

                $category_u = $wma_bdd_id->category;
                $cat_e = explode(',', $category_u);
                
                $cat_e = array_filter($cat_e , function ($item){
                    return !preg_match('/@/i', $item);
                });

                $cat_i = implode(',', array_filter($cat_e));

                $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push_perso SET category = %s WHERE `id` = %s", $cat_i, $wma_user_id));
            }
            /***/
        }
	}
}

function canGetWPMobileCookie() {

	if (defined( 'DOING_AJAX' ) && DOING_AJAX) {
		return false;
	}

	if (isset($_SERVER['HTTP_X_WPMOBILEAPP_WEB'])) {
		return false;
	}

	if (isset($_GET['wpappninja_simul4'])) {
		return false;
	}

	if (isset($_GET['wpappninja_my_theme'])) {
		return false;
	}

	if (isset($_SERVER['HTTP_REFERER']) && preg_match('/\/wpappninja\/assets\/3rd-party\/sw\.php/', $_SERVER['HTTP_REFERER'])) {
		return false;
	}

	return true;
}

/**
 * BETA Try to find a fix for a better compatibility with cache plugin.
 *
 * @since 7.0.12
 */
function wpappninja_cache_friendly($s) {

	$preventCache = false;
	if (get_wpappninja_option('agressive_anti_cache') == '1') {

		// valid url
		if (filter_var($s, FILTER_VALIDATE_URL) && !preg_match('#add-to-cart=#', $s) && !preg_match('#_wpnonce=#', $s) && !preg_match('#wpmobileexternal=#', $s) && !preg_match('#wpappninja_v=#', $s) && !preg_match('#\.(pdf|png|jpg|jpeg|gif|bmp)#', $s) && !preg_match('#\##', $s)) {

			// internal link
			if (preg_match('/^('.preg_quote(get_site_url(''), "/").'|\/[^\/])/', $s)) {


				$preventCache = true;

				$query = parse_url($s, PHP_URL_QUERY);
				if ($query) {
					$s .= '&wpappninja_v=' . uniqid();
				} else {
    				$s .= '?wpappninja_v=' . uniqid();
    			}
			}
		}
	}

	if (wpappninja_is_pwa()) {

		// valid url
		if (filter_var($s, FILTER_VALIDATE_URL) && !preg_match('#add-to-cart=#', $s) && !preg_match('#_wpnonce=#', $s) && !preg_match('#wpmobileexternal=#', $s) && !preg_match('#is_wppwa=#', $s) && !preg_match('#\.(pdf|png|jpg|jpeg|gif|bmp)#', $s) && !preg_match('#\##', $s)) {

			// internal link
			if (preg_match('/^('.preg_quote(get_site_url(''), "/").'|\/[^\/])/', $s)) {

				$query = parse_url($s, PHP_URL_QUERY);
				if ($query) {
					$s .= '&is_wppwa=true';
				} else {
    				$s .= '?is_wppwa=true';
    			}
			}
		}
	}

	if (get_wpappninja_option('cache_friendly') == '1' && !preg_match('#add-to-cart=#', $s) && !preg_match('#wpmobileexternal=#', $s) && !preg_match('#_wpnonce=#', $s) && !$preventCache && !preg_match('#\.(pdf|png|jpg|jpeg|gif|bmp)#', $s) && !preg_match('#\##', $s)) {

		// valid url
		if (filter_var($s, FILTER_VALIDATE_URL) && !preg_match('#wpappninja_cache=#', $s)) {

			// internal link
			if (preg_match('/^('.preg_quote(get_site_url(''), "/").'|\/[^\/])/', $s)) {

				$query = parse_url($s, PHP_URL_QUERY);
				if ($query) {
					$s .= '&wpappninja_cache=friendly';
				} else {
    				$s .= '?wpappninja_cache=friendly';
    			}
			}
		}
	}

	return $s;
}
