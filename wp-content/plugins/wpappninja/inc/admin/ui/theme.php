<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Manage the app theme
 *
 * @since 6.9.0
 */
function _wpappninja_display_theme_page() {

	if (isset($_POST['wpappninja_theme']) && check_admin_referer('wpappninjatheme')) {





		$options            = get_option( WPAPPNINJA_SLUG );


		$appify = '0';
		

		if (isset($_POST['wpappninja_main_theme'])) {
			$data = explode('|', $_POST['wpappninja_main_theme']);

			$_POST['wpappninja_main_theme'] = $data[1];
			$_POST['wpappninjatype'] = $data[0];

			if ($_POST['wpappninja_main_theme'] == "No theme overlay") {
				$_POST['wpappninja_main_theme'] = "No theme";
				$appify = '1';
			}

		}

		$options['appify'] = $appify;

		if (isset($_POST['wpappninja_main_theme'])) {
			$options['wpappninja_main_theme'] = $_POST['wpappninja_main_theme'];
		}

		if ($_POST['wpappninjatype'] == "1") {

			if ($options['wpappninja_main_theme'] == 'WPMobile.App (light)') {
				$options['wpappninja_main_theme'] = 'WPMobile.App';
				$_POST['wpappninja_main_theme'] = 'WPMobile.App';
			}

			if ($_POST['wpappninja_main_theme'] != 'WPMobile.App') {
				$options['speed'] = "1";
				$options['webview'] = "4";
				$options['speed_notheme'] = "1";
				$options['nospeed_notheme'] = "0";
			} else {
				$options['speed'] = "1";
				$options['webview'] = "4";
				$options['speed_notheme'] = "0";
				$options['nospeed_notheme'] = "0";
			}
		} elseif ($_POST['wpappninjatype'] == "0") {

			if (get_wpappninja_option('speed_trad') == 'weglot') {
				$options['speed_trad'] = 'manual';
			}

			if ($_POST['wpappninja_main_theme'] == 'WPMobile.App (light)') {
				$options['speed'] = "0";
				$options['webview'] = "0";
				$options['speed_notheme'] = "0";
				$options['nospeed_notheme'] = "0";
			} elseif ($_POST['wpappninja_main_theme'] == 'WPMobile.App') {
				$options['speed'] = "0";
				$options['webview'] = "4";
				$options['speed_notheme'] = "0";
				$options['nospeed_notheme'] = "1";
			}  elseif ($_POST['wpappninja_main_theme'] == 'WPMobile.App (hybride)') {
				$options['speed'] = "0";
				$options['webview'] = "1";
				$options['speed_notheme'] = "0";
				$options['nospeed_notheme'] = "1";
			} else {
				$options['speed'] = "0";
				$options['webview'] = "4";
				$options['speed_notheme'] = "0";
				$options['nospeed_notheme'] = "0";
			}
		}


		if (isset($_POST['wpappninja']['app']['theme']['primary'])) {
			$options['app']['theme']['primary'] = $_POST['wpappninja']['app']['theme']['primary'];
			$options['app']['theme']['accent'] = $_POST['wpappninja']['app']['theme']['accent'];

			if (!isset($options['firstcssfill'])) {

				$options['css_74537a66b8370a71e9b05c3c4ddbf522'] = $_POST['wpappninja']['app']['theme']['primary'];
				$options['css_30dedde4388861bae10614e6d3863acb'] = $_POST['wpappninja']['app']['theme']['primary'];
				$options['css_06a182f400cbc8002d5b0aa4d0d2082e'] = $_POST['wpappninja']['app']['theme']['primary'];
				$options['css_51d39016596e1db1ffd8f5118a11dd3c'] = $_POST['wpappninja']['app']['theme']['primary'];
				$options['css_9be9a1df3d0a60c0bc18ff5c65da2d99'] = $_POST['wpappninja']['app']['theme']['primary'];

				$options['css_4fc1ded5c6315ed4e79133a69f3b6d98'] = $_POST['wpappninja']['app']['theme']['accent'];
				$options['css_d115509b7fa9b63e2e07aed34183fea8'] = $_POST['wpappninja']['app']['theme']['accent'];
				$options['css_505d1630f760002560ab00bb1370ef2a'] = $_POST['wpappninja']['app']['theme']['accent'];
				$options['css_6b7947ad360264957ec213610d66ae74'] = $_POST['wpappninja']['app']['theme']['accent'];
				$options['css_0c5c5bf1fda47e5230fff4396a1f8779'] = '#dd5742';

				$options['firstcssfill'] = "ok";
			}
			//$options['css'][''] = $_POST['wpappninja']['app']['theme']['primary']
		}

		foreach($_POST['wpappninja'] as $k => $v) {

			if ($k == "app") {
				continue;
			}

			/*if (preg_match('#^css_|widget_#', $k)) {
				if ($v == "") {
					$v = " ";
				}*/
				if (is_array($v)) {
					$options[$k] = $v;
				} else {
					$options[$k] = stripslashes($v);
				}
			//}
		}

		update_option( WPAPPNINJA_SLUG, $options );
	}


	$app_data = get_wpappninja_option('app');
	$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();
	$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
	$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";
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
    width: 160px!important;
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
	
	

	?>
	<div class="wrap">
		<h1 style="right: 20px;margin: 20px 0 0;position: absolute;"></h1>
		<h2 style="font-size:1.3em"></h2>
		
		<?php $menu_current = 'theme';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>

		<div style="padding: 20px;background: white;margin: 0px 0;border-bottom: 1px solid #fd9b02;border-top: 3px solid #fd9b02;">

<?php require( WPAPPNINJA_ADMIN_UI_PATH   . 'submenu.php' ); ?>



			<form action="" method="post">
				<input type="hidden" name="wpappninja_theme" value="1" />
				<?php wp_nonce_field( 'wpappninjatheme' );?>
				
				<div class="wpappninja_div" style="<?php if (isset($_GET['theme'])) {/*echo ' float:left;width:500px';*/} ?>">


<?php $section = 'theme'; ?>
<h2 style=" <?php if (!isset($_GET['theme']) && get_wpappninja_option('nomoreqrcode') != '0') {echo ' display:none;';} ?><?php if (!isset($_GET['theme']) && get_wpappninja_option('nomoreqrcode') != '1') {echo ' display:none;';} ?> <?php if (get_wpappninja_option('nomoretheme') == '1_0') {echo "display:none;";} ?>background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Theme', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="<?php if (!isset($_GET['theme']) && get_wpappninja_option('nomoreqrcode') != '1') {echo ' display:none;';} ?>">

<div class="wpappninja-builder" style="<?php if (!isset($_GET['theme']) && get_wpappninja_option('nomoreqrcode') != '0') {echo ' display:none;';} ?>">
	<div class="wpappninja-builder-left">
		<?php _e('App theme', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">
				<?php
				$default_homepage = "";
				if (get_wpappninja_option('speed') == '1') {
					$default_homepage = wpappninja_get_home();
				}

				$homepage_wpapp = get_wpappninja_option('pageashome_speed', $default_homepage);

	if (!preg_match('#^http#', $homepage_wpapp)) {

		if (preg_match('#^cat_#', $homepage_wpapp)) {

			$homepage_wpapp = preg_replace('#^cat_#', '', $homepage_wpapp);
			$taxonomy = wpappninja_get_all_taxonomy();

			foreach ($taxonomy as $tax) {
				$obj = get_term_by('id', $homepage_wpapp, $tax);
				if (is_object($obj)) {

					$homepage_wpapp = get_term_link($obj);
					break;
				}
			}
		} else {

			if (get_permalink(intval($homepage_wpapp))) {
				$homepage_wpapp = get_permalink(intval($homepage_wpapp));
			}
		}

		if (!preg_match('#^http#', $homepage_wpapp)) {
			$homepage_wpapp = wpappninja_get_home();
		}
	}

	if (!$homepage_wpapp || $homepage_wpapp == "") {
		
		$homepage_wpapp = get_home_url();
		if (get_wpappninja_option('speed') == '1') {
			$homepage_wpapp = wpappninja_get_home();
		}
	}



				?>
<script>
function wpappninja_frame(elem) {

	/*var url = jQuery(elem).find(':selected').data('url');

	if (url != "") {
		jQuery('#wpappiframe').attr('src', url);
	}*/

}
</script>

<?php if(get_wpappninja_option('nomoretheme') == '1') { ?>

		<select name="wpappninja_main_theme" onchange="wpappninja_frame(this)">
		

			<?php $actuel = get_option( 'current_theme'); ?>

			<!--<optgroup label="<?php _e('WPMobile.App themes', 'wpappninja');?>">-->

			<option  data-url="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=WPMobile.App" value="1|WPMobile.App" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App' && get_wpappninja_option('speed') == '1'){echo 'selected';} ?>><?php _e('Native mobile interface', 'wpappninja');?></option>



				<option data-url="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=<?php echo $actuel;?>" value="1|No theme overlay" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'No theme' && get_wpappninja_option('speed') == '1' && get_wpappninja_option('appify') == '1'){echo 'selected';} ?>><?php echo $actuel . ' + ' .__('Native mobile interface', 'wpappninja');?></option>


			<option style="display:none" data-url="https://support.wpmobile.app/article/62-can-i-test-my-android-and-ios-mobile-app?lang=<?php echo wpmobile_getSupportLang();?>" value="0|WPMobile.App (light)" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App (light)'){echo 'selected';} ?>><?php _e('Super light', 'wpappninja');?></option>

			<!--</optgroup>-->




				<!--<optgroup label="<?php _e('WordPress themes', 'wpappninja');?>">-->
				<option data-url="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=<?php echo $actuel;?>" value="1|No theme" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'No theme' && get_wpappninja_option('speed') == '1' && get_wpappninja_option('appify') == '0'){echo 'selected';} ?>><?php echo $actuel;?></option>

			  	<?php
			  				$themes = wp_get_themes(array('allowed' => null));

			foreach ($themes as $theme) {
				if ($theme->get('parent_theme') == "" && $theme->Name != $actuel) {

				echo '<option '; ?>data-url="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=<?php echo $theme->Name;?>" <?php echo ' value="1|' . $theme->Name . '" ';if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == $theme->Name && get_wpappninja_option('speed') == '1'){echo 'selected';}echo '>' . $theme->Name . '</option>';
				}
			}
			?>
		<!--</optgroup>-->

		</select>

