<?php

defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

add_shortcode( 'wpapp_date', 'wpapp_date' );
function wpapp_date() {

	$content_post = get_post();
	
	return '<!-- Date -->
	<p class="wpappninja_date">' . wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date)) . '</p>';
}

function wpmobile_retrieve_password($user_login) {
    global $wpdb, $current_site;

    if ( strpos( $user_login, '@' ) ) {
        $user_data = get_user_by( 'email', trim( $user_login ) );
        if ( empty( $user_data ) )
           return false;
    } else {
        return false;
    }

    do_action('lostpassword_post');


    if ( !$user_data ) return false;

    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    do_action('retrieve_password', $user_login);

    $allow = apply_filters('allow_password_reset', true, $user_data->ID);

    if ( ! $allow )
        return false;
    else if ( is_wp_error($allow) )
        return false;


        // Generate something random for a key...
        $key = wp_generate_password(20, false);
        do_action('retrieve_password_key', $user_login, $key);


    	if ( empty( $wp_hasher ) ) {
        	require_once ABSPATH . WPINC . '/class-phpass.php';
        	$wp_hasher = new PasswordHash( 8, true );
    	}
	    $hashed    = time() . ':' . $wp_hasher->HashPassword( $key );
        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));


	$message = "<br/>".__('Someone has requested a password reset for the following account:') . "<br/><br/>";
    $message .= "<b>".$user_login . "</b> <a href='" .network_home_url( '/' ). "'>" .network_home_url( '/' ). "</a><br/><br/>";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "<br/><br/>";
    $message .= __('To reset your password, visit the following address:') . "<br/>";
    $message .= "<a href='";
    $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
    $message .= "'>".network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login')."</a>";
    $message .= "<br/><br/>";

    if ( is_multisite() )
        $blogname = $GLOBALS['current_site']->site_name;
    else
        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $title = sprintf( __('[%s] Password Reset'), $blogname );

    $title = apply_filters('retrieve_password_title', $title);
    $message = apply_filters('retrieve_password_message', $message, $key);

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($user_email, $title, $message, $headers);

    return true;
}

function wpmobile_register_new_user($user_login, $user_email, $user_pass) {

	if ( !get_option( 'users_can_register' ) ) {
		return;
	}

    $errors = new WP_Error();
 
    $sanitized_user_login = sanitize_user( $user_login );

    $user_email = apply_filters( 'user_registration_email', $user_email );
 
    // Check the username
    if ( $sanitized_user_login == '' ) {
        $errors->add( 'empty_username', __( 'Please enter a username.', 'wpappninja' ) );
    } elseif ( ! validate_username( $user_login ) ) {
        $errors->add( 'invalid_username', __( 'This username is invalid because it uses illegal characters. Please enter a valid username.', 'wpappninja') );
        $sanitized_user_login = '';
    } elseif ( username_exists( $sanitized_user_login ) ) {
        $errors->add( 'username_exists', __( 'This username is already registered. Please choose another one.', 'wpappninja' ) );
 
    } else {
        /** This filter is documented in wp-includes/user.php */
        $illegal_user_logins = array_map( 'strtolower', (array) apply_filters( 'illegal_user_logins', array() ) );
        if ( in_array( strtolower( $sanitized_user_login ), $illegal_user_logins ) ) {
            $errors->add( 'invalid_username', __( 'Sorry, that username is not allowed.', 'wpappninja' ) );
        }
    }
 
    // Check the email address
    if ( $user_email == '' ) {
        $errors->add( 'empty_email', __( 'Please type your email address.', 'wpappninja' ) );
    } elseif ( ! is_email( $user_email ) ) {
        $errors->add( 'invalid_email', __( 'The email address isn&#8217;t correct.' , 'wpappninja') );
        $user_email = '';
    } elseif ( email_exists( $user_email ) ) {
        $errors->add( 'email_exists', __( 'This email is already registered, please choose another one.', 'wpappninja' ) );
    }



 
    // Check the email address
    if ( $user_pass == '' ) {
        $errors->add( 'empty_pass', __( 'Please type a password.', 'wpappninja' ) );
    } elseif ( strlen($user_pass) < 8 ) {
        $errors->add( 'invalid_pass', __( 'The password need to contain at least 8 characters.' , 'wpappninja') );
    }




    do_action( 'register_post', $sanitized_user_login, $user_email, $errors );
 
    $errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );
 
    if ( $errors->get_error_code() )
        return $errors;
 
    $user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
    if ( ! $user_id || is_wp_error( $user_id ) ) {
        $errors->add( 'registerfail', sprintf( __( 'Couldn&#8217;t register you&hellip; please contact the <a href="mailto:%s">webmaster</a> !' , 'wpappninja'), get_option( 'admin_email' ) ) );
        return $errors;
    }

    //do_action( 'register_new_user', $user_id );
 
    return $user_id;
}




add_action('init', 'wpmobileapp_reset_password');
function wpmobileapp_reset_password() {

	if (!isset($_POST['resetwpmobileapp'])) {
		return;
	}
    
    if (!check_admin_referer( 'resetwpmobileapp' )) {
    	return;
    }

    $email = ( isset($_POST['uemail']) ? $_POST['uemail'] : '' );


    $return = wpmobile_retrieve_password( $email );
	
	if( !$return ) {
	    global $wpappninja_popup;
		$wpappninja_popup .= '<script>jQuery(function(){app.dialog.alert( \''.__('Impossible to reset the password for this email.', 'wpappninja').'\',\''.__('Error', 'wpappninja').'\',function(){app.popup.open(jQuery(\'.popup-reset\'));});});</script>';
	} else {
	    global $wpappninja_popup;
		$wpappninja_popup .= '<script>jQuery(function(){app.dialog.alert(\''.__('Reset link sended. Check your mailbox.', 'wpappninja').'\',\''.__('Reset successfull', 'wpappninja').'\', function(){});});</script>';
	}

}



add_action('init', 'wpmobileapp_create_account');
function wpmobileapp_create_account() {

	if ( !get_option( 'users_can_register' ) ) {
		return;
	}

	if (!isset($_POST['registerwpmobileapp'])) {
		return;
	}
    
    if (!check_admin_referer( 'registerwpmobileapp' )) {
    	return;
    }

    $user = ( isset($_POST['uname']) ? $_POST['uname'] : '' );
    $email = ( isset($_POST['uemail']) ? $_POST['uemail'] : '' );
    $pass = ( isset($_POST['upass']) ? $_POST['upass'] : '' );


    $return = wpmobile_register_new_user( $user, $email, $pass );
	
	if( is_wp_error( $return ) ) {
	    global $wpappninja_popup;
		$wpappninja_popup .= '<script>jQuery(function(){app.dialog.alert(\''.$return->get_error_message().'\',\''.__('Register', 'wpappninja').'\', function(){app.popup.open(jQuery(\'.popup-register\'));});});</script>';
	} else {
	    global $wpappninja_popup;
		wp_set_current_user($return, $user);
		wp_set_auth_cookie($return, true); 

        $wp__user = get_user_by('email', $email);
		do_action('wp_login', $user, $wp__user);
        
		wpappninja_stats_log("signup", 1);
		$wpappninja_popup .= '<script>jQuery(function(){app.dialog.alert(\''.__('Account successfully created.', 'wpappninja').'\',\''.__('You are now connected', 'wpappninja').'\');});</script>';
	}

}

add_filter( 'authenticate', 'wpmobile_username_password', 1, 3);
function wpmobile_username_password( $user, $username, $password ) {

	if (is_wpappninja()) {

        $redirect_to = wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home()));
        if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
            $redirect_to = wpappninja_translate(wpappninja_get_home());
        }
        
        
	if( $username == "" || $password == "" ) {
		wp_redirect($redirect_to . '?wpapp_shortcode=wpapp_login&login=fail&reason=both_empty' );
		exit;
	}
	}
}

//add_action( 'wp_login_failed', 'wpmobile_login_fail', 1);  // hook failed login
function wpmobile_login_fail( $username ) {

	if (is_wpappninja()) {
	     $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
    	 // if there's a valid referrer, and it's not the default log-in screen
        
        $redirect_to = wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home()));
        if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
            $redirect_to = wpappninja_translate(wpappninja_get_home());
        }
        
        
    	 if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
        	  wp_redirect($redirect_to . '?wpapp_shortcode=wpapp_login&login=fail' );  // let's append some information (login=failed) to the URL for the theme to use
          	exit;
     	}
     }
}

add_filter("logout_redirect", "wpmobile_redirect_after_login", 1, 3);
add_filter("login_redirect", "wpmobile_redirect_after_login", 1, 3);
function wpmobile_redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

    if (is_wpappninja()) {

        if (is_wp_error($user)) {
        //Login failed, find out why...
        $error_types = array_keys($user->errors);
        //Error type seems to be empty if none of the fields are filled out
            $error_type = 'both_empty';
        //Otherwise just get the first error (as far as I know there
        //will only ever be one)
        if (is_array($error_types) && !empty($error_types)) {
            $error_type = $error_types[0];
        }

            $redirect_to = wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home()));
            if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
                $redirect_to = wpappninja_translate(wpappninja_get_home());
            }
            
            wp_redirect($redirect_to . '?wpapp_shortcode=wpapp_login&login=fail&reason=' . $error_type );
        exit;
    }
        if (isset($user->roles) && is_array($user->roles)) {

        	wpappninja_stats_log("login", 1);

            if (get_wpappninja_option('login_redirect_after') != '') {
                $redirect_to = wpappninja_cache_friendly(wpmobile_weglot(get_wpappninja_option('login_redirect_after')));
                
                if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
                    $redirect_to = wpappninja_translate(get_wpappninja_option('login_redirect_after'));
                }
            } else {
                $redirect_to = wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home()));
                
                if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
                    $redirect_to = wpappninja_translate(wpappninja_get_home());
                }
            }

        }

    }

    return $redirect_to;
}

