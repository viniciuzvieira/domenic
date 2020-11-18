<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/*
 * Tell WP what to do when admin is loaded aka upgrader
 *
 * @since 1.0
 */
add_action( 'admin_init', '_wpappninja_upgrader' );
function _wpappninja_upgrader() {
	
	$current_version = get_wpappninja_option( 'version' );
	
	// You can hook the upgrader to trigger any action when WPMobile.App is upgraded
	// first install
	if ( ! $current_version ) {
		do_action( 'wpappninja_first_install' );
		set_transient( 'wpappninjaadminnoticeupdated', true, 5 );

		$options            = get_option( WPAPPNINJA_SLUG );
		$options['version'] = WPAPPNINJA_VERSION;
		update_option( WPAPPNINJA_SLUG, $options );
	}
	// already installed but got updated
	elseif ( WPAPPNINJA_VERSION != $current_version ) {

		$new = array($current_version => get_option( WPAPPNINJA_SLUG ));
		update_option( 'wpappninja_backup', $new);

		do_action( 'wpappninja_upgrade', WPAPPNINJA_VERSION, $current_version );

		$options            = get_option( WPAPPNINJA_SLUG );
		$options['version'] = WPAPPNINJA_VERSION;
		update_option( WPAPPNINJA_SLUG, $options );
	}
}

/**
 * Display the welcome message
 *
 * @since 5.0.2
 */
add_action('admin_notices', 'wpappninja_activate_message');
function wpappninja_activate_message() {

	if (isset($_GET['wpappninjadismissme'])) {
		delete_option('wpappninjaadminnoticeupdatedwv');
	}

	if (wpappninja_is_store_ready() && !wpappninja_is_paid() && isset( $_GET['page'] ) && in_array($_GET['page'], array(WPAPPNINJA_SETTINGS_SLUG,WPAPPNINJA_PREVIEW_SLUG,WPAPPNINJA_PUSH_SLUG,WPAPPNINJA_QRCODE_SLUG,WPAPPNINJA_CERT_SLUG,WPAPPNINJA_STATS_SLUG,WPAPPNINJA_PUBLISH_SLUG,WPAPPNINJA_PROMOTE_SLUG,WPAPPNINJA_ADSERVER_SLUG,WPAPPNINJA_AUTO_SLUG,WPAPPNINJA_HOME_SLUG,WPAPPNINJA_UPDATE_SLUG,WPAPPNINJA_PWA_SLUG,WPAPPNINJA_THEME_SLUG))) {

		if (preg_match('#fr#', get_locale())) {
			$url = 'https://wpmobile.app/prix/?source=' . home_url('/');
		} else {
			$url = 'https://wpmobile.app/en/price/?source=' . home_url('/');
		}

		//$url = menu_page_url(WPAPPNINJA_HOME_SLUG, false);
	    /*?>
	    <div class="updated notice" style="max-width: 815px;">
	        <p>
	        	<img src="<?php echo WPAPPNINJA_ASSETS_IMG_URL;?>logo.png" style="vertical-align: -6px;height: auto;width: 24px;" /> <span style="font-size:16px"><?php _e( 'Your mobile application is ready!', 'wpappninja' ); ?></span>
	        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        	 <a target="_blank" href="<?php echo $url;?>"><b><?php _e('BUY', 'wpappninja');?></b></a>
	        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        	<a href="https://support.wpmobile.app/?lang=<?php echo wpmobile_getSupportLang();?>" target="_blank"><?php _e('support', 'wpappninja');?></a>
        	</p>
	    </div>
	    <?php */
	}

	/*if( get_transient( 'wpappninjaadminnoticeupdated' ) ){
	    echo "<div class='updated'><p>" . sprintf(__('<b>Everything is ok!</b> <a href="%s">Create my mobile app</a>'), admin_url( 'admin.php?page=' . WPAPPNINJA_PUBLISH_SLUG )) . "</p></div>";
	    delete_transient( 'wpappninjaadminnoticeupdated' );
	}*/

	if( get_option( 'wpappninjaadminnoticeupdatedwv' ) ){

		$url = "https://wpmobile.app/demo-android-ios/?cache=" . uniqid() . "&url=" . rawurlencode(home_url() . '/') . "&slug=iphone5s&lang=" . substr(get_locale(), 0, 2);

	    echo "<div class='updated'><p>" . sprintf(__('<b>WPMobile.App</b> We\'ve made major improvements on the content render <a target="_blank" style="%s" href="%s">Test now my enhanced app</a>'), 'display: inline-block;margin-left: 17px;margin-right: 17px;font-size: 12px;text-decoration: none;background: #007f1b;padding: 4px 12px;margin-top: 0px;text-transform: uppercase;font-weight: 700;border: 1px solid #1ed91e;color: white;box-shadow: 0px 3px 2px 0px #a5a5a5;', $url) . " " . sprintf(__('<a href="%s">dismiss</a>'), admin_url( 'admin.php?page=' . WPAPPNINJA_PUBLISH_SLUG . '&wpappninjadismissme=true' )) . "</p></div>";
	}
}

