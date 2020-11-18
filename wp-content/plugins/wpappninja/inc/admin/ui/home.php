<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Cnfigurator.
 *
 * @since 5.2
 */
function _wpappninja_display_newhome_page() {

    if (isset($_GET['supermagic'])) {
        wpappninja_magic_import();

        echo '<div class="wpappninja_help" style="margin-bottom: 26px;border-left: 5px solid #4CAF50;background: #f8fdf4">'.__('Done! App auto configured', 'wpappninja').'</div>';
    }

    ?>
    <div class="wrap">
        <h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
        <h2></h2>

        <style type="text/css">
#wpappiframe{
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
}.button span {
    vertical-align: text-top;
}

.preview_icon {
    display: block;
    width: 250px;
    background: #f5f5f5;
    float: left;
    border-right: 1px solid #fd9b02;
}.preview_icon a.button {
    width: 100%;
    border-radius: 0;
    box-shadow: 0 0 0 #fff;
    height: auto;
    padding: 10px 10px;
    border: 0;
    border-bottom: 1px solid #eee;
}iframe#wpappiframe {
    display: inline-block;
    float: left;
    margin-left: 0px;
    max-width: 2000px;
}.preview_icon a {display:block;}
.newpanelitem{cursor:pointer;padding:4px;padding: 20px;font-size: 18px;border: 1px solid #e5e5e5;background:white;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04);}
.newpanelitem:hover{background:#f5f5f5;}
        </style>

            
        <?php $menu_current = 'newhome';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' );
    $app = get_wpappninja_option('app');
    $app_ios_background = isset($app['ios_background']) ? $app['ios_background'] : "#000000";
    $app_logo = (isset($app['logo']) && $app['logo'] != "") ? esc_url($app['logo']) : WPAPPNINJA_ASSETS_IMG_URL . 'os/empty.png';
    ?>
     <div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;">




        <div>
        <?php
        

            



        echo '<div class="wpappninja_stats_box_inner" style="margin-top:0px;float:left;width: 285px;">
        <div style="padding:4px;border: 1px solid #e5e5e5;background:white;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04);">';

echo '<div style="width: 100%;margin-top: 15px;text-align:center;margin-bottom: 0px;border-bottom: 1px solid #eee;padding-bottom: 15px;"><img src="'.$app_logo.'" style="width:90px;height:90px;border-radius:35px;background:'.$app_ios_background.'" /><br/><span style="font-size:13px">' . $app['name'] . '</span></div>';

        echo '<div style="font-size: 20px;display:inline-block;width:50%;border-right:1px solid #eee;padding: 28px 0 20px;text-align: center;">';
        echo '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/ios.png" > ' . (wpappninja_get_install(true));


        echo '</div><div style="font-size: 20px;display:inline-block;width:49%;padding: 28px 0 20px;text-align: center;">';
        echo '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/android.png" > ' . (wpappninja_get_install() - wpappninja_get_install(true));

        echo '</div>';
        echo '</div>';?>
                
        <br/>

        <div onclick="document.location = '?page=wpappninja_publish'" class="newpanelitem"><span class="dashicons dashicons-admin-users"></span> <?php _e('Name and logo', 'wpappninja');?></div>
        <div onclick="document.location = '?page=wpappninja&onlymenu=true'" class="newpanelitem"><span class="dashicons dashicons-menu"></span> <?php _e('Homepage and menu', 'wpappninja');?></div>
        <div onclick="document.location = '?page=wpappninja_theme&theme'" class="newpanelitem"><span class="dashicons dashicons-art"></span> <?php _e('Theme and colors', 'wpappninja');?></div>
        <div onclick="document.location = '?page=wpappninja_auto'" class="newpanelitem"><span class="dashicons dashicons-welcome-widgets-menus"></span> <?php _e('Block design', 'wpappninja');?></div>
        <div onclick="document.location = '?page=wpappninja_theme'" class="newpanelitem"><span class="dashicons dashicons-layout"></span> <?php _e('CSS and JavaScript', 'wpappninja');?></div>
        <div onclick="document.location = '?page=wpappninja&onlymenu_trad=true'" class="newpanelitem"><span class="dashicons dashicons-translation"></span> <?php _e('Translation', 'wpappninja');?></div>
        <div onclick="document.location = '?page=wpappninja_auto&settings'" class="newpanelitem"><span class="dashicons dashicons-admin-generic"></span> <?php _e('Options', 'wpappninja');?></div>
        <?php echo '</div>';

            ?>
        </div>

        <div style="float:left;margin:0px 0 0 40px">
<?php


if (!isset($app['splashscreen']) OR $app['splashscreen'] == "" OR preg_match('#/wpappninja/assets/images/os/empty\.png$#', $app['splashscreen'])) {$app['splashscreen'] = "https://my.wpmobile.app/_launchscreen.php?c=" . str_replace('#', '', $app['theme']['primary']) . "&l=" . $app['logo'];}
$hash = sha1($app['name'].$app['logo'].$app['splashscreen']);

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	$langiframe = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}

if ($langiframe != 'fr' && $langiframe != 'en') {
	$langiframe = 'en';
}

?>

<iframe style="width: 600px;
    height: 670px;
    border: 1px solid #eee;
    box-shadow: 0 0 2px #eee;" src="https://wpmobile.app/?hash=<?php echo $hash;?>&v=<?php echo WPAPPNINJA_VERSION;?>&lang=<?php echo $langiframe; ?>&from_wp_website=<?php echo home_url( '' );?>"></iframe>
        </div>

        <div style="clear:both"></div>
 



    <style type="text/css">
    .wpappninja_item {
    border-bottom: 1px solid #fd9b02!important;
    border-radius: 0;
    text-align: center;
    font-size: 14px;
    color: #353535;
    padding: 20px;
        cursor: pointer;
    padding-bottom: 15px;
    padding-top: 37px;
    }
.wpappninja_item span {
    font-size: 55px;
    width: 55px;
    height: 55px;
}
    </style>
    <?php
    echo wpappninja_talkus();
}



