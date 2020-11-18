<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * QR Code.
 *
 * @since 5.2
 */
function _wpappninja_display_qrcode_page() {

	global $wpdb;

	// suppression
	if (isset($_POST['wpappninja_delete_qrcode']) && check_admin_referer( 'wpappninja-delete-qrcode-' . $_POST['wpappninja_delete_qrcode'] )) {
		$idDelete = sanitize_text_field($_POST['wpappninja_delete_qrcode']);
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_qrcode WHERE `id` = %s", $idDelete));
	}

	if (isset($_POST['wpappninja_qrcode_url']) && check_admin_referer('wpappninjaqrcode')) {

		$link = sanitize_text_field($_POST['wpappninja_qrcode_url']);
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_qrcode (`link`) VALUES (%s)", $link));
	}

	if (isset($_POST['wpappninja_qrcode_menu']) && check_admin_referer('wpappninjaqrcodemenu')) {
		$option = get_option(WPAPPNINJA_SLUG);
		$option['qrcode'] = sanitize_text_field($_POST['wpappninja_qrcode_menu']);
		update_option(WPAPPNINJA_SLUG, $option);
	}
	?>

	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2></h2>
			
		<?php $menu_current = 'qrcode';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
			
		<div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;">
            
			<a href="#" onclick="wpappninja_open_editor();return false" class="button button-primary"><?php _e('New QR Code', 'wpappninja');?></a>

			<?php if(get_wpappninja_option('speed') != '1') {if (get_wpappninja_option('qrcode') != '1') { ?>
			<a href="#" onclick="jQuery('#wpappninja_enable_value').val('1');jQuery('#wpappninja_enable').click();return false" style="background-color:darkred;border:1px solid darkred" class="button button-primary"><?php _e("In-app QR Code scanner: DISABLED", "wpappninja");?></a>
			<?php } else { ?>
			<a href="#" onclick="jQuery('#wpappninja_enable_value').val('0');jQuery('#wpappninja_enable').click();return false" style="background-color:darkgreen;border:1px solid darkgreen" class="button button-primary"><?php _e("In-app QR Code scanner: ENABLED", "wpappninja");?></a>
			<?php }} ?>

			<form action="" method="post" style="display:none">
			<?php wp_nonce_field( 'wpappninjaqrcodemenu' );?>
			<input type="hidden" id="wpappninja_enable_value" name="wpappninja_qrcode_menu" vaue="" />
			<input type="submit" id="wpappninja_enable" />
			</form>


			<form action="" method="post" id="wpappninjaqrcode_insert" style="display:none">

				<?php wp_nonce_field( 'wpappninjaqrcode' );?>

				<textarea style="display:none" id="wpappninja_dummy_textarea"></textarea>

				<script type="text/javascript">

				function wpappninja_open_editor() {
            		wpActiveEditor = true;
            		wpLink.open('wpappninja_dummy_textarea');
    		        return false;
		        }

		        jQuery('body').on('click', '#wp-link-submit', function(event) {
           			var linkAtts = wpLink.getAttrs();
		            jQuery('#wpappninja_qrcode_url').val(linkAtts.href);
				    jQuery('#submitme').click();
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
        		.has-text-field #wp-link .query-results {top: 80px!important;}
        		#link-options, #link-options .link-target {display:none!important;}
        		</style>

				<input type="url" name="wpappninja_qrcode_url" id="wpappninja_qrcode_url" />
				<input type="submit" id="submitme" class="button button-primary button-large" />
			</form>

			<?php
			echo '<div style="display:none">';
			wp_editor( "", 'wpappninja_dummy', array(
												'media_buttons' => true,
												'teeny' => false,
												'textarea_name' => 'wpappninja_dummy'
										) );
			echo '</div>';

			echo '<table class="form-table">';

				$query = $wpdb->get_results("SELECT `link`, `id` FROM {$wpdb->prefix}wpappninja_qrcode ORDER BY `id` DESC");
				foreach($query as $obj) {

					$deeplink = urlencode(preg_replace('/^http[s]?/', wpappninja_deeplink_scheme(), $obj->link));
					
					echo '<tr valign="top">
						<th scope="row" style="vertical-align:middle"><a href="' . $obj->link . '" target="_blank">' . $obj->link . '</a></th>
						<td><a href="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $deeplink . '&choe=UTF-8" target="_blank"><img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=' . $deeplink . '&choe=UTF-8" /></a></td>

						<td>

							<form action="'.admin_url('admin.php?page=' . WPAPPNINJA_QRCODE_SLUG).'" method="post">'.wp_nonce_field( 'wpappninja-delete-qrcode-' . $obj->id ).'<input type="hidden" name="wpappninja_delete_qrcode" value="'.$obj->id.'" /><input style="background: none;border: 0;text-decoration: underline;color: red;margin-left: 20px;" type="submit" value="'.__('Delete').'" /></form>

						</td>
					</tr>';
				}
				
			echo '</table>';
			?>

		</div>
	</div>

	<div style="clear:both"></div>

	<?php
	echo wpappninja_talkus();
}
