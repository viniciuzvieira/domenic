<?php
/**
 * Redirect to custom login page
 */
if ( ! function_exists( 'thim_login_failed' ) ) {
	function thim_login_failed() {
		if ( ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'thim_login_ajax' ) || ( isset( $_REQUEST['lp-ajax'] ) && $_REQUEST['lp-ajax'] == 'login' ) || ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return;
		}

		if( isset($_REQUEST['level']) && isset($_REQUEST['submit-checkout']) && isset($_REQUEST['username']) && isset($_REQUEST['password']) ) {
			return;
		}

		if ( is_user_logged_in() ) {
			return;
		}

		$url = add_query_arg( 'result', 'failed', thim_get_login_page_url() );

		if ( isset( $_POST['g-recaptcha-response'] ) ) {
			if ( ! $_POST['g-recaptcha-response'] ) {
				$url = add_query_arg( 'gglcptch_error', '1', $url );
			}
		}

		wp_redirect( $url );
		exit;
	}

	add_action( 'wp_login_failed', 'thim_login_failed', 1000 );
}

/**
 * Register failed
 *
 * @param $sanitized_user_login
 * @param $user_email
 * @param $errors
 */
if ( ! function_exists( 'thim_register_failed' ) ) {
	function thim_register_failed( $errors ) {
		// Prevent redirect in popup
		if ( ! empty( $_POST['is_popup_register'] ) ) {
			return $errors;
		}

		if ( $errors->get_error_code() ) {

			//setup your custom URL for redirection
			$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );

			foreach ( $errors->errors as $e => $m ) {
				$url = add_query_arg( $e, '1', $url );
			}
			wp_redirect( $url );
			exit;
		}

		return $errors;
	}

	add_action( 'registration_errors', 'thim_register_failed', 99, 3 );
}

/**
 * Filter register link
 *
 * @param $register_url
 *
 * @return string|void
 */
if ( ! function_exists( 'thim_get_register_url' ) ) {
	function thim_get_register_url() {
		$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );

		return $url;
	}
}
if ( ! is_multisite() ) {
	add_filter( 'register_url', 'thim_get_register_url' );
}

/**
 * Process extra register fields
 *
 * @param $login
 * @param $email
 * @param $errors
 */
if ( ! function_exists( 'thim_check_extra_register_fields' ) ) {
	function thim_check_extra_register_fields( $login, $email, $errors ) {
		if ( get_theme_mod( 'thim_auto_login', true ) ) {
			if ( $_POST['password'] !== $_POST['repeat_password'] ) {
				$errors->add( 'passwords_not_matched', "<strong>ERROR</strong>: Passwords must match" );
			}
		}
	}
}
add_action( 'register_post', 'thim_check_extra_register_fields', 10, 3 );

/**
 * Update password
 *
 * @param $user_id
 */
if ( ! function_exists( 'thim_register_extra_fields' ) ) {
	function thim_register_extra_fields( $user_id ) {
		if ( ! get_theme_mod( 'thim_auto_login', true ) ) { // normal login
			return false;
		}

		if ( ! isset( $_POST['password'] ) || ! isset( $_POST['repeat_password'] ) ) {
			return false;
		}

		$pw         = sanitize_text_field( $_POST['password'] );
		$confirm_pw = sanitize_text_field( $_POST['repeat_password'] );

		if ( $pw !== $confirm_pw ) {
			return false;
		}

		$user_data       = array();
		$user_data['ID'] = $user_id;

		$user_data['user_pass'] = $pw;

		add_filter( 'send_password_change_email', '__return_false' );

		$new_user_id = wp_update_user( $user_data );

		// Login after registered
		if ( ! is_admin() ) {
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
			wp_new_user_notification( $user_id, null, 'admin' ); // new user registration notification only send to admin

			if ( isset( $_POST['level'] ) && $_POST['level'] && isset( $_POST['token'] ) && $_POST['token'] && isset( $_POST['gateway'] ) && $_POST['gateway'] ) {
				return;
			}

			if ( isset( $_REQUEST['level'] ) && $_REQUEST['level'] && isset( $_REQUEST['review'] ) && $_REQUEST['review'] && isset( $_REQUEST['token'] ) && $_REQUEST['token'] && isset( $_REQUEST['PayerID'] ) && $_REQUEST['PayerID'] ) {
				return;
			}

			if ( ( isset( $_POST['billing_email'] ) && ! empty( $_POST['billing_email'] ) ) || ( isset( $_POST['bconfirmemail'] ) && ! empty( $_POST['bconfirmemail'] ) ) ) {
				return;
			} else {
				if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					wp_redirect( $_REQUEST['redirect_to'] );
				} else {
					$theme_options_data = get_theme_mods();
					if ( ! empty( $_REQUEST['option'] ) && $_REQUEST['option'] == 'moopenid' ) {
						if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
							$http = "https://";
						} else {
							$http = "http://";
						}
						$redirect_url = urldecode( html_entity_decode( esc_url( $http . $_SERVER["HTTP_HOST"] . str_replace( '?option=moopenid', '', $_SERVER['REQUEST_URI'] ) ) ) );
						if ( html_entity_decode( esc_url( remove_query_arg( 'ss_message', $redirect_url ) ) ) == wp_login_url() || strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false || strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) !== false ) {
							$redirect_url = site_url() . '/';
						}

						wp_redirect( $redirect_url );

						return;
					}

					if ( ! empty( $theme_options_data['thim_register_redirect'] ) ) {
						wp_redirect( $theme_options_data['thim_register_redirect'] );
					} else {
						wp_redirect( home_url() );
					}
				}
				exit();
			}
		}

	}
}
add_action( 'user_register', 'thim_register_extra_fields', 1000 );

