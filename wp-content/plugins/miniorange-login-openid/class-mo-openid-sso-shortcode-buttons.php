<?php

function count_convert($count)
{
    if($count=="")
        $count=0;
    if($count>=1000000){
        $count=$count/1000000;
        $count=$count."M";
    }
    else if($count>=1000)
    {
        $ncount=$count/1000;
        $count=$ncount."K";
    }
    return $count;
}

//shortcode for horizontal sharing
function mo_openid_share_shortcode( $atts = '', $title = '', $excerpt = '' ) {
    $html = '';
    $selected_theme = isset( $atts['shape'] )? esc_attr($atts['shape']) : esc_attr(get_option('mo_openid_share_theme'));
    $selected_direction = esc_attr(get_option('mo_openid_share_widget_customize_direction'));
    $sharingSize = isset( $atts['size'] )? esc_attr($atts['size']) : esc_attr(get_option('mo_sharing_icon_custom_size'));
    $custom_color = isset( $atts['backgroundcolor'] )? esc_attr($atts['backgroundcolor']) : esc_attr(get_option('mo_sharing_icon_custom_color'));
    $custom_theme = isset( $atts['theme'] )? esc_attr($atts['theme']) : esc_attr(get_option('mo_openid_share_custom_theme'));
    $fontColor = isset( $atts['fontcolor'] )? esc_attr($atts['fontcolor']) : esc_attr(get_option('mo_sharing_icon_custom_font'));
    $spaceBetweenIcons = isset( $atts['space'] )? esc_attr($atts['space']) : esc_attr(get_option('mo_sharing_icon_space'));
    $textColor = isset( $atts['color'] ) ? esc_attr($atts['color']) : '#'.esc_attr(get_option('mo_openid_share_widget_customize_text_color'));
    $text = isset( $atts['heading'] ) ? esc_attr($atts ['heading']) : esc_attr(get_option('mo_openid_share_widget_customize_text'));
    $twitter_username = get_option('mo_openid_share_twitter_username');
    $url = isset( $atts['url'])? esc_url($atts['url']) : esc_url(get_permalink());
    $sharing_counts = isset( $atts['sharecnt'] )? esc_attr($atts['sharecnt']) : esc_attr(get_option('mo_openid_share_count'));
    if(!$url) {
        $url = esc_attr(get_site_url());
    }

    if($fontColor)
    {
        if(ctype_xdigit($fontColor) && strlen($fontColor)==6 && strpos($fontColor,'#')==false){
            $fontColor= "#".$fontColor;
        }else
            $fontColor;
    }

    if($sharing_counts=="yes" || $sharing_counts=="1")
        $sharing_counts=1;
    else
        $sharing_counts=0;

    $email_subject = esc_html(get_option('mo_openid_share_email_subject'));
    $email_body = get_option('mo_openid_share_email_body');
    $email_body = str_replace('##url##', $url, $email_body);

    if($custom_theme == 'custombackground'){
        $custom_theme = 'custom';
    }
    if($custom_theme == 'nobackground'){
        $custom_theme = 'customFont';
    }

    $orientation = 'hor';

    $html .= '<div class="mo-openid-app-icons circle ">';

    $html .= '<p style="margin-top:4% !important; margin-bottom:0px !important; color:'.$textColor.'">';
    if( $orientation == 'hor' ) {
        $html .=  $text . '</p>';
        $html .= "<div class='horizontal'>";
        if($custom_theme == 'custom'){
            if($sharing_counts) {
                $html.="<div class='sharecount'><ul class='share_count_icon'>";
            }
            if( get_option('mo_openid_facebook_share_enable') ) {
                $link = 'https://www.facebook.com/dialog/share?app_id=766555246789034&amp;display=popup&amp;href='.$url;
                if ($sharing_counts){
                    if(get_option('mo_openid_Facebook_share_count_api')!="") {
                        $count = get_transient('facebook');
                        if ($count == false) {
                            $content = file_get_contents("https://graph.facebook.com/v3.3/?id=" . $url . "&fields=engagement&access_token=" . get_option('mo_openid_Facebook_share_count_api'));
                            $content = explode('share_count":', $content);
                            $content = explode(',', $content[1]);
                            $count = $content[0];
                            set_transient('facebook', $count, 3600);
                        }
                    }
                    $html .= "<li>";}
                $html.="<a rel='nofollow' title='Facebook' onclick='popupCenter(". '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-facebook' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts && get_option('mo_openid_Facebook_share_count_api')!=""){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";} else{$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_twitter_share_enable') ) {
                $link = empty($twitter_username) ? 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url : 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url. '&amp;via='.$twitter_username;
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Twitter' onclick='popupCenter(". '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-twitter' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_google_share_enable') ) {
                if ($sharing_counts){
                    $html .= "<li>";}
                $link = 'https://plus.google.com/share?url='.$url;
                $html .= "<a rel='nofollow' title='Google' onclick='popupCenter(". '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-google-plus' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_vkontakte_share_enable') ) {
                $link = 'http://vk.com/share.php?url='.$url.'&amp;title='.$title.'&amp;description='.$excerpt;
                if ($sharing_counts){
                    $count = get_transient( 'vkontakte' );
                    if($count===false) {
                        $content = file_get_contents("https://vk.com/share.php?act=count&url=".$url);
                        $content=explode(',',$content);
                        $content=explode(')',$content[1]);
                        $count=$content[0];
                        $count=count_convert($count);
                        set_transient('vkontakte', $count, 3600);
                    }
                    $html .= "<li>";
                }
                $html .= "<a rel='nofollow' title='Vkontakte' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-vk' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }
            if( get_option('mo_openid_tumblr_share_enable') ) {
                $link = 'http://www.tumblr.com/share/link?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Tumblr' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-tumblr' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_stumble_share_enable') ) {
                $link = 'http://www.stumbleupon.com/submit?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){
                    $count = get_transient( 'stumbleupon' );
                    if($count===false) {
                        $content = file_get_contents("http://www.stumbleupon.com/services/1.01/badge.getinfo?url=" . $url);
                        $content = explode(',', $content);
                        $content = explode(':', $content[5]);
                        $count = $content[1];
                        $count=count_convert($count);
                        set_transient('stumbleupon', $count, 3600);
                    }
                    $html .= "<li>";
                }
                $html .= "<a rel='nofollow' title='StumbleUpon' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-stumbleupon' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }
            if( get_option('mo_openid_linkedin_share_enable') ) {
                $link = 'https://www.linkedin.com/shareArticle?mini=true&amp;title='.$title.'&amp;url='.$url.'&amp;summary='.$excerpt;
                if ($sharing_counts){
                    $html .= "<li>";
                }
                $html .= "<a rel='nofollow' title='LinkedIn' onclick='popupCenter(". '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-linkedin' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_reddit_share_enable') ) {
                $link = 'http://www.reddit.com/submit?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){
                    $html .= "<li>";
                }
                $html .= "<a rel='nofollow' title='Reddit' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-reddit' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_pinterest_share_enable') ) {
                if ($sharing_counts){
                    $count = get_transient( 'pinterest' );
                    if($count===false) {
                        $content_pint = file_get_contents("https://widgets.pinterest.com/v1/urls/count.json?source=6&url=".$url);
                        $body = preg_replace( '/^receiveCount\((.*)\)$/', '\\1', $content_pint );
                        $count=explode(":",$body);
                        $count=explode("}",$count[3]);
                        $count=count_convert($count[0]);
                        set_transient('pinterest', $count, 3600);
                    }
                    $html .= "<li>";
                }
                $html .= "<a rel='nofollow' title='Pinterest' href='javascript:pinIt();' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-pinterest' style='padding-top:3px;text-align:center;color:#ffffff;font-size:" .($sharingSize-10). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }

            if( get_option('mo_openid_pocket_share_enable') ) {
                $link = 'https://getpocket.com/save?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Pocket' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-get-pocket' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_digg_share_enable') ) {
                $link = 'http://digg.com/submit?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Digg' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-digg' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_delicious_share_enable') ) {
                $link = 'http://www.delicious.com/save?v=5&noui&jump=close&url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Delicious' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-delicious' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_odnoklassniki_share_enable') ) {
                $link = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments='.$excerpt.'&amp;st._surl='.$url;
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Odnoklassniki' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-odnoklassniki' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_mail_share_enable') ) {
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Email this page' onclick='popupCenter(" . '"mailto:?subject= ' . $email_subject . '&amp;body=' . $email_body. '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-envelope' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_print_share_enable') ) {
                if ($sharing_counts){$html .= "<li>";} $html .= "<a rel='nofollow' title='Print this page' onclick='javascript:window.print();' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-print' style='padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_whatsapp_share_enable') ) {

                if(!wp_is_mobile()){
                    if ($sharing_counts){$html .= "<li>";} $html .= '<a rel="nofollow"  title="Whatsapp" target ="_blank" href="https://web.whatsapp.com/send?text=' . $url .'" class="mo-openid-share-link" style="margin-left :  '.($spaceBetweenIcons) . 'px !important;border-bottom:0px !important;"><i class="mo-custom-share-icon ' .$selected_theme. ' fa fa-whatsapp" style="padding-top:8px;text-align:center;color:#ffffff;font-size: '.($sharingSize-16). 'px !important;background-color:#' .$custom_color. ';height: '.$sharingSize. 'px !important;width:' .$sharingSize. 'px !important;"></i></a>'; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
                }
                else{
                    if ($sharing_counts){$html .= "<li>";} $html .= '<a rel="nofollow" title="Whatsapp" href="whatsapp://send?text=' . $url .'" class="mo-openid-share-link" style="margin-left :  '.($spaceBetweenIcons) . 'px !important;border-bottom:0px !important;"><i class="mo-custom-share-icon ' .$selected_theme. ' fa fa-whatsapp" style="padding-top:8px;text-align:center;color:#ffffff;font-size: '.($sharingSize-16). 'px !important;background-color:#' .$custom_color. ';height: '.$sharingSize. 'px !important;width:' .$sharingSize. 'px !important;"></i></a>'; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};

                }
            }
            if($sharing_counts){
                $html.="</ul></div>";
            }

        }

        else if($custom_theme == 'customFont') {
            if ($sharing_counts) {
                $html .= "<div class='sharecount'><ul class='share_count_icon'>";
            }
            if (get_option('mo_openid_facebook_share_enable')) {
                $link = 'https://www.facebook.com/dialog/share?app_id=766555246789034&amp;display=popup&amp;href=' . $url;
                if ($sharing_counts){
                    if(get_option('mo_openid_Facebook_share_count_api')!="") {
                        $count = get_transient('facebook');
                        if ($count === false) {
                            $content = file_get_contents("https://graph.facebook.com/v3.3/?id=" . $url . "&fields=engagement&access_token=" . get_option('mo_openid_Facebook_share_count_api'));
                            $content = explode('share_count":', $content);
                            $content = explode(',', $content[1]);
                            $count = $content[0];
                            set_transient('facebook', $count, 3600);
                        }
                    }
                    $html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Facebook' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-facebook' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts && get_option('mo_openid_Facebook_share_count_api')!=""){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";} else{$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if (get_option('mo_openid_twitter_share_enable')) {
                $link = empty($twitter_username) ? 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url : 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=' . $twitter_username;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Twitter' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-twitter' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if (get_option('mo_openid_google_share_enable')) {

                $link = 'https://plus.google.com/share?url=' . $url;
                if ($sharing_counts){
                    $html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Google' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class='fa fa-google-plus' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_vkontakte_share_enable')) {
                $link = 'http://vk.com/share.php?url=' . $url . '&amp;title=' . $title . '&amp;description=' . $excerpt;
                if ($sharing_counts){
                    $count = get_transient( 'vkontakte' );
                    if($count===false) {
                        $content = file_get_contents("https://vk.com/share.php?act=count&url=".$url);
                        $content=explode(',',$content);
                        $content=explode(')',$content[1]);
                        $count=$content[0];
                        $count=count_convert($count);
                        set_transient('vkontakte', $count, 3600);
                    }
                    $html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Vkontakte' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-vk' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }
            if (get_option('mo_openid_tumblr_share_enable')) {
                $link = 'http://www.tumblr.com/share/link?url=' . $url . '&amp;title=' . $title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Tumblr' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-tumblr' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_stumble_share_enable')) {
                $link = 'http://www.stumbleupon.com/submit?url=' . $url . '&amp;title=' . $title;
                if ($sharing_counts){
                    $count = get_transient( 'stumbleupon' );
                    if($count===false) {
                        $content = file_get_contents("http://www.stumbleupon.com/services/1.01/badge.getinfo?url=" . $url);
                        $content = explode(',', $content);
                        $content = explode(':', $content[5]);
                        $count = $content[1];
                        $count=count_convert($count);
                        set_transient('stumbleupon', $count, 3600);
                    }
                    $html .= "<li style='line-height: 0.0px'>";
                }
                $html .= "<a rel='nofollow' title='StumbleUpon' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-stumbleupon' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }
            if (get_option('mo_openid_linkedin_share_enable')) {
                $link = 'https://www.linkedin.com/shareArticle?mini=true&amp;title=' . $title . '&amp;url=' . $url . '&amp;summary=' . $excerpt;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='LinkedIn' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-linkedin' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_reddit_share_enable')) {
                $link = 'http://www.reddit.com/submit?url=' . $url . '&amp;title=' . $title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Reddit' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-reddit' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_pinterest_share_enable')) {
                if ($sharing_counts){
                    $count = get_transient( 'pinterest' );
                    if($count===false) {
                        $content_pint = file_get_contents("https://widgets.pinterest.com/v1/urls/count.json?source=6&url=".$url);
                        $body = preg_replace( '/^receiveCount\((.*)\)$/', '\\1', $content_pint );
                        $count=explode(":",$body);
                        $count=explode("}",$count[3]);
                        $count=count_convert($count[0]);
                        set_transient('pinterest', $count, 3600);
                    }
                    $html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Pinterest' href='javascript:pinIt();' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-pinterest' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }

            if (get_option('mo_openid_pocket_share_enable')) {
                $link = 'https://getpocket.com/save?url=' . $url . '&amp;title=' . $title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Pocket' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-get-pocket' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_digg_share_enable')) {
                $link = 'http://digg.com/submit?url=' . $url . '&amp;title=' . $title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Digg' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-digg' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_delicious_share_enable')) {
                $link = 'http://www.delicious.com/save?v=5&noui&jump=close&url=' . $url . '&amp;title=' . $title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Delicious' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-delicious' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_odnoklassniki_share_enable')) {
                $link = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments=' . $excerpt . '&amp;st._surl=' . $url;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Odnoklassniki' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons - 6) . "px !important'><i class=' " . $selected_theme . " fa fa-odnoklassniki' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_mail_share_enable')) {
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Email this page' onclick='popupCenter(" . '"mailto:?subject= ' . $email_subject . '&amp;body=' . $email_body . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><i class=' " . $selected_theme . " fa fa-envelope' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_print_share_enable')) {
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Print this page' onclick='javascript:window.print();' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><i class=' " . $selected_theme . " fa fa-print' style='padding-top:4px;text-align:center;color:" . $fontColor . ";font-size:" . $sharingSize . "px !important;height:" . $sharingSize . "px !important;width:" . $sharingSize . "px !important;'></i></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if (get_option('mo_openid_whatsapp_share_enable')) {
                if (wp_is_mobile()) {
                    if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= '<a rel="nofollow"  title="Whatsapp" href="whatsapp://send?text=' . $url . '" class="mo-openid-share-link" style="margin-left :  ' . ($spaceBetweenIcons - 6) . 'px !important;border-bottom:0px !important;"><i class=" ' . $selected_theme . ' fa fa-whatsapp" style="padding-top:4px;text-align:center;color:' . $fontColor . ';font-size:' . $sharingSize . 'px !important;height:' . $sharingSize . 'px !important;width:' . $sharingSize . 'px !important;"></i></a>'; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
                } else {
                    if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= '<a rel="nofollow" title="Whatsapp" target ="_blank" href="https://web.whatsapp.com/send?text=' . $url . '" class="mo-openid-share-link" style="margin-left :  ' . ($spaceBetweenIcons - 6) . 'px !important;border-bottom:0px !important;"><i class=" ' . $selected_theme . ' fa fa-whatsapp" style="padding-top:4px;text-align:center;color:' . $fontColor . ';font-size:' . $sharingSize . 'px !important;height:' . $sharingSize . 'px !important;width:' . $sharingSize . 'px !important;"></i></a>'; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
                }
            }
            if ($sharing_counts) {
                $html .= "</ul></div>";

            }
        }

        else {

            if ($sharing_counts) {
                $html .= "<div class='sharecount'><ul class='share_count_icon'>";
            }

            if( get_option('mo_openid_facebook_share_enable') ) {
                $link = 'https://www.facebook.com/dialog/share?app_id=766555246789034&amp;display=popup&amp;href='.$url;
                if ($sharing_counts){
                    if(get_option('mo_openid_Facebook_share_count_api')!="") {
                        $count = get_transient('facebook');
                        if ($count === false) {
                            $content = file_get_contents("https://graph.facebook.com/v3.3/?id=" . $url . "&fields=engagement&access_token=" . get_option('mo_openid_Facebook_share_count_api'));
                            $content = explode('share_count":', $content);
                            $content = explode(',', $content[1]);
                            $count = $content[0];
                            set_transient('facebook', $count, 3600);
                        }
                    }
                    $html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Facebook' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 400);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Facebook' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/facebook.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts && get_option('mo_openid_Facebook_share_count_api')!=""){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";} else{$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_twitter_share_enable') ) {
                $link = empty($twitter_username) ? 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url : 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url. '&amp;via='.$twitter_username;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Twitter' onclick='popupCenter(" . '"' . $link . '"' . ", 600, 300);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Twitter' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/twitter.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_google_share_enable') ) {
                $link = 'https://plus.google.com/share?url='.$url;
                if ($sharing_counts){
                    $html .= "<li style='line-height: 0.0px'>";} $html .=	"<a rel='nofollow' title='Google' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Google' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;background-color: " . $selected_theme . "' src='" . plugins_url( 'includes/images/icons/google.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_vkontakte_share_enable') ) {
                $link = 'http://vk.com/share.php?url='.$url.'&amp;title='.$title.'&amp;description='.$excerpt;
                if ($sharing_counts){
                    $count = get_transient( 'vkontakte' );
                    if($count===false) {
                        $content = file_get_contents("https://vk.com/share.php?act=count&url=".$url);
                        $content=explode(',',$content);
                        $content=explode(')',$content[1]);
                        $count=$content[0];
                        $count=count_convert($count);
                        set_transient('vkontakte', $count, 3600);
                    }
                    $html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Vkontakte' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Vkontakte' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/vk.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }
            if( get_option('mo_openid_tumblr_share_enable') ) {
                $link = 'http://www.tumblr.com/share/link?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Tumblr' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Tumblr' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/tumblr.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_stumble_share_enable') ) {
                $link = 'http://www.stumbleupon.com/submit?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){
                    $count = get_transient( 'stumbleupon' );
                    if($count===false) {
                        $content = file_get_contents("http://www.stumbleupon.com/services/1.01/badge.getinfo?url=" . $url);
                        $content = explode(',', $content);
                        $content = explode(':', $content[5]);
                        $count = $content[1];
                        $count=count_convert($count);
                        set_transient('stumbleupon', $count, 3600);
                    }
                    $html .= "<li style='line-height: 0.0px'>";
                }
                $html .= "<a rel='nofollow' title='StumbleUpon' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='StumbleUpon' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/stumble.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }
            if( get_option('mo_openid_linkedin_share_enable') ) {
                $link = 'https://www.linkedin.com/shareArticle?mini=true&amp;title='.$title.'&amp;url='.$url.'&amp;summary='.$excerpt;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='LinkedIn' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='LinkedIn' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/linkedin.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_reddit_share_enable') ) {
                $link = 'http://www.reddit.com/submit?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Reddit' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Reddit' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/reddit.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }

            if( get_option('mo_openid_pinterest_share_enable') ) {
                if ($sharing_counts){
                    $count = get_transient( 'pinterest' );
                    if($count===false) {
                        $content_pint = file_get_contents("https://widgets.pinterest.com/v1/urls/count.json?source=6&url=".$url);
                        $body = preg_replace( '/^receiveCount\((.*)\)$/', '\\1', $content_pint );
                        $count=explode(":",$body);
                        $count=explode("}",$count[3]);
                        $count=count_convert($count[0]);
                        set_transient('pinterest', $count, 3600);
                    }
                    $html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Pinterest'  href='javascript:pinIt();' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Pinterest' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" .  plugins_url( 'includes/images/icons/pininterest.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span style='margin-left : " . ($spaceBetweenIcons) . "px !important'>$count</span></li>";};
            }

            if( get_option('mo_openid_pocket_share_enable') ) {
                $link = 'https://getpocket.com/save?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Pocket' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Pocket' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/pocket.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_digg_share_enable') ) {
                $link = 'http://digg.com/submit?url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Digg' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Digg' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/digg.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_delicious_share_enable') ) {
                $link = 'http://www.delicious.com/save?v=5&noui&jump=close&url='.$url.'&amp;title='.$title;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Delicious' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Delicious' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/delicious.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_odnoklassniki_share_enable') ) {
                $link = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments='.$excerpt.'&amp;st._surl='.$url;
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Odnoklassniki' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " . ($spaceBetweenIcons) . "px !important'><img alt='Odnoklassniki' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/odnoklassniki.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_mail_share_enable') ) {
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Email this page' onclick='popupCenter(" . '"mailto:?subject= ' . $email_subject . '&amp;body=' . $email_body. '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><img alt='Email this page' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/mail.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_print_share_enable') ) {
                if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= "<a rel='nofollow' title='Print this page' onclick='javascript:window.print();' class='mo-openid-share-link' style='margin-left : " .($spaceBetweenIcons) . "px !important'><img alt='Print this page' style= 'height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='"  . plugins_url( 'includes/images/icons/print.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>"; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
            }
            if( get_option('mo_openid_whatsapp_share_enable') ) {

                if (wp_is_mobile()){
                    if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= '<a rel="nofollow"  title="Whatsapp" href="whatsapp://send?text=' . $url . '" class="mo-openid-share-link" style="margin-left :  '. ($spaceBetweenIcons) . 'px !important; border-bottom:0px !important;">
			        	<img alt="Whatsapp" style= "height:  '. $sharingSize . 'px !important;width:  '. $sharingSize . 'px !important;" src="'. plugins_url( "includes/images/icons/whatsapp.png", __FILE__ ) . '" class="mo-openid-app-share-icons  '. $selected_theme . '" ></a>'; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
                }
                else{
                    if ($sharing_counts){$html .= "<li style='line-height: 0.0px'>";} $html .= '<a rel="nofollow"  title="Whatsapp" target ="_blank" href="https://web.whatsapp.com/send?text=' . $url . '" class="mo-openid-share-link" style="margin-left :  '. ($spaceBetweenIcons) . 'px !important; border-bottom:0px !important;">
			        	<img alt="Whatsapp" style= "height:  '. $sharingSize . 'px !important;width:  '. $sharingSize . 'px !important;" src="'. plugins_url( "includes/images/icons/whatsapp.png", __FILE__ ) . '" class="mo-openid-app-share-icons  '. $selected_theme . '" ></a>'; if($sharing_counts){$html .= "<span2 style='margin-left : " . ($spaceBetweenIcons) . "px !important'></span2></li>";};
                }

            }
            if ($sharing_counts) {
                $html .= "</ul></div>";

            }

        }
        $html .= "</div>";
    }


    $html .= "</p></div><br/>";

    $html .= '<script>';

    $html .= 'function popupCenter(pageURL, w,h) {';
    $html .= 'var left = (screen.width/2)-(w/2);';
    $html .= 'var top = (screen.height/2)-(h/2);';
    $html .= "var targetWin = window.open (pageURL, '_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);}";

    $html .= 'function pinIt(){';
    $html .= 'var e = document.createElement("script");';
    $html .= "e.setAttribute('type','text/javascript');";
    $html .= "e.setAttribute('charset','UTF-8');";
    $html .= "e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);
		document.body.appendChild(e);}";
    $html .= '</script>';

    return $html;

}