<?php } else { ?>

		<select name="wpappninja_main_theme" onchange="wpappninja_frame(this)">
		
  <optgroup label="WPMobile.App">

			<option  data-url="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=WPMobile.App" value="1|WPMobile.App" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App' && get_wpappninja_option('speed') == '1'){echo 'selected';} ?>><?php _e('WPMobile.App', 'wpappninja');?></option>



			<option value="0|WPMobile.App (hybride)" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App (hybride)' && get_wpappninja_option('speed') == '0'){echo 'selected';} ?>><?php _e('Hybrid', 'wpappninja');?></option>
		


		
			<option value="0|WPMobile.App (light)" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App (light)'){echo 'selected';} ?>><?php _e('Light (only text and image)', 'wpappninja');?></option>


</optgroup>
<?php $actuel = get_option( 'current_theme'); ?>


  <optgroup label="<?php _e('WordPress Theme', 'wpappninja');?>">
				<option value="0|No theme" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'No theme' && get_wpappninja_option('speed') == '0'){echo 'selected';} ?>><?php echo $actuel;?></option>

			<?php
			$themes = wp_get_themes(array('allowed' => null));
			foreach ($themes as $theme) {
				if ($theme->get('parent_theme') == "" && $theme->Name != $actuel) {
					echo '<option value="0|' . $theme->Name . '" ';if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == $theme->Name && get_wpappninja_option('speed') == '0'){echo 'selected';}echo '>' . $theme->Name . '</option>';
				}
			}
			?></optgroup>
			  <optgroup label="<?php _e('WordPress Theme (without UI)', 'wpappninja');?>">

				<option data-url="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=<?php echo $actuel;?>" value="1|No theme" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'No theme' && get_wpappninja_option('speed') == '1'){echo 'selected';} ?>><?php echo $actuel;?></option>


			  	<?php
			foreach ($themes as $theme) {
				if ($theme->get('parent_theme') == "" && $theme->Name != $actuel) {

				echo '<option '; ?>data-url="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=<?php echo $theme->Name;?>" <?php echo ' value="1|' . $theme->Name . '" ';if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == $theme->Name && get_wpappninja_option('speed') == '1'){echo 'selected';}echo '>' . $theme->Name . '</option>';
				}
			}
			?>