/**
 * Redirect to custom register page in case multi sites
 *
 * @param $url
 *
 * @return mixed
 */
if ( ! function_exists( 'thim_multisite_register_redirect' ) ) {
	function thim_multisite_register_redirect( $url ) {

		if ( ! is_user_logged_in() ) {
			if ( is_multisite() ) {
				$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );
			}

			$user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
			$user_email = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';

			$errors = register_new_user( $user_login, $user_email );

			if ( ! is_wp_error( $errors ) ) {
				$redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
				wp_safe_redirect( $redirect_to );
				exit();
			}
		}

		return $url;
	}
}
add_filter( 'wp_signup_location', 'thim_multisite_register_redirect' );

/**
 * Filter lost password link
 *
 * @param $url
 *
 * @return string
 */
if ( ! function_exists( 'thim_get_lost_password_url' ) ) {
	function thim_get_lost_password_url() {
		$url = add_query_arg( 'action', 'lostpassword', thim_get_login_page_url() );

		return $url;
	}
}

/**
 * Add lost password link into login form
 *
 * @param $content
 * @param $args
 *
 * @return string
 */
if ( ! function_exists( 'thim_add_lost_password_link' ) ) {
	function thim_add_lost_password_link( $content ) {
		$content .= '<a class="lost-pass-link" href="' . thim_get_lost_password_url() . '" title="' . esc_attr__( 'Lost Password', 'eduma' ) . '">' . esc_html__( 'Lost your password?', 'eduma' ) . '</a>';

		return $content;
	}
}
add_filter( 'login_form_middle', 'thim_add_lost_password_link', 999 );

/**
 * Register failed
 */
if ( ! function_exists( 'thim_reset_password_failed' ) ) {
	function thim_reset_password_failed() {
		//setup your custom URL for redirection
		$url = add_query_arg( 'action', 'lostpassword', thim_get_login_page_url() );

		if ( empty( $_POST['user_login'] ) ) {
			$url = add_query_arg( 'empty', '1', $url );
			wp_redirect( $url );
			exit;
		} elseif ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
			if ( empty( $user_data ) ) {
				$url = add_query_arg( 'user_not_exist', '1', $url );
				wp_redirect( $url );
				exit;
			}
		} elseif ( ! username_exists( $_POST['user_login'] ) ) {
			$url = add_query_arg( 'user_not_exist', '1', $url );
			wp_redirect( $url );
			exit;
		}
	}
}
add_action( 'lostpassword_post', 'thim_reset_password_failed', 999 );

/**
 * Get login page url
 *
 * @return false|string
 */
if ( ! function_exists( 'thim_get_login_page_url' ) ) {
	function thim_get_login_page_url() {

//		if ( ! thim_plugin_active( 'elementor/elementor.php' ) && ! thim_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) && ! thim_plugin_active( 'js_composer/js_composer.php' ) ) {
//			return wp_login_url();
//		}

		if ( $page = get_option( 'thim_login_page' ) ) {
			return get_permalink( $page );
		} else {
			global $wpdb;
			$page = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
			WHERE 	pm.meta_key = %s
			AND 	pm.meta_value = %s
			AND		p.post_type = %s
			AND		p.post_status = %s",
					'thim_login_page',
					'1',
					'page',
					'publish'
				)
			);
			if ( ! empty( $page[0] ) ) {
				return get_permalink( $page[0] );
			}
		}

		return wp_login_url();

	}
}

/**
 * Process ajax login-popup
 */
