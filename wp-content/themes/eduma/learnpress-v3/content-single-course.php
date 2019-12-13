<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-single-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

$course = LP()->global['course'];
$user   = learn_press_get_current_user();
$is_enrolled = $user->has_enrolled_course($course->get_id());

/**
 * @deprecated
 */
do_action( 'learn_press_before_main_content' );
do_action( 'learn_press_before_single_course' );
do_action( 'learn_press_before_single_course_summary' );

/**
 * @since 3.0.0
 */
do_action( 'learn-press/before-main-content' );

do_action( 'learn-press/before-single-course' );

?>

<?php if( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'new-1' ) {?>

    <div class="content_course_2">

        <div class="row">

            <div class="col-md-9 content-single">

                <div class="learnpress-content learn-press">

                    <div class="header_single_content">

                        <span class="bg_header"></span>

                        <?php do_action( 'thim_single_course_before_meta' );?>

                        <div class="course-meta">

                            <?php do_action( 'thim_single_course_meta' );?>

                        </div>

                    </div>

                </div>

                <div class="course-summary">
                    <?php
                    /**
                     * @since 3.0.0
                     *
                     * @see learn_press_single_course_summary()
                     */
                    do_action( 'learn-press/single-course-summary' );
                    ?>
                </div>
                <?php thim_related_courses(); ?>

            </div>

            <div id="sidebar" class="col-md-3 sticky-sidebar">

                <div class="course_right">

                    <?php learn_press_course_progress(); ?>

                    <div class="course-payment">

                        <?php do_action( 'thim_single_course_payment' );?>

                    </div>

                    <?php do_action( 'thim_before_sidebar_course' ); ?>

                    <div class="menu_course">
                        <?php
                        $tabs = learn_press_get_course_tabs();
                        ?>
                        <ul>
                            <?php foreach ( $tabs as $key => $tab ) { ?>
                                <li role="presentation">
                                    <a href="#<?php echo esc_attr( $tab['id'] ); ?>" data-toggle="tab">
                                        <i class="fa <?php echo $tab['icon']; ?>"></i>
                                        <span><?php echo $tab['title']; ?></span>
                                    </a>
                                </li>
                            <?php }?>
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

    <div id="learn-press-course" class="course-summary learn-press">

        <?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>

        <div class="course-meta">
            <?php do_action( 'thim_single_course_meta' );?>
        </div>
        <div class="course-payment">
            <?php do_action( 'thim_single_course_payment' );?>
        </div>
        <div class="course-summary">
            <?php
            /**
             * @since 3.0.0
             *
             * @see learn_press_single_course_summary()
             */
            do_action( 'learn-press/single-course-summary' );
            ?>
	        <div class="social_share">
		        <?php do_action( 'thim_social_share' ); ?>
	        </div>
        </div>
        <?php thim_related_courses(); ?>
    </div>

<?php }?>

<?php

/**
 * @since 3.0.0
 */
do_action( 'learn-press/after-main-content' );

do_action( 'learn-press/after-single-course' );

/**
 * @deprecated
 */
do_action( 'learn_press_after_single_course_summary' );
do_action( 'learn_press_after_single_course' );
do_action( 'learn_press_after_main_content' );
?>
