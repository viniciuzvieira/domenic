<?php

global $pmpro_level;
$levels       = lp_pmpro_get_all_levels();
$list_courses = lp_pmpro_list_courses( array( $pmpro_level ) );


do_action( 'learn_press_checkout_custom_pricing_before' );
?>
	<table class="lp-pmpro-membership-list">
		<tbody class="lp-pmpro-main">
		<?php
		if ( ! empty( $list_courses ) ) {
			foreach ( $list_courses as $key => $course_item ) {

				$content_item = '<tr class="item-row">';
				$content_item .= apply_filters( 'learn_pres_pmpro_course_header_level', '<td class="list-main-large item-td">' . wp_kses_post( $course_item["link"] ) . '</td>', $course_item["link"], $course_item, $key );
				$content_item .= apply_filters( 'learn_press_pmpro_course_is_level', '<td class="list-item item-td item-check "><i class="fa fa-check"></i></td>', $pmpro_level, 0, $course_item, $key );
				$content_item .= '</tr>';
				$content_item = apply_filters( 'learn_press_pmpro_course_item_checkout', $content_item, $course_item["link"], $course_item, $key );

				echo $content_item;
			}
		}
		?>
		</tbody>
	</table>
<?php
do_action( 'learn_press_checkout_custom_pricing_after' );