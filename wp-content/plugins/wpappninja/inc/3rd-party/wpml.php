<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * To get all categories -> switch to all languages
 *
 * @since 3.0.8
 */
function wpappninja_wpml_fix() {
	global $sitepress;

	if ( method_exists($sitepress, 'switch_lang') ) {
		$sitepress->switch_lang('all', true);
		remove_filter('get_terms_args', array($sitepress, 'get_terms_args_filter'));
		remove_filter('get_term', array($sitepress,'get_term_adjust_id'));
		remove_filter('terms_clauses', array($sitepress,'terms_clauses'));
		remove_filter('comments_clauses', array( $sitepress, 'comments_clauses' ) );
		remove_filter('locale', array( $sitepress, 'locale' ) );
	}
}

/**
 * Include all languages on the WP Link selector.
 *
 * @since 5.2.3
 */
add_action( 'pre_get_posts', 'wpappninja_wpml_pre_get_posts', 1, 1);
function wpappninja_wpml_pre_get_posts($wpq) {

	if(isset($_POST['action']) && 'wp-link-ajax' == $_POST['action']) {
		if(!empty($_SERVER['HTTP_REFERER'])) {
			$parts = parse_url($_SERVER['HTTP_REFERER']);
			parse_str(strval($parts['query']), $query);
			if (isset($query['page']) && is_admin()) {

				if (in_array($query['page'], array(WPAPPNINJA_SLUG, WPAPPNINJA_SETTINGS_SLUG, WPAPPNINJA_PUSH_SLUG, WPAPPNINJA_QRCODE_SLUG, WPAPPNINJA_CERT_SLUG, WPAPPNINJA_STATS_SLUG, WPAPPNINJA_PUBLISH_SLUG, WPAPPNINJA_PROMOTE_SLUG, WPAPPNINJA_ADSERVER_SLUG, WPAPPNINJA_AUTO_SLUG))) {
						$_SERVER['HTTP_REFERER'] .= (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY) ? '&' : '?') . 'lang=all';
						$wpq->query_vars['suppress_filters'] = false;
				}

			}
		}
	}

	return $wpq;
}