add_filter("woocommerce_login_redirect", "wc_wpmobile_redirect_after_login", 1, 3);
function wc_wpmobile_redirect_after_login( $redirect_to, $wc_user ) {
	return wpmobile_redirect_after_login( $redirect_to, '', $wc_user );
}


add_shortcode( 'wpapp_login', 'wpapp_login' );
function wpapp_login() {

    if (isset($_GET['login']) && $_GET['login'] == 'fail') {
        echo '<script>
        jQuery(function(){
            app.popup.open(jQuery(\'.popup-login\'));
        });
        </script>';
                           
        if (isset($_GET['reason'])) {
                           
                           $errormsg = esc_js($_GET['reason']);
                           
                           if ($errormsg == "both_empty") {$errormsg = __('Email and password are both required', 'wpappninja');}
                           if ($errormsg == "incorrect_password") {$errormsg = __('Password is incorrect', 'wpappninja');}
                           if ($errormsg == "invalid_username") {$errormsg = __('Username doesnt exist', 'wpappninja');}

                           echo '<script>
                           jQuery(function(){
                                  app.dialog.alert(\''.$errormsg.'\', \''.__('Something is wrong', 'wpappninja').'\');
                           });
                           </script>';
        }

        echo '<style>form#loginform input[type="text"], form#loginform input[type="password"] {border: 2px solid #c84848;}</style>';
    }

	ob_start();

    if (!is_user_logged_in()) {

        echo '<div class="wpmobile-login-loggedin">
        <div class="wpmobile-login-avatar">';
        echo get_avatar( "", 90 );
        echo '</div>';

    	echo '<p><input data-popup=".popup-login" type="button" class="popup-open button panel-close" style="width:100%" value="'.__('Login', 'wpappninja').'" /></p>';
    	if ( get_option( 'users_can_register' ) ) {
    	echo '<p><input data-popup=".popup-register" type="button" class="popup-open button panel-close" style="width:100%" value="'.__('Register', 'wpappninja').'" /></p>';
    	}
    	echo '</div>

    	<style>p.login-remember {display: none;}</style>

    	<div class="popup popup-reset">
    	<div class="block" style="background:white">
    	        <div class="wpmobile-login-avatar">';
        echo get_avatar( "", 90 );
        echo '</div><br/>
    	<form name="registerform" id="registerform" action="" method="post">
			<input type="hidden" name="resetwpmobileapp" value="1" />
			'.wp_nonce_field( 'resetwpmobileapp' ).'		
			<p class="login-username">
				<label for="uemail">'.__('Email', 'wpappninja').'</label>
				<input style="width: 100%;padding: 10px;background: #fff;border: 1px solid #eee;" type="email" name="uemail" id="wpmobileresetmail" class="input input-with-value" value="" size="20">
			</p>
			<p class="login-submit">
				<input type="submit" name="wp-submit" class="button button-primary" value="'.__('Send reset link', 'wpappninja').'">
			</p>
			
		</form><br/>
        <p style="font-size:0.8em;text-align:center;color:gray"><span data-popup=".popup-reset" class="popup-close">' . __('cancel', 'wpappninja') . '</span></p>
        </div>
        </div>


    	<div class="popup popup-login">
    	<div class="block" style="background:white">
    	        <div class="wpmobile-login-avatar">';
        echo get_avatar( "", 90 );
        echo '</div><br/>';
        wp_login_form(array('redirect' => wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home()))));
        echo '<br/>
        <a href="#" class="button" onclick="app.popup.close(jQuery(\'.popup-login\'));setTimeout(function(){app.popup.open(jQuery(\'.popup-reset\'));}, 300)"><i class="icon icon-lock"></i> ' . __('Password lost?', 'wpappninja') . '</a>
        <br/><br/>
        <p style="font-size:0.8em;text-align:center;color:gray"><span data-popup=".popup-login" class="popup-close">' . __('cancel', 'wpappninja') . '</span></p>
        </div>
        </div>

    	<script>jQuery(function(){jQuery(\'#rememberme\').attr(\'checked\', true);});</script>';

        if ( get_option( 'users_can_register' ) ) {

    	echo '<div class="popup popup-register">
    	<div class="block" style="background:white">
    	        <div class="wpmobile-login-avatar">';
        echo get_avatar( "", 90 );

    $__user = ( isset($_POST['uname']) ? $_POST['uname'] : '' );
    $__email = ( isset($_POST['uemail']) ? $_POST['uemail'] : '' );


        echo '</div><br/>


			<form name="registerform" id="registerform" action="" method="post">
			<input type="hidden" name="registerwpmobileapp" value="1" />
			'.wp_nonce_field( 'registerwpmobileapp' ).'
			<p class="login-username">
				<label for="uanme">'.__('Username', 'wpappninja').'</label>
				<input style="width: 100%;padding: 10px;background: #fff;border: 1px solid #eee;" type="text" name="uname" class="input input-with-value" value="'.$__user.'" size="20">
			</p>			
			<p class="login-username">
				<label for="uemail">'.__('Email', 'wpappninja').'</label>
				<input style="width: 100%;padding: 10px;background: #fff;border: 1px solid #eee;" type="email" name="uemail" class="input input-with-value" value="'.$__email.'" size="20">
			</p>

			<p class="login-username">
				<label for="upass">'.__('Password', 'wpappninja').'</label>
				<input style="width: 100%;padding: 10px;background: #fff;border: 1px solid #eee;" type="password" name="upass" class="input input-with-value" value="" size="20">
			</p>
		

			<p class="login-submit">
				<input type="submit" name="wp-submit" class="button button-primary" value="'.__('Register', 'wpappninja').'">
			</p>
			
		</form>';

        echo '<br/>
        <p style="font-size:0.8em;text-align:center;color:gray"><span data-popup=".popup-register" class="popup-close">' . __('cancel', 'wpappninja') . '</span></p>
        </div>
        </div>';
    	}

    } else {
        $current_user = wp_get_current_user();

        echo '<div class="wpmobile-login-loggedin">
        <div class="wpmobile-login-avatar">';
        echo get_avatar( $current_user->user_email, 90 );
        echo '</div>';
        echo '<div class="wpmobile-login-username">';
    	echo $current_user->display_name . '</div>';
	    echo '<div class="wpmobile-login-action">';

        if (get_wpappninja_option('speed_trad') == 'manual') {
            echo '<a class="button" href="'.wp_logout_url(wpappninja_cache_friendly(wpappninja_translate(wpappninja_get_home()))).'">' . __('Logout', 'wpappninja') . '</a>';
        } else {
            echo '<a class="button" href="'.wp_logout_url(wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home()))).'">' . __('Logout', 'wpappninja') . '</a>';
        }
                                         
	    echo '</div></div>';

    }

    return '<!-- Login -->' . ob_get_clean();
}

add_action('setup_theme', 'wpmobile_redirect_after_lang_switch', 1);
function wpmobile_redirect_after_lang_switch() {

    if (isset($_GET['WPMOBILE_LOCALE'])) {

        $_GET['WPMOBILE_LOCALE'] = strtolower(substr($_GET['WPMOBILE_LOCALE'], 0, 2));
        
        setcookie("WPAPPNINJA_LOCALE", $_GET['WPMOBILE_LOCALE'], time() + 300 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        $_COOKIE['WPAPPNINJA_LOCALE'] = $_GET['WPMOBILE_LOCALE'];

        $homepage_redirect = strtok(base64_decode($_GET['redirect']), "?");
        
        if (get_wpappninja_option('speed_trad') == 'manual') {

            $homepage_redirect = wpappninja_translate($homepage_redirect);
        } else {
            $homepage_redirect = wpappninja_cache_friendly($homepage_redirect);
        }

        if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'weglot') {

            if (wpappninja_get_lang() != get_wpappninja_option('weglot_original', '')) {
                $homepage_redirect = wpmobile_weglot($homepage_redirect);
            }
        }

        $homepage_redirect = wpappninja_cache_friendly($homepage_redirect);


        wp_redirect($homepage_redirect);
        exit();
    }
}

function wpmobile_get_nativename($l) {

    $niceName = json_decode('{"ab":{"name":"Abkhaz","nativeName":"аҧсуа"},"aa":{"name":"Afar","nativeName":"Afaraf"},"af":{"name":"Afrikaans","nativeName":"Afrikaans"},"ak":{"name":"Akan","nativeName":"Akan"},"sq":{"name":"Albanian","nativeName":"Shqip"},"am":{"name":"Amharic","nativeName":"አማርኛ"},"ar":{"name":"Arabic","nativeName":"العربية"},"an":{"name":"Aragonese","nativeName":"Aragonés"},"hy":{"name":"Armenian","nativeName":"Հայերեն"},"as":{"name":"Assamese","nativeName":"অসমীয়া"},"av":{"name":"Avaric","nativeName":"авар мацӀ, магӀарул мацӀ"},"ae":{"name":"Avestan","nativeName":"avesta"},"ay":{"name":"Aymara","nativeName":"aymar aru"},"az":{"name":"Azerbaijani","nativeName":"azərbaycan dili"},"bm":{"name":"Bambara","nativeName":"bamanankan"},"ba":{"name":"Bashkir","nativeName":"башҡорт теле"},"eu":{"name":"Basque","nativeName":"euskara, euskera"},"be":{"name":"Belarusian","nativeName":"Беларуская"},"bn":{"name":"Bengali","nativeName":"বাংলা"},"bh":{"name":"Bihari","nativeName":"भोजपुरी"},"bi":{"name":"Bislama","nativeName":"Bislama"},"bs":{"name":"Bosnian","nativeName":"bosanski jezik"},"br":{"name":"Breton","nativeName":"brezhoneg"},"bg":{"name":"Bulgarian","nativeName":"български език"},"my":{"name":"Burmese","nativeName":"ဗမာစာ"},"ca":{"name":"Catalan; Valencian","nativeName":"Canadian"},"ch":{"name":"Chamorro","nativeName":"Chamoru"},"ce":{"name":"Chechen","nativeName":"нохчийн мотт"},"ny":{"name":"Chichewa; Chewa; Nyanja","nativeName":"chiCheŵa, chinyanja"},"zh":{"name":"Chinese","nativeName":"中文 (Zhōngwén), 汉语, 漢語"},"cn":{"name":"Chinese","nativeName":"中文 (Zhōngwén), 汉语, 漢語"},"cv":{"name":"Chuvash","nativeName":"чӑваш чӗлхи"},"kw":{"name":"Cornish","nativeName":"Kernewek"},"co":{"name":"Corsican","nativeName":"corsu, lingua corsa"},"cr":{"name":"Cree","nativeName":"ᓀᐦᐃᔭᐍᐏᐣ"},"hr":{"name":"Croatian","nativeName":"hrvatski"},"cs":{"name":"Czech","nativeName":"česky, čeština"},"cz":{"name":"Czech","nativeName":"česky, čeština"},"da":{"name":"Danish","nativeName":"dansk"},"dv":{"name":"Divehi; Dhivehi; Maldivian;","nativeName":"ދިވެހި"},"nl":{"name":"Dutch","nativeName":"Nederlands, Vlaams"},"en":{"name":"English","nativeName":"English"},"eo":{"name":"Esperanto","nativeName":"Esperanto"},"et":{"name":"Estonian","nativeName":"eesti, eesti keel"},"ee":{"name":"Ewe","nativeName":"Eʋegbe"},"fo":{"name":"Faroese","nativeName":"føroyskt"},"fj":{"name":"Fijian","nativeName":"vosa Vakaviti"},"fi":{"name":"Finnish","nativeName":"suomi, suomen kieli"},"fr":{"name":"French","nativeName":"français, langue française"},"ff":{"name":"Fula; Fulah; Pulaar; Pular","nativeName":"Fulfulde, Pulaar, Pular"},"gl":{"name":"Galician","nativeName":"Galego"},"ka":{"name":"Georgian","nativeName":"ქართული"},"de":{"name":"German","nativeName":"Deutsch"},"gr":{"name":"Greek","nativeName":"Ελληνικά"},"el":{"name":"Greek, Modern","nativeName":"Ελληνικά"},"gn":{"name":"Guaraní","nativeName":"Avañeẽ"},"gu":{"name":"Gujarati","nativeName":"ગુજરાતી"},"ht":{"name":"Haitian; Haitian Creole","nativeName":"Kreyòl ayisyen"},"ha":{"name":"Hausa","nativeName":"Hausa, هَوُسَ"},"he":{"name":"Hebrew (modern)","nativeName":"עברית"},"hz":{"name":"Herero","nativeName":"Otjiherero"},"hi":{"name":"Hindi","nativeName":"हिन्दी, हिंदी"},"ho":{"name":"Hiri Motu","nativeName":"Hiri Motu"},"hu":{"name":"Hungarian","nativeName":"Magyar"},"ia":{"name":"Interlingua","nativeName":"Interlingua"},"id":{"name":"Indonesian","nativeName":"Bahasa Indonesia"},"ie":{"name":"Interlingue","nativeName":"Originally called Occidental; then Interlingue after WWII"},"ga":{"name":"Irish","nativeName":"Gaeilge"},"ig":{"name":"Igbo","nativeName":"Asụsụ Igbo"},"ik":{"name":"Inupiaq","nativeName":"Iñupiaq, Iñupiatun"},"io":{"name":"Ido","nativeName":"Ido"},"is":{"name":"Icelandic","nativeName":"Íslenska"},"it":{"name":"Italian","nativeName":"Italiano"},"iu":{"name":"Inuktitut","nativeName":"ᐃᓄᒃᑎᑐᑦ"},"ja":{"name":"Japanese","nativeName":"日本語 (にほんご／にっぽんご)"},"jv":{"name":"Javanese","nativeName":"basa Jawa"},"kl":{"name":"Kalaallisut, Greenlandic","nativeName":"kalaallisut, kalaallit oqaasii"},"kn":{"name":"Kannada","nativeName":"ಕನ್ನಡ"},"kr":{"name":"Kanuri","nativeName":"Kanuri"},"ks":{"name":"Kashmiri","nativeName":"कश्मीरी, كشميري‎"},"kk":{"name":"Kazakh","nativeName":"Қазақ тілі"},"km":{"name":"Khmer","nativeName":"ភាសាខ្មែរ"},"ki":{"name":"Kikuyu, Gikuyu","nativeName":"Gĩkũyũ"},"rw":{"name":"Kinyarwanda","nativeName":"Ikinyarwanda"},"ky":{"name":"Kirghiz, Kyrgyz","nativeName":"кыргыз тили"},"kv":{"name":"Komi","nativeName":"коми кыв"},"kg":{"name":"Kongo","nativeName":"KiKongo"},"ko":{"name":"Korean","nativeName":"한국어 (韓國語), 조선말 (朝鮮語)"},"ku":{"name":"Kurdish","nativeName":"Kurdî, كوردی‎"},"kj":{"name":"Kwanyama, Kuanyama","nativeName":"Kuanyama"},"la":{"name":"Latin","nativeName":"latine, lingua latina"},"lb":{"name":"Luxembourgish, Letzeburgesch","nativeName":"Lëtzebuergesch"},"lg":{"name":"Luganda","nativeName":"Luganda"},"li":{"name":"Limburgish, Limburgan, Limburger","nativeName":"Limburgs"},"ln":{"name":"Lingala","nativeName":"Lingála"},"lo":{"name":"Lao","nativeName":"ພາສາລາວ"},"lt":{"name":"Lithuanian","nativeName":"lietuvių kalba"},"lu":{"name":"Luba-Katanga","nativeName":""},"lv":{"name":"Latvian","nativeName":"latviešu valoda"},"gv":{"name":"Manx","nativeName":"Gaelg, Gailck"},"mk":{"name":"Macedonian","nativeName":"македонски јазик"},"te":{"name":"Tetun","nativeName":"Tetun"},"mg":{"name":"Malagasy","nativeName":"Malagasy fiteny"},"ms":{"name":"Malay","nativeName":"bahasa Melayu, بهاس ملايو‎"},"ml":{"name":"Malayalam","nativeName":"മലയാളം"},"mt":{"name":"Maltese","nativeName":"Malti"},"mi":{"name":"Māori","nativeName":"te reo Māori"},"mr":{"name":"Marathi (Marāṭhī)","nativeName":"मराठी"},"mh":{"name":"Marshallese","nativeName":"Kajin M̧ajeļ"},"mn":{"name":"Mongolian","nativeName":"монгол"},"na":{"name":"Nauru","nativeName":"Ekakairũ Naoero"},"nv":{"name":"Navajo, Navaho","nativeName":"Diné bizaad, Dinékʼehǰí"},"nb":{"name":"Norwegian Bokmål","nativeName":"Norsk bokmål"},"nd":{"name":"North Ndebele","nativeName":"isiNdebele"},"ne":{"name":"Nepali","nativeName":"नेपाली"},"ng":{"name":"Ndonga","nativeName":"Owambo"},"nn":{"name":"Norwegian Nynorsk","nativeName":"Norsk nynorsk"},"no":{"name":"Norwegian","nativeName":"Norsk"},"ii":{"name":"Nuosu","nativeName":"ꆈꌠ꒿ Nuosuhxop"},"nr":{"name":"South Ndebele","nativeName":"isiNdebele"},"oc":{"name":"Occitan","nativeName":"Occitan"},"oj":{"name":"Ojibwe, Ojibwa","nativeName":"ᐊᓂᔑᓈᐯᒧᐎᓐ"},"cu":{"name":"Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic","nativeName":"ѩзыкъ словѣньскъ"},"om":{"name":"Oromo","nativeName":"Afaan Oromoo"},"or":{"name":"Oriya","nativeName":"ଓଡ଼ିଆ"},"os":{"name":"Ossetian, Ossetic","nativeName":"ирон æвзаг"},"pa":{"name":"Panjabi, Punjabi","nativeName":"ਪੰਜਾਬੀ, پنجابی‎"},"pi":{"name":"Pāli","nativeName":"पाऴि"},"fa":{"name":"Persian","nativeName":"فارسی"},"pl":{"name":"Polish","nativeName":"polski"},"ps":{"name":"Pashto, Pushto","nativeName":"پښتو"},"pt":{"name":"Portuguese","nativeName":"Português"},"qu":{"name":"Quechua","nativeName":"Runa Simi, Kichwa"},"rm":{"name":"Romansh","nativeName":"rumantsch grischun"},"rn":{"name":"Kirundi","nativeName":"kiRundi"},"ro":{"name":"Romanian, Moldavian, Moldovan","nativeName":"română"},"ru":{"name":"Russian","nativeName":"русский язык"},"sa":{"name":"Sanskrit (Saṁskṛta)","nativeName":"संस्कृतम्"},"sc":{"name":"Sardinian","nativeName":"sardu"},"sd":{"name":"Sindhi","nativeName":"सिन्धी, سنڌي، سندھی‎"},"se":{"name":"Northern Sami","nativeName":"Davvisámegiella"},"sm":{"name":"Samoan","nativeName":"gagana faa Samoa"},"sg":{"name":"Sango","nativeName":"yângâ tî sängö"},"sr":{"name":"Serbian","nativeName":"српски језик"},"gd":{"name":"Scottish Gaelic; Gaelic","nativeName":"Gàidhlig"},"sn":{"name":"Shona","nativeName":"chiShona"},"si":{"name":"Sinhala, Sinhalese","nativeName":"සිංහල"},"sk":{"name":"Slovak","nativeName":"slovenčina"},"sl":{"name":"Slovene","nativeName":"slovenščina"},"so":{"name":"Somali","nativeName":"Soomaaliga, af Soomaali"},"st":{"name":"Southern Sotho","nativeName":"Sesotho"},"es":{"name":"Spanish; Castilian","nativeName":"español, castellano"},"su":{"name":"Sundanese","nativeName":"Basa Sunda"},"sw":{"name":"Swahili","nativeName":"Kiswahili"},"ss":{"name":"Swati","nativeName":"SiSwati"},"sv":{"name":"Swedish","nativeName":"svenska"},"ta":{"name":"Tamil","nativeName":"தமிழ்"},"tg":{"name":"Tajik","nativeName":"тоҷикӣ, toğikī, تاجیکی‎"},"th":{"name":"Thai","nativeName":"ไทย"},"ti":{"name":"Tigrinya","nativeName":"ትግርኛ"},"bo":{"name":"Tibetan Standard, Tibetan, Central","nativeName":"བོད་ཡིག"},"tk":{"name":"Turkmen","nativeName":"Türkmen, Түркмен"},"tl":{"name":"Tagalog","nativeName":"Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔"},"tn":{"name":"Tswana","nativeName":"Setswana"},"to":{"name":"Tonga (Tonga Islands)","nativeName":"faka Tonga"},"tr":{"name":"Turkish","nativeName":"Türkçe"},"ts":{"name":"Tsonga","nativeName":"Xitsonga"},"tt":{"name":"Tatar","nativeName":"татарча, tatarça, تاتارچا‎"},"tw":{"name":"Twi","nativeName":"Twi"},"ty":{"name":"Tahitian","nativeName":"Reo Tahiti"},"ug":{"name":"Uighur, Uyghur","nativeName":"Uyƣurqə, ئۇيغۇرچە‎"},"uk":{"name":"Ukrainian","nativeName":"українська"},"ur":{"name":"Urdu","nativeName":"اردو"},"uz":{"name":"Uzbek","nativeName":"zbek, Ўзбек, أۇزبېك‎"},"ve":{"name":"Venda","nativeName":"Tshivenḓa"},"vi":{"name":"Vietnamese","nativeName":"Tiếng Việt"},"vo":{"name":"Volapük","nativeName":"Volapük"},"wa":{"name":"Walloon","nativeName":"Walon"},"cy":{"name":"Welsh","nativeName":"Cymraeg"},"wo":{"name":"Wolof","nativeName":"Wollof"},"fy":{"name":"Western Frisian","nativeName":"Frysk"},"xh":{"name":"Xhosa","nativeName":"isiXhosa"},"yi":{"name":"Yiddish","nativeName":"ייִדיש"},"yo":{"name":"Yoruba","nativeName":"Yorùbá"},"za":{"name":"Zhuang, Chuang","nativeName":"Saɯ cueŋƅ, Saw cuengh"}}', true);

    $v = $niceName[$l]['nativeName'];

    $multiple = explode(',', $v);

    return ucfirst($multiple[0]);
}

add_shortcode( 'wpapp_lang_selector', 'wpapp_lang_selector' );
function wpapp_lang_selector() {

	$locale = wpappninja_get_lang("long");

	$html = '<!-- Locale Switch -->';

	$langs = wpappninja_available_lang();
	$locale_n = $locale;
	$k = substr($locale, 0, 2);
	
	foreach ( $langs as $l => $ll) {
		if ($ll == $k) {
			$locale_n = $l;
		}
	}

	$html .= '<p><span style="padding: 15px 0;line-height: initial;margin: 15px 0;height: auto;" onclick="wpmobile_locale_switcher.open();" class="panel-close button"><img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$k.'.gif" /> ' . wpmobile_get_nativename($k) . '</span></p>';


	$html .= '<script>
    var wpmobile_locale_switcher = "";
    jQuery(function() {
        wpmobile_locale_switcher = app.actions.create({
        buttons: [';
        foreach ($langs as $n => $l) {
            $html .= '{
              text: \'<img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$l.'.gif" /> '.wpmobile_get_nativename($l).'\',
              onClick: function () {
                document.location = \''.home_url( '' ) . '?redirect='.base64_encode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']).'&WPMOBILE_LOCALE='.$l.'&rand='.uniqid().'\';
              }
            },';
        }
        
        $html .= '{
            text: \''.__('Cancel', 'wpappninja').'\',
            color: \'red\',
        },';
        $html .= ']
        });
    });
    </script>';
	
	return $html;
}

add_shortcode( 'wpapp_category', 'wpapp_category' );
function wpapp_category() {

	$tags = "";
	$posttags = get_the_category();
	if ($posttags) {
	  foreach($posttags as $tag) {
	    //$tags .= '<a href="' . get_category_link($tag->term_id) . '">';
	    $tags .= '<div class="chip"><div class="chip-label">' . $tag->name . '</div></div>';
	    //$tags .= '</a>'; 
	  }
	}

	if ($tags == "") {
		return;
	}

	return '<!-- Category -->
	<div class="wpmobile-category">' . $tags . '</div>';
}

add_shortcode( 'wpapp_tags', 'wpapp_tags' );
function wpapp_tags() {

	$tags = "";
	$posttags = get_the_tags();
	if ($posttags) {
	  foreach($posttags as $tag) {
	    //$tags .= '<a href="' . get_tag_link($tag->term_id) . '">';
	    $tags .= '<div class="chip"><div class="chip-label">' . $tag->name . '</div></div>';
	    //$tags .= '</a>'; 
	  }
	}

	if ($tags == "") {
		return;
	}

	return '<!-- Tags -->
	<div class="wpmobile-tags">' . $tags . '</div>';
}

add_shortcode( 'wpapp_ads', 'wpapp_ads' );
function wpapp_ads() {
	return '<!-- Ads -->
	' . wpappninja_get_ads('top');
}

add_shortcode( 'wpapp_push', 'wpapp_push' );
function wpapp_push() {

	$html = '<p><a style="padding: 15px 0;line-height: initial;margin: 15px 0;height: auto;" class="button" href="'.home_url( '' ).'/?wpapp_shortcode=wpapp_config"><i class="icon f7-icons">gear</i> ' . __('Push settings', 'wpappninja') . '</a></p>';

	return $html;
}

add_shortcode( 'wpapp_config', 'wpapp_config' );
function wpapp_config() {

	global $wpdb;

	$cookieid = (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) ? $_COOKIE['HTTP_X_WPAPPNINJA_ID'] : "");
	$user_bdd_id_check = $wpdb->get_row($wpdb->prepare("SELECT device_id FROM {$wpdb->prefix}wpappninja_ids as a JOIN {$wpdb->prefix}wpappninja_push_perso as b ON a.device_id = b.user_id WHERE b.id = %s", $cookieid));

	$category = array_filter( explode(',', get_wpappninja_option('push_category', '')));

	$user_id = (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) ? $_COOKIE['HTTP_X_WPAPPNINJA_ID'] : "");

	$html = '<!-- Push config -->';

	if (isset($_POST['enablewpapppush'])) {
		$user_category = "";
		if (isset($_POST['wpapp_category']) && is_array($_POST['wpapp_category'])) {
			$user_category = implode(',', $_POST['wpapp_category']);
		}
		
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push_perso SET `category` = %s WHERE `id` = %s", $user_category, $user_id));

		$html .= '<script type="text/javascript">jQuery(function() {app.dialog.alert(\''.__('Settings saved', 'wpappninja').'\',\''.__('Push notification', 'wpappninja').'\');});</script>';
	}

	$user_settings = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $user_id));

	if (!isset($user_bdd_id_check->device_id)) {
		$checked = "";
		$displaynone = "none";
	} else {
		$checked = "checked";
		$displaynone = "block";
	}

	$html .= '<script>function wpmobileshowpush(state) {

		';

		if (!isset($_GET['wpappninja_simul4'])) {



		$html .= 'jQuery(\'.wpmobilehandlerpush\').remove();

		setTimeout(function(){
		jQuery(\'body\').append(\'<a href="'.home_url( '' ).'/?wpapppushconfig=1" class="wpmobilehandlerpush"></a>\');
        jQuery(\'.wpmobilehandlerpush\')[0].click();}, 200);';
		}

		$html .= '}</script>

        <a style="padding: 15px 0;line-height: initial;margin: 15px 0;height: auto;" class="button" href="/?wpapppushconfig=1"><i class="icon f7-icons">gear</i> ' . __('Manage notifications', 'wpappninja') . '</a>


		<form action="" method="post"><div class="list simple-list">
		';


	//$html .= '<p><a style="padding: 15px 0;line-height: initial;margin: 15px 0;height: auto;" class="button" href="?wpapppushconfig=1"><i class="icon f7-icons">gear</i> ' . __('Push settings', 'wpappninja') . '</a></p>';

	  $html .= "<div id='pushctabis'>";
	if (count($category) > 0) {

		$html .= '<ul>
		<input type="hidden" name="enablewpapppush" value="1" />';

		foreach ($category as $c) {

			$c = trim($c);

    		$html .= '<li>

    		      <span>' . $c . '</span>
      <label class="toggle toggle-init">
        <input type="checkbox" name="wpapp_category[]" value="' . $c . '" ';

	        	if (preg_match('#' . $c . '#', $user_settings->category)) {$html .= 'checked';}

	        	$html .= ' />
        <span class="toggle-icon"></span>
      </label>


	   		</li>';

		}
	
		$html .= '</ul><br/><br/><input type="submit" class="button" value="' . __('Save', 'wpappninja') . '" />';
	}
	$html .= "</div>";

	$html .= "</form></div></div>";
	
	return $html;
}


add_shortcode( 'wpapp_search', 'wpapp_search' );
function wpapp_search() {
	return '<!-- Search Bar -->
	<div class="ios wpapp_navbar_search">
	<form class="searchbar" method="get" action="' . home_url( '/' ) . '">
	  <div class="searchbar-inner">
	    <div class="searchbar-input-wrap">
	      <input type="search" placeholder="' . __('Search', 'wpappninja') . '" name="s">
	      <i class="searchbar-icon"></i>
	      <span class="input-clear-button"></span>
	    </div>
	    <span class="searchbar-disable-button">' . __('Cancel', 'wpappninja') . '</span>
	  </div>
	</form>

	<!-- Search Bar overlay-->
	<div class="searchbar-overlay"></div>
	</div>';
}

add_shortcode( 'wpapp_image', 'wpapp_image' );
function wpapp_image() {

	$content_post = get_post();

	if (isset($content_post->ID)) {
		$image = wpappninja_get_image($content_post->ID, false, true, "0");

		if ($image != "") {
			return '<!-- Image -->
			<img src="' . $image . '" class="hero" />';
		}
	}
}


add_shortcode( 'wpapp_image_small', 'wpapp_image_small' );
function wpapp_image_small() {

	$content_post = get_post();
	$image = wpappninja_get_image($content_post->ID, false, true, "0");

	$html = "<div>";

	if ($image != "") {
		$html .= '<!-- Image -->
		<div class="wpappninja_small_card_image" style="background:url(' . $image . ') center center no-repeat;background-size:cover;width: 130px;height:90px;float:left;"></div>';
	}

	$html .= "<div class='wpappninja_small_card_title' style='width: Calc(100% - 160px);float:left;margin-left: 15px;'><h4 style='margin:0'>" . $content_post->post_title . "</h4></div>";

	$html .= '<div style="clear:both"></div></div>';

	return $html;
}

add_shortcode( 'wpapp_author', 'wpapp_author' );
function wpapp_author() {

	$content_post = get_post();

	if (!isset($content_post->post_author)) {
		return;
	}

	$authorAvatar = wpappninja_get_gravatar(get_the_author_meta('user_email', $content_post->post_author));

	$html = '<!-- Author -->
	<div class="card-author">';
		if ($authorAvatar != "") {
			$html .= '<div class="wpappninja-avatar"><img src="' . $authorAvatar . '" width="34" height="34"></div>';
		}
		$html .= '<div class="wpappninja-name">' . get_the_author_meta('display_name', $content_post->post_author) . '</div>
		<div class="wpappninja-date">' . wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date)) . '</div>
	</div>';

	return $html;
}

add_shortcode( 'wpapp_comment_number', 'wpapp_comment_number' );
function wpapp_comment_number() {

    $content_post = get_post();
   
    return '<!-- Number of comments -->
    <span class="badge">' . get_comments_number($content_post->ID) . '</span>';
}

add_shortcode( 'wpapp_title', 'wpapp_title' );
function wpapp_title() {

    /*if (isset($_GET['s'])) {
        return '<!-- Title -->
        <h2 class="wpmobile-title">' . sprintf(__( 'Search Results for &#8220;%s&#8221;', 'wpappninja'), get_search_query()) . '</h2>';
    }*/

    $content_post = get_post();

    if (!isset($content_post->post_title) || $content_post->post_title == "") {

        return;
    }
    
    return '<!-- Title -->
    <h2 class="wpmobile-title">' . $content_post->post_title . '</h2>';
}

add_shortcode( 'wpapp_title_main', 'wpapp_title_main' );
function wpapp_title_main() {

    return '<!-- Title -->
    <h2 class="wpmobile-title">' . trim(wp_title( '', false, 'right' )) . '</h2>';
}

add_shortcode( 'wpapp_comment', 'wpapp_comment' );
function wpapp_comment() {

	ob_start();
    comments_template();
    return '<!-- Comments -->' . ob_get_clean();
}

add_shortcode( 'wpapp_excerpt', 'wpapp_excerpt' );
function wpapp_excerpt() {

	$content_post = get_post();
	
	return '<!-- Excerpt -->
	' . get_the_excerpt($content_post);
}

add_shortcode( 'wpapp_similar', 'wpapp_similar' );
function wpapp_similar() {

	$content_post = get_post();

	$similar = "";

	$terms = wp_get_post_terms( $content_post->ID, get_wpappninja_option('similartype', 'category'), array('fields' => 'ids') );
	$args = array(

		'posts_per_page' => get_wpappninja_option('similarnb', 10),
        'tax_query' => array(
			array(
				'taxonomy' => get_wpappninja_option('similartype', 'category'),
				'terms' => $terms,
				'include_children' => false
			)
		),
		'post__not_in' => array( $content_post->ID ),
		'offset' => 0,
		'post_type' => get_post_types(array('public'=>true))
			);
	$args = wpappninja_get_list_arg($args);

	ob_start();
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {
		while ($my_query->have_posts()) : $my_query->the_post();
			$post = get_post();
			wpappninja_show_card($post);
		endwhile;
	}
	wp_reset_query();
	$similar = ob_get_clean();

	if ($similar == "") {
		return;
	}

	return '<!-- Similars -->' . $similar;
}

add_shortcode( 'wpapp_social', 'wpapp_social' );
function wpapp_social() {

	return '<a style="padding: 15px 0;line-height: initial;margin: 15px 0;height: auto;" href="' . get_home_url() . '/?wpmobileshareme" class="button"><i class="icon f7-icons">share</i> ' . __('Share', 'wpappninja') . '</a>';
}

add_shortcode( 'wpapp_share', 'wpapp_share' );
function wpapp_share($atts) {

	$content_post = get_post();

    $a = shortcode_atts( array(
        'network' => ''
    ), $atts );

	$url 	= get_permalink();
	$text 	= urlencode($content_post->post_title);
	$image 	= wpappninja_get_image($content_post->ID, false, true, "0");

    switch ($a['network']) {

		case 'facebook':
			$link = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
			break;

		case 'google':
			$link = 'https://plus.google.com/share?url=' . $url;
			break;

		case 'twitter':
			$link = 'https://twitter.com/intent/tweet?text=' . $text . '+' . $url;
			break;

		case 'linkedin':
			$link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url;
			break;

		case 'pinterest':
			$link = 'http://pinterest.com/pin/create/button/?description=' . $text . '&media=' . $image . '&url=' . $url;
			break;

		case 'reddit':
			$link = 'https://reddit.com/submit?url=' . $url;
			break;

		case 'digg':
			$link = 'https://digg.com/submit?url=' . $url;
			break;

    }


    return '<a href="' . $link . '" rel="nofollow" target="_blank" class="socialninja socialninja_' . $a['network'] . '"><i class="icon f7-icons">social_' . $a['network'].'_fill</i></a>';
}

add_action('init', 'wpmobile_set_cookie_push');
function wpmobile_set_cookie_push() {
	if ((isset($_GET['wpapp_shortcode']) && $_GET['wpapp_shortcode'] == 'wpapp_history') || isset($_GET['wpmobile_from_push'])) {
		setcookie( "wpmobile_last_seen", strtolower(current_time( 'timestamp' )), time() + 300 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
		//$_COOKIE['wpmobile_last_seen'] = strtolower(current_time( 'timestamp' ));
	}
}


add_filter('wpmobile_push_id', 'wpmobileapp_add_user_for_push');
function wpmobileapp_add_user_for_push($array) {
    if (is_wpappninja()) {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();

            if (!is_array($array)) {
                $array = array();
            }

            if ($current_user->user_email != "") {
                $array[] = $current_user->user_email;
                $array[] = '@';

                foreach ($current_user->roles as $v => $role) {
                    $array[] = 'role___' . $role;
                }
            }
        }
    }

    return $array;
}


add_shortcode( 'wpapp_history', 'wpapp_history' );
function wpapp_history() {
	global $wpdb;

	$user_id = (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) ? $_COOKIE['HTTP_X_WPAPPNINJA_ID'] : "");

	$user_settings = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $user_id));

	if (!isset($user_settings)) {
		$user_settings = new stdClass();
	}

	$user_category = explode(',', $user_settings->category);

	$user_category = apply_filters('wpmobile_push_id', $user_category);

	$like_prepare = " AND (category = %s";
	$like_term = array();
	$like_term[] = wpappninja_get_lang();
	$like_term[] = '1';
	$like_term[] = '';
	foreach ($user_category as $c) {
		$like_prepare .= " OR category LIKE %s";
		$like_term[] = $c;
	}
	$like_term[] = 0;
	$like_term[] = 100;
	$like_prepare .= ')';

	$last_read = $_COOKIE['wpmobile_last_seen'];

	$html = '<!-- Push history -->
	<style>.list ul.wpmobile_list_history:before, .list ul.wpmobile_list_history:after {
    content: none!important;
	}</style>
	<div class="list media-list">
	  <ul class="wpmobile_list_history">';

	$number_push = 0;
	
	$query = $wpdb->get_results($wpdb->prepare("SELECT `id`, `id_post`, `titre`, `message`, `image`, `send_date`, `sended`, `log`, `lang` FROM {$wpdb->prefix}wpappninja_push WHERE (lang = %s OR lang = 'all') AND `sended` = %s " . $like_prepare . " ORDER BY `send_date` DESC LIMIT %d,%d", $like_term));

	$avant = __('%s ago', 'wpappninja');
	foreach($query as $obj) {

			$number_push++;

			$permalink = false;
			if ($obj->id_post > 0) {
				$permalink = get_permalink($obj->id_post);
			}

			if (preg_match('#^http#', $obj->id_post)) {
				$permalink = $obj->id_post;
			}


			$html .= '<!-- Timeline item -->';
    		
    		if ($permalink) {
				$html .= '<a href="' . $permalink . '" style="color:initial">';
			}

			if ($obj->send_date > $last_read) {
	   			$html .= '<li style="border-left: 5px solid;border-bottom: 1px solid #eee;font-weight: 800;">';
	   		} else {
	   			$html .= '<li style="border-left: 5px solid #fff;border-bottom: 1px solid #eee;">';
	   		}

   			$html .= '<div class="item-content">';

		    if ($obj->image != "" && $obj->image != " ") {
		        $html .= '<div class="item-media">
		        	<img src="' . $obj->image . '" width="44" />
			    </div>';
			}
            $html .= '<div class="item-inner">
              <div class="item-title-row">
                <div class="item-title"><b>' . stripslashes($obj->titre) . '</b></div>
              </div>
              <div class="item-subtitle" style="white-space: initial;">
                ' . stripslashes($obj->message) . '
                <br/>
                <span style="color:gray;font-size:10px;">' . sprintf($avant, human_time_diff( $obj->send_date, current_time('timestamp') )) . '</span>
              </div>
            </div>
            
            </div>';

		    $html .= '</li>';
      		if ($permalink) {
				$html .= '</a>';
			}
	}

	$html .= '</ul></div>';

	if ($number_push == 0) {
		$html .= '<center><h1><i class="f7-icons" style="font-size: 60px;color: #b7b7b7;">chats</i></h1>
		'.__('No information for now.', 'wpappninja').'</center>';
	}

	return $html;
}

