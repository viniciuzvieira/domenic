<?php

$wpappninja_popup = "";

/** COMMON **/
if ( ! isset( $content_width ) ) $content_width = 600;

/** CACHE MANAGEMENT **/
/*add_action('init', 'wpappninja_check_cache');
function wpappninja_check_cache() {

  if (isset($_COOKIE['wpappninja_cache'])) {
    $user_cache = $_COOKIE['wpappninja_cache'];
  } else {
    $user_cache = 1;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_cache = $user_cache + 1;
    $_COOKIE['wpappninja_cache'] = $user_cache;
  }

  setcookie( "wpappninja_cache", $user_cache, time() + 864000, COOKIEPATH, COOKIE_DOMAIN );
}*/

/** BODY CLASS **/
add_filter('body_class', 'wpappninja_theme_body_class');
function wpappninja_theme_body_class($classes) {
	$classes[] = 'theme-wpappninja';
	$classes[] = 'wpmobileapp';

	if (wpappninja_isIOS()) {
		$classes[] = 'wpapp-ios';
	}

	return $classes;
}

/** CUSTOM CSS **/
add_filter('wp_head', 'wpappninja_css_colors');
function wpappninja_css_colors() {
	$css = "";
	if (isset($_GET['shortcode_preview'])) {
		$css .= '<link href="'.get_template_directory_uri().'/font/BLOKKNeue-Regular/blokkfont.css" rel="stylesheet" type="text/css">';
	}
	$css .= '<style type="text/css">';

	if (isset($_GET['shortcode_preview'])) {
		$css .= '* {font-family: "BLOKK";}';

		if ($_GET['shortcode_preview'] != "withui") {
		$css.= '.page-content.ptr-content {
    padding: 0!important;
}body, .view, .page, .page-content.ptr-content {background:white;
    height: auto;
    min-height: 100%;
    position: relative;
}.navbar, .toolbar, .ptr-preloader {display: none;}';
	}
}
	$classes = wpappninja_get_css_rules();
	foreach($classes as $class) {
		$css .= $class['class'] . ' { ' . $class['zone'] . ':' . get_wpappninja_option('css_' . md5($class['class'] . $class['zone']), $class['color']) . '}';
	}

	echo $css;
	$css = "";
	if (get_option('wpappninja_042018') == true) { ?>
.woocommerce .main-post input#place_order {
    background: white;
    border: 2px solid;
  }
body.wpappninja.woocommerce .main-post ul.products li {
    width: 100%!important;
    box-shadow: 0 0 5px #ccc;
    border: 1px solid #eee;
    padding: 20px!important;
    box-sizing: border-box;
    margin: 0 0 15px 0!important;
}

.woocommerce .main-post ul.products li h2 {
    font-size: 25px!important;
}

.woocommerce .main-post ul.products li .price {
    position: absolute;
    top: 5%;
    right: 5%;
    background: #77a464;
    color: white!important;
    font-size: 18px!important;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 2px green;
}

.woocommerce .main-post ul.products li a.button {
    width: 100%;
    font-size: 20px;
    height: auto;
    padding: 25px;
    background: #f8f8f8!important;
}

body.wpappninja .product .summary.entry-summary {
    width: 100%!important;
}

.woocommerce .main-post input.button, .woocommerce .main-post a.button,.woocommerce .main-post button.button {
    width: 100%!important;
    height: auto!important;
    padding: 16px!important;
    margin: 0!important;
    line-height: initial!important;
}

.woocommerce .main-post input#coupon_code {
    width: 100%;
    font-size: 18px;
    overflow:auto;
}

.woocommerce .main-post .main-post p.form-row {
    width: 100%;
    margin: 0;
    float: none;
    margin: 0 0 15px;
    padding: 0 0 20px;
}

body.woocommerce-page .main-post p.form-row input, body.woocommerce-page .main-post p.form-row select {
    font-size: 18px;
    padding: 9px;
    border: 1px solid #EEE;
}

	<?php
	}

	$css .= '.panel .wpappninja_make_it_colorfull li{background:'.wpappninja_adjustBrightness(get_wpappninja_option('css_98cbd51ad8789c03f7dd7d6cd3cd9e08', '#f5f5f5'), -20).'}';

	$css .= '.toolbar-inner .wpappninja_make_it_colorfull i {color:'.get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028').'}';
	$css .= '.toolbar-inner .wpappninja_make_it_colorfull span {color:'.get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028').';text-shadow:0 0 #fff}';

	$css .= '.wpappninja_make_it_colorfull i.wpapp_icon_nofill{display:none}';
	$css .= '.wpappninja_make_it_colorfull i.wpapp_icon_fill{display:block}';

	$css .= 'i.wpapp_icon_nofill{display:block}';
	$css .= 'i.wpapp_icon_fill{display:none}';

	//$css .= '.title-speed,.post{display:none;}';

	$css .= '</style>';



	echo $css;

	wpappninja_stats_log("read", 1);
}

/** ENQUEUE **/
add_filter('wp_enqueue_scripts','wpmobile_add_jquery', 1);
function wpmobile_add_jquery() {
	wp_enqueue_script('jquery', false, array(), false, false);
}

add_action('wp_enqueue_scripts', 'wpappninja_theme_styles');
function wpappninja_theme_styles() { 

	wp_register_style( 'wpappninja-theme', get_template_directory_uri() . '/style.css', array(), WPAPPNINJA_VERSION, null );
	wp_enqueue_style( 'wpappninja-theme' );    

	wp_register_style( 'framework7-both', get_template_directory_uri() . '/css/framework7.css', array(), WPAPPNINJA_VERSION, null );
	wp_enqueue_style( 'framework7-both' );

	wp_register_script( 'wpifs', get_template_directory_uri() . '/js/wpifs.js', array( 'jquery' ), WPAPPNINJA_VERSION );
	wp_localize_script(
		'wpifs', 'wpifs_options',
		array(
			'container'  => '.posts',
			'post'       => '.post',
			'pagination' => '.pagination',
			'next'       => 'a.next',
			'loading'    => '<center><div class="preloader"><span class="preloader-inner"><span class="preloader-inner-gap"></span><span class="preloader-inner-left"><span class="preloader-inner-half-circle"></span></span><span class="preloader-inner-right"><span class="preloader-inner-half-circle"></span></span></span></div></center><br/>'
		)
	);

	wp_enqueue_script( 'wpifs' );

    wp_dequeue_style( 'select2' );
    wp_dequeue_script( 'select2');
    wp_dequeue_script( 'selectWoo' );
}

/** WOOCOMMERCE **/
add_filter('woocommerce_get_catalog_ordering_args', 'wpappninja_woocommerce_catalog_orderby');
function wpappninja_woocommerce_catalog_orderby( $args ) {
    $args['orderby'] = get_wpappninja_option('orderby_list', 'post_date');
    $args['order'] = get_wpappninja_option('order_list', 'desc'); 
    return $args;
}
