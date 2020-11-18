<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add link to the plugin configuration pages
 *
 * @since 1.0
 */
add_filter( 'plugin_action_links_' . plugin_basename( WPAPPNINJA_FILE ), '_wpappninja_plugin_action_links' );
function _wpappninja_plugin_action_links( $actions )
{
	array_unshift( $actions, sprintf( '<a href="%s">%s</a>', admin_url('admin.php?page=' . WPAPPNINJA_PUBLISH_SLUG), __( 'Configuration','wpappninja' ) ) );

    return $actions;
}