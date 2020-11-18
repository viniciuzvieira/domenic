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

<?php
    global $wpdb;
    global $daySelect;
    global $monthSelect;
    
    $Sunday = 'Sunday';
    $Monday = 'Monday';
    $Tuesday = 'Tuesday';
    $Wednesday = 'Wednesday';
    $Thursday = 'Thursday';
    $Friday = 'Friday';
    $Saturday = 'Saturday';

    $daySelect = $wpdb->get_results('SELECT DISTINCT(DAY(class_day)) AS class_day, class_teacher_name, student_id, available FROM wp_classes ORDER BY class_day ASC');

    $daysTeachers = $wpdb->get_results('SELECT 
                                            DAY(A.class_day) AS class_day, 
                                            B.class_teacher_name AS class_teacher_name
                                        FROM
                                            wp_classes AS A
                                        INNER JOIN
                                            wp_classes AS B
                                        ON
                                            A.class_day = B.class_day
                                        AND A.class_teacher_name <> B.class_teacher_name
                                        ORDER BY A.class_day ASC');

    $firstDay = $wpdb->get_row('SELECT DAY(class_day), DAYNAME(class_day) AS class_week, (CASE MONTHNAME(class_day) 
                                                                                            when "January" then "Janeiro"
                                                                                            when "February" then "Fevereiro"
                                                                                            when "March" then "Março"
                                                                                            when "April" then "Abril"
                                                                                            when "May" then "Maio"
                                                                                            when "June" then "Junho"
                                                                                            when "July" then "Julho"
                                                                                            when "August" then "Agosto"
                                                                                            when "September" then "Setembro"
                                                                                            when "October" then "Outubro"
                                                                                            when "November" then "Novembro"
                                                                                            when "December" then "Dezembro"
                                                                                            END) AS class_month, student_id FROM wp_classes WHERE DAY(class_day) = 1');

    $lastDay = $wpdb->get_row('SELECT MAX(DAY(class_day)) AS class_day FROM wp_classes');

    $lastDayName = $wpdb->get_row('SELECT DAYNAME(class_day) AS class_week FROM wp_classes WHERE DAY(class_day) = '. $lastDay->class_day);

    $buildFirstDay = '';
    $concTeachers = '';
    $counter = 0;
    $daysRemaining = 0;
    $concDay = '';

    foreach ( $daySelect AS $rowSelect ) {
        $listTeachers = array();
        if($counter == 0){
            foreach ( $daysTeachers AS $rowTeachers ) {
                if(($rowSelect->class_day == $rowTeachers->class_day) && 
                    (in_array($rowTeachers->class_teacher_name, $listTeachers) == false)){
                        array_push($listTeachers, $rowTeachers->class_teacher_name);
                        $concTeachers .= str_replace(' ', '-', $rowTeachers->class_teacher_name) . ' ';
                } else {
                    $counter++;
                    break;
                }
            }
        } else {
            break;
        }   
    }

    $buildFirstDay = '<td class="eventDay"><span class="date"><a class="' . $concTeachers . '"></a>1</span></td>';

    $contentSchedule = '<div id="monthClass" class="title">' . $firstDay->class_month . '</div>';
    $contentSchedule.= '<table border="1">
                        <tr>
                            <th>Domingo</th>
                            <th>Segunda</th>
                            <th>Terça</th>
                            <th>Quarta</th>
                            <th>Quinta</th>
                            <th>Sexta</th>
                            <th>Sábado</th>
                        </tr>
                        <tr>';
        
            if ($firstDay->class_week == $Sunday){
                $daysRemaining = 6;
                $contentSchedule .= $buildFirstDay;
            }
            
            if ($firstDay->class_week == $Monday) {
                $daysRemaining = 5;
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= $buildFirstDay;
            }  
            
            if ($firstDay->class_week == $Tuesday) {
                $daysRemaining = 4;
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= $buildFirstDay;
            } 
            
            if ($firstDay->class_week == $Wednesday) {
                $daysRemaining = 3;
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= $buildFirstDay;
            } 
            
            if ($firstDay->class_week == $Thursday) {
                $daysRemaining = 2;
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= $buildFirstDay;
            }
            
            if ($firstDay->class_week == $Friday) {
                $daysRemaining = 1;
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentSchedule .= $buildFirstDay;
            } 
            
            if ($firstDay->class_week == $Saturday) {
                $daysRemaining = 0;
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>'; //Sunday
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>'; //Monday
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>'; //Tuesday
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>'; //Wednesday
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>'; //Thursday
                $contentSchedule .= '<td class="soldOut"><span class="date">&nbsp;</span></td>'; //Friday
                $contentSchedule .= $buildFirstDay;
            }

            //Build final part of the calendar
            if ($lastDayName->class_week == $Sunday){
                $contentScheduleFinal = '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td></tr></table>';
            }
            
            if ($lastDayName->class_week == $Monday) {
                $contentScheduleFinal = '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td></tr></table>';
            }  
            
            if ($lastDayName->class_week == $Tuesday) {
                $contentScheduleFinal = '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td></tr></table>';
            } 
            
            if ($lastDayName->class_week == $Wednesday) {
                $contentScheduleFinal = '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"<span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td></tr></table>';
            } 
            
            if ($lastDayName->class_week == $Thursday) {
                $contentScheduleFinal = '<td class="soldOut"><span class="date">&nbsp;</span></td>';
                $contentScheduleFinal .= '<td class="soldOut"><span class="date">&nbsp;</span></td></tr></table>';
            }
            
            if ($lastDayName->class_week == $Friday) {
                $contentScheduleFinal = '<td class="soldOut"><span class="date">&nbsp;</span></td></tr></table>';
            } 
            
            if ($lastDayName->class_week == $Saturday) {
               $contentScheduleFinal = '</tr></table>';
            }
            
            //First Week
            $buildFirstWeek = '';
            foreach ( $daySelect AS $rowSelect ) {
                $dayContinue = $rowSelect->class_day;
                $concTeachers = '';
                $moreTeachers = false;
                if($daysRemaining > 0) {  
                    $listTeachers = array();
                    if ($rowSelect->class_day > 1){
                        foreach ( $daysTeachers AS $rowTeachers ) {
                            if(($rowSelect->class_day == $rowTeachers->class_day) && 
                                (in_array($rowTeachers->class_teacher_name, $listTeachers) == false)){
                                    $moreTeachers = true;
                                    array_push($listTeachers, $rowTeachers->class_teacher_name);
                                    $concTeachers .= str_replace(' ', '-', $rowTeachers->class_teacher_name) . ' ';
                            }
                        }
                        if((in_array($rowSelect->class_teacher_name, $listTeachers) == false) && ($moreTeachers == false)){
                             array_push($listTeachers, $rowSelect->class_teacher_name);
                             $concTeachers .= str_replace(' ', '-', $rowSelect->class_teacher_name) . ' ';
                        }

                        if($dayContinue != $concDay){
                            $buildFirstWeek .= '<td class="' . $rowSelect->available . '"><span class="date"><a class="' . $concTeachers . '"></a>'. $rowSelect->class_day .'</span></td>';
                            $daysRemaining--;
                        }

                        $concDay = $rowSelect->class_day;
                    }
                } else {
                    break;
                }
            }

            //Second Week
            $buildSecondWeek = '';
            $buildSecondWeek .= '</tr><tr>';
            $daysRemaining = 7;
            $daySelect = $wpdb->get_results('SELECT DAY(class_day) AS class_day, class_teacher_name, student_id, available FROM wp_classes WHERE DAY(class_day) >= ' . $dayContinue . ' ORDER BY class_day ASC');

            foreach ( $daySelect AS $rowSelect ) {
                $dayContinue = $rowSelect->class_day;
                $concTeachers = '';
                $moreTeachers = false;
                if($daysRemaining > 0) {  
                    $listTeachers = array();
                    if ($rowSelect->class_day > 1){
                        foreach ( $daysTeachers AS $rowTeachers ) {
                            if(($rowSelect->class_day == $rowTeachers->class_day) && 
                                (in_array($rowTeachers->class_teacher_name, $listTeachers) == false)){
                                    $moreTeachers = true;
                                    array_push($listTeachers, $rowTeachers->class_teacher_name);
                                    $concTeachers .= str_replace(' ', '-', $rowTeachers->class_teacher_name) . ' ';
                            }
                        }
                        if((in_array($rowSelect->class_teacher_name, $listTeachers) == false) && ($moreTeachers == false)){
                             array_push($listTeachers, $rowSelect->class_teacher_name);
                             $concTeachers .= str_replace(' ', '-', $rowSelect->class_teacher_name) . ' ';
                        }
                        
                        if($dayContinue != $concDay){
                            $buildSecondWeek .= '<td class="' . $rowSelect->available . '"><span class="date"><a class="' . $concTeachers . '"></a>'. $rowSelect->class_day .'</span></td>';
                            $daysRemaining--;
                        }

                        $concDay = $rowSelect->class_day;
                    }
                } else {
                    break;
                }
            }

            //Third Week
            $buildThirdWeek = '';
            $buildThirdWeek .= '</tr><tr>';
            $daysRemaining = 7;
            $daySelect = $wpdb->get_results('SELECT DAY(class_day) AS class_day, class_teacher_name, student_id, available FROM wp_classes WHERE DAY(class_day) >= ' . $dayContinue . ' ORDER BY class_day ASC');

            foreach ( $daySelect AS $rowSelect ) {
                $dayContinue = $rowSelect->class_day;
                $concTeachers = '';
                $moreTeachers = false;
                if($daysRemaining > 0) {  
                    $listTeachers = array();
                    if ($rowSelect->class_day > 1){
                        foreach ( $daysTeachers AS $rowTeachers ) {
                            if(($rowSelect->class_day == $rowTeachers->class_day) && 
                                (in_array($rowTeachers->class_teacher_name, $listTeachers) == false)){
                                    $moreTeachers = true;
                                    array_push($listTeachers, $rowTeachers->class_teacher_name);
                                    $concTeachers .= str_replace(' ', '-', $rowTeachers->class_teacher_name) . ' ';
                            }
                        }
                        if((in_array($rowSelect->class_teacher_name, $listTeachers) == false) && ($moreTeachers == false)){
                             array_push($listTeachers, $rowSelect->class_teacher_name);
                             $concTeachers .= str_replace(' ', '-', $rowSelect->class_teacher_name) . ' ';
                        }

                        if($dayContinue != $concDay){
                            $buildThirdWeek .= '<td class="' . $rowSelect->available . '"><span class="date"><a class="' . $concTeachers . '"></a>'. $rowSelect->class_day .'</span></td>';
                            $daysRemaining--;
                        }

                        $concDay = $rowSelect->class_day;
                    }
                } else {
                    break;
                }
            }

            //Fourth Week
            $buildFourthWeek = '';
            $buildFourthWeek .= '</tr><tr>';
            $daysRemaining = 7;
            $daySelect = $wpdb->get_results('SELECT DAY(class_day) AS class_day, class_teacher_name, student_id, available FROM wp_classes WHERE DAY(class_day) >= ' . $dayContinue . ' ORDER BY class_day ASC');

            foreach ( $daySelect AS $rowSelect ) {
                $dayContinue = $rowSelect->class_day;
                $concTeachers = '';
                $moreTeachers = false;

                if($daysRemaining > 0) {  
                    $listTeachers = array();
                    if ($rowSelect->class_day > 1){
                        foreach ( $daysTeachers AS $rowTeachers ) {
                            if(($rowSelect->class_day == $rowTeachers->class_day) && 
                                (in_array($rowTeachers->class_teacher_name, $listTeachers) == false)){
                                    $moreTeachers = true;
                                    array_push($listTeachers, $rowTeachers->class_teacher_name);
                                    $concTeachers .= str_replace(' ', '-', $rowTeachers->class_teacher_name) . ' ';
                            }
                        }
                        if((in_array($rowSelect->class_teacher_name, $listTeachers) == false) && ($moreTeachers == false)){
                             array_push($listTeachers, $rowSelect->class_teacher_name);
                             $concTeachers .= str_replace(' ', '-', $rowSelect->class_teacher_name) . ' ';
                        }

                        if($dayContinue != $concDay){
                            $buildFourthWeek .= '<td class="' . $rowSelect->available . '"><span class="date"><a class="' . $concTeachers . '"></a>'. $rowSelect->class_day .'</span></td>';
                            $daysRemaining--;
                        }

                        $concDay = $rowSelect->class_day;

                        if ($dayContinue == $lastDay->class_day){
                            $buildFourthWeek .= $contentScheduleFinal;
                        }
                    }
                } else {
                    break;
                }
            }

            if ($lastDay->class_day > $dayContinue) {
                //Fifth Week
                $buildFifthWeek = '';
                $buildFifthWeek .= '</tr><tr>';
                $daysRemaining = 7;
                $daySelect = $wpdb->get_results('SELECT DAY(class_day) AS class_day, class_teacher_name, student_id, available FROM wp_classes WHERE DAY(class_day) >= ' . $dayContinue . ' ORDER BY class_day ASC');

                foreach ( $daySelect AS $rowSelect ) {
                    $dayContinue = $rowSelect->class_day;
                    $concTeachers = '';
                    $moreTeachers = false;

                    if($daysRemaining > 0) {  
                        $listTeachers = array();
                        if ($rowSelect->class_day > 1){
                            foreach ( $daysTeachers AS $rowTeachers ) {
                                if(($rowSelect->class_day == $rowTeachers->class_day) && 
                                    (in_array($rowTeachers->class_teacher_name, $listTeachers) == false)){
                                        $moreTeachers = true;
                                        array_push($listTeachers, $rowTeachers->class_teacher_name);
                                        $concTeachers .= str_replace(' ', '-', $rowTeachers->class_teacher_name) . ' ';
                                }
                            }
                            if((in_array($rowSelect->class_teacher_name, $listTeachers) == false) && ($moreTeachers == false)){
                                array_push($listTeachers, $rowSelect->class_teacher_name);
                                $concTeachers .= str_replace(' ', '-', $rowSelect->class_teacher_name) . ' ';
                            }

                            if($dayContinue != $concDay){
                                $buildFifthWeek .= '<td class="' . $rowSelect->available . '"><span class="date"><a class="' . $concTeachers . '"></a>'. $rowSelect->class_day .'</span></td>';
                                $daysRemaining--;
                            }
    
                            $concDay = $rowSelect->class_day;
                            
                            if ($dayContinue == $lastDay->class_day){
                                $buildFifthWeek .= $contentScheduleFinal;
                            }
                        }
                    } else {
                        break;
                    }
                }
            }

            if ($lastDay->class_day > $dayContinue) {
                //Sixth Week
                $buildSixthWeek = '';
                $buildSixthWeek .= '</tr><tr>';
                $daysRemaining = 7;
                $daySelect = $wpdb->get_results('SELECT DAY(class_day) AS class_day, class_teacher_name, student_id, available FROM wp_classes WHERE DAY(class_day) >= ' . $dayContinue . ' ORDER BY class_day ASC');

                foreach ( $daySelect AS $rowSelect ) {
                    $dayContinue = $rowSelect->class_day;
                    $concTeachers = '';
                    $moreTeachers = false;

                    if($daysRemaining > 0) {  
                        $listTeachers = array();
                        if ($rowSelect->class_day > 1){
                            foreach ( $daysTeachers AS $rowTeachers ) {
                                if(($rowSelect->class_day == $rowTeachers->class_day) && 
                                    (in_array($rowTeachers->class_teacher_name, $listTeachers) == false)){
                                        $moreTeachers = true;
                                        array_push($listTeachers, $rowTeachers->class_teacher_name);
                                        $concTeachers .= str_replace(' ', '-', $rowTeachers->class_teacher_name) . ' ';
                                }
                            }
                            if((in_array($rowSelect->class_teacher_name, $listTeachers) == false) && ($moreTeachers == false)){
                                array_push($listTeachers, $rowSelect->class_teacher_name);
                                $concTeachers .= str_replace(' ', '-', $rowSelect->class_teacher_name) . ' ';
                            }

                            if($dayContinue != $concDay){
                                $buildSixthWeek .= '<td class="' . $rowSelect->available . '"><span class="date"><a class="' . $concTeachers . '"></a>'. $rowSelect->class_day .'</span></td>';
                                $daysRemaining--;
                            }
    
                            $concDay = $rowSelect->class_day;

                            if ($dayContinue == $lastDay->class_day){
                                $buildSixthWeek .= $contentScheduleFinal;
                            }
                        }
                    } else {
                        break;
                    }
                }
            }

            echo $contentSchedule;
            echo $buildFirstWeek;
            echo $buildSecondWeek;
            echo $buildThirdWeek;
            echo $buildFourthWeek;
            echo $buildFifthWeek;
            echo $buildSixthWeek;

?>

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
