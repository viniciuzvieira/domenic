<?php

/**
 * Animation
 *
 * @param $css_animation
 *
 * @return string
 */
if ( ! function_exists( 'thim_getCSSAnimation' ) ) {
	function thim_getCSSAnimation( $css_animation ) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script( 'thim-waypoints' );
			$output = ' wpb_animate_when_almost_visible wpb_' . $css_animation;
		}

		return $output;
	}
}

/**
 * Custom excerpt
 *
 * @param $limit
 *
 * @return array|mixed|string|void
 */
function thim_excerpt( $limit ) {
	$excerpt = explode( ' ', get_the_excerpt(), $limit );
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( " ", $excerpt ) . '...';
	} else {
		$excerpt = implode( " ", $excerpt );
	}
	$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

	return '<p>' . wp_strip_all_tags( $excerpt ) . '</p>';
}

/**
 * Display breadcrumbs
 */
if ( ! function_exists( 'thim_breadcrumbs' ) ) {
	function thim_breadcrumbs() {

		// Do not display on the homepage
		if ( is_front_page() || is_404() ) {
			return;
		}

		// Get the query & post information
		global $post;
		$categories = get_the_category();

		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';


		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_html( get_home_url() ) . '" title="' . esc_attr__( 'Home', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'eduma' ) . '</span></a></li>';

		if ( is_home() ) {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html__( 'Blog', 'eduma' ) . '</span></li>';
		}

		if ( is_single() ) {


			if ( get_post_type() == 'tp_event' ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'tp_event' ) ) . '" title="' . esc_attr__( 'Events', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Events', 'eduma' ) . '</span></a></li>';
			}

			if ( get_post_type() == 'our_team' ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'our_team' ) ) . '" title="' . esc_attr__( 'Our Team', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Our Team', 'eduma' ) . '</span></a></li>';
			}

			if ( get_post_type() == 'post' ) {
				if ( $blog_id = get_option( 'page_for_posts' ) ) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $blog_id ) ) . '" title="' . esc_attr__( 'Blog', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Blog', 'eduma' ) . '</span></a></li>';

				}
			}

			// Single post (Only display the first category)
			if ( isset( $categories[0] ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" title="' . esc_attr( $categories[0]->cat_name ) . '"><span itemprop="name">' . esc_html( $categories[0]->cat_name ) . '</span></a></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';

		} else if ( is_category() ) {

			$current_category = get_queried_object();
			// Category page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html( $current_category->name ) . '</span></li>';

		} else if ( is_page() ) {

			// Standard page
			if ( $post->post_parent ) {

				// If child page, get parents
				$anc = get_post_ancestors( $post->ID );

				// Get parents in the right order
				$anc = array_reverse( $anc );

				// Parent page loop
				foreach ( $anc as $ancestor ) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $ancestor ) ) . '" title="' . esc_attr( get_the_title( $ancestor ) ) . '"><span itemprop="name">' . esc_html( get_the_title( $ancestor ) ) . '</span></a></li>';
				}
			}

			// Current page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '"> ' . esc_html( get_the_title() ) . '</span></li>';


		} else if ( is_tag() ) {

			// Display the tag name
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( single_term_title( '', false ) ) . '">' . esc_html( single_term_title( '', false ) ) . '</span></li>';

		} elseif ( is_day() ) {

			// Day archive

			// Year link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" title="' . esc_attr( get_the_time( 'Y' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></a></li>';

			// Month link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '" title="' . esc_attr( get_the_time( 'M' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></a></li>';

			// Day display
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'jS' ) ) . '"> ' . esc_html( get_the_time( 'jS' ) ) . ' ' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></li>';

		} else if ( is_month() ) {

			// Month Archive

			// Year link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" title="' . esc_attr( get_the_time( 'Y' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></a></li>';

			// Month display
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'M' ) ) . '">' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></li>';

		} else if ( is_year() ) {

			// Display year archive
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'Y' ) ) . '">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></li>';

		} else if ( is_author() ) {

			// Auhor archive

			// Get the author information
			global $author;
			$userdata = get_userdata( $author );

			// Display author name
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( $userdata->display_name ) . '">' . esc_attr__( 'Author', 'eduma' ) . ' ' . esc_html( $userdata->display_name ) . '</span></li>';

		} else if ( get_query_var( 'paged' ) ) {

			// Paginated archives
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Page', 'eduma' ) . ' ' . get_query_var( 'paged' ) . '">' . esc_html__( 'Page', 'eduma' ) . ' ' . esc_html( get_query_var( 'paged' ) ) . '</span></li>';

		} else if ( is_search() ) {

			// Search results page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Search results for:', 'eduma' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'eduma' ) . ' ' . esc_html( get_search_query() ) . '</span></li>';

		} elseif ( is_404() ) {
			// 404 page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( '404 Page', 'eduma' ) . '">' . esc_html__( '404 Page', 'eduma' ) . '</span></li>';
		} elseif ( is_archive() ) {
			if ( get_post_type() == "tp_event" ) {
				if ( get_query_var( 'taxonomy' ) ) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html( get_queried_object()->name ) . '</span></li>';
				} else {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Events', 'eduma' ) . '">' . esc_html__( 'Events', 'eduma' ) . '</span></li>';
				}

			}
			if ( get_post_type() == "testimonials" ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Testimonials', 'eduma' ) . '">' . esc_html__( 'Testimonials', 'eduma' ) . '</span></li>';
			}
			if ( get_post_type() == "our_team" ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Our Team', 'eduma' ) . '">' . esc_html__( 'Our Team', 'eduma' ) . '</span></li>';
			}
		}

		echo '</ul>';
	}
}

/**
 * Get related posts
 *
 * @param     $post_id
 * @param int $number_posts
 *
 * @return WP_Query
 */
function thim_get_related_posts( $post_id, $number_posts = - 1 ) {
	$query = new WP_Query();
	$args  = '';
	if ( $number_posts == 0 ) {
		return $query;
	}
	$args  = wp_parse_args( $args, array(
		'posts_per_page'      => $number_posts,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => 0,
		'category__in'        => wp_get_post_categories( $post_id )
	) );
	$query = new WP_Query( $args );

	return $query;
}

/**
 * Check is on page of bbpress
 * @return bool
 */
function thim_use_bbpress() {
	if ( function_exists( 'is_bbpress' ) ) {
		return is_bbpress();
	} else {
		return false;
	}
}

/************ List Comment ***************/
if ( ! function_exists( 'thim_comment' ) ) {
	function thim_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		//extract( $args, EXTR_SKIP );
		if ( 'div' == $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo ent2ncr( $tag . ' ' ) ?><?php comment_class( 'description_comment' ) ?>>
		<div class="wrapper-comment">
			<?php
			if ( $args['avatar_size'] != 0 ) {
				echo '<div class="avatar">';
				echo get_avatar( $comment, $args['avatar_size'] );
				echo '</div>';
			}
			?>
			<div class="comment-right">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'eduma' ) ?></em>
				<?php endif; ?>

				<div class="comment-extra-info">
					<div
						class="author"><span class="author-name"><?php echo get_comment_author_link(); ?></span>
					</div>
					<div class="date" itemprop="commentTime">
						<?php printf( get_comment_date(), get_comment_time() ) ?></div>
					<?php edit_comment_link( esc_html__( 'Edit', 'eduma' ), '', '' ); ?>
					<?php comment_reply_link( array_merge( $args, array(
						'add_below' => $add_below,
						'depth'     => $depth,
						'max_depth' => $args['max_depth']
					) ) )
					?>
				</div>

				<div class="content-comment">
					<?php comment_text() ?>
				</div>

				<div class="comment-meta" id="div-comment-<?php comment_ID() ?>">

				</div>
			</div>
		</div>
		<?php
	}
}

// dislay setting layout
require THIM_DIR . 'inc/wrapper-before-after.php';

/**
 * @param $mtb_setting
 *
 * @return mixed
 */
function thim_mtb_setting_after_created( $mtb_setting ) {
	$mtb_setting->removeOption( array( 11 ) );
	$option_name_space = $mtb_setting->owner->optionNamespace;

	$settings   = array(
		'name'      => esc_html__( 'Color Sub Title', 'eduma' ),
		'id'        => 'mtb_color_sub_title',
		'type'      => 'color-opacity',
		'desc'      => ' ',
		'row_class' => 'child_of_' . $option_name_space . '_mtb_using_custom_heading thim_sub_option',
	);
	$settings_1 = array(
		'name' => esc_html__( 'No Padding Content', 'eduma' ),
		'id'   => 'mtb_no_padding',
		'type' => 'checkbox',
		'desc' => ' ',
	);

	$mtb_setting->insertOptionBefore( $settings, 11 );
	$mtb_setting->insertOptionBefore( $settings_1, 16 );

	return $mtb_setting;
}

add_filter( 'thim_mtb_setting_after_created', 'thim_mtb_setting_after_created', 10, 2 );


/**
 * @param $tabs
 *
 * @return array
 */
function thim_widget_group( $tabs ) {
	$tabs[] = array(
		'title'  => esc_html__( 'Thim Widget', 'eduma' ),
		'filter' => array(
			'groups' => array( 'thim_widget_group' )
		)
	);

	return $tabs;
}

add_filter( 'siteorigin_panels_widget_dialog_tabs', 'thim_widget_group', 19 );

/**
 * @param $attributes
 * @param $args
 *
 * @return mixed
 */
function thim_row_style_attributes( $attributes, $args ) {
	if ( ! empty( $args['parallax'] ) ) {
		array_push( $attributes['class'], 'article__parallax' );
	}

	if ( ! empty( $args['row_stretch'] ) && $args['row_stretch'] == 'full-stretched' ) {
		array_push( $attributes['class'], 'thim-fix-stretched' );
	}

	return $attributes;
}

add_filter( 'siteorigin_panels_row_style_attributes', 'thim_row_style_attributes', 10, 2 );

/**
 * @return string
 */
function thim_excerpt_length() {
	$theme_options_data = get_theme_mods();
	if ( isset( $theme_options_data['thim_archive_excerpt_length'] ) ) {
		$length = $theme_options_data['thim_archive_excerpt_length'];
	} else {
		$length = '50';
	}

	return $length;
}

add_filter( 'excerpt_length', 'thim_excerpt_length', 999 );

if ( ! function_exists( 'thim_excerpt_more' ) ) {
	function thim_excerpt_more( $link ) {
		return ' &hellip; ';
	}
}
add_filter( 'excerpt_more', 'thim_excerpt_more' );

/**
 * Social sharing
 */
if ( ! function_exists( 'thim_social_share' ) ) {
	function thim_social_share() {
		$theme_options_data = get_theme_mods();

		$facebook  = isset( $theme_options_data['group_sharing'] ) && in_array( 'facebook', $theme_options_data['group_sharing'] ) ? true : false;
		$twitter   = isset( $theme_options_data['group_sharing'] ) && in_array( 'twitter', $theme_options_data['group_sharing'] ) ? true : false;
		$pinterest = isset( $theme_options_data['group_sharing'] ) && in_array( 'pinterest', $theme_options_data['group_sharing'] ) ? true : false;
		$google    = isset( $theme_options_data['group_sharing'] ) && in_array( 'google', $theme_options_data['group_sharing'] ) ? true : false;
		$linkedin  = isset( $theme_options_data['group_sharing'] ) && in_array( 'linkedin', $theme_options_data['group_sharing'] ) ? true : false;

		if ( $facebook || $twitter || $pinterest || $google || $linkedin ) {
			echo '<ul class="thim-social-share">';
			do_action( 'thim_before_social_list' );
			echo '<li class="heading">' . esc_html__( 'Share:', 'eduma' ) . '</li>';
			if ( $facebook ) {

				echo '<li><div class="facebook-social"><a target="_blank" class="facebook"  href="https://www.facebook.com/sharer.php?u=' . urlencode( get_permalink() ) . '" title="' . esc_attr__( 'Facebook', 'eduma' ) . '"><i class="fa fa-facebook"></i></a></div></li>';

			}
			if ( $google ) {
				echo '<li><div class="googleplus-social"><a target="_blank" class="googleplus" href="https://plus.google.com/share?url=' . urlencode( get_permalink() ) . '&amp;title=' . rawurlencode( esc_attr( get_the_title() ) ) . '" title="' . esc_attr__( 'Google Plus', 'eduma' ) . '" onclick=\'javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;\'><i class="fa fa-google"></i></a></div></li>';

			}
			if ( $twitter ) {
				echo '<li><div class="twitter-social"><a target="_blank" class="twitter" href="https://twitter.com/share?url=' . urlencode( get_permalink() ) . '&amp;text=' . rawurlencode( esc_attr( get_the_title() ) ) . '" title="' . esc_attr__( 'Twitter', 'eduma' ) . '"><i class="fa fa-twitter"></i></a></div></li>';

			}

			if ( $pinterest ) {
				echo '<li><div class="pinterest-social"><a target="_blank" class="pinterest"  href="http://pinterest.com/pin/create/button/?url=' . urlencode( get_permalink() ) . '&amp;description=' . rawurlencode( esc_attr( get_the_excerpt() ) ) . '&amp;media=' . urlencode( wp_get_attachment_url( get_post_thumbnail_id() ) ) . '" onclick="window.open(this.href); return false;" title="' . esc_attr__( 'Pinterest', 'eduma' ) . '"><i class="fa fa-pinterest-p"></i></a></div></li>';

			}

			if ( $linkedin ) {
				echo '<li><div class="linkedin-social"><a target="_blank" class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_permalink() ) . '&title=' . rawurlencode( esc_attr( get_the_title() ) ) . '&summary=&source=' . rawurlencode( esc_attr( get_the_excerpt() ) ) . '"><i class="fa fa-linkedin-square"></i></a></div></li>';

			}
			do_action( 'thim_after_social_list' );

			echo '</ul>';
		}

	}
}
add_action( 'thim_social_share', 'thim_social_share' );


/**
 * Display favicon
 */
function thim_favicon() {
	if ( function_exists( 'wp_site_icon' ) ) {
		if ( function_exists( 'has_site_icon' ) ) {
			if ( ! has_site_icon() ) {
				// Icon default
				$thim_favicon_src = get_template_directory_uri() . "/images/favicon.png";
				echo '<link rel="shortcut icon" href="' . esc_url( $thim_favicon_src ) . '" type="image/x-icon" />';

				return;
			}

			return;
		}
	}

	/**
	 * Support WordPress < 4.3
	 */
	$theme_options_data = get_theme_mods();
	$thim_favicon_src   = '';
	if ( isset( $theme_options_data['thim_favicon'] ) ) {
		$thim_favicon       = $theme_options_data['thim_favicon'];
		$favicon_attachment = wp_get_attachment_image_src( $thim_favicon, 'full' );
		$thim_favicon_src   = $favicon_attachment[0];
	}
	if ( ! $thim_favicon_src ) {
		$thim_favicon_src = get_template_directory_uri() . "/images/favicon.png";
	}
	echo '<link rel="shortcut icon" href="' . esc_url( $thim_favicon_src ) . '" type="image/x-icon" />';
}

