<?php

if ( ! function_exists( 'thim_get_widget_template' ) ) {
	function thim_get_widget_template( $widget_base, $args = array(), $template_name = 'base' ) {
		if ( is_array( $args ) && isset( $args ) ) {
			extract( $args );
		}

		if ( false === strpos( $template_name, '.php' ) ) {
			$template_name .= '.php';
		}

		$parent_path = get_template_directory() . '/inc/widgets/' . $widget_base . '/tpl/' . $template_name;
		$child_path  = get_stylesheet_directory() . '/inc/widgets/' . $widget_base . '/' . $template_name;

		if ( file_exists( $child_path ) ) {
			$template_path = $child_path;
		} elseif ( file_exists( $parent_path ) ) {
			$template_path = $parent_path;
		} else {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_name ), '1.0.0' );

			return;
		}

		require $template_path;
	}
}

