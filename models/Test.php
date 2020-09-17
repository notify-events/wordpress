<?php

namespace notify_events\models;

use ErrorException;
use notify_events\php\Message;

/**
 * Class Test
 * @package notify_events\models
 *
 * @property int    $channel_id
 * @property string $subject
 * @property string $message
 */
class Test extends Model
{
    /**
     * @inheritDoc
     */
    public static function fields()
    {
        return [
            'channel_id',
            'subject',
            'message',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function rules()
    {
        return [
            'channel_id' => [
                ['required'],
                ['int'],
                ['exists', 'model' => Channel::class],
            ],
            'subject' => [
                ['required'],
                ['strip_tags'],
                ['trim'],
                ['string', 'max' => 255],
            ],
            'message' => [
                ['required'],
                ['strip_tags', '<b><i><a>'],
                ['trim'],
                ['string', 'max' => 4096],
            ],
        ];
    }

    public function __construct($data = null)
    {
        $this->subject = __('Example subject', WPNE);
        $this->message = __('Example message with <b>formatting</b>', WPNE);

        parent::__construct($data);
    }

    /**
     * @return Channel|null
     */
    public function get_channel()
    {
        return Channel::findOne($this->channel_id);
    }

    /**
     * @return bool
     * @throws ErrorException
     */
    public function send()
    {
        if (!$this->validate()) {
            return false;
        }

        $message = nl2br($this->message);

        $msg = new Message($message, $this->subject);
        $msg->send($this->get_channel()->token);

        return true;
    }
}