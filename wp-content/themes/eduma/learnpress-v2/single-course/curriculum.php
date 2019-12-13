<?php
/**
 * Template for displaying the curriculum of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$course = LP()->global['course'];
$user   = learn_press_get_current_user();
$theme_options_data = get_theme_mods();
$style_content = isset($theme_options_data['thim_layout_content_page']) ? $theme_options_data['thim_layout_content_page'] : 'normal';

?>
<div class="course-curriculum" id="learn-press-course-curriculum">
	<div class="thim-curriculum-buttons">
		<?php do_action('thim_begin_curriculum_button');?>

        <?php if( $style_content != 'new-1' ) {?>
            <?php
            if ( $user->has( 'finished-course', $course->id ) ): ?>
                <?php if ( $count = $user->can( 'retake-course', $course->id ) ): ?>
                    <button
                            class="button button-retake-course"
                            data-course_id="<?php echo esc_attr( $course->id ); ?>"
                            data-security="<?php echo esc_attr( wp_create_nonce( sprintf( 'learn-press-retake-course-%d-%d', $course->id, $user->id ) ) ); ?>" data-block-content="no">
                        <?php echo esc_html( sprintf( __( 'Retake course (+%d)', 'eduma' ), $count ) ); ?>
                    </button>
                <?php endif; ?>
                <?php
            elseif ( $user->has( 'enrolled-course', $course->id ) ) : ?>
                <?php
                $can_finish = $user->can_finish_course( $course->id );
                $finish_course_security = wp_create_nonce( sprintf( 'learn-press-finish-course-' . $course->id . '-' . $user->id ) );
                ?>
                <button
                        id="learn-press-finish-course"
                        class="button-finish-course<?php echo !$can_finish ? ' hide-if-js' : ''; ?>"
                        data-id="<?php echo esc_attr( $course->id ); ?>"
                        data-security="<?php echo esc_attr( $finish_course_security ); ?>" data-block-content="no">
                    <?php esc_html_e( 'Finish course', 'eduma' ); ?>
                </button>
            <?php endif; ?>
        <?php } ?>

		<?php do_action('thim_end_curriculum_button');?>

	</div>	

	<?php do_action( 'learn_press_before_single_course_curriculum' ); ?>

	<?php if ( $curriculum = $course->get_curriculum() ): ?>

		<ul class="curriculum-sections">

			<?php foreach ( $curriculum as $section ) : ?>

				<?php learn_press_get_template( 'single-course/loop-section.php', array( 'section' => $section ) ); ?>

			<?php endforeach; ?>

		</ul>

	<?php else: ?>
		<?php learn_press_display_message( esc_html__( 'Curriculum is empty.', 'eduma' ), 'notice' ); ?>
	<?php endif; ?>

	<?php do_action( 'learn_press_after_single_course_curriculum' ); ?>

</div>