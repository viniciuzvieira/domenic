<?php

$levels = lp_pmpro_get_all_levels();
global $current_user;

?>

<div class="lp_pmpro_courses_by_level">
	<?php foreach ( $levels as $index => $level ):
		$current_level = false;

		if ( isset( $current_user->membership_level->ID ) ) {
			if ( $current_user->membership_level->ID == $level->id ) {
				$current_level = true;
			}
		}
		?>
		<div class="col-sm-4 col-xs-6 thim-level-wrap">
			<div class="level-wrap <?php echo ( $index == 2 ) ? 'feature' : ''; ?>">
				<div class="lp_pmpro_level">
					<header>
						<h2 class="lp_pmpro_title_level"><?php echo esc_html( $level->name ); ?></h2>

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
						<?php $the_query = lp_pmpro_query_course_by_level( $level->id ); ?>
						<!-- List courses -->
						<?php if ( $the_query->have_posts() ) : ?>
							<ul>
								<?php while ( $the_query->have_posts() ) : $the_query->the_post();
									$course      = LP_Course::get_course( get_the_ID() );
									$is_required = $course->is_required_enroll();
									?>
									<li>
										<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank">
											<?php the_title(); ?>
											<?php if ( $course->is_free() || ! $is_required ) : ?>
												<span class="value free-course" itemprop="price" content="<?php esc_attr_e( 'Free', 'eduma' ); ?>">
													<?php echo '&#40;' . esc_html( 'Free', 'eduma' ) . '&#41;'; ?>
												</span>
											<?php else: $price = learn_press_format_price( $course->get_price(), true ); ?>
												<span class="value " itemprop="price" content="<?php echo esc_attr( $price ); ?>">
													<?php echo '&#40;' . esc_html( $price ) . '&#41;'; ?>
												</span>
											<?php endif; ?>
										</a>
									</li>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</ul>
						<?php else : ?>
							<p class="no-course"><?php esc_html_e( 'No courses!', 'eduma' ); ?></p>
						<?php endif; ?>
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
	<?php endforeach; ?>
	<nav id="nav-below" class="navigation">
		<div class="nav-previous ">
			<?php if ( ! empty( $current_user->membership_level->ID ) ) { ?>
				<a href="<?php echo pmpro_url( "account" ) ?>"><?php esc_html_e( 'Return to Your Account', 'eduma' ); ?></a>
			<?php } ?>
		</div>
	</nav>
</div>

