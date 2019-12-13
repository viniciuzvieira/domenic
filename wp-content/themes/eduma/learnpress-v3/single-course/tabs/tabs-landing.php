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

$course = LP()->global['course'];
$user   = learn_press_get_current_user();
?>

<?php $tabs = learn_press_get_course_tabs(); ?>

<?php
if ( empty( $tabs ) ) {
	return;
}

?>

<div class="thim-course-menu-landing">
    <div class="container">
        <ul class="thim-course-landing-tab">
            <?php foreach ( $tabs as $key => $tab ) { ?>
                <?php
                $classes = array( 'course-nav-tab-' . esc_attr( $key ) );
                if ( ! empty( $tab['current'] ) && $tab['current'] ) {
                    $classes[] = 'active';
                }
                ?>
                <li role="presentation" class="<?php echo join( ' ', $classes ); ?>">
                    <a href="#<?php echo esc_attr( $tab['id'] ); ?>"><?php echo $tab['title']; ?></a>
                </li>
            <?php }?>
        </ul>
        <div class="thim-course-landing-button">
            <?php do_action( 'thim_single_course_payment' );?>
        </div>
    </div>
</div>