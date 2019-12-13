<?php
/**
* Plugin Name: Social Login, Social Sharing by miniOrange
* Plugin URI: https://plugins.miniorange.com/social-login-social-sharing
* Description: Allow your users to login, comment and share with social login apps like Facebook, Google, Twitter, LinkedIn etc using customizable buttons.
* Version: 7.2.6
* Author: social login (miniOrange)
* License URI: http://miniorange.com/usecases/miniOrange_User_Agreement.pdf
*/

require('miniorange_openid_sso_settings_page.php');
include_once dirname( __FILE__ ) . '/class-mo-openid-login-widget.php';
require('class-mo-openid-sso-customer.php');
require('class-mo-openid-sso-shortcode-buttons.php');
require('class-mo-openid-social-comment.php');
define('MO_OPENID_SOCIAL_LOGIN_VERSION', '7.2.6');
include dirname( __FILE__ ) . '/mo_openid_feedback_form.php';
class Miniorange_OpenID_SSO {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'miniorange_openid_menu' ) );
		add_filter( 'plugin_action_links', array($this, 'mo_openid_plugin_actions'), 10, 2 );
		add_action( 'admin_init',  array( $this, 'miniorange_openid_save_settings' ) );

		add_action( 'plugins_loaded',  array( $this, 'mo_login_widget_text_domain' ) );
        add_action( 'plugins_loaded',  array( $this, 'mo_openid_plugin_update' ),1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_style' ) ,5);
		add_action( 'wp_enqueue_scripts', array( $this, 'mo_openid_plugin_script' ) ,5);
        add_action( 'admin_footer', array( $this,'mo_openid_feedback_request' ));

        //for addon
        add_action('wp_ajax_verify_addon_licience', array($this, 'mo_openid_show_verify_license_page'));

		register_deactivation_hook(__FILE__, array( $this, 'mo_openid_deactivate'));
		register_activation_hook( __FILE__, array( $this, 'mo_openid_activate' ) );

		// add social login icons to default login form
		if(get_option('mo_openid_default_login_enable') == 1){
			add_action( 'login_form', array($this, 'mo_openid_add_social_login') );
			add_action( 'login_enqueue_scripts', array( $this, 'mo_custom_login_stylesheet' ) );
		}

		// add social login icons to default registration form
		if(get_option('mo_openid_default_register_enable') == 1){
			add_action( 'register_form', array($this, 'mo_openid_add_social_login') );
            add_action('login_enqueue_scripts', array( $this, 'mo_custom_login_stylesheet'));
		}

		//add shortcode
		add_shortcode( 'miniorange_social_login', array($this, 'mo_get_output') );
		add_shortcode( 'miniorange_social_sharing', array($this, 'mo_get_sharing_output') );
		add_shortcode( 'miniorange_social_sharing_vertical', array($this, 'mo_get_vertical_sharing_output') );
		add_shortcode( 'miniorange_social_comments', array($this, 'mo_get_comments_output') );
        add_shortcode( 'miniorange_social_custom_fields', array($this, 'mo_get_custom_output') );

		// add social login icons to comment form
		if(get_option('mo_openid_default_comment_enable') == 1 ){
			add_action('comment_form_must_log_in_after', array($this, 'mo_openid_add_social_login'));
			add_action('comment_form_top', array($this, 'mo_openid_add_social_login'));
		}

		//add social login to woocommerce
		if(get_option('mo_openid_woocommerce_login_form') == 1){
			add_action( 'woocommerce_login_form_end', array($this, 'mo_openid_add_social_login'));
		}
        if(get_option('mo_openid_woocommerce_before_login_form') == 1){
            add_action( 'woocommerce_login_form_start', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_center_login_form') == 1){
            add_action( 'woocommerce_login_form', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_register_form_start') == 1){
            add_action( 'woocommerce_register_form_start', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_center_register_form') == 1){
            add_action( 'woocommerce_register_form', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_register_form_end') == 1){
            add_action( 'woocommerce_register_form_end', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_before_checkout_billing_form') == 1){
            add_action( 'woocommerce_before_checkout_billing_form', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_woocommerce_after_checkout_billing_form') == 1){
            add_action( 'woocommerce_after_checkout_billing_form', array($this, 'mo_openid_add_social_login'));
        }

        //add social login to buddypress
        if(get_option('mo_openid_bp_before_register_page') == 1){
            add_action( 'bp_before_register_page', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_bp_before_account_details_fields') == 1){
            add_action( 'bp_before_account_details_fields', array($this, 'mo_openid_add_social_login'));
        }
        if(get_option('mo_openid_bp_after_register_page') == 1){
            add_action( 'bp_after_register_page', array($this, 'mo_openid_add_social_login'));
        }

		if(get_option('mo_openid_logout_redirection_enable') == 0){
			remove_filter( 'logout_url', 'mo_openid_redirect_after_logout');
		}

		if(get_option('mo_share_options_wc_sp_summary') == 1){
			add_action('woocommerce_after_single_product_summary', array( $this, 'mo_openid_social_share' ));
		}

		if(get_option('mo_share_options_wc_sp_summary_top') == 1){
			add_action('woocommerce_single_product_summary', array( $this, 'mo_openid_social_share' ));
		}

		if(get_option('mo_openid_social_comment_fb') == 1 || get_option('mo_openid_social_comment_google') == 1 ){
			add_action('comment_form_top', array( $this, 'mo_openid_add_comment'));
		}

		if(get_option('mo_share_options_bb_forum') == 1){
			if(get_option('mo_share_options_bb_forum_position') == 'before')
				add_action('bbp_template_before_single_forum', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_forum_position') == 'after')
				add_action('bbp_template_after_single_forum', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_forum_position') == 'both'){
				add_action('bbp_template_before_single_forum', array( $this, 'mo_openid_social_share' ));
				add_action('bbp_template_after_single_forum', array( $this, 'mo_openid_social_share' ));
			}
		}

		if(get_option('mo_share_options_bb_topic') == 1){
			if(get_option('mo_share_options_bb_topic_position') == 'before')
				add_action('bbp_template_before_single_topic', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_topic_position') == 'after')
				add_action('bbp_template_after_single_topic', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_topic_position') == 'both'){
				add_action('bbp_template_before_single_topic', array( $this, 'mo_openid_social_share' ));
				add_action('bbp_template_after_single_topic', array( $this, 'mo_openid_social_share' ));
			}
		}

		if(get_option('mo_share_options_bb_reply') == 1){
			if(get_option('mo_share_options_bb_reply_position') == 'before')
				add_action('bbp_template_before_single_reply', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_reply_position') == 'after')
				add_action('bbp_template_after_single_reply', array( $this, 'mo_openid_social_share' ));

			if(get_option('mo_share_options_bb_reply_position') == 'both'){
				add_action('bbp_template_before_single_reply', array( $this, 'mo_openid_social_share' ));
				add_action('bbp_template_after_single_reply', array( $this, 'mo_openid_social_share' ));
			}
		}

		add_filter( 'the_content', array( $this, 'mo_openid_add_social_share_links' ) );
		add_filter( 'the_excerpt', array( $this, 'mo_openid_add_social_share_links' ) );

		//custom avatar
		if(get_option('moopenid_social_login_avatar')) {
			add_filter( 'get_avatar', array( $this, 'mo_social_login_custom_avatar' ), 15, 5 );
			add_filter( 'get_avatar_url', array( $this, 'mo_social_login_custom_avatar_url' ), 15, 3);
			add_filter( 'bp_core_fetch_avatar', array( $this, 'mo_social_login_buddypress_avatar' ), 10, 2);
		}

		remove_action( 'admin_notices', array( $this, 'mo_openid_success_message') );
	    remove_action( 'admin_notices', array( $this, 'mo_openid_error_message') );

		//set default values
		add_option( 'mo_openid_login_redirect', 'same' );
		add_option( 'mo_openid_login_theme', 'longbutton' );
		add_option( 'mo_openid_oauth','0');
        add_option('mo_openid_new_user','0');
        add_option('mo_openid_malform_error','0');
		add_option( 'mo_openid_share_theme', 'oval' );
		add_option( 'mo_share_options_enable_post_position', 'before');
		add_option( 'mo_share_options_home_page_position', 'before');
		add_option( 'mo_share_options_static_pages_position', 'before');
		add_option( 'mo_share_options_bb_forum_position', 'before');
		add_option( 'mo_share_options_bb_topic_position', 'before');
		add_option( 'mo_share_options_bb_reply_position', 'before');
		add_option( 'mo_openid_default_login_enable', '1');
		add_option('mo_login_openid_login_widget_customize_textcolor','000000');
		add_option( 'mo_openid_login_widget_customize_text', 'Connect with:' );
		add_option( 'mo_openid_share_widget_customize_text', 'Share with:' );
		add_option( 'mo_openid_share_widget_customize_text_color', '000000');
		add_option( 'mo_openid_login_button_customize_text', 'Login with' );
		add_option( 'mo_openid_share_widget_customize_direction_horizontal','1' );
		add_option( 'mo_sharing_icon_custom_size','35' );
		add_option( 'mo_openid_share_custom_theme', 'default' );
		add_option( 'mo_sharing_icon_custom_color', '000000' );
		add_option( 'mo_sharing_icon_space', '4' );
		add_option( 'mo_sharing_icon_custom_font', '000000' );
		add_option( 'mo_login_icon_custom_size','35' );
		add_option( 'mo_login_icon_space','4' );
		add_option( 'mo_login_icon_custom_width','200' );
		add_option( 'mo_login_icon_custom_height','35' );
		add_option('mo_login_icon_custom_boundary','4');
		add_option( 'mo_openid_login_custom_theme', 'default' );
		add_option( 'mo_login_icon_custom_color', '2B41FF' );
		add_option( 'mo_openid_logout_redirection_enable', '0' );
		add_option( 'mo_openid_logout_redirect', 'currentpage' );
		add_option( 'mo_openid_auto_register_enable', '1');
		add_option( 'mo_openid_account_linking_enable', '0');
		add_option( 'mo_openid_email_enable', '1');
		add_option( 'mo_openid_register_disabled_message', 'Registration is disabled for this website. Please contact the administrator for any queries.' );
		add_option( 'mo_openid_register_email_message', 'Hello,<br><br>##User Name## has registered to your site  successfully.<br><br>Thanks,<br>miniOrange' );
		add_option( 'moopenid_social_login_avatar','1' );
		add_option( 'moopenid_user_attributes','0' );
		add_option( 'mo_share_vertical_hide_mobile', '1' );
		add_option( 'mo_openid_social_comment_blogpost','1' );
		add_option( 'mo_openid_social_comment_default_label', 'Default Comments' );
		add_option( 'mo_openid_social_comment_fb_label', 'Facebook Comments' );
		add_option( 'mo_openid_social_comment_google_label', 'Google+ Comments' );
		add_option( 'mo_openid_social_comment_disqus_label', 'Disqus Comments' );
		add_option( 'mo_openid_social_comment_heading_label', 'Leave a Reply' );
		add_option('mo_openid_login_role_mapping','subscriber');
		add_option( 'mo_openid_user_number',0);
		add_option( 'mo_openid_login_widget_customize_logout_name_text', 'Howdy, ##username## |' );
		add_option( 'mo_openid_login_widget_customize_logout_text', 'Logout?' );
		add_option( 'mo_openid_share_email_subject','I wanted you to see this site' );
		add_option( 'mo_openid_share_email_body','Check out this site ##url##' );
		add_option( 'mo_openid_enable_profile_completion','0' );
        add_option( 'moopenid_logo_check','1' );
        add_option( 'mo_openid_test_configuration', 0);


        //profile completion
        add_option( 'mo_profile_complete_title','Profile Completion');
        add_option( 'mo_profile_complete_username_label','Username');
        add_option( 'mo_profile_complete_email_label','Email');
        add_option( 'mo_profile_complete_submit_button','Submit');
        add_option( 'mo_profile_complete_instruction','If you are an existing user on this site, enter your registered email and username. If you are a new user, please edit/fill the details.');
        add_option( 'mo_profile_complete_extra_instruction','We will be sending a verification code to this email to verify it. Please enter a valid email address.');
        add_option( 'mo_profile_complete_uname_exist','Entered username already exists. Try some other username');
        add_option( 'mo_email_verify_resend_otp_button','Resend OTP');
        add_option( 'mo_email_verify_back_button','Back');
        add_option( 'mo_email_verify_title','Verify your email');
        add_option( 'mo_email_verify_message','We have sent a verification code to given email. Please verify your account with it.');
        add_option( 'mo_email_verify_verification_code_instruction','Enter your verification code');
        add_option( 'mo_email_verify_wrong_otp','You have entered an invalid verification code. Enter a valid code.');
        $message='Dear User,

Your verification code for completing your profile is: ##otp##

Please use this code to complete your profile. Do not share this code with anyone.

Thank you.';

        add_option( 'custom_otp_msg',$message);

        //account linking
        add_option( 'mo_account_linking_title','Account Linking');
        add_option( 'mo_account_linking_new_user_button','Create a new account?');
        add_option( 'mo_account_linking_existing_user_button','Link to an existing account?');
        add_option( 'mo_account_linking_new_user_instruction','If you do not have an existing account with a different email address, click on <b>Create a new account</b>');
        add_option( 'mo_account_linking_existing_user_instruction','If you already have an existing account with a different email adddress and want to link this account with that, click on <b>Link to an existing account</b>.');
        add_option( 'mo_account_linking_extra_instruction','You will be redirected to login page to login to your existing account.');

        //woocommerce display options
        add_option( 'mo_openid_woocommerce_login_form','0');
        add_option( 'mo_openid_woocommerce_before_login_form','0');
        add_option( 'mo_openid_woocommerce_center_login_form','0');
        add_option( 'mo_openid_woocommerce_register_form_start','0');
        add_option( 'mo_openid_woocommerce_center_register_form','0');
        add_option( 'mo_openid_woocommerce_register_form_end','0');
        add_option( 'mo_openid_woocommerce_before_checkout_billing_form','0');
        add_option( 'mo_openid_woocommerce_after_checkout_billing_form','0');
        //buddypress display options
        add_option( 'mo_openid_bp_before_register_page','0');
        add_option( 'mo_openid_bp_before_account_details_fields','0');
        add_option( 'mo_openid_bp_after_register_page','0');

        //Custom app switch button option
        add_option('mo_openid_enable_custom_app_google','1');
        add_option('mo_openid_enable_custom_app_facebook','1');
        add_option('mo_openid_enable_custom_app_twitter','1');

        //GDPR options
        add_option('mo_openid_gdpr_consent_enable', 0);
        add_option( 'mo_openid_privacy_policy_text', 'terms and conditions');
        add_option( 'mo_openid_gdpr_consent_message','I accept the terms and conditions.');
        //Error messages option
        add_option( 'mo_registration_error_message','There was an error in registration. Please contact your administrator.');
        add_option( 'mo_email_failure_message','Either your SMTP is not configured or you have entered an unmailable email. Please go back and try again.');
        add_option( 'mo_existing_username_error_message','This username already exists. Please ask the administrator to create your account with a unique username.');
        add_option( 'mo_manual_login_error_message','There was an error during login. Please try to login/register manually. <a href='.site_url().'>Go back to site</a>');
        add_option( 'mo_delete_user_error_message','Error deleting user from account linking table');
        add_option( 'mo_account_linking_message','Link your social account to existing WordPress account by entering username and password.');
        add_option('regi_pop_up','');
        add_option('mo_openid_tour','0');
        add_option('pop_regi_msg','Your settings are saved successfully. Please enter your valid email address to enable social login.');
        add_option('pop_login_msg','Enter Your Login Credentials.');
    }

    function  mo_openid_show_verify_license_page(){
        wp_send_json(["html"=> mo_openid_show_verify_addon_license_page('extra_attributes_addon')]);
    }

    function mo_openid_feedback_request(){
        mo_openid_display_feedback_form();
    }

	function mo_openid_deactivate() {
		delete_option('mo_openid_host_name');
		delete_option('mo_openid_transactionId');
		delete_option('mo_openid_admin_password');
		delete_option('mo_openid_registration_status');
		delete_option('mo_openid_admin_phone');
		delete_option('mo_openid_new_registration');
		delete_option('mo_openid_admin_customer_key');
		delete_option('mo_openid_admin_api_key');
		delete_option('mo_openid_customer_token');
		delete_option('mo_openid_verify_customer');
		delete_option('mo_openid_message');
		delete_option( 'mo_openid_admin_customer_valid');
		delete_option( 'mo_openid_admin_customer_plan');
	}

    //  create mo_openid_linked_user if it doesn't exist
    // + add entries in wp_mo_openid_linked_user table
    // + remove columns app name and identifier from wp_users table
	function mo_openid_plugin_update(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'mo_openid_linked_user';
        $charset_collate = $wpdb->get_charset_collate();

        $time = $wpdb->get_var("SELECT COLUMN_NAME 
                                    FROM information_schema.COLUMNS 
                                    WHERE
                                     TABLE_SCHEMA='$wpdb->dbname'
                                     AND COLUMN_NAME = 'timestamp'");

        $data_type=$wpdb->get_var("SELECT DATA_TYPE 
                                    FROM information_schema.COLUMNS 
                                    WHERE
                                     TABLE_SCHEMA='$wpdb->dbname'
                                     AND TABLE_NAME = '$table_name'
									 AND COLUMN_NAME = 'user_id'");
        if($data_type=="mediumint")
        {
            $wpdb->get_var("ALTER TABLE $table_name CHANGE `user_id` `user_id` BIGINT(20) NOT NULL");

        }

        // if table mo_openid_linked_user doesn't exist or the 'timestamp' column doesn't exist
        if($wpdb->get_var("show tables like '$table_name'") != $table_name || empty($time) ) {
            $sql = "CREATE TABLE $table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    linked_social_app varchar(55) NOT NULL,
                    linked_email varchar(55) NOT NULL,
                    user_id BIGINT(20) NOT NULL,
                    identifier VARCHAR(100) NOT NULL,
                    timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY  (id)
                ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            $identifier = $wpdb->get_var("SELECT COLUMN_NAME 
                                    FROM information_schema.COLUMNS 
                                    WHERE 
                                    TABLE_NAME = '$wpdb->users' 
                                    AND TABLE_SCHEMA='$wpdb->dbname'
                                    AND COLUMN_NAME = 'identifier'");

            if(strcasecmp( $identifier, "identifier") == 0 ){

                $count= $wpdb->get_var("SELECT count(ID) FROM $wpdb->users WHERE identifier not LIKE ''");
                $result= $wpdb->get_results("SELECT * FROM $wpdb->users WHERE identifier not LIKE ''");

                for($icnt = 0; $icnt < $count; $icnt = $icnt + 1){

                    $provider = $result[$icnt]->provider;
                    $split_app_name = explode('_', $provider);
                    $provider = strtolower($split_app_name[0]);
                    $user_email = $result[$icnt]->user_email;
                    $ID = $result[$icnt]->ID;
                    $identifier = $result[$icnt]->identifier;

                    $output = $wpdb->insert(
                        $table_name,
                        array(
                            'linked_social_app' => $provider,
                            'linked_email' => $user_email,
                            'user_id' =>  $ID,
                            'identifier' => $identifier
                        ),
                        array(
                            '%s',
                            '%s',
                            '%d',
                            '%s'
                        )
                    );
                    if($output === false){
                        $wpdb->show_errors();
                        $wpdb->print_error();
                        wp_die('Error in insert Query');
                        exit;
                    }

                }
                $wpdb->get_var("ALTER TABLE $wpdb->users DROP COLUMN provider");
                $wpdb->get_var("ALTER TABLE $wpdb->users DROP COLUMN identifier");
            }
        }

        $current_version = get_option('mo_openid_social_login_version');

        if(!$current_version && version_compare(MO_OPENID_SOCIAL_LOGIN_VERSION,"6.6.1",">=")){
            //delete entries from mo_openid_linked_user table which have empty column values
            $result = $wpdb->query(
                $wpdb->prepare(
                    "
                        DELETE FROM {$table_name}
                        WHERE linked_social_app = %s
                        OR linked_email = %s
                        OR user_id = %d
                        OR identifier = %s
                        ",
                    '','',0, ''
                )
            );
            if($result === false){
                $wpdb->show_errors();
                $wpdb->print_error();
                wp_die('Error in deletion query');
                exit;
            }
        }
        update_option('mo_openid_social_login_version',MO_OPENID_SOCIAL_LOGIN_VERSION);
    }

	function mo_openid_activate() {
		add_option('Activated_Plugin','Plugin-Slug');
        update_option( 'mo_openid_host_name', 'https://login.xecurify.com' );
	}

	function mo_openid_add_social_login(){
        if(!is_user_logged_in() && mo_openid_is_customer_registered() && strpos( $_SERVER['QUERY_STRING'], 'disable-social-login' ) == false){
            $mo_login_widget = new mo_openid_login_wid();
            $mo_login_widget->openidloginForm();
		}
	}

	function mo_openid_add_social_share_links($content) {
		global $post;
		$post_content=$content;
		$title = str_replace('+', '%20', urlencode($post->post_title));

		if(is_front_page() && get_option('mo_share_options_enable_home_page')==1){
			$html_content = mo_openid_share_shortcode('', $title);

			if ( get_option('mo_share_options_home_page_position') == 'before' ) {
				return  $html_content . $post_content;
			} else if ( get_option('mo_share_options_home_page_position') == 'after' ) {
				 return   $post_content . $html_content;
			} else if ( get_option('mo_share_options_home_page_position') == 'both' ) {
				 return $html_content . $post_content . $html_content;
			}
		} else if(is_page() && get_option('mo_share_options_enable_static_pages')==1){
			$html_content = mo_openid_share_shortcode('', $title);

			if ( get_option('mo_share_options_static_pages_position') == 'before' ) {
				return  $html_content . $post_content;
			} else if ( get_option('mo_share_options_static_pages_position') == 'after' ) {
				 return   $post_content . $html_content;
			} else if ( get_option('mo_share_options_static_pages_position') == 'both' ) {
				 return $html_content . $post_content . $html_content;
			}
		} else if(is_single() && get_option('mo_share_options_enable_post') == 1 ){
			$html_content = mo_openid_share_shortcode('', $title);

			if ( get_option('mo_share_options_enable_post_position') == 'before' ) {
				return  $html_content . $post_content;
			} else if ( get_option('mo_share_options_enable_post_position') == 'after' ) {
				 return   $post_content . $html_content;
			} else if ( get_option('mo_share_options_enable_post_position') == 'both' ) {
				 return $html_content . $post_content . $html_content;
			}
		} else
			return $post_content;

	}

	function mo_openid_social_share(){
		global $post;
		$title = str_replace('+', '%20', urlencode($post->post_title));
		echo mo_openid_share_shortcode('', $title);
	}

	function mo_openid_add_comment(){
		global $post;
		if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
			$http = "https://";
		} else {
			$http = "http://";
		}
		$url = $http . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
		if(is_single() && get_option('mo_openid_social_comment_blogpost') == 1 ) {
			mo_openid_social_comment($post, $url);
		} else if(is_page() && get_option('mo_openid_social_comment_static')==1) {
			mo_openid_social_comment($post, $url);
		}
	}

	function mo_custom_login_stylesheet()
	{
		wp_enqueue_style( 'mo-wp-style',plugins_url('includes/css/mo_openid_style.css?version=5.0.6', __FILE__), false );
		wp_enqueue_style( 'mo-wp-bootstrap-social',plugins_url('includes/css/bootstrap-social.css', __FILE__), false );
		wp_enqueue_style( 'mo-wp-bootstrap-main',plugins_url('includes/css/bootstrap.min.css', __FILE__), false );
		wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.min.css?version=4.8', __FILE__), false );
		wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.css?version=4.8', __FILE__), false );
	}

    function mo_openid_plugin_settings_style() {
        wp_enqueue_style( 'mo_openid_admin_settings_style', plugins_url('includes/css/mo_openid_style.css?version=5.0.6', __FILE__));
        wp_enqueue_style( 'mo_openid_admin_settings_phone_style', plugins_url('includes/css/phone.css', __FILE__));
        wp_enqueue_style( 'mo-wp-bootstrap-social',plugins_url('includes/css/bootstrap-social.css', __FILE__), false );
        wp_enqueue_style( 'mo-wp-bootstrap-main',plugins_url('includes/css/bootstrap.min-preview.css', __FILE__), false );
        wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.min.css?version=4.8', __FILE__), false );
        wp_enqueue_style( 'mo-wp-font-awesome',plugins_url('includes/css/font-awesome.css?version=4.8', __FILE__), false );
        wp_enqueue_style( 'bootstrap_style_ass', plugins_url( 'includes/css/bootstrap-tour-standalone.css?version=5.1.4', __FILE__ ) );



    }

    function mo_openid_plugin_script() {
        wp_enqueue_script( 'js-cookie-script',plugins_url('includes/js/jquery.cookie.min.js', __FILE__), array('jquery'));
        wp_enqueue_script( 'mo-social-login-script',plugins_url('includes/js/social_login.js', __FILE__), array('jquery') );
    }

    function mo_openid_plugin_settings_script() {
        wp_enqueue_script( 'mo_openid_admin_settings_phone_script', plugins_url('includes/js/phone.js', __FILE__ ));
        wp_enqueue_script( 'mo_openid_admin_settings_color_script', plugins_url('includes/jscolor/jscolor.js', __FILE__ ));
        wp_enqueue_script( 'mo_openid_admin_settings_script', plugins_url('includes/js/settings.js?version=4.9.6', __FILE__ ), array('jquery'));
        wp_enqueue_script( 'mo_openid_admin_settings_phone_script', plugins_url('includes/js/bootstrap.min.js', __FILE__ ));
        wp_enqueue_script( 'bootstrap_script_hehe', plugins_url( 'includes/js/bootstrap-tour-standalone.min.js', __FILE__ ) );
    }


    function mo_openid_success_message() {
		$message = get_option('mo_openid_message'); ?>
		<script>

		jQuery(document).ready(function() {
			var message = "<?php echo $message; ?>";
			jQuery('#mo_openid_msgs').append("<div class='error notice is-dismissible mo_openid_error_container'> <p class='mo_openid_msgs'>" + message + "</p></div>");
		});
		</script>
	<?php }

	function mo_openid_error_message() {
		$message = get_option('mo_openid_message'); ?>
		<script>
		jQuery(document).ready(function() {
			var message = "<?php echo $message; ?>";
			jQuery('#mo_openid_msgs').append("<div class='updated notice is-dismissible mo_openid_success_container'> <p class='mo_openid_msgs'>" + message + "</p></div>");
		});
		</script>
	<?php }

	private function mo_openid_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_openid_success_message') );
		add_action( 'admin_notices', array( $this, 'mo_openid_error_message') );
	}

	private function mo_openid_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_openid_error_message') );
		add_action( 'admin_notices', array( $this, 'mo_openid_success_message') );
	}
    function mo_openid_success_facebook_message() {
        $message = 'Other setting are saved sucessfully. For Facebook please setup the Facebook Custome App. '; ?>
        <script>

            jQuery(document).ready(function() {
                var message = "<?php echo $message; ?>";
                jQuery('#mo_openid_msgs').append("<div class='error notice is-dismissible mo_openid_error_container'> <p class='mo_openid_msgs'>" + message + "</p></div>");
            });
        </script>
    <?php }

    private function mo_openid_show_facebook_error_message() {
        remove_action( 'admin_notices', array( $this, 'mo_openid_error_message') );
        add_action( 'admin_notices', array( $this, 'mo_openid_success_facebook_message') );
    }

	public function mo_openid_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

	function  mo_login_widget_openid_options() {
        update_option( 'mo_openid_host_name', 'https://login.xecurify.com' );
		mo_register_openid();
	}

    function  mo_login_widget_openid_options_addon() {
        mo_register_openid_addon();
    }

	function mo_openid_activation_message() {
		$class = "updated";
		$message = get_option('mo_openid_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	function mo_login_widget_text_domain(){
		load_plugin_textdomain('flw', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
	}

	public function mo_oauth_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

    public function if_custom_app_exists($app_name){
        if(get_option('mo_openid_apps_list'))
            $appslist = maybe_unserialize(get_option('mo_openid_apps_list'));
        else
            $appslist = array();

        foreach( $appslist as $key => $app){
            $option = 'mo_openid_enable_custom_app_' . $key;
            if($app_name == $key && get_option($option) == '1')
                return true;
        }
        return false;
    }
    function miniorange_openid_save_settings(){

        if ( current_user_can( 'manage_options' )){
            if(is_admin() && get_option('Activated_Plugin')=='Plugin-Slug') {

                delete_option('Activated_Plugin');
                update_option('mo_openid_message','Go to plugin <b><a href="admin.php?page=mo_openid_settings">settings</a></b> to enable Social Login, Social Sharing by miniOrange.');
                add_action('admin_notices', array($this, 'mo_openid_activation_message'));
            }

            if( isset($_POST['mo_openid_connect_register_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_connect_register_customer" ) {	//register the admin to miniOrange



                $nonce = $_POST['mo_openid_connect_register_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-connect-register-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    //validation and sanitization
                    $company = '';
                    $first_name = '';
                    $last_name = '';
                    $email = '';
                    $phone = '';
                    $password = '';
                    $confirmPassword = '';
                    $illegal = "#$%^*()+=[]';,/{}|:<>?~";
                    $illegal = $illegal . '"';
                    if( $this->mo_openid_check_empty_or_null( $_POST['email'] ) || $this->mo_openid_check_empty_or_null( $_POST['password'] ) || $this->mo_openid_check_empty_or_null( $_POST['confirmPassword'] ) ) {

                        update_option( 'mo_openid_message', 'All the fields are required. Please enter valid entries.');
                        $this->mo_openid_show_error_message();
                        if(get_option('regi_pop_up') =="yes") {
                            update_option('pop_regi_msg', get_option('mo_openid_message'));
                            mo_openid_registeration_modal();
                        }
                        return;
                    } else if( strlen( $_POST['password'] ) < 6 || strlen( $_POST['confirmPassword'] ) < 6){	//check password is of minimum length 6
                        update_option( 'mo_openid_message', 'Choose a password with minimum length 6.');
                        $this->mo_openid_show_error_message();
                        if(get_option('regi_pop_up') =="yes"){
                            update_option('pop_regi_msg', get_option('mo_openid_message'));
                            mo_openid_registeration_modal();
                        }
                        return;
                    } else if(strpbrk($_POST['email'],$illegal)) {
                        update_option( 'mo_openid_message', 'Please match the format of Email. No special characters are allowed.');
                        $this->mo_openid_show_error_message();
                        if(get_option('regi_pop_up') =="yes"){
                            update_option('pop_regi_msg', get_option('mo_openid_message'));
                            mo_openid_registeration_modal();
                        }
                        return;
                    } else {
                        $first_name = sanitize_text_field(isset($_POST['fname'])?$_POST['fname']:'');
                        $last_name = sanitize_text_field(isset($_POST['lname'])?$_POST['lname']:'');
                        $email = sanitize_email( $_POST['email'] );
                        $phone = sanitize_text_field( isset($_POST['phone'])?$_POST['phone']:'' );
                        $password = stripslashes( $_POST['password'] );
                        $confirmPassword = stripslashes( $_POST['confirmPassword'] );

                    }

                    update_option( 'mo_openid_admin_first_name', $first_name);
                    update_option( 'mo_openid_admin_last_name', $last_name);
                    update_option( 'mo_openid_admin_email', $email );
                    update_option( 'mo_openid_admin_phone', $phone );


                    if( strcmp( $password, $confirmPassword) == 0 ) {
                        update_option( 'mo_openid_admin_password', $password );

                        $customer = new CustomerOpenID();
                        $content = json_decode($customer->check_customer(), true);

	                    if( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND') == 0 ){
		                    if ( $content['status'] == 'ERROR' ) {
			                    update_option( 'mo_openid_message',$content['message'] );
			                    $this->mo_openid_show_error_message();
		                    } else {
			                    $this->create_customer();
			                    update_option('mo_openid_oauth','1');
			                    update_option('mo_openid_new_user','1');
			                    update_option('mo_openid_malform_error','1');
		                    }


                        }
                        else if ($content == null){
	                        update_option( 'mo_openid_message',"Please check your internet connetion and try again." );
	                        $this->mo_openid_show_error_message();
	                        if(get_option('regi_pop_up') =="yes"){
		                        update_option('pop_regi_msg', get_option('mo_openid_message'));
		                        mo_openid_registeration_modal();
	                        }

                        }
                        else{
                            $this->get_current_customer();

                        }


                    } else {

                        update_option( 'mo_openid_message', 'Passwords do not match.');
                        if(get_option('regi_pop_up') =="yes"){
                            update_option('pop_regi_msg', get_option('mo_openid_message'));
                            mo_openid_registeration_modal();
                        }
                        delete_option('mo_openid_verify_customer');
                        $this->mo_openid_show_error_message();
                    }
                }

            } else if( isset( $_POST['show_login'] ) )
            {
                mo_pop_show_verify_password_page();
            }

            else if(isset($_POST['mo_openid_verify_license_nonce']) and isset($_POST['option']) && $_POST['option'] == 'mo_openid_verify_license') {
                $nonce = $_POST['mo_openid_verify_license_nonce'];
                if (!wp_verify_nonce($nonce, 'mo-openid-verify-license-nonce')) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    if ($this->mo_openid_check_empty_or_null($_POST['openid_licence_key'])) {
                        update_option('mo_openid_message', 'All the fields are required. Please enter valid license key.');
                        $this->mo_openid_show_error_message();
                        return;
                    }
                    $code = trim($_POST['openid_licence_key']);
                    $customer = new CustomerOpenID();
                    $content = json_decode($customer->check_customer_ln($_POST['licience_type']), true);
                    if (strcasecmp($content['status'], 'SUCCESS') == 0) {
                        $content = json_decode($customer->mo_openid_vl($code, false), true);
                        update_option('mo_openid_vl_check_t', time());
                        if (strcasecmp($content['status'], 'SUCCESS') == 0) {
                            $key = get_option('mo_openid_customer_token');
                            update_option('mo_openid_opn_lk_extra_attr_addon', MOAESEncryption::encrypt_data($code, $key));
                            update_option('mo_openid_message', 'Your addon license is verified. You can now setup the addon plugin.');
                            $key = get_option('mo_openid_customer_token');
                            update_option('mo_openid_site_ck_l', MOAESEncryption::encrypt_data("true", $key));
                            update_option('mo_openid_t_site_status', MOAESEncryption::encrypt_data("false", $key));
                            $this->mo_openid_show_success_message();
                        } else if (strcasecmp($content['status'], 'FAILED') == 0) {
                            if (strcasecmp($content['message'], 'Code has Expired') == 0) {
                                $url = add_query_arg(array('tab' => 'pricing'), $_SERVER['REQUEST_URI']);
                                update_option('mo_openid_message', 'License key you have entered has already been used. Please enter a key which has not been used before on any other instance or if you have exausted all your keys then <a href="' . $url . '">Click here</a> to buy more.');
                            } else {
                                update_option('mo_openid_message', 'You have entered an invalid license key. Please enter a valid license key.');
                            }
                            $this->mo_openid_show_error_message();
                        } else {
                            update_option('mo_openid_message', 'An error occured while processing your request. Please Try again.');
                            $this->mo_openid_show_error_message();
                        }
                    }
                    else {
                        $key = get_option('mo_openid_customer_token');
                        update_option('mo_openid_site_ck_l', MOAESEncryption::encrypt_data("false", $key));
                        $url = add_query_arg(array('tab' => 'pricing'), $_SERVER['REQUEST_URI']);
                        update_option('mo_openid_message', 'You have not upgraded yet. <a href="' . $url . '">Click here</a> to upgrade to premium version.');
                        $this->mo_openid_show_error_message();
                    }

                    $content = json_decode($customer->check_customer_valid(), true);
                    if (strcasecmp($content['status'], 'SUCCESS') == 0) {
                        update_option('mo_openid_admin_customer_plan', isset($content['licensePlan']) ? base64_encode($content['licensePlan']) : 0);
                    }

                }
            }

            else if( isset($_POST['mo_openid_connect_verify_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_connect_verify_customer" ) {	//register the admin to miniOrange
                $nonce = $_POST['mo_openid_connect_verify_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-connect-verify-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    //validation and sanitization
                    $email = '';
                    $password = '';
                    $illegal = "#$%^*()+=[]';,/{}|:<>?~";
                    $illegal = $illegal . '"';
                    if( $this->mo_openid_check_empty_or_null( $_POST['email'] ) || $this->mo_openid_check_empty_or_null( $_POST['password'] ) ) {
                        update_option( 'mo_openid_message', 'All the fields are required. Please enter valid entries.');
                        $this->mo_openid_show_error_message();
                        return;
                    } else if(strpbrk($_POST['email'],$illegal)) {
                        update_option( 'mo_openid_message', 'Please match the format of Email. No special characters are allowed.');
                        $this->mo_openid_show_error_message();
                        return;
                    } else{
                        $email = sanitize_email( $_POST['email'] );
                        $password = stripslashes( $_POST['password'] );
                    }

                    update_option( 'mo_openid_admin_email', $email );
                    update_option( 'mo_openid_admin_password', $password );
                    $customer = new CustomerOpenID();
                    $content = $customer->get_customer_key();
                    $customerKey = json_decode( $content, true );
                    if( isset($customerKey) ) {
                        update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
                        update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
                        update_option( 'mo_openid_customer_token', $customerKey['token'] );
                        //update_option( 'mo_openid_admin_phone', $customerKey['phone'] );
                        update_option('mo_openid_admin_password', '');
                        update_option( 'mo_openid_message', 'Your account has been retrieved successfully.');
                        delete_option('mo_openid_verify_customer');
                        $this->mo_openid_show_success_message();
                    }else if ($content == null){
	                    update_option( 'mo_openid_message',"Please check your internet connetion and try again." );
	                    $this->mo_openid_show_error_message();
	                    if(get_option('regi_pop_up') =="yes"){
		                    update_option('pop_regi_msg', get_option('mo_openid_message'));
		                    mo_openid_registeration_modal();
	                    }

                    } else {
                        update_option( 'mo_openid_message', 'Invalid username or password. Please try again.');
                        $this->mo_openid_show_error_message();
                        if(get_option('regi_pop_up') =="yes") {
                            update_option("pop_login_msg",get_option("mo_openid_message"));
                            mo_pop_show_verify_password_page();

                        }
                    }
                    update_option('mo_openid_admin_password', '');
                }
            }
            else if(isset($_POST['mo_openid_forgot_password_nonce']) and isset($_POST['option']) and $_POST['option'] == 'mo_openid_forgot_password'){
                $nonce = $_POST['mo_openid_forgot_password_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-forgot-password-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    $email ='';
                    if( $this->mo_openid_check_empty_or_null( $email ) ) {
                        if( $this->mo_openid_check_empty_or_null( $_POST['email'] ) ) {
                            update_option( 'mo_openid_message', 'No email provided. Please enter your email below to reset password.');
                            $this->mo_openid_show_error_message();
                            if(get_option('regi_pop_up') =="yes"){
                                update_option("pop_login_msg",get_option("mo_openid_message"));
                                mo_pop_show_verify_password_page();

                            }
                            return;
                        } else {
                            $email = sanitize_email($_POST['email']);

                        }
                    }


                    $customer = new CustomerOpenID();
                    $content = json_decode($customer->forgot_password($email),true);
                    if(strcasecmp($content['status'], 'SUCCESS') == 0){
                        update_option( 'mo_openid_message','You password has been reset successfully. Please enter the new password sent to your registered mail here.');
                        $this->mo_openid_show_success_message();
                        if(get_option('regi_pop_up') =="yes"){
                            update_option("pop_login_msg",get_option("mo_openid_message"));
                            mo_pop_show_verify_password_page();

                        }
                    }else{
                        update_option( 'mo_openid_message','An error occurred while processing your request. Please make sure you are registered in miniOrange with the <b>'. $content['email'] .'</b> email address. ');
                        $this->mo_openid_show_error_message();
                        if(get_option('regi_pop_up') =="yes"){
                            update_option("pop_login_msg",get_option("mo_openid_message"));
                            mo_pop_show_verify_password_page();

                        }
                    }
                }
            }
            else if(isset($_POST['mo_openid_check_license_nonce']) and isset($_POST['option']) and $_POST['option'] == 'mo_openid_check_license'){
                $nonce = $_POST['mo_openid_check_license_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-check-license-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    if(mo_openid_is_customer_registered()) {
                        $customer = new CustomerOpenID();
                        $content = json_decode($customer->check_customer_valid(),true);
                        if(strcasecmp($content['status'], 'SUCCESS') == 0){
                            update_option( 'mo_openid_admin_customer_valid', strcasecmp($content['licenseType'], 'Premium') !== FALSE ? 1 : 0);
                            update_option( 'mo_openid_admin_customer_plan', isset($content['licensePlan']) ? base64_encode($content['licensePlan']) : 0);
                            if(get_option('mo_openid_admin_customer_valid') && isset($content['licensePlan'])){
                                $license = explode(' -', $content['licensePlan']);
                                $lp = $license[0];
                                update_option( 'mo_openid_message','You are on the old ' . $lp . '.');
                            } else
                                update_option( 'mo_openid_message','You are on the Free Plan.');
                            $this->mo_openid_show_success_message();
                        }else if(strcasecmp($content['status'], 'FAILED') == 0){
                            update_option('mo_openid_message', 'You are on Free Plan.');
                            $this->mo_openid_show_success_message();
                        }else{
                            update_option( 'mo_openid_message','An error occured while processing your request. Please try again.');
                            $this->mo_openid_show_error_message();
                        }
                    } else {
                        update_option('mo_openid_message', 'Please register an account before trying to check your plan');
                        $this->mo_openid_show_error_message();
                    }
                }
            }
            else if( isset($_POST['mo_openid_enable_apps_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_enable_apps" ) {
                $nonce = $_POST['mo_openid_enable_apps_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-apps-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {

                    update_option( 'mo_openid_google_enable', isset( $_POST['mo_openid_google_enable']) ? sanitize_text_field($_POST['mo_openid_google_enable']) : 0);
                    update_option( 'mo_openid_salesforce_enable', isset( $_POST['mo_openid_salesforce_enable']) ? sanitize_text_field($_POST['mo_openid_salesforce_enable']) : 0);
                    if($this->if_custom_app_exists('facebook')) {
                        update_option('mo_openid_facebook_enable', isset($_POST['mo_openid_facebook_enable']) ? sanitize_text_field($_POST['mo_openid_facebook_enable']) : 0);
                    }
                    else if(isset($_POST['mo_openid_facebook_enable'])) {
                        update_option('mo_openid_facebook_enable',0);
                        $this->mo_openid_show_facebook_error_message();

                    }
                    update_option( 'mo_openid_linkedin_enable', isset( $_POST['mo_openid_linkedin_enable']) ? sanitize_text_field($_POST['mo_openid_linkedin_enable']) : 0);
                    update_option( 'mo_openid_windowslive_enable', isset( $_POST['mo_openid_windowslive_enable']) ? sanitize_text_field($_POST['mo_openid_windowslive_enable']) : 0);
                    update_option( 'mo_openid_amazon_enable', isset( $_POST['mo_openid_amazon_enable']) ? sanitize_text_field($_POST['mo_openid_amazon_enable']) : 0);
                    update_option( 'mo_openid_instagram_enable', isset( $_POST['mo_openid_instagram_enable']) ? sanitize_text_field($_POST['mo_openid_instagram_enable']) : 0);
                    update_option( 'mo_openid_twitter_enable', isset( $_POST['mo_openid_twitter_enable']) ? sanitize_text_field($_POST['mo_openid_twitter_enable']) : 0);
                    update_option( 'mo_openid_vkontakte_enable', isset( $_POST['mo_openid_vkontakte_enable']) ? sanitize_text_field($_POST['mo_openid_vkontakte_enable']) : 0);
                    update_option( 'mo_openid_yahoo_enable', isset( $_POST['mo_openid_yahoo_enable']) ? sanitize_text_field($_POST['mo_openid_yahoo_enable']) : 0);

                    update_option( 'mo_openid_default_login_enable', isset( $_POST['mo_openid_default_login_enable']) ? sanitize_text_field($_POST['mo_openid_default_login_enable']) : 0);
                    update_option( 'mo_openid_default_register_enable', isset( $_POST['mo_openid_default_register_enable']) ? sanitize_text_field($_POST['mo_openid_default_register_enable']) : 0);
                    update_option( 'mo_openid_default_comment_enable', isset( $_POST['mo_openid_default_comment_enable']) ? sanitize_text_field($_POST['mo_openid_default_comment_enable']) : 0);



                    // GDPR options
                    update_option( 'mo_openid_gdpr_consent_enable', isset( $_POST['mo_openid_gdpr_consent_enable']) ? sanitize_text_field($_POST['mo_openid_gdpr_consent_enable']) : 0);
                    if(get_option('mo_openid_gdpr_consent_enable') == 1 && (!mo_openid_restrict_user())) {
                        update_option('mo_openid_privacy_policy_url', isset($_POST['mo_openid_privacy_policy_url']) ? sanitize_text_field($_POST['mo_openid_privacy_policy_url']) : get_option('mo_openid_privacy_policy_url'));
                        update_option('mo_openid_privacy_policy_text', isset($_POST['mo_openid_privacy_policy_text']) ? sanitize_text_field($_POST['mo_openid_privacy_policy_text']) : get_option('mo_openid_privacy_policy_text'));
                        update_option('mo_openid_gdpr_consent_message', isset($_POST['mo_openid_gdpr_consent_message']) ? stripslashes($_POST['mo_openid_gdpr_consent_message']) : get_option('mo_openid_gdpr_consent_message'));
                    }
                    //Redirect URL
                    update_option( 'mo_openid_login_redirect', sanitize_text_field($_POST['mo_openid_login_redirect']));
                    update_option( 'mo_openid_login_redirect_url', sanitize_text_field($_POST['mo_openid_login_redirect_url'] ));
                    update_option( 'mo_openid_relative_login_redirect_url', isset( $_POST['mo_openid_relative_login_redirect_url']) ? sanitize_text_field($_POST['mo_openid_relative_login_redirect_url']) : "" );

                    //Logout Url
                    update_option( 'mo_openid_logout_redirection_enable', isset( $_POST['mo_openid_logout_redirection_enable']) ? sanitize_text_field($_POST['mo_openid_logout_redirection_enable']) : 0);
                    update_option( 'mo_openid_logout_redirect', sanitize_text_field($_POST['mo_openid_logout_redirect']));
                    update_option( 'mo_openid_logout_redirect_url', sanitize_text_field($_POST['mo_openid_logout_redirect_url'] ));

                    //auto register
                    update_option( 'mo_openid_auto_register_enable', isset( $_POST['mo_openid_auto_register_enable']) ? sanitize_text_field($_POST['mo_openid_auto_register_enable']) : 0);
                    update_option( 'mo_openid_register_disabled_message', sanitize_text_field($_POST['mo_openid_register_disabled_message']));


                    //email notification
                    update_option( 'mo_openid_email_enable', isset( $_POST['mo_openid_email_enable']) ? sanitize_text_field($_POST['mo_openid_email_enable']) : 0);

                    //Customized text
                    update_option('mo_openid_login_widget_customize_text',sanitize_text_field($_POST['mo_openid_login_widget_customize_text'] ));
                    update_option( 'mo_openid_login_button_customize_text',sanitize_text_field($_POST['mo_openid_login_button_customize_text'] ));

                    //profile completion
                    update_option('mo_openid_enable_profile_completion', isset( $_POST['mo_openid_enable_profile_completion']) ? sanitize_text_field($_POST['mo_openid_enable_profile_completion']) : 0);

                    if(get_option('mo_openid_enable_profile_completion') == 1) {

                        update_option('mo_profile_complete_title', sanitize_text_field($_POST['mo_profile_complete_title']));
                        update_option('mo_profile_complete_username_label', sanitize_text_field($_POST['mo_profile_complete_username_label']));
                        update_option('mo_profile_complete_email_label', sanitize_text_field($_POST['mo_profile_complete_email_label']));
                        update_option('mo_profile_complete_submit_button', sanitize_text_field($_POST['mo_profile_complete_submit_button']));
                        update_option('mo_profile_complete_instruction', sanitize_text_field($_POST['mo_profile_complete_instruction']));
                        update_option('mo_profile_complete_extra_instruction', sanitize_text_field($_POST['mo_profile_complete_extra_instruction']));
                        update_option('mo_profile_complete_uname_exist', sanitize_text_field($_POST['mo_profile_complete_uname_exist']));

                        update_option('mo_email_verify_resend_otp_button', sanitize_text_field($_POST['mo_email_verify_resend_otp_button']));
                        update_option('mo_email_verify_back_button', sanitize_text_field($_POST['mo_email_verify_back_button']));
                        update_option('mo_email_verify_title', sanitize_text_field($_POST['mo_email_verify_title']));
                        update_option('mo_email_verify_message', sanitize_text_field($_POST['mo_email_verify_message']));
                        update_option('mo_email_verify_verification_code_instruction', sanitize_text_field($_POST['mo_email_verify_verification_code_instruction']));
                        update_option('mo_email_verify_wrong_otp', sanitize_text_field($_POST['mo_email_verify_wrong_otp']));

                        $_POST['custom_otp_msg']=stripslashes( $_POST['custom_otp_msg']);
                        update_option('custom_otp_msg',($_POST['custom_otp_msg']));
                    }
                    //account-linking
                    update_option( 'mo_openid_account_linking_enable', isset( $_POST['mo_openid_account_linking_enable']) ? sanitize_text_field($_POST['mo_openid_account_linking_enable']) : 0);

                    if(get_option('mo_openid_account_linking_enable') == 1 && (!mo_openid_restrict_user())) {

                        update_option('mo_account_linking_title', sanitize_text_field($_POST['mo_account_linking_title']));
                        update_option('mo_account_linking_new_user_button', sanitize_text_field($_POST['mo_account_linking_new_user_button']));
                        update_option('mo_account_linking_existing_user_button', sanitize_text_field($_POST['mo_account_linking_existing_user_button']));
                        update_option('mo_account_linking_new_user_instruction', sanitize_text_field($_POST['mo_account_linking_new_user_instruction']));
                        update_option('mo_account_linking_existing_user_instruction', sanitize_text_field($_POST['mo_account_linking_existing_user_instruction']));
                        update_option('mo_account_linking_extra_instruction', sanitize_text_field($_POST['mo_account_linking_extra_instruction']));
                    }

                    update_option('mo_openid_login_widget_customize_logout_name_text',sanitize_text_field($_POST['mo_openid_login_widget_customize_logout_name_text']));
                    update_option( 'mo_openid_login_widget_customize_logout_text',$_POST['mo_openid_login_widget_customize_logout_text']);
                    update_option('moopenid_logo_check', isset( $_POST['moopenid_logo_check']) ? sanitize_text_field($_POST['moopenid_logo_check']) : 0);
                    update_option('mo_login_openid_login_widget_customize_textcolor',sanitize_text_field($_POST['mo_login_openid_login_widget_customize_textcolor']));
                    update_option('mo_openid_login_theme',sanitize_text_field($_POST['mo_openid_login_theme'] ));
                    update_option( 'mo_openid_message', 'Your settings are saved successfully.' );

                    //customization of icons
                    update_option('mo_login_icon_custom_size',sanitize_text_field($_POST['mo_login_icon_custom_size'] ));
                    update_option('mo_login_icon_space',sanitize_text_field($_POST['mo_login_icon_space'] ));
                    update_option('mo_login_icon_custom_width',sanitize_text_field($_POST['mo_login_icon_custom_width'] ));
                    update_option('mo_login_icon_custom_height',sanitize_text_field($_POST['mo_login_icon_custom_height'] ));
                    update_option('mo_openid_login_custom_theme',sanitize_text_field($_POST['mo_openid_login_custom_theme'] ));
                    update_option( 'mo_login_icon_custom_color', sanitize_text_field($_POST['mo_login_icon_custom_color'] ));
                    update_option('mo_login_icon_custom_boundary',sanitize_text_field($_POST['mo_login_icon_custom_boundary']));

                    // avatar
                    update_option( 'moopenid_social_login_avatar', isset( $_POST['moopenid_social_login_avatar']) ? sanitize_text_field($_POST['moopenid_social_login_avatar']) : 0);


                    if(isset($_POST['mapping_value_default']))
                        update_option('mo_openid_login_role_mapping', isset( $_POST['mapping_value_default']) ? sanitize_text_field($_POST['mapping_value_default']) : 'subscriber');

                    if(mo_openid_is_customer_valid() && !mo_openid_get_customer_plan('Do It Yourself')){
                        //Attribute collection
                        update_option( 'moopenid_user_attributes', isset( $_POST['moopenid_user_attributes']) ? sanitize_text_field($_POST['moopenid_user_attributes']) : 0);
                    }

                    $this->mo_openid_show_success_message();
                    if(!mo_openid_is_customer_registered()) {
                        mo_openid_registeration_modal();
                    }
                }

            }else if(isset($_POST['go_to_register'])) {
                mo_openid_registeration_modal();
            } else if( isset($_POST['mo_openid_save_woocommerce_field_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_woocommerce_field" ) {
                $nonce = $_POST['mo_openid_save_woocommerce_field_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-save-woocommerce-field-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    //woocommerce integration
                    // woocommerce display options
                    if(!mo_openid_restrict_user()) {

                        update_option('mo_openid_woocommerce_login_form', isset($_POST['mo_openid_woocommerce_login_form']) ? sanitize_text_field($_POST['mo_openid_woocommerce_login_form']) : 0);
                        update_option('mo_openid_woocommerce_before_login_form', isset($_POST['mo_openid_woocommerce_before_login_form']) ? sanitize_text_field($_POST['mo_openid_woocommerce_before_login_form']) : 0);
                        update_option('mo_openid_woocommerce_center_login_form', isset($_POST['mo_openid_woocommerce_center_login_form']) ? sanitize_text_field($_POST['mo_openid_woocommerce_center_login_form']) : 0);
                        update_option('mo_openid_woocommerce_register_form_start', isset($_POST['mo_openid_woocommerce_register_form_start']) ? sanitize_text_field($_POST['mo_openid_woocommerce_register_form_start']) : 0);
                        update_option('mo_openid_woocommerce_center_register_form', isset($_POST['mo_openid_woocommerce_center_register_form']) ? sanitize_text_field($_POST['mo_openid_woocommerce_center_register_form']) : 0);
                        update_option('mo_openid_woocommerce_register_form_end', isset($_POST['mo_openid_woocommerce_register_form_end']) ? sanitize_text_field($_POST['mo_openid_woocommerce_register_form_end']) : 0);
                        update_option('mo_openid_woocommerce_before_checkout_billing_form', isset($_POST['mo_openid_woocommerce_before_checkout_billing_form']) ? sanitize_text_field($_POST['mo_openid_woocommerce_before_checkout_billing_form']) : 0);
                        update_option('mo_openid_woocommerce_after_checkout_billing_form', isset($_POST['mo_openid_woocommerce_after_checkout_billing_form']) ? sanitize_text_field($_POST['mo_openid_woocommerce_after_checkout_billing_form']) : 0);
                    }
                }
            }else if( isset($_POST['mo_openid_save_buddypress_field_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_buddypress_field" ){
                $nonce = $_POST['mo_openid_save_buddypress_field_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-save-buddypress-field-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    //buddypress display options
                    if(!mo_openid_restrict_user()) {
                        update_option('mo_openid_bp_before_register_page', isset($_POST['mo_openid_bp_before_register_page']) ? sanitize_text_field($_POST['mo_openid_bp_before_register_page']) : 0);
                        update_option('mo_openid_bp_before_account_details_fields', isset($_POST['mo_openid_bp_before_account_details_fields']) ? sanitize_text_field($_POST['mo_openid_bp_before_account_details_fields']) : 0);
                        update_option('mo_openid_bp_after_register_page', isset($_POST['mo_openid_bp_after_register_page']) ? sanitize_text_field($_POST['mo_openid_bp_after_register_page']) : 0);
                    }
                }
            }
            else if( isset($_POST['mo_openid_comment_settings_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_comment_settings" ) {
                $nonce = $_POST['mo_openid_comment_settings_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-comment-settings-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {

                    //commenting
                    update_option( 'mo_openid_social_comment_fb', isset( $_POST['mo_openid_social_comment_fb']) ? sanitize_text_field($_POST['mo_openid_social_comment_fb']) : 0);
                    update_option( 'mo_openid_social_comment_google', isset( $_POST['mo_openid_social_comment_google']) ? sanitize_text_field($_POST['mo_openid_social_comment_google']) : 0);
                    update_option( 'mo_openid_social_comment_default', isset( $_POST['mo_openid_social_comment_default']) ? sanitize_text_field($_POST['mo_openid_social_comment_default']) : 0);

                    //comment position
                    update_option( 'mo_openid_social_comment_blogpost', isset( $_POST['mo_openid_social_comment_blogpost']) ? sanitize_text_field($_POST['mo_openid_social_comment_blogpost']) : 0);
                    update_option( 'mo_openid_social_comment_static', isset( $_POST['mo_openid_social_comment_static']) ? sanitize_text_field($_POST['mo_openid_social_comment_static']) : 0);

                    //comment labels
                    update_option('mo_openid_social_comment_default_label', sanitize_text_field($_POST['mo_openid_social_comment_default_label']) );
                    update_option('mo_openid_social_comment_fb_label', sanitize_text_field($_POST['mo_openid_social_comment_fb_label']) );
                    update_option('mo_openid_social_comment_google_label', sanitize_text_field($_POST['mo_openid_social_comment_google_label']) );
                    update_option('mo_openid_social_comment_heading_label', sanitize_text_field($_POST['mo_openid_social_comment_heading_label']) );

                    update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
                    $this->mo_openid_show_success_message();
                    if(!mo_openid_is_customer_registered()) {

                        $redirect = add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] );
                        update_option('mo_openid_message', 'Your settings are successfully saved. Please  <a href=\" '. $redirect .'\">Register or Login with miniOrange</a>  to enable Social Login and Social Sharing.');
                        $this->mo_openid_show_error_message();
                    }
                }
            }
            else if( isset($_POST['mo_openid_contact_us_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_contact_us_query_option" ) {
                $nonce = $_POST['mo_openid_contact_us_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-contact-us-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    // Contact Us query
                    $email = sanitize_email($_POST['mo_openid_contact_us_email']);
                    $phone = sanitize_text_field($_POST['mo_openid_contact_us_phone']);
                    $query = sanitize_text_field($_POST['mo_openid_contact_us_query']);
                    $customer = new CustomerOpenID();
                    if ( $this->mo_openid_check_empty_or_null( $email ) || $this->mo_openid_check_empty_or_null( $query ) ) {
                        update_option('mo_openid_message', 'Please fill up Email and Query fields to submit your query.');
                        $this->mo_openid_show_error_message();
                    } else {
                        $submited = $customer->submit_contact_us( $email, $phone, $query );
                        if ( $submited == false ) {
                            update_option('mo_openid_message', 'Your query could not be submitted. Please try again.');
                            $this->mo_openid_show_error_message();
                        } else {
                            update_option('mo_openid_message', 'Thanks for getting in touch! We shall get back to you shortly.');
                            $this->mo_openid_show_success_message();
                        }
                    }
                }
            }
          else if( isset($_POST['mo_openid_go_back_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_go_back" ){
                $nonce = $_POST['mo_openid_go_back_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-go-back-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    update_option('mo_openid_registration_status','');
                    delete_option('mo_openid_new_registration');
                    delete_option('mo_openid_admin_email');
                    delete_option('mo_openid_sms_otp_count');
                    delete_option('mo_openid_email_otp_count');
                }
            }else if( isset($_POST['mo_openid_go_back_login_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_go_back_login" ){
                $nonce = $_POST['mo_openid_go_back_login_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-go-back-login-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    update_option('mo_openid_registration_status','');
                    delete_option('mo_openid_admin_email');
                    delete_option('mo_openid_admin_phone');
                    delete_option('mo_openid_admin_password');
                    delete_option('mo_openid_admin_customer_key');
                    delete_option('mo_openid_verify_customer');
                }
            }else if( isset($_POST['mo_openid_go_back_registration_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_go_back_registration" ){
                $nonce = $_POST['mo_openid_go_back_registration_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-go-back-register-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    update_option('mo_openid_verify_customer','true');
                }
            }else if( isset($_POST['mo_openid_save_other_settings_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_save_other_settings" ){
                $nonce = $_POST['mo_openid_save_other_settings_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-save-other-settings-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    update_option( 'mo_openid_google_share_enable', isset( $_POST['mo_openid_google_share_enable']) ? sanitize_text_field($_POST['mo_openid_google_share_enable']) : 0);
                    update_option( 'mo_openid_facebook_share_enable', isset( $_POST['mo_openid_facebook_share_enable']) ? sanitize_text_field($_POST['mo_openid_facebook_share_enable']) : 0);
                    update_option( 'mo_openid_linkedin_share_enable', isset( $_POST['mo_openid_linkedin_share_enable']) ? sanitize_text_field($_POST['mo_openid_linkedin_share_enable']) : 0);
                    update_option( 'mo_openid_reddit_share_enable', isset( $_POST['mo_openid_reddit_share_enable']) ? sanitize_text_field($_POST['mo_openid_reddit_share_enable']) : 0);
                    update_option( 'mo_openid_pinterest_share_enable', isset( $_POST['mo_openid_pinterest_share_enable']) ? sanitize_text_field($_POST['mo_openid_pinterest_share_enable']) : 0);
                    update_option( 'mo_openid_twitter_share_enable', isset( $_POST['mo_openid_twitter_share_enable']) ? sanitize_text_field($_POST['mo_openid_twitter_share_enable']) : 0);
                    update_option( 'mo_openid_tumblr_share_enable', isset( $_POST['mo_openid_tumblr_share_enable']) ? sanitize_text_field($_POST['mo_openid_tumblr_share_enable']) : 0);
                    update_option( 'mo_openid_delicious_share_enable', isset( $_POST['mo_openid_delicious_share_enable']) ? sanitize_text_field($_POST['mo_openid_delicious_share_enable']) : 0);
                    update_option( 'mo_openid_vkontakte_share_enable', isset( $_POST['mo_openid_vkontakte_share_enable']) ? sanitize_text_field($_POST['mo_openid_vkontakte_share_enable']) : 0);
                    update_option( 'mo_openid_stumble_share_enable', isset( $_POST['mo_openid_stumble_share_enable']) ? sanitize_text_field($_POST['mo_openid_stumble_share_enable']) : 0);
                    update_option( 'mo_openid_odnoklassniki_share_enable', isset( $_POST['mo_openid_odnoklassniki_share_enable']) ? sanitize_text_field($_POST['mo_openid_odnoklassniki_share_enable']) : 0);
                    update_option( 'mo_openid_digg_share_enable', isset( $_POST['mo_openid_digg_share_enable']) ? sanitize_text_field($_POST['mo_openid_digg_share_enable']) : 0);
                    update_option( 'mo_openid_pocket_share_enable', isset( $_POST['mo_openid_pocket_share_enable']) ? sanitize_text_field($_POST['mo_openid_pocket_share_enable']) : 0);

                    update_option( 'mo_openid_mail_share_enable', isset( $_POST['mo_openid_mail_share_enable']) ? sanitize_text_field($_POST['mo_openid_mail_share_enable']) : 0);
                    update_option( 'mo_openid_print_share_enable', isset( $_POST['mo_openid_print_share_enable']) ? sanitize_text_field($_POST['mo_openid_print_share_enable']) : 0);
                    update_option( 'mo_openid_whatsapp_share_enable', isset( $_POST['mo_openid_whatsapp_share_enable']) ? sanitize_text_field($_POST['mo_openid_whatsapp_share_enable']) : 0);
                    update_option( 'mo_openid_share_count', isset( $_POST['mo_openid_share_count']) ? sanitize_text_field($_POST['mo_openid_share_count']) : 0);
                    update_option( 'mo_openid_Facebook_share_count_api', isset( $_POST['mo_openid_Facebook_share_count_api']) ? sanitize_text_field($_POST['mo_openid_Facebook_share_count_api']) : '');

                    update_option('mo_share_options_enable_home_page',isset( $_POST['mo_share_options_home_page']) ? sanitize_text_field($_POST['mo_share_options_home_page']) : 0);
                    update_option('mo_share_options_enable_post',isset( $_POST['mo_share_options_post']) ? sanitize_text_field($_POST['mo_share_options_post']) : 0);
                    update_option('mo_share_options_enable_static_pages',isset( $_POST['mo_share_options_static_pages']) ? sanitize_text_field($_POST['mo_share_options_static_pages']) : 0);
                    update_option('mo_share_options_wc_sp_summary',isset( $_POST['mo_share_options_wc_sp_summary']) ? sanitize_text_field($_POST['mo_share_options_wc_sp_summary']) : 0);
                    update_option('mo_share_options_wc_sp_summary_top',isset( $_POST['mo_share_options_wc_sp_summary_top']) ? sanitize_text_field($_POST['mo_share_options_wc_sp_summary_top']) : 0);
                    update_option('mo_share_options_enable_post_position', sanitize_text_field($_POST['mo_share_options_enable_post_position']) );
                    update_option('mo_share_options_home_page_position', sanitize_text_field($_POST['mo_share_options_home_page_position']) );
                    update_option('mo_share_options_static_pages_position', sanitize_text_field($_POST['mo_share_options_static_pages_position']) );
                    update_option('mo_share_options_bb_forum_position', sanitize_text_field($_POST['mo_share_options_bb_forum_position']) );
                    update_option('mo_share_options_bb_topic_position', sanitize_text_field($_POST['mo_share_options_bb_topic_position']) );
                    update_option('mo_share_options_bb_reply_position', sanitize_text_field($_POST['mo_share_options_bb_reply_position']) );
                    update_option('mo_openid_share_theme', sanitize_text_field($_POST['mo_openid_share_theme']) );
                    update_option('mo_share_vertical_hide_mobile',isset( $_POST['mo_share_vertical_hide_mobile']) ? sanitize_text_field($_POST['mo_share_vertical_hide_mobile']) : 0);
                    update_option('mo_share_options_bb_forum',isset( $_POST['mo_share_options_bb_forum']) ? sanitize_text_field($_POST['mo_share_options_bb_forum']) : 0);
                    update_option('mo_share_options_bb_topic',isset( $_POST['mo_share_options_bb_topic']) ? sanitize_text_field($_POST['mo_share_options_bb_topic']) : 0);
                    update_option('mo_share_options_bb_reply',isset( $_POST['mo_share_options_bb_reply']) ? sanitize_text_field($_POST['mo_share_options_bb_reply']) : 0);
                    update_option('mo_openid_share_widget_customize_text', sanitize_text_field($_POST['mo_openid_share_widget_customize_text']) );
                    update_option('mo_openid_share_widget_customize_text_color', sanitize_text_field($_POST['mo_openid_share_widget_customize_text_color']) );
                    update_option('mo_openid_share_twitter_username', sanitize_text_field($_POST['mo_openid_share_twitter_username'])) ;
                    update_option('mo_openid_share_email_subject', sanitize_text_field($_POST['mo_openid_share_email_subject'])) ;
                    update_option('mo_openid_share_email_body', sanitize_text_field($_POST['mo_openid_share_email_body'])) ;

                    update_option('mo_openid_share_widget_customize_direction_horizontal',isset( $_POST['mo_openid_share_widget_customize_direction_horizontal']) ? sanitize_text_field($_POST['mo_openid_share_widget_customize_direction_horizontal']) : 0);
                    update_option('mo_openid_share_widget_customize_direction_vertical',isset( $_POST['mo_openid_share_widget_customize_direction_vertical']) ? sanitize_text_field($_POST['mo_openid_share_widget_customize_direction_vertical']) : 0);
                    update_option('mo_sharing_icon_custom_size',isset( $_POST['mo_sharing_icon_custom_size']) ? sanitize_text_field($_POST['mo_sharing_icon_custom_size']) : 35);
                    update_option('mo_sharing_icon_custom_color', sanitize_text_field($_POST['mo_sharing_icon_custom_color']) );
                    update_option('mo_openid_share_custom_theme', sanitize_text_field($_POST['mo_openid_share_custom_theme']) );
                    update_option('mo_sharing_icon_custom_font', sanitize_text_field($_POST['mo_sharing_icon_custom_font']) );
                    update_option('mo_sharing_icon_space', sanitize_text_field($_POST['mo_sharing_icon_space']) );
                    update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
                    $this->mo_openid_show_success_message();
                    if(!mo_openid_is_customer_registered()) {
                        $redirect = add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] );
                        update_option('mo_openid_message', 'Your settings are successfully saved. Please  <a href=\" '. $redirect .'\">Register or Login with miniOrange</a>  to enable Social Login and Social Sharing.');
                        $this->mo_openid_show_error_message();
                    }
                }
            }

            else if( isset($_POST['mo_openid_add_custom_nonce']) and isset( $_POST['option'] ) and $_POST['option'] == "mo_openid_add_custom_app" ) {
                $nonce = $_POST['mo_openid_add_custom_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-add-custom-app-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    if($this->mo_oauth_check_empty_or_null($_POST['mo_oauth_client_id']) || $this->mo_oauth_check_empty_or_null($_POST['mo_oauth_client_secret'])) {
                        update_option( 'message', 'Please enter valid Client ID and Client Secret.');
                        $this->mo_openid_show_error_message();
                        return;
                    } else{
                        $scope = stripslashes(sanitize_text_field( $_POST['mo_oauth_scope'] ));
                        $clientid = stripslashes(sanitize_text_field( $_POST['mo_oauth_client_id'] ));
                        $clientsecret = stripslashes(sanitize_text_field( $_POST['mo_oauth_client_secret'] ));
                        $appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_app_name'] ));

                        if(get_option('mo_openid_apps_list'))
                            $appslist = maybe_unserialize(get_option('mo_openid_apps_list'));
                        else
                            $appslist = array();

                        $newapp = array();

                        foreach($appslist as $key => $currentapp){
                            if($appname == $key){
                                $newapp = $currentapp;
                                break;
                            }
                        }

                        $newapp['clientid'] = $clientid;
                        $newapp['clientsecret'] = $clientsecret;
                        $newapp['scope'] = $scope;
                        $newapp['redirecturi'] = site_url().'/openidcallback';
                        if($appname=="facebook"){
                            $authorizeurl = 'https://www.facebook.com/dialog/oauth';
                            $accesstokenurl = 'https://graph.facebook.com/v2.8/oauth/access_token';
                            $resourceownerdetailsurl = 'https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=';
                        } else if($appname=="google"){
                            $authorizeurl = "https://accounts.google.com/o/oauth2/auth";
                            $accesstokenurl = "https://www.googleapis.com/oauth2/v3/token";
                            $resourceownerdetailsurl = "https://www.googleapis.com/plus/v1/people/me";
                        } else if($appname=="twitter"){
                            $authorizeurl = "https://api.twitter.com/oauth/authorize";
                            $accesstokenurl = "https://api.twitter.com/oauth/access_token";
                            $resourceownerdetailsurl = "https://dev.twitter.com/docs/api/1.1/get/account/verify_credentials?include_email=true";
                        }else {
                            $authorizeurl = stripslashes(sanitize_text_field($_POST['mo_oauth_authorizeurl']));
                            $accesstokenurl = stripslashes(sanitize_text_field($_POST['mo_oauth_accesstokenurl']));
                            $resourceownerdetailsurl = stripslashes(sanitize_text_field($_POST['mo_oauth_resourceownerdetailsurl']));
                            $appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_custom_app_name'] ));
                        }

                        $newapp['authorizeurl'] = $authorizeurl;
                        $newapp['accesstokenurl'] = $accesstokenurl;
                        $newapp['resourceownerdetailsurl'] = $resourceownerdetailsurl;
                        $appslist[$appname] = $newapp;
                        update_option('mo_openid_apps_list', maybe_serialize($appslist));
                        wp_redirect('admin.php?page=mo_openid_settings&tab=custom_app');
                    }
                }
            }
        } 


        if(isset($_POST['mo_openid_feedback_close_nonce']) and isset($_POST['mo_openid_option']) and $_POST['mo_openid_option']=='mo_openid_skip_feedback'){
            $nonce = $_POST['mo_openid_feedback_close_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-feedback-close-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
			} else {
				update_option('mo_openid_feedback_form',1);
				deactivate_plugins( '/miniorange-login-openid/miniorange_openid_sso_settings.php' );
			}

        }
        if(isset($_POST['mo_openid_feedback_nonce']) and isset($_POST['mo_openid_feedback']) and $_POST['mo_openid_feedback']=='mo_openid_feedback') {
            $nonce = $_POST['mo_openid_feedback_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-feedback-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
			} else {
                $message='';
                $email = '';
                if(isset($_POST['deactivate_plugin']) )
                {
                    $message.=' '. sanitize_text_field($_POST['deactivate_plugin']);
                    if($_POST['mo_openid_query_feedback']!='')
                    {
                        $message.='. '.sanitize_text_field($_POST['mo_openid_query_feedback']);
                    }

                    if(get_option('mo_openid_admin_email'))
                    {
                        $email=get_option('mo_openid_admin_email');
                    }

                    //only reason
                    $phone='';
                    $contact_us = new CustomerOpenID();
                    $submited = json_decode( $contact_us->mo_openid_send_email_alert( $email, $phone, $message ), true );

                    if ( json_last_error() == JSON_ERROR_NONE ) {
                        if ( is_array( $submited ) && array_key_exists( 'status', $submited ) && $submited['status'] == 'ERROR' )
                        {
                            if( isset($submited['message']))
                            {
                                update_option('mo_openid_message',$submited['message']);
                                $this->mo_openid_show_error_message();
                            }


                        } else
                        {
                            if ( $submited == false )
                            {
                                update_option( 'mo_openid_message',"ERROR_WHILE_SUBMITTING_QUERY");
                                $this->mo_openid_show_success_message();
                            } else {

                                update_option('mo_openid_message',"Your response is submitted successfully");
                                $this->mo_openid_show_success_message();
                                update_option('mo_openid_feedback_form',1);
                            }
                        }
                    }
                    update_option('mo_openid_feedback_form',1);
                    deactivate_plugins( '/miniorange-login-openid/miniorange_openid_sso_settings.php' );
                }
            }
        }
    }
	function create_customer(){
		delete_option('mo_openid_sms_otp_count');
		delete_option('mo_openid_email_otp_count');
		$customer = new CustomerOpenID();
		$customerKey = json_decode( $customer->create_customer(), true );

		if( strcasecmp( $customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0 ) {
			$this->get_current_customer();
		}
		else if((strcasecmp( $customerKey['status'], 'FAILED' ) == 0) && (strcasecmp( $customerKey['message'], 'Email is not enterprise email.' ) == 0) ){

			update_option( 'mo_openid_message',' There was an error creating an account for you. You may have entered an invalid Email-Id. <b> (We discourage the use of disposable emails) </b> Please try again with a valid email.');
			update_option('mo_openid_registration_status','EMAIL_IS_NOT_ENTERPRISE');
			$this->mo_openid_show_error_message();
			if(get_option('regi_pop_up') =="yes"){
				update_option('pop_regi_msg', get_option('mo_openid_message'));
				mo_openid_registeration_modal();
            }
        }
        else if( strcasecmp( $customerKey['status'], 'SUCCESS' ) == 0 ) {
			update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_openid_customer_token', $customerKey['token'] );
			update_option('mo_openid_admin_password', '');
			update_option('mo_openid_cust', '0');
			update_option( 'mo_openid_message', 'Registration complete!');
			update_option('mo_openid_registration_status','MO_OPENID_REGISTRATION_COMPLETE');
			delete_option('mo_openid_verify_customer');
			delete_option('mo_openid_new_registration');
			$this->mo_openid_show_success_message();
			header('Location: admin.php?page=mo_openid_settings&tab=pricing');
		}
		update_option('mo_openid_admin_password', '');
	}

	function get_current_customer(){
		$customer = new CustomerOpenID();
		$content = $customer->get_customer_key();
		$customerKey = json_decode( $content, true );

		if( isset($customerKey) ) {
			update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_openid_customer_token', $customerKey['token'] );
			update_option('mo_openid_admin_password', '' );
			update_option( 'mo_openid_message', 'Your account has been retrieved successfully.' );
			delete_option('mo_openid_verify_customer');
			delete_option('mo_openid_new_registration');
			$this->mo_openid_show_success_message();
		} else {
			update_option( 'mo_openid_message', 'You already have an account with miniOrange. Please enter a valid password.');
			update_option('mo_openid_verify_customer', 'true');
			delete_option('mo_openid_new_registration');
			$this->mo_openid_show_error_message();
            if(get_option('regi_pop_up') =="yes"){
                update_option("pop_login_msg",get_option("mo_openid_message"));
                mo_pop_show_verify_password_page();

            }
		}

	}

	function miniorange_openid_menu() {

		//Add miniOrange plugin to the menu
		$page = add_menu_page( 'MO OpenID Settings ' . __( 'Configure OpenID', 'mo_openid_settings' ), 'miniOrange Social Login, Sharing', 'manage_options',
		'mo_openid_settings', array( $this, 'mo_login_widget_openid_options' ),plugin_dir_url(__FILE__) . 'includes/images/miniorange_icon.png');
        $page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-General Settings','General Settings', 'administrator','mo_openid_general_settings',array( $this, 'mo_login_widget_openid_options' ));
        $page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-Add_On','Add-On', 'administrator','mo_openid_settings_addOn',array( $this, 'mo_login_widget_openid_options_addon' ));
        remove_submenu_page('mo_openid_settings','mo_openid_settings');
	}

	function mo_openid_plugin_actions( $links, $file ) {
	 	if( $file == 'miniorange-login-openid/miniorange_openid_sso_settings.php' && function_exists( "admin_url" ) ) {
			$settings_link = '<a href="' . admin_url( 'tools.php?page=mo_openid_settings' ) . '">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}

	public function mo_get_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$miniorange_widget = new mo_openid_login_wid();
			$html = $miniorange_widget->openidloginFormShortCode( $atts );
			return $html;
		}
	}

	public function mo_get_sharing_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$title = '';
			global $post;
			if(isset($post)) {
				$content=get_the_content();
				$title = str_replace('+', '%20', urlencode($post->post_title));
				$content=strip_shortcodes( strip_tags( get_the_content() ) );
			}
			$html = mo_openid_share_shortcode( $atts, $title);
			return $html;
		}
	}

	public function mo_get_vertical_sharing_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$title = '';
			global $post;
			if(isset($post)) {
				$content=get_the_content();
				$title = str_replace('+', '%20', urlencode($post->post_title));
				$content=strip_shortcodes( strip_tags( get_the_content() ) );
			}
			$html = mo_openid_vertical_share_shortcode( $atts, $title);
			return $html;
		}
	}

	public function mo_get_comments_output( $atts ){
		if(mo_openid_is_customer_registered()){
			$html = mo_openid_comments_shortcode();
			return $html;
		}
	}

    public function mo_get_custom_output(){
        if(mo_openid_is_customer_registered()){
            $title = '';
            global $post;
            if(isset($post)) {
                $content=get_the_content();
                $title = str_replace('+', '%20', urlencode($post->post_title));
                $content=strip_shortcodes( strip_tags( get_the_content() ) );
            }
            $curr_user=get_current_user_id();
            if($curr_user==0)
            {
                $last_name = isset($_POST['last_name'])?$_POST['last_name']:"";
                $first_name = isset($_POST['first_name'])?$_POST['first_name']:"";
                $user_full_name = isset($_POST['user_full_name'])?$_POST['user_full_name']:"";
                $user_url = isset($_POST['user_url'])?$_POST['user_url']:"";
                $call = $_POST['call'];
                $user_profile_url = isset($_POST['user_profile_url'])?$_POST['user_profile_url']:"";
                $user_picture = isset($_POST['user_picture'])?$_POST['user_picture']:"";
                $username = isset($_POST['username'])?$_POST['username']:"";
                $user_email = isset($_POST['user_email'])?$_POST['user_email']:"";
                $random_password = isset($_POST['random_password'])?$_POST['random_password']:"";
                $decrypted_app_name = isset($_POST['decrypted_app_name'])?$_POST['decrypted_app_name']:"";
                $decrypted_user_id = isset($_POST['decrypted_user_id'])?$_POST['decrypted_user_id']:"";
                $social_app_name = isset($_POST['social_app_name'])?$_POST['social_app_name']:"";
                $social_user_id = isset($_POST['social_user_id'])?$_POST['social_user_id']:"";
            }
            else
            {
                $last_name = "";
                $first_name = "";
                $user_full_name = "";
                $user_url = "";
                $call = "";
                $user_profile_url = "";
                $user_picture = "";
                $username = "";
                $user_email = "";
                $random_password = "";
                $decrypted_app_name = "";
                $decrypted_user_id = "";
                $social_app_name = "";
                $social_user_id = "";
            }
            $html = apply_filters('customization_form', $last_name, $first_name, $user_full_name, $user_url, $call, $user_profile_url, $user_picture, $username, $user_email, $random_password, $decrypted_app_name, $decrypted_user_id, $social_app_name, $social_user_id);
            return $html;
        }
    }

	function mo_social_login_custom_avatar( $avatar, $mixed, $size, $default, $alt = '' ) {

        if ( is_numeric( $mixed ) AND $mixed > 0 ) {	//Check if we have a user identifier
            $user_id = $mixed;
        } elseif ( is_string( $mixed ) AND ( $user = get_user_by( 'email', $mixed )) ) {	//Check if we have a user email
        	$user_id = $user->ID;
        } elseif ( is_object( $mixed ) AND property_exists( $mixed, 'user_id' ) AND is_numeric( $mixed->user_id ) ) {		//Check if we have a user object
            $user_id = $mixed->user_id;
        } else {		//None found
            $user_id = null;
        }

        if (  !empty( $user_id ) ) {    //User found?
            $filename = '';
            if ($this->mo_openid_is_buddypress_active()) {
                $filename = bp_upload_dir();
                $filename = $filename['basedir'] . "/avatars/" . $user_id;
            }
            if (!(is_dir($filename))) {
                $user_meta_thumbnail = get_user_meta($user_id, 'moopenid_user_avatar', true);        //Read the avatar
                $user_meta_name = get_user_meta($user_id, 'user_name', true);        //read user details
                $user_picture = (!empty($user_meta_thumbnail) ? $user_meta_thumbnail : '');
                if ($user_picture !== false AND strlen(trim($user_picture)) > 0) {    //Avatar found?
                    return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
                }
            }
        }
        return $avatar;
	}

	function mo_social_login_buddypress_avatar( $text, $args) {
		if(is_array($args)){
			if(!empty($args['object']) && strtolower($args['object']) == 'user'){
				if(!empty($args['item_id']) && is_numeric($args['item_id'])) {
                    $filename = '';
                    if ($this->mo_openid_is_buddypress_active()) {
                        $filename = bp_upload_dir();
                        $filename = $filename['basedir'] . "/avatars/" . $args['item_id'];
                    }
                    if (!(is_dir($filename))) {
                        if (($userdata = get_userdata($args['item_id'])) !== false) {
                            $user_meta_thumbnail = get_user_meta($userdata->ID, 'moopenid_user_avatar', true);        //Read the avatar
                            $user_meta_name = $userdata->user_login;        //read user details
                            $user_picture = (!empty($user_meta_thumbnail) ? $user_meta_thumbnail : '');
                            $size = (!empty($args['width']) ? 'width="' . $args['width'] . '" ' : 'width="50"');
                            if ($user_picture !== false AND strlen(trim($user_picture)) > 0) {    //Avatar found?
                                return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
                            }
                        }
                    }
                }
			}
		}
		return $text;
	}

	function mo_social_login_custom_avatar_url( $url, $id_or_email, $args = null ) {
		if ( is_numeric( $id_or_email ) AND $id_or_email > 0 ) {	//Check if we have an user identifier
			$user_id = $id_or_email;
		} elseif ( is_string( $id_or_email ) AND ( $user = get_user_by( 'email', $id_or_email )) ) {	//Check if we have an user email
			$user_id = $user->ID;
		} elseif ( is_object( $id_or_email ) AND property_exists( $id_or_email, 'user_id' ) AND is_numeric( $id_or_email->user_id ) ) {		//Check if we have an user object
			$user_id = $id_or_email->user_id;
		} else {		//None found
			$user_id = null;
		}

		if (  !empty( $user_id ) ) {
            $filename = '';
            if ($this->mo_openid_is_buddypress_active()) {
                $filename = bp_upload_dir();
                $filename = $filename['basedir'] . "/avatars/" . $user_id;
            }
            if (!(is_dir($filename))) {
                $user_meta_thumbnail = get_user_meta($user_id, 'moopenid_user_avatar', true);
                $user_picture = (!empty($user_meta_thumbnail) ? $user_meta_thumbnail : $url);
                return $user_picture;
            }
        }
		return $url;
	}

    function mo_openid_is_buddypress_active(){
        include_once(ABSPATH.'wp-admin/includes/plugin.php');
        if(is_plugin_active('buddypress/bp-loader.php') )
            return true;
        else
            return false;
    }
}
new Miniorange_OpenID_SSO;
?>