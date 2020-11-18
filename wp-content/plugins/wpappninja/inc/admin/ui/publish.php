<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Publish the app on store
 *
 * @since 4.0.5
 */
function _wpappninja_display_publish_page() {

	if (isset($_GET['magic'])) {
		foreach(get_wpappninja_option('lang_exclude', array()) as $lang) {
			wpappninja_magic_import($lang);
		}
	}

	?>
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
}		h2{color:#555;font-size:33px;}
#wpappninja_app_store_data{max-width:100%!important;}
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

					</style><?php
	
	// is publishing ready?	
	$current_user = wp_get_current_user();
	$category = array('Books','Business','Catalogs','Education','Entertainment','Finance','Food &amp; Drink','Games','Health &amp; Fitness','Lifestyle','Magazines &amp; Newspapers','Medical','Music','Navigation','News','Photo &amp; Video','Productivity','Reference','Shopping','Social Networking','Sports','Travel','Utilities','Weather');
	$loc = array('fr-FR', 'en-US', 'de-DE', 'es-ES', 'it-IT', 'pt-PT');

	// remove '
	/*if (isset($_POST['wpappninja']['app']['name'])) {
		$_POST['wpappninja']['app']['name'] = str_replace("'", " ", stripslashes($_POST['wpappninja']['app']['name']));
	}*/
	if (isset($_POST['fastsplash'])) {
		$options = get_option(WPAPPNINJA_SLUG);
		$options['fastsplash'] = round($_POST['fastsplash']);
		update_option(WPAPPNINJA_SLUG, $options);

		echo '<div class="updated" style="padding: 25px;font-size: 22px;line-height: 30px;">'.__('To apply the name - logo - splashscreen change, you have to update the app on stores.', 'wpappninja').' <a href="https://support.wpmobile.app/article/269-update-the-app-name-logo-or-splashscreen" target="_blank">Guide</a></div>';
	}
	
	$app_data = (isset($_POST['wpappninja']['app']) && check_admin_referer('wpappninjapublication')) ? $_POST['wpappninja']['app'] : get_wpappninja_option('app');
	
	$app_user_name = isset($app_data['user']['name']) ? $app_data['user']['name'] : $current_user->user_firstname.' '.$current_user->user_lastname;
	$app_user_mail = isset($app_data['user']['mail']) ? $app_data['user']['mail'] : $current_user->user_email;
	$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();
	$app_url_home = isset($app_data['url']['home']) ? esc_url($app_data['url']['home']) : home_url() . '/';
	$app_url_contact = isset($app_data['url']['contact']) ? esc_url($app_data['url']['contact']) : home_url() . '/';
	$app_url_privacy = isset($app_data['url']['privacy']) ? esc_url($app_data['url']['privacy']) : home_url() . '/';
	$app_store_locale = isset($app_data['store']['locale']) ? $app_data['store']['locale'] : get_locale();
	$app_store_category = isset($app_data['store']['category']) ? $app_data['store']['category'] : "";
	$app_logo = (isset($app_data['logo']) && $app_data['logo'] != "") ? esc_url($app_data['logo']) : WPAPPNINJA_ASSETS_IMG_URL . 'os/empty.png';
	$splashscreen = (isset($app_data['splashscreen']) && $app_data['splashscreen'] != "") ? esc_url($app_data['splashscreen']) : WPAPPNINJA_ASSETS_IMG_URL . 'os/empty.png';



	$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
	$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";
	$app_store_intro = isset($app_data['store']['intro']) ? stripslashes($app_data['store']['intro']) : "";
	$app_store_text = isset($app_data['store']['text']) ? stripslashes($app_data['store']['text']) : "";
	$app_store_keywords = isset($app_data['store']['keywords']) ? $app_data['store']['keywords'] : "";
	$app_ios_background = isset($app_data['ios_background']) ? $app_data['ios_background'] : "#000000";

	update_option('wpappninja_primary', $app_theme_primary);
	update_option('wpappninja_secondary', $app_theme_accent);
	
	$app_display = 'block';
	if ($app_name != "" && $app_url_home != "" && $app_logo != "") {

		// if everything is ok, push to wpapp.ninja generate server on change
		$hash_data = sha1($app_name . $app_logo . $app_ios_background . $splashscreen);
		if (get_wpappninja_option('hash_data') != $hash_data) {
			
			// save if publish
			$options = get_option(WPAPPNINJA_SLUG);
			$options['app'] = $app_data;
			$options['hash_data'] = $hash_data;
			update_option(WPAPPNINJA_SLUG, $options);

			update_option('wpappninja_follow_tuto', '1');
			update_option('wpappninja_need_update', true);
		}
	} else {
		// save draft
		$options = get_option(WPAPPNINJA_SLUG);
		$options['app'] = $app_data;
		update_option(WPAPPNINJA_SLUG, $options);
	}
	

	?>
	<div class="wrap">
		<h1 style="right: 20px;margin: 20px 0 0;position: absolute;"></h1>
		<h2 style="font-size:1.3em"></h2>

		<?php $menu_current = 'publish';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>

		<div style="padding: 20px;background: white;margin: 0px 0;border-bottom: 1px solid #fd9b02;border-top: 3px solid #fd9b02;">
		<div id="wpm_select" style="display:none">
			<a style="
    display: inline-block;
    text-align: center;
    text-decoration: none;
    font-size: 20px;
    border: 1px solid #fd9b02;
    padding: 25px;
    margin: 0 25px 0 0;
    width: Calc(50% - 102px);
" href="#" onclick="jQuery('#wpm_select').css('display', 'none');jQuery('#wpm_auto').css('display', 'block');return false"><span class="dashicons dashicons-art" style="
    color: #fd9b02;
    font-size: 60px;
    width: 60px;
    height: 60px;
    margin: 0 0 20px 0;
"></span><br/><?php _e('Fast configuration', 'wpappninja');?></a>
			<a  style="
    display: inline-block;
    text-align: center;
    text-decoration: none;
    font-size: 20px;
    border: 1px solid #fd9b02;
    padding: 25px;
    margin: 0;
    width: Calc(50% - 102px);
" href="#" onclick="jQuery('#wpm_select').css('display', 'none');jQuery('#wpm_expert').css('display', 'block');return false"><span class="dashicons dashicons-admin-settings" style="
    color: #fd9b02;
    font-size: 60px;
    width: 60px;
    height: 60px;
    margin: 0 0 20px 0;
"></span><br/><?php _e('Expert mode', 'wpappninja');?></a>

		</div>


		<div id="wpm_auto" style="display:none">


			<style>iframe#wpappiframe {
    display: inline-block;
    margin-left: 60px;
    max-width: 2000px;
    width: 720px;
    border: 20px solid #333;
    height: 1480px;
    border-radius: 20px;
    zoom: 0.5;
    -ms-zoom: 0.75;
    -moz-transform: scale(0.75);
    -moz-transform-origin: 0 0;
    -o-transform: scale(0.75);
    -o-transform-origin: 0 0;
    -webkit-transform: scale(0.75);
    -webkit-transform-origin: 0 0;
    display:none;
}</style>



