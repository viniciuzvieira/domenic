<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Render the talkus support chat.
 *
 * @since 3.6.6
 */
function wpappninja_talkus() {

	$html = "";

	if (isset($_GET['page']) && $_GET['page'] == 'wpappninja_home' && get_wpappninja_option('nomoreqrcode') != '1') {
	
		$html = '<form action="" method="post" id="formnewpanel" style="font-size:15px;margin-top:30px;">
		'.__('Enable the light app mode (beta)', 'wpappninja').'&nbsp;&nbsp;
		<label class="switch">
		    <input type="hidden" name="wpappnewdashboard" value="1" />
			<input onchange="jQuery(\'#formnewpanel\').submit();" type="checkbox" name="newdashboard" ';
			if (get_wpappninja_option('nomoreqrcode') == '1') {$html .= 'checked';}
			$html .= ' />
			 <span class="slider round"></span>
		</label>
		</form>';
	}

	return $html;

	if (defined('WPAPPNINJA_WHITE_LABEL')) {
		return "";
	}

	$return = "";
	
	$return .= '<br>
	<div class="wpappninja_help" style="border-color: #ffbdf1;max-width: 815px;width: 100%;margin: 35px 0 0;">
	' . __( 'You love WPMobile.App?', 'wpappninja' ) . ' <a target="_blank" href="https://wordpress.org/support/plugin/wpappninja/reviews/#new-post">
		' . __('Spread the word', 'wpappninja') . '
	</a> 😻
	</div>';

	/*$return .= '<script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={docs:{enabled:!0,baseUrl:"https://wpappninja.helpscoutdocs.com/"},contact:{enabled:!1,formId:"3ff17aa0-463f-11e8-8d65-0ee9bb0328ce"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});HS.beacon.config({color: \'#fd9b02\',icon: \'question\',topArticles: true, poweredBy: false, showSubject:true, showName:true});HS.beacon.ready(function() {
  HS.beacon.identify({
    \'Website\': \''.get_home_url().'\'
  });
});</script>';*/
	
	return $return;
}
