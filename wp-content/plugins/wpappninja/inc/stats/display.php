<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add a widget to the dashboard.
 *
 * @since 4.0
 */
function wpappninja_stats_dashboard() {
	
	if (!current_user_can( wpappninja_get_right() )) {
		return;
	}
	
	$availables = wpappninja_stats_plugin();
	
	foreach ($availables as $k) {
		wp_add_dashboard_widget(
                 'wpappninja_stats_dashboard_render_' . $k,
                 wpappninja_stats_title_box($k),
                 'wpappninja_stats_dashboard_render',
				 null,
				 array($k)
        );
	}
}
//add_action( 'wp_dashboard_setup', 'wpappninja_stats_dashboard' );

/**
 * Output the content to dashboard widget.
 *
 * @since 4.0
 */
function wpappninja_stats_dashboard_render($post, $args) {
	global $wpdb;
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
	
	// highcharts
	if ($args['args'][0] == 'graph') {
		wp_enqueue_script( 'wpappninja-stats-highcharts' );
	}
	
	// css
	echo '<style type="text/css">
	.wpappninja_stats_box_inner h2{display:none}
	.wpappninja_stats_box_inner table{border:0;}
	.wpappninja_stats_button {
    display: inline-block;
    color: #fff;
    border: 1px solid;
    border-radius: 5px;
    padding: 4px 7px;
	}
	</style>';
	
	// show
	wpappninja_show_stats($args['args'][0], 0, $segment, $limit, $query_nb, $query_nb_distinct);
	
}

/**
 * Display the title box.
 *
 * @since 4.0.2
 */
function wpappninja_stats_title_box($k) {
	
	switch ($k) {
		case 'timeline':
			return '⌛ ' . __('Timeline', 'wpappninja');
			break;

		case 'graph':
			return '📈 ' . __('Chart', 'wpappninja');
			break;

		case 'installation':
			return '📲 ' . __('Installations', 'wpappninja');
			break;

		case 'action':
			return '🎉 ' . __('Actions', 'wpappninja');
			break;

		case 'push':
			return '📢 ' . __('Push notifications', 'wpappninja');
			break;

		case 'content':
			return '📄 ' . __('Content', 'wpappninja');
			break;

		case 'user':
			return '🙂 ' . __('Users', 'wpappninja');
			break;

		case 'platform':
			return '📱 ' . __('Platforms', 'wpappninja');
			break;

		case 'hour':
			return '🕓 ' . __('Hours', 'wpappninja');
			break;

		case 'continent_old':
			return '🌍 ' . __('Continents', 'wpappninja');
			break;

		case 'country':
			return '🌍 ' . __('Country', 'wpappninja');
			break;

		case 'language':
			return '🗣 ' . __('Languages', 'wpappninja');
			break;
	}
}

/**
 * Show the stats box.
 *
 * @since 4.0.2
 */
function wpappninja_stats_plugin() {
	return array('installation', 'graph', 'content', 'action', 'language');
} 

/**
 * Show the stats box.
 *
 * @since 4.0.2
 */
