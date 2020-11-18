<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Cnfigurator.
 *
 * @since 5.2
 */
function _wpappninja_display_auto_page() {

	set_transient( 'is_wpappninja_ajax', false, 60*60 );
							$disableswipe = false;
	$linkdisplay = 'block';
	$formdisplay = 'none';
	$formdisplay_2 = 'none';

	if (isset($_POST['wpappninja_auto']) && check_admin_referer('wpappninjaauto')) {





		$options            = get_option( WPAPPNINJA_SLUG );

		foreach($_POST['wpappninja'] as $k => $v) {

			if ($k == "app") {
				continue;
			}

			/*if (preg_match('#^css_|widget_#', $k)) {
				if ($v == "") {
					$v = " ";
				}*/

				if (preg_match('#^widget_#', $k)) {

					if (!$v || $v == "") {
						$v = "&nbsp;";
					}
				}


				if (is_array($v)) {
					$options[$k] = $v;
				} else {
					$options[$k] = stripslashes($v);
				}
			//}
		}
        
        if (isset($_POST['wpappninja']['cache_type'])) {

            $options['cache_friendly'] = 1;
            $options['agressive_anti_cache'] = 0;
            
            if ($_POST['wpappninja']['cache_type'] == "networkonly") {
                $options['appcachemode'] = "prefer-online";
                $options['appcachedelay'] = "dmYHms";
            }
            
            if ($_POST['wpappninja']['cache_type'] == "fast1day") {
                $options['appcachemode'] = "fast";
                $options['appcachedelay'] = "dmY";
            }
            
            if ($_POST['wpappninja']['cache_type'] == "fast1month") {
                $options['appcachemode'] = "fast";
                $options['appcachedelay'] = "dm";
            }
            if ($_POST['wpappninja']['cache_type'] == "fast1hour") {
                $options['appcachemode'] = "fast";
                $options['appcachedelay'] = "dmYH";
            }
        
            if ($_POST['wpappninja']['disable_all_cache'] == "on") {
            
                $options['cache_friendly'] = 1;
                $options['agressive_anti_cache'] = 1;
                $options['cache_type'] = "networkonly";
            
            } else {
                $options['disable_all_cache'] = "off";
            }
        }
        
		update_option( WPAPPNINJA_SLUG, $options );


					if (get_wpappninja_option('temp_tag', '') != '') {
						$options            = get_option( WPAPPNINJA_SLUG );
						$options['temp_tag'] = '';

						$tag = get_term_by('name', get_wpappninja_option('temp_tag', ''), 'post_tag');

						if ($tag->term_id != '') {
							$options['excluded'][] = $tag->term_id;
						}

						update_option( WPAPPNINJA_SLUG, $options );
					}
	}

	if (isset($_GET['url'])) {
		$demopage = $_GET['url'];
	} else {

		$query = wp_get_recent_posts(array(
										'posts_per_page' => 1,
										'post_type' => get_post_types(array('public'=>true)),
										'post_status' => 'publish',
										'numberposts' => 1,
										'offset'=>0
									));

		$demopage = get_permalink($query[0]['ID']);
	}

	if (isset($demopage)) {

		$json = array();

		$url = esc_url ($demopage);
		$postid = url_to_postid( $url );
		$id = $postid;

		if ($postid == 0 && wpappninja_url_to_postid($url) != 0) {
			$postid = wpappninja_url_to_postid($url);
		}

		if ($postid != 0) {
			$linkdisplay = 'none';
			$formdisplay = 'block';

			$content_post = get_post($postid);

			$json['url'] = $url;
			$json['url_optimal'] = home_url( '/' ) . "?iswpappninjaconfigurator=true&wpappninja_read_enhanced=" . $id;
			$json['id'] = $id;
			$json['image'] = wpappninja_get_image($postid, '0', true);
			$json['titre'] = html_entity_decode(get_the_title($postid));
			$json['date'] = wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date));

			$content = '';
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = apply_filters('appandroid_content', $content, array('id' => $id));
			$content = get_wpappninja_option('beforepost', '') . $content . get_wpappninja_option('afterpost', '');
			$content = strip_shortcodes( $content );
			$content = str_replace(']]>', ']]&gt;', $content);
			$content = str_replace("&nbsp;", " ", $content);
			$content = html_entity_decode($content);
			$content = wpappninja_pre_tags($content);
			$content = str_replace(array("\r", "\n"),"", $content);
	
			// youtube
			$content = preg_replace('/<iframe.*?src=[\'"](https?:)?(\/\/)?(www\.)?(youtu\.be\/|youtube(-nocookie)?\.[a-z]{2,4}(?:\/embed\/|\/v\/|\/watch\?.*?v=))([\w\-]{10,12})([\?|&]?.*?)?[\'"]([^>]+|)><\/iframe>/', '$1$2$3$4$5$6', $content);
			$content = preg_replace('/>(https?:)?(\/\/)?(www\.)?(youtu\.be\/|youtube(-nocookie)?\.[a-z]{2,4}(?:\/embed\/|\/v\/|\/watch\?.*?v=))([\w\-]{10,12})([\?|&]?.*?)?</', '><a href="https://www.youtube.com/watch?v=$6"><img src="https://api.wpmobile.app/youtube/$6.png" /></a><br/><', $content);
			$content = preg_replace('/>(https?:)?(\/\/)?(www\.)?(youtu\.be\/|youtube(-nocookie)?\.[a-z]{2,4}(?:\/embed\/|\/v\/|\/watch\?.*?v=))([\w\-]{10,12})([\?|&]?.*?)?/', '><a href="https://www.youtube.com/watch?v=$6"><img src="https://api.wpmobile.app/youtube/$6.png" /></a><br/>', $content);
		
			// dailymotion
			$content = preg_replace('/<iframe.*?src=[\'"](https?:)?(\/\/)?(www\.)?(dailymotion\.[a-z]{2,4}(?:\/embed\/video\/))([\w\-]{5,20})([\?|&]?.*?)?[\'"]([^>]+|)><\/iframe>/', '<a href="https://www.dailymotion.com/video/$5"><img src="https://api.wpmobile.app/dailymotion/$5.png" /></a>', $content);

			// vimeo
			$content = preg_replace('/<iframe.*?src=[\'"](https?:)?(\/\/)?(player\.)(vimeo\.[a-z]{2,4}(?:\/video\/))([\w\-]{5,20})([\?|&]?.*?)?[\'"]([^>]+|)><\/iframe>/', '<a href="https://vimeo.com/$5"><img src="https://api.wpmobile.app/vimeo/$5.png" /></a>', $content);
		
			// convert table to list
			$content = preg_replace('#<tr([^>]+|)>#', '<ul>', $content);
			$content = preg_replace('#</tr>#', '</ul><br/>', $content);

			$content = preg_replace('#<thead([^>]+|)>#', '<span>', $content);
			$content = preg_replace('#</thead>#', '</span>', $content);

			$content = preg_replace('#<th([^>]+|)>#', '<li><b>', $content);
			$content = preg_replace('#</th>#', '</b></li>', $content);

			$content = preg_replace('#<td([^>]+|)>#', '<li>', $content);
			$content = preg_replace('#</td>#', '</li>', $content);
		
			// suppression des shortcodes recalcitrants
			$content = preg_replace('/\[[^\]]+\]/', '', $content);
	
			// tweet
			$content = preg_replace_callback('/p>https?:\/\/twitter\.com\/(?:#!\/)?(\w+)\/status(es)?\/(\d+)</', function ($matches) {
			$jsonTweet = json_decode(file_get_contents('https://api.twitter.com/1/statuses/oembed.json?url=https://twitter.com/'.$matches[1].'/status/'.$matches[3]), TRUE);
			return 'p>'.$jsonTweet['html'].'<';
			}, $content);

			$content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
			$content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
			$content = preg_replace('#	#is', '', $content);
			$content = preg_replace('# style="[^"]+"#is', '', $content);
			$content = preg_replace('# class="(?!wp-smiley)[^"]+"#is', '', $content);

			$json['content'] = $content;
	
			// remove content by regex
			$regex = get_wpappninja_option('regex');
			if ($regex !== FALSE) {
				$rules = explode("\n", $regex);
		
				foreach($rules as $rule) {
					$rule = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $rule));
					$content = preg_replace($rule, '', $content);
				}
			}
	
			$bio = array(
				'avatar' => wpappninja_get_gravatar(get_the_author_meta('user_email', $content_post->post_author)),
				'name' => get_the_author_meta('display_name', $content_post->post_author),
				'description' => nl2br(get_the_author_meta('description', $content_post->post_author)),
				'url' => get_the_author_meta('user_url', $content_post->post_author)
			);

			$json['bio'] = $bio;

			$json['config']['webview'] = get_wpappninja_option('webview', '2');
			$json['config']['disablefeat'] = get_wpappninja_option('disablefeat', '0');
			$json['config']['hideimgonlypage'] = get_wpappninja_option('hideimgonlypage', '0');
			$json['config']['remove_title'] = get_wpappninja_option('remove_title', '0');
			$json['config']['show_avatar'] = get_wpappninja_option('show_avatar', '1');
			$json['config']['show_date'] = get_wpappninja_option('show_date', '0');

		} else {
			$id = wpappninja_url_to_catid($url);
			if ($id != 0) {
				$formdisplay_2 = 'block';
				$jsonlist = wpappninja_recent(0, $id);
				$title = get_cat_name($id);
			} else if (preg_match('/recent$/', $url)) {
				$formdisplay_2 = 'block';
				$jsonlist = wpappninja_recent("recent", 0);
				$title = __('Recent posts', 'wpappninja');
			}
		}
	}

	$formdisplay_2 = 'block';
	define("WPAPPNINJA_FORCE_IMG", true);
	$jsonlist = wpappninja_recent("recent", 0);
	$title = __('Recent posts', 'wpappninja');

	$app_data = get_wpappninja_option('app');
	$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();
	$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
	$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";
	?>

	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2 style="font-size:1.3em"></h2>

		<style type="text/css">
		h2{color:#555;font-size:33px;}
		</style>
			
		<?php $menu_current = 'auto';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
			
		<div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;">
             
		<?php $menu_current = 'auto';require( WPAPPNINJA_ADMIN_UI_PATH   . 'submenu.php' ); ?>


            <script type="text/javascript">
            function wpappninja_resizeIframe(obj) {
    			obj.style.height = (obj.contentWindow.document.body.scrollHeight) + 'px';
  			}

  			<?php /*if (get_wpappninja_option('speed') != '1') { ?>
  			jQuery(document).on('submit','#wpappninjaauto_insert',function(){

  				document.getElementById('wpappninja_hide_me').value = window.frames['wpappninja_iframe'].contentDocument.getElementById('_sg_path_field').value;

  				//this.submit();
  			});
  			<?php }*/ ?>

            </script>

            <style>
            <?php

            if (isset($_GET['settings'])) {
            	echo "form h2{display:none}form h2.wpapp_settings {display:block}";
            } else {
            	echo "form h2.wpapp_settings {display:none}";
            }

            ?>
	        </style>

	        <?php if(get_wpappninja_option('appify') != '1' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != "WPMobile.App" && get_wpappninja_option('speed') == '1') { ?>
<br/><br/><span style="
    display: block;
    background: #d8ebf1;
    padding: 40px;
    width: 100%;
    text-align: center;
    font-size: 25px;
"><?php _e('Some options require the WPMobile.App theme', 'wpappninja');?></span>
<?php } ?>

			<form action="" method="post" id="wpappninjaauto_insert">
<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[pwa_cache]" value="<?php echo (get_wpappninja_option('pwa_cache', 1) + 1);?>" />
				<?php wp_nonce_field( 'wpappninjaauto' );?>

				<input type="hidden" name="wpappninja_auto" value="1" />




									<style type="text/css">
									input[type="text"], select {
    width: 350px;
    border: 2px solid #eee!important;
    box-shadow: 0 0 0 #fff!important;
    font-size: 20px;
    background: #fdfdfd;
    box-sizing: content-box;
    padding: 2px;
}textarea {
    padding: 2px 6px;
    line-height: 1.4;
    width: 360px!important;
    border: 2px solid #eee;
    box-shadow: 0 0 0 #fff;
    font-family: courier;
    font-size: 19px;
}input#submitme {
    font-size: 25px!important;
    box-sizing: initial;
    height: auto;
    line-height: initial!important;
    width: 320px!important;
    padding: 10px 150px!important;
    margin: 25px auto;
    display: block;
}
					.wpappninja-builder {
						padding:15px;
						font-size: 18px;
						background:#fff;
						border-bottom:1px solid #eee;
					}
					.wpappninja-builder:hover {
						background: #ffe;
					}
					.wpappninja-builder-left {
						width: 300px;
						padding: 6px;
						float: left;
					}
					.wpappninja-builder-right {
						float:left;
					}

					.selectapptype label {
					    display: inline-block;
						background:#f9f9f9;
					    padding: 25px;
                        width:49%;box-sizing: border-box;
					}

					.selectapptype label:hover{
						background:#fff;
						box-shadow:0 0 4px #eee;
					}

					</style>
							<script type="text/javascript">
								function checkselectedtype() {

									//if (hide) {
										jQuery('#hidemeplsivechanged').hide();
									//}
									//jQuery('.selectapptype input').parent().css('background', '#f9f9f9');
									//jQuery('.selectapptype input:checked').parent().css('background', '#e9ffe9');
								}
