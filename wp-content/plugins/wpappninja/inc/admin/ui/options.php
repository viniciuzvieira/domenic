<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * The main settings page construtor
 *
 * @since 1.0
 */
function _wpappninja_display_options_page() {

	set_transient( 'is_wpappninja_ajax', true, 60*60 );
	
	// import menu
	$wpappninja_array_lang = array();
	$wpappninja_array_lang['speed'] = 'speed';



if (get_wpappninja_option('wpappninjaddlang') != '') {
	$lang = get_wpappninja_option('wpappninjaddlang');

	global $wpdb;
	$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_stats_users (`id`, `lang`) VALUES (%s, %s)", uniqid(), $lang));
			$option = get_option(WPAPPNINJA_SLUG);
	$option['wpappninjaddlang'] = '';
	update_option(WPAPPNINJA_SLUG, $option);

}


	/*if (get_wpappninja_option('speed') == '1') {
			$wpappninja_array_lang = array('English' => 'en');
	}*/
	
	foreach($wpappninja_array_lang as $lang) {
		$import_menu = get_wpappninja_option('import_menu_' . $lang, '');
		if ($import_menu != '') {
			wpappninja_import_menu($import_menu, $lang);
		}

		$import_homepage = get_wpappninja_option('import_homepage_' . $lang, '');
		if ($import_homepage != '') {
			wpappninja_import_homepage($lang);
		}

		$add_link = get_wpappninja_option('add_link_' . $lang, '');
		if ($add_link != '') {
			wpappninja_add_link($add_link, $lang);
		}

		$add_link_homepage = get_wpappninja_option('add_link_homepage_' . $lang, '');
		if ($add_link_homepage != '') {
			wpappninja_add_link_homepage($add_link_homepage, $lang);
		}
	}

	// tinymce
	add_filter( 'mce_buttons','wpappninja_tinymce1_buttons' );
	function wpappninja_tinymce1_buttons( $buttons ){
		$remove = array('hr', 'alignleft', 'aligncenter', 'alignright', 'wp_more', 'spellchecker', 'fullscreen', 'wp_adv');
		return array_diff( $buttons, $remove );
	}

	add_filter( 'mce_buttons_2','wpappninja_tinymce2_buttons' );
	function wpappninja_tinymce2_buttons( $buttons ){
		$remove = array('alignjustify', 'forecolor', 'outdent', 'indent', 'wp_help' );
		return array_diff( $buttons, $remove );
	}
 
	// delete transient
	if (isset($_GET['settings-updated'])) {
		wpappninja_clear_cache();
	}
	
	wpappninja_wpml_fix();
	
	//$pages = get_posts(array('fields' => 'ids', 'posts_per_page' => -1, 'post_type' => array('page')));
	//$posts = get_posts(array('fields' => 'ids', 'posts_per_page' => 30, 'orderby' => 'date'));

	$produ_type = get_post_types(array('public'=>true));
	$produ = get_posts(array('posts_per_page' => 30, 'orderby' => 'modified', 'post_type' => $produ_type));
	?>	<style type="text/css">
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
}		h2{color:#555;font-size:33px!important;}
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

					</style>
	<div class="wrap">
		<h1 style="right: 20px;margin: 20px 0 0;position: absolute;"></h1>
		<h2 style="font-size:1.3em!important"></h2>
		
		<?php $menu_current = 'settings';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>

		<?php if (isset($_GET['erreur'])) {?>
			<div class="error"><p><?php _e('Please review the form', 'wpappninja');?></p></div>
		<?php } ?>

		<?php
		
		// First configuration, send data to wpappninja server for compilation
		$firstinstall = false;
			
		if (1<0) {

		} else {
			
			// is publishing ready?
			$is_app_store_ready = false;
			$current_user = wp_get_current_user();
			$category = array('Books','Business','Catalogs','Education','Entertainment','Finance','Food &amp; Drink','Games','Health &amp; Fitness','Lifestyle','Magazines &amp; Newspapers','Medical','Music','Navigation','News','Photo &amp; Video','Productivity','Reference','Shopping','Social Networking','Sports','Travel','Utilities','Weather');
			$loc = array('fr-FR', 'en-US', 'de-DE', 'es-ES', 'it-IT', 'pt-PT');
			
			$app_data = get_wpappninja_option('app');
			$app_user_name = isset($app_data['user']['name']) ? $app_data['user']['name'] : $current_user->user_firstname.' '.$current_user->user_lastname;
			$app_user_mail = isset($app_data['user']['mail']) ? $app_data['user']['mail'] : $current_user->user_email;
			$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();
			$app_url_home = isset($app_data['url']['home']) ? esc_url($app_data['url']['home']) : home_url() . '/';
			$app_url_contact = isset($app_data['url']['contact']) ? esc_url($app_data['url']['contact']) : home_url() . '/';
			$app_url_privacy = isset($app_data['url']['privacy']) ? esc_url($app_data['url']['privacy']) : home_url() . '/';
			$app_store_locale = isset($app_data['store']['locale']) ? $app_data['store']['locale'] : get_locale();
			$app_store_category = isset($app_data['store']['category']) ? $app_data['store']['category'] : "";
			$app_logo = isset($app_data['logo']) ? esc_url($app_data['logo']) : "";
			$splashscreen = isset($app_data['splashscreen']) ? esc_url($app_data['splashscreen']) : "";
			$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
			$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";
			$app_store_intro = isset($app_data['store']['intro']) ? $app_data['store']['intro'] : "";
			$app_store_text = isset($app_data['store']['text']) ? $app_data['store']['text'] : "";
			$app_store_keywords = isset($app_data['store']['keywords']) ? $app_data['store']['keywords'] : "";
			$app_ios_background = isset($app_data['ios_background']) ? $app_data['ios_background'] : "#000000";
			
			$app_display = 'block';

			// verif theme premium
			$hasPremiumTheme = true;
			$primary = '#0f53a6';
			$secondary = '#dd9933';
			$premium_margin = '20px 0 25px 115px';
			
			// Show a message about the updating delay
			if (!get_wpappninja_option('disclameeer') && 1<0) { ?>
				<div id="wpappninja_disclamer">
					<h2><?php _e('Important notice', 'wpappninja');?></h2>
					<p><?php _e('To optimize performance, the application uses a cache system. Changes made from WordPress can take up to 1 hour to be available on apps.', 'wpappninja');?></p>
					<a style="" href="#" onclick="jQuery(this).parent().toggle();return false"><?php _e('GOT IT', 'wpappninja');?></a>
				</div>
			<?php }
			
			// Make the conf as ok
			$options = get_option(WPAPPNINJA_SLUG);
			$options['configureok'] = 1;
			$options['disclameeer'] = 1;
			update_option(WPAPPNINJA_SLUG, $options);
			
			?>
 
			<form method="post" action="options.php" id="wpappninja_form" style="border-bottom: 1px solid #fd9b02;border-top: 3px solid #fd9b02;">

<div style="padding:20px;">
<?php require( WPAPPNINJA_ADMIN_UI_PATH   . 'submenu.php' ); ?>
</div>
			<?php
			settings_fields( WPAPPNINJA_SLUG );
			do_settings_sections( WPAPPNINJA_SLUG );
			?>
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[version_app]" value="<?php echo round(get_wpappninja_option('version_app', 1) + 1); ?>" />
			<div class="wpappninja_left_panel" <?php if (isset($_GET['onlymenu']) || isset($_GET['onlymenu_trad'])) {echo 'style="display:none"';} ?>>
			
				<?php
				if(wpappninja_is_paid()) { ?>
					<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[ispaid]" value="1" />
				<?php } ?>
				
				<br/>
				
				<?php
				$default_lang_array = array_values($wpappninja_array_lang);
				$default_lang = $default_lang_array[0];
				
				$lang_incomplete = false;
				$available_lang = get_wpappninja_option('lang_exclude', array());
				
				echo '<a class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, \'lang\');return false" id="wpappninja_label_lang"';if (get_wpappninja_option('speed') == '1') {echo ' style="display:none"';}echo '><span class="dashicons dashicons-flag"></span>';
				if (count($available_lang) == 0) {
					echo '<span style="color:red" class="dashicons dashicons-warning"></span>';
				} else {
					echo '<span style="color:green" class="dashicons dashicons-yes"></span>';
				}
				?><br/><?php _e('Language(s) of the app', 'wpappninja');?></a>

				<?php if (get_wpappninja_option('theme', 'premium') != 'premium') { ?><a class="wpappninja_item" href="#" id="wpappninja_label_theme" onclick="wpappninja_toggle(this, 'theme');return false"><span class="dashicons dashicons-admin-appearance"></span><br/><?php _e('Colors of the app', 'wpappninja');?></a> <?php } elseif (1<0) { ?><a class="wpappninja_item" href="#" id="wpappninja_label_themenew" onclick="wpappninja_toggle(this, 'themenew');return false"><span class="dashicons dashicons-admin-appearance"></span><br/><?php _e('Colors of the app', 'wpappninja');?></a> <?php } ?>
				
				<?php
				if (
					(count(wpappninja_get_menu_reloaded('fr')) == 0 && in_array('fr', $available_lang)) ||
					(count(wpappninja_get_menu_reloaded('de')) == 0 && in_array('de', $available_lang)) ||
					(count(wpappninja_get_menu_reloaded('en')) == 0 && in_array('en', $available_lang)) ||
					(count(wpappninja_get_menu_reloaded('it')) == 0 && in_array('it', $available_lang)) ||
					(count(wpappninja_get_menu_reloaded('pt')) == 0 && in_array('pt', $available_lang)) ||
					(count(wpappninja_get_menu_reloaded('es')) == 0 && in_array('es', $available_lang))
				) {$lang_incomplete = true;}
				?>

				
				<a style="display:none" class="wpappninja_item" id="wpappninja_label_menu" href="#" onclick="wpappninja_toggle(this, 'menu_<?php echo $default_lang;?>');return false"><span class="dashicons dashicons-menu"></span><?php if($lang_incomplete){echo '<span style="color:red" class="dashicons dashicons-warning"></span>';} else {echo '<span style="color:green" class="dashicons dashicons-yes"></span>';}?><br/><?php _e('Home page and menu', 'wpappninja');?></a>
				<a style="display:none" class="wpappninja_item" id="wpappninja_label_warning" href="#" onclick="wpappninja_toggle(this, 'warning');return false"><span class="dashicons dashicons-menu"></span><?php if($lang_incomplete){echo '<span style="color:red" class="dashicons dashicons-warning"></span>';} else {echo '<span style="color:green" class="dashicons dashicons-yes"></span>';}?><br/><?php _e('Home page and menu', 'wpappninja');?></a>

	<?php if (isset($_GET['onlymenu'])) {



		/*if (get_wpappninja_option('speed') == '1' && 
			get_wpappninja_option('webview') == '4' && 
			get_wpappninja_option('speed_notheme') == '1' && 
			get_wpappninja_option('nospeed_notheme') == '0') {

			echo '<script type="text/javascript">wpappninja_go_toggle = "0";jQuery(function() {wpappninja_toggle(document.getElementById(\'wpappninja_label_warning\'), \'warning\');});</script>';
		} else {*/
			echo '<script type="text/javascript">wpappninja_go_toggle = "0";jQuery(function() {wpappninja_toggle(document.getElementById(\'wpappninja_label_menu\'), \'menu_'.$default_lang.'\');});</script>';
		//}

		



	} ?>

								<?php if (isset($_GET['onlymenu_trad']) ) {echo '<script type="text/javascript">wpappninja_go_toggle = "0";jQuery(function() {wpappninja_toggle(document.getElementById(\'wpappninja_label_lang\'), \'lang\');});</script>';} ?>


				<a  class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, 'banner');return false" id="wpappninja_label_banner"><span class="dashicons dashicons-download"></span><br/><?php _e('Smart banner', 'wpappninja');?></a>

				<a <?php if (get_wpappninja_option('speed') == '1') {echo ' style="display:none"';} ?> class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, 'webview');return false" id="wpappninja_label_webview"><span  class="dashicons dashicons-admin-site"></span><br/><?php _e('Main content', 'wpappninja');?></a>

				<!--<a  class="wpappninja_item" href="#" id="wpappninja_label_rating" onclick="wpappninja_toggle(this, 'rating');return false"><span class="dashicons dashicons-star-filled"></span><?php 
				if (
					((get_wpappninja_option('rating_titre_fr', '') == '' || get_wpappninja_option('rating_texte_fr', '') == '') && in_array('fr', $available_lang)) || 
					((get_wpappninja_option('rating_titre_en', '') == '' || get_wpappninja_option('rating_texte_en', '') == '') && in_array('en', $available_lang)) || 
					((get_wpappninja_option('rating_titre_de', '') == '' || get_wpappninja_option('rating_texte_de', '') == '') && in_array('de', $available_lang)) || 
					((get_wpappninja_option('rating_titre_it', '') == '' || get_wpappninja_option('rating_texte_it', '') == '') && in_array('it', $available_lang)) || 
					((get_wpappninja_option('rating_titre_es', '') == '' || get_wpappninja_option('rating_texte_es', '') == '') && in_array('es', $available_lang)) || 
					((get_wpappninja_option('rating_titre_pt', '') == '' || get_wpappninja_option('rating_texte_pt', '') == '') && in_array('pt', $available_lang))
				) {
					echo '<span style="color:red" class="dashicons dashicons-warning"></span>';
				} else {
					echo '<span style="color:green" class="dashicons dashicons-yes"></span>';
				}
				?><br/><?php _e('Rating popup', 'wpappninja');?></a>-->

				<a <?php if (get_wpappninja_option('speed') == '1') {echo ' style="display:none"';} ?> class="wpappninja_item" href="#" id="wpappninja_label_interface" onclick="wpappninja_toggle(this, 'interface');return false"><span class="dashicons dashicons-smartphone"></span><br/><?php _e('App interface', 'wpappninja');?></a>

				<a <?php if (get_wpappninja_option('speed') == '1') {echo ' style="display:none"';} ?> class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, 'pegi');return false" id="wpappninja_label_pegi"><span class="dashicons dashicons-format-aside"></span><br/><?php _e('Content to show', 'wpappninja');?></a>


				<!--<a class="wpappninja_item" id="wpappninja_label_about" href="#" onclick="wpappninja_toggle(this, 'about');return false"><span class="dashicons dashicons-admin-users"></span><?php
				/*if (
					(get_wpappninja_option('mentions_fr', '') == '' && in_array('fr', $available_lang)) || 
					(get_wpappninja_option('mentions_en', '') == '' && in_array('en', $available_lang)) || 
					(get_wpappninja_option('mentions_de', '') == '' && in_array('de', $available_lang)) || 
					(get_wpappninja_option('mentions_es', '') == '' && in_array('es', $available_lang)) || 
					(get_wpappninja_option('mentions_it', '') == '' && in_array('it', $available_lang)) || 
					(get_wpappninja_option('mentions_pt', '') == '' && in_array('pt', $available_lang))
				) {
					echo '<span style="color:red" class="dashicons dashicons-warning"></span>';
				} else {
					echo '<span style="color:green" class="dashicons dashicons-yes"></span>';
				}*/
				?><br/><?php _e('About page', 'wpappninja');?></a>-->

				<!--<a class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, 'push');return false" id="wpappninja_label_push"><span class="dashicons dashicons-admin-comments"></span><?php	if (get_wpappninja_option('project', '') == '' || get_wpappninja_option('apipush', '') == '' || get_option('wpappninja_pem_file', '') == ''){echo '<span style="color:red" class="dashicons dashicons-warning"></span>';} else {echo '<span style="color:green" class="dashicons dashicons-yes"></span>';}?><br/><?php _e('Push notifications', 'wpappninja');?></a>-->

								
				<a  class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, 'third');return false" id="wpappninja_label_third"><span class="dashicons dashicons-admin-plugins"></span><br/><?php _e('Plugins', 'wpappninja');?></a>
				
				

				
				
				<br/><br/><br/>
				
				<!--<a href="<?php echo admin_url( 'admin.php?page=' . WPAPPNINJA_PROMOTE_SLUG . '&settings');?>"><?php _e("Smart banner", "wpappninja");?></a><br/><br/>-->

				<?php if (get_wpappninja_option('speed') == '1') { ?>
				<a class="button button-primary button-large" style="border-radius: 0;color: darkred;background: #f5f5f5;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;" target="_blank" href="#" onclick="wpappninja_toggle(this, 'debug');return false" id="wpappninja_label_debug"><span class="dashicons dashicons-editor-help"></span> <?php _e('DEBUG', 'wpappninja');?></a><br/><br/>
				<?php } else { ?>
				<a href="#" onclick="jQuery('#wpappninja_devtools').toggle();return false"><?php _e("I'm a web developer", "wpappninja");?></a><br/><br/>
				<div id="wpappninja_devtools" style="display:none">
				<a class="button button-primary button-large" style="border-radius: 0;color: darkred;background: #f5f5f5;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;" target="_blank" href="#" onclick="wpappninja_toggle(this, 'debug');return false" id="wpappninja_label_debug"><span class="dashicons dashicons-editor-help"></span> <?php _e('DEBUG', 'wpappninja');?></a><br/><br/>
				<a style="color:#666;border-color:#ddd" class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, 'inject');return false" id="wpappninja_label_inject"><span style="color:#999" class="dashicons dashicons-align-center"></span><br/><?php _e('HTML injection', 'wpappninja');?></a>
				<!--<a style="color:#666;border-color:#ddd" class="wpappninja_item" href="#" onclick="wpappninja_toggle(this, 'regex');return false" id="wpappninja_label_regex"><span style="color:#999" class="dashicons dashicons-edit"></span><br/><?php _e('Regex modifications', 'wpappninja');?></a>-->
				</div>
				<?php } ?>

				
				<a href="#" onclick="wpappninja_toggle(this, 'right');return false"><?php _e("Access right of the plugin", "wpappninja");?></a>

			</div>
			
			<div id="wpappninja_main_tab" <?php if (isset($_GET['onlymenu']) || isset($_GET['onlymenu_trad'])) {echo 'style="border: 0;padding: 0;width: Calc(100% - 40px);min-height:0;float:none!important;margin-top:13px;"';} ?>>
			<div style="text-align:center;padding:60px;font-size:50px;display:block;min-width:350px" class="wpappninja_i_" >...</div>

			<!--<div class="wpappninja_help" style="border-color: #0fa624;margin-bottom: 20px;"><?php _e('All changes are instantly reflected on the app', 'wpappninja');?></div>-->
			
			<div id="wpappninja_i__buy" class="wpappninja_i_">
				<div class="wpappninja_div">
					<?php
					if ($is_app_store_ready) {
						$app_display = 'none';
						?>
						
						<div id="wpappninja_app_store_result" style="margin-bottom:350px;">
						<?php if (wpappninja_is_paid()) { ?>
							<a class="button button-primary button-large" style="border-radius: 0;color: #fff;background: #07A91B;border: 2px solid #17A50E;text-shadow: 0 0 0;font-weight: 700;font-size: 18px;height: 40px;box-shadow: 0 0 44px #999;margin: 0 13px 25px 0;padding: 5px 17px;" target="_blank" href="https://wpmobile.app/<?php if (preg_match('#fr#', get_locale())) {echo 'product/mise-a-jour/';}else{echo 'en/product/update-app/';}?>?source=<?php echo $app_url_home;?>"><span class="dashicons dashicons-cart"></span> <?php _e('UPDATE NOW ON STORES FOR', 'wpappninja');?> <b>49&euro;</b></a>
						<?php } else { ?>
							<a class="button button-primary button-large" style="border-radius: 0;color: #fff;background: #07A91B;border: 2px solid #17A50E;text-shadow: 0 0 0;font-weight: 700;font-size: 18px;height: 40px;box-shadow: 0 0 44px #999;margin: 0 13px 25px 0;padding: 5px 17px;" target="_blank" href="https://wpmobile.app/<?php if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}?>/"><span class="dashicons dashicons-cart"></span> <?php _e('PUBLISH ON STORES FOR', 'wpappninja');?> <b>449&euro;</b></a>
						<?php } ?>
						<h2><?php _e('Your app', 'wpappninja');?></h2>
						<div style="background:#f5f5f5;border:1px solid #eee;padding:20px;max-width:500px;">
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
						<br/>
						<a class="button button-primary button-large" href="#" onclick="jQuery('#wpappninja_app_store_data').toggle();jQuery('#wpappninja_app_store_result').toggle();return false"><?php _e('Edit', 'wpappninja');?></a>
						<br/><br/>
						<h2><?php _e('Custom theme', 'wpappninja');?></h2>
						<div style="background:#fdfdfd;border:1px solid #eee;padding:20px;max-width:500px;">
						<div class="mini_android"><div class="mini_android_toolbar" style="background:<?php echo $app_theme_primary;?>"></div><div class="mini_android_bubble" style="background:<?php echo $app_theme_accent;?>"></div></div></div>
						<br/>
						<a class="button button-primary button-large" href="#" onclick="jQuery('#wpappninja_app_store_data').toggle();jQuery('#wpappninja_app_store_result').toggle();return false"><?php _e('Edit', 'wpappninja');?></a>
						<br/><br/>
						
						<h2><?php _e('Contact info', 'wpappninja');?></h2>
						<div style="background:#fdfdfd;border:1px solid #eee;padding:20px;max-width:500px;">
							<div style="float:left"><?php echo get_avatar( $app_user_mail , 55 );?></div>
							<div style="float:left;margin:10px 0 0 20px;">
								<b style="font-size:20px;"><?php echo $app_user_name;?></b><br/>
								<?php echo $app_user_mail;?>
							</div>
							<div style="clear:both"></div>
						</div>
						<br/>
						<a class="button button-primary button-large" href="#" onclick="jQuery('#wpappninja_app_store_data').toggle();jQuery('#wpappninja_app_store_result').toggle();return false"><?php _e('Edit', 'wpappninja');?></a>
						</div>
						<?php
					}
					?>
					

					<div id="wpappninja_app_store_data" style="display:<?php echo $app_display;?>">

					<?php /* ?>
					<h2><?php _e('Buy and publish', 'wpappninja');?></h2>
					<b style="font-size: 20px;display: block;background: #ffe;padding: 15px;"><?php _e('You\'ll be able to publish when the entire form will be properly filled', 'wpappninja');?></b>
					<h3><?php _e('About you', 'wpappninja');?></h3>
					<?php _e('First and last name', 'wpappninja');?><br/>
					<input name="<?php echo WPAPPNINJA_SLUG;?>[app][user][name]" type="text" value="<?php echo $app_user_name;?>" /><br/><br/>
					<?php _e('Email', 'wpappninja');?><br/>
					<input name="<?php echo WPAPPNINJA_SLUG;?>[app][user][mail]" type="text" value="<?php echo $app_user_mail;?>" /><br/>
					
					<br/>
					<?php */ ?>
					<h3><?php _e('Your App', 'wpappninja');?></h3>
					<?php _e('App title', 'wpappninja');?><br/>
					<input name="<?php echo WPAPPNINJA_SLUG;?>[app][name]" type="text" value="<?php echo $app_name;?>"/><br/><br/>
					<?php _e('Blog url', 'wpappninja');?><br/>
					<input name="<?php echo WPAPPNINJA_SLUG;?>[app][url][home]" type="url" value="<?php echo $app_url_home;?>" readonly /><br/>

					<br/>
						<input id="blog_splashscreen" name="<?php echo WPAPPNINJA_SLUG;?>[app][splashscreen]" type="text" value="<?php echo $splashscreen?>" />
					<h3><?php _e('App logo', 'wpappninja');?></h3>
					<div class="uploader">
						<input id="blog_logo" name="<?php echo WPAPPNINJA_SLUG;?>[app][logo]" type="text" value="<?php echo $app_logo?>" />
						<input id="blog_logo_button" class="button" name="blog_logo_button" type="text" value="<?php _e('Choose a logo', 'wpappninja');?>" />
					</div><br/>
					<?php _e('Background color for iOS logo (if transparency on logo)', 'wpappninja');?><br/>
					<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[app][ios_background]" value="<?php echo $app_ios_background;?>" class="wpapp-color-picker" /><br/><br/>
					
					<br/>
					<h3><?php _e('Custom theme', 'wpappninja');?></h3>
					<?php _e('Primary color', 'wpappninja');?><br/>
					<!--<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[app][theme][primary]" value="<?php echo $app_theme_primary;?>" class="wpapp-color-picker" />--><br/><br/>
					<?php _e('Secondary color', 'wpappninja');?><br/>
					<!--<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[app][theme][accent]" value="<?php echo $app_theme_accent;?>" class="wpapp-color-picker" />--><br/>
					
					<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[app][store][locale]" value="<?php echo $app_store_locale;?>" />
					<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[hash_data]" value="<?php echo get_wpappninja_option('hash_data');?>" />
					<br/>
					<?php /* ?>
					<h3><?php _e('Your app on the stores', 'wpappninja');?></h3>
					<?php _e('Intro text', 'wpappninja');?><br/>
					<input type="text" style="width:100%" maxlength="80" name="<?php echo WPAPPNINJA_SLUG;?>[app][store][intro]" value="<?php echo $app_store_intro; ?>" /><br/><br/>
					
					<?php _e('Description', 'wpappninja');?><br/>
					<textarea style="width:100%;height:100px;" name="<?php echo WPAPPNINJA_SLUG;?>[app][store][text]"><?php echo $app_store_text;?></textarea><br/><br/>

					<?php _e('Keywords (comma separated)', 'wpappninja');?><br/>
					<input type="text" maxlength="80" name="<?php echo WPAPPNINJA_SLUG;?>[app][store][keywords]" value="<?php echo $app_store_keywords;?>" /><br/><br/>
					
					<?php _e('Category', 'wpappninja');?><br/>
					<select name="<?php echo WPAPPNINJA_SLUG;?>[app][store][category]">
						<?php
						foreach ($category as $cat) {
							echo '<option ';if (htmlentities($app_store_category) == $cat){echo 'selected';}echo '>'.$cat.'</option>';
						}
						?>
					</select><br/>
					
					<br/>
					<?php _e('Support/Contact url', 'wpappninja');?><br/>
					<input name="<?php echo WPAPPNINJA_SLUG;?>[app][url][contact]" type="url" value="<?php echo $app_url_contact;?>" /><br/><br/>
					<?php _e('Privacy policy url', 'wpappninja');?><br/>
					<input name="<?php echo WPAPPNINJA_SLUG;?>[app][url][privacy]" type="url" value="<?php echo $app_url_privacy;?>" /><br/>
					
					<br/>
					<?php _e('Main langague', 'wpappninja');?><br/>
					<select name="<?php echo WPAPPNINJA_SLUG;?>[app][store][locale]">
						<?php
						foreach ($loc as $l) {
							echo '<option ';if($app_store_locale == $l) {echo 'selected';}echo '>'.$l.'</option>';
						}
						?>
					</select>
					<?php */ ?>
					</div>
				</div>
			</div>
			
			<div id="wpappninja_i__push" class="wpappninja_i_">
				<h2><?php _e('Push notifications', 'wpappninja');?></h2>
				
				<div class="wpappninja_div">
				<h3><?php _e('Welcome', 'wpappninja');?></h3>
				<p class="wpappninja_help"><?php _e('You can send a welcome notification on the first app launch.', 'wpappninja');?> <b><?php _e('Go on the language section to configure it.', 'wpappninja');?></b></p>
				<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Send a welcome notification', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[send_welcome_push]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('send_welcome_push') === "0"){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
					</table>
				</div>
				
				<div class="wpappninja_div">
				<h3>iOS</h3>
				
					<p class="wpappninja_help"><?php printf(__('You must upload a certificate to enable iOS notifications.<br/>You\'ll receive it by mail when the app is under publication on the App Store.<br/>%sI got the certificate, go to the upload page%s', 'wpappninja'), '<a href="'.admin_url( 'admin.php?page=' . WPAPPNINJA_CERT_SLUG).'">', '</a>');?></p>
					<div class="form-table">
						<?php if (get_option('wpappninja_pem_file', '') == ''){echo '<b style="color:darkred;">KO</b> <a href="'.admin_url( 'admin.php?page=' . WPAPPNINJA_CERT_SLUG).'">Upload my cert</a>';} else {echo '<b style="color:darkgreen;">OK</b>';} ?>
					</div>	
				</div>
				<br/>
				<div class="wpappninja_div">
				<h3>Android</h3>
				
					<p class="wpappninja_help"><?php _e('Follow the <b><a href="https://wpmobile.app/en/send-push-notifications/" target="_blank">quick guide</a></b> to get the Google API Key', 'wpappninja'); ?></p>
					<table class="form-table"<?php if (get_wpappninja_option('project', '') == '' || get_wpappninja_option('apipush', '') == ''){echo ' style="background:#ffe"';}?>>
						<tr valign="top">
							<th scope="row"><?php _e('Server API Key', 'wpappninja');?><br/></th>
							<td><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[apipush]" value="<?php echo get_wpappninja_option('apipush');?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Sender ID', 'wpappninja');?><br/></th>
							<td>#<input style="width: 80%;" placeholder="00000000000" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[project]" value="<?php echo get_wpappninja_option('project');?>" /></td>
						</tr>
					</table>
				</div>

		<h3><?php _e('Welcome', 'wpappninja');?></h3>
		<p class="wpappninja_help"><?php _e('You can send a welcome notification on the first app launch.', 'wpappninja');?></p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Send a welcome notification', 'wpappninja');?></th>
				<td><select name="<?php echo WPAPPNINJA_SLUG;?>[send_welcome_push]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('send_welcome_push') === "0"){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option></select></td>
			</tr>
		</table>
		<br/>

		<?php
		$localize_i = 0;
		$wpappninja_array_lang = array();
		$wpappninja_array_lang['speed'] = 'speed';

		/*if (get_wpappninja_option('speed') == '1') {
			$wpappninja_array_lang = array('English' => 'en');
		}*/
		foreach($wpappninja_array_lang as $name => $code) {
			$localize_css = '';
			if ($localize_i == 0) {
				$localize_css = 'color:#fff!important;background-color:#fd9b02 !important;';
			}
						
			echo '<a style="min-height: 0;width: 110px;'.$localize_css.'" class="wpappninja_item wpappninja_localize_a" href="#" onclick="jQuery(\'.wpappninja_localize\').css(\'display\', \'none\');jQuery(\'#wpappninja_localize_'.$code.'\').css(\'display\', \'block\');jQuery(\'.wpappninja_localize_a\').css(\'background\', \'#fff\');jQuery(\'.wpappninja_localize_a\').css(\'color\', \'#555\');jQuery(this).css(\'color\', \'#fff\');jQuery(this).css(\'background\', \'#fd9b02\');return false"><img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" />';
				if (get_wpappninja_option('welcome_titre_' . $code, '') == '' || get_wpappninja_option('welcome_' . $code, '') == '' || get_wpappninja_option('bienvenue_' . $code, '') == '') {
					echo '<span style="color:red" class="dashicons dashicons-warning"></span>';
				} else {
					echo '<span style="color:green" class="dashicons dashicons-yes"></span>';
				}
				echo '<br/>'.$name.'</a>';
						
				$localize_i++;
		} ?>


		<?php
		$localize_i = 0;
		foreach ($wpappninja_array_lang as $name => $code) {
			$localize_display = 'none';
			if ($localize_i == 0){
				$localize_display = 'block';
			}
			?>
			<div id="_wpappninja_localize_<?php echo $code;?>" style="display:<?php echo $localize_display;?>" class="wpappninja_localize">
				<div id="welcome_t_<?php echo $code;?>">
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Title', 'wpappninja');?><br/></th>
							<td><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[welcome_titre_<?php echo $code;?>]" value="<?php echo get_wpappninja_option('welcome_titre_' . $code);?>" /></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Sub title', 'wpappninja');?><br/></th>
							<td><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[welcome_<?php echo $code;?>]" value="<?php echo get_wpappninja_option('welcome_' . $code);?>" /></td>
						</tr>
					</table>
					<table class="form-table">
						<tr valign="top">
							<td>
								<textarea style="width:100%;height:350px;" name="<?php echo WPAPPNINJA_SLUG;?>[bienvenue_<?php echo $code;?>]" id="wpappninja_bienvenue_<?php echo $code;?>"><?php echo get_wpappninja_option('bienvenue_' . $code);?></textarea>
							</td>
						</tr>
					</table>
				</div>
				<br/><hr/><br/>
			</div>
			<?php
			$localize_i++;
		}
		?>

		<h3><?php _e('General', 'wpappninja');?></h3>
		<p class="wpappninja_help"><?php _e('Muted categories do not trigger notifications', 'wpappninja');?></p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Muted category', 'wpappninja');?></th>
				<td>
					<?php
					$silent = array();
					if (is_array(get_wpappninja_option('silent'))) {
						$silent = get_wpappninja_option('silent');
					}
					$categories = get_terms('category', array('hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 30));
					foreach ($categories as $category) {
						if ($category->parent == 0) {
							echo '<label><input type="checkbox" value="'.$category->term_id.'" name="' . WPAPPNINJA_SLUG . '[silent][]" ';if (in_array($category->term_id, $silent)) {echo 'checked';}echo ' /> '.$category->name.'</label><br/>';
						}
					}
					?>
				</td>
			</tr>
		</table>
			</div>

			<div id="wpappninja_i__about" class="wpappninja_i_">

				<div class="wpappninja_div">
					<?php $lang_exclude = get_wpappninja_option('lang_exclude', array()); ?>
					
					<?php
					$localize_i = 0;
					if (count($wpappninja_array_lang) > 1) {
					foreach($wpappninja_array_lang as $name => $code) {
						$localize_css = '';
						if ($localize_i == 0) {
							$localize_css = 'color:#fff!important;background-color:#fd9b02 !important;';
						}
						
						
						echo '<a style="min-height: 0;width: 110px;'.$localize_css.'" class="wpappninja_item_nojs wpappninja_localize_a" href="#" onclick="jQuery(\'.wpappninja_localize\').css(\'display\', \'none\');jQuery(\'#wpappninja_localize_'.$code.'\').css(\'display\', \'block\');jQuery(\'.wpappninja_localize_a\').css(\'background\', \'#fff\');jQuery(\'.wpappninja_localize_a\').css(\'color\', \'#555\');jQuery(this).css(\'color\', \'#fff\');jQuery(this).css(\'background\', \'#fd9b02\');return false"><img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" />';
						if (
							get_wpappninja_option('mentions_' . $code, '') == ''
						) {
							echo '<span style="color:red" class="dashicons dashicons-warning"></span>';
						} else {
							echo '<span style="color:green" class="dashicons dashicons-yes"></span>';
						}
						echo '<br/>'.$name.'</a>';
						
						$localize_i++;
					} } ?>

					<?php $localize_i = 0;
					foreach ($wpappninja_array_lang as $name => $code) {
						$localize_display = 'none';
						if ($localize_i == 0){
							$localize_display = 'block';
						}
						?>
						<div id="wpappninja_localize_<?php echo $code;?>" style="display:<?php echo $localize_display;?>" class="wpappninja_localize">
						
						<div id="mentions_t_<?php echo $code;?>">
						<p class="wpappninja_help"><?php _e('You can enter here your legal terms.', 'wpappninja');?></p>
						<table class="form-table">
							<tr valign="top">
								<td>
									<?php
									wp_editor( get_wpappninja_option('mentions_' . $code), 'wpappninja_mentions_' . $code, array(
												'media_buttons' => true,
												'teeny' => false,
												'textarea_name' => WPAPPNINJA_SLUG . '[mentions_' . $code . ']'
										) ); ?>
								</td>
							</tr>
						</table>
						</div>
						</div>
						<?php
						$localize_i++;
					}
					?>
				</div>
			</div>
	
			<div id="wpappninja_i__lang" class="wpappninja_i_">
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Languages', 'wpappninja');?></h2>
<div class="wpappninja_div">


				<div class="wpappninja_div" <?php if (get_wpappninja_option('speed') != '1' && 1<0){echo 'style="display:none"';}?>>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Auto detected users languages', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">


							<?php



							foreach(wpappninja_available_lang() as $name => $code) {

								echo '<label><img src="'.WPAPPNINJA_ASSETS_IMG_URL.'/flags/'.$code.'.gif" /> ' . $name . '</label><br/>';
							}
							?>
							</div>
	<div class="clear"></div>
</div>
            
            
            <div class="wpappninja-builder">
                <div class="wpappninja-builder-left">
                    <?php _e('Remove a language', 'wpappninja');?>
                </div>
                <div class="wpappninja-builder-right">

                                        <?php
                                        foreach ( wpappninja_available_lang("all") as $name => $code ) {
                                            echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[localeko][]" value="' . $code . '" ';
                                            if (in_array($code, get_wpappninja_option('localeko', array()))) {
                                                echo 'checked';
                                            }
                                            echo ' /> ' . $name . '</label><br/>';
                                        }
                                        ?>
                                        </div>
                <div class="clear"></div>
            </div>
            
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Add a language', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

		<select name="<?php echo WPAPPNINJA_SLUG;?>[wpappninjaddlang]"><option></option>
							<?php
							foreach(wpappninja_available_lang(true) as $name => $code) {

								echo '<option value="'.$code.'"> ' . $name . '</option>';
							}
							?></select>
							</div>
	<div class="clear"></div>
</div>

<?php
$weglot=true;

					if (get_wpappninja_option('speed') == '1' && 
					get_wpappninja_option('webview') == '4' && 
					get_wpappninja_option('appify') != '1' && 
					get_wpappninja_option('speed_notheme') == '1' && 
					get_wpappninja_option('nospeed_notheme') == '0') {$weglot=false;}

						if (get_wpappninja_option('speed') == '0') {$weglot=false;}
					?>




<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Translation system', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">
							<select id="wpappninja_speed_trad" name="<?php echo WPAPPNINJA_SLUG;?>[speed_trad]">
								<option value="none"><?php _e('No translation', 'wpappninja');?></option>
								<option value="manual" <?php if (get_wpappninja_option('speed_trad') == 'manual'){echo 'selected';}?>><?php _e('Manual', 'wpappninja');?></option>
								<option value="weglot" <?php if (get_wpappninja_option('speed_trad') == 'weglot' && $weglot){echo 'selected';}?> 

									<?php if (!$weglot){echo ' disabled';}?>><?php _e('Automatic (with Weglot)', 'wpappninja');?></option>
							</select>
</div>
	<div class="clear"></div>
</div>


</div>




<div id="wpappninja_trad_weglot" <?php if (get_wpappninja_option('speed_trad') != 'weglot' || !$weglot){echo 'style="display:none"';}?>>


	<h2 style="margin-top:50px;background: #f5f5f5;padding: 15px;cursor: pointer;"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Weglot', 'wpappninja');?></h2>
<div class="wpappninja_div">
	
	<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<a href="https://weglot.com/" target="_blank"><?php _e('API Key', 'wpappninja');?></a>
	</div>
	<div class="wpappninja-builder-right">
<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[weglot_apikey]" placeholder="<?php _e('API Key', 'wpappninja');?>" value="<?php echo get_wpappninja_option('weglot_apikey', '');?>" />
</div>
	<div class="clear"></div>
</div>

	<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Original language (2 letters code)', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">
<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[weglot_original]" placeholder="fr, en, es, ..." maxlength="2" value="<?php echo get_wpappninja_option('weglot_original', '');?>" />
</div>
	<div class="clear"></div>
</div>
</div>
							
</div>

<?php
$weglot=true;

					if (get_wpappninja_option('speed') == '1' && 
					get_wpappninja_option('webview') == '4' && 
					get_wpappninja_option('appify') != '1' &&
					get_wpappninja_option('speed_notheme') == '1' && 
					get_wpappninja_option('nospeed_notheme') == '0') {$weglot=false;}?>

<div id="wpappninja_trad_manual" <?php if (get_wpappninja_option('speed_trad') != 'manual'){echo 'style="display:none"';}?>>




							<?php
							$trads = get_wpappninja_option('trad');

							if (get_wpappninja_option('speed_trad') == 'manual') {

							foreach (wpappninja_available_lang() as $name => $code) {

								echo '<h2 style="margin-top:50px;background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery(\'#wpappninja_trad_' . $code . '\').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <img src="'.WPAPPNINJA_ASSETS_IMG_URL.'/flags/'.$code.'.gif" style="height: auto;width: 32px;" /> ' . $name . '</h2>
								<div class="wpappninja_div" id="wpappninja_trad_' . $code . '" style="display:none">';







								$homepage_wpapp = wpappninja_convertid_to_url(get_wpappninja_option('pageashome_speed', wpappninja_get_home()));
								$title_homepage_wpapp = get_wpappninja_option('pageashometitle_speed', "");

								$title = $trads[md5($title_homepage_wpapp)][$code];
								if ($title == "") {
									$title = $title_homepage_wpapp;
								}

								$url = $trads[md5($homepage_wpapp)][$code];
								if ($url == "") {
									$url = wpappninja_convertid_to_url($homepage_wpapp);
								}


?>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php echo $title_homepage_wpapp;?>
	</div>
	<div class="wpappninja-builder-right">
							<?php echo '<input type="text" name="' . WPAPPNINJA_SLUG . '[trad][' . md5($title_homepage_wpapp) . '][' . $code . ']" value="' . $title . '" placeholder="' . __('Title', 'wpappninja') . '" />'; ?>
</div>
	<div class="clear"></div>
</div>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php echo wpappninja_convertid_to_url($homepage_wpapp);?>
	</div>
	<div class="wpappninja-builder-right">
							<?php echo '<input type="text" name="' . WPAPPNINJA_SLUG . '[trad][' . md5($homepage_wpapp) . '][' . $code . ']" value="' . $url . '" size="35" placeholder="' . __('Url', 'wpappninja') . '" />'; ?>
</div>
	<div class="clear"></div>
</div>
<?php



								if (!$weglot) {echo '<div style="display:none">';}

								$pages = wpappninja_get_menu_reloaded('speed');
								$nb=0;
								foreach ($pages as $page) {

									$title_homepage_wpapp = $page['name'];
									$homepage_wpapp = $page['id'];

									if ($page['type'] == 'cat') {
										$homepage_wpapp = 'cat_' . $page['id'];
									}

									$title = $trads[md5($title_homepage_wpapp)][$code];
									if ($title == "") {
										$title = $title_homepage_wpapp;
									}

									$url = $trads[md5($homepage_wpapp)][$code];
									if ($url == "") {
										$url = wpappninja_convertid_to_url($homepage_wpapp);
									}


if (1>0) {

$nb++;
?>
<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php echo $page['name'];?>
	</div>
	<div class="wpappninja-builder-right">
							<?php echo '<input type="text" name="' . WPAPPNINJA_SLUG . '[trad][' . md5($title_homepage_wpapp) . '][' . $code . ']" value="' . $title . '" placeholder="' . __('Title', 'wpappninja') . '" />'; ?>
</div>
	<div class="clear"></div>
</div>
<?php
}


								}

																$nb=0;
								foreach ($pages as $page) {

									$title_homepage_wpapp = $page['name'];
									$homepage_wpapp = $page['id'];

									if ($page['type'] == 'cat') {
										$homepage_wpapp = 'cat_' . $page['id'];
									}

									$title = $trads[md5($title_homepage_wpapp)][$code];
									if ($title == "") {
										$title = $title_homepage_wpapp;
									}

									$url = $trads[md5($homepage_wpapp)][$code];
									if ($url == "") {
										$url = wpappninja_convertid_to_url($homepage_wpapp);
									}


if (wpappninja_convertid_to_url($homepage_wpapp) != '') {

$nb++;
?>

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php echo wpappninja_convertid_to_url($homepage_wpapp);?>
	</div>
	<div class="wpappninja-builder-right">
							<?php echo '<input type="text" name="' . WPAPPNINJA_SLUG . '[trad][' . md5($homepage_wpapp) . '][' . $code . ']" value="' . $url . '" size="35" placeholder="' . __('Url', 'wpappninja') . '" />'; ?>
</div>
	<div class="clear"></div>
</div>
<?php
}


								}
								if (!$weglot) {echo '</div>';}

								echo '</div>';

							}

						}
							?>

</div>




											<script type="text/javascript">
						jQuery('#wpappninja_speed_trad').change(function() {

							jQuery("#wpappninja_trad_manual").css('display', 'none');
							jQuery("#wpappninja_trad_weglot").css('display', 'none');

						    if (jQuery(this).val() === 'manual') {
								jQuery("#wpappninja_trad_manual").css('display', 'block');
						    }
						    if (jQuery(this).val() === 'weglot') {
								jQuery("#wpappninja_trad_weglot").css('display', 'block');
						    }
						});
						</script>

</div>

			</div>

			<div id="wpappninja_i__banner" class="wpappninja_i_">
				<h2><?php _e('Smart Banner', 'wpappninja');?></h2>
				<div class="wpappninja_div">
					<p class="wpappninja_help"><?php _e('Display a banner on top of your site pointing on the store for your mobile visitors.', 'wpappninja');?></p>
				</div>
				<div class="wpappninja_div">
				<?php $appdata = get_wpappninja_option('app'); ?>			
					<style type="text/css">	
	#wpappninja_banner {
		height: 66px;
		background: #ffffff;
		border-bottom: 1px solid #f9f9f9;
	    box-shadow: 0 0 1px #999;
	    padding: 3px 0;
		width: 400px;
		text-align: center;
	}
	#wpappninja_banner .wpappninja_banner_close {
		color: gray;
		font-size: 14px;
		vertical-align: top;
		display: inline-block;
		margin: 24px 15px 0 0;
	}
	#wpappninja_banner .wpappninja_banner_logo {
	    vertical-align: top;
	    display: inline-block;
	    margin: 6px 12px;
	    width: 48px;
	    height: 48px;
	    padding: 3px;

		    border-radius: 8px;
		    background: <?php echo $appdata['ios_background'];?>;
	}
	#wpappninja_banner .wpappninja_banner_text {
		color: #333;
		line-height: 10px;
		font-size: 15px;
		text-align: left;
		vertical-align: top;
		display: inline-block;
		margin: 11px 30px 0 0;
	}
	#wpappninja_banner .wpappninja_banner_text span {
		color:#696969;
		font-size:11px;
	}
	#wpappninja_banner .wpappninja_banner_click {
		display: inline-block;
		background: <?php echo wpappninja_get_hex_color(false);?>;
		padding: 3px 10px;
		font-size: 16px;
		color: white;
		vertical-align: top;
		margin: 21px 37px 0 10px;
	}
	#wpappninja_banner .wpappninja_banner_click img {
	    width: 20px;
		vertical-align: middle;
	}
	</style>


	<div id="wpappninja_banner">
		<div class="wpappninja_banner_close" src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>close_icon.png">
			&#10005;
		</div>
		<div class="wpappninja_banner_logo">
			<img src="<?php echo $appdata['logo'];?>" width="48" height="48" />
		</div>
		<div class="wpappninja_banner_text">
			<b><?php echo $appdata['name']; ?></b><br/><br/>
			<span><?php _e('FREE', 'wpappninja');?><br/></span>
			<span><?php _e('In App Store', 'wpappninja');?></span>
		</div>
		<div class="wpappninja_banner_click">
			<?php _e('VIEW', 'wpappninja');?>
		</div>
	</div>

					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Show the smart banner', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[smartbanner]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('smartbanner') === '0'){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Display the smart banner on', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[bannertype]"><option value="both"><?php _e('Android and iOS', 'wpappninja');?></option><option value="android" <?php if (get_wpappninja_option('bannertype') === 'android'){echo 'selected';}?>><?php _e('Android', 'wpappninja');?></option><option value="ios" <?php if (get_wpappninja_option('bannertype') === 'ios'){echo 'selected';}?>><?php _e('iOS', 'wpappninja');?></option></select></td>
						</tr>

						
					</table>
				</div>
			</div>

			<div id="wpappninja_i__appindexing" class="wpappninja_i_">
				<h2><?php _e('App indexing (SEO)', 'wpappninja');?></h2>
				<div class="wpappninja_div">
					<p class="wpappninja_help"><?php printf(__('Follow %sthe guide%s for activate the app indexing api', 'wpappninja'), '<a href="https://wpmobile.app/app-indexing/" target="_blank">', '</a>');?></p>
				</div>
			</div>
		
			<div id="wpappninja_i__inject" class="wpappninja_i_">
				<h2><?php _e('Inject HTML', 'wpappninja');?></h2>
				<p class="wpappninja_help"><?php _e("Useful to add your html banners or communicate an event.<br/><b>Note that you can not use CSS or Javascript. Only HTML tags (img, a, b, i, p, ...).</b>", "wpappninja");?></p>
				<div class="wpappninja_div">
					<h3><?php _e('Before the post', 'wpappninja');?></h3>
					<table class="form-table">	
						<tr valign="top">
							<td>
								<textarea style="width:100%;height:350px;" name="<?php echo WPAPPNINJA_SLUG;?>[beforepost]" id="wpappninja_beforepost"><?php echo get_wpappninja_option('beforepost');?></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class="wpappninja_div">
					<h3><?php _e('After the post', 'wpappninja');?></h3>
					<table class="form-table">
						<tr valign="top">
							<td>
								<textarea style="width:100%;height:350px;" name="<?php echo WPAPPNINJA_SLUG;?>[afterpost]" id="wpappninja_afterpost"><?php echo get_wpappninja_option('afterpost');?></textarea>
							</td>
						</tr>
					</table>
				</div>
			</div>
			
			<div id="wpappninja_i__debug" class="wpappninja_i_">
				<?php 
				$nbCron = 0;

				if (isset($_GET['wpappninja_repair_cron'])) {
					wp_clear_scheduled_hook( 'wpappninjacron' );
					wp_schedule_event( time(), 'wpappninja_every_three_minutes', 'wpappninjacron' );
				}
					
				foreach (_get_cron_array() as $cron) {
					if (key($cron) == 'wpappninjacron'){
						$nbCron++;
					}
				}
				
				if ($nbCron == 1) {
					$cronState = '<b style="color:darkgreen;font-size:25px;">OK</b>';
				} elseif ($nbCron == 0) {
					$cronState = '<b style="color:darkred;font-size:25px;">KO</b><br/><a href="?page=' . WPAPPNINJA_SLUG . '&wpappninja_repair_cron">REPAIR</a>';
				} elseif ($nbCron > 1) {
					$cronState = '<b style="color:darkorange;font-size:25px;">NOK '.$nbCron.'</b><br/><a href="?page=' . WPAPPNINJA_SLUG . '&wpappninja_repair_cron">REPAIR</a>';
				}
				
				$jsonState
				?>
				<h2><?php _e('DEBUG', 'wpappninja');?></h2>
				<div class="wpappninja_div">
					<h3><?php _e('Cron', 'wpappninja');?> <a href="http://wpformation.com/cron-wordpress/" target="_blank">En savoir plus</a></h3>
					<table class="form-table">	
						<tr valign="top">
							<td><?php echo $cronState;?></td>
						</tr>
					</table>
				</div>
				
				<div class="wpappninja_div">
					<h3><?php _e('Package', 'wpappninja');?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Package name', 'wpappninja');?><?php echo '<br/><a href="https://api.wpmobile.app/package.php?url='.urlencode(home_url()).'" target="_blank">'.__('Get it', 'wpappninja').'</a>';?></th>
							<td><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[package]" value="<?php echo get_wpappninja_option('package', '');?>" /></td>
						</tr>
					</table>
					
					
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php echo __('App Store ID', 'wpappninja');?>
							<?php echo '<br/><a href="https://api.wpmobile.app/package_ios_reload.php?url='.urlencode(home_url()).'" target="_blank">'.__('Get it', 'wpappninja').'</a>';?></th>
							<td><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[appstore_package]" value="<?php echo get_wpappninja_option('appstore_package', '');?>" /></td>
						</tr>
					</table>
				</div>
			</div>

			<div id="wpappninja_i__third" class="wpappninja_i_">
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
				<br/>
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

						<tr valign="top">
							<th scope="row"><?php _e('300x250 before post', 'wpappninja');?></th>
							<td>
								Android<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_t]" value="<?php echo get_wpappninja_option('admob_t');?>" />
								<br/><br/>
								iOS<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_t_ios]" value="<?php echo get_wpappninja_option('admob_t_ios');?>" />
							</td>
						</tr>
					
						<tr valign="top">
							<th scope="row"><?php _e('300x250 after post', 'wpappninja');?></th>
							<td>
								Android<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_b]" value="<?php echo get_wpappninja_option('admob_b');?>" />
								<br/><br/>
								iOS<br/><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[admob_b_ios]" value="<?php echo get_wpappninja_option('admob_b_ios');?>" />
							</td>
						</tr>
					</table>
				</div>
				<br/>
				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_analytics').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Google Analytics', 'wpappninja');?></h2>
				<div class="wpappninja_div" id="wpappninja_analytics" style="display:none">
					<p class="wpappninja_help"><?php printf(__('Follow %sthe guide%s to get a Google Analytics UA id', 'wpappninja'), '<a href="https://support.google.com/analytics/answer/1009714" target="_blank">', '</a>');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Identifiant', 'wpappninja');?></th>
							<td><input placeholder="UA-XXXXXXXX-X" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[ga]" value="<?php echo get_wpappninja_option('ga');?>" /></td>
						</tr>
					</table>
				</div>
				<br/>
			</div>
			
			<div id="wpappninja_i__regex" class="wpappninja_i_">
				<h2><?php _e('Regex modifications', 'wpappninja');?></h2>
				<p class="wpappninja_help"><?php _e("<b>'Expert' function, use only if you know what you are doing.</b><br/>If you have any doubt, please contact us on the chat (bottom right) to see what we can do ;)<br/><br/>You can via a regular expression delete some portions of your articles. Useful for example to remove tags or shortcodes that are not automatically deleted or element that is not useful in the application.", "wpappninja");?></p>
				<div class="wpappninja_div">
					<h3><?php _e('Add your regex rules (line break separated)', 'wpappninja');?></h3>
					<h4><?php _e('Example: /&lt;strong&gt;.*&lt;\/strong&gt;/i', 'wpappninja');?></h4>
					<table class="form-table">
						<tr valign="top">
							<td><textarea style="width: 100%;height: 350px;" name="<?php echo WPAPPNINJA_SLUG;?>[regex]"><?php echo get_wpappninja_option('regex', '');?></textarea></td>
						</tr>
					</table>
				</div>
			</div>

			<div id="wpappninja_i__rating" class="wpappninja_i_">
				<h2><?php _e('Rating popup', 'wpappninja');?></h2>
				
				<p class="wpappninja_help"><?php _e('The rating popup allow the users to go on the store and leave a review.', 'wpappninja');?><br/><br/><img src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>rate.png" /></p>

				<div class="wpappninja_div">
					<h3><?php _e('Trigger rule', 'wpappninja');?></h3>
					<table class="form-table">
	
						<tr valign="top">
							<th scope="row"><?php _e('After reading X posts (0 = deactivate)', 'wpappninja');?></th>
							<td>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[rating_seuil]">
								<?php
								$nbsimi = get_wpappninja_option('rating_seuil', 10);
								for ($i=0;$i<21;$i++) {
									echo '<option ';if ($i == $nbsimi){echo 'selected';}echo '>'.$i.'</option>';
								}
								?>
								</select>
							</td>
						</tr>
					</table>

					<br/>

					<?php
					$localize_i = 0;
					foreach($wpappninja_array_lang as $name => $code) {
						$localize_css = '';
						if ($localize_i == 0) {
							$localize_css = 'color:#fff!important;background-color:#fd9b02 !important;';
						}
						
						
						echo '<a style="min-height: 0;width: 110px;'.$localize_css.'" class="wpappninja_item_nojs wpappninja_localize_rate_a" href="#" onclick="jQuery(\'.wpappninja_localize_rate_\').css(\'display\', \'none\');jQuery(\'#wpappninja_localize_rate_'.$code.'\').css(\'display\', \'block\');jQuery(\'.wpappninja_localize_rate_a\').css(\'background\', \'#fff\');jQuery(\'.wpappninja_localize_rate_a\').css(\'color\', \'#555\');jQuery(this).css(\'color\', \'#fff\');jQuery(this).css(\'background\', \'#fd9b02\');return false"><img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" />';
						if (
							get_wpappninja_option('rating_titre_' . $code, '') == '' || 
							get_wpappninja_option('rating_texte_' . $code, '') == ''
						) {
							echo '<span style="color:red" class="dashicons dashicons-warning"></span>';
						} else {
							echo '<span style="color:green" class="dashicons dashicons-yes"></span>';
						}
						echo '<br/>'.$name.'</a>';
						
						$localize_i++;
					} ?>


					<?php $localize_i = 0;
					foreach ($wpappninja_array_lang as $name => $code) {
						$localize_display = 'none';
						if ($localize_i == 0){
							$localize_display = 'block';
						}
						?>
						<div id="wpappninja_localize_rate_<?php echo $code;?>" style="display:<?php echo $localize_display;?>" class="wpappninja_localize_rate_">
						<div id="rating_t_<?php echo $code;?>" style="display:block">
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Title', 'wpappninja');?></th>
								<td><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[rating_titre_<?php echo $code;?>]" value="<?php echo get_wpappninja_option('rating_titre_' . $code, '');?>" /></td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Content', 'wpappninja');?></th>
								<td><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[rating_texte_<?php echo $code;?>]" value="<?php echo get_wpappninja_option('rating_texte_' . $code, '');?>" /></td>
							</tr>
						</table>
						</div>
						</div>
						<?php
						$localize_i++;
					}
					?>

				</div>
			</div>

			<div id="wpappninja_i__webview" class="wpappninja_i_">
				<h2><?php _e('Main content', 'wpappninja');?></h2>

				
				<div class="wpappninja_div" style="display:none">
					<h3><?php _e('Additionnal CSS for the Android night mode', 'wpappninja');?></h3>
					<table class="form-table">
						<tr valign="top">
							<td><textarea name="<?php echo WPAPPNINJA_SLUG;?>[customcss_night]" style="width: 100%;height: 200px;"><?php echo get_wpappninja_option('customcss_night');?></textarea></td>
						</tr>
					</table>
				</div>
				
				<div class="wpappninja_div">
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><b><?php _e("Display type", "wpappninja");?></b></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[webview]">

								<option value="4" <?php if (get_wpappninja_option('webview', '0') == '4'){echo 'selected';} ?>><?php _e('WPMobile.App (best)', 'wpappninja');?></option>
								<option value="0" <?php if (get_wpappninja_option('webview', '0') == '0'){echo 'selected';} ?>><?php _e('Simple (text only)', 'wpappninja');?></option>
								<option value="2" <?php if (get_wpappninja_option('webview', '0') == '2'){echo 'selected';} ?>><?php _e('Website theme (not app friendly)', 'wpappninja');?></option>


								<optgroup label="Deprecated">
									<option value="1" <?php if (get_wpappninja_option('webview', '0') == '1'){echo 'selected';} ?>><?php _e('Optimal', 'wpappninja');?></option>
								</optgroup>


							</select></td>
						</tr>
						
						<?php $webview_selective = get_wpappninja_option('webview_selective', array()); ?>

						<?php /* ?>
						<tr valign="top">
							<th scope="row"><b><?php _e("Pages", "wpappninja");?></b></th>
							<td>
							<?php
							foreach ($pages as $page) {
								echo '<label><input type="checkbox" value="'.$page.'" name="<?php echo WPAPPNINJA_SLUG;?>[webview_selective][]" ';
								if (in_array($page, $webview_selective)) {echo 'checked';}
								echo '/> '.trim(get_the_title($page)).'</label><br/>';
							}
							?>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><b><?php _e("Posts", "wpappninja");?></b></th>
							<td>
							<?php
							foreach ($posts as $page) {
								echo '<label><input type="checkbox" value="'.$page.'" name="<?php echo WPAPPNINJA_SLUG;?>[webview_selective][]" ';
								if (in_array($page, $webview_selective)) {echo 'checked';}
								echo '/> '.trim(get_the_title($page)).'</label><br/>';
							}
							?>
							</td>
						</tr>
						<?php */

						if (count($webview_selective) > 0) { ?>
						
						<tr valign="top">
							<th scope="row"><b><?php _e("Only on some page?", "wpappninja");?></b></th>
							<td>
							<?php
							foreach ($webview_selective as $page) {
								echo '<label><input type="checkbox" value="'.$page.'" name="' . WPAPPNINJA_SLUG . '[webview_selective][]" checked /> '.trim(get_the_title($page)).'</label><br/>';
							}
							?>
							</td>
						</tr>

						<?php } ?>
					</table>
				</div>
				<br/>
				<div class="wpappninja_div" <?php if (get_wpappninja_option('webview', '0') == '0'){echo 'style="display:none"';}?>>
					<h3><?php _e('Custom CSS (only on app)', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('You can use CSS to customize the content', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<td><textarea name="<?php echo WPAPPNINJA_SLUG;?>[customcss]" style="width: 100%;height: 200px;"><?php echo get_wpappninja_option('customcss');?></textarea><textarea name="<?php echo WPAPPNINJA_SLUG;?>[customjs]" style="width: 100%;height: 200px;"><?php echo get_wpappninja_option('customjs');?></textarea></td>
						</tr>
					</table>
				</div>

				<br/>


				<div class="wpappninja_div">
					<h3><?php _e('Custom CSS (only on website)', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('You can use CSS to customize the content', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<td><textarea name="<?php echo WPAPPNINJA_SLUG;?>[customcss_website]" style="width: 100%;height: 200px;"><?php echo get_wpappninja_option('customcss_website');?></textarea><textarea name="<?php echo WPAPPNINJA_SLUG;?>[customjs_website]" style="width: 100%;height: 200px;"><?php echo get_wpappninja_option('customjs_website');?></textarea></td>
						</tr>
					</table>
				</div>

				<br/>



				<div class="wpappninja_div">
					<h3><?php _e('Custom rules', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('You can use a different mode for some pages', 'wpappninja');?></p>
					<table class="form-table">

						<tr valign="top">
							<th scope="row"><input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[temp__webview_rules]" placeholder="<?php _e('Page url', 'wpappninja');?>" /></th>
							<td></td>
						</tr>

						<?php

						/** add temp **/
						if (get_wpappninja_option('temp__webview_rules', '') != '') {
							
							$options = get_option(WPAPPNINJA_SLUG);
							$options['temp__webview_rules'] = '';

							$new_id = wpappninja_url_to_postid(get_wpappninja_option('temp__webview_rules', ''));
							if ($new_id != 0) {
								$options['webview_rules'][$new_id] = "2";
							}

							update_option(WPAPPNINJA_SLUG, $options);
			
						}
						$custom_rules = get_wpappninja_option('webview_rules', array());

						foreach($custom_rules as $id => $mode) { ?>
						<tr valign="top" id="wv_rules_<?php echo $id;?>" >
							<th scope="row"><?php echo get_permalink($id);?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[webview_rules][<?php echo $id;?>]">

								<option value="0" <?php if ($mode == '0'){echo 'selected';} ?>><?php _e('Simple (text only)', 'wpappninja');?></option>
								<option value="2" <?php if ($mode == '2'){echo 'selected';} ?>><?php _e('Website theme (not app friendly)', 'wpappninja');?></option>


								<optgroup label="Deprecated">
									<option value="1" <?php if ($mode == '1'){echo 'selected';} ?>><?php _e('Optimal', 'wpappninja');?></option>
								</optgroup>


							</select></td>
							<td>
								<a style="color:red" href="#" onclick="jQuery('#wv_rules_<?php echo $id;?>').remove();return false"><?php _e('Remove', 'wppappninja');?></a>
							</td>
						</tr>
						<?php } ?>

					</table>
				</div>
			</div>

			<div id="wpappninja_i__interface" class="wpappninja_i_">
				<h2><?php _e('App interface', 'wpappninja');?></h2>
				
				<div class="wpappninja_div">
					<h3><?php _e('Special features', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('&bull; <b>Comments</b>: show the comments and a form to publish on posts<br/>&bull; <b>Subscribe system</b>: add an item on the menu pointing to subscribed category and a button on each post to (un)subscribe<br/>&bull; <b>Favorite system</b>: add an item on the menu to list all favorited posts and a button on each post to favorite<br/>&bull; <b>Share bubble</b>: show a button on each post to share it on social networks', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Comments', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[commentaire]"><option value="1" <?php if (get_wpappninja_option('commentaire', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('commentaire', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Subscribe system', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[show_abonnement]"><option value="1" <?php if (get_wpappninja_option('show_abonnement', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_abonnement', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Favorite system', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[show_favori]"><option value="1" <?php if (get_wpappninja_option('show_favori', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_favori', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Sharing system', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[share]"><option value="1" <?php if (get_wpappninja_option('share', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('share', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Search engine', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[show_search]"><option value="1" <?php if (get_wpappninja_option('show_search', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_search', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
					</table>
				</div>
				<br/>

				<div class="wpappninja_div">
					<h3><?php _e('Links', 'wpappninja');?></h3>
					<table class="form-table">

						<tr valign="top">
							<th scope="row"><?php _e('Open all links in the internal browser', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[all_link_browser]"><option value="1" <?php if (get_wpappninja_option('all_link_browser', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('all_link_browser', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Open all links on the same screen (faster)', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[nodeeplink]"><option value="1" <?php if (get_wpappninja_option('nodeeplink', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('nodeeplink', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Open in browser menu option (Android only)', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[show_browser]"><option value="1" <?php if (get_wpappninja_option('show_browser', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_browser', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
					</table>
				</div>
				<br/>



				<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[launchscreen]" value="<?php echo get_wpappninja_option('launchscreen', '0');?>" />

				<div class="wpappninja_div">
					<h3><?php _e('Posts listing', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('<b>Small cards</b> Little image on left and title on right of the image<br/><b>Big cards</b> Big image and title below', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Default view', 'wpappninja');?></th>
							<td>
								<?php $typedevue = get_wpappninja_option('typedevue', 'big'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[typedevue]">
									<option value="big" <?php if (get_wpappninja_option('typedevue', 'big') == 'big'){echo 'selected';} ?>><?php _e('Big cards', 'wpappninja');?></option>
									<option value="small" <?php if (get_wpappninja_option('typedevue', 'big') == 'small'){echo 'selected';} ?>><?php _e('Small cards', 'wpappninja');?></option>
								</select>
							</td>
						</tr>


						<tr valign="top">
							<th scope="row"><?php _e('Order by', 'wpappninja');?></th>
							<td>
								<?php $orderby = get_wpappninja_option('orderby_list', 'post_date'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[orderby_list]">

									<option value="post_date" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'post_date'){echo 'selected';} ?>><?php _e('Date', 'wpappninja');?></option>

									<option value="comment_count" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'comment_count'){echo 'selected';} ?>><?php _e('Comment count', 'wpappninja');?></option>

									<option value="author" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'author'){echo 'selected';} ?>><?php _e('Author', 'wpappninja');?></option>

									<option value="title" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'title'){echo 'selected';} ?>><?php _e('Title', 'wpappninja');?></option>

									<option value="modified" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'modified'){echo 'selected';} ?>><?php _e('Last modified date', 'wpappninja');?></option>

									<option value="rand" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'rand'){echo 'selected';} ?>><?php _e('Random', 'wpappninja');?></option>


									<option value="none" <?php if (get_wpappninja_option('orderby_list', 'post_date') == 'none'){echo 'selected';} ?>><?php _e('No order', 'wpappninja');?></option>

								</select>
							</td>
						</tr>


						<tr valign="top">
							<th scope="row"><?php _e('Order', 'wpappninja');?></th>
							<td>
								<?php $orderby = get_wpappninja_option('orderby_list', 'post_date'); ?>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[order_list]">

									<option value="ASC" <?php if (get_wpappninja_option('order_list', 'DESC') == 'ASC'){echo 'selected';} ?>><?php _e('Ascending order from lowest to highest values', 'wpappninja');?></option>		
									
									<option value="DESC" <?php if (get_wpappninja_option('order_list', 'DESC') == 'DESC'){echo 'selected';} ?>><?php _e('Descending order from highest to lowest values', 'wpappninja');?></option>


								</select>
							</td>
						</tr>



					</table>	
				</div>
				<br/>
				
				<div class="wpappninja_div">
					<h3><?php _e('Image', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('If a post is published without image, you can define here a default image.', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Default image url (optional)', 'wpappninja');?></th>
							<td><input type="text" placeholder="http://example.com/image.png" name="<?php echo WPAPPNINJA_SLUG;?>[defautimg]" value="<?php echo get_wpappninja_option('defautimg');?>" /></td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e("Use any image on the post as featured?", "wpappninja");?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[anyfeat]"><option value="1" <?php if (get_wpappninja_option('anyfeat', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('anyfeat', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e("Hide image on lists?", "wpappninja");?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[disablefeat]"><option value="1" <?php if (get_wpappninja_option('disablefeat', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('disablefeat', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e("Hide image on pages?", "wpappninja");?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[hideimgonlypage]"><option value="1" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('hideimgonlypage', '0') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
					</table>	
				</div>
				<br/>

				<div class="wpappninja_div">
					<h3><?php _e('Title', 'wpappninja');?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Show the title before content?', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[remove_title]"><option value="1" <?php if (get_wpappninja_option('remove_title', '0') == '1'){echo 'selected';} ?>><?php _e('No');?></option><option value="0" <?php if (get_wpappninja_option('remove_title', '0') == '0'){echo 'selected';} ?>><?php _e('Yes', 'wpappninja');?></option></select></td>
						</tr>
					</table>
				</div>
				<br/>
				
				<div class="wpappninja_div">
					<h3><?php _e('Date and time', 'wpappninja');?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Date type', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[datetype]">
								<option value="date" <?php if (get_wpappninja_option('datetype', 'date') == 'date'){echo 'selected';} ?>><?php echo date_i18n( get_option( 'date_format' ), current_time('timestamp'));?></option>
								<option value="ilya" <?php if (get_wpappninja_option('datetype', 'date') == 'ilya'){echo 'selected';} ?>><?php _e('5 days ago', 'wpappninja');?></option>
							</select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e("Show date on list/post?", "wpappninja");?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[remove_date]"><option value="1" <?php if (get_wpappninja_option('remove_date', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('remove_date', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e("Show hour?", "wpappninja");?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[showdate]"><option value="1" <?php if (get_wpappninja_option('showdate', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('showdate', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
					</table>
				</div>
				<br/>
				
				<div class="wpappninja_div">
					<h3><?php _e('Posts content', 'wpappninja');?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Show author name and avatar before post', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[show_avatar]"><option value="1" <?php if (get_wpappninja_option('show_avatar', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_avatar', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Show date before post', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[show_date]"><option value="1" <?php if (get_wpappninja_option('show_date', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('show_date', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Show author bio after post', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[bio]"><option value="1" <?php if (get_wpappninja_option('bio', '1') == '1'){echo 'selected';} ?>><?php _e('Yes');?></option><option value="0" <?php if (get_wpappninja_option('bio', '1') == '0'){echo 'selected';} ?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
					</table>
				</div>
				<br/>
				<div class="wpappninja_div">
					<h3><?php _e('Similar posts', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('You can show similar posts after an article to engage your users.', 'wpappninja');?></p>
					<table class="form-table">
						<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[similaire]" value="1" />
						<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[nbsimilar]" value="10" />
						<tr valign="top">
							<th scope="row"><?php _e('Number', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[similarnb]">
							<?php
							$nbsimi = get_wpappninja_option('similarnb', 10);
							for ($i=0;$i<21;$i++) {
								echo '<option ';if ($i == $nbsimi){echo 'selected';}echo '>'.$i.'</option>';
							}
							?>
							</select></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Selection rule', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[similartype]">
								<?php
								$taxonomy = wpappninja_get_all_taxonomy();
								$_taxonomy = get_taxonomies(array('public'=>true), 'objects');
								foreach($_taxonomy as $p => $k) { ?>
									<option value="<?php echo $k->name;?>" <?php if (get_wpappninja_option('similartype', 'category') == $k->name){echo 'selected';} ?>><?php echo $k->labels->name;?></option>
								<?php } ?>
							</select></td>
						</tr>
					</table>
				</div>
			</div>
			
			<div id="wpappninja_i__theme" class="wpappninja_i_">
				<h2><?php _e('Theme of the app', 'wpappninja');?></h2>
				<p class="wpappninja_help"><?php _e('You can change the theme of your app when you want, it will be modified in real time on all devices.', 'wpappninja');?></p>
				<div class="wpappninja_div" style="max-width:5000px">
					<table class="form-table">		
						<tr valign="top">
						<td class="mini_android_label">
						
							<?php
							//if ($hasPremiumTheme) {
								?>
								<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="premium" checked /><div class="mini_android"><div class="mini_android_toolbar" style="background:<?php echo $primary;?>"></div><div class="mini_android_bubble" style="background:<?php echo $secondary;?>"></div></div></label>
								<?php
							//}
							?>
							
							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="black" <?php if (get_wpappninja_option('theme') == 'black'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#818286"></div><div class="mini_android_bubble" style="background:#000000"></div></div></label>
							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="grisrouge" <?php if (get_wpappninja_option('theme') == 'grisrouge'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#607D8B"></div><div class="mini_android_bubble" style="background:#ea5758"></div></div></label>
							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="blue" <?php if (get_wpappninja_option('theme') == 'blue'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#03A9F4"></div><div class="mini_android_bubble" style="background:#FF5252"></div></div></label>


							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="blueyellow" <?php if (get_wpappninja_option('theme') == 'blueyellow'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#2196f3"></div><div class="mini_android_bubble" style="background:#ffeb3b"></div></div></label>
							
							
							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="vert" <?php if (get_wpappninja_option('theme') == 'vert'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#4CAF50"></div><div class="mini_android_bubble" style="background:#A62A54"></div></div></label>

							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="rouge" <?php if (get_wpappninja_option('theme') == 'rouge'){echo 'checked';} ?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#F44336"></div><div class="mini_android_bubble" style="background:#8BC34A"></div></div></label>


							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="redblack" <?php if (get_wpappninja_option('theme') == 'redblack'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#f44336"></div><div class="mini_android_bubble" style="background:#000000"></div></div></label>


							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="lime" <?php if (get_wpappninja_option('theme') == 'lime'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#cddc39"></div><div class="mini_android_bubble" style="background:#ff4081"></div></div></label>

							<label><input name="<?php echo WPAPPNINJA_SLUG;?>[theme]" type="radio" value="orangeblue" <?php if (get_wpappninja_option('theme') == 'orangeblue'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#ff9800"></div><div class="mini_android_bubble" style="background:#536dfe"></div></div></label>
						</td>
						</tr>
					</table>
				</div>
			</div>

			<div id="wpappninja_i__themenew" class="wpappninja_i_">
				<h2><?php _e('Theme of the app', 'wpappninja');?></h2>
				<p class="wpappninja_help"><?php _e('You can change the theme of your app when you want, it will be modified in real time on all devices.', 'wpappninja');?></p>
				<div class="wpappninja_div">

					<div style="background:#fdfdfd;border:1px solid #eee;padding:20px;max-width:500px;" class="wpapp_admin_w100">
						<div class="mini_android" style="margin-bottom:0!important"><div id="wpapp_color_primary" class="mini_android_toolbar" style="background:<?php echo $app_theme_primary;?>"></div><div class="mini_android_bubble" style="background:<?php echo $app_theme_accent;?>" id="wpapp_color_accent"></div></div>
					</div>
					<br/>

					<?php _e('Primary color', 'wpappninja');?><br/>
					<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[app][theme][primary]" value="<?php echo $app_theme_primary;?>" class="wpapp-color-picker-primary" required /><br/><br/>
					<?php _e('Secondary color', 'wpappninja');?><br/>
					<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[app][theme][accent]" value="<?php echo $app_theme_accent;?>" class="wpapp-color-picker-accent" required  /><br/>

				</div>
			</div>

			<div id="wpappninja_i__right" class="wpappninja_i_">
				<h2><?php _e('Access right', 'wpappninja');?></h2>
				<p class="wpappninja_help"><?php _e('You change the level of right needed to access the statistics, notifications and settings panel.', 'wpappninja');?></p>
				<div class="wpappninja_div">

						<h4><?php echo __('Level', 'wpappninja');?></h4>

						<?php
						$right = array(
										'manage_options' 	=> __('Administrator'),
										'edit_posts' 		=> __('Editor'),
										'publish_posts' 	=> __('Author'),
										'read' 				=> __('Subscriber'),
									);
									?>

						<select name="<?php echo WPAPPNINJA_SLUG;?>[right]">

							<?php
							foreach ($right as $r => $n) {
								echo '<option value="' . $r . '" ';if (get_wpappninja_option('right', 'manage_options') == $r) {echo 'selected';}echo '>' . $n . '</option>';
							} ?>

						</select>

				</div>
			</div>

			<script type="text/javascript">
			var wpappninja_enable_speed_menu = <?php if(get_wpappninja_option('speed') == '1') {echo '1';}else{echo '0';}?>;
			</script>
	
			
			<div id="wpappninja_i__warning" class="wpappninja_i_" style="max-width:500px">
			<span style="
    display: block;
    background: #d8ebf1;
    padding: 40px;
    width: 100%;
    text-align: center;
    font-size: 25px;
"><?php _e('Build the app menu with your theme', 'wpappninja');echo ' <b>' . get_wpappninja_option('wpappninja_main_theme');?></b></span>
			</div>
			<?php
			$nomoreselector = false;
			foreach($wpappninja_array_lang as $currentname => $current_locale) { ?>
			<div id="wpappninja_i__menu_<?php echo $current_locale;?>" class="wpappninja_i_" style="max-width:640px">

				<?php if (!$nomoreselector) { ?>
				<div style="display:<?php if (get_wpappninja_option('speed') == '0' && isset($_GET['onlymenu_trad'])) {echo 'block';}else{echo 'none';}?>">
					<?php $lang_exclude = get_wpappninja_option('lang_exclude', array()); ?>

					<table class="form-table" style="
    background: #fafafa;
    border: 2px solid #eee;
    margin: 0 0 31px;
">
						<tr valign="top">
							<th scope="row"><?php _e('Language to enable', 'wpappninja');?></th>
							<td>
							<?php
							foreach ( wpappninja_available_lang(true) as $name => $code ) {
								echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[lang_exclude][]" value="' . $code . '" ';
								if (in_array($code, $lang_exclude)) {
									echo 'checked';
								}
								echo ' /> ' . $name . '</label><br/>';
							}
							?>
							</td>
						</tr>
					</table>
				</div>
				<?php $nomoreselector = true;} ?>

				<div <?php if (get_wpappninja_option('speed') == '1' || 1>0) {echo 'style="display:block"';}?>>
				<?php
				if (count($wpappninja_array_lang) > 1 && get_wpappninja_option('speed') != '1' && isset($_GET['onlymenu_trad'])) {
				foreach($wpappninja_array_lang as $name => $code) {

					if ($code != 'speed') {
					echo '<a style="';/*if ($current_locale == $code){echo 'background-color: #0073AA!important;color: white;';}*/echo 'min-height: 0;width: 110px;" class="wpappninja_item wpappninja_label_menu_'.$code.'" href="#" onclick="wpappninja_toggle(this, \'menu_'.$code.'\');return false"><img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" />';
					if ((count(wpappninja_get_menu_reloaded($code)) == 0)) {
						echo '<span style="color:red" class="dashicons dashicons-warning"></span>';
					} else {
						echo '<span style="color:green" class="dashicons dashicons-yes"></span>';
					}
					echo '<br/>'.$name.'</a>';
					}
				}
				} ?>



<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Homepage', 'wpappninja');?></h2>

				<div class="wpappninja_div" style="max-width:100%">



<!--<a class="button button-primary button-large wpappninja-add-item" href="#" onclick="wpappninja_open_editor('<?php echo $current_locale;?>', true);return false"><?php _e('SELECT HOMEPAGE', 'wpappninja');?></a>-->

<br/><br/>

				<!--<h3><?php _e('Homepage', 'wpappninja');?></h3>-->
				<!--<p class="wpappninja_help"><?php _e('The homepage is the main screen of your application.', 'wpappninja');?></p>-->
				
				<?php				
				echo '<div style="padding: 8px;margin-bottom:0;background:white;border: 1px solid #ccc;">';

				if (get_wpappninja_option('speed') == '1') {
					echo '<div style="float:left;margin: 18px 0 0 18px;"><img style="inline-block;margin-right: 25px;width: 35px;" src="' . WPAPPNINJA_SVG_URL . get_wpappninja_option('pageashomeicon_'.$current_locale, 'chevron_right').'.svg" /></div>';
				} else {
					echo '<div style="float:left;margin: 18px 0 0 18px;"><img style="inline-block;margin-right: 25px;width: 35px;" src="' . WPAPPNINJA_ASSETS_IMG_URL . 'icons/'.get_wpappninja_option('pageashomeicon_'.$current_locale, 'arrowlight').'.png" /></div>';
				}

				echo '<div style="float:left;margin-top:9px;">';

			if (preg_match('#wpapp_shortcode=wpapp_home#', wpappninja_get_home("speed"))) {
				echo '<b style="
    font-size: 20px;
    margin: 10px 0 0;
    display: inline-block;
">';
_e('User homepage', 'wpappninja');
echo '</b><br/><br/><a class="button" target="_blank" href="?page=' . WPAPPNINJA_AUTO_SLUG . '&settings">'.__('CONFIGURE', 'wpappninja').'</a><br/><br/>';
			} else {
			echo '<b style="
    font-size: 20px;
    margin: 10px 0 0;
    display: inline-block;
">'.get_wpappninja_option('pageashometitle_'.$current_locale, 'Recent post').'<br/></b><br/><a href="' . wpappninja_get_home("speed") . '" target="_blank">' . wpappninja_get_home("speed") . '</a>';
				}
				$forcecss = '';

				echo '</div><div style="clear:both;height:13px;"></div>';?><?php echo '<a href="#" class="button button-primary button-large" style="border-radius: 0;margin: 0 0px 9px 77px;color: #333;background: #f5f5f5;'.$forcecss.'border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;" onclick="wpappninja_open_editor(\''.$current_locale.'\', true);return false;">'.__('Edit', 'wpappninja').'</a>
				</div>';
				?>
				
				<table class="form-table" id="wpappninja_menu_home_<?php echo $current_locale;?>" style="padding: 8px;margin-bottom:0;background:white;border: 1px solid #ccc;display:none">
					<tr valign="top">
						<td class="wpappninja_iconselect">
							<div class="label_iconic" id="label_iconic_home_<?php echo $current_locale;?>" style="display:inline-block">
								<?php

								if (get_wpappninja_option('speed') == '1') {
									$files = glob(WPAPPNINJA_SVG_PATH . '*.svg', GLOB_BRACE);
								} else {
									$files = glob(WPAPPNINJA_ICONS_PATH . '*.png', GLOB_BRACE);
								}
								sort($files);
								foreach($files as $file) {
									if (get_wpappninja_option('speed') == '1') {

										if (!preg_match('#_fill#', $file)) {
										
										$file = preg_replace('#.*\/([a-z_\-]+)\.svg$#', '$1', $file);
										echo '<label><input style="display:none" type="radio" name="' . WPAPPNINJA_SLUG . '[pageashomeicon_'.$current_locale.']" value="'.$file.'" ';if (get_wpappninja_option('pageashomeicon_'.$current_locale, 'chevron_right') == $file){echo 'checked';} echo '/><img width="32" src="' . WPAPPNINJA_SVG_URL . $file.'.svg" /><br/>'.preg_replace('#.svg#', '', preg_replace('#_#', ' ', ucfirst($file))).'</label>';

										}


									} else {
										$file = preg_replace('#.*\/([a-z_]+)\.png$#', '$1', $file);
										echo '<label><input style="display:none" type="radio" name="' . WPAPPNINJA_SLUG . '[pageashomeicon_'.$current_locale.']" value="'.$file.'" ';if (get_wpappninja_option('pageashomeicon_'.$current_locale, 'arrowlight') == $file){echo 'checked';} echo '/><img width="32" src="' . WPAPPNINJA_ASSETS_IMG_URL . 'icons/'.$file.'.png" /></label>';
									}
								}

								?>
							</div>
							<div style="float: left;margin-left: 100px;position:relative;margin-top: -29px;"><a href="#" onclick="jQuery('#label_iconic_home_<?php echo $current_locale;?> label').css('display', 'inline-block');return false"><?php _e('Edit');?></a></div>
						</td>
					</tr>
					<tr valign="top">
						<td style="display: block;">

							<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[add_link_homepage_<?php echo $current_locale;?>]" value="" id="wpappninja_temp_add_homepage_<?php echo $current_locale;?>"/>


							<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[pageashome_<?php echo $current_locale;?>]" value="<?php echo get_wpappninja_option('pageashome_'.$current_locale, '');?>" />



						<input style="width: 95%;padding: 10px;font-size: 17px;margin: 0 0 15px;" type="text" name="<?php echo WPAPPNINJA_SLUG;?>[pageashometitle_<?php echo $current_locale;?>]" value="<?php echo get_wpappninja_option('pageashometitle_'.$current_locale, __('Latest posts', 'wpappninja')); ?>"/></td>
					</tr>
				</table>
				</div>
				<br/><br/>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Menu', 'wpappninja');?></h2>

				<?php if (get_wpappninja_option('speed') == '1' && 
					get_wpappninja_option('appify') != '1' && 
					get_wpappninja_option('webview') == '4' && 
					get_wpappninja_option('speed_notheme') == '1' && 
					get_wpappninja_option('nospeed_notheme') == '0') { ?>
<div class="wpappninja_div" style="max-width:100%;"><span style="
    display: block;
    background: #d8ebf1;
    padding: 40px;
    width: 100%;
    text-align: center;
    font-size: 25px;
"><?php _e('Build the app menu with your theme', 'wpappninja');echo ' <b>' . get_wpappninja_option('wpappninja_main_theme');?></b></span></div>
				<?php } ?>
				<div class="wpappninja_div" style="max-width:100%;<?php if (get_wpappninja_option('speed') == '1' && 
			get_wpappninja_option('webview') == '4' && 
			get_wpappninja_option('appify') != '1' && 
			get_wpappninja_option('speed_notheme') == '1' && 
			get_wpappninja_option('nospeed_notheme') == '0') {echo 'display:none;';} ?>">
				<!--<h3><?php echo __('Menu builder', 'wpappninja');?></h3>-->

				<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[add_link_<?php echo $current_locale;?>]" value="" id="wpappninja_temp_add_<?php echo $current_locale;?>"/>

				<textarea style="display:none" id="wpappninja_dummy_textarea"></textarea>

				<script type="text/javascript">
				var wpappninja_locale_js = "";
				var wpappninja_is_homepage = false;

				function wpappninja_open_editor(lang, homepage) {

					wpappninja_is_homepage = false;
					
					<?php if (get_wpappninja_option('speed') == '1') { ?>
					//jQuery("#link-options").css('display', 'none');
					<?php } ?>

					if (homepage === true) {
						//jQuery("#link-options").css('display', 'none');
						wpappninja_is_homepage = true;
					}

					wpappninja_locale_js = lang;
            		wpActiveEditor = true;
            		wpLink.open('wpappninja_dummy_textarea');
    		        return false;
		        }

		        jQuery('body').on('click', '#wp-link-submit', function(event) {
           			var linkAtts = wpLink.getAttrs();

           			if (wpappninja_is_homepage === true) {
           				jQuery("#most-recent-results").css('top', '80px!important');
           				jQuery('#wpappninja_temp_add_homepage_' + wpappninja_locale_js).val(linkAtts.href);
           			} else {
           				jQuery("#most-recent-results").css('top', '200px!important');
           				jQuery('#wpappninja_temp_add_' + wpappninja_locale_js).val(linkAtts.href);
           			}
		            
				    jQuery('#submitme').click();
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
        		</script>

        		<style type="text/css">
        		#link-options .wp-link-text-field, #link-options .link-target {display:none!important;}
.label_iconic {
display:none;
    position: absolute;
    width: 490px;
    height: 250px;
    overflow: scroll;
    background: white;
    box-shadow: 0 0 25px;
    border: 10px solid;
}.label_iconic label {
    display: inline-block!important;
}
        		</style>

				<h4>
					<a class="button button-primary button-large wpappninja-add-item" href="#" onclick="wpappninja_open_editor('<?php echo $current_locale;?>', false);return false"><?php _e('+ ADD AN ITEM TO THE MENU', 'wpappninja');?></a>
					<?php
					echo ' <a class="button button-primary button-large" style="float:right;border-radius: 0;color: darkred;background: #f5f5f5;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;" href="#" onclick="jQuery(\'#wpappninja_i__menu_'.$current_locale.' .wpappninja_menuitem\').remove();return false">'.__('Reset', 'wpappninja').'</a>';
					?>
				</h4>
				<table class="form-table">
				<input name="<?php echo WPAPPNINJA_SLUG;?>[menu_reload_<?php echo $current_locale;?>]" value="" type="hidden" />
				<tr valign="top">
					<td style="padding:0">
						<ul id="wpappninja_sort_menu_<?php echo $current_locale;?>">
						<?php
						$i = 0;
						$items = wpappninja_weight_order(get_wpappninja_option('menu_reload_'.$current_locale));

								if (get_wpappninja_option('speed') == '1') {
									$files = glob(WPAPPNINJA_SVG_PATH . '*.svg', GLOB_BRACE);
								} else {
									$files = glob(WPAPPNINJA_ICONS_PATH . '*.png', GLOB_BRACE);
								}

								sort($files);
								
						foreach($items as $item) {

							if (!isset($item['feat'])) {
								$item['feat'] = '0';
							}
							
							echo '<li><div class="wpappninja_menuitem" id="wpappninja_item_'.$current_locale.''.$i.'_head" style="padding: 8px;margin-bottom:0;background:white;border: 1px solid #ccc;"><input style="display:none" class="wpappninja_position" name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][weight]" type="number" value="'.$item['weight'].'" />';
							
							echo '<div style="float:right;color:gray;font-size:15px;cursor: move;"><span class="dashicons dashicons-sort"></span></div>';
							
						if (get_wpappninja_option('speed') != '1') {
							echo '<label><input style="display:none" class="wpappninja_radio" onclick="jQuery(\'#wpappninja_i__menu_'.$current_locale.' .wpappninja_radio\').attr(\'checked\', false);jQuery(this).attr(\'checked\', true);jQuery(\'#wpappninja_i__menu_'.$current_locale.' .wpappninja_stars\').css(\'display\', \'none\');jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_star\').css(\'display\', \'block\');jQuery(\'#wpappninja_i__menu_'.$current_locale.' .wpappninja_stars_empty\').css(\'display\', \'block\');jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_star_empty\').css(\'display\', \'none\');" type="radio" name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][feat]" value="1" ';if (isset($item['feat'])){if ($item['feat'] == '1'){echo 'checked';}}echo ' />';

							echo '<span id="wpappninja_item_'.$current_locale.''.$i.'_star" class="wpappninja_stars dashicons dashicons-star-filled" style="margin: 0px 10px 0 0;color: #FFAE2F;float: right;display:none;';
							if (isset($item['feat'])){if ($item['feat'] == '1'){echo 'display:block';}}
							echo '"></span>';

							echo '<span id="wpappninja_item_'.$current_locale.''.$i.'_star_empty" class="wpappninja_stars_empty dashicons dashicons-star-empty" style="margin: 0px 10px 0 0;color: gray;float: right;display:block;';
							if (isset($item['feat'])){if ($item['feat'] == '1'){echo 'display:none';}}
							echo '"></span>';
							echo '</label>';
						}



							if (get_wpappninja_option('speed') == '1') {
								echo '<div style="float:left;margin: 18px 0 0 18px;"><img style="inline-block;margin-right: 25px;width: 35px;" id="wpappninja_item_'.$current_locale.''.$i.'_icon" src="' . WPAPPNINJA_SVG_URL . $item['icon'].'.svg" /></div>';
							} else {
								echo '<div style="float:left;margin: 18px 0 0 18px;"><img style="inline-block;margin-right: 25px;width: 35px;" id="wpappninja_item_'.$current_locale.''.$i.'_icon" src="' . WPAPPNINJA_ASSETS_IMG_URL . 'icons/'.$item['icon'].'.png" /></div>';
							}
							echo '<div style="float:left;line-height: 29px;margin-top:9px;width:260px;">';

							if (isset($item['role']) && $item['role'] != "") {
								$user_role_d = $item['role'];

								if ($item['role'] == "anonymous") {
									$user_role_d = __('Not logged in', 'wpappninja');
								} elseif ($item['role'] == "notanonymous") {
									$user_role_d = __('Logged in', 'wpappninja');
								}

								echo '<div style="    line-height: 15px;background: #ecab34;display: inline-block;color: white;padding: 3px 5px;font-size: 10px;text-transform: uppercase;border-radius: 8px;text-align: right;right: 0;">'.__('Restricted to', 'wpappninja').' '.$user_role_d.'</div><br/>';
							}

							if (get_wpappninja_option('speed') == '1') {
								if ($item['feat'] == '0') {
									echo '<div style="    line-height: 15px;background: #8484fb;display: inline-block;color: white;padding: 3px 5px;font-size: 10px;text-transform: uppercase;border-radius: 8px;text-align: right;right: 0;">'.__('Left menu', 'wpappninja').'</div>';
								} elseif ($item['feat'] == '1') {
									echo '<div style="    line-height: 15px;background: #8484fb;display: inline-block;color: white;padding: 3px 5px;font-size: 10px;text-transform: uppercase;border-radius: 8px;text-align: right;right: 0;">'.__('Toolbar menu', 'wpappninja').'</div>';
								} elseif ($item['feat'] == '2') {
									echo '<div style="    line-height: 15px;background: #8484fb;display: inline-block;color: white;padding: 3px 5px;font-size: 10px;text-transform: uppercase;border-radius: 8px;text-align: right;right: 0;">'.__('Floating Action Bar', 'wpappninja').'</div>';
								}
								echo '<br/>';
							}

							if ($item['id'] == 'http://separatorend'){echo '<b>'.__('Group end', 'wpappninja').'</b><br/>';}
							if ($item['id'] == 'http://separator'){echo '<b>'.__('Group start', 'wpappninja').'</b><br/>';}

							if ($item['id'] == 'http://separatorend'){echo '<div style="display:none">';}

							echo '<b><span style="font-size: 20px; margin: 10px 0 0;display: inline-block;" id="wpappninja_item_'.$current_locale.''.$i.'_en">'.$item['name'].'</span>';
							if ($item['id'] == 'http://separatorend'){echo '</div>';}

							if ($item['id'] == 'http://separatorend' OR $item['id'] == 'http://separator'){echo '<div style="display:none">';}
							echo '<br/></b><a href="' . wpappninja_get_http_link($item) . '" target="_blank">' . wpappninja_get_http_link($item) . '</a><br/>';
							if ($item['id'] == 'http://separatorend' OR $item['id'] == 'http://separator'){echo '</div>';}
							
							$forcecss = '';
							if ($item['id'] == '') {
								$forcecss = 'color: #FFF;font-weight:700;background: #FF6262;';
							}
							
							echo '</div><div style="clear:both;height:13px;"></div><a href="#" class="button button-primary button-large" style="';echo 'margin: 0 0px 9px 77px;border-radius: 0;color: #333;background: #f5f5f5;'.$forcecss.'border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;" onclick="jQuery(this).css({ \'background-color\': \'#f5f5f5\', \'font-weight\': \'500\' , \'color\': \'black\'});jQuery(this).parent().toggle();jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'\').toggle();return false;">'.__('Edit', 'wpappninja').'</a>';
							echo '</div>';
							
							echo '<div class="wpappninja_menuitem" id="wpappninja_item_'.$current_locale.''.$i.'" style="display:none;background:white;padding-top:22px;margin-bottom:0">';
								
								// help
								//echo '<p class="wpappninja_help">'.sprintf(__('&bull; <b>Featured</b> can be used only on 1 item %s<br/>&bull; <b>Icon</b> is displayed to the right of the item<br/>&bull; <b>Destination</b> is the page, category, ... that is opened on click<br/>&bull; <b>Label</b> is the name of the item', 'wpappninja'), '<img src="'. WPAPPNINJA_ASSETS_IMG_URL . 'feat.png" style="display:block;margin: 5px 0 0 8px;width: 200px;" />').'</p>';

								// feat?


								
								// icon
                            echo '<div ';if ($item['id'] == 'http://separatorend'){echo 'style="display:none;" ';}echo ' class="label_iconic" id="label_iconic_'.$i.'"><input style="display:none" type="text" name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][icon]" id="wpmobilemenuicon'.$i.'" value="'.$item['icon'].'" />';

								foreach($files as $file) {
									if (get_wpappninja_option('speed') == '1') {

										if (!preg_match('#_fill#', $file)) {

										$file = preg_replace('#.*\/([a-z_\-]+)\.svg$#', '$1', $file);
                                            echo '<label onclick="jQuery(\'#label_iconic_'.$i.'\').css(\'display\', \'none\');jQuery(\'#wpmobilemenuicon'.$i.'\').val(\''.$file.'\');jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_icon\').attr(\'src\',\'' . WPAPPNINJA_SVG_URL . $file.'.svg\');"><img src="' . WPAPPNINJA_SVG_URL . $file.'.svg" /><br/>'.preg_replace('#.svg#', '', preg_replace('#_#', ' ', ucfirst($file))).'</label>';

										}
									} else {
										$file = preg_replace('#.*\/([a-z_]+)\.png$#', '$1', $file);
										echo '<label onclick="jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_icon\').attr(\'src\',\'' . WPAPPNINJA_ASSETS_IMG_URL . 'icons/'.$file.'.png\')"><input style="display:none" type="radio" name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][icon]" value="'.$file.'" ';if ($item['icon'] == $file){echo 'checked';}echo ' /><img src="' . WPAPPNINJA_ASSETS_IMG_URL . 'icons/'.$file.'.png" /></label>';
									}
								}
								echo '</div>';
				
								// edit icon link
								echo '<div style="';if ($item['id'] == 'http://separatorend'){echo 'display:none;';}echo '"><a style="    text-decoration: none;
    background: whitesmoke;
    text-transform: uppercase;
    font-weight: 700;
    padding: 15px;
    display: inline-block;
    border-radius: 4px;
    margin: 0 0 15px;" href="#" onclick="jQuery(\'#label_iconic_'.$i.'\').css(\'display\', \'block\');return false">'.__('Change the icon').'</a></div>';
								
								// item
								if ($item['id'] == 'http://separatorend' OR $item['id'] == 'http://separator'){echo '<div style="display:none;">';}
								echo '<br/>
								'.__('URL', 'wpappninja').'<br/>
								<input style="width: 95%;padding: 10px;font-size: 17px;margin: 0 0 15px;" type="text" name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][id]" value="'.$item['id'].'" /><br/>';

								if ($item['id'] == 'http://separatorend' OR $item['id'] == 'http://separator'){echo '</div>';}

								if ($item['id'] == 'http://separatorend'){echo '<div style="display:none;">';}

								echo __('Title', 'wpappninja').'<br/>

								<input style="width: 95%;padding: 10px;font-size: 17px;margin: 0 0 15px;" id="wpappninja_item_'.$current_locale.''.$i.'_en_" type="text" name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][name]" value="' . $item['name'] . '"/>';
								if ($item['id'] == 'http://separatorend'){echo '</div>';}

								if (get_wpappninja_option('speed') == '1') {


									if ($item['id'] == 'http://separatorend' OR $item['id'] == 'http://separator'){echo '<div style="display:none;">';}
									echo '<br/><br/>'.__('Location', 'wpappninja').'<br/><select name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][feat]"><option value="0">' . __('Left menu','wpappninja') . '</option><option value="1"';if($item['feat'] == '1'){echo ' selected';}echo '>' . __('Toolbar menu', 'wpappninja') . '</option><option value="2"';if($item['feat'] == '2'){echo ' selected';}echo '>' . __('Floating Action Bar', 'wpappninja') . '</option></select>';
								
								if ($item['id'] == 'http://separatorend' OR $item['id'] == 'http://separator'){echo '</div>';}

								
								echo '<br/><br/>'.__('Assigned to a role (optional)', 'wpappninja').'<br/><select name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][role]">

									<option></option>';

									echo '<option value="anonymous" ';
									if ("anonymous" == $item['role']) {echo 'selected';}
									echo '>'.__('Not logged in', 'wpappninja').'</option>';


									echo '<option value="notanonymous" ';
									if ("notanonymous" == $item['role']) {echo 'selected';}
									echo '>'.__('Logged in', 'wpappninja').'</option>';


									echo '<option disabled>---</option>';

									foreach (get_editable_roles() as $role_name => $role_info) {

										echo '<option value="'.$role_info['name'].'" ';
										if ($role_info['name'] == $item['role']) {echo 'selected';}
										echo '>'.$role_info['name'].'</option>';

									}

								echo '</select>';

}

								echo '<input type="hidden" name="' . WPAPPNINJA_SLUG . '[menu_reload_'.$current_locale.']['.$i.'][type]" value="'.$item['type'].'" />';
								
								// delete
								echo '<br/><br/>
								<a href="#" onclick="jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_en\').text(jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_en_\').val());jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_head\').toggle();jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'\').toggle();return false" class="button button-primary button-large" style="border-radius: 0;color: white;background: #3F963F;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;padding-left:20px;padding-right:20px;">'.__('OK').'</a> &nbsp;&nbsp;&nbsp; 
								<a href="#" onclick="jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'_head\').remove();jQuery(\'#wpappninja_item_'.$current_locale.''.$i.'\').remove();return false" class="button button-primary button-large" style="border-radius: 0;color: darkred;background: #f5f5f5;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;">'.__('Delete').'</a>';
								
							echo '</div></li>';
							
							$i++;
						}
						if ($i == 0) {
							echo '<div style="padding:50px;color:gray;background:#ffe;text-align:center;font-size:25px;">' . __('Empty', 'wpappninja') . '</div>';
						}

						?>
						</ul>
						<script type="text/javascript">
						var wpappninja_i<?php echo $current_locale;?> = '<?php echo $i;?>';
						var WPAPPNINJA_ASSETS_IMG_URL = '<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>';
						<?php /*
						function wpappninja_addItem_<?php echo $current_locale;?>(type) {
							
							<?php
							$wpappninja_icone = '';
							$files = glob(WPAPPNINJA_ICONS_PATH . '*.png', GLOB_BRACE);
							foreach($files as $file) {
								$file = preg_replace('#.*\/([a-z_]+)\.png$#', '$1', $file);
								$wpappninja_icone .= '<label><input onclick="jQuery(\\\'#wpappninja_item_'.$current_locale.'\' + wpappninja_i'.$current_locale.' + \'_icon\\\').attr(\\\'src\\\',\\\'' . WPAPPNINJA_ASSETS_IMG_URL . 'icons/'.$file.'.png\\\')" type="radio" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.' + \'][icon]" value="'.$file.'" ';if ('arrow' == $file){$wpappninja_icone .= 'checked';}$wpappninja_icone .= ' /> <img src="' . WPAPPNINJA_ASSETS_IMG_URL . 'icons/'.$file.'.png" /></label>';
							}
							echo 'wpappninja_icone = \'' . $wpappninja_icone . '\';';
							
							
							$wpappninja_js_page = '';
							$wpappninja_js_page .='<span><b>PAGE</b><br/><span>Destination</span><br/><select onchange="wpappninja_select_defaut(\\\'wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.' + \'][id]\\\', \\\'wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.' + \'][name]\\\')" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.' + \'][id]" style="width:250px;">';
							$wpappninja_js_page .='<option value=""></option>';
							
							//$wpappninja_js_page .='<optgroup label="'.__('Pages').'">';
							foreach ( $produ as $page ) {
								$wpappninja_js_page .='<option value="' . $page . '">' . addslashes(trim(get_the_title($page))) . '</option>';
							}
							//$wpappninja_js_page .='</optgroup>';
							
							$wpappninja_js_page .='</select><br/><span>Label</span><br/><input id="wpappninja_item_'.$current_locale.'\' + wpappninja_i'.$current_locale.' + \'_en_" type="text" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.' + \'][name]" value=""/><br/><br/></span><input type="hidden" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.' + \'][type]" value="page" />';
							
							$wpappninja_js_form = '';
							if ( is_plugin_active('gravityforms/gravityforms.php') && class_exists('GFAPI') ) {
							$forms = GFAPI::get_forms();
							$wpappninja_js_form .='<span><b>FORM</b><br/><span>Destination</span><br/><select onchange="wpappninja_select_defaut(\\\'wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][id]\\\', \\\'wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][name]\\\')" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][id]" style="width:250px;">';
							$wpappninja_js_form .='<option value=""></option>';
							foreach ( $forms as $form ) {
								$wpappninja_js_form .='<option value="' . $form['id'] . '">' . addslashes(trim($form['title'])) . '</option>';
							}
							$wpappninja_js_form .='</select><br/><span>Label</span><br/><input id="wpappninja_item_'.$current_locale.'\' + wpappninja_i'.$current_locale.'  + \'_en_" type="text" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][name]" value=""/><br/><br/></span>';
							$wpappninja_js_form .='<input type="hidden" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][type]" value="form" />';
							}


							$wpappninja_js_link = '<a href="https://wpmobile.app/android-liens-speciaux/" target="_blank" style="font-size:16px">&raquo; '.__('Guide for special link', 'wpappninja').'</a><br/><br/>';
							$wpappninja_js_link .='<span><b>LINK</b><br/><span>Destination</span><br/><input type="text" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][id]" placeholder="http://example.com/" style="width:250px;" /><br/><span>Label</span><br/><input id="wpappninja_item_'.$current_locale.'\' + wpappninja_i'.$current_locale.'  + \'_en_" type="text" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][name]" value=""/><br/><br/></span>';
							$wpappninja_js_link .='<input type="hidden" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][type]" value="link" />';						
							
							
							$wpappninja_js_cat = '';
							$wpappninja_js_cat .='<span><b>CAT</b><br/><span>Destination</span><br/><select onchange="wpappninja_select_defaut(\\\'wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][id]\\\', \\\'wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][name]\\\')" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][id]" style="width:250px;">';
							$wpappninja_js_cat .='<option value=""></option>';

							$wpappninja_js_cat .= '<option value="0">' . __('Latest posts', 'wpappninja') . '</option>';

							foreach ( $category as $cat ) {
								$wpappninja_js_cat .='<option value="' . $cat->term_id . '">' .addslashes(trim($cat->name)) . '</option>';
							}
							$wpappninja_js_cat .='</select><br/><span>Label</span><br/><input id="wpappninja_item_'.$current_locale.'\' + wpappninja_i'.$current_locale.'  + \'_en_" type="text" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][name]" value=""/><br/><br/></span><input type="hidden" name="wpappninja[menu_reload_'.$current_locale.'][\' + wpappninja_i'.$current_locale.'  + \'][type]" value="cat" />';
							?>
							header = '<li><div class="wpappninja_menuitem" id="wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_head" style="padding: 8px;margin-bottom:0;background:white;border: 1px solid #ccc;display:none;"><input style="display:none" class="wpappninja_position" name="wpappninja[menu_reload_<?php echo $current_locale;?>][' + wpappninja_i<?php echo $current_locale;?> + '][weight]" type="number" value="'+(-999-wpappninja_i<?php echo $current_locale;?>)+'" /><div style="float:right;color:gray;font-size:15px;cursor: move;"><span class="dashicons dashicons-sort"></span></div><span id="wpappninja_item_<?php echo $current_locale;?>'+wpappninja_i<?php echo $current_locale;?>+'_star" class="wpappninja_stars dashicons dashicons-star-filled" style="margin: 0px 10px 0 0;color: #FFAE2F;float: right;display:none;"></span><div style="float:left;"><img style="inline-block;margin-right: 25px;width: 35px;" id="wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_icon" src="' + WPAPPNINJA_ASSETS_IMG_URL + 'icons/arrow.png" /></div><div style="float:left;width:260px;"><b><span id="wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_en"></span><br/></b></div><div style="clear:both;height:13px;"></div><a href="#" class="button button-primary button-large" style="border-radius: 0;color: #333;background: #f5f5f5;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;" onclick="jQuery(this).parent().toggle();jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '\').toggle();return false;"><?php _e('Edit', 'wpappninja');?></a></div><div class="wpappninja_menuitem" id="wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '" style="display:block;background:white;margin-bottom:0"><div style="border-bottom: 1px solid #eee;"><label style="margin: 20px 0;display: inline-block;"><input class="wpappninja_radio" onclick="jQuery(\'#wpappninja_i__menu_<?php echo $current_locale;?> .wpappninja_radio\').attr(\'checked\', false);jQuery(this).attr(\'checked\', true);jQuery(\'#wpappninja_i__menu_<?php echo $current_locale;?>  .wpappninja_stars\').css(\'display\', \'none\');jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_star\').css(\'display\', \'block\');" type="radio" name="wpappninja[menu_reload_<?php echo $current_locale;?>][' + wpappninja_i<?php echo $current_locale;?> + '][feat]" value="1" /> <b><?php _e('Featured', 'wpappninja');?></b></label><div style="clear:both"></div></div><br/><div class="label_iconic" id="label_iconic_' + wpappninja_i<?php echo $current_locale;?> + '">'+wpappninja_icone+'</div><div style="float: left;margin-left: 100px;margin-top: -29px;"><a href="#" onclick="jQuery(\'#label_iconic_' + wpappninja_i<?php echo $current_locale;?> + ' label\').css(\'display\', \'inline-block\');return false"><?php _e('Edit', 'wpappninja');?></a></div><br/>';
	
							if (type == 'page') {
								content = '<?php echo $wpappninja_js_page;?>';
							}
	
							if (type == 'cat') {
								content = '<?php echo $wpappninja_js_cat;?>';
							}
	
							if (type == 'form') {
								content = '<?php echo $wpappninja_js_form;?>';
							}

							if (type == 'link') {
								content = '<?php echo $wpappninja_js_link;?>';
							}
							
							footer = '<br/><br/><a href="#" onclick="jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_en\').text(jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_en_\').val());jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_head\').toggle();jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '\').toggle();return false" class="button button-primary button-large" style="border-radius: 0;color: white;background: #3F963F;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;padding-left:20px;padding-right:20px;">OK</a> &nbsp;&nbsp;&nbsp; <a href="#" onclick="jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '_head\').remove();jQuery(\'#wpappninja_item_<?php echo $current_locale;?>' + wpappninja_i<?php echo $current_locale;?> + '\').remove();return false" class="button button-primary button-large" style="border-radius: 0;color: darkred;background: #f5f5f5;border: 0;box-shadow: 0 0 0;text-shadow: 0 0 0;"><?php _e('Delete', 'wpappninja');?></a></div></li>';
	
							jQuery(header + content + footer).prependTo('#wpappninja_sort_menu_<?php echo $current_locale;?>');
							
							wpappninja_i<?php echo $current_locale;?> += 1;
							
							wpappninja_label();
							jQuery('.label_iconic label').click(wpappninja_label);
						}
						<?php */ ?>
						</script>
					</td>
				</tr>
				

				</table>
				</div>

				<br/>


				<div style="<?php if (get_wpappninja_option('speed') == '1'){echo ';display:none;';}?>">

<?php $section = 'menutitle'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Menu title (ios only)', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Title', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">

						<input type="text" name="wpappninja[iosmenulabel_<?php echo $current_locale;?>]" value="<?php echo get_wpappninja_option('iosmenulabel_' . $current_locale, 'Menu');?>" />

					
	</div>
	<div class="clear"></div>
</div>


</div>


				</div>

				<br/><br/>





<?php $section = 'import'; ?>
<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Import', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		
		<?php _e('Homepage', 'wpappninja');?>

			
	</div>
	<div class="wpappninja-builder-right">

						<select name="<?php echo WPAPPNINJA_SLUG;?>[import_homepage_<?php echo $current_locale;?>]">
							<option value=""></option>
							<option value="1"><?php _e('Yes', 'wpappninja');?></option>
						</select>


	</div>
	<div class="clear"></div>
</div>


<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		

		<?php _e('WordPress menu', 'wpappninja');?>
			
	</div>
	<div class="wpappninja-builder-right">



					<select name="<?php echo WPAPPNINJA_SLUG;?>[import_menu_<?php echo $current_locale;?>]">
							<option value=""></option>
							<?php
							$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
							foreach($menus as $menu) {
								echo '<option value="'.$menu->term_id.'">'.$menu->name.' (' . $menu->count . ')</option>';
							}
							?>
						</select>
	</div>
	<div class="clear"></div>
</div>





</div>





				</div>
			</div>
			
			<script type="text/javascript">jQuery(document).ready(function($){jQuery( "#wpappninja_sort_menu_<?php echo $current_locale;?>" ).sortable({handle: ".dashicons-sort",cursor: "move",stop: function() {jQuery( "#wpappninja_sort_menu_<?php echo $current_locale;?> li" ).each(function( index ) {jQuery(this).find('input.wpappninja_position').val(index);});}});});</script>
			<?php } ?>

			<div id="wpappninja_i__pegi" class="wpappninja_i_">
				<h2><?php _e('Posts visibles in the app', 'wpappninja');?></h2>
				<!--<div class="wpappninja_div">
					<h3><?php _e('Content type', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('Leave only `post` if you do not know what you are doing', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Content type', 'wpappninja');?></th>
							<td>
								<?php
								$posttype = get_wpappninja_option('posttype');
								foreach(get_post_types(array('public'=>true)) as $k) {
									echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[posttype][]" value="' . $k . '" ';
									if (is_array($posttype))
									{
										if (in_array($k, $posttype)) {
											echo 'checked';
										}
									} else if ($k == 'post') {
										echo 'checked';
									}

									$post_type_obj = get_post_type_object($k);
									echo ' /> ' . $post_type_obj->labels->name . '</label><br/>';
								}
								?>
							</td>
						</tr>
					</table>
				</div>
				<br/>
				<div class="wpappninja_div">
					<h3><?php _e('Category type', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('Leave only `category` if you do not know what you are doing', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Category type', 'wpappninja');?></th>
							<td>
								<?php
								$taxonomy = wpappninja_get_all_taxonomy();
								$_taxonomy = get_taxonomies(array('public'=>true), 'objects');
								foreach($_taxonomy as $p => $k) {
									echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[taxonomy][]" value="' . $k->name . '" ';
									if (is_array($taxonomy))
									{
										if (in_array($k->name, $taxonomy)) {
											echo 'checked';
										}
									} else if ($k->name == 'post') {
										echo 'checked';
									}
									echo ' /> ' . $k->labels->name . '</label><br/>';
								}
								?>
							</td>
						</tr>
					</table>
				</div>
				<br/>-->
				<div class="wpappninja_div">
					<h3><?php _e('Posts', 'wpappninja');?></h3>

					<?php
					if (get_wpappninja_option('temp_tag', '') != '') {
						$options            = get_option( WPAPPNINJA_SLUG );
						$options['temp_tag'] = '';

						$tag = get_term_by('name', get_wpappninja_option('temp_tag', ''), 'post_tag');

						if ($tag->term_id != '') {
							$options['excluded'][] = $tag->term_id;
						}

						update_option( WPAPPNINJA_SLUG, $options );
					}
					?>

					<p class="wpappninja_help"><?php _e('Exclude some posts by tags', 'wpappninja');?></p>
					<table class="form-table">

						<tr valign="top">
							<th scope="row"><?php _e('Direct tag name', 'wpappninja');?></th>
							<td>
								<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[temp_tag]" />
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Tags', 'wpappninja');?></th>
							<td>
							<?php
							$excluded = get_wpappninja_option('excluded', array());

							foreach ($excluded as $exclude) {
								$tag = get_tag($exclude);
								echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="' . $exclude . '" checked /> ' . trim($tag->name) . '</label><br/>';
							}

							$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 50));
							foreach ( $tags as $tag ) {

								if (!in_array($tag->term_id, $excluded)) {
									echo '<label><input type="checkbox" name="' . WPAPPNINJA_SLUG . '[excluded][]" value="' . $tag->term_id . '" /> ' . trim($tag->name) . '</label><br/>';
								}
							}
							?>
							</td>
						</tr>
					</table>
				</div>
				<br/>
				<div class="wpappninja_div">
					<p class="wpappninja_help"><?php _e('Hide posts older than X days', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Days', 'wpappninja');?></th>
							<td><input type="number" name="<?php echo WPAPPNINJA_SLUG;?>[maxage]" value="<?php echo get_wpappninja_option('maxage', 365000);?>" /></td>
						</tr>
					</table>
				</div>
				<br/>
				<div class="wpappninja_div">
					<p class="wpappninja_help"><?php _e('Show password protected posts', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Show', 'wpappninja');?></th>
							<td><select name="<?php echo WPAPPNINJA_SLUG;?>[has_password]"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('has_password', '0') == "0"){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option></select></td>
						</tr>
					</table>
				</div>
				<br/>
				<div class="wpappninja_div">
					<h3><?php _e('Search restriction', 'wpappninja');?></h3>
					<p class="wpappninja_help"><?php _e('You can restrict the search engine to only some category of the blog.', 'wpappninja');?></p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Number of category to show', 'wpappninja');?></th>
							<td>
								<select name="<?php echo WPAPPNINJA_SLUG . '[searchnb]';?>">
									<?php
									for($i = 10;$i < 200;$i+=10) {
										echo '<option ';if(get_wpappninja_option('searchnb', 60) == $i) {echo 'selected';}echo '>'.$i.'</option>';
									} ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('By category', 'wpappninja');?></th>
							<td>
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
							</td>
						</tr>



						<tr valign="top">
							<th scope="row"><?php _e('Order by', 'wpappninja');?></th>
							<td>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[orderby_search]">

									<option value="post_date" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'post_date'){echo 'selected';} ?>><?php _e('Date', 'wpappninja');?></option>

									<option value="relevance" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'relevance'){echo 'selected';} ?>><?php _e('Relevance', 'wpappninja');?></option>

									<option value="comment_count" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'comment_count'){echo 'selected';} ?>><?php _e('Comment count', 'wpappninja');?></option>

									<option value="author" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'author'){echo 'selected';} ?>><?php _e('Author', 'wpappninja');?></option>

									<option value="title" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'title'){echo 'selected';} ?>><?php _e('Title', 'wpappninja');?></option>

									<option value="modified" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'modified'){echo 'selected';} ?>><?php _e('Last modified date', 'wpappninja');?></option>

									<option value="rand" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'rand'){echo 'selected';} ?>><?php _e('Random', 'wpappninja');?></option>


									<option value="none" <?php if (get_wpappninja_option('orderby_search', 'relevance') == 'none'){echo 'selected';} ?>><?php _e('No order', 'wpappninja');?></option>

								</select>
							</td>
						</tr>


						<tr valign="top">
							<th scope="row"><?php _e('Order', 'wpappninja');?></th>
							<td>
								<select name="<?php echo WPAPPNINJA_SLUG;?>[order_search]">

									<option value="ASC" <?php if (get_wpappninja_option('order_search', 'DESC') == 'ASC'){echo 'selected';} ?>><?php _e('Ascending order from lowest to highest values', 'wpappninja');?></option>		
									
									<option value="DESC" <?php if (get_wpappninja_option('order_search', 'DESC') == 'DESC'){echo 'selected';} ?>><?php _e('Descending order from highest to lowest values', 'wpappninja');?></option>


								</select>
							</td>
						</tr>







					</table>
				</div>			<div style="clear:both"></div>

			</div>
			
			<br/><br/>
			<input type="submit" id="submitme" class="button button-primary button-large" />
			<div style="clear:both"></div>
			<br/>
			</div>

			<?php
			$rules = wpappninja_get_css_rules();
			foreach ($rules as $r) {
				echo '<input type="hidden" name="' . WPAPPNINJA_SLUG . '[css_' . md5($r['class'] . $r['zone']) . ']" value="' . sanitize_text_field(get_wpappninja_option('css_' . md5($r['class'] . $r['zone']), $r['color'])) . '" />';
			}

			$widgets = wpappninja_get_widgets();
			foreach ($widgets as $r) {
				echo '<input type="hidden" name="' . WPAPPNINJA_SLUG . '[widget_' . md5($r['id']) . ']" value="' . esc_html(get_wpappninja_option('widget_' . md5($r['id']), $r['default'])) . '" />';
			}
			?>

			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[push_category]" value="<?php echo get_wpappninja_option('push_category', '');?>" />


<div style="clear:both"></div>
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[customiconnotif]" value="<?php echo get_wpappninja_option('customiconnotif', 'icon_notif');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[speed]" value="<?php echo get_wpappninja_option('speed', '0');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[qrcode]" value="<?php echo get_wpappninja_option('qrcode');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[alwayspush]" value="<?php echo get_wpappninja_option('alwayspush');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[defaultpushlang]" value="<?php echo get_wpappninja_option('defaultpushlang');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[push_send_type]" value="<?php echo get_wpappninja_option('push_send_type');?>" />



			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[appify]" value="<?php echo get_wpappninja_option('appify');?>" />



			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[rightpush]" value="<?php echo get_wpappninja_option('rightpush', 'manage_options');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[rightstats]" value="<?php echo get_wpappninja_option('rightstats', 'manage_options');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[rightqrcode]" value="<?php echo get_wpappninja_option('rightqrcode', 'manage_options');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[statictoolbar]" value="<?php echo get_wpappninja_option('statictoolbar', '1');?>" />



			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_loader_all_theme]" value="<?php echo get_wpappninja_option('wpmobile_loader_all_theme', '0');?>" />



			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpappninja_pwa]" value="<?php echo get_wpappninja_option('wpappninja_pwa', 'on');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[iosjusttitle]" value="<?php echo get_wpappninja_option('iosjusttitle', 'off');?>" />


			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[cache_type]" value="<?php echo get_wpappninja_option('cache_type', 'networkonly');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpm_lazyload]" value="<?php echo get_wpappninja_option('wpm_lazyload', '1');?>" />


<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[appcachemode]" value="<?php echo get_wpappninja_option('appcachemode', 'prefer-online');?>" />
<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[appcachedelay]" value="<?php echo get_wpappninja_option('appcachedelay', 'dmYHms');?>" />


			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[slidetoopen]" value="<?php echo get_wpappninja_option('slidetoopen','1');?>" />


						<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[infinitescroll]" value="<?php echo get_wpappninja_option('infinitescroll','0');?>" />

			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[speed_notheme]" value="<?php echo get_wpappninja_option('speed_notheme');?>" />


            <input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[speed_reload]" value="<?php echo get_wpappninja_option('speed_reload', '1');?>" />
<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[disable_all_cache]" value="<?php echo get_wpappninja_option('disable_all_cache', 'off');?>" />




			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[nospeed_notheme]" value="<?php echo get_wpappninja_option('nospeed_notheme', '0');?>" />


			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpappninja_main_theme]" value="<?php echo get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App');?>" />

			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[pwa_cache]" value="<?php echo (get_wpappninja_option('pwa_cache', 1) + 1);?>" />



            <input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[login_redirect_after]" value="<?php echo get_wpappninja_option('login_redirect_after', '');?>" />
<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[notimeoutjs]" value="<?php echo get_wpappninja_option('notimeoutjs', '0');?>" />



			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_auto_bp]" value="<?php echo get_wpappninja_option('wpmobile_auto_bp', '0');?>" />
            <input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_auto_wc]" value="<?php echo get_wpappninja_option('wpmobile_auto_wc', '0');?>" />
            <input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_auto_peepso]" value="<?php echo get_wpappninja_option('wpmobile_auto_peepso', '0');?>" />
            <input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_auto_gravity]" value="<?php echo get_wpappninja_option('wpmobile_auto_gravity', '0');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_auto_post]" value="<?php echo get_wpappninja_option('wpmobile_auto_post', '0');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_auto_post_update]" value="<?php echo get_wpappninja_option('wpmobile_auto_post_update', '0');?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[wpmobile_auto_mail]" value="<?php echo get_wpappninja_option('wpmobile_auto_mail', '0');?>" />



			


						<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[listnb]" value="<?php echo get_wpappninja_option('listnb', '10');?>" />


						<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[home_type]" value="<?php echo get_wpappninja_option('home_type', 'list');?>" />



			<?php $all = get_wpappninja_option('home_available', array());
			foreach ($all as $i) {?>
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[home_available][]" value="<?php echo $i;?>" />
			<?php } ?>


			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[nomoreqrcode]" value="<?php echo get_wpappninja_option('nomoreqrcode', '0'); ?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[pdfdrive]" value="<?php echo get_wpappninja_option('pdfdrive', '1'); ?>" />

<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[vibrator]" value="<?php echo get_wpappninja_option('vibrator', '1'); ?>" />


			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[configureok]" value="1" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[disclameeer]" value="1" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[version]" value="<?php echo WPAPPNINJA_VERSION;?>" />

			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[titlespeed]" value="<?php echo get_wpappninja_option('titlespeed'); ?>" />

			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[cache_friendly]" value="<?php echo get_wpappninja_option('cache_friendly'); ?>" />
			<input type="hidden" name="<?php echo WPAPPNINJA_SLUG;?>[agressive_anti_cache]" value="<?php echo get_wpappninja_option('agressive_anti_cache'); ?>" />



			<input name="<?php echo WPAPPNINJA_SLUG;?>[stats_second]" value="<?php echo get_wpappninja_option('stats_second', round(30 * 86400)); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[stats_limit]" value="<?php echo get_wpappninja_option('stats_limit', 10); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[stats_platform]" value="<?php echo get_wpappninja_option('stats_platform', -1); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[stats_lang]" value="<?php echo get_wpappninja_option('stats_lang', ''); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[fastsplash]" value="<?php echo get_wpappninja_option('fastsplash', '500'); ?>" type="hidden" />



			<input name="<?php echo WPAPPNINJA_SLUG;?>[redirection_type]" value="<?php echo get_wpappninja_option('redirection_type', '1'); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[sdk2019]" value="<?php echo get_wpappninja_option('sdk2019', '0'); ?>" type="hidden" />


			<input name="<?php echo WPAPPNINJA_SLUG;?>[nomoretheme]" value="<?php echo get_wpappninja_option('nomoretheme', '0'); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[fastclick]" value="<?php echo get_wpappninja_option('fastclick', '0'); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[fullspeed]" value="<?php echo get_wpappninja_option('fullspeed', '1'); ?>" type="hidden" />
			<input name="<?php echo WPAPPNINJA_SLUG;?>[wpappninja_042018]" value="<?php echo get_wpappninja_option('wpappninja_042018', 'false'); ?>" type="hidden" />


		
			<div style="clear:both"></div>
			
			</form>
			
		</div>
		<div style="clear:both"></div>
		
		<div id="wpapp_custom_back" onclick="this.style.display = 'none';jQuery('#wpapp_custom_form').css('display', 'none');" style="position:fixed;width:100%;height:100%;top:0;left:0;background:#333;opacity:0.6;z-index:99;display:none"></div>

		<?php
	}
	?>

	<script type="text/javascript">
	jQuery(document).ready(function($){
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
	});
	</script>

	<?php
	
	echo wpappninja_talkus();
}

/**
 * Add taxonomy to WPLink popup.
 *
 * @since 4.3.1
 */
add_filter('wp_link_query', 'wpappninja_archive_link_popup', 10, 2);
function wpappninja_archive_link_popup ($results, $query) {

	if (!get_transient('is_wpappninja_ajax')) {
		return $results;
	}

    if($query['offset'] > 0) {
        return $results;
    }
    
    if ($query["s"] != "") {
	    $taxonomy = get_terms(wpappninja_get_all_taxonomy(), array(
	    								'search' => $query["s"],
								    	'orderby' => 'count', 'order' => 'DESC', 'number' => 30,
										'parent' => 0,
										'hide_empty' => true
									));
    
    	foreach ($taxonomy as $tax) {
           	array_unshift($results, array(
                	'ID' => $tax->term_id,
                	'title' => $tax->name,
                	'permalink' => get_term_link($tax),
                	'info' => 'Category'
            	));
       	}
    } else {
	    $taxonomy = get_terms(wpappninja_get_all_taxonomy(), array(
								    	'orderby' => 'count', 'order' => 'DESC', 'number' => 10,
										'parent' => 0,
										'hide_empty' => true
									));
    
    	foreach ($taxonomy as $tax) {
           	array_unshift($results, array(
                	'ID' => $tax->term_id,
                	'title' => $tax->name,
                	'permalink' => get_term_link($tax),
                	'info' => 'Category'
            	));
       	}
    }

    if ($query["s"] == "") {

    if (get_wpappninja_option('speed') == '1') {

    	array_unshift($results, array(
            'ID' => 0,
            'title' => __('Start a group', 'wpappninja'),
            'permalink' => "separator",
            'info' => 'Group'
        ));


    	array_unshift($results, array(
            'ID' => 0,
            'title' => __('End a group', 'wpappninja'),
            'permalink' => "separatorend",
            'info' => 'Group'
        ));

      	array_unshift($results, array(
            'ID' => 0,
            'title' => __('Recent posts', 'wpappninja'),
            'permalink' => preg_replace('#/$#', '', get_home_url()) . "/?wpapp_shortcode=wpapp_recent",
            'info' => 'Recents'
        ));

	    array_unshift($results, array(
            'ID' => -100,
            'title' => __('Notification history', 'wpappninja'),
            'permalink' => preg_replace('#/$#', '', get_home_url()) . "/?wpapp_shortcode=wpapp_history",
            'info' => 'Notification history'
        ));

    	array_unshift($results, array(
            'ID' => -999,
            'title' => __('Push config', 'wpappninja'),
            'permalink' => preg_replace('#/$#', '', get_home_url()) . "/?wpapp_shortcode=wpapp_config",
            'info' => 'Push config'
        ));
    	
    	array_unshift($results, array(
            'ID' => 0,
            'title' => __('Custom homepage', 'wpappninja'),
            'permalink' => preg_replace('#/$#', '', get_home_url()) . "/?wpapp_shortcode=wpapp_home",
            'info' => 'Custom homepage'
        ));
    	
    	array_unshift($results, array(
            'ID' => 0,
            'title' => __('Login page', 'wpappninja'),
            'permalink' => preg_replace('#/$#', '', get_home_url()) . "/?wpapp_shortcode=wpapp_login",
            'info' => 'Login page'
        ));
    	
    	array_unshift($results, array(
            'ID' => 0,
            'title' => __('Share box', 'wpappninja'),
            'permalink' => preg_replace('#/$#', '', get_home_url()) . "/?wpmobileshareme",
            'info' => 'Share box'
        ));

    }

    if (get_wpappninja_option('speed') != '1') {

    	array_unshift($results, array(
            'ID' => 0,
            'title' => __('Recent posts', 'wpappninja'),
            'permalink' => "recent",
            'info' => 'Recents'
        ));

	    array_unshift($results, array(
            'ID' => -100,
            'title' => __('Notification history', 'wpappninja'),
            'permalink' => "notifications",
            'info' => 'Notification history'
        ));

    	array_unshift($results, array(
            'ID' => -999,
            'title' => __('Push config', 'wpappninja'),
            'permalink' => "pushconfig",
            'info' => 'Push config'
        ));

	}
	}

    return $results;
}
