<?php
header('Content-Type: text/cache-manifest');
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$wp_path = explode('wp-content', dirname(__FILE__));
define('WPAPPNINJA_PWA', 'true');

if (file_exists($wp_path[0].'wp-load.php')) {
  require($wp_path[0].'wp-load.php');

  ?>CACHE MANIFEST

# CACHE V.<?php echo time();?>


FALLBACK:

/ <?php echo wpappninja_cache_friendly(wpmobile_weglot(preg_replace('#/$#', '', home_url( '' )).'/pwa.wpapp.offline/?offlinewpappninja=true&is_wppwa=true'));?>

CACHE:

<?php echo site_url( '' );?>/?wpmobile_homepage=true

NETWORK:

*

<?php
}