<?php
$tabs = apply_filters( 'learn_press_course_tabs', array() );
if ( !empty( $tabs ) ) : ?>
	<?php
	$index        = 0;
	$active_index = - 1;
	foreach ( $tabs as $key => $tab ) {
		if ( !empty( $tab['active'] ) && $tab['active'] == true ) {
			$active_index = $index;
		}
		$index ++;
	}
	if ( $active_index == - 1 ) {
		$active_index = 0;
	}
	$index = 0;
	?>
	<div class="course-tabs">
		<ul class="nav nav-tabs">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<?php
				$unikey            = uniqid( $key . '-' );
				$tabs[$key]['key'] = $unikey;
				?>
				<li class="<?php echo esc_attr( $key ); ?><?php echo $index++ == $active_index ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $key ); ?>">
					<a data-toggle="tab" href="#tab-<?php echo esc_attr( $unikey ); ?>"><?php echo apply_filters( 'learn_press_course_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php $index = 0; ?>
		<div class="tab-content">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="tab-pane<?php echo $index ++ == $active_index ? ' active' : ''; ?>" id="tab-<?php echo esc_attr( $tab['key'] ); ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
