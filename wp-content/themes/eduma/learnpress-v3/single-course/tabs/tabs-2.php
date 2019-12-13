<?php
/**
 * Template for displaying tab nav of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/tabs/tabs.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$tabs = learn_press_get_course_tabs();
?>

<?php foreach ( $tabs as $key => $tab ) { ?>
    <div id="<?php echo esc_attr( $tab['id'] ); ?>" class="row_content_course">
        <div class="sc_heading clone_title  text-left">
            <h2 class="title"><?php echo $tab['title']; ?></h2>
            <div class="clone"><?php echo $tab['title']; ?></div>
        </div>
        <?php
        if ( apply_filters( 'learn_press_allow_display_tab_section', true, $key, $tab ) ) {
            if ( is_callable( $tab['callback'] ) ) {
                call_user_func( $tab['callback'], $key, $tab );
            } else {
                /**
                 * @since 3.0.0
                 */
                do_action( 'learn-press/course-tab-content', $key, $tab );
            }
        }
        ?>
    </div>
<?php }?>