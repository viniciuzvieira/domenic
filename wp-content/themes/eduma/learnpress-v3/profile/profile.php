<?php
/**
 * Template for displaying main user profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/profile.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$profile = LP_Global::profile();

if ( $profile->is_public() ) {
	?>

    <div class="learn-press-user-profile profile-container" id="learn-press-user-profile">
        <div class="user-tab">
            <?php
            /**
             * @since 3.0.0
             */
            do_action( 'learn-press/before-user-profile', $profile );
            ?>
        </div>

        <div class="profile-tabs">
            <?php
            /**
             * @since 3.0.0
             */
            do_action( 'learn-press/user-profile', $profile );
            ?>
        </div>

        <?php
        /**
         * @since 3.0.0
         */
        do_action( 'learn-press/after-user-profile', $profile );
        ?>
    </div>

<?php } else {
	_e( 'This user does not public their profile.', 'eduma' );
}