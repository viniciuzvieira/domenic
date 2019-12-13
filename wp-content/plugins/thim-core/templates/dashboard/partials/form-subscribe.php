<?php
$user  = wp_get_current_user();
$email = '';
if ( $user ) {
	$email = $user->user_email;
}
?>

<form action="<?php printf( '//thimpress.us12.list-manage.com/subscribe/post?u=%s&amp;id=%s', $args['user'], $args['form'] ); ?>"
      method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
      class="validate" target="_blank">
    <div id="mc_embed_signup_scroll">
        <input type="email" value="<?php echo esc_attr( $email ); ?>" name="EMAIL" class="email" id="mce-EMAIL"
               placeholder="Email address" required>
        <div style="position: absolute; left: -5000px;" aria-hidden="true">
            <input type="text"
                   name="<?php echo esc_attr( sprintf( 'b_%s_%s', $args['user'], $args['form'] ) ); ?>"
                   tabindex="-1" value=""></div>
        <button type="submit" name="subscribe"
                class="button button-primary tc-button"><?php esc_html_e( 'Subscribe', 'thim-core' ); ?></button>
    </div>
</form>
