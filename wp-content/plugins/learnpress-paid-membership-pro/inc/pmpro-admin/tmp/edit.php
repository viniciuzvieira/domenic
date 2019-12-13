<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$level_id = $_GET['edit'];
$courses = lp_pmpro_get_course_by_level_id($level_id);
?>
<h3 class="topborder"><?php _e('Courses Settings', 'learnpress-paid-membership-pro' );?></h3>
<table class="form-table">
	<tbody>
		<tr class="membership_categories">
			<th scope="row" valign="top"><label><?php _e( 'Courses', 'learnpress-paid-membership-pro' ); ?>:</label></th>
			<td>
				<select class="lp-pmpro-courses-data-search-ajax" name="_lp_pmpro_courses[]" multiple="multiple" style="width: 300px;" data-level_id="<?php echo esc_attr($level_id);?>">
					<?php
						if( !empty($courses) ) {
							foreach( $courses as $course ) {
								echo '<option value="'.esc_attr($course->ID).'" selected="selected">'.esc_html($course->title).'</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>
	</tbody>
</table>