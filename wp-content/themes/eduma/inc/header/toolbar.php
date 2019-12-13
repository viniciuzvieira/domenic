<?php if ( is_active_sidebar( 'toolbar' ) ) : ?>
	<div id="toolbar" class="toolbar">
		<div class="<?php echo get_theme_mod( 'thim_header_size', 'default' ) == 'full_width' ? 'header_full' : 'container';?>">
			<div class="row">
				<div class="col-sm-12">
					<div class="toolbar-container">
						<?php dynamic_sidebar( 'toolbar' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div><!--End/div#toolbar-->
<?php
endif;
