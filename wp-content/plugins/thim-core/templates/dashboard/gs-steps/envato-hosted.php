<div class="top">
    <h2>Activate your Envato subscription code</h2>

    <div class="caption no-line">
        <p>
            Hi! Thanks for using our theme with Envato Hosted. </p>

        <p>
            Before installing required plugins, we will need you to verify your site with your Envato Subscription Code.

            Once our system can verify your Subscription code, the theme will automatically start downloading required
            plugins and demo content.

            Just click the verify button below to verify your subscription.
        </p>

        <p>Please <a href="https://thimpress.com/contact-us/" target="_blank">contact us</a> if you have any questions
            or problems.</p>

        <div class="logos">
            <img height="50" width="auto"
                 src="<?php echo esc_url( THIM_CORE_ADMIN_URI . '/assets/images/envato-thimpress.png' ); ?>"
                 alt="Envato and ThimPress">
        </div>
    </div>
</div>

<div class="bottom">
    <?php
    $return = Thim_Getting_Started::get_link_redirect_step( 'install-plugins' );
    Thim_Dashboard::get_template(
        'partials/button-verify-envato-site.php',
        array(
            'return' => $return,
        )
    );
    ?>
</div>
