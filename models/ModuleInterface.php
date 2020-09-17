<?php

namespace notify_events\models;

/**
 * Interface ModuleInterface
 * @package notify_events\models
 */
interface ModuleInterface
{
    /**
     * @return string
     */
    public static function module_plugin();

    /**
     * @return string
     */
    public static function module_name();

    /**
     * @return string
     */
    public static function module_title();

    /**
     * @return int
     */
    public static function module_order();

    /**
     * @return array[]
     */
    public function event_list();
}