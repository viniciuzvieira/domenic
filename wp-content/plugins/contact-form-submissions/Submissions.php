<?php

require_once(ABSPATH . 'wp-content\plugins\PHPExcel-1.8\Classes\PHPExcel.php');

class WPCF7Submissions
{
    public function __construct()
    {
        add_action('init', array($this, 'post_type'));

        add_action('wpcf7_mail_components', array($this, 'submission'), 999, 2);
        add_filter('wpcf7_posted_data', array($this, 'posted'), 999, 3);
    }

    /**
     * Register the post type
     */
    public function post_type()
    {
        $labels = array(
            'name'                => __('Contact Form Submissions', 'contact-form-submissions'),
            'singular_name'       => __('Submission', 'contact-form-submissions'),
            'menu_name'           => __('Submission', 'contact-form-submissions'),
            'all_items'           => __('Submissions', 'contact-form-submissions'),
            'view_item'           => __('Submission', 'contact-form-submissions'),
            'edit_item'           => __('Submission', 'contact-form-submissions'),
            'search_items'        => __('Search', 'contact-form-submissions'),
            'not_found'           => __('Not found', 'contact-form-submissions'),
            'not_found_in_trash'  => __('Not found in Trash', 'contact-form-submissions'),
        );
        $args = array(
            'label'               => __('Submission', 'contact-form-submissions'),
            'description'         => __('Post Type Description', 'contact-form-submissions'),
            'labels'              => $labels,
            'supports'            => false,
            'hierarchical'        => true,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'wpcf7',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'rewrite'             => false,
            'capability_type'     => 'page',
            'query_var'           => false,
            'capabilities' => array(
                'create_posts'  => false
            ),
            'map_meta_cap' => true
        );
        register_post_type('wpcf7s', $args);
    }

    /**
     * Hook into when a cf7 form is submitted to save the post data
     */
    public function posted($posted_data)
    {
        global $wpcf7s_posted_data;

        $wpcf7s_posted_data = $posted_data;

        return $posted_data;
    }

    /**
     * Hook into when a cf7 form has been submitted and the values have been inserted
     *
     * @param  [type] $components   [description]
     * @param  [type] $contact_form [description]
     * @param  [type] $mail         [description]
     *
     * @return [type]               [description]
     */
    public function submission($components, $contact_form)
    {
        global $wpcf7s_post_id, $wpcf7s_posted_data;

        $submission = WPCF7_Submission::get_instance();

        $contact_form_id = 0;
        if (method_exists($contact_form, 'id')) {
            $contact_form_id = $contact_form->id();
        } elseif (property_exists($contact_form, 'id')) {
            $contact_form_id = $contact_form->id;
        }

        // don't save mail2 autoresponders by default
        if (!empty($wpcf7s_post_id) && false === apply_filters('wpcf7s_save_submission_mail2', true, $contact_form_id)) {
            return $components;
        }

        if (!empty($wpcf7s_posted_data)) {
            foreach ($wpcf7s_posted_data as $name => $value) {
                if ('_wpcf7' !== substr($name, 0, 6)) {
                    // skip empty arrays
                    if (is_array($value) && !array_filter($value)) {
                        continue;
                    }

                    $fields[$name] = $value;
                }
            }
        }

        $body = $components['body'];
        $sender = wpcf7_strip_newline($components['sender']);
        $recipient = wpcf7_strip_newline($components['recipient']);
        $subject = wpcf7_strip_newline($components['subject']);
        $headers = trim($components['additional_headers']);

        // get the form file attachements
        $attachments = $submission->uploaded_files();

        $submission = array(
            'form_id'   => $contact_form_id,
            'body'      => $body,
            'sender'    => $sender,
            'subject'   => $subject,
            'recipient' => $recipient,
            'additional_headers' => $headers,
            'attachments' => $attachments,
            'fields'    => $fields
        );

        if (!empty($wpcf7s_post_id)) {
            $submission['parent'] = $wpcf7s_post_id;
        }

        // store the form submission
        $post_id = $this->save($submission);

        if (empty($wpcf7s_post_id)) {
            $wpcf7s_post_id = $post_id;
        }

        return $components;
    }

