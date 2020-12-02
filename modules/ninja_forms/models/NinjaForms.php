<?php

namespace notify_events\modules\ninja_forms\models;

use notify_events\models\Module;
use notify_events\modules\ninja_forms\models\events\FormSubmit;

/**
 * Class NinjaForms
 * @package notify_events\modules\ninja_forms\models
 */
class NinjaForms extends Module
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
        return 'ninja_forms';
    }

    /**
     * @inheritDoc
     */
    public static function module_title()
    {
        return __('Ninja Forms', WPNE);
    }

    /**
     * @inheritDoc
     */
    public static function module_order()
    {
        return 500;
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
