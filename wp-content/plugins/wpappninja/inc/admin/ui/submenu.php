
<?php

$text = "";
$warning = false;

if ($menu_current == 'publish') {
	$url = "https://support.wpmobile.app/article/247-how-to-publish-the-application?lang=".wpmobile_getSupportLang()."";
	$text = __('The logo is an important step for the publication', 'wpappninja');
	 
} else if ($menu_current == 'settings' && isset($_GET['onlymenu'])) {
	$url = "https://support.wpmobile.app/article/246-design-app-menu?lang=".wpmobile_getSupportLang()."";
	$text = __('You can do a lot of things with the menu', 'wpappninja');
	 
} else if ($menu_current == 'theme' && isset($_GET['theme'])) {
	$url = "https://support.wpmobile.app/article/244-design-app-content?lang=".wpmobile_getSupportLang()."";
	$text = __('You can customize your theme for the app', 'wpappninja');

	if (get_wpappninja_option('appify') != '1' && get_wpappninja_option('wpappninja_main_theme') != 'WPMobile.App') {
		$warning = true;

		$url = "https://support.wpmobile.app/article/247-how-to-publish-the-application?lang=".wpmobile_getSupportLang()."";
		$text = __('Your application should not just display your site as in a web browser, <b>It\'s better to use the WPMobile.App theme.</b>', 'wpappninja');
	}
	 
} else if ($menu_current == 'auto' && !isset($_GET['settings'])) {
	$url = "https://support.wpmobile.app/category/144-theme-design?lang=".wpmobile_getSupportLang()."";
	$text = __('Learn how to play with the app elements', 'wpappninja');
	 
} else if ($menu_current == 'theme' && !isset($_GET['theme'])) {
	$url = "https://support.wpmobile.app/category/143-theme-widgets?lang=".wpmobile_getSupportLang()."";
	$text = __('You can modify with CSS each elements of the app', 'wpappninja');
	 
} else if ($menu_current == 'settings' && isset($_GET['onlymenu_trad'])) {
	$url = "https://support.wpmobile.app/article/41-what-languages-are-supported?lang=".wpmobile_getSupportLang()."";
	$text = __('2 ways to translate your app', 'wpappninja');
	 
} else if ($menu_current == 'auto' && isset($_GET['settings'])) {
	//$url = "https://support.wpmobile.app/article/246-design-app-menu?lang=".wpmobile_getSupportLang()."";
	//$text = __('You can do a lot of things with the menu', 'wpappninja');
	
} ?>


<?php if ($text != "" && get_wpappninja_option('nomoreqrcode') != '1') {

	$lightcolor = "#4CAF50";
	$backcolor = "#f8fdf4";

	if ($warning) {
		$lightcolor = "#e7aaaa";
		$backcolor = "#fde8e8";
	}
	?>
	<div class="wpappninja_help" style="box-shadow: 0 0 0;margin-bottom: 26px;border-left: 5px solid <?php echo $lightcolor;?>;background: <?php echo $backcolor;?>">
	<?php echo $text;?> <b><a style="display: inline-block;margin-left: 17px;font-size:17px;" target="_blank" href="<?php echo $url;?>"><?php _e('+ more', 'wpappninja');?></a></b>
</div>
<?php } ?>





<?php
if (get_wpappninja_option('nomoreqrcode') != '1') { ?>
	<div class="wpappninjasubmenu">			
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'publish'){echo 'wpapp_menu_admin_current';}?>"><?php _e('Name and logo', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_SLUG;?>&onlymenu=true';" class="wpapp_menu_admin <?php if ($menu_current == 'settings' && isset($_GET['onlymenu'])){echo 'wpapp_menu_admin_current';}?>"><?php echo $error_message . __('Homepage and menu', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_THEME_SLUG;?>&theme';" class="wpapp_menu_admin <?php if ($menu_current == 'theme' && isset($_GET['theme'])){echo 'wpapp_menu_admin_current';}?>"><?php _e('Theme', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_THEME_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'theme' && !isset($_GET['theme'])){echo 'wpapp_menu_admin_current';}?>"><?php _e('Style and Javascript', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_AUTO_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'auto' && !isset($_GET['settings'])){echo 'wpapp_menu_admin_current';}?>"><?php _e('Design', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_SLUG;?>&onlymenu_trad=true';" class="wpapp_menu_admin <?php if ($menu_current == 'settings' && isset($_GET['onlymenu_trad'])){echo 'wpapp_menu_admin_current';}?>"><?php _e('Translate', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_AUTO_SLUG;?>&settings';" class="wpapp_menu_admin <?php if ($menu_current == 'auto' && isset($_GET['settings'])){echo 'wpapp_menu_admin_current';}?>"><?php _e('Others', 'wpappninja');?></div>


<!--<div style="border-color: #089108;" onclick="wpappninja_magic();return false"  class="wpapp_menu_admin"><?php _e('Automatic configuration', 'wpappninja');?></div>-->



</div>
<?php } else { ?>
	<div class="wpappninjasubmenu">
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_HOME_SLUG;?>';" class="wpapp_menu_admin"><?php _e('< Back', 'wpappninja');?></div>

</div>
<?php } ?>

<script>
function wpappninja_magic() {
    var r = confirm("<?php _e('If you continue, you will lose all previous settings', 'wpappninja');?>");
    if (r == true) {
        document.location = '?page=<?php echo WPAPPNINJA_HOME_SLUG;?>&supermagic=on';
    }
}
</script>