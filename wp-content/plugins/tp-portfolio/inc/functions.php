<?php

/**
 * Get template part (for templates like the shop-loop).
 *
 * @param $slug
 * @param string $name
 *
 * @return mixed|string
 */
function tp_portfolio_get_template_part( $slug, $name = '' ) {

	$plugin_path = untrailingslashit( plugin_dir_path( TP_PORTFOLIO_PLUGIN_FILE ) );

	$template = '';
	// Look in yourtheme/slug-name.php and yourtheme/portfolio/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php", 'portfolio/' . "{$slug}-{$name}.php" ) );
	}
	// Get default slug-name.php
	if ( ! $template && $name && file_exists( $plugin_path . "/templates/{$slug}-{$name}.php" ) ) {
		$template = $plugin_path . "/templates/{$slug}-{$name}.php";
	}
	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/portfolio/slug.php
	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", 'portfolio/' . "{$slug}.php" ) );
	}
	// Allow 3rd party plugin filter template file from their plugin
	$template = apply_filters( 'get_template_part', $template, $slug, $name );

	return $template;

}

/**
 * Get template type
 *
 * @param $name
 */
function tp_portfolio_get_template_type( $name ) {
	$template = '';

	// Look in yourtheme/pofolio/type/name.php
	if ( $name ) {
		$template = locate_template( "/portfolio/type/{$name}.php" );
	}

	// Get default name.php
	if ( ! $template && $name && file_exists( CORE_PLUGIN_PATH . "/templates/type/{$name}.php" ) ) {
		$template = CORE_PLUGIN_PATH . "/templates/type/{$name}.php";
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'tp_portfolio_get_template_type', $template, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get related portfolio.
 *
 * @access public
 * @return html
 */
function tp_portfolio_related() {
	global $portfolio_data;
	?>
    <div class="related-portfolio col-md-12">
        <div class="module_title"><h4
                    class="widget-title"><?php _e( 'VIEW OTHER RELATED ITEMS', 'tp-portfolio' ); ?></h4>
        </div>

		<?php //Get Related posts by category	-->
		$args      = array(
			'posts_per_page' => 3,
			'post_type'      => 'portfolio',
			'post_status'    => 'publish'
		);
		$port_post = get_posts( $args );
		?>

        <ul class="row">
			<?php
			foreach ( $port_post as $post ) : setup_postdata( $post ); ?>
                <li class="col-sm-4">
					<?php
					// check postfolio type
					$data_href = "";
					if ( get_post_meta( get_the_ID(), 'selectPortfolio', true ) == "portfolio_type_1" ) {
						if ( get_post_meta( get_the_ID(), 'style_image_popup', true ) == "Style-01" ) { // prettyPhoto
							$imclass = "image-popup-01";
							if ( get_post_meta( get_the_ID(), 'project_item_slides', true ) != "" ) { //overide image
								$att     = get_post_meta( get_the_ID(), 'project_item_slides', true );
								$imImage = wp_get_attachment_image_src( $att, 'full' );
								$imImage = $imImage[0];
							} else if ( has_post_thumbnail( $post->ID ) ) {// using thumb

								$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
								$imImage = $image[0];
							} else {// no thumb and no overide image
								$imclass = "";
								$imImage = get_permalink( $post->ID );
							}
						} else { // magnific
							$imclass = "image-popup-02";
							if ( get_post_meta( get_the_ID(), 'project_item_slides', true ) != "" ) {
								$att     = get_post_meta( get_the_ID(), 'project_item_slides', true );
								$imImage = wp_get_attachment_image_src( $att, 'full' );
								$imImage = $imImage[0];
							} else if ( has_post_thumbnail( $post->ID ) ) {

								$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
								$imImage = $image[0];
							} else {
								$imclass = "";
								$imImage = get_permalink( $post->ID );
							}
						}
					} else if ( get_post_meta( get_the_ID(), 'selectPortfolio', true ) == "portfolio_type_3" ) {
						$imclass = "video-popup";
						if ( get_post_meta( get_the_ID(), 'project_video_embed', true ) != "" ) {

							if ( get_post_meta( get_the_ID(), 'project_video_type', true ) == "youtube" ) {
								$imImage = 'http://www.youtube.com/watch?v=' . get_post_meta( get_the_ID(), 'project_video_embed', true );
							} else if ( get_post_meta( get_the_ID(), 'project_video_type', true ) == "vimeo" ) {
								$imImage = 'https://vimeo.com/' . get_post_meta( get_the_ID(), 'project_video_embed', true );
							}


						} else if ( has_post_thumbnail( $post->ID ) ) {
							$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
							$imImage = $image[0];
						} else {
							$imclass = "";
							$imImage = get_permalink( $post->ID );
						}
					} else if ( get_post_meta( get_the_ID(), 'selectPortfolio', true ) == "portfolio_type_2" ) {
						$imclass   = "slider-popup";
						$imImage   = "#" . $post->post_name;
						$data_href = 'data-href="' . esc_url( get_permalink( $post->ID ) ) . '"';
					} else {
						$imclass   = "";
						$data_href = "";
						$imImage   = get_permalink( $post->ID );
					}
					/* end check portfolio type */

					$images_size = 'portfolio_size11';
					$image_id    = get_post_thumbnail_id( $post->ID );
					//$image_url = wp_get_attachment_image( $image_id, $images_size, false, array( 'alt' => get_the_title(), 'title' => get_the_title() ) );
					$dimensions = isset( $portfolio_data['thim_portfolio_option_dimensions'] ) ? $portfolio_data['thim_portfolio_option_dimensions'] : array();
					$w          = isset( $dimensions['width'] ) ? $dimensions['width'] : '480';
					$h          = isset( $dimensions['height'] ) ? $dimensions['height'] : '320';

					$crop       = true;
					$imgurl     = wp_get_attachment_image_src( $image_id, 'full' );
					$image_crop = aq_resize( $imgurl[0], $w, $h, $crop );
					$image_url  = '<img src="' . $image_crop . '" alt= ' . get_the_title() . ' title = ' . get_the_title() . ' />';

					echo '<div class="portfolio-image">' . $image_url . '
					<div class="portfolio_hover"><div class="thumb-bg""><div class="mask-content">';
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
					echo '<a href="' . esc_url( $imImage ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" class="btn_zoom ' . $imclass . '" ' . $data_href . '>Zoom</a>';
					echo '</div></div></div></div>';
					?>
                </li>
			<?php endforeach; ?>
        </ul>
		<?php wp_reset_postdata(); ?>
    </div><!--#portfolio_related-->
	<?php
}

/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package thimpress
 */
function portfolio_pagination( $pages = '', $range = 2, $paged = 1 ) {
	$showitems = ( $range * 2 ) + 1;

	if ( empty( $paged ) ) {
		$paged = 1;
	}

	if ( $pages == '' ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}

	if ( 1 != $pages ) {
		echo "<div class='pagination loop-pagination'><ul class='page-numbers'>";
		if ( $paged > 1 ) {
			echo "<li><a class='prev page-numbers' href='" . get_pagenum_link( $paged - 1 ) . "'></a></li> ";
		}

		for ( $i = 1; $i <= $pages; $i ++ ) {
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				echo ( $paged == $i ) ? "<li><span class='page-numbers current'>" . $i . "</span></li> " : "<li><a href='" . get_pagenum_link( $i ) . "' class='page-numbers' >" . $i . "</a></li> ";
			}
		}

		if ( $paged < $pages ) {
			echo "<li><a class='next page-numbers' href='" . get_pagenum_link( $paged + 1 ) . "'></span></a></li> ";
		}
		echo "</ul></div>";
	}
}

function content_at_the_end() {
	echo '<div class="gallery-slider-content"></div>';
}

add_action( 'wp_footer', 'content_at_the_end' );

function thim_portfolio_breadcrumbs() {
	return 'Will update later!';
}