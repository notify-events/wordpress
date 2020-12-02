<?php

namespace notify_events\modules\wordpress\models\events\other;

use ErrorException;
use notify_events\modules\wordpress\models\Event;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\EMail;

/**
 * Class EMailSend
 * @package notify_events\modules\wordpress\models\events\other
 */
class EMailSend extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('E-Mail Send', WPNE);
    }

    public static function register()
    {
        add_action('wp_mail', static::class . '::handle', 10, 1);
    }

    /**
     * @param array $email_args
     * @throws ErrorException
     */
    public static function handle($email_args)
    {
        $tags = array_merge(
            EMail::values($email_args),
            Common::values()
        );

        static::execute($tags);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('Email "[email-subject]" sent to [email-to] on [site-name]', WPNE);
        $this->message = __('[email-message]', WPNE);

        parent::__construct($post);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('E-Mail', WPNE) => EMail::labels(),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            EMail::preview(),
            parent::tag_preview()
        );
    }
}