<?php

namespace notify_events\modules\contact_form_7\models;

use notify_events\models\Module;
use notify_events\modules\contact_form_7\models\events\FormSubmit;

/**
 * Class ContactForm7
 * @package notify_events\modules\contact_form_7\models
 */
class ContactForm7 extends Module
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
        return 'contact_form_7';
    }

    /**
     * @inheritDoc
     */
    public static function module_title()
    {
        return __('Contact Form 7', WPNE);
    }

    /**
     * @inheritDoc
     */
    public static function module_order()
    {
        return 300;
    }

    /**
     * @inheritDoc
     */
    public function event_list()
    {
        return [
            __('Form', WPNE) => [
                FormSubmit::class,
            ],
        ];
    }
}
