<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Adserver.
 *
 * @since 4.4
 */
function _wpappninja_display_adserver_page() {

	global $wpdb;
	$lang_array = wpappninja_available_lang();

	// suppression
	if (isset($_POST['wpappninja_delete_ads']) && check_admin_referer( 'wpappninja-delete-ads-' . $_POST['wpappninja_delete_ads'] )) {
		$idDelete = sanitize_text_field($_POST['wpappninja_delete_ads']);
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_adserver WHERE `id` = %s", $idDelete));
	}

	if ((isset($_POST['wpappninja_ad_title']) || isset($_POST['wpappninja']['admob_splash'])) && check_admin_referer('wpappninjaadserver')) {


		if (isset($_POST['wpappninja']['admob_splash'])) {
		$options            = get_option( WPAPPNINJA_SLUG );
		$options['admob_splash'] = $_POST['wpappninja']['admob_splash'];
		$options['admob_splash_ios'] = $_POST['wpappninja']['admob_splash_ios'];
		$options['admob_float'] = $_POST['wpappninja']['admob_float'];
		$options['admob_float_ios'] = $_POST['wpappninja']['admob_float_ios'];
		$options['adbuddiz'] = $_POST['wpappninja']['adbuddiz'];
		$options['adbuddiz_ios'] = $_POST['wpappninja']['adbuddiz_ios'];

		$options['admob_t'] = $_POST['wpappninja']['admob_t'];
		$options['admob_t_ios'] = $_POST['wpappninja']['admob_t_ios'];
		$options['admob_b'] = $_POST['wpappninja']['admob_b'];
		$options['admob_b_ios'] = $_POST['wpappninja']['admob_b_ios'];

		$options['injectads'] = $_POST['wpappninja']['injectads'];


		$options['beforepost'] = $_POST['wpappninja']['beforepost'];
		$options['afterpost'] = $_POST['wpappninja']['afterpost'];
		update_option( WPAPPNINJA_SLUG, $options );
} 
		// add or modify an ad
		if (isset($_POST['wpappninja_ad_title']) && wpappninja_has_adserver()) {
			if ($_POST['wpappninja_ad_title'] != '' || $_POST['wpappninja_ad_html'] != '') {

				$stop = strtotime(sanitize_text_field($_POST['wpappninja_ad_stop']));
				$start = strtotime(sanitize_text_field($_POST['wpappninja_ad_start']));

				$html = stripslashes($_POST['wpappninja_ad_html']);
				$format = sanitize_text_field($_POST['wpappninja_ad_format']);
				$logo = sanitize_text_field($_POST['wpappninja_ad_logo']);
				$title = sanitize_text_field($_POST['wpappninja_ad_title']);
				$text = sanitize_text_field($_POST['wpappninja_ad_text']);
				$color = sanitize_text_field($_POST['wpappninja_ad_color']);
				$link = sanitize_text_field($_POST['wpappninja_ad_link']);
				$lang = sanitize_text_field($_POST['wpappninja_ad_lang']);

				$updateID = sanitize_text_field($_POST['wpappninja_ad_id']);

				if ($updateID != '') {
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_adserver SET `html` = %s, `stop` = %d, `start` = %d, `format` = %s, `logo` = %s, `title` = %s, `text` = %s, `color` = %s, `link` = %s, `lang` = %s WHERE `id` = %s", $html, $stop, $start, $format, $logo, $title, $text, $color, $link, $lang, $updateID));
				} else {
					$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_adserver (`html`, `stop`, `start`, `format`, `logo`, `title`, `text`, `color`, `link`, `lang`) VALUES (%s, %d, %d, %s, %s, %s, %s, %s, %s, %s)", $html, $stop, $start, $format, $logo, $title, $text, $color, $link, $lang));
				}

				wpappninja_clear_cache();
			}
		}
	}
	?>

	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2></h2>
			
		<?php $menu_current = 'adserver';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
			
		<div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;">
<?php

$text = "";

if ($menu_current == 'push') {
    $url = "https://support.wpmobile.app/article/80-how-to-send-a-notification-when-a-post-is-published?lang=".wpmobile_getSupportLang()."";
    $text = __('Learn how to send a notification when you publish', 'wpappninja');
     
} else if (isset($_GET['page']) && $_GET['page'] == WPAPPNINJA_PWA_SLUG) {
    $url = "https://support.wpmobile.app/article/112-progressive-web-app-wordpress?lang=".wpmobile_getSupportLang()."";
    $text = __('Progressive Web App is a free feature', 'wpappninja');
     
} else if (isset($_GET['page']) && $_GET['page'] == WPAPPNINJA_ADSERVER_SLUG) {
    $url = "https://support.wpmobile.app/article/44-can-i-embed-advertising-on-my-mobile-app?lang=".wpmobile_getSupportLang()."";
    $text = __('Learn how to turn on advertising on the app', 'wpappninja');
     
}  ?>


<?php if ($text != "") { ?>
    <div class="wpappninja_help" style="box-shadow: 0 0 0;margin-bottom: 26px;border-left: 5px solid #4CAF50;background: #f8fdf4">
    <?php echo $text;?> <b><a style="display: inline-block;margin-left: 17px;font-size:17px;" target="_blank" href="<?php echo $url;?>"><?php _e('+ more', 'wpappninja');?></a></b>
</div>
<?php } ?>
			<?php
			if (!wpappninja_has_adserver()) {
				echo '<div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;">' . __("You can't use or modify the adserver without a PREMIUM pack", 'wpappninja') . ' <a style="display: inline-block;margin-left: 17px;font-size:17px;" target="_blank" href="https://wpmobile.app/'; if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}echo '/?source=' . home_url() . '/">' . strtolower(__('UPDATE MY PLAN', 'wpappninja')) . '</a></div>
			<br/><br/>';
			} ?>
            
			<a href="#" onclick="jQuery('#wpappninjaadserver_insert').toggle();return false" class="button button-primary"><?php _e('New ad', 'wpappninja');?></a>
			<a style="display: inline-block;margin: 4px 0 0 20px;" href="javascript:jQuery('#wpappninja_ads').toggle()"><?php _e('settings', 'wpappninja');?></a>


