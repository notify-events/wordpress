<?php

namespace notify_events\modules\wordpress\tags;

/**
 * Class EMail
 * @package notify_events\modules\wordpress\tags
 */
class EMail
{
    /**
     * @return string[]
     */
    public static function labels()
    {
        return [
            'email-to'      => __('E-Mail TO', WPNE),
            'email-subject' => __('E-Mail Subject', WPNE),
            'email-message' => __('E-Mail Message', WPNE),
        ];
    }

    /**
     * @param array $email_args
     * @return string[]
     */
    public static function values($email_args)
    {
        return [
            'email-to'      => $email_args['to'],
            'email-subject' => $email_args['subject'],
            'email-message' => $email_args['message'],
        ];
    }

    /**
     * @return string[]
     */
    public static function preview()
    {
        return [
            'email-to'      => __('mail@example.com', WPNE),
            'email-subject' => __('New message', WPNE),
            'email-message' => __('Hello Example', WPNE),
        ];
    }
}