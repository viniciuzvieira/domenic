<?php
/**
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<ul class="section-content">

	<?php if ( !empty( $section->items ) ) { ?>

	<?php
	$count = array();
	foreach ( $section->items as $item ) {
		$post_type = str_replace( 'lp_', '', $item->post_type );
		if ( empty( $count[$post_type] ) ) {
			$count[$post_type] = 1;
		} else {
			$count[$post_type] ++;
		}
		$index = $section->section_index . '.' . $count[$post_type];
		if ( !in_array( $post_type, array( 'lesson', 'quiz' ) ) ) continue;
		$args = array(
			'item'    => $item,
			'section' => $section,
			'index'       => $index
		);
		learn_press_get_template( "single-course/section/item-{$post_type}.php", $args );
	}
	?>
	<?php } else { ?>

		<li class="course-item section-empty"><?php learn_press_display_message( esc_html__( 'No items in this section', 'eduma' ), 'notice' );?></li>

	<?php } ?>
</ul>
