<?php
/**
 * Template for displaying content of landing course
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$course    = learn_press_get_course();
$course_id = $course->get_id();

?>

<?php if( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'new-1' ) {?>

    <div class="content_course_2">

        <div class="row">

            <div class="<?php if( 'yes' == get_post_meta( $course_id, '_lp_coming_soon_metadata', true ) ) echo 'col-md-9'; else echo 'col-md-12'; ?>">

                <div id="lp-single-course" class="learnpress-content learn-press">

                    <div class="header_single_content">

                        <span class="bg_header"></span>

                        <div class="thim-top-course<?php echo !has_post_thumbnail($course_id) ? ' no-thumbnail' : ''; ?>">
                            <?php if( 'yes' !== get_post_meta( $course_id, '_lp_coming_soon_countdown', true ) ) {?>
                                <?php do_action( 'learn_press_content_coming_soon_countdown' ); ?>
                            <?php }?>
                            <?php do_action( 'thim_single_course_before_meta' );?>
                        </div>

                        <?php if( 'yes' == get_post_meta( $course_id, '_lp_coming_soon_metadata', true ) ) {?>

                        <div class="course-meta">

                            <?php do_action( 'thim_single_course_meta' );?>

                        </div>

                        <?php }?>

                    </div>

                </div>

                <?php if( 'yes' == get_post_meta( $course_id, '_lp_coming_soon_details', true ) ) {?>

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

                <?php }?>

                <div class="message message-warning learn-press-message coming-soon-message"><?php do_action( 'learn_press_content_coming_soon_message' ); ?></div>

            </div>

            <?php if( 'yes' === get_post_meta( $course_id, '_lp_coming_soon_metadata', true ) ) {?>

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

            <?php }?>

        </div>

    </div>

<?php } else { ?>

    <div id="learn-press-course" class="course-summary learn-press coming-soon-detail">

    <?php do_action( 'learn_press_before_content_coming_soon' ); ?>

    <?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>

    <?php if( 'yes' == get_post_meta( $course_id, '_lp_coming_soon_metadata', true ) ) {?>

        <div class="course-meta">
            <?php do_action( 'thim_single_course_meta' );?>
        </div>
        <div class="course-payment">
            <?php do_action( 'thim_single_course_payment' );?>
        </div>

    <?php }?>

    <div class="thim-top-course<?php echo !has_post_thumbnail($course_id) ? ' no-thumbnail' : ''; ?>">
        <?php if( 'yes' == get_post_meta( $course_id, '_lp_coming_soon_countdown', true ) ) {?>
            <?php do_action( 'learn_press_content_coming_soon_countdown' ); ?>
        <?php }?>
        <?php learn_press_get_template( 'single-course/thumbnail.php', array() ); ?>
    </div>

        <?php if( 'yes' == get_post_meta( $course_id, '_lp_coming_soon_details', true ) ) {?>

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

        <?php }?>

        <div class="message message-warning learn-press-message coming-soon-message"><?php do_action( 'learn_press_content_coming_soon_message' ); ?></div>

    <?php do_action( 'learn_press_after_content_coming_soon' ); ?>

    </div>

<?php }?>
