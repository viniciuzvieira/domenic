<?php
global $current_user;
$levels       = lp_pmpro_get_all_levels();
$list_courses = lp_pmpro_list_courses( $levels );
?>
<?php do_action( 'learn_press_pmpro_before_levels' ); ?>
<div class="lp-membership-list-mobile">
	<div class="lp-pmpro-membership-list">
		<?php foreach ( $levels as $index => $level ): ?>
			<?php
			$current_level = false;
			$icon = get_option('thim_level_'.$level->id) ? get_option('thim_level_'.$level->id) : '';
			if ( isset( $current_user->membership_level->ID ) ) {
				if ( $current_user->membership_level->ID == $level->id ) {
					$current_level = true;
				}
			}
			?>
			<div class="item_level">
				<div <?php if($icon) echo ' style="background-image: url('.$icon.');" ';?>class="header-item list-item<?php echo ' position-' . $index; ?>">
					<h2 class="lp-title"><?php echo esc_html( $level->name ); ?></h2>
					<?php
					if ( !empty( $level->description ) ) {
						echo '<div class="lp-desc">' . $level->description . '</div>';
					}
					?>
					<div class="lp-price">
						<?php if ( pmpro_isLevelFree( $level ) ): ?>
							<?php esc_html_e( 'Free', 'eduma' ); ?>
						<?php else: ?>
							<?php
							$cost_text = pmpro_getLevelCost( $level, true, true );
							echo ent2ncr( $cost_text );
							?>
						<?php endif; ?>
					</div>
				</div>
				<div class="list_courses">
					<?php
					if ( !empty( $list_courses ) ) {
						foreach ( $list_courses as $key => $course_item ) {
							//var_dump($course_item['level']);
							if ( in_array( $level->id, $course_item['level'] ) ) {
								echo '<div class="item-td">';
								echo $course_item['link'];
								echo '</div>';
							}
						}
					}
					?>
				</div>
				<div class="footer-item">
					<?php if ( empty( $current_user->membership_level->ID ) || ! $current_level ) { ?>
						<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url( 'checkout', '?level=' . $level->id, 'https' ) ?>"><?php _e( 'GET IT NOW', 'eduma' ); ?></a>
					<?php } elseif ( $current_level ) { ?>
						<?php
						if ( pmpro_isLevelExpiringSoon( $current_user->membership_level ) && $current_user->membership_level->allow_signups ) {
							?>
							<a class="pmpro_btn pmpro_btn-select"
							   href="<?php echo pmpro_url( 'checkout', '?level=' . $level->id, 'https' ) ?>"><?php _e( 'Renew', 'eduma' ); ?></a>
							<?php
						} else {
							?>
							<a class="pmpro_btn disabled" href="<?php echo pmpro_url( 'account' ) ?>"><?php _e( 'Your Level', 'eduma' ); ?></a>
							<?php
						}
						?>

					<?php } ?>
				</div>
			</div>
		<?php endforeach;?>
	</div>
</div>
<?php do_action( 'learn_press_pmpro_after_levels' ); ?>

