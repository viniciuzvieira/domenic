<?php
$user    = $args['user'];
$user_id = $user->ID;

$total              = lp_commission_get_total_commission( $user_id );
$currency           = learn_press_get_currency_symbol();
$min                = LPC()->get_commission_min();
$current_tab        = learn_press_get_current_profile_tab();
$notifications      = $args['notifications'];
$histories          = LP_RW()->get_withdrawals_by_user_id( $user_id );
$payment_methods    = LP_RW()->get_payment_methods();
$withdrawal_methods = LP_RW()->get_withdrawal_methods();

?>
<h3 class="box-title"><?php echo esc_html__('Your commission','eduma'); ?></h3>
<p>Total:
	<span class="count"><?php echo esc_html( $total ); ?></span>
	<span class="unit"><?php echo $currency; ?></span></p>
<?php 
if ( key_exists( 'return', $notifications ) ) {
	if ( $notifications['return'] ) {
		learn_press_display_message( $notifications['msg'] );
	} else {
		learn_press_display_message( $notifications['msg'] . ' (' . $notifications['code'] . ')', 'error' );
	}
} 
?>
<h3 class="box-title"><?php _e('Withdrawal History','eduma'); ?></h3>
<?php if ( ! empty( $histories ) ): ?>
	<table class="lp_list">
		<thead>
		<tr>
			<th>ID</th>
			<th>Amount (<?php echo learn_press_get_currency_symbol(); ?>)</th>
			<th>Time request</th>
			<th>Time resolve</th>
			<th>Method</th>
			<th>Status</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $histories as $index => $history ):
			$method = $history['method_title'];
			?>
			<tr>
				<td>#<?php echo $history['ID']; ?></td>
				<td><?php echo esc_html( $history['value'] );
					learn_press_currency_positions() ?></td>
				<td><?php echo esc_html( $history['time_request'] ); ?></td>
				<td><?php echo esc_html( $history['time_resolve'] ); ?></td>
				<td><?php echo esc_html( $method ); ?></td>
				<td><?php echo esc_html( $history['status'] ); ?></td>
			</tr>
			<?php
		endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<h3 class="box-title"><?php echo __('Withdrawal','eduma'); ?></h3>
<?php
if ( $total <= 0 && $total < $min ) {
	?>
	<div><?php _e( 'You have not enough money to withdraw', 'eduma'); ?></div>
	<?php
} else {
	# show list of withdrawal methods
	foreach ( $withdrawal_methods as $method_key => $method_title ):
		?>
				<a href="javascript: showWithdrawalForm('<?php echo esc_attr( $method_key ); ?>');"><?php echo esc_html( $method_title ); ?></a>
		<?php
	endforeach;
	# show list of withdrawal forms
	foreach ( $withdrawal_methods as $method_key => $method_title ):
		$html = LP_RW()->get_withdrawal_form( $method_key, $total, $min, $currency );
		?>
				<div class="withdrawal_form_wraper <?php echo $method_key; ?>" style="display: none;"><?php echo $html; ?></div>
		<?php
	endforeach;
}
?>
