<?php

namespace notify_events\modules\wordpress\tags;

use WP_User;

/**
 * Class User
 * @package notify_events\modules\wordpress\tags
 */
class User
{
    /**
     * @param string $prefix
     * @return string[]
     */
    public static function labels($prefix = 'user-')
    {
        return [
            $prefix . 'id'         => __('User ID', WPNE),
            $prefix . 'login'      => __('User Login', WPNE),
            $prefix . 'email'      => __('User E-Mail', WPNE),
            $prefix . 'nickname'   => __('User NickName', WPNE),
            $prefix . 'first_name' => __('User First Name', WPNE),
            $prefix . 'last_name'  => __('User Last Name', WPNE),
            $prefix . 'url'        => __('User URL', WPNE),
        ];
    }

    /**
     * @param WP_User $user
     * @param string  $prefix
     * @return array
     */
    public static function values($user, $prefix = 'user-')
    {
        return [
            $prefix . 'id'         => $user->ID,
            $prefix . 'login'      => $user->user_login,
            $prefix . 'email'      => $user->user_email,
            $prefix . 'nickname'   => $user->nickname,
            $prefix . 'first_name' => $user->user_firstname,
            $prefix . 'last_name'  => $user->user_lastname,
            $prefix . 'url'        => $user->user_url,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function preview($prefix = 'user-')
    {
        return [
            $prefix . 'id'         => 1,
            $prefix . 'login'      => __('example', WPNE),
            $prefix . 'email'      => __('mail@example.com', WPNE),
            $prefix . 'nickname'   => __('Example', WPNE),
            $prefix . 'first_name' => __('John', WPNE),
            $prefix . 'last_name'  => __('Smith', WPNE),
            $prefix . 'url'        => __('http://example.com', WPNE),
        ];
    }
}