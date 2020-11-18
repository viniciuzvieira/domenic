<?php

if (wpappninja_is_pwa() && get_wpappninja_option('cache_type', 'networkonly') == "cacheonly") {
  $seconds_to_cache = 86400;
  $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
  header("Expires: $ts");
  header("Pragma: cache");
  header("Cache-Control: max-age=$seconds_to_cache");
}

$pages = wpappninja_get_pages();
$wpappninja_locale = "speed";

$homepage_wpapp = get_wpappninja_option('pageashome_' . $wpappninja_locale, "");

if (get_wpappninja_option('speed', '1') == '1' && !preg_match('#^http#', $homepage_wpapp)) {

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

if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
  $homepage_wpapp = wpappninja_translate($homepage_wpapp);
}
    
    if (is_user_logged_in()) {
        if (get_wpappninja_option('login_redirect_after') != '') {
            $homepage_wpapp = wpappninja_cache_friendly(wpmobile_weglot(get_wpappninja_option('login_redirect_after')));
            
            if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
                $homepage_wpapp = wpappninja_translate(get_wpappninja_option('login_redirect_after'));
            }
        }
    }

$viewport = "";
if (wpappninja_isIOS()) {
  $viewport = ", viewport-fit=cover";
}
?>

<!DOCTYPE html>
<html class="<?php if (wpappninja_iosstyle()) {echo 'ios with-statusbar';}else{echo 'md';} ?>" <?php echo ' manifest="' . WPAPPNINJA_ASSETS_3RD_URL . 'appmanifest.php" ';?>>
<head>

  <?php if (wpappninja_isIOS()) { ?>
  <script>
  function wpappninja_correct_height() {

    width = screen.width;
    height = screen.height;

    newHeight = (window.orientation === 0 ? Math.max(width, height) : Math.min(width, height));

    document.documentElement.style.height = newHeight + "px";

    /*isPortrait = screen.orientation.type.search('portrait');
    isLandscape = screen.orientation.type.search('landscape');

    width = screen.width;
    height = screen.height;

    documentHeight = Math.max(width, height);

    if (isLandscape >= 0) {
      documentHeight = Math.min(width, height);
    }
    
    document.documentElement.style.height = documentHeight + "px";*/
  }
  wpappninja_correct_height();
  window.addEventListener("orientationchange", function() {
    wpappninja_correct_height();
  });

  </script>
  <?php } ?>


  <title> <?php wp_title('', true,''); ?> </title>
  <meta charset="utf-8">
  <meta name="viewport" content="height=device-height,width=device-width,initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no<?php echo $viewport;?>">
  <meta name="apple-mobile-web-app-capable" content="yes">

  <!--<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">-->

  <?php wp_head(); ?>

  <script>
    <?php if (get_wpappninja_option('effect', '1') == '1') {

    echo "var wpmobileImLoaded = true, wpmobileinterval;
    function wpmobileIsLoaded() {
        if (wpmobileImLoaded) {
          wpmobileappHideSplashscreen();
          app.progressbar.hide();
          jQuery('.wpmobile_preload').css('display','none');
          setTimeout(function(){jQuery('.posts,.title-speed').css('opacity', '1');},100);
        } else {
          //clearInterval(wpmobileinterval)
        }
      }
      jQuery( document ).ready(function() {
        wpmobileIsLoaded();
        //wpmobileinterval = setInterval(wpmobileIsLoaded, 300);
      });
      window.addEventListener('pageshow', function(event) {
        wpmobileIsLoaded();
      });";
    }?>
  </script>
  <style>



  .md .item-input-focused .item-input-wrap:after,
.md .input-focused:after {
  background: <?php echo get_wpappninja_option('css_5786e51e83c834d64469d823887736ff');?>!important
}
  .md .dialog-button, .ios .dialog-button {color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>!important;}
.page-content {background-color:<?php echo get_wpappninja_option('css_102c4591c3ac08bbcdbf73981d5eb725');?>!important}

  body.wpappninja .toolbar {background:<?php echo get_wpappninja_option('css_9be9a1df3d0a60c0bc18ff5c65da2d99');?>!important}


.preloader {
    margin-top: 15px;
}a.title {    margin: 0px!important;
    position: absolute!important;
    transform: translateX(-50%);
    left: 50%;
}span.preloader-inner-half-circle {
    border-color: <?php echo get_wpappninja_option('css_e0c30224e61a0fa53753d0992872782d');?>!important;}a{color:<?php echo get_wpappninja_option('css_d115509b7fa9b63e2e07aed34183fea8');?>}.tabbar-labels a {color:<?php echo get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028');?>!important;}.fab a {
    background: <?php echo get_wpappninja_option('css_06a182f400cbc8002d5b0aa4d0d2082e');?>!important;
}.item-after i.icon.f7-icons {
    color: #b7b7b7;
    font-size: 15px;
}.wpapp_sep i.icon {
    display: none;
}
.ios form.searchbar {background:<?php echo get_wpappninja_option('css_51d39016596e1db1ffd8f5118a11dd3c');?>}
.md .page,.ios .page{background:<?php echo get_wpappninja_option('css_95549900f280b71ea92d360dd94dfbd3');?>;}


