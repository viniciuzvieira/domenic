<?php
/**
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !is_user_logged_in() ) {
	return;
}

global $user_identity;
?>

<div class="message message-notice"><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.', 'eduma'), get_edit_user_link(), $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php esc_attr_e('Log out of this account', 'eduma'); ?>"><?php esc_html_e('Log out &raquo;', 'eduma'); ?></a></div>
