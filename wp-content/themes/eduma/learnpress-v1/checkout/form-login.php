<?php
/**
 * Output login form
 *
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

$heading    = apply_filters( 'learn_press_checkout_login_heading', __( 'RETURNING CUSTOMER', 'eduma' ) );
$subheading = apply_filters( 'learn_press_checkout_login_subheading', __( 'I am a returning customer', 'eduma' ) );

?>

<div id="learn-press-checkout-user-login" class="learn-press-user-form">

	<?php do_action( 'learn_press_checkout_before_user_login_form' ); ?>

	<?php if ( $heading ) { ?>
		<h3 class="form-heading"><?php echo $heading; ?></h3>
	<?php } ?>

	<?php if ( $subheading ) { ?>
		<p class="form-subheading"><?php echo $subheading; ?></p>
	<?php } ?>

	<ul class="form-fields">

		<?php do_action( 'learn_press_checkout_user_login_before_form_fields' ); ?>

		<li>
			<label><?php esc_html_e( 'Username', 'eduma' ); ?></label>
			<input type="text" name="user_login" placeholder="Username" />
		</li>
		<li>
			<label><?php esc_html_e( 'Password', 'eduma' ); ?></label>
			<input type="password" name="user_password" placeholder="Password" />
		</li>
		<li>
			<button type="submit" id="learn-press-checkout-login-button"><?php esc_html_e( 'Login', 'eduma' ); ?></button>
		</li>

		<?php do_action( 'learn_press_checkout_user_login_after_form_fields' ); ?>

	</ul>

	<?php do_action( 'learn_press_checkout_after_user_login_form' ); ?>

</div>