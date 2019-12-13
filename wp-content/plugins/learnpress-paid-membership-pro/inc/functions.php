<?php
/**
 * Custom functions
 */
defined( 'ABSPATH' ) || exit();
define( 'LP_PMPRO_TEMPLATE', learn_press_template_path() . '/addons/paid-membership-pro/' );

/**
 * Get template file for addon
 *
 * @param	  $name
 * @param null $args
 */
function learn_press_pmpro_get_template( $name, $args = null ) {
	if ( file_exists( learn_press_locate_template( $name, 'learnpress-paid-membership-pro', LP_PMPRO_TEMPLATE ) ) ) {
		learn_press_get_template( $name, $args, 'learnpress-paid-membership-pro/', get_template_directory() . '/' . LP_PMPRO_TEMPLATE );
	} else {
		learn_press_get_template( $name, $args, LP_PMPRO_TEMPLATE, LP_ADDON_PMPRO_PATH . '/templates/' );
	}
}

function learn_press_pmpro_locate_template( $name ) {
	// Look in folder learnpress-paid-membership-pro in the theme first
	$file = learn_press_locate_template( $name, 'learnpress-paid-membership-pro', learn_press_template_path() . '/addons/paid-membership-pro/' );
	// If template does not exists then look in learnpress/addons/paid-membership-pro in the theme
	if ( ! file_exists( $file ) ) {
	$file = learn_press_locate_template( $name, learn_press_template_path() . '/addons/paid-membership-pro/', LP_ADDON_PMPRO_PATH . '/templates/' );
	}
	return $file;
}

function lp_pmpro_query_course_by_level( $level_id ) {
	global $learn_press_pmpro_cache;

	$level_id = intval( $level_id );

	if ( ! empty( $learn_press_pmpro_cache[ 'query_level_' . $level_id ] ) ) {
		return $learn_press_pmpro_cache[ 'query_level_' . $level_id ];
	}
	$post_type											 = LP_COURSE_CPT;
	$args												  = array(
		'post_type'	  => array( $post_type ),
		'post_status'	=> array( 'publish' ),
		'posts_per_page' => - 1,
		'meta_query'	 => array(
			array(
				'key'   => '_lp_pmpro_levels',
				'value' => $level_id,
			),
		),
	);
	$query												 = new WP_Query( $args );
	$learn_press_pmpro_cache[ 'query_level_' . $level_id ] = $query;

	return $query;
}

function lp_pmpro_get_all_levels() {
	if ( false === ( $pmpro_levels = wp_cache_get( 'pmp-levels', 'learn-press' ) ) ) {
		$pmpro_levels = pmpro_getAllLevels( false, true );
		wp_cache_set( 'pmp-levels', $pmpro_levels, 'learn-press' );
	}
	$pmpro_levels = apply_filters( 'lp_pmpro_levels_array', $pmpro_levels );

	return $pmpro_levels;
}

function lp_pmpro_get_all_levels_id( $pmpro_levels ) {
	if ( empty( $pmpro_levels ) ) {
		return array();
	}
	$return = array();
	foreach ( $pmpro_levels as $level ) {
		$return[] = $level->id;
	}

	return $return;
}

function lp_pmpro_list_courses( $levels = null ) {

	global $current_user;
	$list_courses = array();

	if ( ! $levels ) {
		$levels = lp_pmpro_get_all_levels();
	}
	foreach ( $levels as $index => $level ) {
		$the_query = lp_pmpro_query_course_by_level( $level->id );
		if ( ! empty( $the_query->posts ) ) {
			foreach ( $the_query->posts as $key => $course ) {
				$course_id						  = $course->ID;
				$list_courses[ $course_id ]['id']   = $course_id;
				$list_courses[ $course_id ]['link'] = '<a href="' . get_the_permalink( $course_id ) . '" >' . get_the_title( $course_id ) . '</a>';
				if ( empty( $list_courses[ $course_id ]['level'] ) ) {
					$list_courses[ $course_id ]['level'] = array();
				}
				if ( ! in_array( $level->id, $list_courses[ $course_id ]['level'] ) ) {
					$list_courses[ $course_id ]['level'][] = $level->id;
				}
			}
		}

	}
	$list_courses = apply_filters( 'learn_press_pmpro_list_courses', $list_courses, $current_user, $levels );

	return $list_courses;
}

