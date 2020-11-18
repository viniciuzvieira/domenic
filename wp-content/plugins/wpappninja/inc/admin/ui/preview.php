<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * QR Code.
 *
 * @since 5.2
 */
function _wpappninja_display_preview_page() {
				$default_homepage = "";
				if (get_wpappninja_option('speed') == '1') {
					$default_homepage = wpappninja_get_home();
				}

				$homepage_wpapp = get_wpappninja_option('pageashome_speed', $default_homepage);

	if (!preg_match('#^http#', $homepage_wpapp)) {

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

	if (!$homepage_wpapp || $homepage_wpapp == "") {
		
		$homepage_wpapp = get_home_url();
		if (get_wpappninja_option('speed') == '1') {
			$homepage_wpapp = wpappninja_get_home();
		}
	}

	if ($homepage_wpapp == "") {
		$homepage_wpapp = "/";
	}
		if (is_user_logged_in()) {
	        if (get_wpappninja_option('login_redirect_after') != '') {
               	$homepage_wpapp = get_wpappninja_option('login_redirect_after');
       		}
		}
	?>

	<style>.wpappiframe{
    width: 720px;
    border: 20px solid #333;
    height: 1480px;
    border-radius: 20px;
    zoom: 0.5;
    -ms-zoom: 0.75;
    -moz-transform: scale(0.75);
    -moz-transform-origin: 0 0;
    -o-transform: scale(0.75);
    -o-transform-origin: 0 0;
    -webkit-transform: scale(0.75);
    -webkit-transform-origin: 0 0;
}.button span {
    vertical-align: text-top;
}

.preview_icon {
    display: block;
    width: 250px;
    background: #f5f5f5;
    float: left;
    border-right: 1px solid #fd9b02;
}.preview_icon a.button {
    width: 100%;
    border-radius: 0;
    box-shadow: 0 0 0 #fff;
    height: auto;
    padding: 10px 10px;
    border: 0;
    border-bottom: 1px solid #eee;
}.wpappiframe {
    display: inline-block;
    float: left;
    margin-left: 60px;
    max-width: 2000px;
}.preview_icon a {display:block;}
</style>

<script>
	function wpappninja_change_size(type) {
jQuery('.wpappiframesplash').css('display', 'block');jQuery('.wpappiframeapp').css('display', 'none');setTimeout(function(){jQuery('.wpappiframesplash').css('display', 'none');jQuery('.wpappiframeapp').css('display', 'block');}, 900);
		ratio = 2;

		if (type == "laptop") {
			w = (1366 * (ratio - 0.6));
			h = (768 * (ratio - 0.6));
		}

		if (type == "tablet") {
			w = (2048 / (ratio - 0.6));
			h = (1536 / (ratio - 0.6));
		}

		if (type == "smartphone") {
			w = (1440 / ratio);
			h = (2960 / ratio);
		}

		if (type == "orientation") {
			w = jQuery(".wpappiframe").height();
			h = jQuery(".wpappiframe").width();
		}

		jQuery(".wpappiframe").css('width', w + 'px');
		jQuery(".wpappiframe").css('height', h + 'px');
	}

</script>
	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2></h2>
			
		<?php $menu_current = 'preview';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' );
		$app_data = get_wpappninja_option('app');
		$name = (isset($app_data['name'])) ? $app_data['name'] : "";
		$logo = (isset($app_data['logo'])) ? $app_data['logo'] : "";
		$array = array('name'=>$name, 'url'=>home_url( '/' ), 'img'=>$logo);
		$json = json_encode($array);

		$lang = "";
		$mylang = wpappninja_get_lang();
		if ($mylang != "fr") {
			$lang = $mylang."/";
		}

		 ?>
			
		<div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;">

			<div class="preview_icon">

			<!--<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo urlencode("demowpapp://wpmobile.app/".$lang."2018-espace-client/?wpmobile_add=".base64_encode($json));?>" width="200" height="200" />
			<br/>-->

<div style="background:white;text-align:center;padding-top:30px;padding-bottom: 20px">
<a href="https://support.wpmobile.app/article/62-can-i-test-my-android-and-ios-mobile-app" target="_blank"><img style="max-width: 85%;height: auto;" src="https://cdn.wpmobile.app/wp-content/uploads/2016/05/appstoreen.png" alt="appstore" width="284" height="84" /></a><br/><a href="https://support.wpmobile.app/article/62-can-i-test-my-android-and-ios-mobile-app" target="_blank"><img style="max-width: 85%;height: auto;" src="https://cdn.wpmobile.app/wp-content/uploads/2016/05/en_badge_web_generic.png" alt="fr_badge_web_generic" width="282" height="84" /></a>

</div>




			<a href="#" onclick="wpappninja_change_size('laptop');return false" class="button "><span class="dashicons dashicons-laptop"></span> <?php _e('Chromebook', 'wpappninja');?></a>
			<a href="#" onclick="wpappninja_change_size('tablet');return false" class="button "><span class="dashicons dashicons-tablet"></span> <?php _e('Tablet', 'wpappninja');?></a>
			<a href="#" onclick="wpappninja_change_size('smartphone');return false" class="button"><span class="dashicons dashicons-smartphone"></span> <?php _e('Smartphone', 'wpappninja');?></a>
			<a href="#" onclick="wpappninja_change_size('orientation');return false" class="button"><span class="dashicons dashicons-update"></span> <?php _e('Orientation', 'wpappninja');?></a>


			<script>
			function wpmobile_open_preview() {
 
 				var url = "<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true&wpappninja_my_theme=<?php echo get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App');?>";
		       newwindow = window.open(url,'wpmobilepreview','height=600,width=335,top=70,left='+(screen.width - 385));
		       if (window.focus) {newwindow.focus()}
       			return false;
     		}
	     	</script>

			<a  class="button" href="javascript:wpmobile_open_preview()"><span class="dashicons dashicons-editor-code"></span> <?php _e('Open in a new tab', 'wpappninja');?></a>


			</div>
			
            
							<?php if (get_wpappninja_option('speed', '1') == '1') { ?>

			<a href="#" style="margin-left:30px" onclick="jQuery('.wpappiframesplash').css('display', 'block');jQuery('.wpappiframeapp').css('display', 'none');return false" class="button"><span class="dashicons dashicons-external"></span> <?php _e('Splashscreen', 'wpappninja');?></a>
			<a href="#" onclick="jQuery('.wpappiframesplash').css('display', 'none');jQuery('.wpappiframeapp').css('display', 'block');return false" class="button"><span class="dashicons dashicons-smartphone"></span> <?php _e('App', 'wpappninja');?></a>


			<?php /*<script src="<?php echo WPAPPNINJA_ASSETS_URL . 'js/';?>html2canvas.min.js"></script>
			<script>
			function wpmobileTakeScreen() {

				var devices = {
				    'iphonex': [
    				  ['300', '1200']
				 	],
				};

				var element_screen = jQuery('#wpappiframe').contents().find('body')[0];

				for (var device in devices) {

					html2canvas(element_screen, {

						useCORS: true,
						logging: true,

						width: devices[device][0][0],
						height: devices[device][0][1]

					}).then(function(canvas) {

				    	var a = document.createElement('a');
						a.href = canvas.toDataURL("image/png");
  						a.download = device + '.png';
  						a.click();
					});

				}
			}
			</script>

			<br/>
			<a href="#" style="margin:15px 0 15px 30px" onclick="wpmobileTakeScreen();return false" class="button"><span class="dashicons dashicons-welcome-view-site"></span> <?php _e('Take a screenshot', 'wpappninja');?></a>*/?>


			<br/><br/>

			<?php
			if (!isset($app_data['splashscreen']) OR $app_data['splashscreen'] == "" OR preg_match('#/wpappninja/assets/images/os/empty\.png$#', $app_data['splashscreen'])) {$app_data['splashscreen'] = "https://my.wpmobile.app/_launchscreen.php?c=" . str_replace('#', '', $app_data['theme']['primary']) . "&l=" . $app_data['logo'];}
			?>

							<div class="wpappiframe wpappiframesplash" style="display:block;background:url(<?php echo $app_data['splashscreen'];?> ) no-repeat center center;background-size: cover;"></div>
							<iframe  style="display:none" name="wpappiframe" class="wpappiframe wpappiframeapp" id="wpappiframe" src="<?php echo $homepage_wpapp;?><?php echo (parse_url($homepage_wpapp, PHP_URL_QUERY) ? '&' : '?'); ?>wpappninja=true&wpappninja_simul4=true<?php if(get_wpappninja_option('appify') == '1') {echo '&fakepwa=1';}else{echo '&wpappninja_my_theme='.get_wpappninja_option('wpappninja_main_theme', 'WPMobile.App');}?>"></iframe>

							<script>setTimeout(function(){jQuery('.wpappiframesplash').css('display', 'none');jQuery('.wpappiframeapp').css('display', 'block');}, 900);</script>

							<?php } else { ?>

							<a style="margin: 25px auto;display: inline-block;font-size: 27px;text-align: center;width: 100%;line-height: 47px;" href="https://wpmobile.app/demo-android-ios/" target="_blank"><?php _e('You have to test on a real device', 'wpappninja');?></a>

							<?php } ?>

							<div style="clear:both"></div>

		</div>
	</div>

	<div style="clear:both"></div>

	<?php
	echo wpappninja_talkus();
}