    /**
     * Save the form submission into the db
     */
    private function save($submission = array())
    {
        if (true === apply_filters('wpcf7s_save_submission', true, $submission['form_id'])) {
            $post = array(
                'post_title'    => ' ',
                'post_content'  => $submission['body'],
                'post_status'   => 'publish',
                'post_type'     => 'wpcf7s',
            );

            if (isset($submission['parent'])) {
                $post['post_parent'] = $submission['parent'];
            }

            $post_id = wp_insert_post($post);

            // check the post was created
            if (!empty($post_id) && !is_wp_error($post_id)) {

                add_post_meta($post_id, 'form_id', $submission['form_id']);
                add_post_meta($post_id, 'subject', $submission['subject']);
                add_post_meta($post_id, 'sender', $submission['sender']);
                add_post_meta($post_id, 'recipient', $submission['recipient']);
                add_post_meta($post_id, 'additional_headers', $submission['additional_headers']);

                $additional_fields = apply_filters('wpcf7s_submission_fields', $submission['fields'], $submission['form_id']);
                if (!empty($additional_fields)) {
                    foreach ($additional_fields as $name => $value) {
                        if (!empty($value)) {
                            add_post_meta($post_id, 'wpcf7s_posted-' . $name, $value);
                        }
                    }
                }

                $attachments = $submission['attachments'];
                if (!empty($attachments)) {

                    $insert = '';
                    global $wpdb;

                    $wpcf7s_dir = $this->get_wpcf7s_dir();
                    // add a sub directory of the submission post id
                    $wpcf7s_dir .= '/' . $post_id;

                    mkdir($wpcf7s_dir, 0755, true);

                    foreach ($attachments as $name => $file_path) {
                        if (!empty($file_path)) {
                            // get the file name
                            $file_name = basename($file_path);

                            $copied = copy($file_path, $wpcf7s_dir . '/' . $file_name);

                            add_post_meta($post_id, 'wpcf7s_file-' . $name, $file_name, false);
                        }
                    }
                }
            }

                    session_start();
                    $_SESSION["create_schedule"] = true;

                    $objReader = new PHPExcel_Reader_Excel5();
                    $objReader->setReadDataOnly(true);

                    $objPHPExcel = $objReader->load($wpcf7s_dir . '/' . 'wp_classes.xls');

                    $columns = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
                    $total_columns = PHPExcel_Cell::columnIndexFromString($columns);

                    $total_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

                    for ($row = 2; $row <= $total_rows; $row++) {
                        $wp_classes = array();
                        for ($column = 0; $column <= $total_columns - 1; $column++) {
                            array_push($wp_classes, $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($column, $row)->getFormattedValue());
                        }
                        
                        $wp_classes[1] = implode('-', array_reverse(explode('/', $wp_classes[1])));

                        $insert = $wpdb->prepare( '(%s, %s, %s, %s, %s)', $wp_classes[0], $wp_classes[1], $wp_classes[2], $wp_classes[3], $wp_classes[4]);
                        $wpdb->query( "INSERT INTO bitnami_wordpress.wp_classes VALUES " . $insert );
                    }

            global $daySelect;
            
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
                                                                                                    END) AS class_month, student_id, available FROM wp_classes WHERE DAY(class_day) = 1');

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

            $buildFirstDay = '<td class="'. $firstDay->available .'"><span class="date"><a class="' . $concTeachers . '"></a>1</span></td>';

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

                    $insert = '';
                    
                    $insert = $wpdb->prepare( '(%s, %s, %s, %s, %s, %s, %s, %s)', $contentSchedule, $buildFirstWeek, $buildSecondWeek, $buildThirdWeek, $buildFourthWeek, $buildFifthWeek, $buildSixthWeek, $contentScheduleFinal);
                    $wpdb->query( "INSERT INTO bitnami_wordpress.wp_build_schedule VALUES " . $insert );

            return $post_id;
        }
    }

    /**
     * Get the path of where uploads go
     *
     * @return string full path
     */
    public function get_wpcf7s_dir()
    {
        $upload_dir = wp_upload_dir();
        $wpcf7s_dir = apply_filters('wpcf7s_dir', $upload_dir['basedir'] . '/wpcf7-submissions');

        return $wpcf7s_dir;
    }

    /**
     * Get the url of where uploads go
     *
     * @return string full url
     */
    public function get_wpcf7s_url()
    {
        $upload_dir = wp_upload_dir();
        $wpcf7s_url = apply_filters('wpcf7s_url', $upload_dir['baseurl'] . '/wpcf7-submissions');

        return $wpcf7s_url;
    }
}
