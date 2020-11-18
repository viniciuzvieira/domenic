<?php
header('Content-Type: text/cache-manifest');
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$wp_path = explode('wp-content', dirname(__FILE__));
//define('WPAPPNINJA_PWA', 'true');

if (file_exists($wp_path[0].'wp-load.php')) {
  require($wp_path[0].'wp-load.php');

  $user_cache = 1;

  $wpapp_cache = get_wpappninja_option('pwa_cache', 1);
  $cache = round($user_cache + $wpapp_cache);


  ?>CACHE MANIFEST

# Cache manifest version <?php echo $cache;?>


FALLBACK:

/ <?php echo wpappninja_cache_friendly(wpmobile_weglot(preg_replace('#/$#', '', home_url( '' )).'/pwa.wpapp.offline/?offlinewpappninja=true&is_wppwa=true'));?>

NETWORK:
*

CACHE:

<?php
$pages = wpappninja_get_pages();
$list = wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home("speed"))). "\r\n";
$list .= wpmobile_weglot(preg_replace('#/$#', '', home_url( '' ))."/pwa.wpapp.offline/?offlinewpappninja=true&is_wppwa=true")."\r\n";

$homeurl = home_url( '' );
foreach($pages as $p) {
  if ($p['link'] != "" && strtolower(parse_url($homeurl, PHP_URL_HOST)) == strtolower(parse_url($p['link'], PHP_URL_HOST))) {

    $list .= wpappninja_cache_friendly(wpmobile_weglot($p['link'])) . "\r\n";
  }
}

$list .= preg_replace('#/$#', '', site_url( '' ))."/?wpmobile_homepage=true"."\r\n";

echo $list;

}