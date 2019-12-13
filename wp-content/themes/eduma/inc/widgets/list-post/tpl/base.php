<?php
global $post;
$number_posts = 2;
if ( $instance['number_posts'] != '' ) {
	$number_posts = $instance['number_posts'];
}
$style = '';
if ( $instance['style'] != '' ) {
	$style = $instance['style'];
}
$image_size = 'none';
if ( $instance['image_size'] && $instance['image_size'] <> 'none' ) {
	$image_size = $instance['image_size'];
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


switch ( $number_posts ) {
	case 1:
		$class = 'item-post col-sm-12';
		break;
	case 2:
		$class = 'item-post col-sm-6';
		break;
	case 3:
		$class = 'item-post col-sm-4';
		break;
	case 4:
		$class = 'item-post col-sm-3';
		break;
	case 5:
		$class = 'item-post thim_col_custom';
		break;
	case 6:
		$class = 'item-post col-sm-2';
		break;
}

$posts_display = new WP_Query( $query_args );
if ( $posts_display->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	if ( $style == 'homepage' ) {
        echo '<div class="thim-owl-carousel-post row thim-list-posts ' . $style . '" >';
        while ($posts_display->have_posts()) {
            $posts_display->the_post();
            ?>
            <div <?php post_class($class); ?>>
                <?php
                if ($image_size <> 'none' && has_post_thumbnail()) {
                    if ($image_size == 'custom_image') {
                        echo '<div class="image">';
                        echo '<a href="' . esc_url(get_permalink(get_the_ID())) . '">';
                        echo thim_get_feature_image(get_post_thumbnail_id($post->ID), 'full', apply_filters('thim_carousel_post_thumbnail_width', 450), apply_filters('thim_carousel_post_thumbnail_height', 267), get_the_title());
                        echo '</a>';
                        echo '</div>';
                    } else {
                        echo '<div class="image">';
                        echo '<a href="' . esc_url(get_permalink(get_the_ID())) . '">';
                        echo the_post_thumbnail($image_size);
                        echo '</a>';
                        echo '</div>';
                    }

                }
                ?>
                <div class="content">
                    <div class="info">
                        <div class="author">
                            <?php echo '<span>' . esc_html(get_the_author()) . '</span>'; ?>
                        </div>
                        <div class="date">
                            <?php echo get_the_date(get_option('date_format')); ?>
                        </div>
                    </div>
                    <h4 class="title">
                        <a href="<?php echo esc_url(get_permalink(get_the_ID())) ?>"><?php echo get_the_title(); ?></a>
                    </h4>
                    <?php
                    if ($instance['show_description'] && $instance['show_description'] != 'no') {
                        echo '<div class="description">' . thim_excerpt('50') . '</div>';
                    }

                    if ($instance['text_link'] && $instance['text_link'] != '') {
                        echo '<a class="read-more" href="' . esc_url(get_permalink(get_the_ID())) . '">' . $instance['text_link'] . '</a>';
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } elseif ( $style == 'home-new' ) {
        echo '<div class="thim-list-posts ' . $style . '">';
        while ( $posts_display->have_posts() ) {
            $posts_display->the_post();
            $class = 'item-post';
            if ( $image_size != 'none' && has_post_thumbnail() ) $class .= ' has_thumb';
            ?>
            <div <?php post_class( $class ); ?>>
                <?php
                if ( $image_size != 'none' && has_post_thumbnail() ) {
                    echo '<div class="article-image">';
                    echo the_post_thumbnail( $image_size );
                    echo '</div>';
                }
                echo '<div class="article-title-wrapper">';
                echo '<h5><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" class="article-title">' .  get_the_title() . '</a></h5>';
                echo '<div class="article-date"><i class="ion-ios-calendar-outline"></i> <span class="month">' . get_the_date( 'F' ) . '</span> <span class="day">' . get_the_date( 'd' ) . '</span>, <span class="year">' . get_the_date( 'Y' ) . '</span></div>';
                if ( $instance['show_description'] && $instance['show_description'] <> 'no' ) {
                    echo thim_excerpt( '10' );
                }
                echo '</div>';
                ?>
            </div>
            <?php
        }
        if ( $instance['link'] <> '' ) {
            echo '<div class="link_read_more"><a href="' . $instance['link'] . '">' . $instance['text_link'] . '</a></div>';
        }
        echo '</div>';
	} else {
		echo '<div class="thim-list-posts ' . $style . '">';
		while ( $posts_display->have_posts() ) {
			$posts_display->the_post();
			$class = 'item-post';
			if ( $image_size != 'none' && has_post_thumbnail() ) $class .= ' has_thumb';
			?>
			<div <?php post_class( $class ); ?>>
				<?php
				if ( $image_size != 'none' && has_post_thumbnail() ) {
					echo '<div class="article-image">';
					echo the_post_thumbnail( $image_size );
					echo '</div>';
				}
				echo '<div class="article-title-wrapper">';
				echo '<h5><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" class="article-title">' .  get_the_title() . '</a></h5>';
				echo '<div class="article-date"><span class="day">' . get_the_date( 'd' ) . '</span><span class="month">' . get_the_date( 'M' ) . '</span><span class="year">' . get_the_date( 'Y' ) . '</span></div>';
				if ( $instance['show_description'] && $instance['show_description'] <> 'no' ) {
					echo thim_excerpt( '10' );
				}
				echo '</div>';
				?>
			</div>
			<?php
		}
		if ( $instance['link'] <> '' ) {
			echo '<div class="link_read_more"><a href="' . $instance['link'] . '">' . $instance['text_link'] . '</a></div>';
		}
		echo '</div>';
	}

}
wp_reset_postdata();

?>