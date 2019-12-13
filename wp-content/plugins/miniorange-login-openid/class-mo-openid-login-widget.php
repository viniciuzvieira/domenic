<?php
include "class-mo-openid-twitter-oauth.php";
include "class-mo-openid-social-login-functions.php";
include_once dirname(__FILE__) . '/miniorange_openid_sso_encryption.php';

    /*
    * Login Widget
    *
    */
    class mo_openid_login_wid extends WP_Widget {

        public function __construct() {
            parent::__construct(
                'mo_openid_login_wid',
                'miniOrange Social Login Widget',
                array(
                    'description' => __( 'Login using Social Apps like Google, Facebook, LinkedIn, Microsoft, Instagram.', 'flw' ),
                    'customize_selective_refresh' => true,
                )
            );
        }

        public function widget( $args, $instance ) {
            extract( $args );

            echo $args['before_widget'];
            $this->openidloginForm();

            echo $args['after_widget'];
        }

        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
            return $instance;
        }

        public function openidloginForm()
        {
            if (mo_openid_is_customer_registered()) {
                if ($GLOBALS['pagenow'] === 'wp-login.php') {
                    ?>
                    <script
                            src="https://code.jquery.com/jquery-1.12.4.js"
                            integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
                            crossorigin="anonymous"></script>
                    <?php
                }

                $selected_theme = esc_attr(get_option('mo_openid_login_theme'));
                $appsConfigured = get_option('mo_openid_google_enable') | get_option('mo_openid_salesforce_enable') | get_option('mo_openid_facebook_enable') | get_option('mo_openid_linkedin_enable') | get_option('mo_openid_instagram_enable') | get_option('mo_openid_amazon_enable') | get_option('mo_openid_windowslive_enable') | get_option('mo_openid_twitter_enable') | get_option('mo_openid_vkontakte_enable');
                $spacebetweenicons = esc_attr(get_option('mo_login_icon_space'));
                $customWidth = esc_attr(get_option('mo_login_icon_custom_width'));
                $customHeight = esc_attr(get_option('mo_login_icon_custom_height'));
                $customSize = esc_attr(get_option('mo_login_icon_custom_size'));
                $customBackground = esc_attr(get_option('mo_login_icon_custom_color'));
                $customTheme = esc_attr(get_option('mo_openid_login_custom_theme'));
                $customTextofTitle = esc_attr(get_option('mo_openid_login_button_customize_text'));
                $customBoundary = esc_attr(get_option('mo_login_icon_custom_boundary'));
                $customLogoutName = esc_attr(get_option('mo_openid_login_widget_customize_logout_name_text'));
                $customLogoutLink = (get_option('mo_openid_login_widget_customize_logout_text'));
                $customTextColor= esc_attr(get_option('mo_login_openid_login_widget_customize_textcolor'));
                $customText = esc_html(get_option('mo_openid_login_widget_customize_text'));
    
                $facebook_custom_app = esc_attr($this->if_custom_app_exists('facebook'));
                $google_custom_app = esc_attr($this->if_custom_app_exists('google'));
                $twitter_custom_app = esc_attr($this->if_custom_app_exists('twitter'));
                $salesforce_custom_app = esc_attr($this->if_custom_app_exists('salesforce'));
                $linkedin_custom_app = esc_attr($this->if_custom_app_exists('linkedin'));
                $windowslive_custom_app = esc_attr($this->if_custom_app_exists('windowslive'));
                $vkontakte_custom_app = esc_attr($this->if_custom_app_exists('vkontakte'));
                $amazon_custom_app = esc_attr($this->if_custom_app_exists('amazon'));
                $instagram_custom_app = esc_attr($this->if_custom_app_exists('instagram'));
                $yahoo_custom_app = esc_attr($this->if_custom_app_exists('yahoo'));
                if (get_option('mo_openid_gdpr_consent_enable')) {
                    $gdpr_setting = "disabled='disabled'";
                } else
                    $gdpr_setting = '';

                $url = esc_url(get_option('mo_openid_privacy_policy_url'));
                $text = esc_html(get_option('mo_openid_privacy_policy_text'));

                if (!empty($text) && strpos(get_option('mo_openid_gdpr_consent_message'), $text)) {
                    $consent_message = str_replace(get_option('mo_openid_privacy_policy_text'), '<a target="_blank" href="' . $url . '">' . $text . '</a>', get_option('mo_openid_gdpr_consent_message'));
                } else if (empty($text)) {
                    $consent_message = get_option('mo_openid_gdpr_consent_message');
                }

                if (!is_user_logged_in()) {

                    if ($appsConfigured) {
                        $this->mo_openid_load_login_script();
                        ?>

                        <div class="mo-openid-app-icons">

                            <p style="color:#<?php echo $customTextColor ?>"><?php echo $customText ?>
                            </p>
                            <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                $consent_message = isset($consent_message) ? $consent_message : ''; ?>
                                <label class="mo-consent" style="padding-right: 10px;"><input type="checkbox"
                                                                                              onchange="mo_openid_on_consent_change(this,value)"
                                                                                              value="1"
                                                                                              id="mo_openid_consent_checkbox"><?php echo $consent_message; ?>
                                </label>
                                <br>
                            <?php }

                            if ($customTheme == 'default') {

                                if (get_option('mo_openid_facebook_enable')) {
                                    if ($selected_theme == 'longbutton') {

                                        ?>

                                        <a  rel='nofollow' <?php echo $gdpr_setting; ?>  onClick="moOpenIdLogin('facebook','<?php echo $facebook_custom_app?>');" style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight-27?>px !important;padding-bottom:<?php echo $customHeight-29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons-5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;" class="btn-mo btn btn-block btn-social btn-facebook  btn-custom-size login-button"  ><svg xmlns="http://www.w3.org/2000/svg" style="padding-top: <?php echo $customHeight-30?>px;border-right:none;margin-left: 2%;" ><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"/></svg><?php
                                                echo get_option('mo_openid_login_button_customize_text'); ?> Facebook</a>
                                        <?php

                                    } else {
                                        ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           title="<?php echo $customTextofTitle ?> Facebook"
                                           onClick="moOpenIdLogin('facebook','<?php echo $facebook_custom_app ?>');"><img
                                                    alt='Facebook'
                                                    style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                    src="<?php echo plugins_url('includes/images/icons/facebook.png', __FILE__) ?>"
                                                    class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                        echo "dis";
                                                    } ?> login-button"></a>
                                    <?php }

                                }

                                if (get_option('mo_openid_google_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?>

                                        <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                           onClick="moOpenIdLogin('google','<?php echo $google_custom_app ?>');"
                                           style='background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important; width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;'
                                           class='btn-mo btn btn-block btn-social btn-google btn-custom-size login-button'>
                                            <i style='border-right:none;padding-top:<?php echo $customHeight - 30 ?>px !important'
                                               class='fa'><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70 70" style="padding-left: 8%;" ><defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"/><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"/><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"/><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"/></svg></i><span style="color:#FFFFFF;"><?php echo get_option('mo_openid_login_button_customize_text'); ?> Google</span></a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('google','<?php echo $google_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Google"><img alt='Google'
                                                                                                style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                                                                src="<?php echo plugins_url('includes/images/icons/google.png', __FILE__) ?>"
                                                                                                class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                                                                    echo "dis";
                                                                                                } ?> login-button"></a>
                                        <?php
                                    }
                                }

                                if (get_option('mo_openid_vkontakte_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?>

                                        <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                           onClick="moOpenIdLogin('vkontakte','<?php echo $vkontakte_custom_app ?>');"
                                           style='width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;'
                                           class='btn-mo btn btn-block btn-social btn-vk btn-custom-size login-button'>
                                            <i style='padding-top:<?php echo $customHeight - 35 ?>px !important'
                                               class='fa fa-vk'></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Vkontakte</a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('vkontakte','<?php echo $vkontakte_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Vkontakte"><img alt='Vkontakte'
                                                                                                   style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                                                                   src="<?php echo plugins_url('includes/images/icons/vk.png', __FILE__) ?>"
                                                                                                   class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                                                                       echo "dis";
                                                                                                   } ?> login-button"></a>
                                        <?php
                                    }
                                }

                                if (get_option('mo_openid_twitter_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                              onClick="moOpenIdLogin('twitter','<?php echo $twitter_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn-mo btn btn-block btn-social btn-twitter btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-twitter"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Twitter</a>
                                    <?php } else { ?>


                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           title="<?php echo $customTextofTitle ?> Twitter"
                                           onClick="moOpenIdLogin('twitter','<?php echo $twitter_custom_app ?>');"><img
                                                    alt='Twitter'
                                                    style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                    src="<?php echo plugins_url('includes/images/icons/twitter.png', __FILE__) ?>"
                                                    class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                        echo "dis";
                                                    } ?> login-button"></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_linkedin_enable')) {
                                    if ($selected_theme == 'longbutton') { ?>
                                        <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                           onClick="moOpenIdLogin('linkedin','<?php echo $linkedin_custom_app ?>');"
                                           style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                           class="btn-mo btn btn-block btn-social btn-linkedin btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-linkedin"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> LinkedIn</a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow' <?php echo $gdpr_setting; ?>
                                           title="<?php echo $customTextofTitle ?> LinkedIn"
                                           onClick="moOpenIdLogin('linkedin','<?php echo $linkedin_custom_app ?>');"><img
                                                    alt='LinkedIn'
                                                    style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                    src="<?php echo plugins_url('includes/images/icons/linkedin.png', __FILE__) ?>"
                                                    class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                        echo "dis";
                                                    } ?> btn-mo login-button"></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_instagram_enable')) {
                                    if ($selected_theme == 'longbutton') { ?>
                                        <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                           onClick="moOpenIdLogin('instagram','<?php echo $instagram_custom_app ?>');"
                                           style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                           class="btn-mo btn btn-block btn-social btn-instagram btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-instagram"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Instagram</a>
                                    <?php } else { ?>


                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow' <?php echo $gdpr_setting; ?>
                                           title="<?php echo $customTextofTitle ?> Instagram"
                                           onClick="moOpenIdLogin('instagram','<?php echo $instagram_custom_app ?>');"><img
                                                    alt='Instagram'
                                                    style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                    src="<?php echo plugins_url('includes/images/icons/instagram.png', __FILE__) ?>"
                                                    class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                        echo "dis";
                                                    } ?> login-button"></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_amazon_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                              onClick="moOpenIdLogin('amazon','<?php echo $amazon_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn-mo btn btn-block btn-social btn-soundcloud btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-amazon"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Amazon</a>
                                    <?php } else { ?>

                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow' <?php echo $gdpr_setting; ?>
                                           title="<?php echo $customTextofTitle ?> Amazon"
                                           onClick="moOpenIdLogin('amazon','<?php echo $amazon_custom_app ?>');"><img
                                                    alt='Amazon'
                                                    style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                    src="<?php echo plugins_url('includes/images/icons/amazon.png', __FILE__) ?>"
                                                    class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                        echo "dis";
                                                    } ?> login-button"></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_salesforce_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                              onClick="moOpenIdLogin('salesforce','<?php echo $salesforce_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn-mo btn btn-block btn-social btn-vimeo btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-cloud"></i> <?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Salesforce</a>
                                    <?php } else { ?>


                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow' <?php echo $gdpr_setting; ?>
                                           title="<?php echo $customTextofTitle ?> Salesforce"
                                           onClick="moOpenIdLogin('salesforce','<?php echo $salesforce_custom_app ?>');"><img
                                                    alt='Salesforce'
                                                    style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                    src="<?php echo plugins_url('includes/images/icons/salesforce.png', __FILE__) ?>"
                                                    class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                        echo "dis";
                                                    } ?> login-button"></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_windowslive_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow'
                                              <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('windowslive','<?php echo $windowslive_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn-mo btn btn-block btn-social btn-microsoft btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-windows"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Microsoft</a>
                                    <?php } else { ?>


                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           <?php echo $gdpr_setting; ?>title="<?php echo $customTextofTitle ?> Microsoft"
                                           onClick="moOpenIdLogin('windowslive','<?php echo $windowslive_custom_app ?>');"><img
                                                    alt='Windowslive'
                                                    style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                    src="<?php echo plugins_url('includes/images/icons/windowslive.png', __FILE__) ?>"
                                                    class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                        echo "dis";
                                                    } ?> login-button"></a>
                                    <?php }
                                }
                                if (get_option('mo_openid_yahoo_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?><a  rel='nofollow' <?php echo $gdpr_setting; ?>
                                              onClick="moOpenIdLogin('yahoo','<?php echo $yahoo_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn-mo btn btn-block btn-social btn-yahoo  btn-custom-size login-button"  >
                                        <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                           class="fa fa-yahoo"></i><?php
                                        echo get_option('mo_openid_login_button_customize_text'); ?> Yahoo</a>
                                        <?php
                                    } else {
                                        ?><a  class=" <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow' title="<?php echo $customTextofTitle ?> Yahoo"
                                              onClick="moOpenIdLogin('yahoo','<?php echo $yahoo_custom_app ?>');"><img
                                                alt='Yahoo'
                                                style="width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important"
                                                src="<?php echo plugins_url('includes/images/icons/yahoo.png', __FILE__) ?>"
                                                class="<?php echo $selected_theme; ?> <?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                                    echo "dis";
                                                } ?> login-button"></a>
                                    <?php }
                                }

                            }
                            ?>



                            <?php
                            if ($customTheme == 'custom') {
                                if (get_option('mo_openid_facebook_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow'
                                              <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('facebook','<?php echo $facebook_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn-mo btn btn-block btn-social btn-facebook  btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa"><svg xmlns="http://www.w3.org/2000/svg" style="padding-top:12%;border-right:none;margin-left: 2%;" ><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"/></svg></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Facebook</a>
                                    <?php } else { ?>

                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('facebook','<?php echo $facebook_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Facebook"><i
                                                    style="margin-top:10px;!important;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-facebook custom-login-button <?php echo $selected_theme; ?>"></i></a>

                                    <?php }

                                }

                                if (get_option('mo_openid_google_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?>

                                        <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                           onClick="moOpenIdLogin('google','<?php echo $google_custom_app ?>');"
                                           style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important; background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                           class="btn-mo btn btn-block btn-social btn-customtheme btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-google"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Google</a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('google','<?php echo $google_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Google"><i
                                                    style="margin-top:10px;!important;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-google custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                        <?php
                                    }
                                }

                                if (get_option('mo_openid_vkontakte_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?>

                                        <a rel='nofollow'
                                           <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('vkontakte','<?php echo $vkontakte_custom_app ?>');"
                                           style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important; background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                           class="btn-mo btn btn-block btn-social btn-customtheme btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-vk"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Vkontakte</a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('vkontakte','<?php echo $vkontakte_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Vkontakte"><i
                                                    style="margin-top:10px;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-vk custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                        <?php
                                    }
                                }

                                if (get_option('mo_openid_twitter_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?>

                                        <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                           onClick="moOpenIdLogin('twitter','<?php echo $twitter_custom_app ?>');"
                                           style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important; background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                           class="btn btn-mo btn-block btn-social btn-customtheme btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-twitter"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Twitter</a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('twitter','<?php echo $twitter_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Twitter"><i
                                                    style="margin-top:10px;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-twitter custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                        <?php
                                    }
                                }

                                if (get_option('mo_openid_linkedin_enable')) {
                                    if ($selected_theme == 'longbutton') { ?>
                                        <a rel='nofollow'
                                           <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('linkedin','<?php echo $linkedin_custom_app ?>');"
                                           style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                           class="btn btn-mo btn-block btn-social btn-linkedin btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-linkedin"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> LinkedIn</a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('linkedin','<?php echo $linkedin_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> LinkedIn"><i
                                                    style="margin-top:10px;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-linkedin custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_instagram_enable')) {
                                    if ($selected_theme == 'longbutton') { ?>
                                        <a rel='nofollow'
                                           <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('instagram','<?php echo $instagram_custom_app ?>');"
                                           style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                           class="btn btn-block btn-mo btn-social btn-instagram btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-instagram"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Instagram</a>
                                    <?php } else { ?>


                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('instagram','<?php echo $instagram_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Instagram"><i
                                                    style="margin-top:10px;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-instagram custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_amazon_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow' <?php echo $gdpr_setting; ?>
                                              onClick="moOpenIdLogin('amazon','<?php echo $amazon_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn btn-mo btn-block btn-social btn-linkedin btn-custom-size login-button"><i
                                                    style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                                    class="fa fa-amazon"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Amazon</a>
                                    <?php } else { ?>

                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('amazon','<?php echo $amazon_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Amazon"><i
                                                    style="margin-top:10px;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-amazon custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_salesforce_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow'
                                              <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('salesforce','<?php echo $salesforce_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn btn-mo btn-block btn-social btn-linkedin btn-custom-size login-button"><i
                                                    style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                                    class="fa fa-cloud"></i> <?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Salesforce</a>
                                    <?php } else { ?>


                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('salesforce','<?php echo $salesforce_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Salesforce"><i
                                                    style="margin-top:10px;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px "
                                                    class="fa fa-cloud custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                    <?php }
                                }

                                if (get_option('mo_openid_windowslive_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow'
                                              <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('windowslive','<?php echo $windowslive_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn btn-mo btn-block btn-social btn-microsoft btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-windows"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Microsoft</a>
                                    <?php } else { ?>


                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('windowslive','<?php echo $windowslive_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Microsoft"><i
                                                    style="margin-top:10px;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class=" fa fa-windows custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                    <?php }
                                }
                                if (get_option('mo_openid_yahoo_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        ?> <a rel='nofollow'
                                              <?php echo $gdpr_setting; ?>onClick="moOpenIdLogin('yahoo','<?php echo $yahoo_custom_app ?>');"
                                              style="width:<?php echo $customWidth ?>px !important;padding-top:<?php echo $customHeight - 29 ?>px !important;padding-bottom:<?php echo $customHeight - 29 ?>px !important;margin-bottom:<?php echo $spacebetweenicons - 5 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;border-radius:<?php echo $customBoundary ?>px !important;"
                                              class="btn-mo btn btn-block btn-social btn-yahoo  btn-custom-size login-button">
                                            <i style="padding-top:<?php echo $customHeight - 35 ?>px !important"
                                               class="fa fa-yahoo"></i><?php
                                            echo get_option('mo_openid_login_button_customize_text'); ?> Yahoo</a>
                                    <?php } else { ?>
                                        <a class="<?php if (get_option('mo_openid_gdpr_consent_enable')) {
                                            echo "dis";
                                        } ?> login-button" rel='nofollow'
                                           onClick="moOpenIdLogin('yahoo','<?php echo $yahoo_custom_app ?>');"
                                           title="<?php echo $customTextofTitle ?> Yahoo"><i
                                                    style="margin-top:10px;!important;width:<?php echo $customSize ?>px !important;height:<?php echo $customSize ?>px !important;margin-left:<?php echo $spacebetweenicons - 4 ?>px !important;background:<?php echo "#" . $customBackground ?> !important;font-size:<?php echo $customSize - 16 ?>px !important;"
                                                    class="fa fa-yahoo custom-login-button <?php echo $selected_theme; ?>"></i></a>
                                    <?php }

                                }


                            }
                            ?>
                            <br>
                        </div>
                        <?php


                    } else {
                        ?>
                        <div>No apps configured. Please contact your administrator.</div>
                        <?php
                    }
                    if ($appsConfigured && get_option('moopenid_logo_check') == 1) {
                        $logo_html = $this->mo_openid_customize_logo();
                        echo $logo_html;
                    }
                    ?>
                    <br/>
                    <?php
                } else {
                    global $current_user;
                    $current_user = wp_get_current_user();
                    $customLogoutName = str_replace('##username##', $current_user->display_name, $customLogoutName);
                    $link_with_username = $customLogoutName;
                    if (empty($customLogoutName) || empty($customLogoutLink)) {
                        ?>
                        <div id="logged_in_user" class="mo_openid_login_wid">
                            <li><?php echo $link_with_username; ?> <a href="<?php echo wp_logout_url(site_url()); ?>"
                                                                      title="<?php _e('Logout', 'flw'); ?>"><?php _e($customLogoutLink, 'flw'); ?></a>
                            </li>
                        </div>
                        <?php

                    } else {
                        ?>
                        <div id="logged_in_user" class="mo_openid_login_wid">
                            <li><?php echo $link_with_username; ?> <a href="<?php echo wp_logout_url(site_url()); ?>"
                                                                      title="<?php _e('Logout', 'flw'); ?>"><?php _e($customLogoutLink, 'flw'); ?></a>
                            </li>
                        </div>
                        <?php
                    }
                }
            }
        }

        public function mo_openid_customize_logo(){
            $logo =" <div style='float:left;' class='mo_image_id'>
			<a target='_blank' href='https://www.miniorange.com/'>
			<img alt='logo' src='". plugins_url('/includes/images/miniOrange.png',__FILE__) ."' class='mo_openid_image'>
			</a>
			</div>
			<br/>";
            return $logo;
        }

        public function if_custom_app_exists($app_name){
            if(get_option('mo_openid_apps_list'))
                $appslist = maybe_unserialize(get_option('mo_openid_apps_list'));
            else
                $appslist = array();

            foreach( $appslist as $key => $app){
                $option = 'mo_openid_enable_custom_app_' . $key;
                if($app_name == $key && get_option($option) == '1')
                    return 'true';
            }
            return 'false';
        }

        public function openidloginFormShortCode( $atts )
        {

            if (mo_openid_is_customer_registered()) {
                global $post;
                $html = '';
                $apps="";
                $selected_theme = isset( $atts['shape'] )? esc_attr($atts['shape']) : esc_attr(get_option('mo_openid_login_theme'));
                $selected_apps = isset( $atts['apps'] )? esc_attr($atts['apps']) : "";
                $appsConfigured = get_option('mo_openid_google_enable') | get_option('mo_openid_salesforce_enable') | get_option('mo_openid_facebook_enable') | get_option('mo_openid_linkedin_enable') | get_option('mo_openid_instagram_enable') | get_option('mo_openid_amazon_enable') | get_option('mo_openid_windowslive_enable') |get_option('mo_openid_twitter_enable') | get_option('mo_openid_vkontakte_enable') | get_option('mo_openid_yahoo_enable');
                $spacebetweenicons = isset( $atts['space'] )? esc_attr(intval($atts['space'])) : esc_attr(intval(get_option('mo_login_icon_space')));
                $customWidth = isset( $atts['width'] )? esc_attr(intval($atts['width'])) : esc_attr(intval(get_option('mo_login_icon_custom_width')));
                $customHeight = isset( $atts['height'] )? esc_attr(intval($atts['height'])) : esc_attr(intval(get_option('mo_login_icon_custom_height')));
                $customSize = isset( $atts['size'] )? esc_attr(intval($atts['size'])) : esc_attr(intval(get_option('mo_login_icon_custom_size')));
                $customBackground = isset( $atts['background'] )? esc_attr($atts['background']) : esc_attr(get_option('mo_login_icon_custom_color'));
                $customTheme = isset( $atts['theme'] )? esc_attr($atts['theme']) : esc_attr(get_option('mo_openid_login_custom_theme'));
                $buttonText = esc_html(get_option('mo_openid_login_button_customize_text'));
                $customTextofTitle = esc_attr(get_option('mo_openid_login_button_customize_text'));
                $logoutUrl = esc_url(wp_logout_url(site_url()));
                $customBoundary = isset( $atts['edge'] )? esc_attr($atts['edge']) : esc_attr(get_option('mo_login_icon_custom_boundary'));
                $customLogoutName = esc_attr(get_option('mo_openid_login_widget_customize_logout_name_text'));
                $customLogoutLink = (get_option('mo_openid_login_widget_customize_logout_text'));
                $customTextColor= isset( $atts['color'] )? esc_attr($atts['color']) : esc_attr(get_option('mo_login_openid_login_widget_customize_textcolor'));
                $customText=isset( $atts['heading'] )? esc_html($atts['heading']) :esc_html(get_option('mo_openid_login_widget_customize_text'));
                $view=isset( $atts['view'] )? esc_attr($atts['view']) : "";
                $appcnt=isset( $atts['appcnt'] )? esc_attr($atts['appcnt']) : "";

                $facebook_custom_app = esc_attr($this->if_custom_app_exists('facebook'));
                $google_custom_app = esc_attr($this->if_custom_app_exists('google'));
                $twitter_custom_app = esc_attr($this->if_custom_app_exists('twitter'));
                $salesforce_custom_app = esc_attr($this->if_custom_app_exists('salesforce'));
                $linkedin_custom_app = esc_attr($this->if_custom_app_exists('linkedin'));
                $windowslive_custom_app = esc_attr($this->if_custom_app_exists('windowslive'));
                $vkontakte_custom_app = esc_attr($this->if_custom_app_exists('vkontakte'));
                $amazon_custom_app = esc_attr($this->if_custom_app_exists('amazon'));
                $instagram_custom_app = esc_attr($this->if_custom_app_exists('instagram'));
                $yahoo_custom_app = esc_attr($this->if_custom_app_exists('yahoo'));

                if ($selected_theme == 'longbuttonwithtext') {
                    $selected_theme = 'longbutton';
                }
                if($selected_apps!="")
                {
                    $apps=explode(',', $selected_apps);
                }
                if ($customTheme == 'custombackground') {
                    $customTheme = 'custom';
                }

                if (get_option('mo_openid_gdpr_consent_enable')) {
                    $gdpr_setting = "disabled='disabled'";
                } else
                    $gdpr_setting = '';

                $url = esc_url(get_option('mo_openid_privacy_policy_url'));
                $text = esc_html(get_option('mo_openid_privacy_policy_text'));

                if (!empty($text) && strpos(get_option('mo_openid_gdpr_consent_message'), $text)) {
                    $consent_message = str_replace(get_option('mo_openid_privacy_policy_text'), '<a target="_blank" href="' . $url . '">' . $text . '</a>', get_option('mo_openid_gdpr_consent_message'));
                } else if (empty($text)) {
                    $consent_message = get_option('mo_openid_gdpr_consent_message');
                }

                if (get_option('mo_openid_gdpr_consent_enable')) {
                    $dis = "dis";
                } else {
                    $dis = '';
                }

                if (!is_user_logged_in()) {

                    if ($appsConfigured|| $apps!="") {
                        $this->mo_openid_load_login_script();
                        $html .= "<div class='mo-openid-app-icons'>
	 
					 <p style='color:#" . $customTextColor . "'> $customText</p>";

                        if (get_option('mo_openid_gdpr_consent_enable')) {
                            $html .= '<label class="mo-consent"><input type="checkbox" onchange="mo_openid_on_consent_change(this,value)" value="1" id="mo_openid_consent_checkbox">';
                            $html .= $consent_message . '</label><br>';
                        }

                        $app_dis="";
                        if($customTheme == 'default')
                        {
                            $count = -1;
                            if($apps!="")
                            {
                                foreach ($apps as $select_apps)
                                {
                                    $app_dis="";
                                    if($selected_theme == 'longbutton')
                                    {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    switch ($select_apps)
                                    {
                                        case 'fb':
                                            $custom_app=$facebook_custom_app;
                                            $custom_app=="false"?$app_dis="disable":$app_dis="";
                                            $html = $this->add_apps("fb", "default", $gdpr_setting, $spacebetweenicons, $customWidth, $customHeight, $customBoundary, $buttonText, $dis, $customTextofTitle, $customSize, $selected_theme, $custom_app, $html, $view, $customBackground,$app_dis);
                                            break;
                                        case 'google':
                                            $custom_app=$google_custom_app;
                                            $html=$this->add_apps("google","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'vk':
                                            $custom_app=$vkontakte_custom_app;
                                            $html=$this->add_apps("vk","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'twitter':
                                            $custom_app=$twitter_custom_app;
                                            $html=$this->add_apps("twitter","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'linkin':
                                            $custom_app=$linkedin_custom_app;
                                            $html=$this->add_apps("linkin","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'insta':
                                            $custom_app=$instagram_custom_app;
                                            $html=$this->add_apps("insta","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'amazon':
                                            $custom_app=$amazon_custom_app;
                                            $html=$this->add_apps("amazon","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'sforce':
                                            $custom_app=$salesforce_custom_app;
                                            $html=$this->add_apps("sforce","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'wlive':
                                            $custom_app=$windowslive_custom_app;
                                            $html=$this->add_apps("wlive","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'yahoo':
                                            $custom_app=$yahoo_custom_app;
                                            $html=$this->add_apps("yahoo","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                    }
                                }
                            }

                            else {
                                if (get_option('mo_openid_facebook_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app = $facebook_custom_app;
                                    $html = $this->add_apps("fb", "default", $gdpr_setting, $spacebetweenicons, $customWidth, $customHeight, $customBoundary, $buttonText, $dis, $customTextofTitle, $customSize, $selected_theme, $custom_app, $html, $view, $customBackground,$app_dis);
                                }

                                if (get_option('mo_openid_google_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app = $google_custom_app;
                                    $html = $this->add_apps("google", "default", $gdpr_setting, $spacebetweenicons, $customWidth, $customHeight, $customBoundary, $buttonText, $dis, $customTextofTitle, $customSize, $selected_theme, $custom_app, $html, $view, $customBackground,$app_dis);
                                }


                                if (get_option('mo_openid_vkontakte_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$vkontakte_custom_app;
                                    $html=$this->add_apps("vk","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }

                                if (get_option('mo_openid_twitter_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$twitter_custom_app;
                                    $html=$this->add_apps("twitter","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }

                                if (get_option('mo_openid_linkedin_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$linkedin_custom_app;
                                    $html=$this->add_apps("linkin","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_instagram_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$instagram_custom_app;
                                    $html=$this->add_apps("insta","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_amazon_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$amazon_custom_app;
                                    $html=$this->add_apps("amazon","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_salesforce_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$salesforce_custom_app;
                                    $html=$this->add_apps("sforce","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_windowslive_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$windowslive_custom_app;
                                    $html=$this->add_apps("wlive","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_yahoo_enable')) {

                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$yahoo_custom_app;
                                    $html=$this->add_apps("yahoo","default",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                            }
                        }

                        if($customTheme == 'custom'){
                            $count=-1;
                            if($apps!="")
                            {
                                foreach ($apps as $select_apps)
                                {
                                    $app_dis="";
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    switch ($select_apps)
                                    {
                                        case 'fb':
                                            $custom_app=$facebook_custom_app;
                                            $custom_app=="false"?$app_dis="disable":$app_dis="";
                                            $html=$this->add_apps("fb","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;

                                        case 'google':
                                            $custom_app=$google_custom_app;
                                            $html=$this->add_apps("google","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'vk':
                                            $custom_app=$vkontakte_custom_app;
                                            $html=$this->add_apps("vk","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'twitter':
                                            $custom_app=$twitter_custom_app;
                                            $html=$this->add_apps("twitter","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'linkin':
                                            $custom_app=$linkedin_custom_app;
                                            $html=$this->add_apps("linkin","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'insta':
                                            $custom_app=$instagram_custom_app;
                                            $html=$this->add_apps("insta","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'amazon':
                                            $custom_app=$amazon_custom_app;
                                            $html=$this->add_apps("amazon","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'sforce':
                                            $custom_app=$salesforce_custom_app;
                                            $html=$this->add_apps("sforce","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'wlive':
                                            $custom_app=$windowslive_custom_app;
                                            $html=$this->add_apps("wlive","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                        case 'yahoo':
                                            $custom_app=$yahoo_custom_app;
                                            $html=$this->add_apps("yahoo","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                            break;
                                    }
                                }
                            }
                            else
                            {
                                if (get_option('mo_openid_facebook_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$facebook_custom_app;
                                    $html=$this->add_apps("fb","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }

                                if (get_option('mo_openid_google_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$google_custom_app;
                                    $html=$this->add_apps("google","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }

                                if (get_option('mo_openid_vkontakte_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$vkontakte_custom_app;
                                    $html=$this->add_apps("vk","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }

                                if (get_option('mo_openid_twitter_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$twitter_custom_app;
                                    $html=$this->add_apps("twitter","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_linkedin_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$linkedin_custom_app;
                                    $html=$this->add_apps("linkin","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_instagram_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$instagram_custom_app;
                                    $html=$this->add_apps("insta","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_amazon_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$amazon_custom_app;
                                    $html=$this->add_apps("amazon","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_salesforce_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$salesforce_custom_app;
                                    $html=$this->add_apps("sforce","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_windowslive_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$windowslive_custom_app;
                                    $html=$this->add_apps("wlive","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                                if (get_option('mo_openid_yahoo_enable')) {
                                    if ($selected_theme == 'longbutton') {
                                        if ($view == "horizontal" && isset($appcnt)) {
                                            $count++;
                                            if ($count == $appcnt) {
                                                $html .= "<br/>";
                                                $count = 0;
                                            }
                                        }
                                    }
                                    $custom_app=$yahoo_custom_app;
                                    $html=$this->add_apps("yahoo","custom",$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view, $customBackground,$app_dis);
                                }
                            }
                        }

                        $html .= '</div> <br>';

                    } else {

                        $html .= '<div>No apps configured. Please contact your administrator.</div>';

                    }
                    if ($appsConfigured && get_option('moopenid_logo_check') == 1) {
                        $logo_html = $this->mo_openid_customize_logo();
                        $html .= $logo_html;
                    }
                    ?>
                    <?php
                } else {
                    global $current_user;
                    $current_user = wp_get_current_user();
                    $customLogoutName = str_replace('##username##', $current_user->display_name, $customLogoutName);
                    $flw = __($customLogoutLink, "flw");
                    if (empty($customLogoutName) || empty($customLogoutLink)) {
                        $html .= '<div id="logged_in_user" class="mo_openid_login_wid">' . $customLogoutName . ' <a href=' . $logoutUrl . ' title=" ' . $flw . '"> ' . $flw . '</a></div>';
                    } else {
                        $html .= '<div id="logged_in_user" class="mo_openid_login_wid">' . $customLogoutName . ' <a href=' . $logoutUrl . ' title=" ' . $flw . '"> ' . $flw . '</a></div>';
                    }
                }
                return $html;
            }
        }

        public function add_apps($app_name,$theme,$gdpr_setting,$spacebetweenicons,$customWidth,$customHeight,$customBoundary,$buttonText,$dis,$customTextofTitle,$customSize,$selected_theme,$custom_app,$html,$view,$customBackground,$app_dis)
        {
            if($theme=="default")
            {
                if($app_name=="fb")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow' " . $gdpr_setting . " style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-facebook btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-facebook btn-custom-dec login-button'";
                        }
                        if($app_dis!="disable")
                            $html .= "onClick=\"moOpenIdLogin('facebook','" . $custom_app . "');\"";
                        $html .= "> <svg xmlns=\"http://www.w3.org/2000/svg\" style=\"padding-top:" . ($customHeight - 30) . "px;border-right:none;margin-left: 2%;\" ><path fill=\"#fff\" d=\"M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z\"/></svg>" . $buttonText . " Facebook</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Facebook'"; if($app_dis!="disable") $html.=" onClick=\"moOpenIdLogin('facebook','" . $custom_app . "');\""; $html.="><img alt='Facebook' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/facebook.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="google")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow' " . $gdpr_setting . " style='margin-left: " . $spacebetweenicons . "px !important;background: rgb(255,255,255)!important; background:linear-gradient(90deg, rgba(255,255,255,1) 38px, rgb(79, 113, 232) 5%) !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;border-color: rgba(79, 113, 232, 1);border-bottom-width: thin;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-google btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-google btn-custom-dec login-button'";
                        }
                        $html .= "onClick=\"moOpenIdLogin('google','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 29) . "px !important;border-right:none;' class='fa'><svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" viewBox=\"0 0 70 70\" style=\"padding-left: 8%;\" ><defs><path id=\"a\" d=\"M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z\"/></defs><clipPath id=\"b\"><use xlink:href=\"#a\" overflow=\"visible\"/></clipPath><path clip-path=\"url(#b)\" fill=\"#FBBC05\" d=\"M0 37V11l17 13z\"/><path clip-path=\"url(#b)\" fill=\"#EA4335\" d=\"M0 11l17 13 7-6.1L48 14V0H0z\"/><path clip-path=\"url(#b)\" fill=\"#34A853\" d=\"M0 37l30-23 7.9 1L48 0v48H0z\"/><path clip-path=\"url(#b)\" fill=\"#4285F4\" d=\"M48 48L17 24l-4-3 35-10z\"/></svg></i><span style=\"color:#ffffff;\">" . $buttonText . " Google</span></a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' onClick=\"moOpenIdLogin('google','" . $custom_app . "');\" title= ' " . $customTextofTitle . " Google'><img alt='Google' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/google.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="vk")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow' " . $gdpr_setting . "  style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-vk btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-vk btn-custom-dec login-button'";
                        }
                        $html .= "onClick=\"moOpenIdLogin('vkontakte','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-vk'></i>" . $buttonText . " Vkontakte</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' onClick=\"moOpenIdLogin('vkontakte','" . $custom_app . "');\" title= ' " . $customTextofTitle . " Vkontakte'><img alt='Vkontakte' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/vk.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="twitter")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'  " . $gdpr_setting . " style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-twitter btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-twitter btn-custom-dec login-button'";
                        }
                        $html .= "onClick=\"moOpenIdLogin('twitter','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-twitter'></i>" . $buttonText . " Twitter</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Twitter' onClick=\"moOpenIdLogin('twitter','" . $custom_app . "');\" ><img alt='Twitter' style=' width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/twitter.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="linkin")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow'  " . $gdpr_setting . "style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-linkedin btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-linkedin btn-custom-dec login-button'";
                        }
                        $html .= "onClick=\"moOpenIdLogin('linkedin','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-linkedin'></i>" . $buttonText . " LinkedIn</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " LinkedIn' onClick=\"moOpenIdLogin('linkedin','" . $custom_app . "');\" ><img alt='LinkedIn' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/linkedin.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="insta")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'  " . $gdpr_setting . " style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-instagram btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-instagram btn-custom-dec login-button'";
                        }
                        $html .= "onClick=\"moOpenIdLogin('instagram','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-instagram'></i>" . $buttonText . " Instagram</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' " . $customTextofTitle . " Instagram' onClick=\"moOpenIdLogin('instagram','" . $custom_app . "');\" ><img alt='Instagram' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/instagram.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="amazon")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow'  " . $gdpr_setting . "style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-soundcloud btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-soundcloud btn-custom-dec login-button'";
                        }
                        $html .= "onClick=\"moOpenIdLogin('amazon','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-amazon'></i>" . $buttonText . " Amazon</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Amazon' onClick=\"moOpenIdLogin('amazon','" . $custom_app . "');\" ><img alt='Amazon' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/amazon.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="sforce")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'   " . $gdpr_setting . "style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-vimeo btn-custom-dec login-button' ";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-vimeo btn-custom-dec login-button' ";
                        }
                        $html .= "onClick=\"moOpenIdLogin('salesforce','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-cloud'></i>" . $buttonText . " Salesforce</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Salesforce' onClick=\"moOpenIdLogin('salesforce','" . $custom_app . "');\" ><img alt='Salesforce' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/salesforce.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="wlive")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'   " . $gdpr_setting . "style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-microsoft btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-microsoft btn-custom-dec login-button'";
                        }
                        $html .= " onClick=\"moOpenIdLogin('windowslive','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-windows'></i>" . $buttonText . " Microsoft</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow'  title= ' " . $customTextofTitle . " Microsoft' onClick=\"moOpenIdLogin('windowslive','" . $custom_app . "');\" ><img alt='Windowslive' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/windowslive.png', __FILE__) . "' class='" . $dis . " login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
                else if($app_name=="yahoo")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow' " . $gdpr_setting . " style='margin-left: " . $spacebetweenicons . "px !important;width: " . $customWidth . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom: " . ($spacebetweenicons - 5) . "px !important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-yahoo btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-yahoo btn-custom-dec login-button'";
                        }
                        $html .= "onClick=\"moOpenIdLogin('yaahoo','" . $custom_app . "');\"> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-yahoo'></i>" . $buttonText . " Yahoo</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " .
                            $customTextofTitle . " Yahoo' onClick=\"moOpenIdLogin('yaahoo','" . $custom_app . "');\" ><img alt='Yahoo' style='width:" . $customSize . "px !important;height: " . $customSize . "px !important;margin-left: " . ($spacebetweenicons) . "px !important' src='" . plugins_url('includes/images/icons/yahoo.png', __FILE__) . "' class='login-button " . $selected_theme . "' ></a>";
                    }
                    return $html;
                }
            }

            else if($theme=="custom"){
                if($app_name=="fb")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'   " . $gdpr_setting . ""; if($app_dis!="disable") $html.=" onClick=\"moOpenIdLogin('facebook','" . $custom_app . "');\"";$html.=" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-facebook'></i> " . $buttonText . " Facebook</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Facebook'"; if($app_dis!="disable") $html.=" onClick=\"moOpenIdLogin('facebook','" . $custom_app . "');\"";$html.=" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-facebook custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
                else if($app_name=="google") {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'   " . $gdpr_setting . " onClick=\"moOpenIdLogin('google','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-google'></i> " . $buttonText . " Google</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Google' onClick=\"moOpenIdLogin('google','" . $custom_app . "');\" title= ' " . $customTextofTitle . "  Google'><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-google custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
                else if($app_name=="vk") {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'   " . $gdpr_setting . " onClick=\"moOpenIdLogin('vkontakte','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-vk'></i> " . $buttonText . " Vkontakte</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Vkontakte' onClick=\"moOpenIdLogin('vkontakte','" . $custom_app . "');\" title= ' " . $customTextofTitle . "  Vkontakte'><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-vk custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
                else if($app_name=="twitter") {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow'   " . $gdpr_setting . "onClick=\"moOpenIdLogin('twitter','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-twitter'></i> " . $buttonText . " Twitter</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Twitter' onClick=\"moOpenIdLogin('twitter','" . $custom_app . "');\" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-twitter custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
                else if($app_name=="linkin") {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow'   " . $gdpr_setting . "onClick=\"moOpenIdLogin('linkedin','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-linkedin'></i> " . $buttonText . " LinkedIn</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " LinkedIn' onClick=\"moOpenIdLogin('linkedin','" . $custom_app . "');\" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-linkedin custom-login-button  " . $selected_theme . "' ></i></a>";
                    }

                    return $html;
                }
                else if($app_name=="insta") {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow'   " . $gdpr_setting . "onClick=\"moOpenIdLogin('instagram','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-instagram'></i> " . $buttonText . " Instagram</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Instagram' onClick=\"moOpenIdLogin('instagram','" . $custom_app . "');\" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-instagram custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
                else if($app_name=="amazon") {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'   " . $gdpr_setting . " onClick=\"moOpenIdLogin('amazon','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-amazon'></i> " . $buttonText . " Amazon</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow'  title= ' " . $customTextofTitle . " Amazon'  onClick=\"moOpenIdLogin('amazon','" . $custom_app . "');\" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-amazon custom-login-button  " . $selected_theme . "' ></i></a>";
                    }

                    return $html;
                }
                else if($app_name=="sforce")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow'  " . $gdpr_setting . " onClick=\"moOpenIdLogin('salesforce','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-cloud'></i> " . $buttonText . " Salesforce</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Salesforce' onClick=\"moOpenIdLogin('salesforce','" . $custom_app . "');\" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-cloud custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
                else if($app_name=="wlive")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a  rel='nofollow'  " . $gdpr_setting . " onClick=\"moOpenIdLogin('windowslive','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-windows'></i> " . $buttonText . " Microsoft</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow'  title= ' " . $customTextofTitle . " Microsoft' onClick=\"moOpenIdLogin('windowslive','" . $custom_app . "');\" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-windows custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
                else if($app_name=="yahoo")
                {
                    if ($selected_theme == 'longbutton') {
                        $html .= "<a rel='nofollow'   " . $gdpr_setting . " onClick=\"moOpenIdLogin('yaahoo','" . $custom_app . "');\" style='margin-left: " . $spacebetweenicons . "px !important;width:" . ($customWidth) . "px !important;padding-top:" . ($customHeight - 29) . "px !important;padding-bottom:" . ($customHeight - 29) . "px !important;margin-bottom:" . ($spacebetweenicons - 5) . "px !important; background:#" . $customBackground . "!important;border-radius: " . $customBoundary . "px !important;'";
                        if ($view == "horizontal") {
                            $html .= "class='btn btn-mo btn-block-inline btn-social btn-customtheme btn-custom-dec login-button'";
                        } else {
                            $html .= "class='btn btn-mo btn-block btn-social btn-customtheme btn-custom-dec login-button'";
                        }
                        $html .= "> <i style='padding-top:" . ($customHeight - 35) . "px !important' class='fa fa-yahoo'></i> " . $buttonText . " Yahoo</a>";
                    } else {
                        $html .= "<a class='" . $dis . " login-button' rel='nofollow' title= ' " . $customTextofTitle . " Yahoo' onClick=\"moOpenIdLogin('yaahoo','" . $custom_app . "');\" ><i style='margin-top:10px;width:" . $customSize . "px !important;height:" . $customSize . "px !important;margin-left:" . ($spacebetweenicons) . "px !important;background:#" . $customBackground . " !important;font-size: " . ($customSize - 16) . "px !important;'  class='fa btn-mo fa-yahoo custom-login-button  " . $selected_theme . "' ></i></a>";
                    }
                    return $html;
                }
            }

        }


        private function mo_openid_load_login_script() {

            if(!get_option('mo_openid_gdpr_consent_enable')){?>
                <script>
                    jQuery(".btn-mo").prop("disabled",false);
                </script>
            <?php }
            echo '<script src="' . plugins_url( 'includes/js/jquery.cookie.min.js', __FILE__ ) . '" ></script>';
            ?>
            <script type="text/javascript">
                function mo_openid_on_consent_change(checkbox,value){

                    if (value == 0) {
                        jQuery('#mo_openid_consent_checkbox').val(1);
                        jQuery(".btn-mo").attr("disabled",true);
                        jQuery(".login-button").addClass("dis");
                    }
                    else {
                        jQuery('#mo_openid_consent_checkbox').val(0);
                        jQuery(".btn-mo").attr("disabled",false);
                        jQuery(".login-button").removeClass("dis");
                    }
                }

                function moOpenIdLogin(app_name,is_custom_app) {
                    var current_url = window.location.href;
                    var cookie_name = "redirect_current_url";
                    var d = new Date();
                    d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
                    var expires = "expires="+d.toUTCString();
                    document.cookie = cookie_name + "=" + current_url + ";" + expires + ";path=/";

                    <?php
                    if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
                        $http = "https://";
                    } else {
                        $http =  "http://";
                    }
                    ?>
                    var base_url = '<?php echo esc_url(site_url());?>';
                    var request_uri = '<?php echo $_SERVER['REQUEST_URI'];?>';
                    var http = '<?php echo $http;?>';
                    var http_host = '<?php echo $_SERVER['HTTP_HOST'];?>';
                    var default_nonce = '<?php echo wp_create_nonce( 'mo-openid-get-social-login-nonce' ); ?>';
                    var custom_nonce = '<?php echo wp_create_nonce( 'mo-openid-oauth-app-nonce' ); ?>';

                   if(is_custom_app == 'false'){

                        if ( request_uri.indexOf('wp-login.php') !=-1){
                            var redirect_url = base_url + '/?option=getmosociallogin&wp_nonce=' + default_nonce + '&app_name=';


                        }else {
                            var redirect_url = http + http_host + request_uri;
                            if(redirect_url.indexOf('?') != -1){
                                redirect_url = redirect_url +'&option=getmosociallogin&wp_nonce=' + default_nonce + '&app_name=';

                            }
                            else
                            {
                                redirect_url = redirect_url +'?option=getmosociallogin&wp_nonce=' + default_nonce + '&app_name=';

                            }
                        }
                    }
                    else {

                        if ( request_uri.indexOf('wp-login.php') !=-1){
                            var redirect_url = base_url + '/?option=oauthredirect&wp_nonce=' + custom_nonce + '&app_name=';

                        }else {
                            var redirect_url = http + http_host + request_uri;
                            if(redirect_url.indexOf('?') != -1)
                                redirect_url = redirect_url +'&option=oauthredirect&wp_nonce=' + custom_nonce + '&app_name=';
                            else
                                redirect_url = redirect_url +'?option=oauthredirect&wp_nonce=' + custom_nonce + '&app_name=';
                        }

                    }

                    window.location.href = redirect_url + app_name;

                }
            </script>
            <?php
        }
    }

    /**
     * Sharing Widget Horizontal
     *
     */
    class mo_openid_sharing_hor_wid extends WP_Widget {

        public function __construct() {
            parent::__construct(
                'mo_openid_sharing_hor_wid',
                'miniOrange Sharing - Horizontal',
                array(
                    'description' => __( 'Share using horizontal widget. Lets you share with Social Apps like Google, Facebook, LinkedIn, Pinterest, Reddit.', 'flw' ),
                    'customize_selective_refresh' => true,
                )
            );
        }

        public function widget( $args, $instance ) {
            extract( $args );

            echo $args['before_widget'];
            $this->show_sharing_buttons_horizontal();

            echo $args['after_widget'];
        }

        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
            return $instance;
        }

        public function show_sharing_buttons_horizontal(){
            global $post;
            $title = str_replace('+', '%20', urlencode($post->post_title));
            $content=strip_shortcodes( strip_tags( get_the_content() ) );
            $post_content=$content;
            $excerpt = '';
            $landscape = 'horizontal';
            include( plugin_dir_path( __FILE__ ) . 'class-mo-openid-social-share.php');
        }

    }


    /**
     * Sharing Vertical Widget
     *
     */
    class mo_openid_sharing_ver_wid extends WP_Widget {

        public function __construct() {
            parent::__construct(
                'mo_openid_sharing_ver_wid',
                'miniOrange Sharing - Vertical',
                array(
                    'description' => __( 'Share using a vertical floating widget. Lets you share with Social Apps like Google, Facebook, LinkedIn, Pinterest, Reddit.', 'flw' ),
                    'customize_selective_refresh' => true,
                )
            );
        }

        public function widget( $args, $instance ) {
            extract( $args );
            extract( $instance );

            $wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
            $alignment = apply_filters( 'alignment', isset($instance['alignment'])? $instance['alignment'] : 'left');
            $left_offset = apply_filters( 'left_offset', isset($instance['left_offset'])? $instance['left_offset'] : '20');
            $right_offset = apply_filters( 'right_offset', isset($instance['right_offset'])? $instance['right_offset'] : '0');
            $top_offset = apply_filters( 'top_offset', isset($instance['top_offset'])? $instance['top_offset'] : '100');
            $space_icons = apply_filters( 'space_icons', isset($instance['space_icons'])? $instance['space_icons'] : '10');

            echo $args['before_widget'];
            if ( ! empty( $wid_title ) )
                echo $args['before_title'] . $wid_title . $args['after_title'];

            echo "<div class='mo_openid_vertical' style='" .(isset($alignment) && $alignment != '' && isset($instance[$alignment.'_offset']) ? esc_attr($alignment) .': '. ( $instance[$alignment.'_offset'] == '' ? 0 : esc_attr($instance[$alignment.'_offset'] )) .'px;' : '').(isset($top_offset) ? 'top: '. ( $top_offset == '' ? 0 : esc_attr($top_offset) ) .'px;' : '') ."'>";

            $this->show_sharing_buttons_vertical($space_icons);

            echo '</div>';

            echo $args['after_widget'];
        }

        /*Called when user changes configuration in Widget Admin Panel*/
        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
            $instance['alignment'] = $new_instance['alignment'];
            $instance['left_offset'] = $new_instance['left_offset'];
            $instance['right_offset'] = $new_instance['right_offset'];
            $instance['top_offset'] = $new_instance['top_offset'];
            $instance['space_icons'] = $new_instance['space_icons'];
            return $instance;
        }


        public function show_sharing_buttons_vertical($space_icons){
            global $post;
            if($post->post_title) {
                $title = str_replace('+', '%20', urlencode($post->post_title));
            } else {
                $title = get_bloginfo( 'name' );
            }
            $content=strip_shortcodes( strip_tags( get_the_content() ) );
            $post_content=$content;
            $excerpt = '';
            $landscape = 'vertical';

            include( plugin_dir_path( __FILE__ ) . 'class-mo-openid-social-share.php');
        }

        /** Widget edit form at admin panel */
        function form( $instance ) {
            /* Set up default widget settings. */
            $defaults = array('alignment' => 'left', 'left_offset' => '20', 'right_offset' => '0', 'top_offset' => '100' , 'space_icons' => '10');

            foreach( $instance as $key => $value ){
                $instance[ $key ] = esc_attr( $value );
            }

            $instance = wp_parse_args( (array)$instance, $defaults );
            ?>
            <p>
                <script>
                    function moOpenIDVerticalSharingOffset(alignment){
                        if(alignment == 'left'){
                            jQuery('.moVerSharingLeftOffset').css('display', 'block');
                            jQuery('.moVerSharingRightOffset').css('display', 'none');
                        }else{
                            jQuery('.moVerSharingLeftOffset').css('display', 'none');
                            jQuery('.moVerSharingRightOffset').css('display', 'block');
                        }
                    }
                </script>
                <label for="<?php echo esc_attr($this->get_field_id( 'alignment' )); ?>">Alignment</label>
                <select onchange="moOpenIDVerticalSharingOffset(this.value)" style="width: 95%" id="<?php echo esc_attr($this->get_field_id( 'alignment' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'alignment' )); ?>">
                    <option value="left" <?php echo $instance['alignment'] == 'left' ? 'selected' : ''; ?>>Left</option>
                    <option value="right" <?php echo $instance['alignment'] == 'right' ? 'selected' : ''; ?>>Right</option>
                </select>
            <div class="moVerSharingLeftOffset" <?php echo $instance['alignment'] == 'right' ? 'style="display: none"' : ''; ?>>
                <label for="<?php echo esc_attr($this->get_field_id( 'left_offset' )); ?>">Left Offset</label>
                <input style="width: 95%" id="<?php echo esc_attr($this->get_field_id( 'left_offset' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'left_offset' )); ?>" type="text" value="<?php echo $instance['left_offset']; ?>" />px<br/>
            </div>
            <div class="moVerSharingRightOffset" <?php echo $instance['alignment'] == 'left' ? 'style="display: none"' : ''; ?>>
                <label for="<?php echo esc_attr($this->get_field_id( 'right_offset' )); ?>">Right Offset</label>
                <input style="width: 95%" id="<?php echo esc_attr($this->get_field_id( 'right_offset' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'right_offset' )); ?>" type="text" value="<?php echo $instance['right_offset']; ?>" />px<br/>
            </div>
            <label for="<?php echo esc_attr($this->get_field_id( 'top_offset' )); ?>">Top Offset</label>
            <input style="width: 95%" id="<?php echo esc_attr($this->get_field_id( 'top_offset' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'top_offset' )); ?>" type="text" value="<?php echo $instance['top_offset']; ?>" />px<br/>
            <label for="<?php echo esc_attr($this->get_field_id( 'space_icons' )); ?>">Space between icons</label>
            <input style="width: 95%" id="<?php echo esc_attr($this->get_field_id( 'space_icons' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'space_icons' )); ?>" type="text" value="<?php echo $instance['space_icons']; ?>" />px<br/>
            </p>
            <?php
        }

    }

    function mo_openid_start_session() {
        if( !session_id() ) {
            session_start();
        }
    }

    function mo_openid_end_session() {
        session_start();
        session_unset(); //unsets all session variables
    }

    function update_custom_data($user_id)
    {
        $set_cust_field = get_option('mo_openid_custom_field_mapping');
        $cust_reg_val = array();
        foreach ($set_cust_field as $x) {
            $count = 0;
            $a = 1;
            $res = "";
            foreach ($x as $xx => $x_value) {
                if ($count == 0)
                    $predefine = $x_value;
                elseif ($count == 1)
                    $opt_val = $x_value;
                elseif ($count == 2)
                    $field = $x_value;
                elseif ($count == 3)
                    $type = $x_value;
                elseif ($count == 4)
                    $add_field = $x_value;
                $count++;
            }

            if ($opt_val === "other") {
                if ($predefine != "")
                    $field_update = $predefine;
                else
                    $field_update = $field;
            } else {
                if ($predefine != "") {
                    $field_update = $predefine;
                    $field = $predefine;
                } else {
                    $field_update = $opt_val;
                    $field = $opt_val . "_update";
                }
            }
            if ($type != "checkbox") {
                if(get_option('mo_customer_validation_mo_social_login_enable')=="1" && get_option('mo_openid_customised_field_enable') == "1" )
                    array_push($cust_reg_val,[$field_update=>$_POST[$field]]);
                else
                    update_user_meta($user_id, $field_update, $_POST[$field]);
            } else {
                $flag = 0;
                $str_res = explode(";", $add_field);
                foreach ($str_res as $value_genetared) {
                    if (isset($_POST[$field . $a])) {
                        if ($flag != 0)
                            $res = $res . ";";
                        $res = $res . $_POST[$field . $a];
                        $flag++;
                    }
                    $a++;
                }
                if(get_option('mo_customer_validation_mo_social_login_enable')=="1" && get_option('mo_openid_customised_field_enable') == "1" )
                    array_push($cust_reg_val,[$field_update=>$res]);
                else
                    update_user_meta($user_id, $field_update, $res);
            }
        }
        return $cust_reg_val;
    }

    function encrypt_data($data, $key) {
        return base64_encode(openssl_encrypt($data, 'aes-128-ecb', $key, OPENSSL_RAW_DATA));
    }

    function decrypt_data($data, $key) {

        return openssl_decrypt( base64_decode($data), 'aes-128-ecb', $key, OPENSSL_RAW_DATA);

    }

    function mo_openid_login_validate(){

        if((isset($_POST['action'])) && (strpos($_POST['action'], 'delete_social_profile_data') !== false) && isset($_POST['user_id'])){
            // delete first name, last name, user_url and profile_url from usermeta
            $id = sanitize_text_field($_POST['user_id']);
            mo_openid_delete_social_profile($id);
        }

        // ajax call -  custom app over default app
        else if ((isset($_POST['selected_app'])) && (isset($_POST['selected_app_value']))){
            if($_POST['selected_app_value'] == 'true'){
                //if custome app enable
                if($_POST['selected_app']=="facebook") {
                    update_option('mo_openid_facebook_enable',1);
                }
                $option = 'mo_openid_enable_custom_app_' . sanitize_text_field($_POST['selected_app']);
                update_option( $option, '1');
            }
            else{
                //if custome app Disable
                if($_POST['selected_app']=="facebook") {
                    update_option('mo_openid_facebook_enable',0);
                }
                $option = 'mo_openid_enable_custom_app_' . sanitize_text_field($_POST['selected_app']);
                update_option( $option, '0');
            }
            exit;
        }
        
		else if ((isset($_POST['appname'])) && (isset($_POST['test_configuration']))){
            update_option( 'mo_openid_test_configuration', 1);
            exit;
        }
        
		else if( isset($_POST['mo_openid_show_profile_form_nonce']) and isset( $_POST['option'] ) and strpos( $_POST['option'], 'mo_openid_show_profile_form' ) !== false ){
            $nonce = $_POST['mo_openid_show_profile_form_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-user-show-profile-form-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
            } else {
                $last_name = sanitize_text_field($_POST["last_name"]);
                $first_name = sanitize_text_field($_POST["first_name"]);
                $full_name = sanitize_text_field($_POST["user_full_name"]);
                $url = sanitize_text_field($_POST["user_url"]); 
                $user_picture = sanitize_text_field($_POST["user_picture"]); 
                $username_field = sanitize_text_field($_POST['username_field']); 
                $email_field = sanitize_email($_POST['email_field']);
                $decrypted_app_name = sanitize_text_field($_POST["decrypted_app_name"]);
                $decrypted_user_id = sanitize_text_field($_POST["decrypted_user_id"]);
                echo mo_openid_profile_completion_form($last_name, $first_name, $full_name, $url, $user_picture, $username_field, $email_field, $decrypted_app_name, $decrypted_user_id);
                exit;
            }
        }

        else if( isset($_POST['mo_openid_account_linking_nonce']) and isset( $_POST['option'] ) and strpos( $_POST['option'], 'mo_openid_account_linking' ) !== false ){
            $nonce = $_POST['mo_openid_account_linking_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-account-linking-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
            } else {
                mo_openid_start_session();
                //link account
                if(!isset($_POST['mo_openid_create_new_account'])){
                    $nonce = wp_create_nonce( 'mo-openid-disable-social-login-nonce' );
                    $url = site_url().'/wp-login.php?option=disable-social-login&wp_nonce=' . $nonce;
                    header('Location:'. $url);
                    exit;
                }
                //create new account
                else {
                    $username = sanitize_text_field($_POST['username']);
                    $user_email = sanitize_email($_POST['user_email']);
                    $first_name = sanitize_text_field($_POST['first_name']);
                    $last_name = sanitize_text_field($_POST['last_name']);
                    $user_full_name = sanitize_text_field($_POST['user_full_name']);
                    $user_url = sanitize_text_field($_POST['user_url']);
                    $user_picture = sanitize_text_field($_POST['user_picture']);
                    $decrypted_app_name = sanitize_text_field($_POST['decrypted_app_name']);
                    $decrypted_user_id = sanitize_text_field($_POST['decrypted_user_id']);

                    mo_openid_process_account_linking($username, $user_email, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id);
                }
            }
        }

        else if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'getmosociallogin' ) !== false ) {
            if(isset($_REQUEST['wp_nonce'])){
                $nonce = $_REQUEST['wp_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-get-social-login-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    mo_openid_initialize_social_login();
                }
            }
        }

        else if ( isset($_POST['mo_openid_custom_form_submitted_nonce']) and isset($_POST['username']) and $_POST['option'] == 'mo_openid_custom_form_submitted' ){
            $nonce = $_POST['mo_openid_custom_form_submitted_nonce'];
            if ( ! wp_verify_nonce( $nonce, 'mo-openid-custom-form-submitted-nonce' ) ) {
                wp_die('<strong>ERROR</strong>: Invalid Request.' . $nonce);
            } else {
                $curr_user=get_current_user_id();
                if($curr_user!=0) {
                    update_custom_data($curr_user);
                    header("Location:".get_option('profile_completion_page'));
                    exit;
                }
                $user_picture = $_POST["user_picture"];
                $user_url = $_POST["user_url"];
                $last_name = $_POST["last_name"];
                $username=$_POST["username"];
                $user_email=$_POST["user_email"];
                $random_password=$_POST["random_password"];
                $user_full_name = $_POST["user_full_name"];
                $first_name = $_POST["first_name"];
                $decrypted_app_name = $_POST["decrypted_app_name"];
                $decrypted_user_id = $_POST["decrypted_user_id"];
                $call = $_POST["call"];
                $user_profile_url = $_POST["user_profile_url"];
                $social_app_name = $_POST["social_app_name"];
                $social_user_id = $_POST["social_user_id"];

                $userdata = array(
                    'user_login'  => $username,
                    'user_email'    => $user_email,
                    'user_pass'   =>  $random_password,
                    'display_name' => $user_full_name,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'user_url' => $user_url,
                );
                $userdetails = array(
                    'user_login'  =>  $username,
                    'user_email'    =>  $user_email,
                    'user_pass'   =>  $random_password,
                    'display_name' => $user_full_name,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'user_url' => $user_url,
                    'user_profile_url' => $user_profile_url,
                    'user_picture' => $user_picture,
                    'decrypted_app_name' => $decrypted_app_name,
                    'decrypted_user_id' => $decrypted_user_id,
                    'social_app_name' => $social_app_name,
                    'social_user_id' => $social_user_id,
                    'call' => $call
                );
                if(get_option('mo_customer_validation_mo_social_login_enable')=="1" && get_option('mo_openid_customised_field_enable') == "1" )
                    $cust_reg_val=update_custom_data("0");
                do_action("mo_before_insert_user",$userdetails,$cust_reg_val);
                $user_id   = wp_insert_user( $userdata);
                if(is_wp_error( $user_id )) {
                    print_r($user_id);
                    wp_die("Error Code ".$call.": ".get_option('mo_registration_error_message'));
                }
                if($call==1 | $call==3 | $call==5)
                    update_option('mo_openid_user_count',get_option('mo_openid_user_count')+1);
                if($call==1) {
                    mo_openid_start_session();
                }
                if($call==2 | $call==3 | $call==4) {
                    $_SESSION['social_app_name'] = $decrypted_app_name;
                    $_SESSION['user_email'] = $user_email;
                    $_SESSION['social_user_id'] = $decrypted_user_id;
                }
                if($call==5 | $call==6) {
                    mo_openid_start_session();
                    $_SESSION['username'] = $username;
                    $_SESSION['user_email'] = $user_email;
                    $_SESSION['user_full_name'] = $user_full_name;
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['user_url'] = $user_url;
                    $_SESSION['user_picture'] = $user_picture;
                    $_SESSION['social_app_name'] = $social_app_name;
                    $_SESSION['social_user_id'] = $social_user_id;
                }
                $user	= get_user_by('id', $user_id );
                update_custom_data($user_id);
                if(get_option('moopenid_social_login_avatar') && isset($user_picture)){
                    update_user_meta($user_id, 'moopenid_user_avatar', $user_picture);
                }
                $_SESSION['mo_login'] = true;
                do_action( 'mo_user_register', $user_id,$user_profile_url);
                do_action( 'miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
                do_action( 'wp_login', $user->user_login, $user );
                wp_set_auth_cookie( $user_id, true );
                $redirect_url = mo_openid_get_redirect_url();
                wp_redirect($redirect_url);
                exit;
            }
        }

        else if( isset($_POST['mo_openid_profile_form_submitted_nonce']) and isset( $_POST['username_field']) and isset($_POST['email_field']) and $_POST['option'] == 'mo_openid_profile_form_submitted' ){
            $nonce = $_POST['mo_openid_profile_form_submitted_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-profile-form-submitted-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.' . $nonce);
            } else {
                $username = sanitize_text_field($_POST['username_field']);
                $user_email = sanitize_email($_POST['email_field']);
                $user_picture = sanitize_text_field($_POST["user_picture"]);
                $user_url = sanitize_text_field($_POST["user_url"]);
                $last_name = sanitize_text_field($_POST["last_name"]);
                $user_full_name = sanitize_text_field($_POST["user_full_name"]);
                $first_name = sanitize_text_field($_POST["first_name"]);
                $decrypted_app_name = sanitize_text_field($_POST["decrypted_app_name"]);
                $decrypted_user_id = sanitize_text_field($_POST["decrypted_user_id"]);

                mo_openid_save_profile_completion_form($username, $user_email, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id);
            }
        }

        else if( isset($_POST['mo_openid_user_otp_validation_nonce']) and isset( $_POST['otp_field']) and $_POST['option'] == 'mo_openid_otp_validation' ){
            $nonce = $_POST['mo_openid_user_otp_validation_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'mo-openid-user-otp-validation-nonce' ) ) {
				wp_die('<strong>ERROR</strong>: Invalid Request.');
            } else {
                $username = sanitize_text_field($_POST["username_field"]);
                $user_email = sanitize_email($_POST["email_field"]);
                $transaction_id = sanitize_text_field($_POST["transaction_id"]);
                $otp_token = sanitize_text_field($_POST['otp_field']);
                $user_picture = sanitize_text_field($_POST["user_picture"]);
                $user_url = sanitize_text_field($_POST["user_url"]);
                $last_name = sanitize_text_field($_POST["last_name"]);
                $user_full_name = sanitize_text_field($_POST["user_full_name"]);
                $first_name = sanitize_text_field($_POST["first_name"]);
                $decrypted_app_name = sanitize_text_field($_POST["decrypted_app_name"]);
                $decrypted_user_id = sanitize_text_field($_POST["decrypted_user_id"]);

                if(isset($_POST['resend_otp'])) {
                    $send_content = send_otp_token($user_email);
                    if($send_content['status']=='FAILURE'){
                        $message ="Error Code 3: ".get_option('mo_email_failure_message');
                        wp_die($message);
                    }
                    $transaction_id = $send_content['tId'];
                    echo mo_openid_validate_otp_form($username, $user_email, $transaction_id, $user_picture, $user_url,  $last_name, $user_full_name,$first_name, $decrypted_app_name, $decrypted_user_id);

                    exit;
                }

                mo_openid_social_login_validate_otp($username, $user_email, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id, $otp_token, $transaction_id);
            }
        }

        else if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'moopenid' ) !== false ){
            mo_openid_process_social_login();
        }

        else if( isset( $_REQUEST['autoregister'] ) and strpos( $_REQUEST['autoregister'],'false') !== false ) {
            if(!is_user_logged_in()) {
                mo_openid_disabled_register_message();
            }
        }

        else if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'oauthredirect' ) !== false ) {
            if(isset($_REQUEST['wp_nonce'])){
                $nonce = $_REQUEST['wp_nonce'];
                if ( ! wp_verify_nonce( $nonce, 'mo-openid-oauth-app-nonce' ) ) {
                    wp_die('<strong>ERROR</strong>: Invalid Request.');
                } else {
                    $appname = sanitize_text_field($_REQUEST['app_name']);
                    mo_openid_custom_app_oauth_redirect($appname);
                }
            }
        }

        else if( strpos( $_SERVER['REQUEST_URI'], "openidcallback") !== false ||((strpos( $_SERVER['REQUEST_URI'], "oauth_token")!== false)&&(strpos( $_SERVER['REQUEST_URI'], "oauth_verifier") ) )) {
            mo_openid_process_custom_app_callback();
        }        
    }

    function mo_openid_json_to_htmltable($arr) {
        $str = "<table border='1'><tbody>";
        foreach ($arr as $key => $val) {
            $str .= "<tr>";
            $str .= "<td>$key</td>";
            $str .= "<td>";
            if (is_array($val)) {
                if (!empty($val)) {
                    $str .= mo_openid_json_to_htmltable($val);
                }
            } else {
                $str .= "<strong>$val</strong>";
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";

        return $str;
    }

    function mo_openid_validate_otp_form($username, $user_email, $transaction_id, $user_picture, $user_url, $last_name, $user_full_name ,$first_name, $decrypted_app_name, $decrypted_user_id,$message = ''){
        $path = mo_openid_get_wp_style();
        $nonce = wp_create_nonce( 'mo-openid-user-otp-validation-nonce' );
        if($message=='')
            $message=get_option('mo_email_verify_message');
        else
            $message=get_option('mo_email_verify_wrong_otp');

        $html =         '
						<style>
                            .mocomp {
                                         margin: auto !important;
                                     }
                            @media only screen and (max-width: 600px) {
                              .mocomp {width: 90%;}
                            }
                            @media only screen and (min-width: 600px) {
                              .mocomp {width: 500px;}
                            }
                        </style>
						<head>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
						<link rel="stylesheet" href='.$path.' type="text/css" media="all" /></head>
          
                        <body class="login login-action-login wp-core-ui  locale-en-us">
                        <div style="position:fixed;background:#f1f1f1;"></div>
                        <div id="add_field" style="position:fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:130px;">
                        <div class="mocomp">   
                        <form name="f" method="post" action="">
                        <div style="background: white;margin-top:-15px;padding: 15px;">
                       
                        <div style="text-align:center"><span style="font-size: 24px;font-family: Arial">'.get_option('mo_email_verify_title').'</span></div><br>
                        <div style="padding: 12px;"></div>
                        <div style=" padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;">
                        <span style=" margin-left: 15px;color: black;font-weight: bold;float: right;font-size: 22px;line-height: 20px;cursor: pointer;font-family: Arial;transition: 0.3s"></span>'.$message.'</div>	<br>					
                        <p>
                        <label for="user_login">'.esc_html(get_option('mo_email_verify_verification_code_instruction')).'<br/>
                        <input type="text" pattern="\d{4,5}" class="input" name="otp_field" value=""  size="20" /></label>
                        </p>
                        <input type="hidden" name="username_field" value='.esc_attr($username).'>
                        <input type="hidden" name="email_field" value='.esc_attr($user_email).'>						
                        <input type="hidden" name="first_name" value='.esc_attr($first_name).'>
                        <input type="hidden" name="last_name" value='.esc_attr($last_name).'>
                        <input type="hidden" name="user_full_name" value='.esc_attr($user_full_name).'>
                        <input type="hidden" name="user_url" value='.esc_url($user_url).'>
                        <input type="hidden" name="user_picture" value='.esc_url($user_picture).'>
                        <input type="hidden" name="transaction_id" value='.esc_attr($transaction_id).'>
                        <input type="hidden" name="decrypted_app_name" value='.esc_attr($decrypted_app_name).'>
                        <input type="hidden" name="decrypted_user_id" value='.esc_attr($decrypted_user_id).'>				
                        <input type="hidden" name="option" value="mo_openid_otp_validation">
                        <input type="hidden" name="mo_openid_user_otp_validation_nonce" value="'.$nonce.'"/>
                        </div>
                        <div style="float: right;margin-right: 11px;">  
                        <input type="submit" style="margin-left:10px"  name="wp-submit" id="wp-submit" class="button button-primary button-large" value="'.get_option('mo_profile_complete_submit_button').'"/>

                        </div>
                        <div style="float: right">
                        <input type="button"  style="margin-left:10px" onclick="show_profile_completion_form()" name="otp_back" id="back" class="button button-primary button-large" value="'.get_option('mo_email_verify_back_button').'"/>&emsp;
                        <input type="submit" name="resend_otp" id="resend_otp" class="button button-primary button-large" value="'.get_option('mo_email_verify_resend_otp_button').'"/>
                        </div>';

        if(get_option('mo_openid_oauth')=='1' && get_option('moopenid_logo_check') == 1) {
            $html .= mo_openid_customize_logo();
        }
        $nonce = wp_create_nonce( 'mo-openid-user-show-profile-form-nonce' );
        $html.=    '</form>
                    <form style="display:none;" name="go_back_form" id="go_back_form" method="post">
                    <input hidden name="option" value="mo_openid_show_profile_form"/>
                    <input type="hidden" name="mo_openid_show_profile_form_nonce" value="'.$nonce.'"/>
                    <input type="hidden" name="username_field" value='.esc_attr($username).'>
                    <input type="hidden" name="email_field" value='.esc_attr($user_email).'>						
                    <input type="hidden" name="first_name" value='.esc_attr($first_name).'>
                    <input type="hidden" name="last_name" value='.esc_attr($last_name).'>
                    <input type="hidden" name="user_full_name" value='.esc_attr($user_full_name).'>
                    <input type="hidden" name="user_url" value='.esc_url($user_url).'>
                    <input type="hidden" name="user_picture" value='.esc_url($user_picture).'>
                    <input type="hidden" name="transaction_id" value='.esc_attr($transaction_id).'>
                    <input type="hidden" name="decrypted_app_name" value='.esc_attr($decrypted_app_name).'>
                    <input type="hidden" name="decrypted_user_id" value='.esc_attr($decrypted_user_id).'>
                    </form>
                    </div>
                    </div>
                    </body>
                    <script>
                    function show_profile_completion_form(){
                        document.getElementById("go_back_form").submit(); 
                    }     
                    </script>';
        return $html;
    }

function mo_openid_profile_completion_form($last_name,$first_name,$user_full_name,$user_url,$user_picture, $decrypted_user_name, $decrypted_email, $decrypted_app_name, $decrypted_user_id,$existing_uname='1'){
    $path = mo_openid_get_wp_style();
    if($existing_uname=='1')
    {
        $instruction_msg=esc_html(get_option('mo_profile_complete_instruction'));
        $extra_instruction=esc_html(get_option('mo_profile_complete_extra_instruction'));
    }
    else{
        $instruction_msg=esc_html(get_option('mo_profile_complete_uname_exist'));
        $extra_instruction="";
    }
    $nonce = wp_create_nonce( 'mo-openid-profile-form-submitted-nonce' );
    $html =     '<style>.form-input-validation.note {color: #d94f4f;}</style>
					<style>
                        .mocomp {
                                     margin: auto !important;
                                 }
                        @media only screen and (max-width: 600px) {
                          .mocomp {width: 90%;}
                        }
                        @media only screen and (min-width: 600px) {
                          .mocomp {width: 500px;}
                        }
                    </style>
					
                    <head>
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<link rel="stylesheet" href='.$path.' type="text/css" media="all" /></head>      
                    <body class="login login-action-login wp-core-ui  locale-en-us">
                    <div style="position:fixed;background:#f1f1f1;"></div>
                    <div id="add_field" style="position:fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:70px;">
                    <div class="mocomp">   
                    <form name="f" method="post" action="">
                    <div style="background: white;margin-top:-15px;padding: 15px;">
                   
                   <div style="text-align:center"><span style="font-size: 24px;font-family: Arial">'.esc_html(get_option('mo_profile_complete_title')).'</span></div><br>
                   <div style="padding: 12px;"></div>
                    <div style=" padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;">
                    <span style=" margin-left: 15px;color: black;font-weight: bold;float: right;font-size: 22px;line-height: 20px;cursor: pointer;font-family: Arial;transition: 0.3s"></span>'.$instruction_msg.'</div><br>					
                    <p>
                    <label for="user_login">'.esc_html(get_option('mo_profile_complete_username_label')).'<br/>
                    <input type="text" class="input" name="username_field"  size="20" required value='.esc_attr($decrypted_user_name).'></label>
                    </p>
                    <p>
                    <label for="user_pass">'.esc_html(get_option('mo_profile_complete_email_label')).'<br/>
                    <input type="email"  class="input" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]+$" name="email_field"  size="20"  required value='.esc_attr($decrypted_email).'></label>
                    <span align="center" class="form-input-validation note">'.$extra_instruction.'</span>
                    </p>
                    <input type="hidden" name="first_name" value='.esc_attr($first_name).'>
                    <input type="hidden" name="last_name" value='.esc_attr($last_name).'>
                    <input type="hidden" name="user_full_name" value='.esc_attr($user_full_name).'>
                    <input type="hidden" name="user_url" value='.esc_url($user_url).'>
                    <input type="hidden" name="user_picture" value='.esc_url($user_picture).'>
                    <input type="hidden" name="decrypted_app_name" value='.esc_attr($decrypted_app_name).'>
                    <input type="hidden" name="decrypted_user_id" value='.esc_attr($decrypted_user_id).'>
                    <input type="hidden" name="option" value="mo_openid_profile_form_submitted">
                    <input type="hidden" name="mo_openid_profile_form_submitted_nonce" value="'.$nonce.'"/>
                    </div>
                    <p class="submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="'.get_option('mo_profile_complete_submit_button').'"/>
                    </p> ';

    if(get_option('mo_openid_oauth')=='1' && get_option('moopenid_logo_check') == 1) {
        $html .=    mo_openid_customize_logo();
    }

    $html.=     '</form>
                    </div>
                    </div>
                    </body>';
    return $html;
}

function mo_openid_account_linking_form($username,$user_email,$first_name,$last_name,$user_full_name,$user_url,$user_picture,$decrypted_app_name,$decrypted_user_id){
        $path = mo_openid_get_wp_style();
        $nonce = wp_create_nonce( 'mo-openid-account-linking-nonce' );
        $html =	"
		        <style>
                    .mocomp {
                                 margin: auto !important;
                             }
                    @media only screen and (max-width: 600px) {
                      .mocomp {width: 90%;}
                    }
                    @media only screen and (min-width: 600px) {
                      .mocomp {width: 500px;}
                    }
                </style>
				<head>
				<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
				<link rel=\"stylesheet\" href=".$path. " type='text/css' media='all' /><head>
                <body class='login login-action-login wp-core-ui  locale-en-us'>
                <div style=\"background:#f1f1f1;\"></div>
                <div id=\"add_field\" style=\"top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:2%;\">
                <div class='mocomp'>
                <form name = 'f' method = 'post' action='' style='margin-left: 2%;margin-right: 2%;'>
                <input type = 'hidden' name = 'option' value = 'mo_openid_account_linking'/>
                <input type='hidden' name='mo_openid_account_linking_nonce' value='". $nonce."'/>
                <input type='hidden' name='user_email' value=".esc_attr($user_email).">
                <input type='hidden' name='username' value=".esc_attr($username).">
                <input type='hidden' name='first_name' value=".esc_attr($first_name).">
                <input type='hidden' name='last_name' value=".esc_attr($last_name).">
                <input type='hidden' name='user_full_name' value=".esc_attr($user_full_name).">
                <input type='hidden' name='user_url' value=".esc_url($user_url).">
                <input type='hidden' name='user_picture' value=".esc_url($user_picture).">
                <input type='hidden' name='decrypted_app_name' value=".esc_attr($decrypted_app_name).">
                <input type='hidden' name='decrypted_user_id' value=".esc_attr($decrypted_user_id).">
                <div  style = 'background-color:white; padding:12px; top:100px; right: 350px; padding-bottom: 20px;left:350px; overflow:hidden; outline:1px black;border-radius: 5px;'>	
                
                <br>
                <div style=\"text-align:center\"><span style='font-size: 24px;font-family: Arial;text-align:center'>".esc_html(get_option('mo_account_linking_title'))."</span></div><br>
                <div style='padding: 12px;'></div>
                <div style=' padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;'>".get_option('mo_account_linking_new_user_instruction').".<br><br>".get_option('mo_account_linking_existing_user_instruction')."".get_option('mo_account_linking_extra_instruction')." 
                </div>                   
                <br><br>

                <input type = 'submit' value = '".esc_attr(get_option('mo_account_linking_existing_user_button'))."' name = 'mo_openid_link_account' class='button button-primary button-large' style = 'margin-left: 3%;margin-right: 0%;'/>
                    
                <input type = 'submit' value = '".esc_attr(get_option('mo_account_linking_new_user_button'))."' name = 'mo_openid_create_new_account' class='button button-primary button-large' style = 'margin-left: 5%margin-right: 5%;'/>";

        if(get_option('mo_openid_oauth')=='1' && get_option('moopenid_logo_check') == 1) {
            $html .= mo_openid_customize_logo();
        }

        $html .=   "</div>
                    </form>
                    </div>
                    </div>
                    </body>";
        return $html;
    }

    function mo_openid_decrypt_sanitize($param) {
        if(strcmp($param,'null')!=0 && strcmp($param,'')!=0){
            $customer_token = get_option('mo_openid_customer_token');
            $decrypted_token = decrypt_data($param,$customer_token);
            // removes control characters and some blank characters
            $decrypted_token_sanitise = preg_replace('/[\x00-\x1F][\x7F][\x81][\x8D][\x8F][\x90][\x9D][\xA0][\xAD]/', '', $decrypted_token);
            //strips space,tab,newline,carriage return,NUL-byte,vertical tab.
            return trim($decrypted_token_sanitise);
        }else{
            return '';
        }
    }

    function mo_openid_link_account( $username, $user ){

        if($user){
            $userid = $user->ID;
        }
        mo_openid_start_session();

        $user_email =  isset($_SESSION['user_email']) ? sanitize_text_field($_SESSION['user_email']):'';
        $social_app_identifier = isset($_SESSION['social_user_id']) ? sanitize_text_field($_SESSION['social_user_id']):'';
        $social_app_name = isset($_SESSION['social_app_name']) ? sanitize_text_field($_SESSION['social_app_name']):'';

        //if user is coming through default wordpress login, do not proceed further and return
        if(isset($userid) && empty($social_app_identifier) && empty($social_app_name) ) {
            return;
        }
        elseif(!isset($userid)){
            return;
            //wp_die('No user is returned.');
        }

        global $wpdb;
        $db_prefix = $wpdb->prefix;
        $linked_email_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM ".$db_prefix."mo_openid_linked_user where linked_email = \"%s\" AND linked_social_app = \"%s\"",$user_email,$social_app_name));

        // if a user with given email and social app name doesn't already exist in the mo_openid_linked_user table
        if(!isset($linked_email_id)){
            mo_openid_insert_query($social_app_name,$user_email,$userid,$social_app_identifier);
        }
    }

    function mo_openid_insert_query($social_app_name,$user_email,$userid,$social_app_identifier){

        // check if none of the column values are empty
        if(!empty($social_app_name) && !empty($user_email) && !empty($userid) && !empty($social_app_identifier)){

            date_default_timezone_set('Asia/Kolkata');
            $date = date('Y-m-d H:i:s');

            global $wpdb;
            $db_prefix = $wpdb->prefix;
            $table_name = $db_prefix. 'mo_openid_linked_user';

            $result = $wpdb->insert(
                $table_name,
                array(
                    'linked_social_app' => $social_app_name,
                    'linked_email' => $user_email,
                    'user_id' =>  $userid,
                    'identifier' => $social_app_identifier,
                    'timestamp' => $date,
                ),
                array(
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%s'
                )
            );
            if($result === false){
                wp_die('Error in insert query');
                $wpdb->show_errors();
                $wpdb->print_error();
                exit;
            }
        }
    }

    function mo_openid_send_email($user_id='', $user_url=''){
        if( get_option('mo_openid_email_enable') == 1) {
            global $wpdb;
            $admin_mail = get_option('mo_openid_admin_email');
            $user_name = ($user_id == '') ? "##UserName##" : ($wpdb->get_var($wpdb->prepare("SELECT user_login FROM {$wpdb->users} WHERE ID = %d", $user_id)));
            $content = get_option('mo_openid_register_email_message');
            $subject = "[" . get_bloginfo('name') . "] New User Registration - Social Login";
            $content = str_replace('##User Name##', $user_name, $content);
            $headers = "Content-Type: text/html";
            wp_mail($admin_mail, $subject, $content, $headers);
        }
    }

    function mo_openid_disabled_register_message() {
        $message = get_option('mo_openid_register_disabled_message').' Go to <a href="' . site_url() .'">Home Page</a>';
        wp_die($message);
    }

    function mo_openid_get_redirect_url() {

        $current_url = isset($_COOKIE["redirect_current_url"]) ? $_COOKIE["redirect_current_url"]:'';
        $pos = strpos($_SERVER['REQUEST_URI'], '/openidcallback');

        if ($pos === false) {
            $url = str_replace('?option=moopenid','',$_SERVER['REQUEST_URI']);
            $current_url = str_replace('?option=moopenid','',$current_url);

        } else {
            $temp_array1 = explode('/openidcallback',$_SERVER['REQUEST_URI']);
            $url = $temp_array1[0];
            $temp_array2 = explode('/openidcallback',$current_url);
            $current_url = $temp_array2[0];
        }

        $option = get_option( 'mo_openid_login_redirect' );
        $redirect_url = site_url();

        if( $option == 'same' ) {
            if(!is_null($current_url)){
                if(strpos($current_url,get_option('siteurl').'/wp-login.php')!== false)
                {
                    $redirect_url=get_option('siteurl');
                }
                    else
                        $redirect_url = $current_url;
            }
            else{
                if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
                    $http = "https://";
                } else {
                    $http =  "http://";
                }
                $redirect_url = urldecode(html_entity_decode(esc_url($http . $_SERVER["HTTP_HOST"] . $url)));
                if(html_entity_decode(esc_url(remove_query_arg('ss_message', $redirect_url))) == wp_login_url() || strpos($_SERVER['REQUEST_URI'],'wp-login.php') !== FALSE || strpos($_SERVER['REQUEST_URI'],'wp-admin') !== FALSE){
                    $redirect_url = site_url().'/';
                }
            }
        } else if( $option == 'homepage' ) {
            $redirect_url = site_url();
        } else if( $option == 'dashboard' ) {
            $redirect_url = admin_url();
        } else if( $option == 'custom' ) {
            $redirect_url = get_option('mo_openid_login_redirect_url');
        }else if($option == 'relative') {
            $redirect_url =  site_url() . (null !== get_option('mo_openid_relative_login_redirect_url')?get_option('mo_openid_relative_login_redirect_url'):'');
        }

        if(strpos($redirect_url,'?') !== FALSE) {
            $redirect_url .= get_option('mo_openid_auto_register_enable') ? '' : '&autoregister=false';
        } else{
            $redirect_url .= get_option('mo_openid_auto_register_enable') ? '' : '?autoregister=false';
        }
        return $redirect_url;
    }

    function mo_openid_redirect_after_logout($logout_url) {
        if(get_option('mo_openid_logout_redirection_enable')){
            $logout_redirect_option = get_option( 'mo_openid_logout_redirect' );
            $redirect_url = site_url();
            if( $logout_redirect_option == 'homepage' ) {
                $redirect_url = $logout_url . '&redirect_to=' .home_url()  ;
            }
            else if($logout_redirect_option == 'currentpage'){
                if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
                    $http = "https://";
                } else {
                    $http =  "http://";
                }
                $redirect_url = $logout_url . '&redirect_to=' . $http . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
            }
            else if($logout_redirect_option == 'login') {
                $redirect_url = $logout_url . '&redirect_to=' . site_url() . '/wp-admin' ;
            }
            else if($logout_redirect_option == 'custom') {
                $redirect_url = $logout_url . '&redirect_to=' . site_url() . (null !== get_option('mo_openid_logout_redirect_url')?get_option('mo_openid_logout_redirect_url'):'');
            }
            return $redirect_url;
        }else{
            return $logout_url;
        }

    }

    function mo_openid_login_redirect($username = '', $user = NULL){
        mo_openid_start_session();
        if(is_string($username) && $username && is_object($user) && !empty($user->ID) && ($user_id = $user->ID) && isset($_SESSION['mo_login']) && $_SESSION['mo_login']){
            $_SESSION['mo_login'] = false;
            wp_set_auth_cookie( $user_id, true );
            $redirect_url = mo_openid_get_redirect_url();

            wp_redirect($redirect_url);
            exit;
        }
    }

    function send_otp_token($email){
        $otp = wp_rand(1000,99999);
        $customerKey = get_option('mo_openid_admin_customer_key');
        $stringToHash = $customerKey . $otp;
        $transactionId = hash("sha512", $stringToHash);
        //wp_email function will come here
        $subject= '['.get_bloginfo('name').'] Verify your email';

        $message=str_replace('##otp##', $otp, get_option('custom_otp_msg'));

        $response = wp_mail($email, $subject,$message);
        if($response){
            mo_openid_start_session();
            $_SESSION['mo_otptoken'] = true;
            $_SESSION['sent_on'] = time();
            $content = array('status' => 'SUCCESS','tId' => $transactionId);
        }
        else
            $content = array('status' => 'FAILURE');
        return $content;
    }

    function validate_otp_token($transactionId,$otpToken){
        mo_openid_start_session();
        $customerKey = get_option('mo_openid_admin_customer_key');
        if($_SESSION['mo_otptoken']){
            $pass =	checkTimeStamp($_SESSION['sent_on'],time());
            $pass = checkTransactionId($customerKey, $otpToken, $transactionId, $pass);
            if($pass)
                $content = array('status' => 'SUCCESS');
            else
                $content = array('status' => 'FAILURE');
            unset($_SESSION['$mo_otptoken']);
        }
        else
            $content = array('status' =>'FAILURE');

        return $content;
    }

    /*
    * This function checks the time otp was sent to and the time
         * user is validating the otp. The time difference shouldn't be
    * more that 60 seconds.
    *
    * @param $sentTime - the time otp was sent to
    * @param $validatedTime - the time otp was validated
    */

    function checkTimeStamp($sentTime,$validatedTime){
        $diff 		= round(abs($validatedTime - $sentTime) / 60,2);
        if($diff>5)
            return false;
        else
            return true;
    }

    /**
     * This function checks and compares the transaction set in session
     * and one generated during validation. Both need to match for the
     * otp to be validated.
     *
     * @param $customerKey - the customer key of the user
     * @param $otpToken - otp token entered by the user
     * @param $transactionId - the transaction id in session
     * @param $pass - the boolean value passed after the time check
     */

    function checkTransactionId($customerKey,$otpToken,$transactionId,$pass){
        if(!$pass){
            return false;
        }
        $stringToHash 	= $customerKey . $otpToken;
        $txtID 		= hash("sha512", $stringToHash);
        if($txtID == $transactionId)
            return true;
    }

    function mo_openid_filter_app_name($decrypted_app_name)
    {
        $decrypted_app_name = strtolower($decrypted_app_name);
        $split_app_name = explode('_', $decrypted_app_name);
        //check to ensure login starts at the click of social login button
        if(empty($split_app_name[0])){
            wp_die(get_option('mo_manual_login_error_message'));
        }
        else {
            return $split_app_name[0];
        }
    }

    function mo_openid_account_linking($messages) {
        if(isset( $_GET['option']) && $_GET['option'] == 'disable-social-login' ){
            $messages = '<p class="message">'.get_option('mo_account_linking_message').'</p>';
        }
        return $messages;
    }

    function mo_openid_customize_logo(){
        $logo =" <div style='float:left;' class='mo_image_id'>
			<a target='_blank' href='https://www.miniorange.com/'>
			<img alt='logo' src='". plugins_url('/includes/images/miniOrange.png',__FILE__) ."' class='mo_openid_image'>
			</a>
			</div>
			<br/>";
        return $logo;
    }

    //delete rows from account linking table that correspond to deleted user
    function mo_openid_delete_account_linking_rows($user_id){
        global $wpdb;
        $db_prefix = $wpdb->prefix;
        $result = $wpdb->get_var($wpdb->prepare("DELETE from ".$db_prefix."mo_openid_linked_user where user_id = %s ",$user_id));
        if($result === false){
            wp_die(get_option('mo_delete_user_error_message'));
            $wpdb->show_errors();
            $wpdb->print_error();
            exit;
        }
    }

    function mo_openid_update_role($user_id='', $user_url=''){
        // save the profile url in user meta // this was added to save facebook url in user meta as it is more than 100 chars
        update_user_meta($user_id, 'moopenid_user_profile_url',$user_url);
        $user = get_user_by('ID',$user_id);
		if(get_option('mo_openid_login_role_mapping') && !(empty($user)) ){
			$user->set_role( get_option('mo_openid_login_role_mapping') );
		}
    }

    function mo_openid_get_wp_style(){
        $path = site_url();
        $path .= '/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load%5B%5D=dashicons,buttons,forms,l10n,login&amp;ver=4.8.1';
        return $path;
    }

    function mo_openid_delete_profile_column($value, $columnName, $userId){
        if('mo_openid_delete_profile_data' == $columnName){
            global $wpdb;
            $socialUser = $wpdb->get_var($wpdb->prepare('SELECT id FROM '. $wpdb->prefix .'mo_openid_linked_user WHERE user_id = %d ', $userId));
            if($socialUser > 0 && !get_user_meta($userId,'mo_openid_data_deleted')){
                return '<a href="javascript:void(0)" onclick="javascript:moOpenidDeleteSocialProfile(this, '. $userId .')">Delete</a>';
            }
            else
                return '<p>NA</p>';
        }
    }
    add_action('manage_users_custom_column', 'mo_openid_delete_profile_column', 9, 3);

    if(get_option('mo_openid_logout_redirection_enable') == 1){
        add_filter( 'logout_url', 'mo_openid_redirect_after_logout',0,1);
    }
    function mo_openid_add_custom_column($columns){
        $columns['mo_openid_delete_profile_data'] = 'Delete Social Profile Data';
        return $columns;
    }

    function mo_openid_delete_social_profile_script(){
?>
        <script type="text/javascript">
			function moOpenidDeleteSocialProfile(elem, userId){
                jQuery.ajax({
                    url:"<?php echo admin_url();?>", //the page containing php script
                    method: "POST", //request type,
                    data: {action : 'delete_social_profile_data', user_id : userId},
                    dataType: 'text',
                    success:function(result){
                        alert('Social Profile Data Deleted successfully. Press OK to continue.');
                      window.location.reload(true);
                    }
                });
            }
		</script>
<?php

    }

    function mo_openid_sanitize_user($username, $raw_username, $strict) {

        $username = wp_strip_all_tags( $raw_username );
        $username = remove_accents( $username );
        // Kill octets
        $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
        $username = preg_replace( '/&.+?;/', '', $username ); // Kill entities


        $username = trim( $username );
        // Consolidate contiguous whitespace
        $username = preg_replace( '|\s+|', ' ', $username );
        return $username;
    }

    add_filter('manage_users_columns', 'mo_openid_add_custom_column');

    add_action( 'widgets_init', function(){register_widget( "mo_openid_login_wid" );});
    add_action( 'widgets_init', function(){register_widget( "mo_openid_sharing_ver_wid" );});
    add_action( 'widgets_init', function(){register_widget( "mo_openid_sharing_hor_wid" );});

    add_action( 'init', 'mo_openid_login_validate' );
    add_action( 'wp_logout', 'mo_openid_end_session',1 );
    add_action( 'mo_user_register', 'mo_openid_update_role', 1, 2);
    add_action( 'wp_login', 'mo_openid_login_redirect', 10, 2);
    add_action( 'wp_login', 'mo_openid_link_account', 9, 2);
    add_filter( 'login_message', 'mo_openid_account_linking');
    add_action( 'delete_user', 'mo_openid_delete_account_linking_rows' );
    add_action( 'mo_user_register','mo_openid_send_email',1, 2 );
    add_action('admin_head', 'mo_openid_delete_social_profile_script');

    //compatibility with international characters
    add_filter('sanitize_user', 'mo_openid_sanitize_user', 10, 3);

?>