add_shortcode('wpmobile_qrcode_2', 'wpmobile_qrcode_2');
function wpmobile_qrcode_2() {
               
    $html = '<script>
    function wpmobile_qrcode() {
               try{wpmobileapp.qrscanner();} catch(err) {}
               try{window.webkit.messageHandlers.wpmobile.postMessage(\'qrscanner\');} catch(err) {}
    }
               
    function wpmobile_qrcode_result(result) {
                                                                                 
               '.apply_filters('wpmobile_qrcode_event', '').'

               if (result == "") {
                          app.dialog.alert("'.__('No result', 'wpappninja').'", "'.__('Scanner', 'wpappninja').'");
                          return;
               }
                                                                                                           
               if (result.startsWith("http")) {
                                                                      wpmobileappStartLoading();
                          document.location = result;
               } else {
                          app.dialog.alert(result, "'.__('Scanner', 'wpappninja').'");
               }
    }
    </script>';

    $html .= '<span onclick="wpmobile_qrcode();" class="button" class="wpmobile_qrcode"><i class="icon f7-icons">qrcode</i></span>';

    return $html;
}
                                                                      
add_shortcode('wpmobile_qrcode', 'wpmobile_qrcode');
function wpmobile_qrcode() {
    
    $html = "";
    
    if (!defined('WPMOBILEQRCODESCRIPTS')) {

    define('WPMOBILEQRCODESCRIPTS', 'TRUE');

    $html .= '<script src="'.WPAPPNINJA_ASSETS_JS_URL.'qrcode/qrcode.js"></script>
    <script src="'.WPAPPNINJA_ASSETS_JS_URL.'qrcode/init.js"></script>

    <script>
    jQuery(function() {
    var qr = new QCodeDecoder();
    jQuery(".wpmobile_qr_upload").change(function (evt) {

        var tgt = evt.target || window.event.srcElement,
            files = tgt.files;

        if (FileReader && files && files.length) {
        
            var fr = new FileReader();
            fr.onload = function () {

                qr.decodeFromImage(fr.result, function (err, result) {

                  '.apply_filters('wpmobile_qrcode_event', '').'

                  if (err) {
                    app.dialog.alert(err, "Scan");
                  } else {

                    if (result.startsWith("http")) {
                        document.location = result;
                    } else {
                        app.dialog.alert(result, "Scan");
                    }
                  }
                });
            }
            fr.readAsDataURL(files[0]);
        }
    });
    });
    </script>';
    }

    $html .= '<label>
        <span class="button"><i class="icon f7-icons">qrcode</i></span>
        <input type="file" accept="image/*" style="display:none" capture="camera" class="wpmobile_qr_upload" />
    </label>';

    return $html;
}

add_shortcode( 'wpapp_qrcode', 'wpapp_qrcode' );
function wpapp_qrcode() {
	
	return '<p><a href="'.home_url( '' ).'/?wpappqrcode=1" style="padding: 15px 0;line-height: initial;margin: 15px 0;height: auto;" class="button"><i class="icon f7-icons">camera</i> ' . __('Scan code', 'wpappninja') . '</a></p>';
}

function wpappninja_home_cmp($a, $b) {
    return @strcmp($a->taxonomy . $a->name, $b->taxonomy. $b->name);
}


add_shortcode('wpmobileapp_author', '_shortcode_wpmobileapp_author');
function _shortcode_wpmobileapp_author() {
    
    $content_post = get_post();
    return "<span class='wpmobileapp_shortcode wpapp_author'>".get_the_author_meta('display_name', $content_post->post_author)."</span>";
}

add_shortcode('wpmobileapp_date', '_shortcode_wpmobileapp_date');
function _shortcode_wpmobileapp_date() {

    $content_post = get_post(); 
    return "<span class='wpmobileapp_shortcode wpapp_date'>".wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date))."</span>";
}

