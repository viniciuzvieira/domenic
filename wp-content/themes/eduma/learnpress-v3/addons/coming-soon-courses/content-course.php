<?php
/**
 * Template for displaying course content within the loop
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$message   = '';
$course    = learn_press_get_course();
$course_id = $course->get_id();

$theme_options_data         = get_theme_mods();
$class                      = isset( $theme_options_data['thim_learnpress_cate_grid_column'] ) && $theme_options_data['thim_learnpress_cate_grid_column'] ? 'course-grid-' . $theme_options_data['thim_learnpress_cate_grid_column'] : 'course-grid-3';
$class                      .= ' lpr_course';
$course_des                 = get_post_meta( $course_id, '_lp_coming_soon_msg', true );
$course_item_excerpt_length = get_theme_mod( 'thim_learnpress_excerpt_length', 25 );
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
	<div class="course-item">
		<?php
		// @since 3.0.0
		do_action( 'learn-press/before-courses-loop-item' );
		?>
		<?php
		// @thim
		do_action( 'thim_courses_loop_item_thumb' );
		?>
		<div class="thim-course-content">
			<?php
			learn_press_courses_loop_item_instructor();
			the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			?>

			<?php if ( intval( $course_item_excerpt_length ) && $course_des ): ?>
				<div class="course-description">
					<?php echo wp_trim_words( $course_des, $course_item_excerpt_length ); ?>
				</div>
			<?php endif; ?>

			<div class="message message-warning learn-press-message coming-soon-message">
				<?php esc_html_e( 'Coming soon', 'eduma' ) ?>
			</div>
			<div class="course-readmore">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
			</div>
		</div>
	</div>
</div>
