<?php
$theme_data = Thim_Theme_Manager::get_metadata();
$theme      = $theme_data['template'];
$version    = $theme_data['version'];
$envato_id  = $theme_data['envato_item_id'];

$url_auth_callback = Thim_Envato_Hosted::get_url_verify_callback();//Back to this site
$return            = isset( $args['return'] ) ? $args['return'] : '';//Back to url was setup

$user  = wp_get_current_user();
$email = '';
if ( $user ) {
    $email = $user->user_email;
}
$subscription_code = Thim_Envato_Hosted::get_subscription_code();
?>

<form class="tc-form-verify-subscription-code"
      action="<?php echo esc_url( Thim_Envato_Hosted::get_url_verify_subscription_code() ); ?>"
      method="post">
    <input type="hidden" name="theme" value="<?php echo esc_attr( $theme ); ?>">
    <input type="hidden" name="core_version" value="<?php echo esc_attr( THIM_CORE_VERSION ); ?>">
    <input type="hidden" name="user" value="<?php echo esc_attr( $email ); ?>">
    <input type="hidden" name="version" value="<?php echo esc_attr( $version ); ?>">
    <input type="hidden" name="envato_id" value="<?php echo esc_attr( $envato_id ); ?>">
    <input type="hidden" name="site" value="<?php echo esc_url( site_url() ); ?>">
    <input type="hidden" name="callback" value="<?php echo esc_url( $url_auth_callback ); ?>">
    <input type="hidden" name="return" value="<?php echo esc_url( $return ); ?>">
    <input type="hidden" name="subscription_code" value="<?php echo esc_attr( $subscription_code ); ?>">
    <button class="button button-primary tc-button activate-btn tc-run-step"
            type="submit"><?php esc_html_e( 'Verify', 'thim-core' ); ?></button>
</form>
