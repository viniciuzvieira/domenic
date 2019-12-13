<?php

if( version_compare(WPEMS_VER, '2.1.5', '>=') ) {
    $query_args = array(
        'post_type'           => 'tp_event',
        'posts_per_page'      => - 1,
        'meta_query' => array(
            array(
                'key'     => 'tp_event_status',
                'value'   => array( 'happening', 'upcoming' ),
                'compare' => 'IN',
            ),
        ),
        'ignore_sticky_posts' => true
    );
} else {
    $query_args = array(
        'post_type'           => 'tp_event',
        'posts_per_page'      => - 1,
        'post_status'         => array( 'tp-event-happenning', 'tp-event-upcoming' ),
        'ignore_sticky_posts' => true
    );
}

$events = new WP_Query( $query_args );

$happening = $expired = $upcoming = '';
if ( $events->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	while ( $events->have_posts() ) {

		$events->the_post();
        if( version_compare(WPEMS_VER, '2.1.5', '>=') ) {
            $event_status = get_post_meta( get_the_ID(), 'tp_event_status', true);
        } else {
            $event_status = get_post_status( get_the_ID() );
        }
		$class        = 'item-event';

		$time_from = tp_event_start( 'g:i A' );
		$time_end  = tp_event_end( 'g:i A' );

		$location   = tp_event_location();
		$date_show  = tp_event_get_time( 'd' );
		$month_show = tp_event_get_time( 'F' );

		ob_start();
		?>
		<div <?php post_class( $class ); ?>>
			<div class="time-from">
				<div class="date">
					<?php echo esc_html( $date_show ); ?>
				</div>
				<div class="month">
					<?php echo esc_html( $month_show ); ?>
				</div>
			</div>
			<?php
			echo '<div class="image">';
			echo thim_get_feature_image( get_post_thumbnail_id(), 'full', apply_filters( 'thim_event_thumbnail_width', 450 ), apply_filters('thim_event_thumbnail_height', 233) );
			echo '</div>';
			?>
			<div class="event-wrapper">
				<h5 class="title">
					<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"> <?php echo get_the_title(); ?></a>
				</h5>

				<div class="meta">
					<div class="time">
						<i class="fa fa-clock-o"></i>
						<?php echo esc_html( $time_from ) . ' - ' . esc_html( $time_end ); ?>
					</div>
					<div class="location">
						<i class="fa fa-map-marker"></i>
						<?php echo ent2ncr( $location ); ?>
					</div>
				</div>
				<div class="description">
					<?php echo thim_excerpt( 25 ); ?>
				</div>
			</div>

		</div>
		<?php
		switch ( $event_status ) {
			case 'tp-event-happenning':
				$happening .= ob_get_contents();
				ob_end_clean();
				break;
			case 'tp-event-expired':
				$expired .= ob_get_contents();
				ob_end_clean();
				break;
			case 'tp-event-upcoming':
				$upcoming .= ob_get_contents();
				ob_end_clean();
				break;
		}

	}
}
wp_reset_postdata();


?>
<div class="list-tab-event">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-happening" data-toggle="tab"><?php esc_html_e( 'Happening', 'eduma' ); ?></a></li>
		<li><a href="#tab-upcoming" data-toggle="tab"><?php esc_html_e( 'Upcoming', 'eduma' ); ?></a></li>
		<li><a href="#tab-expired" data-toggle="tab"><?php esc_html_e( 'Expired', 'eduma' ); ?></a></li>
	</ul>
	<div class="tab-content thim-list-event">
		<div role="tabpanel" class="tab-pane fade in active" id="tab-happening">
			<?php
			if ( $happening != '' ) {
				echo ent2ncr( $happening );
			}
			?>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="tab-upcoming">
			<?php
			if ( $upcoming != '' ) {
				echo ent2ncr( $upcoming );
			}
			?>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="tab-expired">
			<?php
			if ( $expired != '' ) {
				echo ent2ncr( $expired );
			}
			?>
		</div>
	</div>
</div>