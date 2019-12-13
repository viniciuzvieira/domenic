<?php
/**
 * Template for displaying archive course content
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */


if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post, $wp_query;

$show_description = get_theme_mod( 'thim_learnpress_cate_show_description' );
$show_desc   = !empty( $show_description ) ? $show_description : '';
$cat_desc = term_description();

$total = $wp_query->found_posts;

if ( $total == 0 ) {
	$message = '<p class="message message-error">' . esc_html__( 'No courses found!', 'eduma' ) . '</p>';
	$index   = esc_html__( 'There are no available courses!', 'eduma' );
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

$cookie_name = 'course_switch';
$layout      = ( !empty( $_COOKIE[$cookie_name] ) ) ? $_COOKIE[$cookie_name] : 'grid-layout';

?>

<?php do_action( 'learn_press_before_main_content' ); ?>

<?php do_action( 'learn_press_archive_description' ); ?>

<?php if ( $total == 0 ) : ?>
	<div class="thim-course-top switch-layout-container">
		<div class="thim-course-switch-layout switch-layout">
			<a href="#" class="list switchToGrid<?php echo ( $layout == 'grid-layout' ) ? ' switch-active' : ''; ?>"><i class="fa fa-th-large"></i></a>
			<a href="#" class="grid switchToList<?php echo ( $layout == 'list-layout' ) ? ' switch-active' : ''; ?>"><i class="fa fa-list-ul"></i></a>
		</div>
		<div class="course-index">
			<span><?php echo( $index ); ?></span>
		</div>
		<div class="courses-searching">
			<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'lp_course' ) ); ?>">
				<input type="text" value="" name="s" placeholder="<?php esc_attr_e( 'Search our courses', 'eduma' ) ?>" class="form-control course-search-filter" autocomplete="off" />
				<input type="hidden" value="course" name="ref" />
				<button type="submit"><i class="fa fa-search"></i></button>
				<span class="widget-search-close"></span>
			</form>
			<ul class="courses-list-search list-unstyled"></ul>
		</div>
	</div>
	<?php if( $show_desc && $cat_desc ) {?>
		<div class="desc_cat">
			<?php echo $cat_desc;?>
		</div>
	<?php }?>
	<div id="thim-course-archive" class="<?php echo ( $layout == 'list-layout' ) ? 'thim-course-list' : 'thim-course-grid'; ?>" data-cookie="grid-layout">
		<?php echo $message; ?>
	</div>

<?php else: ?>
	<?php if ( $wp_query->have_posts() ) : ?>

		<div class="thim-course-top switch-layout-container <?php if( $show_desc && $cat_desc ) echo 'has_desc';?>">
			<div class="thim-course-switch-layout switch-layout">
				<a href="#" class="list switchToGrid<?php echo ( $layout == 'grid-layout' ) ? ' switch-active' : ''; ?>"><i class="fa fa-th-large"></i></a>
				<a href="#" class="grid switchToList<?php echo ( $layout == 'list-layout' ) ? ' switch-active' : ''; ?>"><i class="fa fa-list-ul"></i></a>
			</div>
			<div class="course-index">
				<span><?php echo( $index ); ?></span>
			</div>
			<div class="courses-searching">
				<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'lp_course' ) ); ?>">
					<input type="text" value="" name="s" placeholder="<?php esc_attr_e( 'Search our courses', 'eduma' ) ?>" class="form-control course-search-filter" autocomplete="off" />
					<input type="hidden" value="course" name="ref" />
					<button type="submit"><i class="fa fa-search"></i></button>
					<span class="widget-search-close"></span>
				</form>
				<ul class="courses-list-search list-unstyled"></ul>
			</div>
		</div>

		<?php do_action( 'learn_press_before_courses_loop' ); ?>

		<?php if( $show_desc && $cat_desc ) {?>
			<div class="desc_cat">
				<?php echo $cat_desc;?>
			</div>
		<?php }?>

		<div id="thim-course-archive" class="<?php echo ( $layout == 'list-layout' ) ? 'thim-course-list' : 'thim-course-grid'; ?>" data-cookie="grid-layout">
			<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

				<?php learn_press_get_template_part( 'content', 'course' ); ?>

			<?php endwhile; ?>
		</div>

		<?php do_action( 'learn_press_after_courses_loop' ); ?>

	<?php endif; ?>
<?php endif; ?>


<?php do_action( 'learn_press_after_main_content' ); ?>


