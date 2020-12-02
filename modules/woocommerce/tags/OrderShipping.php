<?php

namespace notify_events\modules\woocommerce\tags;

use WC_Order;

/**
 * Class OrderShipping
 *
 * @package notify_events\modules\woocommerce\tags
 */
class OrderShipping
{
    /**
     * @return array
     */
    public static function labels()
    {
        return [
            'shipping-first-name'          => __('First Name', WPNE),
            'shipping-last-name'           => __('Last Name', WPNE),
            'shipping-formatted-full-name' => __('Formatted Full Name', WPNE),
            'shipping-company'             => __('Company', WPNE),
            'shipping-formatted-address'   => __('Formatted Address', WPNE),
            'shipping-address-1'           => __('Address 1', WPNE),
            'shipping-address-2'           => __('Address 2', WPNE),
            'shipping-city'                => __('City', WPNE),
            'shipping-state'               => __('State', WPNE),
            'shipping-postcode'            => __('Postcode', WPNE),
            'shipping-country'             => __('Country', WPNE),
            'shipping-address-map-url'     => __('Address Map URL', WPNE),
            'shipping-total'               => __('Total', WPNE),
            'shipping-tax'                 => __('Tax', WPNE),
        ];
    }

    /**
     * @param WC_Order $order
     * @return array
     */
    public static function values($order)
    {
        return [
            'shipping-first-name'          => $order->get_shipping_first_name(),
            'shipping-last-name'           => $order->get_shipping_last_name(),
            'shipping-formatted-full-name' => $order->get_formatted_shipping_full_name(),
            'shipping-company'             => $order->get_shipping_company(),
            'shipping-formatted-address'   => $order->get_formatted_shipping_address(),
            'shipping-address-1'           => $order->get_shipping_address_1(),
            'shipping-address-2'           => $order->get_shipping_address_2(),
            'shipping-city'                => $order->get_shipping_city(),
            'shipping-state'               => $order->get_shipping_state(),
            'shipping-postcode'            => $order->get_shipping_postcode(),
            'shipping-country'             => $order->get_shipping_country(),
            'shipping-address-map-url'     => $order->get_shipping_address_map_url(),
            'shipping-total'               => $order->get_shipping_total(),
            'shipping-tax'                 => $order->get_shipping_tax(),
        ];
    }

    /**
     * @return array
     */
    public static function preview()
    {
        return [
            'shipping-first-name'          => 'John',
            'shipping-last-name'           => 'Smith',
            'shipping-formatted-full-name' => 'John Smith',
            'shipping-company'             => '',
            'shipping-formatted-address'   => 'John Smith<br/>Baker Street 221B<br/>London<br/>NW1',
            'shipping-address-1'           => 'Baker Street 221B',
            'shipping-address-2'           => '',
            'shipping-city'                => 'London',
            'shipping-state'               => '',
            'shipping-postcode'            => 'NW1',
            'shipping-country'             => 'GB',
            'shipping-address-map-url'     => 'https://maps.google.com/maps?&q=Baker%20Street%20221B%2C%20London%2C%20NW1&z=16',
            'shipping-total'               => '0.00',
            'shipping-tax'                 => '0',
        ];
    }
}