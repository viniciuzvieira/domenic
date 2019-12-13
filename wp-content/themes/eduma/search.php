<?php
/**
 * The template for displaying search results pages.
 *
 * @package thim
 */
?>
<?php
if ( have_posts() ) :
	?>
	<div class="row blog-content blog-list">
		<?php
		/* Start the Loop */
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'search' );
		endwhile;
		?>
	</div>
	<?php thim_paging_nav(); ?>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>