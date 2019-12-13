<?php

class rrw_gyrix_get_html
{
	public function rrw_gyrix_header()
	{
		?>
			<div id="redirect-template-page">
			<h2>Redirect Users Settings Page</h2>
			<button class="add_entry" >Add New Redirection</button>
			<span class="rrw_note">*click on - icon on the right to delete.</span>
			<form method="post" name="update_rrw_url" id="update_rrw_url">
				<div class="rrw_template_block">
		<?php
	}
	public function rrw_gyrix_options_header()
	{
		?>
			<div id="redirect-template-page">
			<h2>Options</h2>
			<form method="post" name="options_rrw_url" id="options_rrw_url">
				<div class="rrw_options_block">
		<?php
	}
	public function rrw_gyrix_footer()
	{
		?>
			</div>
			</form>
			<button class="save_redirecturl" style="margin-top:10px;">Save</button>
			</div>
		<?php
	}
	public function rrw_gyrix_options_footer()
	{
		?>
			</div>
			</form>
			<button class="save_redirectoptions" style="margin-top:10px;">Save</button>
			</div>
		<?php
	}

	//when no post with type - rrw_gyrix_urls present this function add one div
	public function rrw_gyrix_add_new()
	{
		if(is_admin() || current_user_can('manage_opions'))
		{
		?>
		<div class="url_block block_urls">
			<span class="dashicons dashicons-minus delete_current_url" style = 'content: "\f460";'></span>
			<div class="urlrrw_div">
			<?php 	
				//fetch all the pages available
				$args = array(
								'sort_order' => 'asc',
								'sort_column' => 'post_title',
								'hierarchical' => 1,
								'exclude' => '',
								'include' => '',
								'meta_key' => '',
								'meta_value' => '',
								'child_of' => 0,
								'parent' => -1,
								'exclude_tree' => '',
								'number' => '',
								'offset' => 0,
								'post_type' => 'page',
								'post_status' => 'publish'
							); 
				$pages_gyrix = get_pages($args); 
				foreach ($pages_gyrix as $key => $value) 
				{
					
					$id_gyrix_page = $value->ID;
					$name_gyrix_page = $value->post_title;
					$pages_array[$id_gyrix_page] = $name_gyrix_page;
					
				}
				//fetch all the role available
				global $wp_roles;
				$wp_roles = new WP_Roles();
				$wp_role_names = $wp_roles->get_names();
			?>
				<div class="pages_urls">
				<input name ="post_id" value="0" type="hidden" class = "post_id" required/>
				<!-- Select source -->
					<table>
						<tr>
							<td>
								<span>Select Source Page/Pages</span>
							</td>
							<td>
								<select name="source_page" class="source_page"  required>
									<?php
									foreach($pages_array as $key => $value):
										echo '<option value="'.$key.'">'.$value.'</option>'; //close your tags!!
									endforeach;
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<!-- Select roles -->
								<span>Select Role/Roles</span>
							</td>
							<td>
								<select name="rrw_role" class="rrw_role" multiple required>
									
									<?php
									foreach($wp_role_names as $key => $value):
										echo '<option value="'.$key.'">'.$value.'</option>'; //close your tags!!
									endforeach;
									?>

									<option value="login">All Roles</option>
									<option value="logout">Logout User</option>
								</select>
							</td>
						</tr>
						<tr>
								<!-- Select destination -->
							<td>
								<span>Select Destination Page</span>
							</td>
							<td>
								<select name="destination_page" class="destination_page" required> 
									<?php
									foreach($pages_array as $key => $value):
										echo '<option value="'.$key.'">'.$value.'</option>'; //close your tags!!
									endforeach;
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var pages_array =<?php echo json_encode($pages_array); ?>;
			var wp_role_names = <?php echo json_encode($wp_role_names); ?>;
		</script>
		<?php
	}
	}

