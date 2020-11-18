<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Return the current language
 *
 * @since 3.0.4
 */
function wpappninja_get_lang($type = 'short') {

	if (get_wpappninja_option('speed') == '1' || 1>0) {

		if (isset($_POST['WPAPPNINJA_LOCALE']) && strlen($_POST['WPAPPNINJA_LOCALE']) == 2) {
			$_POST['WPAPPNINJA_LOCALE'] = $_POST['WPAPPNINJA_LOCALE'] . '_' . strtoupper($_POST['WPAPPNINJA_LOCALE']);

			if ($_POST['WPAPPNINJA_LOCALE'] == 'en_EN') {
				$_POST['WPAPPNINJA_LOCALE'] = 'en_US';
			}
		}

		if (isset($_COOKIE['WPAPPNINJA_LOCALE']) && strlen($_COOKIE['WPAPPNINJA_LOCALE']) == 2) {
			$_COOKIE['WPAPPNINJA_LOCALE'] = $_COOKIE['WPAPPNINJA_LOCALE'] . '_' . strtoupper($_COOKIE['WPAPPNINJA_LOCALE']);

			if ($_COOKIE['WPAPPNINJA_LOCALE'] == 'en_EN') {
				$_COOKIE['WPAPPNINJA_LOCALE'] = 'en_US';
			}
		}

		if (isset($_POST['WPAPPNINJA_LOCALE']) && $type == 'short') {
			return apply_filters('wpmobile_get_lang', substr($_POST['WPAPPNINJA_LOCALE'], 0, 2));
		} elseif (isset($_POST['WPAPPNINJA_LOCALE']) && $type != 'short') {
			return apply_filters('wpmobile_get_lang', $_POST['WPAPPNINJA_LOCALE']);
		}

		if (isset($_COOKIE['WPAPPNINJA_LOCALE']) && $type == 'short') {
			return apply_filters('wpmobile_get_lang', substr($_COOKIE['WPAPPNINJA_LOCALE'], 0, 2));
		} elseif (isset($_COOKIE['WPAPPNINJA_LOCALE']) && $type != 'short') {
			return apply_filters('wpmobile_get_lang', $_COOKIE['WPAPPNINJA_LOCALE']);
		}

		if (!isset($_SERVER['HTTP_X_WPAPPNINJA_LOCALE'])) {

			$lang = "en";
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			}
		} else {
			$lang = substr($_SERVER['HTTP_X_WPAPPNINJA_LOCALE'], 0, 2);
		}

		if ($type == 'short') {

			if ($lang == "") {
				return apply_filters('wpmobile_get_lang', "en");
			}

			return apply_filters('wpmobile_get_lang', $lang);
		} else {

			if ($lang == "") {
				return apply_filters('wpmobile_get_lang', "en_US");
			}
			
			return apply_filters('wpmobile_get_lang', $lang.'_'.strtoupper($lang));
		}
	}
	
	$available = wpappninja_available_lang();
	
	$return = array(
					'en' => array('short' => 'en', 'long' => 'en_US'),
					'fr' => array('short' => 'fr', 'long' => 'fr_FR'),
					'es' => array('short' => 'es', 'long' => 'es_ES'),
					'de' => array('short' => 'de', 'long' => 'de_DE'),
					'it' => array('short' => 'it', 'long' => 'it_IT'),
					'pt' => array('short' => 'pt', 'long' => 'pt_PT'),
				);

	if (isset($_SERVER['HTTP_X_WPAPPNINJA_LOCALE'])) {
		if (preg_match('#fr#', $_SERVER['HTTP_X_WPAPPNINJA_LOCALE']) && isset($available['Français'])) {
			return $return['fr'][$type];
		} else if (preg_match('#pt#', $_SERVER['HTTP_X_WPAPPNINJA_LOCALE']) && isset($available['Português'])) {
			return $return['pt'][$type];
		} else if (preg_match('#de#', $_SERVER['HTTP_X_WPAPPNINJA_LOCALE']) && isset($available['Deutsch'])) {
			return $return['de'][$type];
		} else if (preg_match('#it#', $_SERVER['HTTP_X_WPAPPNINJA_LOCALE']) && isset($available['Italiano'])) {
			return $return['it'][$type];
		} else if (preg_match('#es#', $_SERVER['HTTP_X_WPAPPNINJA_LOCALE']) && isset($available['Español'])) {
			return $return['es'][$type];
		} else if (preg_match('#en#', $_SERVER['HTTP_X_WPAPPNINJA_LOCALE']) && isset($available['English'])) {
			return $return['en'][$type];
		}
	}

	$available = array_values($available);
	return $return[$available[0]][$type];
}
    
