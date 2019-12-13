<?php
/**
 * Plugin load class.
 *
 * @author   ThimPress
 * @package  LearnPress/bbPress/Classes
 * @version  3.0.2
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Addon_bbPress' ) ) {
	/**
	 * Class LP_Addon_bbPress.
	 *
	 * @since 3.0.0
	 */
	class LP_Addon_bbPress extends LP_Addon {

		/**
		 * @var bool
		 */
		protected $_start_forum = false;

		/**
		 * LP_Addon_bbPress constructor.
		 */
		public function __construct() {

			$this->version         = LP_ADDON_BBPRESS_VER;
			$this->require_version = LP_ADDON_BBPRESS_REQUIRE_VER;

			if ( ! $this->bbpress_is_active() ) {
				add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			} else {
				parent::__construct();
			}
		}

		/**
		 * Define constants.
		 */
		protected function _define_constants() {
			define( 'LP_ADDON_BBPRESS_PATH', dirname( LP_ADDON_BBPRESS_FILE ) );
			define( 'LP_ADDON_BBPRESS_TEMPLATE', LP_ADDON_BBPRESS_PATH . '/templates/' );
		}

		/**
		 * Includes files.
		 */
		protected function _includes() {
			include_once 'functions.php';
		}

		/**
		 * Init hooks.
		 */
		protected function _init_hooks() {
			add_filter( 'learn-press/admin-course-tabs', array( $this, 'add_course_tab' ) );
			// save course action
			add_action( 'save_post', array( $this, 'save_post' ) );
			// delete course and delete forum action
			add_action( 'before_delete_post', array( $this, 'delete_post' ) );
			add_action( 'bbp_template_before_single_topic', array( $this, 'before_single' ) );
			add_action( 'bbp_template_before_single_forum', array( $this, 'before_single' ) );
			add_action( 'bbp_template_after_single_topic', array( $this, 'after_single' ) );
			add_action( 'bbp_template_after_single_forum', array( $this, 'after_single' ) );
			add_action( 'learn-press/single-course-summary', array( $this, 'forum_link' ), 0 );
		}

		/**
		 * Add bbPress tab in admin course.
		 *
		 * @param $tabs
		 *
		 * @return array
		 */
		public function add_course_tab( $tabs ) {
			$forum = array( 'course_bbpress' => new RW_Meta_Box( self::course_bbpress_meta_box() ) );

			return array_merge( $tabs, $forum );
		}

		/**
		 * BBPress course meta box.
		 *
		 * @return mixed
		 */
		public static function course_bbpress_meta_box() {

			$args = array( 'post_type' => 'forum', 'post_status' => 'publish', 'posts_per_page' => - 1, );

			$forums = new WP_Query( $args );

			$options     = array();
			$options[''] = __( 'Create New', 'learnpress-bbpress' );
			if ( $forums->have_posts() ) {
				while ( $forums->have_posts() ) {
					$forums->the_post();

					$course_id = learn_press_bbp_get_course( get_the_ID() );
					$post_id   = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : '';

					if ( ! $course_id || $course_id == $post_id ) {
						$options[ get_the_ID() ] = get_the_title();
					}
				}
				wp_reset_postdata();

			}

			$meta_box = array(
				'id'       => 'course_bbpress',
				'title'    => __( 'Forum', 'learnpress-bbpress' ),
				'pages'    => array( LP_COURSE_CPT ),
				'priority' => 'high',
				'icon'     => 'dashicons-list-view',
				'fields'   => array(
					array(
						'name' => __( 'Enable', 'learnpress-bbpress' ),
						'id'   => '_lp_bbpress_forum_enable',
						'type' => 'yes-no',
						'desc' => __( 'Enable bbPress forum for this course.', 'learnpress-bbpress' ),
						'std'  => 'no'
					),
					array(
						'name'       => __( 'Course Forum', 'learnpress-bbpress' ),
						'id'         => '_lp_course_forum',
						'type'       => 'select',
						'desc'       => __( 'Select forum of this course, choose Create New to create new forum for course, uncheck Enable option to disable.', 'learnpress-bbpress' ),
						'options'    => $options,
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => '_lp_bbpress_forum_enable',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
					array(
						'name'       => __( 'Restrict User', 'learnpress-bbpress' ),
						'id'         => '_lp_bbpress_forum_enrolled_user',
						'type'       => 'yes-no',
						'desc'       => __( 'Only user(s) enrolled course can access this forum.', 'learnpress-bbpress' ),
						'std'        => 'no',
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => '_lp_bbpress_forum_enable',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					)
				)
			);

			return apply_filters( 'learn-press/course-bbpress/settings-meta-box-args', $meta_box );
		}

		/**
		 * Save post.
		 *
		 * @param $post_id
		 */
		public function save_post( $post_id ) {
			if ( get_post_type( $post_id ) != LP_COURSE_CPT || wp_is_post_revision( $post_id ) ) {
				return;
			}

			if ( get_post_meta( $post_id, '_lp_bbpress_forum_enable', true ) != 'yes' ) {
				return;
			}

			$course = get_post( $post_id );

			$forum_id = get_post_meta( $post_id, '_lp_course_forum', true );

			if ( ! $forum_id ) {
				$forum = array(
					'post_title'   => $course->post_title,
					'post_content' => '',
					'post_author'  => $course->post_author,
				);

				$forum_id = bbp_insert_forum( $forum, array() );
				update_post_meta( $post_id, '_lp_course_forum', $forum_id );
			}
		}

		/**
		 * Delete forum when delete parent course and disable forum for course when delete it's forum.
		 *
		 * @param $post_id
		 */
		public function delete_post( $post_id ) {

			$post_type = get_post_type( $post_id );

			switch ( $post_type ) {
				case LP_COURSE_CPT:
					$forum_id = get_post_meta( $post_id, '_lp_course_forum', true );

					if ( ! $forum_id ) {
						return;
					}

					wp_delete_post( $forum_id );
					break;

				case 'forum':
					$course_id = learn_press_bbp_get_course( $post_id );

					update_post_meta( $course_id, '_lp_bbpress_forum_enable', 'no' );
					break;
				default:
					break;
			}
		}

		/**
		 * Forum link in single course page.
		 */
		public function forum_link() {

			$course = LP_Global::course();

			if ( ! $course ) {
				return;
			}

			$forum_id = get_post_meta( $course->get_id(), '_lp_course_forum', true );

			if ( ! $forum_id ) {
				return;
			}

			if ( get_post_status( $forum_id ) != 'publish' ) {
				return;
			}

			if ( ! in_array( get_post_type( $forum_id ), array( 'topic', 'forum' ) ) ) {
				return;
			}

			if ( ! $this->can_access_forum( $forum_id, get_post_type( $forum_id ) ) ) {
				return;
			}

			if ( get_post_meta( $course->get_id(), '_lp_bbpress_forum_enable', true ) !== 'yes' ) {
				return;
			}

			learn_press_get_template( 'forum-link.php', array( 'forum_id' => $forum_id ), learn_press_template_path() . '/addons/bbpress/', LP_ADDON_BBPRESS_TEMPLATE );
		}

		/**
		 * Check allow user access forum.
		 *
		 * @param $id
		 * @param $type
		 *
		 * @return bool
		 */
		private function can_access_forum( $id, $type ) {

			// invalid forum
			if ( ! $id ) {
				return false;
			}

			// admin, moderator, key master always can access forum
			if ( current_user_can( 'manage_options' ) || current_user_can( 'bbp_moderator' ) || current_user_can( 'bbp_keymaster' ) ) {
				return true;
			}

			if ( $type == 'forum' ) {
				$forum_id = $id;
			} elseif ( $type == 'topic' ) {
				$forum_id = get_post_meta( $id, '_bbp_forum_id', true );
			} else {
				return false;
			}

			$forum = get_post( $forum_id );

			// restrict access bases on ancestor forums
			if ( $ancestor_forums = $forum->ancestors ) {
				foreach ( $ancestor_forums as $ancestor_forum_id ) {
					if ( ! $this->_restrict_access( $ancestor_forum_id ) ) {
						return false;
					}
				}

				return true;
			}

			return $this->_restrict_access( $forum_id );
		}

		/**
		 * Check forum accessibility.
		 *
		 * @param $forum_id
		 *
		 * @return bool
		 */
		private function _restrict_access( $forum_id ) {
			$course_id = learn_press_bbp_get_course( $forum_id );

			// normal publish forum which has no connecting with any courses
			if ( ! $course_id ) {
				return true;
			}

			if( LP_COURSE_CPT !== get_post_type($course_id) ){
			    return;
			}
			
			$course = learn_press_get_course( $course_id );

			$required_enroll = $course->is_required_enroll();

			// allow access not require enroll course's forum
			if ( ! $required_enroll ) {
				return true;
			}

			if ( $this->is_public_forum( $course_id ) ) {
				return true;
			}

			$user = learn_press_get_current_user();

			if ( ! $user->get_id() ) {
				return false;
			}

			// allow post author access
			if ( $user->get_id() == get_post_field( 'post_author', $course_id ) ) {
				return true;
			}

			// restrict user not enroll
			$user_course_data = $user->get_course_data( $course_id );
			$status           = $user_course_data->get_data( 'status' );
			if ( in_array( $status, array( 'enrolled', 'finished' ) ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check forum public.
		 *
		 * @param $course_id
		 *
		 * @return bool
		 */
		public function is_public_forum( $course_id ) {
			$restrict = get_post_meta( $course_id, '_lp_bbpress_forum_enrolled_user', true );

			if ( is_null( $restrict ) || ( $restrict === false ) || ( $restrict == '' ) || ( $restrict == 'no' ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Before single topic and single forum.
		 */
		public function before_single() {
			global $post;
			if ( ! $this->can_access_forum( $post->ID, $post->post_type ) ) {
				$this->_start_forum = true;
				ob_start();
			}
		}

		/**
		 * After single topic and single forum.
		 */
		public function after_single() {
			global $post;
			if ( $this->_start_forum ) {
				ob_end_clean(); ?>
                <div id="restrict-access-form-message" style="clear: both;">
                    <p><?php _e( 'You have to enroll the respective course!', 'learnpress-bbpress' ); ?></p>
					<?php if ( $course_id = learn_press_bbp_get_course( $post->ID ) ) { ?>
                        <p><?php _e( 'Go back to ', 'learnpress-bbpress' ); ?>
                            <a href="<?php echo get_permalink( $course_id ); ?>"> <?php echo get_the_title( $course_id ); ?></a>
                        </p>
					<?php } ?>
                </div>
				<?php
			}
		}

		/**
		 * Check bbPress active.
		 *
		 * @return bool
		 */
		public function bbpress_is_active() {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			return class_exists( 'bbPress' ) && is_plugin_active( 'bbpress/bbpress.php' );
		}

		/**
		 * Show admin notice when inactive bbPress.
		 */
		public function admin_notices() {
			?>
            <div class="notice notice-error">
                <p>
					<?php echo wp_kses(
						sprintf(
							__( '<strong>bbPress</strong> addon for <strong>LearnPress</strong> requires %s plugin is <strong>installed</strong> and <strong>activated</strong>.', 'learnpress-bbpress' ),
							sprintf( '<a href="%s" target="_blank">bbPress</a>', admin_url( 'plugin-install.php?tab=search&type=term&s=bbpress' ) )
						), array(
							'a'      => array(
								'href'   => array(),
								'target' => array(),
							),
							'strong' => array()
						)
					); ?>
                </p>
            </div>
		<?php }
	}
}