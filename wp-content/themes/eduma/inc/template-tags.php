<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package thim
 */
if ( !function_exists( 'thim_paging_nav' ) ) :

	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function thim_paging_nav() {
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );

		$query_args = array();
		$url_parts  = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( array(
			'base'      => $pagenum_link,
			'format'    => $format,
			'total'     => $GLOBALS['wp_query']->max_num_pages,
			'current'   => $paged,
			'mid_size'  => 1,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => esc_html__( '<', 'eduma' ),
			'next_text' => esc_html__( '>', 'eduma' ),
			'type'      => 'list'
		) );

		if ( $links ) :
			?>
			<div class="pagination loop-pagination">
				<?php echo ent2ncr( $links ); ?>
			</div>
			<!-- .pagination -->
			<?php
		endif;
	}

endif;

if ( !function_exists( 'thim_post_nav' ) ) :

	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function thim_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		if ( !$next && !$previous ) {
			return;
		}

		$previous_post = get_previous_post();
		$next_post = get_next_post();
		if ( isset( $previous_post->ID ) && get_the_post_thumbnail( $previous_post->ID, 'thumbnail' ) ) {
			$prev_img = '<span class="img">' . get_the_post_thumbnail( $previous_post->ID, 'thumbnail' ) . '</span>';
		} else {
			$prev_img = "";
		}

		if ( isset( $next_post->ID ) && get_the_post_thumbnail( $next_post->ID, 'thumbnail' ) ) {
			$next_img = '<span class="img">' . get_the_post_thumbnail( $next_post->ID, 'thumbnail' ) . '</span>';
		} else {
			$next_img = "";
		}

		if ( $previous ) {
			previous_post_link( '<div class="tm-single-nav post-prev">%link</div>', '<i class="fa fa-angle-left"></i><div class="post-entry"><div class="post-entry-inner"><div class="post-entry-content"><h3>%title</h3>' . $prev_img . '</div></div></div>' );
		}
		if ( $next ) {
			next_post_link( '<div class="tm-single-nav post-next">%link</div>', '<i class="fa fa-angle-right"></i><div class="post-entry"><div class="post-entry-inner"><div class="post-entry-content">' . $next_img . '<h3>%title</h3></div></div></div>' );
		}
	}

endif;

if ( !function_exists( 'thim_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function thim_entry_meta() {
		$theme_options_data = get_theme_mods();
		if ( !isset( $theme_options_data['thim_show_author'] ) ) {
			$theme_options_data['thim_show_author'] = 1;
		}

		if ( !isset( $theme_options_data['thim_show_category'] ) ) {
			$theme_options_data['thim_show_category'] = 1;
		}
		if ( !isset( $theme_options_data['thim_show_date'] ) ) {
			$theme_options_data['thim_show_date'] = 1;
		}
		if ( !isset( $theme_options_data['thim_show_comment'] ) ) {
			$theme_options_data['thim_show_comment'] = 1;
		}
		$archive_layout = !empty( $theme_options_data['thim_archive_cate_display_layout'] ) ? $theme_options_data['thim_archive_cate_display_layout']  : 'default';

		?>
		<ul class="entry-meta">
			<?php
			if ( isset( $theme_options_data['thim_show_author'] ) && $theme_options_data['thim_show_author'] == 1 ) {
				?>
				<li class="author">
					<span><?php echo esc_html__( 'Posted by', 'eduma' ); ?></span>
					<?php printf( '<span class="vcard author author_name"><a href="%1$s">%2$s</a></span>',
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						esc_html( get_the_author() )
					); ?>
				</li>
				<?php
			}
			if ( isset( $theme_options_data['thim_show_category'] ) && $theme_options_data['thim_show_category'] == 1 && get_the_category() ) {
				?>
				<li class="entry-category">
					<span><?php esc_html_e( 'Categories', 'eduma' ); ?></span> <?php the_category( ', ', '' ); ?>
				</li>
				<?php
			}
			if ( isset( $theme_options_data['thim_show_date'] ) && $theme_options_data['thim_show_date'] == 1 && ( is_single() || ( $archive_layout == 'grid') ) ) {
				?>
				<li class="entry-date">
					<span><?php echo esc_html__( 'Date', 'eduma' ); ?></span>
					<span class="value"> <?php echo get_the_date( get_option( 'date_format' ) ); ?></span>
				</li>
				<?php
			}

			if ( isset( $theme_options_data['thim_show_comment'] ) && $theme_options_data['thim_show_comment'] == 1 ) {
				?>
				<?php if ( !post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) :
					?>
					<li class="comment-total">
						<span><?php echo esc_html__( 'Comments', 'eduma' ) ?></span>
						<?php comments_popup_link( esc_html__( '0 comment', 'eduma' ), esc_html__( '1 comment', 'eduma' ), '% ' . esc_html__( 'comments', 'eduma' ) ); ?>
					</li>
					<?php
				endif;
			}
			?>

		</ul>
		<?php
	}
endif;

if ( !function_exists( 'thim_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function thim_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' == get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'eduma' ) );
			if ( $categories_list && thim_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'eduma' ) . '</span>', $categories_list );
			}
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'eduma' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'eduma' ) . '</span>', $tags_list );
			}
		}

		if ( !is_single() && !post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'eduma' ), esc_html__( '1 comment', 'eduma' ), '%' . esc_html__( 'comments', 'eduma' ) );
			echo '</span>';
		}

		edit_post_link( esc_html__( 'Edit', 'eduma' ), '<span class="edit-link">', '</span>' );
	}

