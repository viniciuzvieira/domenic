<?php
/**
 * Template for displaying the categories of a course
 */

defined( 'ABSPATH' ) || exit();

$term_list = get_the_term_list( get_the_ID(), 'course_category', '', ', ', '' );
if ( $term_list ) :
	?>
	<div class="course-categories">
		<label><?php esc_html_e( 'Categories', 'eduma' ); ?></label>

		<div class="value">
			<?php

			printf(
				'<span class="cat-links">%s</span>',
				$term_list
			);
			?>
		</div>
	</div>
	<?php
endif;