<a href="#" onclick="loadtheme('wpmobile');return false;">WPMobile.App</a>
<a href="#" onclick="loadtheme('overlay');return false;">Overlay</a>
<a href="#" onclick="loadtheme('website');return false;">Website</a>
<iframe name="wpappiframe" id="wpappiframe" src="about:blank"></iframe>

<script>
var wpmtesturl = "<?php echo get_home_url();?>";
var wpmtestparamter = "?wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=";
var currenttheme = "";

function loadiframe(urliframe) {
	jQuery('#wpappiframe').css('display', 'block');
	jQuery('#wpappiframe').attr('src', urliframe);
}

function loadtheme(theme) {
	if (theme == "wpmobile") {
		currenttheme = "WPMobile.App";
		url = wpmtesturl + wpmtestparamter + currenttheme;
	}

	if (theme == "overlay") {
		currenttheme = "WPMobile.App";
		url = wpmtesturl + wpmtestparamter + currenttheme + "&fakepwa=true";
	}

	if (theme == "website") {
		url = wpmtesturl;
	}

	loadiframe(url);
}
</script>





		</div>


		<div id="wpm_expert" style="display:block">
		


		<?php require( WPAPPNINJA_ADMIN_UI_PATH   . 'submenu.php' ); ?>



			<form action="" method="post">
				<?php wp_nonce_field( 'wpappninjapublication' );?>
				
				<div class="wpappninja_div">
						<?php
						$app_display = 'none';
						?>

						<div id="wpappninja_app_store_result">

						<?php if ((!wpappninja_is_paid() && !wpappninja_is_store_ready()) || 1>0) { 

							if (wpappninja_is_ready_to_start() || 1>0) {
							    $app_display = 'block';

								/*$json = '{"appname":"'.$app_name.'","primary":"'.str_replace('#', '', $app_theme_primary).'","accent":"'.str_replace('#', '', $app_theme_accent).'","baseurl":"'.$app_url_home.'"}';
								echo '<iframe id="wpappninja_appetize" src="https://appetize.io/embed/5daq1k40hv288v0tz3rqch9yqm?device=iphone6&scale=60&screenOnly=false&autoplay=true&orientation=portrait&deviceColor=white&language='.substr($app_store_locale, 0, 2).'&xdocMsg=true&params='.rawurlencode($json).'" style="width: 320px;height: 530px;" frameborder="0" scrolling="no"></iframe>'; ?>
							    <div style="display:inline-block;margin-top:30px;vertical-align:top">
							    	<a class="button button-primary button-large" href="#" style="line-height:40px;height:40px;font-size:26px;" onclick="jQuery('#wpappninja_app_store_data').toggle();jQuery('#wpappninja_app_store_result').toggle();return false"><?php _e('Ok! Publish my app', 'wpappninja');?></a><br/><br/>
							    	<a class="button button-primary button-large" href="?page=wpappninja" style="background: #b7b7b7;border: #B7B7B7;"><?php _e('Edit settings', 'wpappninja');?></a>
						    	</div>
							    <?php */

							} else { ?>
								<br/><br/>
								<h1>👋 <?php _e('Hello!', 'wpappninja'); ?></h1>

								<p><a href="?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>&magic=on"><?php _e('Automatically create my menu', 'wpappninja');?></a></p>
							<?php } ?>

							<div style="display:none">
						<?php } ?>

						
						<?php if (wpappninja_is_paid() && !get_option('wpappninja_app_published')) { ?>
							<a class="button button-primary button-large" href="#" style="background: #b7b7b7;border: #B7B7B7;cursor: not-allowed;" onclick="return false"><?php _e('Edit', 'wpappninja');?></a>
						<?php } else { ?>
							<a class="button button-primary button-large" href="#" onclick="jQuery('#wpappninja_app_store_data').toggle();jQuery('#wpappninja_app_store_result').toggle();return false"><?php _e('Edit', 'wpappninja');?></a>
						<?php } ?>
						<br/><br/>
						
						<h2><?php _e('Your app', 'wpappninja');?></h2>
						<div style="background:#f5f5f5;border:1px solid #eee;padding:20px;max-width:500px;" class="wpapp_admin_w100">
							<div style="float:left;margin-left:20px;">Android<br/><img src="<?php echo $app_logo;?>" style="width:128px;height:128px" /></div>
							<div style="float:left;margin-left:35px;">iOS<br/><img src="<?php echo $app_logo;?>" style="width:128px;height:128px;border-radius:35px;background:<?php echo $app_ios_background;?>" /></div>
							<div style="float:left;margin:10px 0 0 20px;width: Calc(90% - 128px);">
								<b style="font-size:23px;"><?php echo $app_name;?></b> <sup><?php echo $app_store_locale;?></sup><br/><br/>
								<span style="font-size:22px;"><?php echo $app_store_intro;?></span><br/><br/>
								<span style="font-size:12px;"><?php echo nl2br($app_store_text);?></span><br/><br/>
								
								<a href="<?php echo $app_url_home;?>" target="_blank"><?php _e('Homepage', 'wpappninja');?></a> - 
								<a href="<?php echo $app_url_contact;?>" target="_blank"><?php _e('Support', 'wpappninja');?></a> - 
								<a href="<?php echo $app_url_privacy;?>" target="_blank"><?php _e('Privacy policy', 'wpappninja');?></a>
								
								<br/><br/>
								
								<i><?php echo __('Category:', 'wpappninja').' <b>' . $app_store_category . '</b> &bull; ' . __('Keywords:', 'wpappninja').' <b>'.$app_store_keywords.'</b>';?></i>
							</div>
							<div style="clear:both"></div>
						</div>

						<br/><br/>
						<h2><?php _e('Your app theme', 'wpappninja');?></h2>
						<div style="background:#fdfdfd;border:1px solid #eee;padding:20px;max-width:500px;" class="wpapp_admin_w100">
						<div class="mini_android"><div class="mini_android_toolbar" style="background:<?php echo $app_theme_primary;?>"></div><div class="mini_android_bubble" style="background:<?php echo $app_theme_accent;?>"></div></div></div>

						<br/><br/>
						
						<h2><?php _e('Contact info', 'wpappninja');?></h2>
						<div style="background:#fdfdfd;border:1px solid #eee;padding:20px;max-width:500px;" class="wpapp_admin_w100">
							<div style="float:left"><?php echo get_avatar( $app_user_mail , 55 );?></div>
							<div style="float:left;margin:10px 0 0 20px;">
								<b style="font-size:20px;"><?php echo $app_user_name;?></b><br/>
								<?php echo $app_user_mail;?>
							</div>
							<div style="clear:both"></div>
						</div>

						<br/><br/>


						<br/><br/>

						<?php if ((!wpappninja_is_paid() && !wpappninja_is_store_ready())  || 1>0) { ?>
						</div>
						<?php } ?>
						
						<?php if (wpappninja_is_paid() && 1>2) { ?>
							<a href="https://wpmobile.app/<?php if (preg_match('#fr#', get_locale())) {echo 'product/mise-a-jour/';}else{echo 'en/product/update-app/';}?>?source=<?php echo $app_url_home;?>"><?php _e('Buy an update', 'wpappninja');?></a>&nbsp;&nbsp;&nbsp;<a href="https://wpmobile.app/<?php if (preg_match('#fr#', get_locale())) {echo 'product/suppression/';}else{echo 'en/product/removal/';}?>?source=<?php echo $app_url_home;?>" style="color:darkred"><?php _e('Delete and unpublish', 'wpappninja');?></a>
						<?php } ?>
						</div>
					
					<div id="wpappninja_app_store_data" style="display:<?php echo $app_display;?>">

					<!--<p class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;"><?php _e("Any change on this page <u>after the first publication</u> will be billed 49€.<br/><b>Take your time and make sure everything is ok.</b>", 'wpappninja');?></p>-->
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('App design', 'wpappninja');?></h2>
<div class="wpappninja_div">
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Name', 'wpappninja');?> <span class="wpappninja_required"></span>
	</div>
	<div class="wpappninja-builder-right">

			<input name="wpappninja[app][name]" type="text" id="wpappninja_name_count_" maxlength="30" value="<?php echo $app_name;?>" required /><br/><span id="wpappninja_name_count"></span>

	</div>
	<div class="clear"></div>
