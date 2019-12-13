<?php
/**
 * The Template for displaying all archive products.
 *
 * Override this template by copying it to yourtheme/tp-event/templates/archive-event.php
 *
 * @author        ThimPress
 * @package       tp-event/template
 * @version       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wp_query;
$_wp_query = $wp_query;

$default_tab       = array( 'happening', 'upcoming', 'expired' );
$default_tab_title = array(
	'happening' => esc_html__( 'Happening', 'eduma' ),
	'upcoming'  => esc_html__( 'Upcoming', 'eduma' ),
	'expired'   => esc_html__( 'Expired', 'eduma' )
);
$output_tab        = array();

$customize_order_tab = get_theme_mod( 'thim_event_change_order_tab', array() );
if ( ! $customize_order_tab ) { // set default value for the first time
	$customize_order_tab = $default_tab;
}

foreach ( $customize_order_tab as $tab_key ) {
	$output_tab[ $tab_key ] = $default_tab_title[ $tab_key ];
}
?>

<?php
/**
 * tp_event_before_main_content hook
 *
 * @hooked tp_event_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked tp_event_breadcrumb - 20
 */
do_action( 'tp_event_before_main_content' );
?>

<?php
/**
 * tp_event_archive_description hook
 *
 * @hooked tp_event_taxonomy_archive_description - 10
 * @hooked tp_event_room_archive_description - 10
 */
do_action( 'tp_event_archive_description' );
?>
	<div class="list-tab-event">
		<ul class="nav nav-tabs">
			<?php
			$first_tab = true;
			foreach ( $output_tab as $k => $v ) {
				if ( $first_tab ) {
					$first_tab = false;
					echo '<li class="active"><a href="#tab-' . ( $k ) . '" data-toggle="tab">' . ( $v ) . '</a></li>';
				} else {
					echo '<li><a href="#tab-' . ( $k ) . '" data-toggle="tab">' . ( $v ) . '</a></li>';
				}
				?>
				<?php
			}
			?>
		</ul>

		<div class="tab-content thim-list-event">
			<?php
			foreach ( $output_tab as $type => $title ) :
				get_template_part( 'wp-events-manager/archive-event', $type );
			endforeach;
			?>
		</div>
	</div>

<?php
/**
 * tp_event_after_main_content hook
 *
 * @hooked tp_event_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'tp_event_after_main_content' );
?>

<?php
/**
 * tp_event_sidebar hook
 *
 * @hooked tp_event_get_sidebar - 10
 */
do_action( 'tp_event_sidebar' );
