<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );


add_filter( 'wp_title', 'wpmobile_shortcode_title', PHP_INT_MAX );
function wpmobile_shortcode_title($title) {

	if (isset($_GET['wpapp_shortcode'])) {

		$slug = $_GET['wpapp_shortcode'];
		switch ($slug) {
			case 'wpapp_qrcode':
				$slug = __('QRCode Scanner', 'wpappninja');
				break;

			case 'wpapp_history':
				$slug = __('Push history', 'wpappninja');
				break;
		
			case 'wpapp_config':
			case 'wpapp_push':
				$slug = __('Push configuration', 'wpappninja');
				break;
		
			case 'wpapp_home':
				$slug = __('Custom homepage', 'wpappninja');
				break;
		
			case 'wpapp_recent':
				$slug = __('Recent posts', 'wpappninja');
				break;

			case 'wpapp_login':
				$slug = __('Login', 'wpappninja');
				break;
		}

		return $slug;
	}

	return $title;
}

add_action('wp_footer', 'wpmobile_ajax_get_push');
function wpmobile_ajax_get_push() {

	?>

	<span class="wpappninja wpmobileapp" style="display:none"></span>

	<?php

	if (!is_wpappninja()) {
		return;
	}
        
        if (get_wpappninja_option('notimeoutjs', '0') == '1') {
            echo '<script>wpmobile_no_timeout();</script>';
        }
	
	?>
	<script>
	/*jQuery(document).ready( function(){
	    jQuery('a[href^="#"],a[href^="/"]').each( function(){
	    	if (jQuery(this).attr('href').length > 1) {
		        jQuery(this).attr('href', window.location.href.replace(window.location.hash,'') + jQuery(this).attr('href'));
		    }
	    });
	});*/

	jQuery(function() {
           
        //jQuery('a[href*="#"]').click(function() { window.location=this.href; });
           
		if (jQuery(window.location.hash.split('?')[0]).length) {
			jQuery('.page-content').animate({scrollTop: 0}, 0);
            jQuery('.page-content').animate({scrollTop: jQuery(window.location.hash.split('?')[0]).offset().top + 50}, 300);
			window.location.hash = "";
		}
	});

	jQuery(window).on('hashchange', function () {
		if (jQuery(window.location.hash.split('?')[0]).length) {
			jQuery('.page-content').animate({scrollTop: 0}, 0);
            jQuery('.page-content').animate({scrollTop: jQuery(window.location.hash.split('?')[0]).position().top + 50}, 300);
			window.location.hash = "";
			setTimeout(function() {
				if (typeof app !== "undefined") {app.progressbar.hide();}
			}, 1300);
			jQuery('.posts,.title-speed').css('opacity', '1');
		} else {
			setTimeout(function() {
				if (jQuery(window.location.hash.split('?')[0]).length) {
                    jQuery('.page-content').animate({scrollTop: 0}, 0);
					jQuery('.page-content').animate({scrollTop: jQuery(window.location.hash.split('?')[0]).position().top + 50}, 300);
					window.location.hash = "";
					setTimeout(function() {
						if (typeof app !== "undefined") {app.progressbar.hide();}
					}, 1300);
					jQuery('.posts,.title-speed').css('opacity', '1');
				}
			}, 1000);
		}
	});

	function wpmobile_ajax_get_push() {

		jQuery.ajax({
			type: "POST",
			url: "<?php echo admin_url('admin-ajax.php');?>",
			data: {action: 'wpmobile_get_push'},
			success: function (response) {

	    		var json = response;

	    		if (json.slice(-1) === "0") {
	    			json = json.slice(0,-1);
	    		}

	    		json = JSON.parse(json);

	    		if (json.length > 1) {
	    			app.dialog.confirm('<?php _e('You have unread notifications', 'wpappninja');?>', json.length + ' <?php _e('notifications', 'wpappninja');?>', function(){document.location = '?wpapp_shortcode=wpapp_history';});
	    		} else if (json.length == 1) {
	    			jQuery.each(json, function(i, item) {

	    				if (item.link != "") {
							app.dialog.confirm(item.text, item.title, function(){document.location = item.link;});
						} else {
							app.dialog.confirm(item.text, item.title);
						}
					});
   				}

   				setTimeout(function() {
   					jQuery('.dialog').css('margin-top', '-' + (jQuery('.dialog').height() / 2) + 'px');
   				}, 1000);
			}
		});
	}

	jQuery(function(){

		//wpmobile_ajax_get_push();
		setTimeout(wpmobile_ajax_get_push, 10000);

	});
	</script>
	<?php 
}

