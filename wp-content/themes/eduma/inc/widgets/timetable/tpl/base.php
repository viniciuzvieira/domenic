<?php
$html       = '';
$title      = $instance['title'] ? $instance['title'] : '';
$panel_list = $instance['panel'] ? $instance['panel'] : '';
?>

<div class="thim-widget-timetable">
	<?php
	if ( $title != '' ) {
		echo '<h3 class="widget-title">' . $title . '</h3>';
	}
	?>
	<div class="timetable-group">
		<?php foreach ( $panel_list as $key => $panel ) : ?>
			<?php

			$item_data = $item_style = '';

			$class_color = ! empty( $panel['color_style'] ) ? $panel['color_style'] : '';

			if ( ! empty( $panel['background'] ) ) {
				//$item_data .= ' data-background="' . $panel['background'] . '"';
				$item_style .= 'background: ' . $panel['background'];
			}

			if ( ! empty( $panel['background_hover'] ) ) {
				$item_data .= 'background: ' . $panel['background_hover'];
			}

			?>
			<div class="timetable-item <?php echo esc_attr( $class_color ); ?>" style="<?php echo esc_attr( $item_style ); ?>"  data-hover="<?php echo esc_attr( $item_data ); ?>">
				<?php

				echo ( ! empty( $panel['title'] ) ) ? '<h5 class="title">' . $panel['title'] . '</h5>' : '';

				echo ( ! empty( $panel['time'] ) ) ? '<div class="time">' . $panel['time'] . '</div>' : '';

				echo ( ! empty( $panel['location'] ) ) ? '<div class="location">' . $panel['location'] . '</div>' : '';

				echo ( ! empty( $panel['teacher'] ) ) ? '<div class="teacher">' . $panel['teacher'] . '</div>' : '';

				?>

			</div>

		<?php endforeach; ?>
	</div>

</div>