add_shortcode('wpmobileapp_category', '_shortcode_wpmobileapp_category');
function _shortcode_wpmobileapp_category() {

    $content_post = get_post();
    $categories = get_the_category($content_post->ID);
    return "<span class='wpmobileapp_shortcode wpapp_category'>".$categories[0]->name."</span>";
}

add_shortcode( 'wpapp_home_configure', 'wpapp_home_configure' );
function wpapp_home_configure() {

	$category = get_wpappninja_option('home_available', array());
	$pages = wpappninja_get_pages();

	global $wpdb;
	$user_id = (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) ? $_COOKIE['HTTP_X_WPAPPNINJA_ID'] : "");

	$html = '<!-- Home settings -->';

	if (isset($_POST['wpapp_category_check'])) {
		$user_category = "";
		if (is_array($_POST['wpapp_category'])) {
			$user_category = implode(',', $_POST['wpapp_category']);
		}

		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_home_perso (category,id) VALUES (%s,%s) ON DUPLICATE KEY UPDATE category = %s", $user_category, $user_id, $user_category));

		$html .= '<script type="text/javascript">jQuery(function() {app.dialog.alert(\''.__('Settings saved', 'wpappninja').'\',\''.__('My home', 'wpappninja').'\');});</script>';

	}

	$user_settings = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_home_perso WHERE `id` = %s", $user_id));

	if (!isset($user_settings)) {
		$user_settings = new stdClass();
	}

	if (!isset($user_settings->category) || $user_settings->category == "") {
		$user_settings->category = implode(',',$category);
	}

	$html .= '<p><span style="padding: 15px 0;line-height: initial;margin: 15px 20px!important;width: Calc(100% - 40px)!important;height: auto;" data-popup=".popup-home" class="popup-open button"><i class="icon f7-icons">gear</i> ' . __('My home', 'wpappninja') . '</span></p>';

	global $wpappninja_popup;
	$wpappninja_popup .= '<div class="popup popup-home">
    <div class="block" style="background:white;padding:0">

	<form action="" method="post">
	<input type="hidden" name="wpapp_category_check" value="1" />

	<div class="list">
	<ul>';

	$user_category = explode(',', $user_settings->category);

	usort($category, "wpappninja_home_cmp");

	$_taxname = "";
	foreach ($category as $cc) {

		$t = explode('|',$cc);
		$c = get_term($t[1], $t[0]);

		$term = get_taxonomy($c->taxonomy);
		$taxname = $term->label;
		if ($taxname != $_taxname) {
			$_taxname = $taxname;
			$wpappninja_popup .= "</ul><h4 
    style='padding: 13px 0px 0 40px!important;
    font-size: 18px;
    margin-bottom: 11px;'>" . $taxname . "</h4><ul>";
		}

		if ($c->parent == 0) {

		$icon = wpappninja_auto_select_icon($c->name);
		foreach($pages as $p) {
			if ($p['id'] == get_term_link($c->term_id, $c->taxonomy)) {
				$icon = $p['icon'];
			}
		}

    	$wpappninja_popup .= '<li>
      		<label class="item-checkbox item-content"  style="';if (in_array($c->taxonomy . '|' . $c->term_id, $user_category)) {$wpappninja_popup.='color:white;';}$wpappninja_popup .= 'background:#fff;';if (in_array($c->taxonomy . '|' . $c->term_id, $user_category)) { $wpappninja_popup .= 'background:'.get_wpappninja_option('css_0c5c5bf1fda47e5230fff4396a1f8779', '#f5f5f5').';'; }$wpappninja_popup .= '">
	        <input type="checkbox" name="wpapp_category[]" value="' . $c->taxonomy . '|' . $c->term_id . '" ';

	        if (in_array($c->taxonomy . '|' . $c->term_id, $user_category)) {
				$wpappninja_popup .= 'checked';
			}

	        $wpappninja_popup .= ' />';

    	    $wpappninja_popup .= '
        	<div class="item-inner">
          		<div class="item-title"><i class="icon f7-icons">'.$icon.'</i>' . $c->name . '</div>
        	</div>
      		</label>
	   	</li>';

	   }

	}
	
	$wpappninja_popup .= '</ul></div>';

	$wpappninja_popup .= '<p style="padding:0 35px"><input type="submit" class="button" value="' . __('Save', 'wpappninja') . '" /></p>';

	$wpappninja_popup .= '</form><br/><br/></div></div>';

	$wpappninja_popup .= '<style>.item-checkbox i.icon.f7-icons {
    width: 28px;
    text-align: center;
    padding: 0 15px 0 0;
}.item-checkbox .item-media {
    display: none;
	}</style><script>jQuery("input[type=\'checkbox\']").change(function(){
    if(jQuery(this).is(":checked")){
        jQuery(this).parent().css("background", "'.get_wpappninja_option('css_0c5c5bf1fda47e5230fff4396a1f8779', '#f5f5f5').'");
        jQuery(this).parent().css("color", "white");
    }else{
        jQuery(this).parent().css("background", "#fff"); 
        jQuery(this).parent().css("color", "#212121");
    }
});</script>';

	return $html;
}

