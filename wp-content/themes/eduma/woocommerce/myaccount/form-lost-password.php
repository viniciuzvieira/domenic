<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'woocommerce_before_lost_password_form' );
?>
<div class="reset_password">
    <h2><?php esc_attr_e( 'Get Your Password', 'eduma' ); ?></h2>
    <form method="post" class="lost_reset_password">

		<?php if ( 'lost_password' == $args['form'] ) : ?>

            <p class="description"><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'eduma' ) ); ?></p>

            <p class="form-row">
                <input class="input-text" type="text" name="user_login" id="user_login" placeholder="<?php esc_html_e( 'Username or email', 'eduma' ); ?>"/>
                <input type="hidden" name="wc_reset_password" value="true"/>
                <input type="submit" class="button" value="<?php echo 'lost_password' == $args['form'] ? esc_html__( 'Reset Password', 'eduma' ) : esc_html__( 'Save', 'eduma' ); ?>"/>
            </p>

		<?php else : ?>

            <p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'eduma' ) ); ?></p>

            <p class="form-row">
                <input type="password" class="input-text" name="password_1" id="password_1" placeholder="<?php esc_html_e( 'New password', 'eduma' ); ?>"/>
                <input type="password" class="input-text" name="password_2" id="password_2" placeholder="<?php esc_html_e( 'Re-enter new password', 'eduma' ); ?>"/>
                <input type="hidden" name="wc_reset_password" value="true"/>
                <input type="submit" class="button" value="<?php echo 'lost_password' == $args['form'] ? esc_html__( 'Reset Password', 'eduma' ) : esc_html__( 'Save', 'eduma' ); ?>"/>
            </p>

            <input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>"/>
            <input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>"/>

		<?php endif; ?>


        <div class="clear"></div>

		<?php do_action( 'woocommerce_lostpassword_form' ); ?>

		<?php wp_nonce_field( $args['form'] ); ?>

    </form>
</div>
<?php
do_action( 'woocommerce_after_lost_password_form' );
