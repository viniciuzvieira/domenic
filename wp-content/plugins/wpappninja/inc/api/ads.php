<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Inject ads
 *
 * @since 4.4
 */
add_filter( 'the_content', 'wpappninja_adserver_post', 100 );
function wpappninja_adserver_post($content) {

	if (isset($_SERVER['HTTP_X_WPAPPNINJA'])) {
		$content = get_wpappninja_option('beforepost', '') . $content . get_wpappninja_option('afterpost', '');
	}

	if (get_wpappninja_option('injectads', '1') == '0') {
		return $content;
	}

	$top = "";
	$bottom = "";

	if (isset($_SERVER['HTTP_X_WPAPPNINJA']) || defined( 'WPAPPNINJA_READ_ENHANCED' ) || defined( 'DOING_WPAPPNINJA_API' )) {

		$top = wpappninja_get_ads('top');
		$bottom = wpappninja_get_ads('bottom');
	}

	return $top . $content . $bottom;
}

function wpappninja_get_ads($format = "") {

	global $wpdb;

	$return = "";

	$query = $wpdb->get_results($wpdb->prepare("SELECT `html`, `id`, `logo`, `title`, `text`, `color`, `link` FROM {$wpdb->prefix}wpappninja_adserver WHERE `start` < %d AND `stop` > %d AND `lang` = %s ORDER BY `display` ASC LIMIT 1", current_time( 'timestamp' ), current_time( 'timestamp' ),  wpappninja_get_lang()));
	foreach ($query as $obj) {

		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_adserver SET `display` = `display` + 1 WHERE `id` = %s", $obj->id));

		if ($obj->html != "") {
			$return .= $obj->html;

		} else {

			if ($obj->link != "") {
				$link = "https://my.wpmobile.app/adserver/r.php?i=" . $obj->id . "&h=" . rawurlencode(home_url( '/' )) . "&d=" . rawurlencode($obj->link);
				$return .= '<a href="' . $link . '" target="_top" style="text-decoration:none">';
				if (get_wpappninja_option('webview') != '0') {$return .= '<p style="cursor:pointer;border:1px solid #ddd;margin:10px 0;padding:15px;text-align:center;font-size:19px;">';}
			} elseif (get_wpappninja_option('webview') != '0') {
				$return .= '<a href="#" onclick="return false" style="text-decoration:none">';
				$return .= '<p style="border:1px solid #ddd;padding:15px;text-align:center;font-size:19px;">';
			}
			if ($obj->logo != "") {$return .= '<img style="margin:4px;width:90px;" src="'.$obj->logo.'" /><br/>';}
			$return .= '<b style="font-size:24px;color:'.$obj->color.'">'.stripslashes($obj->title).'</b><br/>
			'.stripslashes($obj->text);
			$return .= '<br/><span style="float:right;color:gray;font-size:11px;">Ads</span>';
			if (get_wpappninja_option('webview') != '0') {
				$return .= '<span style="clear:both;display:block"></span>';
				$return .= "</p>";
			}
			$return .= '</a>';
		}
	}

	return $return;
}

function wpappninja_adserver_click($id) {
	global $wpdb;
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_adserver SET `click` = `click` + 1 WHERE `id` = %s", $id));
}
