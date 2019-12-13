<?php
/**
 * Template for displaying order comment.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/order-comment.php.
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

<div class="learn-press-checkout-comment">

    <h3 class="title"><?php _e( 'Additional Information', 'eduma' ); ?></h3>

    <textarea name="order_comments" class="order-comments"
              placeholder="<?php _e( 'Note to administrator', 'eduma' ); ?>"></textarea>

</div>
