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

?>

<?php
    global $wpdb;

    $schedule = $wpdb->get_results('SELECT contentSchedule, 
                                           buildFirstWeek, 
                                           buildSecondWeek, 
                                           buildThirdWeek, 
                                           buildFourthWeek, 
                                           buildFifthWeek, 
                                           buildSixthWeek,
                                           contentScheduleFinal 
                                    FROM wp_build_schedule');

    foreach ( $schedule AS $rowSelect ) { 
        echo $rowSelect->contentSchedule;
        echo $rowSelect->buildFirstWeek;
        echo $rowSelect->buildSecondWeek;
        echo $rowSelect->buildThirdWeek;
        echo $rowSelect->buildFourthWeek;
        echo $rowSelect->buildFifthWeek;
        echo $rowSelect->buildSixthWeek;
        echo $rowSelect->contentScheduleFinal;
    } 

?>