add_shortcode( 'wpapp_home', 'wpapp_home' );
function wpapp_home() {

	global $wpdb;
	$user_id = (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) ? $_COOKIE['HTTP_X_WPAPPNINJA_ID'] : "");
	$category = get_wpappninja_option('home_available', array());

	$wpapp_home_configure = wpapp_home_configure();

	$user_settings = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_home_perso WHERE `id` = %s", $user_id));

	if (!isset($user_settings)) {
		$user_settings = new stdClass();
	}
	
	if ($user_settings->category == "") {
		$user_settings->category = implode(',',$category);
	}

	/** get and display posts **/
	$similar = "";

	$user_category = explode(',', $user_settings->category);
	
	$similar .= "<div class='wpappninja_home_break'>";


	if (get_wpappninja_option('home_type') != 'list') {
	$firstPosts = array();
	$emptyhome = true;
	foreach ($user_category as $cc) {

		

		$t = explode('|',$cc);
		$c = get_term($t[1], $t[0]);

		if ($c->parent == 0) {


	        	$similar .= "<div class='home_wpapp'>";
				$args = array(
					'post__not_in' => $firstPosts,
					'posts_per_page' => 3,
					'offset' => 0,
					'post_type' => get_post_types(array('public'=>true)),
					'tax_query' => array(array(
							'taxonomy' => $c->taxonomy,
							'terms' => $c->term_id,
							'include_children' => true
						))
				);
				$args = wpappninja_get_list_arg($args);
				$titlesetted = false;
				ob_start();
				$my_query = new WP_Query($args);
				if( $my_query->have_posts() ) {
					while ($my_query->have_posts()) : $my_query->the_post();

						$emptyhome = false;
 
						if (!$titlesetted) {
							$titlesetted = true;
							if (isset($c->name) && isset($c->taxonomy) && $c->name != ""){
								echo '<div onclick="document.location=\''.get_term_link($c->slug, $c->taxonomy).'\';" class="title-speed">' . $c->name . '</div>';
							}
						}
						$firstPosts[] = get_the_ID();
						$post = get_post();
						wpappninja_show_card($post);
					endwhile;
				} 

				wp_reset_query();
				$similar .= ob_get_clean();
				$similar .= "</div>";
			
	   }
	}
	} else {

			$offset = 0;
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	}



	$home_posts = array();
	$home_posts['relation'] = 'OR';

	foreach ($user_category as $cc) {

		$t = explode('|',$cc);
		$c = get_term($t[1], $t[0]);

		if ($c->parent == 0) {

				$home_posts[] = array(
							'taxonomy' => $c->taxonomy,
							'terms' => $c->term_id,
							'include_children' => true
						);
	   }
	}



	        	$similar .= "<div class='home_wpapp'>";
				$args = array(
					'posts_per_page' => 10,
					'offset' => $offset,
					'post_type' => get_post_types(array('public'=>true)),
					'tax_query' => $home_posts
				);
				$args = wpappninja_get_list_arg($args);

				$titlesetted = true;
				ob_start();
				$my_query = new WP_Query($args);
				if( $my_query->have_posts() ) {
					while ($my_query->have_posts()) : $my_query->the_post();

						$emptyhome = false;
 
						if (!$titlesetted) {
							$titlesetted = true;
							if (get_wpappninja_option('titlespeed') == '1' && $c->name != ""){
								echo '<div onclick="document.location=\''.get_term_link($c->slug, $c->taxonomy).'\';" class="title-speed">' . $c->name . '</div>';
							}
						}
						$firstPosts[] = get_the_ID();
						$post = get_post();
						wpappninja_show_card($post);
					endwhile;

						?><div class="pagination">
	<?php
	$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$url = preg_replace('#&offset=[0-9]+#', '', $url);
	?>
	<a class="next page-numbers" href="<?php echo $url;?>&offset=<?php echo ($offset+intval(get_wpappninja_option('listnb', 10)));?>">Suivant »</a>	</div>

	</div><?php
				} 

				wp_reset_query();
				$similar .= ob_get_clean();
				$similar .= "</div>";
	}


	$similar .= "</div>";
	if ($emptyhome) {
		$similar .= '<div style="text-align:center;margin:50px auto"><h1><i class="f7-icons" style="font-size: 60px;color: #b7b7b7;">person</i></h1>
			<b>'.__('Hello!', 'wpappninja').'</b><br/>
			'.__('Configure your app homepage', 'wpappninja').'<br/></div>';
	}
	$similar .= "<style>.title-speed{background: ".get_wpappninja_option('css_0c5c5bf1fda47e5230fff4396a1f8779', '#333333').";
    color: white;
    text-transform: uppercase;
    border-radius: 6px;
    display: table;
    margin: 25px auto 0 auto;
    font-size: 11px;
    padding: 4px 20px;
    text-align: center;
    max-width: 40%;
    border: 1px solid white;
}
/*
.home_wpapp .card:nth-of-type(2) .wpappninja_small_card_image {
    width: 100%!important;
    height: 140px!important;
}
.home_wpapp .card:nth-of-type(2) .wpappninja_small_card_title {
    font-size: 18px;
    width: Calc(100% - 30px)!important;
    margin: 0!important;
    padding: 20px;
}
.home_wpapp .card:nth-of-type(2) p {
    line-height: 0;
    margin: 0!important;
    padding: 0!important;
}
.home_wpapp .card:nth-of-type(2) .card-content-inner {
    padding: 0!important;
}*/
.card-content h4 {
    font-size: 17px;
    padding: 8px 0 0;
}
.card-content-inner {
    padding: 1px 15px;
}.card-content h4 {
    font-size: 17px;
    padding: 8px 0 0;
}
</style>";

	if ($emptyhome) {
		$return = $similar.$wpapp_home_configure;
	} else {
		$return = $wpapp_home_configure . $similar;
	}

	return $return;
}