/**
 * Keeps this function up to date at each version
 *
 * @since 1.0
 */
add_action( 'wpappninja_first_install', '_wpappninja_first_install' );
function _wpappninja_first_install() {
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	// Create Options
	/*$lang = 'en';
	$lang_wp = substr(get_locale(), 0, 2);
	if (in_array($lang_wp, array('fr', 'en', 'de', 'it', 'pt', 'es'))) {
		$lang = $lang_wp;
	}*/

	$statsdefault = wpappninja_stats_plugin();
	update_option('wpappninja_follow_tuto', "0");
	update_option('wpappninja_nb_downloads', "2147483647");
	update_option( 'wpappninja_stats_box', $statsdefault );
	add_option( WPAPPNINJA_SLUG,
		array(
			'app' => array('name' => wpappninja_get_appname()),
			'nomoretheme' => '1',
			'speed' => '1',
			'fullspeed' => '1',
			'titlespeed' => '1',
			'theme' => 'premium',
			'fastclick' => '0',
            'cache_type' => 'network_only',
            'disable_all_cache' => 'on',
            'agressive_anti_cache' => '1',
            'cache_friendly' => '1',
			//'lang_exclude' => array($lang),
			'webview' => '4',
			'show_browser' => '0',
			'show_avatar' => '0',
			'show_date' => '0',
			'bio' => '0',
			'showdate' => '0',
			//'mentions_' . $lang => ' &nbsp;',
			'nomoreqrcode' => '1',
			'sdk2019'=>'1',
			'customcss' => '',
			'customcss_website' => '',
			'all_link_browser' => '0',
			'wpappninja_main_theme' => 'WPMobile.App',
			'speed_notheme' => '0',
			'nospeed_notheme' => '0',
			'wpappninja_042018' => true,
			'push_category' => __('News', 'wpappninja').",".__('Offers', 'wpappninja')
		)
	);

	// android smooth deeplinking
	update_option('wpappninja_android_deeplinking', true);

	// premium feature
	update_option('wpappninja_start_premium_feature', true);

	// import homepage and menu
	//wpappninja_magic_import($lang);
	//wpappninja_magic_import();
	update_option('wpmobile_enable_new_fcm', true);
	
	wpmobileapp_install_bdd();
	
	wp_schedule_event( time(), 'wpappninja_every_three_minutes', 'wpappninjacron' );
	//wp_schedule_event( time(), 'daily', 'wpappninjacronnbinstall' );



	if ('divi' == strtolower(get_option( 'template' ) )) {
		$options = get_option( WPAPPNINJA_SLUG );
		$options['appify'] = '1';
		$options['wpappninja_main_theme'] = "No theme";
		$options['speed_notheme'] = "1";
		$options['customcss'] = "header#main-header {display: none;}";
		update_option( WPAPPNINJA_SLUG, $options );
	}
}

