<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/*
 * Get the formated menu
 *
 * @since 1.0
 */
function wpappninja_get_menu_reloaded($locale = 'en', $translate = false) {
	$menu = array();

	$locale = "speed";
	
	$doingapi = false;
	if (defined('DOING_WPAPPNINJA_API')) {
		$doingapi = true;
	}

	$push_as_feat = "1";
	
	$items = wpappninja_weight_order(get_wpappninja_option('menu_reload_' . $locale));
	foreach($items as $item) {


			if ($translate) {

				$item['name'] = wpappninja_translate($item['name']);

				$url = wpappninja_translate($item['id']);
				$compare = $item['id'];
				if ($item['type'] == 'cat') {
					$url = wpappninja_translate('cat_' . $item['id']);
					$compare = 'cat_' . $item['id'];
				}

				if ($url != $compare) {

					$scheme = parse_url( home_url(), PHP_URL_SCHEME );
	    			$url = set_url_scheme( $url, $scheme );

					$item['id'] = url_to_postid($url);
					$item['type'] = 'read';
					if ($item['id'] === 0) {
						$item['id'] = wpappninja_url_to_catid($url);
						if ($item['id'] === 0) {
							$item['id'] = wpappninja_url_to_postid($url);
							$item['type'] = 'read';

							if ($item['id'] === 0) {
								$item['id'] = $url;
								$item['type'] = 'link';
							}
						} else {
							$item['type'] = 'cat';
						}
					}

					$item['id'] = strval($item['id']);
				}
			}
	
		$feat = '0';
		if (isset($item['feat'])) {
			if ($item['feat'] == '1') {
				$feat = '1';
				$push_as_feat = "0";
			}

			if ($item['feat'] == '2' && get_wpappninja_option('speed') == '1') {
				$feat = '2';
			}
		}
		
		// fix undefined
		if (!isset($item['id'])) {$item['id'] = '';}
		if (!isset($item['name'])) {$item['name'] = '';}
		if (!isset($item['type'])) {$item['type'] = 'page';}
		if (!isset($item['icon'])) {$item['icon'] = 'arrow';}
		
		// fix spacing encoding
		$item['id'] = preg_replace('# #', '%20', $item['id']);
		
		// ios8 sms link
		if ($doingapi && isset($_SERVER['HTTP_X_WPAPPNINJA_IOS'])) {
			if ($item['type'] == 'link' && substr($item['id'], 0, 4) == 'sms:') {
				$item['id'] = preg_replace('#\?body#', '&body', $item['id']);
			}

			if ($item['type'] == 'link' && substr($item['id'], 0, 4) == 'geo:') {
				$item['id'] = preg_replace('#geo:0,0#', 'http://maps.apple.com/', $item['id']);
			}

			if ($item['type'] == 'link' && substr($item['id'], 0, 4) == 'http') {

				if (preg_match('/^'.preg_quote(home_url(), "/").'/', $item['id'])) {
					$package = get_wpappninja_option('package', wpappninja_fake_package());
					$item['id'] = preg_replace('#\.#', '', $package) . '://' . preg_replace('#http[s]?://#', '', $item['id']);
				}
			}
		}


		if (get_wpappninja_option('speed') != '1' && $item['icon'] == 'chevron_right') {
			$item['icon'] = 'arrow';
		}


		// fix id or url
		if (get_wpappninja_option('speed') == '1') {

			if (!preg_match('#^http#', $item['id'])) {

				if ($item['type'] == 'cat') {

					$taxonomy = wpappninja_get_all_taxonomy();

				    foreach ($taxonomy as $tax) {
    				  $obj = get_term_by('id', $item['id'], $tax);
   					  if (is_object($obj)) {
						$item['type'] = 'link';
						$item['id'] = get_term_link($obj);
						break;
    				  }
	    			}

				} else if ($item['type'] == 'page') {
					
				    if (get_permalink(intval($item['id']))) {
						$item['type'] = 'link';
    					$item['id'] = get_permalink(intval($item['id']));
    				}

				}

			}



			if ($item['id'] == '-999') {
				$item['type'] = 'link';
				$item['id'] = home_url('/') . '?wpapp_shortcode=wpapp_config';
			}

			if ($item['id'] == '-100') {
				$item['type'] = 'link';
				$item['id'] = home_url('/') . '?wpapp_shortcode=wpapp_history';
			}

			if ($item['id'] == '0') {
				$item['type'] = 'link';
				$item['id'] = home_url('/') . '?wpapp_shortcode=wpapp_recent';
			}

		} else {

			if (preg_match('#^http#', $item['id'])) {

				$id = wpappninja_url_to_catid($item['id']);

				if ($id !== 0) {
					$item['type'] = 'cat';
					$item['id'] = strval($id);
				} else {

					$id = wpappninja_url_to_postid($item['id']);

					if ($id !== 0) {
						$item['type'] = 'page';
						$item['id'] = strval($id);
					}
				}

			}

		}

		
		if (!$doingapi || (trim($item['name']) != '' && $item['id'] != '')) {

			$menu[] = array(
						'id' => $item['id'],
						'name' => $item['name'],
						'type' => $item['type'],
						'icon' => $item['icon'],
						'feat' => $feat
					);
			
		}
	}

	if (get_wpappninja_option('qrcode') == '1' && get_wpappninja_option('speed') != '1') {
		$menu[] = array(
					'id' => "0",
					'name' => __("Scan code", "wpappninja"),
					'type' => "qrcode",
					'icon' => "qrcode",
					'feat' => "0"
				);
	}

	if (get_wpappninja_option('qrcode') == '1' && get_wpappninja_option('speed') == '1' && 1<0) {
		$menu[] = array(
					'id' => home_url( '/' ) . "?wpappqrcode=1",
					'name' => __("Scan code", "wpappninja"),
					'type' => "link",
					'icon' => "camera",
					'feat' => "0"
				);
	}

	if (isset($_SERVER['HTTP_X_WPAPPNINJA_DEMO']) && $_SERVER['HTTP_X_WPAPPNINJA_DEMO'] == "1" && 1<0) {

		$menu[] = array(
				'id' => "/?wpappninjalaunch=",
				'name' => __("Exit", "wpappninja"),
				'type' => "link",
				'icon' => "run",
				'feat' => "1"
			);
	}

	/*$menu[] = array(
				'id' => "-100",
				'name' => __('Notifications', 'wpappninja'),
				'type' => "cat",
				'icon' => "message",
				'feat' => $push_as_feat
			);*/

	return $menu;
}


