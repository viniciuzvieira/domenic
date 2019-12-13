<?php

// load text domain
add_action( 'plugins_loaded', array( 'testimonials_metabox', 'text_domain' ) );
if ( !class_exists( 'testimonials_metabox' ) ) {
	class testimonials_metabox {
		/**
		 * @var array Meta box information
		 */
		public $meta_box;

		// Safe to start up
		public function __construct( $args ) {

			// Assign meta box values to local variables and add it's missed values
			$this->meta_box = $args;

			// Add meta boxes on the 'add_meta_boxes' hook.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			// Enqueue common styles and scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			// Save post meta
			add_action( 'save_post', array( $this, 'save_data' ) );
			// Load text domain
			// add_action( 'plugins_loaded', array( $this, 'text_domain' ) );
		}

		public static function text_domain() {
			// Get mo file
			$text_domain = 'thim-testimonials';
			$locale      = apply_filters( 'plugin_locale', get_locale(), $text_domain );
			$mo_file     = $text_domain . '-' . $locale . '.mo';
			// Check mo file global
			$mo_global = WP_LANG_DIR . '/plugins/' . $mo_file;
			// Load translate file
			if ( file_exists( $mo_global ) ) {
				load_textdomain( $text_domain, $mo_global );
			} else {
				load_textdomain( $text_domain, TESTIMONIALS_PLUGIN_PATH . '/languages/' . $mo_file );
			}
		}

		function admin_enqueue_scripts() {
			wp_enqueue_style( 'thim-meta-box', TESTIMONIALS_PLUGIN_URL . '/assets/css/meta-box.css', array(), "" );
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
					case 'textarea':
						$this->textarea( $field, $post->ID );
						break;
					case 'checkbox':
						$this->checkbox( $field, $post->ID );
						break;
					case 'select':
						$this->select( $field, $post->ID );
						break;
				}
			}
		}

		private function textarea( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';

			echo '<div class="thim-label"><label>' . $field['name'] . ': </label></div>';

			echo '<div class="thim-input">';
			echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '">' . $post_meta . '</textarea>';
			echo '<div class="desc">' . $field['desc'] . '</div>';
			echo '</div>';

			echo '</div>';
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

		private function checkbox( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';

			echo '<div class="thim-label"><label>' . $field['name'] . '</label></div>';
			?>

			<div class="thim-input"><input type="checkbox"
			                               name="<?php echo $field['id']; ?>" <?php checked( $post_meta, 'on' ); ?> />
				<div class="desc"><?php echo $field['desc']; ?></div>
			</div>
			</div>
			<?php
		}

		private function select( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';

			echo '<div class="thim-label"><label>' . $field['name'] . '</label></div>';

			echo '<div class="thim-input"><select name="' . $field['id'] . '" id="' . $field['id'] . '">';

			foreach ( $field['options'] as $key => $value ) {
				echo '<option ' . ( ( $key == $post_meta ) ? ' selected ' : '' ) . ' value="' . $key . '">' . $value . '</option>';
			}
			echo '</select><div class="desc">' . ( isset( $field['desc'] ) ? $field['desc'] : "" ) . '</div></div></div>';
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
if ( !class_exists( 'THIM_Testimonials' ) ) {
	/**
	 * Thim Theme
	 *
	 * Manage the testimonials in the THIM Framework
	 *
	 * @class      THIM_Testimonials
	 * @package    thimpress
	 * @since      1.0
	 * @author     kien16
	 */
	class THIM_Testimonials {

		/**
		 * @var string
		 * @since 1.0
		 */
		public $version = THIM_TESTIMONIALS_VERSION;

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
			return apply_filters( 'testimonials_template_path', 'thim-testimonials/' );
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
		 * Initialize plugin and registers the testimonials cpt
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
			// add_action( 'after_setup_theme', array( $this, 'register_taxonomy' ), 20 );


			// require_once 'lib/aq_resizer.php';

			// Display custom update messages for posts edits
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

			// Display custom update messages for posts edits
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

			// Include OWN Metabox
			add_action( 'admin_init', 'testimonials_register_meta_boxes' );
			function testimonials_register_meta_boxes() {
				$meta_boxes = apply_filters( 'testimonials_meta_boxes', array() );
				foreach ( $meta_boxes as $meta_box ) {
					new testimonials_metabox( $meta_box );
				}
			}

			add_filter( 'testimonials_meta_boxes', array( $this, 'testimonials_register_metabox' ), 20 );
			add_filter( 'template_include', array( $this, 'template_include' ), 20 );

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
			if ( get_post_type() == "testimonials" && ( is_category() || is_archive() ) ) {
				$template = $this->get_template_part( 'archive', "testimonials" );
			} else if ( get_post_type() == "testimonials" && is_single() ) {
				$template = $this->get_template_part( "single", "testimonials" );
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
			// Look in yourtheme/slug-name.php and yourtheme/testimonials/slug-name.php
			if ( $name ) {
				$template = locate_template( array( "{$slug}-{$name}.php", 'testimonials/' . "{$slug}-{$name}.php" ) );
			}
			// Get default slug-name.php
			if ( !$template && $name && file_exists( $this->plugin_path . "/templates/{$slug}-{$name}.php" ) ) {
				$template = $this->plugin_path . "/templates/{$slug}-{$name}.php";
			}
			// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/testimonials/slug.php
			if ( !$template ) {
				$template = locate_template( array( "{$slug}.php", 'testimonials/' . "{$slug}.php" ) );
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
				'name'               => _x( 'Testimonials', 'Post Type General Name', 'thim-testimonials' ),
				'singular_name'      => _x( 'Testimonial', 'Post Type Singular Name', 'thim-testimonials' ),
				'menu_name'          => __( 'Testimonials', 'thim-testimonials' ),
				'parent_item_colon'  => __( 'Parent Testimonial:', 'thim-testimonials' ),
				'all_items'          => __( 'All Testimonials', 'thim-testimonials' ),
				'view_item'          => __( 'View Testimonial', 'thim-testimonials' ),
				'add_new_item'       => __( 'Add New Testimonial', 'thim-testimonials' ),
				'add_new'            => __( 'New Testimonial', 'thim-testimonials' ),
				'edit_item'          => __( 'Edit Testimonial', 'thim-testimonials' ),
				'update_item'        => __( 'Update Testimonial', 'thim-testimonials' ),
				'search_items'       => __( 'Search Testimonials', 'thim-testimonials' ),
				'not_found'          => __( 'No Testimonials found', 'thim-testimonials' ),
				'not_found_in_trash' => __( 'No Testimonials found in Trash', 'thim-testimonials' ),
			);
			$args   = array(
				'labels'      => $labels,
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'menu_icon'   => 'dashicons-testimonial',
				'public'      => true,
				'rewrite'     => array( 'slug' => _x( 'testimonials', 'URL slug', 'thim-testimonials' ) ),
				'has_archive' => true,
			);
			register_post_type( 'testimonials', $args );
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
			$messages['testimonials'] = array(
				0  => '',
				1  => sprintf( __( 'Testimonials updated. <a href="%s">View Testimonials</a>', 'thim-testimonials' ), esc_url( get_permalink( $post_ID ) ) ),
				2  => __( 'Custom field updated.', 'thim-testimonials' ),
				3  => __( 'Custom field deleted.', 'thim-testimonials' ),
				4  => __( 'Testimonial updated.', 'thim-testimonials' ),
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Testimonial restored to revision from %s', 'thim-testimonials' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Testimonials published. <a href="%s">View Testimonials</a>', 'thim-testimonials' ), esc_url( get_permalink( $post_ID ) ) ),
				7  => __( 'Testimonial saved.', 'thim-testimonials' ),
				8  => sprintf( __( 'Testimonials submitted. <a target="_blank" href="%s">Preview Testimonials</a>', 'thim-testimonials' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
				9  => sprintf( __( 'Testimonials scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Testimonials</a>', 'thim-testimonials' ), date_i18n( __( 'M j, Y @ G:i', 'thim-testimonials' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
				10 => sprintf( __( 'Testimonials draft updated. <a target="_blank" href="%s">Preview Testimonials</a>', 'thim-testimonials' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			);

			return $messages;
		}

		/**
		 * Register Testimonials Metabox
		 *
		 * @return void
		 * @since  1.0
		 */
		public function testimonials_register_metabox( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'     => 'testimonials_settings',
				'title'  => 'Testimonials Settings',
				'pages'  => array( 'testimonials' ),
				'fields' => array(
					array(
						'name' => __( 'Regency', 'thim-testimonials' ),
						'id'   => 'regency',
						'type' => 'textfield',
						'desc' => ''
					),

					array(
						'name' => __( 'Website URL', 'thim-testimonials' ),
						'id'   => 'website_url',
						'type' => 'textfield',
						'desc' => ''
					),

				)
			);

			return $meta_boxes;
		}
	}

	/**
	 * Main instance of plugin
	 *
	 * @return \THIM_Testimonials
	 * @since  1.0
	 * @author thimpress
	 */
	function THIM_Testimonials() {
		return THIM_Testimonials::instance();
	}

	/**
	 * Instantiate Testimonials class
	 *
	 * @since  1.0
	 * @author thimpress
	 */
	THIM_Testimonials();
}