add_shortcode( 'wpapp_recent', 'wpapp_recent' );
function wpapp_recent() {

	$offset = 0;
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	}

	/** get and display posts **/
	$similar = "";

	$args = array(
		'posts_per_page' => intval(get_wpappninja_option('listnb', 10)),
		'offset' => $offset,
		'post_type' => 'post'
			);
	$args = wpappninja_get_list_arg($args);

	ob_start();
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {
		while ($my_query->have_posts()) {
			$my_query->the_post();
			$post = get_post();
			wpappninja_show_card($post);
		}


	?><div class="pagination">
	<?php
	$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$url = preg_replace('#&offset=[0-9]+#', '', $url);
	?>
	<a class="next page-numbers" href="<?php echo $url;?>&offset=<?php echo ($offset+intval(get_wpappninja_option('listnb', 10)));?>">Suivant »</a>	</div>

	<?php
	}
	wp_reset_query();

	$similar = ob_get_clean();

	return $similar;
}

function wpmobile_count_push() {

	if (isset($_GET['wpapp_shortcode']) && $_GET['wpapp_shortcode'] == 'wpapp_history') {
		return 0;
	}
	
	global $wpdb;

	$user_id = (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) ? $_COOKIE['HTTP_X_WPAPPNINJA_ID'] : "");

	$user_settings = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $user_id));

	if (!isset($user_settings)) {
		$user_settings = new stdClass();
	}

	$user_category = explode(',', $user_settings->category);

	$user_category = apply_filters('wpmobile_push_id', $user_category);

	$last_seen = current_time('timestamp');
	if (isset($_COOKIE['wpmobile_last_seen'])) {
		$last_seen = $_COOKIE['wpmobile_last_seen'];
	}

	$like_prepare = " AND (category = %s";
	$like_term = array();
	$like_term[] = wpappninja_get_lang();
	$like_term[] = $last_seen;
	$like_term[] = '1';
	$like_term[] = '';
	foreach ($user_category as $c) {
		$like_prepare .= " OR category LIKE %s";
		$like_term[] = $c;
	}
	$like_prepare .= ')';
	
	$query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(`id`) as nb FROM {$wpdb->prefix}wpappninja_push WHERE (lang = %s OR lang = 'all') AND `send_date` > %d AND `sended` = %s " . $like_prepare, $like_term));

    /*if ($query[0]->nb > 100) {
        $query[0]->nb = 100;
    }*/

    echo "<script>
    try{window.webkit.messageHandlers.wpmobile.postMessage('resetbadge');} catch(err) {}";

    for($i=0;$i<$query[0]->nb;$i++) {
        echo "try{window.webkit.messageHandlers.wpmobile.postMessage('incrementbadge');} catch(err) {}";
    }

    echo "</script>";

	return $query[0]->nb;
}

