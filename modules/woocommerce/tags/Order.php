<?php

namespace notify_events\modules\woocommerce\tags;

use WC_Order;

/**
 * Class Order
 *
 * @package notify_events\modules\woocommerce\tags
 */
class Order
{
    /**
     * @return array
     */
    public static function labels()
    {
        return [
            'order-id'             => __('ID', WPNE),
            'order-url'            => __('URL', WPNE),
            'order-edit-url'       => __('Edit URL', WPNE),
            'order-status'         => __('Status', WPNE),
            'order-currency'       => __('Currency', WPNE),
            'order-discount-total' => __('Discount Total', WPNE),
            'order-discount-tax'   => __('Discount Tax', WPNE),
            'order-shipping-total' => __('Shipping Total', WPNE),
            'order-shipping-tax'   => __('Shipping Tax', WPNE),
            'order-cart-tax'       => __('Cart Tax', WPNE),
            'order-total'          => __('Total', WPNE),
            'order-total-tax'      => __('Total Tax', WPNE),
            'order-customer-note'  => __('Customer Note', WPNE),
            'order-payment-method' => __('Payment Method', WPNE),
        ];
    }

    /**
     * @param WC_Order $order
     * @return array
     */
    public static function values($order)
    {
        $statuses = wc_get_order_statuses();
        $gateway  = wc_get_payment_gateway_by_order($order);

        return [
            'order-id'             => $order->get_id(),
            'order-url'            => $order->get_view_order_url(),
            'order-edit-url'       => $order->get_edit_order_url(),
            'order-status'         => $statuses['wc-' . $order->get_status()],
            'order-currency'       => $order->get_currency(),
            'order-discount-total' => $order->get_discount_total(),
            'order-discount-tax'   => $order->get_discount_tax(),
            'order-shopping-total' => $order->get_shipping_total(),
            'order-shipping-tax'   => $order->get_shipping_tax(),
            'order-cart-tax'       => $order->get_cart_tax(),
            'order-total'          => $order->get_total(),
            'order-total-tax'      => $order->get_total_tax(),
            'order-customer-note'  => $order->get_customer_note(),
            'order-payment-method' => $gateway ? $gateway->title : '',
        ];
    }

    /**
     * @return array
     */
    public static function preview()
    {
        $statuses = wc_get_order_statuses();

        return [
            'order-id'             => 1,
            'order-url'            => '/?page_id=1&view-order=1',
            'order-edit-url'       => '/wp-admin/post.php?post=1&action=edit',
            'order-status'         => $statuses['wc-processing'],
            'order-currency'       => 'USD',
            'order-discount-total' => '0',
            'order-discount-tax'   => '0',
            'order-shopping-total' => '0.00',
            'order-shipping-tax'   => '0',
            'order-cart-tax'       => '0',
            'order-total'          => '100.00',
            'order-total-tax'      => '0',
            'order-customer-note'  => '',
            'order-payment-method' => 'Cash on delivery',
        ];
    }
}