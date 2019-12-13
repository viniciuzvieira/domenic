<div class="tc-box-body">
    <div class="text-center">
        <a class="tc-button-box tc-base-color" href="<?php echo wp_customize_url(); ?>">
            <div class="icon">
                <span class="dashicons dashicons-admin-customizer"></span>
            </div>
            <span><?php esc_html_e( 'Customize', 'thim-core' ); ?></span>
        </a>

        <a class="tc-button-box tc-base-color" href="<?php echo admin_url( 'nav-menus.php' ); ?>">
            <div class="icon">
                <span class="dashicons dashicons-menu"></span>
            </div>
            <span><?php esc_html_e( 'Menus', 'thim-core' ); ?></span>
        </a>


        <a class="tc-button-box tc-base-color" href="<?php echo admin_url( 'widgets.php' ); ?>">
            <div class="icon">
                <span class="dashicons dashicons-welcome-widgets-menus"></span>
            </div>
            <span><?php esc_html_e( 'Widgets', 'thim-core' ); ?></span>
        </a>
    </div>
</div>