endif;

if ( !function_exists( 'thim_archive_title' ) ) :

	/*
	 * Shim for `the_archive_title()`.
	 *
	 * Display the archive title based on the queried object.
	 *
	 *
	 * @param string $before Optional. Content to prepend to the title. Default empty.
	 * @param string $after  Optional. Content to append to the title. Default empty.
	 */
	function thim_archive_title( $before = '', $after = '' ) {
		$title = '';
		$theme_options_data = get_theme_mods();
		if ( is_category() ) {
			$title = sprintf( esc_html__( '%s', 'eduma' ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( esc_html__( '%s', 'eduma' ), single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( esc_html__( '%s', 'eduma' ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			$title = sprintf( esc_html__( 'Year: %s', 'eduma' ), get_the_date( _x( 'Y', 'yearly archives date format', 'eduma' ) ) );
		} elseif ( is_month() ) {
			$title = sprintf( esc_html__( 'Month: %s', 'eduma' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'eduma' ) ) );
		} elseif ( is_day() ) {
			$title = sprintf( esc_html__( 'Day: %s', 'eduma' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'eduma' ) ) );
		} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'eduma' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'eduma' );
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( esc_html__( '%s', 'eduma' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( esc_html__( '%1$s: %2$s', 'eduma' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} elseif ( is_404() ) {
			$title = esc_html__( '404 Page', 'eduma' );
			if ( ! empty( $theme_options_data[ 'thim_single_404_page_title' ] ) ) {
				$title = $theme_options_data[ 'thim_single_404_page_title' ];
			}
		} elseif ( is_search() ) {
			$title = sprintf( esc_html__( 'Search Results for: %s', 'eduma' ), get_search_query() );
		} else {
			$title = esc_html__( 'Archives', 'eduma' );
		}

		/**
		 * Filter the archive title.
		 *
		 * @param string $title Archive title to be displayed.
		 */
		//$title = apply_filters( 'get_the_archive_title', $title );

		return $before . $title . $after;
	}

endif;

if ( !function_exists( 'thim_forum_title' ) ) :
	function thim_forum_title( $before = '', $after = '' ) {
		// Search page
		if ( function_exists( 'is_bbpress' ) ) {
			$pre_current_text = '';
			if ( bbp_is_search() ) {
				$pre_current_text = bbp_get_search_title();
				// Forum archive
			} elseif ( bbp_is_forum_archive() ) {
				$pre_current_text = bbp_get_forum_archive_title();
				// Topic archive
			} elseif ( bbp_is_topic_archive() ) {
				$pre_current_text = bbp_get_topic_archive_title();

				// View
			} elseif ( bbp_is_single_view() ) {
				$pre_current_text = bbp_get_view_title();

				// Single Forum
			} elseif ( bbp_is_single_forum() ) {
				$pre_current_text = bbp_get_forum_title();

				// Single Topic
			} elseif ( bbp_is_single_topic() ) {
				$pre_current_text = bbp_get_topic_title();

				// Single Topic
			} elseif ( bbp_is_single_reply() ) {
				$pre_current_text = bbp_get_reply_title();
				// Topic Tag (or theme compat topic tag)
			} elseif ( bbp_is_topic_tag() || ( get_query_var( 'bbp_topic_tag' ) && !bbp_is_topic_tag_edit() ) ) {
				// Always include the tag name
				$tag_data[] = bbp_get_topic_tag_name();
				// If capable, include a link to edit the tag
				if ( current_user_can( 'manage_topic_tags' ) ) {
					$tag_data[] = '' . esc_html__( '(Edit)', 'eduma' ) . '';
				}

				// Implode the results of the tag data
				$pre_current_text = sprintf( esc_html__( 'Topic Tag: %s', 'eduma' ), implode( ' ', $tag_data ) );

				// Edit Topic Tag
			} elseif ( bbp_is_topic_tag_edit() ) {
				$pre_current_text = esc_html__( 'Edit', 'eduma' );
			} else {
				$pre_current_text = get_the_title();
			}

			return $before . $pre_current_text . $after ;
		}
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function thim_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'thim_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'thim_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so thim_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so thim_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in thim_categorized_blog.
 */
function thim_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'thim_categories' );
}

add_action( 'edit_category', 'thim_category_transient_flusher' );
add_action( 'save_post', 'thim_category_transient_flusher' );


/**
 * About the author
 */
if ( !function_exists( 'thim_about_author' ) ) {
	function thim_about_author() {
		$lp_info = get_the_author_meta( 'lp_info' );
		$link    = '#';
		if ( get_post_type() == 'lpr_course' ) {
			$link = apply_filters( 'learn_press_instructor_profile_link', '#', $user_id = null, get_the_ID() );
		} elseif ( get_post_type() == 'lp_course' ) {
			$link = learn_press_user_profile_link( get_the_author_meta( 'ID' ) );
		} elseif ( is_single() ) {
			$link = get_author_posts_url( get_the_author_meta( 'ID' ) );
		}
		?>
		<div class="thim-about-author">
			<div class="author-wrapper">
				<div class="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 110 ); ?>
				</div>
				<div class="author-bio">
					<div class="author-top">
						<a class="name" href="<?php echo esc_url( $link ); ?>">
							<?php echo get_the_author(); ?>
						</a>
						<?php if ( isset( $lp_info['major'] ) && $lp_info['major'] ) : ?>
							<p class="job"><?php echo esc_html( $lp_info['major'] ); ?></p>
						<?php endif; ?>
					</div>
					<ul class="thim-author-social">
						<?php if ( isset( $lp_info['facebook'] ) && $lp_info['facebook'] ) : ?>
							<li>
								<a href="<?php echo esc_url( $lp_info['facebook'] ); ?>" class="facebook"><i class="fa fa-facebook"></i></a>
							</li>
						<?php endif; ?>

						<?php if ( isset( $lp_info['twitter'] ) && $lp_info['twitter'] ) : ?>
							<li>
								<a href="<?php echo esc_url( $lp_info['twitter'] ); ?>" class="twitter"><i class="fa fa-twitter"></i></a>
							</li>
						<?php endif; ?>

						<?php if ( isset( $lp_info['google'] ) && $lp_info['google'] ) : ?>
							<li>
								<a href="<?php echo esc_url( $lp_info['google'] ); ?>" class="google-plus"><i class="fa fa-google-plus"></i></a>
							</li>
						<?php endif; ?>

                        <?php if ( isset( $lp_info['instagram'] ) && $lp_info['instagram'] ) : ?>
                            <li>
                                <a href="<?php echo esc_url( $lp_info['instagram'] ); ?>" class="instagram"><i
                                            class="fa fa-instagram"></i></a>
                            </li>
                        <?php endif; ?>

						<?php if ( isset( $lp_info['linkedin'] ) && $lp_info['linkedin'] ) : ?>
							<li>
								<a href="<?php echo esc_url( $lp_info['linkedin'] ); ?>" class="linkedin"><i class="fa fa-linkedin"></i></a>
							</li>
						<?php endif; ?>

						<?php if ( isset( $lp_info['youtube'] ) && $lp_info['youtube'] ) : ?>
							<li>
								<a href="<?php echo esc_url( $lp_info['youtube'] ); ?>" class="youtube"><i class="fa fa-youtube"></i></a>
							</li>
						<?php endif; ?>
					</ul>

				</div>
				<div class="author-description">
					<?php echo get_the_author_meta( 'description' ); ?>
				</div>
			</div>
		</div>
		<?php
		if ( thim_plugin_active( 'learnpress/learnpress.php' ) ) {
			thim_co_instructors( get_the_ID(), get_the_author_meta( 'ID' ) );
		}

	}
}

add_action( 'thim_about_author', 'thim_about_author' );

function thim_modify_contact_methods( $profile_fields ) {
	$profile_fields['job'] = 'Job';
	return $profile_fields;
}

add_filter( 'user_contactmethods', 'thim_modify_contact_methods' );
