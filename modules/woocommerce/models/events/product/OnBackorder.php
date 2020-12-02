<?php

namespace notify_events\modules\woocommerce\models\events\product;

use ErrorException;
use notify_events\modules\woocommerce\models\Event;
use notify_events\modules\woocommerce\tags\Order;
use notify_events\modules\woocommerce\tags\OrderBilling;
use notify_events\modules\woocommerce\tags\OrderShipping;
use notify_events\modules\woocommerce\tags\Product;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\User;

/**
 * Class OnBackorder
 *
 * @package notify_events\modules\woocommerce\models\events\product
 */
class OnBackorder extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('On Backorder', WPNE);
    }

    public static function register()
    {
        add_action('woocommerce_product_on_backorder', static::class . '::handle');
    }

    /**
     * @param array $array
     * @throws ErrorException
     */
    public static function handle(array $array)
    {
        $order    = wc_get_order($array['order_id']);
        $customer = $order->get_user();

        $tags = array_merge(
            Product::values($array['product'], $array['quantity']),
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
        $this->subject = __('[[site-name]] Product [product-name] is on backorder', WPNE);
        $this->message = __('Product "<a href="[product-url]">[product-name]</a>" on "[site-name]" is on backorder ([product-quantity] items more required)', WPNE);

        parent::__construct($post);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('Product', WPNE)  => Product::labels(true),
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
            Product::preview(true),
            Order::preview(),
            OrderBilling::preview(),
            OrderShipping::preview(),
            User::preview('customer-'),
            parent::tag_preview()
        );
    }
}