<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Human readable value.
 *
 * @since 4.0
 */
function wpmobile_get_shortcode_name($slug) {

	switch ($slug) {
		case 'wpapp_qrcode':
			$slug = __('QRCode Scanner', 'wpappninja');
			break;

		case 'wpapp_history':
			$slug = __('Push history', 'wpappninja');
			break;
		
		case 'wpapp_config':
		case 'wpapp_push':
			$slug = __('Push configuration', 'wpappninja');
			break;
		
		case 'wpapp_home':
			$slug = __('Custom homepage', 'wpappninja');
			break;
		
		case 'wpapp_recent':
			$slug = __('Recent posts', 'wpappninja');
			break;
		
		case 'wpapp_login':
			$slug = __('Login', 'wpappninja');
			break;
	}

	return $slug;
}

function wpappninja_stats_human($action, $value) {
	
	if ($value === 0 || $value == "") {		
		return __('(not set)', 'wpappninja');
	}

	switch ($action) {
		
		case 'push':
			global $wpdb;
			$query = $wpdb->get_results($wpdb->prepare("SELECT `titre` FROM {$wpdb->prefix}wpappninja_push WHERE `id` = %s", $value));
			return (isset($query[0]->titre)) ? $query[0]->titre : __('Deleted notification', 'wpappninja');
			
		case 'comment':
		case 'read':
			return (get_the_title($value) != "") ? get_the_title($value) : wpmobile_get_shortcode_name($value);
			
		case 'bycat':
			return (get_cat_name($value) != "") ? get_cat_name($value) : $value;
		
		case 'recent':
			return __('Recent posts', 'wpappninja');
			
		case 'form':
			if (class_exists('GFAPI')) {
				$form = GFAPI::get_form( $value );
				return $form['title'];
			}
		
		case 'install':
			return ucfirst(__('install', 'wpappninja'));
	}
	
	return $value;
}

/**
 * Label name.
 *
 * @since 4.0
 */
function wpappninja_stats_human_title($action) {

	switch ($action) {
		
		case 'push':
			return __('notification', 'wpappninja');
			
		case 'comment':
			return __('comment', 'wpappninja');
			
		case 'read':
			return __('screen', 'wpappninja');
			
		case 'bycat':
			return __('category', 'wpappninja');
		
		case 'recent':
			return __('recent post', 'wpappninja');
			
		case 'form':	
			return __('form', 'wpappninja');
			
		case 'search':
			return __('search', 'wpappninja');

		case 'install':
			return __('install', 'wpappninja');

		case 'login':
			return __('login', 'wpappninja');

		case 'signup':
			return __('register', 'wpappninja');
	}

	return $action;
}

/**
 * Dashicon for action.
 *
 * @since 4.0
 */
function wpappninja_stats_dashicon($action) {

	switch ($action) {
		
		case 'push':
			return 'megaphone';
			
		case 'comment':
			return 'edit';
			
		case 'read':
			return 'media-document';
			
		case 'bycat':
			return 'category';
		
		case 'recent':
			return 'clock';
			
		case 'form':	
			return 'feedback';
			
		case 'search':
			return 'search';

		case 'install':
			return 'download';

		case 'login':
			return 'admin-users';

		case 'signup':
			return 'yes';
	}
	
	return 'arrow-right-alt';
}

/**
 * Color of action name.
 *
 * @since 4.0
 */
function wpappninja_stats_color($action, $border = FALSE) {

	switch ($action) {
		case 'push':
			return ($border) ? '74a040' : '8bc34a';
			
		case 'comment':
			return ($border) ? '45a062' : '4caf50';
			
		case 'read':
			return ($border) ? '088fa0' : '00BCD4';

		case 'bycat':
			return ($border) ? '2d72a9' : '2196f3';
			
		case 'recent':
			return ($border) ? 'bf7200' : 'FF9800';

		case 'signup':
			return ($border) ? '2196f3': '2196f3';

		case 'login':
		case 'form':
			return ($border) ? '841a96' : '9c27b0';

		case 'search':
			return ($border) ? '4e6773' : '607d8b';
			
		case 'install':
			return ($border) ? '056909' : '00a506';
	}

	return ($border) ? '8A8A8A' : 'B7B7B7';
}

/**
 * Defer the render of the admin js.
 * 
 * @since 4.0.6
 */
