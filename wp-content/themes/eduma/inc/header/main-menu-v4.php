<ul class="nav navbar-nav menu-main-menu">
	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '%3$s'
		) );
	} else {
		?>
		<li class="menu-right">
			<ul>
				<li>
					<div class="thim-widget-login-popup thim-widget-login-popup-base">
						<div class="thim-link-login thim-login-popup">
							<a class="logout" href="<?php echo esc_url( home_url( '/' ) ) . 'wp-admin/nav-menus.php?action=locations'; ?>"><span data-hover="Shop"><?php esc_html_e( 'Click here to select or create a menu', 'eduma' ); ?></span></a>
						</div>
					</div>
				</li>
			</ul>
		</li>
		<?php
	}
	?>
</ul>
<!--</div>-->