add_shortcode('wpapp_welcome', 'wpapp_welcome');
function wpapp_welcome() {
    
}

add_shortcode('wpmobile_notification_badge', 'wpmobile_notification_badge');
function wpmobile_notification_badge() {

	$html = '<style>.navbar .right span.badge {
    background: '.get_wpappninja_option('css_00bcbfacaf98f1b05815ab4eaeee1e13').';
    color: '.get_wpappninja_option('css_74537a66b8370a71e9b05c3c4ddbf522', '#fff').'!important;
    opacity: 0.8;position: absolute;left: 80%;font-size: 12px;top: -10px!important;
	}</style>';

	$html .= '<a style="position:relative;color:'.get_wpappninja_option('css_00bcbfacaf98f1b05815ab4eaeee1e13').'" href="'.home_url( '' ).'/?wpapp_shortcode=wpapp_history"><i class="f7-icons notranslate" translate="no">chat</i>'.wpappninja_woo_icon('wpapp_shortcode=wpapp_history').'</a>&nbsp;&nbsp;&nbsp;';

	return $html;
}

function wpappninja_woo_icon($url) {
	
	if (function_exists('wc_get_page_id')) {

		global $woocommerce;
		$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();
		
		if (preg_match('/^' . preg_quote($cart_url, '/') . '/', $url)) {

			$count = $woocommerce->cart->cart_contents_count;
			
            if ($count > 0) {
    			return '<span class="badge" style="background: #d80e0e!important;color: white!important;position: absolute;left: 60%;font-size: 12px;top: 8px;">' . $count . '</span>';
            }
		}
	}

	if (preg_match('/' . preg_quote('wpapp_shortcode=wpapp_history', '/') . '/', $url)) {

		$count = wpmobile_count_push();

		if ($count > 0) {
			return '<span class="badge" style="background: #d80e0e!important;color: white!important;position: absolute;left: 60%;font-size: 12px;top: 8px;">' . $count . '</span>';
		}
	}

	return "";
}