add_action( 'wpappninja_admin_footer', 'wpappninja_chart_js' );
function wpappninja_chart_js() {
	global $wpdb;
	
	$date			= round(current_time('timestamp') - get_wpappninja_option('stats_second', 2592000));
	
	switch (get_wpappninja_option('stats_platform', -1)) {
		case -1:
			$platform = '';
			break;
		case 0:
			$platform = 'android';
			break;
		case 1:
			$platform = 'ios';
			break;
	}
	$segment		= "date > $date AND platform != '$platform'";
	
	if (get_wpappninja_option('stats_lang', '') != '') {
		$segment .= " AND lang = '" . get_wpappninja_option('stats_lang') . "'";
	}
	
	$graph = array();
	$query	= $wpdb->get_results("SELECT SUM(nb) as nb, action, date, YEAR(FROM_UNIXTIME(date)) as y, MONTH(FROM_UNIXTIME(date)) as m, DAY(FROM_UNIXTIME(date)) as d FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE $segment GROUP BY s.action, YEAR(FROM_UNIXTIME(date)), MONTH(FROM_UNIXTIME(date)), DAY(FROM_UNIXTIME(date)) ORDER BY date ASC");
	foreach($query as $obj) {

		$d = $obj->d.'-'.$obj->m;

		if (!isset($graph[$d])) {
			$graph[$d] = array(
							'push' => 0,
							'comment' => 0,
							'read' => 0,
							'bycat' => 0,
							'recent' => 0,
							'form' => 0,
							'search' => 0,
							'install' => 0,
							'login' => 0,
							'signup' => 0,
						);
		}
			
		if (isset($graph[$d][$obj->action])) {
			$graph[$d][$obj->action] = round($obj->nb);
		}
	}
		
	$xaxis = '';
	$push = '';
	$comment = '';
	$read = '';
	$bycat = '';
	$recent = '';
	$form = '';
	$search = '';
	$install = '';
	$login = '';
	$signup = '';
	foreach ($graph as $day => $action) {
		$xaxis .= "'" .$day."',";
		$push .= $action['push'].',';
		$comment .= $action['comment'].',';
		$read .= $action['read'].',';
		$bycat .= $action['bycat'].',';
		$recent .= $action['recent'].',';
		$form .= $action['form'].',';
		$search .= $action['search'].',';
		$install .= $action['install'].',';
		$login .= $action['login'].',';
		$signup .= $action['signup'].',';
	}
	$xaxis = trim($xaxis, ',');
	$push = trim($push, ',');
	$comment = trim($comment, ',');
	$read = trim($read, ',');
	$bycat = trim($bycat, ',');
	$recent = trim($recent, ',');
	$form = trim($form, ',');
	$search = trim($search, ',');
	$install = trim($install, ',');
	$login = trim($login, ',');
	$signup = trim($signup, ',');
		
	$yaxis = "{name: '" . ucfirst(wpappninja_stats_human_title('push')) . "', data: [$push]},
	{name: '" . ucfirst(wpappninja_stats_human_title('comment')) . "', data: [$comment]},
	{name: '" . ucfirst(wpappninja_stats_human_title('read')) . "', data: [$read]},
	{name: '" . ucfirst(wpappninja_stats_human_title('bycat')) . "', data: [$bycat]},
	{name: '" . ucfirst(wpappninja_stats_human_title('recent')) . "', data: [$recent]},
	{name: '" . ucfirst(wpappninja_stats_human_title('form')) . "', data: [$form]},
	{name: '" . ucfirst(wpappninja_stats_human_title('search')) . "', data: [$search]},
	{name: '" . ucfirst(wpappninja_stats_human_title('install')) . "', data: [$install]},
	{name: '" . ucfirst(wpappninja_stats_human_title('login')) . "', data: [$login]},
	{name: '" . ucfirst(wpappninja_stats_human_title('signup')) . "', data: [$signup]}";

	if (get_wpappninja_option('speed') == '1') {
		$yaxis = "{name: '" . ucfirst(wpappninja_stats_human_title('install')) . "', data: [$install]},
	{name: '" . ucfirst(wpappninja_stats_human_title('login')) . "', data: [$login]},
	{name: '" . ucfirst(wpappninja_stats_human_title('signup')) . "', data: [$signup]},
	{name: '" . ucfirst(wpappninja_stats_human_title('read')) . "', data: [$read]},";
	}

	echo "<script type=\"text/javascript\">jQuery(function () {jQuery('#wpappninja_stats_graph_holder').highcharts({colors: ['#4caf50','#9c27b0', '#2196f3', '#00BCD4',  '#FF9800', '#607d8b'],credits: {  enabled: false  },chart: {type: 'column'},title: {text: '" . __('App activity', 'wpappninja') . "'},xAxis: {categories: [" . $xaxis . "],crosshair: true},yAxis: {min: 0,title: {text: '" . __('By day', 'wpappninja') . "'}},tooltip: {headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +'<td style=\"padding:0\"><b>{point.y:.0f}</b></td></tr>',footerFormat: '</table>',shared: true,useHTML: true},plotOptions: {column: {pointPadding: 0.2,borderWidth: 0}},series: [" . $yaxis . "]});});</script>";
}