</div>


					

					<input name="wpappninja[app][url][home]" type="hidden" value="<?php echo $app_url_home;?>" readonly />
					
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Logo', 'wpappninja');echo ' <span class="wpappninja_required"></span><br/><b>';_e('Optimal size: 512x512 pixels', 'wpappninja');echo '</b>';?>
	</div>
	<div class="wpappninja-builder-right">


					<div class="uploader">
						<input id="blog_logo" name="wpappninja[app][logo]" type="text" value="<?php echo $app_logo?>" required />
						<input id="blog_logo_button" class="button" name="blog_logo_button" type="text" value="<?php _e('Choose a logo', 'wpappninja');?>" />
					</div>
					<br/>
					<div style="background:#fdfdfd;border:1px solid #eee;padding:20px;max-width:500px;" class="wpapp_admin_w100">
						<div style="float:left;margin-left:20px;">Android<br/><img class="wpapp_logo_place" src="<?php echo $app_logo;?>" style="width:128px;height:128px" /></div>
						<div style="float:left;margin-left:35px;">iOS<br/><img class="wpapp_logo_place" src="<?php echo $app_logo;?>" style="width:128px;height:128px;border-radius:35px;background:<?php echo $app_ios_background;?>" id="wpapp_back_logo" /></div>
						<div style="clear:both"></div>
					</div>
					
	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Logo background color', 'wpappninja');?> <span class="wpappninja_required"></span>
	</div>
	<div class="wpappninja-builder-right">

					<input type="text" name="wpappninja[app][ios_background]" value="<?php echo $app_ios_background;?>" class="wpapp-color-picker-logo" />



	</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Launchscreen', 'wpappninja');echo ' <span class="wpappninja_required"></span><br/><b>';_e('Optimal size: 600x900 pixels', 'wpappninja');echo '</b>';?>
	</div>
	<div class="wpappninja-builder-right">

					<div class="uploader">
						<input id="blog_splashscreen" name="wpappninja[app][splashscreen]" type="text" value="<?php echo $splashscreen?>"  />
						<input id="blog_splashscreen_button" class="button" name="blog_splashscreen_button" type="text" value="<?php _e('Choose an image', 'wpappninja');?>" />
					</div>
