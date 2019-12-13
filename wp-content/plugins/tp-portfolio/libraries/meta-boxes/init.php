<?php
add_action('admin_init', 'thim_register_meta_boxes');

/**
 * Register meta boxes via a filter
 * Advantages:
 * - prevents incorrect hook
 * - prevents duplicated global variables
 * - allows users to remove/hide registered meta boxes
 * - no need to check for class existences
 *
 * @return void
 */
function thim_register_meta_boxes()
{
	$meta_boxes = apply_filters('thim_meta_boxes', array());
	foreach ($meta_boxes as $meta_box) {
		new THIM_Meta_Box($meta_box);
	}
}