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
?>

<?php $tabs = learn_press_get_course_tabs(); ?>

<?php
if ( empty( $tabs ) ) {
	return;
}

//xxx($tabs);
?>

<div id="learn-press-course-tabs" class="course-tabs">

    <ul class="nav nav-tabs">
        <?php foreach ( $tabs as $key => $tab ) { ?>
            <?php
            $classes = array( 'course-nav-tab-' . esc_attr( $key ) );
            if ( ! empty( $tab['current'] ) && $tab['current'] ) {
                $classes[] = 'active';
            }
            ?>
            <li role="presentation" class="<?php echo join( ' ', $classes ); ?>">
                <a href="#<?php echo esc_attr( $tab['id'] ); ?>" data-toggle="tab">
                    <i class="fa <?php echo $tab['icon']; ?>"></i>
                    <span><?php echo $tab['title']; ?></span>
                </a>
            </li>
        <?php }?>
    </ul>

    <div class="tab-content">
        <?php foreach ( $tabs as $key => $tab ) { ?>
            <div class="tab-pane course-tab-panel-<?php echo esc_attr( $key ); ?> course-tab-panel<?php echo ! empty( $tab['current'] ) && $tab['current'] ? ' active' : ''; ?>"
                 id="<?php echo esc_attr( $tab['id'] ); ?>">
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
        <?php } ?>

    </div>

</div>