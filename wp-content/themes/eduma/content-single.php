<?php
/**
 * @package thim
 */

$theme_options_data = get_theme_mods();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<div class="page-content-inner">
		<?php if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'normal' ) { ?>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php thim_entry_meta(); ?>
			</header>
			<?php
			/* Video, Audio, Image, Gallery, Default will get thumb */
			do_action( 'thim_entry_top', 'full' );
			?>
		<?php } else { ?>
			<?php
			/* Video, Audio, Image, Gallery, Default will get thumb */
			do_action( 'thim_entry_top', 'full' );
			?>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php thim_entry_meta(); ?>
			</header>
		<?php } ?>
		<!-- .entry-header -->
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
			wp_link_pages( array(
				'before'      => '<div class="pagination loop-pagination">',
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			) );
			?>
		</div>
		<div class="entry-tag-share">
			<div class="row">
				<div class="col-sm-6">
					<?php
					if ( get_the_tag_list() ) {
						echo get_the_tag_list( '<p class="post-tag"><span>' . esc_html__( "Tag:", 'eduma' ) . '</span>', ', ', '</p>' );
					}
					?>
				</div>
				<div class="col-sm-6">
					<?php do_action( 'thim_social_share' ); ?>
				</div>
			</div>
		</div>
		<?php do_action( 'thim_about_author' ); ?>

		<?php
		$prev_post = get_previous_post();
		$next_post = get_next_post();
		?>
		<?php if ( !empty( $prev_post ) || !empty( $next_post ) ): ?>
			<div class="entry-navigation-post">
				<?php
				if ( !empty( $prev_post ) ):
					?>
					<div class="prev-post">
						<p class="heading"><?php echo esc_html__( 'Previous post', 'eduma' ); ?></p>
						<h5 class="title">
							<a href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo esc_html( $prev_post->post_title ); ?></a>
						</h5>

						<div class="date">
							<?php echo get_the_date( get_option( 'date_format' ) ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php
				if ( !empty( $next_post ) ):
					?>
					<div class="next-post">
						<p class="heading"><?php echo esc_html__( 'Next post', 'eduma' ); ?></p>
						<h5 class="title">
							<a href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo esc_html( $next_post->post_title ); ?></a>
						</h5>

						<div class="date">
							<?php echo get_the_date( get_option( 'date_format' ), $next_post->ID ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

		<?php endif; ?>
		<?php
		if ( !empty( $theme_options_data['thim_archive_single_related_post'] ) ) {
			get_template_part( 'inc/related' );
		}
		?>
	</div>
</article>