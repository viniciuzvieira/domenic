<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/*
 * Add meta tags.
 *
 * @since 1.0
 */
add_action('wp_head', 'wpappninja_seo');
function wpappninja_seo() {
	
	// package name and id
	$android 			= get_wpappninja_option('package', '');
	$ios 				= get_wpappninja_option('appstore_package', '');

	// custom scheme
	$scheme 			= str_replace('.', '', get_wpappninja_option('package', wpappninja_fake_package()));

	// current page
	$uri 				= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// app name
	$app_data 			= get_wpappninja_option('app');
	$app_name 			= isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();

	echo PHP_EOL;
	echo PHP_EOL;
	echo '<!-- WPMobile.App -->' . PHP_EOL;

	// progressive web app
	/*if (is_ssl()) {

	if (wpappninja_is_pwa()) {
		echo '<script type="text/javascript">' .PHP_EOL;
		echo "if ('serviceWorker' in navigator) {" . PHP_EOL;
		echo "    self.addEventListener('install', function(event) {" . PHP_EOL;
		echo "        event.waitUntil(self.skipWaiting());" . PHP_EOL;
		echo "    });" . PHP_EOL;
		echo "    self.addEventListener('activate', function(event) {" . PHP_EOL;
		echo "        event.waitUntil(self.clients.claim());" . PHP_EOL;
		echo "    });" . PHP_EOL;
		echo "    window.addEventListener('load', function() {" . PHP_EOL;
		echo "        navigator.serviceWorker.register('" . WPAPPNINJA_ASSETS_3RD_URL . "sw.php', { scope: '/' });" . PHP_EOL;
		echo "    });" . PHP_EOL;
		echo "}" . PHP_EOL;
		echo '</script>' . PHP_EOL;

	} else if (get_option('wpappninja_pwa_home') == '1' && !is_wpappninja() && is_wpmobile_pwa_ready()) {
		echo '<link rel="manifest" href="' . WPAPPNINJA_ASSETS_3RD_URL . 'manifest.php" />' . PHP_EOL;
		echo '<script type="text/javascript">' .PHP_EOL;
		echo "if ('serviceWorker' in navigator) {" . PHP_EOL;
		echo "    self.addEventListener('install', function(event) {" . PHP_EOL;
		echo "        event.waitUntil(self.skipWaiting());" . PHP_EOL;
		echo "    });" . PHP_EOL;
		echo "    self.addEventListener('activate', function(event) {" . PHP_EOL;
		echo "        event.waitUntil(self.clients.claim());" . PHP_EOL;
		echo "    });" . PHP_EOL;
		echo "    window.addEventListener('load', function() {" . PHP_EOL;
		echo "        navigator.serviceWorker.register('" . WPAPPNINJA_ASSETS_3RD_URL . "swweb.php', { scope: '/' });" . PHP_EOL;
		echo "    });" . PHP_EOL;
		echo "}" . PHP_EOL;
		echo '</script>' . PHP_EOL;
	}
	}*/

	// colors on chrome
	echo '<meta name="theme-color" content="' . wpappninja_get_hex_color() . '" />' . PHP_EOL;
	echo '<meta name="msapplication-navbutton-color" content="' . wpappninja_get_hex_color() . '" />' . PHP_EOL;
	echo '<meta name="apple-mobile-web-app-status-bar-style" content="' . wpappninja_get_hex_color() . '" />' . PHP_EOL;

    
    // if promote banner is on
    if (get_wpappninja_option('smartbanner', '') == '1') {
        
    // ios meta
    if ($ios != "" && $ios != "xxx") {
        echo '<meta property="al:ios:url" content="' . $scheme . '://' . $uri . '" />' . PHP_EOL;
        echo '<meta property="al:ios:app_store_id" content="' . $ios . '" />' . PHP_EOL;
        echo '<meta property="al:ios:app_name" content="' . $app_name . '" />' . PHP_EOL;
        echo '<meta name="apple-itunes-app" content="app-id=' . $ios . ', app-argument=' . $scheme . '://' . $uri . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:name:iphone" content="' . $app_name . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:id:iphone" content="' . $ios . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:url:iphone" content="' . $scheme . '://' . $uri . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:name:ipad" content="' . $app_name . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:id:ipad" content="' . $ios . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:url:ipad" content="' . $scheme . '://' . $uri . '" />' . PHP_EOL;
    }

    // android meta
    if ($android != "") {
        //echo '<link rel="alternate" href="android-app://' . $android . '/' . $scheme . '/' . $uri . '" />' . PHP_EOL;
        echo '<meta property="al:android:url" content="' . $scheme . '://' . $uri . '" />' . PHP_EOL;
        echo '<meta property="al:android:package" content="' . $android . '" />' . PHP_EOL;
        echo '<meta property="al:android:app_name" content="' . $app_name . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:name:googleplay" content="' . $app_name . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:id:googleplay" content="' . $android . '" />' . PHP_EOL;
        echo '<meta name="twitter:app:url:googleplay" content="' . $scheme . '://' . $uri . '" />' . PHP_EOL;
    }
    }


	echo '<!-- / WPMobile.App -->' . PHP_EOL;
	echo PHP_EOL;
}

/*
 * Convert an url to category ID.
 *
 * @since 3.9.1
 */
function wpappninja_url_to_catid($url) {
	
    // Get rid of the #anchor
    $url_split = explode('#', $url);
    $url = $url_split[0];

    // Get rid of URL ?query=string
    $url_split = explode('?', $url);
    $url = $url_split[0];

    // Get rid of the last slashe
	$url = rtrim($url,"/");

	// Get the slug
	$parts = explode('/', $url);
	$slug = end($parts);
	
	$id = 0;

	$taxonomy = wpappninja_get_all_taxonomy();

	foreach ($taxonomy as $tax) {
		$obj = get_term_by('slug', $slug, $tax);
		if (is_object($obj)) {
			$id = $obj->term_id;
			break;
		}
	}
	
	return $id;
}

/*
 * Convert an url to post ID.
 *
 * @since 5.2.3
 */
function wpappninja_url_to_postid($url) {

	if (url_to_postid($url) !== 0) {
		return url_to_postid($url);
	}

	global $wpdb;
	$_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid = %s AND post_status = %s", $url, 'publish' ) );

	if (intval($_id) > 0) {
		return $_id;
	}
	
    // Get rid of the #anchor
    $url_split = explode('#', $url);
    $url = $url_split[0];

    // Get rid of URL ?query=string
    $url_split = explode('?', $url);
    $url = $url_split[0];

    // Get rid of the last slashe
	$url = rtrim($url,"/");

	// Get the slug
	$parts = explode('/', $url);
	$slug = end($parts);
	
	$_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_status = %s", $slug, 'publish' ) );

	if (intval($_id) > 0) {
		return $_id;
	}
	
	return 0;
}