function mo_openid_vertical_share_shortcode( $atts = '', $title = '', $excerpt = '' ) {
    $html = '';
    $selected_theme = isset( $atts['shape'] )? esc_attr($atts['shape']) : esc_attr(get_option('mo_openid_share_theme'));
    $selected_direction = esc_attr(get_option('mo_openid_share_widget_customize_direction'));
    $sharingSize = isset( $atts['size'] )? esc_attr($atts['size']) : esc_attr(get_option('mo_sharing_icon_custom_size'));
    $custom_color = isset( $atts['backgroundcolor'] )? esc_attr($atts['backgroundcolor']) : esc_attr(get_option('mo_sharing_icon_custom_color'));
    $custom_theme = isset( $atts['theme'] )? esc_attr($atts['theme']) : esc_attr(get_option('mo_openid_share_custom_theme'));
    $fontColor = isset( $atts['fontcolor'] )? esc_attr($atts['fontcolor']) : esc_attr(get_option('mo_sharing_icon_custom_font'));
    $spaceBetweenIcons = isset( $atts['space'] )? esc_attr($atts['space']) : '10';

    $alignment =  isset( $atts['alignment'] )? esc_attr($atts['alignment']) : 'left';
    $left_offset = isset( $atts['leftoffset'] )? esc_attr($atts['leftoffset']) : '20';
    $right_offset = isset( $atts['rightoffset'] )? esc_attr($atts['rightoffset']) : '10';
    $top_offset = isset( $atts['topoffset'] )? esc_attr($atts['topoffset']) : '100';


    $twitter_username = get_option('mo_openid_share_twitter_username');
    $url = isset( $atts['url'])? esc_url($atts['url']) : esc_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    $email_subject = esc_attr(get_option('mo_openid_share_email_subject'));
    $email_body = get_option('mo_openid_share_email_body');
    $email_body = str_replace('##url##', $url, $email_body);

    if($custom_theme == 'custombackground'){
        $custom_theme = 'custom';
    }
    if($custom_theme == 'nobackground'){
        $custom_theme = 'customFont';
    }


    $html .= "<div class='mo_openid_vertical' style='" .(isset($alignment) && $alignment != ''  ? $alignment .': '. (${$alignment.'_offset'} == '' ? 0 :  ${$alignment.'_offset'} ) .'px;' : '').(isset($top_offset) ? 'top: '. ( $top_offset == '' ? 0 : $top_offset ) .'px;' : '') ."'>";

    $html .= '<div class="mo-openid-app-icons circle ">';
    $html .= '<p>';

    $html .= "<div id='mo_floating_vertical_shortcode'>";
    if($custom_theme == 'custom'){

        if( get_option('mo_openid_facebook_share_enable') ) {
            $link = 'https://www.facebook.com/dialog/share?app_id=766555246789034&amp;display=popup&amp;href='.$url;
            $html .= "<a rel='nofollow' title='Facebook' onclick='popupCenter(" . '"' . $link . '"' .", 1000, 500);' class='mo-openid-share-link' style='margin-bottom : " .$spaceBetweenIcons . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-facebook' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }

        if( get_option('mo_openid_twitter_share_enable') ) {
            $link = empty($twitter_username) ? 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url : 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url. '&amp;via='.$twitter_username;
            $html .= "<a rel='nofollow' title='Twitter' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-twitter' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";

        }

        if( get_option('mo_openid_google_share_enable') ) {
            $link = "https://plus.google.com/share?url=".$url;
            $html .= "<a rel='nofollow' title='Google' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' style='margin-bottom : " .$spaceBetweenIcons . "px !important'><i class='mo-custom-share-icon " .$selected_theme. " fa fa-google-plus' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";

        }
        if( get_option('mo_openid_vkontakte_share_enable') ) {
            $link = 'http://vk.com/share.php?url='.$url.'&amp;title='.$title.'&amp;description='.$excerpt;
            $html .= "<a rel='nofollow' title='Vkontakte' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-vk' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_tumblr_share_enable') ) {
            $link = 'http://www.tumblr.com/share/link?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Tumblr' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-tumblr' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_stumble_share_enable') ) {
            $link = 'http://www.stumbleupon.com/submit?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='StumbleUpon' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-stumbleupon' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_linkedin_share_enable') ) {
            $link = 'https://www.linkedin.com/shareArticle?mini=true&amp;title='.$title.'&amp;url='.$url.'&amp;summary='.$excerpt;


            $html .= "<a rel='nofollow' title='LinkedIn' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-linkedin' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";

        }

        if( get_option('mo_openid_reddit_share_enable') ) {
            $link = 'http://www.reddit.com/submit?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Reddit' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-reddit' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }

        if( get_option('mo_openid_pinterest_share_enable') ) {
            $html .= "<a rel='nofollow' title='Pinterest' href='javascript:pinIt();' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-pinterest' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:3px;text-align:center;color:#ffffff;font-size:" .($sharingSize-10). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";

        }

        if( get_option('mo_openid_pocket_share_enable') ) {
            $link = 'https://getpocket.com/save?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Pocket' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-get-pocket' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }

        if( get_option('mo_openid_digg_share_enable') ) {
            $link = 'http://digg.com/submit?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Digg' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-digg' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_delicious_share_enable') ) {
            $link = 'http://www.delicious.com/save?v=5&noui&jump=close&url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Delicious' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-delicious' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_odnoklassniki_share_enable') ) {
            $link = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments='.$excerpt.'&amp;st._surl='.$url;
            $html .= "<a rel='nofollow' title='Odnoklassniki' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-odnoklassniki' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_mail_share_enable') ) {
            $html .= "<a rel='nofollow' title='Email this page' onclick='popupCenter(" . '"mailto:?subject= ' . $email_subject . '&amp;body=' . $email_body. '"' . ", 800, 500);'  class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-envelope' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_print_share_enable') ) {
            $html .= "<a rel='nofollow' title='Print this page' onclick='javascript:window.print();' class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-print' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_whatsapp_share_enable') ) {
            if(wp_is_mobile()){
                $html .= '<a rel="nofollow" title="Whatsapp" href="whatsapp://send?text=' . $url ."\" class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-whatsapp' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
            }
            else{
                $html .= '<a rel="nofollow" title="Whatsapp" target ="_blank" href="https://web.whatsapp.com/send?text=' . $url ."\" class='mo-openid-share-link' ><i class='mo-custom-share-icon " .$selected_theme. " fa fa-whatsapp' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:8px;text-align:center;color:#ffffff;font-size:" .($sharingSize-16). "px !important;background-color:#" .$custom_color. ";height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
            }
        }


    }

    else if($custom_theme == 'customFont') {

        if( get_option('mo_openid_facebook_share_enable') ) {
            $link = 'https://www.facebook.com/dialog/share?app_id=766555246789034&amp;display=popup&amp;href='.$url;
            $html .= "<a rel='nofollow' title='Facebook' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-facebook' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }

        if( get_option('mo_openid_twitter_share_enable') ) {
            $link = empty($twitter_username) ? 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url : 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url. '&amp;via='.$twitter_username;
            $html .= "<a rel='nofollow' title='Twitter' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-twitter' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }

        if( get_option('mo_openid_google_share_enable') ) {

            $link = 'https://plus.google.com/share?url='.$url;
            $html .= "<a rel='nofollow' title='Google' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link'><i class='fa fa-google-plus' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_vkontakte_share_enable') ) {
            $link = 'http://vk.com/share.php?url='.$url.'&amp;title='.$title.'&amp;description='.$excerpt;
            $html .= "<a rel='nofollow' title='Vkontakte' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-vk' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_tumblr_share_enable') ) {
            $link = 'http://www.tumblr.com/share/link?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Tumblr' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-tumblr' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_stumble_share_enable') ) {
            $link = 'http://www.stumbleupon.com/submit?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='StumbleUpon' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-stumbleupon' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_linkedin_share_enable') ) {
            $link = 'https://www.linkedin.com/shareArticle?mini=true&amp;title='.$title.'&amp;url='.$url.'&amp;summary='.$excerpt;
            $html .= "<a rel='nofollow' title='LinkedIn' onclick='popupCenter(". '"' . $link . '"' .", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-linkedin' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_reddit_share_enable') ) {
            $link = 'http://www.reddit.com/submit?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Reddit' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-reddit' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_pinterest_share_enable') ) {
            $html .= "<a rel='nofollow' title='Pinterest' href='javascript:pinIt();' class='mo-openid-share-link' ><i class='fa fa-pinterest' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .($sharingSize-5). "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }

        if( get_option('mo_openid_pocket_share_enable') ) {
            $link = 'https://getpocket.com/save?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Pocket' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-get-pocket' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_digg_share_enable') ) {
            $link = 'http://digg.com/submit?url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Digg' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-digg' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_delicious_share_enable') ) {
            $link = 'http://www.delicious.com/save?v=5&noui&jump=close&url='.$url.'&amp;title='.$title;
            $html .= "<a rel='nofollow' title='Delicious' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-delicious' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_odnoklassniki_share_enable') ) {
            $link = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments='.$excerpt.'&amp;st._surl='.$url;
            $html .= "<a rel='nofollow' title='Odnoklassniki' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link' ><i class='fa fa-odnoklassniki' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_mail_share_enable') ) {
            $html .= "<a rel='nofollow' title='Email this page' onclick='popupCenter(" . '"mailto:?subject= ' . $email_subject . '&amp;body=' . $email_body. '"' . ", 800, 500);'  class='mo-openid-share-link' ><i class='fa fa-envelope' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_print_share_enable') ) {
            $html .= "<a rel='nofollow' title='Print this page' onclick='javascript:window.print();' class='mo-openid-share-link' ><i class='fa fa-print' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
        }
        if( get_option('mo_openid_whatsapp_share_enable') ) {
            if(wp_is_mobile()){
                $html .= '<a rel="nofollow" title="Whatsapp" href="whatsapp://send?text=' . $url ."\" class='mo-openid-share-link' ><i class='fa fa-whatsapp' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
            }
            else{
                $html .= '<a rel="nofollow" title="Whatsapp" target ="_blank" href="https://web.whatsapp.com/send?text=' . $url ."\" class='mo-openid-share-link' ><i class='fa fa-whatsapp' style='margin-bottom : " . ($spaceBetweenIcons-4) . "px !important;padding-top:4px;text-align:center;color:" .$fontColor . ";font-size:" .$sharingSize. "px !important;height:" .$sharingSize. "px !important;width:" .$sharingSize. "px !important;'></i></a>";
            }
        }


    }

    else {

        if( get_option('mo_openid_facebook_share_enable') ) {
            $link = 'https://www.facebook.com/dialog/share?app_id=766555246789034&amp;display=popup&amp;href='.$url;
            $html .=	"<a rel='nofollow' title='Facebook' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Facebook' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/facebook.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }

        if( get_option('mo_openid_twitter_share_enable') ) {
            $link = empty($twitter_username) ? 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url : 'https://twitter.com/intent/tweet?text='.$title.'&amp;url='.$url. '&amp;via='.$twitter_username;
            $html .=	"<a rel='nofollow' title='Twitter' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Twitter' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/twitter.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }

        if( get_option('mo_openid_google_share_enable') ) {
            $link = 'https://plus.google.com/share?url='.$url;

            $html .=	"<a rel='nofollow' title='Google' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Google' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/google.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_vkontakte_share_enable') ) {
            $link = 'http://vk.com/share.php?url='.$url.'&amp;title='.$title.'&amp;description='.$excerpt;
            $html .=	"<a rel='nofollow' title='Vkontakte' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Vkontakte' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/vk.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_tumblr_share_enable') ) {
            $link = 'http://www.tumblr.com/share/link?url='.$url.'&amp;title='.$title;
            $html .=	"<a rel='nofollow' title='Tumblr' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Tumblr' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/tumblr.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_stumble_share_enable') ) {
            $link = 'http://www.stumbleupon.com/submit?url='.$url.'&amp;title='.$title;
            $html .=	"<a rel='nofollow' title='StumbleUpon' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='StumbleUpon' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/stumble.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_linkedin_share_enable') ) {
            $link = 'https://www.linkedin.com/shareArticle?mini=true&amp;title='.$title.'&amp;url='.$url.'&amp;summary='.$excerpt;
            $html .=	"<a rel='nofollow' title='LinkedIn' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='LinkedIn' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/linkedin.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_reddit_share_enable') ) {
            $link = 'http://www.reddit.com/submit?url='.$url.'&amp;title='.$title;
            $html .=	"<a rel='nofollow' title='Reddit' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Reddit' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/reddit.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_pinterest_share_enable') ) {
            $html .=	"<a rel='nofollow' title='Pinterest' href='javascript:pinIt();' class='mo-openid-share-link'><img alt='Pinterest' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/pininterest.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_pocket_share_enable') ) {
            $link = 'https://getpocket.com/save?url='.$url.'&amp;title='.$title;
            $html .=	"<a rel='nofollow' title='Pocket' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Pocket' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/pocket.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_digg_share_enable') ) {
            $link = 'http://digg.com/submit?url='.$url.'&amp;title='.$title;
            $html .=	"<a rel='nofollow' title='Digg' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Digg' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/digg.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_delicious_share_enable') ) {
            $link = 'http://www.delicious.com/save?v=5&noui&jump=close&url='.$url.'&amp;title='.$title;
            $html .=	"<a rel='nofollow' title='Delicious' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Delicious' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/delicious.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_odnoklassniki_share_enable') ) {
            $link = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments='.$excerpt.'&amp;st._surl='.$url;
            $html .=	"<a rel='nofollow' title='Odnoklassniki' onclick='popupCenter(" . '"' . $link . '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Odnoklassniki' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/odnoklassniki.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_mail_share_enable') ) {
            $html .= "<a rel='nofollow' title='Email this page' onclick='popupCenter(" . '"mailto:?subject= ' . $email_subject . '&amp;body=' . $email_body. '"' . ", 800, 500);' class='mo-openid-share-link'><img alt='Email this page' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important; height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/mail.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_print_share_enable') ) {
            $html .= "<a rel='nofollow' title='Print this page' onclick='javascript:window.print();' class='mo-openid-share-link'><img alt='Print this page' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important; height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/print.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
        }
        if( get_option('mo_openid_whatsapp_share_enable') ) {
            if(wp_is_mobile()){
                $html .= '<a rel="nofollow" title="Whatsapp" href="whatsapp://send?text=' . $url ."\" class='mo-openid-share-link'><img alt='Whatsapp' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/whatsapp.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
            }
            else{
                $html .= '<a rel="nofollow" title="Whatsapp" target ="_blank" href="https://web.whatsapp.com/send?text=' . $url ."\" class='mo-openid-share-link'><img alt='Whatsapp' style= 'margin-bottom : " . ($spaceBetweenIcons-6) . "px !important;height: " . $sharingSize . "px !important;width: " . $sharingSize . "px !important;' src='" . plugins_url( 'includes/images/icons/whatsapp.png', __FILE__ ) . "' class='mo-openid-app-share-icons " . $selected_theme . "' ></a>";
            }
        }

    }
    $html .= "</div>";

    $html .= "</p></div></div>";


    if(get_option('mo_share_vertical_hide_mobile')) {
        $html .= "<script>";
        $html .= "function hideVerticalShare() {";
        $html .= "var isMobile = false;";
        $html .= "if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {";
        $html .= "isMobile = true;";
        $html .= "}";

        $html .= "if(isMobile) {";
        $html .= "if(jQuery('#mo_floating_vertical_shortcode'))";
        $html .= 'jQuery("#mo_floating_vertical_shortcode").hide();';
        $html .= "}";
        $html .= "}";
        $html .= "hideVerticalShare();";
        $html .= "</script>";
    }


    $html .= '<script>';

    $html .= 'function popupCenter(pageURL, w,h) {';
    $html .= 'var left = (screen.width/2)-(w/2);';
    $html .= 'var top = (screen.height/2)-(h/2);';
    $html .= "var targetWin = window.open (pageURL, '_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);}";

    $html .= 'function pinIt(){';
    $html .= 'var e = document.createElement("script");';
    $html .= "e.setAttribute('type','text/javascript');";
    $html .= "e.setAttribute('charset','UTF-8');";
    $html .= "e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);";
    $html .= "document.body.appendChild(e);}";
    $html .= '</script>';

    return $html;

}
?>