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
<style type="text/css">
/* Styling for the title (Month and Year) of the calendar */
div.title {
    font: x-large Verdana, Arial, Helvetica, sans-serif;
    text-align: center;
    height: 40px;
    background-color: white;
    color: black;
    }
/* Styling for the footer */
div.footer {
    font: small Verdana, Arial, Helvetica, sans-serif;
    text-align: center;
    }
/* Styling for the overall table */
table {
    font: 100% Verdana, Arial, Helvetica, sans-serif;
    table-layout: fixed;
    border-collapse: collapse;
    width: 100%;
    }
/* Styling for the column headers (days of the week) */
th {
    padding: 0 0.5em;
    text-align: center;
    background-color:gray;
    color:white;
    }
/* Styling for the individual cells (days) */
td  {     
    font-size: medium;
    padding: 0.25em 0.25em;   
    width: 14%; 
    height: 80px;
    text-align: left;
    vertical-align: top;
    }
/* Styling for the date numbers */
.date  {     
    font-size: large;
    padding: 0.25em 0.25em;   
    text-align: left;
    vertical-align: top;
    }
/* Class for individual days (coming in future release) */
.sun {
     color:red;
     }
/* Hide the month element (coming in future release) */
th.month {
    visibility: hidden;
    display:none;
    }
   
</style>

<div class="thim-login-popup"><a class="login js-show-popup">teste</a></div>

<div class="title">March 2020</div>
<table border="1">
<tr><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr>
<tr><td class="eventDay"><span class="date">1</span></td><td class="eventDay"><span class="date">2</span></td><td class="eventDay"><span class="date">3</span></td><td class="eventDay"><span class="date">4</span></td><td class="eventDay"><span class="date">5</span></td><td class="eventDay"><span class="date">6</span></td><td class="eventDay"><span class="date">7</span></td></tr>
<tr><td class="eventDay"><span class="date">8</span></td><td class="eventDay"><span class="date">9</span></td><td class="eventDay"><span class="date">10</span></td><td class="eventDay"><span class="date">11</span></td><td class="eventDay"><span class="date">12</span></td><td class="eventDay"><span class="date">13</span></td class="eventDay"><td><span class="date">14</span></td></tr>
<tr><td class="eventDay"><span class="date">15</span></td><td class="eventDay"><span class="date">16</span></td><td class="eventDay"><span class="date">17</span></td><td class="eventDay"><span class="date">18</span></td><td class="eventDay"><span class="date">19</span></td><td class="eventDay"><span class="date">20</span></td><td class="eventDay"><span class="date">21</span></td></tr>
<tr><td class="eventDay"><span class="date">22</span></td><td class="eventDay"><span class="date">23</span></td><td class="eventDay"><span class="date">24</span></td><td class="eventDay"><span class="date">25</span></td><td class="eventDay"><span class="date">26</span></td><td class="eventDay"><span class="date">27</span></td><td class="eventDay"><span class="date">28</span></td></tr>
<tr><td class="eventDay"><span class="date">29</span></td><td class="eventDay"><span class="date">30</span></td><td class="eventDay"><span class="date">31</span></td><td class="eventDay"><span class="date">&nbsp;</span></td><td><span class="date">&nbsp;</span></td><td ><span class="date">&nbsp;</span></td><td><span class="date">&nbsp;</span></td></tr>
</table>
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