add_action( 'wp_ajax_wpmobile_get_push', 'wpmobile_get_push' );
add_action( 'wp_ajax_nopriv_wpmobile_get_push', 'wpmobile_get_push' );
function wpmobile_get_push() {

	global $wpdb;

    $return = array();

	$user_id = (isset($_COOKIE['HTTP_X_WPAPPNINJA_ID']) ? $_COOKIE['HTTP_X_WPAPPNINJA_ID'] : "");

	$user_settings = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $user_id));

	if (!isset($user_settings)) {
		$user_settings = new stdClass();
	}

	$user_category = array();

	if (isset($user_settings->category)) {
		$user_category = explode(',', $user_settings->category);
	}
	
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

	if (is_array($user_category)) {
		foreach ($user_category as $c) {
			$like_prepare .= " OR category LIKE %s";
			$like_term[] = $c;
		}
	}
	$like_prepare .= ')';
	
	$query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(`id`) as nb FROM {$wpdb->prefix}wpappninja_push WHERE (lang = %s OR lang = 'all') AND `send_date` > %d AND `sended` = %s " . $like_prepare . "", $like_term));

    $unread = $query[0]->nb;

    $last_seen_banner = 0;
    if (isset($_COOKIE['wpmobile_last_seen_banner'])) {
        $last_seen_banner = $_COOKIE['wpmobile_last_seen_banner'];
    }

    if ($unread > 0 && $last_seen_banner <= $last_seen) {

        setcookie( "wpmobile_last_seen_banner", strtolower(current_time( 'timestamp' )), time() + 300 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        setcookie( "wpmobile_last_seen", strtolower(current_time( 'timestamp' )), time() + 300 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);


		$query_popup = $wpdb->get_results($wpdb->prepare("SELECT `id`, `id_post`, `titre`, `message`, `image`, `send_date`, `sended`, `log`, `lang` FROM {$wpdb->prefix}wpappninja_push WHERE (lang = %s OR lang = 'all') AND `send_date` > %d AND `sended` = %s " . $like_prepare . " ORDER BY `send_date` DESC", $like_term));

		$query_popup = array_reverse($query_popup);

		foreach($query_popup as $obj) {

			$html = "";

			$permalink = false;
			if ($obj->id_post > 0) {
				$permalink = get_permalink($obj->id_post);
			}

			if (preg_match('#^http#', $obj->id_post)) {
				$permalink = $obj->id_post;
			}

		    if ($obj->image != "" && $obj->image != " ") {
		        $html .= '<img src="' . $obj->image . '" width="100%" />';
			}

            //$html .= '<h3>' . stripslashes($obj->titre) . '</h3>';
            $html .= '<p>' . stripslashes($obj->message) . '</p>';
    		

            $link = "";
    		if ($permalink) {
				$link = wpappninja_cache_friendly(wpmobile_weglot($permalink));
			}
            //$html .= '<a href="#" class="button" onclick="app.popup.close(jQuery(\\\'#push'.$obj->id.$unread.'\\\'));return false">'.__('Close', 'wpappninja').'</a>';


			$return[] = array('text' => stripslashes($html), 'title' => stripslashes($obj->titre), 'link' => $link);

		}
	} elseif (!isset($_COOKIE['wpmobile_last_seen'])) {
		setcookie( "wpmobile_last_seen", strtolower(current_time( 'timestamp' )), time() + 300 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
	}

	echo json_encode($return);
	wp_die();
}


add_action('wp_head', 'wpmobile_sdk2019_top', 1);
function wpmobile_sdk2019_top() {

	if (!is_wpappninja()) {
		return;
	}

	// light status
	$app_data = get_wpappninja_option('app');
	$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
	$app_theme_status = get_wpappninja_option('css_74537a66b8370a71e9b05c3c4ddbf522');

	if ($app_theme_status == "") {
		$app_theme_status = "#000000";
	}
	
	if (wpappninja_need_light_status($app_theme_status) && !wpappninja_isIOS()) { ?>
		
		<script>try{wpmobileapp.darkstatus();} catch(err) {}</script>

		<?php
	}

	if (!wpappninja_isIOS()) { ?>

		<script>try{wpmobileapp.setstatus('<?php echo $app_theme_status;?>');} catch(err) {}</script>

		<?php
	}
	
	if (!wpappninja_need_light_status($app_theme_status) && wpappninja_isIOS()) { ?>
		
		<script>try{window.webkit.messageHandlers.wpmobile.postMessage('darkstatus');} catch(err) {}</script>

		<?php
	}

}



add_action('wp_head', 'wpmobile_sdk2019', 11);
function wpmobile_sdk2019() {

	if (!is_wpappninja()) {
		return;
	}

	// external links

	if (get_wpappninja_option('all_link_browser', '0') == '0') {	?>

	<script>
	jQuery(function() {
		jQuery('a').not('[href^="http://'+document.domain+'"],[href^="https://'+document.domain+'"]').each(

		function() {

			if (typeof jQuery(this).attr("href") !== "undefined") {

			var separator = "?",
				href = jQuery(this).attr("href");


			if(href.includes('?')) {
				separator = "&";
			}

			if(href.startsWith("http")) {
				jQuery(this).attr("href", href + separator + "wpmobileexternal=true");
			}

			}
		}
		);
	});


	jQuery(document).ajaxComplete(function(){
		jQuery('a').not('[href^="http://'+document.domain+'"],[href^="https://'+document.domain+'"]').each(

		function() {

			if (typeof jQuery(this).attr("href") !== "undefined") {

			var separator = "?",
				href = jQuery(this).attr("href");


			if(href.includes('?')) {
				separator = "&";
			}

			if(href.startsWith("http")) {
				jQuery(this).attr("href", href + separator + "wpmobileexternal=true");
			}

			}
		}
		);
	});
	</script>

	<?php

	}

}

// redirect home
add_action('wp', 'wpmobile_redirect_homepage');
function wpmobile_redirect_homepage() {

	if (isset($_GET['wpmobile_homepage']) || isset($_GET['loadApp'])) {
        
        
        // set the locale
        if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
            
            $locale = wpappninja_get_lang("long");
            $locale = substr($locale, 0, 2);
            $langs = wpappninja_available_lang();

            $default = null;
            foreach ( $langs as $l => $ll) {
                
                if ($default == null) {$default = $ll;}
                
                if ($ll == $locale) {
                    setcookie("WPAPPNINJA_LOCALE", $locale, time() + 300 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
                    $_COOKIE['WPAPPNINJA_LOCALE'] = $locale;
                }
            }
            
            if ($_COOKIE['WPAPPNINJA_LOCALE'] == "") {
                setcookie("WPAPPNINJA_LOCALE", $default, time() + 300 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
                $_COOKIE['WPAPPNINJA_LOCALE'] = $default;
            }
        }
        
        
        
        

		if (canGetWPMobileCookie()) {
			setcookie( "HTTP_X_WPAPPNINJA", 1, time() + 864000, COOKIEPATH, COOKIE_DOMAIN );
			$_COOKIE['HTTP_X_WPAPPNINJA'] = 1;
		}

		$app_data = get_wpappninja_option('app');
		if (!isset($app_data['splashscreen']) OR $app_data['splashscreen'] == "" OR preg_match('#/wpappninja/assets/images/os/empty\.png$#', $app_data['splashscreen'])) {$app_data['splashscreen'] = "https://my.wpmobile.app/_launchscreen.php?c=" . str_replace('#', '', $app_data['theme']['primary']) . "&l=" . $app_data['logo'];}

		$home = wpappninja_cache_friendly(wpmobile_weglot(wpappninja_get_home()));
        
        if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
            $home = wpappninja_translate($home);
        }

		if ($home == "") {
			$home = wpappninja_cache_friendly(wpmobile_weglot(home_url( '' )));
		}

		if (is_user_logged_in()) {
	        if (get_wpappninja_option('login_redirect_after') != '') {
               	$home = wpappninja_cache_friendly(wpmobile_weglot(get_wpappninja_option('login_redirect_after')));
                
                if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
                    $home = wpappninja_translate(get_wpappninja_option('login_redirect_after'));
                }
       		}
		}

		header("Expires: on, 01 Jan 1970 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		//header('Location: '.$home); ?>
		<html <?php echo ' manifest="' . WPAPPNINJA_ASSETS_3RD_URL . 'appmanifest.php" '; ?> class="wpmobiledonthidesplash wpappninja" style="background:url(<?php echo $app_data['splashscreen'];?>) center center no-repeat;background-size:cover"><head><script>document.location = '<?php echo $home;?>';</script></head><body></body></html>
		<?php
		exit();
	}
}


add_action('wp_head', 'wpmobile_define_js_handler', PHP_INT_MAX);
function wpmobile_define_js_handler() {

	if (isset($_GET['wpappninja_simul4'])) { ?>

	<script>
	jQuery(function() {
		setTimeout(function() {
			jQuery('a[href*="wpappninjasharetext"],a[href*="wpapppushconfig"]').attr('href', 'javascript:app.dialog.alert(\'<?php _e('Use the free preview app to test this feature', 'wpappninja');?>\', \'<?php _e('Not available in demo', 'wpappninja');?>\');');
		}, 500);
	});
	</script>

	<?php }

	if (!is_wpappninja()) {
		return;
	}
	?>
	<style>
html body #root .emojionearea {
display: none!important;
}
html body #root .new-message textarea, html body #root .reply textarea {
display: block!important;
}