function wpappninja_show_stats($k, $i, $segment, $limit, $query_nb, $query_nb_distinct) {
	
	global $wpdb;
	
	$isLast = false;
	
	switch ($k) {
		case 'timeline':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>			
			<div style="padding:4px;border: 1px solid #e5e5e5;background:white;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04);">
				<table class="widefat fixed striped" cellspacing="0">
					<tbody>
						<?php
						$query	= $wpdb->get_results("SELECT action, value, date as timestamp, platform, country, lang, city FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action != 'push' AND $segment ORDER BY date DESC LIMIT $limit");
						foreach($query as $obj) {
					
							// platform
							$platform = strtolower($obj->platform);
							if ($platform != 'android' && $platform != 'ios') {$platform = 'empty';}
					
							// geo
							$loc = $obj->country;
							if ($obj->city != '') {$loc = $obj->city;}
	
							echo '<tr>
								<td style="width:100px;">' . date('d/m H:i', $obj->timestamp) . '</td>
								<td><img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/' . $platform . '.png" /> <img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'flags/' . $obj->lang . '.gif" /> ' . $loc . '</td> 
								<td><span class="dashicons dashicons-' . wpappninja_stats_dashicon($obj->action) . '" title="' . $obj->action . '"></span> <b>' . wpappninja_stats_human($obj->action, $obj->value) . '</b></td>
							</tr>';
						} ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
		break;
		
		case 'graph':
		?>
		<div class="wpappninja_stats_box_inner wpmobile_graph">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
			<div style="padding:4px;border: 1px solid #e5e5e5;background:white;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04);" id="wpappninja_stats_graph_holder"></div>
		</div>
		<?php
		define("WPAPPNINJA_DO_CHART", true);
		break;
		
		case 'installation':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<div style="padding:4px;border: 1px solid #e5e5e5;background:white;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04);"><?php
		$sub = $wpdb->get_results("SELECT SUM(nb) as sub FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE $segment AND action = 'install' GROUP BY s.action ORDER BY nb DESC LIMIT $limit");
		echo '<div style="font-size: 40px;padding: 50px 0;border-bottom: 1px solid #eee;text-align: center;"><b>'.@round($sub[0]->sub).'</b></div>';

		$ios = $wpdb->get_results("SELECT SUM(nb) as ios FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE $segment AND action = 'install' AND u.platform = 'ios' GROUP BY s.action ORDER BY nb DESC LIMIT $limit");
		echo '<div style="font-size: 20px;display:inline-block;width:50%;padding: 28px 0 20px;border-right: 1px solid #eee;text-align: center;">';
		echo '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/ios.png" > ' . @round($ios[0]->ios);
		echo '</div><div style="font-size: 20px;display:inline-block;width:49%;padding: 28px 0 20px;text-align: center;">';
		echo '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/android.png" > ' . @round($sub[0]->sub - $ios[0]->ios);
		echo '</div>';
		?></div>
		</div>
		<?php
		break;
		
		case 'action':
		$query_nb_push	= $wpdb->get_results("SELECT SUM(nb) as nb FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE $segment");
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Action', 'wpappninja');?></th>
					<th>x</th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT SUM(nb) as nb, action FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE $segment AND action != 'read' GROUP BY s.action ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {					
					echo '<tr>
						<td><div class="wpappninja_stats_button" style="background:#' . wpappninja_stats_color($obj->action) . ';border-color:#' . wpappninja_stats_color($obj->action, TRUE) . ';"><span class="dashicons dashicons-' . wpappninja_stats_dashicon($obj->action) . '"></span> ' . wpappninja_stats_human_title($obj->action) . '</div></td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '</td>
						<td>' . @round((100/$query_nb_push[0]->nb)*$obj->nb, 2) . '%</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
		
		case '_push':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th style="width:60%"><?php _e('Push', 'wpappninja');?></th>
					<th><?php _e('Dispatch', 'wpappninja');?></th>
					<!--<th><?php _e('Open', 'wpappninja');?></th>-->
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT SUM(nb) as nb, action, value FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE action = 'push' AND $segment GROUP BY s.action, s.value ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {
					
					// read
					$post_id	= $wpdb->get_results($wpdb->prepare("SELECT id_post FROM {$wpdb->prefix}wpappninja_push WHERE id = %s", $obj->value));

					$id_post_push = "";
					if (isset($post_id[0]->id_post)) {
						$id_post_push = $post_id[0]->id_post;
					}

					$open_rate	= $wpdb->get_results($wpdb->prepare("SELECT SUM(nb) as nb FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE action = 'read' AND value = %s AND $segment", $id_post_push));

					echo '<tr>
						<td><span style="color:#' . wpappninja_stats_color($obj->action) . ';" class="dashicons dashicons-' . wpappninja_stats_dashicon($obj->action) . '" title="' . $obj->action . '"></span> ' . wpappninja_stats_human($obj->action, $obj->value) . '</td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '</td>
						<!--<td>' . @round((100/$obj->nb)*$open_rate[0]->nb, 2) . '%</td>-->
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
		
		case 'content':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>


		<style>.wpmhour{border: 1px solid #e5e5e5;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);
    box-shadow: 0 1px 1px rgba(0,0,0,.04);box-sizing: border-box;
		}</style>
		<div class="widefat fixed striped wpmhour">
				<?php
				$total = 0;
				$query	= $wpdb->get_results("SELECT COUNT(s.user_id) as nb, CONCAT(DAY(FROM_UNIXTIME(date)), '-', MONTH(FROM_UNIXTIME(date)), '-', YEAR(FROM_UNIXTIME(date))) as hour FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action = 'read' AND $segment GROUP BY YEAR(FROM_UNIXTIME(date)), MONTH(FROM_UNIXTIME(date)), DAY(FROM_UNIXTIME(date)) ORDER BY hour ASC");

				$hour = array();
				foreach($query as $obj) {

					$heure = strtotime($obj->hour);
					$total += $obj->nb;
					$hour[$heure] = $obj->nb;
				}

				$nbrow = count($hour);

				foreach($hour as $i => $nb) {
					$percent = round((100/$total)*$nb, 2) * 2.5;
					if ($percent > 100) {}

					$pp = round($percent);
					if ($pp > 100) {$pp = 99;}
					if ($pp < 10) {$pp = '0' . $pp;}

					$color = "rgba(143, 212, 255, 0.".$pp.")";

					if ($nb > -1 && 1<0) {
					echo '<div class="wpappninja_stats_nb" id="hour'.$i.'" style="oveflow:hidden;position:relative;float:left;height:250px;width:Calc(100% / '.$nbrow.');background: linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);background: -webkit-linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);background: -moz-linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);background: -ms-linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);"></div>

					<style>#hour'.$i.':hover:after {display:block}


	#hour'.$i.':after{content: \'' . date_i18n(  'd-m' , $i) . '\';    position: absolute;
    bottom:5px;width:100%;
    padding: 0px;text-align:center;color:#666;text-shadow:0 0 1px #fff;
    display:block;
    border-radius: 36px}

    #hour'.$i.':before{content: \'' . $nb . '\';    position: absolute;
    bottom:30px;width:100%;font-weight:700;font-size:20px;
    padding: 0px;text-align:center;
    color: #333;text-shadow:0 0 2px #fff;display:block;
    border-radius: 36px}</style>';
				}
				} ?>
			<div style="clear:both"></div>
		</div>


		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th style="width:60%"><?php _e('Content', 'wpappninja');?></th>
					<th><?php _e('Action', 'wpappninja');?></th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT SUM(nb) as nb, action, value FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE action != 'login' AND action != 'signup' AND action != 'push' AND action != 'install' AND $segment GROUP BY s.action, s.value ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {					
					echo '<tr>
						<td><span style="color:#' . wpappninja_stats_color($obj->action) . ';" class="dashicons dashicons-' . wpappninja_stats_dashicon($obj->action) . '" title="' . $obj->action . '"></span> ' . wpappninja_stats_human($obj->action, $obj->value) . '</td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '';if (@round((100/$query_nb[0]->nb) * $obj->nb) > 20) {echo ' 🔥';}echo '</td>
						<td>' . @round((100/$query_nb[0]->nb)*$obj->nb, 2) . '%</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
		
		case 'user':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('User', 'wpappninja');?></th>
					<th>x</th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT SUM(nb) as nb, platform, country, lang, city FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action != 'push' AND s.action != 'install' AND $segment GROUP BY s.user_id ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {
					
					// platform
					$platform = strtolower($obj->platform);
					if ($platform != 'android' && $platform != 'ios') {$platform = 'empty';}
					
					// geo
					$loc = $obj->country;
					if ($obj->city != '') {$loc = $obj->city;}
	
					echo '<tr>
						<td><img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/' . $platform . '.png" /> <img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'flags/' . $obj->lang . '.gif" /> ' . $loc . '</td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '</td>
						<td>' . @round((100/$query_nb[0]->nb)*$obj->nb, 2) . '%</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
		
		case 'platform':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Platform', 'wpappninja');?></th>
					<th>x</th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT COUNT(DISTINCT s.user_id) as nb, platform FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action != 'push' AND s.action != 'install' AND $segment GROUP BY u.platform ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {
					echo '<tr>
						<td><img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/' . $obj->platform . '.png" /> ' . $obj->platform . '</td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '</td>
						<td>' . @round((100/$query_nb_distinct[0]->nb)*$obj->nb, 2) . '%</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
		
		case 'hour':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<style>.wpmhour{border: 1px solid #e5e5e5;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);
    box-shadow: 0 1px 1px rgba(0,0,0,.04);box-sizing: border-box;
		}</style>
		<div class="widefat fixed striped wpmhour">
				<?php
				$total = 0;
				$query	= $wpdb->get_results("SELECT COUNT(DISTINCT s.user_id) as nb, date as hour FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action != 'push' AND s.action != 'install' AND $segment GROUP BY HOUR(FROM_UNIXTIME(date)) ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {
					$heure = date('G', $obj->hour);
					$total += $obj->nb;
					$hour[$heure] = $obj->nb;
				}

				for ($i=0;$i<24;$i++) {
					$percent = round((100/$query_nb_distinct[0]->nb)*$hour[$i], 2) * 4;

					$color = "rgba(143, 212, 255, 1.".round($percent/10).")";

					if ($hour[$i] > -1) {
					echo '<div class="wpappninja_stats_nb" id="hour'.$i.'" style="oveflow:hidden;float:left;height:250px;width:Calc(100% / 24);background: linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);background: -webkit-linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);background: -moz-linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);background: -ms-linear-gradient(bottom, '.$color.' '.$percent.'%, white 0%);"></div>

					<style>#hour'.$i.':hover:after {display:block} #hour'.$i.':after{content: \'' . $i . 'h ['.$hour[$i].']\';    position: absolute;
    background: #2273a5;margin-top:25px;
    padding: 10px;
    color: white;
    border-radius: 36px;display:none}</style>';
				}
				} ?>
			<div style="clear:both"></div>
		</div>
		</div>
		<?php
		break;
		
		case 'continent_old':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Continent', 'wpappninja');?></th>
					<th>x</th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT COUNT(DISTINCT s.user_id) as nb, continent FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action != 'push' AND s.action != 'install' AND $segment GROUP BY u.continent ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {
					echo '<tr>
						<td>' . $obj->continent . '</td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '</td>
						<td>' . @round((100/$query_nb_distinct[0]->nb)*$obj->nb, 2) . '%</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
		
		case 'country':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Country', 'wpappninja');?></th>
					<th>x</th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT COUNT(DISTINCT s.user_id) as nb, country FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action != 'push' AND s.action != 'install' AND $segment GROUP BY u.country ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {
					echo '<tr>
						<td>' . $obj->country . '</td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '</td>
						<td>' . @round((100/$query_nb_distinct[0]->nb)*$obj->nb, 2) . '%</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
		
		case 'language':
		?>
		<div class="wpappninja_stats_box_inner">
		<h2><?php echo wpappninja_stats_title_box($k); ?></h2>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Lang', 'wpappninja');?></th>
					<th>x</th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query	= $wpdb->get_results("SELECT COUNT(DISTINCT s.user_id) as nb, lang FROM {$wpdb->prefix}wpappninja_stats s JOIN {$wpdb->prefix}wpappninja_stats_users u ON u.id = s.user_id WHERE s.action != 'push' AND s.action != 'install' AND $segment GROUP BY u.lang ORDER BY nb DESC LIMIT $limit");
				foreach($query as $obj) {
					echo '<tr>
						<td><img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'flags/' . $obj->lang . '.gif" /> ' . $obj->lang . '</td> 
						<td class="wpappninja_stats_nb">' . $obj->nb . '</td>
						<td>' . @round((100/$query_nb_distinct[0]->nb)*$obj->nb, 2) . '%</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		</div>
		<?php
		break;
	}
	
	if ($isLast) { echo '<div class="wpappninja_stats_clear"></div>'; }
}
