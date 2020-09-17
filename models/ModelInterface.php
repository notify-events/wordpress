<?php

namespace notify_events\models;

/**
 * Interface ModelInterface
 * @package notify_events\models
 */
interface ModelInterface
{
    /**
     * @return array
     */
    public static function fields();

    /**
     * @return array
     */
    public static function rules();
}