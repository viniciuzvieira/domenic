<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

$wpappninja_deny_host = array('127.0.0.1', "::1");

/*if(in_array($_SERVER['REMOTE_ADDR'], $wpappninja_deny_host)){
    $class = 'notice notice-error';
    $message = __( "You can't test or publish an app on a local website.", "wpappninja" );
    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}*/

if (isset($_POST)) {
    $_options = get_option( WPAPPNINJA_SLUG );

    if (isset($_options['version_app'])) {
	    $_options['version_app'] = round($_options['version_app'] + 1);
	} else {
	    $_options['version_app'] = 1;
	}


    if (isset($_POST['wpappninja_app_name']) && isset($_POST['wpappninja_app_logo'])) {

        $_options['app']['name'] = sanitize_text_field($_POST['wpappninja_app_name']);
        $_options['app']['logo'] = sanitize_text_field($_POST['wpappninja_app_logo']);

        $_options['app']['splashscreen'] = "https://my.wpmobile.app/_launchscreen.php?c=" . str_replace('#', '', $_POST['wpappninja_main_color']) . "&l=" . sanitize_text_field($_POST['wpappninja_app_logo']);
    }


    update_option( WPAPPNINJA_SLUG, $_options );


    if (isset($_POST['wpappninja_app_name']) && isset($_POST['wpappninja_app_logo'])) {

        wpappninja_magic_import();

    }

}

?>
<script type="text/javascript">
var wpappninja_go_toggle = "1";
</script>
<style type="text/css">
.wpapp_menu_admin {    cursor: pointer;
    background: white;
    display: inline-block;
    border: 1px solid #fd9b02;
    border-radius: 5px 5px 0 0;
    font-size: 16px;
    border-bottom: 0;
    margin-right: 4px;
    padding: 9px 15px;}.wpapp_menu_admin {
    padding-bottom: 10px;
    padding-top: 13px;
}
    .wpapp_menu_admin span {color:#fd9b02;}
.wpapp_menu_admin_current {background:#fd9b02;color:white!important;}
.wpapp_menu_admin_current span{color:white!important;}
.wp-core-ui .wrap .button-primary:hover {
    background: #965C00;
    border: 1px solid #965C00;
    color: #fff;
    text-shadow: 0 0 0;
    box-shadow: 0 0 0;
}
.wp-core-ui .wrap .button-primary {
    background: #fd9b02;
    border: 1px solid #fd9b02;
    color: #fff;
    text-shadow: 0 0 0;
    box-shadow: 0 0 0;
}

.wrap a {color:#333}
#wpappninja_app_store_data {
    max-width:500px;
}
body .wrap h3 {
    border-bottom: 1px solid #eee!important;
    margin-bottom: 20px!important;
    padding: 8px 0!important;
    background: #ffffff!important;
}
#wpappninja_app_store_data input {font-size:18px;width:100%;padding:8px;}
#wpappninja_app_store_data input[type="submit"] {padding: 25px;
    line-height: 3px;
    margin-top: 30px;
    margin-bottom: 40px;}
    
@media screen and (max-width: 782px) {
    
    .wpapp_menu_admin {
        width: 96%;
        padding: 2%;
        border-radius: 0 0 0 0;
        border: 0;
    }
    
    .wpapp_admin_w100 > div {    margin-left: 0!important;float:none!important;width:auto!important;margin:20px 0}
    
    .pushpreview_since {width: 90%;
    margin: 20px 0;
    padding: 5%;}
    
    .pushpreview_block {width: auto;float:none;margin-bottom:0}
    
    #pushpreview_step {
    width: 90%;
    left: 5%;
    padding:0px;
    margin: 0;}
    
    .pushpreview_texte {    float: none!important;
    margin: 0!important;
    overflow: auto;
    width: 100%;
    padding: 10px;
    white-space: initial;}
    
    .wpappninja_stats_box{    width: 100%;
    float: none;}
    
    #wpappninja_stats_box_form {    width: 90%!important;
    left: 5%!important;
    margin: 0!important;
    padding: 20px 0!important;}
    
    h1 {display:none}
    
    .wpappninja_left_panel {width: 100%!important;
    padding: 0!important;}
    
    .wpappninja_item{      text-align: left;  width: 100%!important;
    float: none;
    margin: 0;
    padding: 15px 0;
    border: 0px solid #fff;
    border-radius: 0;height:21px;line-height: 22px;
    min-height: 0;}
    .wpappninja_left_panel br {display:none}
    .wpappninja_item span {    font-size: 20px;}
    .wpappninja_item img {   display:none;}
    #wpappninja_main_tab{    width: 96%;
    float: none;
    border: 0;
    padding: 2%;
    margin: 0;}
}.wpappninjasubmenu .wpapp_menu_admin {
    border: 1px solid #fd9b02;
    border-radius: 5px;
    font-size: 14px;
    padding: 7px 20px;
}/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<?php

