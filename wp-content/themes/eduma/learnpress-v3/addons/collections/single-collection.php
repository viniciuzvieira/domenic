<?php
/**
 * The template for displaying single collection
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//get_header(); ?>

<?php //do_action( 'learn_press_before_main_content' ); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php learn_press_collections_get_template( 'content-single-collection.php' ); ?>

<?php endwhile; ?>

<?php //do_action( 'learn_press_after_main_content' ); ?>

<?php //get_footer(); ?>
