<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Auto import the package name.
 *
 * @since 3.8.8
 */
function wpappninja_get_package($force = false) {

	if (!get_option('wpappninja_app_published') && !$force) {
		return;
	}

	// anti flood
	if (get_transient( 'wpappninja_package_check' ) && !$force) {
		return;
	}
	set_transient( 'wpappninja_package_check', true, 3600 );

	// récuperation du package
	if (get_wpappninja_option('package', '') == '' || $force) {
		$response = wp_remote_get( 'https://api.wpmobile.app/package.php?url=' . urlencode(home_url()) );
		if( is_array($response) ) {
			if ($response['body'] != '' && $response['body'] != 'app need to be published on the play store') {
				$options = get_option(WPAPPNINJA_SLUG);
				$options['package'] = $response['body'];
				update_option(WPAPPNINJA_SLUG, $options);
			}
		}
	}

	// récuperation du package ios
	if (get_wpappninja_option('appstore_package', '') == '' || get_wpappninja_option('appstore_package', '') == 'xxx' || $force) {
		$response = wp_remote_get( 'https://api.wpmobile.app/package_ios_reload.php?url=' . urlencode(home_url()) );
		if( is_array($response) ) {
			if ($response['body'] != '' && $response['body'] != 'app need to be published on the app store') {
				$options = get_option(WPAPPNINJA_SLUG);
				$options['appstore_package'] = $response['body'];
				update_option(WPAPPNINJA_SLUG, $options);
			}
		}
	}

	// récuperation de l'identifiant global
	if (get_option('wpappninja_packagenameInt', '') == '' || $force) {
        $response = wp_remote_get( 'https://api.wpmobile.app/packagenameInt.php?url=' . urlencode(home_url()) );
        if( is_array($response) ) {
            if ($response['body'] != '') {
                update_option('wpappninja_packagenameInt', $response['body']);
            }
        }
    }
}

/**
 * Create fake package name for Android push api key.
 *
 * @since 5.1.2
 */
function wpappninja_fake_package() {

	$url 	= home_url( '/' );
	$package= str_replace('http://', '', $url);
	$package= str_replace('https://', '', $package);
	$package= str_replace('www.', '', $package);
	$package= str_replace('.', '', $package);
	$package= str_replace('/', '', $package);

	$package= preg_replace('#[^a-zA-Z0-9]+#', '', $package);
	
	return 'app' . $package . '.wpapp';
}
