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

  $user_cache = 1;
  /*if (isset($_COOKIE['wpappninja_cache'])) {
    $user_cache = $_COOKIE['wpappninja_cache'];
  } else {
    $user_cache = 1;
  }*/

  $wpapp_cache = get_wpappninja_option('pwa_cache', 1);
  $cache = round($user_cache + $wpapp_cache);

  ?>const CACHE_VERSION = <?php echo $cache;?>;
let CURRENT_CACHES = {
  offline: 'wpmobileapp-v' + CACHE_VERSION
};
const OFFLINE_URL = '<?php echo wpappninja_cache_friendly(wpmobile_weglot(preg_replace('#/$#', '', site_url( '' )).'/pwa.wpapp.offline/?offlinewpappninja=true&is_wppwa=true'));?>';
var urlsToCache = [<?php

$pages = wpappninja_get_pages();
$list = "'" . wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home("speed"))) . "',";
$list .= "'" . wpappninja_cache_friendly(wpmobile_weglot(preg_replace('#/$#', '', site_url( '' ))."/pwa.wpapp.offline/?offlinewpappninja=true&is_wppwa=true"))."',";
$list .= "'" . preg_replace('#/$#', '', site_url( '' ))."/?wpmobile_homepage=true',";
$homeurl = site_url( '' );

$deduplicate = array();
foreach($pages as $p) {
  if (!in_array($p['link'], $deduplicate) && $p['link'] != "" && strtolower(parse_url($homeurl, PHP_URL_HOST)) == strtolower(parse_url($p['link'], PHP_URL_HOST))) {

    $deduplicate[] = $p['link'];

    $list .= "'" . wpappninja_cache_friendly(wpmobile_weglot($p['link'])) . "',";
  }
}

echo trim($list, ',');
?>];


<?php
$cacheType = get_wpappninja_option('cache_type', 'networkonly');

if($cacheType == "cacheonly") { ?>
self.addEventListener('fetch', function(event) {
  if (event.request.method === 'GET' &&
        event.request.url.replace('http://','').replace('https://','').split(/[/?#]/)[0] === self.registration.scope.replace('http://','').replace('https://','').split(/[/?#]/)[0]
       ) {
  event.respondWith(
    caches.open(CURRENT_CACHES.offline).then(function(cache) {
      return cache.match(event.request).then(function(response) {

      	if (response) {
      	   return response;
      	}

        var fetchPromise = fetch(event.request).then(function(networkResponse) {
          if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
            cache.put(event.request, networkResponse.clone());
          }
          return networkResponse;
        }).catch(error => {
          return caches.match(OFFLINE_URL);
        });
        return response || fetchPromise;
      });
    })
  );
  }
});
<?php } else { ?>
self.addEventListener('fetch', function(event) {
  if (event.request.method === 'GET' &&
        event.request.url.replace('http://','').replace('https://','').split(/[/?#]/)[0] === self.registration.scope.replace('http://','').replace('https://','').split(/[/?#]/)[0]
       ) {
  event.respondWith(
    caches.open(CURRENT_CACHES.offline).then(function(cache) {
      return cache.match(event.request).then(function(response) {

        if (response && !event.request.headers.get('accept').includes('text/html')) {
           return response;
        }

        return fetch(event.request).then(function(networkResponse) {
          if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
            cache.put(event.request, networkResponse.clone());
          }
          
          return networkResponse;

        }).catch(error => {
          if (response) {
             return response;
          }
          return caches.match(OFFLINE_URL);
        });
      });
    })
  );
  }
});
<?php } ?>

self.addEventListener('install', function(event) {
  self.skipWaiting();
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