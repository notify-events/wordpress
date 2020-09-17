<?php

namespace notify_events\models;

/**
 * Class Channel
 * @package notify_events
 *
 * @property int    $id
 * @property string $title
 * @property string $token
 */
class Channel extends PostModel
{
    /**
     * @inheritDoc
     */
    public static function post_type()
    {
        return 'wpne_channel';
    }

    /**
     * @inheritDoc
     */
    public static function fields()
    {
        return array_merge(parent::fields(), [
            'token',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function field_assign()
    {
        return array_merge(parent::field_assign(), [
            'token' => 'post_content',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function rules()
    {
        return array_merge(parent::rules(), [
            'token' => [
                ['required'],
                ['strip_tags'],
                ['trim'],
                ['string', 'min' => 32, 'max' => 32],
                ['match', 'pattern' => '#^[a-z0-9_-]{32}$#i'],
            ],
        ]);
    }
}