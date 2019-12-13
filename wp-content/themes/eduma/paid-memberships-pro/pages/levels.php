<?php 
global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels = pmpro_getAllLevels(false, true);
$pmpro_level_order = pmpro_getOption('level_order');

if(!empty($pmpro_level_order))
{
	$order = explode(',',$pmpro_level_order);

	//reorder array
	$reordered_levels = array();
	foreach($order as $level_id) {
		foreach($pmpro_levels as $key=>$level) {
			if($level_id == $level->id)
				$reordered_levels[] = $pmpro_levels[$key];
		}
	}

	$pmpro_levels = $reordered_levels;
}

$pmpro_levels = apply_filters("pmpro_levels_array", $pmpro_levels);

if($pmpro_msg)
{
?>
<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
<?php
}
?>
<div class="lp_pmpro_courses_by_level row">
	<?php
	$count = 0;
	foreach($pmpro_levels as $level)
	{
		if(isset($current_user->membership_level->ID))
			$current_level = ($current_user->membership_level->ID == $level->id);
		else
			$current_level = false;

		?>
		<div class="col-sm-4 col-xs-6 thim-level-wrap">
			<div class="level-wrap">
				<div class="lp_pmpro_level">
					<header>
						<h2 class="lp_pmpro_title_level">
							<?php echo esc_html( $level->name ); ?>
						</h2>
						<div class="lp_pmpro_price_level">
							<div class="price-wrap">
								<?php if ( pmpro_isLevelFree( $level ) ): ?>
									<?php

									echo '<p class="price">' . esc_html( 'Free', 'eduma' ) . '</p>';

									if ( $level->expiration_number ) {
										echo '<p class="expired">' . sprintf( __( "expires after %d %s.", "eduma" ), $level->expiration_number, pmpro_translate_billing_period( $level->expiration_period, $level->expiration_number ) ) . '</p>';
									}

									?>

								<?php else: ?>
									<?php
									$cost_text       = pmpro_getLevelCost( $level, true, true );
									$expiration_text = pmpro_getLevelExpiration( $level );

									echo ent2ncr( $cost_text );

									if ( $level->expiration_number ) {
										echo '<p class="expired">' . sprintf( __( "expires after %d %s.", "eduma" ), $level->expiration_number, pmpro_translate_billing_period( $level->expiration_period, $level->expiration_number ) ) . '</p>';
									}

									?>
								<?php endif; ?>
							</div>
						</div>
					</header>

					<main>
						<?php echo ent2ncr($level->description); ?>
					</main>

					<footer>
						<div class="button">
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
					</footer>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>

<nav id="nav-below" class="navigation" role="navigation">
	<div class="nav-previous alignleft">
		<?php if(!empty($current_user->membership_level->ID)) { ?>
			<a href="<?php echo pmpro_url("account")?>"><?php esc_html_e('&larr; Return to Your Account', 'eduma');?></a>
		<?php } else { ?>
			<a href="<?php echo home_url()?>"><?php _e('&larr; Return to Home', 'eduma');?></a>
		<?php } ?>
	</div>
</nav>
