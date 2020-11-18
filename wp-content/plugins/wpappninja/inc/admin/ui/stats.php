<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Show statistics.
 *
 * @since 4.0
 */
function _wpappninja_display_stats_page() {
	global $wpdb;

	if (isset($_POST['wpappninja_delete_stats']) && check_admin_referer( 'wpappninja-delete-stats' )) {
        
        if ($_POST['wpappninja_delete_stats'] == "0") {
            $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_stats");
        } elseif ($_POST['wpappninja_delete_stats'] == "30") {
            
            $deleterange = round(current_time('timestamp') - 30*86400);
            $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_stats WHERE date < $deleterange");
        } elseif ($_POST['wpappninja_delete_stats'] == "30") {
                   
                   $deleterange = round(current_time('timestamp') - 30*86400);
                   $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_stats WHERE date < $deleterange");
               } elseif ($_POST['wpappninja_delete_stats'] == "90") {
                          
                          $deleterange = round(current_time('timestamp') - 90*86400);
                          $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_stats WHERE date < $deleterange");
                      } elseif ($_POST['wpappninja_delete_stats'] == "365") {
                                 
                                 $deleterange = round(current_time('timestamp') - 365*86400);
                                 $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_stats WHERE date < $deleterange");
                             }
        
        //$wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_stats_users");
	}
	
	if (isset($_POST['wpappninja_lang'])) {

		$plugins = array();
		$options            = get_option( WPAPPNINJA_SLUG );
		
		//foreach ($_POST['stats_box'] as $plugin) {
			//if ($plugin != "" && in_array($plugin, wpappninja_stats_plugin())) {
		//		$plugins[] = $plugin;
			//}
		//}
		
	//	if (count($plugins) > 0) {
	//		update_option( 'wpappninja_stats_box', $plugins );
	//	}
	
		if (isset($_POST['wpappninja_days'])) {
			
			$options['stats_second'] = round($_POST['wpappninja_days'] * 86400);
		}
	
		if (isset($_POST['wpappninja_limit'])) {
			$options['stats_limit'] = round($_POST['wpappninja_limit']);
		}
	
		if (isset($_POST['wpappninja_platform'])) {
			$options['stats_platform'] = round($_POST['wpappninja_platform']);
		}

		if (isset($_POST['wpappninja_lang']) && in_array($_POST['wpappninja_lang'], array('fr', 'en', 'es', 'it', 'pt', 'de'))) {
			$options['stats_lang'] = $_POST['wpappninja_lang'];
		} elseif (isset($_POST['wpappninja_lang']) && $_POST['wpappninja_lang'] == '0') {
			$options['stats_lang'] = '';
		}

		
		update_option( WPAPPNINJA_SLUG, $options );
	}

	if (isset($_POST['wpappninja_ga'])) {

		$options            = get_option( WPAPPNINJA_SLUG );
		$options['ga'] = $_POST['wpappninja_ga'];
		update_option( WPAPPNINJA_SLUG, $options );
	}
	
	// segmentation
	$limit			= round(get_wpappninja_option('stats_limit', 5));

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
	
	// query
	$query_nb 			= $wpdb->get_results("SELECT SUM(nb) as nb FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE action != 'push' AND action != 'install' AND $segment");
	$query_nb_distinct	= $wpdb->get_results("SELECT COUNT(DISTINCT s.user_id) as nb FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE action != 'push' AND action != 'install' AND $segment");
	
	?>
	<style type="text/css">
	h2{background: #fff;
    margin: 0;
    padding: 12px 12px 8px;
    border: 1px solid #ddd;}
    .wpappninja_stats_box_inner {
    width: 32%;margin-left:1%;
    display: inline-table;
}.wpappninja_stats_box_inner.wpmobile_graph {
    width: 65%;
}
	</style>
	
	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2 style="margin: 1em 0;padding:0;border:0px;background:transparent"></h2>
		
		<?php $menu_current = 'stats';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
		
		<div style="padding: 20px;background: white;margin: 0px 0;border-bottom: 1px solid #fd9b02;border-top: 3px solid #fd9b02;">
		
			<?php
			if (!wpappninja_is_premium()) {
				echo '<div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;">' . __("You can't have statistics with the free plan", 'wpappninja') . ' <a style="display: inline-block;margin-left: 17px;font-size:17px;" target="_blank" href="https://wpmobile.app/'; if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}echo '/?source=' . home_url() . '/">' . strtolower(__('UPDATE MY PLAN', 'wpappninja')) . '</a></div>
				<br/><br/>';
			} /*elseif (!get_option('wpappninja_app_published')) {
				echo '<div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;">' . __("Your app is not yet live on stores, no statistics", 'wpappninja') . '</div>
			<br/><br/>';
			}*/ ?>


		<?php
		$range = array(-1 => 'Android + iOS', 0 => 'iOS', 1 => 'Android');
		$current = get_wpappninja_option('stats_platform', -1);
		echo '<input class="button button-primary" type="submit" onclick="jQuery(\'#wpappninja_stats_box_form,#wpappninja_stats_box_form_back\').toggle()" value="' . __('Edit', 'wpappninja') . '" /> <span style="font-size:12px;margin: 6px 15px 0 10px;display: inline-block;">' . round(get_wpappninja_option('stats_second', 2592000) / 86400) . ' ' . __('days', 'wpappninja') . ' - ' . get_wpappninja_option('stats_limit', 5) . ' ' . __('results', 'wpappninja') . ' - ' . $range[$current] . '</span>';


		if (get_wpappninja_option('nomoreqrcode', '0') == '0') {
			echo '<input class="button button-primary" type="submit" style="float:right;';if (get_wpappninja_option('ga') != ''){echo 'background-color:darkgreen;border:1px solid darkgreen';}else{echo 'background-color:#eee;color:gray;border:1px solid gray';}echo '" onclick="jQuery(\'#wpappninja_ga,#wpappninja_stats_box_form_back\').toggle()" value="' . __('Google Analytics', 'wpappninja') . '" />';
		}
		
		echo '<div class="wpappninja_stats_clear"></div>';
		
		$availables = get_option('wpappninja_stats_box', wpappninja_stats_plugin());

		/*echo '<div class="wpappninja_stats_box">';
		$i = 0;
		foreach ($availables as $k) {
			$i++;
			if (in_array($i, array(1,4,7,10,13))) {wpappninja_show_stats($k, $i, $segment, $limit, $query_nb, $query_nb_distinct);}
		}
		echo '</div>';
		echo '<div class="wpappninja_stats_box">';*/
		$i = 0;
		foreach ($availables as $k) {
			$i++;
			/*if (in_array($i, array(2,5,8,11,14))) {*/wpappninja_show_stats($k, $i, $segment, $limit, $query_nb, $query_nb_distinct);//}
		}
		/*echo '</div>';
		echo '<div class="wpappninja_stats_box wpappninja_stats_box_last">';
		$i = 0;
		foreach ($availables as $k) {
			$i++;
			if (in_array($i, array(3,6,9,12,15))) {wpappninja_show_stats($k, $i, $segment, $limit, $query_nb, $query_nb_distinct);}
		}
		echo '</div>';*/



		echo '<form id="wpappninja_ga" action="" method="post" style="z-index:200;display:none;background: white;clear: both;padding: 28px;width: 735px;position: fixed;top: 55px;left: 50%;max-height:75%;overflow:auto;margin-left: -386px;">';
		
		echo '<div style="text-align:right;margin-bottom:10px;font-weight:700;margin-right:15px;"><a href="#" onclick="jQuery(\'#wpappninja_ga,#wpappninja_stats_box_form_back\').toggle();return false">' . __('Close', 'wpappninja') . '</a></div>';
		
		echo '<div style="padding: 0 15px;">
		<h3>' . __('Google Analytics', 'wpappninja') . '</h3>
		<input placeholder="UA-XXXXXXXX-X" type="text" name="wpappninja_ga" value="' . get_wpappninja_option('ga') . '" />
		</div>
		<br/><br/>
		<input style="margin-left:15px;" class="button button-primary" type="submit" />
		<br/>		</form>';




		echo '<form id="wpappninja_stats_box_form" action="" method="post" style="z-index:200;display:none;background: white;clear: both;padding: 28px;width: 735px;position: fixed;top: 55px;left: 50%;max-height:75%;overflow:auto;margin-left: -386px;">';
		
		echo '<div style="text-align:right;margin-bottom:10px;font-weight:700;margin-right:15px;"><a href="#" onclick="jQuery(\'#wpappninja_stats_box_form,#wpappninja_stats_box_form_back\').toggle();return false">' . __('Close', 'wpappninja') . '</a></div>';
		
		echo '<div style="padding: 0 15px;">

		<h3>' . __('How many days?', 'wpappninja') . '</h3>
		<select name="wpappninja_days">';
		$range = array(1, 10, 30, 90, 365);
		foreach ($range as $days) {
			echo '<option ';if (get_wpappninja_option('stats_second', 2592000) == ($days * 86400)){echo ' selected ';}echo ' value="' . $days . '">' . $days. ' ' . _n('day', 'days', $days, 'wpappninja') . '</option>';
		}
		echo '</select>';
		
		echo '<h3>' . __('How many maximum results?', 'wpappninja') . '</h3>
		<select name="wpappninja_limit">';
		$range = array(5, 10, 25, 50);
		foreach ($range as $l) {
			echo '<option ';if (get_wpappninja_option('stats_limit', 5) == $l){echo ' selected ';}echo ' value="' . $l . '">' . $l. ' ' . _n('result', 'results', $l, 'wpappninja') . '</option>';
		}
		echo '</select>';
		
		echo '<h3>' . __('On all platform?', 'wpappninja') . '</h3><select name="wpappninja_platform">';
		$range = array(-1 => 'Android + iOS', 0 => 'iOS', 1 => 'Android');
		foreach ($range as $l => $k) {
			echo '<option ';if (get_wpappninja_option('stats_platform', -1) == intval($l)){echo ' selected ';}echo ' value="' . $l . '">' . $k. '</option>';
		}
		echo '</select>';
		
		echo '<h3>' . __('All languages included?', 'wpappninja') . '</h3>
		<select name="wpappninja_lang">';
		echo '<option ';if (get_wpappninja_option('stats_lang', '') == ''){echo ' selected ';}echo ' value="0">' . __('All', 'wpappninja') . '</option>';
		$range	= $wpdb->get_results("SELECT DISTINCT lang FROM {$wpdb->prefix}wpappninja_stats_users ORDER BY lang ASC");
		foreach ($range as $l) {
			echo '<option ';if (get_wpappninja_option('stats_lang', '') == $l->lang){echo ' selected ';}echo ' value="'.$l->lang.'">' . $l->lang . '</option>';
		}
		echo '</select>
		<br/><br/>
		<!--<h3>' . __('Block disposition', 'wpappninja') . '</h3>-->
		</div>';
		
		/*for ($o = 0;$o < count(wpappninja_stats_plugin()); $o++) {
			$available = (isset($availables[$o])) ? $availables[$o] : "";
			echo '<div style="display:inline-block;line-height:37px;padding:20px;text-align:center;color:#333;font-size:1.2em;border:1px solid #eee;margin:5px 15px;">';
			echo '<b>' . wpappninja_stats_title_box($available) . '</b><br/>';
			echo '<select name="stats_box[' . $o . ']">';
			echo '<option value=""></option>';
			foreach (wpappninja_stats_plugin() as $k) {
				echo '<option value="' . $k . '"';
				if ($available == $k) {echo ' selected';}
				echo '>' . wpappninja_stats_title_box($k) . '</option>';
			}
			echo '</select>';
			echo '</div>';
		}*/
		echo '<br/><br/>
		<input style="margin-left:15px;" class="button button-primary" type="submit" />
		<br/>';
		echo '</form>


		<div style="clear:both"></div>
		<br/><br/>
		<br/><br/>
		<br/><br/>
		<form action="'.admin_url('admin.php?page=' . WPAPPNINJA_STATS_SLUG).'" method="post">
            '.wp_nonce_field( 'wpappninja-delete-stats' ).'
            <select name="wpappninja_delete_stats">';?>
            <option value="365"><?php _e('Delete everything older than 365 days', 'wpappninja');?></option>
            <option value="90"><?php _e('Delete everything older than 90 days', 'wpappninja');?></option>
            <option value="30"><?php _e('Delete everything older than 30 days', 'wpappninja');?></option>
            <option value="0"><?php _e('Delete everything', 'wpappninja');?></option>
            </select>
            <?php
            echo '<input style="background: none;border: 0;text-decoration: underline;color: red;margin-left: 20px;" type="submit" value="'.__('Delete stats (CANT BE UNDONE)').'" />
        </form>

		<div id="wpappninja_stats_box_form_back" style="position:fixed;width:100%;top:0;left:0;height:100%;background:#555;opacity:0.5;z-index:150px;display:none"></div>';
		
		//$active	= $wpdb->get_results("SELECT COUNT(DISTINCT user_id) as nb FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE action != 'push' AND $segment");
		?>
		
		<div style="clear:both"></div>
		</div>
	</div>
	<?php
	if (defined("WPAPPNINJA_DO_CHART")) {do_action('wpappninja_admin_footer');}
	echo wpappninja_talkus();
}
