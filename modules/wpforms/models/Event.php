<?php

namespace notify_events\modules\wpforms\models;

/**
 * Class Event
 * @package notify_events\modules\wpforms\models
 */
abstract class Event extends \notify_events\models\Event
{
    /**
     * @inheritDoc
     */
    public static function module_name()
    {
        return WPForms::module_name();
    }
}