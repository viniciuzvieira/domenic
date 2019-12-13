<?php

$display_year = get_theme_mod( 'thim_event_display_year', false );
$number_posts = $instance['number_posts'] ? $instance['number_posts'] : 10;
$link         = get_post_type_archive_link( 'tp_event' );
$list_status = $instance['status'] ? $instance['status'] : array( 'happening', 'upcoming' );
$list_cat = $instance['cat_id'] ? $instance['cat_id'] : '';

if( version_compare(WPEMS_VER, '2.1.5', '>=') ) {
    $query_args = array(
        'post_type'           => 'tp_event',
        'posts_per_page'      => - 1,
        'meta_query' => array(
            array(
                'key'     => 'tp_event_status',
                'value'   => $list_status,
                'compare' => 'IN',
            ),
        ),
        'ignore_sticky_posts' => true
    );
} else {
    $list_status = $instance['status'] ? $instance['status'] : array( 'tp-event-happenning', 'tp-event-upcoming' );
    $query_args = array(
        'post_type'           => 'tp_event',
        'posts_per_page'      => - 1,
        'post_status'         => $list_status,
        'ignore_sticky_posts' => true
    );
}

if( $list_cat && $list_cat != 'all' ) {
	$list_cat_arr            = explode( ',', $list_cat );
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'tp_event_category',
            'field'    => 'term_id',
            'terms'    => $list_cat_arr
        ),
    );
}

$events = new WP_Query( $query_args );

$html    = array();
$sorting = array();

$event_class = $instance['layout'];
if( $display_year ) {
	$event_class.= ' has-year';
}

if ( $events->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	echo '<div class="thim-list-event layout-2 ' . $event_class . '">';

	while ( $events->have_posts() ) {

		$events->the_post();
		$class = 'item-event';
		$time_format = get_option( 'time_format' );

		if ( class_exists( 'WPEMS' ) ) {
			$time_start = wpems_event_start( get_option('time_format') );
			$time_end  = wpems_event_end( get_option('time_format') );

			$location   = wpems_event_location();
			$date_show  = wpems_get_time( 'd' );
			$month_show = wpems_get_time( 'M' );
            $year_show = wpems_get_time( 'Y' );

			$sorting[get_the_ID()] = strtotime( wpems_get_time() );
		} else {
			$time_start  = tp_event_start( get_option('time_format') );
			$time_end  = tp_event_end( get_option('time_format') );

			$location   = tp_event_location();
			$date_show  = tp_event_get_time( 'd' );
			$month_show = tp_event_get_time( 'M' );
            $year_show = tp_event_get_time( 'Y' );

			$sorting[get_the_ID()] = strtotime( tp_event_get_time() );
		}

		ob_start();
		?>
		<div <?php post_class( $class ); ?>>
			<div class="time-from">
				<?php do_action( 'thim_before_event_time' ); ?>
				<div class="date">
					<?php echo esc_html( $date_show ); ?>
				</div>
				<div class="month">
					<?php echo esc_html( $month_show ); ?>, <?php echo esc_html( $year_show ); ?>
				</div>
				<?php do_action( 'thim_after_event_time' ); ?>
			</div>
			<div class="event-wrapper">
				<h5 class="title">
					<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"> <?php echo get_the_title(); ?></a>
				</h5>

				<div class="meta">
					<div class="time">
						<i class="ion-android-alarm-clock"></i>
						<?php echo esc_html( $time_start ) . ' - ' . esc_html( $time_end ); ?>
					</div>
					<div class="location">
						<i class="ion-ios-location-outline"></i>
						<?php echo ent2ncr( $location ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		$html[ get_the_ID() ] = ob_get_contents();
		ob_end_clean();
	}

	asort( $sorting );

	if ( !empty( $sorting ) ) {
		$index = 1;
		foreach ( $sorting as $key => $value ) {
			if ( $index > $number_posts ) {
				break;
			}
			if ( $html[$key] ) {
				echo ent2ncr( $html[$key] );
			}
			$index ++;
		}
	}

	if ( $instance['text_link'] != '' ) {
		echo '<a class="view-all" href="' . esc_url( $link ) . '">' . $instance['text_link'] . '</a>';
	}
	echo '</div>';
}
wp_reset_postdata();

?>
