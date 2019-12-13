<?php
/**
 * Template for displaying archive course content
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.0.6
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post, $wp_query;

if ( is_tax() ) {
	$total = get_queried_object();
	$total = $total->count;
} elseif ( !empty( $_REQUEST['s'] ) ) {
	$total = $wp_query->found_posts;
} else {
	$total = wp_count_posts( 'lp_course' );
	$total = $total->publish;
}

if ( $total == 0 ) {
	echo '<p class="message message-error">' . esc_html__( 'There are no available courses!', 'eduma' ) . '</p>';
	return;
} elseif ( $total == 1 ) {
	$index = esc_html__( 'Showing only one result', 'eduma' );
} else {
	$courses_per_page = absint( LP()->settings->get( 'archive_course_limit' ) );
	$paged            = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

	$from = 1 + ( $paged - 1 ) * $courses_per_page;
	$to   = ( $paged * $courses_per_page > $total ) ? $total : $paged * $courses_per_page;

	if ( $from == $to ) {
		$index = sprintf(
			esc_html__( 'Showing last course of %s results', 'eduma' ),
			$total
		);
	} else {
		$index = sprintf(
			esc_html__( 'Showing %s-%s of %s results', 'eduma' ),
			$from,
			$to,
			$total
		);
	}
}

?>

<?php do_action( 'learn_press_before_main_content' ); ?>

<?php do_action( 'learn_press_archive_description' ); ?>

<?php if ( LP()->wp_query->have_posts() ) : ?>

	<div class="thim-course-top">
		<div class="thim-course-switch-layout">
			<a href="#" class="list switchToGrid "><i class="fa fa-th-large"></i></a>
			<a href="#" class="grid switchToList"><i class="fa fa-list-ul"></i></a>
		</div>
		<div class="course-index">
			<span><?php echo( $index ); ?></span>
		</div>
		<div class="courses-searching">
			<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text" value="" name="s" placeholder="<?php esc_html_e( 'Search our courses', 'eduma' ) ?>" class="thim-s form-control courses-search-input" autocomplete="off" />
				<input type="hidden" value="course" name="ref" />
				<button type="submit"><i class="fa fa-search"></i></button>
				<span class="widget-search-close"></span>
			</form>
			<ul class="courses-list-search list-unstyled"></ul>
		</div>
	</div>

	<?php do_action( 'learn_press_before_courses_loop' ); ?>

	<div id="thim-course-archive" class="thim-course-grid">
		<?php while ( LP()->wp_query->have_posts() ) : LP()->wp_query->the_post(); ?>

			<?php learn_press_get_template_part( 'content', 'course' ); ?>

		<?php endwhile; ?>
	</div>

	<?php do_action( 'learn_press_after_courses_loop' ); ?>

<?php else: ?>
	<?php learn_press_display_message( __( 'No course found.', 'eduma' ), 'error' ); ?>
<?php endif; ?>

<?php do_action( 'learn_press_after_main_content' ); ?>
