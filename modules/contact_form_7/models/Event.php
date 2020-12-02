<?php

namespace notify_events\modules\contact_form_7\models;

/**
 * Class Event
 * @package notify_events\modules\contact_form_7\models
 */
abstract class Event extends \notify_events\models\Event
{
    /**
     * @inheritDoc
     */
    public static function module_name()
    {
        return ContactForm7::module_name();
    }
}