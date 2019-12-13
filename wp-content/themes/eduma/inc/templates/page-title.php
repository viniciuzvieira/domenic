<?php
global $wp_query, $post;
$GLOBALS['post'] = @$wp_query->post;
$wp_query->setup_postdata( @$wp_query->post );

$theme_options_data = get_theme_mods();

$custom_title = $subtitle = $style_heading = $thim_custom_heading = $front_title = $text_color = $sub_color = $bg_color = $cate_top_image_src = $bg_opacity = $top_overlay_style = '';

$hide_title = $hide_breadcrumbs = 0;

// color theme options
$cat_obj = $wp_query->get_queried_object();

if ( isset( $cat_obj->term_id ) ) {
	$cat_ID       = $cat_obj->term_id;
	$cat_taxonomy = $cat_obj->taxonomy;
} else {
	$cat_ID       = '';
	$cat_taxonomy = '';
}

//Get $prefix
$prefix = thim_get_prefix_page_title();

//Get $prefix_inner
$prefix_inner = thim_get_prefix_inner_page_title();

$style_content = ( isset($theme_options_data[$prefix . $prefix_inner . 'style_content']) ) ? $theme_options_data[$prefix . $prefix_inner . 'style_content'] : '';

//Background image default from customizer options
if ( ! empty( $theme_options_data[ $prefix . $prefix_inner . 'top_image' ] ) ) {
	$cate_top_image = $theme_options_data[ $prefix . $prefix_inner . 'top_image' ];
	if ( is_numeric( $cate_top_image ) ) {
		$cate_top_attachment = wp_get_attachment_image_src( $cate_top_image, 'full' );
		$cate_top_image_src  = $cate_top_attachment[0];
	} else {
		$cate_top_image_src = $cate_top_image;
	}
}

//Hide breadcrumbs default from customizer options
if ( ! empty( $theme_options_data[ $prefix . $prefix_inner . 'hide_breadcrumbs' ] ) ) {
	$hide_breadcrumbs = $theme_options_data[ $prefix . $prefix_inner . 'hide_breadcrumbs' ];
}

//Hide title default from customizer options
if ( ! empty( $theme_options_data[ $prefix . $prefix_inner . 'hide_title' ] ) ) {
	$hide_title = $theme_options_data[ $prefix . $prefix_inner . 'hide_title' ];
}

//Get subtitle default from customizer options
if ( ! empty( $theme_options_data[ $prefix . $prefix_inner . 'sub_title' ] ) ) {
	$subtitle = $theme_options_data[ $prefix . $prefix_inner . 'sub_title' ];
}

if ( ! empty( $theme_options_data[ $prefix . $prefix_inner . 'title_color' ] ) ) {
	$text_color = $theme_options_data[ $prefix . $prefix_inner . 'title_color' ];
}

if ( ! empty( $theme_options_data[ $prefix . $prefix_inner . 'sub_title_color' ] ) ) {
	$sub_color = $theme_options_data[ $prefix . $prefix_inner . 'sub_title_color' ];
}

if ( ! empty( $theme_options_data[ $prefix . $prefix_inner . 'bg_color' ] ) ) {
	$bg_color = $theme_options_data[ $prefix . $prefix_inner . 'bg_color' ];
}

if(get_post_type() == 'forum' || get_post_type() == 'topic') {
	if ( ! empty( $theme_options_data[ 'thim_forum_cate_top_image' ] ) ) {
		$cate_top_image = $theme_options_data[ 'thim_forum_cate_top_image' ];
		if ( is_numeric( $cate_top_image ) ) {
			$cate_top_attachment = wp_get_attachment_image_src( $cate_top_image, 'full' );
			$cate_top_image_src  = $cate_top_attachment[0];
		} else {
			$cate_top_image_src = $cate_top_image;
		}
	}
}

