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
 * Class OrderStatusChange
 * @package notify_events\modules\woocommerce\models\events\order *
 *
 * @property int    $id
 * @property string $title
 * @property int    $channel_id
 * @property int    $statuses
 */
class OrderStatusChange extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Order Status Change', WPNE);
    }

    public static function register()
    {
        add_action('woocommerce_order_status_changed', static::class . '::handle', 10, 4);
    }

    /**
     * @param int      $order_id
     * @param string   $status_from
     * @param string   $status_to
     * @param WC_Order $order
     * @throws ErrorException
     */
    public static function handle($order_id, $status_from, $status_to, $order)
    {
        if ($status_to == $status_from) {
            return;
        }

        $customer = $order->get_user();

        $tags = array_merge(
            Order::values($order),
            OrderBilling::values($order),
            OrderShipping::values($order),
            User::values($customer, 'customer-'),
            Common::values()
        );

        static::execute($tags, [
            'meta_query' => [
                [
                    'key'     => '_wpne_statuses',
                    'value'   => serialize('wc-' . $status_to),
                    'compare' => 'LIKE',
                ],
            ],
        ]);
    }

    /**
     * @return bool
     */
    public static function default_view()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function fields()
    {
        return array_merge(parent::fields(), [
            'statuses',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function rules()
    {
        return array_merge(parent::rules(), [
            'statuses' => [
                ['required'],
                ['each', 'rules' => [
                    ['string'],
                ]],
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('[[site-name]]: Status for order #[order-id] was changed', WPNE);
        $this->message = __('Status for order <a href="[order-edit-url]">#[order-id]</a> on "[site-name]" has been changed to [order-status]', WPNE);

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

    /**
     * @return array
     */
    public static function status_list()
    {
        return wc_get_order_statuses();
    }
}