</script>


<?php

?>





				<?php if (get_wpappninja_option('speed', '0') == '1') {

					define('WPAPPNINJA_NEED_TINYMCE', '1'); ?>


<div id="hidemeplsivechanged">


					<?php
					if (1<0) { ?>



<?php $section = 'links'; ?>
<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Links management', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open all links in the internal browser', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[all_link_browser]"><option value="1" <?php if (get_wpappninja_option('all_link_browser', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('all_link_browser', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open all pdf link with the Drive reader', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[pdfdrive]"><option value="1" <?php if (get_wpappninja_option('pdfdrive', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('pdfdrive', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>
</div>

<?php $section = 'extra'; ?>
<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Extra', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">

<?php
$right = array(
	'manage_options' 	=> __('Administrator'),
	'edit_posts' 		=> __('Editor'),
	'publish_posts' 	=> __('Author'),
	'read' 				=> __('Subscriber'),
);
?>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to send notification', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightpush]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightpush', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to view statistics', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightstats]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightstats', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to view qrcode', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightqrcode]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightqrcode', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Show the download banner on the website', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[smartbanner]"><option value="1" <?php if (get_wpappninja_option('smartbanner', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('smartbanner', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder" <?php if (get_wpappninja_option('appify') != '1' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App') {echo ' style="display:none"';} ?>>
	<div class="wpappninja-builder-left">
		<?php _e('Cache and speed', 'wpappninja');?>
	</div>
	<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpappninja_pwa]" value="on" />
	<div class="wpappninja-builder-right">
 
		<select name="<?php echo WPAPPNINJA_SLUG;?>[cache_type]">

			<option value="cacheonly" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'cacheonly'){echo 'selected';} ?>><?php _e('Cache everything', 'wpappninja');?></option>

			<option value="networkonly" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'networkonly'){echo 'selected';} ?>><?php _e('Cache only for offline', 'wpappninja');?></option>

		</select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App' || 1>0) {echo ' style="display:none"';} ?>>
	<div class="wpappninja-builder-left">
		<?php _e('Enable fastclick', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[fastclick]"><option value="1" <?php if (get_wpappninja_option('fastclick', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('fastclick', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Improve the compatibility with cache plugin', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[cache_friendly]"><option value="1" <?php if (get_wpappninja_option('cache_friendly', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('cache_friendly', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>



</div>
<?php					} else {
					$widgets = wpappninja_get_widgets();




					$classes = wpappninja_get_css_rules();
					$section = "";
					$customcss = false;
					$disableinfinite = false;
					$searchorder = false;
					$disableswipe = false;
					$disablestatic = false;

					foreach ($classes as $c) {


						if ($section != $c['section']) {

							foreach ($widgets as $w) {

								if ($w['section'] == $section) {


									echo '<div class="wpappninja-builder"><div class="wpappninja-builder-left">' . $w['help'] . '</div><div class="wpappninja-builder-right">';


									$settings = array('tinymce' => array('width' => "auto",'height'=>"400px"), 'teeny' => false, 'wpautop' => false, 'textarea_name' => WPAPPNINJA_SLUG . '[widget_' . md5($w['id']) . ']' );

									wp_editor( stripslashes(get_wpappninja_option('widget_'.md5($w['id']), $w['default'])), 'widget_' . md5($w['id']), $settings);
									echo '</div><div style="clear:both"></div></div>';
								}

							}

							if ($section != "") {
								echo '</div>';
							}

							$section = $c['section'];

							?>


<?php if ($c['section'] == __('Extra', 'wpappninja') && !isset($customcsss)) {
								$customcsss = true;?>



	<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_login').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Action on login', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_login" style="display:none">

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Redirect to after login', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<input value="<?php echo get_wpappninja_option('login_redirect_after');?>" name="<?php echo WPAPPNINJA_SLUG;?>[login_redirect_after]" type="text" />


	</div>
	<div class="clear"></div>
</div>

</div>








	<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_homepage').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('User Homepage', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_homepage" style="display:none">

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Home type', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[home_type]"><option value="list" <?php if (get_wpappninja_option('home_type', 'list') == 'list'){echo 'selected';} ?>><?php _e('List with all posts', 'wpappninja');?></option><option value="cat" <?php if (get_wpappninja_option('home_type', 'list') == 'cat'){echo 'selected';} ?>><?php _e('Show 3 posts by category', 'wpappninja');?></option></select>


	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Category available to users', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<?php
		$category = get_terms(get_taxonomies(array('public'=>true)), array('orderby' => 'count', 'order' => 'DESC', 'number' => 50));
		usort($category, "wpappninja_home_cmp_a");

		$checked = get_wpappninja_option('home_available', array());

		$_taxname = "";

		foreach ($category as $cc) {

			$term = get_taxonomy($cc->taxonomy);
			$taxname = $term->label;
			if ($taxname != $_taxname && $taxname != "") {
				$_taxname = $taxname;
				echo "<h4 style='padding: 0px!important;font-size: 18px;margin-bottom: 11px;'>" . $taxname . "</h4>";
			}

			if ($cc->parent == 0) {

				echo '<label><input type="checkbox" value="' . $cc->taxonomy . '|' . $cc->term_id . '" ';

	    	    if (in_array($cc->taxonomy . '|' . $cc->term_id, $checked)) {
					echo 'checked';
				}

		        echo ' name="'.WPAPPNINJA_SLUG.'[home_available][]" /> '.$cc->name.'</label><br/>';
		    }
		} ?>

	</div>
	<div class="clear"></div>
</div>

</div>

	<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_speed').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Cache and speed', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_speed" style="display:none">



<div class="wpappninja-builder" <?php if (get_wpappninja_option('appify') != '1' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App') {echo ' style="display:block"';} ?>>
	<div class="wpappninja-builder-left">
		<?php _e('Cache and speed', 'wpappninja');?>
	</div>
	<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpappninja_pwa]" value="on" />
	<div class="wpappninja-builder-right">
 
		<select name="<?php echo WPAPPNINJA_SLUG;?>[cache_type]">

			<option value="networkonly" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'networkonly'){echo 'selected';} ?>><?php _e('Just for offline', 'wpappninja');?></option>
<option value="fast1hour" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'fast1hour'){echo 'selected';} ?>><?php _e('Fast - 1 hour cache', 'wpappninja');?></option>

            <option value="fast1day" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'fast1day'){echo 'selected';} ?>><?php _e('Fast - 1 day cache', 'wpappninja');?></option>
<option value="fast1month" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'fast1month'){echo 'selected';} ?>><?php _e('Fast - 1 month cache', 'wpappninja');?></option>



		</select>

<br/><br/>
<label><input type="checkbox" value="on" name="<?php echo WPAPPNINJA_SLUG;?>[disable_all_cache]" <?php if (get_wpappninja_option('disable_all_cache', 'off') == 'on'){echo 'checked';} ?> /> <?php _e('Disable all cache', 'wpappninja');?></label>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App' || 1>0) {echo ' style="display:none"';} ?>>
	<div class="wpappninja-builder-left">
		<?php _e('Enable fastclick', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[fastclick]"><option value="1" <?php if (get_wpappninja_option('fastclick', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('fastclick', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder" style="display:none">
	<div class="wpappninja-builder-left">
		<?php _e('Improve the compatibility with cache plugin', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[cache_friendly]"><option value="1" <?php if (get_wpappninja_option('cache_friendly', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('cache_friendly', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder" style="display:none">
	<div class="wpappninja-builder-left">
		<?php _e('Always refresh the app content', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[agressive_anti_cache]"><option value="1" <?php if (get_wpappninja_option('agressive_anti_cache', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('agressive_anti_cache', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>

</div>
<?php } ?>


							<h2 <?php if ($c['section'] == __('Extra', 'wpappninja')) {?>class="wpapp_settings" <?php } ?> style="<?php if ($c['section'] == __('Background', 'wpappninja')) {echo "display:none;";} ?> background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo md5($section); ?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php echo $section;?></h2>
							<div class="wpappninja_div" id="wpappninja_section_<?php echo md5($section);?>" style="display:none">
							<?php
							if ($c['section'] == __('Extra', 'wpappninja') && !$customcss) {
								$customcss = true; ?>


<?php
$right = array(
	'manage_options' 	=> __('Administrator'),
	'edit_posts' 		=> __('Editor'),
	'publish_posts' 	=> __('Author'),
	'read' 				=> __('Subscriber'),
);
?>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to send notification', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightpush]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightpush', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div><div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to view statistics', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightstats]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightstats', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to view qrcode', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightqrcode]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightqrcode', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>
								<div class="wpappninja-builder">
                                    <div class="wpappninja-builder-left">
                                        <?php _e('Show the download banner on the website', 'wpappninja');?>
                                    </div>
                                    <div class="wpappninja-builder-right">

                                        <select name="<?php echo WPAPPNINJA_SLUG;?>[smartbanner]"><option value="1" <?php if (get_wpappninja_option('smartbanner', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('smartbanner', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

                                    </div>
                                    <div class="clear"></div>
                                </div>

<div class="wpappninja-builder">
    <div class="wpappninja-builder-left">
        <?php _e('Never turn off the screen', 'wpappninja');?>
    </div>
    <div class="wpappninja-builder-right">

        <select name="<?php echo WPAPPNINJA_SLUG;?>[notimeoutjs]"><option value="1" <?php if (get_wpappninja_option('notimeoutjs', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('notimeoutjs', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

    </div>
    <div class="clear"></div>
</div>




								<!--<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Use the website theme (not recommanded)', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[speed_notheme]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('speed_notheme') != '1'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>-->
							<?php }

							if ($c['section'] == __('Content', 'wpappninja') && !$disableinfinite) {
								$disableinfinite = true; ?>

								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Animate page load', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[effect]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('effect', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>

            <div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Vibrate effect', 'wpappninja');?></div><div class="wpappninja-builder-right">


            <select name="<?php echo WPAPPNINJA_SLUG;?>[vibrator]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('vibrator', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>

								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Animate page load on WordPress Themes', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_loader_all_theme]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('wpmobile_loader_all_theme', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>








								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Lazy load images', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[wpm_lazyload]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('wpm_lazyload', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>



								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Infinite scroll pages', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[infinitescroll]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('infinitescroll', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>


								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Pull to refresh', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[speed_reload]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('speed_reload') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>

	
							<?php }


							if ($c['section'] == __('Menu', 'wpappninja') && !$disableswipe) {
								$disableswipe = true; ?>
	

								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Slide to open the menu', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[slidetoopen]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('slidetoopen', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>
							<?php }


							/*if ($c['section'] == __('Toolbar', 'wpappninja') && !$disablestatic) {
								$disablestatic = true; ?>
	

								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Fixed toolbar', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[statictoolbar]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('statictoolbar', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>
							<?php }*/

							if ($c['section'] == __('List', 'wpappninja') && !$searchorder) {
								$searchorder = true; ?>

								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Show the title of the list', 'wpappninja');?></div><div class="wpappninja-builder-right">

					
								<select name="<?php echo WPAPPNINJA_SLUG;?>[titlespeed]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if(get_wpappninja_option('titlespeed') != '1'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></div><div class="clear"></div></div>

								
								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Order list by', 'wpappninja');?></div><div class="wpappninja-builder-right">
									
								<?php $orderby = get_wpappninja_option('orderby_list', 'post_date'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[orderby_list]">

									<option value="post_date" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'post_date'){echo 'selected';} ?>><?php _e('Date', 'wpappninja');?></option>

									<option value="comment_count" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'comment_count'){echo 'selected';} ?>><?php _e('Comment count', 'wpappninja');?></option>

									<option value="author" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'author'){echo 'selected';} ?>><?php _e('Author', 'wpappninja');?></option>

									<option value="title" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'title'){echo 'selected';} ?>><?php _e('Title', 'wpappninja');?></option>

									<option value="modified" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'modified'){echo 'selected';} ?>><?php _e('Last modified date', 'wpappninja');?></option>

									<option value="rand" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'rand'){echo 'selected';} ?>><?php _e('Random', 'wpappninja');?></option>


									<option value="none" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'none'){echo 'selected';} ?>><?php _e('No order', 'wpappninja');?></option>

                                    <option value="menu_order" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'menu_order'){echo 'selected';} ?>><?php _e('Menu order', 'wpappninja');?></option>

								</select>
									
								</div><div class="clear"></div></div>
								<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Sort rule', 'wpappninja');?></div><div class="wpappninja-builder-right">
									
<?php $orderby = get_wpappninja_option('orderby_list', 'post_date'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[order_list]">

									<option value="ASC" <?php if (get_wpappninja_option('order_list', 'DESC') == 'ASC'){echo 'selected';} ?>><?php _e('Ascending order from lowest to highest values', 'wpappninja');?></option>		
									
									<option value="DESC" <?php if (get_wpappninja_option('order_list', 'DESC') == 'DESC'){echo 'selected';} ?>><?php _e('Descending order from highest to lowest values', 'wpappninja');?></option>


								</select>
									
								</div><div class="clear"></div></div>




						<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Item number', 'wpappninja');?></div><div class="wpappninja-builder-right">
									
								<select name="<?php echo WPAPPNINJA_SLUG;?>[listnb]">
																	<option value="5" <?php if (get_wpappninja_option('listnb', '10') == 5){echo 'selected';} ?>><?php echo 5;?></option>

								<?php for($i = 1;$i < 10;$i++) { ?>
																	<option value="<?php echo ($i*10);?>" <?php if (get_wpappninja_option('listnb', '10') == ($i * 10)){echo 'selected';} ?>><?php echo ($i * 10);?></option>


								<?php }	?>
<?php for($i = 1;$i < 10;$i++) { ?>
                                    <option value="<?php echo ($i*10);?>" <?php if (get_wpappninja_option('listnb', '10') == ($i * 100)){echo 'selected';} ?>><?php echo ($i * 100);?></option>


<?php }    ?>


								</select>
									
								</div><div class="clear"></div></div>





<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Hide posts older than X days', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<input type="number" name="<?php echo WPAPPNINJA_SLUG;?>[maxage]" value="<?php echo get_wpappninja_option('maxage', 365000);?>" /> <?php _e('Days', 'wpappninja');?>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Show password protected posts', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[has_password]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('has_password', '0') == "0"){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>



								<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Exclude by direct tag name', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[temp_tag]" />

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Exclude by tags', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<?php
							$excluded = get_wpappninja_option('excluded', array());

							foreach ($excluded as $exclude) {
								$tag = get_tag($exclude);

								if ($exclude != "DONOTREALLYEXCLUDEME") {

									echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="' . $exclude . '" checked /> ' . trim($tag->name) . '</label><br/>';
								}
							}

							$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 50));
							foreach ( $tags as $tag ) {

								if (!in_array($tag->term_id, $excluded)) {
									echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="' . $tag->term_id . '" /> ' . trim($tag->name) . '</label><br/>';
								}
							}

							echo '<input style="display:none" type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="DONOTREALLYEXCLUDEME" checked />';
							?>

	</div>
	<div class="clear"></div>
</div>


							<?php }



						}

						if (in_array($c['zone'], array('color', 'border-color', 'background', 'background-color'))) {
							echo '<div class="wpappninja-builder" style="display:none"><div class="wpappninja-builder-left">' . $c['help'] . '</div><div class="wpappninja-builder-right">';
							echo '<input type="text" name="' . WPAPPNINJA_SLUG . '[css_' . md5($c['class'] . $c['zone']) . ']" value="' . get_wpappninja_option('css_'.md5($c['class'] . $c['zone']), $c['color']) . '" class="wpapp-color-picker-primary" required /></div><div style="clear:both"></div></div>';
						}

						if (in_array($c['zone'], array('display'))) {
							echo '<div class="wpappninja-builder"><div class="wpappninja-builder-left">' . $c['help'] . '</div><div class="wpappninja-builder-right">';
							echo '<select name="' . WPAPPNINJA_SLUG . '[css_' . md5($c['class'] . $c['zone']) . ']"><option value="none">' . __('Hide', 'wpappninja') . '</option><option value="'.wpappninja_get_default(md5($c['class'] . $c['zone'])).'" ';if (get_wpappninja_option('css_'.md5($c['class'] . $c['zone']), $c['color']) == wpappninja_get_default(md5($c['class'] . $c['zone']))) {echo 'selected';}echo '>' . __('Show', 'wpappninja') . '</option></select></div><div style="clear:both"></div></div>';
						}

						if (in_array($c['zone'], array('font-size'))) {
							echo '<div class="wpappninja-builder"><div class="wpappninja-builder-left">' . $c['help'] . '</div><div class="wpappninja-builder-right">';
							echo '<select name="' . WPAPPNINJA_SLUG . '[css_' . md5($c['class'] . $c['zone']) . ']">';

							for ($i = 9; $i < 26; $i++) {echo '<option value="' . $i . 'px" ';if (get_wpappninja_option('css_'.md5($c['class'] . $c['zone']), $c['color']) == $i . 'px') {echo 'selected';}echo '>' . $i . 'px</option>';}

							echo '</select></div><div style="clear:both"></div></div>';
						}




					}

					?>
					</div>


<?php $section = 'img'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Image', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Default image url (optional)', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<input type="text" placeholder="http://example.com/image.png" name="<?php echo WPAPPNINJA_SLUG;?>[defautimg]" value="<?php echo get_wpappninja_option('defautimg');?>" />
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e("Use any image on the post as featured", "wpappninja");?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[anyfeat]"><option value="1" <?php if (get_wpappninja_option('anyfeat', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('anyfeat', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>
</div>

<?php $section = 'similar'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Similars posts', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Number', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[similarnb]">
							<?php
							$nbsimi = get_wpappninja_option('similarnb', 10);
							for ($i=0;$i<21;$i++) {
								echo '<option ';if ($i == $nbsimi){echo 'selected';}echo '>'.$i.'</option>';
							}
							?>
							</select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Selection rule', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[similartype]">
								<?php
								$taxonomy = wpappninja_get_all_taxonomy();
								$_taxonomy = get_taxonomies(array('public'=>true), 'objects');
								foreach($_taxonomy as $p => $k) { ?>
									<option value="<?php echo $k->name;?>" <?php if (get_wpappninja_option('similartype', 'category') == $k->name){echo 'selected';} ?>><?php echo $k->labels->name;?></option>
								<?php } ?>
							</select>
					
	</div>
	<div class="clear"></div>
</div>
</div>


<?php $section = 'links'; ?>
<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Links management', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open all links in the internal browser', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[all_link_browser]"><option value="1" <?php if (get_wpappninja_option('all_link_browser', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('all_link_browser', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>



<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open all pdf link with the Drive reader', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[pdfdrive]"><option value="1" <?php if (get_wpappninja_option('pdfdrive', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('pdfdrive', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

</div>





				<br/><br/>
				<!--<input type="submit" id="submitme" class="button button-primary button-large" />-->



				<?php /*if (get_wpappninja_option('fullspeed', '0') != '0') {echo '<div style="display:none">';}?><br/><br/><br/><?php _e('Enable the new app builder', 'wpappninja'); ?> <select name="<?php echo WPAPPNINJA_SLUG;?>[speed]"><option value="0"><?php _e('No', 'wpappninja');?></option><option value="1" <?php if (get_wpappninja_option('speed', '0') == '1') {echo "selected";}?>><?php _e('Yes', 'wpappninja');?></option></select><?php if (get_wpappninja_option('fullspeed', '0') != '0') {echo '</div>';}*/?>

				<?php } } else if(get_wpappninja_option('speed') == '0') { ?><div>

					<?php /*if (get_wpappninja_option('fullspeed', '0') == '0' && get_wpappninja_option('speed', '0') != '1') { _e('Enable the new app builder', 'wpappninja');?>
				<select name="<?php echo WPAPPNINJA_SLUG;?>[speed]"><option value="0"><?php _e('No', 'wpappninja');?></option><option value="1" <?php if (get_wpappninja_option('speed', '0') == '1') {echo "selected";}?>><?php _e('Yes', 'wpappninja');?></option></select><br/><input type="submit" id="submitme" class="button button-primary button-large" /><br/><br/><?php } */?>












<div id="hidemeplsivechanged">










<?php $section = 'features'; ?>
<h2  style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Features', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">



<div class="wpappninja-builder" style="display:none">
	<div class="wpappninja-builder-left">
		
		<?php _e('Subscribe system', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[show_abonnement]"><option value="1"><?php _e('Yes');?></option><option value="0" selected><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Favorite system', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[show_favori]"><option value="1" <?php if (get_wpappninja_option('show_favori', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_favori', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Sharing system', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[share]"><option value="1" <?php if (get_wpappninja_option('share', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('share', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>




</div>


















<?php $section = 'img'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Image', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Default image url (optional)', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<input type="text" placeholder="http://example.com/image.png" name="<?php echo WPAPPNINJA_SLUG;?>[defautimg]" value="<?php echo get_wpappninja_option('defautimg');?>" />
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e("Use any image on the post as featured", "wpappninja");?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[anyfeat]"><option value="1" <?php if (get_wpappninja_option('anyfeat', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('anyfeat', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>
</div>






<?php $section = 'datetime'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Date and time', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Date type', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[datetype]">
								<option value="date" <?php if (get_wpappninja_option('datetype', 'date') == 'date'){echo 'selected';} ?>><?php echo date_i18n( get_option( 'date_format' ), current_time('timestamp'));?></option>
								<option value="ilya" <?php if (get_wpappninja_option('datetype', 'date') == 'ilya'){echo 'selected';} ?>><?php _e('5 days ago', 'wpappninja');?></option>
							</select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e("Show date on list and post", "wpappninja");?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[remove_date]"><option value="1" <?php if (get_wpappninja_option('remove_date', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('remove_date', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e("Show hour", "wpappninja");?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[showdate]"><option value="1" <?php if (get_wpappninja_option('showdate', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('showdate', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>
</div>

<?php $section = 'lists'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Lists', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Item size', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<?php $typedevue = get_wpappninja_option('typedevue', 'big'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[typedevue]">
									<option value="big" <?php if (get_wpappninja_option('typedevue', 'big') == 'big'){echo 'selected';} ?>><?php _e('Big cards', 'wpappninja');?></option>
									<option value="small" <?php if (get_wpappninja_option('typedevue', 'big') == 'small'){echo 'selected';} ?>><?php _e('Small cards', 'wpappninja');?></option>
								</select>
					
	</div>
	<div class="clear"></div>
</div>



<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Hide posts older than X days', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<input type="number" name="<?php echo WPAPPNINJA_SLUG;?>[maxage]" value="<?php echo get_wpappninja_option('maxage', 365000);?>" /> <?php _e('Days', 'wpappninja');?>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Show password protected posts', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[has_password]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('has_password', '0') == "0"){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Exclude by direct tag name', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[temp_tag]" />

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Exclude by tags', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<?php
							$excluded = get_wpappninja_option('excluded', array());

							foreach ($excluded as $exclude) {
								$tag = get_tag($exclude);


								if ($exclude != "DONOTREALLYEXCLUDEME") {
								echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="' . $exclude . '" checked /> ' . trim($tag->name) . '</label><br/>';
							}
							}

							$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 50));
							foreach ( $tags as $tag ) {

								if (!in_array($tag->term_id, $excluded)) {
									echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="' . $tag->term_id . '" /> ' . trim($tag->name) . '</label><br/>';
								}
							}

							echo '<input style="display:none" type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="DONOTREALLYEXCLUDEME" checked />';

							?>

	</div>
	<div class="clear"></div>
</div>



<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Order by', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<?php $orderby = get_wpappninja_option('orderby_list', 'post_date'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[orderby_list]">

									<option value="post_date" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'post_date'){echo 'selected';} ?>><?php _e('Date', 'wpappninja');?></option>

									<option value="comment_count" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'comment_count'){echo 'selected';} ?>><?php _e('Comment count', 'wpappninja');?></option>

									<option value="author" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'author'){echo 'selected';} ?>><?php _e('Author', 'wpappninja');?></option>

									<option value="title" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'title'){echo 'selected';} ?>><?php _e('Title', 'wpappninja');?></option>

									<option value="modified" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'modified'){echo 'selected';} ?>><?php _e('Last modified date', 'wpappninja');?></option>

									<option value="rand" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'rand'){echo 'selected';} ?>><?php _e('Random', 'wpappninja');?></option>


									<option value="none" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'none'){echo 'selected';} ?>><?php _e('No order', 'wpappninja');?></option>

                                    <option value="menu_order" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'menu_order'){echo 'selected';} ?>><?php _e('Menu order', 'wpappninja');?></option>


								</select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Order', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<?php $orderby = get_wpappninja_option('orderby_list', 'post_date'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[order_list]">

									<option value="ASC" <?php if (get_wpappninja_option('order_list', 'DESC') == 'ASC'){echo 'selected';} ?>><?php _e('Ascending order from lowest to highest values', 'wpappninja');?></option>		
									
									<option value="DESC" <?php if (get_wpappninja_option('order_list', 'DESC') == 'DESC'){echo 'selected';} ?>><?php _e('Descending order from highest to lowest values', 'wpappninja');?></option>


								</select>
					
	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e("Show image on lists", "wpappninja");?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[disablefeat]"><option value="1" <?php if (get_wpappninja_option('disablefeat', '0') == '1'){echo 'selected';} ?>><?php _e('No');?></option><option value="0" <?php if (get_wpappninja_option('disablefeat', '0') == '0'){echo 'selected';} ?>><?php _e('Yes', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>
