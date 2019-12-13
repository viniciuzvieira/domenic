<?php

if ( Thim_Envato_Hosted::is_envato_hosted_site() ) {
    return Thim_Dashboard::get_template( 'partials/button-verify-envato-site.php' );
}

$theme_data = Thim_Theme_Manager::get_metadata();
$theme      = $theme_data['template'];
$version    = $theme_data['version'];
$envato_id  = $theme_data['envato_item_id'];
$demo_key = get_option( 'thim_importer_demo_installed', '' );
$url_auth_callback = Thim_Product_Registration::get_url_verify_callback();//Back to this site
$return            = isset( $args['return'] ) ? $args['return'] : '';//Back to url was setup

$user  = wp_get_current_user();
$email = '';
if ( $user ) {
    $email = $user->user_email;
}
?>

<form action="<?php echo esc_url( Thim_Product_Registration::get_url_auth() ); ?>" method="post">
    <input type="hidden" name="theme" value="<?php echo esc_attr( $theme ); ?>">
    <input type="hidden" name="core_version" value="<?php echo esc_attr( THIM_CORE_VERSION ); ?>">
    <input type="hidden" name="user" value="<?php echo esc_attr( $email ); ?>">
    <input type="hidden" name="version" value="<?php echo esc_attr( $version ); ?>">
    <input type="hidden" name="envato_id" value="<?php echo esc_attr( $envato_id ); ?>">
    <input type="hidden" name="site" value="<?php echo esc_url( site_url() ); ?>">
    <input type="hidden" name="callback" value="<?php echo esc_url( $url_auth_callback ); ?>">
    <input type="hidden" name="demo_key" value="<?php echo esc_attr( $demo_key ); ?>">
    <input type="hidden" name="return" value="<?php echo esc_url( $return ); ?>">
    <button class="button button-primary tc-button activate-btn tc-run-step"
            type="submit"><?php esc_html_e( 'Login with Envato', 'thim-core' ); ?></button>
</form>
