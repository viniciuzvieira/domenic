<?php

/**
 * Template Name: Create Schedule
 * Template Post Type: page, ae_global_templates
 **/
?>

<div class="progress" style="display: none;">
      <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            <span id="current-progress"></span>
      </div>
</div>

<?php

// $months = $wpdb->get_results('SELECT
//                                     MONTHNAME(class_day - INTERVAL 1 MONTH) AS MONTH1,
//                                     MONTHNAME(class_day) AS MONTH2,
//                                     MONTHNAME(class_day + INTERVAL 1 MONTH) AS MONTH3,
//                                     MONTHNAME(class_day + INTERVAL 2 MONTH) AS MONTH4 
//                               FROM
//                                     `wp_classes` 
//                               WHERE
//                                     MONTH(class_day) = MONTH(NOW()) 
//                               GROUP BY
//                                     MONTH1,
//                                     MONTH2,
//                                     MONTH3,
//                                     MONTH4');

// foreach ( $months AS $rowSelect ) {

//       $buttonMoth = '<input type="submit" value="GERAR CALENDÁRIO" class="wpcf7-form-control wpcf7-submit">';

// }

$month1 = month_translate(date('F', strtotime('first day of -1 month')));
$month2 = month_translate(date('F'));
$month3 = month_translate(date('F', strtotime('first day of +1 month')));
$month4 = month_translate(date('F', strtotime('first day of +2 month')));


function month_translate($month)
{
      switch ($month) {
            case 'January':
                  $month = 'Janeiro';
                  $monthHTML = '<input type="submit" value="Janeiro" class="button button-small january">';
                  break;
            case 'February':
                  $month = 'Fevereiro';
                  $monthHTML = '<input type="submit" value="Fevereiro" class="button button-small february">';
                  break;
            case 'March':
                  $month = 'Março';
                  $monthHTML = '<input type="submit" value="Março" class="button button-small march">';
                  break;
            case 'April':
                  $month = 'Abril';
                  $monthHTML = '<input type="submit" value="Abril" class="button button-small april">';
                  break;
            case 'May':
                  $month = 'Maio';
                  $monthHTML = '<input type="submit" value="Maio" class="button button-small may">';
                  break;
            case 'June':
                  $month = 'Junho';
                  $monthHTML = '<input type="submit" value="Junho" class="button button-small june">';
                  break;
            case 'July':
                  $month = 'Julho';
                  $monthHTML = '<input type="submit" value="Julho" class="button button-small july">';
                  break;
            case 'August':
                  $month = 'Agosto';
                  $monthHTML = '<input type="submit" value="Agosto" class="button button-small august">';
                  break;
            case 'September':
                  $month = 'Setembro';
                  $monthHTML = '<input type="submit" value="Setembro" class="button button-small september">';
                  break;
            case 'October':
                  $month = 'Outubro';
                  $monthHTML = '<input type="submit" value="Outubro" class="button button-small october">';
                  break;
            case 'November':
                  $month = 'Novembro';
                  $monthHTML = '<input type="submit" value="Novembro" class="button button-small november">';
                  break;
            case 'December':
                  $month = 'Dezembro';
                  $monthHTML = '<input type="submit" value="Dezembro" class="button button-small december">';
                  break;
            default:
                  $monthHTML = '';
                  break;
      }

      return $monthHTML;
}

echo $month1;
echo $month2;
echo $month3;
echo $month4;

echo do_shortcode('[contact-form-7 id="241" title="Criar Calendário"]');

?>