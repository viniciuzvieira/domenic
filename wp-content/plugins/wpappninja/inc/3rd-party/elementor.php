<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Fix elementor invisible element
 *
 */
add_action('wp_head', 'wpmobile_fix_elementor', 999);
function wpmobile_fix_elementor() {

    if (!is_wpappninja()) {
        return;
    } ?>

    <style>
html body #root ul.activity-nav .selected a, html body #root .bp-navs .current a {
    background: none!important;
}
    .navbar-inner p {
        margin: 0!important;
    }
    .elementor-animated-content {
        visibility: visible;
    }
    .elementor-invisible {
        visibility: visible!important;
    }
    body {
        overflow: auto;
    }
    </style>

    <?php
}
