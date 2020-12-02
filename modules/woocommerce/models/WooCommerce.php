<?php

namespace notify_events\modules\woocommerce\models;

use notify_events\models\Module;
use notify_events\modules\woocommerce\models\events\order\NewOrder;
use notify_events\modules\woocommerce\models\events\order\OrderStatusChange;
use notify_events\modules\woocommerce\models\events\product\LowStock;
use notify_events\modules\woocommerce\models\events\product\NoStock;
use notify_events\modules\woocommerce\models\events\product\OnBackorder;

/**
 * Class WooCommerce
 * @package notify_events\modules\woocommerce\models
 */
class WooCommerce extends Module
{
    /**
     * @inheritDoc
     */
    public static function module_plugin()
    {
        return WPNE;
    }

    /**
     * @inheritDoc
     */
    public static function module_name()
    {
        return 'woocommerce';
    }

    /**
     * @inheritDoc
     */
    public static function module_title()
    {
        return __('WooCommerce', WPNE);
    }

    /**
     * @inheritDoc
     */
    public static function module_order()
    {
        return 200;
    }

    /**
     * @inheritDoc
     */
    public function event_list()
    {
        return [
            __('Order', WPNE) => [
                NewOrder::class,
                OrderStatusChange::class,
            ],
            __('Product', WPNE) => [
                LowStock::class,
                NoStock::class,
                OnBackorder::class,
            ],
        ];
    }
}
