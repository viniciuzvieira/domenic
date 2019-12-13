<?php
/*
* Include the redirection class on template load
*/

class rrw_from_front_class
{	
	public function rrw_from_front()
	{	

		
		//fetch all the post with type - rrw_gyrix_urls
		$post_data = array();
		$url_array = array();
		$return = false;
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'	   => 'draft',
			'post_type'        => 'rrw_gyrix_urls'
		);

		$the_query = new WP_Query( $args );

		$posts = $the_query->get_posts();
		$post_data = array();
		global $wp_query;
   	 	$page_id = $wp_query->post->ID;
   		$current_user = wp_get_current_user();
   		$current_roles = $current_user->roles;
   		$rrw_multi_role = get_option('rrw_multirole_gyrix',true);
   		//redirect users if entry exist
		if(count($posts))
		{	
			
			foreach($posts as $post) :

				if( $post->post_title == $page_id )
				{	
					
					$the_rrw_roles = json_decode($post->post_content);

					if($rrw_multi_role == 'multi') {
						foreach ($current_roles as $key => $current_roles_value) {
							if(in_array($current_roles_value, $the_rrw_roles ))
							{	
								$destination = $post->post_name;
								$destination_url = get_permalink($destination);
								header("Location:".$destination_url); /* Redirect user */
							}
						}
					}
					else {
						if(in_array($current_roles[0], $the_rrw_roles ))
							{	
								$destination = $post->post_name;
								$destination_url = get_permalink($destination);
								header("Location:".$destination_url); /* Redirect user */
							}
					}
					
					if(in_array('login', $the_rrw_roles ))
					{
						$destination = $post->post_name;
						$destination_url = get_permalink($destination);
						header("Location:".$destination_url); /* Redirect user */
					}
					if(in_array('logout', $the_rrw_roles ))
					{
						$destination = $post->post_name;
						$destination_url = get_permalink($destination);
						header("Location:".$destination_url); /* Redirect user */
					}

				}
			endforeach;
			
		}		
	}

}
?>