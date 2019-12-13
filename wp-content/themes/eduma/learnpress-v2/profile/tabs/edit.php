<?php
/**
 * User Information
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.1.6
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( thim_is_new_learnpress( '2.1.1' ) ) {
	$user                    = learn_press_get_current_user();
	$user_info               = get_userdata( $user->id );
	$username                = $user_info->user_login;
	$nick_name               = $user_info->nickname;
	$first_name              = $user_info->first_name;
	$last_name               = $user_info->last_name;
	$profile_picture_type    = $user->profile_picture ? 'picture' : 'gravatar';
	$profile_picture         = $user->profile_picture;
	$class_gravatar_selected = ( 'gravatar' === $profile_picture_type ) ? ' lp-menu-item-selected' : '';
	$class_picture_selected  = ( 'picture' === $profile_picture_type ) ? ' lp-menu-item-selected' : '';
	$section                 = !empty( $_REQUEST['section'] ) ? $_REQUEST['section'] : 'basic-information';
	$url_tab                 = learn_press_user_profile_link( $user->id, $current );

	$edit_tabs = array(
		'basic-information' => __( 'Basic Information', 'eduma' ),
		'avatar'            => __( 'Avatar', 'eduma' ),
		'change-password'   => __( 'Change Password', 'eduma' ),
	);
	$first_tab = 'basic-information';
	?>
	<div id="lp-user-profile-form" class="lp-user-profile-form">
		<form id="your-profile" method="post" name="lp-edit-profile">
			<ul class="learn-press-subtabs">
				<?php foreach ( $edit_tabs as $sub_key => $title ): ?>
					<?php
					$classes = array();
					if ( ( $section && $section == $sub_key ) || ( !$section && $sub_key == $first_tab ) ) {
						$classes[] = 'current';
					} ?>
					<li class="<?php echo join( ' ', $classes ); ?>">
						<?php if ( in_array( 'current', $classes ) ) { ?>
							<span><?php echo esc_html( $title ); ?></span>
						<?php } else { ?>
							<a href="<?php echo esc_url( add_query_arg( 'section', $sub_key, $url_tab ) ); ?>"><?php echo esc_html( $title ); ?></a>
						<?php } ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="learn-press-subtab-content user-profile-section-content">
				<?php include learn_press_locate_template( 'profile/tabs/edit/' . $section . '.php' ); ?>
				<input type="hidden" name="lp-profile-section" value="<?php echo $section; ?>" />
			</div>

			<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $user->id ); ?>" />
			<input type="hidden" name="profile-nonce" value="<?php echo esc_attr( wp_create_nonce( 'learn-press-update-user-profile-' . $user->id ) ); ?>" />
			<p class="submit update-profile">
				<input disabled="disabled" type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Update', 'eduma' ); ?>" />
			</p>
		</form>
	</div>
	<?php
} else {
	global $wp_query;
	$user = learn_press_get_current_user();

	$user_info = get_userdata( $user->id );

	$username                = $user_info->user_login;
	$nick_name               = $user_info->nickname;
	$first_name              = $user_info->first_name;
	$last_name               = $user_info->last_name;
	$profile_picture_type    = $user->profile_picture_type ? $user->profile_picture_type : 'gravatar';
	$profile_picture         = $user->profile_picture;
	$class_gravatar_selected = ( 'gravatar' === $profile_picture_type ) ? ' lp-menu-item-selected' : '';
	$class_picture_selected  = ( 'picture' === $profile_picture_type ) ? ' lp-menu-item-selected' : '';

	if ( $user ) :
		?>
		<div class="user-profile-edit-form" id="learn-press-user-profile-edit-form">
			<form id="your-profile" action="" method="post" enctype="multipart/form-data" novalidate="novalidate">


				<h3 class="title"><?php esc_html_e( 'About Yourself', 'eduma' ); ?></h3>

				<div class="user-profile-picture info-field">
					<label><?php esc_html_e( 'Profile Picture', 'eduma' ); ?></label>

					<div id="profile-picture-wrap">
						<div class="profile-picture profile-avatar-current <?php echo $profile_picture_type == 'gravatar' ? 'avatar-picture' : 'avatar-gravatar'; ?>">
							<?php echo $user->get_profile_picture( $profile_picture_type == 'gravatar' ? 'gravatar' : 'picture' ); ?>
						</div>
						<div class="profile-picture profile-avatar-hidden hide-if-js <?php echo $profile_picture_type != 'gravatar' ? 'avatar-picture' : 'avatar-gravatar'; ?>">
							<?php echo $user->get_profile_picture( $profile_picture_type == 'gravatar' ? 'picture' : 'gravatar' ); ?>
						</div>
						<div class="clear"></div>
						<ul id="lp-menu-change-picture">
							<li class="dropdown">
								<span class="lp-label-change-picture"><?php _e( 'Change Picture', 'eduma' ); ?></span>
								<select name="profile_picture_type" id="lp-profile_picture_type" class="hidden">
									<option value="gravatar" <?php selected( 'gravatar', $profile_picture_type ) ?>><?php _e( 'Gravatar', 'eduma' ); ?></option>
									<option value="picture" <?php selected( 'picture', $profile_picture_type ) ?>><?php _e( 'Picture', 'eduma' ); ?></option>
								</select>
								<ul class="dropdown-menu" role="menu">
									<li class="menu-item-use-gravatar<?php echo esc_attr( $class_gravatar_selected ); ?>">
										<span><?php _e( 'Use Gravatar', 'eduma' ); ?></span></li>
									<li class="menu-item-use-picture<?php echo esc_attr( $class_picture_selected ); ?>">
										<span><?php _e( 'Use Picture', 'eduma' ); ?></span></li>
									<li class="menu-item-upload-picture">
										<span><?php _e( 'Upload Picture', 'eduma' ); ?></span></li>
								</ul>

							</li>
						</ul>
					</div>
					<div id="lpbox-upload-crop-profile-picture">
						<input type="hidden" id="lp-user-profile-picture-data" data-current="<?php echo esc_attr( $profile_picture ); ?>" name="profile_picture_data" />
						<div class="lpbox-title"><?php _e( 'Upload Picture', 'eduma' ); ?></div>
						<p class="description">
							<small><?php _e( 'Please use an image that\'s at least 250px in width, 250px in height and under 2MB in size', 'eduma' ); ?></small>
						</p>
						<div id="image-editor-wrap">
							<div class="image-editor image-editor-sidebar-left">
								<div class="cropit-preview"></div>
								<div class="image-editor-btn">
									<input type="range" class="cropit-image-zoom-input">
								</div>
							</div>
							<div class="image-editor-sidebar-right">
								<a href="#" id="lp-button-choose-file"><span class="dashicons dashicons-format-image"></span><?php _e( 'Choose File', 'eduma' ); ?>
								</a>
								<a href="#" id="lp-button-apply-changes"><span class="dashicons dashicons-yes"></span>&nbsp;<?php _e( 'Apply Changes', 'eduma' ); ?>
								</a>
								<a href="#" id="lp-button-cancel-changes"><span class="dashicons dashicons-no"></span><?php _e( 'Cancel', 'eduma' ); ?>
								</a>
								<div id="lp-ocupload-picture"></div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>

				<div class="user-description-wrap info-field end-box">
					<label><?php esc_html_e( 'Biographical Info', 'eduma' ); ?></label>
					<textarea name="description" id="description" rows="5" cols="30"><?php echo esc_html( $user_info->description ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'eduma' ); ?></p>
				</div>

				<h3 class="title"><?php esc_html_e( 'Name', 'eduma' ); ?></h3>

				<div class="user-user-login-wrap info-field">
					<label><?php esc_html_e( 'Username', 'eduma' ); ?></label>
					<input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $user->user->data->user_login ); ?>" disabled="disabled" class="regular-text">
					<p class="description"><?php esc_html_e( 'Username cannot be changed.', 'eduma' ) ?></p>
				</div>

				<div class="user-first-name-wrap info-field">
					<label><?php esc_html_e( 'First Name', 'eduma' ); ?></label>
					<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $first_name ); ?>" class="regular-text">
				</div>

				<div class="user-last-name-wrap info-field">
					<label><?php esc_html_e( 'Last Name', 'eduma' ); ?></label>
					<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $last_name ); ?>" class="regular-text">
				</div>

				<div class="user-nickname-wrap info-field">
					<label><?php esc_html_e( 'Nickname *', 'eduma' ); ?></label>
					<input type="text" name="nickname" id="nickname" value="<?php echo esc_attr( $user_info->nickname ) ?>" class="regular-text" />
				</div>
				<div class="user-last-name-wrap info-field end-box">
					<label><?php esc_html_e( 'Display name publicly as', 'eduma' ); ?></label>
					<select name="display_name" id="display_name">
						<?php
						$public_display                     = array();
						$public_display['display_nickname'] = $user_info->nickname;
						$public_display['display_username'] = $user_info->user_login;

						if ( !empty( $user_info->first_name ) )
							$public_display['display_firstname'] = $user_info->first_name;

						if ( !empty( $user_info->last_name ) )
							$public_display['display_lastname'] = $user_info->last_name;

						if ( !empty( $user_info->first_name ) && !empty( $user_info->last_name ) ) {
							$public_display['display_firstlast'] = $user_info->first_name . ' ' . $user_info->last_name;
							$public_display['display_lastfirst'] = $user_info->last_name . ' ' . $user_info->first_name;
						}

						if ( !in_array( $user_info->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
						{
							$public_display = array( 'display_displayname' => $user_info->display_name ) + $public_display;
						}

						$public_display = array_map( 'trim', $public_display );
						$public_display = array_unique( $public_display );

						foreach ( $public_display as $id => $item ) {
							?>
							<option <?php selected( $user_info->display_name, $item ); ?>><?php echo esc_html( $item ); ?></option>
							<?php
						}
						?>
					</select>
				</div>

				<h3 class="title"><?php esc_html_e( 'Account Management', 'eduma' ); ?></h3>
				<div class="change-password">
					<a href="#" id="learn-press-toggle-password" class="link-change-password"><?php esc_html_e( 'Change Password', 'eduma' ); ?></a>
					<div id="user_profile_password_form" class="info-field end-box hide-if-js">
						<label><?php esc_html_e( 'Old Password', 'eduma' ); ?></label>
						<input type="password" id="pass0" name="pass0" autocomplete="off" class="regular-text" disabled="disabled" />

						<label><?php esc_html_e( 'New Password', 'eduma' ); ?></label>
						<input type="password" name="pass1" id="pass1" class="regular-text" value="" disabled="disabled" />

						<label><?php esc_html_e( 'Repeat New Password', 'eduma' ); ?></label>
						<input name="pass2" type="password" id="pass2" class="regular-text" value="" disabled="disabled" />
					</div>
				</div>

				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $user->id ); ?>" />
				<input type="hidden" name="profile-nonce" value="<?php echo esc_attr( wp_create_nonce( 'learn-press-user-profile-' . $user->id ) ); ?>" />
				<input type="hidden" name="from" value="profile">
				<input type="hidden" name="checkuser_id" value="2">
				<p class="submit update-profile">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Update Profile', 'eduma' ); ?>" />
				</p>
			</form>
		</div>
		<script type="text/template" id="learn-press-template-block-content">
			<div id="learn-press-block-content" class="popup-block-content">
				<div class="thim-box-loading-container">
					<div class="cssload-container">
						<div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
					</div>
				</div>
			</div>
		</script>
		<?php
	endif;

}
?>

