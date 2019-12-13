<?php
/**
 * User Courses enrolled
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.1.4.2
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php
$course_id = absint( $course_id );
/* Check status course */
$all_status             = learn_press_get_subtabs_course();
$class_loop             = array( 'lpr_course', 'course-grid-3' );
$str_status             = '';
if( $view_all ) {
    $class_loop[] = 'hidden-meta-border';
}

foreach ( $all_status as $key => $status ) {
    $slug           = preg_replace('/\s+|-+/', '_', $key);
    $is_status      = false;
    switch ($key) {
        case 'purchased':
            $is_status  = $user->has_course_status( $course_id, array( 'enrolled' ) );
            break;

        case 'learning':
            $is_status  = $user->has_course_status( $course_id, array( 'started' ) );
            break;

        case 'finished':
            $is_status  = $user->has_finished_course( $course_id ) ;
            break;

        case 'own':
            $is_status = absint( get_post_field( 'post_author' ) ) == $course_id;
            break;
    }

    if ( $is_status ) {
        $class_loop[] = 'learpress-status-' .esc_attr( $slug );
    }

    if ( in_array( 'learpress-status-finished', $class_loop ) ) {
//        $str_status =  __( 'Review', 'learnpress' );
    }
    else if ( in_array( 'learpress-status-purchased', $class_loop ) ) {
        $str_status = __( 'Continue', 'eduma' );
    }
}

?>

<div class="<?php echo implode( ' ', $class_loop ); ?>">
	<?php

	learn_press_get_template( 'profile/tabs/courses/index.php', array( 'view_all' => $view_all ) );

	?>
</div>

<?php
