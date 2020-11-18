<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/**
 * Import a WP menu on the WPMobile.App builder.
 *
 * @since 3.6.4
 */
function wpappninja_import_menu($import_menu, $lang) {
	$option = get_option(WPAPPNINJA_SLUG);
	$option['import_menu_' . $lang] = '';

	if ($option['menu_reload_' . $lang] == '') {
		$option['menu_reload_' . $lang] = array();
	}

	$weight = 0;
	foreach (get_wpappninja_option('menu_reload_' . $lang, array()) as $item) {
		if ($item['weight'] > $weight) {
			$weight = $item['weight'] + 1;
		}
	}

	$items = wp_get_nav_menu_items ($import_menu);
	foreach($items as $item) {
		$weight++;
		$name = $item->title;
		$id = $item->object_id;
		if ($item->type == 'custom') {

			// internal link check
			if (wpappninja_url_to_postid($item->url) !== 0) {
				$id = wpappninja_url_to_postid($item->url);
				$type = 'page';
			} elseif (wpappninja_url_to_catid($item->url) !== 0) {
				$id = wpappninja_url_to_catid($item->url);
				$type = 'cat';
			} else {
				$id = $item->url;
				$type = 'link';
			}

		} else if ($item->type == 'taxonomy') {
			$type = 'cat';
		} else {
			$type = 'page';
		}


		// recent post
		if ($type == 'page' && $id == get_option( 'page_for_posts' )) {
			$id = 0;
			$type = 'cat';
		}

		// icon
		$icon = 'arrowlight';

		if ($type == 'page') {
			$post = get_post($id); 
			$search = get_permalink($post) . ' ' . $post->post_name . ' ' . $post->post_title;
			$icon = wpappninja_auto_select_icon($search);

		} elseif ($type == 'cat') {
			$taxonomy = wpappninja_get_all_taxonomy();
			$search = get_category_link($id);
			foreach ($taxonomy as $tax) {
				$obj = get_term_by('id', $id, $tax);
				if (is_object($obj)) {
					$search .= $obj->name . ' ' . $obj->description;
					break;
				}

				$icon = wpappninja_auto_select_icon($search);
			}

		} elseif ($type == 'link') {
			$search = $id . ' ' . $name;
			$icon = wpappninja_auto_select_icon($search);
		}

		if (!($type == 'page' && $id == 0)) {
			$menu_item = array('feat' => '0', 'weight' => $weight, 'icon' => $icon, 'id' => strval($id), 'name' => $name, 'type' => $type);
			$option['menu_reload_' . $lang][] = $menu_item;
		}
	}
	
	update_option(WPAPPNINJA_SLUG, $option);

	wpappninja_clear_cache();
}

/**
 * Import the current homepage.
 *
 * @since 4.3.1
 */
function wpappninja_import_homepage($lang) {
	$option = get_option(WPAPPNINJA_SLUG);
	$option['import_homepage_' . $lang] = '';

	if (get_option('show_on_front') == 'posts') {
		$homeid = '';
		$option['pageashomeicon_' . $lang] = 'arrowlight';
	} else {
		$homeid = get_option('page_on_front');

		$post = get_post($homeid); 
		$search = $post->post_name . ' ' . $post->post_title;
		$option['pageashomeicon_' . $lang] = wpappninja_auto_select_icon($search);
	}

	$option['pageashome_' . $lang] = $homeid;
	$option['pageashometitle_' . $lang] = wpappninja_get_appname();
	update_option(WPAPPNINJA_SLUG, $option);

	wpappninja_clear_cache();
}

/**
 * Auto import homepage and menu.
 *
 * @since 4.3.1
 */
