<?php

namespace notify_events\models;

/**
 * Class Alert
 * @package notify_events\models
 */
class Alert
{
    /**
     *
     */
    public static function display()
    {
        if (!array_key_exists('_wpne_alert', $_SESSION)) {
            return;
        }

        $alert = $_SESSION['_wpne_alert'];

        unset($_SESSION['_wpne_alert']);

        echo sprintf('<div class="notice notice-%s is-dismissible"><p>%s</p></div>', esc_attr($alert['type']), esc_html($alert['message']));
    }

    /**
     * @param string $message
     */
    public static function error($message)
    {
        $_SESSION['_wpne_alert'] = [
            'type'    => 'error',
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     */
    public static function warning($message)
    {
        $_SESSION['_wpne_alert'] = [
            'type'    => 'warning',
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     */
    public static function success($message)
    {
        $_SESSION['_wpne_alert'] = [
            'type'    => 'success',
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     */
    public static function info($message)
    {
        $_SESSION['_wpne_alert'] = [
            'type'    => 'info',
            'message' => $message,
        ];
    }
}