//Get by Tax-meta-class for categories & custom field for single
if ( is_page() || is_single() ) {
	if(get_post_type() == 'forum' || get_post_type() == 'topic') {
		$hide_title = $theme_options_data[ 'thim_forum_cate_hide_title' ];
		$hide_breadcrumbs = $theme_options_data[ 'thim_forum_cate_hide_breadcrumbs' ];
	}
	$post_id = get_the_ID();
	//Check using custom heading on single
	$using_custom_heading = get_post_meta($post_id, 'thim_mtb_using_custom_heading', true);

	if ($using_custom_heading) {

		$hide_title = get_post_meta($post_id, 'thim_mtb_hide_title_and_subtitle', true);
		$hide_breadcrumbs = get_post_meta($post_id, 'thim_mtb_hide_breadcrumbs', true);
		$custom_title = get_post_meta($post_id, 'thim_mtb_custom_title', true);
		$subtitle = get_post_meta($post_id, 'thim_subtitle', true);
		$bg_opacity = get_post_meta($post_id, 'thim_mtb_bg_opacity', true);

		$text_color_single = get_post_meta($post_id, 'thim_mtb_text_color', true);
		if (!empty($text_color_single) && $text_color_single != '#') {
			$text_color = $text_color_single;
		}
		$sub_color_single = get_post_meta($post_id, 'thim_mtb_color_sub_title', true);
		if (!empty($sub_color_single) && $sub_color_single != '#') {
			$sub_color = $sub_color_single;
		}
		$bg_color_single = get_post_meta($post_id, 'thim_mtb_bg_color', true);
		if (!empty($bg_color_single) && $bg_color_single != '#') {
			$bg_color = $bg_color_single;
		}
		$cate_top_image = get_post_meta($post_id, 'thim_mtb_top_image', true);
		if (is_numeric($cate_top_image)) {
			$post_page_top_attachment = wp_get_attachment_image_src($cate_top_image, 'full');
			$cate_top_image_src = $post_page_top_attachment[0];
		}
	}
} else if( is_404() ) {
	if ( ! empty( $theme_options_data[ 'thim_single_404_sub_title' ] ) ) {
		$subtitle = $theme_options_data[ 'thim_single_404_sub_title' ];
	}
	if ( ! empty( $theme_options_data[ 'thim_single_404_title_color' ] ) ) {
		$text_color = $theme_options_data[ 'thim_single_404_title_color' ];
	}
	if ( ! empty( $theme_options_data[ 'thim_single_404_sub_title_color' ] ) ) {
		$sub_color = $theme_options_data[ 'thim_single_404_sub_title_color' ];
	}
	if ( ! empty( $theme_options_data[ 'thim_single_404_bg_color' ] ) ) {
		$bg_color = $theme_options_data[ 'thim_single_404_bg_color' ];
	}
	if ( ! empty( $theme_options_data[ 'thim_single_404_top_image' ] ) ) {
		$cate_top_image_src = $theme_options_data[ 'thim_single_404_top_image' ];
	}
} else {
	$thim_custom_heading = get_term_meta( $cat_ID, 'thim_custom_heading', true );
	if ( $thim_custom_heading == 'custom' || $thim_custom_heading == 'on' ) {
		$text_color_cate = get_term_meta( $cat_ID, $prefix . '_cate_heading_text_color', true );
		$bg_color_cate   = get_term_meta( $cat_ID, $prefix . '_cate_heading_bg_color', true );
		$sub_color_cate  = get_term_meta( $cat_ID, $prefix . '_cate_sub_heading_bg_color', true );
		// reset default
		if ( ! empty( $text_color_cate ) && $text_color_cate != '#' ) {
			$text_color = $text_color_cate;
		}
		if ( ! empty( $bg_color_cate ) && $bg_color_cate != '#' ) {
			$bg_color = $bg_color_cate;
		}
		if ( ! empty( $sub_color_cate ) && $sub_color_cate != '#' ) {
			$sub_color = $sub_color_cate;
		}

		$subtitle = term_description( $cat_ID, $cat_taxonomy );

		$hide_breadcrumbs = get_term_meta( $cat_ID, $prefix . '_cate_hide_breadcrumbs', true );
		$hide_title       = get_term_meta( $cat_ID, $prefix . '_cate_hide_title', true );
		$cate_top_image   = get_term_meta( $cat_ID, $prefix . '_top_image', true );
		$bg_opacity       = get_term_meta( $cat_ID, $prefix . '_cate_heading_bg_opacity', true );

		if ( ! empty( $cate_top_image ) ) {
			$cate_top_image_src = $cate_top_image['url'];
		}
	}
}

//Check ssl for top image
$cate_top_image_src = thim_ssl_secure_url( $cate_top_image_src );

// css
$top_site_main_style = ( $text_color != '' ) ? 'color: ' . $text_color . ';' : '';
$top_site_main_style .= ( $cate_top_image_src != '' ) ? 'background-image:url(' . $cate_top_image_src . ');' : '';
$sub_title_style     = ( $sub_color != '' ) ? 'style="color:' . $sub_color . '"' : '';
if ( ! empty( $bg_color ) ) {
	$top_overlay_style = 'background:' . $bg_color . ';';
}
if ( ! empty( $bg_opacity ) ) {
	$top_overlay_style .= 'opacity:' . $bg_opacity . ';';
}

if ( is_front_page() || is_home() ) {

	if ( ! empty( $theme_options_data['thim_front_page_hide_breadcrumbs'] ) ) {
		$hide_breadcrumbs = '1';
	}
	if ( ! empty( $theme_options_data['thim_front_page_hide_title'] ) ) {
		$hide_title = '1';
	}
}

//set style heading title
if ( ! empty( $theme_options_data[ 'thim_layout_content_page' ] ) ) {
    //$style_heading = $theme_options_data[ 'thim_layout_content_page' ];
    if( $theme_options_data[ 'thim_layout_content_page' ] == 'new-1' ) {
        $top_overlay_style = '';
        $style_heading = 'style_heading_2';
        if( is_single() && get_post_type() == 'lp_course' )
            $style_content = 'style_content_2';
    }
}

?>
<div class="top_heading <?php echo $style_content;?> <?php echo $style_heading;?>_out">
    <div class="top_site_main <?php echo $style_heading;?>" style="<?php echo ent2ncr( $top_site_main_style ); ?>">
        <span class="overlay-top-header" style="<?php echo ent2ncr( $top_overlay_style ); ?>"></span>
        <?php if ( $hide_title != '1' ) : ?>
            <div class="page-title-wrapper">
                <div class="banner-wrapper container">
                    <?php

                    if ( is_single() ) {
                        $typography = 'h2';
                    } else {
                        $typography = 'h1';
                    }

                    if ( $style_content == 'style_content_2' && is_single() && get_post_type() == 'lp_course' ) $custom_title = get_the_title();
                    $heading_title = thim_get_page_title( $custom_title, $front_title );

                    echo '<' . $typography . '>' . $heading_title . '</' . $typography . '>';

                    if ( ! empty( $subtitle ) ) {
                        echo '<div class="banner-description" ' . $sub_title_style . '>' . $subtitle . '</div>';
                    }

                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php

    //Display breadcrumbs
    if ( $hide_breadcrumbs != '1' && ! is_front_page() && ! is_404() ) {
        thim_print_breadcrumbs();
    }
    ?>
</div>