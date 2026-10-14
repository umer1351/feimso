<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\WritesDocs;
use Illuminate\Database\Seeder;

/**
 * Email + push notification templates the storefront looks up by `type`.
 * Checkout stops (never reaches the success page) if `new_order_placed` is
 * missing, so these must exist. Editable in Admin › Email templates /
 * Notifications.
 */
class TemplatesSeeder extends Seeder
{
    use WritesDocs;

    public function run(): void
    {
        $orderBody = <<<'HTML'
<p>Hi {username},</p>
<p>Thank you for your order <strong>#{orderid}</strong> on {date}.</p>
{productdetails}
<table cellpadding="4">
<tr><td>Subtotal</td><td>{subtotal}</td></tr>
<tr><td>Discount {coupon}</td><td>{discountamount}</td></tr>
<tr><td>Special discount</td><td>{specialdiscountamount}</td></tr>
{taxdetails}
<tr><td>Delivery</td><td>{shippingcharge}</td></tr>
<tr><td>Tip</td><td>{tipamount}</td></tr>
<tr><td><strong>Total</strong></td><td><strong>{totalAmount}</strong></td></tr>
</table>
<p>Payment: {paymentmethod}<br>Deliver to: {address}</p>
HTML;

        $emails = [
            'new_order_placed' => 'Your order #{orderid} has been placed',
            'new_ondemand_book' => 'Your booking #{orderid} has been placed',
            'new_car_book' => 'Your ride #{orderid} has been booked',
        ];
        foreach ($emails as $type => $subject) {
            $this->doc('email_templates', "tpl_$type", ['type' => $type, 'subject' => $subject, 'message' => $orderBody]);
        }

        $notifications = [
            'order_placed' => ['New order', 'You have received a new order.'],
            'schedule_order' => ['Scheduled order', 'A new scheduled order was placed.'],
            'dinein_placed' => ['New table booking', 'A new table booking request was placed.'],
            'dinein_accepted' => ['Booking confirmed', 'Your table booking has been accepted.'],
            'booking_placed' => ['New booking', 'A new service booking was placed.'],
            'service_cancelled' => ['Booking cancelled', 'A service booking was cancelled.'],
            'rental_booked' => ['New rental', 'A new rental was booked.'],
            'restaurant_accepted' => ['Order accepted', 'Your order has been accepted by the restaurant.'],
            'restaurant_rejected' => ['Order rejected', 'Sorry, your order was rejected.'],
            'store_accepted' => ['Order accepted', 'Your order has been accepted by the store.'],
            'store_completed' => ['Order delivered', 'Your order has been delivered.'],
            'store_intransit' => ['Order on the way', 'Your order is on the way.'],
            'driver_accepted' => ['Driver assigned', 'A driver has accepted your order.'],
            'driver_completed' => ['Order delivered', 'Your order has been delivered.'],
            'takeaway_completed' => ['Order ready', 'Your takeaway order is complete.'],
        ];
        foreach ($notifications as $type => [$subject, $message]) {
            $this->doc('dynamic_notification', "ntf_$type", ['type' => $type, 'subject' => $subject, 'message' => $message]);
        }
    }
}
