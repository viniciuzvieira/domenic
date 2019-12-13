<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

    <div id="comment-<?php comment_ID(); ?>" class="comment_container">

		<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '70' ), '' ); ?>

        <div class="comment-text">


			<?php if ( '0' == $comment->comment_approved ) : ?>

                <div class="meta">
                    <div class="waiting-approval"><?php esc_html_e( 'Your comment is awaiting approval', 'eduma' ); ?></div>
                </div>

			<?php else : ?>

                <div class="meta">
                    <h4 class="author" itemprop="author"><?php comment_author(); ?></h4>
					<?php

					if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' ) {
						if ( wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) ) {
							echo '<em class="verified">(' . esc_html__( 'verified owner', 'eduma' ) . ')</em> ';
						}
					}

					?>
                    <div class="date" itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( wc_date_format() ); ?></div>
                </div>

			<?php endif; ?>

			<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>

                <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_html__( 'Rated %d out of 5', 'eduma' ), $rating ); ?>">
                    <span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"></span>
                </div>

			<?php endif; ?>

            <div itemprop="description" class="description"><?php comment_text(); ?></div>
        </div>
    </div>
