<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Fix elementor invisible element
 *
 */
add_action('wp_head', 'wpmobile_fix_contact7', 999);
function wpmobile_fix_contact7() {

    if (!is_wpappninja()) {
        return;
    } ?>

    <style>
    form.wpcf7-form input, form.wpcf7-form textarea {
        padding: 10px!important;
        border: 1px solid!important;
        width: 100%!important;
    }
    </style>

    <?php
}
