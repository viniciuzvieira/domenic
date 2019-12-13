<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.awakensolutions.com
 * @since      1.0.0
 *
 * @package    Simple_Restrict
 * @subpackage Simple_Restrict/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Restrict
 * @subpackage Simple_Restrict/public
 * @author     Awaken Solutions Inc. <info@awakensolutions.com>
 */
class Simple_Restrict_Public {

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
	 * @param      string    $simple_restrict       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $simple_restrict, $version ) {

		$this->simple_restrict = $simple_restrict;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->simple_restrict, plugin_dir_url( __FILE__ ) . 'css/simple-restrict-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->simple_restrict, plugin_dir_url( __FILE__ ) . 'js/simple-restrict-public.js', array( 'jquery' ), $this->version, false );

	}
	
	// Get an array of all the terms for the Page taxonomy 'simple-restrict-permission' (same as function in class-simple-restrict-admin.php but we also need that data here).
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
	
	//Also defined in class-simple-restrict-admin.php
	public function define_initial_variables () {
		$this->generic_restricted_message = __("Sorry, this content is restricted to users who are logged in with the correct permissions.", 'simple-restrict');
	}
	
	public function display_message() {

		$user_defined_restricted_message = get_option( 'simple_restrict_setting_one' );

		if( (isset($user_defined_restricted_message)) && ($user_defined_restricted_message != '') ) {
			return $user_defined_restricted_message;
		} else {
			return $this->generic_restricted_message;
		}

	}

	
	// Restrict content of specific page(s)
	public function restrict_content($content) {
		
		
		// We must prefix 'simple-restrict' to all the user metas (to not conflict with WordPress existing metas)
		$current_user_permissions = array(); // User permissions will be prefixed by default
		$current_page_permissions = array(); // Page permissions are user-defined, so we prefix them manually in next array
		$current_page_permissions_prefixed = array();  // This array will prefix each of the page permissions
		
		$postID = get_the_ID();
		//echo('$postID' . $postID);

		//echo("<br />Current page's permissions:<br />");
		// Create an array of the current page's permissions
		$page_terms_list = wp_get_post_terms($postID, 'simple-restrict-permission', array("fields" => "all"));
		foreach($page_terms_list as $current_term) {
		    if(!in_array($current_term->slug, $current_page_permissions, true)){
			    $current_term_slug = $current_term->slug;
			    $current_term_slug_prefixed = 'simple-restrict-'.$current_term_slug;			    
				array_push($current_page_permissions,$current_term->slug);
				array_push($current_page_permissions_prefixed,$current_term_slug_prefixed);
				//print_r($current_page_permissions_prefixed);
		    }
		}
		// Debug 
		/*
		foreach($current_page_permissions as $current_page_permission) {
			echo('Page permission: '.$current_page_permission.'<br />');
		}
		foreach($current_page_permissions_prefixed as $current_page_permission) {
			echo('Page permission prefixed: '.$current_page_permission.'<br />');
		}
		*/
		

		// If the page has no permissions required, show the content and don't bother checking user
		if(empty($current_page_permissions)) {
			return $content;
		// Otherwise check the user to see if it's permissions match the page's permissions
		} else {
			//echo("<br />Current user's permissions:<br />");
			// Create an array of the current user's permissions by cycling through all possible page permissions and putting any matches into user permissions array
			$current_user_id = get_current_user_id();
			//echo('$current_user_id: ' . $current_user_id);			
			// Only populate user permissions if this is a registered user, otherwise leave permissions array empty
			if($current_user_id != 0) {
				foreach($this->taxonomy_terms_object_array as $taxonomy_object) {
					$taxonomy_slug = $taxonomy_object->slug;
					$taxonomy_slug_prefixed = 'simple-restrict-' . $taxonomy_slug;
					//echo('$taxonomy_slug_prefixed ' . $taxonomy_slug_prefixed);
					if (esc_attr( get_the_author_meta( $taxonomy_slug_prefixed, $current_user_id )) == "yes") {
						// Only add to array if it wasn't already there ($current_user_permissions values are always prefixed)
					    if(!in_array($taxonomy_slug_prefixed, $current_user_permissions, true)){
							array_push($current_user_permissions,$taxonomy_slug_prefixed);
					    }
					}
				}
			}
			// Debug 
			/*
			foreach($current_user_permissions as $current_user_permission) {
				echo('User permission: '.$current_user_permission.'<br />');
			}
			*/
			
			$user_defined_restricted_message = esc_attr( get_option( 'simple_restrict_setting_one' ));
			$user_defined_restricted_message = get_option( 'simple_restrict_setting_one' );
			$simple_restrict_setting_redirect = get_option( 'simple_restrict_setting_redirect' );
			// If the user's permissions don't match any of the page's permissions
			if (!array_intersect($current_page_permissions_prefixed, $current_user_permissions)) {

				// Redirect to login or display message
				if( isset($simple_restrict_setting_redirect) && ($simple_restrict_setting_redirect == 1) ) {
					header("Location: /wp-login.php?redirect_to=" . $_SERVER['REQUEST_URI']);
					exit;
				} else {
					add_filter('the_content', array($this, 'display_message'));
				}

			} else {
				// Otherwise show the regular content because it is restricted but the user has the permission
				// (Note that $content is empty so below does nothing, and our script simply ends without a restriction)
				return $content;
			}
		}
	}


}
