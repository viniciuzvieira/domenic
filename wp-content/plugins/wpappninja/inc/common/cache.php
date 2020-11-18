<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Purge the cache when a comment is posted.
 *
 * @since 3.8.2
 */
add_action( 'comment_post', 'wpappninja_purge_cache_comment', PHP_INT_MAX, 2 );
function wpappninja_purge_cache_comment( $comment_ID, $comment_approved ) {
	if( 1 === $comment_approved ){
		$comment = get_comment( $comment_ID );
		$lang = wpappninja_available_lang();
		
		foreach ($lang as $name => $slug) {
			$transient = 'wpappninja_comment/' . $comment->comment_post_ID . $slug;
			delete_transient( $transient );
		}
	}
}

/**
 * Purge all cache.
 *
 * @since 3.9.2
 */
function wpappninja_clear_cache() {
	global $wpdb;
	$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}options WHERE `option_name` LIKE %s", '_transient_wpappninja_%'));
}

/**
 * Add cache headers.
 *
 * @since 5.1.7
 */
//add_action('template_redirect', 'wpappninja_headers_cache', PHP_INT_MAX - 1);
function wpappninja_headers_cache() {

	if (isset($_SERVER['HTTP_X_WPAPPNINJA'])) {

		if (1 > 10) {
			$ts = gmdate("D, d M Y H:i:s", current_time( 'timestamp' )) . " GMT";
			header("X-WPAPPNINJA-CACHE: false");
			header("Expires: $ts");
			header("Cache-Control: no-cache");
			return;
		}

		$ts = gmdate("D, d M Y H:i:s", current_time( 'timestamp' ) + HOUR_IN_SECONDS) . " GMT";
		header("X-WPAPPNINJA-CACHE: true");
		header("Expires: $ts");
		header("Pragma: cache");
		header("Cache-Control: max-age=" . HOUR_IN_SECONDS);
	}

}