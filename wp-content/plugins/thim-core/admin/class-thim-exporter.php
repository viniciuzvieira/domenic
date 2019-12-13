<?php

/**
 * Class Thim_Export
 *
 * @since 1.2.0
 */
class Thim_Exporter extends Thim_Singleton {
	/**
	 * @var string
	 *
	 * @since 1.2.0
	 */
	private static $action = 'thim-exporter';

	/**
	 * Get base url export.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	private static function get_base_url_export() {
		return admin_url( 'admin.php?action=' . self::$action );
	}

	/**
	 * Get url export package.
	 *
	 * @since 1.2.0
	 *
	 * @param $package string
	 *
	 * @return string
	 */
	public static function get_url_export( $package ) {
		$base = self::get_base_url_export();

		return add_query_arg( 'package', $package, $base );
	}

	/**
	 * Thim_Export constructor.
	 *
	 * @since 1.2.0
	 */
	protected function __construct() {
		$this->hooks();
	}

	/**
	 * Add hooks.
	 *
	 * @since 1.0.0
	 */
	private function hooks() {
		add_action( 'admin_action_' . self::$action, array( $this, 'handle' ) );
	}

	/**
	 * Handle request.
	 *
	 * @since 1.2.0
	 */
	public function handle() {
		$package = isset( $_REQUEST['package'] ) ? $_REQUEST['package'] : false;

		if ( ! $package ) {
			return;
		}

		do_action( 'thim_exporter_package', $package );

		wp_die( __( 'Package not found!', 'thim-core' ) );
	}
}