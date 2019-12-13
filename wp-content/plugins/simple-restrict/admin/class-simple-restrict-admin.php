<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.awakensolutions.com
 * @since      1.0.0
 *
 * @package    Simple_Restrict
 * @subpackage Simple_Restrict/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Restrict
 * @subpackage Simple_Restrict/admin
 * @author     Awaken Solutions Inc. <info@awakensolutions.com>
 */
class Simple_Restrict_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $simple_restrict    The ID of this plugin.
	 */
	private $simple_restrict;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $taxonomy_terms_object_array = array();
	public $generic_restricted_message;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $simple_restrict       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $simple_restrict, $version ) {

		$this->simple_restrict = $simple_restrict;
		$this->version = $version;
		//can't call this here, call it via init hook in Simple_Restrict class so that taxonomies will have been created
		//$this->get_taxonomy_terms_object_array();


	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Restrict_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Restrict_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->simple_restrict, plugin_dir_url( __FILE__ ) . 'css/simple-restrict-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Restrict_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Restrict_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->simple_restrict, plugin_dir_url( __FILE__ ) . 'js/simple-restrict-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add custom taxonomies.
	 *
	 * @since    1.0.0
	 * @param      string    $simple_restrict       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function add_custom_taxonomies() {
		// Add new "Permission" taxonomy to Pages
		register_taxonomy('simple-restrict-permission', 'page', array(
			// Hierarchical taxonomy (like categories) so that checkboxes are used when editing page
			'hierarchical' => true,
			// Show in WordPress 5 (Gutenberg)
			'show_in_rest' => true,
			// This array of options controls the labels displayed in the WordPress Admin UI
			'labels' => array(
				'name' => _x( 'Permissions', 'taxonomy general name', 'simple-restrict' ),
				'singular_name' => _x( 'Permission', 'taxonomy singular name', 'simple-restrict' ),
				'search_items' =>  __( 'Search Permissions', 'simple-restrict' ),
				'all_items' => __( 'All Permissions', 'simple-restrict' ),
				'parent_item' => __( 'Parent Permission', 'simple-restrict' ),
				'parent_item_colon' => __( 'Parent Permission:', 'simple-restrict' ),
				'edit_item' => __( 'Edit Permission', 'simple-restrict' ),
				'update_item' => __( 'Update Permission', 'simple-restrict' ),
				'add_new_item' => __( 'Add New Permission', 'simple-restrict' ),
				'new_item_name' => __( 'New Permission Name', 'simple-restrict' ),
				'menu_name' => __( 'Permissions', 'simple-restrict' ),
			),
			// Control the slugs used for this taxonomy
			'rewrite' => array(
				'slug' => 'simple-restrict-permissions', // This controls the base slug that will display before each term
				'with_front' => false, // Don't display the category base before "/simple-restrict-permissions/"
				'hierarchical' => false // This would have allowed URL's like "/simple-restrict-permissions/gold/gold2/"
			),
		));

	}

	// Get an array of all the terms for the Page taxonomy 'simple-restrict-permission' (these can be anything the user types, but we can't create matching user metas without prefixing them first, so we will prefix this array too)
	public function get_taxonomy_terms_object_array() {
		$taxonomy = 'simple-restrict-permission';
		$term_args = array(
			'hide_empty' => false,
			'orderby' => 'name',
			'order' => 'ASC'
		);
		//echo('taxonomy = '.$taxonomy);
		$this->taxonomy_terms_object_array = get_terms($taxonomy,$term_args);
	}


	//Also defined in class-simple-restrict-public.php
	public function define_initial_variables () {
		$this->generic_restricted_message = __("Sorry, this content is restricted to users who are logged in with the correct permissions.", 'simple-restrict');
	}



	// Add new checkboxes (user meta) to profile ($user is sent to this function from action that fires it). Instead of the manage_options capability, we will require the edit_users capability (as per https://wordpress.org/support/topic/permission-for-user-editors/#post-11162529)
	public function add_permission_checkboxes( $user ) {
		if(current_user_can('edit_users')) {
	?>
	        <h3 class="simple-restrict-permissions-title"><?php _e('Permissions', 'simple-restrict'); ?></h3>
	        <table class="form-table simple-restrict-permissions-table">
				<?php foreach($this->taxonomy_terms_object_array as $taxonomy_object) {
					$taxonomy_slug = $taxonomy_object->slug;
					$taxonomy_slug_prefixed = 'simple-restrict-' . $taxonomy_slug;
			 	?>
					<tr class="user-profile-simple-restrict-permission">
						<th scope="row"><?php echo($taxonomy_object->name) ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php echo($taxonomy_slug_prefixed) ?></span></legend>
								<label for="<?php echo($taxonomy_slug_prefixed) ?>">
									<input type="checkbox" name="<?php echo($taxonomy_slug_prefixed) ?>" id="<?php echo($taxonomy_slug_prefixed) ?>" value="yes" <?php if (esc_attr( get_the_author_meta( $taxonomy_slug_prefixed, $user->ID )) == "yes") echo "checked"; ?> /> <?php printf(__('Allow this user to see pages marked as <em>%s</em>','simple-restrict'),$taxonomy_object->name); ?>
								</label><br>
							</fieldset>
						</td>
					</tr>
				<?php } //end foreach ?>
	        </table>
	<?php
		}
	}

	// Save checkboxes (user meta) to profile ($user_id is sent to this function from action that fires it)
	public function save_permission_checkboxes( $user_id ) {
		if(current_user_can('edit_users')) {
			foreach($this->taxonomy_terms_object_array as $taxonomy_object) {
				$taxonomy_slug = $taxonomy_object->slug;
				$taxonomy_slug_prefixed = 'simple-restrict-' . $taxonomy_slug;
			    update_user_meta( $user_id, $taxonomy_slug_prefixed, sanitize_text_field( $_POST[$taxonomy_slug_prefixed] ) );
			}
		}
	}

	// Create settings menu item
	public function custom_admin_menu() {
		$page_title = __('Simple Restrict Settings', 'simple-restrict'); //the name displayed in the title bar of the browser
		$menu_title = __('Simple Restrict', 'simple-restrict'); //the name displayed in the Menu item
		$capability = 'manage_options'; //the capabilities the user needs to use this settings page e.g. manage_options
		$menu_slug = 'simple_restrict_plugin'; //the slug that the wordpress admin uses to identify this page
		$function = array($this, 'simple_restrict_options_page'); //the callback function that displays your settings page
	    add_options_page(
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$function
	    );
	}

	// Display settings page
	public function simple_restrict_options_page() { ?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<h2 style="margin-top: 20px;"><?php _e('Page Permissions', 'simple-restrict') ?></h2>
			<p><?php _e('This plugin adds a new Permissions taxonomy to your pages. Administrators can create/assign new permissions from the Edit Page screen (you can also use the Quick Edit link). You can add/edit/delete permissions from the Permissions sub-menu under the Pages menu. Pages with no assigned permissions can be seen by everyone.', 'simple-restrict') ?></p>
			<h2><?php _e('User Permissions', 'simple-restrict') ?></h2>
			<p><?php _e('Administrators can add/remove permissions from a User using the checkboxes on the Edit User screen. The All Users page has a column that shows the permissions assigned to each user.', 'simple-restrict') ?></p>
			<form action="options.php" method="POST">
				<?php
					//renders a few hidden fields that tell WordPress which settings we are about to update on this page. Argument should be the settings group (and should be the first argument you use for all the times you use register_setting)
					settings_fields('simple_restrict_settings_group');
					//output all the sections and fields that have been added to the options page (below). Argument should be the menu page slug which was the before-last argument of the add_options_page() call, as well as used in add_settings_section() and add_settings_field().
					do_settings_sections('simple_restrict_plugin');
					submit_button();
				?>
			</form>
		</div>

	<?php
	}

	// Register new admin settings with WordPress and add them to the settings page
	public function simple_restrict_admin_init(){

		// Register one setting with the WordPress Settings API. First argument is the settings group name used in settings_fields. The second is the particular setting name (we'd need to repeat this line and change this second argument for other options). The third option is an optional callback to sanitize our data.
		register_setting( 'simple_restrict_settings_group', 'simple_restrict_setting_one' );
		// Add a section for the fields with a unique ID, title, function to display it, page slug (matches do_settings_sections call)
		$restriction_message_section_title = __('Restriction Message', 'simple-restrict');
		add_settings_section('simple_restrict_section_one', $restriction_message_section_title, array($this, 'simple_restrict_section_one_callback'), 'simple_restrict_plugin');
		// Add a field with unique ID, title, function to display it, page slug (matches do_settings_sections call), and section.
		// This field's callback function will show the field and associate it's value with a register_setting setting.
		$restriction_message_label = __('Restriction Message', 'simple-restrict');
		add_settings_field('simple_restrict_field_one', $restriction_message_label, array($this, 'simple_restrict_field_one_callback'), 'simple_restrict_plugin', 'simple_restrict_section_one');
		// So add_settings_section creates a section, add_settings_field puts a field in that section, do_settings_sections shows that section (and it's field) on the page. And each field is associated with a settings that we save using WordPress settings API and register_setting.

		// Add a setting to redirect vs showing a message
		register_setting( 'simple_restrict_settings_group', 'simple_restrict_setting_redirect' );
		add_settings_section('simple_restrict_option_redirect_section', "Redirect vs Message", array($this, 'simple_restrict_option_redirect_message'), 'simple_restrict_plugin');
		add_settings_field("simple_restrict_option_redirect_field", "Redirect user to login page:", array($this, 'simple_restrict_option_redirect_checkbox'), "simple_restrict_plugin", 'simple_restrict_option_redirect_section');
	}

	// Callback for the redirect checkbox
	public function simple_restrict_option_redirect_checkbox($args) {
		$setting = get_option( 'simple_restrict_setting_redirect' );
		?>
			<input type="checkbox" name="simple_restrict_setting_redirect" value="1" <?php checked(1, get_option('simple_restrict_setting_redirect'), true); ?> />
		<?php
	}

	// Callback for the redirect option
	public function simple_restrict_option_redirect_message() {
		echo('<p>');
		_e("If this option is enabled, the user will be redirected to the login page instead of shown the Restriction Message.");
		echo('<p>');
	}

	// Callback for the created section
	public function simple_restrict_section_one_callback() {
		echo('<p>');
		_e("If a page has Permissions assigned, the content will only be visible to Users that have one of those same Permissions assigned. Otherwise, the content will be replaced by the message below.",'simple-restrict');
		echo('</p>');
	}

	// Callback to fetch the value of the setting that we registered earlier (so be sure to call the same setting name so WordPress knows which option to update with this field) and add the field to the form.
	public function simple_restrict_field_one_callback() {
		//$setting = esc_attr( get_option( 'simple_restrict_setting_one' ) );
		$setting = get_option( 'simple_restrict_setting_one' );

		wp_editor( $setting, 'simple-restrict-message', array( 'textarea_name' => 'simple_restrict_setting_one', 'teeny' => true ) );
	    //echo "<input type='text' name='simple_restrict_setting_one' value='$setting' class='simple_restrict_setting_one_text_field' />";
	    echo '<p class="description" id="simple_restrict_setting_one">';
	    printf(__("Default: <em>%s</em>",'simple-restrict'),$this->generic_restricted_message);
	    echo '</p>';
	}

	public function add_permissions_column($columns) {
	    $columns['simple-restrict-permissions'] = 'Permissions';
	    return $columns;
	}

	public function show_permissions_column_content( $val, $column_name, $user_id ) {
		// Fired by manage_users_custom_column
	    $user = get_userdata( $user_id );
		$current_user_permissions = array();
		
		// For this particular row, do the following in the Permissions column (repeats for all rows)
	    if ($column_name == 'simple-restrict-permissions') {
			foreach($this->taxonomy_terms_object_array as $taxonomy_object) {
				$taxonomy_slug = $taxonomy_object->slug;
				$taxonomy_slug_prefixed = 'simple-restrict-' . $taxonomy_slug;
				// Check if the current user meta includes the prefixed permission term's slug
				if (esc_attr( get_the_author_meta( $taxonomy_slug_prefixed, $user_id )) == "yes") {
					// Only add to array if it wasn't already there
				    if(!in_array($taxonomy_object->slug, $current_user_permissions, true)){
						array_push($current_user_permissions,$taxonomy_object->name);
				    }
				}
			}
			$list_of_permissions = '';
			foreach($current_user_permissions as $current_user_permission) {
				//echo($current_user_permission.'<br />');
				$list_of_permissions .= $current_user_permission.'</br>';
			}

            return $list_of_permissions;
	    }
	    return;
	}		

	// Add new fields to new user form
	public function user_new_form_function ( $type ){
	    if( 'add-new-user' !== $type )
	        return;

		// Instead of the manage_options capability, we will require the edit_users capability (as per https://wordpress.org/support/topic/permission-for-user-editors/#post-11162529)
		if(current_user_can('edit_users')) {
		//echo('<pre>');
		//print_r($this->taxonomy_terms_object_array);
		//echo('<pre>');
	?>
	        <h3 class="permission-checkbox-heading"><?php _e('Permissions', 'simple-restrict'); ?></h3>
	        <table class="form-table">
				<?php foreach($this->taxonomy_terms_object_array as $taxonomy_object) {
					$taxonomy_slug = $taxonomy_object->slug;
					$taxonomy_slug_prefixed = 'simple-restrict-' . $taxonomy_slug;
				?>
					<tr class="user-profile-simple-restrict-permission">
						<th scope="row"><?php echo($taxonomy_object->name) ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php echo($taxonomy_slug_prefixed) ?></span></legend>
								<label for="<?php echo($taxonomy_slug_prefixed) ?>">
									<input type="checkbox" name="<?php echo($taxonomy_slug_prefixed) ?>" id="<?php echo($taxonomy_slug_prefixed) ?>" value="yes" /> <?php printf(__('Allow this user to see pages marked as <em>%s</em>','simple-restrict'),$taxonomy_object->name); ?>
								</label><br>
							</fieldset>
						</td>
					</tr>
				<?php } //end foreach ?>
	        </table>
	<?php
		}

	}

	// Now save these fields from the new user form
	public function save_custom_user_profile_fields($user_id) {
		// again do this only if you can (again, use edit_users instead of manage_options)
		if(!current_user_can('edit_users'))
			return false;

		foreach($this->taxonomy_terms_object_array as $taxonomy_object) {
			$taxonomy_slug = $taxonomy_object->slug;
			$taxonomy_slug_prefixed = 'simple-restrict-' . $taxonomy_slug;
			if($_POST[$taxonomy_slug_prefixed] == 'yes') {
			    update_user_meta( $user_id, $taxonomy_slug_prefixed, 'yes' );
			}
		}
	}

}