/**
 * What to do when WPMobile.App is updated, depending on versions
 *
 * @since 1.0
 */
add_action( 'wpappninja_upgrade', '_wpappninja_new_upgrade', 10, 2 );
function _wpappninja_new_upgrade( $wpappninja_version, $current_version )
{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$actual_version = get_wpappninja_option( 'version', '1.0' );

    $_options = get_option( WPAPPNINJA_SLUG );
    $_options['version_app'] = round(get_wpappninja_option( 'version_app', 1 ) + 1);
    update_option( WPAPPNINJA_SLUG, $_options );

    wpmobileapp_install_bdd();
	
	if ( version_compare( $actual_version, '3.0', '<' ) ) {
		$option = get_option(WPAPPNINJA_SLUG);
		$option['admob_b'] = get_option('wpappninja_admob_b');
		$option['admob_float'] = get_option('wpappninja_admob_float');
		$option['admob_splash'] = get_option('wpappninja_admob_splash');
		$option['admob_t'] = get_option('wpappninja_admob_t');
		$option['afterpost'] = get_option('wpappninja_afterpost');
		$option['apipush'] = get_option('wpappninja_apipush');
		$option['autopush'] = get_option('wpappninja_autopush');
		$option['beforepost'] = get_option('wpappninja_beforepost');
		$option['bienvenue_en'] = get_option('wpappninja_bienvenue');
		$option['bienvenue_fr'] = get_option('wpappninja_bienvenue');
		$option['bio'] = get_option('wpappninja_bio');
		$option['commentaire'] = get_option('wpappninja_commentaire');
		$option['configureok'] = get_option('wpappninja_configureok');
		$option['datetype'] = get_option('wpappninja_datetype');
		$option['defautimg'] = get_option('wpappninja_defautimg');
		$option['disclameeer'] = get_option('wpappninja_disclameeer');
		$option['excluded'] = get_option('wpappninja_excluded');
		$option['ga'] = get_option('wpappninja_ga');
		$option['iconmenui'] = get_option('wpappninja_iconmenui');
		$option['maxage'] = get_option('wpappninja_maxage');
		$option['mentions_en'] = get_option('wpappninja_mentions');
		$option['mentions_fr'] = get_option('wpappninja_mentions');
		$option['menu'] = get_option('wpappninja_menu');
		$option['menuorder'] = get_option('wpappninja_menuorder');
		$option['nbsimilar'] = get_option('wpappninja_nbsimilar');
		$option['package'] = get_option('wpappninja_package');
		$option['pageashome'] = get_option('wpappninja_pageashome');
		$option['pageashomeicon'] = get_option('wpappninja_pageashomeicon');
		$option['pageashometitle_en'] = get_option('wpappninja_pageashometitle');
		$option['pageashometitle_fr'] = get_option('wpappninja_pageashometitle');
		$option['project'] = get_option('wpappninja_project');
		$option['rating_seuil'] = get_option('wpappninja_rating_seuil');
		$option['rating_texte_en'] = get_option('wpappninja_rating_texte');
		$option['rating_texte_fr'] = get_option('wpappninja_rating_texte');
		$option['rating_titre_en'] = get_option('wpappninja_rating_titre');
		$option['rating_titre_fr'] = get_option('wpappninja_rating_titre');
		$option['share'] = get_option('wpappninja_share');
		$option['showdate'] = get_option('wpappninja_showdate');
		$option['silent'] = get_option('wpappninja_silent');
		$option['similaire'] = get_option('wpappninja_similaire');
		$option['similarnb'] = get_option('wpappninja_similarnb');
		$option['similartype'] = get_option('wpappninja_similartype');
		$option['theme'] = get_option('wpappninja_theme');
		$option['typedevue'] = get_option('wpappninja_typedevue');
		$option['welcome_en'] = get_option('wpappninja_welcome');
		$option['welcome_fr'] = get_option('wpappninja_welcome');
		$option['welcome_titre_en'] = get_option('wpappninja_welcome_titre');
		$option['welcome_titre_fr'] = get_option('wpappninja_welcome_titre');
		update_option(WPAPPNINJA_SLUG, $option);
		
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_logs");
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpappninja_push");
		$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_push (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			gmt int(20) NOT NULL DEFAULT 0,
			http varchar(3) NOT NULL DEFAULT '',
			log text(1024) NOT NULL DEFAULT '',
			send_date int(20) NOT NULL DEFAULT 0,
			sended varchar(1) NOT NULL DEFAULT '0',
			id_post int(20) NOT NULL DEFAULT 0,
			titre varchar(255) NOT NULL DEFAULT '',
			message varchar(255) NOT NULL DEFAULT '',
			image varchar(255) NOT NULL DEFAULT '',
			UNIQUE KEY id (id)
		) $charset_collate;";
		dbDelta( $sql );
		
		wp_clear_scheduled_hook( 'sendnotificationspush' );
		wp_schedule_event( time(), 'wpappninja_every_three_minutes', 'wpappninjacron' );
	}
	
	if ( version_compare( $actual_version, '3.0.5', '<' ) ) {
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."wpappninja_push ADD lang varchar(5) NOT NULL DEFAULT ''");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."wpappninja_ids ADD lang varchar(5) NOT NULL DEFAULT ''");
	}

	if ( version_compare( $actual_version, '3.1.2', '<' ) ) {
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."wpappninja_ids ADD welcome varchar(2) NOT NULL DEFAULT ''");
	}

	if ( version_compare( $actual_version, '3.1.4', '<' ) ) {
		wp_clear_scheduled_hook( 'sendnotificationspush' );
		wp_clear_scheduled_hook( 'wpappninjacron' );
		wp_schedule_event( time(), 'wpappninja_every_three_minutes', 'wpappninjacron' );
	}
	
	if ( version_compare( $actual_version, '3.6.5', '<' ) ) {
		
		$menu_fr = array();
		$menu_en = array();
		
		$items = wpappninja_weight_order(get_wpappninja_option('menu_reloaded'));
		foreach($items as $item) {
			$feat = '0';
			if (isset($item['feat'])) {
				if ($item['feat'] == '1') {
					$feat = '1';
				}
			}

			$menu_fr[] = array(
						'id' => $item['id_fr'],
						'name' => trim($item['name_fr']),
						'type' => $item['type'],
						'icon' => $item['icon'],
						'feat' => $feat
					);

			$menu_en[] = array(
						'id' => $item['id_en'],
						'name' => trim($item['name_en']),
						'type' => $item['type'],
						'icon' => $item['icon'],
						'feat' => $feat
					);
		}
		
		$option = get_option(WPAPPNINJA_SLUG);
		
		if ($option['lang_en'] == '1') {
			$option['menu_reload_en'] = $menu_en;
			$option['pageashomeicon_en'] = $option['pageashomeicon'];
			$option['lang_exclude'][] = 'en';
		}
		
		if ($option['lang_fr'] == '1') {
			$option['menu_reload_fr'] = $menu_fr;
			$option['pageashomeicon_fr'] = $option['pageashomeicon'];
			$option['lang_exclude'][] = 'fr';
		}

		update_option(WPAPPNINJA_SLUG, $option);
	}
	
	if ( version_compare( $actual_version, '3.9.0', '<' ) ) {
		$option = get_option(WPAPPNINJA_SLUG);
		$option['admob_float_ios'] = $option['admob_float'];
		$option['admob_splash_ios'] = $option['admob_splash'];
		$option['admob_t_ios'] = $option['admob_t'];
		$option['admob_b_ios'] = $option['admob_b'];
		update_option(WPAPPNINJA_SLUG, $option);
	}
	
	if ( version_compare( $actual_version, '3.9.1', '<' ) ) {
		$option = get_option(WPAPPNINJA_SLUG);
		$option['send_welcome_push'] = "1";
		update_option(WPAPPNINJA_SLUG, $option);
	}
	
	if ( version_compare( $actual_version, '4.0', '<' ) ) {
		
		// log of stats
		$sql1 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_stats (	
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id varchar(32) NOT NULL DEFAULT '',
			action varchar(32) NOT NULL DEFAULT '',
			value varchar(32) NOT NULL DEFAULT '',
			nb mediumint(6) NOT NULL DEFAULT 1,
			date int(32) NOT NULL DEFAULT 0,
			UNIQUE KEY id (id),
			KEY user_id (user_id),
			KEY action (action)
		) $charset_collate;";
		dbDelta( $sql1 );
	
		// info about users for stats
		$sql2 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_stats_users (	
			id varchar(32) NOT NULL DEFAULT '',
			continent varchar(2) NOT NULL DEFAULT '',
			country varchar(32) NOT NULL DEFAULT '',
			city varchar(32) NOT NULL DEFAULT '',
			platform varchar(10) NOT NULL DEFAULT '',
			lang varchar(5) NOT NULL DEFAULT '',
			UNIQUE KEY id (id),
			KEY platform (platform),
			KEY lang (lang)
		) $charset_collate;";
		dbDelta( $sql2 );
	}
	
	if ( version_compare( $actual_version, '4.0.1', '<' ) ) {
		// correct some stats
		$wpdb->query("UPDATE {$wpdb->prefix}wpappninja_stats SET value = '' WHERE action = 'recent'");
		$wpdb->query("UPDATE {$wpdb->prefix}wpappninja_stats_users SET continent = '?' WHERE continent = ''");
		$wpdb->query("UPDATE {$wpdb->prefix}wpappninja_stats_users SET country = 'unknown' WHERE country = ''");
	}

	if ( version_compare( $actual_version, '4.1.5', '<' ) ) {

		// table to count the installations
		$sql5 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_installs (
			device_id varchar(255) NOT NULL DEFAULT '',
			device_type int(1) NOT NULL DEFAULT 0,
			UNIQUE KEY device_id (device_id(128))
		) $charset_collate;";
		dbDelta( $sql5 );

		$query = $wpdb->get_results("SELECT device_id FROM {$wpdb->prefix}wpappninja_ids");
		foreach($query as $obj) {

			$device_id = sha1($obj->device_id);
			
			$device_type = 0; // android
			if (substr($obj->device_id, 0, 5) == "_IOS_") {
				$device_type = 1;
			}

			$wpdb->query($wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}wpappninja_installs (device_id, device_type) VALUES (%s, %d)", $device_id, $device_type));
		}
	}

	if ( version_compare( $actual_version, '4.1.8', '<' ) ) {
		
		if (wpappninja_is_paid()) {
			wpappninja_published();
		}
	}

	if ( version_compare( $actual_version, '4.2.0', '<' ) ) {
		wp_schedule_event( time(), 'daily', 'wpappninjacronnbinstall' );
		
		$option = get_option(WPAPPNINJA_SLUG);
		$option['smartbanner'] = "1";
		update_option(WPAPPNINJA_SLUG, $option);
	}

	if ( version_compare( $actual_version, '4.3.2', '<' ) ) {
		$option = get_option(WPAPPNINJA_SLUG);
		$option['customcss'] = "#content {padding:0 6%;background:white}";
		update_option(WPAPPNINJA_SLUG, $option);
	}

	if ( version_compare( $actual_version, '4.4', '<' ) ) {
		// adserver
		$sql6 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_adserver (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			format varchar(20) NOT NULL DEFAULT 'top',
			logo varchar(255) NOT NULL DEFAULT '',
			title varchar(100) NOT NULL DEFAULT '',
			text varchar(255) NOT NULL DEFAULT 0,
			color varchar(7) NOT NULL DEFAULT '#333333',
			link varchar(255) NOT NULL DEFAULT '',
			start int(20) NOT NULL DEFAULT 0,
			stop int(20) NOT NULL DEFAULT 0,
			click int(20) NOT NULL DEFAULT 0,
			display int(20) NOT NULL DEFAULT 0,
			lang varchar(2) NOT NULL DEFAULT '',
			UNIQUE KEY id (id)
		) $charset_collate;";
		dbDelta( $sql6 );
	}

	if ( version_compare( $actual_version, '5.0', '<' ) ) {
		update_option('wpappninja_old_ios_deeplinking', true);
	}

	if ( version_compare( $actual_version, '5.0.6', '<' ) ) {
		
		// cleanup stats
		$actions = array('update', 'adserver', 'cancel', 'published', 'register_install', 'getinstall', 'updated', 'category', 'store', 'unregister', 'register', 'redirection', 'similaires', 'favoris', 'custom', 'cronjob');

		foreach ($actions as $action) {
			$wpdb->delete( $wpdb->prefix."wpappninja_stats", array( 'action' => $action ), array( '%s' ) );
		}

		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_stats WHERE value LIKE %s", "-%"));

		// switch webview mode
		$option = get_option(WPAPPNINJA_SLUG);
		if (trim($option['customcss']) == "#content {padding:6%;background:white}" || trim($option['customcss']) == "#content {padding:0 6%;background:white}" || trim($option['customcss']) == "#content {padding:20px 6% 20px;background:white}") {
			$option['customcss'] = "#content {padding:20px 6% 20px;background:white} header,footer,#header,#footer,.header,.footer,nav,#sidebar,div[role=complementary],#comments{display:none!important}";


			if (!get_option('wpappninja_app_published')) {
				$option['webview'] = '2';

				update_option( 'wpappninjaadminnoticeupdatedwv', true );
			}
		}
		update_option(WPAPPNINJA_SLUG, $option);
	}

	if ( version_compare( $actual_version, '5.2', '<' ) ) {
		$wpdb->delete( $wpdb->prefix."wpappninja_stats", array( 'action' => 'version' ), array( '%s' ) );

		// qrcode
		$sql7 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_qrcode (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			link varchar(255) NOT NULL DEFAULT '',
			UNIQUE KEY id (id)
		) $charset_collate;";
		dbDelta( $sql7 );

		// alter push table
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."wpappninja_push CHANGE id_post id_post VARCHAR( 255 ) NOT NULL");
	}

	if ( version_compare( $actual_version, '5.3', '<' ) ) {
		
		// cleanup stats
		$actions = array('healme', 'apple_403');

		foreach ($actions as $action) {
			$wpdb->delete( $wpdb->prefix."wpappninja_stats", array( 'action' => $action ), array( '%s' ) );
		}
	}

	if ( version_compare( $actual_version, '6.2.1', '<' ) ) {

		// push_perso
		$sql8 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_push_perso (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id varchar(255) NOT NULL DEFAULT '',
			category varchar(255) NOT NULL DEFAULT '',
			UNIQUE KEY id (id)
		) $charset_collate;";
		dbDelta( $sql8 );

		$wpdb->query("ALTER TABLE ".$wpdb->prefix."wpappninja_push ADD category varchar(255) NOT NULL DEFAULT ''");

	}

	if ( version_compare( $actual_version, '6.6', '<' ) ) {

		// home_perso
		$sql9 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_home_perso (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id varchar(255) NOT NULL DEFAULT '',
			category varchar(255) NOT NULL DEFAULT '',
			UNIQUE KEY id (id)
		) $charset_collate;";
		dbDelta( $sql9 );
	}

	if ( version_compare( $actual_version, '6.7.0', '<' ) ) {

		$option = get_option(WPAPPNINJA_SLUG);

		$option['css_74537a66b8370a71e9b05c3c4ddbf522'] = $option['css_f654d251339685f135e57aac41dbf8fd'];
		$option['css_e0c30224e61a0fa53753d0992872782d'] = $option['css_29861368debca9385c00079b6ced9773'];

		update_option(WPAPPNINJA_SLUG, $option);

	}

	if ( version_compare( $actual_version, '6.7.1', '<' ) ) {

		wp_clear_scheduled_hook( 'wpappninjacronnbinstall' );
	}

	if ( version_compare( $actual_version, '7.0', '<' ) ) {
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."wpappninja_adserver ADD html varchar(1024) NOT NULL DEFAULT ''");

		$wpdb->query("ALTER TABLE ".$wpdb->prefix."wpappninja_push_perso ADD UNIQUE `user_id` (`user_id`(50))");


		$speed = get_wpappninja_option('menu_reload_speed');

		if (!is_array($speed)) {

			$speed = array();

			$lang = array('en', 'fr', 'it', 'de', 'es', 'pt');
			foreach ($lang as $l) {
			
				$a = get_wpappninja_option('menu_reload_' . $l);

				if (is_array($a)) {
					$speed = array_merge($speed, $a);
				}

			}
		}


		$option = get_option(WPAPPNINJA_SLUG);

		$mainlang = get_wpappninja_option('lang_exclude');

    	if (get_wpappninja_option('pageashome_speed', '') == '') {

    		if (get_wpappninja_option('pageashome_' . $mainlang[0], '') != '') {

    			$option['pageashome_speed'] = get_wpappninja_option('pageashome_' . $mainlang[0]);
    			$option['pageashometitle_speed'] = get_wpappninja_option('pageashometitle_' . $mainlang[0]);
    		}

    	}

		$option['menu_reload_speed'] = $speed;
		update_option(WPAPPNINJA_SLUG, $option);


	}

	if ( version_compare( $actual_version, '7.0.12', '<' ) ) {

		// correct some stats
		$remove = array('aq', 'cw', 'bq', 'bg', 'gg', 'im', 'je', 'xk', 'bl', 'mf', 'sx', 'ss');

		foreach ($remove as $r) {
			$wpdb->query("UPDATE {$wpdb->prefix}wpappninja_stats_users SET lang = 'en' WHERE lang = '$r'");
		}

	}

	if ( version_compare( $actual_version, '7.1.1', '<' ) ) {

		$p = get_option('wpappninja_packagenameInt', '');
		$nb = wpappninja_get_install();
	}

	if ( version_compare( $actual_version, '7.1.3', '<' ) ) {

		$option = get_option(WPAPPNINJA_SLUG);
		$option['firstcssfill'] = "ok";
		update_option(WPAPPNINJA_SLUG, $option);

	}

	if ( version_compare( $actual_version, '7.2.9', '<' ) ) {

		$option = get_option(WPAPPNINJA_SLUG);
		
		if ($option['css_305cad765b7512c618c0d6174913fb94'] == "" || $option['css_305cad765b7512c618c0d6174913fb94'] == "white") {
		
			$option['css_305cad765b7512c618c0d6174913fb94'] = "#ffffff";
		}
		
		update_option(WPAPPNINJA_SLUG, $option);

	}

	if ( version_compare( $actual_version, '7.3.1', '<' ) ) {

		$option = get_option(WPAPPNINJA_SLUG);
		$option['cache_friendly'] = "1";
		update_option(WPAPPNINJA_SLUG, $option);

	}


	if ( version_compare( $actual_version, '8.0', '<' ) ) {

		$option = get_option(WPAPPNINJA_SLUG);
		if ($option['wpappninja_main_theme'] == "WPAPP.NINJA") {
			$option['wpappninja_main_theme'] = "WPMobile.App";
		}
		update_option(WPAPPNINJA_SLUG, $option);

	}


	if ( version_compare( $actual_version, '8.5', '<' ) ) {

		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}wpappninja_push_perso WHERE user_id LIKE %s", "RANDOM_%"));

	}

	
	if ( version_compare( $actual_version, '9.0.23', '<' ) ) {

		$option = get_option(WPAPPNINJA_SLUG);
		if ($option['effect'] == "1") {
			$option['wpmobile_loader_all_theme'] = "1";
		}
		update_option(WPAPPNINJA_SLUG, $option);

	}

	// clear cache
	wpappninja_clear_cache();
	
	// get the package name
	wpappninja_get_package();
}

