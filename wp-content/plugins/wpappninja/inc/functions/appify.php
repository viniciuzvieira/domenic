<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/* ADD BODY CLASS */
add_filter('body_class', 'wpmobileapp_theme_body_class');
function wpmobileapp_theme_body_class($classes) {

    if (is_wpappninja()) {

        $classes[] = 'theme-wpappninja';
        $classes[] = 'wpmobileapp';

    	if (wpappninja_isIOS()) {
	       $classes[] = 'wpapp-ios';
        }
    }

	return $classes;
}

/* BE SURE JQUERY IS READY */
add_filter('wp_enqueue_scripts','wpmobileapp_add_jquery', 1);
function wpmobileapp_add_jquery() {

	if (is_wpappninja()) {
		wp_enqueue_script('jquery', false, array(), false, false);
	}
}

/* LISTS OPTIONS */
add_action( 'pre_get_posts', 'wpmobileapp_pre_get_posts' );
function wpmobileapp_pre_get_posts($query) {

	if (is_wpappninja() && !is_page() && !is_single() && !is_admin() && get_wpappninja_option('speed') == '1' && get_wpappninja_option('appify') != '1') {

		$has_password = null;
		if (get_wpappninja_option('has_password', "0") == "0") {
			$has_password = false;
		}

		$query->set( 'posts_per_page', intval(get_wpappninja_option('listnb', 10)) );
		$query->set( 'orderby', get_wpappninja_option('orderby_list', 'post_date') );
		$query->set( 'order', get_wpappninja_option('order_list', 'DESC') );
		$query->set( 'has_password', $has_password );
		$query->set( 'date_query', array(
														'column'  => 'post_date',
														'after'   => '- '.get_wpappninja_option('maxage', '365000').' days'
													) );
		$query->set( 'tag__not_in', get_wpappninja_option('excluded', '') );
	}

}

/* WOOCOMMERCE */
add_filter('woocommerce_get_catalog_ordering_args', 'wpmobileapp_woocommerce_catalog_orderby');
function wpmobileapp_woocommerce_catalog_orderby( $args ) {

	if (is_wpappninja()) {
	    $args['orderby'] = get_wpappninja_option('orderby_list', 'post_date');
	    $args['order'] = get_wpappninja_option('order_list', 'desc'); 
	}

    return $args;
}
add_action( 'widgets_init', 'wpmobileapp_woo_sidebar' );
function wpmobileapp_woo_sidebar() {

    //woocommerce sidebar
    if (function_exists('is_woocommerce') && is_woocommerce() && is_wpmobileapp_ready()) {
	    unregister_sidebar('shop');
	}
}

/* MAIN JS AND CSS */
add_action('wp_enqueue_scripts', 'wpmobileapp_theme_styles');
function wpmobileapp_theme_styles() {

    if (is_wpmobileapp_ready()) {

    	wp_register_style( 'wpmobileapp-css', WPAPPNINJA_URL . 'themes/wpmobileapp/includes/app-ui.css', array(), WPAPPNINJA_VERSION, null );
		wp_enqueue_style( 'wpmobileapp-css' );

        wp_dequeue_style( 'select2' );
        wp_dequeue_script( 'select2');
        wp_dequeue_script( 'selectWoo' );
    }
}

