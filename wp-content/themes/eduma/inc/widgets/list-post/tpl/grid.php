<?php
global $post;
$number_posts = 3;
if ( ! empty( $instance['number_posts'] ) ) {
	$number_posts = $instance['number_posts'];
}
$items_vertical = ( ! empty( $instance['item_vertical'] ) && $instance['item_vertical'] > 0 ) ? $instance['item_vertical'] : 0;

$style = $feature_html = '';
if ( $instance['style'] != '' ) {
	$style = $instance['style'];
}

$query_args = array(
	'post_type'           => 'post',
	'posts_per_page'      => $number_posts,
	'order'               => ( 'asc' == $instance['order'] ) ? 'asc' : 'desc',
	'ignore_sticky_posts' => true
);
if ( $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	$query_args['cat'] = $instance['cat_id'];
}
switch ( $instance['orderby'] ) {
	case 'recent' :
		$query_args['orderby'] = 'post_date';
		break;
	case 'title' :
		$query_args['orderby'] = 'post_title';
		break;
	case 'popular' :
		$query_args['orderby'] = 'comment_count';
		break;
	default : //random
		$query_args['orderby'] = 'rand';
}

$posts_display = new WP_Query( $query_args );
$box_class     = $items_vertical < $number_posts ? ' has-horizontal' : '';
$box_class     .= $items_vertical > 0 ? ' has-vertical' : '';
$feature_post  = ( ! empty( $instance['display_feature'] ) && $instance['display_feature'] == 'yes' ) ? $instance['display_feature'] : false;

if ( count( $posts_display->posts ) < $number_posts ) {
	$number_posts = count( $posts_display->posts );
}

if ( $posts_display->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	$index       = 1;
	$inner_class = '';
	if ( $feature_post ) {
		$index        = 0;
		$number_posts = $number_posts - 1;
		$inner_class  = ' has-feature';
	}
	echo '<div class="thim-list-post-inner' . $inner_class . '">';
	echo '<div class="thim-grid-posts' . $box_class . '">';
	while ( $posts_display->have_posts() ) {
		$posts_display->the_post();
		$class = 'item-post';

		if ( $index == 0 ) {
			$feature_html .= '<div class="feature-item">';
			$feature_html .= '<div class="' . join( ' ', get_post_class( $class, get_the_ID() ) ) . '">';
			if ( has_post_thumbnail() ) {
				$feature_html .= '<div class="article-image">';
				$feature_html .= get_the_post_thumbnail( get_the_ID(), 'full' );
				$feature_html .= '</div>';
			}
			$feature_html .= '<div class="article-wrapper">';
			$feature_html .= '<div class="date">' . get_the_date( get_option( 'date_format' ) ) . '</div>';
			$feature_html .= '<h5 class="title"><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" class="article-title">' . esc_attr( get_the_title() ) . '</a></h5>';
			if ( $instance['show_description'] && $instance['show_description'] <> 'no' ) {
				$feature_html .= '<div class="desc">' . thim_excerpt( '13' ) . '</div>';
			}
			$feature_html .= '<a class="read-more" href="' . get_the_permalink() . '" >' . esc_html__( 'Read More', 'eduma' ) . '<i class="fa fa-long-arrow-right"></i></a>';
			$feature_html .= '</div>';

			$feature_html .= '</div>';
			$feature_html .= '</div>';
		} else {
			if ( $index == 1 && ( $items_vertical < $number_posts ) ) {
				//Open div grid-horizontal
				echo '<div class="grid-horizontal">';
			}

			if ( ( ( $index - 1 == $number_posts - $items_vertical ) && $items_vertical > 0 ) || ( $items_vertical >= $number_posts && $index == 1 ) ) {
				//Open div grid-vertical
				echo '<div class="grid-vertical">';
			}

			?>
			<div <?php post_class( $class ); ?>>
				<?php
				if ( has_post_thumbnail() ) {
					echo '<div class="article-image">';
					if ( ! empty( $instance['img_w'] ) && ! empty( $instance['img_h'] ) ) {
						echo thim_get_feature_image( get_post_thumbnail_id(), 'full', $instance['img_w'], $instance['img_h'] );
					} else {
						echo thim_get_feature_image( get_post_thumbnail_id(), 'full' );
					}
					echo '</div>';
				}
				echo '<div class="article-wrapper">';
				echo '<div class="date">' . get_the_date( get_option( 'date_format' ) ) . '</div>';
				echo '<h5 class="title"><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" class="article-title">' . esc_attr( get_the_title() ) . '</a></h5>';
				if ( $instance['show_description'] && $instance['show_description'] <> 'no' ) {
					echo '<div class="desc">' . thim_excerpt( '13' ) . '</div>';
				}
				echo '<a class="read-more" href="' . get_the_permalink() . '" >' . esc_html__( 'Read More', 'eduma' ) . '<i class="fa fa-long-arrow-right"></i></a>';
				echo '</div>';
				?>
			</div>
			<?php
			if ( $index == $number_posts - $items_vertical ) {
				//Close div grid-horizontal
				echo '</div>';
			}
		}
		$index ++;
	}

	echo '</div></div>';

	//Link All Posts
	if ( $instance['link'] <> '' ) {
		echo '<div class="link_read_more"><a href="' . $instance['link'] . '">' . $instance['text_link'] . '</a></div>';
	}
	echo ent2ncr( $feature_html );
	echo '</div>';
}
wp_reset_postdata();