add_action( 'wp_ajax_nopriv_thim_login_ajax', 'thim_login_ajax_callback' );
add_action( 'wp_ajax_thim_login_ajax', 'thim_login_ajax_callback' );
if ( ! function_exists( 'thim_login_ajax_callback' ) ) {
	function thim_login_ajax_callback() {
		//ob_start();
		if ( empty( $_REQUEST['data'] ) ) {
			$response_data = array(
				'code'    => - 1,
				'message' => '<p class="message message-error">' . esc_html__( 'Something wrong. Please try again.', 'eduma' ) . '</p>'
			);
		} else {

			parse_str( $_REQUEST['data'], $login_data );

			foreach ( $login_data as $k => $v ) {
				$_POST[ $k ] = $v;
			}

			$user_verify = wp_signon( array(), is_ssl() );

			$code    = 1;
			$message = '';

			if ( is_wp_error( $user_verify ) ) {
				if ( ! empty( $user_verify->errors ) ) {
					$errors = $user_verify->errors;

					if ( ! empty( $errors['gglcptch_error'] ) ) {
						$message = '<p class="message message-error">' . esc_html__( 'You have entered an incorrect reCAPTCHA value.', 'eduma' ) . '</p>';
					} elseif ( ! empty( $errors['invalid_username'] ) || ! empty( $errors['incorrect_password'] ) ) {
						$message = '<p class="message message-error">' . esc_html__( 'Invalid username or password. Please try again!', 'eduma' ) . '</p>';
					} else if ( ! empty( $errors['cptch_error'] ) && is_array( $errors['cptch_error'] ) ) {
						foreach ( $errors['cptch_error'] as $key => $value ) {
							$message .= '<p class="message message-error">' . $value . '</p>';
						}
					} else {
						$message = '<p class="message message-error">' . esc_html__( 'Something wrong. Please try again.', 'eduma' ) . '</p>';
					}
				} else {
					$message = '<p class="message message-error">' . esc_html__( 'Something wrong. Please try again.', 'eduma' ) . '</p>';
				}
				$code = - 1;
			} else {
				$message = '<p class="message message-success">' . esc_html__( 'Login successful, redirecting...', 'eduma' ) . '</p>';
			}

			$response_data = array(
				'code'    => $code,
				'message' => $message
			);

			if ( ! empty( $login_data['redirect_to'] ) ) {
				$response_data['redirect'] = $login_data['redirect_to'];
			}
		}
		echo json_encode( $response_data );
		die(); // this is required to return a proper result
	}
}

/*
 * Process ajax register popup
 * */
if ( ! function_exists( 'thim_register_ajax_callback' ) ) {
	function thim_register_ajax_callback() {
		$has_auto_login = get_theme_mod( 'thim_auto_login', true );
		// First check the nonce, if it fails the function will break
		$secure = check_ajax_referer( 'ajax_register_nonce', 'register_security', false );

		if ( ! $secure ) {
			$response_data = array(
				'message' => '<p class="message message-error">' . esc_html__( 'Something wrong. Please try again.', 'eduma' ) . '</p>'
			);

			wp_send_json_error( $response_data );
		}

		parse_str( $_POST['data'], $data );

		foreach ( $data as $k => $v ) {
			$_POST[ $k ] = $v;
		}

		$_POST['is_popup_register'] = 1;

		if ( ! empty( $data['modify_user_notification'] ) ) {
			$_REQUEST['modify_user_notification'] = 1;
		}

		$info = array();

		$info['user_login'] = sanitize_user( $data['user_login'] );
		$info['user_email'] = sanitize_email( $data['user_email'] );
		$info['user_pass']  = sanitize_text_field( $data['password'] );

		if ( $has_auto_login ) {
			$confirm_password = sanitize_text_field( $data['repeat_password'] );

			if ( $info['user_pass'] !== $confirm_password ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'Those passwords didn\'t match. Try again.', 'eduma' ) . '</p>'
				);

				wp_send_json_error( $response_data );
			}
		}

		// Register the user
		$user_register = register_new_user( $info['user_login'], $info['user_email'] );

		if ( is_wp_error( $user_register ) ) {
			$error = $user_register->get_error_codes();

			if ( in_array( 'gglcptch_error', $error ) ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'You have entered an incorrect reCAPTCHA value.', 'eduma' ) . '</p>'
				);
			} elseif ( in_array( 'empty_username', $error ) ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'Please enter a username!', 'eduma' ) . '</p>'
				);
			} elseif ( in_array( 'invalid_username', $error ) ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'The username is invalid. Please try again!', 'eduma' ) . '</p>'
				);
			} elseif ( in_array( 'username_exists', $error ) ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'This username is already registered. Please choose another one!', 'eduma' ) . '</p>'
				);
			} elseif ( in_array( 'empty_email', $error ) ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'Please type your e-mail address!', 'eduma' ) . '</p>'
				);
			} elseif ( in_array( 'invalid_email', $error ) ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'The email address isn\'t correct. Please try again!', 'eduma' ) . '</p>'
				);
			} elseif ( in_array( 'email_exists', $error ) ) {
				$response_data = array(
					'message' => '<p class="message message-error">' . esc_html__( 'This email is already registered. Please choose another one!', 'eduma' ) . '</p>'
				);
			}

			wp_send_json_error( $response_data );
		} else {
			if ( $has_auto_login ) {
				// wp_new_user_notification( $user_register, null, 'admin' );

				//				$creds                  = array();
				//				$creds['user_login']    = $info['user_login'];
				//				$creds['user_password'] = $info['user_pass'];
				//
				//				$user_signon = wp_signon( $creds, false );
				//				if ( is_wp_error( $user_signon ) ) {
				//					$response_data = array(
				//						'message' => '<p class="message message-error">' . esc_html__( 'Wrong username or password.', 'eduma' ) . '</p>'
				//					);
				//
				//					wp_send_json_error( $response_data );
				//				} else {
				//					wp_set_current_user( $user_signon->ID );
				//					wp_set_auth_cookie( $user_signon->ID );
				//
				//					$response_data = array(
				//						'message' => '<p class="message message-success">' . esc_html__( 'Registration successful, redirecting...', 'eduma' ) . '</p>'
				//					);
				//
				//					wp_send_json_success( $response_data );
				//				}

				wp_set_current_user( $user_register );
				wp_set_auth_cookie( $user_register );

				$response_data = array(
					'message' => '<p class="message message-success">' . esc_html__( 'Registration successful, redirecting...', 'eduma' ) . '</p>'
				);

				wp_send_json_success( $response_data );
			} else {
				//				wp_new_user_notification( $user_register, null, 'both' );

				$response_data = array(
					'message' => '<p class="message message-success">' . esc_html__( 'Registration is successful. Confirmation will be e-mailed to you.', 'eduma' ) . '</p>'
				);

				wp_send_json_success( $response_data );
			}
		}
	}
}

