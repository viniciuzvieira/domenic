<?php
/**
 * Template for displaying add-to-cart button
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.2
 */

defined( 'ABSPATH' ) || exit();
$course = LP()->global['course'];
?>
<?php if ( LP()->settings->get( 'woo_purchase_button' ) == 'single' ) { ?>
	<input type="hidden" name="single-purchase" value="yes" />
<?php } else { ?>
	<button class="button purchase-button thim-enroll-course-button button-add-to-cart"><?php _e( 'Add to cart', 'eduma' ); ?></button>
<?php } ?>
<input type="hidden" name="add-to-cart" value="<?php echo $course->id; ?>" />