<?php
/**
 * Class Thim Portfolio Custom Post Types.
 *
 * @author  ThimPress
 * @package Thim_Portfolio/Classes
 * @version 1.4
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'TP_PORTFOLIO_POST_TYPES' ) ) {
	/**
	 * Class TP_PORTFOLIO_POST_TYPES.
	 */
	class TP_PORTFOLIO_POST_TYPES {

		/**
		 * TP_PORTFOLIO_POST_TYPES constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'after_setup_theme', array( $this, 'register_taxonomies' ) );

			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

			add_filter( 'thim_meta_boxes', array( $this, 'register_metabox' ), 20 );

			add_action( 'template_include', array( $this, 'template_include' ), 20 );
		}

		/**
		 * Register portfolio post type.
		 */
		public function register_post_types() {
			$labels = array(
				'name'               => _x( 'Projects', 'General Name', 'tp-portfolio' ),
				'singular_name'      => _x( 'Project', 'Singular Name', 'tp-portfolio' ),
				'menu_name'          => esc_html__( 'Portfolio', 'tp-portfolio' ),
				'parent_item_colon'  => esc_html__( 'Parent Portfolio:', 'tp-portfolio' ),
				'all_items'          => esc_html__( 'All Projects', 'tp-portfolio' ),
				'view_item'          => esc_html__( 'View Project', 'tp-portfolio' ),
				'add_new_item'       => esc_html__( 'Add New Project', 'tp-portfolio' ),
				'add_new'            => esc_html__( 'New Project', 'tp-portfolio' ),
				'edit_item'          => esc_html__( 'Edit Project', 'tp-portfolio' ),
				'update_item'        => esc_html__( 'Update Portfolio', 'tp-portfolio' ),
				'search_items'       => esc_html__( 'Search Projects', 'tp-portfolio' ),
				'not_found'          => esc_html__( 'No Projects found', 'tp-portfolio' ),
				'not_found_in_trash' => esc_html__( 'No Projects found in Trash', 'tp-portfolio' ),
			);
			$args   = array(
				'labels'      => $labels,
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'menu_icon'   => 'dashicons-portfolio',
				'public'      => true,
				'has_archive' => true,
				'rewrite'     => array( 'slug' => _x( 'portfolio', 'URL slug', 'tp-portfolio' ) )
			);
			register_post_type( 'portfolio', $args );
		}

		/**
		 * Register portfolio type taxonomy.
		 */
		public function register_taxonomies() {
			// Portfolio Categories
			$labels = array(
				'name'                       => _x( 'Project Types', 'Taxonomy General Name', 'tp-portfolio' ),
				'singular_name'              => _x( 'Project Type', 'Taxonomy Singular Name', 'tp-portfolio' ),
				'menu_name'                  => esc_html__( 'Project Types', 'tp-portfolio' ),
				'all_items'                  => esc_html__( 'All Project Types', 'tp-portfolio' ),
				'parent_item'                => esc_html__( 'Parent Project Type', 'tp-portfolio' ),
				'parent_item_colon'          => esc_html__( 'Parent Project Type:', 'tp-portfolio' ),
				'new_item_name'              => esc_html__( 'New Project Type Name', 'tp-portfolio' ),
				'add_new_item'               => esc_html__( 'Add New Project Type', 'tp-portfolio' ),
				'edit_item'                  => esc_html__( 'Edit Project Type', 'tp-portfolio' ),
				'update_item'                => esc_html__( 'Update Project Type', 'tp-portfolio' ),
				'separate_items_with_commas' => esc_html__( 'Separate Project Types with commas', 'tp-portfolio' ),
				'search_items'               => esc_html__( 'Search Project Types', 'tp-portfolio' ),
				'add_or_remove_items'        => esc_html__( 'Add or remove Project Types', 'tp-portfolio' ),
				'choose_from_most_used'      => esc_html__( 'Choose from the most used Project Types', 'tp-portfolio' ),
			);
			$args   = array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'menu_icon'         => 'dashicons-portfolio',
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'portfolio_category' ),
			);
			register_taxonomy( 'portfolio_category', 'portfolio', $args );
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
			$messages['portfolio'] = array(
				0  => '',
				1  => sprintf( __( 'Portfolio updated. <a href="%s">View Portfolio</a>', 'tp-portfolio' ), esc_url( get_permalink( $post_ID ) ) ),
				2  => __( 'Custom field updated.', 'tp-portfolio' ),
				3  => __( 'Custom field deleted.', 'tp-portfolio' ),
				4  => __( 'Portfolio updated.', 'tp-portfolio' ),
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Portfolio restored to revision from %s', 'tp-portfolio' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Portfolio published. <a href="%s">View Portfolio</a>', 'tp-portfolio' ), esc_url( get_permalink( $post_ID ) ) ),
				7  => __( 'Portfolio saved.', 'tp-portfolio' ),
				8  => sprintf( __( 'Portfolio submitted. <a target="_blank" href="%s">Preview Portfolio</a>', 'tp-portfolio' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
				9  => sprintf( __( 'Portfolio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Portfolio</a>', 'tp-portfolio' ), date_i18n( __( 'M j, Y @ G:i', 'tp-portfolio' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
				10 => sprintf( __( 'Portfolio draft updated. <a target="_blank" href="%s">Preview Portfolio</a>', 'tp-portfolio' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			);

			return $messages;
		}

		/**
		 * Register Portfolio Metabox
		 *
		 * @param $meta_boxes
		 *
		 * @return array
		 */
		public function register_metabox( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'     => 'portfolio_settings',
				'title'  => esc_html__( 'Portfolio Settings', 'tp-portfolio' ),
				'pages'  => array( 'portfolio' ),
				'fields' => array(
					array(
						'name'    => esc_html__( 'Multigrid Size', 'tp-portfolio' ),
						'id'      => 'feature_images',
						'type'    => 'select',
						'desc'    => esc_html__( 'This config will working for portfolio layout style.', 'tp-portfolio' ),
						'std'     => 'Random',
						'options' => array(
							'random' => "Random",
							'size11' => "Size 1x1(480 x 320)",
							'size12' => "Size 1x2(480 x 640)",
							'size21' => "Size 2x1(960 x 320)",
							'size22' => "Size 2x2(960 x 640)"
						),
					),
					array(
						'name'     => esc_html__( 'Portfolio Type', 'tp-portfolio' ),
						'id'       => "selectPortfolio",
						'type'     => 'select',
						'options'  => array(
							'portfolio_type_image'                  => __( 'Image', 'tp-portfolio' ),
							'portfolio_type_slider'                 => __( 'Slider', 'tp-portfolio' ),
							'portfolio_type_video'                  => __( 'Video', 'tp-portfolio' ),
							'portfolio_type_left_floating_sidebar'  => __( 'Left Floating Sidebar', 'tp-portfolio' ),
							'portfolio_type_right_floating_sidebar' => __( 'Right Floating Sidebar', 'tp-portfolio' ),
							'portfolio_type_gallery'                => __( 'Gallery', 'tp-portfolio' ),
							'portfolio_type_sidebar_slider'         => __( 'Sidebar Slider', 'tp-portfolio' ),
							'portfolio_type_vertical_stacked'       => __( 'Vertical Stacked', 'tp-portfolio' ),
							'portfolio_type_page_builder'           => __( 'Page Builder', 'tp-portfolio' ),

						),
						// Select multiple values, optional. Default is false.
						'multiple' => false,
						'std'      => 'portfolio_type_image',
					),

					array(
						'name'     => esc_html__( 'Video', 'tp-portfolio' ),
						'id'       => 'project_video_type',
						'type'     => 'select',
						'class'    => 'portfolio_type_video',
						'options'  => array(
							'youtube' => 'Youtube',
							'vimeo'   => 'Vimeo',
						),
						'multiple' => false,
						'std'      => array( 'no' )
					),
					array(
						'name'  => esc_html__( "Video URL or own Embedd Code<br />(Audio Embedd Code is possible, too)", 'tp-portfolio' ),
						'id'    => 'project_video_embed',
						'desc'  => wp_kses( __( "Just paste the ID of the video (E.g. http://www.youtube.com/watch?v=<strong>GUEZCxBcM78</strong>) you want to show, or insert own Embed Code. <br />This will show the Video <strong>INSTEAD</strong> of the Image Slider.<br /><strong>Of course you can also insert your Audio Embedd Code!</strong><br /><br /><strong>Notice:</strong> The Preview Image will be the Image set as Featured Image..", 'tp-portfolio' ), array(
							'br'     => array(),
							'strong' => array()
						) ),
						'type'  => 'textarea',
						'class' => 'portfolio_type_video',
						'std'   => "",
						'cols'  => "40",
						'rows'  => "8"
					),

					array(
						'name'             => esc_html__( 'Upload Image', 'tp-portfolio' ),
						'desc'             => __( 'Upload up images for a slideshow - or only one to display a single image. <br /><br /><strong>Notice:</strong> The Preview Image will be the Image set as Featured Image.', 'tp-portfolio' ),
						'id'               => 'project_item_slides',
						'type'             => 'image',
						'max_file_uploads' => 1,
						'class'            => 'portfolio_type_image portfolio_type_gallery portfolio_type_vertical_stacked',
					),

					array(
						'name'             => esc_html__( 'Upload Image', 'tp-portfolio' ),
						'desc'             => __( 'Upload up images for a slideshow - or only one to display a single image. <br /><br /><strong>Notice:</strong> The Preview Image will be the Image set as Featured Image.', 'tp-portfolio' ),
						'id'               => 'portfolio_sliders',
						'type'             => 'image_video',
						'class'            => 'portfolio_type_sidebar_slider portfolio_type_slider portfolio_type_left_floating_sidebar portfolio_type_right_floating_sidebar',
						'max_file_uploads' => 20,
					),
				)
			);

			return $meta_boxes;
		}

		/**
		 * Template part Redirect.
		 *
		 * @param $template
		 *
		 * @return mixed|string
		 */
		public function template_include( $template ) {
			if ( get_post_type() == 'portfolio' && ( is_category() || is_archive() ) ) {
				$template = tp_portfolio_get_template_part( 'archive', 'portfolio' );
			} else if ( get_post_type() == 'portfolio' && is_single() ) {
				$template = tp_portfolio_get_template_part( "single", 'portfolio' );
			}

			return $template;
		}
	}
}

new TP_PORTFOLIO_POST_TYPES();