.ios .panel-backdrop {background: rgba(139, 139, 139, 0.37);}
body .woocommerce .button.checkout, body .woocommerce .button.alt{border:1px solid;background-color:transparent!important;color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>!important;}
<?php

  if (get_wpappninja_option('effect', '1') == '0') {
    echo '.posts,.title-speed{opacity:1;}.wpmobile_preload{display:none!important}';
  } else {
    echo '.posts,.title-speed{opacity:0}';
  }
?>





.wpmobile_preload {
  background:<?php echo get_wpappninja_option('css_102c4591c3ac08bbcdbf73981d5eb725');?>;
}
.load_post .load_avatar {
    width: 100%;
    height: 250px;
    background-color: #e8e8e8;
    border-radius: 0;
    margin: 0;
    transition: background-color 2s ease;
    animation: wpmobile_heartbeat 2s infinite;
}

.load_post .load_line {
    margin: 15px;
    width: Calc(100% - 30px);
    height: 16px;
    background: #eee;
    background-image: linear-gradient(to right, #e0e0e0 0%,#e0e0e0 100%);
    background-size: 30px;
    background-repeat: no-repeat;
       animation: wpmobile_shine 0.9s infinite cubic-bezier(1, 0.03, 0, 0.98);
}
@keyframes wpmobile_shine {
  0% {
    background-position: -40px;
  }
  100% {
    background-position: 100%;
  }
}

@keyframes wpmobile_heartbeat
{
  0%
  {
    background-color: #e8e8e8;
  }
  50%
  {
    background-color: #f7f7f7;
  }
  100%
  {
    background-color: #e8e8e8;
  }
}


</style>
</head>
<body <?php body_class(); ?> data-no-instant >


  <div id="root" class="wpmobile-<?php the_ID(); ?> wpmobile-<?php echo md5($_SERVER['REQUEST_URI']);?> framework7-root">
  
  <div class="statusbar" style="background:<?php echo get_wpappninja_option('css_74537a66b8370a71e9b05c3c4ddbf522');?>"></div>

  <div class="panel-overlay"></div>
  <div class="panel panel-left panel-cover">

    <div class="menuwidget"><?php echo wpappninja_widget('menu-top'); ?></div>

    <!-- Left menu -->
    <div class="content-block">
      <div class="list">
        <ul>

          <?php
          foreach ($pages as $page) {
            if (isset($page['menu']) && $page['menu'] == "menu") {

            if (preg_match('#separator$#', $page['id'])) {
            $uniqid = uniqid(); ?>

            </ul>
            <div style="border-bottom: 2px solid #eee;" class="item-content list-panel-all wpapp_toggle" onclick="jQuery('#sep_<?php echo $uniqid;?>').slideToggle();">
              <div class="item-media notranslate" translate="no"><i class="icon f7-icons wpapp_icon_fill"><?php echo $page['icon_2'];?></i><i class="icon f7-icons wpapp_icon_nofill"><?php echo $page['icon'];?></i><?php echo wpappninja_woo_icon($page['id']);?></div>
              <div class="item-inner">
                <div class="item-title"><?php echo $page['label'];?></div>
                <div class="item-after notranslate" translate="no"><i class="icon f7-icons">chevron_down</i></div>
              </div>
            </div>
            <ul style="display:none;margin-bottom:1px" class="wpapp_sep" id="sep_<?php echo $uniqid;?>">

            <?php } elseif (preg_match('#separatorend#', $page['link'])) {
            $uniqid = uniqid(); ?>

            </ul><ul>

            <?php } else { 

            ?>

            <a id="wpm_left_<?php echo md5($page['link']);?>" href="<?php echo $page['link'];?>" style="color:initial" class="wpappninja_change_color <?php echo $page['class'];?>">
            <li class="item-content list-panel-all">
              <div class="item-media notranslate" translate="no"><i class="icon f7-icons wpapp_icon_fill"><?php echo $page['icon_2'];?></i><i class="icon f7-icons wpapp_icon_nofill"><?php echo $page['icon'];?></i><?php echo wpappninja_woo_icon($page['id']);?></div>
              <div class="item-inner">
                <div class="item-title"><?php echo apply_filters('wpmobile_custom_label', $page['label']);?></div>
              </div>
            </li>
          </a>

            <?php }
          }
          } ?>

        </ul>
      </div>
    </div>

    <div class="menuwidget"><?php echo wpappninja_widget('menu-bottom'); ?></div>

  </div>

  <div class="view view-main ios-edges">
     <div class="page">

        <div class="navbar">
          <div class="navbar-inner">
            <div class="left">
              <span class="link icon-only panel-open notranslate" translate="no" data-panel="left">
                <i class="icon f7-icons">bars</i>
              </span>
            </div>
            <?php if (!preg_match('#<form#', wpappninja_widget('navbar-middle'))) { ?><a href="<?php echo $homepage_wpapp;?>" style="color:initial" class="title"><div><?php } else { ?><div class="title"><?php } ?>
            

              <?php echo preg_replace('#<img#', '<img data-nolazy', wpappninja_widget('navbar-middle')); ?>

            </div>
            <?php if (!preg_match('#<form#', wpappninja_widget('navbar-middle'))) { ?></a><?php } ?>

              <div class="right">
                <?php echo wpappninja_widget('navbar-right');

                if (!defined("WPAPPNINJA_MAIN_APP") && isset($_SERVER['HTTP_X_WPAPPNINJA_DEMO']) && $_SERVER['HTTP_X_WPAPPNINJA_DEMO'] == "1" && 1<0) {

                  echo '<div class="wpappninja-hide-me" style="width:35px">
                    <a href="/?wpappninjalaunch=">
                      <div class="item-media notranslate" translate="no">
                        <i class="icon f7-icons">forward_fill</i>
                      </div>
                    </a>
                  </div>';

                } ?>
                  
              </div>
          </div>
        </div>

        <?php if (wpappninja_is_toolbar()) { ?>
        <div class="toolbar tabbar-labels toolbar-bottom-md">
          <div class="toolbar-inner">
            <?php
            foreach ($pages as $page) {
              if ($page['menu'] == "tabbar") {
                echo '<a id="wpm_float_'.md5($page['link']).'" href="' . $page['link'] . '" class="tab-link wpappninja_change_color '.$page['class'].'">
                <i class="f7-icons wpapp_tabbar wpapp_icon_fill notranslate" translate="no">' . $page['icon_2'] . '</i><i class="f7-icons wpapp_tabbar wpapp_icon_nofill notranslate" translate="no">' . $page['icon'] . '</i>' . wpappninja_woo_icon($page['id']).'
                <span class="tabbar-label wpapp_tabbar">' . $page['label'] . '</span>
                </a>';
              }
            }
            ?>
          </div>
        </div>
        <?php } ?>

          <?php if (wpappninja_is_fab()) {

          $pages = wpappninja_get_pages();

          $nbfab = 0;
          foreach ($pages as $p) {

            if ($p['menu'] == 'fab') {

              $nbfab++;
            }

          } 

          if ($nbfab == 1) { ?>
        <div class="fab fab-right-bottom notranslate" translate="no">

          <?php foreach ($pages as $p) {

            if ($p['menu'] == 'fab') {

              echo '<a href="'.$p['link'].'">
                <i class="icon f7-icons">' . $p['icon'] . '</i>
              </a>';
            }

          }
          
          ?>
        </div>
      <?php } elseif ($nbfab > 1) { ?>
        <div class="fab fab-right-bottom notranslate" translate="no">
          <a href="#">
          <i class="icon f7-icons">add</i>
          <i class="icon f7-icons">close</i>
          </a>

          <div class="fab-buttons fab-buttons-top">
          <?php foreach ($pages as $p) {

            if ($p['menu'] == 'fab') {

              echo '<a href="'.$p['link'].'">
                <i class="icon f7-icons">' . $p['icon'] . '</i>
              </a>';
            }

          }
          
          ?>
          </div>
        </div>
      <?php } ?>
        <?php } ?>

        <div class="page-content <?php if (get_wpappninja_option('speed_reload', '1') == '1') { ?>ptr-content" data-ptr-distance="55<?php } ?>">

          <div class="wpmobile_preload" style="max-height: 100%;overflow: hidden;position:absolute;top:0;right:0;left:0;bottom:0;margin:auto;display:block;z-index:2147483647;display:none">

      <div class="load_container">
        <div class="load_post">
          <div class="load_avatar"></div>
          <?php
          for ($i = 0; $i<18; $i++) { ?>
          <div class="load_line"></div>
          <?php } ?>
        </div>
      </div>


          </div>

          <?php if (get_wpappninja_option('speed_reload', '1') == '1') { ?>
          <div class="ptr-preloader">
            <div class="preloader"></div>
            <div class="ptr-arrow"></div>
          </div>
          <?php } ?>

          <div class="wpappninja_loadme wpifs-loading"><br><center><span class="preloader"></span></center><br></div>

          <?php echo wpappninja_widget('content-top'); ?>