function wpappninja_magic_import() {

	$options = get_option( WPAPPNINJA_SLUG );
	$options['menu_reload_speed'] = array();
	$options['speed'] = "1";
	$options['customcss'] = "";
	$options['customcss_website'] = "";
	$options['webview'] = "4";
	$options['speed_notheme'] = "0";
	$options['nospeed_notheme'] = "0";
	$options['nomoretheme'] = "1";
	$options['sdk2019'] = "1";

	$options['slidetoopen'] = "1";
	$options['css_068548511b2b468fd25b4d0affd5b6e8'] = "block";
	$options['infinitescroll'] = "0";
	$options['speed_reload'] = "1";
	$options['titlespeed'] = "0";
	$options['wpappninja_pwa'] = "on";
	$options['cache_type'] = "networkonly";
	$options['fastclick'] = "0";
	$options['cache_friendly'] = "1";
	$options['all_link_browser'] = "0";
	$options['css_69ad2f07d7ea5a123c3ff5ad054ce272'] = "17px";
	$options['push_category'] = "";
	$options['wpm_lazyload'] = '1';

	update_option( WPAPPNINJA_SLUG, $options );

	$app_data = get_wpappninja_option('app');
	$logo = isset($app_data['logo']) ? $app_data['logo'] : "https://cdn.wpmobile.app/FFFFFF-0.png";

	// homepage
	//wpappninja_add_link_homepage(home_url( '' ) . "/?wpapp_shortcode=wpapp_home", "speed");
	wpappninja_add_link_homepage(home_url( '' ), "speed");


	
	if ( class_exists( 'WooCommerce' ) ) {

		// menu
		$taxonomy     = 'product_cat';
		$orderby      = 'number';  
  		$show_count   = 0;
		$pad_counts   = 0;
	  	$empty        = 1;
	  	$order 		  = "DESC";
	  	$number		  = 5;

		$args = array(
        	'taxonomy'     => $taxonomy,
        	'orderby'      => $orderby,
        	'show_count'   => $show_count,
        	'pad_counts'   => $pad_counts,
        	'hide_empty'   => $empty,
        	'order'		   => $order,
        	'number' 	   => $number,
		);

		$all_categories = get_categories( $args );
		$nbitem = 0;
		foreach ($all_categories as $cat) {
		    if($cat->category_parent == 0 && $nbitem < 5) {
		    	$nbitem++;
        		wpappninja_add_link(get_term_link($cat->slug, 'product_cat'), "speed");
		    }       
		}

		// toolbar menu
		wpappninja_add_link(get_permalink( wc_get_page_id( 'shop' ) ), "speed", "1");
		wpappninja_add_link(get_permalink( wc_get_page_id( 'cart' ) ), "speed", "1");

	}

	// blog
	$args = array(
	    'post_type' => 'post',
	    'post_status' => 'publish',
	    'orderby' => 'date',
	    'numberposts' => 1,
	    'order' => 'DESC',
	);
	$recent_posts = wp_get_recent_posts( $args, ARRAY_A );

	if (isset($recent_posts[0]) && count($recent_posts[0]) > 0) {

		$args = array(
        	'taxonomy'     => 'category',
        	'hide_empty'   => true,
		);

		$all_categories = get_categories( $args );
		$nbitem = 0;
		foreach ($all_categories as $cat) {
		    if($cat->category_parent == 0 && $nbitem < 10) {
		    	$nbitem++;
        		wpappninja_add_link(get_term_link($cat->slug, 'category'), "speed");
		    }       
		}
	}


	// determine colors
	$primary = $_POST['wpappninja_main_color'];
	if ($primary == "" OR !strlen($primary) == 7) {
		$primary = "#ffffff";
	}
	$accent = wpappninja_get_accent($primary);

	$options            = get_option( WPAPPNINJA_SLUG );

	$options['app']['theme']['primary'] = $primary;
	$options['app']['theme']['accent'] = $accent;
	$options['app']['ios_background'] = $primary;
	$options['firstcssfill'] = "ok";

		$status = $primary;

		$status_text = '#ffffff';
		if (wpappninja_need_light_status($status)) {
			$status_text = '#000000';
		}

		$background = '#ffffff';
		$semibackground = '#ffffff';

		$menu_bas = $status;
		$menu_bas_text = $status_text;

		$menu_gauche_fond = '#ffffff';
		$menu_gauche_fond_item = '#ffffff';
		$menu_gauche_icon = '#333';
		if (!wpappninja_need_light_status($status)) {
			$menu_gauche_icon = $status;
		}
		$menu_gauche_text = '#333333';

		$menu_flottant_fond = $status;
		$menu_flottant_text = $status_text;

		$text = '#333333';
		$lien = '#333333';

		$bouton_bordure = '#ffffff';
		$bouton_text = '#333333';

		$list_fond = '#ffffff';

		$loadingbar = $status_text;

	$cssrules = array(

		'css_51d39016596e1db1ffd8f5118a11dd3c' => 'transparent',
		'css_95549900f280b71ea92d360dd94dfbd3' => $semibackground,
		'css_102c4591c3ac08bbcdbf73981d5eb725' => $background,
		'css_0c5c5bf1fda47e5230fff4396a1f8779' => '#dd5742',
		'css_74537a66b8370a71e9b05c3c4ddbf522' => $status,
		'css_dc2e1703b492b0ad78d631130af23035' => $status,
		'css_00bcbfacaf98f1b05815ab4eaeee1e13' => $status_text,
		'css_9be9a1df3d0a60c0bc18ff5c65da2d99' => $menu_bas,
		'css_d56e17633aad9957d84a39b9db286028' => $menu_bas_text,  
		'css_3f5f8d30cf6e081b99f6f35c69cf0ccd' => $menu_gauche_fond,
		'css_98cbd51ad8789c03f7dd7d6cd3cd9e08' => $menu_gauche_fond_item,
		'css_4fc1ded5c6315ed4e79133a69f3b6d98' => $menu_gauche_icon,
		'css_c1cbcf662a13f13037d53a185986c2ad' => $menu_gauche_text,
		'css_06a182f400cbc8002d5b0aa4d0d2082e' => $menu_flottant_fond,
		'css_d28f02c7af0320dd04a77d66f5dea891' => $menu_flottant_text,
		'css_d7a8405db9b1bc84f477b325f32d2574' => $text,
		'css_dd5ec26859062e9c07578efb2f601d7f' => $text,
		'css_d115509b7fa9b63e2e07aed34183fea8' => $lien,
		'css_5786e51e83c834d64469d823887736ff' => $bouton_bordure,
		'css_37a011662d8b2e4e27b9f662ff3f91ed' => $bouton_text,
		'css_305cad765b7512c618c0d6174913fb94' => $list_fond,
		'css_e0c30224e61a0fa53753d0992872782d' => $loadingbar,

	);

	foreach ($cssrules as $key => $val) {

		$options[$key] = $val;
	}

	$widgets = wpappninja_get_widgets();
	foreach($widgets as $w) {
		$options['widget_' . md5($w['id'])] = $w['default'];
	}

	// notifications
	wpappninja_add_link("pushconfig", "speed");

	update_option( WPAPPNINJA_SLUG, $options );
}

