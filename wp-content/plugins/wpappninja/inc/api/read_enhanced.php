<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Render posts in html
 *
 * int $id Post ID
 * @since 4.3.1
 */

add_action('template_redirect', 'wpappninja_read_enhanced', PHP_INT_MAX);
function wpappninja_read_enhanced() {
	
	$pageid = isset($_GET['wpappninja_read_enhanced']) ? sanitize_text_field($_GET['wpappninja_read_enhanced']) : 0;

	if (strval(abs($pageid)) > 0 || $pageid === "-welcome") {
		wpappninja_make_it_beautiful($pageid);
	}
}

function wpappninja_make_it_beautiful($id) {
	
	header("HTTP/1.1 200 OK");
	header('Content-Type: text/html; charset=utf-8');

	global $wp_query, $wp_the_query, $post;

	define('WPAPPNINJA_READ_ENHANCED', true);

	if (substr($id, 0, 1) != "-") {
		$post_object = get_post($id);
		query_posts("p=$id&post_type=any");
		$wp_the_query = $wp_query;
		$post = $post_object;
		setup_postdata( $post );
	}
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width"> 

		<?php wp_head() ?>
	</head>
	<body <?php body_class(); ?> style="padding:10px 20px">
		<div id="content">

		<?php
		// notification
		if (substr($id, 0, 4) == "-999") {

			echo do_shortcode("[wpappninja_push_config]");

		} elseif (substr($id, 0, 1) == "-") {
			global $wpdb;
			$id = str_replace("-", "", strval($id));


			if ($id === "welcome") {
				echo '<h2>' . get_wpappninja_option('welcome_titre_speed') . '</h2>';
				echo '<h3>' . get_wpappninja_option('welcome_speed') . '</h3>';
				echo stripslashes(get_wpappninja_option('bienvenue_speed'));
			} else {

			$query = $wpdb->get_results($wpdb->prepare("SELECT `message`, `id_post`, titre, image FROM {$wpdb->prefix}wpappninja_push WHERE `sended` = %s AND id = %s LIMIT 1", '1', $id));
			foreach($query as $obj) {

				$permalink = $obj->id_post;
				if (!preg_match('#^http#', $permalink)) {
					$permalink = get_permalink($obj->id_post);
				}
				if (!preg_match('#^http#', $permalink)) {
					$permalink = wpappninja_get_home();
				}

				if (strlen($obj->image) > 2) {
					echo '<img src="' . $obj->image . '" class="hero" alt="" />';
				}

				echo '<h2>' . stripslashes($obj->titre) . '</h2>';
				echo stripslashes($obj->message);

				$read_link = '<br/><br/><a class="button wpappninja_push_button" href="' . $permalink . '">' . __('Read', 'wpappninja') . '</a>';
				echo $read_link;
			}

			}
		}

		// woocommmerce
		elseif ('product' == $post->post_type) {
			the_content();
			$type = $post->post_type . '_page';
			$shortcode = apply_filters( "{$type}_shortcode_tag", $type );
			$shortcode = '[' . $shortcode . ' id="' . $id . '"]';
			$result = do_shortcode( $shortcode );

			if ($result != $shortcode) {
				echo $result;
			}			
		}

		// geodirectory
		else if ('gd_place' == $post->post_type) {
			global $post_images;
			do_action('geodir_wrapper_open', 'details-page', 'geodir-wrapper', '');
			do_action('geodir_top_content', 'details-page');
			do_action('geodir_detail_before_main_content');
			do_action('geodir_before_main_content', 'details-page');
			if (get_option('geodir_detail_sidebar_left_section')) {
			    do_action('geodir_detail_sidebar');
			}
			do_action('geodir_wrapper_content_open', 'details-page', 'geodir-wrapper-content', '');
			do_action('geodir_article_open', 'details-page', 'post-' . get_the_ID(), get_post_class(), '');
			do_action('geodir_add_page_content', 'before', 'details-page');
			the_post();
			do_action('geodir_details_main_content', $post);
			do_action('geodir_add_page_content', 'after', 'details-page');
			do_action('geodir_article_close', 'details-page');
			do_action('geodir_after_main_content');
			do_action('geodir_wrapper_content_close', 'details-page');
			if (!get_option('geodir_detail_sidebar_left_section')) {
    			do_action('geodir_detail_sidebar');
			}
			do_action('geodir_wrapper_close', 'details-page');
			do_action('geodir_sidebar_detail_bottom_section', '');		
		}

		else {
			the_content();
		}
		?>

		</div>
		<?php wp_footer(); ?>
		
	</body>
	</html>
	<?php
	exit(0);
}


