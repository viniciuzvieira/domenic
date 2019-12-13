<?php
/**
 * Section Course Features
 *
 * @package Eduma
 */

thim_customizer()->add_section(
    array(
        'id'       => 'course_features',
        'panel'    => 'course',
        'title'    => esc_html__( 'Features', 'eduma' ),
        'priority' => 20,
    )
);

// Enable or Disable Login Popup when take this course
thim_customizer()->add_field(
    array(
        'id'          => 'thim_learnpress_single_popup',
        'type'        => 'switch',
        'label'       => esc_html__( 'Enable Login Popup', 'eduma' ),
        'tooltip'     => esc_html__( 'Enable login popup when take this course with user not logged in.', 'eduma' ),
        'section'     => 'course_features',
        'default'     => true,
        'priority'    => 15,
        'choices'     => array(
            true  	  => esc_html__( 'Show', 'eduma' ),
            false	  => esc_html__( 'Hide', 'eduma' ),
        ),
    )
);

// Feature: Body custom class
thim_customizer()->add_field(
    array(
        'type'     => 'text',
        'id'       => 'thim_learnpress_one_course_id',
        'label'    => esc_html__( 'One Course ID', 'eduma' ),
        'tooltip'  => esc_html__( 'Only use for Demo One Course.', 'eduma' ),
        'section'  => 'course_features',
        'priority' => 20,
    )
);

// Feature: Setup contact form 7 shortcode
thim_customizer()->add_field(
    array(
        'type'     => 'text',
        'id'       => 'thim_learnpress_shortcode_contact',
        'label'    => esc_html__( 'Contact Form 7 Shortcode', 'eduma' ),
        'tooltip'  => esc_html__( 'Only use for Demo Kindergarten.', 'eduma' ),
        'section'  => 'course_features',
        'priority' => 30,
    )
);

// Setup timetable link for single course
thim_customizer()->add_field(
    array(
        'type'     => 'text',
        'id'       => 'thim_learnpress_timetable_link',
        'label'    => esc_html__( 'Timetable Link', 'eduma' ),
        'tooltip'  => esc_html__( 'Only use for Demo Kindergarten.', 'eduma' ),
        'section'  => 'course_features',
        'priority' => 40,
    )
);

// Enable or Disable Login Popup when take this course
thim_customizer()->add_field(
    array(
        'id'          => 'thim_learnpress_hidden_ads',
        'type'        => 'switch',
        'label'       => esc_html__( 'Hidden Ads', 'eduma' ),
        'tooltip'     => esc_html__( 'Hidden ads learnpress on WordPress admin.', 'eduma' ),
        'section'     => 'course_features',
        'default'     => true,
        'priority'    => 50,
        'choices'     => array(
            true  	  => esc_html__( 'Show', 'eduma' ),
            false	  => esc_html__( 'Hide', 'eduma' ),
        ),
    )
);