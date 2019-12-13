<?php
/**
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$user         = learn_press_get_current_user();
$course = LP_Course::get_course( $post->ID );
$count_student = $course->count_users_enrolled( 'append' ) ? $course->count_users_enrolled( 'append' ) : 0;

?>
<div class="course-item">
	<div class="course-thumbnail">
		<a href="<?php echo get_the_permalink(); ?>">
			<?php
			echo thim_get_feature_image( get_post_thumbnail_id(), 'full', 450, 450, get_the_title() );
			?>
		</a>
		<?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
	</div>
	<div class="thim-course-content">
		<div class="course-author">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?>
			<div class="author-contain">
				<div class="value">
					<a href="<?php echo esc_url( learn_press_user_profile_link( get_the_author_meta( 'ID' ) ) ); ?>">
						<?php echo get_the_author(); ?>
					</a>
				</div>
			</div>
		</div>
		<?php do_action( 'learn_press_before_enrolled_course_title' ); ?>
		<h2 class="course-title">
			<a rel="bookmark" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
		</h2>
		<?php do_action( 'learn_press_after_enrolled_course_title' ); ?>
		<?php
		if( $view_all ) {
			$args = array(
				'user'      => $user,
				'course_id' => $post->ID
			);
			learn_press_get_template( 'profile/tabs/courses/progress.php', $args );
		}
		?>
		<div class="course-meta">
			<div class="course-students">
				<label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
				<?php do_action( 'learn_press_begin_course_students' ); ?>

				<div class="value"><i class="fa fa-group"></i>
					<?php echo esc_html( $count_student ); ?>
				</div>
				<?php do_action( 'learn_press_end_course_students' ); ?>

			</div>
			<?php thim_course_ratings_count( $course->ID ); ?>
			<div class="course-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<?php if ( $course->is_free() ) : ?>
					<div class="value free-course" itemprop="price" content="<?php esc_attr_e( 'Free', 'eduma' ); ?>">
						<?php esc_html_e( 'Free', 'eduma' ); ?>
					</div>
				<?php else:
					$price = $course->get_price_html();
					$origin_price = $course->get_origin_price_html();
					?>
					<div class="value " itemprop="price" content="<?php echo esc_attr( $price ); ?>">
						<?php
						echo esc_html( $price );
						if ( $price != $origin_price ) {
							echo '<span class="course-origin-price">' . $origin_price . '</span>';
						}
						?>
					</div>
				<?php endif; ?>
				<meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency(); ?>" />
			</div>
		</div>
	</div>
</div>