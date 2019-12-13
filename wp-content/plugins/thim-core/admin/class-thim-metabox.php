<?php

/**
 * Class Thim_Core_Admin.
 *
 * @package   Thim_Core
 * @since     0.1.0
 */
class Thim_Metabox extends Thim_Singleton {
	/**
	 * Thim_Metabox constructor.
	 *
	 * @since 0.1.0
	 */
	protected function __construct() {
		$this->init();
	}

	/**
	 * Init.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		$this->libraries();
	}

	/**
	 * Hook register metabox.
	 *
	 * @since 0.1.0
	 */
	public function register_metabox() {
		do_action( 'thim_metabox_register' );
	}

	/**
	 * Include libraries.
	 *
	 * @since 0.1.0
	 */
	private function libraries() {
		$this->metabox_core();
		$this->metabox_extensions();
	}

	/**
	 * Include metabox.io
	 *
	 * @since 0.1.0
	 */
	private function metabox_core() {
		if ( class_exists( 'RWMB_Loader' ) ) {
			return;
		}

		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box/meta-box.php';
	}

	/**
	 * Include metabox.io extensions.
	 *
	 * @since 0.1.0
	 */
	private function metabox_extensions() {
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-show-hide/meta-box-show-hide.php';
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-group/meta-box-group.php';
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-tabs/meta-box-tabs.php';
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-conditional-logic/meta-box-conditional-logic.php';
	}
}
