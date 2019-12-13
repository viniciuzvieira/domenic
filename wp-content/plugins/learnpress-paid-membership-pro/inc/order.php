<?php

class LP_Pmpro_Order {

	public static $_payment_method 		= 'paid-memberships-pro';
	public static $_payment_method_title 	= '';
	public static $_key_lp_pmpro_level 	= '_lp_pmpro_level'; // meta key for lp_order
	public static $_key_lp_pmpro_levels 	= '_lp_pmpro_levels'; // meta key for lp_course
	public static $_loaded 			= false;

	public $redirect 	= false;
	public $user_orders 	= array();

	public function __construct () {
		add_action( 'template_include', array( $this, 'template_include' ), 15 );
		add_filter('learn_press_display_payment_method_title', array( $this, 'display_payment_method_title' ), 10,2 );
	}

	public function init( ) {
		if ( !is_admin() ) {
			$user = learn_press_get_current_user();
			if($user){
				$this->auto_update_lp_orders();
			}
		}
	}
	
	
	public function template_include( $template ) {
		if ( !is_admin() ) {
			$user = learn_press_get_current_user();
			if( $user ) {
				$this->auto_update_lp_orders();
			}
		}
		return $template;
	}


	public function display_payment_method_title( $title, $payment_method ) {
		if( self::$_payment_method == $payment_method ) {
			return $this->get_payment_method_title();
		}
		return $title;
	}


	public function get_order_level( $lp_order_id ){
		$level_id = get_post_meta( $lp_order_id, self::$_key_lp_pmpro_level, true );
		if(!$level_id || empty($level_id)){
			return false;
		}
		$all_levels = pmpro_getAllLevels();
		if(!$all_levels || empty($all_levels)){
			return array();
		}
		$all_levels_id = array_keys($all_levels);
		if( in_array( $level_id, $all_levels_id ) ) {
			return $level_id;
		}
		return false;
	}


	public function get_payment_method_title(){
		if( !self::$_payment_method_title ){
			self::$_payment_method_title = __( 'Pay via <strong>Paid Memberships Pro</strong>', 'learnpress-paid-membership-pro' );
		}
		return self::$_payment_method_title;
	}


	/**
	 * Get all user's order created for memberships level
	 * @param int $user_id
	 * @param int $level_id
	 * @return array|mixed|object|NULL
	 */
	public function get_user_orders( $user_id=null, $level_id=null ) {
		global $wpdb;
		if ( !$user_id ) {
			$user_id = learn_press_get_current_user_id();
		}

		if( !$this->user_orders ) {
			$args_meta_query = array(
				array(
					'key'   => '_user_id',
					'value' => $user_id,
				)
			);

			$args = array(
				'post_type'      => LP_ORDER_CPT,
				'post_status'    => 'any',
				'meta_key' => self::$_key_lp_pmpro_level,
				'meta_query'     => $args_meta_query
			);

			if( $level_id ){
				$args['meta_value'] = $level_id;
			}

			$orders = get_posts( $args );

			if ( !empty( $orders ) ) {
				foreach ( $orders as $order ) {
					$order_level = get_post_meta( $order->ID, '_lp_pmpro_level', true );
					if (! isset($this->user_orders[$order_level])) {
						$this->user_orders[$order_level] = array(
							$order->ID => $order
						);
					} elseif (! isset($this->user_orders[$order_level][$order->ID])) {
						$this->user_orders[$order_level][$order->ID] = $order;
					}
				}
			}
		}
		return $this->user_orders;
	}


	/**
	 * Auto create or update lp_order
	 */
	public function auto_update_lp_orders( ) {
		if( !is_user_logged_in() || is_admin() ) {
			return;
		}

		if( !( learn_press_is_course() || learn_press_is_profile() || learn_press_is_course_archive() ) ){
			return;
		}

		$user = learn_press_get_current_user();
		if( !$user || !$user->get_id() ) {
			return;
		}
		$this->redirect = false;
		$user_id = $user->get_id();

		// get all memberships levels of user
		$user_levels = pmpro_getMembershipLevelsForUser( $user_id );

		// get all order of user
		$user_orders = $this->get_user_orders( $user_id ); 
		$user_order_level_ids = array_keys( $user_orders );

		# cancel order has expired level
		if( !empty( $user_orders ) ) {
			foreach ( $user_orders as $user_level_id => $user_level_orders ) {
				// make sure that user realy have membership level
				$hasMembershipLevel  = $this->hasMembershipLevel( $user_level_id, $user_id );
				if( !$hasMembershipLevel ) {
					foreach ( $user_level_orders as $lp_order_id => $user_level_order ) {
						if( $this->set_order_cancelled( $lp_order_id,'' ) ){
							$this->redirect = true;
						}
					}
				}
			}
		}

		if( !$user_levels || empty( $user_levels ) ) {
			return;
		}

		# create user order
		foreach ( $user_levels as $user_level ) {
			$user_level_id       = $user_level->ID;
			$hasMembershipLevel  = $this->hasMembershipLevel( $user_level_id, $user_id );
			if( $hasMembershipLevel ) {
				$lp_orders_data = $this->get_user_order( $user_id, $user_level_id);
				if( ! $lp_orders_data ) {
					# create new lp_order
					$lp_order_id = $this->create_lp_order( $user_id, $user_level_id );
					if( $lp_order_id ) {
						$this->redirect = true;
					}
					
				} elseif ( !empty( $lp_orders_data ) ) {
					# update lp_order
					foreach ( $lp_orders_data as $lp_order_id => $lp_order_data ) {
						if( $this->update_lp_order( $lp_order_id ) ) {
							$this->redirect = true;
						}
						if( $this->set_order_completed( $lp_order_id, 'auto update' ) ) {
							$this->redirect = true;
						}
					}
				}
			}
		}

		# reload current page if new order is created or order have any change
		
		if( $this->redirect ) {
			$this->redirect  = false;
			$current_url = learn_press_get_current_url();
			wp_redirect( $current_url );
		}
	}


