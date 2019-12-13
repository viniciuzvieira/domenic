<?php
/**
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
	return;
}
?>

<div id="learn-press-checkout-user-form">

	<h3 class="title"><?php esc_html_e( 'Login or Register', 'eduma' ); ?></h3>
	<p class="sub-heading"><?php esc_html_e( 'You must login to checkout courses. Let\'s click the button bellow', 'eduma' ); ?></p>
	<button class="thim-button-checkout" data-redirect="<?php echo thim_get_login_page_url() . '?redirect_to=http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>"><?php esc_html_e( 'Start Now', 'eduma' ); ?></button>

	<?php //do_action( 'learn_press_checkout_user_form' ); ?>

	<div class="clearfix"></div>
</div>
