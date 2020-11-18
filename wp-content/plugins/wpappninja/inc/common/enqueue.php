<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add custom css on webview
 */
add_action( 'wp_head', 'wpappninja_webview', 100 );
function wpappninja_webview() {

	if (!is_wpappninja()) { ?>
		<style type="text/css"><?php echo get_wpappninja_option('customcss_website', ''); ?></style>
		<script type="text/javascript"><?php echo get_wpappninja_option('customjs_website', ''); ?></script>
	<?php } else { ?>
		
		<style type="text/css"><?php echo get_wpappninja_option('customcss', ''); ?></style>
		<script type="text/javascript"><?php echo get_wpappninja_option('customjs', ''); ?></script>

		<script>

		function wpmobileappHideSplashscreen() {
	    	try {
      			window.webkit.messageHandlers.callbackHandler.postMessage("hideSplashscreen");
    		} catch(err) {}

    		try{window.webkit.messageHandlers.wpmobile.postMessage('loaded');} catch(err) {}

	    	try {
      			wpmobileapp.hideSplashscreen();
    		} catch(err) {}
		}

		setTimeout(wpmobileappHideSplashscreen, <?php echo get_wpappninja_option('fastsplash', '500'); ?>);
		</script>

		<?php
		$admob_float = '';
  		if (wpappninja_is_apple_reviewer()) {
    		$admob_float = 'ca-app-pub-4670434097606105/3232908161';
  		}
	    $admob_float = wpappninja_is_ios() ? get_wpappninja_option('admob_float_ios', $admob_float) : get_wpappninja_option('admob_float', '');

	  	if ($admob_float != '' && wpappninja_is_ios()) { ?>

			<style>html{max-height:Calc(100% - 50px)!important;}</style>
	    <?php }

    	if ($admob_float != '' && !wpappninja_is_ios()) { ?>

			<style>html{max-height:Calc(100% - 50px)!important;}</style>
  		<?php } ?>



  		<script>
  		jQuery(function(){
  			if (document.cookie.match(/^(.*;)?\s*wpmobile_go_settings\s*=\s*[^;]+(.*)?$/)) {
            	document.cookie = "wpmobile_go_settings=false; expires=Fri, 31 Dec 2000 23:59:59 GMT; path=/;";
            	jQuery('.wpmobilegosettings').remove();
        	    jQuery('body').append('<a href="<?php echo home_url( '' );?>/?wpapp_shortcode=wpapp_config" class="wpmobilegosettings"></a>');
    	        jQuery('.wpmobilegosettings')[0].click();
    	    }

    	    jQuery(document).on('click', '.dialog-backdrop', function(){app.dialog.close();});
	    });

  		</script>

  		<style>.title p {
    margin: 0;
}.title img{width:auto!important;min-width:1px!important;max-width:100%!important;height:auto!important;min-height:1px!important;max-height:42px!important;}.panel span.badge {
    left: auto!important;
    right: 0px;
    top: 23px!important;
}
  		.button{height:auto!important;}
	  	</style>

	<?php }

	if (isset($_GET['iswpappninjaconfigurator'])) { ?>
		<style type="text/css">
		#wpadminbar{display:none!important}html.no-touch{margin-top:0!important}
		body img.sg_selected, body .sg_selected, body .sg_suggested {border-color:#f12525!important;background-color: #f12525 !important;background-image: none !important;}
		body .sg_reject, body .sg_rejected{border-color:transparent!important;background-color:transparent!important;}
		body div#_sg_div {display:none!important;}
		</style>
	<?php }

	if (isset($_GET['wpappninja_simul4'])) { ?>

		<style type="text/css">
		html {
	    	overflow: scroll;
	    	overflow-x: hidden;
		}
		::-webkit-scrollbar {
	    	width: 0px;
	    	background: transparent;
		}

		</style>

	<?php }
	
	if (isset($_SERVER['HTTP_X_WPAPPNINJA_NIGHT'])) {
		if ($_SERVER['HTTP_X_WPAPPNINJA_NIGHT'] == '1') { ?>
			<style type="text/css"><?php echo get_wpappninja_option('customcss_night', ''); ?></style>
		<?php }
	}
}

add_action('wp_footer', 'wpmobile_footer_common');
function wpmobile_footer_common() {

	if (is_wpappninja()) { ?>

		<script type="text/javascript">
		jQuery.ajaxPrefilter(function( options ) {
			if ( options.crossDomain ) {
				var scheme = options.url.split(":");
				if (scheme[0] != "http" && scheme[0] != "https") {
					options.url = options.url.replace(scheme[0] + ":", location.protocol);
				}
			}
		});
		jQuery( document ).ajaxSend(function( event, xhr, settings ) {
		  var regExp = new RegExp("//" + location.host + "($|/)");
		  if( settings.url.substring(0,4) !== "http" || regExp.test(settings.url) ) {
		    xhr.setRequestHeader('X-WPAPPNINJA', '1');
		    xhr.setRequestHeader('X-WPMOBILEAPP-WEB', '1');
		  }
		});

		<?php if (isset($_GET['wpappninja_simul4'])) { ?>
			function wpmobile_add_link() {
				jQuery('a[href^="http://'+document.domain+'"],a[href^="https://'+document.domain+'"],a[href^="/"],a[href^="?"]').each(function() {
  					this.href += (/\?/.test(this.href) ? '&' : '?') + 'wpappninja=true&wpappninja_simul4=true';
				});
			}
			jQuery( document ).ajaxComplete(wpmobile_add_link());
			jQuery(document).ready(wpmobile_add_link());
		<?php } ?>

		function wpmobile_share_link() {
			jQuery('a[href*="wpmobileshareme"]').each(function() {

					var shareurl = window.location.href;
					shareurl = shareurl.replace(/&?[wpappninja_v|is_wppwa|wpappninja_cache]+=([^&]$|[^&]*)/ig, "");
					shareurl = shareurl.replace(/\?$/ig, "");

  					this.href = '/?wpappninjasharetext=' + encodeURIComponent(document.title + ' ') + '&wpappninjasharelink=' + encodeURIComponent(shareurl);
			});
		}
		if (typeof LazyLoad !== "undefined") {
			jQuery( document ).ajaxComplete(function(){
				new LazyLoad();
			});
		}

		jQuery(document).ajaxComplete(function(){
			wpmobile_share_link();
		});

		jQuery(document).ready(function(){
			wpmobile_share_link();
		});
		</script>
	
	<?php }
}

/**
 * Add custom body class
 */
add_filter( 'body_class', 'wpappninjaclasses');
function wpappninjaclasses( $classes ) {

	if (is_wpappninja()) {
	    $classes[] = 'wpappninja';
	}

	return $classes;

}
