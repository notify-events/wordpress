<?php

namespace notify_events\modules\woocommerce\models\events\order;

use ErrorException;
use notify_events\modules\woocommerce\models\Event;
use notify_events\modules\woocommerce\tags\Order;
use notify_events\modules\woocommerce\tags\OrderBilling;
use notify_events\modules\woocommerce\tags\OrderShipping;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\User;
use WC_Order;

/**
 * Class NewOrder
 * @package notify_events\modules\woocommerce\models\events\order
 */
class NewOrder extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('New Order', WPNE);
    }

    public static function register()
    {
        add_action('woocommerce_checkout_order_processed', static::class . '::handle', 10, 3);
    }

    /**
     * @param int      $order_id
     * @param array    $posted_data
     * @param WC_Order $order
     * @throws ErrorException
     */
    public static function handle($order_id, $posted_data, $order)
    {
        $customer = $order->get_user();

        $tags = array_merge(
            Order::values($order),
            OrderBilling::values($order),
            OrderShipping::values($order),
            User::values($customer, 'customer-'),
            Common::values()
        );

        static::execute($tags);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('[[site-name]]: New order #[order-id]', WPNE);
        $this->message = __("You have a new order <a href=\"[order-edit-url]\">#[order-id]</a> on \"[site-name]\"\nTotal: [order-total] [order-currency]\nPayment method: [order-payment-method]", WPNE);

        parent::__construct($post);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('Order', WPNE)    => Order::labels(),
            __('Billing', WPNE)  => OrderBilling::labels(),
            __('Shipping', WPNE) => OrderShipping::labels(),
            __('Customer', WPNE) => User::labels('customer-'),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            Order::preview(),
            OrderBilling::preview(),
            OrderShipping::preview(),
            User::preview('customer-'),
            parent::tag_preview()
        );
    }
}