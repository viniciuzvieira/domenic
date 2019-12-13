<?php
/**
 * Template for displaying the thumbnail of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_singular() ) {
	if( has_post_thumbnail() ) :
	?>
	<div class="course-thumbnail">
		<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
	</div>
		<?php
		endif;

} else {
	?>
	<div class="course-thumbnail">
		<a href="<?php echo get_the_permalink(); ?>">
			<?php
			echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', 450, 450, get_the_title() );
			?>
		</a>
		<?php thim_course_wishlist_button(); ?>
		<?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
	</div>
	<?php
}
