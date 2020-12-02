<?php

namespace notify_events\modules\ninja_forms\models;

/**
 * Class Event
 * @package notify_events\modules\ninja_forms\models
 */
abstract class Event extends \notify_events\models\Event
{
    /**
     * @inheritDoc
     */
    public static function module_name()
    {
        return NinjaForms::module_name();
    }
}