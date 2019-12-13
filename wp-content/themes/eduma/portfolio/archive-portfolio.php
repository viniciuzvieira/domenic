<?php
/**
 * The Template for displaying all single posts.
 *
 * @package    thimpress
 */

//get_header();

global $portfolio_data;

$theme_options_data = get_theme_mods();


$column = isset( $theme_options_data['thim_portfolio_cate_grid_column'] ) ? $theme_options_data['thim_portfolio_cate_grid_column'] : 3;

$query_object = get_queried_object();
// Item style
if ( isset( $query_object->term_id ) ) {
	$category = $query_object->term_id;
} else {
	$category = '';
}
$filter_hiden    = 'on';
$filter_style    = '';
$filter_position = '';
$gutter          = 1;
$item_size       = 'same';
$item_style      = 'classic';
$item_effect     = 'classic';
$paging          = 'all';


// Filter position
$css_filter_position = ' style="text-align:center;"';

// Gutter
if ( $gutter ) {
	$gutter = 'on';
} else {
	$gutter = 'off';
}

if ( 'on' == $gutter ) {
	$class_gutter = ' gutter';
} else {
	$class_gutter = '';
}

switch ( $column ) {
	case 1:
		$class_column = 'one-col';
		break;
	case 2:
		$class_column = 'two-col';
		break;
	case 3:
		$class_column = 'three-col';
		break;
	case 4:
		$class_column = 'four-col';
		break;
	case 5:
		$class_column = 'five-col';
		break;
}

$args = array(
	'post_type'      => 'portfolio',
	'posts_per_page' => - 1,
);

if ( ( is_array( $category ) && ! empty( $category ) ) || ( ! is_array( $category ) && $category ) ) {

	$args['tax_query'][] = array(
		'taxonomy' => 'portfolio_category',
		'field'    => 'ID',
		'terms'    => $category,
	);
}

$gallery = new WP_Query( $args );

$number_total = max( $gallery->post_count, $paging );
if ( is_array( $gallery->posts ) && ! empty( $gallery->posts ) && $gallery->post_count ) {
	foreach ( $gallery->posts as $gallery_post ) {
		$post_taxs = wp_get_post_terms( $gallery_post->ID, 'portfolio_category', array(
			'fields' => 'all',
		) );
		if ( is_array( $post_taxs ) && ! empty( $post_taxs ) ) {
			foreach ( $post_taxs as $post_tax ) {
				if ( is_array( $category ) && ! empty( $category ) && ( in_array( $post_tax->term_id, $category ) || in_array( $post_tax->parent, $category ) ) ) {
					$portfolio_taxs[ urldecode( $post_tax->slug ) ] = $post_tax->name;
				}
				if ( empty( $category ) || ! isset( $category ) ) {
					$portfolio_taxs[ urldecode( $post_tax->slug ) ] = $post_tax->name;
				}
			}
		}
	}
} else {
	exit;
}

?>
    <div class="portfolio_container <?php echo isset( $pf_layout ) ? $pf_layout : ''; ?>">
        <div
                class="wapper_portfolio <?php echo $item_style; ?> <?php echo $item_effect; ?><?php echo $class_gutter; ?> <?php echo $item_size; ?> <?php echo $paging; ?>">
			<?php if ( ! empty( $portfolio_taxs ) ) : ?>
                <div class="portfolio-tabs-wapper filters">
                    <ul class="portfolio-tabs">
                        <li><a href="#" class="filter active" data-filter="*"><?php echo esc_html__( 'All', 'eduma' ); ?></a>
                        </li>
						<?php foreach ( $portfolio_taxs as $portfolio_tax_slug => $portfolio_tax_name ) : ?>
                            <li>
                                <a class="filter" href="#" data-filter=".<?php echo $portfolio_tax_slug; ?>"><?php echo $portfolio_tax_name; ?></a>
                            </li>
						<?php endforeach; ?>
                    </ul>
                </div>
			<?php endif; ?>
            <div class="portfolio_column">
                <ul class="content_portfolio style01">
					<?php
					while ( $gallery->have_posts() ) :
						$gallery->the_post();
						$feature_images = get_post_meta( get_the_ID(), 'feature_images', true );
						$bk_ef          = get_post_meta( get_the_ID(), 'thim_portfolio_bg_color_ef', true );
						if ( '' == $bk_ef ) {
							$bk_ef = get_post_meta( get_the_ID(), 'thim_portfolio_bg_color_ef', true );
							$bg    = '';
						} else {
							$bk_ef = get_post_meta( get_the_ID(), 'thim_portfolio_bg_color_ef', true );
							$bg    = 'style="background-color:' . $bk_ef . ';"';
						}

						$images_size  = 'portfolio_size11';
						$style_layout = '';

						$class_size = ' ' . $class_column;

						$item_classes = '';
						$terms_id     = array();
						$item_cats    = get_the_terms( $post->ID, 'portfolio_category' );
						if ( $item_cats ) :
							foreach ( $item_cats as $item_cat ) {
								$item_classes .= urldecode( $item_cat->slug ) . ' ';
								$terms_id[]   = $item_cat->term_id;
							}
						endif;

						$image_id = get_post_thumbnail_id( $post->ID );

						$crop       = true;
						$dimensions = isset( $portfolio_data['thim_portfolio_option_dimensions'] ) ? $portfolio_data['thim_portfolio_option_dimensions'] : array();
						if ( 'portfolio_size11' == $images_size ) {
							$w = isset( $dimensions['width'] ) ? $dimensions['width'] : '480';
							$h = isset( $dimensions['height'] ) ? $dimensions['height'] : '320';
						}

						$imgurl     = wp_get_attachment_image_src( $image_id, 'full' );
						$image_crop = aq_resize( $imgurl[0], $w, $h, $crop );

						$image_url = '<img src="' . $image_crop . '" alt= ' . get_the_title() . ' title = ' . get_the_title() . ' />';

						// check postfolio type
						$data_href = '';

						$imclass  = '';
						$im_image = get_permalink( $post->ID );
						$btn_text = esc_html__( 'View More', 'eduma' );

						/* end check portfolio type */

						echo '<li data-color="' . $bk_ef . '"  class="element-item ' . $item_classes . ' item_portfolio ' . $class_size . $style_layout . '" ' . $bg . '>';
						echo '<div class="portfolio-image">' . $image_url . '
							<div class="portfolio-hover"  ' . $bg . '><div class="thumb-bg""><div class="mask-content">';
						echo '<h3><a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" >' . get_the_title( $post->ID ) . '</a></h3>';
						echo '<span class="p_line"></span>';
						$terms    = get_the_terms( $post->ID, 'portfolio_category' );
						$cat_name = '';
						if ( $terms && ! is_wp_error( $terms ) ) :
							foreach ( $terms as $term ) {
								if ( $cat_name ) {
									$cat_name .= ', ';
								}
								$cat_name .= '<a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a>';
							}
							echo '<div class="cat_portfolio">' . $cat_name . '</div>';
						endif;
						echo '<a href="' . esc_url( $im_image ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" class="btn_zoom ' . $imclass . '" ' . $data_href . '>' . $btn_text . '</a>';
						echo '</div></div></div></div>';
						echo '</li>';
						?>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
                </ul>
            </div>
        </div>
        <!-- .wapper portfolio -->

    </div>
<?php
//get_footer();
