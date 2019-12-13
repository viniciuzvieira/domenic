<?php
/**
 * @Author: ducnvtt
 * @Date  :   2016-03-03 10:34:45
 * @Last  Modified by:   ducnvtt
 * @Last  Modified time: 2016-03-25 17:12:38
 */

//use TP_Event_Auth\Events\Event as Event;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$event = new Auth_Event( get_the_ID() );
//$user     = TP_Event_Authentication()->loader->load_module( 'TP_Event_Auth\Auth\User' );
$user_reg = $event->booked_quantity( get_current_user_id() );
?>
<h3 class="book-title"><?php esc_html_e( 'Buy Ticket', 'eduma' ); ?></h3>

<div class="event_register_area">

    <form name="event_register" class="event_register" method="POST">

        <ul class="info-event">
            <li>
                <div class="label"><?php esc_html_e( 'Total Slots', 'eduma' ); ?></div>
                <div class="value"><?php echo absint( $event->qty ); ?></div>
            </li>
            <li>
                <div class="label"><?php esc_html_e( 'Booked Times', 'eduma' ); ?></div>
                <div class="value"><?php echo count( $event->load_registered() ); ?></div>
            </li>
            <li>
                <div class="label"><?php esc_html_e( 'Booked Slots', 'eduma' ); ?></div>
                <div class="value"><?php echo esc_html( $event->booked_quantity() ); ?></div>
            </li>
            <li class="event-cost">
                <div class="label"><?php esc_html_e( 'Cost', 'eduma' ); ?></div>
                <div class="value"><?php echo ( $event->price ) ? event_auth_format_price( $event->price ).esc_html__('/Slot','eduma') : '<span class="free">'.esc_html__('Free','eduma').'</span>'; ?></div>
            </li>
            <li>
                <?php if ( ( $event->price || event_get_option( 'event_free_book_number' ) === 'many' ) && ( absint( $event->qty ) != 0 && $event->post->post_status !== 'tp-event-expired' ) ) : ?>
                    <div class="label"><?php esc_html_e( 'Quantity', 'eduma' ); ?></div>
                    <div class="value">
                        <input type="number" name="qty" value="1" min="1" id="event_register_qty" />
                    </div>
                <?php else: ?>
                    <div class="label"><?php esc_html_e( 'Quantity', 'eduma' ); ?></div>
                    <div class="value">
                        <input disabled type="number" value="1" min="1" id="event_register_qty" />
                        <input type="hidden" name="qty" value="1" min="1" />
                    </div>
                <?php endif; ?>
            </li>
            <?php if ( intval( $event->price ) > 0 ) : ?>
                <li class="event-payment">
                    <div class="label"><?php esc_html_e( 'Pay with', 'eduma' ); ?></div>
                    <div class="envent_auth_payment_methods">
                        <?php $payments = event_auth_payments(); ?>
                        <?php $i = 0; foreach ( $payments as $id => $payment ) : ?>

                            <input id="payment_method_<?php echo esc_attr( $id ) ?>" type="radio" name="payment_method" value="<?php echo esc_attr( $id ) ?>"<?php echo $i === 0 ? ' checked' : '' ?>/>
                            <label for="payment_method_<?php echo esc_attr( $id ) ?>"><?php echo esc_html( $payment->get_title() ) ?></label>
                            <?php $i++; endforeach; ?>
                        <?php //do_action( 'event_auth_payment_gateways_select' ); ?>
                    </div>

                </li>
            <?php endif; ?>
        </ul>


        <!--Hide payment option when price is 0-->

        <!--End hide payment option when price is 0-->

        <div class="event_register_foot">
            <input type="hidden" name="event_id" value="<?php echo esc_attr( get_the_ID() ) ?>" />
            <input type="hidden" name="action" value="event_auth_register" />
            <?php wp_nonce_field( 'event_auth_register_nonce', 'event_auth_register_nonce' ); ?>
            <?php if(  $event->post->post_status === 'tp-event-expired' ) : ?>
                <button type="submit" disabled class="event_button_disable"><?php esc_html_e( 'Expired', 'eduma' ); ?></button>
            <?php elseif(  absint( $event->qty ) == 0  ) : ?>
                <button type="submit" disabled class="event_button_disable"><?php esc_html_e( 'Sold Out', 'eduma' ); ?></button>
            <?php else: ?>
                <button type="submit" class="event_register_submit event_auth_button"><?php esc_html_e( 'Book Now', 'eduma' ); ?></button>
            <?php endif ?>

        </div>

    </form>

</div>