<?php

namespace notify_events\modules\wordpress\models\events\user;

use ErrorException;
use notify_events\tags\Common;
use notify_events\tags\User;
use notify_events\modules\wordpress\models\Event;

/**
 * Class UserRegistered
 * @package notify_events\modules\wordpress\models\events\user
 */
class UserRegistered extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('User Registered', WPNE);
    }

    public static function register()
    {
        add_action('user_register', static::class . '::handle', 1000);
    }

    /**
     * @param int $user_id
     * @throws ErrorException
     */
    public static function handle($user_id)
    {
        $user = get_userdata($user_id);

        $tags = array_merge(
            User::values($user),
            Common::values()
        );

        static::execute($tags);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('User [user-nickname] registered on [site-name]', WPNE);
        $this->message = __("User email <a href=\"mailto:[user-email]\">[user-email]</a>", WPNE);

        parent::__construct($post);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('User', WPNE) => User::labels(),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            User::preview(),
            parent::tag_preview()
        );
    }
}