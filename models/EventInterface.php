<?php

namespace notify_events\models;

/**
 * Interface EventInterface
 * @package notify_events\models
 */
interface EventInterface
{
    /**
     * @return string
     */
    public static function module_name();

    /**
     * @return string
     */
    public static function event_title();

    public static function register();
}