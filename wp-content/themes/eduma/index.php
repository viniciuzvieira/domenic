<?php
$class_archive  = '';
$is_grid_layout = get_theme_mod( 'thim_front_page_cate_display_layout', false );
$layout_type    = $is_grid_layout ? 'grid' : '';
if ( $layout_type == 'grid' ) {
	$class_archive = ' blog-switch-layout blog-list';
	global $post, $wp_query;

	if ( is_category() ) {
		$total = get_queried_object();
		$total = $total->count;
	} elseif ( ! empty( $_REQUEST['s'] ) ) {
		$total = $wp_query->found_posts;
	} else {
		$total = wp_count_posts( 'post' );
		$total = $total->publish;
	}

	if ( $total == 0 ) {
		echo '<p class="message message-error">' . esc_html__( 'There are no available posts!', 'eduma' ) . '</p>';

		return;
	} elseif ( $total == 1 ) {
		$index = esc_html__( 'Showing only one result', 'eduma' );
	} else {
		$courses_per_page = absint( get_option( 'posts_per_page' ) );
		$paged            = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

		$from = 1 + ( $paged - 1 ) * $courses_per_page;
		$to   = ( $paged * $courses_per_page > $total ) ? $total : $paged * $courses_per_page;

		if ( $from == $to ) {
			$index = sprintf(
				esc_html__( 'Showing last post of %s results', 'eduma' ),
				$total
			);
		} else {
			$index = sprintf(
				esc_html__( 'Showing %s-%s of %s results', 'eduma' ),
				$from,
				$to,
				$total
			);
		}
	}
}


if ( have_posts() ) :?>
	<div id="blog-archive" class="blog-content<?php echo esc_attr( $class_archive ); ?>">
		<?php if ( $layout_type == 'grid' ): ?>
			<div class="thim-blog-top switch-layout-container">
				<div class="switch-layout">
					<a href="#" class="list switchToGrid  switch-active"><i class="fa fa-th-large"></i></a>
					<a href="#" class="grid switchToList"><i class="fa fa-list-ul"></i></a>
				</div>
				<div class="post-index"><?php echo esc_html( $index ); ?></div>
			</div>
			<div class="row">
				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content-grid' );
				endwhile;
				?>
			</div>
		<?php else: ?>
			<div class="row">
				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					get_template_part( 'content' );
				endwhile;
				?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	thim_paging_nav();
else :
	get_template_part( 'content', 'none' );
endif;