.ios #root .list ul {
    background: initial;
}

.navbar .wpmobile-title {
    font-size: 17px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    width: 100%!important;
    text-align: center;
    color:<?php echo get_wpappninja_option('css_00bcbfacaf98f1b05815ab4eaeee1e13');?>;
}

#root .woocommerce-info, #root .woocommerce-info:before {
    content: ""!important;
}
nav.woocommerce-MyAccount-navigation ul {
    margin: 0 0 25px;
}body.woocommerce-account .wpmobile-widget-page-top {
    display: none;
}
html #root header.woocommerce-Address-title.title .edit {
    width: 100%;
}
html #root header.woocommerce-Address-title.title {
    width: 100%!important;
    position: initial!important;
    margin-top: 30px;
}
header.woocommerce-Address-title.title h3 {
    margin: 0;
}
nav.woocommerce-MyAccount-navigation ul li a {
    display: block;
}
td.woocommerce-orders-table__cell .button {
    margin: 28px 0;
}
html #root .woocommerce .woocommerce-customer-details address {
    width: auto;
}
nav.woocommerce-MyAccount-navigation ul li {
    display: block;
    width: 100%;
    text-align: left;
    border: 0;
    box-sizing: border-box;
    border-radius: 0;
    padding: 9px 11px;
    margin: 0;
    background: #eaeaea;
    font-size: 17px;
    line-height: 25px;
    color: #515151;
}
.woocommerce-MyAccount-navigation-link--customer-logout {display:none!important;}
	nav.woocommerce-MyAccount-navigation ul li.is-active {background-color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>!important;}
	nav.woocommerce-MyAccount-navigation ul li.is-active a {color:white!important;}
