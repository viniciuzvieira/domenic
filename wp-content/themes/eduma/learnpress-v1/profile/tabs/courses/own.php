<?php
/**
 * User Courses own
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !user_can( $user->ID, 'edit_lp_courses' ) ) {
	return;
}
global $post;

?>

	<h3 class="box-title"><?php echo esc_html__( 'Own Courses', 'eduma' ); ?></h3>

<?php if ( $courses ) : ?>

	<div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="3" data-pagination="0" data-navigation="1">

		<?php foreach ( $courses as $post ): ?>
			<?php setup_postdata( $post ); ?>
			<?php learn_press_get_template( 'profile/tabs/courses/loop.php', array( 'subtab' => 'own' ) ); ?>

		<?php endforeach; ?>

	</div>

<?php else: ?>

	<?php learn_press_display_message( esc_html__( 'No published courses.', 'eduma' ), 'notice' ); ?>

<?php endif ?>

<?php wp_reset_postdata(); // do not forget to call this function here! ?>