<?php
/**
 * The Template for displaying all single posts.
 *
 * @package    thimpress
 */

get_header();
?>
	<section class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_title(); ?>
			<div class="clear"></div>
		<?php endwhile; // end of the loop. ?>
	</section>
<?php
get_footer();