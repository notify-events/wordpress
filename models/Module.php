<?php

namespace notify_events\models;

/**
 * Class Module
 * @package notify_events
 */
abstract class Module implements ModuleInterface
{
    public static function register()
    {
        $module = new static();

        Core::instance()->module_register($module);
    }

    /**
     * Module constructor.
     */
    public function __construct()
    {
        foreach ($this->event_list() as $group) {
            /** @var Event $event */
            foreach ($group as $event) {
                $event::register();
            }
        }
    }
}