<?php
/**
 * Template for displaying collection content within the loop
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$theme_options_data = get_theme_mods();
$class              = isset( $theme_options_data['thim_learnpress_cate_grid_column'] ) && $theme_options_data['thim_learnpress_cate_grid_column'] ? 'course-grid-' . $theme_options_data['thim_learnpress_cate_grid_column'] : 'course-grid-3';

$class .= ' lpr_course';
$course        = LP_Global::course();
$course_id = $course->get_id();
$course_item_excerpt_length = get_theme_mod( 'thim_learnpress_excerpt_length', 25 );
$course_des                 = get_post_meta( $course_id, '_lp_coming_soon_msg', true );
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
	<?php do_action( 'learn_press_before_courses_loop_item' ); ?>
	<div class="course-item">
                    <?php
                    // @thim
                    do_action( 'thim_courses_loop_item_thumb' );
                    ?>
                    <div class="thim-course-content">
                        <?php if ( thim_plugin_active( 'learnpress-coming-soon-courses/learnpress-coming-soon-courses.php' ) && learn_press_is_coming_soon( $course_id ) ): ?>

                            <?php learn_press_courses_loop_item_instructor(); ?>
                            <?php
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

                        <?php else: ?>

                            <?php learn_press_courses_loop_item_instructor(); ?>
                            <?php
                            the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
                            ?>
                            <div class="course-meta">
                                <?php learn_press_courses_loop_item_instructor(); ?>
                                <?php thim_course_ratings(); ?>
                                <?php learn_press_courses_loop_item_students(); ?>
                                <?php thim_course_ratings_count(); ?>
                                <?php learn_press_courses_loop_item_price(); ?>
                            </div>

                            <div class="course-description">
                                <?php
                                do_action( 'learn_press_before_course_content' );
                                echo thim_excerpt(25);
                                do_action( 'learn_press_after_course_content' );
                                ?>
                            </div>
                            <?php learn_press_courses_loop_item_price(); ?>
                            <div class="course-readmore">
                                <a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
	<?php do_action( 'learn_press_after_courses_loop_item' ); ?>

</div>