<?php
/*
get redirection data	
*/
class rrw_gyrix_view
{
	public function rrw_gyrix_get_url()
	{
		if(!is_admin() && !current_user_can('manage_opions'))
        {
			die(0);
		}
		$post_data = array();
		$url_array = array();
		$return = false;
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'rrw_gyrix_urls'
		);
		$the_query = new WP_Query( $args );
		$posts = $the_query->get_posts();
		$post_data = array();
		if(count($posts))
		{
			foreach($posts as $post) :
				$post_data[] = array('Id'=>intval($post->ID),
									'title' => $post->post_title, 
									'content' => $post->post_content, 
									'name' => $post->post_name
								);
			endforeach;
			return $post_data;
		}		
		return(false);
	}
}