/**
 * Transform a link to a sentence.
 *
 * @since 4.3.2
 */
function wpappninja_textify($url) {

	if (preg_match('#wpapp_shortcode=wpapp_home#', $url)) {
		return __('My home', 'wpappninja');
	}


	if (preg_match('#wpmobileshareme#', $url)) {
		return __('Share', 'wpappninja');
	}


	if (preg_match('#wpappqrcode=1#', $url) || preg_match('#wpapp_shortcode=wpapp_qrcode#', $url)) {
		return __('QRCode reader', 'wpappninja');
	}


	if (preg_match('#wpapp_shortcode=wpapp_config#', $url)) {
		return __('Push config', 'wpappninja');
	}


	if (preg_match('#wpapp_shortcode=wpapp_history#', $url)) {
		return __('Notification history', 'wpappninja');
	}


	if (preg_match('#wpapp_shortcode=wpapp_login#', $url)) {
		return __('Login', 'wpappninja');
	}


	if (preg_match('#wpapp_shortcode=wpapp_recent#', $url)) {
		return __('Recent posts', 'wpappninja');
	}

    $url = urldecode($url);
    $url = preg_replace("/^" . preg_quote(home_url( '' ), '/') . "/", "", $url);
    $url = preg_replace("/http(s)?:\/\/(www\.)?/", "", $url);
    $url = str_replace(array(".", "/", ",", "-", "_"), " ", $url);
    $url = ucwords($url);
    $url = wordwrap($url, 40);
    //$url = substr($url, 0, strpos($url, "\n"));
    return $url;
}

/**
 * Add a new item based on a link.
 *
 * @since 4.3.1
 */
