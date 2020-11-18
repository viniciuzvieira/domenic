<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Add a tag to inline links
 */
function wpappninja_add_links($html) {
    return preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $html);
}

/**
 * Get gravatar url
 */
function wpappninja_get_gravatar($email) {
    if ($email != '') {
        $html = get_avatar($email, 200, get_option( 'avatar_default', 'mystery' ));
        if ($html != FALSE) {
            preg_match("/src=['|\"](.*?)['|\"]/i", $html, $matches);


            if (preg_match('#^//#', $matches[1])) {

                if (is_ssl()) {
                    $matches[1] = 'https:' . $matches[1];
                } else {
                    $matches[1] = 'http:' . $matches[1];
                }
            }
            
            // add domain name
            if (!preg_match('#^http#', $matches[1])) {
                return get_site_url() . preg_replace('/^'.preg_quote(get_site_url(), '/').'/', '', preg_replace('/&#038;/', '&', $matches[1]));
            } else {
                return preg_replace('/&#038;/', '&', $matches[1]);
            }
        }
    }
    return '';
}

/**
 * Return human readable timestamp
 */
function wpappninja_human_time($s) {

    // hide date everywhere
    if (get_wpappninja_option('remove_date', '1') == '0') {
        return "";
    }

    if (get_wpappninja_option('datetype', 'date') == 'date') {
        $date = date_i18n( get_option( 'date_format' ), current_time('timestamp') - $s);
        if (get_wpappninja_option('showdate', '1') == '1') {
            $date .= ' @ ' . date_i18n( get_option( 'time_format' ), current_time('timestamp') - $s);
        }
        return $date;
    }

    $m = round($s / 60);
    $h = round($s / 3600);
    $d = round($s / 86400);
    $month = round($s / 2592000);
    $annee = round($s / 31104000);
    if ($m > 1) {
        if ($h > 1) {
            if ($d > 1) {
                if ($month > 1) {
                    if ($annee > 0) {
                        if ($annee > 1){
                            return sprintf(__('%s years ago', 'wpappninja'), $annee);
                        } else {
                            return sprintf(__('%s year ago', 'wpappninja'), $annee);
                        }
                    } else {
                        if ($month > 1) {
                            return sprintf(__('%s months ago', 'wpappninja'), $month);
                        } else {
                            return sprintf(__('%s month ago', 'wpappninja'), $month);
                        }
                    }
                } else {
                    if ($d > 1){
                        return sprintf(__('%s days ago', 'wpappninja'), $d);
                    } else {
                        return sprintf(__('%s day ago', 'wpappninja'), $d);
                    }
                }
            } else {
                if ($h > 1){
                    return sprintf(__('%s hours ago', 'wpappninja'), $h);
                } else {
                    return sprintf(__('%s hour ago', 'wpappninja'), $month);
                }
            }
        } else {
            if ($m > 1){
                return sprintf(__('%s minutes ago', 'wpappninja'), $m);
            } else {
                return sprintf(__('%s minute ago', 'wpappninja'), $m);
            }
        }
    } else {
        return sprintf(__('%s seconds ago', 'wpappninja'), $s);
    }
}

/**
 * Fix a bug with pre tags.
 *
 * @since 3.6.7
 */
function wpappninja_pre_tags($content) {
    return preg_replace_callback('/<pre.*?>(.*?)<\/pre>/imsu', function($matches){
            $pre = nl2br(htmlspecialchars($matches[1]));
            return '<blockquote>' . $pre . '</blockquote>';
        }, $content);
}

/**
 * Order a get_posts array by post type.
 *
 * @since 4.3.1
 */
function wpappninja_order_by_post_type($a, $b) {
    return strcmp($a->post_type, $b->post_type);
}

/**
 * Get taxonomy.
 *
 * @since 4.3.3
 */
function wpappninja_get_all_taxonomy() {
    $taxonomy = array();
    $_taxonomy = get_taxonomies(array('public'=>true), 'objects');
    foreach($_taxonomy as $p => $k) {
        $taxonomy[] = $k->name;
    }

    return $taxonomy;
}

/**
 * Cut a sentence on dot or space.
 *
 * @since 5.2.8
 */
function wpappninja_nice_cut($string, $max) {
    
    if (strlen($string) <= $max) {
        return $string;
    }
    
    $max -= 3;
    
    $string = substr($string, 0, $max);

    $i = strrpos($string, ' ');
    if ($i !== false) {
        $string = substr($string, 0, $i);
    }
    
    return $string . "...";
}

/**
 * Get blog name.
 *
 * @since 6.4
 */
