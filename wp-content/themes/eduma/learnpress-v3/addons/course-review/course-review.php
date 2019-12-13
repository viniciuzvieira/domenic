<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$course_id     = get_the_ID();

$course_review = learn_press_get_course_review( $course_id, isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1, 5, true );
if ( $course_review['total'] ) {
	$reviews = $course_review['reviews'];
	?>
	<div class="course-review">
		<div id="course-reviews" class="content-review">
			<ul class="course-reviews-list">
				<?php foreach ( $reviews as $review ) : ?>
					<?php
					learn_press_course_review_template( 'loop-review.php', array( 'review' => $review ) );
					?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php if ( empty( $course_review['finish'] ) ) : ?>
		<div class="review-load-more">
                <span id="course-review-load-more" data-paged="<?php echo esc_attr( $course_review['paged'] ); ?>"><i
		                class="fa fa-angle-double-down"></i></span>
		</div>
	<?php endif; ?>
	<?php
}