html #root .summary.entry-summary {
    width: 100%;
}

	html #root .panel.panel-left .button {color:<?php echo get_wpappninja_option('css_c1cbcf662a13f13037d53a185986c2ad');?>;}
	html #root .panel.panel-left .button i.icon {color:<?php echo get_wpappninja_option('css_c1cbcf662a13f13037d53a185986c2ad');?>;}
	html #root .button {border-color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>;border:1px solid;color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>;}
	html #root .button i.icon {color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>;}
	html #root .posts .et_animated, html #root .et_pb_section img, html #root .et_pb_section span {
	    opacity: 1!important;
	}
	.posts .card {
	    overflow: hidden;
	}
	#comments article {
	    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
	    padding: 15px;
	    margin: 0 0 15px -7px;
	    width: Calc(100% + -16px);
	    border-radius: 2px;	
	    overflow: hidden;
	}
	.woocommerce-product-gallery {
	    margin: -15px;
	    width: Calc(100% + 30px)!important;
	}
	div#comments h4 {
	    display: none;
	}
	ul.tabs.wc-tabs {
	    margin: 0 0 0 -15px!important;
	    width: Calc(100% + 30px);
	}
	p.woocommerce-result-count {
	    display: none;
	}
	html #root ul.products li.product {
	    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
	    border: 0;
	    margin-left: -7px;
	    width: Calc(100% + 14px);
	    border-radius: 4px;
	    margin-bottom: 20px;
	    float: none;
	    padding: 15px;
	    box-sizing: border-box;
	}
	div#bp-nouveau-activity-form {
	    border: 0;
	    background: transparent;
	    box-shadow: 0 0 0;
	}
	div#whats-new-avatar {
	    display: inline-block;
	    border-radius: 999px!important;
	    overflow: hidden;
	    width: 50px;
	    height: 50px;
	}
	div#whats-new-content {
	    width: Calc(100% - 70px);
	    display: inline-block;
	    padding: 0!important;
	    margin: 0 0 10px 20px;
	}
	nav.main-navs {
	    margin-top: 15px;
	}
	.card-author .wpappninja-avatar img {
    	border-radius: 999px;
	}
	nav.bp-navs {
    	/*width: Calc(100% + 30px);
    	margin: 0 0 0 -15px;*/
	}
	.activity-meta.action {
    	border-top: 1px solid #f5f5f5!important;
    	padding-top: 12px!important;
	}
	.woocommerce-product-gallery a.woocommerce-product-gallery__trigger {
	    display: none;
	}
	nav.bp-navs ul li.selected a {background-color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>!important;}
	html #root #activity-stream ul li {
	    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
	    border: 0;
	    margin-left: -7px;
	    width: Calc(100% - 19px);
	}
	html #root ul.activity-list.item-list.bp-list {
	    background: transparent!important;
	}
	div#buddypress {
	    margin-top: -15px;
	}
	html #root .user-update .activity-read-more {
    	display: none;
	}
	h2.screen-heading.view-profile-screen {
	    display: none;
	}
	form.cart input.qty {
	    border: 1px solid #eee;
	    padding: 10px;
	    width:100%;
	}
	ul.products li.product {
	    overflow: hidden!important;
	}
	ul.products li.product a img:first-child {
	    margin: -15px 0 0 -15px;
	    width: Calc(100% + 30px)!important;
	    max-width: 200%;
	}
	.woocommerce div.product form.cart div.quantity:before {
	    content: "Quantity";
	    font-size: 11px;
	}
	form.cart .quantity {
	    width: 100%;
	    margin: 0 0 20px 0!important;
	}
	.single-product .product .onsale {
	    margin-top: 25px;
	}
	html #root #activity-stream ul li ul li {
	    box-shadow: 0 0 0!important;
	    border: 0!important;
	}
	#respond .item-inner {
	    padding: 0;
	}
	div#comments .chip .chip-media img {
    width: 20px;
    height: 20px;
}#comments .chip.bg-wpappninja {
    background: transparent;
    padding: 0 0 0 10px;
}#comments .chip-label {
    color: #333!important;
}#comments .block-title {
    display: inline;
}
	html #root .buddypress span.activity-read-more {
    	display: block;
	    margin: 35px 0 10px 0!important;
	}
	.activity-content .activity-inner {
	    background: transparent!important;
	    padding: 0!important;
	    margin: 10px 0 15px!important;
	}
	.activity-avatar.item-avatar img {
	    border-radius: 999px;
	}
	html body .page-content.ptr-content {
	    margin: 0;
	}
	.activity-avatar.item-avatar {
	    width: 50px!important;
	    position: absolute;
	    margin:0px 0 0 -13px!important;
	}
	html #root #activity-stream ul li ul li {
	    width: 100%;
	    margin: 0;
	}
	html #root li.load-more {
	    width: Calc(100% + 14px)!important;
	}
	.activity-comments form {
	    padding: 0!important;
	    margin-top: 23px;
	}
	.activity-comments input[type="submit"] {
	    float: right;
	    margin: 0!important;
	}
	.activity-comments form textarea {
	    box-shadow: 0 0 5px #eee!important;
	    border: 1px solid #eee;
	    margin: 5px!important;
	}
	html body.bp-user.front div#item-header {
	    display: block!important;
	}
	html body.bp-user div#item-header {
	    display: none;
	}
	html #root .bp-messages p, html #root .bp-feedback p {
    	padding: 10px 0;
	}
	html #root .generic-button {
	    width: Calc(33% - 3px);
	}
	input#bp-browse-button {
	    margin: auto;
	}
	div#subsubnav ul {
	    padding: 0;
	}
	html #root .bp-messages, html #root .bp-feedback {
	    box-shadow: 0 0 0;
	    line-height: initial;
	    border-radius: 24px;
	    overflow: hidden;
	    border: 0;
	    background: #f9f9f9f9;
	}
	html #root div#item-header {
	    margin: -20px;
	}
	html #root ul.member-header-actions.action {
	    display: none;
	}
	html #root #buddypress #header-cover-image {
    	height: 150px;
	}
	html #root h2.user-nicename {
	    margin-top: 0!important;
	}
	html #root div#item-header-avatar {
	    padding: 0!important;
	    margin: 50px 0 0!important;
	}
	html #root .generic-button {
	    border-left: 1px solid #dadada;
	    background:transparent;
	}
	html #root .activity-meta.action {
	    background: transparent!important;
	}
	html #root .activity-meta.action .generic-button:first-child {
    	border: 0;
	}
	html body #root .activity-meta.action .generic-button a {
	    padding: 10px 0!important;
	    margin: 0!important;
	    border: 0!important;
	    width: 100%;
	    text-align: center;
	    display: inline-block!important;
	}
	html .atwho-container .atwho-view {
	    z-index: 100000;
	    position: fixed;
	    bottom: 0!important;
	    max-height: 300px;
	    min-height: 0;
	    margin: 0!important;
	    overflow: auto!important;
	    height: auto;
	    top: unset!important;
	}
	html #root ul#members-list li {
	    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
	    border: 0;
	    margin-left: -7px;
	    width: Calc(100% + 14px);
	}
	.bp-pagination.top {
	    display: none;
	}
	ul#members-list {
	    border: 0;
	    margin: 0;
	}
	.pag-count.bottom {
	    display: none;
	}
	.bp-pagination-links.bottom {
	    width: 100%!important;
	    margin: 0!important;
	    padding: 0!important;
	    float: none!important;
	    border: 0!important;
	    text-align: center;
	    margin-bottom: 30px!important;
	}
	.bp-pagination-links.bottom a, .bp-pagination-links.bottom span {
		display: none;
	}
	.bp-pagination-links.bottom a.next {
	    font-size: 50px!important;
	    display:inline!important;
	}
	nav.members-type-navs.main-navs.bp-navs.dir-navs {
	    display: none;
	}
	ul#members-list .user-update {
	    border: 0;
	    padding: 0;
	    font-style: inherit;
	}
	ul#members-list li .list-wrap {
		display:block;
	}
	ul#members-list li .item .item-block {
	    width: Calc(100% - 50px);
	    margin: 17px 0 14px 50px;
	}
	.wpmobile_list_history .item-title {
	    white-space: normal;
	}
	.dialog .dialog-inner .dialog-text img {
	    max-height: 130px;
	    height: 100%;
	    width: auto;
	    margin: auto;
	    display: block;
	}
	ul#members-list li .item .item-block * {
	    text-align: left;
		line-height: 10px;
	}
	ul#members-list li .item-avatar {
	    width: 40px;
	    display: inline-block;
	    position: absolute;
	    left: 10px;
	}
	ul#members-list li .item .item-block {
	    width: Calc(100% - 50px);
	    margin-left: 50px;
	}
	#root #buddypress #item-header-cover-image #item-header-avatar img.avatar {
	    border: 0;
	    border-radius: 999px;
	    box-shadow: 0 0 5px #aaa;
	}
	ul#members-list li .item {
	    width: 100%;
	    display: inline-block;
	    padding: 0 20px;
	    box-sizing: border-box;
	}
	html body.wpmobileapp span.select2-container {
	    z-index: 999999999999;
	}
	ul#members-list li .item-avatar img {
	    border-radius: 999px;
	    margin: 10px;
	    width: 40px;
	    height: 40px;
	}
	html #root #buddypress .feed {
    	display: none;
	}
	html #root #buddypress .select-wrap select {
	    width: 100%;
	}
	html #root .activity-inner iframe {
	    max-width: 100%!important;
	    height: auto!important;
	}
	html #root .activity-stream iframe {max-width: 100%!important;height:auto!important;}
	html #root ul.activity-list.item-list.bp-list {
	    padding: 0;
	    border: 0;
	}
	html #root span.select-arrow {
	    float: right;
	    margin-top: -27px;
	    margin-right: 10px;
	}
	<?php if(preg_match('#<form#', wpappninja_widget('navbar-middle'))) { ?>
	.title {
		width: Calc(100% - 18% - 40px)!important;
    	max-width: 100%!important;
    	position: absolute!important;
    	left: 9%;
	}
	body.wpappninja #root .navbar .ios.wpapp_navbar_search {
	    width: 100%;
	    margin-left: 0;
	}

	<?php } ?>
	html #root .card-content h2.wpmobile-title {
    	margin: 0;
	}
	html #root .card-content-inner {
    	overflow: hidden;
	}
	html #root .wpmobile-widget-card-content img.hero {
    	margin-top: -30px;
	}
	#root .woocommerce-info, #root .woocommerce-info:before {
    	color: <?php echo get_wpappninja_option('css_74537a66b8370a71e9b05c3c4ddbf522');?>;
    	border-color: <?php echo get_wpappninja_option('css_74537a66b8370a71e9b05c3c4ddbf522');?>;
	}
	#root input#coupon_code {
    	border: 1px solid #eee;
    	padding: 10px;
	}
	html body #root .woocommerce .button {
    	overflow: hidden!important;
	}
	html #root p.cart-empty.woocommerce-info {
    	border-top: 0;
	}
	html body #root .button {
    	display: block;
    	font-weight: 500!important;
    	background: initial;
    	padding: 10px;
	}
	a.added_to_cart.wc-forward {
    	text-align: center;
    	display: block;
    	text-transform: uppercase;
    	margin: 10px 0 0;
    	padding: 9px;
    	font-size: 12px;
	}
	form.woocommerce-ordering select {
	    border: 1px solid #eee;
	    padding: 8px;
	    width: 100%;
	}
	.woocommerce .woocommerce-ordering, .woocommerce-page .woocommerce-ordering {
	    float: none;
	}
	.woocommerce form input[type=text], .woocommerce form input[type=tel], .woocommerce form input[type=email], .woocommerce form input[type=password], .woocommerce textarea, .woocommerce form select {
	    width: 100%!important;
	    border: 1px solid #eee;
	    padding: 8px;
	}
	</style>



	<script>
