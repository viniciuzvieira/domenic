<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * A wrapper to easily get WPMobile.App option
 *
 * @since 1.0
 *
 * @param  string $option  The option name
 * @param  bool   $default The default value of option
 * @return mixed  The option value
 */
function get_wpappninja_option( $option, $default = false ) {
  /**
   * Pre-filter any WPMobile.App option before read
   *
   * @since 1.0
   *
   * @param variant $default The default value
  */
  $value = apply_filters( 'pre_get_wpappninja_option_' . $option, NULL, $default );

  if ( NULL !== $value ) {
    return $value;
  }

  $options = get_option( WPAPPNINJA_SLUG );
  $value   = isset( $options[ $option ] ) && $options[ $option ] !== '' ? $options[ $option ] : $default;

  if ($option == 'app') {
    $value = stripslashes_deep($value);

    if (isset($value['splashscreen']) && $value['splashscreen'] == "") {
      $value['splashscreen'] = WPAPPNINJA_ASSETS_IMG_URL . 'os/empty.png';
    }

    if (isset($value['logo']) && $value['logo'] == "") {
      $value['logo'] = WPAPPNINJA_ASSETS_IMG_URL . 'os/empty.png';
    }

    if (isset($value['name']) && $value['name'] == "") {
      $value['name'] = get_bloginfo('name');
    }

    if (isset($value['theme']['primary'])) {
      $value['theme']['primary'] = wpappninja_check_hex(get_wpappninja_option('css_dc2e1703b492b0ad78d631130af23035', '#333333'));
    }

    if (isset($value['theme']['accent'])) {
      $value['theme']['accent'] = wpappninja_check_hex($value['theme']['accent'], true);
    }
  }
  
  /**
   * Filter any WPMobile.App option after read
   *
   * @since 1.0
   *
   * @param variant $default The default value
  */
  return apply_filters( 'get_wpappninja_option_' . $option, $value, $default );
}


add_filter( 'get_wpappninja_option_wpappninja_main_theme', 'wpappninja_main_theme' );
function wpappninja_main_theme($value) {

  if (isset($_GET['wpappninja_my_theme'])) {

    $themes = wp_get_themes(array('allowed' => 'site'));
    foreach ($themes as $theme) {

      if ($theme->Name == $_GET['wpappninja_my_theme']) {
        return $_GET['wpappninja_my_theme'];
      }
    }
  }

  return $value;
}

add_filter( 'get_wpappninja_option_menu_reload_speed', 'wpappninja_correct_icons' );
add_filter( 'get_wpappninja_option_pageashomeicon_speed', 'wpappninja_correct_icons' );
function wpappninja_correct_icons($value) {

  //$check = wpappninja_get_icons();

  if (is_array($value)) {

    //$i = min(array_keys($value));
    foreach ($value as $i => $item) {

      $value[$i]['name'] = wp_strip_all_tags($item['name']);

      if (!isset($item['icon'])) {
        $value[$i]['icon'] = "";
      }

      /*if (!in_array($item['icon'], $check)) {
        $value[$i]['icon'] = wpappninja_auto_select_icon($item['name'] . ' ' . wpappninja_get_http_link($item));
      }*/

      if (get_wpappninja_option('speed') != '1' && $value[$i]['icon'] == 'chevron_right') {
         $value[$i]['icon'] = 'arrow';
      }

      if (is_wpappninja() && isset($value[$i]['role']) && $value[$i]['role'] != "") {

        global $wp_roles;
        $roles = wp_get_current_user()->roles;
        $roles_name = $wp_roles->get_names();

        $getout = true;
        foreach ($roles as $v) {
          if ($roles_name[$v] == $value[$i]['role']) {
            $getout = false;
          }
        }

        if ("anonymous" == $value[$i]['role'] && !is_user_logged_in()) {
          $getout = false;
        }

        if ("notanonymous" == $value[$i]['role'] && is_user_logged_in()) {
          $getout = false;
        }

        if ($getout && !is_admin()) {
          unset($value[$i]);
        }

      }

      //$i++;
    }
  } else {

    /*if (!in_array($value, $check)) {
      $value = wpappninja_auto_select_icon(get_wpappninja_option('pageashome_speed') . ' ' . get_wpappninja_option('pageashometitle_speed'));

          if (get_wpappninja_option('speed') != '1' && $value == 'chevron_right') {
            $value = 'arrow';
          }
    }*/
  }

  return $value;
}
