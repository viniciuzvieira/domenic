<?php
/**
 * User Courses tab
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$args              = array(
	'user' => $user
);

$limit             = LP()->settings->get( 'profile_courses_limit', 10 );
$limit             = apply_filters( 'learn_press_profile_tab_courses_all_limit', $limit );
$courses           = $user->get( 'courses', array( 'limit' => $limit ) );
$num_pages         = learn_press_get_num_pages( $user->_get_found_rows(), $limit );
$args['courses']   = $courses;
$args['num_pages'] = $num_pages;

$current_user = get_current_user_id();

$profile_id = isset( $user->id ) ? $user->id : 0 ;

$view_all = false;

if( $current_user == $profile_id || current_user_can('manage_options') ) {
	$view_all = true;
}

$own_courses = $user->get_own_courses();


if ( $courses ) {
	?>
	<div class="learn-press-subtab-content" style="display: block">
		<div class="learn-press-courses profile-courses courses-list thim-course-grid">
			<?php
			foreach ( $courses as $post ) {
				if ( array_key_exists( $post->ID, $own_courses ) || $view_all ) {
					setup_postdata( $post );
					learn_press_get_template( 'profile/tabs/courses/loop.php', array( 'user' => $user, 'course_id' => $post->ID , 'view_all' => $view_all) );
					wp_reset_postdata();
				}
			}
			?>
		</div>

		<nav class="learn-press-pagination navigation pagination">
			<?php
			echo paginate_links( apply_filters( 'learn_press_pagination_args', array(
				'base'         => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ),
				'format'       => '',
				'add_args'     => '',
				'current'      => max( 1, get_query_var( 'paged' ) ),
				'total'        => $num_pages,
				'prev_text'    => '&larr;',
				'next_text'    => '&rarr;',
				'type'         => 'list',
				'end_size'     => 3,
				'mid_size'     => 3
			) ) );
			?>
		</nav>

	</div>
	<?php
} else {
	learn_press_display_message( __( 'You haven\'t got any courses yet!', 'eduma' ) );
}