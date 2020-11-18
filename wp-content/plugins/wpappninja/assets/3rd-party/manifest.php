<?php
header('Content-Type: application/javascript');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$wp_path = explode('wp-content', dirname(__FILE__));

if (file_exists($wp_path[0].'wp-load.php')) {
	require($wp_path[0].'wp-load.php');

	$pwa_data = get_option('wpappninja_progressive_app');
	if ($pwa_data['color'] == "") {
		$pwa_data['color'] = "#333333";
	}

	$related = "false";
	if (wpappninja_is_ios() && get_wpappninja_option('appstore_package', '') != '' && get_wpappninja_option('appstore_package', '') != 'xxx') {
		$related = "true";
	} else if (!wpappninja_is_ios() && get_wpappninja_option('package', '') != '') {
		$related = "true";
	}

	echo '{
	  "short_name": "' . $pwa_data['name'] . '",
	  "name": "' . $pwa_data['name'] . '",
	  "icons": [
	    {
	      "src":"' . $pwa_data['logo'] . '",
	      "sizes": "512x512",
	      "type": "image/png"
	    },
	    {
	      "src":"' . $pwa_data['logo'] . '",
	      "sizes": "192x192",
	      "type": "image/png"
	    }
	  ],
	  "prefer_related_applications": ' . $related . ',
	  "related_applications": [';
	  if (get_wpappninja_option('package', '') != '') {
  	   echo '{
	    "platform": "play",
    	"id": "' . get_wpappninja_option('package', '') . '"
  	    }';
  	   }
  	   if (get_wpappninja_option('appstore_package', '') != '' && get_wpappninja_option('appstore_package', '') != 'xxx') {

  	   	if (get_wpappninja_option('package', '') != '') {
  	   		echo ',';
  	   	}
  	    echo '{
    	"platform": "itunes",
    	"id": "'.get_wpappninja_option('appstore_package', '').'",
    	"url": "https://itunes.apple.com/app/id' . get_wpappninja_option('appstore_package', '') . '"
	    }';
	   }
	  echo '],
	  "start_url": "' . $pwa_data['homepage'] . '",
	  "background_color": "' . $pwa_data['color'] . '",
	  "theme_color": "' . $pwa_data['color'] . '",
	  "display": "standalone"
	}';
}
