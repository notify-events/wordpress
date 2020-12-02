<?php

namespace notify_events\modules\wpforms\models;

use notify_events\models\Module;
use notify_events\modules\wpforms\models\events\FormSubmit;

/**
 * Class WPForms
 * @package notify_events\modules\wpforms\models
 */
class WPForms extends Module
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
        return 'wpforms';
    }

    /**
     * @inheritDoc
     */
    public static function module_title()
    {
        return __('WPForms', WPNE);
    }

    /**
     * @inheritDoc
     */
    public static function module_order()
    {
        return 400;
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
