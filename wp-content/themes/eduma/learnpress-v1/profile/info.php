<div class="user-info">

	<div class="author-avatar"><?php echo get_avatar( $user->user->data->ID, 270 ); ?></div>

	<div class="user-information">

		<?php
		$lp_info = get_the_author_meta( 'lp_info', $user->user->data->ID );
		?>
		<ul class="thim-author-social">
			<?php if ( isset( $lp_info['facebook'] ) && $lp_info['facebook'] ) : ?>
				<li>
					<a href="<?php echo esc_url( $lp_info['facebook'] ); ?>" class="facebook"><i class="fa fa-facebook"></i></a>
				</li>
			<?php endif; ?>

			<?php if ( isset( $lp_info['twitter'] ) && $lp_info['twitter'] ) : ?>
				<li>
					<a href="<?php echo esc_url( $lp_info['twitter'] ); ?>" class="twitter"><i class="fa fa-twitter"></i></a>
				</li>
			<?php endif; ?>

			<?php if ( isset( $lp_info['google'] ) && $lp_info['google'] ) : ?>
				<li>
					<a href="<?php echo esc_url( $lp_info['google'] ); ?>" class="google-plus"><i class="fa fa-google-plus"></i></a>
				</li>
			<?php endif; ?>

			<?php if ( isset( $lp_info['linkedin'] ) && $lp_info['linkedin'] ) : ?>
				<li>
					<a href="<?php echo esc_url( $lp_info['linkedin'] ); ?>" class="linkedin"><i class="fa fa-linkedin"></i></a>
				</li>
			<?php endif; ?>

			<?php if ( isset( $lp_info['youtube'] ) && $lp_info['youtube'] ) : ?>
				<li>
					<a href="<?php echo esc_url( $lp_info['youtube'] ); ?>" class="youtube"><i class="fa fa-youtube"></i></a>
				</li>
			<?php endif; ?>
		</ul>
		<h3 class="author-name"><?php echo esc_attr( $user->user->data->display_name ); ?></h3>

		<p><?php echo get_user_meta( $user->user->data->ID, 'description', true ); ?></p>
		
		<?php $current_id = get_current_user_id(); ?>
		<?php if ( $current_id && $current_id == $user->user->data->ID ) : ?>
			<?php echo '<p class="edit-profile"><a href="' . get_edit_user_link( $current_id ) . '">' . esc_html__( 'Edit Profile', 'eduma' ) . '</a></p>'; ?>
		<?php endif; ?>
	</div>
</div>
