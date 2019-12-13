<?php
/**
 * Template for displaying user profile
 *
 * @author ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="learn-press-user-profile profile-container" id="learn-press-user-profile">
	<div class="user-tab">
		<?php learn_press_get_template( 'profile/info.php', array( 'user' => $user, 'tabs' => $tabs, 'current' => $current ) ); ?>
	</div>

	<div class="profile-tabs">
		<?php learn_press_get_template( 'profile/tabs.php', array( 'user' => $user, 'tabs' => $tabs, 'current' => $current ) ); ?>
	</div>
</div>