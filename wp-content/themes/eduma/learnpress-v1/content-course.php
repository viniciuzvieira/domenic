<?php
/**
 * Template for displaying course content within the loop
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */
$theme_options_data = get_theme_mods();
$class = isset($theme_options_data['thim_learnpress_cate_grid_column']) && $theme_options_data['thim_learnpress_cate_grid_column'] ? 'course-grid-'.$theme_options_data['thim_learnpress_cate_grid_column'] : 'course-grid-3';
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$class .= ' lpr_course';

?>
<div id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
	<?php do_action( 'learn_press_before_courses_loop_item' ); ?>
	<div class="course-item">
		<?php learn_press_course_thumbnail(); ?>
		<div class="thim-course-content">
			<?php
			learn_press_course_instructor();
			do_action( 'learn_press_before_the_title' );
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
	<?php do_action( 'learn_press_after_courses_loop_item' ); ?>
</div>
