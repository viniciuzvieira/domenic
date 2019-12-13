<?php

do_action( 'thim_core_background_check_update_theme_lite' );

$theme_data      = Thim_Theme_Manager::get_metadata();
$template        = $theme_data['template'];
$current_version = $theme_data['version'];

$update_themes = Thim_Free_Theme::get_update_themes();
$themes        = $update_themes['themes'];
$last_checked  = $update_themes['last_checked'];

$data = isset( $themes[ $template ] ) ? $themes[ $template ] : false;

$link_check = Thim_Dashboard::get_link_main_dashboard(
    array(
        'force-check' => 1,
    )
);

$is_active = true;

?>
<div class="tc-box-body">
    <div class="tc-box-update-wrapper active-registration <?php echo empty( $data['icon'] ) ? 'no-icon' : 'has-icon' ?>">
        <?php if ( ! $data ) : ?>
            <div class="note">
                <?php esc_html_e( 'Something went wrong! Please try again later.', 'thim-core' ); ?>
            </div>
        <?php else : ?>
            <?php
            $can_update = version_compare( $data['version'], $current_version, '>' );
            ?>

            <?php if ( ! empty( $data['icon'] ) ): ?>
                <a class="item-icon" href="<?php echo esc_url( $data['url'] ); ?>" target="_blank">
                    <img src="<?php echo esc_url( $data['icon'] ); ?>" alt="<?php echo esc_attr( $data['name'] ); ?>">
                </a>
            <?php endif; ?>

            <div class="item-detail">
                <h4>
                    <span class="name"><?php echo esc_html( $data['name'] ); ?></span>
                    <span class="version"><?php printf( __( 'Version: <span class="version-number">%s</span>', 'thim-core' ), $current_version ); ?></span>
                </h4>

                <?php
                if ( ! empty( $data['rating_count'] ) ) {
                    wp_star_rating( array(
                        'rating' => $data['rating'] * 100 / 5,
                        'type'   => 'percent',
                        'number' => $data['rating_count'],
                    ) );
                }
                ?>

                <p class="description"><?php echo esc_html( $data['description'] ); ?></p>
                <p class="author">
                    <cite><?php printf( __( 'By <a href="%1$s" target="_blank">%2$s</a>', 'thim-core' ), $data['author_url'], $data['author'] ); ?></cite>
                </p>
            </div>

            <?php if ( $can_update ) : ?>
                <div class="update-message notice inline notice-warning notice-alt">
                    <p><?php if ( $is_active ) : ?>
                            <?php if ( Thim_Envato_Hosted::is_envato_hosted_site() ): ?>
                                <?php printf( __( '<span>Use <strong>Envato Market</strong> plugin to update the theme.</span>', 'thim-core' ) ); ?>
                            <?php else: ?>
                                <button class="button-link tc-update-now"
                                        type="button"><?php esc_html_e( 'Update now.', 'thim-core' ); ?></button>
                            <?php endif; ?>
                        <?php else : ?>
                            <strong><?php printf( __( 'Please <a href="%1$s" class="tc-login-envato">login with Envato</a> to update theme.', 'thim-core' ), '#thim-core-product-registration' ); ?></strong>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="tc-box-footer">
    <?php
    if ( $last_checked ) {
        $last_checked = $last_checked + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
        printf( __( '<span class="tc-last-check">Last checked on %1$s at %2$s.</span>', 'thim-core' ), date_i18n( __( 'F j, Y' ), $last_checked ), date_i18n( __( 'g:i a' ), $last_checked ) );
    }
    ?>
    <a class="button button-secondary tc-button"
       href="<?php echo esc_url( $link_check ); ?>"><?php esc_html_e( 'Check again', 'thim-core' ); ?></a>
</div>
