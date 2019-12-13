<?php

/**
 * Class Thim_Feedback.
 *
 * @since 1.2.1
 */
class Thim_Feedback extends Thim_Singleton {
	/**
	 * Get button open box send feedback.
	 *
	 * @since 1.2.1
	 *
	 * @return string
	 */
	public static function get_button() {
		$link = '<button class="tc-btn-send-feedback thim-core-open-send-feedback button-link">Send feedback</button>';

		return $link;
	}

	/**
	 * Thim_Feedback constructor.
	 *
	 * @since 1.2.1
	 */
	protected function __construct() {
		$this->hooks();
	}

	/**
	 * Add hooks.
	 *
	 * @since 1.2.1
	 */
	private function hooks() {
		add_action( 'thim_core_dashboard_enqueue_scripts', array( $this, 'enqueue_scripts_dashboard' ) );
		add_action( 'thim_core_list_modals', array( $this, 'add_modal' ) );
		add_action( 'wp_ajax_thim_core_feedback', array( $this, 'ajax_send_feedback' ) );
	}

	/**
	 * Handle ajax send feedback.
	 *
	 * @since 1.2.1
	 */
	public function ajax_send_feedback() {
		$content        = isset( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : '';
		$email          = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$include_access = isset( $_POST['developer-access'] ) ? sanitize_text_field( $_POST['developer-access'] ) : 'no';

		if ( ! is_email( $email ) ) {
			wp_send_json_error( __( 'Email is invalid! Please try again with another email.', 'thim-core' ) );
		}

		if ( ! Thim_Product_Registration::is_active() && ! Thim_Free_Theme::is_free() ) {
			wp_send_json_error( __( 'You need activate theme to use this feature.', 'thim-core' ) );
		}

		$result = $this->send_feedback( array(
			'content'          => $content,
			'email'            => $email,
			'developer-access' => $include_access
		) );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		wp_send_json_success( __( 'Thank you for the feedback!', 'thim-core' ) );
	}

	/**
	 * Send feedback.
	 *
	 * @since 1.2.1
	 *
	 * @param $args
	 *
	 * @return true|WP_Error
	 */
	private function send_feedback( $args ) {
		$data = wp_parse_args( $args, array(
			'email'            => '',
			'content'          => '',
			'developer-access' => ''
		) );

		$data['site-url']      = site_url();
		$data['system-status'] = Thim_System_Status::get_draw_system_status();
		$data['envato-id']     = Thim_Theme_Manager::get_data( 'envato_item_id' );

		if ( $data['developer-access'] === 'yes' ) {
			do_action( 'thim_core_grant_developer_access' );

			$data['developer-access'] = Thim_Developer_Access::get_link_access();
		}

		return $this->send_to_server_feedback( $data );
	}

	/**
	 * Send data feedback to server.
	 *
	 * @param $data
	 *
	 * @return true|WP_Error
	 */
	private function send_to_server_feedback( $data ) {
		$host = Thim_Admin_Config::get( 'host_downloads' ) . '/user-feedback/';

		$response = Thim_Remote_Helper::post(
			$host, array(
			'body' => $data
		), true );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$arr    = (array) $response;
		$result = isset( $arr['success'] ) ? $arr['success'] : false;

		if ( ! $result ) {
			return new WP_Error( __( 'Something went wrong', 'thim-core' ) );
		}

		return true;
	}

	/**
	 * Add modal send feedback.
	 *
	 * @since 1.2.2
	 */
	public function add_modal() {
		Thim_Modal::render_modal( array(
			'id'       => 'tc-send-feedback',
			'template' => 'send-feedback.php'
		) );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.2.1
	 */
	public function enqueue_scripts_dashboard() {
		wp_enqueue_script( 'thim-core-feedback', THIM_CORE_ADMIN_URI . '/assets/js/feedback.js', array( 'thim-modal-v2', 'thim-core-admin' ), THIM_CORE_VERSION );

		$this->localize();
	}

	/**
	 * Localize script.
	 *
	 * @since 1.2.1
	 */
	private function localize() {
		wp_localize_script( 'thim-core-feedback', 'thim_core_feedback', array(
			'ajax_url' => admin_url( 'admin-ajax.php?action=thim_core_feedback' ),
			'wrong'    => __( 'Something went wrong! Please try again later.', 'thim-core' )
		) );
	}
}