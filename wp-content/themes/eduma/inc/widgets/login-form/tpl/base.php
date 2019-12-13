<?php

//update_post_meta( get_the_ID(), 'thim_login_page', '1' );
update_option( 'thim_login_page', get_the_ID() );

if ( is_user_logged_in() ) {
	echo '<p class="message message-success">' . sprintf( wp_kses( __( 'You have logged in. <a href="%s">Sign Out</a>', 'eduma' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( wp_logout_url( apply_filters( 'thim_default_logout_redirect', 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) ) ) ) . '</p>';

	return;
}
$theme_options_data = get_theme_mods();
?>
<?php if ( isset( $_GET['result'] ) || isset( $_GET['action'] ) ) : ?>
	<?php if ( isset( $_GET['result'] ) && $_GET['result'] == 'failed' ): ?>
		<?php if ( isset( $_GET['gglcptch_error'] ) && $_GET['gglcptch_error'] ): ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'You have entered an incorrect reCAPTCHA value.', 'eduma' ) . '</p>'; ?>
		<?php else: ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'Invalid username or password. Please try again!', 'eduma' ) . '</p>'; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( ! empty( $_GET['action'] ) && $_GET['action'] == 'register' ) : ?>
		<?php if ( get_option( 'users_can_register' ) ) : ?>
			<?php if ( ! empty( $_GET['empty_username'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'Please enter a username!', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<?php if ( ! empty( $_GET['empty_email'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'Please type your e-mail address!', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<?php if ( ! empty( $_GET['username_exists'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'This username is already registered. Please choose another one!', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<?php if ( ! empty( $_GET['email_exists'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'This email is already registered. Please choose another one!', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<?php if ( ! empty( $_GET['invalid_email'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'The email address isn\'t correct. Please try again!', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<?php if ( ! empty( $_GET['invalid_username'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'The username is invalid. Please try again!', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<?php if ( ! empty( $_GET['passwords_not_matched'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'Passwords must matched!', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<?php if ( ! empty( $_GET['gglcptch_error'] ) ) : ?>
				<?php echo '<p class="message message-error">' . esc_html__( 'You have entered an incorrect reCAPTCHA value.', 'eduma' ) . '</p>'; ?>
			<?php endif; ?>

			<div class="thim-login form-submission-register">
				<h2 class="title"><?php esc_html_e( 'Register', 'eduma' ); ?></h2>

				<form class="<?php if ( get_theme_mod( 'thim_auto_login', true ) ) {
					echo 'auto_login';
				} ?>" name="registerform" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post" novalidate="novalidate">
					<p>
						<input placeholder="<?php esc_attr_e( 'Username', 'eduma' ); ?>" type="text" name="user_login" class="input required" />
					</p>

					<p>
						<input placeholder="<?php esc_attr_e( 'Email', 'eduma' ); ?>" type="email" name="user_email" class="input required" />
					</p>

					<?php if ( get_theme_mod( 'thim_auto_login', true ) ) { ?>
						<p>
							<input placeholder="<?php esc_attr_e( 'Password', 'eduma' ); ?>" type="password" name="password" class="input required" />
						</p>
						<p>
							<input placeholder="<?php esc_attr_e( 'Repeat Password', 'eduma' ); ?>" type="password" name="repeat_password" class="input required" />
						</p>

					<?php } ?>

					<?php
					if ( is_multisite() && function_exists( 'gglcptch_login_display' ) ) {
						gglcptch_login_display();
					}

					do_action( 'register_form' );
					?>

					<?php if ( isset( $instance['captcha'] ) && $instance['captcha'] == 'yes' ) : ?>
						<p class="thim-login-captcha">
							<?php
							$value_1 = rand( 1, 9 );
							$value_2 = rand( 1, 9 );
							?>
							<input type="text" data-captcha1="<?php echo esc_attr( $value_1 ); ?>" data-captcha2="<?php echo esc_attr( $value_2 ); ?>" placeholder="<?php echo esc_attr( $value_1 . ' &#43; ' . $value_2 . ' &#61;' ); ?>" class="captcha-result required" />
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $instance['term'] ) ): ?>
						<?php
						$target = isset($instance['is_external']) ? $instance['is_external'] : '_blank';
						$rel = isset($instance['nofollow']) ? 'nofollow' : 'dofollow';
						?>
						<p>
							<input type="checkbox" class="required" name="term" id="termFormField">
							<label for="termFormField"><?php printf( __( 'I accept the <a href="%s" target="%s" rel="%s">Terms of Service</a>', 'eduma' ), esc_url( $instance['term'] ), $target, $rel ); ?></label>
						</p>
					<?php endif; ?>

					<p>
						<?php
						$register_redirect = get_theme_mod( 'thim_register_redirect', false );
						if ( empty( $register_redirect ) ) {
							$register_redirect = add_query_arg( 'result', 'registered', thim_get_login_page_url() );
						}
						?>
						<input type="hidden" name="redirect_to" value="<?php echo ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : $register_redirect; ?>" />
						<input type="hidden" name="modify_user_notification" value="1">
					</p>

					<?php do_action( 'signup_hidden_fields', 'create-another-site' ); ?>
					<p class="submit">
						<input type="submit" name="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Sign up', 'eduma' ); ?>" />
					</p>
				</form>
				<?php echo '<p class="link-bottom">' . esc_html__( 'Are you a member? ', 'eduma' ) . ' <a href="' . esc_url( thim_get_login_page_url() ) . '">' . esc_html__( 'Login now', 'eduma' ) . '</a></p>'; ?>
			</div>

			<?php return; ?>
		<?php else : ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'Your site does not allow users registration.', 'eduma' ) . '</p>'; ?>
			<?php return; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( isset( $_GET['action'] ) && $_GET['action'] == 'lostpassword' ) : ?>

		<?php if ( ! empty( $_GET['empty'] ) ) : ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'Please enter a username or email!', 'eduma' ) . '</p>'; ?>
		<?php endif; ?>
		<?php if ( ! empty( $_GET['user_not_exist'] ) ) : ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'The user does not exist. Please try again!', 'eduma' ) . '</p>'; ?>
		<?php endif; ?>

		<div class="thim-login form-submission-lost-password">
			<h2 class="title"><?php esc_html_e( 'Get Your Password', 'eduma' ); ?></h2>

			<form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url( network_site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ); ?>" method="post">
				<p class="description"><?php esc_html_e( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'eduma' ); ?></p>

				<p>
					<input placeholder="<?php esc_attr_e( 'Username or email', 'eduma' ); ?>" type="text" name="user_login" class="input required" />
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( add_query_arg( 'result', 'reset', thim_get_login_page_url() ) ); ?>" />
					<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Reset password', 'eduma' ); ?>" />
				</p>
				<?php do_action( 'lostpassword_form' ); ?>
			</form>
		</div>
		<?php return; ?>
	<?php endif; ?>

	<?php if ( isset( $_GET['action'] ) && $_GET['action'] == 'rp' ) : ?>

		<?php if ( ! empty( $_GET['expired_key'] ) ) : ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'The key is expired. Please try again!', 'eduma' ) . '</p>'; ?>
		<?php endif; ?>
		<?php if ( ! empty( $_GET['invalid_key'] ) ) : ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'The key is invalid. Please try again!', 'eduma' ) . '</p>'; ?>
		<?php endif; ?>
		<?php if ( ! empty( $_GET['invalid_password'] ) ) : ?>
			<?php echo '<p class="message message-error">' . esc_html__( 'The password is invalid. Please try again!', 'eduma' ) . '</p>'; ?>
		<?php endif; ?>

		<div class="thim-login form-submission-change-password">
			<h2 class="title"><?php esc_html_e( 'Change Password', 'eduma' ); ?></h2>

			<form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
				<input type="hidden" id="user_login" name="login" value="<?php echo isset( $_GET['login'] ) ? esc_attr( $_GET['login'] ) : ''; ?>" autocomplete="off" />
				<input type="hidden" name="key" value="<?php echo isset( $_GET['key'] ) ? esc_attr( $_GET['key'] ) : ''; ?>" />

				<p>
					<input placeholder="<?php esc_attr_e( 'New password', 'eduma' ); ?>" type="text" name="password" id="password" class="input" />
				</p>

				<p class="resetpass-submit">
					<input type="submit" name="submit" id="resetpass-button" class="button" value="<?php _e( 'Reset Password', 'eduma' ); ?>" />
				</p>

				<p class="message message-success">
					<?php esc_html_e( 'Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).', 'eduma' ); ?>
				</p>

			</form>
		</div>
		<?php return; ?>
	<?php endif; ?>

	<?php if ( isset( $_GET['result'] ) && $_GET['result'] == 'registered' ) : ?>
		<?php echo '<p class="message message-success">' . esc_html__( 'Registration is successful. Confirmation will be e-mailed to you.', 'eduma' ) . '</p>'; ?>
		<?php return; ?>
	<?php endif; ?>

	<?php if ( isset( $_GET['result'] ) && $_GET['result'] == 'reset' ) : ?>
		<?php echo '<p class="message message-success">' . esc_html__( 'Check your email to get a link to create a new password.', 'eduma' ) . '</p>'; ?>
		<?php return; ?>
	<?php endif; ?>

	<?php if ( isset( $_GET['result'] ) && $_GET['result'] == 'changed' ) : ?>
		<?php echo '<p class="message message-success">' . sprintf( wp_kses( __( 'Password changed. You can <a href="%s">login</a> now.', 'eduma' ), array( 'a' => array( 'href' => array() ) ) ), thim_get_login_page_url() ) . '</p>'; ?>
		<?php return; ?>
	<?php endif; ?>

<?php endif; ?>

<div id="thim-form-login">
	<div class="thim-login-container">
		<div class="thim-login form-submission-login">
			<h2 class="title"><?php esc_html_e( 'Login with your site account', 'eduma' ); ?></h2>
			<?php
			$login_redirect = get_theme_mod( 'thim_login_redirect', false );
			if ( empty( $login_redirect ) ) {
				$login_redirect = apply_filters( 'thim_default_login_redirect', home_url() );
			}
			$redirect = ! empty( $_REQUEST['redirect_to'] ) ? esc_url( $_REQUEST['redirect_to'] ) : $login_redirect;

			$link_redirect = ! empty( $_REQUEST['redirect_to'] ) ? esc_url( $_REQUEST['redirect_to'] ) : '';
			$link_register = add_query_arg( 'redirect_to', urlencode( $link_redirect ), thim_get_register_url() );
			?>
			<form name="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post" novalidate>
				<p class="login-username">
					<input type="text" name="log" placeholder="<?php esc_html_e( 'Username or email', 'eduma' ); ?>" class="input required" size="20" />
				</p>
				<p class="login-password">
					<input type="password" name="pwd" placeholder="<?php esc_html_e( 'Password', 'eduma' ); ?>" class="input required" value="" size="20" />
				</p>
				<?php
				/**
				 * Fires following the 'Password' field in the login form.
				 *
				 * @since 2.1.0
				 */
				do_action( 'login_form' );
				?>
				<?php if ( isset( $instance['captcha'] ) && $instance['captcha'] == 'yes' ) : ?>
					<p class="thim-login-captcha">
						<?php
						$value_1 = rand( 1, 9 );
						$value_2 = rand( 1, 9 );
						?>
						<input type="text" data-captcha1="<?php echo esc_attr( $value_1 ); ?>" data-captcha2="<?php echo esc_attr( $value_2 ); ?>" placeholder="<?php echo esc_attr( $value_1 . ' &#43; ' . $value_2 . ' &#61;' ); ?>" class="captcha-result required" />
					</p>
				<?php endif; ?>
				<?php echo '<a class="lost-pass-link" href="' . thim_get_lost_password_url() . '" title="' . esc_attr__( 'Lost Password', 'eduma' ) . '">' . esc_html__( 'Lost your password?', 'eduma' ) . '</a>'; ?>
				<p class="forgetmenot login-remember">
					<label for="rememberMe"><input name="rememberme" type="checkbox" id="rememberMe" value="forever" /> <?php esc_html_e( 'Remember Me', 'eduma' ); ?>
					</label></p>
				<p class="submit login-submit">
					<input type="submit" name="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Login', 'eduma' ); ?>" />
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>" />
					<input type="hidden" name="testcookie" value="1" />
				</p>
			</form>
			<?php
			$registration_enabled = get_option( 'users_can_register' );
			if ( $registration_enabled ) {
				echo '<p class="link-bottom">' . esc_html__( 'Not a member yet? ', 'eduma' ) . ' <a href="' . esc_attr( $link_register ) . '">' . esc_html__( 'Register now', 'eduma' ) . '</a></p>';
			}
			?>
		</div>
	</div>
</div>

