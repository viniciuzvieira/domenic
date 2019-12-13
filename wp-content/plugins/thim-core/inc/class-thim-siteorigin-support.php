<?php

/**
 * Class Thim_SiteOrigin_Support.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Thim_SiteOrigin_Support' ) ) {

	class Thim_SiteOrigin_Support extends Thim_Singleton {

		/**
		 * Thim_SiteOrigin_Support constructor.
		 *
		 * @since 1.0.0
		 */
		protected function __construct() {
			$this->hooks();
		}

		/**
		 * Add hooks.
		 *
		 * @since 1.3.1
		 */
		private function hooks() {
			add_action( 'admin_init', array( $this, 'check_required' ) );
			add_filter( 'siteorigin_widgets_widget_banner', array( $this, 'widget_banner_img_src' ), 10, 2 );
		}

		/**
		 * Set banner url for widget.
		 *
		 * @since 1.0.0
		 *
		 * @param $banner_url
		 * @param $widget_meta
		 *
		 * @return string
		 */
		public function widget_banner_img_src( $banner_url, $widget_meta ) {
			if ( ! $this->is_support() ) {
				return $banner_url;
			}

			$id = $widget_meta['ID'];

			if ( strpos( $id, 'thim-' ) === false ) {
				return $banner_url;
			}

			$file = $widget_meta['File'];

			$banner = str_replace( $id . '.php', 'assets/banner.svg', $file );

			$theme_uri  = get_template_directory_uri();
			$theme_path = get_template_directory();

			$banner_sub_path = str_replace( $theme_path, '', $banner );
			$banner_url      = $theme_uri . $banner_sub_path;

			return $banner_url;
		}

		/**
		 * Get theme support SiteOrigin Panels.
		 *
		 * @since 1.0.0
		 *
		 * @return bool
		 */
		private function is_support() {
			return get_theme_support( 'so-builder' );
		}

		/**
		 * Check require plugin SiteOrigin Panels and SO Widgets Bundle.
		 *
		 * @since 1.0.0
		 */
		public function check_required() {
			if ( ! $this->is_support() ) {
				return;
			}

			if ( ! function_exists( 'siteorigin_panels_init' ) ) {
				$this->require_plugin( 'siteorigin-panels', 'Page Builder by SiteOrigin' );
			}

			if ( ! class_exists( 'SiteOrigin_Widgets_Bundle' ) ) {
				$this->require_plugin( 'so-widgets-bundle', 'SiteOrigin Widgets Bundle' );
			}
		}

		/**
		 * Require plugin from WP.org.
		 *
		 * @since 1.0.0
		 *
		 * @param $slug
		 * @param $name
		 */
		private function require_plugin( $slug, $name ) {
			$link = Thim_Plugins_Manager::get_url_plugin_actions(
				array(
					'slug'          => $slug,
					'plugin-action' => 'install',
					'network'       => false,
					'wporg'         => true,
				)
			);

			Thim_Notification::add_notification(
				array(
					'id'          => 'require_plugin_' . $slug,
					'type'        => 'success',
					'content'     => sprintf( 'Install and activate <a href="%s">%s</a> to start now! If you don\'t use <a href="%s" target="_blank">SiteOrigin</a> please ignore this message.', $link, $name, 'https://wordpress.org/plugins/siteorigin-panels/' ),
					'dismissible' => true,
					'global'      => true,
				)
			);
		}
	}
}