function wpappninja_localeko($v) {
        
    $ko = get_wpappninja_option('localeko', array());
    
    if (in_array($v, $ko)) {
        return false;
    }
    
    return true;
}

/**
 * Get the current possible languages.
 *
 * @since 3.6.4
 */
function wpappninja_available_lang($force_all = false) {

	if (get_wpappninja_option('speed') == '1' || 1>0) {
		global $wpdb;
		$langs = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT `lang` FROM {$wpdb->prefix}wpappninja_stats_users WHERE `lang` != %s", ""));
		
		$all = array();

		$names = json_decode('{"TE": "Tetun", "EN": "English", "EU": "Basque", "BD": "Bangladesh", "BE": "Belgium", "BF": "Burkina Faso", "BA": "Bosnia and Herzegovina", "BB": "Barbados", "WF": "Wallis and Futuna", "BM": "Bermuda", "BN": "Brunei", "BO": "Bolivia", "BH": "Bahrain", "BI": "Burundi", "BJ": "Benin", "BT": "Bhutan", "JM": "Jamaica", "BV": "Bouvet Island", "BW": "Botswana", "WS": "Samoa", "BR": "Brazil", "BS": "Bahamas", "BY": "Belarus", "BZ": "Belize", "RU": "Russia", "RW": "Rwanda", "RS": "Serbia", "TL": "East Timor", "RE": "Reunion", "TM": "Turkmenistan", "TJ": "Tajikistan", "RO": "Romania", "TK": "Tokelau", "GW": "Guinea-Bissau", "GU": "Guam", "GT": "Guatemala", "GS": "South Georgia and the South Sandwich Islands", "GR": "Greece", "GQ": "Equatorial Guinea", "GP": "Guadeloupe", "JP": "Japan", "GY": "Guyana", "GF": "French Guiana", "GE": "Georgia", "GD": "Grenada", "GB": "United Kingdom", "GA": "Gabon", "SV": "El Salvador", "GN": "Guinea", "GM": "Gambia", "GL": "Greenland", "GI": "Gibraltar", "GH": "Ghana", "OM": "Oman", "TN": "Tunisia", "JO": "Jordan", "HR": "Croatia", "HT": "Haiti", "HU": "Hungary", "HK": "Hong Kong", "HN": "Honduras", "HM": "Heard Island and McDonald Islands", "VE": "Venezuela", "PR": "Puerto Rico", "PS": "Palestinian Territory", "PW": "Palau", "PT": "Portugal", "SJ": "Svalbard and Jan Mayen", "PY": "Paraguay", "IQ": "Iraq", "PA": "Panama", "PF": "French Polynesia", "PG": "Papua New Guinea", "PE": "Peru", "PK": "Pakistan", "PH": "Philippines", "PN": "Pitcairn", "PL": "Poland", "PM": "Saint Pierre and Miquelon", "ZM": "Zambia", "EH": "Western Sahara", "EE": "Estonia", "EG": "Egypt", "ZA": "South Africa", "EC": "Ecuador", "IT": "Italy", "VN": "Vietnam", "SB": "Solomon Islands", "ET": "Ethiopia", "SO": "Somalia", "ZW": "Zimbabwe", "SA": "Saudi Arabia", "ES": "Spain", "ER": "Eritrea", "ME": "Montenegro", "MD": "Moldova", "MG": "Madagascar", "MA": "Morocco", "MC": "Monaco", "UZ": "Uzbekistan", "MM": "Myanmar", "ML": "Mali", "MO": "Macao", "MN": "Mongolia", "MH": "Marshall Islands", "MK": "Macedonia", "MU": "Mauritius", "MT": "Malta", "MW": "Malawi", "MV": "Maldives", "MQ": "Martinique", "MP": "Northern Mariana Islands", "MS": "Montserrat", "MR": "Mauritania", "UG": "Uganda", "TZ": "Tanzania", "MY": "Malaysia", "MX": "Mexico", "IL": "Israel", "FR": "France", "IO": "British Indian Ocean Territory", "SH": "Saint Helena", "FI": "Finland", "FJ": "Fiji", "FK": "Falkland Islands", "FM": "Micronesia", "FO": "Faroe Islands", "NI": "Nicaragua", "NL": "Netherlands", "NO": "Norway", "NA": "Namibia", "VU": "Vanuatu", "NC": "New Caledonia", "NE": "Niger", "NF": "Norfolk Island", "NG": "Nigeria", "NZ": "New Zealand", "NP": "Nepal", "NR": "Nauru", "NU": "Niue", "CK": "Cook Islands", "CI": "Ivory Coast", "CH": "Switzerland", "CO": "Colombia", "CN": "China", "CM": "Cameroon", "CL": "Chile", "CC": "Cocos Islands", "CA": "Canada", "CG": "Republic of the Congo", "CF": "Central African Republic", "CD": "Democratic Republic of the Congo", "CZ": "Czech Republic", "CY": "Cyprus", "CX": "Christmas Island", "CR": "Costa Rica", "CV": "Cape Verde", "CU": "Cuba", "SZ": "Swaziland", "SY": "Syria", "KG": "Kyrgyzstan", "KE": "Kenya", "SR": "Suriname", "KI": "Kiribati", "KH": "Cambodia", "KN": "Saint Kitts and Nevis", "KM": "Comoros", "ST": "Sao Tome and Principe", "SK": "Slovakia", "KR": "South Korea", "SI": "Slovenia", "KP": "North Korea", "KW": "Kuwait", "SN": "Senegal", "SM": "San Marino", "SL": "Sierra Leone", "SC": "Seychelles", "KZ": "Kazakhstan", "KY": "Cayman Islands", "SG": "Singapore", "SE": "Sweden", "SD": "Sudan", "DO": "Dominican Republic", "DM": "Dominica", "DJ": "Djibouti", "DK": "Denmark", "VG": "British Virgin Islands", "DE": "Germany", "YE": "Yemen", "DZ": "Algeria", "US": "United States", "UY": "Uruguay", "YT": "Mayotte", "UM": "United States Minor Outlying Islands", "LB": "Lebanon", "LC": "Saint Lucia", "LA": "Laos", "TV": "Tuvalu", "TW": "Taiwan", "TT": "Trinidad and Tobago", "TR": "Turkey", "LK": "Sri Lanka", "LI": "Liechtenstein", "LV": "Latvia", "TO": "Tonga", "LT": "Lithuania", "LU": "Luxembourg", "LR": "Liberia", "LS": "Lesotho", "TH": "Thailand", "TF": "French Southern Territories", "TG": "Togo", "TD": "Chad", "TC": "Turks and Caicos Islands", "LY": "Libya", "VA": "Vatican", "VC": "Saint Vincent and the Grenadines", "AE": "United Arab Emirates", "AD": "Andorra", "AG": "Antigua and Barbuda", "AF": "Afghanistan", "AI": "Anguilla", "VI": "U.S. Virgin Islands", "IS": "Iceland", "IR": "Iran", "AM": "Armenia", "AL": "Albania", "AO": "Angola", "AS": "American Samoa", "AR": "Argentina", "AU": "Australia", "AT": "Austria", "AW": "Aruba", "IN": "India", "AX": "Aland Islands", "AZ": "Azerbaijan", "IE": "Ireland", "ID": "Indonesia", "UA": "Ukraine", "QA": "Qatar", "MZ": "Mozambique"}', TRUE);

        if ($force_all === true) {
            
                asort($names);
                foreach ( $names as $k => $v ) {
                    $l_upper = strtoupper($k);
                    $name = $names[$l_upper];

                    $all[$name] = strtolower($k);
                }
            } elseif (!$force_all || $force_all == "all") {
			foreach ( $langs as $l ) {
				$l_upper = strtoupper($l->lang);

				if (isset($names[$l_upper])) {
					$name = $names[$l_upper];
				} else {
					$name = $l_upper;
				}

				$all[$name] = $l->lang;
			}
		}else{
            asort($names);
            foreach ( $names as $k => $v ) {
                $l_upper = strtoupper($k);
                $name = $names[$l_upper];

                $all[$name] = strtolower($k);
            }
        }

		if (count($all) == 0) {
			$all['en'] = 'English';
		}
        
        if ($force_all != "all") {
            $all = array_filter($all, "wpappninja_localeko");
        }
        
		return $all;
	}


	$lang = array('English' => 'en', 'Français' => 'fr', 'Deutsch' => 'de', 'Español' => 'es', 'Italiano' => 'it', 'Português' => 'pt');
	$code = array('en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch', 'es' => 'Español', 'it' => 'Italiano', 'pt' => 'Português');
	
	if (!$force_all) {
		$return = array();
		
		$lang_exclude = get_wpappninja_option('lang_exclude', array());
		foreach($lang_exclude as $exclude) {
			$return[$code[$exclude]] = $exclude;
		}
		
		if (count($return) == 0) {
			$return['English'] = 'en';
		}
		
		return $return;
	}

	return $lang;
}

