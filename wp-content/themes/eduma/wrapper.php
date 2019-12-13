<?php
$template_file = thim_template_path();
if ( ! is_search() && ( is_page_template( 'page-templates/homepage.php' ) || is_page_template( 'page-templates/maintenance.php' ) || is_page_template( 'page-templates/landing-page.php' ) || is_page_template( 'page-templates/blank-page.php' ) || is_singular( 'lpr_quiz' ) || is_singular( 'lp_quiz' ) || is_page_template( 'page-templates/landing-no-footer.php' ) ) ) {
	load_template( $template_file );

	return;
}
get_header();
?>
    <section class="content-area">
		<?php
		get_template_part(   'inc/templates/page-title' );
		do_action( 'thim_wrapper_loop_start' );
		load_template( $template_file );

		do_action( 'thim_wrapper_loop_end' );
		?>
    </section>
<?php
get_footer();