/*
 * Search for the id by value.
 *
 * @since 1.0
 */
function wpappninja_search_for_id($id, $array) {
	foreach ($array as $key => $val) {
		if ($val['type'].$val['id'] === $id) {
			return $val;
		}
	}
	return null;
}


/**
 * Order menu by weight
 */
function wpappninja_weight_order($menu) {
	
	if (!is_array($menu)) {
		return array();
	}
	
	uasort($menu, function($a, $b) {
					if (!isset($a['weight'])){$a['weight'] = '0';}
					if (!isset($b['weight'])){$b['weight'] = '0';}
					if ($a['weight'] == $b['weight']) {
						return 0;
					}
					return ($a['weight'] < $b['weight']) ? -1 : 1;
				}
			);

	/*if (get_wpappninja_option('speed') == '1') {
	
		uasort($menu, function($a, $b) {

					if ($a['feat'] == $b['feat']) {
						return 0;
					}
					return ($a['feat'] < $b['feat']) ? -1 : 1;
				}
			);
	}*/

	if (!defined('WPMOBILEDONTSLICETHEMENU')) {

		$menu = array_slice($menu, 0, 50);
	}
	
	return $menu;
}

/**
 * Get homepage link
 *
 * @since 6.1.1
 */
function wpappninja_get_home($lang = "") {

	if ($lang == "") {
		$lang = "speed";
	}

	$pages = get_wpappninja_option('pages', array());

	// Use the old menu
	if (count($pages) == 0) {

		$home_id = get_wpappninja_option('pageashome_' . $lang, '');

		if (preg_match('#^http#', $home_id)) {
			return $home_id;
		}

		if ($home_id == "" || $home_id == "cat_0") {
			return get_permalink( get_option( 'page_for_posts' ) );
		}

		if (preg_match('#^cat_#', $home_id)) {
			$cat_id = preg_replace('#^cat_#', '', $home_id);
			
			$taxonomy = wpappninja_get_all_taxonomy();

			foreach ($taxonomy as $tax) {
				$obj = get_term_by('id', $cat_id, $tax);
				if (is_object($obj)) {

					return get_term_link($obj);
					break;
				}
			}
		}

		return get_permalink(intval($home_id));

	}

	foreach($pages as $page) {
		if ($page['menu'] == "home") {

			return $page['link'];
		}
	}

	return get_home_url();
}

