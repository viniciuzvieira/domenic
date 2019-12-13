<?php
/**
 * Template for displaying add review form
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit();

?>
<div class="add-review">
    <h3 class="title"><?php esc_html_e( 'Leave A Review', 'eduma' ); ?></h3>

    <p class="description"><?php esc_html_e( 'Please provide as much detail as you can to justify your rating and to help others.', 'eduma' ); ?></p>
    <?php do_action( 'learn_press_before_review_fields' ); ?>
    <form method="post">
        <div>
            <label for="review-title"><?php esc_html_e( 'Title', 'eduma' ); ?>
                <span class="required">*</span></label>
            <input required type="text" id="review-title" name="review-course-title"/>
        </div>
        <div>

            <label><?php esc_html_e( 'Rating', 'eduma' ); ?>
                <span class="required">*</span></label>

            <div class="review-stars-rated">
                <ul class="review-stars">
                    <li><span class="fa fa-star-o"></span></li>
                    <li><span class="fa fa-star-o"></span></li>
                    <li><span class="fa fa-star-o"></span></li>
                    <li><span class="fa fa-star-o"></span></li>
                    <li><span class="fa fa-star-o"></span></li>
                </ul>
                <ul class="review-stars filled" style="width: 100%">
                    <li><span class="fa fa-star"></span></li>
                    <li><span class="fa fa-star"></span></li>
                    <li><span class="fa fa-star"></span></li>
                    <li><span class="fa fa-star"></span></li>
                    <li><span class="fa fa-star"></span></li>
                </ul>
            </div>
        </div>
        <div>
            <label for="review-content"><?php esc_html_e( 'Comment', 'eduma' ); ?>
                <span class="required">*</span></label>
            <textarea required id="review-content" name="review-course-content"></textarea>
        </div>
        <input type="hidden" id="review-course-value" name="review-course-value" value="5"/>
        <input type="hidden" id="comment_post_ID" name="comment_post_ID"
               value="<?php echo get_the_ID(); ?>"/>
        <button type="submit"><?php esc_html_e( 'Submit Review', 'eduma' ); ?></button>
    </form>
    <?php do_action( 'learn_press_after_review_fields' ); ?>
</div>