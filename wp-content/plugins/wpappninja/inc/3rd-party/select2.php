<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Fix Select2 dropdown
 *
 */
add_action('wp_head', 'wpmobile_fix_select2', 999);
function wpmobile_fix_select2() {

    if (!is_wpappninja()) {
        return;
    } ?>

    <script>
    jQuery(document).ready(function(){
        setTimeout(function() {
                if (jQuery("twitter-widget,.select2-selection,.pac-container")[0]) {
                    app.off('touchstart');
                }
            }, 800);
    });
    </script>

    <style>
    .pac-container {
        z-index: 99999!important;
    }
    </style>

    <?php
}