function learn_press_pmpro_check_require_template() {

	global $current_user, $post;
	$user_id		= get_current_user_id();
	$user		   = learn_press_get_user( $user_id, true );
	$levels_page_id = pmpro_getOption( "levels_page_id" );
	$all_levels	 = lp_pmpro_get_all_levels();
	//$levels	   = lp_pmpro_get_all_levels();
	$all_levels_id = lp_pmpro_get_all_levels_id( $all_levels );
	$course		= learn_press_get_course( $post->ID );
	if( !$course ) {
		return false;
	}
	$list_courses  = lp_pmpro_list_courses( $all_levels );

	/**
	 * Return if user have purchased this course
	 */
	if ( $user->has_purchased_course( $post->ID ) ) {
		return false;
	}

	/**
	 * Return if page is not include any levels membership
	 */
	if ( empty( $levels_page_id ) ) {
		return false;
	}

	/**
	 * Check if this course not assign anyone membership level
	 */
	if ( empty( $list_courses[ $course->get_id() ] ) ) {
		return false;
	}

	/**
	 * Return if current user is buy this level membership of current page
	 */
	if ( $current_user->membership_levels ) {

		// List memberships level is accessed into this course
		$list_memberships_of_course = lp_pmpro_list_courses( $current_user->membership_levels );

		foreach ( $current_user->membership_levels as $level ) {
			if ( in_array( $level->ID, $list_memberships_of_course ) ) {
				return false;
			}
		}
	}

	/**
	 * Return if not exists level membership
	 */
	if ( empty( $all_levels ) ) {
		return false;
	}

	return array(
		'current_user'   => $current_user,
		'post'		   => $post,
		'user_id'		=> $user_id,
		'user'		   => $user,
		'levels_page_id' => $levels_page_id,
		'all_levels'	 => $all_levels,
		'all_levels_id'  => $all_levels_id,
		'course'		 => $course,
		'levels'		 => $all_levels,
		'list_courses'   => $list_courses
	);

}


/**
 * get learn press order from current user
 * @global type $wpdb
 *
 * @param type  $level_id
 * @param type  $user_id
 *
 * @return lp_order
 */
function learn_press_pmpro_get_order_ids_by_membership_level( $level_id = null, $user_id = null ) {
	global $wpdb;
	if ( ! $user_id ) {
		$user_id = learn_press_get_current_user_id();
	}
	if ( ! $level_id ) {
		$user_level = learn_press_get_membership_level_for_user( $user_id );
		$level_id   = $user_level->id;
	}

	$sql = 'SELECT 
				`po`.`id`
				, po.checkout_id
				,`pm`.`post_id`
			FROM
				`lp`.`' . $wpdb->prefix . 'pmpro_membership_orders` AS `po`
					LEFT JOIN
				`' . $wpdb->prefix . 'postmeta` AS `pm` ON `pm`.`meta_key` = "_pmpro_membership_order_id"
					AND `pm`.`meta_value` = `po`.`id`
			WHERE
				`po`.`user_id` = 3
					AND `po`.`membership_id` = 1
					AND `po`.`status` = "success"
			ORDER BY `timestamp` DESC
			LIMIT 1';
	$row = $wpdb->get_row( $sql );

	return $row;
}

/**
 * get Course by Memberships level id
 * @global type $wpdb
 *
 * @param int   $level_id
 *
 * @return array object
 */
