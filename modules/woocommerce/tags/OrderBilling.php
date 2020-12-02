<?php

namespace notify_events\modules\woocommerce\tags;

use WC_Order;

/**
 * Class OrderBilling
 *
 * @package notify_events\modules\woocommerce\tags
 */
class OrderBilling
{
    /**
     * @return array
     */
    public static function labels()
    {
        return [
            'billing-first-name'          => __('First Name', WPNE),
            'billing-last-name'           => __('Last Name', WPNE),
            'billing-formatted-full-name' => __('Formatted Full Name', WPNE),
            'billing-company'             => __('Company', WPNE),
            'billing-formatted-address'   => __('Formatted Address', WPNE),
            'billing-address-1'           => __('Address 1', WPNE),
            'billing-address-2'           => __('Address 2', WPNE),
            'billing-city'                => __('City', WPNE),
            'billing-state'               => __('State', WPNE),
            'billing-postcode'            => __('Postcode', WPNE),
            'billing-country'             => __('Country', WPNE),
            'billing-email'               => __('E-Mail', WPNE),
            'billing-phone'               => __('Phone', WPNE),
        ];
    }

    /**
     * @param WC_Order $order
     * @return array
     */
    public static function values($order)
    {
        return [
            'billing-first-name'          => $order->get_billing_first_name(),
            'billing-last-name'           => $order->get_billing_last_name(),
            'billing-formatted-full-name' => $order->get_formatted_billing_full_name(),
            'billing-company'             => $order->get_billing_company(),
            'billing-formatted-address'   => $order->get_formatted_billing_address(),
            'billing-address-1'           => $order->get_billing_address_1(),
            'billing-address-2'           => $order->get_billing_address_2(),
            'billing-city'                => $order->get_billing_city(),
            'billing-state'               => $order->get_billing_state(),
            'billing-postcode'            => $order->get_billing_postcode(),
            'billing-country'             => $order->get_billing_country(),
            'billing-email'               => $order->get_billing_email(),
            'billing-phone'               => $order->get_billing_phone(),
        ];
    }

    /**
     * @return array
     */
    public static function preview()
    {
        return [
            'billing-first-name'          => 'John',
            'billing-last-name'           => 'Smith',
            'billing-formatted-full-name' => 'John Smith',
            'billing-company'             => '',
            'billing-formatted-address'   => 'John Smith<br/>Baker Street 221B<br/>London<br/>NW1',
            'billing-address-1'           => 'Baker Street 221B',
            'billing-address-2'           => '',
            'billing-city'                => 'London',
            'billing-state'               => '',
            'billing-postcode'            => 'NW1',
            'billing-country'             => 'GB',
            'billing-email'               => 'mail@example.com',
            'billing-phone'               => '+1234567890',
        ];
    }
}