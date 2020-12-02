<?php

namespace notify_events\modules\wordpress\tags;

/**
 * Class Common
 * @package notify_events\modules\wordpress\tags
 */
class Common
{
    /**
     * @return string[]
     */
    public static function labels()
    {
        return [
            'site-name' => __('Site Name', WPNE),
            'site-url'  => __('Site URL', WPNE),
            'home-url'  => __('Home URL', WPNE),
        ];
    }

    /**
     * @return string[]
     */
    public static function values()
    {
        return [
            'site-name' => get_bloginfo('name'),
            'site-url'  => site_url(),
            'home-url'  => home_url(),
        ];
    }

    /**
     * @return string[]
     */
    public static function preview()
    {
        return [
            'site-name' => get_bloginfo('name'),
            'site-url'  => site_url(),
            'home-url'  => home_url(),
        ];
    }
}