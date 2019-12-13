<?php 
/**
* Hook list
*/

class rrw_gyrix_manager
{	
	public function __construct()
	{	
		$this->rrw_init_plugin();
	}
	public function rrw_init_plugin()
	{	
		 $this->rrw_load_files();
		 $gyrixredirectionfront = new rrw_from_front_class;
		 add_action('template_redirect', array($gyrixredirectionfront, 'rrw_from_front'));
		 if(is_admin() || current_user_can('manage_opions'))
         {
			
			$gyrixredirectionhook = new rrw_gyrix_manager_load;
			add_action('admin_menu', array($gyrixredirectionhook, 'rrw_gyrixcallhooks'));
			add_action( 'admin_enqueue_scripts', array($gyrixredirectionhook, 'rrw_gyrixenqueue_styles' ));
			add_action( 'admin_enqueue_scripts', array($gyrixredirectionhook, 'rrw_gyrixenqueue_jscript' ));
			$gyrixpages = new rrw_GyrixSaveURLS;
			add_action('wp_ajax_rrw_save_urls',array($gyrixpages , 'rrw_gyrix_save_urls' ));
			add_action('wp_ajax_rrw_save_options',array($gyrixpages , 'rrw_gyrix_save_options' ));
		}
	}
	public function rrw_load_files()
	{	
		include_once(RRW_GYRIXTEMPLATEPATH.'/includes/front-end-redirection.php');
		if(is_admin() || current_user_can('manage_opions'))
        {
			include_once(RRW_GYRIXTEMPLATEPATH.'/includes/redirect-user-loader.php');
			include_once(RRW_GYRIXTEMPLATEPATH."/templates/redirect-user-templates.php");
			include_once(RRW_GYRIXTEMPLATEPATH.'/admin/inc/redirect-user-save.php');
			include_once(RRW_GYRIXTEMPLATEPATH.'/admin/inc/redirect-user-view.php');
			
		}
	}
}
?>