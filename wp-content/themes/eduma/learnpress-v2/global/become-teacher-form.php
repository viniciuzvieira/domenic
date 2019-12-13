<?php
/**
 * Template for displaying the form let user fill out their information to become a teacher
 *
 * @author        ThimPress
 * @package       LearnPress/Templates
 * @version       1.0
 */

$submit_button_process_text = esc_html__( 'Processing', 'eduma' );
$submit_button_text         = esc_html__( 'Submit', 'eduma' );
$request                    = $method == 'post' ? $_POST : $_REQUEST;
$is_logged_in               = is_user_logged_in();
$disabled                   = !$is_logged_in ? 'disabled="disabled"' : '';
$can_edit_course = current_user_can( 'edit_lp_courses' );
if( $can_edit_course ) {
	$disabled = 'disabled="disabled"';
}
?>

<div class="become-teacher-form<?php echo ( $is_logged_in && !$can_edit_course ) ? ' allow' : ''; ?>">
	<h4 class="teacher-title"><?php esc_html_e( 'Register to become a teacher', 'eduma' ); ?></h4>
	<?php if ( !$is_logged_in ) { ?>
		<p class="message message-info"><?php printf( __( 'You have to <a class="login" href="%s">login</a> to fill out this form', 'eduma' ), add_query_arg( 'redirect_to', get_permalink(), thim_get_login_page_url() ) ); ?></p>
	<?php } ?>
	<?php if ( $can_edit_course ) { ?>
		<p class="message message-info"><?php esc_html_e( 'Your account has already signed up to create courses.', 'eduma' ); ?></p>
	<?php } ?>
	<form name="become-teacher-form" method="<?php echo $method; ?>" enctype="multipart/form-data" action="<?php echo esc_url( $action ); ?>">
		<?php if ( $fields ): ?>
			<ul>
				<?php foreach ( $fields as $name => $option ): ?>
					<?php
					$option        = wp_parse_args(
						$option,
						array(
							'title'       => '',
							'type'        => '',
							'def'         => '',
							'placeholder' => ''
						)
					);
					$value         = !empty( $request[$name] ) ? $request[$name] : ( !empty( $option['def'] ) ? $option['def'] : '' );
					$requested     = strtolower( $_SERVER['REQUEST_METHOD'] ) == $method;
					$error_message = null;
					if ( $requested ) {
						$error_message = apply_filters( 'learn_press_become_teacher_form_validate_' . $name, $value );
					}

					?>
					<li>
						<?php
						switch ( $option['type'] ) {
							case 'text':
							case 'email':
								printf( '<input type="%s" name="%s" placeholder="%s" value="%s" %s/>', $option['type'], $name, $option['placeholder'], esc_attr( $value ), $disabled );
								break;
						}
						if ( $error_message ) {
							printf( '<p class="message message-error">%s</p>', $error );
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>
			<button type="submit" <?php echo esc_attr( $disabled ); ?> data-text="<?php echo esc_attr( $submit_button_text ); ?>" data-text-process="<?php echo esc_attr( $submit_button_process_text ); ?>"><?php echo esc_html( $submit_button_text ); ?></button>
			<input type="hidden" name="lp-ajax" value="become-a-teacher" />
		<?php endif; ?>
	</form>
</div>