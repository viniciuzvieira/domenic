<?php

/**
 * Class Thim_Market_Service.
 *
 * @since 1.3.0
 */
class Thim_Market_Service {

    /**
     * @param $slug
     * @param string $id
     *
     * @return array|WP_Error
     */
    public static function get_theme_detail( $slug, $id = '' ) {
        $url_api = Thim_Admin_Config::get( 'host_downloads' ) . "/get-my-them-detail/?theme=$slug&id=$id";

        $response = Thim_Remote_Helper::get( $url_api, array(), true );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $success = isset( $response->success ) ? ! ! $response->success : false;
        $data    = isset( $response->data ) ? $response->data : new stdClass();

        if ( ! $success ) {
            $message = isset( $data->message ) ? $data->message : __( 'Something wen wrong.', 'thim-envato-market' );

            return new WP_Error( 'thim_market/theme_detail', $message );
        }

        return (array) $data;
    }
}