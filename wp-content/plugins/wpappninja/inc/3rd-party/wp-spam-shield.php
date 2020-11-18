<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Allow push registration with WPSpam-Shield
 *
 * @since 9.0
 */

if (isset($_POST['regId']) && isset($_POST['u']) && isset($_GET['pagename']) && $_GET['pagename'] == 'wpappninja' && isset($_GET['type']) && $_GET['type'] == 'register') {
	define('DOING_CRON', true);
}