	private function hasMembershipLevel( $level_id, $user_id ) {
		return learn_press_pmpro_hasMembershipLevel( $level_id, $user_id );
	}


	private function set_order_completed( $order_id, $order_note='' ) {
		$order = learn_press_get_order( $order_id );
		if( 'completed' !== $order->get_status() ) {
			$order->set_status( 'completed', $order_note );
			$order->save();
			return true;
		}
		return false;
	}


	private function set_order_cancelled( $order_id, $order_note='' ) {
		$order = learn_press_get_order( $order_id );
		if( $order->has_status( 'completed' ) ) {
			$order->update_status( 'cancelled' );
			return true;
		}
		return false;
	}


	public function get_user_order( $user_id = null, $level_id ) {
		global $wpdb;
		if ( !$user_id ) {
			$user_id = learn_press_get_current_user_id();
		}
		if( !isset($this->user_orders[$level_id]) || empty($this->user_orders[$level_id]) ) {
			return false;
		}
		return $this->user_orders[$level_id];
	}


	public function update_lp_order( $lp_order_id ) {

		$update_access_course = LP()->settings->get( 'pmpro_update_access_course', 'yes' );
		$level_id 	= $this->get_order_level( $lp_order_id );
		if( $level_id ) {
			$meta_key 	= '_payment_method';
			$meta_value 	= 'paid-memberships-pro';
			$payment_method = get_post_meta( $lp_order_id, $meta_key );
			if( $meta_value !== $payment_method ) {
				update_post_meta( $lp_order_id, $meta_key, $meta_value );
			}
		}

		if( $update_access_course == 'no' ) {
			return false;
		}

		$lp_order = learn_press_get_order( $lp_order_id );
		$order_courses = ( $lp_order->get_item_ids() );
		if( !$order_courses ) {
			$order_courses = array();
		}

		$level_courses = array_keys( $this->get_courses_by_level( $level_id ) );

		$add_courses = array_diff( $level_courses, $order_courses );
		$rem_courses = array_diff( $order_courses, $level_courses );
		if( empty( $add_courses ) && empty( $rem_courses ) ) {
			return false;
		}

		$change = false;
		if( !empty( $add_courses ) ) {
			foreach ($add_courses as $cid ) {
				$lp_order->add_item( intval($cid) );
			}
			$change = true;
			$this->redirect = true;
		}

		if( !empty($rem_courses) ) {
			foreach ( $rem_courses as $item_id => $cid ){
				$lp_order->remove_item( intval( $item_id ) );
			}
			$change = true;
		}

		if( $change ) {
			$lp_order->save();
			$this->redirect = true;
		}
		return $change;
	}


	public function create_lp_order( $user_id = null, $level_id ) {
		# get course by level
		$courses = $this->get_courses_by_level( $level_id );

		if( !$courses || empty( $courses ) ) {
			return false;
		}

		# cretate order
		$user_id 	= learn_press_get_current_user_id();
		$user 		= learn_press_get_current_user();
		$level 		= pmpro_getLevel( $level_id );

		$level_cost 	= learn_press_pmpro_getLevelCost( $level, $user_id );
		$order_data = array(
			'post_author' 	=> $user_id,
			'post_parent' 	=> '0',
			'post_type' 	=> LP_ORDER_CPT,
			'post_status' 	=> 'lp-completed',
			'ping_status' 	=> 'closed',
			'post_title' 	=> __( 'Order on', 'learnpress-paid-membership-pro' ) . ' ' . current_time( "l jS F Y h:i:s A" ),
			'meta_input' 	=> array(
				'_user_id' 		=> $user_id,
				'_created_via' 		=> 'membership_auto',
				'_payment_method' 	=> self::$_payment_method,
				'_payment_method_title' => __( 'Memberships', 'learnpress-paid-membership-pro' ),
				'_order_total' => $level_cost,
				self::$_key_lp_pmpro_level => $level_id,
			)
		);

		$order_id = wp_insert_post( $order_data );
		$lp_order = learn_press_get_order( $order_id );
		foreach ( $courses as $course_id => $course ) {
			$lp_order->add_item( $course_id );
		}
		$lp_order->save();
		return $order_id;
	}


	public function get_courses_by_level( $level_id ) {
		global $wpdb;
		$sql = $wpdb->prepare("SELECT
					p.ID, p.post_title
				FROM
					{$wpdb->posts} AS p
						INNER JOIN
					{$wpdb->postmeta} AS pm ON (p.ID = pm.post_id)
				WHERE
					1 = 1
						AND ((pm.meta_key = %s 
						AND pm.meta_value = %s))
						AND p.post_type = %s
						AND ((p.post_status = 'publish'))
				GROUP BY p.ID
				ORDER BY p.post_date DESC", self::$_key_lp_pmpro_levels, $level_id, LP_COURSE_CPT );
		$rows = $wpdb->get_results( $sql, OBJECT_K );
		return $rows;
	}

}

$x = new LP_Pmpro_Order();
//$x->learn_press_lock_course_after_expired( 889, 731 );
