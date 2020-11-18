<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Promote the app.
 *
 * @since 4.2.0
 */
function _wpappninja_display_promote_page() {

	if (!empty($_POST) && check_admin_referer('wpappninja_smartbanner')) {
	
		if (isset($_POST['wpappninja_smartbanner'])) {

			$smart_banner = sanitize_text_field($_POST['wpappninja_smartbanner']);
			$option = get_option(WPAPPNINJA_SLUG);
			$option['smartbanner'] = $smart_banner;
			update_option(WPAPPNINJA_SLUG, $option);
		}
	}

	$appdata = get_wpappninja_option('app');
	?>

	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2></h2>
			
		<?php $menu_current = 'promote';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
			
		<div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;">

			<?php
			if (!isset($_GET['settings'])) { ?>



			<?php
			if (!get_option('wpappninja_app_published')) {
				echo '<div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;">' . __("Your app is not yet live on stores, you can't promote it", 'wpappninja') . '</div>
			<br/><br/>';
			} else { ?>
			<a class="button button-primary" href="?page=<?php echo WPAPPNINJA_PROMOTE_SLUG;?>&settings"><?php _e('settings', 'wpappninja');?></a>
			<br/><br/><br/>
			<?php if (get_wpappninja_option('package', '') != '') { ?>

				<div style="float:left;width:40%;min-width:320px;border-right:3px solid #fd9b02;padding-right:4%">
				    <a target="_blank" href="https://play.google.com/store/apps/details?id=<?php echo get_wpappninja_option('package', '');?>"><img src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>playstore.png" width="100%" /></a>
			    	<br/><br/>

			    	<a style="border:0!important;background:#3b5998!important" target="_blank" class="button button-primary" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3D<?php echo get_wpappninja_option('package', '');?>">Facebook</a>

					<a style="border:0!important;background:#00aced!important" target="_blank" class="button button-primary" href="https://twitter.com/home?status=%20https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3D<?php echo get_wpappninja_option('package', '');?>">Twitter</a>

					<a style="border:0!important;background:#007bb6!important" target="_blank" class="button button-primary" href="https://www.linkedin.com/shareArticle?mini=true&url=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3D<?php echo get_wpappninja_option('package', '');?>&title=&summary=&source=">LinkedIn</a>
			    	<br/>
				</div>
			<?php } ?>
            
            <?php if (get_wpappninja_option('appstore_package', '') != '') { ?>
				<div style="float:left;width:40%;min-width:320px;padding-left:4%">
				    <a target="_blank" href="http://appstore.com/<?php echo get_wpappninja_option('appstore_package', '');?>"><img src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>appstore.png" width="100%" /></a>
			    	<br/><br/>

			    	<a style="border:0!important;background:#3b5998!important" target="_blank" class="button button-primary" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fappstore.com%2F<?php echo get_wpappninja_option('appstore_package', '');?>">Facebook</a>

					<a style="border:0!important;background:#00aced!important" target="_blank" class="button button-primary" href="https://twitter.com/home?status=%20http%3A%2F%2Fappstore.com%2F<?php echo get_wpappninja_option('appstore_package', '');?>">Twitter</a>

					<a style="border:0!important;background:#007bb6!important" target="_blank" class="button button-primary" href="https://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fappstore.com%2F<?php echo get_wpappninja_option('appstore_package', '');?>&title=&summary=&source=">LinkedIn</a>
				    <br/>
				</div>
			<?php } ?>
			<div style="clear:both"></div>
			<br/><br/>

			<?php }
			} else { ?>
			<div class="wpappninja_div">
				<p class="wpappninja_help"><?php _e('Display a banner on top of your site pointing on the store for your mobile visitors.', 'wpappninja');?></p>
			</div>
			<div class="wpappninja_div">					
				<style type="text/css">	
	#wpappninja_banner {
		height: 66px;
		background: #ffffff;
		border-bottom: 1px solid #f9f9f9;
	    box-shadow: 0 0 1px #999;
	    padding: 3px 0;
		width: 400px;
		text-align: center;
	}
	#wpappninja_banner .wpappninja_banner_close {
		color: gray;
		font-size: 14px;
		vertical-align: top;
		display: inline-block;
		margin: 24px 15px 0 0;
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
		font-size: 15px;
		text-align: left;
		vertical-align: top;
		display: inline-block;
		margin: 11px 30px 0 0;
	}
	#wpappninja_banner .wpappninja_banner_text span {
		color:#696969;
		font-size:11px;
	}
	#wpappninja_banner .wpappninja_banner_click {
		display: inline-block;
		background: <?php echo wpappninja_get_hex_color(false);?>;
		padding: 3px 10px;
		font-size: 16px;
		color: white;
		vertical-align: top;
		margin: 21px 37px 0 10px;
	}
	#wpappninja_banner .wpappninja_banner_click img {
	    width: 20px;
		vertical-align: middle;
	}
	</style>


	<div id="wpappninja_banner">
		<div class="wpappninja_banner_close" src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>close_icon.png">
			&#10005;
		</div>
		<div class="wpappninja_banner_logo">
			<img src="<?php echo $appdata['logo'];?>" width="48" height="48" />
		</div>
		<div class="wpappninja_banner_text">
			<b><?php echo $appdata['name']; ?></b><br/><br/>
			<span><?php _e('FREE', 'wpappninja');?><br/></span>
			<span><?php _e('In App Store', 'wpappninja');?></span>
		</div>
		<div class="wpappninja_banner_click">
			<?php _e('VIEW', 'wpappninja');?>
		</div>
	</div>

				<form action="" method="post">
				<?php wp_nonce_field('wpappninja_smartbanner'); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Show the smart banner', 'wpappninja');?></th>
						<td><select name="wpappninja_smartbanner"><option value="1"><?php _e('Yes', 'wpappninja');?></option><option value="0" <?php if (get_wpappninja_option('smartbanner') === '0'){echo 'selected';}?>><?php _e('No', 'wpappninja');?></option></select></td>
					</tr>
				</table>

				<?php submit_button(); ?>
				</form>
			</div>

			<?php } ?>

		</div>
	</div>

	<div style="clear:both"></div>

	<?php
	echo wpappninja_talkus();
}
