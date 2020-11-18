<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add a meta box on post edit form to publish a push notification
 *
 * @since 3.0.4
 */
/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function wpappninja_add_meta_box() {

	if (!wpappninja_is_premium() OR get_option('wpappninja_disable_autopush') OR get_wpappninja_option('nomoreqrcode', '0') == '1') {
		//return;
	}

	$screens = get_post_types(array('public'=>true));

	foreach ( $screens as $screen ) {

		add_meta_box(
			'wpappninja_push',
			__( 'Send a push notification', 'wpappninja' ),
			'wpappninja_meta_box_callback',
			$screen,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'wpappninja_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function wpappninja_meta_box_callback( $post ) {
	
	$lang_array = wpappninja_available_lang();

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wpappninja_save_meta_box_data', 'wpappninja_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$sended = get_post_meta( $post->ID, '_wpappninja_sended', true );
	$IDs = get_post_meta( $post->ID, '_wpappninja_arrayids', true );
	$senddate = get_post_meta( $post->ID, '_wpappninja_senddate', true );
	$lang = get_post_meta( $post->ID, '_wpappninja_lang', true );
	$set = get_post_meta( $post->ID, '_wpappninja_set', true );
	$sendtype = get_post_meta( $post->ID, '_wpappninja_send_type', true );
	$customcat = get_post_meta( $post->ID, '_wpappninja_category', true);
	if (!get_wpappninja_option('apipush') && get_option('wpappninja_pem_file', '') == '') {
		echo '<b>'.__('Action required', 'wpappninja').'</b><br/>
		<a href="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG).'">'.__('You must add an API KEY', 'wpappninja').'</a>';
		return;
	}

	if ($sended == '1' && is_array($IDs)) {
		
		global $wpdb;
		$i = 0;
		foreach($IDs as $id) {

			if ($i > 0) {
				break;
			}
			
			$notif 		= $wpdb->get_row($wpdb->prepare("SELECT `id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `sended`, `log`, `category` FROM {$wpdb->prefix}wpappninja_push WHERE `id` = %s AND sended != %s GROUP BY send_date", $id, '2'));
			
			if (null !== $notif) {
			
			if ($i > 0) {
				echo '<br/><br/><hr/><br/>';
			}
			$i++;
			$titre			= stripslashes($notif->titre);
			$excerpt		= stripslashes($notif->message);
			$image			= $notif->image;
			$prettydate		= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:gray'>".date_i18n( get_option( 'date_format' ), $notif->send_date) . ' ' . __('at') . ' ' .date_i18n( get_option( 'time_format' ), $notif->send_date)."</span>";
			$lang			= $notif->lang;
			$category		= $notif->category;
			
			if ($notif->sended == '1') {echo '<div style="background:#E2F5E2;margin-bottom:15px;padding:8px;"><b style="color:darkgreen">&#10003; '.__('Sended', 'wpappninja').'</b> ' .$prettydate. '</div>';} else {echo '<div style="background:#FFF7E0;margin-bottom:15px;padding:8px;"><b style="color:darkorange">&#x1F55C; '.__('Scheduled', 'wpappninja').'</b> ' .$prettydate. '</div>';}

			echo '<div style="border:1px solid #ddd;border-radius:5px;background:#fff">
				<div style="padding:8px;"><b>'.$titre.'</b><br/>
				'.$excerpt.'</div>';
				if ($image != '') {echo '<div class="pushpreview_image" style="height:60px;background:no-repeat center center url('.$image.');background-size:cover"></div>';}
			echo '</div>';
			
			//echo '<br/><b>'.__( 'Send date', 'wpappninja' ) .'</b><br/>'.$prettydate;

			//echo '<br/><br/><b>'.__('Categorie', 'wpappninja') . '</b><br/>' . $category;

			//echo '<br/><br/><b>'.__('Lang', 'wpappninja').'</b><br/>';
			
			foreach (wpappninja_available_lang(true) as $name => $code) {
				if ($lang == $code) {$img = $code;$l = $name;}
			}
			
			//echo '<img src="'.WPAPPNINJA_ASSETS_IMG_URL . 'flags/'.$img.'.gif" /> ' . $l;
		
			/*if ($notif->sended == '0'){echo '<br/><br/><a href="'.admin_url('admin.php?page=' . WPAPPNINJA_PUSH_SLUG . '&postID=_'.$id).'" target="_blank">'.__('Edit', 'wpappninja').'</a>';
			}*/
			}
		}

		if ($i > 0){return;}
	}
	
	if ($senddate == '' || strtotime($senddate) < current_time('timestamp')){$senddate = date('d-m-Y H:i', current_time('timestamp'));}

	$display = 'none';
	$checked = '';
	$isNotOld = true;
	$error = '';
	if ($post->post_status == 'publish') {$set = '';$isNotOld = false;$error = __('This post is already published. Notification off.', 'wpappninja');}
	
	if ($set == '1' || ($set == '' && get_wpappninja_option('alwayspush') && $isNotOld)) {$display = 'block';$checked = 'checked';}
	if ($error != ''){echo '<b style="color:red;">'.$error.'</b><br/><br/>';}
	echo '<label><input type="checkbox" onclick="jQuery(\'#wpappninja_set_push\').toggle()" name="wpappninja_set" value="1" '.$checked.' /> '.__('Set a notification?', 'wpappninja').'</label>
	<div id="wpappninja_set_push" style="display:'.$display.'"><br/><hr/><br/>';
	echo '<label for="wpappninja_senddate"><b>';
	_e( 'Send date', 'wpappninja' );
	echo '</b></label><br/>';
	
	if (!$sendtype) {$sendtype = get_wpappninja_option('push_send_type');}
	
	echo '<label onclick="jQuery(\'#wpappninja_send_set\').css(\'display\',\'none\');"><input type="radio" name="wpappninja_send_type" value="publish" ';if($sendtype == 'publish'){echo 'checked';}echo ' /> '.__('On post publish', 'wpappninja').'</label><br/>';
	echo '<label onclick="jQuery(\'#wpappninja_send_set\').css(\'display\',\'block\');"><input type="radio" name="wpappninja_send_type" value="set" ';if($sendtype == 'set'){echo 'checked';}echo ' /> '.__('Set a date', 'wpappninja').'</label><br/>';
	
	$display_send = 'none';
	if ($sendtype == 'set'){$display_send = 'block';}
	echo '<label id="wpappninja_send_set" style="display:'.$display_send.'"><br/><input type="text" name="wpappninja_senddate" value="' .esc_attr( $senddate ). '" /></label>';


	echo '<br/><label for="wpappninja_category"><b>';
	_e( 'Categorie', 'wpappninja' );
	echo '</b></label><br/>';

	echo '<label><input type="radio" name="wpappninja_category" value="" checked /> '.__('All', 'wpappninja').'</label><br/>';
								
	$nb_category = 0;
	$cats_perso = array_filter( explode(',', get_wpappninja_option('push_category', '')));
	foreach($cats_perso as $cat_perso) {

		$cat_perso = trim($cat_perso);
										
		$nb_category++;
		echo '<label><input type="radio" name="wpappninja_category" value="'.$cat_perso.'" ';if ($cat_perso == $customcat){echo 'checked';}echo ' /> '.$cat_perso.'</label><br/>';
	}
	if ($nb_category == 0) {
		echo '<input type="hidden" name="wpappninja_category" value="" />';
	}
	
	if (get_wpappninja_option('speed_trad') == 'none') {echo '<div style="display:none">';}
	echo '<br/><label for="wpappninja_lang"><b>';
	_e( 'Lang', 'wpappninja' );
	echo '</b></label><br/>';
	
	$nb_lang = 0;
	$all_lang = '';
	$langchecked = false;
	foreach($lang_array as $name => $code) {
		$nb_lang++;
		$all_lang .= '<img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" /> ';
		echo '<label><input type="radio" name="wpappninja_lang" value="'.$code.'" ';if ($lang == $code || get_wpappninja_option('defaultpushlang') == $code){echo 'checked';$langchecked = true;}echo '/> <img src="'.WPAPPNINJA_ASSETS_IMG_URL.'flags/'.$code.'.gif" /> '.$name.'</label><br/>';
	}
	
	if ($nb_lang > 1 || get_wpappninja_option('speed_trad') == 'none' || !$langchecked) {
		echo '<label><input type="radio" name="wpappninja_lang" value="all" ';if ($lang == 'all' || get_wpappninja_option('defaultpushlang') == 'all' || get_wpappninja_option('speed_trad') == 'none' || !$langchecked){echo 'checked';}echo ' /> '.$all_lang.' </label>';
	}
	if (get_wpappninja_option('speed_trad') == 'none') {echo '</div>';}	

	echo '<br/><br/>
	<label><input type="checkbox" name="wpappninja_setdefault" value="1" ';if (get_wpappninja_option('alwayspush')){echo 'checked';}echo '> '.__('Set as default', 'wpappninja').'</label>';
	
	echo '</div>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function wpappninja_save_meta_box_data( $post_id ) {

	$lang_array = wpappninja_available_lang();
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['wpappninja_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpappninja_meta_box_nonce'], 'wpappninja_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	/*if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}*/

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['wpappninja_senddate'] ) || ! isset( $_POST['wpappninja_lang']) || get_post_meta( $post_id, '_wpappninja_sended', true ) == '1') {
		return;
	}

	// Sanitize user input.
	$senddate = isset($_POST['wpappninja_senddate']) ? sanitize_text_field( $_POST['wpappninja_senddate'] ) : "";
	$set = isset($_POST['wpappninja_set']) ? sanitize_text_field( $_POST['wpappninja_set'] ) : "";
	$lang = isset($_POST['wpappninja_lang']) ? sanitize_text_field( $_POST['wpappninja_lang'] ) : "";
	$default = isset($_POST['wpappninja_setdefault']) ? sanitize_text_field( $_POST['wpappninja_setdefault'] ) : "";
	$sendtype = isset($_POST['wpappninja_send_type']) ? sanitize_text_field( $_POST['wpappninja_send_type'] ) : "";
	$category = isset($_POST['wpappninja_category']) ? sanitize_text_field( $_POST['wpappninja_category'] ) : "";
	
	if ($sendtype == 'publish') {
		if (get_post_status($post_id) == 'publish') {
			$senddate = date('d-m-Y H:i', current_time('timestamp'));
		} elseif(get_post_status($post_id) == 'future') {
			
			$post_data = get_post($post_id);
			$senddate = date('d-m-Y H:i', strtotime($post_data->post_date));
		}
	}
	
	$setted = '0';
	if ($set == '1'){$setted = '1';}

	// Update the meta field in the database.
	update_post_meta( $post_id, '_wpappninja_senddate', $senddate );
	update_post_meta( $post_id, '_wpappninja_lang', $lang );
	update_post_meta( $post_id, '_wpappninja_set', $setted );
	update_post_meta( $post_id, '_wpappninja_send_type', $sendtype );
	update_post_meta( $post_id, '_wpappninja_category', $category );
	
	if ($default == '1' && $setted == '1') {
		$options = get_option( WPAPPNINJA_SLUG );
		$options['alwayspush'] = true;
		$options['defaultpushlang'] = $lang;
		$options['push_send_type'] = $sendtype;
		update_option( WPAPPNINJA_SLUG, $options );
	}

	if ($setted == '0') {
		$options = get_option( WPAPPNINJA_SLUG );
		$options['alwayspush'] = false;
		update_option( WPAPPNINJA_SLUG, $options );
	}
	
	if ((get_post_status($post_id) == 'publish' || get_post_status($post_id) == 'future') && $setted == '1') {
		$timestamp = strtotime($senddate);
		$title = stripslashes(get_the_title($post_id));
		$content_post = get_post($post_id);
		$content = $content_post->post_content;
		$content = mb_substr(wp_strip_all_tags(stripslashes($content)), 0, 255);
		$image = wpappninja_get_image($post_id);

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

		global $wpdb;
		$ids = array();
		/*if ($lang == 'all') {
			foreach($lang_array as $name => $code) {
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push (`id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `category`) VALUES (%s, %s, %s, %s, %s, %s, %s)", $post_id, $title, $content, $image, $timestamp, $code, $category));
				$ids[] = $wpdb->insert_id;
			}
		*///} else {
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}wpappninja_push (`id_post`, `titre`, `message`, `image`, `send_date`, `lang`, `category`) VALUES (%s, %s, %s, %s, %s, %s, %s)", $post_id, $title, $content, $image, $timestamp, $lang, $category));
			$ids[] = $wpdb->insert_id;
		//}
		update_post_meta( $post_id, '_wpappninja_sended', '1' );
		update_post_meta( $post_id, '_wpappninja_arrayids', $ids );
		
		//wpappninja_cron();
	}
}
add_action( 'save_post', 'wpappninja_save_meta_box_data' );