function lp_pmpro_get_course_by_level_id( $level_id ) {
	global $wpdb;
	$sql  = $wpdb->prepare( "SELECT 
						p.ID, CONCAT(p.ID,' - ', p.post_title) AS `title`
					FROM
						{$wpdb->posts} AS p
							INNER JOIN
						{$wpdb->postmeta} AS pm ON (p.ID = pm.post_id)
					WHERE
						1 = 1
							AND ((pm.meta_key = '_lp_pmpro_levels'
							AND pm.meta_value = %s))
							AND p.post_type = 'lp_course'
							AND ((p.post_status = 'publish'))
					GROUP BY p.ID
					ORDER BY p.post_date DESC", $level_id );
	$rows = $wpdb->get_results( $sql, OBJECT_K );

	return $rows;
}

/**
 * check user is able or not albe enroll course
 *
 * @param type $course_id
 * @param type $user
 *
 * @return boolean
 */
function learn_press_pmpro_user_can_enroll_course( $course_id, $user = null ) {
	if ( ! $user ) {
		$user = learn_press_get_current_user();
	}
	if ( ! $user || ! $user->get_id() ) {
		return false;
	}

	$course_levels = learn_press_pmpro_get_course_levels($course_id);
	$user_level	= learn_press_get_membership_level_for_user( $user->get_id() );

	if ( ! $course_levels || empty( $course_levels ) || ! $user_level || ! isset( $user_level->id ) || ! in_array( $user_level->id, $course_levels ) ) {
		return false;
	}
	$has_membership_level = learn_press_pmpro_hasMembershipLevel( array( $user_level->id ), $user->get_id() );
	if ( ! $has_membership_level ) {
		return false;
	}

	return true;
}


/**
 * Wrap function learn_press_get_membership_level_for_user from pmpro.
 * Main purpose for caching data.
 *
 * @since 3.0.0
 *
 * @param int $user_id
 *
 * @return bool|mixed
 */
function learn_press_get_membership_level_for_user( $user_id ) {

	if ( false === ( $level = wp_cache_get( 'user-' . $user_id, 'pmpro-user-level' ) ) ) {
		$level = pmpro_getMembershipLevelForUser( $user_id );
		wp_cache_set( 'user-' . $user_id, $level, 'pmpro-user-level' );
	}

	return $level;
}

/**
 * check user has membership level
 * @param mixed $level_id
 * @param int $user_id
 * @return boolean
 */
function learn_press_pmpro_hasMembershipLevel( $level_id=null, $user_id =null ) {

	if( !$user_id ) {
		$user_id = get_current_user_id();
	}

	$has_level = pmpro_hasMembershipLevel( $level_id, $user_id );
	// start: make compatible with addon Pay By Check of Paid Memberships Pro plugin =
	if( $has_level && function_exists('pmpropbc_isMemberPending') ) {
		if( is_array( $level_id ) && !empty( $level_id ) ) {
			foreach ( $level_id as $lv_id ) {
				if( !pmpropbc_isMemberPending( $user_id, $lv_id ) ) {
					$has_level = true;
					break;
				}else{
					$has_level = false;
				}
			}
		} else {
			if( pmpropbc_isMemberPending( $user_id, $level_id ) ) {
				$has_level = false;
			}
		}
	}
	// end

	// make sure that level is not expired
	$levels	= pmpro_getMembershipLevelsForUser();

	if( $has_level && $levels && !empty($levels) ) {
		$level_ids = is_array($level_id)? $level_id : array($level_id);
		$expired = true;
		foreach ( $levels as $level ) {
			if( in_array( $level->id, $level_ids ) ) {
				$res = lp_pmpro_isLevelExpiring( $level );
				if( !$res ) {
					$expired = false;
					break;
				}
			}
		}
		$has_level = !$expired;
	}

	return $has_level;
}

/**
 * rewrite function pmpro_isLevelExpiring
 * @param unknown $level
 * @return boolean
 */
function lp_pmpro_isLevelExpiring( $level ) {
	$now = current_time( 'timestamp' );
	if ( ! empty( $level ) ) {
		if ( ! empty( $level->enddate ) && intval( $level->enddate ) > $now || empty( $level->enddate ) ) {
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

function learn_press_pmpro_getLevelCost( $level, $user_id ){
	
	$membership_values = pmpro_getMembershipLevelForUser($user_id);
	$cost = 0;
	if(!empty($membership_values))
	{
		$membership_values->original_initial_payment = $membership_values->initial_payment;
		$membership_values->initial_payment = $membership_values->billing_amount;
	}

	if(empty($membership_values) || pmpro_isLevelFree($membership_values))
	{
		if(!empty($membership_values->original_initial_payment) && $membership_values->original_initial_payment > 0) {
			$cost = $membership_values->original_initial_payment;
		} else {
			$cost = 0;
		}
	} else {
		$cost = pmpro_getLevelCost( $level, false, true );
		$match = array();
		preg_match( '/'.$level->initial_payment.'/i', $cost, $match );
		if( $match && is_array($match) && !empty( $match ) ) {
			$cost = $level->initial_payment;
		}
	}
	return $cost;
}

/**
 * Get levels that course is connected
 * @param unknown $course_id
 * @return array|array
 */
function learn_press_pmpro_get_course_levels( $course_id ) {
	$course_levels = get_post_meta( $course_id, '_lp_pmpro_levels' );
	if(!$course_levels || empty($course_levels)){
		return array();
	}
	$all_levels = pmpro_getAllLevels();
	if(!$all_levels || empty($all_levels)){
		return array();
	}
	$all_levels_id = array_keys($all_levels);
	$course_levels = array_intersect($course_levels, $all_levels_id);
	return $course_levels;
}

add_filter('learn_press_course_price_html_free', 'lp_pmpro_course_price_html_free_filter_callback', 10, 2 );
function lp_pmpro_course_price_html_free_filter_callback( $text, $course ){
    $leves = learn_press_pmpro_get_course_levels($course->get_id());
    if($leves && !empty($leves)){
        return '';
    }

    return $text;
}

add_filter('learn_press_course_price_html', 'lp_pmpro_course_price_html_filter_callback', 10, 2 );
function lp_pmpro_course_price_html_filter_callback( $text, $course ){
    $leves = learn_press_pmpro_get_course_levels($course->get_id());
    $buy_through_membership	  = LP()->settings->get( 'buy_through_membership' ) === 'yes';
    if($leves && !empty($leves) && $buy_through_membership ){
        return '';
    }
    return $text;
}

