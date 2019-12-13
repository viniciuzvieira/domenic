<?php
ob_start();
function mo_register_openid() {

            if(get_option('mo_openid_social_comment_google') == 1){
                ?>
                <div id="upgrade_notice" class="update-nag" style="width: 92.5%;margin-left: 0%;"><strong>Google+ APIs will be shut down soon on March 7, 2019. So please disable the Google+ commenting option from<a href="<?php echo site_url();?>/wp-admin/admin.php?page=mo_openid_settings&tab=comment    " ><b>Social Comments</b></a> tab.</strong></div>
            <?php }
	if( isset( $_GET[ 'tab' ]) && $_GET[ 'tab' ] !== 'register' ) {
		$active_tab = $_GET[ 'tab' ];
	} else if(mo_openid_is_customer_registered()) {
		$active_tab = 'login';
	} else if(!isset($_GET[ 'tab' ])) {
        $active_tab = 'login';
	}
	else{
        $active_tab = $_GET[ 'tab' ];
    }

	if(mo_openid_is_curl_installed()==0){ ?>
		<p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
	<?php
	}?>
    <div>
        <table>
        <tr><td>
            <img id="logo" style="margin-top: 25px" src="<?php echo plugin_dir_url(__FILE__);?>includes/images/logo.png">
            </td> <td>&nbsp;<a style="text-decoration:none" href="https://www.miniorange.com/social-login-social-sharing/" target="_blank"><h1 style="color: #c9302c">miniOrange Social Login</h1></a></td>
            <td>  &nbsp;&nbsp;&nbsp;&nbsp; <a id="pricing" style="margin-top: 23px" class="button"<?php echo $active_tab == 'pricing' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'pricing'), $_SERVER['REQUEST_URI'] ); ?>">Licensing Plans</a>
            </td> <td>&nbsp; <a id="privacy" style="margin-top: 23px" class="button" <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'privacy_policy'), $_SERVER['REQUEST_URI'] ); ?>">Privacy Policy</a>
            </td> <td> &nbsp; <a id="faq" style="margin-top: 23px" class="button" <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'help'), $_SERVER['REQUEST_URI'] ); ?>">FAQ</a>
            </td><td> &nbsp<a id="addon" style="margin-top: 23px;background: #FFA335;color: white;" class="button" <?php echo $active_tab == 'addon' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'addon'), $_SERVER['REQUEST_URI'] ); ?>">Add On</a>
            </td>
        </tr> </table>
    </div>
<div id="tab">
	<h2 class="nav-tab-wrapper">
		<a id="social_login" class="nav-tab <?php echo $active_tab == 'login' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login'), $_SERVER['REQUEST_URI'] ); ?>">Social Login</a>
		<a id="custom_app" class="nav-tab <?php echo $active_tab == 'custom_app' ? 'nav-tab-active' : ''; ?>" href="admin.php?page=mo_openid_settings&tab=custom_app">Custom App</a>
        <a id="email_notif" class="nav-tab <?php echo $active_tab == 'email_notif' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'email_notif'), $_SERVER['REQUEST_URI'] ); ?>">Email Notification</a>
        <a id="integration" class="nav-tab <?php echo $active_tab == 'integrations' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'integrations'), $_SERVER['REQUEST_URI'] ); ?>">Advanced Settings</a>
        <a id="sharing" class="nav-tab <?php echo $active_tab == 'share' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'share'), $_SERVER['REQUEST_URI'] ); ?>">Social Sharing</a>
		<a id="commenting" class="nav-tab <?php echo $active_tab == 'comment' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'comment'), $_SERVER['REQUEST_URI'] ); ?>">Social Comments</a>
		<a id="shortcode" class="nav-tab <?php echo $active_tab == 'shortcode' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'shortcode'), $_SERVER['REQUEST_URI'] ); ?>">Shortcode</a>
        <?php if(!mo_openid_is_customer_registered()) { ?>
            <a class="nav-tab <?php echo $active_tab == 'register' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Account Setup</a>
        <?php } ?>
    </h2>
</div>

    <script>
        var base_url = '<?php echo site_url();?>';

        var tour1 = new Tour({
                name: "tour1",
                steps: [
                    {
                        element: "#social_login",
                        title: "Social Login",
                        content: "Enable one click login to your website using various Social Login applications.",
                        backdrop:'body',
                        backdropPadding:'6',

                    },
                    {
                        element: "#select_app",
                        title: "Select Apps",
                        content: "Select multiple apps to enable Social Login.",
                        backdrop:'body',
                        backdropPadding:'6'
                    },
                    {
                        element: "#display_opt",
                        title: "Display Options",
                        content: "Select the pages where you want to show the social login icons.",

                        backdrop:'body',
                        backdropPadding:'6'
                    },
                    {
                        element: "#redirect_opt",
                        title: "Redirection Option",
                        content: "Redirect user after login and logout on your website. ",
                        backdrop:'body',
                        backdropPadding:'6',


                    },
                    {
                        element: "#premium_ad",
                        title: "Premium Features",
                        content: "These are the most commonly used features. Please update your license to standard/premium to get them.",

                        backdrop:'body',
                        backdropPadding:'6'
                    },
                    {
                        element: "#addon",
                        title: "Add Ons",
                        content: "Introduced a new Add On which helps to store and update user's extra information during registration through social login.",
                        backdrop:'body',
                        backdropPadding:'6',
                        onNext: function() {
                            window.location = base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=custom_app'
                        }

                    },{
                        element: "#custom_app",
                        title: "Custom App",
                        content: "Configure your own custom apps for social login applications.",
                        backdrop:'body',
                        backdropPadding:'6',
                        onNext: function() {
                            window.location = base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=email_notif'
                        }

                    },
                    {
                        element: "#email_notif",
                        title: "Email Notifications",
                        content: "Send customized email notifications to admin and users.",
                        backdrop:'body',
                        backdropPadding:'6',
                        onNext: function() {
                            window.location = base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=integrations'
                        }
                    }
                    ,
                    {
                        element: "#integration",
                        title: "Premium Features",
                        content: "Use our BuddyPress and WooCommerce integrations to map user information. Add users to MailChimp lists during social login.",
                        backdrop:'body',
                        backdropPadding:'6',
                        onNext: function() {
                            window.location = base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=pricing'
                        }
                    },
                    {
                        element: "#pricing",
                        title: "Licensing Plans",
                        content: "Check out more features to choose the best plan for you.",
                        backdrop:'body',
                        backdropPadding:'6',

                    }
                ],
                  template: function () {
                        var return_value;
                            if(tour1.getCurrentStep()===9)
                                return_value="<div class=\"mo2f_popover\" role=\"tooltip\" style='margin-top: 2%'> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0%;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\"><div style=\"width:47%;margin-top: -7%;\"><h4  style=\"float:left;margin-top:30%;margin-left:30%;\">"+ (tour1.getCurrentStep()+1)+"/10</h4></div></div>&nbsp;&nbsp; <button class=\"button button-primary button-large\" data-role=\"end\" onclick=\"end_tour1();\">Done</button> </div> </div>";
                                else {
                                    if(tour1.getCurrentStep()===5)
                                        return_value = "<div class=\"mo2f_popover\" role=\"tooltip\" style='margin-top: 2%'> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\" style=\"width: 100%;\"><div style='margin-top: -6%;' ><h4>"+ (tour1.getCurrentStep()+1)+"/10</h4></div> <button class=\"mo2f_tour_btn mo2f_tour_btn-sm mo2f_tour_btn-secondary mo2f_tour_btn_next-success\" style=\"width: 32%;height: 0%;margin-left: 25%;\" data-role=\"next\">Next &raquo;</button><button class=\"button button-primary button-large\" data-role=\"end\" style='margin-left: 7%'onclick=\"window.location= base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=login'\">Close</button></div></div></div>";
                                    else
                                        return_value = "<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\" style=\"width: 100%;\"><div style='margin-top: -6%;' ><h4>"+ (tour1.getCurrentStep()+1)+"/10</h4></div> <button class=\"mo2f_tour_btn mo2f_tour_btn-sm mo2f_tour_btn-secondary mo2f_tour_btn_next-success\" style=\"width: 32%;height: 0%;margin-left: 25%;\" data-role=\"next\">Next &raquo;</button><button class=\"button button-primary button-large\" data-role=\"end\" style='margin-left: 7%'onclick=\"window.location= base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=login'\">Close</button></div></div></div>";
                            }
                      return (return_value);
                    }
            });

        var temp = "<?php echo get_option('mo_openid_tour'); ?>";
          if(temp=="0") { // Initialize the tour
              tour1.init();
              // Start the tour
              tour1.start();
          }

            function end_tour() {
                tour.end();
                tour1.end();
            }

        function end_tour1() {
              var tour_variable = "plugin_tour";
            jQuery.ajax({
                url:base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=pricing', //the page containing php script
                method: "POST", //request type,
                data: {update_tour_status: tour_variable},
                dataType: 'text',
                success:function(result){
                    window.location= base_url+'/wp-admin/admin.php?page=mo_openid_settings&tab=pricing';
                }
            });

        }

        function popup_delete_app(selected_app){
			<?php $nonce = wp_create_nonce( 'mo-openid-delete-selected-app-nonce' ); ?>
            if (confirm("Are you sure you want to delete the app?")) {
                location.href='admin.php?page=mo_openid_settings&tab=custom_app&action=delete&app=' + selected_app + '&wp_nonce=' + '<?php echo $nonce ?>';

            }
        }
    </script>

<div id="mo_openid_settings">

	<div class="mo_container">
		<div id="mo_openid_msgs"></div>
			<table style="width:100%;">
				<tr>
                    <?php
							if( $active_tab == 'share' ) {
								mo_openid_other_settings();
							} else if ( $active_tab == 'register') {
								if (get_option ( 'mo_openid_verify_customer' ) == 'true') {
									mo_openid_show_verify_password_page();
								} else if (trim ( get_option ( 'mo_openid_admin_email' ) ) != '' && trim ( get_option ( 'mo_openid_admin_api_key' ) ) == '' && get_option ( 'mo_openid_new_registration' ) != 'true') {
									mo_openid_show_verify_password_page();
								} else if(get_option('mo_openid_registration_status') == 'MO_OTP_DELIVERED_SUCCESS' || get_option('mo_openid_registration_status') == 'MO_OTP_VALIDATION_FAILURE' || get_option('mo_openid_registration_status') == 'MO_OTP_DELIVERED_FAILURE' ){
									mo_openid_show_otp_verification();
								}else if (! mo_openid_is_customer_registered()) {
									delete_option ( 'password_mismatch' );
									mo_openid_show_new_registration_page();
								}
							} else if($active_tab == 'login'){
								mo_openid_apps_config();
							}
                            else if($active_tab == 'email_notif'){
                                mo_openid_email_notification();
                            }
                            else if($active_tab == 'integrations'){
                                mo_openid_integrations();
                            }
                            else if($active_tab == 'comment'){
								mo_openid_app_comment();
							} else if($active_tab == 'shortcode') {
								mo_openid_shortcode_info();
							} else if($active_tab == 'pricing') {
								mo_openid_pricing_info();
							} else if($active_tab == 'help') {
                                mo_openid_troubleshoot_info();
                            } else if($active_tab == 'addon') {
                                header('Location: '.site_url().'/wp-admin/admin.php?page=mo_openid_settings_addOn');
							} else if($active_tab == 'privacy_policy'){
                                mo_openid_privacy_policy();
                            }
							else if($active_tab == 'custom_app') {
								?>
                    <td style="vertical-align:top;width:65%;">
                                    <?php mo_openid_custom_app_config();?>
								</td>
								
								<td style="vertical-align:top;padding-left:1%;">
                                    <?php echo miniorange_openid_support(); ?>
								</td></tr></table></div></div>
    <?php
							}
							
							

						?>
			</table>
    <?php
}

function mo_register_openid_addon() {
    if( isset( $_GET[ 'tab' ]) && $_GET[ 'tab' ] !== 'register' ) {
        $active_tab = $_GET[ 'tab' ];
    } else if(mo_openid_is_customer_registered()) {
        $active_tab = 'customization';
    } else {
        $active_tab = 'register';
    }
    if(mo_openid_is_curl_installed()==0){ ?>
        <p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
        <?php
    }?>
    <div >
        <table>
            <tr><td>

                    <img id="logo" style="margin-top: 25px" src="<?php echo plugin_dir_url(__FILE__);?>includes/images/logo.png">
                </td> <td>&nbsp;<a style="text-decoration:none" href="https://www.miniorange.com/social-login-social-sharing/" target="_blank"><h1 style="color: #c9302c">miniOrange Social Login Add-on</h1></a></td>
                <td>  &nbsp;&nbsp;&nbsp;&nbsp; <a id="pricing" style="margin-top: 23px" class="button"<?php echo $active_tab == 'pricing' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'pricing'), $_SERVER['REQUEST_URI'] ); ?>">Licensing Plans</a>
                </td> <td>&nbsp; <a id="privacy" style="margin-top: 23px" class="button" <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'privacy_policy'), $_SERVER['REQUEST_URI'] ); ?>">Privacy Policy</a>
                </td> <td> &nbsp; <a id="faq" style="margin-top: 23px" class="button" <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'help'), $_SERVER['REQUEST_URI'] ); ?>">FAQ</a>
                </td></tr> </table>
    </div>
    <div id="tab">
        <h2 class="nav-tab-wrapper">
            <a id="social_login" class="nav-tab <?php echo $active_tab == 'customization' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'customization'), $_SERVER['REQUEST_URI'] ); ?>">Integration Add-on</a>

            <?php if(!mo_openid_is_customer_registered()) { ?>
                <a class="nav-tab <?php echo $active_tab == 'register' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Account Setup</a>
            <?php } ?>
        </h2>
    </div>

    <table id="mo_openid_settings">
        <tr class="mo_container">
            <div id="mo_openid_msgs"></div>
            <table style="width:100%;">
                <tr>
                    <td style="vertical-align:top;">
                        <?php
                        if($active_tab == 'pricing') {
								mo_openid_pricing_info();
							}
                        else if($active_tab == 'help') {
                                mo_openid_troubleshoot_info();
                            }
                        else if($active_tab == 'privacy_policy'){
                            mo_openid_privacy_policy();
                        }
                        else if($active_tab == 'customization'){
                            if(mo_openid_is_customer_registered() && mo_openid_is_customer_addon_license_key_verified()) {
                                if(is_plugin_active('miniorange-login-openid-extra-attributes-addon/miniorange_openid_sso_customization_addon.php'))
                                    do_action('customization_addon');
                                else {
                                    mo_openid_show_addon_message_page();
                                }
                            }
                            else
                                mo_openid_addon();
                        }
                        else if ( $active_tab == 'register') {
                            mo_openid_show_verify_password_page();
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <?php
            }

function mo_openid_show_new_registration_page() {
    update_option('regi_pop_up','no');
	update_option ( 'mo_openid_new_registration', 'true' );
	global $current_user;
	$current_user = wp_get_current_user();
	?>
    <td style="vertical-align:top;width:65%;">
		<!--Register with miniOrange-->
					<form name="f" method="post" action="" id="register-form">
								<input type="hidden" name="option" value="mo_openid_connect_register_customer" />
								<input type="hidden" name="mo_openid_connect_register_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-connect-register-nonce' ); ?>"/>
								
								
								<div class="mo_openid_table_layout">
                                    <h3>Register with miniOrange</h3>

										<p>Please enter a valid email that you have access to. You will be able to move forward after verifying an OTP that we will be sending to this email. <b>OR</b> Login using your miniOrange credentials.
										</p>
										<table class="mo_openid_settings_table">
											<tr>
												<td><b><font color="#FF0000">*</font>Email:</b></td>
												<td><input class="mo_openid_table_textbox" type="email" name="email"
													required placeholder="person@example.com"
													value="<?php echo esc_attr($current_user->user_email);?>" /></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Password:</b></td>
												<td><input class="mo_openid_table_textbox" required type="password"
													name="password" placeholder="Choose your password (Min. length 6)" /></td>
											</tr>
											<tr>
												<td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
												<td><input class="mo_openid_table_textbox" required type="password"
													name="confirmPassword" placeholder="Confirm your password" /></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td><br /><input type="submit" name="submit" value="Submit" style="width:100px;"
													class="button button-primary button-large" />
													<input type="button" value="Login Page" id="mo_openid_go_back_registration" style="width:150px;"
													class="button button-primary button-large" />
												</td>
											</tr>
										</table>
									<br/>By clicking Submit, you agree to our <a href="https://www.miniorange.com/usecases/miniOrange_Privacy_Policy.pdf" target="_blank">Privacy Policy</a> and <a href="https://www.miniorange.com/usecases/miniOrange_User_Agreement.pdf" target="_blank">User Agreement</a>.
								</div>
				</form>
				<form name="f" method="post" action="" id="openidgobackloginform">
					<input type="hidden" name="option" value="mo_openid_go_back_registration"/>
					<input type="hidden" name="mo_openid_go_back_registration_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-go-back-register-nonce' ); ?>"/>
				</form>
				<script>
						jQuery('#mo_openid_go_back_registration').click(function() {
							jQuery('#openidgobackloginform').submit();
						});
						var text = "&nbsp;&nbsp;We will call only if you need support."
						jQuery('.intl-number-input').append(text);

				</script>
		</td>
		<td style="vertical-align:top;padding-left:1%;">
            <?php echo miniorange_openid_support(); ?>
        </td>
    <?php
}

function mo_openid_show_verify_password_page() {
    update_option('regi_pop_up','no');
    ?>
    <td style="vertical-align:top;width:65%;">
			<!--Verify password with miniOrange-->
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_openid_connect_verify_customer" />
			<input type="hidden" name="mo_openid_connect_verify_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-connect-verify-nonce' ); ?>"/>
			<div class="mo_openid_table_layout">
                <h3>Login with miniOrange</h3>
				<p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password. <a href="#forgot_password">Click here if you forgot your password?</a></b></p>
				<table class="mo_openid_settings_table">
					<tr>
						<td><b><font color="#FF0000">*</font>Email:</b></td>
						<td><input class="mo_openid_table_textbox" id="email" type="email" name="email"
							required placeholder="person@example.com"
							value="<?php echo esc_attr(get_option('mo_openid_admin_email'));?>" /></td>
					</tr>
					<td><b><font color="#FF0000">*</font>Password:</b></td>
					<td><input class="mo_openid_table_textbox" required type="password"
						name="password" placeholder="Choose your password" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" value="Login"
							class="button button-primary button-large" />
						<input type="button" value="Registration Page" id="mo_openid_go_back"
													class="button button-primary button-large" />
						</td>
					</tr>
				</table>
			</div>
		</form>
		<form name="f" method="post" action="" id="openidgobackform">
				<input type="hidden" name="option" value="mo_openid_go_back_login"/>
				<input type="hidden" name="mo_openid_go_back_login_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-go-back-login-nonce' ); ?>"/>
			</form>
		<form name="forgotpassword" method="post" action="" id="openidforgotpasswordform">
			<input type="hidden" name="option" value="mo_openid_forgot_password"/>
			<input type="hidden" name="mo_openid_forgot_password_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-forgot-password-nonce' ); ?>"/>
			<input type="hidden" id="forgot_pass_email" name="email" value=""/>
		</form>
		<script>
			jQuery('#mo_openid_go_back').click(function() {
				jQuery('#openidgobackform').submit();
			});
			jQuery('a[href="#forgot_password"]').click(function(){
				jQuery('#forgot_pass_email').val(jQuery('#email').val());
				jQuery('#openidforgotpasswordform').submit();
			});
		</script>
		</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>
		<?php
}

