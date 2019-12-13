<?php
/**
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       1.0
 */
defined( 'ABSPATH' ) || exit();

$user = learn_press_get_current_user();
if ( !$user->can_view_item( $item ) ) {
	return;
}
?>
<div class="course-content-lesson-nav course-item-next">
	<span><?php esc_html_e( 'Next', 'eduma' ); ?></span>
	<a class="js-action nav-link-item" data-nav-id="<?php echo $item; ?>"  href="<?php echo esc_url( $course->get_item_link( $item ) . '?content-item-only=yes' ); ?>"><?php echo get_the_title( $item ); ?></a>
</div>