if ( get_option( 'users_can_register' ) ) {
	add_action( 'wp_ajax_nopriv_thim_register_ajax', 'thim_register_ajax_callback' );
}

/**
 * Add filter login redirect
 */
add_filter( 'login_redirect', 'thim_login_redirect', 1000 );
if ( ! function_exists( 'thim_login_redirect' ) ) {
	function thim_login_redirect() {
		if ( empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_url = get_theme_mod( 'thim_login_redirect' );
			if ( ! empty( $redirect_url ) ) {
				return $redirect_url;
			} else {
				return home_url();
			}
		} else {
			return $_REQUEST['redirect_to'];
		}
	}
}

/**
 * Redirect reset password
 */
if ( ! function_exists( 'thim_redirect_rp_url' ) ) {
	function thim_redirect_rp_url() {
		if (
			! empty( $_REQUEST['action'] ) && $_REQUEST['action'] == 'rp'
			&& ! empty( $_REQUEST['key'] ) && ! empty( $_REQUEST['login'] )
		) {
			$reset_link = add_query_arg(
				array(
					'action' => 'rp',
					'key'    => $_REQUEST['key'],
					'login'  => rawurlencode( $_REQUEST['login'] )
				), thim_get_login_page_url()
			);

			if ( ! thim_is_current_url( $reset_link ) ) {
				wp_redirect( $reset_link );
				exit();
			}
		}
	}
}

if ( ! function_exists( 'is_wpe' ) && ! function_exists( 'is_wpe_snapshot' ) ) {
	add_action( 'init', 'thim_redirect_rp_url' );
}

/*
 * Remove default login page link in the email
 *
 */
function thim_remove_default_login_url( $url ) {
	global $wp;

	if ( ! empty( $wp->query_vars['modify_user_notification'] ) ) {
		unset( $wp->query_vars['modify_user_notification'] );

		return '';
	}

	return $url;
}

add_filter( 'login_url', 'thim_remove_default_login_url', 1000 );

/*
 * Change new user email content
 *
 */
if ( ! function_exists( 'thim_change_new_user_email_content' ) ) {
	function thim_change_new_user_email_content( $wp_new_user_notification_email, $user ) {
		if ( array_key_exists( 'message', $wp_new_user_notification_email ) ) {
			$message = sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
			$message .= __( 'To set your password, visit the following address:' ) . "\r\n\r\n";
			$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . ">\r\n\r\n";

			$message .= wp_login_url() . "\r\n";
		}

		return $wp_new_user_notification_email;
	}

	add_filter( 'wp_new_user_notification_email', 'thim_change_new_user_email_content', 15, 2 );
}

/*
 * Add google captcha register check to register form ( multisite case )
 */
if ( is_multisite() && function_exists( 'gglcptch_register_check' ) ) {
	global $gglcptch_ip_in_whitelist;

	if ( ! $gglcptch_ip_in_whitelist ) {
		add_action( 'registration_errors', 'gglcptch_register_check', 10, 1 );
	}
}