$app_data = get_wpappninja_option('app');
$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();
    $app_logo = (isset($app_data['logo']) && $app_data['logo'] != "") ? esc_url($app_data['logo']) : WPAPPNINJA_ASSETS_IMG_URL . 'os/empty.png';


if (!wpappninja_is_store_ready()) { ?>
    <div style="position:fixed;top:0;bottom:0;right:0;left:0;background:#ddd;opacity:0.6;z-index:1000;width:100%;height:100%"></div>
    <form action="" method="post" style="margin-bottom:50px;padding:40px;background:white;position: absolute;width:60%;box-shadow: 0 0 18px #999;z-index:1001;max-width:500px;height: auto;/* min-height:300px; */margin:auto;overflow: auto;top: 50px;/* bottom: 0; */left: 0;right: 0;">

<h2><?php _e('Your application is almost ready!', 'wpappninja');?></h2>

<h4 style="font-size: 22px;margin: 50px 0 0;"><?php _e('Name of the application', 'wpappninja');?></h4>
<h6 style="font-size: 13px;color: gray;margin: 5px 0 20px;"><?php _e('SHORTER IS BETTER', 'wpappninja');?></h6>
<input name="wpappninja_app_name" type="text" id="wpappninja_name_count_" maxlength="30" required />

<h4 style="font-size: 22px;margin: 50px 0 0;"><?php _e('Logo for the app launcher', 'wpappninja');?></h4>
<h6 style="font-size: 13px;color: gray;margin: 5px 0 20px;"><?php _e('BEST SIZE: 1024x1024', 'wpappninja');?></h6>
<div class="uploader">
    <img src="" style="width:200px;height:200px;display:none" class="wpmobilelogo" /><br/>
    <input id="wpmobileupload" style="display:none" name="wpappninja_app_logo" type="text" required />
    <input id="wpmobileupload_button" class="button" name="blog_logo_button" type="text" value="<?php _e('Select a logo', 'wpappninja');?>" />
</div>

<h4 style="font-size: 22px;margin: 50px 0 0;"><?php _e('Main color', 'wpappninja');?></h4>
<br/>
<input type="text" name="wpappninja_main_color" value="#1e73be" class="wpapp-color-picker" />

<input value="<?php _e('Create my app', 'wpappninja');?>" type="submit" id="submitme" class="button button-primary button-large" style="width: 100%!important;margin: 65px 0; height: auto;font-size: 18px!important;padding: 11px 0px!important;" />


    </form>

        <script type="text/javascript">
    jQuery(document).ready(function($){

            var wpapp_color_primary = {
        palettes: true,
        hide: false
    };

    jQuery("input.wpapp-color-picker").wpColorPicker(wpapp_color_primary);


    var _custom_media = true,
    _orig_send_attachment = wp.media.editor.send.attachment;

    $('#wpmobileupload_button').click(function(e) {
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        var id = button.attr('id').replace('_button', '');
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment){
            if ( _custom_media ) {
                $("#"+id).val(attachment.url);
                $(".wpmobilelogo").attr("src", attachment.url);
                $(".wpmobilelogo").css('display', 'block');

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

    });
    
    </script>

<?php }

 
if (current_user_can( wpappninja_get_right() ) ) {

    if ((get_wpappninja_option('package', '') != '' && get_wpappninja_option('apipush', '') == '') || (get_wpappninja_option('appstore_package', '') != '' && get_wpappninja_option('appstore_package', '') != 'xxx' && get_option('wpappninja_pem_file', '') == '')) { ?>
    <div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;margin-bottom: 26px;"><?php _e('Push notifications are not yet fully configured', 'wpappninja'); echo ' <a style="display: inline-block;margin-left: 17px;font-size:17px;" href="?page=' . WPAPPNINJA_PUSH_SLUG . '&settings">';?><?php echo strtolower(__('CONFIGURE', 'wpappninja'));?></a></div>
<?php }
?>





<?php if (wpappninja_alert_old_basic()) { ?>
    <div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;margin-bottom: 26px;"><?php _e('On December 15, 2016 notifications and statistics will no longer be available with the free package', 'wpappninja'); echo ' <a style="display: inline-block;margin-left: 17px;font-size:17px;" target="_blank" href="https://wpmobile.app/'; if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}?>/?source=<?php echo home_url(); ?>/"><?php echo strtolower(__('UPDATE MY PLAN', 'wpappninja'));?></a></div>
<?php }

?>

<?php /*if (!wpappninja_is_paid() && !wpappninja_is_store_ready() && wpappninja_is_ready_to_start()) { ?>
    <div class="wpappninja_help" style="margin-bottom: 26px;border-left: 5px solid #4CAF50;background: #f8fdf4"><?php _e('Your app is not yet ready', 'wpappninja');?> <b><a style="display: inline-block;margin-left: 17px;font-size:17px;" href="?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>"><?php echo _e('configure my app', 'wpappninja');?></a></b> <a style="display: inline-block;margin-left: 20px;color:gray" href="https://wpmobile.app/demo-android-ios/?cache=<?php echo uniqid();?>&url=<?php echo rawurlencode(home_url() . '/');?>&slug=iphone5s&lang=<?php echo substr(get_locale(), 0, 2);?>" target="_blank"><b><?php _e('test my app', 'wpappninja');?></b></a></div>
<?php }*/ ?>

<?php /*if (!wpappninja_is_paid() && wpappninja_is_store_ready()) { ?>
    <div class="wpappninja_help" style="padding-bottom: 8px;margin-bottom: 26px;border-left: 5px solid #4CAF50;background: #f8fdf4"><?php _e('Your app is ready!', 'wpappninja');?> <b><a style="display: inline-block;margin-left: 17px;font-size: 17px;text-decoration: none;background: #007f1b;padding: 12px;margin-top: -8px;text-transform: uppercase;font-weight: 700;border: 1px solid #1ed91e;color: white;box-shadow: 0px 3px 2px 0px #a5a5a5;" href="https://wpmobile.app/<?php if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}?>/?adm=<?php echo str_replace('/', '', str_replace(home_url('/'), '', admin_url()));?>&source=<?php echo home_url(); ?>/"><?php echo _e('BUY MY APP', 'wpappninja');?></a></b> <a style="display: inline-block;margin-left: 20px;color:gray" href="https://wpmobile.app/demo-android-ios/?cache=<?php echo uniqid();?>&url=<?php echo rawurlencode(home_url() . '/');?>&slug=iphone5s&lang=<?php echo substr(get_locale(), 0, 2);?>" target="_blank"><?php _e('test my app', 'wpappninja');?></a></div>
<?php }*/ ?>
                        
<?php if (wpappninja_need_update() && !defined('WPAPPNINJA_WHITE_LABEL') && 1<0) { ?>
    <div class="wpappninja_help" style="margin-bottom: 26px;border-left: 5px solid #4CAF50;background: #f8fdf4"><?php _e('Your app need to be updated on stores', 'wpappninja');?> <b><a style="display: inline-block;margin-left: 17px;font-size:17px;" href="?page=<?php echo WPAPPNINJA_UPDATE_SLUG;?>"><?php echo _e('UPDATE', 'wpappninja');?></a></b></div>
<?php } 


}



$error_message = '';

if (get_wpappninja_option('speed') != '1') {
    $available_lang = get_wpappninja_option('lang_exclude', array());
    if (
                    (count(wpappninja_get_menu_reloaded('fr')) == 0 && in_array('fr', $available_lang)) ||
                    (count(wpappninja_get_menu_reloaded('de')) == 0 && in_array('de', $available_lang)) ||
                    (count(wpappninja_get_menu_reloaded('en')) == 0 && in_array('en', $available_lang)) ||
                    (count(wpappninja_get_menu_reloaded('it')) == 0 && in_array('it', $available_lang)) ||
                    (count(wpappninja_get_menu_reloaded('pt')) == 0 && in_array('pt', $available_lang)) ||
                    (count(wpappninja_get_menu_reloaded('es')) == 0 && in_array('es', $available_lang))
                ) {
        $error_message = '<span style="color:red;display:inline" class="dashicons dashicons-warning"></span> ';
    }
}
?>



<?php if ($menu_current != 'home' && current_user_can( wpappninja_get_right() )){ ?>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_HOME_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'home' || $menu_current == 'newhome'){echo 'wpapp_menu_admin_current';}?>"><span class="dashicons dashicons-dashboard"></span> <?php _e('Dashboard', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PREVIEW_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'preview'){echo 'wpapp_menu_admin_current';}?>"><span class="dashicons dashicons-smartphone"></span> <?php _e('Preview', 'wpappninja');?></div>
<?php if (get_wpappninja_option('nomoreqrcode', '0') == '0') { ?>


<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'publish' || $menu_current == 'theme' || ($menu_current == 'settings' && isset($_GET['onlymenu'])) || $menu_current == 'auto' || ($menu_current == 'settings' && isset($_GET['onlymenu_trad']))){echo 'wpapp_menu_admin_current';}?>"><span class="dashicons dashicons-admin-generic"></span> <?php _e('Configuration', 'wpappninja');?></div>
<?php } ?>

<div onclick="document.location = '?page=<?php echo WPAPPNINJA_STATS_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'stats'){echo 'wpapp_menu_admin_current';}?>"><span class="dashicons dashicons-chart-area"></span> <?php _e('Statistics', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PUSH_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'push'){echo 'wpapp_menu_admin_current';}?>"><span class="dashicons dashicons-format-status"></span> <?php _e('Push notifications', 'wpappninja');?></div>

<?php if (get_wpappninja_option('nomoreqrcode', '0') == '0') { ?>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_QRCODE_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'qrcode'){echo 'wpapp_menu_admin_current';}?>"><span class="dashicons dashicons-camera"></span> <?php _e('QR Code', 'wpappninja');?></div>
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_ADSERVER_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'adserver'){echo 'wpapp_menu_admin_current';}?>"><span class="dashicons dashicons-megaphone"></span> <?php _e('Advertising', 'wpappninja');?></div>
<?php } ?>



<?php } ?>








<?php /*
<!--<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PROMOTE_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'promote'){echo 'wpapp_menu_admin_current';}?>"><?php _e('Promote', 'wpappninja');?></div>-->
<!--<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'publish'){echo 'wpapp_menu_admin_current';}?>"><?php _e('Publication', 'wpappninja');?></div>-->
<div onclick="document.location = '?page=<?php echo WPAPPNINJA_SLUG;?>';" class="wpapp_menu_admin <?php if ($menu_current == 'settings' && !isset($_GET['onlymenu'])){echo 'wpapp_menu_admin_current';}?>"><?php _e('Settings', 'wpappninja');echo $error_message;?></div>

<?php */