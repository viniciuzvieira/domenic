<?php
/**
 * Template for displaying the curriculum of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;

$section_index = 1;

?>
<div class="curriculum-sections course-curriculum" id="learn-press-course-curriculum">

	<?php do_action( 'learn_press_before_single_course_curriculum' ); ?>

	<?php if ( $curriculum = $course->get_curriculum() ): ?>


		<ul class="curriculum-sections">

			<?php foreach ( $curriculum as $section ) : ?>

				<?php
				$section->section_index = $section_index;
				learn_press_get_template(
					'single-course/loop-section.php',
					array(
						'section'       => $section,
					)
				); ?>
				<?php $section_index ++; ?>
			<?php endforeach; ?>

		</ul>

	<?php else: ?>
		<?php echo apply_filters( 'learn_press_course_curriculum_empty', __( 'Curriculum is empty', 'eduma' ) ); ?>
	<?php endif; ?>

	<?php do_action( 'learn_press_after_single_course_curriculum' ); ?>

</div>