function wpappninja_add_link($url, $lang, $feat = "0") {

	if (!is_string($url)) {
		return;
	}

	$option = get_option(WPAPPNINJA_SLUG);
	$option['add_link_' . $lang] = '';

	if ($option['menu_reload_' . $lang] == '') {
		$option['menu_reload_' . $lang] = array();
	}

	$name = $url;

	$weight = -1;
	foreach (get_wpappninja_option('menu_reload_' . $lang, array()) as $item) {
		if ($item['weight'] > $weight) {
			$weight = $item['weight'] + 1;
		}
	}

	if (!preg_match('#wpapp_shortcode#', $url) && get_wpappninja_option('speed') != '1') {
		if (wpappninja_url_to_postid($url) !== 0) {
			$id = wpappninja_url_to_postid($url);
			$type = 'page';
		} elseif (wpappninja_url_to_catid($url) !== 0) {
			$id = wpappninja_url_to_catid($url);
			$type = 'cat';
		} else {
			$id = $url;
			$type = 'link';
		}
	} else {
		$id = $url;
		$type = 'link';
	}

	// icon
	$icon = 'arrowlight';

	$continuecat = true;

	// recent post
	if ($type == 'page' && $id == get_option( 'page_for_posts' )) {
		$id = 0;
		$type = 'cat';
		$name = __('Recent posts', 'wpappninja');
		$icon = wpappninja_auto_select_icon($name);
		$continuecat = false;
	}

	// recent
	if (preg_replace('#http://#', '', $url) == "recent") {
		$id = 0;
		$type = 'cat';
		$name = __('Recent posts', 'wpappninja');
		$icon = wpappninja_auto_select_icon($name);
		$continuecat = false;
	}

	// push history
	if (preg_replace('#http://#', '', $url) == "notifications") {
		$id = -100;
		$type = 'cat';
		$name = __('Notification history', 'wpappninja');
		$icon = wpappninja_auto_select_icon($name);
		$continuecat = false;
	}

	// chat
	if (preg_replace('#http://#', '', $url) == "pushconfig") {
		$id = -999;
		$type = 'page';
		$name = __('Push config', 'wpappninja');
		$icon = wpappninja_auto_select_icon($name);
	}

	if ($type == 'page' && $id != -999) {
		$post = get_post($id); 
		$search = get_permalink($post) . ' ' . $post->post_name . ' ' . $post->post_title;
		$name = $post->post_title;
		$icon = wpappninja_auto_select_icon($search);

	} elseif ($type == 'cat' && $continuecat) {
		$taxonomy = wpappninja_get_all_taxonomy();
		$search = get_category_link($id);
		foreach ($taxonomy as $tax) {
			$obj = get_term_by('id', $id, $tax);
			if (is_object($obj)) {
				$search .= $obj->name . ' ' . $obj->description;
				$name = $obj->name;
				break;
			}
			$icon = wpappninja_auto_select_icon($search);
		}

	} elseif ($type == 'link') {
		$name = wpappninja_textify($name);
		$search = $id . ' ' . $name;
		$icon = wpappninja_auto_select_icon($search);
	}

	// mail
	if (preg_match('#^mailto:#', $url)) {
		$name = __('Contact', 'wpappninja');
	}

	if (!($type == 'page' && $id == 0)) {
		$menu_item = array('feat' => $feat, 'weight' => $weight, 'icon' => $icon, 'id' => strval($id), 'name' => $name, 'type' => $type);
		$option['menu_reload_' . $lang][] = $menu_item;
	}

	update_option(WPAPPNINJA_SLUG, $option);
}

/**
 * Add a new homepage based on a link.
 *
 * @since 5.2.1
 */
function wpappninja_add_link_homepage($url, $lang) {

	$option = get_option(WPAPPNINJA_SLUG);
	$option['add_link_homepage_' . $lang] = '';

	$name = $url;

	if (!preg_match('#wpapp_shortcode#', $url) && get_wpappninja_option('speed') != '1') {
		if (wpappninja_url_to_postid($url) !== 0) {
			$id = wpappninja_url_to_postid($url);
			$type = 'page';
		} elseif (wpappninja_url_to_catid($url) !== 0) {
			$id = wpappninja_url_to_catid($url);
			$type = 'cat';
		} else {
			$id = $url;
			$type = 'link';
		}
	} else {
		$id = $url;
		$type = 'link';
	}

	// recent post
	if ($type == 'page' && $id == get_option( 'page_for_posts' )) {
		$id = 0;
		$type = 'cat';
	}

	// recent
	if (preg_replace('#http://#', '', $url) == "recent") {
		$id = 0;
		$type = 'cat';
		$name = __('Recent posts', 'wpappninja');
	}

	// recent
	if (preg_replace('#http://#', '', $url) == "notifications") {
		$id = -100;
		$type = 'cat';
		$name = __('Notification history', 'wpappninja');
	}

	// recent
	if (preg_replace('#http://#', '', $url) == "pushconfig") {
		$id = -999;
		$type = 'page';
		$name = __('Push config', 'wpappninja');
	}
	
	if ($type == 'page' && $id != -999) {
		$post = get_post($id); 
		$name = $post->post_title;

	} elseif ($type == 'cat') {
		$taxonomy = wpappninja_get_all_taxonomy();
		foreach ($taxonomy as $tax) {
			$obj = get_term_by('id', $id, $tax);
			if (is_object($obj)) {
				$name = $obj->name;
				break;
			}
		}

	} elseif ($type == 'link') {
		$name = wpappninja_textify($name);
	}

	if (!($type == 'page' && $id == 0)) {

		if ($type == 'cat') {
			$type = 'cat_';
		} else {
			$type = '';
		}

		$option['pageashome_' . $lang] = $type . $id;
		$option['pageashometitle_' . $lang] = $name;
		$option['pageashomeicon_' . $lang] = wpappninja_auto_select_icon($type . ' ' . $id . ' ' . $name);
	}

	update_option(WPAPPNINJA_SLUG, $option);
}