function wpappninja_show_content($content_post) {



	$content = apply_filters('the_content', $content_post->post_content);
	?>

	<div class="post main-post" id="post-<?php the_ID(); ?>">

		<?php
		if (!wpappninja_is_custom_home($content_post)) {
			if(  function_exists ( "bp_current_component" ) && bp_current_component()){
				echo wpappninja_widget('buddypress-top');
			} elseif(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
				echo wpappninja_widget('woocommerce-top');
			} elseif (is_page()) {
				echo wpappninja_widget('page-top');
			} else {
				echo wpappninja_widget('post-top');
			}
		} ?>

		<?php
		if (post_password_required()) {
			echo '<style>input[type="submit"] {    width: 100%;
    border: 0;
    font-size: 22px;
    padding: 12px;
}input[type="password"] {
    border: 1px solid #b8b8b8;
    background: #fff;
    padding: 18px;
    width: 100%;color:#333;
    margin: 20px 0;
}form.post-password-form {
    color: gray;
    text-align: center;
}label {
    color: #fff;
    /* font-size: 0px; */
}</style><h1 style="text-align:center"><i class="f7-icons" style="font-size: 60px;color: #b7b7b7;">lock_fill</i></h1>';
			echo get_the_password_form();
		} else { ?>
		<div class="wpapp-post-content" data-instant><?php echo $content; ?></div>
		<?php } ?>

		<?php
		if (!wpappninja_is_custom_home($content_post)) {
			if(  function_exists ( "bp_current_component" ) && bp_current_component()){
				echo wpappninja_widget('buddypress-bottom');
			} elseif(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
				echo wpappninja_widget('woocommerce-bottom');
			} elseif (is_page()) {
				echo wpappninja_widget('page-bottom');
			} else {
				echo wpappninja_widget('post-bottom');
			}
		} ?>

	</div>
	<?php
}

function wpappninja_show_card($content_post) {
	?>

	<div class="card wpappninja-card-header-pic wpappninja-card post <?php echo get_post_type();?> <?php echo strip_tags(get_the_term_list($content_post->ID, 'category', ' ', ' ', ''));?>" id="post-<?php echo $content_post->ID; ?>">
	<a style="color:initial" class="wpappninja_change_color_card" href="<?php echo get_the_permalink($content_post->ID);?>">
		<div class="card-content">
			<div class="card-content-inner">
				<?php echo wpappninja_widget('card-content'); ?>
			</div>
		</div>
	</a>
	</div>
	<?php
}


function wpappninja_show_previous_next($post) {

	$next_post = get_previous_post();
	if (!empty( $next_post )) { ?>
	<div class="pagination" style="display:none"><a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="next"><?php _e('Next', 'wpappninja');?></a>
	</div>
	<?php }

}

function wpappninja_get_list_arg($args) {

	$has_password = null;
	if (get_wpappninja_option('has_password', "0") == "0") {
		$has_password = false;
	}
	
	$order = array(
				'orderby' => get_wpappninja_option('orderby_list', 'post_date'),
				'order' => get_wpappninja_option('order_list', 'DESC'),
				'has_password' => $has_password,
				'date_query' => array(
						'column'  => 'post_date',
						'after'   => '- '.get_wpappninja_option('maxage', '365000').' days'
							),
				
				'post_status' => 'publish',
                'tag__not_in' => get_wpappninja_option('excluded', '')
			);

	return array_merge($args, $order);
}

function wpappninja_is_toolbar() {

	$pages = wpappninja_get_pages();
	foreach($pages as $page) {
		if (isset($page['menu'])) {
			if ($page['menu'] == "tabbar") {
				return true;
			}
		}
	}

	return false;
}

function wpappninja_is_fab() {

	$pages = wpappninja_get_pages();
	foreach($pages as $page) {
		if (isset($page['menu'])) {
			if ($page['menu'] == "fab") {
				return true;
			}
		}
	}

	return false;
}

function wpappninja_is_custom_home($content_post) {

	if (preg_match('/\[wpapp_(home|recent)\]/', $content_post->post_content)) {
		return true;
	}

	return false;
}

function wpappninja_iosstyle() {
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : "";

	$isIOS = false;
	if (preg_match('#iOS|iPhone|iPad|iPod#i', $ua)) {
		$isIOS = true;
	}

	return $isIOS;
}
