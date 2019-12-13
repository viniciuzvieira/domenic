<?php
/**
 * User Profile tabs
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
$current = learn_press_get_current_profile_tab();
?>
<ul class="nav nav-tabs" role="tablist">
	<?php foreach ( $tabs as $key => $tab ) : 
	   $tab_base = isset($tab['base']) ? $tab['base'] : $key; ?>
		<li class="<?php echo ( $key ==  $current ) ? 'active' : ''; ?>">
			<a href="#user_<?php echo esc_attr( $key ); ?>" data-toggle="tab"><?php echo apply_filters( 'learn_press_profile_' . $tab_base . '_tab_title',  $tab['title'] , $tab_base, $key ); ?></a>
		</li>
	<?php endforeach; ?>
</ul>
<div class="tab-content">
	<?php foreach ( $tabs as $key => $tab ) : ?>
		<div class="tab-pane <?php echo ( $key == $current ) ? 'active' : ''; ?>" id="user_<?php echo esc_attr( $key ); ?>">
			<?php if ( is_callable( $tab['callback'] ) ): ?>
				<?php echo call_user_func_array( $tab['callback'], array( $key, $tab, $user ) ); ?>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<div class="clearfix"></div>