<?php
echo '<input class="button button-primary" type="submit" style="float:right;';if (get_wpappninja_option('adbuddiz') != '' || get_wpappninja_option('adbuddiz_ios') != ""){echo 'background-color:darkgreen;border:1px solid darkgreen';}else{echo 'background-color:#eee;color:gray;border:1px solid gray';}echo '" onclick="jQuery(\'#wpappninja_ads\').toggle()" value="' . __('AdBuddiz', 'wpappninja') . '" />';

echo '<input class="button button-primary" type="submit" style="margin-right: 15px;float:right;';if (get_wpappninja_option('admob_splash') != '' || get_wpappninja_option('admob_splash_ios') != "" || get_wpappninja_option('admob_float') != '' || get_wpappninja_option('admob_float_ios') != ""){echo 'background-color:darkgreen;border:1px solid darkgreen';}else{echo 'background-color:#eee;color:gray;border:1px solid gray';}echo '" onclick="jQuery(\'#wpappninja_ads\').toggle()" value="' . __('AdMob', 'wpappninja') . '" />';

echo '<input class="button button-primary" type="submit" style="margin-right: 15px;float:right;';if (get_wpappninja_option('beforepost') != '' || get_wpappninja_option('afterpost') != ""){echo 'background-color:darkgreen;border:1px solid darkgreen';}else{echo 'background-color:#eee;color:gray;border:1px solid gray';}echo '" onclick="jQuery(\'#wpappninja_ads\').toggle()" value="' . __('HTML Code', 'wpappninja') . '" />';

