<?php
/**
 * User Information
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="user-info">

	<div class="author-avatar"><?php echo $user->get_profile_picture( null, '270' ); ?></div>

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
		<h3 class="author-name"><?php echo learn_press_get_profile_display_name( $user ); ?></h3>

		<p><?php echo get_user_meta( $user->id, 'description', true ); ?></p>
	</div>
</div>