function wpmobile_no_timeout() {
    
           try{window.webkit.messageHandlers.wpmobile.postMessage('notimeout');} catch(err) {}
       try{wpmobileapp.notimeout();} catch(err) {}
}

	jQuery(function() {

		jQuery('.skip-link.screen-reader-text').remove();

		jQuery('header.woocommerce-Address-title.title .edit').addClass('button');

		jQuery('body .um-profile-photo-img, body.buddypress .activity-inner .activity-read-more a').attr('href', '');
jQuery('.page.page-current').attr('style', '');

		jQuery('.ajax_add_to_cart, .rtmedia-list a, .activity-meta a, a[href*="admin-ajax"], .bp-pagination-links.bottom a.next, nav#subnav a, ul.component-navigation.activity-nav a, .product-remove a').each(function(){
			jQuery(this).attr("href", jQuery(this).attr("href") + "&fake=.png");
		});
	});

	setTimeout(function() {
		jQuery('.skip-link.screen-reader-text').remove();

		jQuery('body .um-profile-photo-img, body.buddypress .activity-inner .activity-read-more a').attr('href', '');

		jQuery('.ajax_add_to_cart, .rtmedia-list a, .activity-meta a, a[href*="admin-ajax"], .bp-pagination-links.bottom a.next, nav#subnav a, ul.component-navigation.activity-nav a, .product-remove a').each(function(){

			if (jQuery(this).attr("href").indexOf("fake=.png") < 0) {
				jQuery(this).attr("href", jQuery(this).attr("href") + "&fake=.png");
			}
		});
	}, 500);
	
	jQuery(document).ajaxComplete(function() {

		if (jQuery('.woocommerce-error').length) {
			console.log(jQuery('.woocommerce-error').offset().top);
			setTimeout(function(){console.log(jQuery('.woocommerce-error').offset().top);jQuery('.page-content').animate({scrollTop: jQuery('.woocommerce-error').offset().top}, 300);}, 300);
		}


		jQuery('.ajax_add_to_cart, .rtmedia-list a, .activity-meta a, a[href*="admin-ajax"], .bp-pagination-links.bottom a.next, nav#subnav a, ul.component-navigation.activity-nav a, .product-remove a').each(function(){

			if (jQuery(this).attr("href").indexOf("fake=.png") < 0) {
				jQuery(this).attr("href", jQuery(this).attr("href") + "&fake=.png");
			}
		});
	});
	jQuery(function() {
    	jQuery('html.ios').addClass('with-statusbar');
    	setTimeout(function() {jQuery('html.ios').addClass('with-statusbar');}, 300);
	});
	
	function wpmobileappStopLoading() {

		// remove the loading effect
		try{window.webkit.messageHandlers.wpmobile.postMessage('loaded');} catch(err) {}
		try{wpmobileapp.loaded();} catch(err) {}
	    
      	jQuery('.wpmobile_preload').css('display','none');
      	setTimeout(function(){jQuery('body,.posts,.title-speed').css('opacity', '1');},100);
      	setTimeout(function(){app.progressbar.hide();},300);

	}

	function wpmobileappShowPush(url) {

        jQuery('.wpmobileopenpush').remove();
        jQuery('body').append('<a href="'+url+'" class="wpmobileopenpush"></a>');
    	jQuery('.wpmobileopenpush')[0].click();

	}

	function wpmobileappStartLoading() {
	
		// display the loading effect
		try{window.webkit.messageHandlers.wpmobile.postMessage('load');} catch(err) {}
		app.panel.close('left', true);
		app.popup.close();
		app.dialog.close();
		app.progressbar.show();
		jQuery('.posts,.title-speed').css('opacity', '0.2');
	}

	function wpmobileappSetToken(identifier, token) {

		// send back to the server the uniqid and token (old post request)
		jQuery.ajax({url: "<?php echo home_url( '' );?>/?<?php echo 'uniqid='.uniqid().'&';?>pagename=wpappninja&type=register&wpmobile_sdk2019_id="+identifier+"&wpmobile_sdk2019_token="+token});
	}

	jQuery(function() {
		try{window.webkit.messageHandlers.wpmobile.postMessage('register');} catch(err) {}
		try{wpmobileapp.token();} catch(err) {}
	});
	</script>

	<?php
}

add_filter( 'wp_redirect', 'wpmobile_demo_preview', 2, 100 );
function wpmobile_demo_preview($location, $status) {

	if (isset($_GET['wpappninja_simul4'])) {

		$query = parse_url($location, PHP_URL_QUERY);
		if ($query) {
			$location .= '&wpappninja_simul4=true&wpappninja=true';
		} else {
			$location .= '?wpappninja_simul4=true&wpappninja=true';
		}
	}

	return $location;
}
