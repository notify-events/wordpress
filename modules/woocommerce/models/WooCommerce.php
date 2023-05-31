<?php

namespace notify_events\modules\woocommerce\models;

use notify_events\models\Module;
use notify_events\modules\woocommerce\models\events\order\NewOrder;
use notify_events\modules\woocommerce\models\events\order\OrderStatusChange;
use notify_events\modules\woocommerce\models\events\product\LowStock;
use notify_events\modules\woocommerce\models\events\product\NoStock;
use notify_events\modules\woocommerce\models\events\product\OnBackorder;
use notify_events\modules\woocommerce\models\events\product\ProductAdded;
use notify_events\modules\woocommerce\models\events\product\ProductApproved;
use notify_events\modules\woocommerce\models\events\product\ProductCustom;
use notify_events\modules\woocommerce\models\events\product\ProductPending;
use notify_events\modules\woocommerce\models\events\product\ProductPublished;

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
                ProductAdded::class,
                ProductPublished::class,
                ProductPending::class,
                ProductApproved::class,
                ProductCustom::class,
                OnBackorder::class,
            ],
            __('Stock', WPNE) => [
                LowStock::class,
                NoStock::class,
            ],
        ];
    }
}
