<?php

namespace notify_events\helpers;

/**
 * Class Badge
 * @package notify_events\models
 */
class Badge
{
    /**
     * @param string $type
     * @param string $message
     * @return string
     */
    protected static function badge($type, $message)
    {
        $attrs = [
            'class' => sprintf('wpne-badge wpne-badge-%s', $type),
        ];

        return Html::tag('span', esc_html($message), $attrs);
    }

    /**
     * @param string $message
     * @return string
     */
    public static function danger($message)
    {
        return static::badge('danger', $message);
    }

    /**
     * @param string $message
     * @return string
     */
    public static function warning($message)
    {
        return static::badge('warning', $message);
    }

    /**
     * @param string $message
     * @return string
     */
    public static function info($message)
    {
        return static::badge('info', $message);
    }

    /**
     * @param string $message
     * @return string
     */
    public static function success($message)
    {
        return static::badge('success', $message);
    }

    /**
     * @param string $message
     * @return string
     */
    public static function primary($message)
    {
        return static::badge('primary', $message);
    }
}