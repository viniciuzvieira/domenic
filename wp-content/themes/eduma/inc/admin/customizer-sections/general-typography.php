<?php
/**
 * Panel and Group Typography
 *
 * @package Hair_Salon
 */

thim_customizer()->add_section(
    array(
        'id'       => 'typography',
        'panel'    => 'general',
        'priority' => 60,
        'title'    => esc_html__( 'Typography', 'eduma' ),
    )
);

// Body_Typography_Group
thim_customizer()->add_group( array(
    'id'       => 'body_typography',
    'section'  => 'typography',
    'priority' => 10,
    'groups'   => array(
        array(
            'id'     => 'thim_body_group',
            'label'  => esc_html__( 'Body', 'eduma' ),
            'fields' => array(
                array(
                    'id'        => 'thim_font_body',
                    'label'     => esc_html__( 'Body Font', 'eduma' ),
                    'tooltip'  => esc_html__( 'Allows you to select all font properties of body tag for your site', 'eduma' ),
                    'type'      => 'typography',
                    'priority'    => 10,
                    'default'     => array(
                        'font-family'    => 'Roboto',
                        'variant'        => '400',
                        'font-size'      => '15px',
                        'line-height'    => '1.7em',
                        'color'          => '#666666',
                    ),
                    'transport' => 'postMessage',
                    'js_vars'   => array(
                        array(
                            'choice'   => 'font-family',
                            'element'  => 'body',
                            'property' => 'font-family',
                        ),
                        array(
                            'choice'   => 'variant',
                            'element'  => 'body',
                            'property' => 'font-weight',
                        ),
                        array(
                            'choice'   => 'font-size',
                            'element'  => 'body',
                            'property' => 'font-size',
                        ),
                        array(
                            'choice'   => 'line-height',
                            'element'  => 'body',
                            'property' => 'line-height',
                        ),
                        array(
                            'choice'   => 'color',
                            'element'  => 'body',
                            'property' => 'color',
                        ),
                    )
                ),
            ),
        ),
    )
) );