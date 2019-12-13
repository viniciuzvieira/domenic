<div class="tc-box-body">
    <div class="thim-form-subscribe">
        <div class="description">
			<?php esc_html_e( 'Sign up to our monthly newsletter to find out when we launch new features, products.', 'thim-core' ); ?>
        </div>

        <div class="form text-center">
			<?php
			Thim_Subscribe::get_form();
			?>
        </div>

        <div class="tc-hide text-center thanks">
            <button class="button button-primary tc-button"><?php esc_html_e( 'Thank you!', 'thim-core' ); ?></button>

            <p><?php esc_html_e( 'This box will be hidden after the page was reloaded :)', 'thim-core' ); ?></p>
        </div>
    </div>
</div>
