<?php

/**
 * Class Thim_Envato_Service.
 *
 * @since 0.7.0
 */
class Thim_Envato_API {
	/**
	 * Get access token from refresh token.
	 *
	 * @since 0.8.9
	 *
	 * @param $refresh_token
	 *
	 * @return string|WP_Error
	 */
	public static function get_access_token( $refresh_token ) {
		if ( empty( $refresh_token ) ) {
			return new WP_Error( 'tc_invalid_access_token', __( 'Invalid access token', 'thim-core' ) );
		}

		$url      = Thim_Admin_Config::get( 'host_downloads' ) . "/access-token/?refresh_token=$refresh_token";
		$response = wp_remote_get( $url);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body   = wp_remote_retrieve_body( $response );
		$object = json_decode( $body );
		$arr    = (array) $object;

		$arr = wp_parse_args( $arr, array(
			'success' => false,
			'data'    => ''
		) );

		if ( ! $arr['success'] ) {
			return new WP_Error( 'tc_get_access_token_failed', $arr['data'] );
		}

		return $arr['data'];
	}

	/**
	 * Get url file theme (zip file).
	 *
	 * @since 0.7.0
	 *
	 * @param $item_id
	 * @param $refresh_token
	 *
	 * @return WP_Error|string
	 */
	public static function get_url_download_item( $item_id, $refresh_token ) {
		$url = Thim_Admin_Config::get( 'host_downloads' ) . "/download-theme/";

		$site_key          = Thim_Product_Registration::get_site_key();
		$subscription_code = Thim_Envato_Hosted::get_subscription_code();
		$code              = thim_core_generate_code_by_site_key( $site_key );
		$response          = Thim_Remote_Helper::post( $url, array(
			'body' => array(
				'refresh-token'     => $refresh_token,
				'subscription-code' => $subscription_code,
				'envato-id'         => $item_id,
				'debug'             => TP::is_debug() ? 'yes' : 'no',
				'code'              => $code
			),
		), true );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$arr = (array) $response;
		$arr = wp_parse_args( $arr, array(
			'success' => false,
			'data'    => ''
		) );

		if ( ! $arr['success'] ) {
			$data = (array) $arr['data'];

			if ( isset( $data['code'] ) ) {
				return new WP_Error( 'thim_core_key_broken', __( 'Please re-activate the theme.' ) );
			}
		}

		if ( ! $arr['success'] ) {
			return new WP_Error( 'tc_get_link_download_failed', $arr['data'] );
		}

		return $arr['data'];
	}

	/**
	 * Get theme metadata.
	 *
	 * @since 0.7.0
	 *
	 * @param $id
	 * @param $token
	 * @param $refresh_token
	 *
	 * @return array|WP_Error
	 */
	public static function get_theme_metadata( $id, $token, $refresh_token = '' ) {
		$url_api = 'https://api.envato.com/v3/market/catalog/item?id=' . $id;

		$response = self::request( $url_api, $token, $refresh_token );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$detect_error = ! empty( $response['error'] ) ? true : false;
		if ( $detect_error ) {
			return new WP_Error( 404, __( 'Some thing went wrong!', 'thim-core' ) );
		}

		$metadata                 = ! empty( $response['wordpress_theme_metadata'] ) ? $response['wordpress_theme_metadata'] : array();
		$metadata['icon']         = ! empty( $response['previews']['icon_with_landscape_preview']['icon_url'] ) ? $response['previews']['icon_with_landscape_preview']['icon_url'] : false;
		$metadata['author_url']   = ! empty( $response['author_url'] ) ? $response['author_url'] : false;
		$metadata['rating']       = ! empty( $response['rating'] ) ? $response['rating'] : false;
		$metadata['rating_count'] = ! empty( $response['rating_count'] ) ? $response['rating_count'] : false;
		$metadata['url']          = ! empty( $response['url'] ) ? $response['url'] : false;

		$data = wp_parse_args( $metadata, array(
			'theme_name'  => '',
			'author_name' => '',
			'version'     => false,
			'description' => ''
		) );

		return apply_filters( 'thim_core_envato_api_get_theme_metadata', $data, $id );
	}

	/**
	 * Request to Envato API.
	 *
	 * @since 0.7.0
	 *
	 * @param $url
	 * @param $token
	 * @param $refresh_token
	 *
	 * @return array|WP_Error
	 */
	private static function request( $url, $token, $refresh_token = '' ) {
		if ( empty( $token ) ) {
			return new WP_Error( 'api_token_error', __( 'An API token is required.', 'thim-core' ) );
		}

		if ( ! empty( $refresh_token ) ) {
			$token = self::get_access_token( $refresh_token );
		}

		$args = array(
			'headers'   => array(
				'Authorization' => "Bearer $token",
				'User-Agent'    => sprintf( 'WordPress - Thim Core %s', THIM_CORE_VERSION ),
			),
			'timeout'   => 30,
		);

		$response      = wp_remote_get( esc_url_raw( $url ), $args );
		$response_code = wp_remote_retrieve_response_code( $response );
		$return        = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $response_code ) {
			if ( null === $return || empty( $return['error_description'] ) ) {
				return new WP_Error( 'api_error', __( 'An unknown API error occurred.', 'thim-core' ) );
			}

			return new WP_Error( $response_code, $return['error_description'] );
		}

		return $return;
	}
}