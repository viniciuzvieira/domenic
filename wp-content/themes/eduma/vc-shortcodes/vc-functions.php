<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THIM_CHILD_THEME_DIR', trailingslashit( get_stylesheet_directory() ) );

/**
 * Generate param type "number"
 *
 * @param $settings
 * @param $value
 *
 * @return string
 */

function thim_sc_number_settings_field( $settings, $value ) {
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$min        = isset( $settings['min'] ) ? $settings['min'] : '';
	$max        = isset( $settings['max'] ) ? $settings['max'] : '';
	$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$output     = '<input type="number" min="' . $min . '" max="' . $max . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" style="max-width:100px; margin-right: 10px;" />' . $suffix;

	return $output;
}

vc_add_shortcode_param( 'number', 'thim_sc_number_settings_field' );

function thim_vc_dropdown_multiple_form_field( $settings, $value ) {
	$output     = '';
	$css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );
	$output     .= '<select name="'
	               . $settings['param_name']
	               . '" class="wpb_vc_param_value wpb-input wpb-select '
	               . $settings['param_name']
	               . ' ' . $settings['type']
	               . ' ' . $css_option
	               . '" multiple data-option="' . $css_option . '">';
	if ( is_array( $value ) ) {
		$value = isset( $value['value'] ) ? $value['value'] : array_shift( $value );
	}
	if ( ! empty( $settings['value'] ) ) {
		foreach ( $settings['value'] as $index => $data ) {
			if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
				$option_label = $data;
				$option_value = $data;
			} elseif ( is_numeric( $index ) && is_array( $data ) ) {
				$option_label = isset( $data['label'] ) ? $data['label'] : array_pop( $data );
				$option_value = isset( $data['value'] ) ? $data['value'] : array_pop( $data );
			} else {
				$option_value = $data;
				$option_label = $index;
			}
			$selected            = '';
			$option_value_string = (string) $option_value;
			$value_string        = (string) $value;
			if ( '' !== $value && $option_value_string === $value_string ) {
				$selected = ' selected="selected"';
			}
			$option_class = str_replace( '#', 'hash-', $option_value );
			$output       .= '<option class="' . esc_attr( $option_class ) . '" value="' . esc_attr( $option_value ) . '"' . $selected . '>'
			                 . htmlspecialchars( $option_label ) . '</option>';
		}
	}
	$output .= '</select>';

	return $output;
}

vc_add_shortcode_param( 'dropdown_multiple', 'thim_vc_dropdown_multiple_form_field' );

/**
 * Get post categories array
 *
 * @return array
 */
function thim_sc_get_categories() {
	$args                                  = array(
		'orderby' => 'id',
		'parent'  => 0
	);
	$items                                 = array();
	$items[ esc_html__( 'All', 'eduma' ) ] = 'all';
	$categories                            = get_categories( $args );
	if ( isset( $categories ) ) {
		foreach ( $categories as $key => $cat ) {
			$items[ $cat->cat_name ] = $cat->cat_ID;
			$childrens               = get_term_children( $cat->term_id, $cat->taxonomy );
			if ( $childrens ) {
				foreach ( $childrens as $key => $children ) {
					$child                        = get_term_by( 'id', $children, $cat->taxonomy );
					$items[ '--' . $child->name ] = $child->term_id;

				}
			}
		}
	}

	return $items;
}

function thim_sc_get_list_image_size() {
	global $_wp_additional_image_sizes;

	$sizes                        = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach ( $get_intermediate_image_sizes as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

			$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop']
			);

		}

	}

	$image_size                                          = array();
	$image_size[ esc_html__( "No Image", 'eduma' ) ]     = 'none';
	$image_size[ esc_html__( "Custom Image", 'eduma' ) ] = 'custom_image';
	if ( ! empty( $sizes ) ) {
		foreach ( $sizes as $key => $value ) {
			if ( $value['width'] && $value['height'] ) {
				$image_size[ $value['width'] . 'x' . $value['height'] ] = $key;
			} else {
				$image_size[ $key ] = $key;
			}
		}
	}

	return $image_size;
}

/**
 * Custom excerpt
 *
 * @param $length
 *
 * @return string
 */
function thim_sc_get_the_excerpt( $length ) {
	$excerpt = get_the_excerpt();

	if ( ! $excerpt ) {
		$excerpt = __( 'Sometimes, a picture is worth a thousand words.', 'eduma' );
	} else {
		$words   = explode( ' ', $excerpt );
		$excerpt = '';

		foreach ( $words as $word ) {
			if ( strlen( $excerpt ) < $length ) {
				$excerpt .= $word . ' ';
			} else {
				break;
			}
		}
	}

	return $excerpt . '...';
}

// Get list Instructor
if ( thim_plugin_active( 'learnpress-co-instructor/learnpress-co-instructor.php' ) ) {
    function thim_sc_get_instructors() {
        $co_instructors = thim_get_all_courses_instructors();
        $ins[''] = esc_html__( 'Select', 'eduma' );
        if ( !empty( $co_instructors ) ) {
            foreach ( $co_instructors as $key => $value ) {
                $ins[get_the_author_meta( 'display_name', $value["user_id"] )] = $value["user_id"];
            }
        }

        return $ins;
    }
}


function thim_sc_get_course_categories( $cats = false ) {
	global $wpdb;
	$query = $wpdb->get_results( $wpdb->prepare(
		"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
		'course_category', 0
	) );

	if ( ! $cats ) {
		$cats = array();
	}
	if ( ! empty( $query ) ) {
		foreach ( $query as $key => $value ) {
			$cats[ $value->name ] = $value->term_id;
		}
	}

	return $cats;
}

function thim_sc_get_event_categories( $cats = false ) {
    global $wpdb;
    $query = $wpdb->get_results( $wpdb->prepare(
        "
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  ",
        'tp_event_category'
    ) );

    if ( ! $cats ) {
        $cats = array();
    }
    if ( ! empty( $query ) ) {
        foreach ( $query as $key => $value ) {
            $cats[ $value->name ] = $value->term_id;
        }
    }

    return $cats;
}

// Get list category our team
function thim_sc_get_team_categories() {
	global $wpdb;
	$query = $wpdb->get_results( $wpdb->prepare(
		"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
		'our_team_category', 0
	) );

	$cats        = array();
	$cats['All'] = esc_html__( 'all', 'eduma' );
	if ( ! empty( $query ) ) {
		foreach ( $query as $key => $value ) {
			$cats[ $value->name ] = $value->term_id;
		}
	}

	return $cats;
}

function thim_sc_get_portfolio_categories() {
	$portfolio_category = get_terms( 'portfolio_category', array(
		'hide_empty' => 0,
		'orderby'    => 'ASC',
		'parent'     => 0
	) );

	$cate                                 = array();
	$cate[ esc_html__( 'All', 'eduma' ) ] = '';
	if ( is_array( $portfolio_category ) ) {
		foreach ( $portfolio_category as $cat ) {
			$cate[ $cat->name ] = $cat->term_id;
		}
	}

	return $cate;
}

function thim_sc_get_categories_autocomplete() {
	$args       = array(
		'orderby' => 'id',
		'parent'  => 0
	);
	$items      = array();
	$categories = get_categories( $args );
	if ( isset( $categories ) ) {
		foreach ( $categories as $key => $cat ) {
			$items[]   = array(
				'value' => $cat->cat_ID,
				'label' => $cat->cat_name
			);
			$childrens = get_term_children( $cat->term_id, $cat->taxonomy );
			if ( $childrens ) {
				foreach ( $childrens as $key => $children ) {
					$child   = get_term_by( 'id', $children, $cat->taxonomy );
					$items[] = array(
						'value' => $child->term_id,
						'label' => '--' . $child->name
					);
				}
			}
		}
	}

	return $items;
}

