<?php
header('Content-Type: application/javascript');
header('Service-Worker-Allowed: / ');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$wp_path = explode('wp-content', dirname(__FILE__));

define('WPAPPNINJA_PWA', 'true');

if (file_exists($wp_path[0].'wp-load.php')) {
  require($wp_path[0].'wp-load.php');

  $pwa_data = get_option('wpappninja_progressive_app');
  ?>const CACHE_VERSION = <?php echo round($pwa_data['version']);?>;
let CURRENT_CACHES = {
  offline: 'offline-web-v' + CACHE_VERSION
};
var urlsToCache = [<?php

$list = "'" . $pwa_data['homepage'] ."',";

$pages = preg_split('/\r\n|[\r\n]/', $pwa_data['pages']);
foreach ($pages as $p) {
  if ($p != "") {
    $list .= "'" . $p . "',";
  }
}

echo trim($list, ',');
?>];

self.addEventListener('fetch', function(event) {
  if (event.request.method === 'GET' &&
       event.request.headers.get('accept').includes('text/html')) {
  event.respondWith(
    caches.match(event.request).then(function(response) {
      return response || fetch(event.request);
    })
  );
  }
});

self.addEventListener('install', function(event) {
  self.skipWaiting();self.skipWaiting();
  event.waitUntil(
    caches.open(CURRENT_CACHES.offline).then(function(cache) {
        return cache.addAll(urlsToCache).catch(function() {});
    })
  );
});

self.addEventListener('activate', function(event) {
  self.skipWaiting();
  let expectedCacheNames = Object.keys(CURRENT_CACHES).map(function(key) {
    return CURRENT_CACHES[key];
  });
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (expectedCacheNames.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
<?php } ?>