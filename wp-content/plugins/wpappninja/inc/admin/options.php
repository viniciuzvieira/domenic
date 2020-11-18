<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Register the option name
 *
 * @since 1.0
 */
add_action( 'admin_init', '_wpappninja_register_setting' );
function _wpappninja_register_setting()
{
	register_setting( WPAPPNINJA_SLUG, WPAPPNINJA_SLUG );
}