</div>

<?php $section = 'content'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Content', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">

	<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e("Show image on pages", "wpappninja");?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[hideimgonlypage]"><option value="1" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '1'){echo 'selected';} ?>><?php _e('No');?></option><option value="0" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '0'){echo 'selected';} ?>><?php _e('Yes', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Show the title before content', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[remove_title]"><option value="1" <?php if (get_wpappninja_option('remove_title', '0') == '1'){echo 'selected';} ?>><?php _e('No');?></option><option value="0" <?php if (get_wpappninja_option('remove_title', '0') == '0'){echo 'selected';} ?>><?php _e('Yes', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Show author name and avatar before post', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[show_avatar]"><option value="1" <?php if (get_wpappninja_option('show_avatar', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_avatar', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Show date before post', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[show_date]"><option value="1" <?php if (get_wpappninja_option('show_date', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_date', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Show author bio after post', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[bio]"><option value="1" <?php if (get_wpappninja_option('bio', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('bio', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

	<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Comments', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[commentaire]"><option value="1" <?php if (get_wpappninja_option('commentaire', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('commentaire', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>
</div>



<?php $section = 'similar'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Similars posts', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Number', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[similarnb]">
							<?php
							$nbsimi = get_wpappninja_option('similarnb', 10);
							for ($i=0;$i<21;$i++) {
								echo '<option ';if ($i == $nbsimi){echo 'selected';}echo '>'.$i.'</option>';
							}
							?>
							</select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Selection rule', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[similartype]">
								<?php
								$taxonomy = wpappninja_get_all_taxonomy();
								$_taxonomy = get_taxonomies(array('public'=>true), 'objects');
								foreach($_taxonomy as $p => $k) { ?>
									<option value="<?php echo $k->name;?>" <?php if (get_wpappninja_option('similartype', 'category') == $k->name){echo 'selected';} ?>><?php echo $k->labels->name;?></option>
								<?php } ?>
							</select>
					
	</div>
	<div class="clear"></div>
</div>
</div>

<?php $section = 'searchengine'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Search engine', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Search engine', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[show_search]"><option value="1" <?php if (get_wpappninja_option('show_search', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_search', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>




<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Exclude by category', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<?php
							$search_cat = array();
							if (is_array(get_wpappninja_option('search_cat'))) {
								$search_cat = get_wpappninja_option('search_cat');
							}
							$categories = get_terms(wpappninja_get_all_taxonomy(), array('orderby' => 'count', 'order' => 'DESC', 'number' => intval(get_wpappninja_option('searchnb', 60))));
							foreach ($categories as $category) {
								if ($category->parent == 0) {
									echo '<label><input type="checkbox" value="'.$category->term_id.'" name="' . WPAPPNINJA_SLUG . '[search_cat][]" ';if (in_array($category->term_id, $search_cat)) {echo 'checked';}echo ' /> '.$category->name.'</label><br/>';
								}
							}
							?>

	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Show more category', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG . '[searchnb]';?>">
									<?php
									for($i = 10;$i < 200;$i+=10) {
										echo '<option ';if(get_wpappninja_option('searchnb', 60) == $i) {echo 'selected';}echo '>'.$i.'</option>';
									} ?>
								</select>

	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Order by', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[orderby_search]">

									<option value="post_date" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'post_date'){echo 'selected';} ?>><?php _e('Date', 'wpappninja');?></option>

									<option value="relevance" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'relevance'){echo 'selected';} ?>><?php _e('Relevance', 'wpappninja');?></option>

									<option value="comment_count" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'comment_count'){echo 'selected';} ?>><?php _e('Comment count', 'wpappninja');?></option>

									<option value="author" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'author'){echo 'selected';} ?>><?php _e('Author', 'wpappninja');?></option>

									<option value="title" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'title'){echo 'selected';} ?>><?php _e('Title', 'wpappninja');?></option>

									<option value="modified" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'modified'){echo 'selected';} ?>><?php _e('Last modified date', 'wpappninja');?></option>

									<option value="rand" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'rand'){echo 'selected';} ?>><?php _e('Random', 'wpappninja');?></option>


                                    <option value="none" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'none'){echo 'selected';} ?>><?php _e('No order', 'wpappninja');?></option>

                                    <option value="menu_order" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'menu_order'){echo 'selected';} ?>><?php _e('Menu order', 'wpappninja');?></option>

								</select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Order', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[order_search]">

									<option value="ASC" <?php if (get_wpappninja_option('order_search', 'DESC') == 'ASC'){echo 'selected';} ?>><?php _e('Ascending order from lowest to highest values', 'wpappninja');?></option>		
									
									<option value="DESC" <?php if (get_wpappninja_option('order_search', 'DESC') == 'DESC'){echo 'selected';} ?>><?php _e('Descending order from highest to lowest values', 'wpappninja');?></option>


								</select>

	</div>
	<div class="clear"></div>
