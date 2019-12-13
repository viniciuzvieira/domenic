<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.awakensolutions.com
 * @since      1.0.0
 *
 * @package    Simple_Restrict
 * @subpackage Simple_Restrict/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simple_Restrict
 * @subpackage Simple_Restrict/includes
 * @author     Awaken Solutions Inc. <info@awakensolutions.com>
 */
class Simple_Restrict {
	

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Simple_Restrict_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $simple_restrict    The string used to uniquely identify this plugin.
	 */
	protected $simple_restrict;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->simple_restrict = 'simple-restrict';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Simple_Restrict_Loader. Orchestrates the hooks of the plugin.
	 * - Simple_Restrict_i18n. Defines internationalization functionality.
	 * - Simple_Restrict_Admin. Defines all hooks for the admin area.
	 * - Simple_Restrict_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-restrict-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-restrict-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-restrict-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-simple-restrict-public.php';

		$this->loader = new Simple_Restrict_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Simple_Restrict_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Simple_Restrict_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		// See Simple_Restrict_Admin class (class-simple-restrict-admin.php)
		$plugin_admin = new Simple_Restrict_Admin( $this->get_simple_restrict(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Define variables (like $this->generic_restricted_message) for other functions (plugins_loaded is called very early)
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'define_initial_variables' );

		// Add custom taxonomies
		$this->loader->add_action( 'init', $plugin_admin, 'add_custom_taxonomies' );
		$this->loader->add_action( 'init', $plugin_admin, 'get_taxonomy_terms_object_array' );

		// Add and save checkboxes when viewing own profile (show_user_profile) and others' (edit_user_profile)
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'add_permission_checkboxes' );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'add_permission_checkboxes' );
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'save_permission_checkboxes' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_permission_checkboxes' );
		
		// Add plugin settings menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'custom_admin_menu' );

		// Register new admin settings with WordPress and add them to the settings page 
		$this->loader->add_action( 'admin_init', $plugin_admin, 'simple_restrict_admin_init' );
		
		$this->loader->add_action( 'manage_users_columns', $plugin_admin, 'add_permissions_column', 10, 1 );
		$this->loader->add_action( 'manage_users_custom_column', $plugin_admin, 'show_permissions_column_content', 10, 3 );

		$this->loader->add_action( 'user_new_form', $plugin_admin, 'user_new_form_function' );
		$this->loader->add_action( 'user_register', $plugin_admin, 'save_custom_user_profile_fields' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Simple_Restrict_Public( $this->get_simple_restrict(), $this->get_version() );

		// Define variables (like $this->generic_restricted_message) for other functions (plugins_loaded is called very early)
		$this->loader->add_action( 'plugins_loaded', $plugin_public, 'define_initial_variables' );

		// Check permissions and restrict content if necessary (call with wp hook instead of init hook so we can access post ID for all pages including homepage)
		$this->loader->add_action( 'wp', $plugin_public, 'restrict_content' );
		
		$this->loader->add_action( 'init', $plugin_public, 'get_taxonomy_terms_object_array' );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_simple_restrict() {
		return $this->simple_restrict;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Simple_Restrict_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
}
