<?php
/**
 * Display settings for checkout
 *
 * @author  ThimPress
 * @package LearnPress/Admin/Views
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = LP()->settings;

?>
<table id="learn-press-pmpro-settings-admin" class="form-table">
	<tbody>
	<?php

		do_action( 'learn_press_before_' . $this->id . '_' . $this->section['id'] . '_settings_fields', $this );

		foreach ( $this->get_settings() as $field ) {
			$this->output_field( $field );
		}

	?>
	</tbody>

</table>