if ( ! function_exists( 'thim_modify_icon_param' ) ) {
    function thim_modify_icon_param() {
        if ( class_exists( 'Vc_Manager' ) ) {
            $settings_icon_type = array(
                __( 'font_ionicons', 'course-builder' ) => 'font_ionicons'
            );
            vc_map_update( 'icon_type-', $settings_icon_type ); // Note: 'vc_message' was used as a base for "Message box" element
        }
    }
}

add_action( 'plugins_loaded', 'thim_modify_icon_param' );

add_filter( 'vc_iconpicker-type-ionicons', 'thim_vc_iconpicker_type_ionicons' );

/**
 * Openicons icons from fontello.com
 *
 * @param $icons - taken from filter - vc_map param field settings['source']
 *               provided icons (default empty array). If array categorized it will
 *               auto-enable category dropdown
 *
 * @since 4.4
 * @return array - of icons for iconpicker, can be categorized, or not.
 */

add_action( 'vc_base_register_front_css', 'thim_vc_iconpicker_base_register_css' );
add_action( 'vc_base_register_admin_css', 'thim_vc_iconpicker_base_register_css' );
function thim_vc_iconpicker_base_register_css() {
    wp_register_style( 'font_ionicons', THIM_URI . '/assets/css/ionicons.min.css' );
}

add_action( 'vc_backend_editor_enqueue_js_css', 'thim_vc_iconpicker_editor_jscss' );
add_action( 'vc_frontend_editor_enqueue_js_css', 'thim_vc_iconpicker_editor_jscss' );
function thim_vc_iconpicker_editor_jscss() {
    wp_enqueue_style( 'font_ionicons' );
}

add_action( 'vc_enqueue_font_icon_element', 'thim_enqueue_font_iconssolid' );
function thim_enqueue_font_iconssolid( $font ) {
    switch ( $font ) {
        case 'font_ionicons':
            wp_enqueue_style( 'font_ionicons' );
    }
}