/**
 * Install / Repair the database.
 *
 * @since 8.2.3
 */
function wpmobileapp_install_bdd() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
	// store the id for push notifications
	$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_ids (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		registration_id varchar(255) NOT NULL DEFAULT '',
		device_id varchar(255) NOT NULL DEFAULT '',
		lang varchar(5) NOT NULL DEFAULT '',
		welcome varchar(2) NOT NULL DEFAULT '',
		UNIQUE KEY id (id),
		UNIQUE KEY registration_id (registration_id(128))
	) $charset_collate;";
	dbDelta( $sql );

	// push history
	$sql2 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_push (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		gmt int(20) NOT NULL DEFAULT 0,
		http varchar(3) NOT NULL DEFAULT '',
		log text(1024) NOT NULL DEFAULT '',
		send_date int(20) NOT NULL DEFAULT 0,
		sended varchar(1) NOT NULL DEFAULT '0',
		id_post varchar(255) NOT NULL DEFAULT '0',
		titre varchar(255) NOT NULL DEFAULT '',
		message varchar(255) NOT NULL DEFAULT '',
		image varchar(255) NOT NULL DEFAULT '',
		category varchar(255) NOT NULL DEFAULT '',
		lang varchar(5) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql2 );
	
	// log of stats
	$sql3 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_stats (	
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id varchar(32) NOT NULL DEFAULT '',
		action varchar(32) NOT NULL DEFAULT '',
		value varchar(32) NOT NULL DEFAULT '',
		nb mediumint(6) NOT NULL DEFAULT 1,
		date int(32) NOT NULL DEFAULT 0,
		UNIQUE KEY id (id),
		KEY user_id (user_id),
		KEY action (action)
	) $charset_collate;";
	dbDelta( $sql3 );
	
	// info about users for stats
	$sql4 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_stats_users (	
		id varchar(32) NOT NULL DEFAULT '',
		continent varchar(2) NOT NULL DEFAULT '',
		country varchar(32) NOT NULL DEFAULT '',
		city varchar(32) NOT NULL DEFAULT '',
		platform varchar(10) NOT NULL DEFAULT '',
		lang varchar(5) NOT NULL DEFAULT '',
		UNIQUE KEY id (id),
		KEY platform (platform),
		KEY lang (lang)
	) $charset_collate;";
	dbDelta( $sql4 );

	$sql5 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_installs (
		device_id varchar(255) NOT NULL DEFAULT '',
		device_type int(1) NOT NULL DEFAULT 0,
		UNIQUE KEY device_id (device_id(128))
	) $charset_collate;";
	dbDelta( $sql5 );

	// adserver
	$sql6 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_adserver (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		html varchar(1024) NOT NULL DEFAULT '',
		format varchar(20) NOT NULL DEFAULT 'top',
		logo varchar(255) NOT NULL DEFAULT '',
		title varchar(100) NOT NULL DEFAULT '',
		text varchar(255) NOT NULL DEFAULT 0,
		color varchar(7) NOT NULL DEFAULT '#333333',
		link varchar(255) NOT NULL DEFAULT '',
		start int(20) NOT NULL DEFAULT 0,
		stop int(20) NOT NULL DEFAULT 0,
		click int(20) NOT NULL DEFAULT 0,
		display int(20) NOT NULL DEFAULT 0,
		lang varchar(2) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql6 );

	// qrcode
	$sql7 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_qrcode (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		link varchar(255) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql7 );

	// push_perso
	$sql8 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_push_perso (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id varchar(255) NOT NULL DEFAULT '',
		category varchar(255) NOT NULL DEFAULT '',
		UNIQUE KEY id (id),
		UNIQUE KEY user_id (user_id(128))
	) $charset_collate;";
	dbDelta( $sql8 );

	// home_perso
	$sql9 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wpappninja_home_perso (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id varchar(255) NOT NULL DEFAULT '',
		category varchar(255) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql9 );

}