function mo_openid_apps_config() {

	?>
	<td style="vertical-align:top;width:65%;">
		<!-- Google configurations -->
				<form id="form-apps" name="form-apps" method="post" action="">
					<input type="hidden" name="option" value="mo_openid_enable_apps" />
					<input type="hidden" name="mo_openid_enable_apps_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-enable-apps-nonce' ); ?>"/>
					<div class="mo_openid_table_layout">
							<table>
									<tr>
										<td colspan="2">
											<h3>Social Login
                                                <input type="submit" name="submit" id="save_opt" value="Save" style="float:right; margin-right:2%; margin-top: -3px;width:100px;"	class="button button-primary button-large" onclick="end_tour();" />	<button type="button" id="mo_openid_restart_tour" class="button button-primary button-large" style="float:right; margin-right:2%; margin-top: -3px;width:20%" onclick="restart_tour();"><i class="fa fa-refresh"></i> Social Login Tour</button>
												</h3>
												<b>Select applications to enable login for your users. Customize your login icons using a range of shapes, themes and sizes. You can choose different places to display these icons and also customize redirect URL after login.</b>
										</td>
									</tr>
								</table>
							<table>
                               <tr id="select_app"><td><table style="width: 100%">
                                           <h3>Select Apps</h3>
								<h4>Select Default Apps:</h4>
								<tr >
									<td>
                                        <table style="width:100%">
											<tr >
                                            	<td>
												<input type="checkbox" id="google_enable" class="app_enable" name="mo_openid_google_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_google_enable') == 1 );?> /><strong>Google</strong>
												</td>
												<td>
												<input type="checkbox" id="vkontakte_enable" class="app_enable" name="mo_openid_vkontakte_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_vkontakte_enable') == 1 );?> /><strong>Vkontakte</strong>
												</td>
												<td>
												<input type="checkbox" id="twitter_enable" class="app_enable" name="mo_openid_twitter_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_twitter_enable') == 1 );?> /><strong>Twitter</strong>
												</td>
												<td>
												<input title="Instagram doesn't return email address. Scroll down and check 'Prompt users for email' in advanced settings. Also setup SMTP for sending verification emails." type="checkbox"
												id="instagram_enable" class="app_enable" name="mo_openid_instagram_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_instagram_enable') == 1 );?> /><strong>*Instagram</strong>
												</td>
												<td>
												<input type="checkbox" id="linkedin_enable" class="app_enable" name="mo_openid_linkedin_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_linkedin_enable') == 1 );?> /><strong>LinkedIn</strong>
												</td>
											</tr>
											<tr>

												<td>
												<input type="checkbox" id="amazon_enable" class="app_enable" name="mo_openid_amazon_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_amazon_enable') == 1 );?> /><strong>Amazon</strong>
												</td>
												<td>
												<input type="checkbox" id="salesforce_enable" class="app_enable" name="mo_openid_salesforce_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_salesforce_enable') == 1 );?> /><strong>Salesforce</strong>
												</td>
												<td>
												<input type="checkbox" id="windowslive_enable" class="app_enable" name="mo_openid_windowslive_enable" value="1" onchange="previewLoginIcons();"
												<?php checked( get_option('mo_openid_windowslive_enable') == 1 );?> /><strong>Windows Live</strong>
												</td>
												<td>  <input type="checkbox" id="yahoo_enable" class="app_enable" name="mo_openid_yahoo_enable" value="1" onchange="previewLoginIcons();"
                            <?php checked( get_option('mo_openid_yahoo_enable') == 1 );?> /><strong>Yahoo</strong>
                    </td><td>
                        <input type="checkbox" id="wordpress_enable" class="app_enable" name="mo_openid_wordpress_enable" value="1" onchange="previewLoginIcons();"
                            disabled="disabled" /><strong><span style="color: red">*</span>WordPress</strong>
                    </td>
                    				</tr>
										</table>
									</td>
								</tr>
								<tr><td><h4>Setup Custom Apps:</h4></td>
								</tr>
								<tr>
                                    <td>
                                        <div>
                                                        <input type="checkbox" id="facebook_enable" class="app_enable tooltip" name="mo_openid_facebook_enable" value="1" <?php $x=is_custom_app('facebook');echo "onchange='previewLoginIcons(),redirectto_custom_tab(".$x.")'";?>
                                                        <?php checked( get_option('mo_openid_facebook_enable') == 1 );?> /><strong> Facebook </strong>
                                             &nbsp &nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp  <input type="checkbox" class="app_enable"  disabled="disabled" /><strong><span style="color: red">*</span>Disqus</strong>
                                            &nbsp &nbsp &nbsp&nbsp &nbsp&nbsp &nbsp  <input type="checkbox" class="app_enable"  disabled="disabled" /><strong><span style="color: red">*</span>Vimeo</strong>
                                            &nbsp &nbsp &nbsp&nbsp &nbsp&nbsp &nbsp  <input type="checkbox" class="app_enable"  disabled="disabled" /><strong><span style="color: red">*</span>Kakao</strong>
                                                </div>
                                    </td>
                                </tr>
								</table></td></tr>
								<tr>
					            <td>

						<br>
						<strong><span style="color: red">*</span>Available in standard and premium plugins.</strong><br/>
						<br><strong>Note:</strong> Enabling Apps from here will only display Social Login Icons in Wordpress default login. To use Social Login options on the pages other than Wordpress default login/registration form use <a href="admin.php?page=mo_openid_settings&tab=shortcode" target="_blank">Shortcode</a>
							<hr>
<tr id="custom_icon"><td><table style="width:100%">							<h3>Customize Login Icons</h3>
							<p>Customize shape, theme and size of social login icons</p>
							</td>
		</tr>
		<tr>
		<td>
			<b>Shape</b>
			<b style="margin-left:130px;">Theme</b>
			<b style="margin-left:130px;">Space between Icons</b>
			<b style="margin-left:70px;">Size of Icons</b>
			</td>
		</tr>
		<tr >

				<td class="mo_openid_table_td_checkbox">
					<input type="radio"    name="mo_openid_login_theme" value="circle" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value ,'circle',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)"
								<?php checked( get_option('mo_openid_login_theme') == 'circle' );?> />Round

				<span style="margin-left:106px;">
					<input type="radio" id="mo_openid_login_default_radio"  name="mo_openid_login_custom_theme" value="default" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'default',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)"
								<?php checked( get_option('mo_openid_login_custom_theme') == 'default' );?>/>Default

				</span>

				<span  style="margin-left:111px;">
						<input style="width:50px" onkeyup="moLoginSpaceValidate(this)" id="mo_login_icon_space" name="mo_login_icon_space" type="text" value="<?php echo esc_attr(get_option('mo_login_icon_space'))?>" />
						<input id="mo_login_space_plus" type="button" value="+" onmouseup="moLoginPreview(setSizeOfIcons() ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" />
						<input id="mo_login_space_minus" type="button" value="-" onmouseup="moLoginPreview(setSizeOfIcons()  ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" />
				</span>


				<span id="commontheme" style="margin-left:115px">
				<input style="width:50px" id="mo_login_icon_size" onkeyup="moLoginSizeValidate(this)" name="mo_login_icon_custom_size" type="text" value="<?php echo esc_attr(get_option('mo_login_icon_custom_size'))?>" >
				<input id="mo_login_size_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" >
				<input id="mo_login_size_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_size').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" >

				</span>
				<span style="margin-left:91px" class="longbuttontheme">Width:&nbsp;
				<input style="width:50px" id="mo_login_icon_width" onkeyup="moLoginWidthValidate(this)" name="mo_login_icon_custom_width" type="text" value="<?php echo esc_attr(get_option('mo_login_icon_custom_width'))?>" >
				<span style="margin-left:3px;">

				<input id="mo_login_width_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" >
				<input id="mo_login_width_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value ,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" >

				</span>
				</span>


				</td>
		</tr>

		<tr>
				<td class="mo_openid_table_td_checkbox">
				<input type="radio"   name="mo_openid_login_theme"  value="oval" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value,'oval',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_size').value,document.getElementById('mo_login_icon_custom_boundary').value )"
								<?php checked( get_option('mo_openid_login_theme') == 'oval' );?> />Rounded Edges

				<span style="margin-left:50px;">
						<input type="radio" id="mo_openid_login_custom_radio"  name="mo_openid_login_custom_theme" value="custom" onclick="checkLoginButton();moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)"
								<?php checked( get_option('mo_openid_login_custom_theme') == 'custom' );?> />Custom Background*

						</span>



					<span style="margin-left:235px" class="longbuttontheme" >Height:
				<input style="width:50px" id="mo_login_icon_height" onkeyup="moLoginHeightValidate(this)" name="mo_login_icon_custom_height" type="text" value="<?php echo esc_attr(get_option('mo_login_icon_custom_height'))?>" >
				<span style="margin-left:1px;">

				<input id="mo_login_height_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" >
				<input id="mo_login_height_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" >
				</span>
				</span>
				</td>
		</tr>

		<tr>
				<td class="mo_openid_table_td_checkbox">
						<input type="radio"   name="mo_openid_login_theme" value="square" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_size').value ,'square',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_size').value,document.getElementById('mo_login_icon_custom_boundary').value )"
								<?php checked( get_option('mo_openid_login_theme') == 'square' );?> />Square

						<span style="margin-left:113px;">
						<input id="mo_login_icon_custom_color" style="width:135px;" name="mo_login_icon_custom_color"  class="color" value="<?php echo esc_attr(get_option('mo_login_icon_custom_color'))?>" onchange="moLoginPreview(setSizeOfIcons(), setLoginTheme(),'custom',document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_custom_boundary').value)" >
						</span>


						<span style="margin-left:228px" class="longbuttontheme">Curve:&nbsp;
						<input style="width:50px" id="mo_login_icon_custom_boundary" onkeyup="moLoginBoundaryValidate(this)" name="mo_login_icon_custom_boundary" type="text" value=
						"<?php echo esc_attr(get_option('mo_login_icon_custom_boundary'))?>" />
						<span style="margin-left:4px;">

						<input id="mo_login_boundary_plus" type="button" value="+" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" />
				<input id="mo_login_boundary_minus" type="button" value="-" onmouseup="moLoginPreview(document.getElementById('mo_login_icon_width').value,setLoginTheme(),setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)" />
                         </span>
						 </span>
				</td>
		</tr>
		<tr>
				<td class="mo_openid_table_td_checkbox">
						<input type="radio" id="iconwithtext"   name="mo_openid_login_theme" value="longbutton" onclick="checkLoginButton();moLoginPreview(document.getElementById('mo_login_icon_width').value ,'longbutton',setLoginCustomTheme(),document.getElementById('mo_login_icon_custom_color').value,document.getElementById('mo_login_icon_space').value,document.getElementById('mo_login_icon_height').value,document.getElementById('mo_login_icon_custom_boundary').value)"
								<?php checked( get_option('mo_openid_login_theme') == 'longbutton' );?> />Long Button with Text</td>
		</tr>
		<tr>
			<td>	<br><b>Preview : </b><br/><span hidden id="no_apps_text">No apps selected</span>
				<div>
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_facebook" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_google" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_vkontakte" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_twitter" src="<?php echo plugins_url( 'includes/images/icons/twitter.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_instagram" src="<?php echo plugins_url( 'includes/images/icons/instagram.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_linkedin" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_amazon" src="<?php echo plugins_url( 'includes/images/icons/amazon.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_salesforce" src="<?php echo plugins_url( 'includes/images/icons/salesforce.png', __FILE__ )?>" />
					<img class="mo_login_icon_preview" id="mo_login_icon_preview_windowslive" src="<?php echo plugins_url( 'includes/images/icons/windowslive.png', __FILE__ )?>" />
				    <img class="mo_login_icon_preview" id="mo_login_icon_preview_yahoo" src="<?php echo plugins_url( 'includes/images/icons/yahoo.png', __FILE__ )?>" />
                </div>

				<div>
					<a id="mo_login_button_preview_facebook" class="btn btn-block btn-defaulttheme btn-social btn-facebook btn-custom-size"> <i class="fa" style="border-right:none;"><svg xmlns="http://www.w3.org/2000/svg" style="margin-top:12%;margin-left: 2%;" ><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"/></svg></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Facebook</a>
				  <a style="background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" id="mo_login_button_preview_google" class="btn btn-block btn-defaulttheme btn-social btn-google btn-custom-size"> <i class="fa"style="border-right:none;padding-top:3% !important"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70 70" style="padding-left: 8%;padding-top: 20%;" ><defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"/><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"/><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"/><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"/></svg></i><?php
                                                ?> <span style="color:#ffffff;"><?php echo esc_html(get_option('mo_openid_login_button_customize_text'));?> Google</span></a>
					<a id="mo_login_button_preview_vkontakte" class="btn btn-block btn-defaulttheme btn-social btn-vk btn-custom-size"> <i class="fa fa-vk"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Vkontakte</a>
					<a id="mo_login_button_preview_twitter" class="btn btn-block btn-defaulttheme btn-social btn-twitter btn-custom-size"> <i class="fa fa-twitter"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Twitter</a>
					<a id="mo_login_button_preview_instagram" class="btn btn-block btn-defaulttheme btn-social btn-instagram btn-custom-size"> <i class="fa fa-instagram"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Instagram</a>
					<a id="mo_login_button_preview_linkedin" class="btn btn-block btn-defaulttheme btn-social btn-linkedin btn-custom-size"> <i class="fa fa-linkedin"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> LinkedIn</a>
					<a id="mo_login_button_preview_amazon" class="btn btn-block btn-defaulttheme btn-social btn-soundcloud btn-custom-size"> <i class="fa fa-amazon"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Amazon</a>
					<a id="mo_login_button_preview_salesforce" class="btn btn-block btn-defaulttheme btn-social btn-vimeo btn-custom-size"> <i class="fa fa-cloud"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Salesforce</a>
					<a id="mo_login_button_preview_windowslive" class="btn btn-block btn-defaulttheme btn-social btn-microsoft btn-custom-size"> <i class="fa fa-windows"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Windows</a>
				    <a id="mo_login_button_preview_yahoo" class="btn btn-block btn-defaulttheme btn-social btn-yahoo btn-custom-size"> <i class="fa fa-yahoo"></i><?php
                                    echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Yahoo</a>

				</div>
				<div>
					<i class="mo_custom_login_icon_preview fa fa-facebook" id="mo_custom_login_icon_preview_facebook"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-google" id="mo_custom_login_icon_preview_google"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-vk" id="mo_custom_login_icon_preview_vkontakte"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-twitter" id="mo_custom_login_icon_preview_twitter" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_login_icon_preview fa fa-instagram" id="mo_custom_login_icon_preview_instagram" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-linkedin" id="mo_custom_login_icon_preview_linkedin" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-amazon" id="amazoncustom" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_login_icon_preview fa fa-cloud" id="salesforcecustom" style="margin-bottom:-10px;color:#ffffff;text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_login_icon_preview fa fa-windows" id="mo_custom_login_icon_preview_windows" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
				    <i class="mo_custom_login_icon_preview fa fa-yahoo" id="mo_custom_login_icon_preview_yahoo" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
				</div>
				<div>
					<a id="mo_custom_login_button_preview_facebook"  class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa" style="border-right:none;"><svg xmlns="http://www.w3.org/2000/svg" style="margin-top:12%;margin-left: 2%;" ><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"/></svg></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Facebook</a>
					<a id="mo_custom_login_button_preview_google" class="btn btn-block btn-customtheme btn-social   btn-custom-size"> <i class="fa fa-google"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Google</a>
					<a id="mo_custom_login_button_preview_vkontakte" class="btn btn-block btn-customtheme btn-social   btn-custom-size"> <i class="fa fa-vk"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Vkontakte</a>
					<a id="mo_custom_login_button_preview_twitter" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-twitter"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Twitter</a>
					<a id="mo_custom_login_button_preview_instagram" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-instagram"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Instagram</a>
					<a id="mo_custom_login_button_preview_linkedin" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-linkedin"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> LinkedIn</a>
					<a id="mo_custom_login_button_preview_amazon" class="btn btn-block btn-customtheme btn-social  btn-custom-size"><i class="fa fa-amazon"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Amazon</a>
					<a id="mo_custom_login_button_preview_salesforce" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-cloud"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Salesforce</a>
					<a id="mo_custom_login_button_preview_windows" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-windows"></i><?php
									echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Windows</a>
				<a id="mo_custom_login_button_preview_yahoo" class="btn btn-block btn-customtheme btn-social  btn-custom-size"> <i class="fa fa-yahoo"></i><?php
                                    echo esc_html(get_option('mo_openid_login_button_customize_text')); 	?> Yahoo</a>

            </div>
		</td>
	</tr>
	<tr>
		<td><br>
		<strong>*NOTE:</strong><br/>Custom background: This will change the background color of login icons.
		</td>
	</tr></table></td></tr>
	<tr>
	<tr>
		<td>
			<br>
			<hr>
			<h3>Customize Text For Social Login Buttons / Icons</h3>
		</td>
	</tr>


	<tr>
		<td>
			<b>Select color for customize text:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span style="margin-left:15px;">
			<input id="mo_openid_table_textbox" style="width:135px;" name="mo_login_openid_login_widget_customize_textcolor"  class="color" value="<?php echo esc_attr(get_option('mo_login_openid_login_widget_customize_textcolor'))?>" > </td>
			</span>
		</td>
	</tr>

	<tr>
		<td><b>Enter text to show above login widget:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<input class="mo_openid_table_textbox" style="margin-left:12px;width:50%" type="text" name="mo_openid_login_widget_customize_text" value="<?php echo esc_attr(get_option('mo_openid_login_widget_customize_text')); ?>" /></td>
	</tr>
	<tr>
		<td><b>Enter text to show on your login buttons (If you have</b>
			<br/><b> selected shape 4 from 'Customize Login Icons' section):</b>&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="mo_openid_table_textbox" style="width:50%" type="text" name="mo_openid_login_button_customize_text"
			 value="<?php echo esc_attr(get_option('mo_openid_login_button_customize_text')); ?>"  /></td>
	</tr>

	<tr>
		<td>
			<br>
			<hr>
			<h3>Customize Text to show user after Login</h3>
		</td>
	</tr>
	<tr>
		<td><b>Enter text to show before the logout link</b>
			<br/>Use ##username## to display current username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="mo_openid_table_textbox" style="width:50%" type="text" name="mo_openid_login_widget_customize_logout_name_text"  value="<?php echo esc_attr(get_option('mo_openid_login_widget_customize_logout_name_text')); ?>" /></td>
	</tr>
	<tr>
		<td><b>Enter text to show as logout link:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="mo_openid_table_textbox" style="width:50%" type="text" name="mo_openid_login_widget_customize_logout_text"
			 value="<?php echo (get_option('mo_openid_login_widget_customize_logout_text')); ?>"  /></td>
	</tr>

                                         <tr><td>
                                                <hr>
                                               <tr id="display_opt"><td><table style="width:100%">
                                                <h3>Display Options</h3>
                                                <b>Select the options where you want to display the social login icons</b>
                                            </td></tr>

											<tr>
												<td class="mo_openid_table_td_checkbox">
												<input type="checkbox" id="default_login_enable" name="mo_openid_default_login_enable" value="1"
														<?php checked( get_option('mo_openid_default_login_enable') == 1 );?> />Default Login Form</td>
											</tr>
											<tr>
												<td class="mo_openid_table_td_checkbox">
												<input type="checkbox" id="default_register_enable" name="mo_openid_default_register_enable" value="1"
													<?php checked( get_option('mo_openid_default_register_enable') == 1 );?> />Default Registration Form</td>
											</tr>
											<tr>
												<td class="mo_openid_table_td_checkbox">
													<input type="checkbox" id="default_comment_enable" name="mo_openid_default_comment_enable" value="1"
														<?php checked( get_option('mo_openid_default_comment_enable') == 1 );?> />Comment Form</td>
                                            </tr>
                                            <tr><td>
                                            <h3><a id="openid_login_shortcode_title"  aria-expanded="false" >Add Login Icons</a></h3>
                                            <div hidden="" id="openid_login_shortcode" style="font-size:13px !important">

						You can add login icons in the following areas from <strong>Display Options</strong>. For other areas(widget areas), use Login Widget.
					<ol>
						<li>Default Login Form: This option places login icons below the default login form on wp-login.</li>
						<li>Default Registration Form: This option places login icons below the default registration form.</li>
						<li>Comment Form: This option places login icons above the comment section of all your posts.</li>
					</ol>
</div>
</td></tr>
<tr><td>
<h3><a   id="openid_sharing_shortcode_title"  >Add Login Icons as Widget</a></h3>
				<div hidden="" id="openid_sharing_shortcode" style="font-size:13px !important">
					<ol>
						<li>Go to Appearance->Widgets. Among the available widgets you
							will find miniOrange Social Login Widget, drag it to the widget area where
							you want it to appear.</li>
						<li>Now logout and go to your site. You will see app icon for which you enabled login.</li>
						<li>Click that app icon and login with your existing app account to wordpress.</li>
					</ol>
					</div>
</td></tr>
                      <tr><td>
                      <h3><a   id="openid_comments_shortcode_title"  >Using Shortcode</a></h3>
				<div hidden="" id="openid_comments_shortcode" style="font-size:13px !important">

				<?php echo"* Detailed information about how to use shortcode is given in <a href=" . site_url() ."/wp-admin/admin.php?page=mo_openid_settings&tab=shortcode>Shortcode</a> tab";?>
				</div>

</td></tr>                      </table></td></tr>
                                <tr><td>
                                        <br>
                                    <?php echo"NOTE: BuddyPress and Woocomerce display options are available in <a href=" . site_url() ."/wp-admin/admin.php?page=mo_openid_settings&tab=integrations>Advanced Settings</a>.";?>
                                        <hr>
                                    </td>
                                </tr>

								<tr id="redirect_opt"><td><table style="width: 100%"><tr>
									<td>
                                        <h3>Redirection Options</h3>
										<b>Redirect URL after login:</b>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_same_page" name="mo_openid_login_redirect" value="same"
										 <?php checked( get_option('mo_openid_login_redirect') == 'same' );?> /><b>*</b>Same page where user logged in
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_homepage" name="mo_openid_login_redirect" value="homepage"
										 <?php checked( get_option('mo_openid_login_redirect') == 'homepage' );?> />Homepage
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_dashboard" name="mo_openid_login_redirect" value="dashboard"
										 <?php checked( get_option('mo_openid_login_redirect') == 'dashboard' );?> />Account dashboard
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="login_redirect_customurl" name="mo_openid_login_redirect" value="custom"
										 <?php checked( get_option('mo_openid_login_redirect') == 'custom' );?> />Custom URL
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="url" id="login_redirect_url" style="width:50%;margin-left: 12px;" name="mo_openid_login_redirect_url" value="<?php echo esc_url(get_option('mo_openid_login_redirect_url'))?>" />
									</td>
								</tr>
                                <tr>
                                    <td>
                                        <input type="radio" id="login_redirect_relativeurl" name="mo_openid_login_redirect" value="relative" <?php checked( get_option('mo_openid_login_redirect') == 'relative' );?> />Relative URL
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo site_url();?>
                                        <input type="text" id="login_redirect_url" style="width:50%" name="mo_openid_relative_login_redirect_url" value="<?php echo esc_url(get_option('mo_openid_relative_login_redirect_url'))?>" />
                                    </td>
                                </tr>

								<tr><td>&nbsp;</td></tr>
								<tr>
									<td>
										<input type="checkbox" id="logout_redirection_enable" name="mo_openid_logout_redirection_enable" value="1"
													<?php checked( get_option('mo_openid_logout_redirection_enable') == 1 );?> />
										<b>Enable Logout Redirection</b>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_home" name="mo_openid_logout_redirect" value="homepage"
										 <?php checked( get_option('mo_openid_logout_redirect') == 'homepage' );?> />Home Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_current" name="mo_openid_logout_redirect" value="currentpage"
										 <?php checked( get_option('mo_openid_logout_redirect') == 'currentpage' );?> />Current Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_login" name="mo_openid_logout_redirect" value="login"
										 <?php checked( get_option('mo_openid_logout_redirect') == 'login' );?> />Login Page
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" id="logout_redirect_customurl" name="mo_openid_logout_redirect" value="custom"
										 <?php checked( get_option('mo_openid_logout_redirect') == 'custom' );?> />Relative URL
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php echo site_url();?>
										<input type="text" id="logout_redirect_url" style="width:50%" name="mo_openid_logout_redirect_url" value="<?php echo esc_url(get_option('mo_openid_logout_redirect_url'))?>" />
									</td>
								</tr>
								</table></td></tr>
								<tr>
									<td>
										<br>
                                        <b>*NOTE</b>: If you login through wordpress default login page after login you will be redirected to Homepage.
										<hr>
										<h3>Registration Options</h3>
									</td>
								</tr>
								<tr>
									<td>
										If Auto-register users is unchecked, users will not be able to register using Social Login. The users who already have an account will be able to login.  This setting stands true only when users are registering using Social Login. This will not interfere with users registering through the regular WordPress.
										<br/><br/>
										<input type="checkbox" id="auto_register_enable" name="mo_openid_auto_register_enable" value="1"
											<?php checked( get_option('mo_openid_auto_register_enable') == 1 );?> /><b>Auto-register users</b>
										<br/><br/>
										<b>Registration disabled message: </b>
										<textarea id="auto_register_disabled_message" style="width:80%" name="mo_openid_register_disabled_message" ><?php echo esc_textarea(get_option('mo_openid_register_disabled_message'))?></textarea>
									</td>
								</tr>
                                <tr>
                                    <td>
                                        <h3 style="font-size: 16px;">Role Mapping</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Use Role Mapping to assign this universal role to the all users registering through Social Login.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br/>
                                        <b>Universal Role: </b>
                                        <br>
                                        <select name="mapping_value_default" style="width:30%" id="default_group_mapping">
                                            <?php
                                            if(get_option('mo_openid_login_role_mapping'))
                                                $default_role = get_option('mo_openid_login_role_mapping');
                                            else
                                                $default_role = get_option('default_role');
                                            wp_dropdown_roles($default_role); ?>
                                        </select>
                                    </td>
                                </tr>
                              <?php if(!mo_openid_restrict_user()){ ?>
                                 <tr>
                                    <td>
                                        <br>
                                        <hr>
                                        <h3>GDPR Settings</h3>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        If GDPR check is enabled, users will be asked to give consent before using Social Login. Users who will not give consent will not be able to log in. This setting stands true only when users are registering using Social Login. This will not interfere with users registering through the regular WordPress. (Click <a target="_blank" href="<?php echo add_query_arg( array('tab' => 'privacy_policy'), $_SERVER['REQUEST_URI'] ); ?>"> here </a> to read miniOrange Social Login Privacy Policy. Please update your website's privacy policy accordingly and enter the URL to your privacy policy below.)
                                        <br/><br/>
                                        <input type="checkbox" name="mo_openid_gdpr_consent_enable" value="1"
                                            <?php checked( get_option('mo_openid_gdpr_consent_enable') == 1 );?> /><b>Take consent from users</b>
                                        <br/><br/>
                                        <b>Enter the Consent message: </b><br>
                                        <textarea  style="width:80%" name="mo_openid_gdpr_consent_message"><?php echo esc_textarea(get_option('mo_openid_gdpr_consent_message'))?></textarea>
                                        <br><br>
                                        <b>Enter the text that redirects to Privacy Policy URL: </b><br>
                                        <input type="text" style="width:90%" name="mo_openid_privacy_policy_text" value="<?php echo esc_attr(get_option('mo_openid_privacy_policy_text'))?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled';?>/>
                                        <br><br>
                                        <b>Enter Privacy Policy URL: </b><br>
                                        <input type="text" style="width:90%" name="mo_openid_privacy_policy_url" value="<?php echo esc_url(get_option('mo_openid_privacy_policy_url'))?>" <?php if(!mo_openid_is_customer_registered()) echo 'disabled';?> />
                                    </td>
                                </tr>
                                <td>
										<br>
										<hr>
										<h3>Account Linking</h3>
									</td>
									<tr>
										<td>
											Enable account linking to let your users link their Social accounts with existing WordPress account. Users will be prompted with the option to either link to any existing account using WordPress login page or register as a new user.<br><br>
											<input type="checkbox" id="account_linking_enable" name="mo_openid_account_linking_enable" value="1"
												<?php checked( get_option('mo_openid_account_linking_enable') == 1 );?> onclick="customize_account_linking()" /><b>Enable Account-Linking</b>
                                         </td>

									</tr>

<?php if(get_option('mo_openid_account_linking_enable')){?>
									<tr id="account_link_customized_text"><td><h3 style="float: left">Customize Text for Account Linking</h3><a style="float: right;margin-right: 325px;margin-top: 20px" onclick="customize_account_linking_img()">View Dialogue Box</a></td></tr>
									<tr id="acc_link_img"><td></td></tr>
									<tr id="account_link_customized_text"><td><b>1. Enter title of Account linking form:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left:135px;" type="text" name="mo_account_linking_title" value="<?php echo esc_attr(get_option('mo_account_linking_title')); ?>" /></td></tr>
<tr id="account_link_customized_text"><td><b>2. Enter button text for create new user:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 115px" type="text" name="mo_account_linking_new_user_button" value="<?php echo esc_attr(get_option('mo_account_linking_new_user_button')); ?>"/></td></tr>
<tr id="account_link_customized_text">
<td>
<b>3. Enter button text for Link to existing user:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left:89px" type="text" name="mo_account_linking_existing_user_button" value="<?php echo esc_attr(get_option('mo_account_linking_existing_user_button')); ?>"/></td></tr>
<tr id="account_link_customized_text"><td ><b>4. Enter instruction to Create New Account :</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px" type="text" name="mo_account_linking_new_user_instruction" value="<?php echo esc_attr(get_option('mo_account_linking_new_user_instruction')); ?>"/>
</td>
</tr>
<tr id="account_link_customized_text">
<td>
<b>5. Enter instructions to link to an existing account :</b><input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px" type="text" name="mo_account_linking_existing_user_instruction" value="<?php echo esc_attr(get_option('mo_account_linking_existing_user_instruction')); ?>"/></td></tr><tr id="account_link_customized_text"><td><b>5. Enter extra instructions for account linking :</b><input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px;" type="text" name="mo_account_linking_extra_instruction" value="<?php echo esc_attr(get_option('mo_account_linking_extra_instruction')); ?>"/>
</td>
</tr>
<?php }?><?php ;} ?>
				<tr id="acc_link"><td> </td></tr>

		<tr>
		<td>
		<br>
		<hr>
		<h3>Profile Completion</h3>

            <input type="checkbox" id="profile_completion_enable" name="mo_openid_enable_profile_completion" value="1" <?php checked( get_option('mo_openid_enable_profile_completion') == '1' );?> onclick="customize_profile_completion()"><b>Prompt users for username &amp; email when unavailable (profile completion)</b>
            <br>In case of unavailability of username or email from the social media application, user is prompted to input the same.
            <p style="color:#000000;">
                <b>*NOTE:</b><br> Disabling profile completion is not recommended. Instagram and Twitter don't return email address. Please keep this enabled if you are using Instagram or Twitter. This feature requires SMTP to be setup.</p>


		</td>
		</tr>
		</tr>

	<?php if(get_option('mo_openid_enable_profile_completion')){?>
		<tr id="profile_completion_customized_text"><td><h3 style="float: left">Customize Text for Profile Completion</h3><a style="float: right;margin-right: 300px;margin-top: 20px" onclick="customize_profile_completion_img()">View Dialogue Box</a></td></tr>
		<tr id="profile_completion_img"><td></td></tr>
		<tr id="profile_completion_customized_text"><td><b>1. Enter title of Profle Completion:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 150px" type="text" name="mo_profile_complete_title" value="<?php echo esc_attr(get_option('mo_profile_complete_title')); ?>" /></td></tr>
		<tr id="profile_completion_customized_text"><td><b>2. Enter Username Label text:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 177px" type="text" name="mo_profile_complete_username_label" value="<?php echo esc_attr(get_option('mo_profile_complete_username_label')); ?>"/></td></tr>
		<tr id="profile_completion_customized_text"><td><b>3. Enter Email Label text:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 205px" type="text" name="mo_profile_complete_email_label" value="<?php echo esc_attr(get_option('mo_profile_complete_email_label')); ?>"/></td></tr>
		<tr id="profile_completion_customized_text"><td><b>4. Enter Submit button text:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 184px" type="text" name="mo_profile_complete_submit_button" value="<?php echo esc_attr(get_option('mo_profile_complete_submit_button')); ?>"/></td></tr>
		<tr id="profile_completion_customized_text"><td><b style="margin-top:20px">5. Enter instruction for Profile Completion :</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px;margin-bottom:5px" type="text" name="mo_profile_complete_instruction" value="<?php echo esc_attr(get_option('mo_profile_complete_instruction')); ?>"/></td></tr>
        <tr id="profile_completion_customized_text"><td><b>6. Enter extra instruction for Profile Completion :</b><input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px" type="text" name="mo_profile_complete_extra_instruction" value="<?php echo esc_attr(get_option('mo_profile_complete_extra_instruction')); ?>"/></td></tr>
        <tr id="profile_completion_customized_text"><td><b>Enter username already exists warning message text :</b><input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px" type="text" name="mo_profile_complete_uname_exist" value="<?php echo esc_attr(get_option('mo_profile_complete_uname_exist')); ?>"/></td></tr>
        <tr id="profile_completion_customized_text"><td><h3 style="float: left">Customize Text for Email Verification</h3><a style="float: right;margin-right: 300px;margin-top: 20px" onclick="customize_email_verify_img()">View Dialogue Box
        </a></td></tr> <tr id="email_verify"><td></td></tr><tr id="profile_completion_customized_text"><td><b>1. Enter title of Email Verification form:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 125px" type="text" name="mo_email_verify_title" value="<?php echo esc_attr(get_option('mo_email_verify_title')); ?>"/></td></tr><tr id="profile_completion_customized_text"><td><b>2. Enter Resend OTP button text:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 160px" type="text" name="mo_email_verify_resend_otp_button" value="<?php echo esc_attr(get_option('mo_email_verify_resend_otp_button')); ?>"/></td></tr><tr id="profile_completion_customized_text"><td><b>3. Enter Back button text:</b><input  class="mo_openid_table_textbox" style="width:50%;margin-left: 202px" type="text" name="mo_email_verify_back_button" value="<?php echo esc_attr(get_option('mo_email_verify_back_button')); ?>"/></td></tr><tr id="profile_completion_customized_text"><td><b>4. Enter instruction for Email Verification form:</b><input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px" type="text" name="mo_email_verify_message" value="<?php echo esc_attr(get_option('mo_email_verify_message')); ?>"/></td></tr><tr id="profile_completion_customized_text"><td><b>5. Enter verification code in Email Verification form:</b><input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px" type="text" name="mo_email_verify_verification_code_instruction" value="<?php echo esc_attr(get_option('mo_email_verify_verification_code_instruction')); ?>"/></td></tr>
        <tr id="profile_completion_customized_text"><td><b> Enter Message for wrong OTP :</b><input  class="mo_openid_table_textbox" style="width:98%;margin-left: 0px" type="text" name="mo_email_verify_wrong_otp" value="<?php echo esc_attr(get_option('mo_email_verify_wrong_otp')); ?>"/></td></tr>
        <tr id="profile_completion_customized_text"><td><br><h3> <b>Customized OTP Message:</b> </h3><textarea class="tinymce" rows="6" id="mo_openid_email_message_id" style="width:100%" name="custom_otp_msg" ><?php echo esc_html(get_option('custom_otp_msg'))?></textarea></td></tr>
<tr id='profile_completion_customized_text'><td><b>NOTE</b>: Please enter <b>##otp##</b> in message where you want to show otp.</td></tr>
<?php } ?>
		 <tr id="prof_completion"><td> </td></tr>



<script>
var custom_link;
var custom_prof_completion;
var custom_link_img;
var custom_profile_img;
var custom_email_verify_img;
var checkbox1 = document.getElementById('account_linking_enable');
var checkbox2 = document.getElementById('profile_completion_enable');
                function customize_account_linking(){
                    if (checkbox1.checked == true){
                     if(custom_link==1){
                         jQuery("<tr id=\"account_link_customized_text\"><td><h3 style=\"float: left\">Customize Text for Account Linking</h3><a style=\"float: right;margin-right: 302px;margin-top: 20px\" onclick=\"customize_account_linking_img()\">View Dialogue Box</a></td></tr><tr id=\"acc_link_img\"><td></td></tr><tr id=\"account_link_customized_text\"><td><b>1. Enter title of Account linking form:</b>\n"+
"\n"+"\t\t\t<input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 132px;margin-bottom:0px\" type=\"text\" name=\"mo_account_linking_title\" value=\"<?php echo esc_attr(get_option('mo_account_linking_title')); ?>\" /></td>\n"+"\t</tr><tr id=\"account_link_customized_text\"><td><b>2. Enter button text for create new user:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 115px;margin-bottom:0px\" type=\"text\" name=\"mo_account_linking_new_user_button\" value=\"<?php echo get_option('mo_account_linking_new_user_button'); ?>\"/></td></tr><tr id=\"account_link_customized_text\"><td><b>3. Enter button text for Link to existing user:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 89px;margin-bottom:0px\" type=\"text\" name=\"mo_account_linking_existing_user_button\" value=\"<?php echo esc_attr(get_option('mo_account_linking_existing_user_button')); ?>\"/></td></tr><tr id=\"account_link_customized_text\">\n"+"\t\t<td ><b style=\"margin-top:20px\">4. Enter instruction to Create New Account :</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n"+
" <input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px;margin-bottom:0px\" type=\"text\" name=\"mo_account_linking_new_user_instruction\" value=\"<?php echo esc_attr(get_option('mo_account_linking_new_user_instruction')); ?>\"/></td>\n"+
"\t</tr><tr id=\"account_link_customized_text\"><td><b>5. Enter instructions to link to an existing account :</b><input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px;margin-bottom:0px\" type=\"text\" name=\"mo_account_linking_existing_user_instruction\" value=\"<?php echo esc_attr(get_option('mo_account_linking_existing_user_instruction')); ?>\"/></td></tr><tr id=\"account_link_customized_text\"><td><b>5. Enter extra instructions for account linking :</b><input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px;margin-bottom:0px\" type=\"text\" name=\"mo_account_linking_extra_instruction\" value=\"<?php echo esc_attr(get_option('mo_account_linking_extra_instruction')); ?>\"/></td></tr>").insertBefore(jQuery("#acc_link"));
                        custom_link=2;
                         }
                         }
                         if(checkbox1.checked != true){
                        jQuery("tr[id*=account_link_customized_text]").remove();
                        jQuery("tr[id*=acc_link_img]").remove();
                          custom_link=1;

                         }
                }

 function customize_profile_completion(){
                    if (checkbox2.checked == true){
                     if(custom_prof_completion==1) {
                         jQuery("<tr id=\"profile_completion_customized_text\"><td><h3 style=\"float: left\">Customize Text for Profile Completion</h3><a style=\"float: right;margin-right: 300px;margin-top: 20px\" onclick=\"customize_profile_completion_img()\">View Dialogue Box</a></td></tr><tr id=\"profile_completion_img\"><td></td></tr><tr id=\"profile_completion_customized_text\"><td><b>1. Enter title of Profle Completion:</b>\n"+
"\n"+"\t\t\t<input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 145px;margin-bottom:5px\" type=\"text\" name=\"mo_profile_complete_title\" value=\"<?php echo esc_attr(get_option('mo_profile_complete_title')); ?>\" /></td>\n"+"\t</tr><tr id=\"profile_completion_customized_text\"><td><b>2. Enter Username Label text:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 175px;margin-bottom:5px\" type=\"text\" name=\"mo_profile_complete_username_label\" value=\"<?php echo esc_attr(get_option('mo_profile_complete_username_label')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b>3. Enter Email Label text:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 202px;margin-bottom:5px\" type=\"text\" name=\"mo_profile_complete_email_label\" value=\"<?php echo esc_attr(get_option('mo_profile_complete_email_label')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b>4. Enter Submit button text:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 182px;margin-bottom:5px\" type=\"text\" name=\"mo_profile_complete_submit_button\" value=\"<?php echo esc_attr(get_option('mo_profile_complete_submit_button')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\">\n"+"\t\t<td ><b style=\"margin-top:20px\">5. Enter instruction for Profile Completion :</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n"+
" <input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px;margin-bottom:5px\" type=\"text\" name=\"mo_profile_complete_instruction\" value=\"<?php echo esc_attr(get_option('mo_profile_complete_instruction')); ?>\"/></td>\n"+
"\t</tr><tr id=\"profile_completion_customized_text\"><td><b>6. Enter extra instruction for Profile Completion :</b><input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px;margin-bottom:5px\" type=\"text\" name=\"mo_profile_complete_extra_instruction\" value=\"<?php echo esc_attr(get_option('mo_profile_complete_extra_instruction')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b>Enter username already exists warning message text :</b><input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px;margin-bottom:5px\" type=\"text\" name=\"mo_profile_complete_uname_exist\" value=\"<?php echo esc_attr(get_option('mo_profile_complete_uname_exist'));?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><h3 style=\"float: left\">Customize Text for Email Verification</h3><a style=\"float: right;margin-right: 300px;margin-top: 20px\" onclick=\"customize_email_verify_img();\">View Dialogue Box</a></td></tr><tr id=\"email_verify\"><td></td></tr><tr id=\"profile_completion_customized_text\"><td><b>1. Enter title of Email Verification form:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 125px\" type=\"text\" name=\"mo_email_verify_title\" value=\"<?php echo esc_attr(get_option('mo_email_verify_title')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b>2. Enter Resend OTP button text:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 160px\" type=\"text\" name=\"mo_email_verify_resend_otp_button\" value=\"<?php echo esc_attr(get_option('mo_email_verify_resend_otp_button')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b>3. Enter Back button text:</b><input  class=\"mo_openid_table_textbox\" style=\"width:50%;margin-left: 201px\" type=\"text\" name=\"mo_email_verify_back_button\" value=\"<?php echo esc_attr(get_option('mo_email_verify_back_button')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b>4. Enter instruction for Email Verification form:</b><input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px\" type=\"text\" name=\"mo_email_verify_message\" value=\"<?php echo get_option('mo_email_verify_message'); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b>5. Enter verification code in Email Verification form:</b><input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px\" type=\"text\" name=\"mo_email_verify_verification_code_instruction\" value=\"<?php echo esc_attr(get_option('mo_email_verify_verification_code_instruction')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><b> Enter Message for wrong OTP :</b><input  class=\"mo_openid_table_textbox\" style=\"width:98%;margin-left: 0px\" type=\"text\" name=\"mo_email_verify_wrong_otp\" value=\"<?php echo esc_attr(get_option('mo_email_verify_wrong_otp')); ?>\"/></td></tr><tr id=\"profile_completion_customized_text\"><td><br><h3> <b>Customized OTP Message:</b> </h3><textarea class=\"tinymce\" rows=\"6\" style=\"width:100%\" name=\"custom_otp_msg\">"+ <?php echo json_encode(get_option('custom_otp_msg'));?>+"</textarea></td></tr><tr id='profile_completion_customized_text'><td><b>NOTE</b>: Please enter <b>##otp##</b> in message where you want to show otp.</td></tr>").insertBefore(jQuery("#prof_completion"));
                        custom_prof_completion=2;
                         }
                         }
                         if(checkbox2.checked != true){
                        jQuery("tr[id*=profile_completion_customized_text]").remove();
                          jQuery("tr[id*=email_verify]").remove();
                          jQuery("tr[id*=profile_completion_img]").remove();
                          custom_prof_completion=1;

                         }
                   }


             jQuery(document).ready(function(){
                 custom_link= 1;
                 custom_prof_completion=1;
                 custom_link_img=1;
                 custom_profile_img=1;
                 custom_email_verify_img=1;
            }
            );

function customize_account_linking_img(){
                 if(custom_link_img==1){
                     jQuery("<tr id=\"account_linking_img\"><td><img style=\"margin-top: 15px;margin-left: 15px;\" src=\"<?php echo plugin_dir_url(__FILE__);?>includes/images/account_linking.png\"></td></tr>").insertBefore(jQuery("#acc_link_img"));
                                custom_link_img=2;
                       }else{
                            jQuery("#account_linking_img").remove();
                          custom_link_img=1;
                      }
                }
function customize_profile_completion_img(){
                 if(custom_profile_img==1){
                     jQuery("<tr id=\"profile_completion_img\"><td><img style=\"margin-top: 15px;margin-left: 85px;\" src=\"<?php echo plugin_dir_url(__FILE__);?>includes/images/profile_completion.png\" height=\"400\" width=\"550\"></td></tr>").insertBefore(jQuery("#profile_completion_img"));
                                custom_profile_img=2;
                       }else{
                            jQuery("#profile_completion_img").remove();
                          custom_profile_img=1;
                      }
                }
function customize_email_verify_img(){
                 if(custom_email_verify_img==1){
                     jQuery("<tr id=\"email_verify_img\"><td><img style=\"margin-top: 15px;margin-left: 85px;\" src=\"<?php echo plugin_dir_url(__FILE__);?>includes/images/email_verify.png\" height=\"350\" width=\"550\"></td></tr>").insertBefore(jQuery("#email_verify"));
                                custom_email_verify_img=2;
                       }else{
                            jQuery("#email_verify_img").remove();
                          custom_email_verify_img=1;
                      }
                }
</script>
				<script>
				 function redirectto_custom_tab(x)
                                    {
                                        var site_url = '<?php echo site_url();?>';
										var nonce = '<?php echo wp_create_nonce( 'mo-openid-add-selected-app-nonce' ); ?>';
                                        box1 = document.getElementById('facebook_enable');
                                        if (box1.checked == true){
                                        if(x==0){
                                            window.location= site_url+"/wp-admin/admin.php?page=mo_openid_settings&tab=custom_app&action=add&wp_nonce=" + nonce + "&app_name=facebook";
                                       }
                                       if(x==1){
                                            window.location= site_url+"/wp-admin/admin.php?page=mo_openid_settings&tab=custom_app&setup_msg";
                                       }
                                     }
                                   }

					var tempHorSize = '<?php echo esc_attr(get_option('mo_login_icon_custom_size')) ?>';
					var tempHorTheme = '<?php echo esc_attr(get_option('mo_openid_login_theme')) ?>';
					var tempHorCustomTheme = '<?php echo esc_attr(get_option('mo_openid_login_custom_theme')) ?>';
					var tempHorCustomColor = '<?php echo esc_attr(get_option('mo_login_icon_custom_color')) ?>';
					var tempHorSpace = '<?php echo esc_attr(get_option('mo_login_icon_space'))?>';
					var tempHorHeight = '<?php echo esc_attr(get_option('mo_login_icon_custom_height')) ?>';
					var tempHorBoundary='<?php echo esc_attr(get_option('mo_login_icon_custom_boundary'))?>';
						function moLoginIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>20&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

						function moLoginSpaceIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

						function moLoginWidthIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<1000?r.value++:"subtract"==t&&r.value>140&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

						function moLoginHeightIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>35&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

							function moLoginBoundaryIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<25?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}


					moLoginIncrement(document.getElementById('mo_login_size_plus'), "add", document.getElementById('mo_login_icon_size'), 300, 0.7);
					moLoginIncrement(document.getElementById('mo_login_size_minus'), "subtract", document.getElementById('mo_login_icon_size'), 300, 0.7);

					moLoginIncrement(document.getElementById('mo_login_size_plus'), "add", document.getElementById('mo_login_icon_size'), 300, 0.7);
					moLoginIncrement(document.getElementById('mo_login_size_minus'), "subtract", document.getElementById('mo_login_icon_size'), 300, 0.7);

					moLoginSpaceIncrement(document.getElementById('mo_login_space_plus'), "add", document.getElementById('mo_login_icon_space'), 300, 0.7);
					moLoginSpaceIncrement(document.getElementById('mo_login_space_minus'), "subtract", document.getElementById('mo_login_icon_space'), 300, 0.7);

					moLoginWidthIncrement(document.getElementById('mo_login_width_plus'), "add", document.getElementById('mo_login_icon_width'), 300, 0.7);
					moLoginWidthIncrement(document.getElementById('mo_login_width_minus'), "subtract", document.getElementById('mo_login_icon_width'), 300, 0.7);

					moLoginHeightIncrement(document.getElementById('mo_login_height_plus'), "add", document.getElementById('mo_login_icon_height'), 300, 0.7);
					moLoginHeightIncrement(document.getElementById('mo_login_height_minus'), "subtract", document.getElementById('mo_login_icon_height'), 300, 0.7);

					moLoginBoundaryIncrement(document.getElementById('mo_login_boundary_plus'), "add", document.getElementById('mo_login_icon_custom_boundary'), 300, 0.7);
					moLoginBoundaryIncrement(document.getElementById('mo_login_boundary_minus'), "subtract", document.getElementById('mo_login_icon_custom_boundary'), 300, 0.7);

					function setLoginTheme(){return jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val();}
					function setLoginCustomTheme(){return jQuery('input[name=mo_openid_login_custom_theme]:checked', '#form-apps').val();}
					function setSizeOfIcons(){

								if((jQuery('input[name=mo_openid_login_theme]:checked', '#form-apps').val()) == 'longbutton'){
									return document.getElementById('mo_login_icon_width').value;
								}else{
									return document.getElementById('mo_login_icon_size').value;
								}
					}
					moLoginPreview(setSizeOfIcons(),tempHorTheme,tempHorCustomTheme,tempHorCustomColor,tempHorSpace,tempHorHeight,tempHorBoundary);



					function moLoginPreview(t,r,l,p,n,h,k){

									if(l == 'default'){
										if(r == 'longbutton'){

											var a = "btn-defaulttheme";
										jQuery("."+a).css("width",t+"px");
										jQuery("."+a).css("padding-top",(h-29)+"px");
										jQuery("."+a).css("padding-bottom",(h-29)+"px");
										jQuery(".fa").css("padding-top",(h-35)+"px");
										jQuery("."+a).css("margin-bottom",(n-5)+"px");
										jQuery("."+a).css("border-radius",k+"px");
										}else{
											var a="mo_login_icon_preview";
											jQuery("."+a).css("margin-left",(n-4)+"px");

											if(r=="circle"){
												jQuery("."+a).css({height:t,width:t});
												jQuery("."+a).css("borderRadius","999px");
											}else if(r=="oval"){
												jQuery("."+a).css("borderRadius","5px");
												jQuery("."+a).css({height:t,width:t});
											}else if(r=="square"){
												jQuery("."+a).css("borderRadius","0px");
												jQuery("."+a).css({height:t,width:t});
											}
										}
									}
									else if(l == 'custom'){
										if(r == 'longbutton'){

												var a = "btn-customtheme";
												jQuery("."+a).css("width",(t)+"px");
												jQuery("."+a).css("padding-top",(h-29)+"px");
												jQuery("."+a).css("padding-bottom",(h-29)+"px");
												jQuery(".fa").css("padding-top",(h-35)+"px");
												jQuery("."+a).css("margin-bottom",(n-5)+"px");
												jQuery("."+a).css("background","#"+p);
												jQuery("."+a).css("border-radius",k+"px");
										}else{
											var a="mo_custom_login_icon_preview";
											jQuery("."+a).css({height:t-8,width:t});
											jQuery("."+a).css("padding-top","8px");
											jQuery("."+a).css("margin-left",(n-4)+"px");

											if(r=="circle"){
												jQuery("."+a).css("borderRadius","999px");
											}else if(r=="oval"){
												jQuery("."+a).css("borderRadius","5px");
												}else if(r=="square"){
												jQuery("."+a).css("borderRadius","0px");
											}
											jQuery("."+a).css("background","#"+p);
											jQuery("."+a).css("font-size",(t-16)+"px");
										}
									}


								previewLoginIcons();

					}

					function checkLoginButton(){
								if(document.getElementById('iconwithtext').checked) {
									if(setLoginCustomTheme() == 'default'){
										 jQuery(".mo_login_icon_preview").hide();
										 jQuery(".mo_custom_login_icon_preview").hide();
										 jQuery(".btn-customtheme").hide();
										 jQuery(".btn-defaulttheme").show();
									}else if(setLoginCustomTheme() == 'custom'){
										jQuery(".mo_login_icon_preview").hide();
										 jQuery(".mo_custom_login_icon_preview").hide();
										 jQuery(".btn-defaulttheme").hide();
											jQuery(".btn-customtheme").show();
									}
									jQuery("#commontheme").hide();
									jQuery(".longbuttontheme").show();
								}else {

									if(setLoginCustomTheme() == 'default'){
										jQuery(".mo_login_icon_preview").show();
										jQuery(".btn-defaulttheme").hide();
										jQuery(".btn-customtheme").hide();
										jQuery(".mo_custom_login_icon_preview").hide();
									}else if(setLoginCustomTheme() == 'custom'){
										jQuery(".mo_login_icon_preview").hide();
										 jQuery(".mo_custom_login_icon_preview").show();
										 jQuery(".btn-defaulttheme").hide();
										 jQuery(".btn-customtheme").hide();
									}
									jQuery("#commontheme").show();
									jQuery(".longbuttontheme").hide();
								}
								previewLoginIcons();
						}

						function previewLoginIcons() {
								var flag = 0;
								if (document.getElementById('google_enable').checked)   {
									flag = 1;
										if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
											jQuery("#mo_login_icon_preview_google").show();
										if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
											jQuery("#mo_custom_login_icon_preview_google").show();
										if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
											jQuery("#mo_login_button_preview_google").show();
										if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
											jQuery("#mo_custom_login_button_preview_google").show();
								} else if(!document.getElementById('google_enable').checked){
									jQuery("#mo_login_icon_preview_google").hide();
									jQuery("#mo_custom_login_icon_preview_google").hide();
									jQuery("#mo_login_button_preview_google").hide();
									jQuery("#mo_custom_login_button_preview_google").hide();

								}

								if (document.getElementById('facebook_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_facebook").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_facebook").show();
								}else if(!document.getElementById('facebook_enable').checked){
									jQuery("#mo_login_icon_preview_facebook").hide();
									jQuery("#mo_custom_login_icon_preview_facebook").hide();
									jQuery("#mo_login_button_preview_facebook").hide();
									jQuery("#mo_custom_login_button_preview_facebook").hide();
								}

								if (document.getElementById('vkontakte_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_vkontakte").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_vkontakte").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_vkontakte").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_vkontakte").show();
								}else if(!document.getElementById('vkontakte_enable').checked){
									jQuery("#mo_login_icon_preview_vkontakte").hide();
									jQuery("#mo_custom_login_icon_preview_vkontakte").hide();
									jQuery("#mo_login_button_preview_vkontakte").hide();
									jQuery("#mo_custom_login_button_preview_vkontakte").hide();
								}

								if (document.getElementById('linkedin_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_linkedin").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_linkedin").show();
								} else if(!document.getElementById('linkedin_enable').checked){
									jQuery("#mo_login_icon_preview_linkedin").hide();
									jQuery("#mo_custom_login_icon_preview_linkedin").hide();
									jQuery("#mo_login_button_preview_linkedin").hide();
									jQuery("#mo_custom_login_button_preview_linkedin").hide();
								}

								if (document.getElementById('instagram_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_instagram").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_instagram").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_instagram").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_instagram").show();
								} else if(!document.getElementById('instagram_enable').checked){
									jQuery("#mo_login_icon_preview_instagram").hide();
									jQuery("#mo_custom_login_icon_preview_instagram").hide();
									jQuery("#mo_login_button_preview_instagram").hide();
									jQuery("#mo_custom_login_button_preview_instagram").hide();
								}

								if (document.getElementById('amazon_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checkedd)
										jQuery("#mo_login_icon_preview_amazon").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#amazoncustom").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked) {
										jQuery("#mo_login_button_preview_amazon").show();
										jQuery("#mo_login_icon_preview_amazon").hide();
									}
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_amazon").show();
								}else if(!document.getElementById('amazon_enable').checked){
									jQuery("#mo_login_icon_preview_amazon").hide();
									jQuery("#amazoncustom").hide();
									jQuery("#mo_login_button_preview_amazon").hide();
									jQuery("#mo_custom_login_button_preview_amazon").hide();
								}

								if (document.getElementById('salesforce_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_salesforce").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#salesforcecustom").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_salesforce").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_salesforce").show();
								} else if(!document.getElementById('salesforce_enable').checked){
									jQuery("#mo_login_icon_preview_salesforce").hide();
									jQuery("#salesforcecustom").hide();
									jQuery("#mo_login_button_preview_salesforce").hide();
									jQuery("#mo_custom_login_button_preview_salesforce").hide();
								}

								if (document.getElementById('windowslive_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_windowslive").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_windows").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_windowslive").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_windows").show();
								} else if(!document.getElementById('windowslive_enable').checked){
									jQuery("#mo_login_icon_preview_windowslive").hide();
									jQuery("#mo_custom_login_icon_preview_windows").hide();
									jQuery("#mo_login_button_preview_windowslive").hide();
									jQuery("#mo_custom_login_button_preview_windows").hide();
								}


								if (document.getElementById('twitter_enable').checked) {
								if(flag) {
									jQuery("#no_apps_text").hide();
								} else {
									jQuery("#no_apps_text").show();
								}

									flag = 1;
									if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_login_button_preview_twitter").show();
									if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
										jQuery("#mo_custom_login_button_preview_twitter").show();
								}else if(!document.getElementById('twitter_enable').checked){
									jQuery("#mo_login_icon_preview_twitter").hide();
									jQuery("#mo_custom_login_icon_preview_twitter").hide();
									jQuery("#mo_login_button_preview_twitter").hide();
									jQuery("#mo_custom_login_button_preview_twitter").hide();
								}
                if (document.getElementById('yahoo_enable').checked) {
                    flag = 1;
                    if(document.getElementById('mo_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
                        jQuery("#mo_login_icon_preview_yahoo").show();
                    if(document.getElementById('mo_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
                        jQuery("#mo_custom_login_icon_preview_yahoo").show();
                    if(document.getElementById('mo_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
                        jQuery("#mo_login_button_preview_yahoo").show();
                    if(document.getElementById('mo_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
                        jQuery("#mo_custom_login_button_preview_yahoo").show();
                }
                else if(!document.getElementById('yahoo_enable').checked){
                    jQuery("#mo_login_icon_preview_yahoo").hide();
                    jQuery("#mo_custom_login_icon_preview_yahoo").hide();
                    jQuery("#mo_login_button_preview_yahoo").hide();
                    jQuery("#mo_custom_login_button_preview_yahoo").hide();
                }


						}
						checkLoginButton();
				</script>
        <script>
            function restart_tour() {
                tour.restart();
            }

            var tour = new Tour({
                name: "tour",
                steps: [
                    {
                        element: "#select_app",
                        title: "Select Apps",
                        content: "Select multiple apps to enable Social Login.",
                        backdrop:'body',
                        backdropPadding:'6'
                    }, {
                        element: "#custom_icon",
                        title: "Customize Icon",
                        content: "Customize the look and feel of app icons to suit your website's theme. Live preview included.",
                        backdrop:'body',
                        backdropPadding:'6'
                    }, {
                        element: "#display_opt",
                        title: "Display Options",
                        content: "Select the pages where you want to show the social login icons.",

                        backdrop:'body',
                        backdropPadding:'6'
                    }
                ,{
                        element: "#redirect_opt",
                        title: "Redirection Option",
                        content: "Redirect user after login and logout on your website. ",
                        backdrop:'body',
                        backdropPadding:'6',


                    }, {
                        element: "#support",
                        title: "miniOrange Support",
                        content: "Feel free to reach out to us in case of any assistance.",
                        backdrop:'body',
                        backdropPadding:'6',


                    },{
                        element: "#save_opt",
                        title: "Save",
                        content: "Save the updated changes.",
                        backdrop:'body',
                        backdropPadding:'6',
                    }

                ],
                  template: function () {
                        return (
                            tour.getCurrentStep()===5 // Is this the last step?
                                ? "<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\"><div style=\"width:47%;margin-top: -7%;\"><h4  style=\"float:left;margin-top:30%;margin-left:30%;\">"+ (tour.getCurrentStep()+1)+"/6</h4></div></div>&nbsp;&nbsp; <button class=\"button button-primary button-large\" data-role=\"end\">Done</button> </div> </div>"
                                : "<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\" style=\"width: 100%;\"><div style='margin-top: -6%;' ><h4>"+ (tour.getCurrentStep()+1)+"/6</h4></div> <button class=\"mo2f_tour_btn mo2f_tour_btn-sm mo2f_tour_btn-secondary mo2f_tour_btn_next-success\" style=\"width: 32%;height: 0%;margin-left: 25%;\" data-role=\"next\">Next &raquo;</button><button class=\"button button-primary button-large\" data-role=\"end\" style='margin-left: 7%'>End</button></div></div></div>"
                        );
                    }
            });

        </script>

		<tr>
			<td>
				<hr>
				<h3>Advanced Settings</h3>
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" id="moopenid_social_login_avatar" name="moopenid_social_login_avatar" value="1"  <?php checked( get_option('moopenid_social_login_avatar') == 1 );?> /><b>Set Display Picture for User</b>
			</td>
		</tr>

		<tr>
			<td>
				<br/>
				<input type="checkbox" id="auto_email_enable" name="mo_openid_email_enable" value="1"
					<?php checked( get_option('mo_openid_email_enable') == 1 );?> /><b>Enable Email Notification to Admin - <?php echo esc_html(get_option("mo_openid_admin_email"));?> on User Registration</b>
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <b>Notice:</b> SMTP should be configured. ]
			</td>
		</tr>

        <tr >
            <td>
                <div ><br>
                <input type="checkbox" id="moopenid_logo_check" name="moopenid_logo_check" value="1"  <?php checked( get_option('moopenid_logo_check') == 1 );?> /><b>Display miniOrange logo with social login icons and on account linking & profile completion forms.</b>
                </div>
            </td>
        </tr>
		



		
		<?php if(mo_openid_is_customer_valid() && !mo_openid_get_customer_plan('Do It Yourself')) { ?>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td><input type="checkbox" id="moopenid_user_attributes" name="moopenid_user_attributes" value="1"  <?php checked( get_option('moopenid_user_attributes') == 1 );?> /><b>Extended User Attributes</b>
			</td>
		</tr>
		<?php } else { 
			if(get_option('moopenid_user_attributes')) update_option('moopenid_user_attributes', 0);
		} ?>
    <tr>
        <td><br /><input type="submit" name="submit" value="Save" style="width:100px;" class="button button-primary button-large" />
        </td>
    </tr> <?php if(mo_openid_restrict_user()){ ?>

                              <tr>
                                    <td>
                                        <br>
                                        <hr><br>
                                        <div style="margin-left: 55px; max-width: 600px;background:#f0f8f5 no-repeat 20px 40px;border-radius: 5px;border: 2px solid #FA9444;">

                                            <h3><table>
                                                    <tr><td><img src="<?php echo plugin_dir_url(__FILE__);?>includes/images/lock1.png"></td>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;Get Standard or Premium plugin to unlock following features</h3> <br> <a style="margin-top: 25px;margin-left: 140px" class="button button-primary button-large" href="https://www.miniorange.com/social-login-social-sharing/" target="_blank">Upgrade Now</a></td>
                                </tr>
                            </table>
                    </div></td></tr>

        <tr ><td><table id="premium_ad">
    <tr>
                <td>
                    <br>
                    <h3><span style="color:red;">* </span>Recaptcha Support</h3>
                    &nbsp&nbsp&nbsp<input type="checkbox" disabled <b>Enable recaptcha</b>
                </td>
            </tr>
    <tr>
        <td>
            <br>
            <hr>
            <h3><span style="color:red;">* </span>Account Linking</h3>
        </td>
    </tr>
    <tr>
        <td>
            Enable account linking to let your users link their Social accounts with existing WordPress account. Users will be prompted with the option to either link to any existing account using WordPress login page or register as a new user.<br><br>
            <input type="checkbox" disabled="disabled" /><b>Enable Account-Linking</b>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <hr>
            <h3><span style="color:red;">* </span>GDPR Settings</h3>
        </td>
    </tr>
    <tr>
        <td>
            If GDPR check is enabled, users will be asked to give consent before using Social Login. Users who will not give consent will not be able to log in. This setting stands true only when users are registering using Social Login. This will not interfere with users registering through the regular WordPress. (Click <a target="_blank" href="<?php echo add_query_arg( array('tab' => 'privacy_policy'), $_SERVER['REQUEST_URI'] ); ?>"> here </a> to read miniOrange Social Login Privacy Policy. Please update your website's privacy policy accordingly and enter the URL to your privacy policy below.)
            <br/><br/>
            <input type="checkbox"  disabled="disabled" /><b>Take consent from users</b>
            <br/><br/>
            <b>Enter the Consent message: </b><br>
            <textarea  style="width:80%" disabled="disabled"><?php echo esc_textarea(get_option('mo_openid_gdpr_consent_message'))?></textarea>
            <br><br>
            <b>Enter the text that redirects to Privacy Policy URL: </b><br>
            <input type="text" style="width:90%" disabled="disabled"/>
            <br><br>
            <b>Enter Privacy Policy URL: </b><br>
            <input type="text" style="width:90%" disabled="disabled" />
        </td>
    </tr></table></td></tr>
     <br><hr><br><?php ;} ?>
		</table>
	</div>
</form>

<script>
jQuery(function() {
				jQuery('#tab2').removeClass('disabledTab');
});
</script>
</td>
		<td style="vertical-align:top;padding-left:1%;" >
			<div id="support"><?php echo miniorange_openid_support(); ?></div>
            <div style="margin-top:70px; height: 319px" class="mo_openid_support_layout">
                <div>
                    <p>Looking for a <b>Custom Registration Form</b>? Try our new <b>Social Login Integration Add-on</b>.</p>
                        <div style="width:95%; height:250px; border: 1px solid lightgrey;">
                            <div style="width: 100%; height: 70px; margin-top: 15px">
                                <div style=" margin-left: 20px; width: 20%; float: left"><a href="admin.php?page=mo_openid_settings_addOn" target="_blank"><img style="height:60px" src="<?php echo plugins_url('includes/images/miniorange_logo.png', __FILE__) ?>"></a></div>
                                <div style="width: 70%; float:right">
                                    <h1 style="margin: 0 0 15px; font-weight: 600; font-size: 22px; line-height: 1.3;">
                                        <a class="mo-pugin-add-link-sl mo-add-hover" href="admin.php?page=mo_openid_settings_addOn" target="_blank">Social Login Integration Add-on</a>
                                        <a class="mo-pugin-add-comp-link-sl">by </a>
                                        <a class="mo-pugin-add-comp-link-sl mo-add-hover" href="https://www.miniorange.com/" target="_blank">miniorange</a>
                                    </h1>
                                </div>
                            </div>
                            <p style="margin-left: 10px; font-size: 14px">Custom Registration Form Add-On helps you to integrate details of new as well as existing users. You can add as many fields as you want including the one which are returned by social sites at time of registration.</p>
                            <a href="admin.php?page=mo_openid_settings_addOn" target="_blank"><button style="margin-right: 25px; align-content: center; float: right; height: 30px" class="button button-primary button-large"><b>Get this plugin</b></button></a><br><br>
                        </div>
                </div>
            </div>
            <div style="margin-top:20px; height: auto" class="mo_openid_support_layout">
                <div>
                    <p>Do you want to make your <b>website more secure</b> by Brute Force attack, Spam Protection etc? Try out our Plugin.</p>
                    <script type='text/javascript'>
                        <!--//--><![CDATA[//><!--
                        !function(a,b){"use strict";function c(){if(!e){e=!0;var a,c,d,f,g=-1!==navigator.appVersion.indexOf("MSIE 10"),h=!!navigator.userAgent.match(/Trident.*rv:11\./),i=b.querySelectorAll("iframe.wp-embedded-content");for(c=0;c<i.length;c++){if(d=i[c],!d.getAttribute("data-secret"))f=Math.random().toString(36).substr(2,10),d.src+="#?secret="+f,d.setAttribute("data-secret",f);if(g||h)a=d.cloneNode(!0),a.removeAttribute("security"),d.parentNode.replaceChild(a,d)}}}var d=!1,e=!1;if(b.querySelector)if(a.addEventListener)d=!0;if(a.wp=a.wp||{},!a.wp.receiveEmbedMessage)if(a.wp.receiveEmbedMessage=function(c){var d=c.data;if(d)if(d.secret||d.message||d.value)if(!/[^a-zA-Z0-9]/.test(d.secret)){var e,f,g,h,i,j=b.querySelectorAll('iframe[data-secret="'+d.secret+'"]'),k=b.querySelectorAll('blockquote[data-secret="'+d.secret+'"]');for(e=0;e<k.length;e++)k[e].style.display="none";for(e=0;e<j.length;e++)if(f=j[e],c.source===f.contentWindow){if(f.removeAttribute("style"),"height"===d.message){if(g=parseInt(d.value,10),g>1e3)g=1e3;else if(~~g<200)g=200;f.height=g}if("link"===d.message)if(h=b.createElement("a"),i=b.createElement("a"),h.href=f.getAttribute("src"),i.href=d.value,i.host===h.host)if(b.activeElement===f)a.top.location.href=d.value}else;}},d)a.addEventListener("message",a.wp.receiveEmbedMessage,!1),b.addEventListener("DOMContentLoaded",c,!1),a.addEventListener("load",c,!1)}(window,document);
                        //--><!]]>
                    </script>
                    <iframe sandbox="allow-scripts" security="restricted" target="_blank" src="https://wordpress.org/plugins/wp-security-pro/embed/" width="95%" height="230" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" class="wp-embedded-content"></iframe>
                </div>
            </div>
            <div style="margin-top:20px; height: auto" class="mo_openid_support_layout">
                <div>
                    <p>Looking for <b>OTP Verification</b> along with Social Login? Try our OTP Plugin.</p>
                    <script type='text/javascript'>
                        <!--//--><![CDATA[//><!--
                        !function(a,b){"use strict";function c(){if(!e){e=!0;var a,c,d,f,g=-1!==navigator.appVersion.indexOf("MSIE 10"),h=!!navigator.userAgent.match(/Trident.*rv:11\./),i=b.querySelectorAll("iframe.wp-embedded-content");for(c=0;c<i.length;c++){if(d=i[c],!d.getAttribute("data-secret"))f=Math.random().toString(36).substr(2,10),d.src+="#?secret="+f,d.setAttribute("data-secret",f);if(g||h)a=d.cloneNode(!0),a.removeAttribute("security"),d.parentNode.replaceChild(a,d)}}}var d=!1,e=!1;if(b.querySelector)if(a.addEventListener)d=!0;if(a.wp=a.wp||{},!a.wp.receiveEmbedMessage)if(a.wp.receiveEmbedMessage=function(c){var d=c.data;if(d)if(d.secret||d.message||d.value)if(!/[^a-zA-Z0-9]/.test(d.secret)){var e,f,g,h,i,j=b.querySelectorAll('iframe[data-secret="'+d.secret+'"]'),k=b.querySelectorAll('blockquote[data-secret="'+d.secret+'"]');for(e=0;e<k.length;e++)k[e].style.display="none";for(e=0;e<j.length;e++)if(f=j[e],c.source===f.contentWindow){if(f.removeAttribute("style"),"height"===d.message){if(g=parseInt(d.value,10),g>1e3)g=1e3;else if(~~g<200)g=200;f.height=g}if("link"===d.message)if(h=b.createElement("a"),i=b.createElement("a"),h.href=f.getAttribute("src"),i.href=d.value,i.host===h.host)if(b.activeElement===f)a.top.location.href=d.value}else;}},d)a.addEventListener("message",a.wp.receiveEmbedMessage,!1),b.addEventListener("DOMContentLoaded",c,!1),a.addEventListener("load",c,!1)}(window,document);
                        //--><!]]>
                    </script>
                    <iframe sandbox="allow-scripts" security="restricted" target="_blank" src="https://wordpress.org/plugins/miniorange-otp-verification/embed/" width="95%" height="230" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" class="wp-embedded-content"></iframe>
                </div>
            </div>
		</td>
        <script>
            var tour2 = new Tour({
                name: "tour2",
                steps: [
                    {element: "#support",
                        title: "miniOrange Support",
                        content: "Feel free to reach out to us in case of any assistance.",
                        backdrop:'body',
                        backdropPadding:'6',
                    },
                ],
                template: function () {
                    return ("<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\" style=\"width: 100%;\"><button class=\"button button-primary button-large\" data-role=\"end\"onclick='end_tour1();'>OK Got It</button></div></div></div>");
                }
            });
            var temp = "<?php echo get_option('mo_openid_tour'); ?>";
            if(temp=="0"&&tour1.ended()) {
                tour2.init();
                tour2.start();
            }
        </script>
<?php
}

function mo_openid_integrations(){

    ?>
    <td style="vertical-align:top; ">
        <div class="mo_openid_table_layout">
            <style>
                .tableborder {
                    border-collapse: collapse;
                    width: 100%;
                    border-color:#eee;
                }

                .tableborder th, .tableborder td {
                    text-align: left;
                    padding: 8px;
                    border-color:#eee;
                }

                .tableborder tr:nth-child(even){background-color: #f2f2f2}
            </style>

            <div style="display:block;">
                <b><span style="display:block;margin-top:10px;background-color:aliceblue;padding:5px;border:solid 1px deepskyblue;">*NOTE: These features are available in the <a target="_blank" href="<?php echo add_query_arg( array('tab' => 'pricing'), $_SERVER['REQUEST_URI'] ); ?>">premium</a> version of the plugin.</span>
                </b>
            </div>



            <h3><span style='color:red;'>*</span>BuddyPress</h3>

            <form name="mo_openid_buddypress_form" method="post" id="mo_openid_buddypress_form">
                <input type="hidden" name="option" value="mo_openid_save_buddypress_field"/>
				<input type="hidden" name="mo_openid_save_buddypress_field_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-save-buddypress-field-nonce' ); ?>"/>
    <table>
    <tr><td>
        <b>BuddyPress display options</b>
            <input style="margin-left: 400px;" type="submit" value="Save " <?php if(!mo_openid_is_customer_registered()) echo 'disabled';if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> class="button button-primary button-large" />
        </td></tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="bp_before_register_page" name="mo_openid_bp_before_register_page" value="1"
                <?php checked( get_option('mo_openid_bp_before_register_page') == 1 ); if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />Before BuddyPress Registration Form</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="bp_before_account_details_fields" name="mo_openid_bp_before_account_details_fields" value="1"
                <?php checked( get_option('mo_openid_bp_before_account_details_fields') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />Before BuddyPress Account Details</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="bp_after_register_page" name="mo_openid_bp_after_register_page" value="1"
                <?php checked( get_option('mo_openid_bp_after_register_page') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />After BuddyPress Registration Form</td>
    </tr></table>
            </form>
            <h3>BuddyPress Integration</h3>
            <h4>BuddyPress Extended Attributes Mapping</h4>
            <?php
            echo "<div style='text-align: center'><p>You have not setup attribute mapping for any apps yet. Please click on <b>Add Application</b> to configure mapping for each app.</p>";
            echo "</div>";
            echo "<br><input disabled style='margin-left:300px;text-align:center;' type=\"button\" name=\"add_app\" id=\"add_app\" class=\"button button-primary button-large\" value=\"Add Application\" onclick=\"window.location.href='admin.php?page=mo_openid_settings&tab=integrations&action=add'\" />";

            ?>

            <!--MailChimp form --- remove serverside code-->
            <form name="mo_openid_mailchimp_form" method="post" id="mo_openid_mailchimp_form">
                <input type="hidden" name="option" value="mo_openid_save_mailchimp_field"/><br>
                <hr>
                <h3><span style="color:red;">*</span>MailChimp Integration</h3>
                <p><b>A user is added as a subscriber to a mailing list in MailChimp when that user registers using social login. First name, last name and email are also captured for that user in the Mailing List.</b></p>
                (List ID in MailChimp : Lists -> Select your List -> Settings -> List Name and Defaults -> List ID) <br>
                (API Key in MailChimp : Profile -> Extras -> API Keys -> Your API Key )<br><br>
                <b>List Id:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="background: #DCDAD1;" disabled size="50" class="mo_table_textbox" type="text" id="mo_openid_mailchimp_listid" display:"inline-block" name="mo_openid_mailchimp_listid" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_mailchimp_listid');?>"> <br><br>
                <b>API Key: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="background: #DCDAD1;" disabled size="50" class="mo_table_textbox" type="text" id="mo_openid_mailchimp_api_key" display:"inline-block" name="mo_openid_mailchimp_api_key" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> value="<?php echo get_option('mo_openid_mailchimp_api_key');?>"><br><br>
                <input disabled type="checkbox"  name="mo_openid_show_mailchimp_form" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_show_mailchimp_form') == 1 );?> />
                <strong>Ask user for permission to be added in MailChimp Subscriber list </strong>
                <br>(If unchecked, user will get subscribed during registration.)
                <br><br>
                <b>Click on Download button to get a list of emails of WordPress users registered on your site. You can import this file in MailChimp. </b><br><br>
                <input disabled type="submit" value="Save " <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> class="button button-primary button-large" />
                <a disabled style="width:190px;" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> class="button button-primary button-large" href="<?php echo plugin_dir_url(__FILE__) . 'miniorange_openid_sso_download_emails.php' ?>">
                    Download emails of users
                </a><br>
            </form>

            <!-- Woocommerce form -->
            <form name="mo_openid_woocommerce_form" method="post" id="mo_openid_woocommerce_form">
                <input type="hidden" name="option" value="mo_openid_save_woocommerce_field"/>
				<input type="hidden" name="mo_openid_save_woocommerce_field_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-save-woocommerce-field-nonce' ); ?>"/>
                <br>
                <hr>
                <h3><span style="color:red;">*</span>Woocommerce</h3>

    <table><td>
        <b>Woocommerce display options</b>
    </td>


    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_before_login_form" name="mo_openid_woocommerce_before_login_form" value="1"
                <?php checked( get_option('mo_openid_woocommerce_before_login_form') == 1 ); if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />Before WooCommerce Login Form</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_center_login_form" name="mo_openid_woocommerce_center_login_form" value="1"
                <?php checked( get_option('mo_openid_woocommerce_center_login_form') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />Before 'Remember Me' of WooCommerce Login Form</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_login_form" name="mo_openid_woocommerce_login_form" value="1"
                <?php checked( get_option('mo_openid_woocommerce_login_form') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />After WooCommerce Login Form</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_register_form_start" name="mo_openid_woocommerce_register_form_start" value="1"
                <?php checked( get_option('mo_openid_woocommerce_register_form_start') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />Before WooCommerce Registration Form</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_center_register_form" name="mo_openid_woocommerce_center_register_form" value="1"
                <?php checked( get_option('mo_openid_woocommerce_center_register_form') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />Before 'Register button' of WooCommerce Registration Form</td>
    </tr>

    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_register_form_end" name="mo_openid_woocommerce_register_form_end" value="1"
                <?php checked( get_option('mo_openid_woocommerce_register_form_end') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />After WooCommerce Registration Form</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_before_checkout_billing_form" name="mo_openid_woocommerce_before_checkout_billing_form" value="1"
                <?php checked( get_option('mo_openid_woocommerce_before_checkout_billing_form') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />Before WooCommerce Checkout Form</td>
    </tr>
    <tr>
        <td class="mo_openid_table_td_checkbox">
            <input type="checkbox" id="woocommerce_after_checkout_billing_form" name="mo_openid_woocommerce_after_checkout_billing_form" value="1"
                <?php checked( get_option('mo_openid_woocommerce_after_checkout_billing_form') == 1 );if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> />After WooCommerce Checkout Form</td>
    </tr>

    </table>

                <h3>Woocommerce Integration</h3>
                <p><b>If enabled, first name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page.</b></p>
                <input disabled type="checkbox"  name="mo_openid_save_woocommerce_field" value="1" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_enable_woocommerce_sync') == 1 );?> /><strong>Sync Woocommerce checkout fields</strong>
                <br><br>
                <input type="submit" value="Save " <?php if(!mo_openid_is_customer_registered()) echo 'disabled';if(mo_openid_restrict_user()) echo "disabled=\"disabled\"";?> class="button button-primary button-large" />
                <br>
            </form><br><br>

        </div>

    </td>

    <td style="vertical-align:top;padding-left:1%; width: 35%">
        <?php echo miniorange_openid_support(); ?>
    </td>

    <?php
}

function mo_openid_email_notification(){
    ?>
    <td style="vertical-align:top;width:65%;">
            <div class="mo_openid_table_layout">

                <div style="display:block;">
                    <b><span style="display:block;margin-top:10px;background-color:aliceblue;padding:5px;border:solid 1px deepskyblue;">*NOTE: These features are available in the <a target="_blank" href="<?php echo add_query_arg( array('tab' => 'pricing'), $_SERVER['REQUEST_URI'] ); ?>">standard and premium</a> version of the plugin.</span>
                    </b>
                </div>

                <table style="width:99%;">
                    <tr>
                        <td>
                            <h3> Send Mail To Admin</h3>
                        </td>
                    </tr>
                </table>
                <table style="width:99%;" >

                    <tr>
                        <td>
                            <span style="color:red;">*</span><b>If you want to send Email Notification to multiple admins, enter emails of all admins here:</b><br>(If left empty only administrator gets email)<br><br>
                            <textarea disabled rows="2" id="mo_openid_multiple_email_id" placeholder="Emails should be separated by comma" style="width:100%;background: #DCDAD1;" name="mo_openid_multiple_admin_emails"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <br>
                            <input style ="background: #DCDAD1;"  disabled type="checkbox" id="mo_admin_email_template" name="mo_admin_email_template" value="1"/><b><span style="color:red;">*</span>Edit Email Notification Template to Admin</b>
                            <br/>
                        </td>
                    </tr>

                    <tr class="mo_openid_email_subject">
                        <td><br />
                            <span style="color:red;">*</span>
                            <b>Email Subject:</b>
                            <textarea disabled rows="2" id="mo_openid_email_subject" placeholder="Enter your subject line here" style="width:100%;background: #DCDAD1;" name="mo_openid_email_subject"></textarea>
                            <br/>
                        </td>
                    </tr>

                    <tr class="mo_admin_email_template">
                        <td>
                            <br />
                            <span style="color:red;">*</span>
                            <b>Edit Content of Email:</b>
                            <textarea disabled rows="6" id="mo_openid_email_message_id" style="width:100%;background: #DCDAD1;" name="mo_openid_registration_email_content"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <br>
                            <hr>
                            <h3>Send Mail to User</h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input style="background: #DCDAD1;" disabled type="checkbox" id="auto_welcome_email_enable" name="mo_openid_welcome_email_enable" value="1"
                                <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?>	<?php checked( get_option('mo_openid_welcome_email_enable') == 1 );?> /><b><span style="color:red;">*</span>Enable Email Notification to User on User Registration*</b>
                            <br/>
                        </td>
                    </tr>
                    <tr class="mo_openid_welcome_email_subject">
                        <td><br />
                            <span style="color:red;">*</span>
                            <b>Email Subject:</b>
                            <textarea disabled rows="2" id="mo_openid_welcome_email_subject" placeholder="Enter your subject line here" style="width:100%;background: #DCDAD1;" name="mo_openid_welcome_email_subject"></textarea>
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <br/ >
                            <span style="color:red;">*</span>
                            <b>Edit Content of Email:</b>
                            <textarea disabled rows="6" id="mo_openid_email_message_id" style="width:100%;background: #DCDAD1;" name="mo_openid_user_register_message"></textarea>

                        </td>
                    </tr>
                </table>
                <table class="mo_openid_display_table">
                    <tr>
                        <td><br /><input disabled type="submit" name="submit" value="Save" style="width:100px;" class="button button-primary button-large" />
                        </td>
                    </tr>
                </table>
                <br><b>*NOTE: These features require SMTP to be setup.</b>
            </div>
    </td>
    <td style="vertical-align:top;padding-left:1%;">
        <?php echo miniorange_openid_support(); ?>
    </td>
    <?php exit;
}

function mo_openid_app_comment() {
?>
	<td style="vertical-align:top;width:65%;">
		<form name="f" method="post" id="comment_settings_form" action="">
		<input type="hidden" name="option" value="mo_openid_save_comment_settings" />
		<input type="hidden" name="mo_openid_comment_settings_nonce"
        		value="<?php echo wp_create_nonce( 'mo-openid-comment-settings-nonce' ); ?>"/>
		<div class="mo_openid_table_layout">
		
		<table class="mo_openid_display_table">

			<tr>
				<td colspan="2">
					<h3>Social Comments
                        <input type="submit" id="comment_save" name="submit" value="Save" style="width:100px;float:right;margin-right:2%" class="button button-primary button-large" onclick="end_tour();"/> <button type="button" id="mo_openid_restart_tour" class="button button-primary button-large"style="float:right; margin-right:2%;width:25%" onclick="restart_tour();"><i class="fa fa-refresh"></i> Social Commenting Tour</button>
					</h3>
					<b>Select applications to add Social Comments. These commenting applications will be added to your blog post pages at the location of your comments.</b>
				</td>
			</tr>
		</table><br/>
            <hr>
		<table id="sel_app" class="mo_openid_display_table">
			<tr>
				<td colspan="2">

					<h3>Select Applications</h3>
					If none of the below are selected, default WordPress comments will only be visible. Only selecting Default WordPress Comments will not result in any changes.
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			</tr>
				<td><input type="checkbox" id="mo_openid_social_comment_default" name="mo_openid_social_comment_default" value="1"  <?php checked( get_option('mo_openid_social_comment_default') == 1 );?> /><b>Default WordPress Comments</b>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_fb" name="mo_openid_social_comment_fb" value="1"  <?php checked( get_option('mo_openid_social_comment_fb') == 1 );?> /><b>Facebook Comments</b>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_google" name="mo_openid_social_comment_google" value="1"  <?php checked( get_option('mo_openid_social_comment_google') == 1 );?> /><b>Google+ Comments</b>
				</td>
			<tr>
		</table><br>
            <hr>
		<table id="disp_opt" class="mo_openid_display_table">
			<tr>
				<td colspan="2">

					<h3>Display Options</h3>
					Select the options where you want to add social comments.
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_blogpost" name="mo_openid_social_comment_blogpost" value="1"  <?php checked( get_option('mo_openid_social_comment_blogpost') == 1 );?> /><b>Blog Post</b>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="mo_openid_social_comment_static" name="mo_openid_social_comment_static" value="1"  <?php checked( get_option('mo_openid_social_comment_static') == 1 );?> /><b>Static Pages</b>
				</td>
			<tr>
		</table><br>
            <hr>
		<table  class="mo_openid_display_table">
			<tr><td><table id="cust_text"><tr>
				<td colspan="2">

					<h3>Customize Text For Social Comment Labels</h3>
				</td>
			</tr>
			<tr>
				<td><b>Comment Section Heading:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_heading_label"  value="<?php echo esc_attr(get_option('mo_openid_social_comment_heading_label')); ?>" /></td>
			</tr>
			<tr>
				<td><b>Comments - Default Label:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_default_label"  value="<?php echo esc_attr(get_option('mo_openid_social_comment_default_label')); ?>" /></td>
			</tr>
			<tr>
				<td><b>Comments - Facebook Label:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_fb_label"  value="<?php echo esc_attr(get_option('mo_openid_social_comment_fb_label')); ?>" /></td>
			</tr>
			<tr>
				<td><b>Comments - Google Label:</b></td>
				<td><input class="mo_openid_table_textbox" type="text" name="mo_openid_social_comment_google_label"  value="<?php echo esc_attr(get_option('mo_openid_social_comment_google_label')); ?>" /></td>
			</tr></table></td></tr>
		
			<tr>
				<td><br /><input type="submit" name="submit" value="Save" style="width:100px;" class="button button-primary button-large" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr>
					<p>
						<h3>Enable Social Comments</h3>
						To enable Social Comments, please select either Facebook Comments and/or Google+ Comments from <strong>Select Applications</strong>. Also select one or both of the options from <strong>Display Options</strong>.

						<h3>Add Social Comments</h3>
						You can add social comments in the following areas from <strong>Display Options</strong>. If you require a shortcode, please contact us from the Support form on the right.
						<ol>
							<li>Blog Post: This option enables Social Comments on Posts / Blog Post.</li>
							<li>Static pages: This option places Social Comments on Pages / Static Pages with comments enabled.</li>
						</ol>
					</p>
				</td>
			</tr>
		</table>
	</form>
	</td>
	<td style="vertical-align:top;padding-left:1%;">
        <div id="support"><?php echo miniorange_openid_support(); ?></div>
	</td>
    <script>
        function restart_tour() {

            tour.restart();
        }

        var tour = new Tour({
            name: "tour",
            steps: [
                {
                    element: "#sel_app",
                    title: "Select Apps",
                    content: "Select multiple apps to enable Social commenting.",
                    backdrop:'body',
                    backdropPadding:'6'
                }, {
                    element: "#disp_opt",
                    title: "Display Options",
                    content: "Select the options where you want to add social comments.",
                    backdrop:'body',
                    backdropPadding:'6'
                }, {
                    element: "#cust_text",
                    title: "Customize Text",
                    content: "Customize the text of social commenting labels.",
                    backdrop:'body',
                    backdropPadding:'6'
                }
                ,{
                    element: "#support",
                    title: "miniOrange Support",
                    content: "Feel free to reach out to us in case of any assistance.",
                    backdrop:'body',
                    backdropPadding:'6',


                },{
                    element: "#comment_save",
                    title: "Save",
                    content: "Save the updated changes.",
                    backdrop:'body',
                    backdropPadding:'6'
                }
            ],
            template: function () {
                return (
                    tour.getCurrentStep()===4// Is this the last step?
                        ? "<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\"><div style=\"width:47%;margin-top: -7%;\"><h4  style=\"float:left;margin-top:30%;margin-left:30%;\">"+ (tour.getCurrentStep()+1)+"/5</h4></div></div>&nbsp;&nbsp; <button class=\"button button-primary button-large\" data-role=\"end\">Done</button> </div> </div>"
                        : "<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\" style=\"width: 100%;\"><div style='margin-top: -6%;' ><h4>"+ (tour.getCurrentStep()+1)+"/5</h4></div> <button class=\"mo2f_tour_btn mo2f_tour_btn-sm mo2f_tour_btn-secondary mo2f_tour_btn_next-success\" style=\"width: 32%;height: 0%;margin-left: 25%;\" data-role=\"next\">Next &raquo;</button><button class=\"button button-primary button-large\" data-role=\"end\" style='margin-left: 7%'>End</button></div></div></div>"
                );
            }
        });


    </script>
	<?php
}

function mo_openid_other_settings(){
	
?>
<td style="vertical-align:top;width:65%;">
	<form name="f" method="post" id="settings_form" action="">
	<input type="hidden" name="option" value="mo_openid_save_other_settings" />
	<input type="hidden" name="mo_openid_save_other_settings_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-save-other-settings-nonce' ); ?>"/>
	<div class="mo_openid_table_layout">

								<table>
									<tr>
										<td colspan="2">
                                            <h3>Social Sharing  <input type="submit" id="share_save" name="submit" value="Save" style="width:100px;float:right;margin-right:2%" class="button button-primary button-large" onclick="end_tour();"/><button type="button" id="mo_openid_restart_tour" class="button button-primary button-large"style="float:right; margin-right:2%;width:22%" onclick="restart_tour();"><i class="fa fa-refresh"></i> Social Sharing Tour</button>
												</h3>
												<b>Select applications to add share icons. Customize sharing icons by using a range of shapes, themes and sizes to suit to your website. You can also choose different places to display these icons. Additionally, place vertical floating icons on your pages.</b>
										</td>
										
									</tr>
								</table>
	
	<table class="mo_openid_settings_table">
        <tr id="sel_apps"><td><table><h3>Select Social Apps</h3>
		<p>Select applications to enable social sharing</p>
		<tr>
			<td class="mo_openid_table_td_checkbox">

					<tr>
						<td style="width:20%">
							<input type="checkbox" id="facebook_share_enable" class="app_enable" name="mo_openid_facebook_share_enable" value="1" 
							onclick="addSelectedApps();"  <?php checked( get_option('mo_openid_facebook_share_enable') == 1 );?> />
							<strong>Facebook</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox"
									id="twitter_share_enable" class="app_enable" name="mo_openid_twitter_share_enable" value="1" onclick="addSelectedApps();"
								<?php checked( get_option('mo_openid_twitter_share_enable') == 1 );?> />
							<strong>Twitter </strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="google_share_enable" class="app_enable" name="mo_openid_google_share_enable" value="1" onclick="addSelectedApps();"
							 <?php checked( get_option('mo_openid_google_share_enable') == 1 );?> />
							<strong>Google</strong>
						</td>
						
						<td style="width:20%">
                            <input type="checkbox" id="vkontakte_share_enable" class="app_enable" name="mo_openid_vkontakte_share_enable" value="1"
                                   onclick="addSelectedApps();" <?php checked( get_option('mo_openid_vkontakte_share_enable') == 1 );?> />
                            <strong>Vkontakte</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox"
							id="tumblr_share_enable" class="app_enable" name="mo_openid_tumblr_share_enable" value="1" onclick="addSelectedApps();"
							<?php checked( get_option('mo_openid_tumblr_share_enable') == 1 );?> />
							<strong>Tumblr </strong>
						</td>
					</tr>
					<tr>
						<td style="width:20%">
							<input type="checkbox" id="stumble_share_enable" class="app_enable" name="mo_openid_stumble_share_enable" value="1" onclick="addSelectedApps();"  <?php checked( get_option('mo_openid_stumble_share_enable') == 1 );?> />
							<strong>StumbleUpon</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="linkedin_share_enable" class="app_enable" name="mo_openid_linkedin_share_enable" value="1" onclick="addSelectedApps();"
							<?php checked( get_option('mo_openid_linkedin_share_enable') == 1 );?> />
							<strong>LinkedIn</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox"
							id="reddit_share_enable" class="app_enable" name="mo_openid_reddit_share_enable" value="1" onclick="addSelectedApps();"
							<?php checked( get_option('mo_openid_reddit_share_enable') == 1 );?> />
							<strong>Reddit </strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="pinterest_share_enable" class="app_enable" name="mo_openid_pinterest_share_enable" value="1" onclick="addSelectedApps();"

							<?php checked( get_option('mo_openid_pinterest_share_enable') == 1 );?> />
							<strong>Pinterest </strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="pocket_share_enable" class="app_enable" name="mo_openid_pocket_share_enable" value="1" onclick="addSelectedApps();" <?php checked( get_option('mo_openid_pocket_share_enable') == 1 );?> />
							<strong>Pocket</strong>
						</td>
					</tr>
					<tr>
						<td style="width:20%">
							<input type="checkbox" id="digg_share_enable" class="app_enable" name="mo_openid_digg_share_enable" value="1" 
							onclick="addSelectedApps();"  <?php checked( get_option('mo_openid_digg_share_enable') == 1 );?> />
							<strong>Digg</strong>
						</td>
						<td style="width:20%">
						<input type="checkbox" id="delicious_share_enable" class="app_enable" name="mo_openid_delicious_share_enable" value="1" 
						onclick="addSelectedApps();"  <?php 	checked( get_option('mo_openid_delicious_share_enable') == 1 );?> />
						<strong>Delicious</strong></td>
						<td style="width:20%">
							<input type="checkbox" id="odnoklassniki_share_enable" class="app_enable" name="mo_openid_odnoklassniki_share_enable" value="1" 
							onclick="addSelectedApps();"  <?php checked( get_option('mo_openid_odnoklassniki_share_enable') == 1 );?> />
							<strong>Odnoklassniki</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="mail_share_enable" class="app_enable" name="mo_openid_mail_share_enable" value="1" 
							onclick="addSelectedApps();moSharingPreview();"  <?php checked( get_option('mo_openid_mail_share_enable') == 1 );?> />
							<strong>Email</strong>
						</td>
						<td style="width:20%">
							<input type="checkbox" id="print_share_enable" class="app_enable" name="mo_openid_print_share_enable" value="1" 
							onclick="addSelectedApps();moSharingPreview();"  <?php checked( get_option('mo_openid_print_share_enable') == 1 );?> />
							<strong>Print</strong>
						</td>
					</tr>
					<tr>
						<td style="width:20%">
							<input type="checkbox" id="whatsapp_share_enable" class="app_enable" name="mo_openid_whatsapp_share_enable" value="1" 
							onclick="addSelectedApps();moSharingPreview();"  <?php checked( get_option('mo_openid_whatsapp_share_enable') == 1 );?> />
							<strong>Whatsapp</strong>
						</td>
					</tr>

			</td>
		</tr>
                </table>
        </td>
        </tr>
									

		
		
		<tr id="cust_icon"><td><table><tr>
			<td>
				<br>
				<hr>
				<h3>Customize Sharing Icons</h3>
				<p>Customize shape, size and background for sharing icons</p>
			</td>
		</tr>
		<tr>
			<td>
				<table style="width:98%">
					<tr>
						<td><b>Shape</b></td>
						<td><b>Theme</b></td>
						<td><b>Space between Icons</b></td>
						<td><b>Size of Icons</b></td>
					</tr>
					<tr>
						<td style="width:inherit;"> <!-- Shape radio buttons -->
							<!-- Round -->
							<input type="radio" id="mo_openid_share_theme_circle"    name="mo_openid_share_theme" value="circle" onclick="tempHorShape = 'circle';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, 'circle', setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)" <?php checked( get_option('mo_openid_share_theme') == 'circle' );?> />Round
							
						</td>
						<td><!-- Theme radio buttons -->
							<!-- Default -->
							<input type="radio" id="mo_openid_default_background_radio"  name="mo_openid_share_custom_theme" value="default" onclick="tempHorTheme = 'default';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(), 'default', document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)"
							<?php checked( get_option('mo_openid_share_custom_theme') == 'default' );?> />Default
						</td>
						<td> <!-- Size between icons buttons-->
							<input style="width:50px" onkeyup="moSharingSpaceValidate(this)" id="mo_sharing_icon_space" name="mo_sharing_icon_space" type="text" value="<?php echo esc_attr(get_option('mo_sharing_icon_space'))?>" />
							<input id="mo_sharing_space_plus" type="button" value="+" onmouseup="moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)"/>
							<input id="mo_sharing_space_minus" type="button" value="-" onmouseup="moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)" />
						</td>
						<td> <!-- Size buttons-->
							<input style="width:50px" id="mo_sharing_icon_size" onkeyup="moSharingSizeValidate(this)" name="mo_sharing_icon_custom_size" type="text" value="<?php echo esc_attr(get_option('mo_sharing_icon_custom_size'))?>" >
				
							<input id="mo_sharing_size_plus" type="button" value="+" onmouseup="tempHorSize = document.getElementById('mo_sharing_icon_size').value;moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value , setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)" >
				
							<input id="mo_sharing_size_minus" type="button" value="-" onmouseup="tempHorSize = document.getElementById('mo_sharing_icon_size').value;moSharingPreview('horizontal',document.getElementById('mo_sharing_icon_size').value ,setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value, document.getElementById('mo_sharing_icon_custom_font').value)" >
						</td>
					</tr>
					<tr>
						<td> <!-- Shape radio buttons -->
							<!-- Rounded Edges -->
							<input type="radio"   name="mo_openid_share_theme"  value="oval" onclick="tempHorShape = 'circle';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, 'oval', setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value)"  <?php checked( get_option('mo_openid_share_theme') == 'oval' );?> />Rounded Edges
						</td>
						<td> <!-- Theme radio buttons -->
							<!-- Custom background -->
							
							<input type="radio" id="mo_openid_custom_background_radio"  name="mo_openid_share_custom_theme" value="custom" onclick="tempHorTheme = 'custom';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),'custom',document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value)"
							<?php checked( get_option('mo_openid_share_custom_theme') == 'custom' );?> />Custom background*
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> <!-- Shape radio buttons -->
							<!-- Square -->
							<input type="radio"   name="mo_openid_share_theme" value="square" onclick="tempHorShape = 'square';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(), setCustomTheme(), document.getElementById('mo_sharing_icon_custom_color').value, document.getElementById('mo_sharing_icon_space').value)"  <?php checked( get_option('mo_openid_share_theme') == 'square' );?> />Square
						</td>
						<td> <!-- Theme radio buttons -->
							<!-- Custom background textbox -->
							
							<input id="mo_sharing_icon_custom_color" name="mo_sharing_icon_custom_color" class="color" value="<?php echo esc_attr(get_option('mo_sharing_icon_custom_color'))?>" onchange="moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)" >
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td> <!-- Theme radio buttons -->
							<!-- No background -->
							<input type="radio" id="mo_openid_no_background_radio"  name="mo_openid_share_custom_theme" value="customFont" onclick="tempHorTheme = 'custom';moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),'customFont',document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value)"  <?php checked( get_option('mo_openid_share_custom_theme') == 'customFont' );?> />No background*
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td> <!-- Theme radio buttons -->
							<!-- No background textbox-->
							<input id="mo_sharing_icon_custom_font" name="mo_sharing_icon_custom_font"  class="color" value="<?php echo esc_attr(get_option('mo_sharing_icon_custom_font'))?>" onchange="moSharingPreview('horizontal', document.getElementById('mo_sharing_icon_size').value, setTheme(),setCustomTheme(),document.getElementById('mo_sharing_icon_custom_color').value,document.getElementById('mo_sharing_icon_space').value,document.getElementById('mo_sharing_icon_custom_font').value,document.getElementById('mo_sharing_icon_custom_font').value)" />
						</td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>					
		
		<tr><td>&nbsp;</td></tr>		
		
		<tr>
			<td><b>Preview: </b><br/><span hidden id="no_apps_text">No apps selected</span></td>
		</tr>
		
		<tr>
			<td>
		
				<div>
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_facebook" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_twitter" src="<?php echo plugins_url( 'includes/images/icons/twitter.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_google" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_vk" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_tumblr" src="<?php echo plugins_url( 'includes/images/icons/tumblr.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_stumble" src="<?php echo plugins_url( 'includes/images/icons/stumble.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_linkedin" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_reddit" src="<?php echo plugins_url( 'includes/images/icons/reddit.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_pinterest" src="<?php echo plugins_url( 'includes/images/icons/pininterest.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_pocket" src="<?php echo plugins_url( 'includes/images/icons/pocket.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_digg" src="<?php echo plugins_url( 'includes/images/icons/digg.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_delicious" src="<?php echo plugins_url( 'includes/images/icons/delicious.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_odnoklassniki" src="<?php echo plugins_url( 'includes/images/icons/odnoklassniki.png', __FILE__ )?>" />
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_mail" src="<?php echo plugins_url( 'includes/images/icons/mail.png', __FILE__ )?>"/>
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_print" src="<?php echo plugins_url( 'includes/images/icons/print.png', __FILE__ )?>"/>
					<img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_whatsapp" src="<?php echo plugins_url( 'includes/images/icons/whatsapp.png', __FILE__ )?>"/>
				</div>
		
				<div>
					<i class="mo_custom_sharing_icon_preview fa fa-facebook" id="mo_custom_sharing_icon_preview_facebook"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-twitter" id="mo_custom_sharing_icon_preview_twitter" style="color:#ffffff;text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_sharing_icon_preview fa fa-google" id="mo_custom_sharing_icon_preview_google"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-vk" id="mo_custom_sharing_icon_preview_vk"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-tumblr" id="mo_custom_sharing_icon_preview_tumblr"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-stumbleupon" id="mo_custom_sharing_icon_preview_stumble"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-linkedin" id="mo_custom_sharing_icon_preview_linkedin" style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-reddit" id="mo_custom_sharing_icon_preview_reddit"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-pinterest" id="mo_custom_sharing_icon_preview_pinterest"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-get-pocket" id="mo_custom_sharing_icon_preview_pocket"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-digg" id="mo_custom_sharing_icon_preview_digg"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-delicious" id="mo_custom_sharing_icon_preview_delicious"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-odnoklassniki" id="mo_custom_sharing_icon_preview_odnoklassniki"  style="color:#ffffff;text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_preview fa fa-envelope" id="mo_custom_sharing_icon_preview_mail"  style="color:#ffffff;text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_preview fa fa-print" id="mo_custom_sharing_icon_preview_print"  style="color:#ffffff;text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_preview fa fa-whatsapp" id="mo_custom_sharing_icon_preview_whatsapp"  style="color:#ffffff;text-align:center;  "></i>
				</div>
											
				<div>
					<i class="mo_custom_sharing_icon_font_preview fa fa-facebook" id="mo_custom_sharing_icon_font_preview_facebook"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-twitter" id="mo_custom_sharing_icon_font_preview_twitter" style="text-align:center;margin-top:5px;" ></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-google" id="mo_custom_sharing_icon_font_preview_google"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-vk" id="mo_custom_sharing_icon_font_preview_vk"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-tumblr" id="mo_custom_sharing_icon_font_preview_tumblr"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-stumbleupon" id="mo_custom_sharing_icon_font_preview_stumble"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-linkedin" id="mo_custom_sharing_icon_font_preview_linkedin" style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-reddit" id="mo_custom_sharing_icon_font_preview_reddit"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-pinterest" id="mo_custom_sharing_icon_font_preview_pinterest"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-get-pocket" id="mo_custom_sharing_icon_font_preview_pocket"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-digg" id="mo_custom_sharing_icon_font_preview_digg"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-delicious" id="mo_custom_sharing_icon_font_preview_delicious"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-odnoklassniki" id="mo_custom_sharing_icon_font_preview_odnoklassniki"  style="text-align:center;margin-top:5px;"></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-envelope" id="mo_custom_sharing_icon_font_preview_mail"  style="text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-print" id="mo_custom_sharing_icon_font_preview_print"  style="text-align:center;  "></i>
					<i class="mo_custom_sharing_icon_font_preview fa fa-whatsapp" id="mo_custom_sharing_icon_font_preview_whatsapp"  style="text-align:center;  "></i>
				</div>
	
			</td>
		</tr>
                </table>
                <hr/>
        </td></tr>
        <tr id="share_cnt">
            <td>
                <table style="width: 100%">
                    <tr>
                        <td>
                            <h3>Social share counts</h3>
                            <b>Share counts are supported for Facebook, Vkontakte, Stumble Upon and Pinterest.</b>
                            <br>
                            <br>
                            <input type="checkbox" id="share_count" name="mo_openid_share_count" value="1"
                                <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> <?php checked( get_option('mo_openid_share_count') == 1 );?> />
                            <strong>Enable share counts</strong>
                            <br><br>
                            <input type="button" id="delete_count_transient" value="Delete Count Session" name="delete_count_transient"
                                   style="width:150px; margin-bottom: 0px" onclick="delete_transit()" class="button button-primary button-large"/>
                        </td>
                    </tr>
                    <script>
                        function  delete_transit() {
                            var a= "<?php delete_transient('facebook');
                                delete_transient('vkontakte');
                                delete_transient('stumbleupon');
                                delete_transient('pinterest'); ?>";
                            location.reload();
                        }
                    </script>
                    <tr>
                        <td style="width: 100%; align-content: center">
                            <div class="sharecount" style="text-align: center">
                                <ul class="share_count_icon">
                                    <li >
                                        <img class="mo_sharing_icon_preview" id="mo_sharing_icon_preview_count_facebook" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" />
                                        <i class="mo_custom_sharing_icon_preview fa fa-facebook" id="mo_custom_sharing_icon_preview_count_facebook"  style="color:#ffffff;text-align:center;"></i>
                                        <i class="mo_custom_sharing_icon_font_preview fa fa-facebook" id="mo_custom_sharing_icon_font_preview_count_facebook"  style="text-align:center;margin-top:5px;"></i>
                                        <span style='margin-left : <?php echo esc_attr(get_option('mo_sharing_icon_space'))?>px !important'>12k</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <b>Facebook Access Token</b> <input style="width: 50%;" type="text" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> name="mo_openid_Facebook_share_count_api" value="<?php echo esc_attr(get_option( 'mo_openid_Facebook_share_count_api')); ?>"><br/>
                                <p><b>Instructions to configure Facebook Share Counts:</b></p>
                                <ol>
                                    <li>Go to <a href="https://developers.facebook.com/tools/explorer/" target="_blank">https://developers.facebook.com/tools/explorer/</a>. Sign in with your Facebook account.</li>
                                    <li>Use an existing app if you already have one or create a new facebook app by clicking on <b>Create App</b>. Go to <b>My Apps</b> and select <b>Create App</b>. Enter Display Name i.e App Name and click on <b>Create App ID</b>.</li>
                                    <li>Go to <b>Tools</b> select <b>Graph API Explorer</b> and click on <b>Get Access Token</b>.</li>
                                    <li>Now, go to <a href="https://developers.facebook.com/tools/accesstoken" target="_blank">Access Token Tool</a> and press <b>Debug</b> option at right side for the <b>User Token</b></li>
                                    <li>Now copy the <b>Access Token</b> and paste it in the above field and click on <b>save</b>.</li>
                                </ol>
                                <p><span style="color: red">*</span><strong>According to the new updates of Facebook API it will expires after every 60 days. So to avoid any issues update it again before 60 days.</strong></p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
		
		<script>
					var tempHorSize = '<?php echo esc_attr(get_option('mo_sharing_icon_custom_size')) ?>';
					var tempHorShape = '<?php echo esc_attr(get_option('mo_openid_share_theme')) ?>';
					var tempHorTheme = '<?php echo esc_attr(get_option('mo_openid_share_custom_theme')) ?>';
					var tempbackColor = '<?php echo esc_attr(get_option('mo_sharing_icon_custom_color'))?>';
					var tempHorSpace = '<?php echo esc_attr(get_option('mo_sharing_icon_space'))?>';
					var tempHorFontColor = '<?php echo esc_attr(get_option('mo_sharing_icon_custom_font'))?>';
					function moSharingIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>10&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
					
					moSharingIncrement(document.getElementById('mo_sharing_size_plus'), "add", document.getElementById('mo_sharing_icon_size'), 300, 0.7);
					moSharingIncrement(document.getElementById('mo_sharing_size_minus'), "subtract", document.getElementById('mo_sharing_icon_size'), 300, 0.7);
					
					function moSharingSpaceIncrement(e,t,r,a,i){
						var h,s,c=!1,_=a;s=function(){
							"add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
					moSharingSpaceIncrement(document.getElementById('mo_sharing_space_plus'), "add", document.getElementById('mo_sharing_icon_space'), 300, 0.7);
					moSharingSpaceIncrement(document.getElementById('mo_sharing_space_minus'), "subtract", document.getElementById('mo_sharing_icon_space'), 300, 0.7);
					
					
					function setTheme(){return jQuery('input[name=mo_openid_share_theme]:checked', '#settings_form').val();}
					function setCustomTheme(){return jQuery('input[name=mo_openid_share_custom_theme]:checked', '#settings_form').val();}
		</script>	
		
		

		<script type="text/javascript">
				
				var selectedApps = [];
		
					
				
						function moSharingPreview(e,t,r,w,h,n,x){
							
							if("default"==w){
								var a="mo_sharing_icon_preview";
								jQuery('.mo_sharing_icon_preview').show();
								jQuery('.mo_custom_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_font_preview').hide();
								jQuery("."+a).css({height:t,width:t});
								jQuery("."+a).css("font-size",(t-10)+"px");
								jQuery("."+a).css("margin-left",(n-4)+"px");
								
								if(r=="circle"){
								jQuery("."+a).css("borderRadius","999px");
								}else if(r=="oval"){
								jQuery("."+a).css("borderRadius","5px");
								}else if(r=="square"){
								jQuery("."+a).css("borderRadius","0px");
								}
								
							}
							else if(w == "custom"){
								var a="mo_custom_sharing_icon_preview";
								jQuery('.mo_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_font_preview').hide();
								jQuery('.mo_custom_sharing_icon_preview').show();
								jQuery("."+a).css("background","#"+h);
								jQuery("."+a).css("padding-top","8px");
								jQuery("."+a).css({height:t-8,width:t});
								jQuery("."+a).css("font-size",(t-16)+"px");
								
								if(r=="circle"){
								jQuery("."+a).css("borderRadius","999px");
								}else if(r=="oval"){
								jQuery("."+a).css("borderRadius","5px");
								}else if(r=="square"){
								jQuery("."+a).css("borderRadius","0px");
								}
								
								jQuery("."+a).css("margin-left",(n-4)+"px");
							}
							
							else if("customFont"==w){
								var a="mo_custom_sharing_icon_font_preview";
								jQuery('.mo_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_preview').hide();
								jQuery('.mo_custom_sharing_icon_font_preview').show();
								jQuery("."+a).css("font-size",t+"px");
								jQuery('.mo_custom_sharing_icon_font_preview').css("color","#"+x);
								jQuery("."+a).css("margin-left",(n-4)+"px");
								
								if(r=="circle"){
								jQuery("."+a).css("borderRadius","999px");
								
								}else if(r=="oval"){
								jQuery("."+a).css("borderRadius","5px");
								}else if(r=="square"){
								jQuery("."+a).css("borderRadius","0px");
								}
								
							}
							addSelectedApps();
							
							
							
						}
						moSharingPreview('horizontal',tempHorSize,tempHorShape,tempHorTheme,tempbackColor,tempHorSpace,tempHorFontColor);
						
						function addSelectedApps() {
							var flag = 0;
								if (document.getElementById('google_share_enable').checked)   {
									flag = 1;
										if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_google").show();
										if(document.getElementById('mo_openid_custom_background_radio').checked)
											jQuery("#mo_custom_sharing_icon_preview_google").show();
										if(document.getElementById('mo_openid_no_background_radio').checked)
											jQuery("#mo_custom_sharing_icon_font_preview_google").show();
								} else if(!document.getElementById('google_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_google").hide();
									jQuery("#mo_custom_sharing_icon_preview_google").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_google").hide();
									
								}
								
								if (document.getElementById('facebook_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_facebook").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_facebook").show();
								}else if(!document.getElementById('facebook_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_facebook").hide();
									jQuery("#mo_custom_sharing_icon_preview_facebook").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_facebook").hide();
								}
								
								if (document.getElementById('linkedin_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_linkedin").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)	
										jQuery("#mo_custom_sharing_icon_font_preview_linkedin").show();
								} else if(!document.getElementById('linkedin_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_linkedin").hide();
									jQuery("#mo_custom_sharing_icon_preview_linkedin").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_linkedin").hide();
								}
								
								if (document.getElementById('twitter_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_twitter").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_twitter").show();
								} else if(!document.getElementById('twitter_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_twitter").hide();
									jQuery("#mo_custom_sharing_icon_preview_twitter").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_twitter").hide();
								}
								
								if (document.getElementById('pinterest_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_pinterest").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_pinterest").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_pinterest").show();
								}else if(!document.getElementById('pinterest_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_pinterest").hide();
									jQuery("#mo_custom_sharing_icon_preview_pinterest").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_pinterest").hide();
								}
								
								if (document.getElementById('reddit_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_reddit").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_reddit").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_reddit").show();
									//}
								} else if(!document.getElementById('reddit_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_reddit").hide();
									jQuery("#mo_custom_sharing_icon_preview_reddit").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_reddit").hide();
								}
								
								if (document.getElementById('vkontakte_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_vk").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_vk").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_vk").show();
									//}
								} else if(!document.getElementById('vkontakte_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_vk").hide();
									jQuery("#mo_custom_sharing_icon_preview_vk").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_vk").hide();
								}
								
								if (document.getElementById('stumble_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_stumble").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_stumble").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_stumble").show();
									//}
								} else if(!document.getElementById('stumble_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_stumble").hide();
									jQuery("#mo_custom_sharing_icon_preview_stumble").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_stumble").hide();
								}
								
								if (document.getElementById('tumblr_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_tumblr").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_tumblr").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_tumblr").show();
									//}
								} else if(!document.getElementById('tumblr_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_tumblr").hide();
									jQuery("#mo_custom_sharing_icon_preview_tumblr").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_tumblr").hide();
								}
								
								if (document.getElementById('pocket_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_pocket").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_pocket").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_pocket").show();
									//}
								} else if(!document.getElementById('pocket_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_pocket").hide();
									jQuery("#mo_custom_sharing_icon_preview_pocket").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_pocket").hide();
								}
								if (document.getElementById('digg_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_digg").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_digg").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_digg").show();
									//}
								} else if(!document.getElementById('digg_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_digg").hide();
									jQuery("#mo_custom_sharing_icon_preview_digg").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_digg").hide();
								}
								if (document.getElementById('delicious_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_delicious").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_delicious").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_delicious").show();
									//}
								} else if(!document.getElementById('delicious_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_delicious").hide();
									jQuery("#mo_custom_sharing_icon_preview_delicious").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_delicious").hide();
								}
								if (document.getElementById('odnoklassniki_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
										jQuery("#mo_sharing_icon_preview_odnoklassniki").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_odnoklassniki").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_odnoklassniki").show();
									//}
								} else if(!document.getElementById('odnoklassniki_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_odnoklassniki").hide();
									jQuery("#mo_custom_sharing_icon_preview_odnoklassniki").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_odnoklassniki").hide();
								}
								if (document.getElementById('mail_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_mail").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_mail").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_mail").show();
								} else if(!document.getElementById('mail_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_mail").hide();
									jQuery("#mo_custom_sharing_icon_preview_mail").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_mail").hide();
								}
								if (document.getElementById('print_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_print").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_print").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_print").show();
								} else if(!document.getElementById('print_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_print").hide();
									jQuery("#mo_custom_sharing_icon_preview_print").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_print").hide();
								}
								if (document.getElementById('whatsapp_share_enable').checked) {
									flag = 1;
									if(document.getElementById('mo_openid_default_background_radio').checked)
											jQuery("#mo_sharing_icon_preview_whatsapp").show();
									if(document.getElementById('mo_openid_custom_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_preview_whatsapp").show();
										jQuery("#mo_sharing_button_preview_custom_whatsapp").show();
									if(document.getElementById('mo_openid_no_background_radio').checked)
										jQuery("#mo_custom_sharing_icon_font_preview_whatsapp").show();
									
								} else if(!document.getElementById('whatsapp_share_enable').checked){
									jQuery("#mo_sharing_icon_preview_whatsapp").hide();
									jQuery("#mo_custom_sharing_icon_preview_whatsapp").hide();
									jQuery("#mo_custom_sharing_icon_font_preview_whatsapp").hide();
								}
								
								if(flag) {
									jQuery("#no_apps_text").hide();
								} else {
									jQuery("#no_apps_text").show();
								}
						}
						
				jQuery( document ).ready(function() {
						addSelectedApps();					
				});
		</script>
        <script>
            function restart_tour() {

                tour.restart();
            }

            var tour = new Tour({
                name: "tour",
                steps: [
                    {
                        element: "#sel_apps",
                        title: "Select Apps",
                        content: "Select multiple apps to enable Social Sharing.",
                        backdrop:'body',
                        backdropPadding:'6'
                    }, {
                        element: "#cust_icon",
                        title: "Customize Icons",
                        content: "Customize the look and feel of app icons to suit your website's theme. Live preview included.",
                        backdrop:'body',
                        backdropPadding:'6'
                    }, {
                        element: "#share_cnt",
                        title: "Social share Count",
                        content: "Enable social share count to display the total number of shares of your page.",
                        backdrop:'body',
                        backdropPadding:'6'
                    }, {
                        element: "#disp_options",
                        title: "Display Option",
                        content: "Select the pages where you want to show the social sharing icons.",
                        backdrop:'body',
                        backdropPadding:'6'
                    }
                    ,{
                        element: "#support",
                        title: "miniOrange Support",
                        content: "Feel free to reach out to us in case of any assistance.",
                        backdrop:'body',
                        backdropPadding:'6',


                    },{
                        element: "#share_save",
                        title: "Save",
                        content: "Save the updated changes.",
                        backdrop:'body',
                        backdropPadding:'6'
                    }

                ],
                template: function () {
                    return (
                        tour.getCurrentStep()===5 // Is this the last step?
                            ? "<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\"><div style=\"width:47%;margin-top: -7%;\"><h4  style=\"float:left;margin-top:30%;margin-left:30%;\">"+ (tour.getCurrentStep()+1)+"/6</h4></div></div>&nbsp;&nbsp; <button class=\"button button-primary button-large\" data-role=\"end\">Done</button> </div> </div>"
                            : "<div class=\"mo2f_popover\" role=\"tooltip\"> <div class=\"mo2f_arrow\" ></div> <h3 class=\"mo2f_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo2f_popover-body\"></div> <div class=\"mo2f_popover-navigation\"> <div class=\"mo2f_tour_btn-group\" style=\"width: 100%;\"><div style='margin-top: -6%;' ><h4>"+ (tour.getCurrentStep()+1)+"/6</h4></div> <button class=\"mo2f_tour_btn mo2f_tour_btn-sm mo2f_tour_btn-secondary mo2f_tour_btn_next-success\" style=\"width: 32%;height: 0%;margin-left: 25%;\" data-role=\"next\">Next &raquo;</button><button class=\"button button-primary button-large\" data-role=\"end\" style='margin-left: 7%'>End</button></div></div></div>"
                    );
                }
            });



        </script>
		<tr>
			<td>
				<br/>
				<strong>*NOTE:</strong><br/>Custom background: This will change the background color of sharing icons.
				<br/>No background: This will change the font color of icons without background.
			</td>
		</tr>
		<tr>
			<td>
			<br>
			<hr>
			<h3>Customize Text For Social Share Icons</h3>
			</td>
		</tr>

		<tr>
			<td><b>Select color for share heading:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input id="mo_openid_table_textbox" style="width:135px;" name="mo_openid_share_widget_customize_text_color" class="color" value="<?php echo esc_attr(get_option('mo_openid_share_widget_customize_text_color'))?>" > </td>
		</tr>
		<tr>
			<td>
				<b>Enter text to show above share widget:</b>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_widget_customize_text"
					value="<?php echo esc_attr(get_option('mo_openid_share_widget_customize_text')); ?>"  />
			</td>
		</tr>
		<tr>
			<td>
				<b>Enter your twitter Username (without @):</b>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_twitter_username"
					value="<?php echo esc_attr(get_option('mo_openid_share_twitter_username')); ?>"  />
			</td>
		</tr>
		<tr>
			<td>
				<b>Enter the Email subject (email share):</b>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_email_subject"
					value="<?php echo esc_attr(get_option('mo_openid_share_email_subject')); ?>"  />
			</td>
		</tr>
		<tr>
			<td>
				<b>Enter the Email body (add ##url## to place the URL):</b>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input class="mo_openid_table_textbox" style="width:50%;" type="text" name="mo_openid_share_email_body"
					value="<?php echo esc_attr(get_option('mo_openid_share_email_body')); ?>"  />
			</td>
		</tr>
		<tr id="disp_options"><td><table><tr>
			<td>
				<br>
				 <hr>
				<h3>Display Options</h3>
				<p><strong>Select the options where you want to display social share icons</strong></p>
			</td>
		</tr>
											
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_home_page"  name="mo_share_options_home_page"  value="1"  <?php checked( get_option('mo_share_options_enable_home_page') == 1 );?>>
				Home Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="before"  <?php checked( get_option('mo_share_options_home_page_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="after"  <?php checked( get_option('mo_share_options_home_page_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_home_page_position" value="both"  <?php checked( get_option('mo_share_options_home_page_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>

		<tr>
			<td>
				<input type="checkbox" id="mo_apps_posts"  name="mo_share_options_post" value="1"  <?php checked( get_option('mo_share_options_enable_post') == '1' );?>>
				Blog Post
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="before"  <?php checked( get_option('mo_share_options_enable_post_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="after"  <?php checked( get_option('mo_share_options_enable_post_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_enable_post_position" value="both"  <?php checked( get_option('mo_share_options_enable_post_position') == 'both' );?>>
				Both before and after content
			</td>		
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_static_page"  name="mo_share_options_static_pages"  value="1"  <?php checked( get_option('mo_share_options_enable_static_pages') == 1 );?>>
				Static Pages
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="before"  <?php checked( get_option('mo_share_options_static_pages_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="after"  <?php checked( get_option('mo_share_options_static_pages_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_static_pages_position" value="both"  <?php checked( get_option('mo_share_options_static_pages_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_wc_sp_page_top"  name="mo_share_options_wc_sp_summary_top"  value="1"  <?php checked( get_option('mo_share_options_wc_sp_summary_top') == 1 );?>>
				WooCommerce Individual Product Page(Top)
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_wc_sp_page"  name="mo_share_options_wc_sp_summary"  value="1"  <?php checked( get_option('mo_share_options_wc_sp_summary') == 1 );?>>
				WooCommerce Individual Product Page
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_bb_forum"  name="mo_share_options_bb_forum"  value="1"  <?php checked( get_option('mo_share_options_bb_forum') == 1 );?>>
				BBPress Forums Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="before"  <?php checked( get_option('mo_share_options_bb_forum_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="after" <?php checked( get_option('mo_share_options_bb_forum_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_forum_position" value="both"  <?php checked( get_option('mo_share_options_bb_forum_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_bb_topic"  name="mo_share_options_bb_topic"  value="1"  <?php checked( get_option('mo_share_options_bb_topic') == 1 );?>>
				BBPress Topic Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="before"  <?php checked( get_option('mo_share_options_bb_topic_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="after"  <?php checked( get_option('mo_share_options_bb_topic_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_topic_position" value="both"  <?php checked( get_option('mo_share_options_bb_topic_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_apps_bb_reply"  name="mo_share_options_bb_reply"  value="1"  <?php checked( get_option('mo_share_options_bb_reply') == 1 );?>>
				BBPress Reply Page
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="before"  <?php checked( get_option('mo_share_options_bb_reply_position') == 'before' );?>>
				Before content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="after"  <?php checked( get_option('mo_share_options_bb_reply_position') == 'after' );?>>
				After content
				<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="mo_apps_posts_options"  name="mo_share_options_bb_reply_position" value="both"  <?php checked( get_option('mo_share_options_bb_reply_position') == 'both' );?>>
				Both before and after content
			</td>
		</tr></table></td></tr>
				<tr>
			<td>
				<br/>
				<strong>NOTE:</strong>  The icons in above pages will be placed horizontally. For vertical icons, add <b>miniOrange Sharing - Vertical</b> widget from Appearance->Widgets.
			</td>
		</tr>
		<tr>
			<td>
			<br>
			<hr>
			<h3>Floating Vertical Social Share</h3>
			<p>Floating vertical share icons can be added in two ways.</p>
			<ul>
				<li><b>Widget</b>: Go to Appearance > Widgets. There are a few options which can be set like Alignment, left/right offset, top offset and space between icons</li>
				<li><b>Shortcode</b>: Add [miniorange_social_sharing_vertical] to your page or post to add vertical icons. Further details are available on >Shortcode tab</li>
			</ul>
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" id="mo_share_vertical_hide_mobile"  name="mo_share_vertical_hide_mobile"  value="1"  <?php checked( get_option('mo_share_vertical_hide_mobile') == 1 );?>>
				Hide Floating Vertical Share icons on mobile
			</td>
		</tr>
		<tr>
									
			<td>
				<br />
				<input type="submit" name="submit" value="Save" style="width:100px;"
					class="button button-primary button-large" />
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr>
				<p>
					<h3>Add Sharing Icons</h3>
					You can add sharing icons in the following areas from <strong>Display Options</strong>. For other areas(widget areas) and vertical floating widget, use Sharing Widgets.
				<ol>
					<li>Home Page: This option places sharing icons in the homepage.</li>
					<li>Blog Post: This option places sharing icons in individual post pages.</li>
					<li>Static Pages: This option places sharing icons in all non-post pages.</li>
				</ol>
					<h3>Add Sharing Icons as Widget</h3>

				<ol>
					<li>Go to Appearance->Widgets. Among the available widgets you will find <b>miniOrange Sharing - Vertical</b> and <b>miniOrange Sharing - Horizontal</b>.</li>
					<li>Drag the one you want to a widget area. You can edit Vertical widget position.</li>
					<li>Now go to your site. You will see the icons for apps which you enabled for sharing.</li>
				</ol>
				</p>
			</td>
		</tr>					
    </table>		
	</div>

</form>
<script>
jQuery(function() {
				jQuery('#tab1').removeClass("nav-tab-active");
				jQuery('#tab2').addClass("nav-tab-active");
				
		});
</script>
</td>
		<td style="vertical-align:top;padding-left:1%;">
            <div id="support"><?php echo miniorange_openid_support(); ?></div>
		</td>
<?php
}

function mo_openid_shortcode_info(){
?>
<td style="vertical-align:top;width:65%;">
	<div class="mo_openid_table_layout">

        <table>
            <tr>
                <td colspan="2">
                    <h3>Shortcode</h3>
                    <b>If you are using Social login, Social Sharing by miniOrange plugin,  follow the steps mentioned below to enable social login/social sharing in the content of individual page/post/frontend login form.</b>
                    <p>If any section is not opening, press CTRL + F5 to clear cache.<p>

                </td>
            </tr>
            <tr>
                <td>
                    <h3><a id="openid_login_shortcode_title"  aria-expanded="false" >Social Login Shortcode</a></h3>
                    <div hidden="" id="openid_login_shortcode" style="font-size:13px !important">
                        Use social login Shortcode in the content of required page/post where you want to display Social Login Icons.<br/>
                        <h3>Default Social Login Shortcode</h3>
                        This will display Social Login Icons with same default settings<br/>
                        <b>Example:</b> <code>[miniorange_social_login]</code><hr>

                        <h3>For Icons</h3>
                        You can use  different attribute to customize social login icons. All attributes are optional except view.<br/><br/>
                        <b>Square Shape Example:</b> <code>[miniorange_social_login shape="square" theme="default" space="4" size="35"]</code><br/><br/>
                        <a class=" login-button" rel="nofollow" title=" Login with Facebook"><img alt="Facebook" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" class=" login-button square"></a>
                        <a class=" login-button" rel="nofollow" title=" Login with Google"><img alt="Google" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" class=" login-button square"></a>
                        <a class=" login-button" rel="nofollow" title=" Login with Vkontakte"><img alt="Vkontakte" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" class=" login-button square"></a>
                        <a class=" login-button" rel="nofollow" title=" Login with LinkedIn"><img alt="LinkedIn" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" class=" login-button square"></a>
                        <a class=" login-button" rel="nofollow" title=" Login with instagram"><img alt="Instagram" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/instagram.png', __FILE__ )?>" class=" login-button square"></a><br/><br/>

                        <b>Round Shape Example:</b> <code>[miniorange_social_login shape="round" theme="default" space="4" size="35"]</code><br/><br/>
                        <a class=" login-button" rel="nofollow" title=" Login with Facebook"><img alt="Facebook" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/facebook.png', __FILE__ )?>" class=" login-button round"></a>
                        <a class=" login-button" rel="nofollow" title=" Login with Google"><img alt="Google" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/google.png', __FILE__ )?>" class=" login-button round"></a>
                        <a class=" login-button" rel="nofollow" title=" Login with Vkontakte"><img alt="Vkontakte" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/vk.png', __FILE__ )?>" class=" login-button round"></a>
                        <a class=" login-button" rel="nofollow" title=" Login with LinkedIn"><img alt="LinkedIn" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/linkedin.png', __FILE__ )?>" class=" login-button round"></a>
                        <a class=" login-button" rel="nofollow" title="login with instagram"><img alt="Instagram" style="width:35px !important;height: 35px !important;margin-left: 4px !important" src="<?php echo plugins_url( 'includes/images/icons/instagram.png', __FILE__ )?>" class=" login-button round"></a><br/><br/>

                        <hr>
                        <h3>For Long-Buttons</h3>
                        You can use different attribute to customize social login buttons. All attributes are optional.<br/><br/>
                        <b>Vertical View Example:</b><code>[miniorange_social_login shape="longbuttonwithtext" theme="default" space="8" width="180" height="35" color="000000"]</code><br/><br/>
                        <a rel="nofollow" style="margin-left: 8px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 3px !important;border-radius: 4px !important;" class="btn btn-mo btn-block btn-social btn-facebook btn-custom-dec login-button"> <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:5px;border-right:none;margin-left: 2%;"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg>Login with Facebook</a>
                        <a rel="nofollow" style="margin-left: 8px !important;background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 3px !important;border-radius: 4px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" class="btn btn-mo btn-block btn-social btn-google btn-custom-dec login-button"> <i style="padding-top:6px !important;border-right:none;" class="fa"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70 70" style="padding-left: 8%;"><defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"></path></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"></use></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"></path><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"></path><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"></path><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"></path></svg></i><span style="color:#ffffff;">Login with Google</span></a>
                        <a rel="nofollow" style="margin-left: 8px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 3px !important;border-radius: 4px !important;" class="btn btn-mo btn-block btn-social btn-vk btn-custom-dec login-button"> <i style="padding-top:0px !important" class="fa fa-vk"></i>Login with Vkontakte</a><br/>

                        <b>Horizontal View Example:</b><code>[miniorange_social_login shape="longbuttonwithtext" view="horizontal" appcnt="3" theme="default" space="35" width="180" height="35" color="000000"]</code><br/><br/>
                        <a rel="nofollow" style="margin-left: 35px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 30px !important;border-radius: 4px !important;" class="btn btn-mo btn-block-inline btn-social btn-facebook btn-custom-dec login-button"> <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:5px;border-right:none;margin-left: 2%;"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg>Login with Facebook</a>
                        <a rel="nofollow" style="margin-left: 35px !important;background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 30px !important;border-radius: 4px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" class="btn btn-mo btn-block-inline btn-social btn-google btn-custom-dec login-button"> <i style="padding-top:6px !important;border-right:none;" class="fa"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70 70" style="padding-left: 8%;"><defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"></path></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"></use></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"></path><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"></path><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"></path><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"></path></svg></i><span style="color:#ffffff;">Login with Google</span></a>
                        <a rel="nofollow" style="margin-left: 35px !important;width: 180px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 30px !important;border-radius: 4px !important;" class="btn btn-mo btn-block-inline btn-social btn-vk btn-custom-dec login-button"> <i style="padding-top:0px !important" class="fa fa-vk"></i>Login with Vkontakte</a><br/>
                        <b>Note:</b> By default Long-Button view is <b>Vertical</b><hr>

                        <h3>For Selected Applications</h3>
                        If you want to show selected applications without setting up default settings then use this shortcode. You can use different attribute to customize social login buttons. All attributes are optional.<br/><br/>
                        <b>Example:</b><code>[miniorange_social_login apps="vk,google,fb" shape="longbuttonwithtext" view="horizontal" theme="default" space="10" width="200" height="35" color="000000"]</code><br/><br/>
                        <a rel="nofollow" style="margin-left: 10px !important;width: 200px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 5px !important;border-radius: 4px !important;" class="btn btn-mo btn-block-inline btn-social btn-vk btn-custom-dec login-button"> <i style="padding-top:0px !important" class="fa fa-vk"></i>Login with Vkontakte</a>
                        <a rel="nofollow" style="margin-left: 10px !important;background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;width: 200px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 5px !important;border-radius: 4px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;" class="btn btn-mo btn-block-inline btn-social btn-google btn-custom-dec login-button"> <i style="padding-top:6px !important;border-right:none;" class="fa"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70 70" style="padding-left: 8%;"><defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"></path></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"></use></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"></path><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"></path><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"></path><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"></path></svg></i><span style="color:#ffffff;">Login with Google</span></a>
                        <a rel="nofollow" style="margin-left: 10px !important;width: 200px !important;padding-top:6px !important;padding-bottom:6px !important;margin-bottom: 5px !important;border-radius: 4px !important;" class="btn btn-mo btn-block-inline btn-social btn-facebook btn-custom-dec login-button""> <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:5px;border-right:none;margin-left: 2%;"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg>Login with Facebook</a>
                        <br/><br/>
                        <table border="1" width="100%">
                            <tr align="center">
                                <td colspan="8"><b>Values for application</b></td>
                            </tr>
                            <tbody align="center"><tr>
                                <td><b>Name</b></td><td><b>Value</b></td><td><b>Name</b></td><td><b>Value</b></td><td><b>Name</b></td><td><b>Value</b></td><td><b>Name</b></td><td><b>Value</b></td>
                            </tr><tr>
                                <td><b>Facebook</b></td><td>fb</td><td><b>Google</b></td><td>google</td><td><b>Vkontakte</b></td><td>vk</td><td><b>Twitter</b></td><td>twitter</td>
                            </tr><tr>
                                <td><b>Instagram</b></td><td>insta</td><td><b>LinkedIn</b></td><td>linkin</td><td><b>Amazon</b></td><td>amazon</td><td><b>Salesforce</b></td><td>sforce</td>
                            </tr><tr>
                                <td><b>Windows Live</b></td><td>wlive</td><td><b>Yahoo</b></td><td>yahoo</td><td><b>Wordpress</b></td><td>wpress</td><td><b>Disqus</b></td><td>disqus</td>
                            </tr><tr>
                                <td><b>Pinterest</b></td><td>pin</td><td><b>Twitch</b></td><td>twitch</td><td><b>Vimeo</b></td><td>vimeo</td><td><b>Kakao</b></td><td>kakao</td>
                            </tr></tbody>
                        </table><br/>
                        <b>Note:</b> If Facebook, Disqus, Pinterest, Twitch, Vimeo or Kakao is selected then please set up <b>Custom App</b> first otherwise the buttons will be <b>disabled<b>
                                <br/><hr/><br/>

                                <table border="1" width="60%">
                                    <tr align="center">
                                        <td colspan="2"><b>Available values for attributes</b></td>
                                    </tr>
                                    <tbody align="center">
                                    <tr width="100%">
                                        <td width="20%"><b>view</b></td>
                                        <td width="80%">horizontal, vertical</td>
                                    </tr>
                                    <tr>
                                        <td><b><b>shape</b></b></td>
                                        <td>round, roundededges, square, longbuttonwithtext</td>
                                    </tr>
                                    <tr>
                                        <td><b><b>theme</b></b></td>
                                        <td>default, custombackground</td>
                                    </tr>
                                    <tr>
                                        <td><b><b>size</b></b></td>
                                        <td>Any value between 20 to 100</td>
                                    </tr>
                                    <tr>
                                        <td><b><b>space</b></b></td>
                                        <td>Any value between 0 to 100</td>
                                    </tr>
                                    <tr>
                                        <td><b><b>width</b></b></td>
                                        <td>Any value between 200 to 1000</td>
                                    </tr>
                                    <tr>
                                        <td><b>height</b></td>
                                        <td>Any value between 35 to 50</td>
                                    </tr>
                                    <tr>
                                        <td><b>color</b></td>
                                        <td>Enter color code for example, FFFFFF</td>
                                    </tr>
                                    <tr>
                                        <td><b>heading</b></td>
                                        <td>Enter custom heading</td>
                                    </tr>
                                    <tr>
                                        <td><b>appcnt</b></td>
                                        <td>Any value for number of icons in one row</td>
                                    </tr>
                                    </tbody>
                                </table>
                    </div>
                    <hr>
                </td>
            </tr>

            <tr>
                <td>
                    <h3><a id="openid_sharing_shortcode_title"  >Social Sharing Shortcode</a></h3>
                    <div hidden="" id="openid_sharing_shortcode" style="font-size:13px !important">
                        <b>Horizontal</b> --> <code>[miniorange_social_sharing]</code><br>
                        <b>Vertical</b> --> <code>[miniorange_social_sharing_vertical]</code>
                        <!--Use [miniorange_social_sharing] Shortcode in the content of required page/post where you want to display horizontal Social Sharing Icons. Use [miniorange_social_sharing_vertical] shortcode for vertical Social Sharing Icons.--><br>


                        <h4>For Sharing Icons</h4>
                        You can use  different attribute to customize social sharing icons. All attributes are optional.<br>
                        <b>Example:</b> <code> [miniorange_social_sharing  shape="square" heading="Share with" color="#000000" fontcolor="blue" theme="nobackground" space="4" size="30" url="https://www.miniorange.com"]</code>
                        <br>

                        <h4 style="margin-bottom:0 !important">Common attributes - Horizontal and Vertical</h4>
                        <b>shape</b>: round, roundededges, square<br>
                        <b>theme</b>: default, custombackground, nobackground<br>
                        <b>size</b>: Any value between 20 to 100<br>
                        <b>space</b>: Any value between 0 to 50<br>
                        <b>url</b>: Enter custom URL for sharing<br>
                        <b>fontcolor</b>: Enter custom color for icons (only works with no background theme)<br>
                        <b>sharecnt</b>: yes, no <b>*To see social share count*</b><br>
                        <h4 style="margin-bottom:0 !important">Horizontal attributes</h4>
                        <b>heading</b>: Enter custom heading text<br>
                        <b>color</b>: Enter custom text color for heading eg: cyan, red, blue, orange, yellow <br>
                        <br>
                        <b>Vertical attributes</b><br>
                        <b>alignment</b>: left,right<br>
                        <b>topoffset</b>: Any value(height from top) between 0 to 1000<br>
                        <b>rightoffset(Applicable if alignment is right)</b>: Any value between 0 to 200<br>
                        <b>leftoffset(Applicable if alignment is left)</b>: Any value between 0 to 200<br>
                    </div>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    <h3><a   id="openid_comments_shortcode_title"  >Social Comments Shortcode</a></h3>
                    <div hidden="" id="openid_comments_shortcode" style="font-size:13px !important">
                        <code>[miniorange_social_comments]</code><br>	<br>
                        1. Configure Social Comments in the Social Comments tab of the plugin.<br>
                        2. Keep both the display options checked and Save.<br>
                        3. Enable Comments for the post/page you want to add <br>
                        4. <b>Pages-> Quick Edit-> Allow Comments</b> (Skip this step if you already have Comments enabled.)<br>
                        5. Add the shortcode [miniorange_social_comments] to an individual page/post.<br>

                    </div>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    <h3><a id="openid_shortcode_inphp_title">Shortcode in php file</a></h3>
                    <div hidden="" id = "openid_shortcode_inphp" style="font-size:13px !important">
                        You can use shortcode in PHP file as following: &nbsp;&nbsp;
                        &nbsp;
                        <code>&lt;&#63;php echo do_shortcode(‘SHORTCODE’) /&#63;&gt;</code>
                        <br>
                        Replace SHORTCODE in above code with the required shortcode like [miniorange_social_login theme="default"], so the final code looks like following :
                        <br>
                        <code>&lt;&#63;php echo do_shortcode('[miniorange_social_login theme="default"]') &#63;&gt;</code></div>
                    <hr>

                </td>
            </tr>

        </table>
	</div>
	</td>
	<td style="vertical-align:top;padding-left:1%;">
		<?php echo miniorange_openid_support(); ?>
	</td>
<?php	
}

function mo_openid_pricing_info(){

 if (isset($_POST['update_tour_status'])){
        update_option('mo_openid_tour','1');

    }
    ?>
    <div id="upgrade_notice" class="notice notice-success is-dismissible" style="width: 94.8%;margin-left: 0.3%;display:none">
        <p><strong>How do I activate or download the social login standard/premium plugin? <a href="https://faq.miniorange.com/knowledgebase/activate-download-social-login-premium-plugin/" target="_blank">Click Here</a></strong></p>
    </div>
    <td style="vertical-align:top;width:100%;">
        <div class="mo_openid_table_layout">

            <h2>Licensing Plans For Social Login
            <span style="float:right; margin-right: 40px;">
			<input type="button" name="ok_btn" id="ok_btn" class="button button-primary button-large" value="OK, Got It" onclick="window.location.href='admin.php?page=mo_openid_settings&tab=login'" />
			<input type="button" name="check_plan" id="check_plan" <?php if(!mo_openid_is_customer_registered()) echo 'disabled'?> class="button button-primary button-large" value="Check License" onclick="checkLicense();"/>
            </span>
            </h2>

            <table class="table mo_table-bordered mo_table-striped">

                <thead>
                <tr style="background-color:#0085ba;">
                    <th width="25%"><br><br><h3><font color="#ffffff">Features / Plans</font></h3></th>
                    <th class="text-center" style="" width="25%"><h3><font color="#ffffff">Free</h3><p class="mo_plan-desc"></p><h3>$0 - One Time Payment <br><br>(You are automatically on this plan)</font><br><br><span>
							</span></h3></th>
                    <th class="text-center" width="25%"><h3><font color="#ffffff">Standard</h3><p class="mo_plan-desc"></p><h3>$19 - One Time Payment</font><br><br>
                            <span>

									<input type="button" id="upgrade_btn" name="upgrade_btn" class="button button-default button-large" value="Upgrade" onclick="upgradeform('wp_social_login_standard_plan')" />


								</span></h3></th>

                    <th class="text-center" width="25%"><h3><font color="#ffffff">Premium</h3><p class="mo_plan-desc"></p><h3>$49 - One Time Payment</font><br><br><span>
							<input type="button" id="upgrade_btn" name="upgrade_btn" class="button button-default button-large" value="Upgrade" onclick="upgradeform('wp_social_login_premium_plan')" />	</span></h3></th>


                </tr>
                </thead>

                <tbody class="mo_align-center mo-fa-icon">
                <tr>
                    <td><div class="tooltip">Pre-configured Social Login Apps <i class="fa fa-commenting " style="font-size:18px;color:#85929E"></i><span class="tooltiptext"style="width:350px;">Pre-configured apps are already configured by miniOrange. Login flow will go from plugin to miniOrange service and then to the Social Application and then back to plugin through miniOrange service.</span></div></td>
                    <td><div class="tooltip">9 Apps&nbsp  <i class="fa fa-bookmark " style="font-size:18px;color:#85929E"></i><span class="tooltiptext1" style="width:83px;"> Google  Twitter  Vkontakte  LinkedIn  Windows Live  Amazon  Instagram  Salesforce  Yahoo</span></div></td>
                    <td><div class="tooltip">10 Apps&nbsp  <i class="fa fa-bookmark " style="font-size:18px;color:#85929E"></i><span class="tooltiptext1" style="width:83px;"> Google  Twitter  Vkontakte  LinkedIn  Windows Live  Amazon  Instagram  Salesforce  Yahoo  WordPress</span></div></td>
                    <td><div class="tooltip">10 Apps&nbsp  <i class="fa fa-bookmark " style="font-size:18px;color:#85929E"></i><span class="tooltiptext1" style="width:83px;"> Google  Twitter  Vkontakte  LinkedIn  Windows Live  Amazon  Instagram  Salesforce  Yahoo  WordPress</span></div></td>

                </tr>
                <tr>
                    <td><div class="tooltip">Custom Social Login Apps <i class="fa fa-commenting " style="font-size:18px;color:#85929E"></i><span class="tooltiptext"style="width:350px;"> Using the custom app tab, you can set up your own App ID and secret in the plugin. Login flow will not involve miniOrange in between. Login flow will go from plugin to social media application and then back to plugin.</span></div></td>
                    <td>Facebook, Twitter</td>
                    <td><div class="tooltip">15 Apps&nbsp  <i class="fa fa-bookmark " style="font-size:18px;color:#85929E"></i><span class="tooltiptext1" style="width:83px;"> Facebook  Google  Twitter  Vkontakte  LinkedIn  Windows Live  Amazon  Instagram  Yahoo  WordPress  Disqus  Pinterest  Twitch  Vimeo  Kakao</span></div></td>
                    <td><div class="tooltip">15 Apps&nbsp  <i class="fa fa-bookmark " style="font-size:18px;color:#85929E"></i><span class="tooltiptext1" style="width:83px;"> Facebook  Google  Twitter  Vkontakte  LinkedIn  Windows Live  Amazon  Instagram  Yahoo  WordPress  Disqus  Pinterest  Twitch  Vimeo  Kakao</span></div></td>

                </tr>
                <tr>
                    <td>Beautiful Icon Customisations</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>16 Social Sharing Apps</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Facebook & Google+ Social Comments</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Login Redirect URL</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td> Logout Redirect URL</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>

                <tr>
                    <td>Profile completion (username, email)</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Profile Picture</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Customizable Text For Login Icons</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Option to enable/disable user registration </td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Basic Email Support</td>
                    <td><i class="fa fa-check"></i></</td>
                    <td><i class="fa fa-check"></i></</td>
                    <td><i class="fa fa-check"></i></</td>
                </tr>
                <tr>
                    <td>Email notification to admin </td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>

                <tr>
                    <td>BBPress Display Options</td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Account Linking</td>
                    <td>Basic</td>
                    <td>Advanced</td>
                    <td>Advanced</td>
                </tr>
                <tr>
                    <td>Woocommerce Display Options</td>
                    <?php if(!mo_openid_restrict_user()){?>  <td><i class="fa fa-check"></i></td>
                    <?php } else{?><td></td> <?php }?>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>BuddyPress Display Options</td>
                    <?php if(!mo_openid_restrict_user()){?>  <td><i class="fa fa-check"></i></td>
                    <?php } else{?><td></td> <?php }?>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Email Notification to multiple admins</td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Welcome email to end users </td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Customizable Email Notification template</td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Customizable welcome email template</td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Send username and password reset link</td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Social Login Opens in a New Window</td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td title="Restrict registration/login for users with a specific domain ">Domain restriction </td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td >Force Admin To Login Using Password </td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td >Recaptcha Support </td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>User Moderation </td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Account Unlinking </td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Paid Membership Pro Plugin Integration</td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td><span class="tooltip">Custom attribute mapping <i class="fa fa-commenting" style="font-size:18px;color:#85929E"></i> <span class="tooltiptext"style="width:350px;">Extended attributes returned from social app are mapped to Custom attributes created by admin. These Attributes get stored in user_meta table.</span></div></td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
				<tr>
                    <td><div class="tooltip">Woocommerce Integration <i class="fa fa-commenting" style="font-size:18px;color:#85929E"></i> <span class="tooltiptext"style="width:350px;"> First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page. Social Login icons are displayed on the Woocommerce checkout page.</span></div></td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>

                <tr>
                    <td><div class="tooltip">BuddyPress Integration <i class="fa fa-commenting " style="font-size:18px;color:#85929E"> </i><span class="tooltiptext" style="width:350px;"> Extended attributes returned from social app are mapped to Custom BuddyPress fields. Profile picture from social media is mapped to Buddypress avatar.</span></div></td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td><div class="tooltip">MailChimp Integration <i class="fa fa-commenting " style="font-size:18px;color:#85929E"> </i><span class="tooltiptext" style="width:350px;">A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></div></td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td><div class="tooltip">Extended Profile Data <i class="fa fa-commenting " style="font-size:18px;color:#85929E"> </i><span class="tooltiptext" style="width:350px;">Extended profile data feature requires additional configuration. You need to have your own social media app and permissions from social media providers to collect extended user data.</span></div></td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td>Social Analytics Dashboard Access</td>
                    <td></td>
                    <td></td>
                    <td><i class="fa fa-check"></i></td>
                </tr>
                <tr>
                    <td><div class="tooltip">Custom Integration  <i class="fa fa-commenting " style="font-size:18px;color:#85929E"> </i><span class="tooltiptext" style="width:350px;"> If you have a specific custom requirement in the Social Login plugin, we can implement and integrate it in the plugin for you. This includes all those custom features that come under the scope of Social Login/ Sharing/ Comments and impart additional value to the plugin. These features are chargeable. Please send us a query through the support forum to get in touch with us about your custom requirements.</span></div></td>
                    <td><a target="_blank" href="<?php echo get_site_url() . "/wp-admin/admin.php?page=mo_openid_settings&tab=login";?> ">Contact Us using Support Form</a></td>
                    <td><a target="_blank" href="<?php echo get_site_url() . "/wp-admin/admin.php?page=mo_openid_settings&tab=login";?> ">Contact Us using Support Form</a></td>
                    <td><a target="_blank" href="<?php echo get_site_url() . "/wp-admin/admin.php?page=mo_openid_settings&tab=login";?> ">Contact Us using Support Form</a></td>
                </tr>

            </table>

		   <br>
            <hr>
            <form style="display:none;" id="loginform" action="<?php echo get_option( 'mo_openid_host_name').'/moas/login'; ?>"
                  target="_blank" method="post">
                <input type="email" name="username" value="<?php echo get_option('mo_openid_admin_email'); ?>" />
                <input type="text" name="redirectUrl" value="<?php echo get_option( 'mo_openid_host_name').'/moas/initializepayment'; ?>" />
                <input type="text" name="requestOrigin" id="requestOrigin"  />
            </form>
            <form method="post" id="checkLicenseForm">
                <input type="hidden" name="option" value="mo_openid_check_license">
				<input type="hidden" name="mo_openid_check_license_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-check-license-nonce' ); ?>"/>
            </form>

            <script>
                function upgradeform(planType){
                    jQuery("#upgrade_notice").css("display", "block");
                    jQuery('#requestOrigin').val(planType);
                    jQuery('#loginform').submit();

                }
                function checkLicense(){
                    jQuery("#checkLicenseForm").submit();
                }

            </script>

            <h3><b><span style="color:#da7587;font-weight:bold;">Refund Policy -</h3>
            <p><b>At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved then we will refund the whole amount within 10 days of the purchase. Please email us at <a href="mailto:info@xecurify.com"><i>info@xecurify.com</i></a> for any queries regarding the return policy.</b></p>
            <b>Not applicable for -</b>
            <ol>
                <li>Returns that are because of features that are not advertised.</li>
                <li>Returns beyond 10 days.</li>
            </ol>

        </div>


    </td>

    <?php
}

function mo_openid_troubleshoot_info(){ ?>
<td style="vertical-align:top;width:65%;">
	<div class="mo_openid_table_layout">
	

				<table width="100%">
		<tbody>

		<tr><td>
			<p>If you are unable to open any section, press CTRL + F5 to clear cache.<p>
			<h3><a  id="openid_question_plugin" class="mo_openid_title_panel" >Site Issue</a></h3>
			<div class="mo_openid_help_desc" hidden="" id="openid_question_plugin_desc">
				<h4><a  id="openid_question14">I installed the plugin and my website stopped working. How can I recover my site?</a></h4>
				<div  id="openid_question14_desc">
					There must have been a server error on your website. To get your website back online:<br/>
					<ol>
						<li>Open FTP access and look for plugins folder under wp-content.</li>
						<li>Change the extension folder name miniorange-login-openid to miniorange-login-openid1</li>
						<li>Check your website. It must have started working.</li>
						<li>Change the folder name back to miniorange-login-openid.</li>
					</ol>
				</div>
				For any further queries, please submit a query on right hand side in our <b>Support Section</b>.
			</div>
			<hr>
		</td></tr>

		<tr><td>
			<h3><a  id="openid_question_email" class="mo_openid_title_panel" >Change email</a></h3>
			<div class="mo_openid_help_desc" hidden="" id="openid_question_email_desc">
				<h4><a  id="openid_question20">I want to change the email address with which I access my account. How can I do that?</a></h4>
				<div  id="openid_question20_desc">
				You will have to register in miniOrange again with your new email id.
				Please deactivate and activate the plugin by going to <strong>Plugins -> Installed Plugins</strong> and then go to the Social Login Plugin to register again. This will enable you to access miniOrange dashboard with new email address.</div><br/>
				For any further queries, please submit a query on right hand side in our <b>Support Section</b>.
			</div>
			<hr>
		</td></tr>

		<tr><td>
			<h3><a  id="openid_question_curl" class="mo_openid_title_panel" >cURL</a></h3>
			<div class="mo_openid_help_desc" hidden="" id="openid_question_curl_desc">

			<h4><a  id="openid_question1"  >How to enable PHP cURL extension? (Pre-requisite)</a></h4>
			<div  id="openid_question1_desc">
			cURL is enabled by default but in case you have disabled it, follow the steps to enable it
			<ol>
				<li>Open php.ini(it's usually in /etc/ or in php folder on the server).</li>
				<li>Search for extension=php_curl.dll. Uncomment it by removing the semi-colon( ; ) in front of it.</li>
				<li>Restart the Apache Server.</li>
			</ol>
			For any further queries, please submit a query on right hand side in our <b>Support Section</b>.

			</div>
				<hr>

			<h4><a  id="openid_question9"  >I am getting error - curl_setopt(): CURLOPT_FOLLOWLOCATION cannot be activated when an open_basedir is set</a></h4>
			<div   id="openid_question9_desc">
				Just setsafe_mode = Off in your php.ini file (it's usually in /etc/ on the server). If that's already off, then look around for the open_basedir in the php.ini file, and change it to open_basedir = .
			</div>


			</div>
		<hr>
		</td></tr>

		 <tr><td>
				<h3><a  id="openid_question_otp" class="mo_openid_title_panel" >OTP and Forgot Password</a></h3>
				  <div class="mo_openid_help_desc" hidden="" id="openid_question_otp_desc">
					<h4><a  id="openid_question7"  >I did not recieve OTP. What should I do?</a></h4>
					<div  id="openid_question7_desc">
						The OTP is sent as an email to your email address with which you have registered with miniOrange. If you can't see the email from miniOrange in your mails, please make sure to check your SPAM folder. <br/><br/>If you don't see an email even in SPAM folder, please verify your account using your mobile number. You will get an OTP on your mobile number which you need to enter on the page. If none of the above works, please contact us using the Support form on the right.
					</div>
					<hr>

					<h4><a  id="openid_question8"  >After entering OTP, I get Invalid OTP. What should I do?</a></h4>
					<div  id="openid_question8_desc">
						Use the <b>Resend OTP</b> option to get an additional OTP. Plese make sure you did not enter the first OTP you recieved if you selected <b>Resend OTP</b> option to get an additional OTP. Enter the latest OTP since the previous ones expire once you click on Resend OTP. <br/><br/>If OTP sent on your email address are not working, please verify your account using your mobile number. You will get an OTP on your mobile number which you need to enter on the page. If none of the above works, please contact us using the Support form on the right.
					</div>
					<hr>

					<h4><a  id="openid_question5" >I forgot the password of my miniOrange account. How can I reset it?</a></h4>
					<div  id="openid_question5_desc">
						There are two cases according to the page you see -<br><br/>
						1. <b>Login with miniOrange</b> screen: You should click on <b>forgot password</b> link. You will get your new password on your email address which you have registered with miniOrange . Now you can login with the new password.<br><br/>
						2. <b>Register with miniOrange</b> screen: Enter your email ID and any random password in <b>password</b> and <b>confirm password</b> input box. This will redirect you to <b>Login with miniOrange</b> screen. Now follow first step.
					</div>

				</div>
				<hr>
		</td></tr>


		<tr><td>
					<h3><a  id="openid_question_login" class="mo_openid_title_panel" >Social Login</a></h3>
					<div class="mo_openid_help_desc" hidden="" id="openid_question_login_desc">

					<h4><a  id="openid_question2"  >How to add login icons to frontend login page?</a></h4>
					<div   id="openid_question2_desc">
					You can add social login icons to frontend login page using our shortcode [miniorange_social_login]. Refer to 'Shortcode' tab to add customizations to Shortcode.
					</div>
					<hr>

					<h4><a  id="openid_question4"  >How can I put social login icons on a page without using widgets?</a></h4>
					<div  id="openid_question4_desc">
					You can add social login icons to any page or custom login page using 'social login shortcode' [miniorange_social_login]. Refer to 'Shortcode' tab to add customizations to Shortcode.
					</div>
					<hr>

					<h4><a  id="openid_question12" >Social Login icons are not added to login/registration form.</a></h4>
					<div  id="openid_question12_desc">
					Your login/registration form may not be wordpress's default login/registration form. In this case you can add social login icons to custom login/registration form using 'social login shortcode' [miniorange_social_login]. Refer to 'Shortcode' tab to add customizations to Shortcode.
					</div>
					<hr>

					<h4><a  id="openid_question3"  >How can I redirect to my blog page after login?</a></h4>
					<div  id="openid_question3_desc">
					You can select one of the options from <b>Redirect URL after login</b> of <b>Display Option</b> section under <b>Social Login</b> tab. <br>
					1. Same page where user logged in <br>
					2. Homepage <br>
					3. Account Dsahboard <br>
					4. Custom URL - Example: https://www.example.com <br>
					</div>
					<hr>

					<?php if(get_option('mo_openid_oauth')!='1'){?>
					<h4><a id="openid_question14">Can I configure my own apps for Facebook, Google+, Twitter etc.?</a></h4>
					<div id="openid_question14_desc">
					Yes, it is possible to configure your own app. That is available in the Standard and Premium plans.<br><br>
					Please contact us using the Support form on the right if you want to purchase these plans.<br>
					</div>
					<hr>
					<?php }?>

					<h4><a  id="openid_question11"  >After logout I am redirected to blank page</a></h4>
					<div  id="openid_question11_desc">
					Your theme and Social Login plugin may conflict during logout. To resolve it you need to uncheck <b>Enable Logout Redirection</b> checkbox under <b>Display Option</b> of <b>Social Login</b> tab.
					</div>
					<hr>

                    <?php if(get_option('mo_openid_oauth')!='1'){?>
					<h4><a  id="openid_question7"  >I am not able to fetch extended attributes. How do I access extended attributes? </a></h4>
					<div  id="openid_question7_desc">Check the option of Extended Attributes in the Social Login tab. Then in the miniOrange dashboard, go to Social analytics-> Social Applications Usage Summary, click on Search and then click on View under Additional Information to see extended attributes of users who login using your app. You can also download it as a CSV there.<br> <strong >Note:</strong> Your app needs to have permission from users to collect extended attributes.
					</div>
					<hr>
                    <?php }?>

					<h4><a  id="openid_question5"  >My users get the following message -"Registration has been disabled for this site. Please contact your administrator." What should I do?</a></h4>
					<div  id="openid_question5_desc">
					This means you must have unchecked the check-box of auto-register in the Social Login tab. Please check it. This will allow new users to be able to register to your site.
					</div>
					<hr>

					<h4><a  id="openid_question7"  >Why do my users get a message that it is not secure to proceed?</a></h4>
					<div  id="openid_question7_desc">Your website must be starting with http://. Now generally that's not an issue but our service uses https://( s stands for secure). You get a warning from the browser that the information is being passed insecurely. This happens after you log in to social media application and are coming back to your website. The warning is triggered from the browser since the data passes from https:// to http://, i.e. from a secure site to non-secure site.<br><br>We make sure that the information(email, name, username) getting passed from social media application to your website is encrypted with a key which is unique to you. So, even if the there is a warning of sending information without security, that information is encrypted. <br><br>

					<?php if(get_option('mo_openid_oauth')=='1') {?>
					<strong>To remove this warning, you can add an SSL certificate to your website to change it to https OR use your own <a href="admin.php?page=mo_openid_settings&tab=custom_app"></strong>Custom App</a>


					<?php } else {?>
					<strong>To remove this warning, you can add an SSL certificate to your website to change it to https OR use your own custom app</strong>

					<?php }?>
					</div>
					<hr>

					<h4><a  id="openid_question1"  >My users get the following message -"There was an error in registration. Please contact your administrator." What should I do?</a></h4>
					<div  id="openid_question1_desc">
					This message is thrown by WordPress when there is an error in user-registration. <br><br>
					1. To see the actual error thrown by WordPress, go to \wordpress\wp-content\plugins\miniorange-login-openid\class-mo-openid-login-widget.php file  <br>
					2. Search for the line :<br/><code> //print_r($user_id); </code> <br>
					3. Change it to <br/> <code>print_r($user_id); </code><br>
					4. Save the file and try logging again. Please send us the error you see while logging in through the support forum to your right.
					</div>

					<h4><a  id="openid_question6"  >How do I centre the social login icons?</a></h4>
					<div  id="openid_question6_desc">
					1.If you are making changes to a PHP file.<br/><br/>
					Go to the PHP file which invokes your page/post and insert the following html snippet. Also, increase the margin-left value as per your requirement. Save the file. <br>
					<code>&ltdiv style="margin-left:100px;"&gt <br>&lt?php echo do_shortcode('[miniorange_social_login]')?&gt <br>
					&lt/div&gt </code><br/><br/>
					2.If you are making changes to an HTML file.<br/><br/>
					Go to the HTML file which invokes your page/post and insert the following html snippet. Also, increase the margin-left value as per your requirement. Save the file. <br>
					<code>&ltdiv style="margin-left:100px;"&gt <br>[miniorange_social_login]')<br>
					&lt/div&gt </code>
					</div>

				</div>
					<hr>
		</td></tr>

		<tr><td>
			<h3><a  id="openid_question_sharing" class="mo_openid_title_panel" >Social Sharing</a></h3>
				<div class="mo_openid_help_desc" hidden="" id="openid_question_sharing_desc">
				<h4><a  id="openid_question6"  >Is it possible to show sharing icons below the post content?</a></h4>
				<div  id="openid_question6_desc">
					You can put social sharing icons before the content, after the content or both before and after the content. Go to <b>Sharing tab</b> , check <b>Blog post</b> checkbox and select one of three(before, after, both) options available. Save settings.
				</div>
				<hr>

				<h4><a  id="openid_question10" >Why is sharing with some applications not working?</a></h4>
				<div  id="openid_question10_desc">
					This issue arises if your website is not publicly hosted. Facebook, for example looks for the URL to generate its preview for sharing. That does not work on localhost or any privately hosted URL.
				</div>
				<hr>

				<h4><a  id="openid_question13" >Facebook sharing is showing the wrong image. How do I change the image?</a></h4>
				<div  id="openid_question13_desc">
					The image is selected by Facebook and it is a part of Facebook sharing feature. We provide Facebook with webpage URL. It generates the entire preview of webpage using that URL.<br/><br/>
					To set an image for the page, set it as a meta tag in <head> of your webpage.<br/>
					<b>< meta property="og:image" content="http://example.com/image.jpg" ></b><br/><br/>
					You can further debug the issue with Facebook's tool - <a href="https://developers.facebook.com/tools/debug/og/object">https://developers.facebook.com/tools/debug/og/object</a>
					<br/><br/>
					If the problem still persists, please contact us using the Support form on the right.
				</div>
				<hr>

				<h4><a  id="openid_question21" >There is no option of Instagram in Social Sharing. Why?</a></h4>
				<div  id="openid_question21_desc">
				Instagram has made a conscious effort to not allow sharing from external sources to fight spam and low quality photos.
				At this point of time, uploading via Instagram's API from external sources is not possible.<br><br>
				<a href='https://help.instagram.com/158826297591430' target='_blank'>https://help.instagram.com/158826297591430</a>
				</div>
				<hr>

				<h4><a  id="openid_question18" >Email share is not working. Why?</a></h4>
				<div  id="openid_question18_desc">
					Email share in the plugin is enabled through <b>mailto</b>. mailto is generally configured through desktop or browser so if it is not working, mailto is not setup or improperly configured.<br><br>
					To set it up properly, search for "mailto settings " followed by your Operating System's name where you have your browser installed.
				</div>
				<hr>

				<h4><a id="openid_question19" >I cannot see some icons in preview or on blog even though I have selected them in the plugin settings.</a></h4>
				<div  id="openid_question19_desc">
					Please check if you have an Adblock extension installed on your browser where you are checking the plugin. If you do, the Adblock extension will have a setting to block Social buttons. Uncheck this option.
					<br/><br/>
					If you don't have Adblock installed and still face this issue, please contact us using the Support form on the right or mail us at info@xecurify.com.
				</div>

				</div>
				<hr>
		</td></tr>

		</tbody>
		</table>
	</div>
	</td>
		<td style="vertical-align:top;padding-left:1%;">
			<?php echo miniorange_openid_support(); ?>
		</td>
	
<?php	
}

function mo_openid_privacy_policy(){ ?>
    <td style="vertical-align:top;width:65%;">
        <div class="mo_openid_table_layout">

            <table width="100%">
                <tbody>

                <tr><td>
                        <p>If you are unable to open any section, press CTRL + F5 to clear cache.<p>
                        <h3><a  id="openid_question_plugin" class="mo_openid_title_panel" >Terms used in this document</a></h3>
                        <div class="mo_openid_help_desc" hidden="" id="openid_question_plugin_desc">
                            <ol>
                                    <li>Customer - Person who is registered with miniOrange and has installed the Social Login Plugin on his/her website  </li>
                                    <li>End-user - Person who will log in to your site using social applications like Facebook, Google, Twitter etc. </li>
                                    <li>miniOrange - Company owning the Social Login Plugin. </li>
                                    <li>Social Profile Data - Information like the first name, last name, email address etc. that is fetched from a social application like Facebook when end-user logs into the website</li>
                                <li>Website Administrator - Alias for Customer </li>
                            </ol>
                        </div>
                        <hr>
                    </td></tr>

                <tr><td>
                        <h3><a  id="openid_question_email" class="mo_openid_title_panel" >Introduction</a></h3>
                        <div class="mo_openid_help_desc" hidden="" id="openid_question_email_desc">
                            <div  id="openid_question20_desc">
                                We protect your personal information using industry ­standard safeguards. We may share your information only with your consent or as required by law as detailed in this policy, and we will always let you know when we make significant changes to this Privacy Policy. Maintaining your trust is our top priority, so we adhere to the following principles to protect your privacy:
                                <br><br>
                                We protect your personal information and will only provide it to third parties:
                                <ol>
                                    <li>with your consent;  </li>
                                    <li>where it is necessary to carry out your instructions; </li>
                                    <li>as reasonably necessary in order to provide our features and functionality to you;  </li>
                                    <li>when we reasonably believe it is required by law, subpoena or other legal process; or </li>
                                    <li>as necessary to enforce our User Agreement or protect the rights, property, or safety of miniOrange, its Customers and End users,
                                        and the public. </li>
                                </ol>
                               </div>
                        </div>
                        <hr>
                    </td></tr>

                <tr><td>
                        <h3><a  id="openid_question_curl" class="mo_openid_title_panel" >What information do we collect ? </a></h3>
                        <div class="mo_openid_help_desc" hidden="" id="openid_question_curl_desc">

                            <div  id="openid_question1_desc">
                                1. When you register in the plugin, you provide us with information (including your name(optional), email address, phone number(optional), company name/website and password) that we use to offer you a personalized, relevant experience on miniOrange.
                            </div>
                            <hr>

                            <div  id="openid_question1_desc">
                                2. When you contact our Customer Support, personal contact information, such as name, company name, address, phone number, email address, and any other information necessary for us to provide Visitors with access to the various aspects of the Services. The Personal Information you provided is used for such purposes as answering questions, improving the content of the website, customizing the content, and communicating with the Visitors about
                                miniOrange’s Services, including specials and new features.
                            </div>
                            <hr>

                            <div  id="openid_question1_desc">
                                3. When you contact us using our support form, we collect information that helps us categorize your question, respond to it, and, if applicable, investigate any breach of our User Agreement or this Privacy Policy. We also use this information to track potential problems and trends and customize our support responses to better serve you.
                            </div>
                            <hr>

                            <div  id="openid_question1_desc">
                                4. We do not collect email address from miniOrange production service for marketing use.
                            </div>
                            <hr>

                            <div  id="openid_question1_desc">
                                5. In the free and standard plans of the social login plugin, we collect the following information from your end users :
                                <ol>
                                    <li>First name   </li>
                                    <li>last name  </li>
                                    <li>username </li>
                                    <li>email </li>
                                    <li>application name </li>
                                    <li>social profile url    </li>
                                    <li>profile picture  </li>
                                    <li>the identifier in the social application </li>
                                </ol>
                            </div>
                            <hr>

                            <div  id="openid_question1_desc">
                                6. In the premium plugin, we collect the following information from your end users.
                                <ol>
                                    <li>First name   </li>
                                    <li>last name  </li>
                                    <li>username </li>
                                    <li>email </li>
                                    <li>application name </li>
                                    <li>social profile url    </li>
                                    <li>profile picture  </li>
                                    <li>Age</li>
                                    <li>Gender   </li>
                                    <li>Location  </li>
                                    <li>DOB</li>
                                    <li>Company Name</li>
                                    <li>Friend list</li>
                                    <li>Contact number</li>
                                    <li>website</li>
                                    <li>Relationship Status</li>
                                    <li>Education</li>
                                    <li>university_name</li>
                                    <li>description</li>
                                    <li>placesLived</li>
                                    <li>Industry</li>
                                    <li>headline</li>

                                </ol>
                            </div>
                        </div>
                        <hr>
                    </td></tr>

                <tr><td>
                        <h3><a  id="openid_question_otp" class="mo_openid_title_panel" >How we use personal information?</a></h3>
                        <div class="mo_openid_help_desc" hidden="" id="openid_question_otp_desc">
                            <div  id="openid_question7_desc">
                                miniOrange Social Login Plugin gives two options to website administrators/customers/you<br>
                                A. to login users with default miniOrange apps that are pre-configured in the plugin<br>
                                B. to login users with custom apps that you can configure in the plugin
                                <br><br>
                                In the free & standard plugin, miniOrange collects data but it is not stored in miniOrange (except for an identifier for audit and usage purposes). This data is to log in the end-user on the WordPress website and for enhancing the end user's experience.
                                <br><br>
                                In the premium plugin, miniOrange collects and stores data only if the customer has chosen the Extended attributes feature. The data collected is for reporting for customers. miniOrange does not sell, reproduce or use this information in any way. Website administrators have the sole right to this information of end users.
                            </div>
                            <hr>

                            <h4><a  id="openid_question8"  >Data Security when you choose A</a></h4>
                            <div  id="openid_question8_desc">
                                >><b> Right to be forgotten:</b> Social profile data that is fetched from a social application is stored in two places -> WordPress database & miniOrange. Website administrator can delete a specific end user's social profile data from WordPress & miniOrange if an end user requests so.
                                <br><br>
                                >> <b>User consent:</b> End users will be asked for consent if they agree to the terms and conditions of your website. If they deny consent, they will not be logged in and no data will be fetched.
                                <br><br>
                                >><b> Encryption: </b>All data that is in transit because of miniOrange is encrypted with a key that is unique to the customer registered in the Social Login plugin.
                            </div>
                            <hr>

                            <h4><a  id="openid_question5" >Data Security when you choose B</a></h4>
                            <div  id="openid_question5_desc">
                                >> <b> Right to be forgotten:</b> Social profile data fetched from each social application is stored only in the WordPress database. Website administrator can delete a specific end user's social profile data from WordPress if an end user requests so.
                                <br><br>
                                >> <b>User consent:</b>  End users will be asked for consent if they agree to the terms and conditions of your website. If they deny consent, they will not be logged in and no data will be fetched.
                            </div>

                        </div>
                        <hr>
                    </td></tr>

                </tbody>
            </table>
        </div>
    </td>
    <td style="vertical-align:top;padding-left:1%;">
        <?php echo miniorange_openid_support(); ?>
    </td>

    <?php
}

function mo_openid_custom_app_config(){?>
	<style>
		.tableborder {
			border-collapse: collapse;
			width: 100%;
			border-color:#eee;
		}

		.tableborder th, .tableborder td {
			text-align: left;
			padding: 8px;
			border-color:#eee;
		}

		.tableborder tr:nth-child(even){background-color: #f2f2f2}
	</style>
	<div class="mo_table_layout" style="min-height: 400px;">
	<?php
	
	if(isset($_GET['action']) && $_GET['action']=='delete'){
		if(isset($_GET['wp_nonce'])){
			$nonce = $_GET['wp_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-delete-selected-app-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
			} else {
				if(isset($_GET['app'])){
					$app = sanitize_text_field($_GET['app']);
					delete_custom_app($app);
				}
			}
		}
	} else if(isset($_GET['action']) && $_GET['action']=='instructions'){
		if(isset($_GET['app'])){
			$app = sanitize_text_field($_GET['app']);
			mo_custom_app_instructions($_GET['app']);
		}
	}
	
	if(isset($_GET['action']) && $_GET['action']=='add'){
		if(isset($_GET['wp_nonce'])){
			$nonce = $_GET['wp_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-add-selected-app-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
			} else {
				add_custom_app();
			}
		}
	} 
	else if(isset($_GET['action']) && $_GET['action']=='update'){
		if(isset($_GET['wp_nonce'])){
			$nonce = $_GET['wp_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-update-selected-app-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
			} else {
				if(isset($_GET['app'])) {
					$app = sanitize_text_field($_GET['app']);
					update_custom_app($app);
					mo_custom_app_instructions($app);
				}
			}
		}
	}
		else if(get_option('mo_openid_apps_list')){
            if(strpos($_SERVER['REQUEST_URI'], "setup_msg")!== false)
            {
                ?><div id="upgrade_notice" class="notice notice-success is-dismissible" style="width: 92.5%;margin-left: 0%;"><p><strong>Please enable the Facebook custom app.</strong></p></div>
                <?php
            }
			$appslist = maybe_unserialize(get_option('mo_openid_apps_list'));
			$nonce = wp_create_nonce( 'mo-openid-add-selected-app-nonce' );
			echo "<br><input onclick='window.location.href=\"admin.php?page=mo_openid_settings&tab=custom_app&action=add&wp_nonce=".$nonce."\"' type='button' class='button button-primary button-large' style='float:right;text-align:center;' value='Add Application'>";
			echo "<h3>Applications List</h3>";
			echo "<table class='tableborder'>";
            echo "<tr><th><b>Name</b></th><th>Action</th><th>Enable Custom app</tr></tr>";
			foreach($appslist as $key => $app){

                $option = 'mo_openid_enable_custom_app_' . $key;

                if(get_option($option) == '1'){
                    $enable_status = 'checked';
                }
                else{
                    $enable_status = '';
                }
                if($key=='facebook')
                {
                    $test_config=" <a onclick=\"testconfiguration('".$key."')\"><u>Test Configuration</u></a> |";
                }
                else {
                    $test_config = '';
				}
				$nonce_update = wp_create_nonce( 'mo-openid-update-selected-app-nonce' );
                echo "<tr><td>".$key."</td><td><a href='admin.php?page=mo_openid_settings&tab=custom_app&action=update&app=".$key."&wp_nonce=" . $nonce_update . "'>Edit</a> | ".$test_config." <a onclick='popup_delete_app(\"".$key."\")'>Delete</a>
                </td><td><label class='mo-switch'>
 				<input type='checkbox' ". $enable_status ." onclick='enable_custom_app(\"".$key."\");  ' id='mo_id_".$key."'  >
                <div class='mo-slider round' id='switch_checkbox' >
                </div>            
            	</label></td></tr>";
            }
			echo "</table>";
            echo "<br><br><br><br><br><br><br><br>";
            echo "<div style='text-align: center'><p><u>Please Note :</u><br>Enable custom app overrides the default app miniOrange uses. If you want to use the default app miniOrange uses, please disable the custom app here.<br>
                  </p></div>";
	
		}elseif (get_option('mo_openid_apps_list')==null){
			$nonce = wp_create_nonce( 'mo-openid-add-selected-app-nonce' );
            echo "<div style='text-align: center'><p>You have not configured any custom apps yet. Please click on <b>Add Application</b> to configure your own app.</p>";
            echo "<br><input type='button'  onclick='window.location.href=\"admin.php?page=mo_openid_settings&tab=custom_app&action=add&wp_nonce=" . $nonce . "\"' class='button button-primary button-large' style='text-align:center;' value='Add Application'>";
            echo "<br><br><br><br><br>";?>
            <img style="margin-top: -3%" src="<?php echo plugin_dir_url(__FILE__);?>includes/images/custom_app.png">

<?php
        }?>
		</div>


		</td>
    <script>
        //ajax call for custom app over default app
        function enable_custom_app(appname){

            var checkbox_id = 'mo_id_'+appname;
            var value = document.getElementById(checkbox_id).checked;

            jQuery.ajax({
                url:"<?php echo admin_url();?>", //the page containing php script
                method: "POST", //request type,
                data: {selected_app: appname, selected_app_value : value},
                dataType: 'text',
                success:function(result){

                }
            });
        }
        function testconfiguration(selected_app) {
            jQuery.ajax({
                url:"<?php echo admin_url();?>", //the page containing php script
                method: "POST", //request type,
                data: {appname: selected_app, test_configuration : true},
                dataType: 'text',
                success:function(result){
                    var myWindow = window.open('<?php echo home_url(); ?>' + '/?option=oauthredirect&app_name='+selected_app+'&wp_nonce='+'<?php echo wp_create_nonce( 'mo-openid-oauth-app-nonce' ); ?>',"", "width=950, height=600");
                }
            });
        }
    </script>

<?php
}

function add_custom_app(){
    if(strpos( $_SERVER['REQUEST_URI'], "facebook")!== false){?><div id="upgrade_notice" class="notice notice-success is-dismissible" style="width: 92.5%;margin-left: 0%;"><p><strong>Please configure the Facebook custom app and test your configuration.</strong></p></div>
    <?php }
	?>

		<script>

                function selectapp(app_name) {
                    var appname
                    if(app_name=="facebook")
                    {
                        appname="facebook";
                    }
                    else{
                        appname = document.getElementById("mo_oauth_app").value;
                    }
				document.getElementById("instructions").innerHTML  = "";
                if(appname=="google"){
                    document.getElementById("mo_oauth_scope").value = "email+profile";
                    document.getElementById("instructions").innerHTML  = '<br><strong>Instructions to configure Google :</strong><ol><li>Visit the Google website for developers <a href="https://console.developers.google.com/project"target="_blank">console.developers.google.com</a>.</li><li>Open the Google API Console Credentials page and go to API Manager -> Credentials</li><li>From the project drop-down, click on + to Create a new project, enter a name for the project, and optionally, edit the provided Project ID. Click Create.</li><li>On the Credentials menu, select Create credentials, then select OAuth client ID.</li><li>You may be prompted to set a product name on the Consent screen. If so, click Configure consent screen, supply the requested information, and click Save to return to the Credentials screen.</li><li>Select Web Application for the Application Type. Enter <b><?php if(get_option('mo_openid_malform_error')){if(get_option( 'permalink_structure' )){echo site_url()."/openidcallback/google";}else echo site_url()."/?openidcallback=google"; } else{if(get_option( 'permalink_structure' )){echo site_url()."/openidcallback";}else echo site_url()."/?openidcallback"; }?></b> in Authorized redirect URIs.</li><li>Click Create.</li><li>On the page that appears, copy the client ID and client secret to your clipboard, as you will need them to configure above.</li><li>Enable the Google+ API.<ul><li>In the Dashboard menu, click on ENABLE APIS AND SERVICES.</li><li>Type Google+ in search box, select Google+ API and click on Enable.</li></ul></li><li>Input scope as <b>email+profile </b></li><li>Go to Social Login tab to configure the display as well as other login settings.</li></ol>';
                } else if(appname=="facebook"){
                    document.getElementById("mo_oauth_scope").value = "email, public_profile";
                    document.getElementById("instructions").innerHTML  = '<br><br><strong>Facebook custom application setup video : </strong><br><br><iframe width="450" height="250" src="https://www.youtube.com/embed/hHx-oR7XiZo" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen style="margin-left: 17%;"></iframe><br><br><strong>Instructions to configure Facebook : </strong><br><ol><li>Go to Facebook developers console <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>. Login with your facebook developer account.</li><li>Click on Create a New App/Add new App button.</li><li>Enter <b>Display Name</b> and click on Create App ID.</li><li>Click on <b>Products</b> from left pane of the page and select <b>Facebook Login</b> and click on <b>Set Up</b> button.</li><li>Click on <b>Web</b>. Enter <b><?php echo site_url(); ?></b> into <b>Site URL</b> than click on <b> Save</b>.</li><li>Goto <b>Settings -> Basic</b> from left pane of the page, Enter <b><?php $str=str_replace('https://','',site_url()); echo $str; ?></b> in <b>App Domain</b>, your privacy policy URL in <b>Privacy Policy URL</b> and select <b>Category</b> of your website. Then click on <b>Save Changes</b>.</li><li>From the left pane, select <b>Facebook Login -> Settings</b>.</li><li>Under Client OAuth Settings section, Enter <b><?php if(get_option('mo_openid_malform_error')){if(get_option( 'permalink_structure' )){echo site_url()."/openidcallback/facebook";}else echo site_url()."/?openidcallback=facebook"; } else{if(get_option( 'permalink_structure' )){echo site_url()."/openidcallback";}else echo site_url()."/?openidcallback"; }?></b> in <b>Valid OAuth Redirect URIs</b> and click on <b>Save Changes</b> button.</li><li>Change your app status from In Development to Live by clicking on OFF (sliding button) beside Status option of the top right corner. Then, click on Confirm button.</li><li>Go to Settings > Basic. Paste your <b>App ID</b> and <b>App Secret</b> provided by Facebook into the fields above.</li><li>Input <b> email, public_profile </b>as scope.</li><li> If you want to access the <b>user_birthday, user_hometown, user_location</b> of a Facebook user, you need to send your app for review to Facebook. For submitting an app for review, click <a target="_blank" href="https://developers.facebook.com/docs/facebook-login/review/how-to-submit ">here</a>. After your app is reviewed, you can add the scopes you have sent for review in the scope above. If your app is not approved or is in the process of getting approved, let the scope be <b>email, public_profile</b></li><li>Click on the <b>Save settings</b> button.</li><li>Go to Social Login tab to configure the display as well as other login settings.</li></ol>';
                } else if(appname=="twitter"){
                    document.getElementById("mo_oauth_scope").value = "";
                    document.getElementById("instructions").innerHTML  = '<br><strong>Instructions to configure Twitter : </strong><ol><li>Go to <a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a> and sign in with your twitter account.</li><li>Click on <b>Create New App</b>.</li> <li>Enter Name, description, website name and callback URL.</li><li><b>Callback URL</b> <br><u>Example of public website, </u><br>if your website URL is =><?php echo site_url();?><br> then your callback URL should be => <?php echo site_url();?><br><u>Example of localhost,</u><br> Twitter may not accept local IPs so try using 127.0.0.1 instead of localhost.<br> Also make sure your Callback URL is prefixed with the website.  <br> Go to Settings-> General and replace "localhost" with "127.0.0.1" in <b>WordPress Address (URL)</b> and <b>Site Address (URL)</b> <br>if your website URL is => http://127.0.0.1/wordpress <br> then your callback URL should be => http://127.0.0.1/wordpress/openidcallback</li><li>Twitter might ask you to add your phone number to your profile while creating the app.</li><li>Check the developer agreement checkbox and click on <b>Create your Twitter Application</b>.Under <b>Keys and Access Token</b> Tab, you will find your <b>API Key/Secret</b>.Paste them into the fields above.</li><li>Leave the scope field blank.</li><li><u>Instructions to request email address of a user</u>: The â€œRequest email addresses from usersâ€ checkbox is available under the app permissions on apps.twitter.com. Privacy Policy URL and Terms of Service URL fields must be completed in the app settings in order for email address access to function. If enabled, users will be informed via the oauth/authorize dialog that your app can access their email address.If the user does not have an email address on their account, or if the email address is not verified, email will not be returned.</li><li>Go to Permissions tab -> Access. Select <b>Read Only</b> type of access.</li><li>Click on the Update settings button.</li><li>Go to Social Login tab to configure the display as well as other login settings.</li></ol>';
                }
				
			}

		</script>
    <div id="toggle2" class="panel_toggle">
        <h3>Add Application</h3>
    </div>
    <form id="form-common" name="form-common" method="post" action="admin.php?page=mo_openid_settings&tab=custom_app">
        <input type="hidden" name="option" value="mo_openid_add_custom_app" />
		<input type="hidden" name="mo_openid_add_custom_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-add-custom-app-nonce' ); ?>"/>
        <table class="mo_settings_table">
            <tr>
                <td><strong><font color="#FF0000">*</font>Select Application:</strong></td>
                <td>
                    <select class="mo_table_textbox" style="width:500px;" required="true" name="mo_oauth_app_name" id="mo_oauth_app" onchange="selectapp('')">
                        <option value="">Select Application</option>
                        <?php if(!mo_openid_restrict_user())echo "<option value='google'>Google</option>";?>
                        <option value="facebook" <?php if(strpos( $_SERVER['REQUEST_URI'], "facebook")!== false) echo"selected";?> ">Facebook</option>
                        <option value="twitter">Twitter</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><font color="#FF0000">*</font>Client ID:</strong></td>
                <td><input class="mo_table_textbox" required="" style="height: 27px; width:500px" type="text" name="mo_oauth_client_id" value=""></td>
            </tr>
            <tr>
                <td><strong><font color="#FF0000">*</font>Client Secret:</strong></td>
                <td><input class="mo_table_textbox" required="" style="height: 27px; width:500px" type="text"  name="mo_oauth_client_secret" value=""></td>
            </tr>
            <tr>
                <td><strong>Scope:</strong></td>
                <td><input class="mo_table_textbox" style="height: 27px; width:500px"  type="text" id="mo_oauth_scope" name="mo_oauth_scope" value="" ></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" value="Save settings" class="button button-primary button-large" />
                    <input type="button" name="back" onclick="goBack();" value="Back" class="button button-primary button-large" />
                </td>
            </tr>
        </table>
    </form>

    <div id="instructions">

    </div> <?php if(strpos( $_SERVER['REQUEST_URI'], "facebook")!== false){?><script>selectapp("facebook");</script><?php } ?>
    <script>
        function goBack(){

            var base_url = '<?php echo site_url();?>';
            base_url += '/wp-admin/admin.php?page=mo_openid_settings&tab=custom_app';
            window.location.href= base_url ;
        }
    </script>

    <?php
}
function update_custom_app($appname){
	
	$appslist = maybe_unserialize(get_option('mo_openid_apps_list'));
	foreach($appslist as $key => $app){
		if($appname == $key){
			$currentappname = $appname;
			$currentapp = $app;
			break;
		}
	}
	
	if(!$currentapp)
		return;
	?>
		
		<div id="toggle2" class="panel_toggle">
			<h3>Update Application</h3>
		</div>
		<form id="form-common" name="form-common" method="post" action="admin.php?page=mo_openid_settings&tab=custom_app">
		<input type="hidden" name="option" value="mo_openid_add_custom_app" />
            <input type="hidden" name="mo_openid_add_custom_nonce"
                   value="<?php echo wp_create_nonce( 'mo-openid-add-custom-app-nonce' ); ?>"/>
		<table class="mo_settings_table">
			<tr>
			<td><strong><font color="#FF0000">*</font>Application:</strong></td>
			<td>
				<input class="mo_table_textbox" required="" type="hidden" name="mo_oauth_app_name" value="<?php echo $currentappname;?>">
				<input class="mo_table_textbox" required="" type="hidden" name="mo_oauth_custom_app_name" value="<?php echo $currentappname;?>">
				<?php echo esc_html($currentappname);?><br><br>
			</td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Client ID:</strong></td>
				<td><input class="mo_table_textbox" required="" style="height: 27px; width:500px" type="text" name="mo_oauth_client_id" value="<?php echo esc_html($currentapp['clientid']);?>"></td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Client Secret:</strong></td>
				<td><input class="mo_table_textbox" required="" style="height: 27px; width:500px" type="text" name="mo_oauth_client_secret" value="<?php echo esc_html($currentapp['clientsecret']);?>"></td>
			</tr>
			<tr>
				<td><strong>Scope:</strong></td>
				<td><input class="mo_table_textbox" style="height: 27px; width:500px" type="text" name="mo_oauth_scope" value="<?php echo esc_html($currentapp['scope']);?>"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" name="submit" value="Save settings" class="button button-primary button-large" />
                    <input type="button" name="back" onclick="goBack();" value="Back" class="button button-primary button-large" />
                </td>
			</tr>
		</table>
		</form>
        <script>
            function goBack(){

                var base_url = '<?php echo site_url();?>';
                base_url += '/wp-admin/admin.php?page=mo_openid_settings&tab=custom_app';
                window.location.href= base_url ;
            }
        </script>
		<?php
}

function delete_custom_app($appname){
	$appslist = maybe_unserialize(get_option('mo_openid_apps_list'));
	foreach($appslist as $key => $app){
		if($appname == $key){
			unset($appslist[$key]);
		}
	}
	if($appname=="facebook") 
	{
		update_option('mo_openid_facebook_enable',0);
	}
	if(!empty($appslist))
		update_option('mo_openid_apps_list', maybe_serialize($appslist));
	else
		delete_option('mo_openid_apps_list');
}

function mo_custom_app_instructions($appname){

    if(mo_openid_malform_error())
    {
        if(get_option( 'permalink_structure' )) {
            $oauth_redirect_url = site_url() . "/openidcallback/" . $appname;
        }
        else
            $oauth_redirect_url = site_url() . "/?openidcallback=" . $appname;
    }
    else{
        if(get_option( 'permalink_structure' )) {
            $oauth_redirect_url = site_url() . "/openidcallback";
        }else
            $oauth_redirect_url = site_url() . "/?openidcallback";
    }

    if ($appname == "google") {
        echo '<br><strong>Instructions to configure Google :</strong><ol><li>Visit the Google website for developers <a href="https://console.developers.google.com/project"target="_blank">console.developers.google.com</a>.</li><li>Open the Google API Console Credentials page and go to API Manager -> Credentials</li><li>From the project drop-down, choose Create a new project, enter a name for the project, and optionally, edit the provided Project ID. Click Create.</li><li>On the Credentials page, select Create credentials, then select OAuth client ID.</li><li>You may be prompted to set a product name on the Consent screen. If so, click Configure consent screen, supply the requested information, and click Save to return to the Credentials screen.</li><li>Select Web Application for the Application Type. Follow the instructions to enter JavaScript origins, redirect URIs, or both. provide <b>' . $oauth_redirect_url . '</b> for the Redirect URI.</li><li>Click Create.</li><li>On the page that appears, copy the client ID and client secret to your clipboard, as you will need them to configure above.</li><li>Enable the Google+ API.<ul><li>In the Dashboard menu, click on ENABLE APIS AND SERVICES.</li><li>Type Google+ in search box, select Google+ API and click on Enable.</li></ul></li></li><li>Input scope as <b>email+profile </b></li><li>Go to Social Login tab and configure the icons.</li></ol>';
    } else if ($appname == "facebook") {
        echo '<br><br><strong>Facebook custom application setup video : </strong><br><br><iframe width="450" height="250" src="https://www.youtube.com/embed/hHx-oR7XiZo" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen style="margin-left: 17%;"></iframe><br><br><strong>Instructions to configure Facebook : </strong><br><ol><li>Go to Facebook developers console <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>. Login with your facebook developer account.</li><li>Click on Create a New App/Add new App button.</li><li>Enter <b>Display Name</b> and click on Create App ID.</li><li>Click on <b>Products</b> from left pane of the page and select <b>Facebook Login</b> and click on <b>Set Up</b> button.</li><li>Click on <b>Web</b>. Enter <b>' . site_url() . '</b> into <b>Site URL</b> than click on <b> Save</b>.</li><li>Goto <b>Settings -> Basic</b> from left pane of the page, Enter <b>' . str_replace('https://', '', site_url()) . '</b> in <b>App Domain</b>, your privacy policy URL in <b>Privacy Policy URL</b> and select <b>Category</b> of your website. Then click on <b>Save Changes</b>.</li><li>From the left pane, select <b>Facebook Login -> Settings</b>.</li><li>Under Client OAuth Settings section, Enter <b>' . $oauth_redirect_url . '</b> in <b>Valid OAuth Redirect URIs</b> and click on <b>Save Changes</b> button.</li><li>Change your app status from In Development to Live by clicking on OFF (sliding button) beside Status option of the top right corner. Then, click on Confirm button.</li><li>Go to Settings > Basic. Paste your <b>App ID</b> and <b>App Secret</b> provided by Facebook into the fields above.</li><li>Input <b> email, public_profile </b>as scope.</li><li> If you want to access the <b>user_birthday, user_hometown, user_location</b> of a Facebook user, you need to send your app for review to Facebook. For submitting an app for review, click <a target="_blank" href="https://developers.facebook.com/docs/facebook-login/review/how-to-submit ">here</a>. After your app is reviewed, you can add the scopes you have sent for review in the scope above. If your app is not approved or is in the process of getting approved, let the scope be <b>email, public_profile</b></li><li>Click on the <b>Save settings</b> button.</li><li>Go to Social Login tab to configure the display as well as other login settings.</li></ol>';
    } else if ($appname == "twitter") {
        echo '<br><strong>Instructions to configure Twitter : </strong><ol><li>Go to <a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a> and sign in with your twitter account.</li><li>Click on <b>Create New App</b>.</li> <li>Enter Name, description, website name and callback URL.</li><li><b>Callback URL</b> <br><u>Example of public website, </u><br>if your website URL is =>' . site_url() . '<br> then your callback URL should be => ' . site_url() . '<br><u>Example of localhost,</u><br> Twitter may not accept local IPs so try using 127.0.0.1 instead of localhost.<br> Also make sure your Callback URL is prefixed with the website.  <br> Go to Settings-> General and replace "localhost" with "127.0.0.1" in <b>WordPress Address (URL)</b> and <b>Site Address (URL)</b> <br>if your website URL is => http://127.0.0.1/wordpress <br> then your callback URL should be => http://127.0.0.1/wordpress/openidcallback </li><li>Twitter might ask you to add your phone number to your profile while creating the app.</li><li>Check the developer agreement checkbox and click on <b>Create your Twitter Application</b>. Under <b>Keys and Access Token</b> Tab, you will find your <b>API Key/Secret</b>. Paste them into the fields above.</li><li>Leave the scope field blank.</li><li><u>Instructions to request email address of a user</u>: The “Request email addresses from users” checkbox is available under the app permissions on apps.twitter.com. Privacy Policy URL and Terms of Service URL fields must be completed in the app settings in order for email address access to function. If enabled, users will be informed via the oauth/authorize dialog that your app can access their email address.If the user does not have an email address on their account, or if the email address is not verified, email will not be returned.</li><li>Click on the Save settings button.</li><li>Go to Social Login tab to configure the display as well as other login settings.</li></ol>';
    }
    
}

function mo_openid_addon()
{
    if (mo_openid_is_customer_registered()) { ?>
        <td style="vertical-align:top;width:65%;">
            <div class="mo_openid_table_layout" id="customization_ins" style="display: block">

                <?php if (!mo_openid_is_customer_registered()) { ?>
                    <div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                        Please <a
                                href="<?php echo add_query_arg(array('tab' => 'register'), $_SERVER['REQUEST_URI']); ?>">Login
                            with miniOrange</a> to enable Social Login, Social Sharing and Add-on.
                    </div>
                <?php } ?>

                <table>
                    <tr>
                        <td >
                            <h3>Custom Registration Form
                                <input type="button" value="Purchase"
                                       onclick="mosocial_addonform('wp_social_login_extra_attributes_addon')"
                                       id="mosocial_purchase_cust_addon"
                                       class="button button-primary button-large"
                                       style="float: right; margin-left: 10px;">
                                <input type="button" value="Verify Key"
                                       id="mosocial_purchase_cust_addon_verify"
                                       class="button button-primary button-large"
                                       style="float: right;">

                            </h3>
                            <b>Custom Registration Form Add-On helps you to integrate details of new as well as existing users. You
                                can add as many fields as you want including the one which are returned by
                                social sites at time of registration.</b>
                        </td>
                    </tr>
                </table>
                <table class="mo_openid_display_table table" id="mo_openid_extra_attributes_addon_video">

                    <tr>
                        <td colspan="2">
                            <hr>
                            <p>
                                <br><br>
                                <iframe width="450" height="250" src="https://www.youtube.com/embed/cEvU9d3YBus"
                                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
                                        style="margin-left: 17%; align-content: center; ma"></iframe>
                            </p>
                        </td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <td style="vertical-align:top; ">
                            <div class="mo_openid_table_layout"><br/>
                                <form method="post">
                                    <h3>Customization Fields</h3>
                                    <input type="checkbox" disabled="disabled" id="customised_field_enable"
                                           value="1" checked
                                        <?php if (!mo_openid_is_customer_registered()) echo 'disabled' ?>/>
                                    <b>Enable Auto Field Registration Form</b>

                                    <style>
                                        .tableborder {
                                            border-collapse: collapse;
                                            width: 100%;
                                            border-color: #eee;
                                        }

                                        .tableborder th, .tableborder td {
                                            text-align: left;
                                            padding: 8px;
                                            border-color: #eee;
                                        }

                                        .tableborder tr:nth-child(even) {
                                            background-color: #f2f2f2
                                        }
                                    </style>
                                    <!--mo_openid_custom_field_update-->
                                    <table id="custom_field" style="width:100%; text-align: center;" class="table mywatermark">
                                        <div id="myCheck">
                                            <h4>Registration page link <input type="text" name="profile_completion_page"
                                                                              style="width: 350px" disabled="disabled"
                                                                              value="<?php echo get_option('profile_completion_page'); ?>"
                                                                              required/></h4>
                                            <thead>
                                            <tr>
                                                <th>Existing Field</th>
                                                <th>Field</th>
                                                <th>Custom name</th>
                                                <th>Field Type</th>
                                                <th>Field Options</th>
                                                <th>Required Field</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <?php
                                            ?>
                                            <tr>
                                                <td style="width: 15%"><br><input type="text" disabled="disabled" placeholder="Existing meta field"
                                                                                  style="width:90%;"/></td>
                                                <td style="width: 15%"><br><select id="field_1_name" disabled="disabled"
                                                                                   onchange="myFunction('field_1_name','opt_field_1_name','field_1_value','additional_field_1_value')"
                                                                                   style="width:80%">
                                                        <option value="">Select Field</option>
                                                    </select></td>
                                                <td style="width: 15%"><br><input type="text" id="opt_field_1_name" disabled="disabled"
                                                                                  placeholder="Custom Field Name"
                                                                                  style="width:90%;"/></td>
                                                <td style="width: 15%"><br><select id="field_1_value" name="field_1_value" disabled="disabled"
                                                                                   onchange="myFunction2('field_1_name','opt_field_1_name','field_1_value','additional_field_1_value')"
                                                                                   style="width:80%">
                                                        <option value="default">Select Type</option>
                                                    </select></td>
                                                <td style="width: 20%"><br><input type="text" id="additional_field_1_value" disabled="disabled"
                                                                                  placeholder="e.g. opt1;opt2;opt3"
                                                                                  style="width:90%;"/></td>
                                                <td style="width: 10%"><br><select name="mo_openid_custom_field_1_Required" disabled="disabled"
                                                                                   style="width:57%">
                                                        <option value="no">No</option>
                                                    </select></td>
                                                <td style="width: 10%"><br><input type="button" disabled="disabled"
                                                                                  value="+" onclick="add_custom_field();"
                                                                                  class=" button-primary"/>&nbsp;
                                                    <input type="button" name="mo_remove_attribute" value="-" disabled="disabled"
                                                           onclick="remove_custom_field();" class=" button-primary"/>
                                                </td>
                                            </tr>
                                        </div>
                                        <tr id="mo_openid_custom_field">
                                            <td align="center" colspan="7"><br>
                                                <input name="mo_openid_save_config_element" type="submit" disabled="disabled"
                                                       value="Save" <?php if (!mo_openid_is_customer_registered()) echo 'disabled' ?>
                                                       class="button button-primary button-large"/>
                                                &nbsp &nbsp <a class="button button-primary button-large" disabled="disabled">Cancel</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="7">
                                                <h3>Instructions to setup:</h3>
                                                <p>
                                                <ol>
                                                    <li> Create a page and use shortcode <b>[miniorange_social_custom_fields]</b>
                                                        where you want your form to be displayed.
                                                    </li>
                                                    <li> Copy the page link and paste it in the above field <b>Registration page
                                                            link</b>.
                                                    </li>
                                                    <li> If you have any existing wp_usermeta field then enter that field's name in
                                                        <b>Existing
                                                            Field</b> column. For example, if you are saving <b>First Name</b> as
                                                        <b>fname</b>
                                                        in wp_usermeta field then enter <b>fname</b> in <b>Existing Field</b>
                                                        column.
                                                    </li>
                                                    <li> Select field name under the <b>Field</b> dropdown.</li>
                                                    <li> If selected field is other than custom, then <b>Field Type</b> will
                                                        automatically be <b>Textbox</b> and there is no need to enter <b>Custom
                                                            name</b> and <b>Field options</b>.
                                                    </li>
                                                    <li> If selected field is custom, then enter <b>Custom name</b>.</li>
                                                    <li> Select <b>Field Type</b>, if selected <b>Field Type</b> is
                                                        <b>Checkbox</b> or <b>Dropdown</b> then enter the desire options in <b>Field
                                                            Options</b> seprated by semicolon '<b>;</b>' otherwise leave <b>Field
                                                            Options</b> blank.
                                                    </li>
                                                    <li> Select <b>Required Field</b> as <b>Yes</b> if you want to make that field
                                                        compulsory for user.
                                                    </li>
                                                    <li> If you want to add more than 1 fields at a time click on <b>"+"</b>.</li>
                                                    <li> Last click on <b>Save</b> button.</li>
                                                </ol>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                                <br>
                                <hr>
                            </div>
                        </td>
                    </tr>
                </table>


            </div>
        </td>
        <td style="vertical-align:top;padding-left:1%;">
            <div id="support"><?php echo miniorange_openid_support(); ?></div>
        </td>
        <td><form style="display:none;" id="mosocial_loginform" action="<?php echo get_option( 'mo_openid_host_name' ) . '/moas/login'; ?>"
                  target="_blank" method="post" >
                <input type="email" name="username" value="<?php echo esc_attr(get_option('mo_openid_admin_email')); ?>" />
                <input type="text" name="redirectUrl" value="<?php echo esc_attr(get_option( 'mo_openid_host_name')).'/moas/initializepayment'; ?>" />
                <input type="text" name="requestOrigin" id="requestOrigin"/>
            </form>
            <script>
                function mosocial_addonform(planType) {
                    jQuery('#requestOrigin').val(planType);
                    jQuery('#mosocial_loginform').submit();                        }
            </script>
        </td>
        <td>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
            <script type="text/javascript">
                $mo=jQuery;
                $mo(document).ready(function($){
                    $("#mosocial_purchase_cust_addon_verify").on("click",function(){
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo admin_url("admin-ajax.php"); ?>',
                            data: {
                                action:'verify_addon_licience',
                                something:'hellow',
                            },
                            crossDomain :!0, dataType:"html",
                            success: function(data) {
                                console.log(data);
                                var flag=0;
                                $("input").each(function(){
                                    if($(this).val()=="mo_openid_verify_license") flag=1;
                                });
                                if(!flag) {
                                    $(data).insertBefore("#mo_openid_extra_attributes_addon_video");
                                    $("#customization_ins").find($("#cust_supp")).css("display", "none");
                                }
                            },
                            error: function (data){}
                        });
                    });
                });
            </script>
        </td>
        <?php
    }
}

function mo_openid_show_addon_message_page()
{
    ?>
    <td style="vertical-align:top;width:65%;">
            <div class="mo_openid_table_layout">
                <?php
                echo "<div style='text-align: center'><p>It seems <b>Extra Attributes Addon plugin</b> is not installed or activated. Please download or activate the addon.</p></div>";
                ?>
                <h2 align="center">How do I download or activate the Social Integration Registration Form Addon ?</h2>
                <p><b>Download:</b> Download addon and license key from xecurify Console
                <ol>
                    <li>Login to <a target=”_blank” href="https://login.xecurify.com">xecurify Console</a>.</li>
                    <li>Click on <b>License</b> on the left menu.</li>
                    <li>You will see the payment made by you. Against the payment, you will see a <b>Download Plugin</b> option. Click on the link to download the plugin.</li>
                    <li>A plugin of .zip extension will get downloaded.</li>
                    <li>Click on <b>View License Key</b>.</li>
                    <li>Copy the license key given for use during installation.</li>
                </ol>
                </p>
                <p><b>Installation:</b>
                <ol>
                    <li>Go to <b>Plugins > Add New</b> and click on <b>Upload addon</b>.</li>
                    <li>Upload the downloaded zip and Activate.</li>
                </ol>
                </p>
            </div>
    </td>
    <td style="vertical-align:top;padding-left:1%;">
        <?php echo miniorange_openid_support(); ?>
    </td>
    <?php
}

function mo_openid_show_verify_addon_license_page($licience_type)
{
    ?>
    <td style="vertical-align:top;width:65%;">
        <div class="mo_openid_table_layout" style="padding-bottom:50px;!important">

            <h3>Verify Addon License </h3>
            <form name="addon_license" method="post" action="">
                <input type="hidden" name="option" value="mo_openid_verify_license"/>
                <input type="hidden" name="mo_openid_verify_license_nonce"
                       value="<?php echo wp_create_nonce('mo-openid-verify-license-nonce'); ?>"/>

                <p><b><font color="#FF0000">*</font>Enter your addon license key to activate the
                        addon plugin:</b>
                    <input class="mo_openid_table_textbox" required type="text"
                           style="margin-left:40px;width:300px;"
                           name="openid_licence_key"
                           placeholder="Enter your license key to activate the plugin"/>
                </p>
                <p><b><font color="#FF0000">*</font>Please check this to confirm that you have read
                        it: </b>&nbsp;&nbsp;<input required type="checkbox"
                                                   name="license_conditions"/></p>


                <ol>
                    <li>License key you have entered here is associated with this site instance. In
                        future, if you are re-installing the plugin or your site for any reason. You
                        should deactivate and then delete the plugin from wordpress console and
                        should not manually delete the plugin folder. So that you can resuse the
                        same license key.
                    </li>
                    <br>
                    <li><b>This is not a developer's license.</b> Making any kind of change to the
                        addon or plugin's code will delete all your configuration and make the addon as well as plugin
                        unusable.
                    </li>
                </ol>
                <br>
                <input type="hidden" name="licience_type" value="<?php echo $licience_type ?>"/>
                <input type="submit" name="submit" value="Activate License"
                       class="button button-primary button-large"/>

            </form>
        </div>
    </td>
    <td style="vertical-align:top;padding-left:1%;" id="cust_supp">
        <?php echo miniorange_openid_support(); ?>
    </td>
    <?php
}

function mo_openid_is_customer_registered() {
			$email 			= get_option('mo_openid_admin_email');
			$customerKey 	= get_option('mo_openid_admin_customer_key');
			if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
				return 0;
			} else {
				return 1;
			}
}

function mo_openid_is_customer_addon_license_key_verified() {
    $licenseKey = get_option('mo_openid_opn_lk_extra_attr_addon');
    $email 		= get_option('mo_openid_admin_email');
    $customerKey = get_option('mo_openid_admin_customer_key');
    if(  !$licenseKey || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ){
        return 0;
    }else {
        return 1;
    }
}

function miniorange_openid_support(){
	global $current_user;
	$current_user = wp_get_current_user();
?>
	<div class="mo_openid_support_layout">

			<h3>Support</h3>

			<p>Need any help? Couldn't find an answer in <a href="<?php echo add_query_arg( array('tab' => 'help'), $_SERVER['REQUEST_URI'] ); ?>">FAQ</a>?<br>Just send us a query so we can help you.</p>
			<form method="post" action="">
				<input type="hidden" name="option" value="mo_openid_contact_us_query_option" />
				<input type="hidden" name="mo_openid_contact_us_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-contact-us-nonce' ); ?>"/>
				<table class="mo_openid_settings_table">
					<tr>
						<td><input type="email" class="mo_openid_table_contact" required placeholder="Enter your Email" name="mo_openid_contact_us_email" value="<?php echo get_option("mo_openid_admin_email"); ?>"></td>
					</tr>
					<tr>
						<td><input type="tel" id="contact_us_phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" placeholder="Enter your phone number with country code (+1)" class="mo_openid_table_contact" name="mo_openid_contact_us_phone" value="<?php echo get_option('mo_openid_admin_phone');?>"></td>
					</tr>
					<tr>
						<td><textarea class="mo_openid_table_contact" onkeypress="mo_openid_valid_query(this)" onkeyup="mo_openid_valid_query(this)" placeholder="Write your query here" onblur="mo_openid_valid_query(this)" required name="mo_openid_contact_us_query" rows="4" style="resize: vertical;"></textarea></td>
					</tr>
				</table>
				<br>
			<input type="submit" name="submit" value="Submit Query" style="width:110px;margin-left: 30%;margin-top: -2%;" class="button button-primary button-large" />
                <br><h2 style="text-align: center;margin-left: -10%;">OR</h2><br>

                <button type="button" class="button button-primary button-large"style=" margin-top: -5%;margin-left: 23%;" onclick="wordpress_support();"> WordPress Support Forum</button>
			</form>
			<p style="margin-top: 7%;">If you want custom features in the plugin, just drop an email at <a href="mailto:info@xecurify.com">info@xecurify.com</a>.</p>
		</div>
	</div>
	</div>
	</div>
	<script>
		jQuery("#contact_us_phone").intlTelInput();
		function mo_openid_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
					/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
		}
		
		function moSharingSizeValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
		}
		function moSharingSpaceValidate(e){
			var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
		}
		function moLoginSizeValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
		}
		function moLoginSpaceValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
		}
		function moLoginWidthValidate(e){
			var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
		}
		function moLoginHeightValidate(e){
			var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
		}
        function wordpress_support() {
            window.open("https://wordpress.org/support/plugin/miniorange-login-openid");

        }
	</script>
<?php
}

function mo_openid_is_customer_valid(){
	$valid = sanitize_text_field(get_option('mo_openid_admin_customer_valid'));
	if(isset($valid) && get_option('mo_openid_admin_customer_plan'))
		return $valid;
	else
		return false;
}

function mo_openid_get_customer_plan($customerPlan){
	$plan = sanitize_text_field(get_option('mo_openid_admin_customer_plan'));
	$planName = isset($plan) ? base64_decode($plan) : 0;
	if($planName) {
		if(strpos($planName, $customerPlan) !== FALSE)
			return true;
		else
			return false;
	} else
		return false;
}

function mo_openid_is_extension_installed($name) {
	if  (in_array  ($name, get_loaded_extensions())) {
		return true;
	}
	else {
		return false;
	}
}

function mo_openid_is_curl_installed() {
    if (in_array ('curl', get_loaded_extensions())) {
        return 1;
    } else
        return 0;
}

function mo_openid_restrict_user() {
    if((get_option('mo_openid_admin_customer_key')>151617) || (get_option('mo_openid_new_user')==1)|| !mo_openid_is_customer_registered()) {
        return true;
    } else {
        return false;
    }
}
function mo_openid_malform_error() {
    if(get_option("mo_openid_malform_error")=='1'){
        return true;
    } else {
        return false;
    }
}

function mo_openid_registeration_modal(){
    update_option('regi_pop_up','yes');
    update_option ( 'mo_openid_new_registration', 'true' );
    global $current_user;

    $current_user = wp_get_current_user();
    ?><div id="request_registeration" class="mo_openid_modal" style="height:100%">
        <!-- Modal content -->
        <div class="mo_openid_modal-content" style="text-align:center;margin-left: 32%;margin-top: 4%;width: 37%;">
            <div class="modal-head">
                <!--                <span class="mo_close">&times;</span>-->
                <a href="" class="mo_close" id="mo_close" style="text-decoration:none;margin-top: 3%;">&times;</a>
                <h1>Save As</h1>
            </div>
            <br>
            <span id="msg1" style="text-align: center;color: #56b11e"><?php echo (get_option("pop_regi_msg")); update_option('pop_regi_msg','Your settings are saved successfully. Please enter your valid email address and enter a password to get touch with our support team.');?></span>
            <br>
            <br>
            <br>
            <div id="mo_saml_show_registeration_modal" >

                <!--Register with miniOrange-->

                <form name="f" method="post" action="" id="pop-register-form">
                    <input name="option" value="mo_openid_connect_register_customer" type="hidden"/>
					<input type="hidden" name="mo_openid_connect_register_nonce"
                   					value="<?php echo wp_create_nonce( 'mo-openid-connect-register-nonce' ); ?>"/>
                    <div>


                        <table style="text-align: left;width: 100%">


                            <tbody><tr>
                                <td><b><font color="#FF0000">*</font>Email:</b></td>
                                <td><input class="mo_openid_table_textbox" name="email" style="width: 100%" 
								required placeholder="person@example.com" 
								value="<?php echo $current_user->user_email;?>" type="email" />
                                </td>
                            </tr>

                            <tr>
                                <td><b><font color="#FF0000">*</font>Password:</b></td>
                                <td><input class="mo_openid_table_textbox" required name="password" 
								style="width: 100%" placeholder="Choose your password (Min. length 6)" 
								type="password" /></td>
                            </tr>
                            <tr id="pop_register" >
                                <td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
                                <td><input class="mo_openid_table_textbox" required name="confirmPassword" 
								style="width: 100%" placeholder="Confirm your password"
								type="password" /></td>
                            </tr>

                            </tbody></table>
                        <br><br>
                        <input value="Submit" id="register_submit" style="margin-right: 28%;" class="button button-primary button-large" type="submit">

                    </div>
                </form>
                <form method="post">

                    <input id="pop_next" name="show_login" value="Existing Account" class="button button-primary button-large" style="margin-left: 35%;margin-top: -7.7%;" type="submit">

                </form>



            </div>
        </div>

    </div>

    <?php
}

function mo_pop_show_verify_password_page() {
	update_option('regi_pop_up','yes');
	?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <div id="request_registeration" class="mo_openid_modal" style="height:100%">

        <!-- Modal content -->
        <div class="mo_openid_modal-content" style="text-align:center;margin-left: 32%;margin-top: 2%;width: 37%;">
            <div class="modal-head">
                <a href="" class="mo_close" id="mo_close" style="text-decoration:none;margin-top: 3%;">&times;</a>
                <h1>Login</h1>
            </div>
            <br>
            <span style="text-align: center;color: #56b11e"><?php echo (get_option("pop_login_msg")); update_option('pop_login_msg','Enter Your Login Credentials.');?></span>
            <br><br>
            <div id="mo_saml_show_registeration_modal" >

                <!--Register with miniOrange-->
                <form name="f" method="post" action="" id="pop-register-form">
                    <input type="hidden" name="option" value="mo_openid_connect_verify_customer" />
                    <input type="hidden" name="mo_openid_connect_verify_nonce"
                           value="<?php echo wp_create_nonce( 'mo-openid-connect-verify-nonce' ); ?>"/>
                    <div>
                        <table style="text-align: left;width: 100%">

                            <tr>
                                <td><b><font color="#FF0000">*</font>Email:</b></td>
                                <td><input class="mo_openid_table_textbox" id="email" type="email" name="email"
                                           required placeholder="person@example.com"
                                           value="<?php echo esc_attr(get_option('mo_openid_admin_email'));?>" />
                                </td>
                            </tr>

                            <tr>
                                <td><b><font color="#FF0000">*</font>Password:</b></td>
                                <td><input class="mo_openid_table_textbox" required type="password"
                                           name="password" placeholder="Choose your password" /></td>
                            </tr>
                            <tr style="text-align: center">
                                <td>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </td>

                            </tr>
                        </table>
                        <input type="submit"  value="Login" id="login_submit" style="margin-left: -21%;margin-top: -1%;" class="button button-primary button-large" "/>
                    </div>
                </form>
                &nbsp &nbsp &nbsp <br><form method="post" style="margin-top: -15.1%;margin-left: 46%;">
                    <input type="submit" id="go_to_register" name="go_to_register" value="Register" class="button button-primary button-large" style="margin-left: -67%;margin-top: 7.8%;">
                </form>
                <p><b><a href="#forgot_password">Click here if you forgot your password?</a></b></p>
                <form name="forgotpassword" method="post" action="" id="openidforgotpasswordform">
                    <input type="hidden" name="option" value="mo_openid_forgot_password"/>
                    <input type="hidden" name="mo_openid_forgot_password_nonce"
                           value="<?php echo wp_create_nonce( 'mo-openid-forgot-password-nonce' ); ?>"/>
                    <input type="hidden" id="forgot_pass_email" name="email" value=""/>
                </form>
            </div>

        </div>

    </div>

    <script>
        jQuery('a[href="#forgot_password"]').click(function(){
            jQuery('#forgot_pass_email').val(jQuery('#email').val());
            jQuery('#openidforgotpasswordform').submit();
        });
    </script>
	<?php
}

function is_custom_app($app_name){

    if(get_option('mo_openid_apps_list'))
        $appslist = maybe_unserialize(get_option('mo_openid_apps_list'));
    else
        $appslist = array();

    $flag=0;
    foreach( $appslist as $key => $app){
        if($app_name == $key ){
            $flag=1;
            break;
        }
    }
    $option = 'mo_openid_enable_custom_app_' .$app_name;
    if($flag==0)
    {
        return 0;
    }
    if($flag==1&&get_option($option) == '0'){
        return 1;
    }
    if($flag==1&&get_option($option) == '1')
    {
        return 2;
    }
}
?>