/**
 * Get menu pages
 *
 * @since 6.6.6
 */

function wpappninja_get_pages() {

	global $wp;
	$current_url =  add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );
	$current_url = preg_replace('#\?#', '/?', $current_url);
	$current_url = preg_replace('#\/\/\?#', '/?', $current_url);

	$pages = get_wpappninja_option('pages', array());

	// Use the old menu
	if (count($pages) == 0) {
		$pages = wpappninja_get_menu_reloaded('speed');

		$lang = array('en', 'fr', 'it', 'de', 'es', 'pt');
		if (count($pages) == 0) {

			foreach ($lang as $l) {
				$pages = wpappninja_get_menu_reloaded($l);
				if (count($pages) > 0) {
					break;
				}
			}
			
		}

		foreach ($pages as $k => $page) {

			if (!preg_match('/^(\?|\/|http|javascript:|mailto:|geo:|tel:|sms:)/', $page['id'])) {

				if ($page['type'] == "page") {
					if (get_permalink(intval($page['id']))) {
						$pages[$k]['id'] = get_permalink(intval($page['id']));
					} else {
						continue;
					}
				} else if ($page['type'] == "cat") {

					$taxonomy = wpappninja_get_all_taxonomy();

					foreach ($taxonomy as $tax) {
						$obj = get_term_by('id', $page['id'], $tax);
						if (is_object($obj)) {

							$pages[$k]['id'] = get_term_link($obj);
							break;
						}
					}

				} else {
					continue;
				}
			}

			if ($page['icon'] == 'arrow' || $page['icon'] == 'arrowlight' || $page['icon'] == '') {
				$page['icon'] = 'chevron_right';
			}

			$pages[$k]['link'] = $pages[$k]['id'];
			$pages[$k]['label'] = $page['name'];
			if ($page['feat'] == '1') {
				$pages[$k]['menu'] = "tabbar";
			} elseif ($page['feat'] == '2') {
				$pages[$k]['menu'] = "fab";
			} else {
				$pages[$k]['menu'] = "menu";
			}
			$pages[$k]['icon'] = $page['icon'];

			if (get_wpappninja_option('speed_trad') == 'manual') {
				
				$homepage_wpapp = $pages[$k]['id'];
				if ($page['type'] == 'cat') {
					$homepage_wpapp = 'cat_' . $page['id'];
				}

				if (wpappninja_translate($homepage_wpapp) != $homepage_wpapp) {
					$pages[$k]['id'] = wpappninja_translate($homepage_wpapp);
					$pages[$k]['link'] = wpappninja_translate($homepage_wpapp);
				}

				$pages[$k]['label'] = wpappninja_translate($pages[$k]['label']);
			}

			// if current url
			if (trim(wpappninja_cache_friendly($pages[$k]['id']), '/') == trim($current_url, '/')) {
				$pages[$k]['class'] = "wpappninja_make_it_colorfull";
			} else {
				$pages[$k]['class'] = "";
			}
			$pages[$k]['icon'] = preg_replace('#_fill$#', '', $pages[$k]['icon']);
			$pages[$k]['icon_2'] = preg_replace('#_fill$#', '', $pages[$k]['icon']) . "_fill";

			if (preg_match('#wpapp_shortcode=wpapp_home#', $page['id'])) {
				$pages[$k]['link'] = $pages[$k]['link'] . "&" . uniqid();
			}
			$pages[$k]['id'] = $pages[$k]['link'];
		}
	}

	return apply_filters('wpmobileapp_menu_pages', $pages);
}

/**
 * Get http link.
 *
 * @since 7.0.1
 */
