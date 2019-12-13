<?php
// add meta box
if ( !class_exists( 'our_team_metabox' ) ) {
	class our_team_metabox {
		/**
		 * @var array Meta box information
		 */
		public $meta_box;

		// Safe to start up
		public function __construct( $args ) {

			// Assign meta box values to local variables and add it's missed values
			$this->meta_box = $args;
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			// Enqueue common styles and scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			// Save post meta
			add_action( 'save_post', array( $this, 'save_data' ) );
			// Load text domain
			add_action( 'plugins_loaded', array( $this, 'text_domain' ) );
		}

		public function text_domain() {
			// Get mo file
			$text_domain = 'thim-our-team';
			$locale      = apply_filters( 'plugin_locale', get_locale(), $text_domain );
			$mo_file     = $text_domain . '-' . $locale . '.mo';
			// Check mo file global
			$mo_global = WP_LANG_DIR . '/plugins/' . $mo_file;
			// Load translate file
			if ( file_exists( $mo_global ) ) {
				load_textdomain( $text_domain, $mo_global );
			} else {
				load_textdomain( $text_domain, OUR_TEAM_PLUGIN_PATH . '/languages/' . $mo_file );
			}
		}

		function admin_enqueue_scripts() {
			wp_enqueue_style( 'thim-meta-box', OUR_TEAM_PLUGIN_URL . '/assets/css/meta-box.css', array(), "" );
		}

		/**
		 * Add meta box for multiple post types
		 *
		 * @return void
		 */
		public function add_meta_boxes() {
			// Use nonce for verification
			// create a custom nonce for submit verification later
			foreach ( $this->meta_box['pages'] as $page ) {
				add_meta_box(
					$this->meta_box['id'],
					$this->meta_box['title'],
					array( $this, 'meta_boxes_callback' ),
					$page,
					isset( $this->meta_box['context'] ) ? $this->meta_box['context'] : 'normal',
					isset( $this->meta_box['priority'] ) ? $this->meta_box['priority'] : 'default',
					$this->meta_box['fields']
				);
			}
		}

		// Callback function, uses helper function to print each meta box
		public function meta_boxes_callback( $post, $fields ) {
			// create a custom nonce for submit verification later
			echo '<input type="hidden" name="thim_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';

			foreach ( $fields['args'] as $field ) {
				switch ( $field['type'] ) {
					case 'textfield':
						$this->textfield( $field, $post->ID );
						break;
				}
			}
		}

		private function textfield( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}

			printf(
				'<div class="thim-field%s"><div class="thim-label"><label>%s: </label></div> <div class="thim-input"><input type="text" name="%s" value="%s" /> <div class="desc">%s</div></div></div>',
				$extra_class,
				$field['name'],
				$field['id'],
				$post_meta,
				$field['desc']
			);
		}

		// Save data from meta box
		public function save_data( $post_id ) {
			// verify nonce
			if ( !isset( $_POST['thim_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['thim_meta_box_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			// check permissions
			if ( 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			foreach ( $this->meta_box['fields'] as $field ) {
				$old = get_post_meta( $post_id, $field['id'], true );
				$new = $_POST[$field['id']];
				if ( $new && $new != $old ) {
					update_post_meta( $post_id, $field['id'], $new );
				} elseif ( '' == $new && $old ) {
					delete_post_meta( $post_id, $field['id'], $old );
				}
			}
		}
	}
}


// add class Our Team
if ( !class_exists( 'THIM_Our_Team' ) ) {
	/**
	 * Thim Theme
	 *
	 * Manage the our_team in the THIM Framework
	 *
	 * @class      THIM_Our_Team
	 * @package    thimpress
	 * @since      1.0
	 * @author     kien16
	 */
	class THIM_Our_Team {

		/**
		 * @var string
		 * @since 1.0
		 */
		public $version = THIM_OUR_TEAM_VERSION;

		/**
		 * @var object The single instance of the class
		 * @since 1.0
		 */
		protected static $_instance = null;

		/**
		 * @var string
		 * @since 1.0
		 */
		public $plugin_url;

		/**
		 * @var string
		 * @since 1.0
		 */
		public $plugin_path;

		/**
		 * The array of templates that this plugin tracks.
		 *
		 * @var      array
		 */
		protected $templates;

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'our_team_template_path', 'thim-our-team/' );
		}

		/**
		 * Main plugin Instance
		 *
		 * @static
		 * @return object Main instance
		 *
		 * @since  1.0
		 * @author Antonino Scarfì <antonino.scarfi@yithemes.com>
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers the our_team cpt
		 */
		public function __construct() {

			// Define the url and path of plugin
			$this->plugin_url  = untrailingslashit( plugins_url( '/', __FILE__ ) );
			$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

			//add_action( 'admin_enqueue_scripts', array( $this, 'thim_scripts' ) );
			add_action( 'wp_footer', array( $this, 'thim_scripts' ) );
			//add_action( 'wp_enqueue_scripts', array( $this, 'thim_scripts' ) );

			// Register CPTU
			add_action( 'after_setup_theme', array( $this, 'register_cptu' ), 20 );

			// Register Taxonomy
			add_action( 'after_setup_theme', array( $this, 'register_taxonomy' ), 20 );


			// require_once 'lib/aq_resizer.php';

			// Display custom update messages for posts edits
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

			// Include OWN Metabox
			add_action( 'admin_init', 'our_team_register_meta_boxes' );
			function our_team_register_meta_boxes() {
				$meta_boxes = apply_filters( 'our_team_meta_boxes', array() );
				foreach ( $meta_boxes as $meta_box ) {
					new our_team_metabox( $meta_box );
				}
			}

			add_filter( 'our_team_meta_boxes', array( $this, 'our_team_register_metabox' ), 20 );
			add_action( 'template_include', array( $this, 'template_include' ), 20 );

		}

		/**
		 * Enqueue script and styles in admin side
		 *
		 * Add style and scripts to administrator
		 *
		 * @return void
		 * @since    1.0
		 * @author   thim
		 */
		public function thim_scripts() {
		}

		/**
		 * Template part Redirect.
		 *
		 * @access public
		 * @return void
		 */
		public function template_include( $template ) {
			if ( get_post_type() == "our_team" && ( is_category() || is_archive() ) ) {
				$template = $this->get_template_part( 'archive', "our-team" );
			} else if ( get_post_type() == "our_team" && is_single() ) {
				$template = $this->get_template_part( "single", "our-team" );
			}
			return $template;
		}

		/**
		 * Get template part (for templates like the shop-loop).
		 *
		 * @access public
		 *
		 * @param mixed  $slug
		 * @param string $name (default: '')
		 *
		 * @return void
		 */
		public function get_template_part( $slug, $name = '' ) {
			$template = '';
			// Look in yourtheme/slug-name.php and yourtheme/our_team/slug-name.php
			if ( $name ) {
				$template = locate_template( array( "{$slug}-{$name}.php", 'our-team/' . "{$slug}-{$name}.php" ) );
			}
			// Get default slug-name.php
			if ( !$template && $name && file_exists( $this->plugin_path . "/templates/{$slug}-{$name}.php" ) ) {
				$template = $this->plugin_path . "/templates/{$slug}-{$name}.php";
			}
			// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/our_team/slug.php
			if ( !$template ) {
				$template = locate_template( array( "{$slug}.php", 'our-team/' . "{$slug}.php" ) );
			}
			// Allow 3rd party plugin filter template file from their plugin
			$template = apply_filters( 'get_template_part', $template, $slug, $name );

			return $template;
		}

		/**
		 * Register the Custom Post Type Unlimited
		 *
		 * @return void
		 * @since  1.0
		 * @author thimpress
		 */
		public function register_cptu() {
			$labels = array(
				'name'               => _x( 'Our Team', 'Post Type General Name', 'thim-our-team' ),
				'singular_name'      => _x( 'Our Team', 'Post Type Singular Name', 'thim-our-team' ),
				'menu_name'          => __( 'Our Team', 'thim-our-team' ),
				'parent_item_colon'  => __( 'Parent Our Team:', 'thim-our-team' ),
				'all_items'          => __( 'All Members', 'thim-our-team' ),
				'view_item'          => __( 'View Member', 'thim-our-team' ),
				'add_new_item'       => __( 'Add New Member', 'thim-our-team' ),
				'add_new'            => __( 'New Member', 'thim-our-team' ),
				'edit_item'          => __( 'Edit Member', 'thim-our-team' ),
				'update_item'        => __( 'Update Member', 'thim-our-team' ),
				'search_items'       => __( 'Search Members', 'thim-our-team' ),
				'not_found'          => __( 'No team member found', 'thim-our-team' ),
				'not_found_in_trash' => __( 'No team member found in Trash', 'thim-our-team' ),
			);
			$args   = array(
				'labels'      => $labels,
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'menu_icon'   => 'dashicons-businessman',
				'public'      => true,
				'rewrite'     => array( 'slug' => _x( 'our_team', 'URL slug', 'thim-our-team' ) ),
				'has_archive' => true,
			);
			register_post_type( 'our_team', $args );
		}

		/**
		 * Register Our_Team Taxonomy
		 *
		 * @return void
		 * @since  1.0
		 */
		public function register_taxonomy() {
			// Our_Team Categories
			$labels = array(
				'name'                       => _x( 'Categories', 'Taxonomy General Name', 'thim-our-team' ),
				'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'thim-our-team' ),
				'menu_name'                  => __( 'Categories', 'thim-our-team' ),
				'all_items'                  => __( 'All Categories', 'thim-our-team' ),
				'parent_item'                => __( 'Parent Category', 'thim-our-team' ),
				'parent_item_colon'          => __( 'Parent Category:', 'thim-our-team' ),
				'new_item_name'              => __( 'New Category Name', 'thim-our-team' ),
				'add_new_item'               => __( 'Add New Category', 'thim-our-team' ),
				'edit_item'                  => __( 'Edit Category', 'thim-our-team' ),
				'update_item'                => __( 'Update Category', 'thim-our-team' ),
				'separate_items_with_commas' => __( 'Separate categories with commas', 'thim-our-team' ),
				'search_items'               => __( 'Search categories', 'thim-our-team' ),
				'add_or_remove_items'        => __( 'Add or remove categories', 'thim-our-team' ),
				'choose_from_most_used'      => __( 'Choose from the most used categories', 'thim-our-team' ),
			);
			$args   = array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'our_team_category' ),
			);
			register_taxonomy( 'our_team_category', 'our_team', $args );
		}

		/**
		 * Change updated messages
		 *
		 * @param  array $messages
		 *
		 * @return array
		 * @since  1.0
		 */
		public function updated_messages( $messages = array() ) {
			global $post, $post_ID;
			$messages['our_team'] = array(
				0  => '',
				1  => sprintf( __( 'Our Team updated. <a href="%s">View Our Team</a>', 'thim-our-team' ), esc_url( get_permalink( $post_ID ) ) ),
				2  => __( 'Custom field updated.', 'thim-our-team' ),
				3  => __( 'Custom field deleted.', 'thim-our-team' ),
				4  => __( 'Our Team updated.', 'thim-our-team' ),
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Our Team restored to revision from %s', 'thim-our-team' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Our Team published. <a href="%s">View Our Team</a>', 'thim-our-team' ), esc_url( get_permalink( $post_ID ) ) ),
				7  => __( 'Our Team saved.', 'thim-our-team' ),
				8  => sprintf( __( 'Our Team submitted. <a target="_blank" href="%s">Preview Our Team</a>', 'thim-our-team' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
				9  => sprintf( __( 'Our Team scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Our Team</a>', 'thim-our-team' ), date_i18n( __( 'M j, Y @ G:i', 'thim-our-team' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
				10 => sprintf( __( 'Our Team draft updated. <a target="_blank" href="%s">Preview Our Team</a>', 'thim-our-team' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			);

			return $messages;
		}

		/**
		 * Register Our_Team Metabox
		 *
		 * @return void
		 * @since  1.0
		 */
		public function our_team_register_metabox( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'     => 'our_team_settings',
				'title'  => 'Contact Information',
				'pages'  => array( 'our_team' ),
				'fields' => array(
					array(
						'name' => __( 'Regency', 'thim-our-team' ),
						'id'   => 'regency',
						'type' => 'textfield',
						'desc' => ''
					),

					array(
						'name' => __( 'Facebook URL', 'thim-our-team' ),
						'id'   => 'face_url',
						'type' => 'textfield',
						'desc' => ''
					),

					array(
						'name' => __( 'Twitter URL', 'thim-our-team' ),
						'id'   => 'twitter_url',
						'type' => 'textfield',
						'desc' => ''
					),

					array(
						'name' => __( 'Skype URL', 'thim-our-team' ),
						'id'   => 'skype_url',
						'type' => 'textfield',
						'desc' => ''
					),

					array(
						'name' => __( 'Dribbble URL', 'thim-our-team' ),
						'id'   => 'dribbble_url',
						'type' => 'textfield',
						'desc' => ''
					),
					array(
						'name' => __( 'Linked In URL', 'thim-our-team' ),
						'id'   => 'linkedin_url',
						'type' => 'textfield',
						'desc' => ''
					),
					array(
						'name' => __( 'Phone', 'thim-our-team' ),
						'id'   => 'our_team_phone',
						'type' => 'textfield',
						'desc' => ''
					),
					array(
						'name' => __( 'Email', 'thim-our-team' ),
						'id'   => 'our_team_email',
						'type' => 'textfield',
						'desc' => ''
					)
				)
			);

			return $meta_boxes;
		}
	}

	/**
	 * Main instance of plugin
	 *
	 * @return \THIM_Our_Team
	 * @since  1.0
	 * @author thimpress
	 */
	function THIM_Our_Team() {
		return THIM_Our_Team::instance();
	}

	/**
	 * Instantiate Our_Team class
	 *
	 * @since  1.0
	 * @author thimpress
	 */
	THIM_Our_Team();
}