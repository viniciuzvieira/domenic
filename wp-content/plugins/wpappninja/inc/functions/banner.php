<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add a banner pointing to the Play Store for mobile user.
 *
 * @since 3.6.2
 */
add_action( 'wp_footer', 'wpappninja_banner', PHP_INT_MAX );
function wpappninja_banner() {

	if (isset($_SERVER['HTTP_X_WPAPPNINJA']) || defined( 'WPAPPNINJA_READ_ENHANCED' )) {


		if (wpappninja_is_ios()) { ?>
			<div id="wpappninja_force_complete" style="display:none"></div>
			<script type="text/javascript">
			document.onreadystatechange = function () {
				if (document.readyState == "complete") {
    				var iframe = document.createElement('iframe');
					var html = '1';
					iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(html);
					document.getElementById('wpappninja_force_complete').appendChild(iframe);
  				}
			}
			</script>
			<?php
		}
		return;
	}

	if (get_wpappninja_option('smartbanner', '') != '1') {
		return;
	}

	$js = "<script>function wpmobile_read_cookie(k) {
    return(document.cookie.match('(^|; )'+k+'=([^;]*)')||0)[2]
	}
	var isIOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
	var isAndroid = /(android)/i.test(navigator.userAgent);

	var packageAndroid = '".get_wpappninja_option('package', '')."';
	var textAndroid = '".__('In Google Play', 'wpappninja')."';
	var linkAndroid = 'https://play.google.com/store/apps/details?id=" . get_wpappninja_option('package') . "';

	var packageIOS = '".get_wpappninja_option('appstore_package', '')."';
	var textIOS = '".__('In App Store', 'wpappninja')."';
	var linkIOS = 'https://itunes.apple.com/app/id" . get_wpappninja_option('appstore_package', '')."';
	</script>";
	echo $js;

	$appdata = get_wpappninja_option('app');
	?>
	<style type="text/css">
	#wpappninja_banner {
		display: none;
		height: 66px;
		z-index: 2147483647;
		background: #ffffff;
		border-bottom: 1px solid #f9f9f9;
	    box-shadow: 0 0 1px #999;
	    padding: 3px 0;
		position: absolute;
		top: 0;
		width: 100%;
		text-align: center;
	}
	#wpappninja_banner .wpappninja_banner_close {
		color: gray;
		font-size: 14px;
		vertical-align: top;
		display: inline-block;
		margin: 19px 0 0 0;
	}
	#wpappninja_banner .wpappninja_banner_logo {
	    vertical-align: top;
	    display: inline-block;
	    margin: 6px 12px;
	    width: 48px;
	    height: 48px;
	    padding: 3px;
		border-radius: 8px;
		background: <?php echo $appdata['ios_background'];?>;
	}
	#wpappninja_banner .wpappninja_banner_text {
		color: #333;
		line-height: 10px;
		font-size: 13px;
		text-align: left;
		vertical-align: top;
		display: inline-block;
		margin: 10px 0px 0 0;
		width: Calc(100% - 200px);
	    max-height: 55px;
	    overflow: hidden;
	    padding-top: 2px;
	}
	#wpappninja_banner .wpappninja_banner_text span {
		color:#696969;
		font-size:11px;
	}
	#wpappninja_banner .wpappninja_banner_click {
		display: inline-block;
		background: <?php echo wpappninja_color_inverse(wpappninja_get_hex_color(false));?>;
		padding: 3px 10px;
		font-size: 16px;
		color: white;
		vertical-align: top;
		margin: 16px 0px 0 10px;
	}
	#wpappninja_banner .wpappninja_banner_click img {
	    width: 20px;
		vertical-align: middle;
	}
	</style>
	
	<div id="wpappninja_banner">
		<div class="wpappninja_banner_close" onclick="document.cookie = 'wpappninja_disable_banner=true;expires=<?php echo date('r', (time()+86400*30));?>;path=/';document.getElementById('wpappninja_banner').style.display = 'none';document.body.style.marginTop = '0px';" src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>close_icon.png">
			&#10005;
		</div>
		<div class="wpappninja_banner_logo">
			<img src="<?php echo $appdata['logo'];?>" width="48" height="48" />
		</div>
		<div class="wpappninja_banner_text">
			<b><?php echo $appdata['name']; ?></b><br/><br/>
			<span><?php _e('FREE', 'wpappninja');?><br/></span>
			<span id="wpmobile_banner_text"></span>
		</div>
		<div class="wpappninja_banner_click">
			<?php _e('VIEW', 'wpappninja');?>
		</div>
	</div>
	
	<script>
	if (isAndroid && packageAndroid != "" && wpmobile_read_cookie("wpappninja_disable_banner") != "true") {
		jQuery("#wpmobile_banner_text").text(textAndroid);
		jQuery(".wpappninja_banner_click").on('click', function() {document.location = linkAndroid;});
		jQuery("#wpappninja_banner").css('display', 'block');
		jQuery("body").css('margin-top', '66px');
	}

	if (isIOS && packageIOS != "" && packageIOS != "xxx" && wpmobile_read_cookie("wpappninja_disable_banner") != "true") {
		jQuery("#wpmobile_banner_text").text(textIOS);
		jQuery(".wpappninja_banner_click").on('click', function() {document.location = linkIOS;});
		jQuery("#wpappninja_banner").css('display', 'block');
		jQuery("body").css('margin-top', '66px');
	}
	</script>
	
	<?php
}

/**
 * Invert the color for banner link.
 *
 * @since 7.0.21
 */
function wpappninja_color_inverse($color){
    
    if (strtolower($color) == "#ffffff") {
    	return "#979797";
    }

    return $color;
}
