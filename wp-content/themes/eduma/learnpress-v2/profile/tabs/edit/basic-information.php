<?php
/**
 * Form for editing basic information of user in profile page
 *
 * @author  ThimPress
 * @version 2.1.1
 * @package LearnPress/Templates
 */

defined( 'ABSPATH' ) || exit;
$lp_info = get_the_author_meta( 'lp_info', $user->user->data->ID );
?>

<ul class="lp-form-field-wrap">
	<?php do_action( 'learn_press_before_' . $section . '_edit_fields' ); ?>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Biographical Info', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<p class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'eduma' ); ?></p>
			<textarea name="description" id="description" rows="5" cols="30"><?php echo esc_html( $user_info->description ); ?></textarea>
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'First Name', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $first_name ); ?>" class="regular-text">
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Last Name', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $last_name ); ?>" class="regular-text">
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Nickname', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="nickname" id="nickname" value="<?php echo esc_attr( $user_info->nickname ) ?>" class="regular-text" />
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Display name publicly as', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<select name="display_name" id="display_name">
				<?php
				$public_display = learn_press_get_display_name_publicly( $user_info );
				foreach ( $public_display as $id => $item ) {
					?>
					<option <?php selected( $user_info->display_name, $item ); ?>><?php echo $item; ?></option>
					<?php
				}
				?>
			</select>
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Facebook', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="lp_info[facebook]" id="facebook" value="<?php echo isset( $lp_info['facebook'] ) ? $lp_info['facebook'] : ''; ?>" class="regular-text">
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Twitter', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="lp_info[twitter]" id="twitter" value="<?php echo isset( $lp_info['twitter'] ) ? $lp_info['twitter'] : ''; ?>" class="regular-text">
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Google Plus', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="lp_info[google]" id="google" value="<?php echo isset( $lp_info['google'] ) ? $lp_info['google'] : ''; ?>" class="regular-text">
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'LinkedIn', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="lp_info[linkedin]" id="linkedin" value="<?php echo isset( $lp_info['linkedin'] ) ? $lp_info['linkedin'] : ''; ?>" class="regular-text">
		</div>
	</li>
	<li class="lp-form-field">
		<label class="lp-form-field-label"><?php _e( 'Youtube', 'eduma' ); ?></label>
		<div class="lp-form-field-input">
			<input type="text" name="lp_info[youtube]" id="youtube" value="<?php echo isset( $lp_info['youtube'] ) ? $lp_info['youtube'] : ''; ?>" class="regular-text">
		</div>
	</li>
	<?php do_action( 'learn_press_after_' . $section . '_edit_fields' ); ?>
</ul>