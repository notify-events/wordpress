<?php

namespace notify_events\helpers;

/**
 * Class Url
 * @package notify_events\helpers
 */
class Url
{
    /**
     * @param array $args
     * @return string
     */
    public static function to($args = [])
    {
        return add_query_arg($args, menu_page_url(WPNE, false));
    }
}