<?php

if ( ! defined( 'THIM_CORE_VERSION' ) ) {
	return;
}

function thim_eduma_can_update_thim_core() {
	if ( ! defined( 'THIM_CORE_VERSION' ) ) {
		return false;
	}

	if ( version_compare( THIM_CORE_VERSION, '1.0.3', '>' ) ) {
		return false;
	}

	return true;
}

add_filter( 'pre_set_site_transient_update_plugins', 'thim_eduma_inject_update_new_thim_core' );
add_filter( 'pre_set_transient_update_plugins', 'thim_eduma_inject_update_new_thim_core' );

function thim_eduma_inject_update_new_thim_core( $value ) {
	if ( ! thim_eduma_can_update_thim_core() ) {
		return $value;
	}

	$value->response['thim-core/thim-core.php'] = (object) array(
		'slug'        => 'thim-core',
		'new_version' => '1.0.6',
		'url'         => 'https://foobla.bitbucket.io/thim-core/',
		'package'     => 'https://foobla.bitbucket.io/thim-core/dist/thim-core.zip',
		'tested'      => '4.7.3',
	);

	return $value;
}

add_filter( 'http_request_args', 'thim_eduma_exclude_check_update_from_wp_org', 5, 2 );

function thim_eduma_exclude_check_update_from_wp_org( $request, $url ) {
	if ( ! thim_eduma_can_update_thim_core() ) {
		return $request;
	}

	if ( false === strpos( $url, '//api.wordpress.org/plugins/update-check' ) ) {
		return $request;
	}

	$data   = json_decode( $request['body']['plugins'] );
	$plugin = 'thim-core/thim-core.php';

	if ( isset( $data->plugins->$plugin ) ) {
		unset( $data->plugins->$plugin );
	}

	$request['body']['plugins'] = wp_json_encode( $data );

	return $request;
}

add_action( 'admin_notices', 'thim_eduma_notice_update_thim_core' );

function thim_eduma_notice_update_thim_core() {
	if ( ! thim_eduma_can_update_thim_core() ) {
		return;
	}

	$detect_updating = isset( $_GET['action'] ) ? $_GET['action'] : false;
	if ( $detect_updating == 'do-plugin-upgrade' ) {
		return;
	}

	?>
    <div class="notice notice-success">
        <h3><?php _e( 'Important Update!', 'eduma' ); ?></h3>
        <p><?php printf( __( 'Thim Core %s is available for your system and is ready to install.', 'eduma' ), '1.0.4' ); ?></p>
        <p><a class="button button-primary" href="<?php echo network_admin_url( 'update-core.php#update-plugins-table' ); ?>"><?php esc_html_e( 'Go to update', 'eduma' ); ?></a></p>
    </div>
	<?php
}