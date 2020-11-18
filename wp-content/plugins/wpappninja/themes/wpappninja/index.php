<?php get_header(); ?>

<div class="pages">
	<div data-page="404-<?php the_ID(); ?>" class="page">
		<div class="page-content">

			<div class="content-block">

				<?php while ( have_posts() ) : the_post(); ?>
					<div class="content-block-inner">
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php the_excerpt(); ?>

						</article>
					</div>

				<?php endwhile; ?>

			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>

