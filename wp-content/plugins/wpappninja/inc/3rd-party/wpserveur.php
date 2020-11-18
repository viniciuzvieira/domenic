<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Fix WPServeur cache
 *
 * @since 7.0.12
 */
/*
//add_action( 'wp_head', 'wpappninja_wps_rpfc_public', 1 );
function wpappninja_wps_rpfc_public() {

	if (!isset($_SERVER['HTTP_X_WPAPPNINJA'])) {
		return false;
	}

	global $wp_query;

	?>
	<script type="text/javascript">
    document.cookie = "WPServeur-js=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    </script>

	<?php
	if ( isset( $_COOKIE['WPServeur-php'] ) ) {
		unset( $_COOKIE['WPServeur-php'] );
	}
}

function wpappninja_add_header_nocache() {
	header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
}

//add_action( 'wp_head', 'wpappninja_add_browser_nocache' );
function wpappninja_add_browser_nocache() { ?>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="pragma" content="no-cache"/>
	<?php
}

//add_action( 'wp_head', 'wpappninja_send_cookie', 2 );
function wpappninja_send_cookie() { ?>
    <script type="text/javascript">
        document.cookie = "WPServeur-js=NOCACHE; expires=Thu, 01 Jan 2070 00:00:00 UTC; path=/;"
    </script>
	<?php
	setcookie( 'WPServeur-php', 'NOCACHE' );
} */