add_shortcode( 'wpappninja_push_config', 'wpappninja_push_config' );
function wpappninja_push_config() {

	global $wpdb;

	$user_bdd_id_check = $wpdb->get_row($wpdb->prepare("SELECT `user_id` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $_COOKIE['HTTP_X_WPAPPNINJA_ID']));

	if (!isset($user_bdd_id_check->user_id) || !isset($_COOKIE['HTTP_X_WPAPPNINJA_ID'])) {
		$html = "<p><b style='color:darkred'>" . __('Push notifications are disabled.', 'wpappninja') . "</b></p>";
		$html .= '<p><a style="padding: 14px;border-radius:5px;line-height: initial;border:1px solid '.wpappninja_get_hex_color().';color:'.wpappninja_get_hex_color().';text-transform:uppercase;margin: 15px auto 25px;height: auto;display: inline-block;background:white" class="button" href="?wpapppushconfig=1">' . __('Enable push notifications', 'wpappninja') . '</a></p>';
		return $html;
	}


	$category = array_filter( explode(',', get_wpappninja_option('push_category', '')));
	$user_id = $_COOKIE['HTTP_X_WPAPPNINJA_ID'];


	$html = '<!-- Push settings -->';

	if (isset($_POST['enablewpapppush'])) {
		$user_category = "";
		if (is_array($_POST['wpapp_category'])) {
			$user_category = implode(',', $_POST['wpapp_category']);
		}

		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpappninja_push_perso SET `category` = %s WHERE `id` = %s", $user_category, $user_id));

		$html .= '<p style="background: #348734;text-align:center;color:white;padding: 10px 0;text-transform: uppercase;font-weight: 700;">' . __('Settings saved', 'wpappninja'). '</p>';
	}

	$user_settings = $wpdb->get_row($wpdb->prepare("SELECT `category` FROM {$wpdb->prefix}wpappninja_push_perso WHERE `id` = %s", $user_id));

	$html .= '<p><a style="padding: 14px;border-radius:5px;line-height: initial;border:1px solid '.wpappninja_get_hex_color().';color:'.wpappninja_get_hex_color().';text-transform:uppercase;margin: 15px auto 25px;height: auto;display: inline-block;background:white" class="button" href="?wpapppushconfig=1">' . __('Enable push notifications', 'wpappninja') . '</a></p>';

	if (count($category) > 0) {

		$html .= '<form action="" method="post">
		<h3>'.__('Subscriptions', 'wpappninja').'</h3>
		<input type="hidden" name="enablewpapppush" value="1" />
		<div class="list-block">';

		foreach ($category as $c) {

			$c = trim($c);

    		$html .= '<label class="label-checkbox item-content">
	   	    	<input type="checkbox" name="wpapp_category[]" value="' . $c . '" ';

	    	    if (preg_match('#' . $c . '#', $user_settings->category)) {$html .= 'checked';}

	    	    $html .= ' /> ' . $c . '
   			</label>
   			<br/>';

		}
	
		$html .= '</div><br/><input type="submit" style="color:white;background:'.wpappninja_get_hex_color().'" class="button" value="' . __('Save', 'wpappninja') . '" /></form>';
	}

	return $html;
}