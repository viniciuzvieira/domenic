<?php
header('Content-Type: text/cache-manifest');
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$wp_path = explode('wp-content', dirname(__FILE__));

if (file_exists($wp_path[0].'wp-load.php')) {
  require($wp_path[0].'wp-load.php');
    
    $delay = date(get_wpappninja_option('appcachedelay', 'dmYHms'));
    if (get_wpappninja_option('appcachedelay', 'dmYHms') == 'dmYHms') {
        $seconds = time();
        $seconds /= 10;
        $seconds = round($seconds);
        $seconds *= 10;
        $delay = $seconds;
    }
    
    if (get_wpappninja_option('disable_all_cache') == 'on' || isset($_GET['wpappninja_simul4'])) {
echo "CACHE MANIFEST

# VERSION ".time()."
    
CACHE:

NETWORK:
*";
        exit();}

  ?>CACHE MANIFEST

# CACHE v<?php echo get_wpappninja_option('appcachemode', 'prefer-online').$delay."\r\n";?>

FALLBACK:
/ <?php echo wpappninja_cache_friendly(wpmobile_weglot(preg_replace('#/$#', '', home_url( '' )).'/pwa.wpapp.offline/?offlinewpappninja=true&is_wppwa=true'))."\r\n";?>

CACHE:
<?php echo preg_replace('#/$#', '', site_url( '' ));?>/?wpmobile_homepage=true
<?php
$pages = wpappninja_get_pages();
//echo wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home("speed"))) . "\r\n";
$homeurl = site_url( '' );

$deduplicate = array();
foreach($pages as $p) {
  if (!in_array($p['link'], $deduplicate) && $p['link'] != "" && strtolower(parse_url($homeurl, PHP_URL_HOST)) == strtolower(parse_url($p['link'], PHP_URL_HOST))) {

    $deduplicate[] = $p['link'];

      echo wpappninja_cache_friendly(wpmobile_weglot($p['link'])) . "\r\n";
  }
}?>

NETWORK:
*

<?php
}
