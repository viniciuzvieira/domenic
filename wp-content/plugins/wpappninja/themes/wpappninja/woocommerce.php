<?php get_header(); ?>

<div class="pages">
	<div data-page="post-<?php the_ID(); ?>" class="page">
		<div class="page-content">
			<div class="content-block">
				<div class="content-block-inner">
					<?php woocommerce_content(); ?>

				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
