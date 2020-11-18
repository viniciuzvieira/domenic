<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Cnfigurator.
 *
 * @since 5.2
 */
function _wpappninja_display_pwa_page() {

	if (isset($_POST['wpappninja_pwa_menu']) && check_admin_referer('wpappninjapwamenu')) {

		if ($_POST['wpappninja_pwa_menu'] == "1" OR $_POST['wpappninja_pwa_menu'] == "0") {
			update_option('wpappninja_pwa_home', $_POST['wpappninja_pwa_menu']);
		}

	}

	if (isset($_POST['wpappninja_progressive_app']) && check_admin_referer('wpappninja_progressive_app')) {

		update_option('wpappninja_progressive_app', $_POST['wpappninja_progressive_app']);

	}

	$pwa_data = get_option('wpappninja_progressive_app');
	?>
	<style type="text/css">
	input[type="text"], select {
		width: 350px;
		border: 2px solid #eee!important;
		box-shadow: 0 0 0 #fff!important;
		font-size: 20px;
		background: #fdfdfd;
		box-sizing: content-box;
		padding: 2px;
		}textarea {
			padding: 2px 6px;
			line-height: 1.4;
			width: 360px!important;
			border: 2px solid #eee;
			box-shadow: 0 0 0 #fff;
			font-family: courier;
			font-size: 19px;
			}input#submitme {
				font-size: 25px!important;
				box-sizing: initial;
				height: auto;
				line-height: initial!important;
				width: 320px!important;
				padding: 10px 150px!important;
				margin: 25px auto;
				display: block;
				}       h2{color:#555;font-size:33px;}
				#wpappninja_app_store_data{max-width:100%!important;}
				.wpappninja-builder {
					padding:15px;
					font-size: 18px;
					background:#fff;
					border-bottom:1px solid #eee;
				}
				.wpappninja-builder:hover {
					background: #ffe;
				}
				.wpappninja-builder-left {
					width: 300px;
					padding: 6px;
					float: left;
				}
				.wpappninja-builder-right {
					float:left;
				}

				.selectapptype label {
					display: inline-block;
					background:#f9f9f9;
					padding: 25px;
					width:49%;box-sizing: border-box;
				}

				.selectapptype label:hover{
					background:#fff;
					box-shadow:0 0 4px #eee;
				}

			</style>
	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2></h2>




		<?php $menu_current = 'pwa';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
		<div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;font-size:20px;">

<?php

$text = "";

if ($menu_current == 'push') {
    $url = "https://support.wpmobile.app/article/80-how-to-send-a-notification-when-a-post-is-published?lang=".wpmobile_getSupportLang()."";
    $text = __('Learn how to send a notification when you publish', 'wpappninja');
     
} else if (isset($_GET['page']) && $_GET['page'] == WPAPPNINJA_PWA_SLUG) {
    $url = "https://support.wpmobile.app/article/112-progressive-web-app-wordpress?lang=".wpmobile_getSupportLang()."";
    $text = __('Progressive Web App is a free feature', 'wpappninja');
     
} else if (isset($_GET['page']) && $_GET['page'] == WPAPPNINJA_ADSERVER_SLUG) {
    $url = "https://support.wpmobile.app/article/44-can-i-embed-advertising-on-my-mobile-app?lang=".wpmobile_getSupportLang()."";
    $text = __('Learn how to turn on advertising on the app', 'wpappninja');
     
}  ?>


<?php if ($text != "") { ?>
    <div class="wpappninja_help" style="box-shadow: 0 0 0;margin-bottom: 26px;border-left: 5px solid #4CAF50;background: #f8fdf4">
    <?php echo $text;?> <b><a style="display: inline-block;margin-left: 17px;font-size:17px;" target="_blank" href="<?php echo $url;?>"><?php _e('+ more', 'wpappninja');?></a></b>
</div>
<?php } ?>


			<?php if (get_option('wpappninja_pwa_home') != '1') { ?>
			<a href="#" onclick="jQuery('#wpappninja_enable_value').val('1');jQuery('#wpappninja_enable').click();return false" style="background-color:darkred;border:1px solid darkred" class="button button-primary"><?php _e("Progressive Web App: DISABLED", "wpappninja");?></a>
			<?php } else { ?>
			<a href="#" onclick="jQuery('#wpappninja_enable_value').val('0');jQuery('#wpappninja_enable').click();return false" style="background-color:darkgreen;border:1px solid darkgreen" class="button button-primary"><?php _e("Progressive Web App: ENABLED", "wpappninja");?></a>
			<?php } ?>

			<form action="" method="post" style="display:none">
				<?php wp_nonce_field( 'wpappninjapwamenu' );?>
				<input type="hidden" id="wpappninja_enable_value" name="wpappninja_pwa_menu" vaue="" />
				<input type="submit" id="wpappninja_enable" />
			</form>

			<form action="" method="post">
				<?php wp_nonce_field( 'wpappninja_progressive_app' );?>
				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Design', 'wpappninja');?></h2>
				<div class="wpappninja_div">
					<div class="wpappninja-builder">

						<div class="wpappninja-builder-left">
							<?php _e('Name', 'wpappninja');?>
						</div>
						<div class="wpappninja-builder-right">

							<input type="text" name="wpappninja_progressive_app[name]" value="<?php echo $pwa_data['name'];?>" />

						</div>
						<div class="clear"></div>
					</div>

					<div class="wpappninja-builder">

						<div class="wpappninja-builder-left">
							<?php _e('Logo', 'wpappninja');?>
						</div>
						<div class="wpappninja-builder-right">

							<?php if ($pwa_data['logo'] != "") {
								echo '<img src="' . $pwa_data['logo'] . '" width="128" height="128" class="wpapp_logo_place" />';
							} ?>

							<div class="uploader">
								<input id="blog_logo" name="wpappninja_progressive_app[logo]" type="text" value="<?php echo $pwa_data['logo'];?>" required style="display:none" />
								<input id="blog_logo_button" class="button" name="blog_logo_button" type="text" value="<?php _e('Choose a logo', 'wpappninja');?>" />
							</div>

						</div>
						<div class="clear"></div>
					</div>

					<div class="wpappninja-builder">

						<div class="wpappninja-builder-left">
							<?php _e('Main color', 'wpappninja');?>
						</div>
						<div class="wpappninja-builder-right">

							<input type="text" class="wpapp-color-picker-primary" name="wpappninja_progressive_app[color]" value="<?php echo $pwa_data['color'];?>" />

						</div>
						<div class="clear"></div>
					</div>
				</div>


				<h2 style="background: #f5f5f5;padding: 15px;cursor: pointer;"><span class="dashicons dashicons-arrow-down-alt2"></span> <?php _e('Content', 'wpappninja');?></h2>
				<div class="wpappninja_div">
					<div class="wpappninja-builder">

						<div class="wpappninja-builder-left">
							<?php _e('Homepage', 'wpappninja');?>
						</div>
						<div class="wpappninja-builder-right">

							<input type="text" id="wpappninja_push_link" name="wpappninja_progressive_app[homepage]" value="<?php echo $pwa_data['homepage'];?>" /> <a href="#" onclick="wpappninja_open_editor();return false"><?php _e('Select page', 'wpappninja');?></a>

						</div>
						<div class="clear"></div>
					</div>

					<div class="wpappninja-builder">

						<div class="wpappninja-builder-left">
							<?php _e('Pages to preload and cache', 'wpappninja');?>
							<br/><small><?php _e('One per line', 'wpappninja');?></small>
						</div>
						<div class="wpappninja-builder-right">

							<textarea rows="10" name="wpappninja_progressive_app[pages]"><?php echo $pwa_data['pages'];?></textarea>

						</div>
						<div class="clear"></div>
					</div>

				</div>

				<input type="hidden" name="wpappninja_progressive_app[version]" value="<?php echo ($pwa_data['version'] + 1);?>" />

				<br/><br/>
				<input type="submit" id="submitme" class="button button-primary button-large" />
				<br/>
			</form>

		</div>
	</div>
	<script>
	jQuery(document).ready(function($){
	var wpapp_color_primary = {
	    palettes: true
	};
	jQuery("input.wpapp-color-picker-primary").wpColorPicker(wpapp_color_primary);

	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;

	$('#blog_logo_button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$("#"+id).val(attachment.url);
				$(".wpapp_logo_place").attr("src", attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}

		wp.media.editor.open(button);
		return false;
	});

	});
	</script>

				<div style="display:none"><?php
				wp_editor( "", "wpappninja_dummy_textarea", array(
								'media_buttons' => true,
								'teeny' => false,
								'textarea_name' => 'wpappninja_dummy_textarea'
						) ); ?>
				</div>

				<script type="text/javascript">

				function wpappninja_open_editor() {
            		wpActiveEditor = true;
            		wpLink.open('wpappninja_dummy_textarea');
    		        return false;
		        }

		        jQuery('body').on('click', '#wp-link-submit', function(event) {
           			var linkAtts = wpLink.getAttrs();
				    jQuery('#wpappninja_push_link').val(linkAtts.href);
		            wpLink.textarea = jQuery('body');
				    wpLink.close();
		            event.preventDefault ? event.preventDefault() : event.returnValue = false;
    		        event.stopPropagation();
        		    return false;
        		});

        		jQuery('body').on('click', '#wp-link-cancel, #wp-link-close', function(event) {
       				wpLink.textarea = jQuery('body');
			        wpLink.close();
    			    event.preventDefault ? event.preventDefault() : event.returnValue = false;
        			event.stopPropagation();
        			return false;
    			});
        		</script>

        		<style type="text/css">
        		.link-search-wrapper{margin-top:16px}#wplink-link-existing-content, #link-options .wp-link-text-field, #link-options .link-target {display:none!important;}
        		</style>
	<?php
	echo wpappninja_talkus();
}