function thim_vc_iconpicker_type_ionicons( $icons ) {
    $font_ionicons = array(
        array( 'ion-alert' => 'Alert' ),
        array( 'ion-alert-circled' => 'Alert Circled' ),
        array( 'ion-android-add' => 'Android Add' ),
        array( 'ion-android-add-circle' => 'Android Add Circle' ),
        array( 'ion-android-alarm-clock' => 'Android Alarm Clock' ),
        array( 'ion-android-alert' => 'Android Alert' ),
        array( 'ion-android-apps' => 'Android Apps' ),
        array( 'ion-android-archive' => 'Android Archive' ),
        array( 'ion-android-arrow-back' => 'Android Arrow Back' ),
        array( 'ion-android-arrow-down' => 'Android Arrow Down' ),
        array( 'ion-android-arrow-dropdown' => 'Android Arrow Dropdown' ),
        array( 'ion-android-arrow-dropdown-circle' => 'Android Arrow Dropdown Circle' ),
        array( 'ion-android-arrow-dropleft' => 'Android Arrow Dropleft' ),
        array( 'ion-android-arrow-dropleft-circle' => 'Android Arrow Dropleft Circle' ),
        array( 'ion-android-arrow-dropright' => 'Android Arrow Dropright' ),
        array( 'ion-android-arrow-dropright-circle' => 'Android Arrow Dropright Circle' ),
        array( 'ion-android-arrow-dropup' => 'Android Arrow Dropup' ),
        array( 'ion-android-arrow-dropup-circle' => 'Android Arrow Dropup Circle' ),
        array( 'ion-android-arrow-forward' => 'Android Arrow Forward' ),
        array( 'ion-android-arrow-up' => 'Android Arrow Up' ),
        array( 'ion-android-attach' => 'Android Attach' ),
        array( 'ion-android-bar' => 'Android Bar' ),
        array( 'ion-android-bicycle' => 'Android Bicycle' ),
        array( 'ion-android-boat' => 'Android Boat' ),
        array( 'ion-android-bookmark' => 'Android Bookmark' ),
        array( 'ion-android-bulb' => 'Android Bulb' ),
        array( 'ion-android-bus' => 'Android Bus' ),
        array( 'ion-android-calendar' => 'Android Calendar' ),
        array( 'ion-android-call' => 'Android Call' ),
        array( 'ion-android-camera' => 'Android Camera' ),
        array( 'ion-android-cancel' => 'Android Cancel' ),
        array( 'ion-android-car' => 'Android Car' ),
        array( 'ion-android-cart' => 'Android Cart' ),
        array( 'ion-android-chat' => 'Android Chat' ),
        array( 'ion-android-checkbox' => 'Android Checkbox' ),
        array( 'ion-android-checkbox-blank' => 'Android Checkbox Blank' ),
        array( 'ion-android-checkbox-outline' => 'Android Checkbox Outline' ),
        array( 'ion-android-checkbox-outline-blank' => 'Android Checkbox Outline Blank' ),
        array( 'ion-android-checkmark-circle' => 'Android Checkmark Circle' ),
        array( 'ion-android-clipboard' => 'Android Clipboard' ),
        array( 'ion-android-close' => 'Android Close' ),
        array( 'ion-android-cloud' => 'Android Cloud' ),
        array( 'ion-android-cloud-circle' => 'Android Cloud Circle' ),
        array( 'ion-android-cloud-done' => 'Android Cloud Done' ),
        array( 'ion-android-cloud-outline' => 'Android Cloud Outline' ),
        array( 'ion-android-color-palette' => 'Android Color Palette' ),
        array( 'ion-android-compass' => 'Android Compass' ),
        array( 'ion-android-contact' => 'Android Contact' ),
        array( 'ion-android-contacts' => 'Android Contacts' ),
        array( 'ion-android-contract' => 'Android Contract' ),
        array( 'ion-android-create' => 'Android Create' ),
        array( 'ion-android-delete' => 'Android Delete' ),
        array( 'ion-android-desktop' => 'Android Desktop' ),
        array( 'ion-android-document' => 'Android Document' ),
        array( 'ion-android-done' => 'Android Done' ),
        array( 'ion-android-done-all' => 'Android Done All' ),
        array( 'ion-android-download' => 'Android Download' ),
        array( 'ion-android-drafts' => 'Android Drafts' ),
        array( 'ion-android-exit' => 'Android Exit' ),
        array( 'ion-android-expand' => 'Android Expand' ),
        array( 'ion-android-favorite' => 'Android Favorite' ),
        array( 'ion-android-favorite-outline' => 'Android Favorite Outline' ),
        array( 'ion-android-film' => 'Android Film' ),
        array( 'ion-android-folder' => 'Android Folder' ),
        array( 'ion-android-folder-open' => 'Android Folder Open' ),
        array( 'ion-android-funnel' => 'Android Funnel' ),
        array( 'ion-android-globe' => 'Android Globe' ),
        array( 'ion-android-hand' => 'Android Hand' ),
        array( 'ion-android-hangout' => 'Android Hangout' ),
        array( 'ion-android-happy' => 'Android Happy' ),
        array( 'ion-android-home' => 'Android Home' ),
        array( 'ion-android-image' => 'Android Image' ),
        array( 'ion-android-laptop' => 'Android Laptop' ),
        array( 'ion-android-list' => 'Android List' ),
        array( 'ion-android-locate' => 'Android Locate' ),
        array( 'ion-android-lock' => 'Android Lock' ),
        array( 'ion-android-mail' => 'Android Mail' ),
        array( 'ion-android-map' => 'Android Map' ),
        array( 'ion-android-menu' => 'Android Menu' ),
        array( 'ion-android-microphone' => 'Android Microphone' ),
        array( 'ion-android-microphone-off' => 'Android Microphone Off' ),
        array( 'ion-android-more-horizontal' => 'Android More Horizontal' ),
        array( 'ion-android-more-vertical' => 'Android More Vertical' ),
        array( 'ion-android-navigate' => 'Android Navigate' ),
        array( 'ion-android-notifications' => 'Android Notifications' ),
        array( 'ion-android-notifications-none' => 'Android Notifications None' ),
        array( 'ion-android-notifications-off' => 'Android Notifications Off' ),
        array( 'ion-android-open' => 'Android Open' ),
        array( 'ion-android-options' => 'Android Options' ),
        array( 'ion-android-people' => 'Android People' ),
        array( 'ion-android-person' => 'Android Person' ),
        array( 'ion-android-person-add' => 'Android Person Add' ),
        array( 'ion-android-phone-landscape' => 'Android Phone Landscape' ),
        array( 'ion-android-phone-portrait' => 'Android Phone Portrait' ),
        array( 'ion-android-pin' => 'Android Pin' ),
        array( 'ion-android-plane' => 'Android Plane' ),
        array( 'ion-android-playstore' => 'Android Playstore' ),
        array( 'ion-android-print' => 'Android Print' ),
        array( 'ion-android-radio-button-off' => 'Android Radio Button Off' ),
        array( 'ion-android-radio-button-on' => 'Android Radio Button On' ),
        array( 'ion-android-refresh' => 'Android Refresh' ),
        array( 'ion-android-remove' => 'Android Remove' ),
        array( 'ion-android-remove-circle' => 'Android Remove Circle' ),
        array( 'ion-android-restaurant' => 'Android Restaurant' ),
        array( 'ion-android-sad' => 'Android Sad' ),
        array( 'ion-android-search' => 'Android Search' ),
        array( 'ion-android-send' => 'Android Send' ),
        array( 'ion-android-settings' => 'Android Settings' ),
        array( 'ion-android-share' => 'Android Share' ),
        array( 'ion-android-share-alt' => 'Android Share Alt' ),
        array( 'ion-android-star' => 'Android Star' ),
        array( 'ion-android-star-half' => 'Android Star Half' ),
        array( 'ion-android-star-outline' => 'Android Star Outline' ),
        array( 'ion-android-stopwatch' => 'Android Stopwatch' ),
        array( 'ion-android-subway' => 'Android Subway' ),
        array( 'ion-android-sunny' => 'Android Sunny' ),
        array( 'ion-android-sync' => 'Android Sync' ),
        array( 'ion-android-textsms' => 'Android Textsms' ),
        array( 'ion-android-time' => 'Android Time' ),
        array( 'ion-android-train' => 'Android Train' ),
        array( 'ion-android-unlock' => 'Android Unlock' ),
        array( 'ion-android-upload' => 'Android Upload' ),
        array( 'ion-android-volume-down' => 'Android Volume Down' ),
        array( 'ion-android-volume-mute' => 'Android Volume Mute' ),
        array( 'ion-android-volume-off' => 'Android Volume Off' ),
        array( 'ion-android-volume-up' => 'Android Volume Up' ),
        array( 'ion-android-walk' => 'Android Walk' ),
        array( 'ion-android-warning' => 'Android Warning' ),
        array( 'ion-android-watch' => 'Android Watch' ),
        array( 'ion-android-wifi' => 'Android Wifi' ),
        array( 'ion-aperture' => 'Aperture' ),
        array( 'ion-archive' => 'Archive' ),
        array( 'ion-arrow-down-a' => 'Arrow Down A' ),
        array( 'ion-arrow-down-b' => 'Arrow Down B' ),
        array( 'ion-arrow-down-c' => 'Arrow Down C' ),
        array( 'ion-arrow-expand' => 'Arrow Expand' ),
        array( 'ion-arrow-graph-down-left' => 'Arrow Graph Down Left' ),
        array( 'ion-arrow-graph-down-right' => 'Arrow Graph Down Right' ),
        array( 'ion-arrow-graph-up-left' => 'Arrow Graph Up Left' ),
        array( 'ion-arrow-graph-up-right' => 'Arrow Graph Up Right' ),
        array( 'ion-arrow-left-a' => 'Arrow Left A' ),
        array( 'ion-arrow-left-b' => 'Arrow Left B' ),
        array( 'ion-arrow-left-c' => 'Arrow Left C' ),
        array( 'ion-arrow-move' => 'Arrow Move' ),
        array( 'ion-arrow-resize' => 'Arrow Resize' ),
        array( 'ion-arrow-return-left' => 'Arrow Return Left' ),
        array( 'ion-arrow-return-right' => 'Arrow Return Right' ),
        array( 'ion-arrow-right-a' => 'Arrow Right A' ),
        array( 'ion-arrow-right-b' => 'Arrow Right B' ),
        array( 'ion-arrow-right-c' => 'Arrow Right C' ),
        array( 'ion-arrow-shrink' => 'Arrow Shrink' ),
        array( 'ion-arrow-swap' => 'Arrow Swap' ),
        array( 'ion-arrow-up-a' => 'Arrow Up A' ),
        array( 'ion-arrow-up-b' => 'Arrow Up B' ),
        array( 'ion-arrow-up-c' => 'Arrow Up C' ),
        array( 'ion-asterisk' => 'Asterisk' ),
        array( 'ion-at' => 'At' ),
        array( 'ion-backspace' => 'Backspace' ),
        array( 'ion-backspace-outline' => 'Backspace Outline' ),
        array( 'ion-bag' => 'Bag' ),
        array( 'ion-battery-charging' => 'Battery Charging' ),
        array( 'ion-battery-empty' => 'Battery Empty' ),
        array( 'ion-battery-full' => 'Battery Full' ),
        array( 'ion-battery-half' => 'Battery Half' ),
        array( 'ion-battery-low' => 'Battery Low' ),
        array( 'ion-beaker' => 'Beaker' ),
        array( 'ion-beer' => 'Beer' ),
        array( 'ion-bluetooth' => 'Bluetooth' ),
        array( 'ion-bonfire' => 'Bonfire' ),
        array( 'ion-bookmark' => 'Bookmark' ),
        array( 'ion-bowtie' => 'Bowtie' ),
        array( 'ion-briefcase' => 'Briefcase' ),
        array( 'ion-bug' => 'Bug' ),
        array( 'ion-calculator' => 'Calculator' ),
        array( 'ion-calendar' => 'Calendar' ),
        array( 'ion-camera' => 'Camera' ),
        array( 'ion-card' => 'Card' ),
        array( 'ion-cash' => 'Cash' ),
        array( 'ion-chatbox' => 'Chatbox' ),
        array( 'ion-chatbox-working' => 'Chatbox Working' ),
        array( 'ion-chatboxes' => 'Chatboxes' ),
        array( 'ion-chatbubble' => 'Chatbubble' ),
        array( 'ion-chatbubble-working' => 'Chatbubble Working' ),
        array( 'ion-chatbubbles' => 'Chatbubbles' ),
        array( 'ion-checkmark' => 'Checkmark' ),
        array( 'ion-checkmark-circled' => 'Checkmark Circled' ),
        array( 'ion-checkmark-round' => 'Checkmark Round' ),
        array( 'ion-chevron-down' => 'Chevron Down' ),
        array( 'ion-chevron-left' => 'Chevron Left' ),
        array( 'ion-chevron-right' => 'Chevron Right' ),
        array( 'ion-chevron-up' => 'Chevron Up' ),
        array( 'ion-clipboard' => 'Clipboard' ),
        array( 'ion-clock' => 'Clock' ),
        array( 'ion-close' => 'Close' ),
        array( 'ion-close-circled' => 'Close Circled' ),
        array( 'ion-close-round' => 'Close Round' ),
        array( 'ion-closed-captioning' => 'Closed Captioning' ),
        array( 'ion-cloud' => 'Cloud' ),
        array( 'ion-code' => 'Code' ),
        array( 'ion-code-download' => 'Code Download' ),
        array( 'ion-code-working' => 'Code Working' ),
        array( 'ion-coffee' => 'Coffee' ),
        array( 'ion-compass' => 'Compass' ),
        array( 'ion-compose' => 'Compose' ),
        array( 'ion-connection-bars' => 'Connectbars' ),
        array( 'ion-contrast' => 'Contrast' ),
        array( 'ion-crop' => 'Crop' ),
        array( 'ion-cube' => 'Cube' ),
        array( 'ion-disc' => 'Disc' ),
        array( 'ion-document' => 'Document' ),
        array( 'ion-document-text' => 'Document Text' ),
        array( 'ion-drag' => 'Drag' ),
        array( 'ion-earth' => 'Earth' ),
        array( 'ion-easel' => 'Easel' ),
        array( 'ion-edit' => 'Edit' ),
        array( 'ion-egg' => 'Egg' ),
        array( 'ion-eject' => 'Eject' ),
        array( 'ion-email' => 'Email' ),
        array( 'ion-email-unread' => 'Email Unread' ),
        array( 'ion-erlenmeyer-flask' => 'Erlenmeyer Flask' ),
        array( 'ion-erlenmeyer-flask-bubbles' => 'Erlenmeyer Flask Bubbles' ),
        array( 'ion-eye' => 'Eye' ),
        array( 'ion-eye-disabled' => 'Eye Disabled' ),
        array( 'ion-female' => 'Female' ),
        array( 'ion-filing' => 'Filing' ),
        array( 'ion-film-marker' => 'Film Marker' ),
        array( 'ion-fireball' => 'Fireball' ),
        array( 'ion-flag' => 'Flag' ),
        array( 'ion-flame' => 'Flame' ),
        array( 'ion-flash' => 'Flash' ),
        array( 'ion-flash-off' => 'Flash Off' ),
        array( 'ion-folder' => 'Folder' ),
        array( 'ion-fork' => 'Fork' ),
        array( 'ion-fork-repo' => 'Fork Repo' ),
        array( 'ion-forward' => 'Forward' ),
        array( 'ion-funnel' => 'Funnel' ),
        array( 'ion-gear-a' => 'Gear A' ),
        array( 'ion-gear-b' => 'Gear B' ),
        array( 'ion-grid' => 'Grid' ),
        array( 'ion-hammer' => 'Hammer' ),
        array( 'ion-happy' => 'Happy' ),
        array( 'ion-happy-outline' => 'Happy Outline' ),
        array( 'ion-headphone' => 'Headphone' ),
        array( 'ion-heart' => 'Heart' ),
        array( 'ion-heart-broken' => 'Heart Broken' ),
        array( 'ion-help' => 'Help' ),
        array( 'ion-help-buoy' => 'Help Buoy' ),
        array( 'ion-help-circled' => 'Help Circled' ),
        array( 'ion-home' => 'Home' ),
        array( 'ion-icecream' => 'Icecream' ),
        array( 'ion-image' => 'Image' ),
        array( 'ion-images' => 'Images' ),
        array( 'ion-information' => 'Information' ),
        array( 'ion-information-circled' => 'Informatcircled' ),
        array( 'ion-ionic' => 'Ionic' ),
        array( 'ion-ios-alarm' => 'Ios Alarm' ),
        array( 'ion-ios-alarm-outline' => 'Ios Alarm Outline' ),
        array( 'ion-ios-albums' => 'Ios Albums' ),
        array( 'ion-ios-albums-outline' => 'Ios Albums Outline' ),
        array( 'ion-ios-americanfootball' => 'Ios Americanfootball' ),
        array( 'ion-ios-americanfootball-outline' => 'Ios Americanfootball Outline' ),
        array( 'ion-ios-analytics' => 'Ios Analytics' ),
        array( 'ion-ios-analytics-outline' => 'Ios Analytics Outline' ),
        array( 'ion-ios-arrow-back' => 'Ios Arrow Back' ),
        array( 'ion-ios-arrow-down' => 'Ios Arrow Down' ),
        array( 'ion-ios-arrow-forward' => 'Ios Arrow Forward' ),
        array( 'ion-ios-arrow-left' => 'Ios Arrow Left' ),
        array( 'ion-ios-arrow-right' => 'Ios Arrow Right' ),
        array( 'ion-ios-arrow-thin-down' => 'Ios Arrow Thin Down' ),
        array( 'ion-ios-arrow-thin-left' => 'Ios Arrow Thin Left' ),
        array( 'ion-ios-arrow-thin-right' => 'Ios Arrow Thin Right' ),
        array( 'ion-ios-arrow-thin-up' => 'Ios Arrow Thin Up' ),
        array( 'ion-ios-arrow-up' => 'Ios Arrow Up' ),
        array( 'ion-ios-at' => 'Ios At' ),
        array( 'ion-ios-at-outline' => 'Ios At Outline' ),
        array( 'ion-ios-barcode' => 'Ios Barcode' ),
        array( 'ion-ios-barcode-outline' => 'Ios Barcode Outline' ),
        array( 'ion-ios-baseball' => 'Ios Baseball' ),
        array( 'ion-ios-baseball-outline' => 'Ios Baseball Outline' ),
        array( 'ion-ios-basketball' => 'Ios Basketball' ),
        array( 'ion-ios-basketball-outline' => 'Ios Basketball Outline' ),
        array( 'ion-ios-bell' => 'Ios Bell' ),
        array( 'ion-ios-bell-outline' => 'Ios Bell Outline' ),
        array( 'ion-ios-body' => 'Ios Body' ),
        array( 'ion-ios-body-outline' => 'Ios Body Outline' ),
        array( 'ion-ios-bolt' => 'Ios Bolt' ),
        array( 'ion-ios-bolt-outline' => 'Ios Bolt Outline' ),
        array( 'ion-ios-book' => 'Ios Book' ),
        array( 'ion-ios-book-outline' => 'Ios Book Outline' ),
        array( 'ion-ios-bookmarks' => 'Ios Bookmarks' ),
        array( 'ion-ios-bookmarks-outline' => 'Ios Bookmarks Outline' ),
        array( 'ion-ios-box' => 'Ios Box' ),
        array( 'ion-ios-box-outline' => 'Ios Box Outline' ),
        array( 'ion-ios-briefcase' => 'Ios Briefcase' ),
        array( 'ion-ios-briefcase-outline' => 'Ios Briefcase Outline' ),
        array( 'ion-ios-browsers' => 'Ios Browsers' ),
        array( 'ion-ios-browsers-outline' => 'Ios Browsers Outline' ),
        array( 'ion-ios-calculator' => 'Ios Calculator' ),
        array( 'ion-ios-calculator-outline' => 'Ios Calculator Outline' ),
        array( 'ion-ios-calendar' => 'Ios Calendar' ),
        array( 'ion-ios-calendar-outline' => 'Ios Calendar Outline' ),
        array( 'ion-ios-camera' => 'Ios Camera' ),
        array( 'ion-ios-camera-outline' => 'Ios Camera Outline' ),
        array( 'ion-ios-cart' => 'Ios Cart' ),
        array( 'ion-ios-cart-outline' => 'Ios Cart Outline' ),
        array( 'ion-ios-chatboxes' => 'Ios Chatboxes' ),
        array( 'ion-ios-chatboxes-outline' => 'Ios Chatboxes Outline' ),
        array( 'ion-ios-chatbubble' => 'Ios Chatbubble' ),
        array( 'ion-ios-chatbubble-outline' => 'Ios Chatbubble Outline' ),
        array( 'ion-ios-checkmark' => 'Ios Checkmark' ),
        array( 'ion-ios-checkmark-empty' => 'Ios Checkmark Empty' ),
        array( 'ion-ios-checkmark-outline' => 'Ios Checkmark Outline' ),
        array( 'ion-ios-circle-filled' => 'Ios Circle Filled' ),
        array( 'ion-ios-circle-outline' => 'Ios Circle Outline' ),
        array( 'ion-ios-clock' => 'Ios Clock' ),
        array( 'ion-ios-clock-outline' => 'Ios Clock Outline' ),
        array( 'ion-ios-close' => 'Ios Close' ),
        array( 'ion-ios-close-empty' => 'Ios Close Empty' ),
        array( 'ion-ios-close-outline' => 'Ios Close Outline' ),
        array( 'ion-ios-cloud' => 'Ios Cloud' ),
        array( 'ion-ios-cloud-download' => 'Ios Cloud Download' ),
        array( 'ion-ios-cloud-download-outline' => 'Ios Cloud Download Outline' ),
        array( 'ion-ios-cloud-outline' => 'Ios Cloud Outline' ),
        array( 'ion-ios-cloud-upload' => 'Ios Cloud Upload' ),
        array( 'ion-ios-cloud-upload-outline' => 'Ios Cloud Upload Outline' ),
        array( 'ion-ios-cloudy' => 'Ios Cloudy' ),
        array( 'ion-ios-cloudy-night' => 'Ios Cloudy Night' ),
        array( 'ion-ios-cloudy-night-outline' => 'Ios Cloudy Night Outline' ),
        array( 'ion-ios-cloudy-outline' => 'Ios Cloudy Outline' ),
        array( 'ion-ios-cog' => 'Ios Cog' ),
        array( 'ion-ios-cog-outline' => 'Ios Cog Outline' ),
        array( 'ion-ios-color-filter' => 'Ios Color Filter' ),
        array( 'ion-ios-color-filter-outline' => 'Ios Color Filter Outline' ),
        array( 'ion-ios-color-wand' => 'Ios Color Wand' ),
        array( 'ion-ios-color-wand-outline' => 'Ios Color Wand Outline' ),
        array( 'ion-ios-compose' => 'Ios Compose' ),
        array( 'ion-ios-compose-outline' => 'Ios Compose Outline' ),
        array( 'ion-ios-contact' => 'Ios Contact' ),
        array( 'ion-ios-contact-outline' => 'Ios Contact Outline' ),
        array( 'ion-ios-copy' => 'Ios Copy' ),
        array( 'ion-ios-copy-outline' => 'Ios Copy Outline' ),
        array( 'ion-ios-crop' => 'Ios Crop' ),
        array( 'ion-ios-crop-strong' => 'Ios Crop Strong' ),
        array( 'ion-ios-download' => 'Ios Download' ),
        array( 'ion-ios-download-outline' => 'Ios Download Outline' ),
        array( 'ion-ios-drag' => 'Ios Drag' ),
        array( 'ion-ios-email' => 'Ios Email' ),
        array( 'ion-ios-email-outline' => 'Ios Email Outline' ),
        array( 'ion-ios-eye' => 'Ios Eye' ),
        array( 'ion-ios-eye-outline' => 'Ios Eye Outline' ),
        array( 'ion-ios-fastforward' => 'Ios Fastforward' ),
        array( 'ion-ios-fastforward-outline' => 'Ios Fastforward Outline' ),
        array( 'ion-ios-filing' => 'Ios Filing' ),
        array( 'ion-ios-filing-outline' => 'Ios Filing Outline' ),
        array( 'ion-ios-film' => 'Ios Film' ),
        array( 'ion-ios-film-outline' => 'Ios Film Outline' ),
        array( 'ion-ios-flag' => 'Ios Flag' ),
        array( 'ion-ios-flag-outline' => 'Ios Flag Outline' ),
        array( 'ion-ios-flame' => 'Ios Flame' ),
        array( 'ion-ios-flame-outline' => 'Ios Flame Outline' ),
        array( 'ion-ios-flask' => 'Ios Flask' ),
        array( 'ion-ios-flask-outline' => 'Ios Flask Outline' ),
        array( 'ion-ios-flower' => 'Ios Flower' ),
        array( 'ion-ios-flower-outline' => 'Ios Flower Outline' ),
        array( 'ion-ios-folder' => 'Ios Folder' ),
        array( 'ion-ios-folder-outline' => 'Ios Folder Outline' ),
        array( 'ion-ios-football' => 'Ios Football' ),
        array( 'ion-ios-football-outline' => 'Ios Football Outline' ),
        array( 'ion-ios-game-controller-a' => 'Ios Game Controller A' ),
        array( 'ion-ios-game-controller-a-outline' => 'Ios Game Controller A Outline' ),
        array( 'ion-ios-game-controller-b' => 'Ios Game Controller B' ),
        array( 'ion-ios-game-controller-b-outline' => 'Ios Game Controller B Outline' ),
        array( 'ion-ios-gear' => 'Ios Gear' ),
        array( 'ion-ios-gear-outline' => 'Ios Gear Outline' ),
        array( 'ion-ios-glasses' => 'Ios Glasses' ),
        array( 'ion-ios-glasses-outline' => 'Ios Glasses Outline' ),
        array( 'ion-ios-grid-view' => 'Ios Grid View' ),
        array( 'ion-ios-grid-view-outline' => 'Ios Grid View Outline' ),
        array( 'ion-ios-heart' => 'Ios Heart' ),
        array( 'ion-ios-heart-outline' => 'Ios Heart Outline' ),
        array( 'ion-ios-help' => 'Ios Help' ),
        array( 'ion-ios-help-empty' => 'Ios Help Empty' ),
        array( 'ion-ios-help-outline' => 'Ios Help Outline' ),
        array( 'ion-ios-home' => 'Ios Home' ),
        array( 'ion-ios-home-outline' => 'Ios Home Outline' ),
        array( 'ion-ios-infinite' => 'Ios Infinite' ),
        array( 'ion-ios-infinite-outline' => 'Ios Infinite Outline' ),
        array( 'ion-ios-information' => 'Ios Information' ),
        array( 'ion-ios-information-empty' => 'Ios Informatempty' ),
        array( 'ion-ios-information-outline' => 'Ios Informatoutline' ),
        array( 'ion-ios-ionic-outline' => 'Ios Ionic Outline' ),
        array( 'ion-ios-keypad' => 'Ios Keypad' ),
        array( 'ion-ios-keypad-outline' => 'Ios Keypad Outline' ),
        array( 'ion-ios-lightbulb' => 'Ios Lightbulb' ),
        array( 'ion-ios-lightbulb-outline' => 'Ios Lightbulb Outline' ),
        array( 'ion-ios-list' => 'Ios List' ),
        array( 'ion-ios-list-outline' => 'Ios List Outline' ),
        array( 'ion-ios-location' => 'Ios Location' ),
        array( 'ion-ios-location-outline' => 'Ios Locatoutline' ),
        array( 'ion-ios-locked' => 'Ios Locked' ),
        array( 'ion-ios-locked-outline' => 'Ios Locked Outline' ),
        array( 'ion-ios-loop' => 'Ios Loop' ),
        array( 'ion-ios-loop-strong' => 'Ios Loop Strong' ),
        array( 'ion-ios-medical' => 'Ios Medical' ),
        array( 'ion-ios-medical-outline' => 'Ios Medical Outline' ),
        array( 'ion-ios-medkit' => 'Ios Medkit' ),
        array( 'ion-ios-medkit-outline' => 'Ios Medkit Outline' ),
        array( 'ion-ios-mic' => 'Ios Mic' ),
        array( 'ion-ios-mic-off' => 'Ios Mic Off' ),
        array( 'ion-ios-mic-outline' => 'Ios Mic Outline' ),
        array( 'ion-ios-minus' => 'Ios Minus' ),
        array( 'ion-ios-minus-empty' => 'Ios Minus Empty' ),
        array( 'ion-ios-minus-outline' => 'Ios Minus Outline' ),
        array( 'ion-ios-monitor' => 'Ios Monitor' ),
        array( 'ion-ios-monitor-outline' => 'Ios Monitor Outline' ),
        array( 'ion-ios-moon' => 'Ios Moon' ),
        array( 'ion-ios-moon-outline' => 'Ios Moon Outline' ),
        array( 'ion-ios-more' => 'Ios More' ),
        array( 'ion-ios-more-outline' => 'Ios More Outline' ),
        array( 'ion-ios-musical-note' => 'Ios Musical Note' ),
        array( 'ion-ios-musical-notes' => 'Ios Musical Notes' ),
        array( 'ion-ios-navigate' => 'Ios Navigate' ),
        array( 'ion-ios-navigate-outline' => 'Ios Navigate Outline' ),
        array( 'ion-ios-nutrition' => 'Ios Nutrition' ),
        array( 'ion-ios-nutrition-outline' => 'Ios Nutritoutline' ),
        array( 'ion-ios-paper' => 'Ios Paper' ),
        array( 'ion-ios-paper-outline' => 'Ios Paper Outline' ),
        array( 'ion-ios-paperplane' => 'Ios Paperplane' ),
        array( 'ion-ios-paperplane-outline' => 'Ios Paperplane Outline' ),
        array( 'ion-ios-partlysunny' => 'Ios Partlysunny' ),
        array( 'ion-ios-partlysunny-outline' => 'Ios Partlysunny Outline' ),
        array( 'ion-ios-pause' => 'Ios Pause' ),
        array( 'ion-ios-pause-outline' => 'Ios Pause Outline' ),
        array( 'ion-ios-paw' => 'Ios Paw' ),
        array( 'ion-ios-paw-outline' => 'Ios Paw Outline' ),
        array( 'ion-ios-people' => 'Ios People' ),
        array( 'ion-ios-people-outline' => 'Ios People Outline' ),
        array( 'ion-ios-person' => 'Ios Person' ),
        array( 'ion-ios-person-outline' => 'Ios Person Outline' ),
        array( 'ion-ios-personadd' => 'Ios Personadd' ),
        array( 'ion-ios-personadd-outline' => 'Ios Personadd Outline' ),
        array( 'ion-ios-photos' => 'Ios Photos' ),
        array( 'ion-ios-photos-outline' => 'Ios Photos Outline' ),
        array( 'ion-ios-pie' => 'Ios Pie' ),
        array( 'ion-ios-pie-outline' => 'Ios Pie Outline' ),
        array( 'ion-ios-pint' => 'Ios Pint' ),
        array( 'ion-ios-pint-outline' => 'Ios Pint Outline' ),
        array( 'ion-ios-play' => 'Ios Play' ),
        array( 'ion-ios-play-outline' => 'Ios Play Outline' ),
        array( 'ion-ios-plus' => 'Ios Plus' ),
        array( 'ion-ios-plus-empty' => 'Ios Plus Empty' ),
        array( 'ion-ios-plus-outline' => 'Ios Plus Outline' ),
        array( 'ion-ios-pricetag' => 'Ios Pricetag' ),
        array( 'ion-ios-pricetag-outline' => 'Ios Pricetag Outline' ),
        array( 'ion-ios-pricetags' => 'Ios Pricetags' ),
        array( 'ion-ios-pricetags-outline' => 'Ios Pricetags Outline' ),
        array( 'ion-ios-printer' => 'Ios Printer' ),
        array( 'ion-ios-printer-outline' => 'Ios Printer Outline' ),
        array( 'ion-ios-pulse' => 'Ios Pulse' ),
        array( 'ion-ios-pulse-strong' => 'Ios Pulse Strong' ),
        array( 'ion-ios-rainy' => 'Ios Rainy' ),
        array( 'ion-ios-rainy-outline' => 'Ios Rainy Outline' ),
        array( 'ion-ios-recording' => 'Ios Recording' ),
        array( 'ion-ios-recording-outline' => 'Ios Recording Outline' ),
        array( 'ion-ios-redo' => 'Ios Redo' ),
        array( 'ion-ios-redo-outline' => 'Ios Redo Outline' ),
        array( 'ion-ios-refresh' => 'Ios Refresh' ),
        array( 'ion-ios-refresh-empty' => 'Ios Refresh Empty' ),
        array( 'ion-ios-refresh-outline' => 'Ios Refresh Outline' ),
        array( 'ion-ios-reload' => 'Ios Reload' ),
        array( 'ion-ios-reverse-camera' => 'Ios Reverse Camera' ),
        array( 'ion-ios-reverse-camera-outline' => 'Ios Reverse Camera Outline' ),
        array( 'ion-ios-rewind' => 'Ios Rewind' ),
        array( 'ion-ios-rewind-outline' => 'Ios Rewind Outline' ),
        array( 'ion-ios-rose' => 'Ios Rose' ),
        array( 'ion-ios-rose-outline' => 'Ios Rose Outline' ),
        array( 'ion-ios-search' => 'Ios Search' ),
        array( 'ion-ios-search-strong' => 'Ios Search Strong' ),
        array( 'ion-ios-settings' => 'Ios Settings' ),
        array( 'ion-ios-settings-strong' => 'Ios Settings Strong' ),
        array( 'ion-ios-shuffle' => 'Ios Shuffle' ),
        array( 'ion-ios-shuffle-strong' => 'Ios Shuffle Strong' ),
        array( 'ion-ios-skipbackward' => 'Ios Skipbackward' ),
        array( 'ion-ios-skipbackward-outline' => 'Ios Skipbackward Outline' ),
        array( 'ion-ios-skipforward' => 'Ios Skipforward' ),
        array( 'ion-ios-skipforward-outline' => 'Ios Skipforward Outline' ),
        array( 'ion-ios-snowy' => 'Ios Snowy' ),
        array( 'ion-ios-speedometer' => 'Ios Speedometer' ),
        array( 'ion-ios-speedometer-outline' => 'Ios Speedometer Outline' ),
        array( 'ion-ios-star' => 'Ios Star' ),
        array( 'ion-ios-star-half' => 'Ios Star Half' ),
        array( 'ion-ios-star-outline' => 'Ios Star Outline' ),
        array( 'ion-ios-stopwatch' => 'Ios Stopwatch' ),
        array( 'ion-ios-stopwatch-outline' => 'Ios Stopwatch Outline' ),
        array( 'ion-ios-sunny' => 'Ios Sunny' ),
        array( 'ion-ios-sunny-outline' => 'Ios Sunny Outline' ),
        array( 'ion-ios-telephone' => 'Ios Telephone' ),
        array( 'ion-ios-telephone-outline' => 'Ios Telephone Outline' ),
        array( 'ion-ios-tennisball' => 'Ios Tennisball' ),
        array( 'ion-ios-tennisball-outline' => 'Ios Tennisball Outline' ),
        array( 'ion-ios-thunderstorm' => 'Ios Thunderstorm' ),
        array( 'ion-ios-thunderstorm-outline' => 'Ios Thunderstorm Outline' ),
        array( 'ion-ios-time' => 'Ios Time' ),
        array( 'ion-ios-time-outline' => 'Ios Time Outline' ),
        array( 'ion-ios-timer' => 'Ios Timer' ),
        array( 'ion-ios-timer-outline' => 'Ios Timer Outline' ),
        array( 'ion-ios-toggle' => 'Ios Toggle' ),
        array( 'ion-ios-toggle-outline' => 'Ios Toggle Outline' ),
        array( 'ion-ios-trash' => 'Ios Trash' ),
        array( 'ion-ios-trash-outline' => 'Ios Trash Outline' ),
        array( 'ion-ios-undo' => 'Ios Undo' ),
        array( 'ion-ios-undo-outline' => 'Ios Undo Outline' ),
        array( 'ion-ios-unlocked' => 'Ios Unlocked' ),
        array( 'ion-ios-unlocked-outline' => 'Ios Unlocked Outline' ),
        array( 'ion-ios-upload' => 'Ios Upload' ),
        array( 'ion-ios-upload-outline' => 'Ios Upload Outline' ),
        array( 'ion-ios-videocam' => 'Ios Videocam' ),
        array( 'ion-ios-videocam-outline' => 'Ios Videocam Outline' ),
        array( 'ion-ios-volume-high' => 'Ios Volume High' ),
        array( 'ion-ios-volume-low' => 'Ios Volume Low' ),
        array( 'ion-ios-wineglass' => 'Ios Wineglass' ),
        array( 'ion-ios-wineglass-outline' => 'Ios Wineglass Outline' ),
        array( 'ion-ios-world' => 'Ios World' ),
        array( 'ion-ios-world-outline' => 'Ios World Outline' ),
        array( 'ion-ipad' => 'Ipad' ),
        array( 'ion-iphone' => 'Iphone' ),
        array( 'ion-ipod' => 'Ipod' ),
        array( 'ion-jet' => 'Jet' ),
        array( 'ion-key' => 'Key' ),
        array( 'ion-knife' => 'Knife' ),
        array( 'ion-laptop' => 'Laptop' ),
        array( 'ion-leaf' => 'Leaf' ),
        array( 'ion-levels' => 'Levels' ),
        array( 'ion-lightbulb' => 'Lightbulb' ),
        array( 'ion-link' => 'Link' ),
        array( 'ion-load-a' => 'Load A' ),
        array( 'ion-load-b' => 'Load B' ),
        array( 'ion-load-c' => 'Load C' ),
        array( 'ion-load-d' => 'Load D' ),
        array( 'ion-location' => 'Location' ),
        array( 'ion-lock-combination' => 'Lock Combination' ),
        array( 'ion-locked' => 'Locked' ),
        array( 'ion-log-in' => 'Log In' ),
        array( 'ion-log-out' => 'Log Out' ),
        array( 'ion-loop' => 'Loop' ),
        array( 'ion-magnet' => 'Magnet' ),
        array( 'ion-male' => 'Male' ),
        array( 'ion-man' => 'Man' ),
        array( 'ion-map' => 'Map' ),
        array( 'ion-medkit' => 'Medkit' ),
        array( 'ion-merge' => 'Merge' ),
        array( 'ion-mic-a' => 'Mic A' ),
        array( 'ion-mic-b' => 'Mic B' ),
        array( 'ion-mic-c' => 'Mic C' ),
        array( 'ion-minus' => 'Minus' ),
        array( 'ion-minus-circled' => 'Minus Circled' ),
        array( 'ion-minus-round' => 'Minus Round' ),
        array( 'ion-model-s' => 'Model S' ),
        array( 'ion-monitor' => 'Monitor' ),
        array( 'ion-more' => 'More' ),
        array( 'ion-mouse' => 'Mouse' ),
        array( 'ion-music-note' => 'Music Note' ),
        array( 'ion-navicon' => 'Navicon' ),
        array( 'ion-navicon-round' => 'Navicon Round' ),
        array( 'ion-navigate' => 'Navigate' ),
        array( 'ion-network' => 'Network' ),
        array( 'ion-no-smoking' => 'No Smoking' ),
        array( 'ion-nuclear' => 'Nuclear' ),
        array( 'ion-outlet' => 'Outlet' ),
        array( 'ion-paintbrush' => 'Paintbrush' ),
        array( 'ion-paintbucket' => 'Paintbucket' ),
        array( 'ion-paper-airplane' => 'Paper Airplane' ),
        array( 'ion-paperclip' => 'Paperclip' ),
        array( 'ion-pause' => 'Pause' ),
        array( 'ion-person' => 'Person' ),
        array( 'ion-person-add' => 'Person Add' ),
        array( 'ion-person-stalker' => 'Person Stalker' ),
        array( 'ion-pie-graph' => 'Pie Graph' ),
        array( 'ion-pin' => 'Pin' ),
        array( 'ion-pinpoint' => 'Pinpoint' ),
        array( 'ion-pizza' => 'Pizza' ),
        array( 'ion-plane' => 'Plane' ),
        array( 'ion-planet' => 'Planet' ),
        array( 'ion-play' => 'Play' ),
        array( 'ion-playstation' => 'Playstation' ),
        array( 'ion-plus' => 'Plus' ),
        array( 'ion-plus-circled' => 'Plus Circled' ),
        array( 'ion-plus-round' => 'Plus Round' ),
        array( 'ion-podium' => 'Podium' ),
        array( 'ion-pound' => 'Pound' ),
        array( 'ion-power' => 'Power' ),
        array( 'ion-pricetag' => 'Pricetag' ),
        array( 'ion-pricetags' => 'Pricetags' ),
        array( 'ion-printer' => 'Printer' ),
        array( 'ion-pull-request' => 'Pull Request' ),
        array( 'ion-qr-scanner' => 'Qr Scanner' ),
        array( 'ion-quote' => 'Quote' ),
        array( 'ion-radio-waves' => 'Radio Waves' ),
        array( 'ion-record' => 'Record' ),
        array( 'ion-refresh' => 'Refresh' ),
        array( 'ion-reply' => 'Reply' ),
        array( 'ion-reply-all' => 'Reply All' ),
        array( 'ion-ribbon-a' => 'Ribbon A' ),
        array( 'ion-ribbon-b' => 'Ribbon B' ),
        array( 'ion-sad' => 'Sad' ),
        array( 'ion-sad-outline' => 'Sad Outline' ),
        array( 'ion-scissors' => 'Scissors' ),
        array( 'ion-search' => 'Search' ),
        array( 'ion-settings' => 'Settings' ),
        array( 'ion-share' => 'Share' ),
        array( 'ion-shuffle' => 'Shuffle' ),
        array( 'ion-skip-backward' => 'Skip Backward' ),
        array( 'ion-skip-forward' => 'Skip Forward' ),
        array( 'ion-social-android' => 'Social Android' ),
        array( 'ion-social-android-outline' => 'Social Android Outline' ),
        array( 'ion-social-angular' => 'Social Angular' ),
        array( 'ion-social-angular-outline' => 'Social Angular Outline' ),
        array( 'ion-social-apple' => 'Social Apple' ),
        array( 'ion-social-apple-outline' => 'Social Apple Outline' ),
        array( 'ion-social-bitcoin' => 'Social Bitcoin' ),
        array( 'ion-social-bitcoin-outline' => 'Social Bitcoin Outline' ),
        array( 'ion-social-buffer' => 'Social Buffer' ),
        array( 'ion-social-buffer-outline' => 'Social Buffer Outline' ),
        array( 'ion-social-chrome' => 'Social Chrome' ),
        array( 'ion-social-chrome-outline' => 'Social Chrome Outline' ),
        array( 'ion-social-codepen' => 'Social Codepen' ),
        array( 'ion-social-codepen-outline' => 'Social Codepen Outline' ),
        array( 'ion-social-css3' => 'Social Css3' ),
        array( 'ion-social-css3-outline' => 'Social Css3 Outline' ),
        array( 'ion-social-designernews' => 'Social Designernews' ),
        array( 'ion-social-designernews-outline' => 'Social Designernews Outline' ),
        array( 'ion-social-dribbble' => 'Social Dribbble' ),
        array( 'ion-social-dribbble-outline' => 'Social Dribbble Outline' ),
        array( 'ion-social-dropbox' => 'Social Dropbox' ),
        array( 'ion-social-dropbox-outline' => 'Social Dropbox Outline' ),
        array( 'ion-social-euro' => 'Social Euro' ),
        array( 'ion-social-euro-outline' => 'Social Euro Outline' ),
        array( 'ion-social-facebook' => 'Social Facebook' ),
        array( 'ion-social-facebook-outline' => 'Social Facebook Outline' ),
        array( 'ion-social-foursquare' => 'Social Foursquare' ),
        array( 'ion-social-foursquare-outline' => 'Social Foursquare Outline' ),
        array( 'ion-social-freebsd-devil' => 'Social Freebsd Devil' ),
        array( 'ion-social-github' => 'Social Github' ),
        array( 'ion-social-github-outline' => 'Social Github Outline' ),
        array( 'ion-social-google' => 'Social Google' ),
        array( 'ion-social-google-outline' => 'Social Google Outline' ),
        array( 'ion-social-googleplus' => 'Social Googleplus' ),
        array( 'ion-social-googleplus-outline' => 'Social Googleplus Outline' ),
        array( 'ion-social-hackernews' => 'Social Hackernews' ),
        array( 'ion-social-hackernews-outline' => 'Social Hackernews Outline' ),
        array( 'ion-social-html5' => 'Social Html5' ),
        array( 'ion-social-html5-outline' => 'Social Html5 Outline' ),
        array( 'ion-social-instagram' => 'Social Instagram' ),
        array( 'ion-social-instagram-outline' => 'Social Instagram Outline' ),
        array( 'ion-social-javascript' => 'Social Javascript' ),
        array( 'ion-social-javascript-outline' => 'Social Javascript Outline' ),
        array( 'ion-social-linkedin' => 'Social Linkedin' ),
        array( 'ion-social-linkedin-outline' => 'Social Linkedin Outline' ),
        array( 'ion-social-markdown' => 'Social Markdown' ),
        array( 'ion-social-nodejs' => 'Social Nodejs' ),
        array( 'ion-social-octocat' => 'Social Octocat' ),
        array( 'ion-social-pinterest' => 'Social Pinterest' ),
        array( 'ion-social-pinterest-outline' => 'Social Pinterest Outline' ),
        array( 'ion-social-python' => 'Social Python' ),
        array( 'ion-social-reddit' => 'Social Reddit' ),
        array( 'ion-social-reddit-outline' => 'Social Reddit Outline' ),
        array( 'ion-social-rss' => 'Social Rss' ),
        array( 'ion-social-rss-outline' => 'Social Rss Outline' ),
        array( 'ion-social-sass' => 'Social Sass' ),
        array( 'ion-social-skype' => 'Social Skype' ),
        array( 'ion-social-skype-outline' => 'Social Skype Outline' ),
        array( 'ion-social-snapchat' => 'Social Snapchat' ),
        array( 'ion-social-snapchat-outline' => 'Social Snapchat Outline' ),
        array( 'ion-social-tumblr' => 'Social Tumblr' ),
        array( 'ion-social-tumblr-outline' => 'Social Tumblr Outline' ),
        array( 'ion-social-tux' => 'Social Tux' ),
        array( 'ion-social-twitch' => 'Social Twitch' ),
        array( 'ion-social-twitch-outline' => 'Social Twitch Outline' ),
        array( 'ion-social-twitter' => 'Social Twitter' ),
        array( 'ion-social-twitter-outline' => 'Social Twitter Outline' ),
        array( 'ion-social-usd' => 'Social Usd' ),
        array( 'ion-social-usd-outline' => 'Social Usd Outline' ),
        array( 'ion-social-vimeo' => 'Social Vimeo' ),
        array( 'ion-social-vimeo-outline' => 'Social Vimeo Outline' ),
        array( 'ion-social-whatsapp' => 'Social Whatsapp' ),
        array( 'ion-social-whatsapp-outline' => 'Social Whatsapp Outline' ),
        array( 'ion-social-windows' => 'Social Windows' ),
        array( 'ion-social-windows-outline' => 'Social Windows Outline' ),
        array( 'ion-social-wordpress' => 'Social Wordpress' ),
        array( 'ion-social-wordpress-outline' => 'Social Wordpress Outline' ),
        array( 'ion-social-yahoo' => 'Social Yahoo' ),
        array( 'ion-social-yahoo-outline' => 'Social Yahoo Outline' ),
        array( 'ion-social-yen' => 'Social Yen' ),
        array( 'ion-social-yen-outline' => 'Social Yen Outline' ),
        array( 'ion-social-youtube' => 'Social Youtube' ),
        array( 'ion-social-youtube-outline' => 'Social Youtube Outline' ),
        array( 'ion-soup-can' => 'Soup Can' ),
        array( 'ion-soup-can-outline' => 'Soup Can Outline' ),
        array( 'ion-speakerphone' => 'Speakerphone' ),
        array( 'ion-speedometer' => 'Speedometer' ),
        array( 'ion-spoon' => 'Spoon' ),
        array( 'ion-star' => 'Star' ),
        array( 'ion-stats-bars' => 'Stats Bars' ),
        array( 'ion-steam' => 'Steam' ),
        array( 'ion-stop' => 'Stop' ),
        array( 'ion-thermometer' => 'Thermometer' ),
        array( 'ion-thumbsdown' => 'Thumbsdown' ),
        array( 'ion-thumbsup' => 'Thumbsup' ),
        array( 'ion-toggle' => 'Toggle' ),
        array( 'ion-toggle-filled' => 'Toggle Filled' ),
        array( 'ion-transgender' => 'Transgender' ),
        array( 'ion-trash-a' => 'Trash A' ),
        array( 'ion-trash-b' => 'Trash B' ),
        array( 'ion-trophy' => 'Trophy' ),
        array( 'ion-tshirt' => 'Tshirt' ),
        array( 'ion-tshirt-outline' => 'Tshirt Outline' ),
        array( 'ion-umbrella' => 'Umbrella' ),
        array( 'ion-university' => 'University' ),
        array( 'ion-unlocked' => 'Unlocked' ),
        array( 'ion-upload' => 'Upload' ),
        array( 'ion-usb' => 'Usb' ),
        array( 'ion-videocamera' => 'Videocamera' ),
        array( 'ion-volume-high' => 'Volume High' ),
        array( 'ion-volume-low' => 'Volume Low' ),
        array( 'ion-volume-medium' => 'Volume Medium' ),
        array( 'ion-volume-mute' => 'Volume Mute' ),
        array( 'ion-wand' => 'Wand' ),
        array( 'ion-waterdrop' => 'Waterdrop' ),
        array( 'ion-wifi' => 'Wifi' ),
        array( 'ion-wineglass' => 'Wineglass' ),
        array( 'ion-woman' => 'Woman' ),
        array( 'ion-wrench' => 'Wrench' ),
        array( 'ion-xbox' => 'Xbox' ),
    );

    return array_merge( $icons, $font_ionicons );
}