<?php
/**
 * Template for displaying archive collection content
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php //get_header(); ?>

<?php do_action( 'learn_press_before_main_content' ); ?>

<?php do_action( 'learn_press_collections_archive_description' ); ?>

<?php if ( have_posts() ) : ?>

	<?php do_action( 'learn_press_collections_before_loop' ); ?>

	<div class="row thim-courses-collection">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php learn_press_collections_get_template( 'content-collection.php' ); ?>

	<?php endwhile; ?>

	</div>

	<?php do_action( 'learn_press_collections_after_loop' ); ?>

<?php endif; ?>

<?php do_action( 'learn_press_after_main_content' ); ?>

<?php //get_footer(); ?>