function _wpappninja_display_home_page() {

    if (isset($_GET['supermagic'])) {
        wpappninja_magic_import();

        echo '<div class="wpappninja_help" style="margin-bottom: 26px;border-left: 5px solid #4CAF50;background: #f8fdf4">'.__('Done! App auto configured', 'wpappninja').'</div>';
    }

    ?>
    <div class="wrap">
        <h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
        <h2></h2>

        <style type="text/css">
        .wpappninja_item:hover{box-shadow: 0 0 15px #bcbcbc!important;background: #fd9b02;color: white;}
        .wpappninja_item:hover span {color:white;}
        .wpappninja_item.last:hover {box-shadow:0 0 0!important;background:transparent!important;color:#353535!important;}
        .wpappninja_item.last:hover span {color:#fd9b02!important;}
        .wpappninja_item{transition:0.2s;font-size:18px!important;padding-bottom: 30px!important;padding-top: 27px!important;}
        .wpappninja_item span {margin-bottom: 8px!important;}
        h2, h3 {
    font-size: 1.3em;
    margin: 5px 0 25px;
    text-transform: uppercase;
    color: #333;
}.wpapp_menu_admin{border: 1px solid #fd9b02!important;
    border-radius: 5px!important;
    font-size: 15px!important;}
        </style>

            
        <?php $menu_current = 'home';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>

<?php if (1>0 || wpappninja_is_paid()) {
    $color_buy_link = "";
    if (wpappninja_get_install() > wpappninja_get_allowed_install()) {
        $color_buy_link = "border-left: 5px solid #c10033;background: #ffffd8;";     
    }

    $wpappninja_get_allowed_install = (wpappninja_get_allowed_install() == "2147483647") ? __('unlimited', 'wpappninja') : wpappninja_get_allowed_install();
    ?>
    <div class="wpappninja_help" style="    padding:0px;
    border: 0;border: 0px solid #ddd;    width: 854px;
    box-shadow: 0 0 15px #dfdfdf;background:#fdfdfd;overflow:hidden;border: 1px solid #fd9b02;color:#333;">



        <div style="padding:25px;<?php $app = get_wpappninja_option('app');if (isset($app['logo']) && isset($app['name'])) { ?>width:300px;float:left;<?php } ?>">
        <?php
        

        if (1>10 || (isset($app['logo']) && isset($app['name']))) {
            echo '<img src="' . $app['logo'] . '" width="50" height="50" /><br/><b style="font-size:30px">' . $app['name'] . '</b><br/>'; ?>

<div style="width: auto;min-height: 0;height: 24px;margin: 25px 0 0;padding: 26px 20px 26px 5px!important;line-height: normal;" onclick="document.location = '?page=<?php echo WPAPPNINJA_PREVIEW_SLUG;?>';" class="wpappninja_item wpappninja_item_current"><span class="dashicons dashicons-smartphone"></span> <?php _e('Preview', 'wpappninja');?></div>


        <?php } else { ?>
<div style="width: auto;min-height: 0;display: block;height: 24px;margin: 18px auto;padding: 20px 0px;line-height: normal;" onclick="document.location = '?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>';" class="wpappninja_item wpappninja_item_current"><span class="dashicons dashicons-admin-settings"></span> <?php _e('Create my app', 'wpappninja');?></div>

        <?php } ?>
        </div>

        <?php if (isset($app['logo']) && isset($app['name'])) { ?>
        <div style="float:right;height:240px;box-shadow:0 0 20px #ddd;border-left: 1px solid #ddd;width: Calc(100% - 350px);background:#fff;padding: 25px;box-sizing: border-box;margin: 1px 0 0 0;">

        <?php if (wpappninja_get_allowed_install() != "2147483647") {echo __('Downloads:', 'wpappninja') . ' <b>' . wpappninja_get_install() . '</b> / <b>' . $wpappninja_get_allowed_install . '</b> '; echo '<a style="display: inline-block;margin-left: 17px;font-size:17px;" target="_blank" href="https://wpmobile.app/'; if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}?>/?source=<?php echo home_url(); ?>/"><?php echo strtolower(__('UPDATE MY PLAN', 'wpappninja'));?></a><?php }
            
            $paxk = get_option('wpappninja_packagenameInt', '');

            if ($paxk == "") {
                $response = wp_remote_get( 'https://api.wpmobile.app/packagenameInt.php?url=' . urlencode(home_url()) );
                if( is_array($response) ) {
                    if ($response['body'] != '' && $response['body'] != '') {
                        update_option('wpappninja_packagenameInt', $response['body']);
                        $paxk = $response['body'];
                    }
                }
            }
?>

            <div style="float:left;width:50%;">
        <?php echo '<h2 style="font-size:15px"><img style="vertical-align:-2px;" src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/ios.png" > iOS</h2>';?>
            <?php if (get_wpappninja_option('appstore_package', '') != '' && get_wpappninja_option('appstore_package', '') != 'xxx') {

            $update_link = true; ?>
                <div style="display:block;font-size: 12px;">
                    

                    <b style="font-size: 30px;vertical-align: text-top;"><?php echo wpappninja_get_install(true); ?></b><br/><?php echo __('downloads', 'wpappninja');?>
                                    </div>
<br/>


<a target="_blank" href="https://itunes.apple.com/app/id<?php echo get_wpappninja_option('appstore_package', '');?>"><img width="139" border="0" src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>appstore.png" /></a>



                <?php if(!defined('WPAPPNINJA_WHITE_LABEL')) { ?>
                <br/><br/>
<a style="font-size: 14px;border: 1px solid #f49c00;padding: 8px;color: #000000;text-decoration: none;border-radius: 4px;" href="https://wpmobile.app/data/ios_files/<?php echo $paxk;?>" target="_blank"><?php _e('Update and files', 'wpappninja');?></a>

                <?php } ?>

            <?php } elseif(!defined('WPAPPNINJA_WHITE_LABEL') && get_wpappninja_option('appstore_package', '') != 'xxx') {
                echo '<a style="margin: 35px 0 0;display: inline-block;font-size: 17px;text-decoration: none;background: #fbfbfb;padding: 18px 25px;text-align:center;width:90px;/* text-transform: uppercase; */font-weight: 500;text-decoration: underline;/* border: 1px solid #1ed91e; */color:#fff;background: #fd9b02;/* box-shadow: 0px 3px 2px 0px #a5a5a5; */" target="_blank" href="https://wpmobile.app/'; if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}?>/?source=<?php echo home_url(); ?>/"><?php echo strtolower(__('BUY', 'wpappninja'));?></a>
               

            <?php } elseif(get_wpappninja_option('appstore_package', '') == 'xxx') {
                ?>

                <?php if(!defined('WPAPPNINJA_WHITE_LABEL')) { ?>
                <br/>
<a style="font-size: 14px;border: 1px solid #f49c00;padding: 8px;color: #000000;text-decoration: none;border-radius: 4px;" href="https://wpmobile.app/data/ios_files/<?php echo $paxk;?>" target="_blank"><?php _e('Build the app', 'wpappninja');?></a>

                <?php } ?>
            <?php } ?>
<div class="clear"></div>
</div>
        <?php echo '<div style="float:left;width:50%">';


        echo '<h2 style="font-size:15px"><img style="vertical-align:-2px;" src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/android.png" > Android</h2>';




                $update_link = false;if (get_wpappninja_option('package', '') != '') { 

                $update_link = true; ?>

                
                <div style="display:block;font-size: 12px;">
                    <b style="font-size: 30px;vertical-align: text-top;"><?php echo (wpappninja_get_install() - wpappninja_get_install(true)); ?></b><br/><?php echo __('downloads', 'wpappninja');?>

                </div>

                <br/>

                                <a target="_blank" href="https://play.google.com/store/apps/details?id=<?php echo get_wpappninja_option('package', '');?>"><img width="139" border="0" src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>playstore.png" /></a>



                <?php if(!defined('WPAPPNINJA_WHITE_LABEL')) { ?>
                <br/><br/>

                <a style="font-size: 14px;border: 1px solid #f49c00;padding: 8px;color: #000000;text-decoration: none;border-radius: 4px;" href="https://wpmobile.app/data/android/<?php echo $paxk;?>" target="_blank"><?php _e('Update and files', 'wpappninja');?></a>


                <?php } ?>



            <?php } elseif(!defined('WPAPPNINJA_WHITE_LABEL')) {
                echo '<a style="margin: 35px 0 0;display: inline-block;font-size: 17px;text-decoration: none;background: #fbfbfb;padding: 18px 25px;text-align:center;width:90px;/* text-transform: uppercase; */font-weight: 500;text-decoration: underline;/* border: 1px solid #1ed91e; */color:#fff;background: #fd9b02;/* box-shadow: 0px 3px 2px 0px #a5a5a5; */" href="https://wpmobile.app/'; if (preg_match('#fr#', get_locale())) {echo 'prix';}else{echo 'en/price';}?>/?source=<?php echo home_url(); ?>/"><?php echo strtolower(__('BUY', 'wpappninja'));?></a>

            <?php } ?>
            </div>
            <?php if ($update_link && 1>2) {?>
            <a style="display: inline-block;margin-left: 17px;font-size:17px;" href="?page=<?php echo WPAPPNINJA_UPDATE_SLUG;?>"><?php echo _e('UPDATE', 'wpappninja');?></a>
            <?php } ?>

</div><?php } ?><div style="clear:both"></div>
    </div>
<?php } ?>

<br/>


<div style="width: 119px;height: 79px;" onclick="document.location = '?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'publish'){echo 'wpappninja_item_current';}?>"><span class="dashicons dashicons-admin-generic"></span><br/><?php _e('Configuration', 'wpappninja');?></div>
<div style="width: 119px;height: 79px;" onclick="document.location = '?page=<?php echo WPAPPNINJA_STATS_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'stats'){echo 'wpappninja_item_current';}?>"><span class="dashicons dashicons-chart-area"></span><br/><?php _e('Statistics', 'wpappninja');?></div>

<div style="width: 119px;height: 79px;" onclick="document.location = '?page=<?php echo WPAPPNINJA_PUSH_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'push'){echo 'wpappninja_item_current';}?>"><span class="dashicons dashicons-format-status"></span><br><?php _e('Push notifications', 'wpappninja');?></div>

<div style="width: 119px;height: 79px;" onclick="document.location = '?page=<?php echo WPAPPNINJA_QRCODE_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'qrcode'){echo 'wpappninja_item_current';}?>"><span class="dashicons dashicons-camera"></span><br/><?php _e('QR Code', 'wpappninja');?></div>
<div style="width: 119px;height: 79px;" onclick="document.location = '?page=<?php echo WPAPPNINJA_ADSERVER_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'adserver'){echo 'wpappninja_item_current';}?>"><span class="dashicons dashicons-megaphone"></span><br/><?php _e('Advertising', 'wpappninja');?></div>


<!--<div style="width: 120px;height: 79px;position:relative" onclick="document.location = '?page=<?php echo WPAPPNINJA_PWA_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'pwa'){echo 'wpappninja_item_current';}?>"><span class="dashicons dashicons-dashboard"></span><br/><?php _e('Progressive Web App', 'wpappninja');?></div>-->


<!--<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PROMOTE_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'promote'){echo 'wpappninja_item_current';}?>"><?php _e('Promote', 'wpappninja');?></div>-->
<!--<div onclick="document.location = '?page=<?php echo WPAPPNINJA_PUBLISH_SLUG;?>';" class="wpappninja_item <?php if ($menu_current == 'publish'){echo 'wpappninja_item_current';}?>"><?php _e('Publication', 'wpappninja');?></div>-->

<!--<br/><br/>
<div style="    width: 250px;
    border: 0;
    border-bottom: 0!important;
    background: transparent;
    text-align: left;" onclick="document.location = '?page=<?php echo WPAPPNINJA_SLUG;?>';" class="wpappninja_item last <?php if ($menu_current == 'settings' && !isset($_GET['onlymenu'])){echo 'wpappninja_item_current';}?>"><span style="font-size:15px" class="dashicons dashicons-admin-settings"></span> <?php _e('Settings', 'wpappninja');echo $error_message;?></div>

    </div>-->

    <style type="text/css">
    .wpappninja_item {
    border-bottom: 1px solid #fd9b02!important;
    border-radius: 0;
    text-align: center;
    font-size: 14px;
    color: #353535;
    padding: 20px;
        cursor: pointer;
    padding-bottom: 15px;
    padding-top: 37px;
    }
.wpappninja_item span {
    font-size: 55px;
    width: 55px;
    height: 55px;
}
    </style>
    <?php
    echo wpappninja_talkus();
}
