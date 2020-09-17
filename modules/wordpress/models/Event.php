<?php

namespace notify_events\modules\wordpress\models;

/**
 * Class Event
 * @package notify_events\modules\wordpress\models
 */
abstract class Event extends \notify_events\models\Event
{
    /**
     * @inheritDoc
     */
    public static function module_name()
    {
        return WordPress::module_name();
    }
}