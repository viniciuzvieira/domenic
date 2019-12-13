<?php
/**
 * Template for displaying content of learning course
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$review_is_enable = thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' );
$student_list_enable = thim_plugin_active( 'learnpress-students-list/learnpress-students-list.php' );
$course = LP()->global['course'];
$hide_students_list = get_post_meta( $course->ID, '_lp_hide_students_list', true );

$theme_options_data = get_theme_mods();
$group_tab = isset($theme_options_data['group_tabs_course']) ? $theme_options_data['group_tabs_course'] : array('description', 'curriculum', 'instructor', 'review');
$active_tab = isset($theme_options_data['default_tab_course']) ? $theme_options_data['default_tab_course'] : 'description';
if( in_array( 'curriculum', $group_tab ) )
    $active_tab = 'curriculum';
$arr_variable = array();
$arr_variable['description'] = array("title"=>esc_html__( 'Description', 'eduma' ), "icon"=>"fa-bookmark");
$arr_variable['curriculum'] = array("title"=>esc_html__( 'Curriculum', 'eduma' ), "icon"=>"fa-cube");
$arr_variable['instructor'] = array("title"=>esc_html__( 'Instructors', 'eduma' ), "icon"=>"fa-user");
$arr_variable['review'] = array("title"=>esc_html__( 'Review', 'eduma' ), "icon"=>"fa-comments");
?>

<?php do_action( 'learn_press_before_content_learning' );?>

<div class="course-learning-summary">

	<?php do_action( 'learn_press_content_learning_summary' ); ?>

</div>
<div id="course-learning">
	<div class="course-tabs">
		<ul class="nav nav-tabs">
			<?php for( $i=0; $i<count($group_tab); $i++ ) {?>
				<?php if( $group_tab[$i]!='review' || ( $group_tab[$i]=='review' && $review_is_enable ) ) {?>
					<li role="presentation" <?php if($active_tab==$group_tab[$i]) echo 'class="active"';?>>
						<?php
						//var_dump($arr_variable[$group_tab[$i]]["title"]);
						?>
						<a href="#tab-course-<?php echo $group_tab[$i];?>" data-toggle="tab">
							<i class="fa <?php echo $arr_variable[$group_tab[$i]]["icon"];?>"></i>
							<span><?php echo $arr_variable[$group_tab[$i]]["title"]; ?></span>
						</a>
					</li>
				<?php }?>
			<?php }?>
			<?php if ( $student_list_enable && $hide_students_list != 'yes' ) : ?>
				<li role="presentation">
					<a href="#tab-course-student-list" data-toggle="tab">
						<i class="fa fa-list"></i>
						<span><?php esc_html_e( 'Student List', 'eduma' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
		</ul>

		<div class="tab-content">
			<div class="tab-pane <?php if($active_tab=='description') echo 'active';?>" id="tab-course-description">
				<?php do_action( 'learn_press_begin_course_content_course_description' ); ?>
				<div class="thim-course-content">
					<?php the_content(); ?>
				</div>
				<?php thim_course_info(); ?>
				<?php do_action( 'learn_press_end_course_content_course_description' ); ?>
				<?php do_action( 'thim_social_share' ); ?>
			</div>
			<div class="tab-pane <?php if($active_tab=='curriculum') echo 'active';?>" id="tab-course-curriculum">
				<?php learn_press_course_curriculum(); ?>
			</div>
			<div class="tab-pane <?php if($active_tab=='instructor') echo 'active';?>" id="tab-course-instructor">
				<?php thim_about_author(); ?>
			</div>
			<?php if ( $review_is_enable ) : ?>
				<div class="tab-pane <?php if($active_tab=='review') echo 'active';?>" id="tab-course-review">
					<?php thim_course_review(); ?>
				</div>
			<?php endif; ?>
			<?php if ( $student_list_enable && $hide_students_list != 'yes' ) : ?>
				<div class="tab-pane <?php if($active_tab=='student-list') echo 'active';?>" id="tab-course-student-list">
					<?php learn_press_course_students_list(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php do_action( 'learn_press_after_content_learning' );?>
