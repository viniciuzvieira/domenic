<?php
/**
 * Template Name: Landing Page
 *
 **/
get_header();?>
	<div id="main-home-content" class="home-content home-page container" role="main">
		<?php
		// Start the Loop.
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
		?>
	</div><!-- #main-content -->
</div></div></div>
<?php wp_footer();
