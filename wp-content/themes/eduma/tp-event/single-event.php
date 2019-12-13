<?php
/**
 * Template Single Event post type
 */

//get_header( ); ?>

	<?php
		/**
		 * tp_event_before_main_content hook
		 */
		do_action( 'tp_event_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php tp_event_get_template_part( 'content', 'single-event' ); ?>

		<?php endwhile; // end of the loop. ?>
		
		<?php 
		
		// If comments are open or we have at least one comment, load up the comment template
        if ( comments_open() || '0' != get_comments_number() ) :
            comments_template();
        endif;
		
		?>
		

	<?php
		
		/**
		 * hotel_booking_after_main_content hook
		 *
		 * @hooked tp_event_after_main_content - 10 (outputs closing divs for the content)
		 */
		do_action( 'tp_event_after_main_content' );
	?>

<?php //get_footer( );