function wpmobile_weglot($url) { 
	if (wpappninja_get_lang() != get_wpappninja_option('weglot_original', '') && get_wpappninja_option('speed_trad') == 'weglot') {
      $url = substr_replace($url, "/" . apply_filters('wpmobile_weglot_default', wpappninja_get_lang()) . "/", wpappninja_get_pos("/", $url, 3), 1);
    }

    return $url;
}

function wpmobile_getSupportLang() {

	$lang = 'en';

	if (substr(get_locale(), 0,2) == 'fr') {
		$lang = 'fr';
	}

	return $lang;
}


/** WEGLOT **/
add_action('init', 'wpappninja_weglot');
function wpappninja_weglot() {

	if (is_wpappninja() && get_wpappninja_option('speed_trad') == 'weglot' && !class_exists('\Weglot\WG') && get_wpappninja_option('weglot_apikey', '') != '') {

		wpappninja_stats_log("weglot", 1);

		$lang = "";
		foreach(wpappninja_available_lang() as $name => $code) {
			if ($code != get_wpappninja_option('weglot_original', '') && $code != ""){
				$lang .= $code.',';
			} 
		}

		require_once WPAPPNINJA_PATH . 'weglot/weglot.php';

		\Weglot\WG::Instance(array(
			"api_key" => get_wpappninja_option('weglot_apikey', ''),
			"original_l" => get_wpappninja_option('weglot_original', ''),
			"destination_l" => apply_filters('wpmobile_weglot_destination', trim($lang, ',')),
			"buttonOptions" => array("fullname"=>true,"with_name"=>true,"is_dropdown"=>false,"with_flags"=>true,"type_flags"=>3),
			"exclude_blocks" => ".f7-icons,.notranslate,.no-translate,.wg-notranslate",
			"exclude_urls" => apply_filters('wpmobile_weglot_exclude_url', ''),
		));
	}
}