<br/>

					<div style="background:#fdfdfd;border:1px solid #eee;padding:20px;max-width:500px;" class="wpapp_admin_w100">

						<?php
						if ($splashscreen == "" OR preg_match('#/wpappninja/assets/images/os/empty\.png$#', $splashscreen)) {
							$splashscreen = "https://my.wpmobile.app/_launchscreen.php?c=" . str_replace('#', '', $app_theme_primary) . "&l=" . $app_logo;
						} ?>

						<div style="margin-left:20px;"><img class="wpapp_splashscreen_place" src="<?php echo $splashscreen;?>" style="width:128px;" /></div>
					</div>
					<br/><br/>
					
					<?php _e('Show the splashscreen for maximum', 'wpappninja');?> <input type="number" name="fastsplash" value="<?php echo get_wpappninja_option('fastsplash', '500');?>" style="width:70px;"/>ms (1000 = 1s) 

	</div>
	<div class="clear"></div>
</div>

					
					<input type="hidden" name="wpappninja[app][theme][primary]" value="<?php echo $app_theme_primary;?>"  />
					<input type="hidden" name="wpappninja[app][theme][accent]" value="<?php echo $app_theme_accent;?>"  />

					<?php
					$lang = 'en';
					$lang_wp = substr(get_locale(), 0, 2);
					if (in_array($lang_wp, array('fr', 'en', 'de', 'it', 'pt', 'es'))) {
						$lang = $lang_wp;
					} ?>
					<input type="hidden" name="wpappninja[app][store][locale]" value="<?php echo $lang . '-' . strtoupper($lang);?>" />
					<?php /*
					<h3><?php _e('Your app on the stores', 'wpappninja');?></h3>

					<p class="wpappninja_help"><?php _e("Texts for the App Store pages, be descriptive and commercial", 'wpappninja');?></p>

					<!--<?php _e('Intro text', 'wpappninja');?> (max 80 characters) <span class="wpappninja_required"></span><br/>
					<input type="text" style="width:100%" maxlength="80" id="wpappninja_intro_count_" name="wpappninja[app][store][intro]" value="<?php echo $app_store_intro; ?>" required /><br/><span id="wpappninja_intro_count"></span><br/><br/>-->
					
					<?php _e('Description', 'wpappninja');?> (min. 300 characters, max 4000) <span class="wpappninja_required"></span><br/>
					<textarea style="width:100%;height:100px;" id="wpappninja_text_count_" name="wpappninja[app][store][text]" required><?php echo $app_store_text;?></textarea><br/><span id="wpappninja_text_count"></span><br/><br/>

					<?php _e('Keywords (comma separated)', 'wpappninja');?> <span class="wpappninja_required"></span><br/>
					<input type="text" maxlength="80" name="wpappninja[app][store][keywords]" value="<?php echo $app_store_keywords;?>" required /><br/><br/>
					
					<?php _e('Category', 'wpappninja');?> <span class="wpappninja_required"></span><br/>
					<select name="wpappninja[app][store][category]" required>
						<?php
						foreach ($category as $cat) {
							echo '<option ';if (htmlentities($app_store_category) == $cat){echo 'selected';}echo '>'.$cat.'</option>';
						}
						?>
					</select><br/>
					
					<br/>
					<?php _e('Support/Contact url', 'wpappninja');?><br/>
					<input name="wpappninja[app][url][contact]" type="url" value="<?php echo $app_url_contact;?>" /><br/><br/>
					<?php _e('Privacy policy url', 'wpappninja');?><br/>
					<input name="wpappninja[app][url][privacy]" type="url" value="<?php echo $app_url_privacy;?>" /><br/>
					
					<br/>
					<?php _e('Main langague', 'wpappninja');?> <span class="wpappninja_required"></span><br/>
					<select name="wpappninja[app][store][locale]" required>
						<?php
						foreach ($loc as $l) {
							echo '<option ';if($app_store_locale == $l) {echo 'selected';}echo '>'.$l.'</option>';
						}
						?>
					</select>
					
					<br/>

					
					<h3 id="about"><?php _e('About you', 'wpappninja');?></h3>

					<p class="wpappninja_help"><?php _e("We're Isaure and Amauri, happy to meet you! What's your name? :-)", 'wpappninja');?></p>

					<?php _e('First and last name', 'wpappninja');?> <span class="wpappninja_required"></span><br/>
					<input name="wpappninja[app][user][name]" type="text" value="<?php echo $app_user_name;?>" required /><br/><br/>
					<?php _e('Email', 'wpappninja');?> <span class="wpappninja_required"></span><br/>
					<input name="wpappninja[app][user][mail]" type="email" value="<?php echo $app_user_mail;?>" required /><br/>
					<br/><br/>
					*/ ?>


					<br/>
					<input type="submit" id="submitme" class="button button-primary button-large" />



					</div>


					</div>
				</div>
			</form>
		</div>

	</div>
	</div>
	
	<script type="text/javascript">
	jQuery(document).ready(function($){


    /* jQuery("#wpappninja_text_count_").keyup( function() {
        if(jQuery(this).val().length > 4000){
            jQuery(this).val(jQuery(this).val().substr(0, 4000));
        }

        if(jQuery(this).val().length < 300){
            jQuery("#wpappninja_text_count").css('color', 'red');
        } else {
        	jQuery("#wpappninja_text_count").css('color', 'green');
        }

    	jQuery("#wpappninja_text_count").text(jQuery("#wpappninja_text_count_").val().length + "/4000");
	 });
	 jQuery("#wpappninja_text_count").text(jQuery("#wpappninja_text_count_").val().length + "/4000");*/

     /*jQuery("#wpappninja_intro_count_").keyup( function() {
        if(jQuery(this).val().length > 80){
            jQuery(this).val(jQuery(this).val().substr(0, 80));
        }

        jQuery("#wpappninja_intro_count").css('color', 'green');

    	jQuery("#wpappninja_intro_count").text(jQuery("#wpappninja_intro_count_").val().length + "/80");
	 });
	 jQuery("#wpappninja_intro_count").text(jQuery("#wpappninja_intro_count_").val().length + "/80");*/

     jQuery("#wpappninja_name_count_").keyup( function() {
        if(jQuery(this).val().length > 30){
            jQuery(this).val(jQuery(this).val().substr(0, 30));
        }

        jQuery("#wpappninja_name_count").css('color', 'green');

    	jQuery("#wpappninja_name_count").text(jQuery("#wpappninja_name_count_").val().length + "/30");
	 });
	 jQuery("#wpappninja_name_count").text(jQuery("#wpappninja_name_count_").val().length + "/30");



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

	$('#blog_splashscreen_button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$("#"+id).val(attachment.url);
				$(".wpapp_splashscreen_place").attr("src", attachment.url);
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
	
	var wpapp_color_primary = {
	    change: function(event, ui){
	    	jQuery("#wpapp_color_primary").css( 'background-color', ui.color.toString());
	    },
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-primary").wpColorPicker(wpapp_color_primary);

	var wpapp_color_accent = {
	    change: function(event, ui){
	    	jQuery("#wpapp_color_accent").css( 'background-color', ui.color.toString());
	    },
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-accent").wpColorPicker(wpapp_color_accent);

	var wpapp_color_logo = {
	    change: function(event, ui){
	    	jQuery("#wpapp_back_logo").css( 'background-color', ui.color.toString());
	    },
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-logo").wpColorPicker(wpapp_color_logo);


	});
	</script>
	<?php
	echo wpappninja_talkus();
}
