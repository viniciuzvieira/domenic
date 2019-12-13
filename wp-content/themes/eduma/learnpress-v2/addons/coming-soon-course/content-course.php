<?php
/**
 * Template for displaying course content within the loop
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$message = '';
$course  = LP()->global['course'];

$theme_options_data = get_theme_mods();
$class = isset($theme_options_data['thim_learnpress_cate_grid_column']) && $theme_options_data['thim_learnpress_cate_grid_column'] ? 'course-grid-'.$theme_options_data['thim_learnpress_cate_grid_column'] : 'course-grid-3';
$class .= ' lpr_course coming-soon';

if ( learn_press_is_coming_soon( $course->id ) && '' !== ( $message = get_post_meta( $course->id, '_lp_coming_soon_msg', true ) ) ) {
	$message = strip_tags( $message );
}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
	<div class="course-item">
		<?php learn_press_course_thumbnail(); ?>
		<div class="thim-course-content">
			<?php
			learn_press_course_instructor();
			the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			do_action( 'learn_press_after_the_title' );
			?>
			<div class="course-meta">
				<?php learn_press_course_instructor(); ?>
				<?php thim_course_ratings(); ?>
				<?php learn_press_course_students(); ?>
				<?php thim_course_ratings_count(); ?>
				<?php learn_press_course_price(); ?>
			</div>
			<div class="course-description">
				<?php
				do_action( 'learn_press_before_course_content' );
				echo thim_excerpt(30);
				do_action( 'learn_press_after_course_content' );
				?>
			</div>
			<?php learn_press_course_price(); ?>
			<div class="course-readmore">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
			</div>
		</div>
	</div>
</div>