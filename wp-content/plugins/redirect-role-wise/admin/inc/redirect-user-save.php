<?php
/*
update data to database 
*/
class rrw_GyrixSaveURLS
{
	public function rrw_gyrix_save_urls()
	{	
		
		check_ajax_referer( 'saveGyrixURL'.get_current_user_id(), 'security' );
		if(!is_admin() && !current_user_can('manage_opions'))
        {
			die(0);
		}

		if(isset($_POST['sources']) )
		{
			$id = $_POST['post_id'];

			$sources = $_POST['sources'];
			
			if(isset($_POST['redirectrole']))
				$redirectrole = $_POST['redirectrole'];
			
			if(isset($_POST['destination']))
				$destination = $_POST['destination'];
			
			$duplicateSource = array_count_values($sources);
			foreach ($duplicateSource as $key => $value) 
			{		
				if($value > 1)
				{	
					$duplisource[] = $key;
				}
			}
			$roles = array();
			if(isset($duplisource))
			{
				foreach ($sources as $key => $value) {
					if(in_array($value, $duplisource))
					{	
						foreach ($redirectrole[$key] as $key1 => $value1) {
							array_push($roles, $value1);
						}
					}
				}
				$duplicateEntry = array_count_values($roles);
				foreach ($duplicateEntry as $key2 => $value2) {
					if($value2 > 1)
					{
						die('error');
					}
				}

			}
			foreach ($sources as $key => $value) 
			{	
				
				if(intval($id[$key]) === 0)
				{	
					$my_post = array(
						  'post_title'    => sanitize_text_field($sources[$key]),
						  'post_name'     => sanitize_text_field($destination[$key]),
						  'post_content'  => sanitize_text_field(json_encode($redirectrole[$key])),
						  'post_type'     => sanitize_text_field('rrw_gyrix_urls')
						);
						
					// Insert the post into the database
					$post_id = wp_insert_post( $my_post );
					
				}
				else
				{
					$my_post = array(
					    'ID'           => intval($id[$key]),
					    'post_title'    => sanitize_text_field($sources[$key]),
						'post_name'     => sanitize_text_field($destination[$key]),
						'post_content'  => sanitize_text_field(json_encode($redirectrole[$key])),
					  );
					// Update the post into the database
					wp_update_post(sanitize_post($my_post));	
					
				}				
			}
		}
		// check if any entry to delete
		if(isset($_POST['url_delete']))
		{
			foreach ($_POST['url_delete'] as $url_delete) 
			{
				wp_delete_post(intval($url_delete));
			}
		}
		die("updated");
	}
	public function rrw_gyrix_save_options()
	{	
		
		check_ajax_referer( 'saveGyrixOptions'.get_current_user_id(), 'security' );
		if(!is_admin() && !current_user_can('manage_opions'))
        {
			die(0);
		}
		if(isset($_POST['rrw_multirole']) )
		{
			$rrw_multirole = $_POST['rrw_multirole'];
			
			update_option('rrw_multirole_gyrix',$rrw_multirole);
		
			die("updated");
		}
	}
}