add_action( 'wp_head', 'thim_favicon' );

if ( ! function_exists( 'thim_multisite_signup_redirect' ) ) {
	function thim_multisite_signup_redirect() {
		if ( is_multisite() ) {
			wp_redirect( wp_registration_url() );
			die();
		}
	}
}
add_action( 'signup_header', 'thim_multisite_signup_redirect' );


/**
 * Display feature image
 *
 * @param $attachment_id
 * @param $size_type
 * @param $width
 * @param $height
 * @param $alt
 * @param $title
 *
 * @return string
 */
if ( ! function_exists( 'thim_get_feature_image' ) ) {
	function thim_get_feature_image( $attachment_id, $size_type = null, $width = null, $height = null, $alt = null, $title = null ) {

		if ( ! $size_type ) {
			$size_type = 'full';
		}
		$src   = wp_get_attachment_image_src( $attachment_id, $size_type );
		$style = '';
		if ( ! $src ) {
			// Get demo image
			global $wpdb;
			$attachment_id = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
				WHERE 	pm.meta_key = %s
				AND 	pm.meta_value LIKE %s",
					'_wp_attached_file',
					'%demo_image.jpg'
				)
			);

			if ( empty( $attachment_id[0] ) ) {
				return;
			}

			$attachment_id = $attachment_id[0];
			$src           = wp_get_attachment_image_src( $attachment_id, 'full' );

		}

		if ( $width && $height ) {

			if ( $src[1] >= $width || $src[2] >= $height ) {

				$crop = ( $src[1] >= $width && $src[2] >= $height ) ? true : false;

				if ( $new_link = aq_resize( $src[0], $width, $height, $crop ) ) {

					$src[0] = $new_link;

				}

			}
			$style = ' width="' . $width . '" height="' . $height . '"';
		} else {
			if ( ! empty( $src[1] ) && ! empty( $src[2] ) ) {
				$style = ' width="' . $src[1] . '" height="' . $src[2] . '"';
			}
		}

		if ( ! $alt ) {
			$alt = get_the_title( $attachment_id );
		}

		if ( ! $title ) {
			$title = get_the_title( $attachment_id );
		}

		return '<img src="' . esc_url( $src[0] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" ' . $style . '>';

	}
}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
if ( ! function_exists( 'thim_event_add_meta_boxes' ) ) {
	function thim_event_add_meta_boxes() {

		if ( ! post_type_exists( 'tp_event' ) || ! post_type_exists( 'our_team' ) ) {
			return;
		}
		add_meta_box(
			'thim_organizers',
			esc_html__( 'Organizers', 'eduma' ),
			'thim_event_meta_boxes_callback',
			'tp_event'
		);
	}
}
add_action( 'add_meta_boxes', 'thim_event_add_meta_boxes' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
if ( ! function_exists( 'thim_event_meta_boxes_callback' ) ) {
	function thim_event_meta_boxes_callback( $post ) {

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'thim_event_save_meta_boxes', 'thim_event_meta_boxes_nonce' );

		// Get all team
		$team = new WP_Query( array(
			'post_type'           => 'our_team',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => - 1
		) );

		if ( empty( $team->post_count ) ) {
			echo '<p>' . esc_html__( 'No members exists. You can create a member data from', 'eduma' ) . ' <a target="_blank" href="' . admin_url( 'post-new.php?post_type=our_team' ) . '">Our Team</a></p>';

			return;
		}

		echo '<label for="thim_event_members">';
		esc_html_e( 'Get Members', 'eduma' );
		echo '</label> ';
		echo '<select id="thim_event_members" name="thim_event_members[]" multiple>';
		if ( isset( $team->posts ) ) {
			$team = $team->posts;
			foreach ( $team as $member ) {
				echo '<option value="' . esc_attr( $member->ID ) . '">' . get_the_title( $member->ID ) . '</option>';
			}
		}
		echo '</select>';
		echo '<span>';
		esc_html_e( 'Hold down the Ctrl (Windows) / Command (Mac) button to select multiple options.', 'eduma' );
		echo '</span><br>';
		wp_reset_postdata();

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$members = get_post_meta( $post->ID, 'thim_event_members', true );
		echo '<p>' . esc_html__( 'Current Members: ', 'eduma' );
		if ( ! $members ) {
			echo esc_html__( 'None', 'eduma' ) . '</p>';
		} else {
			$total = count( $members );
			foreach ( $members as $key => $id ) {
				echo '<strong><a target="_blank" href="' . get_edit_post_link( $id ) . '">' . get_the_title( $id ) . '</a></strong>';
				if ( ( $key + 1 ) != $total ) {
					echo ', ';
				}
			}
		}
	}
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
if ( ! function_exists( 'thim_event_save_meta_boxes' ) ) {
	function thim_event_save_meta_boxes( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['thim_event_meta_boxes_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['thim_event_meta_boxes_nonce'], 'thim_event_save_meta_boxes' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'tp_event' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

		}

		/* OK, it's safe for us to save the data now. */

		// Make sure that it is set.
		if ( ! isset( $_POST['thim_event_members'] ) ) {
			return;
		}

		// Update the meta field in the database.
		update_post_meta( $post_id, 'thim_event_members', $_POST['thim_event_members'] );
	}
}
add_action( 'save_post', 'thim_event_save_meta_boxes' );

/**
 * Remove action search on archive page
 */
remove_action( 'tp_event_before_main_content', 'wpems_before_main_content' );

/**
 * Change default comment fields
 *
 * @param $field
 *
 * @return string
 */
if ( ! function_exists( 'thim_new_comment_fields' ) ) {
	function thim_new_comment_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? 'aria-required=true' : '' );

		$fields = array(
			'author' => '<p class="comment-form-author">' . '<input placeholder="' . esc_attr__( 'Name', 'eduma' ) . ( $req ? ' *' : '' ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" ' . $aria_req . ' /></p>',
			'email'  => '<p class="comment-form-email">' . '<input placeholder="' . esc_attr__( 'Email', 'eduma' ) . ( $req ? ' *' : '' ) . '" id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" ' . $aria_req . ' /></p>',
			'url'    => '<p class="comment-form-url">' . '<input placeholder="' . esc_attr__( 'Website', 'eduma' ) . ( $req ? ' *' : '' ) . '" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" ' . $aria_req . ' /></p>',
		);

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'thim_new_comment_fields', 1 );


/**
 * Remove Emoji scripts
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );


/**
 * Optimize script files
 */
if ( ! function_exists( 'thim_optimize_scripts' ) ) {
	function thim_optimize_scripts() {
		global $wp_scripts;
		if ( ! is_a( $wp_scripts, 'WP_Scripts' ) ) {
			return;
		}
		foreach ( $wp_scripts->registered as $handle => $script ) {
			$wp_scripts->registered[ $handle ]->ver = null;
		}
	}
}


/**
 * Optimize style files
 */
if ( ! function_exists( 'thim_optimize_styles' ) ) {
	function thim_optimize_styles() {
		global $wp_styles;
		if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
			return;
		}
		foreach ( $wp_styles->registered as $handle => $style ) {
			if ( $handle !== 'thim-rtl' ) {
				$wp_styles->registered[ $handle ]->ver = null;
			}
		}
	}
}

/**
 * Remove query string in css files & js files
 */
$theme_remove_string = get_theme_mod( 'thim_remove_query_string', false );
if ( $theme_remove_string ) {
	add_action( 'wp_print_scripts', 'thim_optimize_scripts', 999 );
	add_action( 'wp_print_footer_scripts', 'thim_optimize_scripts', 999 );
	add_action( 'admin_print_styles', 'thim_optimize_styles', 999 );
	add_action( 'wp_print_styles', 'thim_optimize_styles', 999 );
}


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function thim_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}

add_filter( 'wp_page_menu_args', 'thim_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
if ( ! function_exists( 'thim_body_classes' ) ) {
	function thim_body_classes( $classes ) {
		$item_only = ! empty( $_REQUEST['content-item-only'] ) ? $_REQUEST['content-item-only'] : false;
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		if ( get_theme_mod( 'thim_body_custom_class', false ) ) {
			$classes[] = get_theme_mod( 'thim_body_custom_class', false );
		}

		if ( get_theme_mod( 'thim_rtl_support', false ) ) {
			$classes[] = 'rtl';
		}

		if ( get_theme_mod( 'thim_preload', true ) && empty( $item_only ) && ! is_page_template( 'page-templates/blank-page.php' ) ) {
			if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'tve_lead_shortcode' && isset( $_GET['tve'] ) && $_GET['tve'] === 'true' ) {
				# do nothings
			} else {
				$classes[] = 'thim-body-preload';
			}
		} else {
			$classes[] = 'thim-body-load-overlay';
		}

		if ( get_theme_mod( 'thim_box_layout', 'wide' ) == 'boxed' ) {
			$classes[] = 'boxed-area';
		}

		if ( get_theme_mod( 'thim_bg_boxed_type', 'image' ) == 'image' ) {
			$classes[] = 'bg-boxed-image';
		} else {
			$classes[] = 'bg-boxed-pattern';
		}

		if ( get_theme_mod( 'thim_size_body', '' ) == 'wide' ) {
			$classes[] = 'size_wide';
		}

		if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) != 'normal' ) {
			$classes[] = 'thim-style-content-' . get_theme_mod( 'thim_layout_content_page', '' );
		}

		if ( get_theme_mod( 'thim_learnpress_single_popup', false ) ) {
			$classes[] = 'thim-popup-feature';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'thim_body_classes' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function thim_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'thim_setup_author' );


/**
 * Check a plugin activate
 *
 * @param $plugin
 *
 * @return bool
 */
function thim_plugin_active( $plugin ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( $plugin ) ) {
		return true;
	}

	return false;
}


/**
 * Custom WooCommerce breadcrumbs
 *
 * @return array
 */
if ( ! function_exists( 'thim_woocommerce_breadcrumbs' ) ) {
	function thim_woocommerce_breadcrumbs() {
		return array(
			'delimiter'   => '',
			'wrap_before' => '<ul class="breadcrumbs" id="breadcrumbs" itemtype="http://schema.org/BreadcrumbList" itemscope="" itemprop="breadcrumb">',
			'wrap_after'  => '</ul>',
			'before'      => '<li itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement">',
			'after'       => '</li>',
			'home'        => esc_html__( 'Home', 'eduma' ),
		);
	}
}
add_filter( 'woocommerce_breadcrumb_defaults', 'thim_woocommerce_breadcrumbs' );

/**
 * Display post thumbnail by default
 *
 * @param $size
 */
if ( ! function_exists( 'thim_default_get_post_thumbnail' ) ) {
	function thim_default_get_post_thumbnail( $size ) {

		if ( thim_plugin_active( 'thim-framework/tp-framework.php' ) || thim_plugin_active( 'thim-core/thim-core.php' ) ) {
			return;
		}

		if ( get_the_post_thumbnail( get_the_ID(), $size ) ) {
			?>
			<div class='post-formats-wrapper'>
				<a class="post-image" href="<?php echo esc_url( get_permalink() ); ?>">
					<?php echo get_the_post_thumbnail( get_the_ID(), $size ); ?>
				</a>
			</div>
			<?php
		}
	}
}
add_action( 'thim_entry_top', 'thim_default_get_post_thumbnail', 20 );


/**
 * Set unlimit events in archive
 *
 * @param $query
 */
if ( ! function_exists( 'thim_event_post_filter' ) ) {
	function thim_event_post_filter( $query ) {
		global $wp_query;

		if ( is_post_type_archive( 'tp_event' ) && 'tp_event' == $query->get( 'post_type' ) ) {
			$query->set( 'posts_per_page', - 1 );

			return;
		}
	}
}
add_action( 'pre_get_posts', 'thim_event_post_filter' );

/**
 * Print inline css siteorigin
 */
if ( ! function_exists( 'thim_start_widget_element_content' ) ) {
	function thim_start_widget_element_content( $content, $panels_data, $post_id ) {
		global $siteorigin_panels_inline_css;

		if ( ! empty( $siteorigin_panels_inline_css[ $post_id ] ) ) {
			$content = '<style scoped>' . ( $siteorigin_panels_inline_css[ $post_id ] ) . '</style>' . $content;
		}

		return $content;
	}
}
remove_action( 'wp_footer', 'siteorigin_panels_print_inline_css' );
add_filter( 'siteorigin_panels_before_content', 'thim_start_widget_element_content', 10, 3 );

/**
 * Check images for ssl
 */
if ( ! function_exists( 'thim_ssl_secure_url' ) ) {
	function thim_ssl_secure_url( $sources ) {
		$scheme = parse_url( site_url(), PHP_URL_SCHEME );
		if ( 'https' == $scheme ) {
			if ( stripos( $sources, 'http://' ) === 0 ) {
				$sources = 'https' . substr( $sources, 4 );
			}

			return $sources;
		}

		return $sources;
	}
}

if ( ! function_exists( 'thim_ssl_secure_image_srcset' ) ) {
	function thim_ssl_secure_image_srcset( $sources ) {
		$scheme = parse_url( site_url(), PHP_URL_SCHEME );
		if ( 'https' == $scheme ) {
			foreach ( $sources as &$source ) {
				if ( stripos( $source['url'], 'http://' ) === 0 ) {
					$source['url'] = 'https' . substr( $source['url'], 4 );
				}
			}

			return $sources;
		}

		return $sources;
	}
}

add_filter( 'wp_calculate_image_srcset', 'thim_ssl_secure_image_srcset' );
add_filter( 'wp_get_attachment_url', 'thim_ssl_secure_url', 1000 );
add_filter( 'image_widget_image_url', 'thim_ssl_secure_url' );


/**
 * Testing with CF7 scripts
 */
if ( ! function_exists( 'thim_disable_cf7_cache' ) ) {
	function thim_disable_cf7_cache() {
		global $wp_scripts;
		if ( ! empty( $wp_scripts->registered['contact-form-7'] ) ) {
			if ( ! empty( $wp_scripts->registered['contact-form-7']->extra['data'] ) ) {
				$localize                                                = $wp_scripts->registered['contact-form-7']->extra['data'];
				$localize                                                = str_replace( '"cached":"1"', '"cached":0', $localize );
				$wp_scripts->registered['contact-form-7']->extra['data'] = $localize;
			}
		}
	}
}

