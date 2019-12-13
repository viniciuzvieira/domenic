<?php
/*
* Include css,js files and create menu
*/

class rrw_gyrix_manager_load
{	
	// Add css
	public function rrw_gyrixenqueue_styles() 
    {	 
        if(is_admin() || current_user_can('manage_opions'))
        {
        	wp_enqueue_style(
                'rrw_gyrixcss',
                RRW_GYRIXTEMPLATEURL . 'admin/css/redirect_gyrixcss.css',
                array(), 
                '1.0.0'
            );
           
        }
    }
	// Add menu
	public function rrw_gyrixcallhooks()
    {
        if(is_admin() || current_user_can('manage_opions'))
        {
            add_menu_page( 'Redirect Users', 'Redirect Users', 'administrator', 'redirect-gyrix-settings', array($this, 'rrw_gyrix_settings_page') );
            add_submenu_page( 'redirect-gyrix-settings', 'Options', 'Options', 'administrator', 'redirect-gyrix-options', array($this, 'rrw_gyrix_options_page') );
        }
    }
    // Add settings page
    public function rrw_gyrix_settings_page() 
	{	
		if(is_admin() || current_user_can('manage_opions'))
        {
            $url = new rrw_gyrix_view;
            $redirectUrls = $url->rrw_gyrix_get_url();
            $gyrixhtml = new rrw_gyrix_get_html;
            $gyrixhtml->rrw_gyrix_header();
            if($redirectUrls)
                $gyrixhtml->rrw_gyrix_show($redirectUrls);
            else
                $gyrixhtml->rrw_gyrix_add_new();
            $gyrixhtml->rrw_gyrix_footer();
        }
	}
    // Add options page
    public function rrw_gyrix_options_page() 
    {   
        if(is_admin() || current_user_can('manage_opions'))
        {   
            $redirectOptions = '';
            //$options = new rrw_gyrix_view_options;
           // $redirectOptions = $url->rrw_gyrix_get_options();
            $gyrixhtml = new rrw_gyrix_get_html;
            $gyrixhtml->rrw_gyrix_options_header();
            $gyrixhtml->rrw_gyrix_show_options($redirectOptions);
            $gyrixhtml->rrw_gyrix_options_footer();
        }
    }
    // Add script and nonce
    public function rrw_gyrixenqueue_jscript() 
    {
        if(is_admin() || current_user_can('manage_opions'))
        {
            $userId = get_current_user_id();
            $ajaxSendGyrix = array(
                'ajaxSaveGyrix'=> wp_create_nonce('saveGyrixURL'. $userId ),
                'ajaxSaveOptionGyrix'=> wp_create_nonce('saveGyrixOptions'. $userId ),
                );
            wp_register_script(
                    'rrw_templatejs',
                    RRW_GYRIXTEMPLATEURL . 'admin/js/userurl-script.min.js',
                    array(), 
                    '1.0.0' 
                );

            wp_localize_script( 'rrw_templatejs', 'gyrixredirectnonce', $ajaxSendGyrix );
            wp_enqueue_script('rrw_templatejs');
        }
    }
}