function wpappninja_get_http_link($page) {

	if (!preg_match('#^http#', $page['id'])) {

		if ($page['type'] == "page") {
			if (get_permalink(intval($page['id']))) {
				return get_permalink(intval($page['id']));
			}
		} else if ($page['type'] == "cat") {

			$taxonomy = wpappninja_get_all_taxonomy();

			foreach ($taxonomy as $tax) {
				$obj = get_term_by('id', $page['id'], $tax);
				if (is_object($obj)) {
					return get_term_link($obj);
				}
			}

		}
	}

	return $page['id'];
}

/**
 * Icon list
 *
 * @since 7.0.11
 */
function wpappninja_get_icons() {

	if (get_wpappninja_option('speed') == '1') {

		$icons = array (
  'more|ajouter' => 'add',
  1 => 'add_round',
  204 => 'social_facebook',
  2 => 'add_round_fill',
  'clock' => 'alarm',
  4 => 'alarm_fill',
  5 => 'albums',
  6 => 'albums_fill',
  7 => 'arrow',
  8 => 'arrow_down',
  9 => 'arrow_down_fill',
  10 => 'arrow_left',
  11 => 'arrow_left_fill',
  12 => 'arrow_right',
  13 => 'arrow_right_fill',
  14 => 'arrow_up',
  15 => 'arrow_up_fill',
  16 => 'arrowlight',
  'mail' => 'at',
  18 => 'at_fill',
  'shopping|cart|panier|produit' => 'bag',
  20 => 'bag_fill',
  21 => 'bars',
  22 => 'bell',
  23 => 'bell_fill',
  24 => 'bolt',
  25 => 'bolt_fill',
  26 => 'bolt_round',
  27 => 'bolt_round_fill',
  'livre' => 'book',
  29 => 'book_fill',
  30 => 'bookmark',
  31 => 'bookmark_fill',
  'legal' => 'box',
  33 => 'box_fill',
  34 => 'briefcase',
  35 => 'briefcase_fill',
  'calendrier' => 'calendar',
  37 => 'calendar_fill',
  'photo' => 'camera',
  'setting|reglage|parametre|config' => 'gear',
  39 => 'camera_fill',
  'vente|sale|paiement|purchase' => 'card',
  41 => 'card_fill',
  'push|forum|support|notification' => 'chat',
  43 => 'chat_fill',
  44 => 'chats',
  45 => 'chats_fill',
  46 => 'check',
  47 => 'check_round',
  48 => 'check_round_fill',
  49 => 'chevron_down',
  50 => 'chevron_left',
  51 => 'chevron_right',
  52 => 'chevron_up',
  53 => 'circle',
  54 => 'circle_fill',
  55 => 'circle_half',
  56 => 'close',
  57 => 'close_round',
  58 => 'close_round_fill',
  59 => 'cloud',
  'telecharger' => 'cloud_download',
  61 => 'cloud_download_fill',
  62 => 'cloud_fill',
  63 => 'cloud_upload',
  64 => 'cloud_upload_fill',
  65 => 'collection',
  66 => 'collection_fill',
  'maps|itineraire|gps|geo:' => 'compass',
  68 => 'compass_fill',
  69 => 'compose',
  70 => 'compose_fill',
  71 => 'data',
  72 => 'data_fill',
  'supprimer' => 'delete',
  74 => 'delete_round',
  75 => 'delete_round_fill',
  76 => 'document',
  77 => 'document_fill',
  78 => 'document_text',
  79 => 'document_text_fill',
  80 => 'down',
  'telecharger' => 'download',
  82 => 'download_fill',
  83 => 'download_round',
  84 => 'download_round_fill',
  85 => 'drawer',
  86 => 'drawer_fill',
  87 => 'drawers',
  88 => 'drawers_fill',
  'mail|contact' => 'email',
  90 => 'email_fill',
  91 => 'eye',
  92 => 'eye_fill',
  93 => 'fastforward',
  94 => 'fastforward_fill',
  95 => 'fastforward_round',
  96 => 'fastforward_round_fill',
  97 => 'favorites',
  98 => 'favorites_fill',
  'cinema' => 'film',
  100 => 'film_fill',
  101 => 'filter-fill',
  102 => 'filter',
  103 => 'flag',
  104 => 'flag_fill',
  'dossier' => 'folder',
  106 => 'folder_fill',
  107 => 'forward',
  108 => 'forward_fill',
  110 => 'gear_fill',
  111 => 'graph_round',
  112 => 'graph_round_fill',
  113 => 'graph_square',
  114 => 'graph_square_fill',
  115 => 'heart',
  116 => 'heart_fill',
  'aide' => 'help',
  118 => 'help_fill',
  'accueil' => 'home',
  120 => 'home_fill',
  121 => 'images',
  122 => 'images_fill',
  123 => 'info',
  124 => 'info_fill',
  125 => 'keyboard',
  126 => 'keyboard_fill',
  127 => 'layers',
  128 => 'layers_fill',
  129 => 'left',
  130 => 'list',
  131 => 'list_fill',
  132 => 'lock',
  133 => 'lock_fill',
  'connexion' => 'login',
  135 => 'login_fill',
  'deconnexion' => 'logout',
  137 => 'logout_fill',
  138 => 'menu',
  139 => 'mic',
  140 => 'mic_fill',
  'price|pricing' => 'money_dollar',
  142 => 'money_dollar_fill',
  'prix|tarif' => 'money_euro',
  144 => 'money_euro_fill',
  145 => 'money_pound',
  146 => 'money_pound_fill',
  147 => 'money_rubl',
  148 => 'money_rubl_fill',
  149 => 'money_yen',
  150 => 'money_yen_fill',
  151 => 'more',
  152 => 'more_fill',
  153 => 'more_round',
  154 => 'more_round_fill',
  155 => 'more_vertical',
  156 => 'more_vertical_fill',
  157 => 'more_vertical_round',
  158 => 'more_vertical_round_fill',
  159 => 'navigation',
  160 => 'navigation_fill',
  161 => 'paper_plane',
  162 => 'paper_plane_fill',
  163 => 'pause',
  164 => 'pause_fill',
  165 => 'pause_round',
  166 => 'pause_round_fill',
  'compte|account' => 'person',
  168 => 'person_fill',
  'partner|partenaire' => 'persons',
  170 => 'persons_fill',
  'telephone|tel:' => 'phone',
  172 => 'phone_fill',
  173 => 'phone_round',
  174 => 'phone_round_fill',
  175 => 'photos',
  176 => 'photos_fill',
  177 => 'pie',
  178 => 'pie_fill',
  179 => 'play',
  180 => 'play_fill',
  181 => 'play_round',
  182 => 'play_round_fill',
  183 => 'radio',
  184 => 'redo',
  185 => 'refresh',
  186 => 'refresh_round',
  187 => 'refresh_round_fill',
  188 => 'reload',
  189 => 'reload_round',
  190 => 'reload_round_fill',
  191 => 'reply',
  192 => 'reply_fill',
  193 => 'rewind',
  194 => 'rewind_fill',
  195 => 'rewind_round',
  196 => 'rewind_round_fill',
  197 => 'right',
  'recherche' => 'search',
  199 => 'search_strong',
  'setting|reglage|parametre|config' => 'settings',
  201 => 'settings_fill',
  'partage|social' => 'share',
  203 => 'share_fill',
  205 => 'social_facebook_fill',
  206 => 'social_github',
  207 => 'social_github_fill',
  'google' => 'social_googleplus',
  209 => 'social_instagram',
  210 => 'social_instagram_fill',
  211 => 'social_linkedin',
  212 => 'social_linkedin_fill',
  213 => 'social_rss',
  214 => 'social_rss_fill',
  215 => 'social_twitter',
  216 => 'social_twitter_fill',
  217 => 'sort',
  218 => 'sort_fill',
  219 => 'star',
  220 => 'star_fill',
  221 => 'star_half',
  222 => 'stopwatch',
  223 => 'stopwatch_fill',
  224 => 'tabs',
  225 => 'tabs_fill',
  226 => 'tags',
  227 => 'tags_fill',
  228 => 'tape',
  229 => 'tape_fill',
  230 => 'ticket',
  231 => 'ticket_fill',
  'recent' => 'time',
  233 => 'time_fill',
  234 => 'timer',
  235 => 'timer_fill',
  236 => 'today',
  237 => 'today_fill',
  238 => 'trash',
  239 => 'trash_fill',
  240 => 'tune',
  241 => 'tune_fill',
  242 => 'undo',
  243 => 'unlock',
  244 => 'unlock_fill',
  245 => 'up',
  'video' => 'videocam',
  247 => 'videocam_fill',
  248 => 'videocam_round',
  249 => 'videocam_round_fill',
  'musique|music' => 'volume',
  251 => 'volume_fill',
  252 => 'volume_low',
  253 => 'volume_low_fill',
  254 => 'volume_mute',
  255 => 'volume_mute_fill',
  'feature|fonctionnalite' => 'world',
  257 => 'world_fill',
  258 => 'zoom_in',
  259 => 'zoom_out',
);
	} else {

		$icons = array (
  0 => 'airplane',
  1 => 'alert',
  2 => 'amazon',
  3 => 'android',
  4 => 'arrow',
  'recent' => 'arrowlight',
  6 => 'beach',
  7 => 'bike',
  8 => 'braces',
  9 => 'calc',
  10 => 'calendar',
  11 => 'car',
  'prix|price' => 'card',
  'panier|shopping|cart|buy|produit|boutique|shop' => 'cartfull',
  14 => 'cat',
  15 => 'charts',
  16 => 'cloud',
  17 => 'coeur',
  18 => 'cross',
  19 => 'diamond',
  20 => 'doc',
  'telecharger' => 'download',
  22 => 'dumbbell',
  23 => 'facebook',
  24 => 'ferry',
  25 => 'film',
  26 => 'food',
  27 => 'gift',
  28 => 'github',
  29 => 'google',
  30 => 'googleplus',
  31 => 'hanger',
  32 => 'headphones',
  33 => 'hotel',
  34 => 'ic_folder',
  35 => 'idea',
  36 => 'instagram',
  37 => 'linkedin',
  38 => 'love',
  'chat|forum|contact' => 'mail',
  'home|accueil' => 'maison',
  'geo:|navigation' => 'map',
  42 => 'martini',
  43 => 'medic',
  'notification|support' => 'message',
  45 => 'motorbike',
  46 => 'paypal',
  'tel:|telephon' => 'phone',
  'image' => 'photo',
  49 => 'pic',
  50 => 'pine',
  51 => 'prez',
  52 => 'pricee',
  53 => 'qrcode',
  54 => 'run',
  55 => 'sale',
  56 => 'school',
  57 => 'security',
  'social' => 'share',
  59 => 'silverware',
  60 => 'soccer',
  61 => 'song',
  62 => 'star',
  63 => 'tagg',
  64 => 'taggue',
  65 => 'tags',
  66 => 'taxi',
  67 => 'theme',
  68 => 'train',
  69 => 'tripadvisor',
  70 => 'twitch',
  71 => 'twitter',
  'check' => 'valid',
  73 => 'video',
  'acheter|buy' => 'visa',
  75 => 'wordpress',
  76 => 'world',
  77 => 'wrench',
  78 => 'write',
  79 => 'youtube',
);
	}

	return $icons;
}

/**
 * Auto select icon.
 *
 * @since 4.3.1
 */
function wpappninja_auto_select_icon($search) {

	if ($search == "") {
		if (get_wpappninja_option('speed') == '1') {
			return "chevron_right";
		} else {
			return "arrow";
		}
	}

	$icons = wpappninja_get_icons();
	$slug = wpappninja_slugify($search);

	foreach ($icons as $regex => $icon) {
		$check = preg_replace('#_fill|money_|social_|chevron_|cloud_|arrow_|_round|_in|_out#', '', $icon);

		if (!is_int($regex) && $regex != "") {
    		$check .= "|" . $regex;
    	} else {
    		$regex = "";
    	}

		if (preg_match("/$check/i", $slug) && (strlen($check) - strlen($regex)) > 3) {
			return $icon;
		}
	}

	if (get_wpappninja_option('speed') == '1') {
		return "chevron_right";
	} else {
		return "arrow";
	}
}

/**
 * Remove accent.
 * Thanks http://www.weirdog.com/blog/php/supprimer-les-accents-des-caracteres-accentues.html
 *
 * @since 4.3.1
 */
function wpappninja_slugify($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}