add_action( 'wpcf7_enqueue_scripts', 'thim_disable_cf7_cache' );


/**
 * Function thim_related_our_team
 */
if ( ! function_exists( 'thim_related_our_team' ) ) {
	function thim_related_our_team( $post_id, $number_posts = - 1 ) {
		$query = new WP_Query();
		$args  = '';
		if ( $number_posts == 0 ) {
			return $query;
		}
		$args  = wp_parse_args( $args, array(
			'posts_per_page'      => $number_posts,
			'post_type'           => 'our_team',
			'post__not_in'        => array( $post_id ),
			'ignore_sticky_posts' => true,
			'tax_query'           => array(
				array(
					'taxonomy' => 'our_team_category',
					// taxonomy name
					'field'    => 'term_id',
					// term_id, slug or name
					'operator' => 'IN',
					'terms'    => wp_get_post_terms( $post_id, 'our_team_category', array( "fields" => "ids" ) ),
					// term id, term slug or term name
				)
			),
		) );
		$query = new WP_Query( $args );

		return $query;
	}
}


/**
 * Process events order
 */

add_filter( 'posts_fields', 'thim_event_posts_fields', 10, 2 );
add_filter( 'posts_join_paged', 'thim_event_posts_join_paged', 10, 2 );
add_filter( 'posts_where_paged', 'thim_event_posts_where_paged', 10, 2 );
add_filter( 'posts_orderby', 'thim_event_posts_orderby', 10, 2 );
/**
 * Check is event archive
 */
if ( ! function_exists( 'thim_is_events_archive' ) ) {
	function thim_is_events_archive() {
		global $pagenow, $post_type;
		if ( ! is_post_type_archive( 'tp_event' ) || ! is_main_query() ) {
			return false;
		}

		return true;
	}
}

/**
 * Event posts fields
 */
if ( ! function_exists( 'thim_event_posts_fields' ) ) {
	function thim_event_posts_fields( $fields, $q ) {
		if ( ! thim_is_events_archive() ) {
			return $fields;
		}
		if ( $q->get( 'post_status' ) == 'tp-event-expired' ) {
			$alias = 'end_date_time';
		} else {
			$alias = 'start_date_time';
		}
		$fields = " DISTINCT " . $fields;
		$fields .= ', concat( str_to_date( pm1.meta_value, \'%m/%d/%Y\' ), \' \', str_to_date(pm2.meta_value, \'%h:%i %p\' ) ) as ' . $alias;

		return $fields;
	}
}

/**
 * Event post join paged
 */
if ( ! function_exists( 'thim_event_posts_join_paged' ) ) {
	function thim_event_posts_join_paged( $join, $q ) {
		if ( ! thim_is_events_archive() ) {
			return $join;
		}

		global $wpdb;
		if ( $q->get( 'post_status' ) == 'tp-event-expired' ) {
			$join .= " LEFT JOIN {$wpdb->postmeta} pm1 ON pm1.post_id = {$wpdb->posts}.ID AND pm1.meta_key = 'tp_event_date_end'";
			$join .= " LEFT JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = {$wpdb->posts}.ID AND pm2.meta_key = 'tp_event_time_end'";
		} else {
			$join .= " LEFT JOIN {$wpdb->postmeta} pm1 ON pm1.post_id = {$wpdb->posts}.ID AND pm1.meta_key = 'tp_event_date_start'";
			$join .= " LEFT JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = {$wpdb->posts}.ID AND pm2.meta_key = 'tp_event_time_start'";
		}

		return $join;
	}
}

/**
 * Event posts where paged
 */
if ( ! function_exists( 'thim_event_posts_where_paged' ) ) {
	function thim_event_posts_where_paged( $where, $q ) {
		if ( ! thim_is_events_archive() ) {
			return $where;
		}

		return $where;
	}
}

/**
 * Event posts orderby
 */
if ( ! function_exists( 'thim_event_posts_orderby' ) ) {
	function thim_event_posts_orderby( $order_by_statement, $q ) {
		global $wp_query;
		if ( ! thim_is_events_archive() ) {
			return $order_by_statement;
		}
		if ( $q->get( 'post_status' ) == 'tp-event-expired' ) {
			$order_by_statement = "end_date_time DESC";
		} else {
			$order_by_statement = "start_date_time ASC";
		}

		return $order_by_statement;
	}
}

/**
 * Replace password message
 */
if ( ! function_exists( 'thim_replace_retrieve_password_message' ) ) {
	function thim_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {

		$reset_link = add_query_arg(
			array(
				'action' => 'rp',
				'key'    => $key,
				'login'  => rawurlencode( $user_login )
			), thim_get_login_page_url()
		);

		// Create new message
		$message = __( 'Someone has requested a password reset for the following account:', 'eduma' ) . "\n";
		$message .= sprintf( __( 'Site Name: %s' ), network_home_url( '/' ) ) . "\n";
		$message .= sprintf( __( 'Username: %s', 'eduma' ), $user_login ) . "\n";
		$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'eduma' ) . "\n";
		$message .= __( 'To reset your password, visit the following address:', 'eduma' ) . "\n";
		$message .= $reset_link . "\n";

		return $message;
	}
}
/**
 * Add filter if without using wpengine
 */
if ( ! function_exists( 'is_wpe' ) && ! function_exists( 'is_wpe_snapshot' ) ) {
	add_filter( 'retrieve_password_message', 'thim_replace_retrieve_password_message', 10, 4 );
}

/**
 * Do password reset
 */
if ( ! function_exists( 'thim_do_password_reset' ) ) {
	function thim_do_password_reset() {

		$login_page = thim_get_login_page_url();
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

			if ( ! isset( $_REQUEST['key'] ) || ! isset( $_REQUEST['login'] ) ) {
				return;
			}

			$key   = $_REQUEST['key'];
			$login = $_REQUEST['login'];

			$user = check_password_reset_key( $key, $login );

			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( add_query_arg(
						array(
							'action'      => 'rp',
							'expired_key' => '1',
						), $login_page
					) );
				} else {
					wp_redirect( add_query_arg(
						array(
							'action'      => 'rp',
							'invalid_key' => '1',
						), $login_page
					) );
				}
				exit;
			}

			if ( isset( $_POST['password'] ) ) {

				if ( empty( $_POST['password'] ) ) {
					// Password is empty
					wp_redirect( add_query_arg(
						array(
							'action'           => 'rp',
							'key'              => $_REQUEST['key'],
							'login'            => $_REQUEST['login'],
							'invalid_password' => '1',
						), $login_page
					) );
					exit;
				}

				// Parameter checks OK, reset password
				reset_password( $user, $_POST['password'] );
				wp_redirect( add_query_arg(
					array(
						'result' => 'changed',
					), $login_page
				) );
			} else {
				_e( 'Invalid request.', 'eduma' );
			}

			exit;
		}
	}
}
add_action( 'login_form_rp', 'thim_do_password_reset', 1000 );
add_action( 'login_form_resetpass', 'thim_do_password_reset', 1000 );


/**
 * Related portfolio
 */