</div>
</div>



<?php $section = 'links'; ?>
<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Links management', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open all links in the internal browser', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[all_link_browser]"><option value="1" <?php if (get_wpappninja_option('all_link_browser', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('all_link_browser', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open all pdf link with the Drive reader', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[pdfdrive]"><option value="1" <?php if (get_wpappninja_option('pdfdrive', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('pdfdrive', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open all links on the same screen (not recommended)', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[nodeeplink]"><option value="1" <?php if (get_wpappninja_option('nodeeplink', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('nodeeplink', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Open in browser menu option (Android only)', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[show_browser]"><option value="1" <?php if (get_wpappninja_option('show_browser', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_browser', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>
					
	</div>
	<div class="clear"></div>
</div>

</div>


<?php $section = 'extra'; ?>
<h2 class="wpapp_settings" style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Extra', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">



<?php
$right = array(
	'manage_options' 	=> __('Administrator'),
	'edit_posts' 		=> __('Editor'),
	'publish_posts' 	=> __('Author'),
	'read' 				=> __('Subscriber'),
);
?>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to send notification', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightpush]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightpush', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to view statistics', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightstats]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightstats', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Minimal role to view qrcode', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[rightqrcode]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('rightqrcode', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Show the download banner on the website', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[smartbanner]"><option value="1" <?php if (get_wpappninja_option('smartbanner', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('smartbanner', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder" <?php if (get_wpappninja_option('appify') != '1' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App') {echo ' style="display:none"';} ?>>
	<div class="wpappninja-builder-left">
		<?php _e('Cache and speed', 'wpappninja');?>
	</div>
	<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpappninja_pwa]" value="on" />
	<div class="wpappninja-builder-right">
 
		<select name="<?php echo WPAPPNINJA_SLUG;?>[cache_type]">

			<option value="cacheonly" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'cacheonly'){echo 'selected';} ?>><?php _e('Cache everything', 'wpappninja');?></option>

			<option value="networkonly" <?php if (get_wpappninja_option('cache_type', 'networkonly') == 'networkonly'){echo 'selected';} ?>><?php _e('Cache only for offline', 'wpappninja');?></option>


		</select>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App' || 1>0) {echo ' style="display:none"';} ?>>
	<div class="wpappninja-builder-left">
		<?php _e('Enable fastclick', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[fastclick]"><option value="1" <?php if (get_wpappninja_option('fastclick', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('fastclick', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder" >
	<div class="wpappninja-builder-left">
		<?php _e('Improve the compatibility with cache plugin', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[cache_friendly]"><option value="1" <?php if (get_wpappninja_option('cache_friendly', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('cache_friendly', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select>

	</div>
	<div class="clear"></div>
</div>

</div>



</div>

<?php }else{ ?>




<?php } ?>




				<textarea style="display:none" id="wpappninja_dummy_textarea"></textarea>




				<?php /* ?><h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_listdesign').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e("List", "wpappninja");?></h2>
				<div style="display:none" id="wpappninja_listdesign">
				<div class="wpappninja_div" style="overflow: hidden;width: 300px;float:left;margin-right: 40px;border: 30px solid #333;border-radius: 30px;box-shadow: 0 0 15px #555;">

					<div id="wpapp_color_primary" class="mini_android_toolbar" style="white-space: nowrap;font-size: 15px;padding: 18px 0 0 10px;width: 290px;height: 37px;color:white;background:<?php echo $app_theme_primary;?>">< <b><?php echo $title;?></b></div>
					<div class="wpappninja_noscroll" style="width:300px;height:450px;overflow:auto;overflow-x: hidden;">
						
							<?php
							$jsonlist = json_decode($jsonlist);
							foreach ($jsonlist->data as $item) {
								echo '<div class="wpappninja_list_item" style="overflow:hidden;border-bottom:1px solid #eee;';if (get_wpappninja_option('typedevue', 'big') == 'big'){echo 'height:150px;';}else{echo 'height:100px;';}echo '">

									<div class="wpappninja_list_img" style="    display: inline-block;background:url('.$item->image.') no-repeat center center;background-size:cover;';if (get_wpappninja_option('typedevue', 'big') == 'big'){echo 'width:100%;height:70px;';}else{echo 'margin-top:20px;height:60px;width:60px;';}echo '"></div>

									<div style="display:inline-block;padding:15px;';if (get_wpappninja_option('typedevue', 'big') == 'big'){echo 'width:100%';}else{echo 'width:Calc(100% - 100px);';}echo '" class="wpappninja_list_width">
									<div class="wpappninja_list_titre" style="';if (get_wpappninja_option('typedevue', 'big') == 'big'){echo 'font-size:18px;';}else{echo 'font-size:17px;';}echo '">'.$item->titre.'</div>

									<span style="color:gray;font-size:12px;">'.$item->texte.'</span>

									</div>

								</div>';
							}

							?>

					</div>
				</div>

				<div style="background: #f5f5f5;float:left;padding: 25px;width: 350px;border: 1px solid #eee;">

					<h3 style="background: #f5f5f5!important;"><?php _e("Main content", "wpappninja");?></h3>

					<?php _e("Display mode", "wpappninja");?>

					<select name="<?php echo WPAPPNINJA_SLUG;?>[typedevue]" id="wpappninja_select_typedevue">

									<option value="big" <?php if (get_wpappninja_option('typedevue', 'big') == 'big'){echo 'selected';} ?>><?php _e('Big cards', 'wpappninja');?></option>
									<option value="small" <?php if (get_wpappninja_option('typedevue', 'big') == 'small'){echo 'selected';} ?>><?php _e('Small cards', 'wpappninja');?></option>


							</select>

					<table class="form-table">

						<tr valign="top">
							<th scope="row"><?php _e("Image on lists", "wpappninja");?></th>
							<td><select id="wpappninja_disablefeat_list" name="<?php echo WPAPPNINJA_SLUG;?>[disablefeat]"><option value="1" <?php if (get_wpappninja_option('disablefeat', '0') == '1'){echo 'selected';} ?>><?php _e('HIDE', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('disablefeat', '0') == '0'){echo 'selected';} ?>><?php _e('SHOW', 'wpappninja');?></option></select></td>
						</tr>

										<tr valign="top" style="display:none">
							<th scope="row"><?php _e("Image on pages", "wpappninja");?></th>
							<td><select id="wpappninja_hideimgonlypage_list" name="<?php echo WPAPPNINJA_SLUG;?>[hideimgonlypage]"><option value="1" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '1'){echo 'selected';} ?>><?php _e('HIDE', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '0'){echo 'selected';} ?>><?php _e('SHOW', 'wpappninja');?></option></select></td>
						</tr>

					</table>


					<input type="submit" id="submitme" class="button button-primary button-large" />
				</div>

				<div style="clear:both"></div>
				</div>




				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_pagedesign').toggle();wpappninja_resizeIframe(document.getElementById('wpappninja_iframe'));"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e("Content", "wpappninja");?></h2>
				<div style="display:none" id="wpappninja_pagedesign">

					<p id="wpappninjahidemenotice" class="wpappninja_help"><?php _e('You can', 'wpappninja');?> <a href="#" onclick="wpappninja_open_editor();return false" style="font-size:13px"><?php _e('test on a different page', 'wpappninja');?></a>
					</p>

				<div class="wpappninja_div" style="overflow: hidden;width: 300px;float:left;margin-right: 40px;border: 30px solid #333;border-radius: 30px;box-shadow: 0 0 15px #555;">



					<div id="wpapp_color_primary" class="mini_android_toolbar" style="white-space: nowrap;font-size: 15px;padding: 18px 0 0 10px;width: 290px;height: 37px;color:white;background:<?php echo $app_theme_primary;?>">< <b><?php echo $json['titre'];?></b></div>
					<div class="wpappninja_noscroll" style="width:300px;height:450px;overflow:auto;overflow-x: hidden;">
						<div id="wpappninja_image" style="background:url(<?php echo $json['image'];?>) no-repeat center center;background-size:cover;width:300px;height:160px;"></div>
						<div id="wpappninja_titre" style="line-height:25px;font-size:25px;color:#333;padding:15px 10px;background:#fff"><?php echo $json['titre'];?></div>
						<div id="wpappninja_auteur" style="margin:15px 10px">
							<div style="float:left;" id="wpappninja_avatar"><img src="<?php echo $json['bio']['avatar'];?>" height="50" width="50" style="border-radius:99px;" /></div>
							<div style="float:left;margin: 7px 0 0 20px;">
								<div id="wpappninja_name" style="font-size:15px;"><?php echo $json['bio']['name'];?></div>
								<div id="wpappninja_date" style="color:gray;font-size:13px;"><?php echo $json['date'];?></div>
							</div>
							<div style="clear:both"></div>
						</div>
						<iframe src="about:blank" onload="wpappninja_resizeIframe(this)" id="wpappninja_iframe" width="300" height="100"></iframe>
						<div id="wpappninja_html" style="box-sizing: border-box;width:300px;height:5000px;padding:20px;overflow:auto;display:none;"><?php ?>-- Only text (no plugins) --</div>
					</div>

				</div>

				<div style="background: #f5f5f5;float:left;padding: 25px;width: 350px;border: 1px solid #eee;">




					<h3 style="background: #f5f5f5!important;"><?php _e("Main content", "wpappninja");?></h3>

					<?php _e("Display mode", "wpappninja");?>

					<select name="<?php echo WPAPPNINJA_SLUG;?>[webview]" id="wpappninja_select_webview">

								<option value="4" <?php if (get_wpappninja_option('webview', '0') == '4'){echo 'selected';} ?>><?php _e('WPMobile.App app (best)', 'wpappninja');?></option>
								<option value="0" <?php if (get_wpappninja_option('webview', '0') == '0'){echo 'selected';} ?>><?php _e('Simple (text only)', 'wpappninja');?></option>
								<option value="2" <?php if (get_wpappninja_option('webview', '0') == '2'){echo 'selected';} ?>><?php _e('Website theme (not app friendly)', 'wpappninja');?></option>


								<optgroup label="Deprecated">
									<option value="1" <?php if (get_wpappninja_option('webview', '0') == '1'){echo 'selected';} ?>><?php _e('Optimal', 'wpappninja');?></option>
								</optgroup>


							</select>

					<table class="form-table">

						<tr style="display:none" valign="top" <?php if ($json['image'] == "") {echo 'style="display:none"';}?>>
							<th scope="row"><?php _e("Image on lists", "wpappninja");?></th>
							<td><select id="wpappninja_disablefeat" name="<?php echo WPAPPNINJA_SLUG;?>[disablefeat]"><option value="1" <?php if (get_wpappninja_option('disablefeat', '0') == '1'){echo 'selected';} ?>><?php _e('HIDE', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('disablefeat', '0') == '0'){echo 'selected';} ?>><?php _e('SHOW', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top" <?php if ($json['image'] == "") {echo 'style="display:none"';}?>>
							<th scope="row"><?php _e("Image on pages", "wpappninja");?></th>
							<td><select id="wpappninja_hideimgonlypage" name="<?php echo WPAPPNINJA_SLUG;?>[hideimgonlypage]"><option value="1" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '1'){echo 'selected';} ?>><?php _e('HIDE', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '0'){echo 'selected';} ?>><?php _e('SHOW', 'wpappninja');?></option></select></td>
						</tr>


						

						<tr valign="top">
							<th scope="row"><?php _e('Title', 'wpappninja');?></th>
							<td><select id="wpappninja_remove_title" name="<?php echo WPAPPNINJA_SLUG;?>[remove_title]"><option value="1" <?php if (get_wpappninja_option('remove_title', '0') == '1'){echo 'selected';} ?>><?php _e('HIDE', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('remove_title', '0') == '0'){echo 'selected';} ?>><?php _e('SHOW', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Avatar and name', 'wpappninja');?></th>
							<td><select id="wpappninja_show_avatar" name="<?php echo WPAPPNINJA_SLUG;?>[show_avatar]"><option value="1" <?php if (get_wpappninja_option('show_avatar', '1') == '1'){echo 'selected';} ?>><?php _e('SHOW', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('show_avatar', '1') == '0'){echo 'selected';} ?>><?php _e('HIDE', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Date', 'wpappninja');?></th>
							<td><select id="wpappninja_show_date" name="<?php echo WPAPPNINJA_SLUG;?>[show_date]"><option value="1" <?php if (get_wpappninja_option('show_date', '1') == '1'){echo 'selected';} ?>><?php _e('SHOW', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('show_date', '1') == '0'){echo 'selected';} ?>><?php _e('HIDE', 'wpappninja');?></option></select></td>
						</tr>



						<tr>
						<input type="hidden" id="wpappninja_hide_me" name="wpappninja_hide_me" />
					</table>



					<input type="submit" id="submitme" class="button button-primary button-large" />
				</div>



				<div style="clear:both"></div>
				</div><?php */ ?>
</div>
<br/>
<input type="submit" id="submitme" class="button button-primary button-large" />


			</form>

			<?php
			echo '<div style="display:none">';
			wp_editor( "", 'wpappninja_dummy', array(
												'media_buttons' => true,
												'teeny' => false,
												'textarea_name' => 'wpappninja_dummy'
										) );
			echo '</div>';

			?>

		</div>
	</div>

	<div style="clear:both"></div>

	<script type="text/javascript">

	var wpappninja_configurator = <?php echo json_encode($json);?>;
	var wpappninja_current_mode = "";



	function wpappninja_auto() {

		<?php if ($formdisplay == 'block') { ?>

		if (wpappninja_current_mode != wpappninja_configurator.config.webview) {

			wpappninja_current_mode = wpappninja_configurator.config.webview;

			wpappninja_url = "about:blank";
			jQuery('#wpappninja_iframe').attr('src', wpappninja_url);
			jQuery('#wpappninja_iframe').css('display', 'block');
			jQuery('#wpappninja_html').css('display', 'none');

			jQuery('#wpappninjahidemenotice').css('display', 'block');

			if (wpappninja_configurator.config.webview == "2") {
				wpappninja_url = wpappninja_configurator.url + "?iswpappninjaconfigurator=true";
			} else if (wpappninja_configurator.config.webview == "0") {
				jQuery('#wpappninjahidemenotice').css('display', 'none');
				jQuery('#wpappninja_iframe').css('display', 'none');
				jQuery('#wpappninja_html').css('display', 'block');
			} else if (wpappninja_configurator.config.webview == "1") {
				wpappninja_url = wpappninja_configurator.url_optimal;
			} else if (wpappninja_configurator.config.webview == "4") {
				wpappninja_url = wpappninja_configurator.url + "?iswpappninjaconfigurator=true&wpappninja_simul4=true";
			}

			jQuery('#wpappninja_iframe').attr('src', wpappninja_url);
		}

		if (wpappninja_configurator.config.disablefeat == "1") {
			jQuery(".wpappninja_list_img").css('display', 'none');
		} else {
			jQuery(".wpappninja_list_img").css('display', 'inline-block');
		}

		if (wpappninja_configurator.config.hideimgonlypage == "1" || wpappninja_configurator.image == "") {
			jQuery("#wpappninja_image").css('display', 'none');
		} else {
			jQuery("#wpappninja_image").css('display', 'block');
		}


		if (wpappninja_configurator.config.remove_title == "1") {
			jQuery("#wpappninja_titre").css('display', 'none');
		} else {
			jQuery("#wpappninja_titre").css('display', 'block');
		}

		if (wpappninja_configurator.config.show_avatar == "0") {
			jQuery("#wpappninja_avatar").css('display', 'none');
			jQuery("#wpappninja_name").css('display', 'none');
		} else {
			jQuery("#wpappninja_avatar").css('display', 'block');
			jQuery("#wpappninja_name").css('display', 'block');
		}

		if (wpappninja_configurator.config.show_date == "0") {
			jQuery("#wpappninja_date").css('display', 'none');
		} else {
			jQuery("#wpappninja_date").css('display', 'block');
		}

		if (wpappninja_configurator.config.show_date == "0" && wpappninja_configurator.config.show_avatar == "0") {
			jQuery("#wpappninja_auteur").css('display', 'none');
		} else {
			jQuery("#wpappninja_auteur").css('display', 'block');
		}

		<?php } ?>
	}

	function wpappninja_open_editor() {
   		wpActiveEditor = true;
   		wpLink.open('wpappninja_dummy_textarea');
        return false;
    }

    jQuery('body').on('click', '#wp-link-submit', function(event) {
		var linkAtts = wpLink.getAttrs();
        

        document.location = "admin.php?page=<?php echo WPAPPNINJA_AUTO_SLUG;?>&url=" + linkAtts.href;

        wpLink.textarea = jQuery('body');
	    wpLink.close();
	    event.preventDefault ? event.preventDefault() : event.returnValue = false;
        event.stopPropagation();
    	return false;
	});

	jQuery('body').on('click', '#wp-link-cancel, #wp-link-close', function(event) {
		wpLink.textarea = jQuery('body');
	    wpLink.close();
        event.preventDefault ? event.preventDefault() : event.returnValue = false;
    	event.stopPropagation();
    	return false;
    });

	jQuery(document).ready(function($){
	jQuery( "#wpappninja_select_webview" ).change(function() {
  		wpappninja_configurator.config.webview = this.value;
  		wpappninja_auto();
	});

	jQuery( "#wpappninja_disablefeat" ).change(function() {
  		wpappninja_configurator.config.disablefeat = this.value;
  		wpappninja_auto();
  		jQuery("#wpappninja_disablefeat_list").val(this.value);
	});

	jQuery( "#wpappninja_hideimgonlypage" ).change(function() {
  		wpappninja_configurator.config.hideimgonlypage = this.value;
  		wpappninja_auto();
  		jQuery("#wpappninja_hideimgonlypage_list").val(this.value);
	});
	
	jQuery( "#wpappninja_disablefeat_list" ).change(function() {
  		if (this.value == "1") {
  			jQuery(".wpappninja_list_img").css('display', 'none');
  		} else {
 			jQuery(".wpappninja_list_img").css('display', 'inline-block');
  		}

  		jQuery("#wpappninja_disablefeat").val(this.value);
	});
	jQuery( "#wpappninja_select_typedevue" ).change(function() {

  		if (this.value == "small") {
  			jQuery(".wpappninja_list_width").css('width', 'Calc(100% - 100px)');
  			jQuery(".wpappninja_list_img").css('margin-top', '20px');
  			jQuery(".wpappninja_list_img").css('width', '60px');
  			jQuery(".wpappninja_list_img").css('height', '60px');
  			jQuery(".wpappninja_list_item").css('height', '100px');
  			//jQuery(".wpappninja_list_img").css('display', 'inline-block');
  			jQuery(".wpappninja_list_titre").css('font-size', '16px');
  		} else {
  			jQuery(".wpappninja_list_width").css('width', '100%');
  			jQuery(".wpappninja_list_img").css('width', '100%');
  			jQuery(".wpappninja_list_img").css('margin-top', '0px');
  			jQuery(".wpappninja_list_img").css('height', '70px');
  			jQuery(".wpappninja_list_item").css('height', '150px');
  			//jQuery(".wpappninja_list_img").css('display', 'block');
  			jQuery(".wpappninja_list_titre").css('font-size', '18px');
  		}
	});

	jQuery( "#wpappninja_remove_title" ).change(function() {
  		wpappninja_configurator.config.remove_title = this.value;
  		wpappninja_auto();
	});

	jQuery( "#wpappninja_show_avatar" ).change(function() {
  		wpappninja_configurator.config.show_avatar = this.value;
  		wpappninja_auto();
	});

	jQuery( "#wpappninja_show_date" ).change(function() {
  		wpappninja_configurator.config.show_date = this.value;
  		wpappninja_auto();
	});
	var wpapp_color_primary = {
	    change: function(event, ui){
	    	jQuery(".mini_android_toolbar").css( 'background-color', ui.color.toString());
	    },
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-primary").wpColorPicker(wpapp_color_primary);

	var wpapp_color_accent = {
	    change: function(event, ui){
	    	jQuery(".wpappicon").css( 'color', ui.color.toString());
	    	jQuery(".wpappninja_colorme").css( 'color', ui.color.toString());
	    },
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-accent").wpColorPicker(wpapp_color_accent);

	wpappninja_auto();
	});
    </script>

    <style type="text/css">
    #wpappninja_html img {max-width:100%;height:auto!important;}
    .wpappiosicon{width:24%;text-align:center;display:inline-block;padding:15px 0 22px;}
    .wpappiosicon span {font-size:27px;}
    .has-text-field #wp-link .query-results {top: 80px!important;}
    #link-options, #link-options .link-target {display:none!important;}
    .wpappninja_noscroll::-webkit-scrollbar{display:none;}
    .wpappninja_colorme{color:<?php echo $app_theme_accent;?>}
    </style>

	<?php
	echo wpappninja_talkus();
}



add_action( 'admin_init', 'wpappninja_tinymce_button' );
function wpappninja_tinymce_button() {
	/*if ( !isset($_GET['page']) && $_GET['page'] != "wpappninja_auto" ) {
        return false;
    }*/

    if (get_wpappninja_option('speed') == '1' && (get_wpappninja_option('wpappninja_main_theme') == 'WPMobile.App' || get_wpappninja_option('appify') == '1')) {
	    add_filter( 'mce_external_plugins', 'wpappninja_script_tiny' );
		add_filter( 'mce_buttons', 'wpappninja_register_button' );
	}
}

function wpappninja_register_button( $buttons ) {
	array_push( $buttons, '|', 'wpappninja_widgets' );
	return $buttons;
}

function wpappninja_script_tiny( $plugin_array ) {
	$plugin_array['wpappninja_editor'] = WPAPPNINJA_ASSETS_URL . 'js/tinymce.js';
	return $plugin_array;
}

add_action( 'admin_init', 'add_wpappninja_styles_to_editor' );
function add_wpappninja_styles_to_editor() {
	global $editor_styles;
	$editor_styles[] = WPAPPNINJA_ASSETS_URL . 'css/tinymce.css';
}

function wpappninja_home_cmp_a($a, $b) {
    return strcmp($a->taxonomy . $a->name, $b->taxonomy. $b->name);
}
