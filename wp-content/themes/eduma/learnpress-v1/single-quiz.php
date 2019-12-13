<?php
/**
 * Template for displaying content of single quiz
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $quiz;
$theme_options_data = get_theme_mods();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?><?php if ( isset( $theme_options_data['thim_rtl_support'] ) && $theme_options_data['thim_rtl_support'] == '1' ) {
	echo " dir=\"rtl\"";
} ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php esc_url( bloginfo( 'pingback_url' ) ); ?>">
	<?php do_action( 'wp_enqueue_scripts' );?>
	<?php do_action( 'thim_quiz_scripts' ); ?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' );?>';
	</script>
	<script type="text/javascript">
		var single_quiz_params = <?php echo json_encode( $quiz->get_settings());?>,
				single_quiz_localize = <?php echo json_encode( $quiz->get_localize());?>;
	</script>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'learn_press_before_main_content' ); ?>

<?php while ( have_posts() ): the_post(); ?>

	<?php learn_press_get_template_part( 'content', 'single-quiz' ); ?>

<?php endwhile; ?>

<?php do_action( 'learn_press_after_main_content' ); ?>
</body>
</html>

