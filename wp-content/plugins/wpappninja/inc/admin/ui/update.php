<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Cnfigurator.
 *
 * @since 5.2
 */
function _wpappninja_display_update_page() {

	?>
	<div class="wrap">
		<h1 style="right:20px;margin:20px 0 0;position:absolute;"></h1>
		<h2></h2>



			
		<?php $menu_current = 'update';require( WPAPPNINJA_ADMIN_UI_PATH   . 'menu.php' ); ?>
        <div style="padding: 20px;border-bottom: 1px solid #fd9b02;background: white;margin: 0px 0;border-top: 3px solid #fd9b02;font-size:20px;">

        <?php if (wpappninja_is_paid() && wpappninja_need_update()) {


            $paxk = get_option('wpappninja_packagenameInt', '');

            if ($paxk == '') {
                $response = wp_remote_get( 'https://api.wpmobile.app/packagenameInt.php?url=' . urlencode(home_url()) );
                if( is_array($response) ) {
                    if ($response['body'] != '') {
                        update_option('wpappninja_packagenameInt', $response['body']);
                        $paxk = $response['body'];
                    }
                }
            }
            

            if ($paxk != '') {
    
                if (get_wpappninja_option('package', '') != '') {

                    $response = wp_remote_get( 'https://my.wpmobile.app/data/android/' . $paxk . '?requestUpdate');
                    if( is_array($response) ) {
                        echo $response['body'];
                    }

                    update_option('wpappninja_need_update', false);
                }
            
                if (get_wpappninja_option('appstore_package', '') != '' && get_wpappninja_option('appstore_package', '') != 'xxx') {
                    
                    $response = wp_remote_get( 'https://my.wpmobile.app/data/ios_files/' . $paxk . '?requestUpdate');
                    if( is_array($response) ) {
                        echo $response['body'];
                    }

                    update_option('wpappninja_need_update', false);
                }

            } ?>

    </div>
<?php } ?>
    </div>
	<?php
	echo wpappninja_talkus();
}
