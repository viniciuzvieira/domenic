<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Disable some antispam
 *
 * @since 4.0.3
 */
if( !empty($_POST['WPAPPNINJA']) && !empty($_SERVER['HTTP_X_POSTED_WITH']) && 1 == $_POST['WPAPPNINJA'] && 'WPAPPNINJA' == $_SERVER['HTTP_X_POSTED_WITH']) {
	remove_all_filters('preprocess_comment');
}
