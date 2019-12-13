<?php
$url_theme_options = Thim_For_Developer::get_url_download( 'theme_options' );
?>

<div class="col-md-4">
    <div class="tc-box">
        <div class="tc-box-header">
            <h2 class="box-title"><?php esc_html_e( 'Export theme_options.dat', 'thim-core' ); ?></h2>
        </div>
        <div class="tc-box-body text-center">
            <a type="button" class="button button-secondary tc-button"
               href="<?php echo esc_url( $url_theme_options ); ?>"
               download="<?php echo esc_url( $url_theme_options ); ?>"><?php esc_html_e( 'Download', 'thim-core' ); ?></a>
        </div>
    </div>
</div>
