<div class="thim-link-login thim-login-popup">
	<?php
	$layout               = isset( $instance['layout'] ) ? $instance['layout'] : 'base';
	$profile_text         = $logout_text = $login_text = $register_text = '';
	$registration_enabled = get_option( 'users_can_register' );

	if ( 'base' == $layout ) {
		$profile_text  = isset( $instance['text_profile'] ) ? $instance['text_profile'] : '';
		$logout_text   = isset( $instance['text_logout'] ) ? $instance['text_logout'] : '';
		$login_text    = isset( $instance['text_login'] ) ? $instance['text_login'] : '';
		$register_text = isset( $instance['text_register'] ) ? $instance['text_register'] : '';
	} else {
		$profile_text = '<i class="ion-android-person"></i>';
		$logout_text  = '<i class="ion-ios-redo"></i>';
		$login_text   = '<i class="ion-android-person"></i>';
	}

	// Login popup link output
	if ( is_user_logged_in() ) {
		if ( thim_plugin_active( 'learnpress/learnpress.php' ) && $profile_text ) {
			if ( thim_is_new_learnpress( '1.0' ) ) {
				echo '<a class="profile" href="' . esc_url( learn_press_user_profile_link() ) . '">' . ( $profile_text ) . '</a>';
			} else {
				echo '<a class="profile" href="' . esc_url( apply_filters( 'learn_press_instructor_profile_link', '#', get_current_user_id(), '' ) ) . '">' . ( $profile_text ) . '</a>';
			}
		}

		if ( $login_text ) {
			?>
			<a class="logout" href="<?php echo esc_url( wp_logout_url( apply_filters( 'thim_default_logout_redirect', ( ! empty( $_SERVER['HTTPS'] ) ? "https" : "http" ) . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) ) ); ?>"><?php echo( $logout_text ); ?></a>
			<?php
		}
	} else {
		if ( $registration_enabled && 'base' == $layout ) {
			if ( $register_text ) {
				echo '<a class="register js-show-popup" href="' . esc_url( thim_get_register_url() ) . '">' . ( $register_text ) . '</a>';
			}
		}

		if ( $login_text ) {
			echo '<a class="login js-show-popup" href="' . esc_url( thim_get_login_page_url() ) . '">' . ( $login_text ) . '</a>';
		}
	}
	// End login popup link output
	?>
</div>
<?php