if ( ! function_exists( 'thim_related_portfolio' ) ) {
	function thim_related_portfolio( $post_id ) {

		?>
		<div class="related-portfolio col-md-12">
			<div class="module_title"><h4 class="widget-title"><?php esc_html_e( 'Related Items', 'eduma' ); ?></h4>
			</div>

			<?php //Get Related posts by category	-->
			$args      = array(
				'posts_per_page' => 3,
				'post_type'      => 'portfolio',
				'post_status'    => 'publish',
				'post__not_in'   => array( $post_id )
			);
			$port_post = get_posts( $args );
			?>

			<ul class="row">
				<?php
				foreach ( $port_post as $post ) : setup_postdata( $post ); ?>
					<?php
					$bk_ef = get_post_meta( $post->ID, 'thim_portfolio_bg_color_ef', true );
					if ( $bk_ef == '' ) {
						$bk_ef = get_post_meta( $post->ID, 'thim_portfolio_bg_color_ef', true );
						$bg    = '';
					} else {
						$bk_ef = get_post_meta( $post->ID, 'thim_portfolio_bg_color_ef', true );
						$bg    = 'style="background-color:' . $bk_ef . ';"';
					}
					?>
					<li class="col-sm-4">
						<?php

						$imImage = get_permalink( $post->ID );

						$image_url = thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_portfolio_thumbnail_width', 480 ), apply_filters( 'thim_portfolio_thumbnail_height', 320 ) );
						echo '<div data-color="' . $bk_ef . '" ' . $bg . '>';
						echo '<div class="portfolio-image" ' . $bg . '>' . $image_url . '
					<div class="portfolio_hover"><div class="thumb-bg"><div class="mask-content">';
						echo '<h3><a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" >' . get_the_title( $post->ID ) . '</a></h3>';
						echo '<span class="p_line"></span>';
						$terms    = get_the_terms( $post->ID, 'portfolio_category' );
						$cat_name = "";
						if ( $terms && ! is_wp_error( $terms ) ) :
							foreach ( $terms as $term ) {
								if ( $cat_name ) {
									$cat_name .= ', ';
								}
								$cat_name .= '<a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . "</a>";
							}
							echo '<div class="cat_portfolio">' . $cat_name . '</div>';
						endif;
						echo '<a href="' . esc_url( $imImage ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" class="btn_zoom ">' . esc_html__( 'Zoom', 'eduma' ) . '</a>';
						echo '</div></div></div></div></div>';
						?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		</div>
		<?php
	}
}


add_action( 'wp_ajax_thim_gallery_popup', 'thim_gallery_popup' );
add_action( 'wp_ajax_nopriv_thim_gallery_popup', 'thim_gallery_popup' );
/**
 * Function ajax widget gallery-posts
 */
if ( ! function_exists( 'thim_gallery_popup' ) ) {
	function thim_gallery_popup() {
		global $post;
		$post_id = $_POST["post_id"];
		$post    = get_post( $post_id );

		$format = get_post_format( $post_id->ID );

		$error = true;
		$link  = get_edit_post_link( $post_id );
		ob_start();

		if ( $format == 'video' ) {
			$url_video = get_post_meta( $post_id, 'thim_video', true );
			if ( empty( $url_video ) ) {
				echo '<div class="thim-gallery-message"><a class="link" href="' . $link . '">' . esc_html__( 'This post doesn\'t have config video, please add the video!', 'eduma' ) . '</a></div>';
			}
			// If URL: show oEmbed HTML
			if ( filter_var( $url_video, FILTER_VALIDATE_URL ) ) {
				if ( $oembed = @wp_oembed_get( $url_video ) ) {
					echo '<div class="video">' . $oembed . '</div>';
				}
			} else {
				echo '<div class="video">' . $url_video . '</div>';
			}

		} else {
			$images = thim_meta( 'thim_gallery', "type=image&single=false&size=full" );
			// Get category permalink


			if ( ! empty( $images ) ) {
				foreach ( $images as $k => $value ) {
					$url_image = $value['url'];
					if ( $url_image && $url_image != '' ) {
						echo '<a href="' . $url_image . '">';
						echo '<img src="' . $url_image . '" />';
						echo '</a>';
						$error = false;
					}
				}
			}
			if ( $error ) {
				if ( is_user_logged_in() ) {
					echo '<div class="thim-gallery-message"><a class="link" href="' . $link . '">' . esc_html__( 'This post doesn\'t have any gallery images, please add some!', 'eduma' ) . '</a></div>';
				} else {
					echo '<div class="thim-gallery-message">' . esc_html__( 'This post doesn\'t have any gallery images, please add some!', 'eduma' ) . '</div>';
				}

			}
		}

		$output = ob_get_contents();
		ob_end_clean();
		echo ent2ncr( $output );
		die();
	}
}

/**
 * LearnPress section
 */

if ( thim_plugin_active( 'learnpress/learnpress.php' ) ) {
	//filter learnpress hooks
	if ( thim_is_new_learnpress( '3.0' ) ) {

		function thim_new_learnpress_template_path( $slash ) {
			return 'learnpress-v3';
		}

		add_filter( 'learn_press_template_path', 'thim_new_learnpress_template_path', 999 );
		require_once THIM_DIR . 'inc/learnpress-v3-functions.php';

	} else if ( thim_is_new_learnpress( '2.0' ) ) {

		function thim_new_learnpress_template_path( $slash ) {
			return 'learnpress-v2';
		}

		add_filter( 'learn_press_template_path', 'thim_new_learnpress_template_path', 999 );
		require_once THIM_DIR . 'inc/learnpress-v2-functions.php';

	} else if ( thim_is_new_learnpress( '1.0' ) ) {

		function thim_new_learnpress_template_path( $slash ) {
			return 'learnpress-v1';
		}

		add_filter( 'learn_press_template_path', 'thim_new_learnpress_template_path', 999 );
		require_once THIM_DIR . 'inc/learnpress-v1-functions.php';

	} else {
		require_once THIM_DIR . 'inc/learnpress-functions.php';
	}

}

/**
 * Check new version of LearnPress
 *
 * @return mixed
 */
function thim_is_new_learnpress( $version ) {
	if ( defined( 'LEARNPRESS_VERSION' ) ) {
		return version_compare( LEARNPRESS_VERSION, $version, '>=' );
	} else {
		return version_compare( get_option( 'learnpress_version' ), $version, '>=' );
	}
}

/**
 * Check new version of addons LearnPress woo
 *
 * @return mixed
 */
function thim_is_version_addons_woo( $version ) {
	if ( defined( 'LP_ADDON_WOO_PAYMENT_VER' ) ) {
		return ( version_compare( LP_ADDON_WOO_PAYMENT_VER, $version, '>=' ) && version_compare( LP_ADDON_WOO_PAYMENT_VER, (int) $version + 1, '<' ) );
	}

	return false;
}

/**
 * Check new version of addons LearnPress course review
 *
 * @return mixed
 */
function thim_is_version_addons_review( $version ) {
	if ( defined( 'LP_ADDON_COURSE_REVIEW_VER' ) ) {
		return ( version_compare( LP_ADDON_COURSE_REVIEW_VER, $version, '>=' ) && version_compare( LP_ADDON_COURSE_REVIEW_VER, (int) $version + 1, '<' ) );
	}

	return false;
}

/**
 * Check new version of addons LearnPress bbpress
 *
 * @return mixed
 */
function thim_is_version_addons_bbpress( $version ) {
	if ( defined( 'LP_ADDON_BBPRESS_VER' ) ) {
		return ( version_compare( LP_ADDON_BBPRESS_VER, $version, '>=' ) && version_compare( LP_ADDON_BBPRESS_VER, (int) $version + 1, '<' ) );
	}

	return false;
}

/**
 * Check new version of addons LearnPress certificate
 *
 * @return mixed
 */
function thim_is_version_addons_certificates( $version ) {
	if ( defined( 'LP_ADDON_CERTIFICATES_VER' ) ) {
		return ( version_compare( LP_ADDON_CERTIFICATES_VER, $version, '>=' ) && version_compare( LP_ADDON_CERTIFICATES_VER, (int) $version + 1, '<' ) );
	}

	return false;
}

/**
 * Check new version of addons LearnPress wishlist
 *
 * @return mixed
 */
function thim_is_version_addons_wishlist( $version ) {
	if ( defined( 'LP_ADDON_WISHLIST_VER' ) ) {
		return ( version_compare( LP_ADDON_WISHLIST_VER, $version, '>=' ) && version_compare( LP_ADDON_WISHLIST_VER, (int) $version + 1, '<' ) );
	}

	return false;
}

/**
 * Check new version of addons LearnPress Co-instructor
 *
 * @return mixed
 */
function thim_is_version_addons_instructor( $version ) {
	if ( defined( 'LP_ADDON_CO_INSTRUCTOR_VER' ) ) {
		return ( version_compare( LP_ADDON_CO_INSTRUCTOR_VER, $version, '>=' ) && version_compare( LP_ADDON_CO_INSTRUCTOR_VER, (int) $version + 1, '<' ) );
	}

	return false;
}

//Action call reload page when change font on preview box
function thim_chameleon_add_script_reload() {
	?>
	location.reload();
	<?php
}

add_action( 'tp_chameleon_script_after_change_body_font', 'thim_chameleon_add_script_reload' );
add_action( 'tp_chameleon_script_after_change_heading_font', 'thim_chameleon_add_script_reload' );


/**
 * Remove action single event
 */
remove_action( 'tp_event_after_loop_event_item', 'event_auth_register' );
remove_action( 'tp_event_after_single_event', 'wpems_single_event_register' );
remove_action( 'tp_event_after_single_event', 'event_auth_register' );
remove_action( 'tp_event_after_single_event', 'tp_event_single_event_register' );

if ( ! function_exists( 'thim_remove_create_page_action_event_auth' ) ) {
	function thim_remove_activate_action_event_auth( $plugin ) {
		if ( $plugin === 'tp-event-auth' ) {
			add_filter( 'event_auth_create_pages', 'thim_remove_create_page_action_event_auth' );
		}
	}
}
add_action( 'activate_plugin', 'thim_remove_activate_action_event_auth' );

if ( ! function_exists( 'thim_remove_create_page_action_event_auth' ) ) {
	function thim_remove_create_page_action_event_auth( $return ) {
		return false;
	}
}

/**
 * Define ajaxurl if not exist
 */
if ( ! function_exists( 'thim_define_ajaxurl' ) ) {
	function thim_define_ajaxurl() {
		?>
		<script type="text/javascript">
            if (typeof ajaxurl === 'undefined') {
                /* <![CDATA[ */
                var ajaxurl = "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>";
                /* ]]> */
            }
		</script>
		<?php
	}
}
add_action( 'wp_head', 'thim_define_ajaxurl', 1000 );

/**
 * Add js for thim-preload
 */
if ( ! function_exists( 'thim_js_inline_windowload' ) ) {
	function thim_js_inline_windowload() {
		$item_only = ! empty( $_REQUEST['content-item-only'] ) ? $_REQUEST['content-item-only'] : false;
		if ( get_theme_mod( 'thim_preload', true ) && empty( $item_only ) && ! is_admin() ) {
			?>
			<script data-cfasync="false" type="text/javascript">
                window.onload = function() {
                    var thim_preload = document.getElementById('preload');
                    if (thim_preload) {
                        setTimeout(function() {
                            var body = document.getElementById('thim-body'),
                                len = body.childNodes.length,
                                class_name = body.className.replace(/(?:^|\s)thim-body-preload(?!\S)/, '').
                                    replace(/(?:^|\s)thim-body-load-overlay(?!\S)/, '');

                            body.className = class_name;
                            if (typeof thim_preload !== 'undefined' && thim_preload !== null) {
                                for (var i = 0; i < len; i++) {
                                    if (body.childNodes[i].id !== 'undefined' && body.childNodes[i].id == 'preload') {
                                        body.removeChild(body.childNodes[i]);
                                        break;
                                    }
                                }
                            }
                        }, 500);
                    }
                };
			</script>
			<?php
		}
		?>
		<script>
            window.addEventListener('load', function() {
                /**
                 * Fix issue there is an empty spacing between image and title of owl-carousel
                 */
                setTimeout(function() {
                    var $ = jQuery;
                    var $carousel = $('.thim-owl-carousel-post').each(function() {
                        $(this).find('.image').css('min-height', 0);
                        $(window).trigger('resize');
                    });
                }, 500);
            });
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'thim_js_inline_windowload' );


/**
 * @param $output
 * @param $args
 *
 * @return string
 */
if ( ! function_exists( 'thim_polylang_dropdown' ) ) {
	function thim_polylang_dropdown( $output, $args ) {

		if ( $args['dropdown'] ) {
			$languages        = PLL()->model->get_languages_list();
			$current_language = $list = '';

			foreach ( $languages as $language ) {
				if ( pll_current_language() == $language->slug ) {
					$current_language = '<a class="lang-item active" href="' . $language->home_url . '"><img src="' . $language->flag_url . '" alt="' . $language->slug . '" />' . $language->name . '</a>';
				}
				$list .= '<li class="lang-item"><a class="lang-select" href="' . $language->home_url . '"><img src="' . $language->flag_url . '" alt="' . $language->slug . '" />' . $language->name . '</a></li>';
			}

			$output = sprintf(
				'<div class="thim-language" id="lang_choice_polylang-3">%s<ul class="list-lang">%s</ul></div>',
				$current_language, $list
			);
		}

		return $output;
	}
}
add_filter( 'pll_the_languages', 'thim_polylang_dropdown', 10, 2 );


/**
 * Remove hook tp-event-auth
 */
if ( class_exists( 'TP_Event_Authentication' ) ) {
	if ( ! version_compare( get_option( 'event_auth_version' ), '1.0.3', '>=' ) ) {
		$auth = TP_Event_Authentication::getInstance()->auth;

		remove_action( 'login_form_login', array( $auth, 'redirect_to_login_page' ) );
		remove_action( 'login_form_register', array( $auth, 'login_form_register' ) );
		remove_action( 'login_form_lostpassword', array( $auth, 'redirect_to_lostpassword' ) );
		remove_action( 'login_form_rp', array( $auth, 'resetpass' ) );
		remove_action( 'login_form_resetpass', array( $auth, 'resetpass' ) );

		remove_action( 'wp_logout', array( $auth, 'wp_logout' ) );
		remove_filter( 'login_url', array( $auth, 'login_url' ) );
		remove_filter( 'login_redirect', array( $auth, 'login_redirect' ) );
	}
}
/**
 * Filter event login url
 */
add_filter( 'tp_event_login_url', 'thim_get_login_page_url' );
add_filter( 'event_auth_login_url', 'thim_get_login_page_url' );

/*
 * Remove login page link in the email new user notification
 * */

// Detect thim register form
function thim_check_user_notification_option() {
	global $wp;

	if ( ! empty( $_REQUEST['modify_user_notification'] ) ) {
		$wp->query_vars['modify_user_notification'] = 1;
	}
}

add_action( 'retrieve_password_key', 'thim_check_user_notification_option' );

/**
 * Get current url
 */
if ( ! function_exists( 'thim_get_current_url' ) ) {
	function thim_get_current_url() {
		static $current_url;
		if ( ! $current_url ) {
			if ( ! empty( $_REQUEST['login'] ) ) {
				$url = add_query_arg( array( 'login' => rawurlencode( $_REQUEST['login'] ) ) );
			} else {
				$url = add_query_arg();
			}

			if ( is_multisite() ) {
				if ( ! preg_match( '!^https?!', $url ) ) {
					$segs1 = explode( '/', get_site_url() );
					$segs2 = explode( '/', $url );
					if ( $removed = array_intersect( $segs1, $segs2 ) ) {
						$segs2 = array_diff( $segs2, $removed );
						$url   = get_site_url() . '/' . join( '/', $segs2 );
					}
				}
			} else {
				if ( ! preg_match( '!^https?!', $url ) ) {
					$segs1 = explode( '/', home_url( '/' ) );
					$segs2 = explode( '/', $url );
					if ( $removed = array_intersect( $segs1, $segs2 ) ) {
						$segs2 = array_diff( $segs2, $removed );
						$url   = home_url( '/' ) . join( '/', $segs2 );
					}
				}
			}

			$current_url = $url;

		}

		return $current_url;
	}
}

/**
 * Check is current url
 */
if ( ! function_exists( 'thim_is_current_url' ) ) {
	function thim_is_current_url( $url ) {
		return strcmp( thim_get_current_url(), $url ) == 0;
	}
}


//Filter post_status tp_event
if ( ! function_exists( 'thim_get_upcoming_events' ) ) {
	function thim_get_upcoming_events( $args = array() ) {
		if ( is_tax( 'tp_event_category' ) ) {
			if ( version_compare( WPEMS_VER, '2.1.5', '>=' ) ) {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'  => 'tp_event',
						'meta_query' => array(
							array(
								'key'     => 'tp_event_status',
								'value'   => 'upcoming',
								'compare' => '=',
							),
						),
						'tax_query'  => array(
							array(
								'taxonomy' => 'tp_event_category',
								'field'    => 'slug',
								'terms'    => get_query_var( 'term' ),
							)
						),
					)
				);
			} else {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'   => 'tp_event',
						'post_status' => 'tp-event-upcoming',
						'tax_query'   => array(
							array(
								'taxonomy' => 'tp_event_category',
								'field'    => 'slug',
								'terms'    => get_query_var( 'term' ),
							)
						),
					)
				);
			}
		} else {
			if ( version_compare( WPEMS_VER, '2.1.5', '>=' ) ) {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'  => 'tp_event',
						'meta_query' => array(
							array(
								'key'     => 'tp_event_status',
								'value'   => 'upcoming',
								'compare' => '=',
							),
						),
					)
				);
			} else {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'   => 'tp_event',
						'post_status' => 'tp-event-upcoming',
					)
				);
			}
		}


		return new WP_Query( $args );
	}
}

if ( ! function_exists( 'thim_get_expired_events' ) ) {
	function thim_get_expired_events( $args = array() ) {
		if ( is_tax( 'tp_event_category' ) ) {
			if ( version_compare( WPEMS_VER, '2.1.5', '>=' ) ) {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'  => 'tp_event',
						'meta_query' => array(
							array(
								'key'     => 'tp_event_status',
								'value'   => 'expired',
								'compare' => '=',
							),
						),
						'tax_query'  => array(
							array(
								'taxonomy' => 'tp_event_category',
								'field'    => 'slug',
								'terms'    => get_query_var( 'term' ),
							)
						),
					)
				);
			} else {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'   => 'tp_event',
						'post_status' => 'tp-event-expired',
						'tax_query'   => array(
							array(
								'taxonomy' => 'tp_event_category',
								'field'    => 'slug',
								'terms'    => get_query_var( 'term' ),
							)
						),
					)
				);
			}
		} else {
			if ( version_compare( WPEMS_VER, '2.1.5', '>=' ) ) {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'  => 'tp_event',
						'meta_query' => array(
							array(
								'key'     => 'tp_event_status',
								'value'   => 'expired',
								'compare' => '=',
							),
						),
					)
				);
			} else {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'   => 'tp_event',
						'post_status' => 'tp-event-expired',
					)
				);
			}
		}

		return new WP_Query( $args );
	}
}

if ( ! function_exists( 'thim_get_happening_events' ) ) {
	function thim_get_happening_events( $args = array() ) {
		if ( is_tax( 'tp_event_category' ) ) {
			if ( version_compare( WPEMS_VER, '2.1.5', '>=' ) ) {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'  => 'tp_event',
						'meta_query' => array(
							array(
								'key'     => 'tp_event_status',
								'value'   => 'happening',
								'compare' => '=',
							),
						),
						'tax_query'  => array(
							array(
								'taxonomy' => 'tp_event_category',
								'field'    => 'slug',
								'terms'    => get_query_var( 'term' ),
							)
						),
					)
				);
			} else {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'   => 'tp_event',
						'post_status' => 'tp-event-happenning',
						'tax_query'   => array(
							array(
								'taxonomy' => 'tp_event_category',
								'field'    => 'slug',
								'terms'    => get_query_var( 'term' ),
							)
						),
					)
				);
			}
		} else {
			if ( version_compare( WPEMS_VER, '2.1.5', '>=' ) ) {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'  => 'tp_event',
						'meta_query' => array(
							array(
								'key'     => 'tp_event_status',
								'value'   => 'happening',
								'compare' => '=',
							),
						),
					)
				);
			} else {
				$args = wp_parse_args(
					$args,
					array(
						'post_type'   => 'tp_event',
						'post_status' => 'tp-event-happenning',
					)
				);
			}
		}

		return new WP_Query( $args );
	}
}

/**
 * Hook get template archive event
 */
if ( ! function_exists( 'thim_archive_event_template' ) ) {
	function thim_archive_event_template( $template ) {
		if ( get_post_type() == 'tp_event' && is_post_type_archive( 'tp_event' ) || is_tax( 'tp_event_category' ) ) {
			$GLOBALS['thim_happening_events'] = thim_get_happening_events();
			$GLOBALS['thim_upcoming_events']  = thim_get_upcoming_events();
			$GLOBALS['thim_expired_events']   = thim_get_expired_events();
		}

		return $template;
	}
}
add_action( 'template_include', 'thim_archive_event_template' );

/**
 * Filter level cost text paid membership pro
 */
