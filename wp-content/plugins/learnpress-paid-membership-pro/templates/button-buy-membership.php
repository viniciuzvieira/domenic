<?php
defined('ABSPATH') || die('nice try');

extract($args);


do_action('learn_press_pmpro_before_course_notice');

$wrap_class = array(
    'learn-press-pmpro-buy-membership purchase-course'
);
$html       = '<a class="button purchase-button" href="' . esc_url($redirect) . '">' . $buy_through_membership_text . '</a>';
$wrap_class = apply_filters('learn_press_pmpro_wrap_template_attributes', $wrap_class, $course, $levels_page_id, $current_user);
$html       = apply_filters('learn_press_pmpro_template_course_notice', $html, $redirect, $course, $levels_page_id, $current_user);
?>
<div id="learn-press-pmpro-notice" class="<?php echo implode( ' ', $wrap_class ); ?>">
	<?php
            do_action('learn_press_pmpro_before_template_course_notice');
            echo $html;
            do_action('learn_press_pmpro_after_template_course_notice');
    ?>
</div>
<?php
do_action('learn_press_pmpro_after_course_notice');