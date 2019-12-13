<?php
/**
 * User Courses finished
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
?>
<h3 class="box-title"><?php echo esc_html__( 'Finished Courses', 'eduma' ); ?></h3>

<?php if ( $courses ) : ?>

	<div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="3" data-pagination="0" data-navigation="1">

		<?php foreach ( $courses as $post ): setup_postdata( $post ); ?>
			<?php setup_postdata($post);?>
			<?php learn_press_get_template( 'profile/tabs/courses/loop.php', array( 'subtab' => 'finished' ) ); ?>

		<?php endforeach; ?>
	</div>

<?php else: ?>

	<?php learn_press_display_message( esc_html__( 'No finished courses.', 'eduma' ), 'notice' ); ?>

<?php endif ?>

<?php wp_reset_postdata(); // do not forget to call this function here! ?>