function wpappninja_get_appname($tiny = false) {

    $app_data = get_wpappninja_option('app');
    $app_name = isset($app_data['name']) ? $app_data['name'] : get_bloginfo('name');

    /*if ($tiny) {
        $app_name = preg_replace(
          array( '#[\\s-]+#', '#[^A-Za-z0-9\. -]+#' ),
          array( '', ' '),
          $app_name);
    }*/

    return $app_name;

}

/**
 * Convert to url.
 *
 * @since 6.4
 */
function wpappninja_convertid_to_url($homepage_wpapp) {

    if (preg_match('/^(\?|\/|http|javascript:|mailto:|geo:|tel:|sms:)/', $homepage_wpapp)) {
        return $homepage_wpapp;
    }
    
    if (preg_match('#^cat_#', $homepage_wpapp)) {

        $homepage_wpapp = preg_replace('#^cat_#', '', $homepage_wpapp);
        $taxonomy = wpappninja_get_all_taxonomy();

        foreach ($taxonomy as $tax) {
            $obj = get_term_by('id', $homepage_wpapp, $tax);
            if (is_object($obj)) {

                $homepage_wpapp = get_term_link($obj);
                break;
            }
        }
    } else {

        if (get_permalink(intval($homepage_wpapp))) {
            $homepage_wpapp = get_permalink(intval($homepage_wpapp));
        }
    }

    if (!preg_match('#^http#', $homepage_wpapp)) {
        $homepage_wpapp = wpappninja_get_home();
    }

    return $homepage_wpapp;
}

/**
 * Translate strings.
 *
 * @since 6.4
 */
function wpappninja_translate($s) {

    if (get_wpappninja_option('speed_trad') != 'manual') {
        return wpappninja_cache_friendly($s);
    }

    $trads = get_wpappninja_option('trad', array());
    $lang = wpappninja_get_lang();

    if (isset($trads[md5($s)][$lang])) {
        return wpappninja_cache_friendly($trads[md5($s)][$lang]);
    }

    foreach($trads as $trad) {
      foreach ($trad as $t) {
        if ($t == $s && $trad[$lang] != "") {
          return $trad[$lang];
        }
      }
    }

    // no match, return homepage
    $homepage_wpapp = get_wpappninja_option('pageashome_speed', "");
    if (isset($trads[md5($homepage_wpapp)][$lang])) {
        return wpappninja_cache_friendly($trads[md5($homepage_wpapp)][$lang]);
    }
    
    return wpappninja_cache_friendly($s);
}

/**
 * Get nh pos in string.
 *
 * @since 6.4.1
 */
function wpappninja_get_pos($search, $string, $offset)
{
    /*** explode the string ***/
    $arr = explode($search, $string);
    /*** check the search is not out of bounds ***/
    switch( $offset )
    {
        case $offset == 0:
        return false;
        break;
    
        case $offset > max(array_keys($arr)):
        return false;
        break;

        default:
        return strlen(implode($search, array_slice($arr, 0, $offset)));
    }
}

/**
 * Show push notification
 *
 * @since 8.1.2
 */
function wpappninja_show_push() {

    global $wpdb;
    $id = $_GET['wpappninja_read_push'];

    $html = '<div class="post main-post">
        <div class="wpapp-post-content" data-instant>';

        if ($id == "welcome") {
                $html .= '<h2>' . get_wpappninja_option('welcome_titre_speed') . '</h2>';
                $html .= '<h3>' . get_wpappninja_option('welcome_speed') . '</h3>';
                $html .= stripslashes(get_wpappninja_option('bienvenue_speed'));
        } else {

        $query = $wpdb->get_results($wpdb->prepare("SELECT `message`, `id_post`, titre, image FROM {$wpdb->prefix}wpappninja_push WHERE `sended` = %s AND id = %s LIMIT 1", '1', $id));
        foreach($query as $obj) {

            $permalink = $obj->id_post;
            if (!preg_match('#^http#', $permalink)) {
                $permalink = get_permalink($obj->id_post);
            }
            /*if (!preg_match('#^http#', $permalink)) {
                $permalink = wpappninja_get_home();
            }*/

            if (strlen($obj->image) > 2) {
                $html .= '<img src="' . $obj->image . '" class="hero" alt="" />';
            }

            $html .= '<h2>' . stripslashes($obj->titre) . '</h2>';
            $html .= stripslashes($obj->message);

            $read_link = "";
            if (preg_match('#^http#', $permalink)) {
                $read_link = '<br/><br/><a class="wpappninja_push_button button" href="' . $permalink . '">' . __('Continue', 'wpappninja') . '</a>';
            }
            $html .= $read_link;
        }

        }

        $html .= '</div>
    </div>';

    return $html;
}
