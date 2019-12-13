<?php
$url_php_info = Thim_For_Developer::get_url_download( 'php_info' );
$url_php_info .= '&TB_iframe=true&width=1024&height=600';
?>

<div class="col-md-4">
    <div class="tc-box">
        <div class="tc-box-header">
            <h2 class="box-title"><?php esc_html_e( 'PHP\'s configuration', 'thim-core' ); ?></h2>
        </div>
        <div class="tc-box-body text-center">
            <a type="button" class="button button-secondary tc-button thickbox" href="<?php echo esc_url( $url_php_info ); ?>"><?php esc_html_e( 'Show', 'thim-core' ); ?></a>
        </div>
    </div>
</div>
