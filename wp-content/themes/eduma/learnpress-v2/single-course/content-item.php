<?php
/**
 * Display content item
 *
 * @author  ThimPress
 * @version 2.1.7
 */
$course = learn_press_get_the_course();
$item   = LP()->global['course-item'];
$user   = learn_press_get_current_user();
if ( !$item ) {
	return;
}
$item_id = isset( $item->id ) ? $item->id : ( isset( $item->ID ) ? $item->ID : 0 );

$next_item = $course->get_next_item_html();

$prev_item = $course->get_prev_item_html();

$block_option = get_post_meta( $course->id, '_lp_block_lesson_content', true );
$duration     = $course->get_user_duration_html( $user->id, true );
$user_data = get_userdata( $user->ID );
$admin     = false;
if ( $user_data && in_array( 'administrator', $user_data->roles ) ) {
	$admin = true;
}

?>
<div id="learn-press-content-item">
	<?php
	if ( $user->can( 'view-item', $item->id, $course->id ) ) {
		$item_detail = $item->post;
		if ( $item_detail->post_type === 'lp_lesson' ) {
			if ( ! $admin && $course->is_expired() <= 0 && ( $block_option == 'yes' ) && (get_post_meta($item->id, '_lp_preview', true) !=='yes') ) {

			} else {
				$video_intro = get_post_meta( $item_id, '_lp_lesson_video_intro', true );
				if ( !empty( $video_intro ) ) {
					?>
					<div class="learn-press-video-intro">
						<div class="video-content">
							<?php echo $video_intro; ?>
						</div>
					</div>
					<?php
				} else {
					if ( has_post_thumbnail( $item_id ) ) {

						echo '<div class="lesson-image">' . get_the_post_thumbnail( $item->id, 'full' ) . '</div>';
					}
				}
			}
		}
	}
	?>

	<div class="learn-press-content-item-container">

		<?php learn_press_print_messages(); ?>

		<?php if ( $item ) { ?>
			<?php if ( $user->can( 'view-item', $item->id, $course->id ) ) { ?>

				<h2 class="lesson-heading"><?php echo $item->post->post_title; ?></h2>

				<?php do_action( 'learn_press_course_item_content', $item ); ?>

			<?php } else { ?>

				<?php learn_press_get_template( 'single-course/content-protected.php', array( 'item' => $item ) ); ?>

			<?php } ?>

		<?php } ?>

		<?php if ( $user->can_edit_item( $item_id, $course->id ) ): ?>
			<p class="edit-course-item-link">
				<a href="<?php echo get_edit_post_link( $item_id ); ?>"><i class="fa fa-pencil-square-o"></i> <?php _e( 'Edit item', 'eduma' ); ?>
				</a>
			</p>
		<?php endif; ?>

		<?php if ( !empty( $next_item ) || !empty( $prev_item ) ) : ?>
			<div class="course-item-nav">
				<?php echo !empty( $prev_item ) ? ent2ncr( $prev_item ) : ''; ?>
				<?php echo !empty( $next_item ) ? ent2ncr( $next_item ) : ''; ?>
			</div>
		<?php endif; ?>
		<?php do_action( 'learn_press/after_course_item_content', $item_id, $course->id ); ?>
	</div>
	<script>
		jQuery(function ($) {
			$(window).load(function(){
				//Set can to press esc
				window.parent.can_escape = true;
				window.parent.jQuery('#course-curriculum-popup').removeClass('loading');
			});

			$(document).on('click', '.course-content-lesson-nav .nav-link-item', function (e) {
				e.preventDefault();
				var data_id = $(this).data('nav-id');
				window.parent.jQuery('[data-id="' + data_id + '"]').trigger('click');
			});


			var content_H = $('#learn-press-content-item').height();
			if (window.parent.jQuery(window).width() < 1025) {
				//jQuery('html, body').css('min-height', content_H);
				window.parent.jQuery('#popup-content-inner iframe').css('min-height', content_H);
				//window.parent.jQuery('#popup-content-inner').css('min-height', content_H);
				//console.log(content_H);
			}

			function updateIframe(){
				window.parent.jQuery(window).trigger('resize.update-iframe');
			}

			window.parent.jQuery(window).on('resize.update-iframe', function () {
				var content_newH = $('#learn-press-content-item').height();
				//console.log('new', content_newH);
				if (window.parent.jQuery(window).width() < 1025) {
					//jQuery('html, body').css('min-height', content_newH);
					window.parent.jQuery('#popup-content-inner iframe').css('min-height', content_newH);
					window.parent.jQuery('#course-curriculum-popup').scrollTop(0);
					//window.parent.jQuery('#popup-content-inner').css('min-height', content_newH);

				} else {
					window.parent.jQuery('#popup-content-inner iframe').css('min-height', 0);
				}
			});

			$(document).on('click', 'button, a', function(){
				updateIframe();
			});
			$( document ).ajaxComplete(function(){
				updateIframe();
			});

			//updateIframe when all images loaded
			var imgArr = $('.learn-press-content-item-only img'),
				imgLength = imgArr.length;
			if (imgLength) {
				imgArr.each(function (index, val) {
					$(this).on('load', function () {
						if (index == imgLength - 1) {
							updateIframe();
						}
					});
				});
			}
		})
	</script>
</div>