?>

			<form action="" method="post" id="wpappninja_ads" style="display:none">

				<?php wp_nonce_field( 'wpappninjaadserver' );?>
		
				<div id="pushpreview_step" style="display:block">
					<div style="text-align:right;margin-bottom:10px;font-weight:700"><a href="?page=<?php echo WPAPPNINJA_ADSERVER_SLUG;?>"><?php _e('Close', 'wpappninja');?></a></div>

				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_adsettings').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> AdServer WPMobile.App</h2>
				<div class="wpappninja_div" id="wpappninja_adsettings" style="display:none">
					<table class="form-table">	
						<tr valign="top">
							<th scope="row"><?php _e('Add an ad before and after the content', 'wpappninja');?></th>
							<td>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[injectads]">
									<option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('injectads') === "0"){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option>
								</select>

							</td>
						</tr>
					</table>
				</div>

				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_adbuddiz').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> AdBuddiz</h2>
				<div class="wpappninja_div" id="wpappninja_adbuddiz" style="display:none">
					<p class="wpappninja_help"><?php printf(__('Follow %sthe guide%s to create a new ad block', 'wpappninja'), '<a href="https://publishers.adbuddiz.com/pub_portal/createPublisher" target="_blank">', '</a>');?></p>
					<table class="form-table">	
						<tr valign="top">
							<th scope="row"><?php _e('Splash screen', 'wpappninja');?></th>
							<td>
								Android<br/><input placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[adbuddiz]" value="<?php echo get_wpappninja_option('adbuddiz');?>" />
								<br/><br/>
								iOS<br/><input placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[adbuddiz_ios]" value="<?php echo get_wpappninja_option('adbuddiz_ios');?>" />
							</td>
						</tr>
					</table>
				</div>
				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_admob').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> AdMob</h2>
				<div class="wpappninja_div" id="wpappninja_admob" style="display:none">
					<p class="wpappninja_help"><?php printf(__('Follow %sthe guide%s to create a new ad block', 'wpappninja'), '<a href="https://support.google.com/admob/answer/3052638" target="_blank">', '</a>');?></p>
					<table class="form-table">	
						<tr valign="top">
							<th scope="row"><?php _e('Splash screen', 'wpappninja');?></th>
							<td>
								Android<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_splash]" value="<?php echo get_wpappninja_option('admob_splash');?>" />
								<br/><br/>
								iOS<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_splash_ios]" value="<?php echo get_wpappninja_option('admob_splash_ios');?>" />
							</td>
						</tr>
		
						<tr valign="top">
							<th scope="row"><?php _e('Floating bar', 'wpappninja');?></th>
							<td>
								Android<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_float]" value="<?php echo get_wpappninja_option('admob_float');?>" />
								<br/><br/>
								iOS<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_float_ios]" value="<?php echo get_wpappninja_option('admob_float_ios');?>" />
							</td>
						</tr>

						<tr valign="top"<?php if (get_wpappninja_option('speed', '1') == '1') { ?> style="display:none" <?php } ?>>
							<th scope="row"><?php _e('300x250 before post', 'wpappninja');?> [deprecated]</th>
							<td>
								Android<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_t]" value="<?php echo get_wpappninja_option('admob_t');?>" />
								<br/><br/>
								iOS<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_t_ios]" value="<?php echo get_wpappninja_option('admob_t_ios');?>" />
							</td>
						</tr>
					
						<tr valign="top"<?php if (get_wpappninja_option('speed', '1') == '1') { ?> style="display:none" <?php } ?>>
							<th scope="row"><?php _e('300x250 after post', 'wpappninja');?> [deprecated]</th>
							<td>
								Android<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_b]" value="<?php echo get_wpappninja_option('admob_b');?>" />
								<br/><br/>
								iOS<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_b_ios]" value="<?php echo get_wpappninja_option('admob_b_ios');?>" />
							</td>
						</tr>

					</table>
				</div>




				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_html').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('HTML Code', 'wpappninja');?></h2>
				<div class="wpappninja_div" id="wpappninja_html" style="display:none">
					<p class="wpappninja_help"><?php _e("Useful to add your html banners or communicate an event.<br/><b>Note that you can not use CSS or Javascript. Only HTML tags (img, a, b, i, p, ...).</b>", "wpappninja");?></p>

					<table class="form-table">	
						<tr valign="top">
							<th scope="row"><?php _e('Before the post', 'wpappninja');?></th>
							<td>
								<textarea style="width:100%;height:150px;" name="<?php echo WPAPPNINJA_SLUG;?>[beforepost]" id="wpappninja_beforepost"><?php echo stripslashes(get_wpappninja_option('beforepost', ''));?></textarea>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('After the post', 'wpappninja');?></th>
							<td>
								<textarea style="width:100%;height:150px;" name="<?php echo WPAPPNINJA_SLUG;?>[afterpost]" id="wpappninja_afterpost"><?php echo stripslashes(get_wpappninja_option('afterpost', ''));?></textarea>
							</td>
						</tr>
					</table>
				</div>








					<?php echo '				<input type="submit" class="button button-primary button-large" value="'.__('Submit', 'wpappninja').'" /></div>	

			</form>';?>
			<form <?php if (!isset($_POST['wpappninja_adserver_editid'])) {echo 'style="display:none"';}?> action="" method="post" id="wpappninjaadserver_insert">

				<?php wp_nonce_field( 'wpappninjaadserver' );?>
		
				<div id="pushpreview_step" style="display:block">
					<div style="text-align:right;margin-bottom:10px;font-weight:700"><a href="?page=<?php echo WPAPPNINJA_ADSERVER_SLUG;?>"><?php _e('Close', 'wpappninja');?></a></div>


		
					<?php
					$adID 		= "";
					$stop		= date('d-m-Y H:i', current_time( 'timestamp' ) + MONTH_IN_SECONDS);
					$start		= date('d-m-Y H:i', current_time( 'timestamp' ));
					$format		= "top";
					$logo		= "";
					$title		= "";
					$text		= "";
					$color		= "#333333";
					$link		= "";
					$lang		= substr(get_locale(), 0, 2);
					$html 		= "";

					// editing
					if (isset($_POST['wpappninja_adserver_editid'])) {
								
						$adID			= round($_POST['wpappninja_adserver_editid']);
						$editad 		= $wpdb->get_row($wpdb->prepare("SELECT `html`, `stop`, `start`, `format`, `logo`, `title`, `text`, `color`, `link`, `lang` FROM {$wpdb->prefix}wpappninja_adserver WHERE `id` = %d", $adID));
								
						$stop		= date('d-m-Y H:i', $editad->stop);
						$start		= date('d-m-Y H:i', $editad->start);
						$format		= $editad->format;
						$logo		= $editad->logo;
						$title		= $editad->title;
						$text		= $editad->text;
						$color		= $editad->color;
						$link		= $editad->link;
						$lang		= $editad->lang;
						$html		= $editad->html;
					}
							
					echo '<input type="hidden" name="wpappninja_ad_id" value="'.$adID.'" />
					
					<div id="pushpreview_started" style="display:block">

						<h2>'.__('HTML ad', 'wpappninja').'</h2>
						<textarea name="wpappninja_ad_html" style="width: 500px;height: 250px;">'.$html.'</textarea>
						<div style="display:none">
						<h2>'.__('Color of the title', 'wpappninja').'</h2>
						<input type="hidden" name="wpappninja_ad_format" value="top" />
									
						<input type="text" name="wpappninja_ad_color" value="'.$color.'" class="wpapp-color-picker-ad" /><br/><br/>

						<h2>'.__('The ad', 'wpappninja').'</h2>
						' . __('Title', 'wpappninja') .'<br/>
						<input type="text" name="wpappninja_ad_title" value="'.$title.'" /><br/><br/>
									
						' . __('Text', 'wpappninja') .'<br/>
						<input type="text" name="wpappninja_ad_text" value="'.$text.'" /><br/><br/>

						' . __('Link', 'wpappninja') .'<br/>
						<input type="text" name="wpappninja_ad_link" value="'.$link.'" /><br/><br/>

						<h2>'.__('Logo', 'wpappninja').'</h2>

						<div class="uploader">
							<input id="blog_logo" name="wpappninja_ad_logo" type="text" value="'.$logo.'" />
							<input id="blog_logo_button" class="button" name="wpappninja_ad_logo_button" type="text" value="'.__('Choose an image', 'wpappninja').'" />
						</div><br/>
						</div>
								
						<h2>'.__('Language', 'wpappninja').'</h2>';
								
						foreach($lang_array as $name => $code) {
							if ($code == 'English') {$code = 'en';}
							echo '<label><input type="radio" name="wpappninja_ad_lang" value="'.$code.'" ';if ($code == $lang) {echo 'checked';}echo ' /> <img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" /> '.$name.'</label><br/>';
						}
								
						echo '<br/><h2>'.__('Schedule', 'wpappninja').'</h2>
						'.__('Start date', 'wpappninja') .'<br/><br/>
						<input type="text" id="datetimepicker3" name="wpappninja_ad_start" value="' .$start. '" /><br/><br/>

						<script>jQuery(\'#datetimepicker3\').datetimepicker({format:\'d-m-Y H:i\'});</script>

						'.__('End date', 'wpappninja') .'<br/><br/>
						<input type="text" id="datetimepicker4" name="wpappninja_ad_stop" value="' .$stop. '" /><br/><br/><br/>

						<script>jQuery(\'#datetimepicker4\').datetimepicker({format:\'d-m-Y H:i\'});</script>


					</div>
								<input type="submit" class="button button-primary button-large" value="'.__('Submit', 'wpappninja').'" /></div>	

			</form>';


			echo '<div id="wpappninja_hist_cont">
				<br/><br/>';

				$sub = $wpdb->get_results("SELECT COUNT(id) as ads, SUM(display) as display, SUM(click) as click FROM {$wpdb->prefix}wpappninja_adserver");
				echo '<div style="font-size: 20px;display: block;background: #fff;padding: 20px 0;border: 1px solid #ddd;max-width: 500px;text-align: center;"><b>'.$sub[0]->ads.'</b> '.__('ads', 'wpappninja').'&nbsp;&nbsp;&nbsp;<b>'.round($sub[0]->display).'</b> '.__('displays', 'wpappninja').'&nbsp;&nbsp;&nbsp;<b>'.round($sub[0]->click).'</b> '.__('clicks', 'wpappninja');		
				echo '</div><br/><br/>';
						
				$query = $wpdb->get_results("SELECT `html`, `id`, `stop`, `start`, `format`, `logo`, `title`, `text`, `color`, `link`, `lang`, `display`, `click` FROM {$wpdb->prefix}wpappninja_adserver ORDER BY `id` DESC");
				foreach($query as $obj) {
					
					$img = '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'flags/' . $obj->lang . '.gif" /> ';
			
					echo '<div class="pushpreview pushpreview_block">';

					if ($obj->html == "") {
						echo '<div class="pushpreview_texte" onclick="window.open(\''.$obj->link.'\');" style="padding-bottom: 22px;font-size: 17px;white-space: normal;text-align:center;cursor:pointer;">';
							if ($obj->logo != "") {echo '<img style="margin:4px;width:90px;" src="'.$obj->logo.'" /><br/>';}
							echo '<b style="font-size:20px;color:'.$obj->color.'">'.stripslashes($obj->title).'</b><br/>
							'.stripslashes($obj->text).'
						</div>
						<div class="clear"></div>';
						if ($image != '') {echo '<div class="pushpreview_image" style="background-image:url('.$image.')"></div>';}
						echo '<div class="clear"></div>';
					} else {echo $obj->html;}
					echo '</div>
					<div class="pushpreview_since" style="font-size:14px">';

						if ($obj->start < current_time( 'timestamp' ) && $obj->stop > current_time( 'timestamp' )) {
							echo '<b style="color:darkgreen;font-size:20px;">' . __('LIVE', 'wpappninja') . '</b>';
						} elseif ($obj->start > current_time( 'timestamp' ) && $obj->stop > current_time( 'timestamp' )) {
							echo '<b style="color:gray;font-size:20px;">' . __('PLANNED', 'wpappninja') . '</b>';
						} elseif ($obj->stop < current_time( 'timestamp' )) {
							echo '<b style="color:darkred;font-size:20px;">' . __('ENDED', 'wpappninja') . '</b>';
						}

						echo '<br/><br/>' . __('Displays:', 'wpappninja').' <b>'.$obj->display.'</b>&nbsp;&nbsp;&nbsp;'.__('Clicks:', 'wpappninja').' <b>'.$obj->click.'</b><br/><br/>

						'.__('Lang:', 'wpappninja').' '.$img.'<br/>

						'.__('Start date:', 'wpappninja').' '.date('d-m-Y H:i', $obj->start).'<br/>
						'.__('End date:', 'wpappninja').' '.date('d-m-Y H:i', $obj->stop).'<br/><br/>

						<form action="" method="post"><input type="hidden" name="wpappninja_adserver_editid" value="'.$obj->id.'" /><input type="submit" value="'.__('Edit', 'wpappninja').'" /></form>

						<form action="'.admin_url('admin.php?page=' . WPAPPNINJA_ADSERVER_SLUG).'" method="post">'.wp_nonce_field( 'wpappninja-delete-ads-' . $obj->id ).'<input type="hidden" name="wpappninja_delete_ads" value="'.$obj->id.'" /><input style="background: none;border: 0;text-decoration: underline;color: red;" type="submit" value="'.__('Delete').'" /></form>

					</div>
					<div class="clear" style="height:20px"></div>';
				}
				
			echo '</div>';
			?>

		</div>
	</div>

	<div style="clear:both"></div>

	<script type="text/javascript">
	jQuery(document).ready(function($){


	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;

	$('#blog_logo_button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$("#"+id).val(attachment.url);
				$(".wpapp_logo_place").attr("src", attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}

		wp.media.editor.open(button);
		return false;
	});

	$('.add_media').on('click', function(){
		_custom_media = false;
	});
	
	var wpapp_color_ad = {
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-ad").wpColorPicker(wpapp_color_ad);

	});
	</script>

	<?php
	echo wpappninja_talkus();
}
