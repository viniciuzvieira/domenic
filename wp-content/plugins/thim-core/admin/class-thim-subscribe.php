<?php

/**
 * Class Thim_Subscribe.
 *
 * @since 0.8.7
 */
class Thim_Subscribe extends Thim_Singleton {
	/**
	 * Is subscribed?
	 *
	 * @since 0.8.9
	 *
	 * @return bool
	 */
	public static function is_subscribed() {
		$option = get_option( 'thim_core_is_subscribed', false );

		$option = (bool) $option;

		return apply_filters( 'thim_core_is_subscribed', $option );
	}

	/**
	 * Get form template.
	 *
	 * @since 0.8.7
	 */
	public static function get_form() {
		$args = self::get_args_form();

		$args = wp_parse_args( $args, array(
			'user' => '',
			'form' => '',
		) );

		wp_enqueue_script( 'tc-form-subscribe' );

		return Thim_Dashboard::get_template( 'partials/form-subscribe.php', $args );
	}

	/**
	 * Get arguments for form.
	 *
	 * @since 0.8.9
	 *
	 * @return array|mixed
	 */
	private static function get_args_form() {
		$defaults = array(
			'user' => 'e514ab4788b7083cb36eed163',
			'form' => 'c246d06775',
		);

		return apply_filters( 'thim_core_args_form_subscribe', $defaults );
	}

	/**
	 * Thim_Subscribe constructor.
	 *
	 * @since 0.8.7
	 */
	protected function __construct() {
		$this->init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 0.8.9
	 */
	private function init_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_tc-submit-form-subscribe', array( $this, 'handle_ajax_submit_form_subscribe' ) );
	}

	/**
	 * Handle ajax submit form subscribe.
	 *
	 * @since 0.9.0
	 */
	public function handle_ajax_submit_form_subscribe() {
		update_option( 'thim_core_is_subscribed', true );

		wp_send_json_success();
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 0.8.9
	 */
	public function enqueue_scripts() {
		wp_register_script( 'tc-form-subscribe', THIM_CORE_ADMIN_URI . '/assets/js/form-subscribe.js', array( 'jquery' ), THIM_CORE_VERSION );
		wp_localize_script( 'tc-form-subscribe', 'tc_form_subscribe', array(
			'url_ajax' => admin_url( 'admin-ajax.php?action=tc-submit-form-subscribe' ),
		) );
	}
}