	// when posts with type - rrw_gyrix_urls are already present
	public function rrw_gyrix_show($urls)
	{
		if(is_admin() || current_user_can('manage_opions'))
		{
		foreach ($urls as $item ) : 
			?>

		<div class="url_block block_urls">
			<span class="dashicons dashicons-minus delete_current_url" style = 'content: "\f460";'></span>
			<div class="urlrrw_div">
			<?php 	
					//fetch all the pages available
					$args = array(
									'sort_order' => 'asc',
									'sort_column' => 'post_title',
									'hierarchical' => 1,
									'exclude' => '',
									'include' => '',
									'meta_key' => '',
									'meta_value' => '',
									'child_of' => 0,
									'parent' => -1,
									'exclude_tree' => '',
									'number' => '',
									'offset' => 0,
									'post_type' => 'page',
									'post_status' => 'publish'
								); 
					$pages_gyrix = get_pages($args); 
					foreach ($pages_gyrix as $key => $value) {
						
						$id_gyrix_page = $value->ID;
						$name_gyrix_page = $value->post_title;
						$pages_array[$id_gyrix_page] = $name_gyrix_page;
						
					}
					//fetch all the role available
					global $wp_roles;
					$wp_roles = new WP_Roles();
					$wp_role_names = $wp_roles->get_names();

			?>
					<div class="pages_urls">
						<input name ="post_id" value="<?php echo $item['Id']; ?>" type="hidden" class = "post_id" readonly required/>
						<!-- Select source -->
						<table>
							<tr>
								<td>
									<span>Select Source Page/Pages</span>
								</td>
								<td>
									<select name="source_page" class="source_page"  required>
										<?php
										foreach($pages_array as $key => $value):
											if($key == $item['title'])
											{
												$selected = 'selected';
											}
											else 
											{
												$selected = '';
											}
											echo '<option value="'.$key.'" '.$selected.' >'.$value.'</option>'; 
										endforeach;
										?>
									</select>
								</td>
							</tr>
							<!-- Select roles -->
							<tr>
								<td>
									<span>Select Role/Roles</span>
								</td>
								<td>
									<select name="rrw_role" class="rrw_role" multiple required>
										
										<?php
										$content = json_decode($item['content']);
										
										foreach($wp_role_names as $key => $value):
											$login = '';
											$logout = '';
											if( in_array($key, $content ))
											{
												$selected = 'selected';
											}
											else 
											{
												$selected = '';
											}
											echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>'; 
											if( in_array('login', $content ))
											{
												$login = 'selected';
											}
											else if( in_array('logout', $content ))
											{
												$logout = 'selected';
											}
											else
											{
												$login = '';
												$logout = '';
											}
										endforeach;

										?>

										<option value="login" <?php echo $login ?> >All Roles</option>
										<option value="logout" <?php echo $logout ?> >Logout User</option>
									</select>
								</td>
							</tr>
							<!-- Select destination -->
							<tr>
								<td>
									<span>Select Destination Page</span>
								</td>
								<td>
									<select name="destination_page" class="destination_page" required> 
										<?php
										foreach($pages_array as $key => $value):
											if($key == $item['name'])
											{
												$selected = 'selected';
											}
											else 
											{
												$selected = '';
											}
											echo '<option value="'.$key.'" '.$selected.' >'.$value.'</option>'; 
										endforeach;
										?>
									</select>
								</td>
							</tr>
						</table>
					</div>
						
			</div>
		</div>

		<?php endforeach; ?>
		<script type="text/javascript">
			var pages_array =<?php echo json_encode($pages_array); ?>;
			var wp_role_names = <?php echo json_encode($wp_role_names); ?>;
		</script>
		<?php
		}	
	}
	// rrw_gyrix_show_options show options
	public function rrw_gyrix_show_options($options)
	{
		if(is_admin() || current_user_can('manage_opions'))
		{
			?>

		<div class="url_block block_urls">
			<div class="urlrrw_div">
			<?php 						
					$rrw_multirole_gyrix = get_option('rrw_multirole_gyrix', true);
				?>
					<div class="pages_urls">
						
						<table>
							<!-- Select option to use only primary role for the functionality -->
							<tr>
								<td>
									<input type="checkbox" class="rrw_multirole" name="rrw_multirole" id="rrw_multirole" <?php if($rrw_multirole_gyrix == 'multi'){ echo 'checked'; } ?> />
								</td>
								<td>
									<label for="rrw_multirole">Tick to consider redirection functionality for multiple roles of user.<br><small> (Here, if multiple redirection rules will be defined for user for a page, then user's primary role will be considered )</small></label>
								</td>
							</tr>
						</table>
					</div>
						
			</div>
		</div>
		<?php
	}
}
}