add_filter( 'pmpro_level_cost_text', 'thim_pmpro_getLevelCost', 10, 4 );
if ( ! function_exists( 'thim_pmpro_getLevelCost' ) ) {
	function thim_pmpro_getLevelCost( $r, $level, $tags, $short ) {
		//initial payment
		if ( ! $short ) {
			$r = sprintf( __( 'The price for membership is <p class="price">%s</p>', 'eduma' ), pmpro_formatPrice( $level->initial_payment ) );
		} else {
			$r = sprintf( __( '%s', 'eduma' ), pmpro_formatPrice( $level->initial_payment ) );
		}

		//recurring part
		if ( $level->billing_amount != '0.00' ) {
			if ( $level->billing_limit > 1 ) {
				if ( $level->cycle_number == '1' ) {
					$r .= sprintf( __( '<p class="expired">then %s per %s for %d more %s</p>', 'eduma' ), pmpro_formatPrice( $level->billing_amount ), pmpro_translate_billing_period( $level->cycle_period ), $level->billing_limit, pmpro_translate_billing_period( $level->cycle_period, $level->billing_limit ) );
				} else {
					$r .= sprintf( __( '<p class="expired">then %s every %d %s for %d more payments</p>', 'eduma' ), pmpro_formatPrice( $level->billing_amount ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ), $level->billing_limit );
				}
			} elseif ( $level->billing_limit == 1 ) {
				$r .= sprintf( __( '<p class="expired">then %s after %d %s</p>', 'eduma' ), pmpro_formatPrice( $level->billing_amount ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
			} else {
				if ( $level->billing_amount === $level->initial_payment ) {
					if ( $level->cycle_number == '1' ) {
						if ( ! $short ) {
							$r = sprintf( __( 'The price for membership is <strong>%s per %s</strong>', 'eduma' ), pmpro_formatPrice( $level->initial_payment ), pmpro_translate_billing_period( $level->cycle_period ) );
						} else {
							$r = sprintf( __( '<p class="expired">%s per %s</p>', 'eduma' ), pmpro_formatPrice( $level->initial_payment ), pmpro_translate_billing_period( $level->cycle_period ) );
						}
					} else {
						if ( ! $short ) {
							$r = sprintf( __( 'The price for membership is <strong>%s every %d %s</strong>', 'eduma' ), pmpro_formatPrice( $level->initial_payment ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
						} else {
							$r = sprintf( __( '<p class="expired">%s every %d %s</p>', 'eduma' ), pmpro_formatPrice( $level->initial_payment ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
						}
					}
				} else {
					if ( $level->cycle_number == '1' ) {
						$r .= sprintf( __( '<p class="expired">then %s per %s</p>', 'eduma' ), pmpro_formatPrice( $level->billing_amount ), pmpro_translate_billing_period( $level->cycle_period ) );
					} else {
						$r .= sprintf( __( '<p class="expired">and then %s every %d %s</p>', 'eduma' ), pmpro_formatPrice( $level->billing_amount ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
					}
				}
			}
		}

		//trial part
		if ( $level->trial_limit ) {
			if ( $level->trial_amount == '0.00' ) {
				if ( $level->trial_limit == '1' ) {
					$r .= ' ' . __( 'After your initial payment, your first payment is Free.', 'eduma' );
				} else {
					$r .= ' ' . sprintf( __( 'After your initial payment, your first %d payments are Free.', 'eduma' ), $level->trial_limit );
				}
			} else {
				if ( $level->trial_limit == '1' ) {
					$r .= ' ' . sprintf( __( 'After your initial payment, your first payment will cost %s.', 'eduma' ), pmpro_formatPrice( $level->trial_amount ) );
				} else {
					$r .= ' ' . sprintf( __( 'After your initial payment, your first %d payments will cost %s.', 'eduma' ), $level->trial_limit, pmpro_formatPrice( $level->trial_amount ) );
				}
			}
		}

		//taxes part
		$tax_state = pmpro_getOption( "tax_state" );
		$tax_rate  = pmpro_getOption( "tax_rate" );

		if ( $tax_state && $tax_rate && ! pmpro_isLevelFree( $level ) ) {
			$r .= sprintf( __( 'Customers in %s will be charged %s%% tax.', 'eduma' ), $tax_state, round( $tax_rate * 100, 2 ) );
		}

		if ( ! $tags ) {
			$r = strip_tags( $r );
		}

		return $r;
	}
}

/**
 * @param $settings
 *
 * @return array
 */
if ( ! function_exists( 'thim_update_metabox_settings' ) ) {
	function thim_update_metabox_settings( $settings ) {
		$settings[] = 'lp_course';
		$settings[] = 'tp_event';

		return $settings;
	}
}
add_filter( 'thim_framework_metabox_settings', 'thim_update_metabox_settings' );

/**
 * Filters Paid Membership pro login redirect & register redirect
 */
remove_filter( 'login_redirect', 'pmpro_login_redirect', 10 );
add_filter( 'pmpro_register_redirect', '__return_false' );

/**
 * Add custom JS
 *
 * TODO check this function for registration feature
 */
if ( ! function_exists( 'thim_add_custom_js' ) ) {
	function thim_add_custom_js() {
		$custom_js = get_theme_mod( 'thim_custom_js', '' );

		if ( ! empty( $custom_js ) ) {
			if ( strpos( $custom_js, '</script>' ) !== false ) {
				echo $custom_js;
			} else {
				?>
				<script data-cfasync="false" type="text/javascript">
					<?php echo $custom_js; ?>
				</script>
				<?php
			}
		}
	}
}
add_action( 'wp_footer', 'thim_add_custom_js' );

/**
 * Get course filter
 */
if ( ! function_exists( 'thim_get_course_filter' ) ) {
	function thim_get_course_filter() {
		global $wp_query;
		if ( ! $wp_query->is_main_query() ) {
			return false;
		}

		if ( $wp_query->get( 'post_type' ) != 'lp_course' ) {
			return false;
		}

		$filter = $wp_query->get( 'course_filter_order' );
		if ( ! $filter ) {
			return false;
		}

		return $filter;
	}
}

/**
 * Course filter where
 */
if ( ! function_exists( 'thim_course_filter_where' ) ) {
	function thim_course_filter_where( $where ) {
		if ( ! $filter = thim_get_course_filter() ) {
			return $where;
		}

		return $where;
	}
}
add_filter( 'posts_where_paged', 'thim_course_filter_where' );

/**
 * Course filter join
 */
if ( ! function_exists( 'thim_course_filter_join' ) ) {
	function thim_course_filter_join( $join ) {
		global $wp_query, $wpdb;
		if ( ! $filter = thim_get_course_filter() ) {
			return $join;
		}
		if ( ! empty( $filter['price'] ) || ( ! empty( $filter['orderby'] ) && $filter['orderby'] == 'price' ) ) {
			$join .= $wpdb->prepare( "
				LEFT JOIN {$wpdb->postmeta} pm_price ON ( {$wpdb->posts}.ID = pm_price.post_id ) AND pm_price.meta_key = %s
				LEFT JOIN {$wpdb->postmeta} pm_payment ON ( {$wpdb->posts}.ID = pm_payment.post_id ) AND pm_payment.meta_key = %s
				LEFT JOIN {$wpdb->postmeta} pm_sale_price ON ( {$wpdb->posts}.ID = pm_sale_price.post_id ) AND pm_sale_price.meta_key = %s
				LEFT JOIN {$wpdb->postmeta} pm_sale_start ON ( {$wpdb->posts}.ID = pm_sale_start.post_id ) AND pm_sale_start.meta_key = %s
				LEFT JOIN {$wpdb->postmeta} pm_sale_end ON ( {$wpdb->posts}.ID = pm_sale_end.post_id ) AND pm_sale_end.meta_key = %s
			", '_lp_price', '_lp_payment', '_lp_sale_price', '_lp_sale_start', '_lp_sale_end' );
		}

		if ( $filter['featured'] ) {
			$join .= $wpdb->prepare( "
				INNER JOIN {$wpdb->postmeta} pm_featured ON ( {$wpdb->posts}.ID = pm_featured.post_id ) AND pm_featured.meta_key = %s AND pm_featured.meta_value = %s
			", '_lp_featured', 'yes' );
		}

		return $join;
	}
}
add_filter( 'posts_join_paged', 'thim_course_filter_join' );

/**
 * Course filter posts fields
 */
if ( ! function_exists( 'thim_course_filter_posts_fields' ) ) {
	function thim_course_filter_posts_fields( $fields ) {
		if ( ! $filter = thim_get_course_filter() ) {
			return $fields;
		}
		global $wpdb;

		if ( ! empty( $filter['price'] ) || ( ! empty( $filter['orderby'] ) && $filter['orderby'] == 'price' ) ) {
			$fields .= ",
			IF(
				pm_payment.meta_value = 'yes',
				IF(
					(pm_sale_start.meta_value IS NULL AND pm_sale_end.meta_value IS NULL) OR
					( NOW() NOT BETWEEN pm_sale_start.meta_value AND pm_sale_end.meta_value	OR NOW() < pm_sale_start.meta_value	OR NOW() > pm_sale_end.meta_value ) ,
					pm_price.meta_value,
					pm_sale_price.meta_value
				),
				0
			) AS price";
		}
		if ( ! empty( $filter['orderby'] ) ) {
			if ( $filter['orderby'] == 'rating' ) {
				$fields .= $wpdb->prepare( ",
					(
						SELECT AVG(cm.meta_value)
						FROM {$wpdb->commentmeta} cm
						LEFT JOIN {$wpdb->comments} c ON c.comment_id = cm.comment_id AND cm.meta_key = %s
						WHERE c.comment_type = %s AND c.comment_approved = 1
						AND c.comment_post_ID = {$wpdb->posts}.ID
						GROUP BY c.comment_post_ID
					) as rating
				", '_lpr_rating', 'review' );
			}

			if ( $filter['orderby'] == 'students' ) {
				$fields .= $wpdb->prepare( ",
					(
						SELECT a+IF(b IS NULL, 0, b) AS students
						FROM (
							SELECT p.ID AS ID, IF(pm.meta_value, pm.meta_value, 0) AS a, (
								SELECT COUNT(*)
								FROM (
									SELECT COUNT(item_id), item_id, user_id
									FROM {$wpdb->prefix}learnpress_user_items
									GROUP BY item_id, user_id
								) AS Y
								GROUP BY item_id
								HAVING item_id = p.ID
							) AS b
							FROM {$wpdb->posts} p
							LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id AND pm.meta_key = %s
							WHERE p.post_type = %s AND p.post_status = %s
							GROUP BY ID
						) AS Z
						WHERE ID = {$wpdb->posts}.ID
					) as students
				", '_lp_students', 'lp_course', 'publish' );
			}
		}

		return $fields;
	}
}
add_filter( 'posts_fields', 'thim_course_filter_posts_fields' );

/**
 * Course filter posts groupby
 */
if ( ! function_exists( 'thim_course_filter_posts_groupby' ) ) {
	function thim_course_filter_posts_groupby( $groupby ) {
		if ( ! $filter = thim_get_course_filter() ) {
			return $groupby;
		}
		global $wpdb;
		if ( ! empty( $filter['price'] ) ) {
			if ( $filter['price'] == 'paid' ) {
				$groupby .= " {$wpdb->posts}.ID HAVING price > 0";
			} else {
				$groupby .= " {$wpdb->posts}.ID HAVING price = 0";
			}
		}

		return $groupby;
	}
}
add_filter( 'posts_groupby', 'thim_course_filter_posts_groupby' );

/**
 * Course filter posts distinct
 */
if ( ! function_exists( 'thim_course_filter_posts_distinct' ) ) {
	function thim_course_filter_posts_distinct( $distinct ) {
		if ( ! $filter = thim_get_course_filter() ) {
			return $distinct;
		}
		$distinct = "DISTINCT";

		return $distinct;
	}
}
add_filter( 'posts_distinct', 'thim_course_filter_posts_distinct' );

/**
 * Course filter orderby
 */
if ( ! function_exists( 'thim_course_filter_orderby' ) ) {
	function thim_course_filter_orderby( $orderby ) {
		if ( ! $filter = thim_get_course_filter() ) {
			return $orderby;
		}
		if ( ! empty( $filter['orderby'] ) ) {
			switch ( $filter['orderby'] ) {
				case 'price':
					$orderby = str_replace( 'RAND(999999999)', 'CAST(price as DECIMAL(10,2))', $orderby );
					break;
				case 'rating':
					$orderby = str_replace( 'RAND(999999999)', 'CAST(rating as DECIMAL(10,2))', $orderby );
					break;
				case 'students':
					$orderby = str_replace( 'RAND(999999999)', 'CAST(students as UNSIGNED)', $orderby );
			}
		}

		return $orderby;
	}
}
add_filter( 'posts_orderby', 'thim_course_filter_orderby' );

/************************* END COURSES FILTER *****************************/


/**
 * Filter redirect plugin tp chameleon
 */
if ( ! function_exists( 'thim_tp_chameleon_redirect' ) ) {
	function thim_tp_chameleon_redirect( $option ) {
		if ( ( ! is_admin() && ! is_home() && ! is_front_page() ) || is_customize_preview() ) {
			return false;
		} else {
			return $option;
		}
	}
}
add_filter( 'tp_chameleon_redirect_iframe', 'thim_tp_chameleon_redirect' );

/**
 * Check is course
 */
if ( ! function_exists( 'thim_check_is_course' ) ) {
	function thim_check_is_course() {
		if ( function_exists( 'learn_press_is_courses' ) && learn_press_is_courses() ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Check is course taxonomy
 */
if ( ! function_exists( 'thim_check_is_course_taxonomy' ) ) {
	function thim_check_is_course_taxonomy() {
		if ( function_exists( 'learn_press_is_course_taxonomy' ) && learn_press_is_course_taxonomy() ) {
			return true;
		} else {
			return false;
		}
	}
}


/**
 * Filter image all-demo tp-chameleon
 */
if ( ! function_exists( 'thim_override_demo_image_tp_chameleon' ) ) {
	function thim_override_demo_image_tp_chameleon() {
		return THIM_URI . 'images/eduma-chameleon-gradschool3.png';
	}
}
add_filter( 'tp_chameleon_get_image_sprite_demos', 'thim_override_demo_image_tp_chameleon' );


/**
 * Remove redirect register url buddypress
 */
remove_filter( 'register_url', 'bp_get_signup_page' );
remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );

/**
 * Remove additional CSS
 */
if ( ! function_exists( 'thim_wp_get_custom_css' ) ) {
	function thim_wp_get_custom_css() {
		return false;
	}
}
add_filter( 'wp_get_custom_css', 'thim_wp_get_custom_css' );

/**
 * Remove vc hook that prevents upgrading from theme
 *
 * @return mixed
 */
if ( ! function_exists( 'thim_remove_vc_hooks' ) ) {
	function thim_remove_vc_hooks() {

		global $vc_manager;
		if ( ! $vc_manager ) {
			return false;
		}
		global $wp_filter;

		$tag = 'upgrader_pre_download';
		if ( empty( $wp_filter[ $tag ] ) ) {
			return false;
		}

		/**
		 * Since WP 4.7
		 */
		if ( $wp_filter[ $tag ] instanceof WP_Hook ) {
			if ( empty( $wp_filter[ $tag ]->callbacks ) ) {
				return false;
			}
			$hook        = &$wp_filter[ $tag ];
			$remove_keys = array();
			foreach ( $hook->callbacks as $priority => $filter ) {
				foreach ( $hook->callbacks[ $priority ] as $k => $v ) {
					$callback = $v['function'];
					if ( $callback[0] instanceof Vc_Updater && $callback[1] == 'preUpgradeFilter' ) {
						if ( empty( $remove_keys[ $priority ] ) ) {
							$remove_keys[ $priority ] = array();
						}
						$remove_keys[ $priority ][] = $k;
					}
				}
			}
			if ( $remove_keys ) {
				foreach ( $remove_keys as $priority => $keys ) {
					foreach ( $keys as $key ) {
						if ( ! empty( $hook->callbacks[ $priority ][ $key ] ) ) {
							unset( $hook->callbacks[ $priority ][ $key ] );
						}
						if ( array_key_exists( $priority, $hook->callbacks ) && empty( $hook->callbacks[ $priority ] ) ) {
							unset( $hook->callbacks[ $priority ] );
						}
					}
				}
			}

			return $wp_filter;
		}

		/**
		 * Backward compatibility for other version of WP
		 */
		return _thim_backward_remove_vc_hooks();
	}
}

/**
 * Backward compatibility remove vc hook for WP version less than 4.7
 */
if ( ! function_exists( '_thim_backward_remove_vc_hooks' ) ) {
	function _thim_backward_remove_vc_hooks() {
		global $wp_filter;
		$tag         = 'upgrader_pre_download';
		$remove_keys = array();

		foreach ( $wp_filter[ $tag ] as $priority => $filter ) {
			foreach ( $wp_filter[ $tag ][ $priority ] as $k => $v ) {
				$callback = $v['function'];
				if ( $callback[0] instanceof Vc_Updater && $callback[1] == 'preUpgradeFilter' ) {
					if ( empty( $remove_keys[ $priority ] ) ) {
						$remove_keys[ $priority ] = array();
					}
					$remove_keys[ $priority ][] = $k;
				}
			}
		}
		if ( $remove_keys ) {
			foreach ( $remove_keys as $priority => $keys ) {
				foreach ( $keys as $key ) {
					if ( ! empty( $wp_filter[ $tag ][ $priority ][ $key ] ) ) {
						unset( $wp_filter[ $tag ][ $priority ][ $key ] );
					}
					if ( array_key_exists( $priority, $wp_filter[ $tag ] ) && empty( $wp_filter[ $tag ][ $priority ] ) ) {
						unset( $wp_filter[ $tag ][ $priority ] );
					}
					if ( array_key_exists( $tag, $wp_filter ) && empty( $wp_filter[ $tag ] ) ) {
						unset( $wp_filter[ $tag ] );
					}
				}
			}
		}

		return $wp_filter;
	}
}
add_action( 'vc_before_mapping', 'thim_remove_vc_hooks' );

/**
 * Add excerpt field to page
 */
if ( ! function_exists( 'thim_update_page_features' ) ) {
	function thim_update_page_features() {
		add_post_type_support( 'page', 'excerpt' );
	}
}
add_action( 'init', 'thim_update_page_features', 100 );


/**
 * Add google analytics & facebook pixel code
 */
if ( ! function_exists( 'thim_add_marketing_code' ) ) {
	function thim_add_marketing_code() {
		$theme_options_data = get_theme_mods();
		if ( ! empty( $theme_options_data['thim_google_analytics'] ) ) {
			?>
			<script>
                (function(i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function() {
                        (i[r].q = i[r].q || []).push(arguments);
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m);
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

                ga('create', '<?php echo esc_html( $theme_options_data['thim_google_analytics'] ); ?>', 'auto');
                ga('send', 'pageview');
			</script>
			<?php
		}
		if ( ! empty( $theme_options_data['thim_facebook_pixel'] ) ) {
			?>
			<script>
                !function(f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function() {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s);
                }(window, document, 'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '<?php echo esc_html( $theme_options_data['thim_facebook_pixel'] ); ?>');
                fbq('track', 'PageView');
			</script>
			<noscript>
				<img height="1" width="1" style="display:none"
				     src="https://www.facebook.com/tr?id=<?php echo esc_attr( $theme_options_data['thim_facebook_pixel'] ); ?>&ev=PageView&noscript=1" />
			</noscript>
			<?php
		}
	}
}
add_action( 'wp_footer', 'thim_add_marketing_code' );


/**
 * Filter add to cart message
 */
add_filter( 'wc_add_to_cart_message_html', 'thim_add_to_cart_message', 10, 2 );
if ( ! function_exists( 'thim_add_to_cart_message' ) ) {
	function thim_add_to_cart_message( $message, $product_id ) {
		$course_id = 0;
		if ( is_array( $product_id ) ) {
			$keys      = array_keys( $product_id );
			$course_id = $keys[0];
		} else {
			$course_id = $product_id;
		}
		$title = get_the_title( $course_id );
		if ( ! empty( $title ) ) {
			$added_text = sprintf( '%s %s', $title, esc_html__( 'has been added to your cart.', 'eduma' ) );

			// Output success messages
			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				$return_to = apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_raw_referer() ? wp_validate_redirect( wc_get_raw_referer(), false ) : wc_get_page_permalink( 'shop' ) );
				$message   = sprintf( '<a href="%s" class="button wc-forward">%s</a> <span>%s</span>', esc_url( $return_to ), esc_html__( 'Continue Shopping', 'eduma' ), esc_html( $added_text ) );
			} else {
				$message = sprintf( '<a href="%s" class="button wc-forward">%s</a> <span>%s</span>', esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View Cart', 'eduma' ), esc_html( $added_text ) );
			}
		}

		return $message;
	}
}

/**
 * Set login cookie
 *
 * @param $logged_in_cookie
 * @param $expire
 * @param $expiration
 * @param $user_id
 * @param $logged_in
 */
function thim_set_logged_in_cookie( $logged_in_cookie, $expire, $expiration, $user_id, $logged_in ) {
	if ( $logged_in == 'logged_in' ) {
		// Hack for wp checking empty($_COOKIE[LOGGED_IN_COOKIE]) after user logged in
		// in "private mode". $_COOKIE is not set after calling setcookie util the response
		// is sent back to client (do not know why in "private mode").
		// @see wp-login.php line #789
		$_COOKIE[ LOGGED_IN_COOKIE ] = $logged_in_cookie;
	}
}

add_action( 'set_logged_in_cookie', 'thim_set_logged_in_cookie', 100, 5 );

/**
 * Filter map single event 2.0
 */
if ( ! function_exists( 'thim_filter_event_map' ) ) {
	function thim_filter_event_map( $map_data ) {
		$map_data['height']                  = '210px';
		$map_data['map_data']['scroll-zoom'] = false;
		$map_data['map_data']['marker-icon'] = get_template_directory_uri() . '/images/map_icon.png';

		return $map_data;
	}
}
add_filter( 'tp_event_filter_event_location_map', 'thim_filter_event_map' );

/**
 * Get prefix for page title
 */
if ( ! function_exists( 'thim_get_prefix_page_title' ) ) {
	function thim_get_prefix_page_title() {

		if ( is_tax() ) {
			$queried_object = get_queried_object();

			if ( $queried_object->taxonomy == "product_cat" ) {
				$prefix = 'thim_woo';
			} elseif ( $queried_object->taxonomy == 'course_category' ) {
				$prefix = 'thim_learnpress';
			} elseif ( $queried_object->taxonomy == 'tp_event_category' ) {
				$prefix = 'thim_event';
			} elseif ( $queried_object->taxonomy == 'our_team_category' ) {
				$prefix = 'thim_team';
			} else {
				$prefix = 'thim_archive';
			}
		} else {
			if ( get_post_type() == "product" ) {
				$prefix = 'thim_woo';
			} elseif ( get_post_type() == "lpr_course" || get_post_type() == "lpr_quiz" || get_post_type() == "lp_course" || get_post_type() == "lp_quiz" || thim_check_is_course() || thim_check_is_course_taxonomy() ) {
				$prefix = 'thim_learnpress';
			} elseif ( get_post_type() == "lp_collection" ) {
				$prefix = 'thim_collection';
			} elseif ( get_post_type() == "tp_event" ) {
				$prefix = 'thim_event';
			} elseif ( get_post_type() == "our_team" ) {
				$prefix = 'thim_team';
			} elseif ( get_post_type() == "testimonials" ) {
				$prefix = 'thim_testimonials';
			} elseif ( get_post_type() == "portfolio" ) {
				$prefix = 'thim_portfolio';
			} elseif ( get_post_type() == "forum" ) {
				$prefix = 'thim_forum';
			} elseif ( is_front_page() || is_home() ) {
				$prefix = 'thim';
			} else {
				$prefix = 'thim_archive';
			}
		}

		return $prefix;
	}
}

/**
 * Get prefix inner for page title
 */
if ( ! function_exists( 'thim_get_prefix_inner_page_title' ) ) {
	function thim_get_prefix_inner_page_title() {
		if ( is_page() || is_single() ) {
			$prefix_inner = '_single_';
			if ( is_page() && get_post_type() == "portfolio" ) {
				$prefix_inner = '_cate_';
			}
		} else {
			if ( is_front_page() || is_home() ) {
				$prefix_inner = '_front_page_';
			} else {
				$prefix_inner = '_cate_';
				if ( get_post_type() == "lp_collection" ) {
					$prefix_inner = '_single_';
				}
			}
		}

		return $prefix_inner;
	}
}

/**
 * Print breadcrumbs
 */
if ( ! function_exists( 'thim_print_breadcrumbs' ) ) {
	function thim_print_breadcrumbs() {
		?>
		<div class="breadcrumbs-wrapper">
			<div class="container">
				<?php
				//Check seo by yoast breadcrumbs
				$wpseo = get_option( 'wpseo_internallinks' );
				if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) && $wpseo['breadcrumbs-enable'] ) {
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<ul id="breadcrumbs">', '</ul>' );
					}
				} else {
					if ( get_post_type() == 'product' ) {
						woocommerce_breadcrumb();
					} elseif ( get_post_type() == 'lpr_course' || get_post_type() == 'lpr_quiz' || get_post_type() == 'lp_course' || get_post_type() == 'lp_quiz' || thim_check_is_course() || thim_check_is_course_taxonomy() ) {
						thim_learnpress_breadcrumb();
					} elseif ( get_post_type() == 'lp_collection' ) {
						thim_courses_collection_breadcrumb();
					} elseif ( thim_use_bbpress() ) {
						bbp_breadcrumb();
					} else {
						thim_breadcrumbs();
					}
				}

				?>
			</div>
		</div>
		<?php
	}
}

/**
 * Get page title
 */
if ( ! function_exists( 'thim_get_page_title' ) ) {
	function thim_get_page_title( $custom_title, $front_title ) {
		$heading_title = esc_html__( 'Page title', 'eduma' );
		if ( get_post_type() == 'product' ) {
			if ( ! empty( $custom_title ) ) {
				$heading_title = $custom_title;
			} else {
				$heading_title = woocommerce_page_title( false );
			}
		} elseif ( get_post_type() == 'lpr_course' || get_post_type() == 'lpr_quiz' || get_post_type() == 'lp_course' || get_post_type() == 'lp_quiz' || thim_check_is_course() || thim_check_is_course_taxonomy() ) {
			if ( is_single() ) {
				if ( ! empty( $custom_title ) ) {
					$heading_title = $custom_title;
				} else {
					$course_cat = get_the_terms( get_the_ID(), 'course_category' );
					if ( ! empty( $course_cat ) ) {
						$heading_title = $course_cat[0]->name;
					} else {
						$heading_title = __( 'Course', 'eduma' );
					}
				}
			} else {
				$heading_title = thim_learnpress_page_title( false );
			}
		} elseif ( get_post_type() == 'lp_collection' ) {
			$heading_title = learn_press_collections_page_title( false );
		} elseif ( ( is_category() || is_archive() || is_search() || is_404() ) && ! thim_use_bbpress() ) {
			if ( get_post_type() == 'tp_event' ) {
				$heading_title = esc_html__( 'Events', 'eduma' );
			} elseif ( get_post_type() == 'our_team' ) {
				$heading_title = esc_html__( 'Our Team', 'eduma' );
			} else {
				$heading_title = thim_archive_title();
			}
		} elseif ( thim_use_bbpress() ) {
			$heading_title = thim_forum_title();
		} elseif ( is_page() || is_single() ) {
			if ( is_single() ) {
				if ( $custom_title ) {
					$heading_title = $custom_title;
				} else {
					$heading_title = get_the_title();
					if ( get_post_type() == 'post' ) {
						$category = get_the_category();
						if ( $category ) {
							//$category_id   = get_cat_ID( $category[0]->cat_name );
							$heading_title = $category[0]->cat_name;
						} else {
							$heading_title = get_the_title();
						}
					}
					if ( get_post_type() == 'tp_event' ) {
						$heading_title = esc_html__( 'Events', 'eduma' );
					}
					if ( get_post_type() == 'portfolio' ) {
						//$heading_title = esc_html__( 'Portfolio', 'eduma' );
					}
					if ( get_post_type() == 'our_team' ) {
						$heading_title = esc_html__( 'Our Team', 'eduma' );
					}
					if ( get_post_type() == 'testimonials' ) {
						$heading_title = esc_html__( 'Testimonials', 'eduma' );
					}
				}

			} else {
				$heading_title = ! empty( $custom_title ) ? $custom_title : get_the_title();
			}
		} elseif ( ! is_front_page() && is_home() ) {
			$heading_title = ! empty( $front_title ) ? $front_title : esc_html__( 'Blog', 'eduma' );;
		}

		return $heading_title;
	}
}


/**
 * Function print preload
 */
if ( ! function_exists( 'thim_print_preload' ) ) {
	function thim_print_preload() {
		$enable_preload     = get_theme_mod( 'thim_preload', 'default' );
		$thim_preload_image = get_theme_mod( 'thim_preload_image', false );
		$item_only          = ! empty( $_REQUEST['content-item-only'] ) ? $_REQUEST['content-item-only'] : false;
		if ( ! empty( $enable_preload ) && empty( $item_only ) ) { ?>
			<div id="preload">
				<?php
				if ( $thim_preload_image && $enable_preload == 'image' ) {
					if ( is_numeric( $thim_preload_image ) ) {
						echo wp_get_attachment_image( $thim_preload_image, 'full' );
					} else {
						echo '<img src="' . $thim_preload_image . '" alt="' . esc_html__( 'Preaload Image', 'eduma' ) . '"/>';
					}
				} else {
					switch ( $enable_preload ) {
						case 'style_1':
							$output_preload = '<div class="cssload-loader-style-1">
													<div class="cssload-inner cssload-one"></div>
													<div class="cssload-inner cssload-two"></div>
													<div class="cssload-inner cssload-three"></div>
												</div>';
							break;
						case 'style_2':
							$output_preload = '<div class="cssload-loader-style-2">
												<div class="cssload-loader-inner"></div>
											</div>';
							break;
						case 'style_3':
							$output_preload = '<div class="sk-folding-cube">
												<div class="sk-cube1 sk-cube"></div>
												<div class="sk-cube2 sk-cube"></div>
												<div class="sk-cube4 sk-cube"></div>
												<div class="sk-cube3 sk-cube"></div>
											</div>';
							break;
						case 'wave':
							$output_preload = '<div class="sk-wave">
										        <div class="sk-rect sk-rect1"></div>
										        <div class="sk-rect sk-rect2"></div>
										        <div class="sk-rect sk-rect3"></div>
										        <div class="sk-rect sk-rect4"></div>
										        <div class="sk-rect sk-rect5"></div>
										      </div>';
							break;
						case 'rotating-plane':
							$output_preload = '<div class="sk-rotating-plane"></div>';
							break;
						case 'double-bounce':
							$output_preload = '<div class="sk-double-bounce">
										        <div class="sk-child sk-double-bounce1"></div>
										        <div class="sk-child sk-double-bounce2"></div>
										      </div>';
							break;
						case 'wandering-cubes':
							$output_preload = '<div class="sk-wandering-cubes">
										        <div class="sk-cube sk-cube1"></div>
										        <div class="sk-cube sk-cube2"></div>
										      </div>';
							break;
						case 'spinner-pulse':
							$output_preload = '<div class="sk-spinner sk-spinner-pulse"></div>';
							break;
						case 'chasing-dots':
							$output_preload = '<div class="sk-chasing-dots">
										        <div class="sk-child sk-dot1"></div>
										        <div class="sk-child sk-dot2"></div>
										      </div>';
							break;
						case 'three-bounce':
							$output_preload = '<div class="sk-three-bounce">
										        <div class="sk-child sk-bounce1"></div>
										        <div class="sk-child sk-bounce2"></div>
										        <div class="sk-child sk-bounce3"></div>
										      </div>';
							break;
						case 'cube-grid':
							$output_preload = '<div class="sk-cube-grid">
										        <div class="sk-cube sk-cube1"></div>
										        <div class="sk-cube sk-cube2"></div>
										        <div class="sk-cube sk-cube3"></div>
										        <div class="sk-cube sk-cube4"></div>
										        <div class="sk-cube sk-cube5"></div>
										        <div class="sk-cube sk-cube6"></div>
										        <div class="sk-cube sk-cube7"></div>
										        <div class="sk-cube sk-cube8"></div>
										        <div class="sk-cube sk-cube9"></div>
										      </div>';
							break;
						default:
							$output_preload = '<div class="sk-folding-cube">
												<div class="sk-cube1 sk-cube"></div>
												<div class="sk-cube2 sk-cube"></div>
												<div class="sk-cube4 sk-cube"></div>
												<div class="sk-cube3 sk-cube"></div>
											</div>';
					}
					echo ent2ncr( $output_preload );
				}
				?>
			</div>
		<?php }
	}
}
add_action( 'thim_before_body', 'thim_print_preload' );

/**
 * Function add h1 tag
 */
if ( ! function_exists( 'thim_add_tag_h1' ) ) {
	function thim_add_tag_h1() {
		if ( is_front_page() ) {
			echo '<h1 class="title">' . get_bloginfo( 'name' ) . '</h1>';
		}
	}
}
add_action( 'thim_before_body', 'thim_add_tag_h1' );

/**
 * Echo header class
 */
if ( ! function_exists( 'thim_header_class' ) ) {
	function thim_header_class() {
		$header_class = '';
		if ( get_theme_mod( 'thim_config_att_sticky', 'sticky_same' ) == 'sticky_custom' ) {
			$header_class .= ' bg-custom-sticky';
		}
		if ( get_theme_mod( 'thim_header_sticky', false ) && ! ( is_singular( 'lpr_course' ) || is_singular( 'lp_course' ) ) ) {
			$header_class .= ' sticky-header';
		}
		if ( get_theme_mod( 'thim_header_position', 'header_overlay' ) == 'header_default' ) {
			$header_class .= ' header_default';
		} else {
			$header_class .= ' header_overlay';
		}
		if ( get_theme_mod( 'thim_header_style', 'header_v1' ) ) {
			$header_class .= ' ' . get_theme_mod( 'thim_header_style', 'header_v1' );
		}
		if ( get_theme_mod( 'thim_config_logo_mobile', 'default_logo' ) == 'custom_logo' ) {
			$header_class .= ' mobile-logo-custom';
		}
		echo esc_attr( $header_class );
	}
}

/**
 * Footer Bottom
 */
if ( ! function_exists( 'thim_footer_bottom' ) ) {
	function thim_footer_bottom() {
		if ( ( is_active_sidebar( 'footer_bottom' ) ) ) {
			?>
			<div class="footer-bottom">

				<div class="container">
					<?php dynamic_sidebar( 'footer_bottom' ); ?>
				</div>

			</div>
		<?php }
	}
}
add_action( 'thim_end_content_pusher', 'thim_footer_bottom' );

if ( ! function_exists( 'thim_above_footer_area_fnc' ) ) {
	function thim_above_footer_area_fnc() {
		if ( is_active_sidebar( 'footer_top' ) ) {
			?>
			<div class="footer-bottom-above">

				<div class="container">
					<?php dynamic_sidebar( 'footer_top' ); ?>
				</div>

			</div>
			<?php
		}
	}
}
add_action( 'thim_above_footer_area', 'thim_above_footer_area_fnc' );

/**
 * Back to top
 */
if ( ! function_exists( 'thim_back_to_top' ) ) {
	function thim_back_to_top() {
		if ( get_theme_mod( 'thim_show_to_top', false ) ) { ?>
			<a href="#" id="back-to-top">
				<i class="fa fa-chevron-up" aria-hidden="true"></i>
			</a>
			<?php
		}
	}
}
add_action( 'thim_end_wrapper_container', 'thim_back_to_top' );

/**
 * Copyright Area
 */
if ( ! function_exists( 'thim_print_copyright' ) ) {
	function thim_print_copyright() {
		$theme_mods          = get_theme_mods();
		$copyright_text      = isset( $theme_mods['thim_copyright_text'] ) ? $theme_mods['thim_copyright_text'] : '';
		$display_copyright   = ( ! isset( $theme_mods['thim_copyright_text'] ) || ! empty( $theme_mods['thim_copyright_text'] ) ) ? true : false;
		$is_active_copyright = is_active_sidebar( 'copyright' );
		if ( $display_copyright || $is_active_copyright ) { ?>
			<div class="copyright-area">
				<div class="container">
					<div class="copyright-content">
						<div class="row">
							<?php
							$class_copyright = $is_active_copyright ? 'col-sm-6' : 'col-sm-12';
							echo '<div class="' . $class_copyright . '"><p class="text-copyright">' . $copyright_text . '</p></div>';
							if ( $is_active_copyright ) : ?>
								<div class="col-sm-6 text-right">
									<?php dynamic_sidebar( 'copyright' ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php }
	}
}
add_action( 'thim_copyright_area', 'thim_print_copyright' );

/**
 * Footer Class
 */
if ( ! function_exists( 'thim_footer_class' ) ) {
	function thim_footer_class() {
		$theme_options_data = get_theme_mods();
		$style_content      = isset( $theme_options_data['thim_layout_content_page'] ) ? $theme_options_data['thim_layout_content_page'] : 'normal';
		$style_header       = isset( $theme_options_data['thim_header_style'] ) ? $theme_options_data['thim_header_style'] : 'header_v1';
		$custom_class       = get_theme_mod( 'thim_footer_custom_class', '' ) . ' site-footer';
		$footer_bg_image    = get_theme_mod( 'thim_footer_background_img', '' );
		$custom_class       .= ! empty( $footer_bg_image ) ? ' footer-bg-image' : '';
		$footer_class       = ( ( is_active_sidebar( 'footer_bottom' ) && $style_content != 'new-1' ) || ( is_active_sidebar( 'footer_bottom' ) && $style_header != 'header_v4' ) ) ? $custom_class . ' has-footer-bottom' : $custom_class;


		echo esc_attr( $footer_class );
	}
}


/**
 * Check and update term meta for tax meta class
 */
if ( ! get_option( 'thim_update_tax_meta', false ) ) {
	global $wpdb;
	$querystr      = "
	    SELECT option_name, option_value
	    FROM $wpdb->options
	    WHERE $wpdb->options.option_name LIKE 'tax_meta_%'
	 ";
	$list_tax_meta = $wpdb->get_results( $querystr );

	if ( ! empty( $list_tax_meta ) ) {
		foreach ( $list_tax_meta as $tax_meta ) {
			$term_id   = str_replace( 'tax_meta_', '', $tax_meta->option_name );
			$term_meta = unserialize( $tax_meta->option_value );
			if ( is_array( $term_meta ) && ! empty( $term_meta ) ) {
				foreach ( $term_meta as $key => $value ) {
					if ( is_array( $value ) ) {
						if ( ! empty( $value['src'] ) ) {
							$value['url'] = $value['src'];
							unset( $value['src'] );
						}
					}
					update_term_meta( $term_id, $key, $value );
				}
			}
		}
	}
	update_option( 'thim_update_tax_meta', '1' );
}

/**
 * Filter demos path
 */
function thim_filter_site_demos( $demo_datas ) {
	$demo_data_file_path = get_template_directory() . '/inc/data/demos.php';
	if ( is_file( $demo_data_file_path ) ) {
		require $demo_data_file_path;
	}

	return $demo_datas;
}

add_filter( 'tp_chameleon_get_site_demos', 'thim_filter_site_demos' );


/**
 * Check import demo data page-builder
 */
add_action( 'wp_ajax_thim_update_theme_mods', 'thim_import_demo_page_builder' );
if ( ! function_exists( 'thim_import_demo_page_builder' ) ) {
	function thim_import_demo_page_builder() {
		$thim_key   = sanitize_text_field( $_POST["thim_key"] );
		$thim_value = sanitize_text_field( $_POST["thim_value"] );

		if ( ! is_multisite() ) {
			$active_plugins = get_option( 'active_plugins' );

			if ( $thim_value == 'visual_composer' ) {
				if ( $site_origin = array_search( 'siteorigin-panels/siteorigin-panels.php', $active_plugins ) ) {
					unset( $active_plugins[ $site_origin ] );
				}

				if ( $elementor = array_search( 'elementor/elementor.php', $active_plugins ) ) {
					unset( $active_plugins[ $elementor ] );
				}

				if ( ! in_array( 'js_composer/js_composer.php', $active_plugins ) ) {
					$active_plugins[] = 'js_composer/js_composer.php';
				}
			} else if ( $thim_value == 'site_origin' ) {
				if ( $visual_composer = array_search( 'js_composer/js_composer.php', $active_plugins ) ) {
					unset( $active_plugins[ $visual_composer ] );
				}

				if ( $elementor = array_search( 'elementor/elementor.php', $active_plugins ) ) {
					unset( $active_plugins[ $elementor ] );
				}

				if ( ! in_array( 'siteorigin-panels/siteorigin-panels.php', $active_plugins ) ) {
					$active_plugins[] = 'siteorigin-panels/siteorigin-panels.php';
				}
			} else if ( $thim_value == 'elementor' ) {
				if ( $visual_composer = array_search( 'js_composer/js_composer.php', $active_plugins ) ) {
					unset( $active_plugins[ $visual_composer ] );
				}

				if ( $site_origin = array_search( 'siteorigin-panels/siteorigin-panels.php', $active_plugins ) ) {
					unset( $active_plugins[ $site_origin ] );
				}

				if ( ! in_array( 'elementor/elementor.php', $active_plugins ) ) {
					$active_plugins[] = 'elementor/elementor.php';
				}
			}

			update_option( 'active_plugins', $active_plugins );
		}

		if ( empty( $thim_key ) || empty( $thim_value ) ) {
			$output = 'update fail';
		} else {
			set_theme_mod( $thim_key, $thim_value );
			$output = 'update success';
		}

		echo ent2ncr( $output );
		die();
	}
}

/**
 * @param $settings
 *
 * @return array
 */
if ( ! function_exists( 'thim_import_add_basic_settings' ) ) {
	function thim_import_add_basic_settings( $settings ) {
		$settings[] = 'learn_press_archive_course_limit';
		$settings[] = 'siteorigin_panels_settings';
		//$settings[] = 'wpb_js_margin';
		//$settings[] = 'users_can_register';
		//$settings[] = 'permalink_structure';
		//$settings[] = 'wpb_js_use_custom';

		// Elementor global settings
		$settings[] = 'elementor_container_width';
		$settings[] = 'elementor_space_between_widgets';

		return $settings;
	}
}
add_filter( 'thim_importer_basic_settings', 'thim_import_add_basic_settings' );

/**
 * @param $settings
 *
 * @return array
 */
if ( ! function_exists( 'thim_import_add_page_id_settings' ) ) {
	function thim_import_add_page_id_settings( $settings ) {
		$settings[] = 'learn_press_courses_page_id';
		$settings[] = 'learn_press_profile_page_id';

		return $settings;
	}
}
add_filter( 'thim_importer_page_id_settings', 'thim_import_add_page_id_settings' );


//Add info for Dashboard Admin
if ( ! function_exists( 'thim_eduma_links_guide_user' ) ) {
	function thim_eduma_links_guide_user() {
		return array(
			'docs'      => 'http://docspress.thimpress.com/eduma/',
			'support'   => 'https://thimpress.com/forums/forum/eduma/',
			'knowledge' => 'https://thimpress.com/knowledge-base/',
		);
	}
}
add_filter( 'thim_theme_links_guide_user', 'thim_eduma_links_guide_user' );

/**
 * Link purchase theme.
 */
if ( ! function_exists( 'thim_eduma_link_purchase' ) ) {
	function thim_eduma_link_purchase() {
		return 'https://themeforest.net/item/education-wordpress-theme-education-wp/14058034?ref=thimpress&utm_source=thimcoredashboard&utm_medium=dashboard';
	}
}
add_filter( 'thim_envato_link_purchase', 'thim_eduma_link_purchase' );

/**
 * Envato id.
 */
if ( ! function_exists( 'thim_eduma_envato_item_id' ) ) {
	function thim_eduma_envato_item_id() {
		return '14058034';
	}
}
add_filter( 'thim_envato_item_id', 'thim_eduma_envato_item_id' );

/**
 * Arguments form subscribe.
 */
if ( ! function_exists( 'thim_eduma_args_form_subscribe' ) ) {
	function thim_eduma_args_form_subscribe() {
		return array(
			'user' => 'e514ab4788b7083cb36eed163',
			'form' => '1beedf87e5',
		);
	}
}
add_filter( 'thim_core_args_form_subscribe', 'thim_eduma_args_form_subscribe' );

/**
 * Default stylesheet uri.
 */
if ( ! function_exists( 'thim_eduma_style_default_uri' ) ) {
	function thim_eduma_style_default_uri() {
		return trailingslashit( get_template_directory_uri() ) . 'inc/data/default.css';
	}
}
add_filter( 'thim_style_default_uri', 'thim_eduma_style_default_uri' );

/**
 * Field name custom css theme mods.
 */
if ( ! function_exists( 'thim_eduma_field_name_custom_css_theme' ) ) {
	function thim_eduma_field_name_custom_css_theme() {
		return 'thim_custom_css';
	}
}
add_filter( 'thim_core_field_name_custom_css_theme', 'thim_eduma_field_name_custom_css_theme' );

/**
 * Waring do not re-activate Thim Framework.
 */
function thim_notify_do_not_re_active_thim_framework() {
	if ( class_exists( 'Thim_Notification' ) ) {
		$detect_upgraded = get_option( 'thim_auto_updated_theme_mods_30', false );

		if ( ! $detect_upgraded ) {
			return;
		}

		$link_delete = network_admin_url( 'plugins.php?plugin_status=inactive' );

		Thim_Notification::add_notification(
			array(
				'id'          => 'do_not_support_thim_framework',
				'type'        => 'warning',
				'content'     => sprintf( __( 'Thim Core plugin is the newest upgrade version of Thim Framework. <strong>Do not re-activate Thim Framework and <a href="%s" title="Delete Thim Framework plugin">better delete this plugin</a></strong>.', 'eduma' ), $link_delete ),
				'dismissible' => true,
				'global'      => true,
			)
		);
	}
}

add_action( 'admin_init', 'thim_notify_do_not_re_active_thim_framework' );

/**
 * Notify fix error theme mods.
 */
function thim_notify_fix_update_failed() {
	if ( class_exists( 'Thim_Notification' ) ) {
		$detect = get_option( 'thim_eduma_fix_theme_mods_broken', false );

		if ( $detect ) {
			return;
		}

		$version = get_option( 'thim_eduma_version' );
		if ( ! empty( $version ) ) {
			if ( version_compare( $version, THIM_THEME_VERSION, '>=' ) ) {
				return;
			}
		}

		$link = admin_url( '?thim_eduma_fix_theme_mods_broken=1' );

		Thim_Notification::add_notification(
			array(
				'id'          => 'eduma_fix_update',
				'type'        => 'warning',
				'content'     => sprintf( __( '<h3>Notice</h3>If you have troubles with saving customize after update the theme, <a href="%s">please click here to fix it</a>. If not, ignore this message.', 'eduma' ), $link ),
				'dismissible' => true,
				'global'      => true,
			)
		);
	}
}

add_action( 'admin_init', 'thim_notify_fix_update_failed' );

/**
 * Only for developer.
 */
add_action( 'admin_init', 'thim_eduma_fix_theme_mods_broken' );
function thim_eduma_fix_theme_mods_broken() {
	$request = isset( $_GET['thim_eduma_fix_theme_mods_broken'] );
	if ( ! $request ) {
		return;
	}

	$thim_font_body = array(
		'font-family' => 'Roboto',
		'variant'     => 'normal',
		'font-size'   => '15px',
		'line-height' => '1.7em',
		'color'       => '#666'
	);

	set_theme_mod( 'thim_font_body', $thim_font_body );

	$thim_font_title = array(
		'font-family' => 'Roboto Slab',
		'variant'     => '700',
		'color'       => '#333333'
	);

	set_theme_mod( 'thim_font_title', $thim_font_title );

	$thim_font_h1 = array(
		'font-size'   => '36px',
		'line-height' => '1.6em',
	);

	set_theme_mod( 'thim_font_h1', $thim_font_h1 );

	$thim_font_h2 = array(
		'font-size'   => '28px',
		'line-height' => '1.6em',
	);

	set_theme_mod( 'thim_font_h2', $thim_font_h2 );

	$thim_font_h3 = array(
		'font-size'   => '24px',
		'line-height' => '1.6em',
	);

	set_theme_mod( 'thim_font_h3', $thim_font_h3 );

	$thim_font_h4 = array(
		'font-size'   => '18px',
		'line-height' => '1.6em',
	);

	set_theme_mod( 'thim_font_h4', $thim_font_h4 );

	$thim_font_h5 = array(
		'font-size'   => '16px',
		'line-height' => '1.6em',
	);

	set_theme_mod( 'thim_font_h5', $thim_font_h5 );

	$thim_font_h6 = array(
		'font-size'   => '16px',
		'line-height' => '1.4em',
	);

	set_theme_mod( 'thim_font_h6', $thim_font_h6 );

	update_option( 'thim_eduma_fix_theme_mods_broken', true );
	if ( ! headers_sent() ) {
		wp_redirect( admin_url( 'customize.php' ) );
	}
}


function thim_eduma_register_meta_boxes_portfolio( $meta_boxes ) {
	$prefix       = 'thim_';
	$meta_boxes[] = array(
		'id'         => 'portfolio_bg_color',
		'title'      => __( 'Portfolio Meta', 'eduma' ),
		'post_types' => 'portfolio',
		'fields'     => array(
			array(
				'name' => __( 'Background Color', 'eduma' ),
				'id'   => $prefix . 'portfolio_bg_color_ef',
				'type' => 'color',
			),
		)
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'thim_eduma_register_meta_boxes_portfolio' );

function thim_eduma_register_meta_boxes_post( $meta_boxes ) {
	$prefix       = 'thim_';
	$meta_boxes[] = array(
		'id'         => 'post_gallery',
		'title'      => __( 'Post Layout', 'eduma' ),
		'post_types' => 'post',
		'fields'     => array(
			array(
				'name'    => __( 'Layout Grid', 'eduma' ),
				'id'      => $prefix . 'post_gallery_layout',
				'type'    => 'select',
				'options' => array(
					'size11' => "Size 1x1(225 x 225)",
					'size32' => "Size 3x2(900 x 450)",
					'size22' => "Size 2x2(450 x 450)"
				),
			),
		)
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'thim_eduma_register_meta_boxes_post' );

function thim_eduma_after_switch_theme() {
	update_option( 'thim_eduma_version', THIM_THEME_VERSION );
}

add_action( 'after_switch_theme', 'thim_eduma_after_switch_theme' );

//add icon for level membership
if ( thim_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php' ) ) {
	add_action( 'pmpro_membership_level_after_other_settings', 'thim_add_icon_package_membership', 11, 1 );
	function thim_add_icon_package_membership() {
		$val = get_option( 'thim_level_' . $_GET['edit'] ) ? get_option( 'thim_level_' . $_GET['edit'] ) : '';
		?>
		<table class="form-table">
			<tbody>
			<tr class="membership_categories">
				<th scope="row" valign="top"><label><?php _e( 'Select Icon ', 'eduma' ); ?>:</label></th>
				<td>
					<input type="text" name="image_level" id="image_level" size="30" value="<?php echo $val; ?>">
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}

	add_action( 'pmpro_save_membership_level', 'thim_save_icon_package_membership', 10, 1 );
	function thim_save_icon_package_membership( $level_id ) {
		$img = isset( $_POST['image_level'] ) ? $_POST['image_level'] : '';
		if ( get_option( 'thim_level_' . $level_id ) !== false ) {
			update_option( 'thim_level_' . $level_id, $img );
		} else {
			add_option( 'thim_level_' . $level_id, $img );
		}
	}
}

/*
 * Check is Maintenance Mode
 */
/*
if( !function_exists( 'thim_is_maintenance_mode' ) ) {
    function thim_is_maintenance_mode() {
        $maintenance_mode = false;
        $theme_options_data = get_theme_mods();
        $day = ( isset($theme_options_data['thim_maintenance_date']) && $theme_options_data['thim_maintenance_date'] != '' ) ? (int) ( $theme_options_data['thim_maintenance_date'] ) : date( "d", time() );
        $month = ( isset($theme_options_data['thim_maintenance_month']) && $theme_options_data['thim_maintenance_month'] != '' ) ? (int) ( $theme_options_data['thim_maintenance_month'] ) : date( "m", time() );
        $year = ( isset($theme_options_data['thim_maintenance_year']) && $theme_options_data['thim_maintenance_year'] != '' ) ? (int) ( $theme_options_data['thim_maintenance_year'] ) : date( "Y", time() );
        $hour = ( isset($theme_options_data['thim_maintenance_hour']) && $theme_options_data['thim_maintenance_hour'] != '' ) ? (int) ( $theme_options_data['thim_maintenance_hour'] ) : date( "G", time() );
        $date = $year . '/' . $month . '/' . $day;
        if( ( isset( $theme_options_data['thim_maintenance_show'] ) && $theme_options_data['thim_maintenance_show'] == true && strtotime($date) > strtotime(date('Y/n/d G:00')) && !is_user_logged_in() ) || is_page_template( 'page-templates/maintenance.php' ) ) {
            $maintenance_mode = true;
        }
        return $maintenance_mode;
    }
}
*/

/*
 * Filter list font Flaticon
 */
if ( ! function_exists( 'thim_get_list_font_flaticon' ) ) {
	function thim_get_list_font_flaticon( $icon ) {
		$new_icon = array(
			'school-material',
			'book',
			'blackboard',
			'mortarboard',
			'science-1',
			'apple',
			'idea',
			'books-1',
			'pencil-case',
			'medal',
			'library',
			'open-book',
			'microscope-1',
			'microscope',
			'notebook',
			'drawing',
			'diploma',
			'online',
			'technology-2',
			'internet',
			'technology-1',
			'school',
			'book-1',
			'technology',
			'education',
			'homework',
			'code',
			'login',
			'notes',
			'learning-2',
			'search',
			'learning-1',
			'statistics',
			'test',
			'learning',
			'study',
			'basketball-player',
			'biology',
			'students',
			'diploma-1',
			'books',
			'networking',
			'teacher',
			'graduate',
			'reading',
			'online-learning',
			'innovation',
			'research',
			'geography',
			'science',
		);

		return $new_icon;
	}
}
add_filter( 'thim_core_widget_flat_icons', 'thim_get_list_font_flaticon' );

if ( ! function_exists( 'thim_time_ago' ) ) {
	function thim_time_ago( $time ) {
		$periods = array(
			esc_html__( 'second', 'eduma' ),
			esc_html__( 'minute', 'eduma' ),
			esc_html__( 'hour', 'eduma' ),
			esc_html__( 'day', 'eduma' ),
			esc_html__( 'week', 'eduma' ),
			esc_html__( 'month', 'eduma' ),
			esc_html__( 'year', 'eduma' ),
			esc_html__( 'decade', 'eduma' ),
		);
		$lengths = array(
			'60',
			'60',
			'24',
			'7',
			'4.35',
			'12',
			'10'
		);


		$now = time();

		$difference = $now - $time;
		$tense      = esc_html__( 'ago', 'eduma' );

		for ( $j = 0; $difference >= $lengths[ $j ] && $j < count( $lengths ) - 1; $j ++ ) {
			$difference /= $lengths[ $j ];
		}

		$difference = round( $difference );

		if ( $difference != 1 ) {
			$periods[ $j ] .= "s";
		}

		return "$difference $periods[$j] $tense";
	}
}

/*
 * Display an author bio excerpt
 *
 * */
if ( ! function_exists( 'thim_author_bio_excerpt' ) ) {
	function thim_author_bio_excerpt( $author_id, $word_limit = 16, $text_end = '...' ) {
		$content_arr = explode( " ", get_the_author_meta( 'description', $author_id ) );

		$end_line = count( $content_arr ) > $word_limit ? $text_end : '';

		$author_des = array_slice( $content_arr, 0, ( $word_limit ) );

		return ( implode( $author_des, ' ' ) ) . $end_line;
	}
}

/*
 * Upload translation language files
 * */
if ( ! function_exists( 'thim_upload_language_files' ) ) {
	function thim_upload_language_files() {
		if ( empty( $_GET['activated'] ) ) {
			return false;
		}

		// Check folder permission and create folder languages in not exist
		if ( ! wp_mkdir_p( ABSPATH . 'wp-content/languages/' ) ) {
			esc_html_e( 'Languages path could not be created', 'eduma' );
		}

		$prefix       = 'eduma';
		$default_lang = array(
			$prefix . '-bg_BG' => 'Bulgarian',
			$prefix . '-da_DK' => 'Danish',
			$prefix . '-es_ES' => 'Spanish(Spain)',
			$prefix . '-es_MX' => 'Spanish(Mexico)',
			$prefix . '-fa_IR' => 'Persian',
			$prefix . '-pl_PL' => 'Polish',
			$prefix . '-pt_BR' => 'Portuguese(Brazil)',
			$prefix . '-ru_RU' => 'Russian',
			$prefix . '-tr_TR' => 'Turkish'
		);

		$required = false;

		foreach ( $default_lang as $k => $val ) {
			$file_dir = WP_CONTENT_DIR . '/languages/themes/' . $k . '.mo';
			//			clearstatcache(true, $file_dir);

			if ( ! file_exists( $file_dir ) ) {
				if ( ! $required ) {
					require_once ABSPATH . 'wp-admin/includes/template.php';
					require_once ABSPATH . 'wp-admin/includes/misc.php';
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
					$required = true;
				}

				$skin    = new WP_Ajax_Upgrader_Skin();
				$install = new WP_Upgrader( $skin );

				$is_success = $install->run( array(
					'package'                     => 'https://github.com/ThimPressWP/demo-data/blob/master/' . $prefix . '/languages/' . $val . '.zip?raw=true',
					'destination'                 => WP_CONTENT_DIR . '/languages/themes/',
					'clear_destination'           => false,
					'abort_if_destination_exists' => false,
					'clear_working'               => false,
				) );

				if ( ! $is_success ) {
					echo '<div class="message error"><p><strong>' . __( 'Installation failed', 'eduma' ) . '</strong></p></div>';
				}
			}
		}
	}
}

add_action( 'after_switch_theme', 'thim_upload_language_files' );


/**
 * Add Thim VC templates.
 *
 */
if ( thim_plugin_active( 'js_composer/js_composer.php' ) ) {
	require THIM_DIR . 'inc/admin/thim-vc-tempate.php';
}

/*
 * Handle conflict between Google captcha plugin vs Revolution Slider plugin
 */
if ( thim_plugin_active( 'google-captcha/google-captcha.php' ) || thim_plugin_active( 'anywhere-elementor/anywhere-elementor.php' ) ) {
	remove_filter( 'widget_text', 'do_shortcode' );
}

