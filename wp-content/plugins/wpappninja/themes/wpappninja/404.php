
<?php get_header(); ?>

<div class="pages">
	<div data-page="404-<?php the_ID(); ?>" class="page">
		<div class="page-content">


			<div class="content-block">
				<div class="content-block-inner">
					<p><?php esc_html_e('Sorry, this page does not exist.', 'wpappninja'); ?></p>

				</div>
			</div>

		</div>
	</div>
</div>

<?php get_footer(); ?>
