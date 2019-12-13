<?php
/**
 * Template for displaying the thumbnail of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.0.6
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
$thim_course_page = LP()->settings->get( 'course_thumbnail_image_size' );
$width            = !empty ( $thim_course_page['width'] ) ? $thim_course_page['width'] : 450;
$height           = !empty ( $thim_course_page['height'] ) ? $thim_course_page['height'] : 450;

if ( is_singular() ) {
	$media_intro = get_post_meta( $post->ID, 'thim_course_media_intro', true );
	if ( !empty( $media_intro ) ) {
		?>
		<div class="course-thumbnail">
			<div class="media-intro">
				<?php echo $media_intro; ?>
			</div>
		</div>
		<?php
	} else {
		if ( has_post_thumbnail() ) :
			?>
			<div class="course-thumbnail">
				<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
			</div>
			<?php
		endif;
	}

} else {
	?>
	<div class="course-thumbnail">
		<a class="thumb" href="<?php echo get_the_permalink(); ?>">
			<?php
			echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', $width, $height, get_the_title() );
			?>
		</a>
		<?php thim_course_wishlist_button(); ?>
		<?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
	</div>
	<?php
}