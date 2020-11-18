<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Fix elementor invisible element
 *
 */
add_action('wp_head', 'wpmobile_fix_adsense', 999);
function wpmobile_fix_adsense() {

    if (!is_wpappninja()) {
        return;
    } ?>

    <script>
    jQuery(function(){
           setInterval(function(){jQuery('.page-current').attr('style', '');}, 500);
           
           jQuery('a[href*="wpapp_shortcode"]').each(function(){
    jQuery(this).attr('href', jQuery(this).attr('href').replace('/?', '/wpmobileapp-shortcode/?'));
});
           });
    </script>

    <?php
}
