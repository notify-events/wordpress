<?php

namespace notify_events\modules\wordpress\models;

use notify_events\models\Module;
use notify_events\modules\wordpress\models\events\comment\CommentAdded;
use notify_events\modules\wordpress\models\events\other\EMailSend;
use notify_events\modules\wordpress\models\events\post\PostAdded;
use notify_events\modules\wordpress\models\events\post\PostApproved;
use notify_events\modules\wordpress\models\events\post\PostCustom;
use notify_events\modules\wordpress\models\events\post\PostPending;
use notify_events\modules\wordpress\models\events\post\PostPublished;
use notify_events\modules\wordpress\models\events\user\UserLogin;
use notify_events\modules\wordpress\models\events\user\UserRegistered;

/**
 * Class WordPress
 * @package notify_events\modules\wordpress\models
 */
class WordPress extends Module
{
    /**
     * @inheritDoc
     */
    public static function module_plugin()
    {
        return WPNE;
    }

    /**
     * @inheritDoc
     */
    public static function module_name()
    {
        return 'wordpress';
    }

    /**
     * @inheritDoc
     */
    public static function module_title()
    {
        return __('WordPress', WPNE);
    }

    /**
     * @inheritDoc
     */
    public static function module_order()
    {
        return 100;
    }

    /**
     * @inheritDoc
     */
    public function event_list()
    {
        return [
            __('Post', WPNE) => [
                PostAdded::class,
                PostPublished::class,
                PostPending::class,
                PostApproved::class,
                PostCustom::class,
            ],
            __('Comment', WPNE) => [
                CommentAdded::class,
            ],
            __('User', WPNE) => [
                UserRegistered::class,
                UserLogin::class,
            ],
            __('Other', WPNE) => [
                EMailSend::class,
            ],
        ];
    }
}