</optgroup>


<optgroup label="<?php _e('Old', 'wpappninja');?>">
				<option value="0|WPMobile.App" <?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App' && get_wpappninja_option('speed') == '0'){echo 'selected';} ?>><?php _e('Hybrid', 'wpappninja');?></option>

</optgroup>

		</select>

		<?php } ?>

	</div>
	<div class="clear"></div>
</div>


</div>


				<h2 style="<?php if (!isset($_GET['theme']) && get_wpappninja_option('nomoreqrcode') != '0') {echo ' display:none;';} ?>background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_colordesign').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e("Colors", "wpappninja");?></h2>
				<div class="wpappninja_div" style="<?php if (!isset($_GET['theme']) && get_wpappninja_option('nomoreqrcode') != '0') {echo ' display:none;';} ?><?php if (get_wpappninja_option('nomoretheme') != '1' && !isset($_GET['theme'])) {echo "display:none;";} ?>" id="wpappninja_colordesign">




<div class="wpappninja-builder" style="display:none">
	<div class="wpappninja-builder-left">
		<?php _e('Primary color', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

			<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[app][theme][primary]" value="<?php echo $app_theme_primary;?>" class="wpapp-color-picker-primary" required />

	</div>
	<div class="clear"></div>
</div>
<div class="wpappninja-builder" style="display:none">
	<div class="wpappninja-builder-left">
		<?php _e('Secondary color', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right">

			<input type="text" name="<?php echo WPAPPNINJA_SLUG;?>[app][theme][accent]" value="<?php echo $app_theme_accent;?>" class="wpapp-color-picker-accent" required  />

	</div>
	<div class="clear"></div>
</div>

<?php


if (get_wpappninja_option('appify') == '1' || get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App') {
					$classes = wpappninja_get_css_rules();
					$section = "";
					$customcss = false;
					$disableinfinite = false;
					$searchorder = false;

					foreach ($classes as $c) {

						if ($section != $c['section']) {
							echo '<h4 style="background: #f5f5f5;
    display: inline-block;
    text-transform: uppercase;
    padding: 10px 15px;
    margin: 0px 0 8px 20px;">' . $c['section'] . '</h4>';
							$section = $c['section'];
						}

						if (in_array($c['zone'], array('color', 'border-color', 'background', 'background-color'))) {
							echo '<div class="wpappninja-builder"><div class="wpappninja-builder-left">' . $c['help'] . '</div><div class="wpappninja-builder-right">';
							echo '<input type="text" name="' . WPAPPNINJA_SLUG . '[css_' . md5($c['class'] . $c['zone']) . ']" value="' . get_wpappninja_option('css_'.md5($c['class'] . $c['zone']), $c['color']) . '" class="wpapp-color-picker-primary" required /></div><div style="clear:both"></div></div>';
						}


} } ?>




</div>





				<?php if (get_wpappninja_option('webview') != '0') {	?>
<?php $section = 'css'; ?>
<h2 style="<?php if (isset($_GET['theme'])) {echo ' display:none;';} ?>background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('On the app', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">

<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Custom CSS', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right" style="width:100%">

		<textarea id="wpappcustomcss" name="<?php echo WPAPPNINJA_SLUG;?>[customcss]" style="width: 100%!important;height: 260px;"><?php echo get_wpappninja_option('customcss');?></textarea>

	</div>
	<div class="clear"></div>
</div>



	<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Custom JS [jQuery, framework7.io]', 'wpappninja');?></div><div class="wpappninja-builder-right" style="width:100%">

					
								<textarea id="wpappcustomjs" name="<?php echo WPAPPNINJA_SLUG;?>[customjs]" style="width: 100%!important;height: 260px;"><?php echo get_wpappninja_option('customjs');?></textarea></div><div class="clear"></div></div>


</div>
<?php $section = 'css_website'; ?>

<h2 style="<?php if (isset($_GET['theme'])) {echo ' display:none;';} ?>background: #f5f5f5;padding: 15px;cursor: pointer;" onclick="jQuery('#wpappninja_section_<?php echo $section;?>').toggle()"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('On website', 'wpappninja');?></h2>
<div class="wpappninja_div" id="wpappninja_section_<?php echo $section;?>" style="display:none">



<div class="wpappninja-builder">
	<div class="wpappninja-builder-left">
		<?php _e('Custom CSS', 'wpappninja');?>
	</div>
	<div class="wpappninja-builder-right" style="width:100%">

		<textarea id="wpappcustomcss_website" name="<?php echo WPAPPNINJA_SLUG;?>[customcss_website]" style="width: 100%!important;height: 260px;"><?php echo get_wpappninja_option('customcss_website');?></textarea>

	</div>
	<div class="clear"></div>
</div>

	<div class="wpappninja-builder"><div class="wpappninja-builder-left"><?php _e('Custom JS [jQuery]', 'wpappninja');?></div><div class="wpappninja-builder-right" style="width:100%">

					
								<textarea id="wpappcustomjs" name="<?php echo WPAPPNINJA_SLUG;?>[customjs_website]" style="width: 100%!important;height: 260px;"><?php echo get_wpappninja_option('customjs_website');?></textarea></div><div class="clear"></div></div>

</div>
<?php }?>











					<br/>
					<input type="submit" id="submitme" class="button button-primary button-large" />



				</div>


				<div style="float:left;margin-top: 33px;<?php if (!isset($_GET['theme'])) {echo ' display:none;';} ?>">

											<?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App (light)' && 1<0){ ?>
							<a style="margin: 0 40px 16px;display: inline-block;font-size: 20px;line-height: 45px;text-align: center;width: Calc(100% - 40px);padding: 0 20px;font-weight: 700;background: #f8fdf4;box-sizing: border-box;color: #30343a;text-decoration: none;text-transform: uppercase;" href="https://wpmobile.app/demo-android-ios/" target="_blank"><?php _e('You can test on a real device', 'wpappninja');?><br/><img src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>appstore.png" height="30" /> <img src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>playstore.png" height="30" /></a>

							<?php }  ?>

				<div style="display:none;overflow: hidden;width: 450px;margin-left: 40px;border: 2px solid #333;border-radius: 5px;">

					<?php
					if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') == 'WPMobile.App') { ?>
					<div id="wpapp_color_primary" class="mini_android_toolbar" style="white-space: nowrap;font-size: 15px;padding: 0;width: 100%;height: 10px;color:white;background:<?php echo get_wpappninja_option('css_74537a66b8370a71e9b05c3c4ddbf522', $app_theme_primary);?>"></div>
					<?php } elseif (get_wpappninja_option('speed') == '0') { ?>
					<div id="wpapp_color_primary" class="mini_android_toolbar" style="white-space: nowrap;font-size: 15px;padding: 0;width: 100%;height: 10px;color:white;background:<?php echo wpappninja_adjustBrightness($app_theme_primary, -50);?>"></div>

					<?php } elseif (get_wpappninja_option('speed') == '0' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App (light)' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App (hybride)') { ?>
					<div id="wpapp_color_primary" class="mini_android_toolbar" style="white-space: nowrap;font-size: 15px;padding: 12px 10px 10px;width: 100%;height: 20px;color:white;background:<?php echo $app_theme_primary;?>"><span class="dashicons dashicons-menu"></span> <?php echo $app_name;?></div>



					<?php } ?>


					<div class="wpappninja_noscroll" style="display:none;height:550px;overflow:auto;overflow-x: hidden;">
						

							<?php if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App (light)' && get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != 'WPMobile.App (hybride)'){ ?>
							<!--<iframe style="width: 100%;height: 550px;" id="wpappiframe" src="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=<?php echo get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App');?>"></iframe>-->

							<?php } else { ?>

							<a style="margin: 25px auto;display: inline-block;font-size: 27px;text-align: center;width: 100%;line-height: 47px;" href="https://wpmobile.app/demo-android-ios/" target="_blank"><?php _e('You have to test on a real device', 'wpappninja');?></a>

							<?php } ?>

					</div>
				</div>
	
				</div>
				<div style="clear:both"></div>

			</form>
		</div>
	</div>
	
	<script type="text/javascript">
	jQuery(document).ready(function($){

	var wpapp_color_primary = {
	    change: function(event, ui){
	    	//jQuery(".mini_android_toolbar").css( 'background-color', ui.color.toString());
	    },
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-primary").wpColorPicker(wpapp_color_primary);

	var wpapp_color_accent = {
	    change: function(event, ui){
	    	//jQuery(".wpappicon").css( 'color', ui.color.toString());
	    	//jQuery(".wpappninja_colorme").css( 'color', ui.color.toString());
	    },
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-accent").wpColorPicker(wpapp_color_accent);


	});
	</script>
    <style type="text/css">
    .wpappninja_colorme{color:<?php echo $app_theme_accent;?>}
    </style>
	<?php
	echo wpappninja_talkus();
}