// LOADER
add_filter('wp_head', 'wpmobileapp_custom_loader', 11);
function wpmobileapp_custom_loader() {

	if (is_wpappninja()) {

		// load framewor7 icons
		?>
		<style>
		#root p label { color: <?php echo get_wpappninja_option('css_d7a8405db9b1bc84f477b325f32d2574', '#000');?>;}
		.posts {background:<?php echo get_wpappninja_option('css_102c4591c3ac08bbcdbf73981d5eb725', '#fff');?>!important;}
		.md body, .ios body {color:<?php echo get_wpappninja_option('css_d7a8405db9b1bc84f477b325f32d2574', '#000');?>;}
		.popup, .block {background: <?php echo get_wpappninja_option('css_102c4591c3ac08bbcdbf73981d5eb725', '#000');?>!important;}
		.wpmobile-login-loggedin div {color: <?php echo get_wpappninja_option('css_c1cbcf662a13f13037d53a185986c2ad', '#fff');?>!important;}
		input[type="submit"] {color: <?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed', '#333');?>!important;background-color: transparent!important;}
		.ios .searchbar:after , .md .searchbar:after {background: transparent!important;}
		.wpmobile-login-loggedin {
    margin-top: 15px;
}
		@font-face {
		  font-family: 'Framework7 Icons';
		  font-style: normal;
		  font-weight: 400;
		  src: url("<?php echo WPAPPNINJA_URL;?>themes/wpmobileapp/includes/fonts/Framework7Icons-Regular.eot?2019");
		  src: local('Framework7 Icons'),
    		 local('Framework7Icons-Regular'),
    		 url("<?php echo WPAPPNINJA_URL;?>themes/wpmobileapp/includes/fonts/Framework7Icons-Regular.woff2?2019") format("woff2"),
    		 url("<?php echo WPAPPNINJA_URL;?>themes/wpmobileapp/includes/fonts/Framework7Icons-Regular.woff?2019") format("woff"),
    		 url("<?php echo WPAPPNINJA_URL;?>themes/wpmobileapp/includes/fonts/Framework7Icons-Regular.ttf?2019") format("truetype");
		}

		.f7-icons, .framework7-icons {
		  font-family: 'Framework7 Icons';
		  font-weight: normal;
		  font-style: normal;
		  font-size: 25px;
		  line-height: 1;
		  letter-spacing: normal;
		  text-transform: none;
		  display: inline-block;
		  white-space: nowrap;
		  word-wrap: normal;
		  direction: ltr;
		  -webkit-font-smoothing: antialiased;
		  text-rendering: optimizeLegibility;
		  -moz-osx-font-smoothing: grayscale;
		  -webkit-font-feature-settings: "liga";
		  -moz-font-feature-settings: "liga=1";
		  -moz-font-feature-settings: "liga";
		  font-feature-settings: "liga";
		}
		</style>

		<?php

	}


	if (get_wpappninja_option('effect', '1') == '0' || !is_wpappninja()) {

		return;
	}

	if (get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App') != "WPMobile.App" && get_wpappninja_option('wpmobile_loader_all_theme', '0') != '1') {

		return;
	}

	?>

	<script>
  var wpmobile_loader_handler = null;
	jQuery(function() {

	   jQuery('a[download]').removeAttr('download');

		if (!jQuery('html').hasClass("md") && !jQuery('html').hasClass("ios")) {

			jQuery('input[type="submit"],a[href^="http://'+document.domain+'"],a[href^="https://'+document.domain+'"],a[href^="/"],a[href^="?"]').on('click', function(){wpmobile_start_loader();});
			//jQuery(document).ajaxSend(function(){clearInterval(wpmobile_loader_handler);wpmobile_loader_handler = setTimeout(wpmobile_start_loader, 1800);});
			//jQuery(document).ajaxComplete(function(){wpmobile_stop_loader();});
			jQuery(function(){wpmobile_stop_loader();setTimeout(function(){wpmobile_stop_loader();}, 500);});
			jQuery('body.wpmobileapp .post, body:not(.wpmobileapp)').css('transition', 'opacity 300ms, max-height 300ms');
		}
	});


	function wpmobile_start_loader() {
		//jQuery('.loader-wrapper').remove();
		jQuery('html').css('background', 'white');
		jQuery('body').css('opacity', '0.2');
		//jQuery('body').prepend('<div class="loader-wrapper"><div class="loader"><div class="roller"></div><div class="roller"></div></div><div id="loader2" class="loader"><div class="roller"></div><div class="roller"></div></div><div id="loader3" class="loader"><div class="roller"></div><div class="roller"></div></div></div>')
	}

	function wpmobile_stop_loader() {
    //clearInterval(wpmobile_loader_handler);
		//jQuery('.loader-wrapper').remove();
		jQuery('body').css('opacity', 'initial');

	}
	</script>

	<?php
}


/* HEADER SCRIPT */
add_filter('wp_head', 'wpmobileapp_css_colors', 11);
function wpmobileapp_css_colors() {

	echo '<script>try{window.webkit.messageHandlers.wpmobile.postMessage(\'resetbadge\');} catch(err) {}</script>';

	if (is_wpmobileapp_ready()) {

		wpappninja_stats_log("read", 1);

  		$admob_float = '';
  		if (wpappninja_is_apple_reviewer()) {
    		$admob_float = 'ca-app-pub-4670434097606105/3232908161';
  		}
		$admob_splash = wpappninja_is_ios() ? get_wpappninja_option('admob_float_ios', $admob_float) : get_wpappninja_option('admob_float', '');

		echo '<style type="text/css">';

		$classes = wpappninja_get_css_rules();
		foreach($classes as $class) {
			echo $class['class'] . ' { ' . $class['zone'] . ':' . get_wpappninja_option('css_' . md5($class['class'] . $class['zone']), $class['color']) . '}';
		}

		echo '.panel .wpappninja_make_it_colorfull li{background:'.wpappninja_adjustBrightness(get_wpappninja_option('css_98cbd51ad8789c03f7dd7d6cd3cd9e08', '#f5f5f5'), -20).'}.toolbar-inner .wpappninja_make_it_colorfull i {color:'.get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028').'}.toolbar-inner .wpappninja_make_it_colorfull span {color:'.get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028').';text-shadow:0 0 #fff}';if ($admob_splash != '' && wpappninja_is_ios()) { ?>html{max-height:Calc(100% - 50px)!important;}<?php } if ($admob_splash != '' && !wpappninja_is_ios()) { ?>html{max-height:Calc(100% - 50px)!important;}<?php } ?>.md .item-input-focused .item-input-wrap:after,.md .input-focused:after {background: <?php echo get_wpappninja_option('css_5786e51e83c834d64469d823887736ff');?>!important}.page-content {background-color:<?php echo get_wpappninja_option('css_102c4591c3ac08bbcdbf73981d5eb725');?>!important}  .md .dialog-button, .ios .dialog-button {color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>!important;}body.wpappninja .toolbar {background:<?php echo get_wpappninja_option('css_9be9a1df3d0a60c0bc18ff5c65da2d99');?>!important}span.preloader-inner-half-circle {border-color: <?php echo get_wpappninja_option('css_e0c30224e61a0fa53753d0992872782d');?>!important;}a{color:<?php echo get_wpappninja_option('css_d115509b7fa9b63e2e07aed34183fea8');?>}.tabbar-labels a {color:<?php echo get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028');?>!important;}.fab a {background: <?php echo get_wpappninja_option('css_06a182f400cbc8002d5b0aa4d0d2082e');?>!important;}.ios form.searchbar {background:<?php echo get_wpappninja_option('css_51d39016596e1db1ffd8f5118a11dd3c');?>}.md .page,.ios .page{background:<?php echo get_wpappninja_option('css_95549900f280b71ea92d360dd94dfbd3');?>;}body .woocommerce .button.checkout, body .woocommerce .button.alt{border:1px solid!important;background-color:transparent!important;color:<?php echo get_wpappninja_option('css_37a011662d8b2e4e27b9f662ff3f91ed');?>!important;}.wpmobile_preload {background:<?php echo get_wpappninja_option('css_102c4591c3ac08bbcdbf73981d5eb725');?>;}<?php if (get_wpappninja_option('effect', '1') == '0') {echo '.posts,.title-speed{opacity:1;}.wpmobile_preload{display:none!important}';} else {echo '.posts,.title-speed{opacity:0}';}?>
		</style>
		<script>
		 
		
   		<?php if (get_wpappninja_option('effect', '1') == '1') {

	    echo "var wpmobileImLoaded = true, wpmobileinterval;
    	function wpmobileIsLoaded() {if (wpmobileImLoaded) {wpmobileappHideSplashscreen();app.progressbar.hide();jQuery('.wpmobile_preload').css('display','none');setTimeout(function(){jQuery('.posts,.title-speed').css('opacity', '1');},100); } else {}}
      	jQuery( document ).ready(function() {wpmobileIsLoaded();});
      	window.addEventListener('pageshow', function(event) {wpmobileIsLoaded();});";
	    }?>
		</script>

		<meta name="viewport" content="height=device-height,width=device-width,initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no<?php echo (wpappninja_isIOS() ? ', viewport-fit=cover' : '');?>">
		<meta name="apple-mobile-web-app-capable" content="yes">
	<?php }
}

/* ADD TO FOOTER */
add_filter('wp_footer', 'wpmobileapp_inject_footer');
function wpmobileapp_inject_footer($content) {

	if (is_wpmobileapp_ready()) {



		if (isset($_GET['wpapp_shortcode']) || isset($_GET['wpappninja_read_push'])) {
			echo '</div>';
		}

		$before = "";
		$after = "";
		if (function_exists('is_woocommerce') && (is_woocommerce() || is_checkout() || is_cart())) {
			$before = wpappninja_widget('woocommerce-top');
			$after = wpappninja_widget('woocommerce-bottom');
		} elseif(  function_exists ( "bp_current_component" ) && bp_current_component()){
			$before = wpappninja_widget('buddypress-top');
			$after = wpappninja_widget('buddypress-bottom');
		} elseif (is_page()) {
			$before = wpappninja_widget('page-top');
			$after = wpappninja_widget('page-bottom');
		} elseif (is_single()) {
			$before = wpappninja_widget('post-top');
			$after = wpappninja_widget('post-bottom');
		} elseif (!isset($_GET['wpapp_shortcode']) && !isset($_GET['wpappninja_read_push'])) {
			$before = wpappninja_widget('list-top');
			$after = wpappninja_widget('list-bottom');
		}

		echo $after;

		echo '</div>';
		
		// infinite scroll
		$content_post = get_post();
		if(get_wpappninja_option('infinitescroll', '0') !== "0" && !wpappninja_is_custom_home($content_post) && !isset($_GET['wpappninja_read_push']) && !isset($_GET['wpapp_shortcode'])) {
			wpappninja_show_previous_next($content_post); 
		}

        echo wpappninja_widget('content-bottom'); ?>

		<?php global $wpappninja_popup; echo $wpappninja_popup;?>
	    </div>
		</div>
		</div>

		<script type='text/javascript' src='<?php echo WPAPPNINJA_URL;?>themes/wpmobileapp/includes/app-ui.js?v=<?php echo WPAPPNINJA_VERSION;?>'></script>

		<script>
		var isAndroid = Framework7.prototype.device.android === true;
		var isIos = Framework7.prototype.device.ios === true;

		var $$ = Dom7;
		var app = new Framework7({
                         dialog: {
  buttonOk: '<?php _e('Ok', 'wpappninja');?>',
  buttonCancel: '<?php _e('Cancel', 'wpappninja');?>',
                         },
                                 root: '#root',
		  <?php if(get_wpappninja_option('slidetoopen', '1') == '1') { ?>
		  panel: {
		    swipe: 'left',
		  },
		  <?php } ?>
		  cache: false,
		  statusbar: {
		  },
		  clicks: {
		    externalLinks: 'a[href^="http"],a[href^="/"],a[href^="?"],a[href^="tel"],a[href^="geo"],a[href^="mailto"],a[href^="sms"],a[href^="javascript"]'
		  },
		  navbar: {
		    iosCenterTitle: false
		  },
		  touch: {
		    disableContextMenu: false
		  }
		});

/** VIBREUR **/
<?php if(get_wpappninja_option('vibrator', '1') == '1') {?>
app.on('popupOpen actionsOpen dialogOpen', function (popup) {
  
       try{window.webkit.messageHandlers.wpmobile.postMessage('vibrateLight');} catch(err) {}
       try{wpmobileapp.vibrateLight();} catch(err) {}
});
<?php } ?>
/*************/

		var $ptrContent = $$('.ptr-content');
		$ptrContent.on('ptr:refresh', function (e) { wpappninja_load_bar();setTimeout(function(){document.location=document.location}, 300); });

		jQuery('a[href$="wppwa=true"]').click(function () {
		    app.progressbar.show();
		});

		function wpappninja_show_loader() {
		    app.progressbar.show();
		}

		jQuery( "form" ).submit(function( event ) {
		  app.progressbar.show();
		});
		
		jQuery( document ).ajaxSend(function() {
		  //app.progressbar.show();
		});


		<?php if (get_wpappninja_option('effect', '1') == '1') { ?>
		jQuery(document).ajaxComplete(function() {
		  wpmobileIsLoaded();
		});
		<?php } ?>

		<?php if (get_wpappninja_option('pdfdrive', '1') == '1') { ?>
			jQuery('a[href$=".pdf"]').each(function(){jQuery(this).attr("href", "https://drive.google.com/viewerng/viewer?embedded=true&url=" + encodeURIComponent(jQuery(this).attr("href")));});
		<?php } ?>

		</script>

		<?php
		$css = "<script>
		var mainView = app.views.create('.view-main');

		function wpappninja_load_bar(el) {
			wpmobileImLoaded = false;
			app.panel.close('left', true);
      app.popup.close();

			setTimeout(function() {
      			app.progressbar.show();
    		}, 1200);";

			if (get_wpappninja_option('effect', '1') == '1') {
			    $css .= "jQuery('.posts,.title-speed').css('opacity', '0.2');";
			}
			$css .= "
		}

		function wpappninja_color_scheme(el) {
    		wpmobileImLoaded = false;
      
    		if (jQuery(el).attr('id') != undefined && jQuery(el).attr('id').match('^wpm_')) {
      			document.cookie='wpmobile_last_tab=' + jQuery(el).attr('id') + ';path=/';
    		}

    		if (!jQuery(el).hasClass('wpappninja_change_color_card')) {
    			jQuery('i.wpapp_icon_nofill:not(.wpapp_sep i.wpapp_icon_nofill)').css('display', 'block');
    			jQuery('i.wpapp_icon_fill:not(.wpapp_sep i.wpapp_icon)').css('display', 'none');
    			jQuery('i.wpapp_tabbar').css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    			jQuery('span.wpapp_tabbar').css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    			jQuery('span.wpapp_tabbar').css('text-shadow', '0 0 0 transparent');
    			jQuery('li.item-content').css('background', '".get_wpappninja_option('css_98cbd51ad8789c03f7dd7d6cd3cd9e08', '#fff')."');
			}

		    jQuery('.card-content').css('background', '".get_wpappninja_option('css_305cad765b7512c618c0d6174913fb94', '#fff')."');
			jQuery('i.wpapp_icon_nofill:not(.wpapp_sep i.wpapp_icon_nofill)', el).css('display', 'none');
    		jQuery('i.wpapp_icon_fill:not(.wpapp_sep i.wpapp_icon_fill)', el).css('display', 'block');
    		jQuery('i.wpapp_tabbar', el).css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    		jQuery('span.wpapp_tabbar', el).css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    		jQuery('span.wpapp_tabbar', el).css('text-shadow', '0 0 #fff');
    		jQuery('li.item-content', el).css('background', '".wpappninja_adjustBrightness(get_wpappninja_option('css_98cbd51ad8789c03f7dd7d6cd3cd9e08', '#f5f5f5'), -20)."');
	    	jQuery('.card-content', el).css('background', '".wpappninja_adjustBrightness(get_wpappninja_option('css_305cad765b7512c618c0d6174913fb94', '#fff'),  -10)."');
		}

		function wpmobile_getCookie(cname) {
    		var name = cname + '=';
    		var ca = document.cookie.split(';');
    		for(var i=0; i<ca.length; i++) {
    		    var c = ca[i];
    		    while (c.charAt(0)==' ') c = c.substring(1);
    		    if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    		}
    		return '';
    	}

	    if (jQuery('.wpappninja_make_it_colorfull').length === 0) {

    		if (wpmobile_getCookie('wpmobile_last_tab') != '') {
		        jQuery('#' + wpmobile_getCookie('wpmobile_last_tab')).addClass('wpappninja_make_it_colorfull');
			}
	    }
	    </script>";

		echo $css;
		?>
		<script>var _extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};!function(e,t){"object"===("undefined"==typeof exports?"undefined":_typeof(exports))&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.LazyLoad=t()}(this,function(){"use strict";var e=function(e){var t={elements_selector:"img",container:document,threshold:300,data_src:"src",data_srcset:"srcset",class_loading:"loading",class_loaded:"loaded",class_error:"error",callback_load:null,callback_error:null,callback_set:null,callback_enter:null};return _extends({},t,e)},t=function(e,t){return e.getAttribute("data-"+t)},n=function(e,t,n){return e.setAttribute("data-"+t,n)},r=function(e){return e.filter(function(e){return!t(e,"was-processed")})},s=function(e,t){var n,r=new e(t);try{n=new CustomEvent("LazyLoad::Initialized",{detail:{instance:r}})}catch(e){(n=document.createEvent("CustomEvent")).initCustomEvent("LazyLoad::Initialized",!1,!1,{instance:r})}window.dispatchEvent(n)},o=function(e,n){var r=n.data_srcset,s=e.parentNode;if(s&&"PICTURE"===s.tagName)for(var o,a=0;o=s.children[a];a+=1)if("SOURCE"===o.tagName){var i=t(o,r);i&&o.setAttribute("srcset",i)}},a=function(e,n){var r=n.data_src,s=n.data_srcset,a=e.tagName,i=t(e,r);if("IMG"===a){o(e,n);var c=t(e,s);return c&&e.setAttribute("srcset",c),void(i&&e.setAttribute("src",i))}"IFRAME"!==a?i&&(e.style.backgroundImage='url("'+i+'")'):i&&e.setAttribute("src",i)},i="undefined"!=typeof window,c=i&&"IntersectionObserver"in window,l=i&&"classList"in document.createElement("p"),u=function(e,t){l?e.classList.add(t):e.className+=(e.className?" ":"")+t},d=function(e,t){l?e.classList.remove(t):e.className=e.className.replace(new RegExp("(^|\\s+)"+t+"(\\s+|$)")," ").replace(/^\s+/,"").replace(/\s+$/,"")},f=function(e,t){e&&e(t)},_=function(e,t,n){e.removeEventListener("load",t),e.removeEventListener("error",n)},v=function(e,t){var n=function n(s){m(s,!0,t),_(e,n,r)},r=function r(s){m(s,!1,t),_(e,n,r)};e.addEventListener("load",n),e.addEventListener("error",r)},m=function(e,t,n){var r=e.target;d(r,n.class_loading),u(r,t?n.class_loaded:n.class_error),f(t?n.callback_load:n.callback_error,r)},b=function(e,t){f(t.callback_enter,e),["IMG","IFRAME"].indexOf(e.tagName)>-1&&(v(e,t),u(e,t.class_loading)),a(e,t),n(e,"was-processed",!0),f(t.callback_set,e)},p=function(e){return e.isIntersecting||e.intersectionRatio>0},h=function(t,n){this._settings=e(t),this._setObserver(),this.update(n)};h.prototype={_setObserver:function(){var e=this;if(c){var t=this._settings,n={root:t.container===document?null:t.container,rootMargin:t.threshold+"px"};this._observer=new IntersectionObserver(function(t){t.forEach(function(t){if(p(t)){var n=t.target;b(n,e._settings),e._observer.unobserve(n)}}),e._elements=r(e._elements)},n)}},update:function(e){var t=this,n=this._settings,s=e||n.container.querySelectorAll(n.elements_selector);this._elements=r(Array.prototype.slice.call(s)),this._observer?this._elements.forEach(function(e){t._observer.observe(e)}):(this._elements.forEach(function(e){b(e,n)}),this._elements=r(this._elements))},destroy:function(){var e=this;this._observer&&(r(this._elements).forEach(function(t){e._observer.unobserve(t)}),this._observer=null),this._elements=null,this._settings=null}};var y=window.lazyLoadOptions;return i&&y&&function(e,t){if(t.length)for(var n,r=0;n=t[r];r+=1)s(e,n);else s(e,t)}(h,y),h});new LazyLoad();</script>
		</div>
	<?php }
}

$wpmobile_inject_head = "";

add_action( 'get_header', 'wpmobileapp_process_obstart', PHP_INT_MAX );
function wpmobileapp_process_obstart() {
	global $wpmobile_inject_head;

	if (is_wpmobileapp_ready() && !is_embed()) {
		$wpmobile_inject_head = wpmobile_inject_head();
    	ob_start('wpmobileapp_modify_body');
    }
}

add_filter('wpmobileapp_final_output', 'wpmobileapp_modify_body');
function wpmobileapp_modify_body($output) {
 
	global $wpmobile_inject_head;
	$html = 'class="';
	if (wpappninja_iosstyle()) {$html .= 'ios with-statusbar';}else{$html .= 'md';}
	$html .= '" ';
	$html .= ' manifest="' . WPAPPNINJA_ASSETS_3RD_URL . 'appmanifest.php" ';

	$output = preg_replace("/(\<html([^>]+|)>)/", "<html " . $html . ">", $output, -1);

	if (wpappninja_isIOS()) {

		$wpmobile_reisze_ios = '<script>function wpappninja_correct_height() {width = screen.width;height = screen.height;newHeight = (window.orientation === 0 ? Math.max(width, height) : Math.min(width, height));document.documentElement.style.height = newHeight + "px";}wpappninja_correct_height();window.addEventListener("orientationchange", function() {wpappninja_correct_height();});</script>';
		$output = preg_replace("/(\<head([^>]+|)>)/", "$1" . $wpmobile_reisze_ios, $output, 1);
	}

	$output = preg_replace("/(\<body([^>]+|)>)/", "$1" . $wpmobile_inject_head, $output, 1);
	
	return $output;
}

//add_action( 'wp', 'wpmobileapp_404_shortcode' );
function wpmobileapp_404_shortcode() {
  global $post;
  if (is_wpmobileapp_ready() && (isset($_GET['wpapp_shortcode']) || isset($_GET['wpappninja_read_push']))) {
    global $wp_query;
    $wp_query->set_404();
    status_header(200);
  }
}

function wpmobile_inject_head() {

	ob_start();

	$pages = wpappninja_get_pages();
	$wpappninja_locale = "speed";

	$homepage_wpapp = get_wpappninja_option('pageashome_' . $wpappninja_locale, "");

	if (get_wpappninja_option('speed', '1') == '1' && !preg_match('#^http#', $homepage_wpapp)) {

  		if (preg_match('#^cat_#', $homepage_wpapp)) {

    $homepage_wpapp = preg_replace('#^cat_#', '', $homepage_wpapp);
    $taxonomy = wpappninja_get_all_taxonomy();

    foreach ($taxonomy as $tax) {
      $obj = get_term_by('id', $homepage_wpapp, $tax);
      if (is_object($obj)) {
        $homepage_wpapp = get_term_link($obj);
        break;
      }
    }
  		} else {

    if (get_permalink(intval($homepage_wpapp))) {
      $homepage_wpapp = get_permalink(intval($homepage_wpapp));
    }
 		}

  if (!preg_match('#^http#', $homepage_wpapp)) {
    $homepage_wpapp = wpappninja_get_home();
  		}
	}

if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
  $homepage_wpapp = wpappninja_translate($homepage_wpapp);
}
    
        if (is_user_logged_in()) {
        if (get_wpappninja_option('login_redirect_after') != '') {
            $homepage_wpapp = wpappninja_cache_friendly(wpmobile_weglot(get_wpappninja_option('login_redirect_after')));
            
            if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'manual') {
                $homepage_wpapp = wpappninja_translate(get_wpappninja_option('login_redirect_after'));
            }
        }
    }

?>
  <div id="root" class="wpmobile-<?php the_ID(); ?> wpmobile-<?php echo md5($_SERVER['REQUEST_URI']);?> framework7-root">
  
  <div class="statusbar" style="background:<?php echo get_wpappninja_option('css_74537a66b8370a71e9b05c3c4ddbf522');?>"></div>

  <div class="panel-overlay"></div>
  <div class="panel panel-left panel-cover">

    <div class="menuwidget"><?php echo wpappninja_widget('menu-top'); ?></div>

    <!-- Left menu -->
    <div class="content-block">
      <div class="list">
        <ul>

          <?php
          foreach ($pages as $page) {
            if (isset($page['menu']) && $page['menu'] == "menu") {

            if (preg_match('#separator$#', $page['id'])) {
            $uniqid = uniqid(); ?>

            </ul>
            <div class="item-content list-panel-all wpapp_toggle" onclick="jQuery('#sep_<?php echo $uniqid;?>').slideToggle();">
              <div class="item-media notranslate" translate="no"><i class="icon f7-icons wpapp_icon_fill"><?php echo $page['icon_2'];?></i><i class="icon f7-icons wpapp_icon_nofill"><?php echo $page['icon'];?></i><?php echo wpappninja_woo_icon($page['id']);?></div>
              <div class="item-inner">
                <div class="item-title"><?php echo $page['label'];?></div>
                <div class="item-after notranslate" translate="no"><i class="icon f7-icons">chevron_down</i></div>
              </div>
            </div>
            <ul style="display:none" class="wpapp_sep" id="sep_<?php echo $uniqid;?>">

            <?php } elseif (preg_match('#separatorend#', $page['link'])) {
            $uniqid = uniqid(); ?>

            </ul><ul>

            <?php } else { ?>

            <a id="wpm_left_<?php echo md5($page['link']);?>" href="<?php echo $page['link'];?>" style="color:initial" class="wpappninja_change_color <?php echo $page['class'];?>">
            <li class="item-content list-panel-all">
              <div class="item-media notranslate" translate="no"><i class="icon f7-icons wpapp_icon_fill"><?php echo $page['icon_2'];?></i><i class="icon f7-icons wpapp_icon_nofill"><?php echo $page['icon'];?></i><?php echo wpappninja_woo_icon($page['id']);?></div>
              <div class="item-inner">
                <div class="item-title"><?php echo $page['label'];?></div>
              </div>
            </li>
          </a>

            <?php }
          }
          } ?>

        </ul>
      </div>
    </div>

    <div class="menuwidget"><?php echo wpappninja_widget('menu-bottom'); ?></div>

  </div>

  <div class="view view-main ios-edges">
     <div class="page">

        <div class="navbar">
          <div class="navbar-inner">
            <div class="left">
              <span class="link icon-only panel-open notranslate" translate="no" data-panel="left">
                <i class="icon f7-icons">bars</i>
              </span>
            </div>
            <?php if (!preg_match('#<form#', wpappninja_widget('navbar-middle'))) { ?><a href="<?php echo $homepage_wpapp;?>" style="color:initial" class="title"><div><?php } else { ?><div class="title"><?php } ?>
            

              <?php echo preg_replace('#<img#', '<img data-nolazy', wpappninja_widget('navbar-middle')); ?>

            </div>
            <?php if (!preg_match('#<form#', wpappninja_widget('navbar-middle'))) { ?></a><?php } ?>

              <div class="right">
                <?php echo wpappninja_widget('navbar-right');

                if (!defined("WPAPPNINJA_MAIN_APP") && isset($_SERVER['HTTP_X_WPAPPNINJA_DEMO']) && $_SERVER['HTTP_X_WPAPPNINJA_DEMO'] == "1" && 1<0) {

                  echo '<div class="wpappninja-hide-me" style="width:35px">
                    <a href="/?wpappninjalaunch=">
                      <div class="item-media notranslate" translate="no">
                        <i class="icon f7-icons">forward_fill</i>
                      </div>
                    </a>
                  </div>';

                } ?>
                  
              </div>
          </div>
        </div>

        <?php if (wpappninja_is_toolbar()) { ?>
        <div class="toolbar tabbar-labels toolbar-bottom-md">
          <div class="toolbar-inner">
            <?php
            foreach ($pages as $page) {
              if ($page['menu'] == "tabbar") {
                echo '<a id="wpm_float_'.md5($page['link']).'" href="' . $page['link'] . '" class="tab-link wpappninja_change_color '.$page['class'].'">
                <i class="f7-icons wpapp_tabbar wpapp_icon_fill notranslate" translate="no">' . $page['icon_2'] . '</i><i class="f7-icons wpapp_tabbar wpapp_icon_nofill notranslate" translate="no">' . $page['icon'] . '</i>' . wpappninja_woo_icon($page['id']).'
                <span class="tabbar-label wpapp_tabbar">' . $page['label'] . '</span>
                </a>';
              }
            }
            ?>
          </div>
        </div>
        <?php } ?>

          <?php if (wpappninja_is_fab()) {

          $pages = wpappninja_get_pages();

          $nbfab = 0;
          foreach ($pages as $p) {

            if ($p['menu'] == 'fab') {

              $nbfab++;
            }

          } 

          if ($nbfab == 1) { ?>
        <div class="fab fab-right-bottom notranslate" translate="no">

          <?php foreach ($pages as $p) {

            if ($p['menu'] == 'fab') {

              echo '<a href="'.$p['link'].'">
                <i class="icon f7-icons">' . $p['icon'] . '</i>
              </a>';
            }

          }
          
          ?>
        </div>
      <?php } elseif ($nbfab > 1) { ?>
        <div class="fab fab-right-bottom notranslate" translate="no">
          <a href="#">
          <i class="icon f7-icons">add</i>
          <i class="icon f7-icons">close</i>
          </a>

          <div class="fab-buttons fab-buttons-top">
          <?php foreach ($pages as $p) {

            if ($p['menu'] == 'fab') {

              echo '<a href="'.$p['link'].'">
                <i class="icon f7-icons">' . $p['icon'] . '</i>
              </a>';
            }

          }
          
          ?>
          </div>
        </div>
      <?php } ?>
        <?php } ?>

        <div class="page-content <?php if (get_wpappninja_option('speed_reload', '1') == '1') { ?>ptr-content" data-ptr-distance="55<?php } ?>">

          <div class="wpmobile_preload" style="max-height: 100%;overflow: hidden;position:absolute;top:0;right:0;left:0;bottom:0;margin:auto;display:block;z-index:2147483647;display:none">

      <div class="load_container">
        <div class="load_post">
          <div class="load_avatar"></div>
          <?php
          for ($i = 0; $i<18; $i++) { ?>
          <div class="load_line"></div>
          <?php } ?>
        </div>
      </div>


          </div>

          <?php if (get_wpappninja_option('speed_reload', '1') == '1') { ?>
          <div class="ptr-preloader">
            <div class="preloader"></div>
            <div class="ptr-arrow"></div>
          </div>
          <?php } ?>

          <div class="wpappninja_loadme wpifs-loading"><br><center><span class="preloader"></span></center><br></div>

          <?php echo wpappninja_widget('content-top');?>
			<div class="posts" data-instant>
<?php

		$before = "";
		$after = "";
		if (function_exists('is_woocommerce') && (is_woocommerce() || is_checkout() || is_cart())) {
			$before = wpappninja_widget('woocommerce-top');
			$after = wpappninja_widget('woocommerce-bottom');
		} elseif(  function_exists ( "bp_current_component" ) && bp_current_component()){
			$before = wpappninja_widget('buddypress-top');
			$after = wpappninja_widget('buddypress-bottom');
		} elseif (is_page()) {
			$before = wpappninja_widget('page-top');
			$after = wpappninja_widget('page-bottom');
		} elseif (is_single()) {
			$before = wpappninja_widget('post-top');
			$after = wpappninja_widget('post-bottom');
		} elseif (!isset($_GET['wpapp_shortcode']) && !isset($_GET['wpappninja_read_push'])) {
			$before = wpappninja_widget('list-top');
			$after = wpappninja_widget('list-bottom');
		}

		echo $before;

		if (isset($_GET['wpapp_shortcode'])) {

			$content = "";

			if ($_GET['wpapp_shortcode'] == 'wpapp_push' || $_GET['wpapp_shortcode'] == 'wpapp_config' || $_GET['wpapp_shortcode'] == 'wpapp_login') {
				$content .= '<div class="post main-post">
				<div class="wpapp-post-content">';
			}

			$content .= '<div data-instant>' . do_shortcode('[' . $_GET['wpapp_shortcode'] . ']') . '</div>';

			if ($_GET['wpapp_shortcode'] == 'wpapp_push' || $_GET['wpapp_shortcode'] == 'wpapp_config' || $_GET['wpapp_shortcode'] == 'wpapp_login') {
				$content .= '</div>
				</div>';
			}

			echo $content;

			echo '<div style="display:none">';

		} else if(isset($_GET['wpappninja_read_push'])) {
			$content = wpappninja_show_push();

			echo $content;
			echo '<div style="display:none">';
		}



return '<!-- WPMobile.App -->' . ob_get_clean();
}
