<?php
global $course;
?>

	<?php do_action( 'learn_press_before_item_meta', $item );?>

	<?php if( $item->post_type == 'lp_quiz' ){ ?>

		<?php if( $course->final_quiz == $item->ID ){?>

<!--			<span class="lp-label lp-label-final">--><?php //_e( 'Final', 'eduma' );?><!--</span>-->

		<?php }?>

	<?php }elseif( $item->post_type == 'lp_lesson' ){ ?>


	<?php } ?>

	<?php learn_press_item_meta_format( $item->ID );?>

	<?php do_action( 'learn_press_after_item_meta', $item );?>
