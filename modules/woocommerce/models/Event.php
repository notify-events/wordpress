<?php

namespace notify_events\modules\woocommerce\models;

/**
 * Class Event
 *
 * @package notify_events\modules\woocommerce\models
 */
abstract class Event extends \notify_events\models\Event
{
    /**
     * @inheritDoc
     */
    public static function module_name()
    {
        return WooCommerce::module_name();
    }
}