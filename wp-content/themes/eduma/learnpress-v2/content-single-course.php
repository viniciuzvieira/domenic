<?php
/**
 * The template for display the content of single course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

$theme_options_data = get_theme_mods();
$style_content = isset($theme_options_data['thim_layout_content_page']) ? $theme_options_data['thim_layout_content_page'] : 'normal';
$review_is_enable = thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' );
$student_list_enable = thim_plugin_active( 'learnpress-students-list/learnpress-students-list.php' );

$course  = learn_press_get_course();//LP()->global['course'];
$user   = learn_press_get_current_user();

$is_enrolled      = $user->has( 'enrolled-course', $course->id );
$require_enrolled = $course->is_require_enrollment();

$buy_through_membership      = LP()->settings->get( 'buy_through_membership' );

$list_course_membership = array();
$hidden_price = false;

if( function_exists('learn_press_pmpro_check_require_template')) {
	$membership_list = learn_press_pmpro_check_require_template();
	$list_course_membership = $membership_list['list_courses'];
}
if( !empty($list_course_membership)) {
	if( array_key_exists($course->id, $list_course_membership ) ) {
		$hidden_price = true;
	}
}

if( !empty( $buy_through_membership )  && $buy_through_membership == 'no' ) {
	$hidden_price = false;
}

?>

<?php if( $style_content == 'new-1' ) {?>
    <div class="content_course_2">
        <div class="row">
            <div class="col-md-9">
                <?php do_action( 'learn_press_before_main_content' ); ?>
                <?php do_action( 'learn_press_before_single_course' ); ?>
                <div class="header_single_content">
                    <span class="bg_header"></span>
                    <?php learn_press_get_template( 'single-course/thumbnail.php', array() ); ?>
                    <div class="course-meta">
                        <?php learn_press_course_instructor(); ?>
                        <?php learn_press_course_categories(); ?>
                        <?php thim_course_number_students(); ?>
                        <?php thim_course_forum_link(); ?>
                        <?php thim_course_ratings_meta(); ?>
                        <?php thim_course_last_update(); ?>
                    </div>
                </div>
                <div class="course-summary">
                    <?php if ( $is_enrolled || $user->has_course_status( $course->id, array( 'enrolled', 'finished' ) ) || !$require_enrolled ) { ?>
                        <?php learn_press_get_template( 'single-course/content-learning-2.php', array() ); ?>
                    <?php } else { ?>
                        <?php learn_press_get_template( 'single-course/content-landing-2.php', array() ); ?>
                    <?php } ?>
                </div>
                <?php thim_related_courses(); ?>
                <?php do_action( 'learn_press_after_single_course' ); ?>
                <?php do_action( 'learn_press_after_main_content' ); ?>
            </div>
            <div id="sidebar" class="col-md-3 sticky-sidebar">
                <div class="course_right">
                    <?php learn_press_course_progress(); ?>
                    <?php if ( !$is_enrolled ) { ?>
                        <div class="course-payment">
                            <?php

                            if ( ( $course->is_free() || !$user->can( 'enroll-course', $course->id ) ) && !$hidden_price ) {
                                learn_press_course_price();
                            }
                            learn_press_course_buttons();

                            ?>
                        </div>
                    <?php } ?>
                    <div class="button_curriculumn">
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
                                    class="button button-finish-course<?php echo !$can_finish ? ' hide-if-js' : ''; ?>"
                                    data-id="<?php echo esc_attr( $course->id ); ?>"
                                    data-security="<?php echo esc_attr( $finish_course_security ); ?>" data-block-content="no">
                                <?php esc_html_e( 'Finish course', 'eduma' ); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                    <?php do_action( 'thim_before_sidebar_course' ); ?>
                    <div class="menu_course">
                        <?php
                        $theme_options_data = get_theme_mods();
                        $group_tab = isset($theme_options_data['group_tabs_course']) ? $theme_options_data['group_tabs_course'] : array('description', 'curriculum', 'instructor', 'review');
                        $active_tab = isset($theme_options_data['default_tab_course']) ? $theme_options_data['default_tab_course'] : 'description';
                        $arr_variable = array();
                        $arr_variable['description'] = array("title"=>esc_html__( 'Description', 'eduma' ), "icon"=>"fa-bookmark");
                        $arr_variable['curriculum'] = array("title"=>esc_html__( 'Curriculum', 'eduma' ), "icon"=>"fa-cube");
                        $arr_variable['instructor'] = array("title"=>esc_html__( 'Instructors', 'eduma' ), "icon"=>"fa-user");
                        $arr_variable['review'] = array("title"=>esc_html__( 'Review', 'eduma' ), "icon"=>"fa-comments");
                        ?>
                        <ul>
                            <?php for( $i=0; $i<count($group_tab); $i++ ) {?>
                                <?php if( $group_tab[$i]!='review' || ( $group_tab[$i]=='review' && $review_is_enable ) ) {?>
                                    <li role="presentation" <?php if($active_tab==$group_tab[$i]) echo 'class="active"';?>>
                                        <?php
                                        //var_dump($arr_variable[$group_tab[$i]]["title"]);
                                        ?>
                                        <a href="#tab-course-<?php echo $group_tab[$i];?>" data-toggle="tab">
                                            <i class="fa <?php echo $arr_variable[$group_tab[$i]]["icon"];?>"></i>
                                            <span><?php echo $arr_variable[$group_tab[$i]]["title"]; ?></span>
                                        </a>
                                    </li>
                                <?php }?>
                            <?php }?>
                            <?php if ( $student_list_enable ) : ?>
                                <li role="presentation">
                                    <a href="#tab-course-student-list" data-toggle="tab">
                                        <i class="fa fa-list"></i>
                                        <span><?php esc_html_e( 'Student List', 'eduma' ); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="social_share">
                        <?php do_action( 'thim_social_share' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {?>

    <?php do_action( 'learn_press_before_main_content' ); ?>

    <?php do_action( 'learn_press_before_single_course' ); ?>

    <?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>

    <?php
    if( $course->is_reached_limit() ) {
        learn_press_display_message( apply_filters( 'learn_press_course enough students_notice', __( 'The class is full so the enrollment is close. Please contact the site admin.', 'learnpress' ) ) );
    }
    ?>

    <div class="course-meta">
        <?php learn_press_course_instructor(); ?>
        <?php learn_press_course_categories(); ?>
        <?php thim_course_forum_link(); ?>
        <?php thim_course_ratings(); ?>
        <?php learn_press_course_progress(); ?>
    </div>

    <?php if ( !$is_enrolled ) { ?>
        <div class="course-payment">
            <?php

            if ( ( $course->is_free() || !$user->can( 'enroll-course', $course->id ) ) && !$hidden_price ) {
                learn_press_course_price();
            }
            learn_press_course_buttons();

            ?>
        </div>
    <?php } ?>

    <?php learn_press_get_template( 'single-course/thumbnail.php', array() ); ?>

    <div class="course-summary">

        <?php if ( $is_enrolled || $user->has_course_status( $course->id, array( 'enrolled', 'finished' ) ) || !$require_enrolled ) { ?>

            <?php learn_press_get_template( 'single-course/content-learning.php', array() ); ?>

        <?php } else { ?>

            <?php learn_press_get_template( 'single-course/content-landing.php', array() ); ?>

        <?php } ?>

    </div>

    <?php //endif; ?>

    <?php thim_related_courses(); ?>

    <?php do_action( 'learn_press_after_single_course' ); ?>

    <?php do_action( 'learn_press_after_main_content' ); ?>

<?php }?>