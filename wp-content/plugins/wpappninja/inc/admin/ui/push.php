<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * The main push page construtor
 *
 * @since 1.0
 */
function _wpappninja_display_push_page() {

	delete_transient( 'is_wpappninja_ajax' );

	global $wpdb;
    
    
    
    if (isset($_POST['wpappninja_delete_push_history']) && check_admin_referer( 'wpappninja-delete-push-history' )) {
        
        
        if ($_POST['wpappninja_delete_push_history'] == "0") {
            $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_push WHERE `sended` != %s", '0'));
        } elseif ($_POST['wpappninja_delete_push_history'] == "30") {
            
            $deleterange = round(current_time('timestamp') - 30*86400);
            $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_push WHERE sended != '0' AND send_date < $deleterange");
        } elseif ($_POST['wpappninja_delete_push_history'] == "30") {
                   
                   $deleterange = round(current_time('timestamp') - 30*86400);
                   $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_push WHERE sended != '0' AND send_date < $deleterange");
               } elseif ($_POST['wpappninja_delete_push_history'] == "90") {
                          
                          $deleterange = round(current_time('timestamp') - 90*86400);
                          $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_push WHERE sended != '0' AND send_date < $deleterange");
                      } elseif ($_POST['wpappninja_delete_push_history'] == "365") {
                                 
                                 $deleterange = round(current_time('timestamp') - 365*86400);
                                 $wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_push WHERE sended != '0' AND send_date < $deleterange");
                             }
        
        //$wpdb->query("DELETE FROM {$wpdb->prefix}wpappninja_stats_users");
    }
    
    
    
    
	
	$lang_array = wpappninja_available_lang();

	if (isset($_GET['postID'])) {

		$temp_post_id = urldecode($_GET['postID']);

		if (filter_var($temp_post_id, FILTER_VALIDATE_URL)) {
			$_GET['postID'] = wpappninja_url_to_postid($temp_post_id);
		}
	}
	
	if (isset($_POST['NOTIFICATIONSPUSH'])) {

		/*****/
		$nbCron = 0;			
		foreach (_get_cron_array() as $cron) {
			if (key($cron) == 'wpappninjacron'){
				$nbCron++;
			}
		}
				
		if ($nbCron != 1) {
			wp_clear_scheduled_hook( 'wpappninjacron' );
			wp_schedule_event( time(), 'wpappninja_every_three_minutes', 'wpappninjacron' );
		}
		/*****/
		
		// insertion d'un cron
		if (isset($_POST['wpappninjapush_insert']) && check_admin_referer( 'wpappninja-add-push' )) {
			if ($_POST['wpappninjapush_insert'] != '') {
				$timestamp = strtotime(sanitize_text_field($_POST['wpappninjapush_insert_timestamp']));
				$id_post = sanitize_text_field($_POST['wpappninja_push_link']);

				if (wpappninja_url_to_postid($_POST['wpappninja_push_link']) != 0) {
					$id_post = wpappninja_url_to_postid($_POST['wpappninja_push_link']);
				}

				if ($id_post == '') {
					$id_post = '-1';
				}

				$title = sanitize_text_field($_POST['wpappninjapush_insert_titre']);
				$content = sanitize_text_field($_POST['wpappninjapush_insert_msg']);
				$content = mb_substr(wp_strip_all_tags($content), 0, 255);
				$image = sanitize_text_field($_POST['wpappninjapush_image']);
				$lang = sanitize_text_field($_POST['wpappninjapush_lang']);

				$customcat = sanitize_text_field($_POST['wpappninjapush_category']);

				$updateID = sanitize_text_field($_POST['wpappninjapush_updateid']);
				if ($updateID != '') {
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push SET `id_post` = %s, `titre` = %s, `message` = %s, `image` = %s, `send_date` = %s, `lang` = %s, `category` = %s WHERE `id` = %s", $id_post, $title, $content, $image, $timestamp, $lang, $customcat, $updateID));
				} else {
					/*if ($lang == 'all') {
						
						foreach ($lang_array as $name => $code) {
							$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push (`id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `category`) VALUES (%s, %s, %s, %s, %s, %s, %s)", $id_post, $title, $content, $image, $timestamp, $code, $customcat));
						}
					*///} else {
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push (`id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `category`) VALUES (%s, %s, %s, %s, %s, %s, %s)", $id_post, $title, $content, $image, $timestamp, $lang, $customcat));
					//}
				}
			}
		}
	
		// suppression notification
		if (isset($_POST['supprimer_notif']) && check_admin_referer( 'wpappninja-delete-push-' . $_POST['supprimer_notif'] )) {
			$idDelete = sanitize_text_field($_POST['supprimer_notif']);
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push SET `sended` = %s WHERE `send_date` = %s", '2', $idDelete));
		}

		// delete history
		if (isset($_POST['purge']) && check_admin_referer( 'wpappninja-purge-push' )) {
			$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_push WHERE `sended` != %s", '0'));
			wpappninja_clear_cache();
		}
	}

	if (isset($_POST['wpappninjacertform'])) {
	if (check_admin_referer('wpappninja_cert')) {
	
		if (isset($_FILES['wpappninja_ios_cert'])) {

			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
	
			if (!defined('ALLOW_UNFILTERED_UPLOADS')) {
				define( 'ALLOW_UNFILTERED_UPLOADS', true );
			}
				
			$cert = $_FILES['wpappninja_ios_cert'];
			if ($cert['name'][0]) {
				$uploadedfile = array(
					'name'     => uniqid() . '.txt',
					'type'     => 'text/plain',
					'tmp_name' => $cert['tmp_name'][0],
					'error'    => $cert['error'][0],
					'size'     => $cert['size'][0]
				);
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				if ( $movefile && ! isset( $movefile['error'] ) ) {				
					update_option('wpappninja_pem_file', $movefile['file']);
					echo '<div class="updated notice"><p>' . __('Upload successful', 'wpappninja') . '</p></div>';
				} else {
					/**
					 * Error generated by _wp_handle_upload()
					 * @see _wp_handle_upload() in wp-admin/includes/file.php
					*/
					echo '<div class="error notice"><p>' . $movefile['error'] . '</p></div>';
				}
			}
		}

		if (isset($_FILES['wpappninja_google_json'])) {

			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
	
			if (!defined('ALLOW_UNFILTERED_UPLOADS')) {
				define( 'ALLOW_UNFILTERED_UPLOADS', true );
			}
		
			$cert = $_FILES['wpappninja_google_json'];
			if ($cert['name'][0]) {
				$uploadedfile = array(
					'name'     => uniqid() . '.json',
					'type'     => 'text/plain',
					'tmp_name' => $cert['tmp_name'][0],
					'error'    => $cert['error'][0],
					'size'     => $cert['size'][0]
				);
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				if ( $movefile && ! isset( $movefile['error'] ) ) {				
					update_option('wpappninja_google_json', $movefile['file']);
					echo '<div class="updated notice"><p>' . __('Upload successful', 'wpappninja') . '</p></div>';
				} else {
					/**
					 * Error generated by _wp_handle_upload()
					 * @see _wp_handle_upload() in wp-admin/includes/file.php
					*/
					echo '<div class="error notice"><p>' . $movefile['error'] . '</p></div>';
				}
			}
		}

		$apipush = sanitize_text_field($_POST['apipush']);
		$project = sanitize_text_field($_POST['project']);
		$customiconnotif = sanitize_text_field($_POST['customiconnotif']);
		$push_category = sanitize_text_field($_POST['push_category']);

		$welcome = sanitize_text_field($_POST['send_welcome_push']);
		$welcome_title = sanitize_text_field($_POST['welcome_title']);
		$welcome_subtitle = sanitize_text_field($_POST['welcome_subtitle']);
		$welcome_content = sanitize_text_field($_POST['welcome_content']);

		$redirection_type = sanitize_text_field($_POST['wpappninja_redirection_type']);
		$iosjusttitle = sanitize_text_field($_POST['iosjusttitle']);

		$option = get_option(WPAPPNINJA_SLUG);
		$option['apipush'] = $apipush;
		$option['project'] = $project;
		$option['send_welcome_push'] = $welcome;
		$option['welcome_titre_speed'] = $welcome_title;
		$option['welcome_speed'] = $welcome_subtitle;
		$option['bienvenue_speed'] = $welcome_content;
		$option['customiconnotif'] = $customiconnotif;
		$option['push_category'] = $push_category;
		$option['redirection_type'] = $redirection_type;
		$option['iosjusttitle'] = $iosjusttitle;

		update_option(WPAPPNINJA_SLUG, $option);

		if (isset($_POST['wpappninja_disable_autopush'])) {
			$wpappninja_disable_autopush = $_POST['wpappninja_disable_autopush'];
			if ($wpappninja_disable_autopush == '1') {
				update_option('wpappninja_disable_autopush', true);
			} else {
				update_option('wpappninja_disable_autopush', false);
			}
		}

		if (isset($_POST['wpmobile_auto_mail'])) {
			$option = get_option(WPAPPNINJA_SLUG);
			$option['wpmobile_auto_mail'] = $_POST['wpmobile_auto_mail'];
			update_option(WPAPPNINJA_SLUG, $option);
		}
        
        if (isset($_POST['wpmobile_auto_wc'])) {
            $option = get_option(WPAPPNINJA_SLUG);
            $option['wpmobile_auto_wc'] = $_POST['wpmobile_auto_wc'];
            update_option(WPAPPNINJA_SLUG, $option);
        }

        
        if (isset($_POST['wpmobile_auto_peepso'])) {
            $option = get_option(WPAPPNINJA_SLUG);
            $option['wpmobile_auto_peepso'] = $_POST['wpmobile_auto_peepso'];
            update_option(WPAPPNINJA_SLUG, $option);
        }

        
        if (isset($_POST['wpmobile_auto_gravity'])) {
            $option = get_option(WPAPPNINJA_SLUG);
            $option['wpmobile_auto_gravity'] = $_POST['wpmobile_auto_gravity'];
            update_option(WPAPPNINJA_SLUG, $option);
        }

        
		if (isset($_POST['wpmobile_auto_bp'])) {
			$option = get_option(WPAPPNINJA_SLUG);
			$option['wpmobile_auto_bp'] = $_POST['wpmobile_auto_bp'];
			update_option(WPAPPNINJA_SLUG, $option);
		}

		if (isset($_POST['wpmobile_auto_post'])) {
			$option = get_option(WPAPPNINJA_SLUG);
			$option['wpmobile_auto_post'] = $_POST['wpmobile_auto_post'];
			update_option(WPAPPNINJA_SLUG, $option);
		}

		if (isset($_POST['wpmobile_auto_post_update'])) {
			$option = get_option(WPAPPNINJA_SLUG);
			$option['wpmobile_auto_post_update'] = $_POST['wpmobile_auto_post_update'];
			update_option(WPAPPNINJA_SLUG, $option);
		}

		wpappninja_clear_cache();
	}
	}
	
	//wpappninja_cron();
	
	$colorTheme = "#fd9b02";
	
	$pushID = '';
	?>
	
	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2 style="font-size:1.3em"></h2>
			
		<?php $menu_current = 'push';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
			
		<div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;">
<?php

$text = "";

if ($menu_current == 'push') {
    //$url = "https://support.wpmobile.app/article/80-how-to-send-a-notification-when-a-post-is-published?lang=".wpmobile_getSupportLang()."";
    //$text = __('Learn how to send a notification when you publish', 'wpappninja');
     
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
		<?php if (!wpappninja_is_paid()) {
			echo '<div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;">' . __("You can't send notification until the app is published", 'wpappninja') . '</div>
			<br/><br/>';
		} /*elseif (!get_option('wpappninja_app_published')) {
			echo '<div class="wpappninja_help" style="border-left: 5px solid #c10033;background: #ffffd8;">' . __("Your app is not yet live on stores, you can't send notification", 'wpappninja') . '</div>
			<br/><br/>';
		}*/

		if (isset($_GET['settings'])) { ?>

			<?php _wpappninja_display_cert_page(); ?>
		<?php } else { ?>
			
			<a href="#" onclick="jQuery('#pushpreview_step').toggle();return false" class="button button-primary"><?php _e('+ New notification', 'wpappninja');?></a> <?php if (current_user_can( wpappninja_get_right() )){?><a style="display: inline-block;margin: 4px 0 0 20px;" href="?page=<?php echo WPAPPNINJA_PUSH_SLUG;?>&settings"><?php _e('settings', 'wpappninja');?></a><?php } ?>

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

				<?php $sub = $wpdb->get_results("SELECT COUNT(id) as sub FROM {$wpdb->prefix}wpappninja_ids");
				
				
				$ios = $wpdb->get_results("SELECT COUNT(id) as ios FROM {$wpdb->prefix}wpappninja_ids WHERE `registration_id` LIKE '_IOS_%'");


		echo '<div class="wpappninja_stats_box_inner" style="margin-top:25px;width: 500px;">
		<div style="padding:4px;border: 1px solid #e5e5e5;background:white;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04);">';

		echo '<div style="font-size: 40px;padding: 50px 0;border-bottom: 1px solid #eee;text-align: center;"><b>'.@round($sub[0]->sub).'</b></div>';

		echo '<div style="font-size: 20px;display:inline-block;width:50%;padding: 28px 0 20px;border-right: 1px solid #eee;text-align: center;">';
		echo '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/ios.png" > ' . @round($ios[0]->ios);
		echo '</div><div style="font-size: 20px;display:inline-block;width:49%;padding: 28px 0 20px;text-align: center;">';
		echo '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'os/android.png" > ' . @round($sub[0]->sub - $ios[0]->ios);
		echo '</div>';
		echo '</div>
		</div>';


		
			echo '<style>#wpappninja_hist_cont:hover{opacity:1!important}</style><div id="wpappninja_hist_cont" style="transition: all 0.4s;width:500px;margin-right:25px;opacity: 1;">';






		
				// historique
				$limit = 0;
				$number = 50;
				if (isset($_GET['limit'])) {
					$limit = round($_GET['limit'] * $number);
				}
				
				$query = $wpdb->get_results($wpdb->prepare("SELECT `id`, `id_post`, `titre`, `message`, `image`, `send_date`, `sended`, `log`, `lang` FROM {$wpdb->prefix}wpappninja_push WHERE `sended` != %s GROUP BY send_date ORDER BY `send_date` DESC LIMIT %d,%d", '2', $limit, $number));
				foreach($query as $obj) {
					
					$devices = 0;
					$mylog = "";

					$query_stats = $wpdb->get_results($wpdb->prepare("SELECT log FROM {$wpdb->prefix}wpappninja_push WHERE send_date = %s", $obj->send_date));
					foreach ($query_stats as $o) {

						preg_match_all("/OK[ ]?: ([0-9]+)/im", $o->log, $out);

						foreach ($out[1] as $oo) {
							$devices += $oo;
						}

						$mylog .= $o->log;
					}
				
					// deja envoye ou non ?
					$annuler = '';
					$isDeleted = false;
					$stats = '';
					if ($obj->sended == '2') {
						$isDeleted = true;
						$icon = '<b style="color:darkred">&#10007;</b> ';
						$annuler = '<span><br/>'.__('deleted - not sended', 'wpappninja').'</span>';
					} else if ($obj->send_date > current_time('timestamp') || $obj->sended == '0') {
						$icon = '<b style="color:darkorange">&#x1F55C;</b> ';
						$annuler = '<a style="font-size: 14px;color:red" href="#" onclick="jQuery(\'#wpappninja_d_n_'.$obj->send_date.'\').submit();return false">'.__('Delete', 'wpappninja').'</a>';
					} else {
						$icon = '<b style="color:darkgreen">&#10003;</b> ';
						$id = uniqid();

						/*if ($devices > 0) {
							$stats = '<b>' . $devices . '</b> ' . _n('device', 'devices', $devices, 'wpappninja');
						} else {
							$stats = '0 device';
						}*/
						//$annuler = '<br/><br/><a href="#" onclick="jQuery(\'#wpapp_log_'.$id.'\').toggle();jQuery(this).toggle();return false" style="color:gray;font-size:11px;">Log</a><textarea style="font-size:11px;width:80%;height:90px;display:none" id="wpapp_log_'.$id.'">'.strip_tags(preg_replace('#<br/>#', "\r\n", $obj->log)).'</textarea>';
					}
			
					$image = $obj->image;
			
					$avant = __('%s ago', 'wpappninja');
					if ($obj->send_date > current_time('timestamp')) {$avant = __('in %s', 'wpappninja');}
					
					echo '<div style="position:relative;    margin-bottom: 15px;">' . $icon . ' ' . $stats . ' ' . sprintf($avant, human_time_diff( $obj->send_date, current_time('timestamp') )) . '
						 ' . $annuler .'</div>';


					echo '<div class="pushpreview pushpreview_block" style="width: 500px;border: 1px solid #eee;box-shadow: inset 0 0 5px #eee;">
						<div class="pushpreview_texte">
							<b>'.stripslashes($obj->titre).'</b><br/>
							'.stripslashes($obj->message).'
						</div>
						<div class="clear"></div>';
						if ($image != '' && $image != " ") {echo '<div class="pushpreview_image" style="background-image:url('.$image.')"></div>';}
						echo '<div class="clear"></div>
					</div>
					<div class="clear" style="height:0px"></div>';


					echo '<form id="wpappninja_d_n_'.$obj->send_date.'" style="margin: -20px 0 50px 380px;display:block" action="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID').'" method="post">'.wp_nonce_field( 'wpappninja-delete-push-' . $obj->send_date ).'<input type="hidden" name="NOTIFICATIONSPUSH" value="1" /><input type="hidden" name="supprimer_notif" value="'.$obj->send_date.'" /><input style="background: none;border: 0;text-decoration: underline;color: red;margin-left: 20px;" type="submit" value="'.__('Delete', 'wpappninja').'" /></form>';
				}
				echo '<div style="border:1px solid #ccc;padding: 15px 0;background:#fff;text-align:center;font-size: 14px;max-width: 320px;">';
				$query_count = $wpdb->get_results($wpdb->prepare("SELECT COUNT(id) as count FROM {$wpdb->prefix}wpappninja_push WHERE `sended` != %s", '2'));
				$nb_page = ($query_count[0]->count / $number);
				$limit = 0;
				if (isset($_GET['limit'])) {
					$limit = round($_GET['limit']);
				}
				if ($nb_page > 0 && $limit > 0) {echo '<a href="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID&limit='.($limit-1)).'">&lt; '.__('Previous', 'wpappninja').'</a> &nbsp;&nbsp;&nbsp; ';}
				for($i = 0;$i < $nb_page;$i++) {
					if ($limit == $i) {echo '<strong>';}
					echo '<a href="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID&limit='.$i).'">'.($i + 1).'</a> ';
					if ($limit == $i) {echo '</strong>';}
				}
				if ($nb_page > 0 && $limit + 1 < $nb_page) {echo ' &nbsp;&nbsp;&nbsp; <a href="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID&limit='.($limit+1)).'">'.__('Next', 'wpappninja').' &gt;</a> &nbsp;&nbsp;&nbsp; ';}
				echo '</div>';
				
                    
    echo '<div style="clear:both"></div>
    <br/><br/>
    <br/><br/>
    <br/><br/>
    <form action="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID').'" method="post">
        '.wp_nonce_field( 'wpappninja-delete-push-history' ).'
        <select name="wpappninja_delete_push_history">';?>
        <option value="365"><?php _e('Delete push older than 365 days', 'wpappninja');?></option>
        <option value="90"><?php _e('Delete push older than 90 days', 'wpappninja');?></option>
        <option value="30"><?php _e('Delete push older than 30 days', 'wpappninja');?></option>
        <option value="0"><?php _e('Delete push', 'wpappninja');?></option>
        </select>
        <?php
        echo '<input style="background: none;border: 0;text-decoration: underline;color: red;margin-left: 20px;" type="submit" value="'.__('Delete pushs (CANT BE UNDONE)').'" />
    </form>';
                    
				//echo '<form action="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID').'" method="post">'.wp_nonce_field( 'wpappninja-purge-push').'<input type="hidden" name="NOTIFICATIONSPUSH" value="1" /><input type="hidden" name="purge" value="ok" /><input style="background: none;border: 0;text-decoration: underline;color: red;margin-top: 20px;" type="submit" value="'.__('Delete history', 'wpappninja').'" /></form>';
				
			echo '</div>';?>

			<form action="<?php echo admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID');?>" method="post" id="wpappninjapush_insert" >

				<?php wp_nonce_field( 'wpappninja-add-push' );?>
				<input type="hidden" name="NOTIFICATIONSPUSH" value="1" />
				<input type="hidden" name="wpappninjapush_insert" value="1" />


		
				<div id="pushpreview_step" style="z-index: 999;display:block;position: absolute;top: 50px;height: auto;width: 600px;left: 0;right: 0;margin: auto;padding: 20px;background: #fefefe;box-shadow: 0 0 1000px #555;border: 1px solid #eee;display:none">
					
					<a href="#" onclick="jQuery('#pushpreview_step').css('display', 'none');return false" style="display: inline-block;margin-bottom: 25px;float: right;"><?php _e('Close', 'wpappninja');?></a><br/>
					<?php
					if (1>0) {
						if (1>0){
							$featPostID = "";

							if (isset($_GET['postID'])) {
								$featPostID = sanitize_text_field($_GET['postID']);
							}

							$currentHour	= date('d-m-Y H:i', current_time('timestamp'));

							$current_cat_perso = "";

							if ($featPostID == '0' || $featPostID == "") {
								$featPostID		= "-1";
								$titre			= "";
								$excerpt		= "";
								$image			= '';
							} else if (preg_match('#^_#', $featPostID)) {
								
								$pushID			= preg_replace('#^_#', '', $featPostID);
								$editPush 		= $wpdb->get_row($wpdb->prepare("SELECT `id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `category` FROM {$wpdb->prefix}wpappninja_push WHERE `id` = %s", $pushID));
								
								$current_cat_perso = $editPush->category;
								$featPostID		= $editPush->id_post;
								$titre			= $editPush->titre;
								$excerpt		= $editPush->message;
								$image			= $editPush->image;
								$currentHour	= date('d-m-Y H:i', $editPush->send_date);
								$langImmutable	= $editPush->lang;
							} else {
								$titre			= get_the_title($featPostID);
								$content_post	= get_post($featPostID);
								$content 	 	= $content_post->post_content;
								$excerpt		= wp_trim_words($content, 20);
								$image			= wpappninja_get_image($featPostID);
							}

							$currentLink = get_permalink($featPostID);
							if (!$currentLink) {
								$currentLink = $featPostID;
							}

							if ($currentLink == "-1") {
								$currentLink = "";
							}
							
							echo '<input type="hidden" name="wpappninjapush_updateid" value="'.$pushID.'" />
							<input type="hidden" name="wpappninjapush_insert_id_post" id="wpappninjapush_insert_id_post" value="'.$featPostID.'" />
							<div id="pushpreview_started" style="display:block">
								<div id="pushpreview" style="display:none;float: right;width: 330px;">
									<div class="pushpreview_texte" style="width: 89%;">
										<b id="pushpreview_titre">'.$titre.'</b><br/>
										<span id="pushpreview_message">'.mb_substr(preg_replace('#"#', '\"', html_entity_decode(strip_tags(str_replace(array("\r", "\n")," ", $excerpt)))), 0, 255).'</span>
									</div>
									<div class="clear"></div>';
									echo '<div id="pushpreview_image" class="pushpreview_image" style="background-image:url('.$image.')"></div>';
									echo '<div class="clear"></div>
								</div>';
								if ($featPostID != '-1') {
									echo '<a href="#" onclick="this.style.display = \'none\';document.getElementById(\'pushpreview_edit\').style.display = \'block\';return false;" style="background: #C3E6C3;color: green;padding: 5px 0px;display: block;width: 90px;text-align: center;margin-bottom: 10px;">'.__('Edit', 'wpappninja').'</a>';
								}
								echo '<div id="pushpreview_edit" class="pushpreview_edit" ';if ($featPostID == '-1') {echo 'style="display:block;width:100%"';}else{echo "style='width:100%'";}echo '>
									<input onkeyup="document.getElementById(\'pushpreview_titre\').innerHTML = this.value" type="text" name="wpappninjapush_insert_titre" id="wpappninjapush_insert_titre" placeholder="'.__('Title', 'wpappninja').'" value="'.$titre.'" maxlength="70" />
									<textarea maxlength="150" onkeyup="document.getElementById(\'pushpreview_message\').innerHTML = this.value" name="wpappninjapush_insert_msg" id="wpappninjapush_insert_msg">'.mb_substr(preg_replace('#"#', '\"', html_entity_decode(strip_tags(str_replace(array("\r", "\n")," ", $excerpt)))), 0, 150).'</textarea>
									
									<div class="uploader">
										<input id="wpappninjapush_image" name="wpappninjapush_image" type="text" value="'.$image.'" />
										<input id="wpappninjapush_image_button" class="button" name="wpappninjapush_image_button" type="text" value="'.__('Choose an image', 'wpappninja').'" />
									</div>

								<h2>'.__('Link', 'wpappninja').'</h2>';
								if ($featPostID == '-1'){$featPostID = '';} ?>
								
								<input type="text" name="wpappninja_push_link" id="wpappninja_push_link" value="<?php echo $currentLink;?>" /> <a href="#" onclick="wpappninja_open_editor();return false"><?php _e('Add link', 'wpappninja');?></a><br/>

								</div>
								<?php

								echo '<h2>'.__('Categorie', 'wpappninja').'</h2>';

								$row = $wpdb->get_results("SELECT COUNT(device_id) as nb FROM {$wpdb->prefix}wpappninja_ids");

								echo '<label><input type="radio" name="wpappninjapush_category" value="" checked /><b>';
									if ($row[0]->nb > 0) {echo '<span style="background: #5b9dd9;color: white;display:inline-block;padding: 3px 5px;font-size:11px;margin-right: 10px;min-width: 90px;border-radius:4px;margin-bottom: 10px;text-align: center;">';echo $row[0]->nb.' '._n('subscriber', 'subscribers', round($row[0]->nb), 'wpappninja');}else{echo '<span style="background: #a9a9a9;color: white;display:inline-block;padding: 3px 5px;font-size:11px;margin-left: 25px;border-radius:4px;margin-bottom: 10px;">';echo '0 ' . __('subscriber', 'wpappninja');}
									echo '</span>  '.__('All', 'wpappninja').'</b></label><br/>';
								
								$nb_category = 0;
								$cats_perso = array_filter( explode(',', get_wpappninja_option('push_category', '')));
								foreach($cats_perso as $cat_perso) {

									$cat_perso = trim($cat_perso);

									$row = $wpdb->get_results($wpdb->prepare("SELECT COUNT(user_id) as nb FROM {$wpdb->prefix}wpappninja_push_perso WHERE `category` LIKE %s", "%" . $cat_perso . "%"));
									$nb_category++;
									echo '<label><input type="radio" name="wpappninjapush_category" value="'.$cat_perso.'" ';if ($current_cat_perso == $cat_perso){echo 'checked';}echo ' />';
									
									if ($row[0]->nb > 0) {echo '<span style="background: #5b9dd9;color: white;display:inline-block;padding: 3px 5px;font-size:11px;margin-right: 10px;min-width: 90px;border-radius:4px;margin-bottom: 10px;text-align: center;">';echo $row[0]->nb.' '._n('subscriber', 'subscribers', round($row[0]->nb), 'wpappninja');}else{echo '<span style="background: #a9a9a9;color: white;display:inline-block;padding: 3px 5px;font-size:11px;margin-left: 25px;border-radius:4px;margin-bottom: 10px;">';echo '0 ' . __('subscriber', 'wpappninja');}echo '</span>'.$cat_perso;
									echo '</label><br/>';
								}

								$customRole = array();
								$chain = "";
								$nbmember = 0;
								$queryemail = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT category FROM {$wpdb->prefix}wpappninja_push_perso WHERE category LIKE %s", '%@%'));
								foreach($queryemail as $objemail) {

									$chain .= $objemail->category.',';

									$nbmember++;


									$user_data = get_user_by('email', $objemail->category);
									if (isset($user_data->roles) && is_array($user_data->roles)) {
										foreach ($user_data->roles as $v => $role) {
											$customRole[$role] += 1;
										}
									}
								}

								if ($nbmember > 0) {
									echo '<label><input type="radio" name="wpappninjapush_category" value="@" ';if ($current_cat_perso == '@'){echo 'checked';}echo ' />';
									
									echo '<span style="background: #5b9dd9;color: white;display:inline-block;padding: 3px 5px;font-size:11px;margin-right: 10px;min-width: 90px;border-radius:4px;margin-bottom: 10px;text-align: center;">';echo $nbmember.' '._n('subscriber', 'subscribers', round($nbmember), 'wpappninja');echo '</span>'.__('All users', 'wpappninja');
									echo '</label><br/>';
								}

								foreach($customRole as $role => $nb) {
									echo '<label><input type="radio" name="wpappninjapush_category" value="role___'.$role.'" ';if ($current_cat_perso == '___'.$role){echo 'checked';}echo ' />';
									
									echo '<span style="background: #5b9dd9;color: white;display:inline-block;padding: 3px 5px;font-size:11px;margin-right: 10px;min-width: 90px;border-radius:4px;margin-bottom: 10px;text-align: center;">';echo $nb.' '.$role;echo '</span>'.$role;
									echo '</label><br/>';
								}

								$emails = explode(',', $chain);

								$emails = array_unique($emails);
								asort($emails);

								echo '<span style="background: #5b9dd9;color: white;display:inline-block;padding: 3px 5px;font-size:11px;margin-left: 0;margin-top: 29px;border-radius:4px;margin-bottom: 10px;">👤 '.__('Users', 'wpappninja').'</span><div style="max-height: 150px;overflow:auto;border: 1px solid #eee;">';

								foreach ($emails as $mail) {

									if (preg_match('#@#', $mail)) {
									echo '<label style="display: block;border-bottom: 2px solid #eee;padding: 13px;"><input type="radio" name="wpappninjapush_category" value="'.$mail.'" /> '.$mail.'</label>';
									}
								}

								echo '</div>';

								if ($nb_category == 0) {
									//echo '<input type="hidden" name="wpappninjapush_category" value="" />';
								}



								if (get_wpappninja_option('speed_trad') == 'none') {echo '<div style="display:none">';}
								echo '<h2>'.__('Language', 'wpappninja').'</h2>';
								
								if (isset($langImmutable)) {
									
									foreach (wpappninja_available_lang(true) as $name => $code) {
										if ($langImmutable == $code) {$flag = $code;$flagName = $name;}
									}

									echo '<label><input type="radio" name="wpappninjapush_lang" value="'.$langImmutable.'" checked /> <img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$flag.'.gif" /> '.$flagName.'</label>';
								} else {
									$nb_lang = 0;
									$all_image = '';
									foreach($lang_array as $name => $code) {
										$nb_lang++;
										$all_image .= '<img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" /> ';
										echo '<label><input type="radio" name="wpappninjapush_lang" value="'.$code.'" checked /> <img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" /> '.$name.'</label><br/>';
									}
									if ($nb_lang > 1 || get_wpappninja_option('speed_trad') == 'none') {
										echo '<label><input type="radio" name="wpappninjapush_lang" value="all" checked /> '.$all_image.' </label>';
									}
								}
								if (get_wpappninja_option('speed_trad') == 'none') {echo '</div>';}
								
								echo '<h2>'.__('Send date', 'wpappninja').'</h2>
								<label>

								<input type="text" id="datetimepicker3" name="wpappninjapush_insert_timestamp" value="' .$currentHour. '" />


								<script>jQuery(\'#datetimepicker3\').datetimepicker({format:\'d-m-Y H:i\'});</script>



								</label>
								<br/><br/>
								<input type="submit" class="button button-primary button-large" value="'.__('Schedule', 'wpappninja').'" />';
								
								if ($pushID != '') {
									echo '<br/><br/><a href="#" onclick="jQuery(\'#wpappninja_d_n\').submit();return false" style="color:red">'.__('Delete', 'wpappninja').'</a>';
								}
								
							echo '</div>';
							
						}
					}
				echo '</div>
			</form>';
			
			//if ($pushID != '') {
				echo '<form id="wpappninja_d_n" style="display:none" action="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID').'" method="post">'.wp_nonce_field( 'wpappninja-delete-push-' . $pushID ).'<input type="hidden" name="NOTIFICATIONSPUSH" value="1" /><input type="hidden" name="supprimer_notif" value="'.$pushID.'" /><input style="background: none;border: 0;text-decoration: underline;color: red;margin-left: 20px;" type="submit" value="'.__('Delete', 'wpappninja').'" /></form>';
			//}

			echo '<div style="clear:both"></div>
			</div>';
		} ?>
	</div>
	<div style="clear:both"></div>
	
	<?php
	echo wpappninja_talkus();
}
