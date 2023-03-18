<?php

namespace notify_events\models;

/**
 * Class Alert
 * @package notify_events\models
 *
 * @property int    $id
 * @property string $type
 * @property string $title
 */
class Alert extends PostModel
{
    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';
    const TYPE_INFO = 'info';

    const TYPES = [
        self::TYPE_ERROR,
        self::TYPE_WARNING,
        self::TYPE_SUCCESS,
        self::TYPE_INFO,
    ];

    /**
     * @inheritDoc
     */
    public static function post_type()
    {
        return 'wpne_alert';
    }

    /**
     * @inheritDoc
     */
    public static function fields()
    {
        return array_merge(parent::fields(), [
            'type',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function field_assign()
    {
        return array_merge(parent::field_assign(), [
            'type' => 'post_content',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function rules()
    {
        return array_merge(parent::rules(), [
            'type' => [
                ['required'],
                ['strip_tags'],
                ['trim'],
                ['in', 'range' => self::TYPES],
            ],
        ]);
    }

    /**
     *
     */
    public static function display()
    {
        $alerts = self::find();

        foreach ($alerts as $alert) {
            echo sprintf('<div class="notice notice-%s is-dismissible"><p>%s</p></div>', esc_attr($alert->type), esc_html($alert->title));

            $alert->delete();
        }
    }

    /**
     * @param $type
     * @param $message
     * @return void
     */
    protected static function alert($type, $message)
    {
        $alert = new self();
        $alert->type  = $type;
        $alert->title = $message;

        $alert->save();
    }

    /**
     * @param string $message
     */
    public static function error($message)
    {
        self::alert(self::TYPE_ERROR, $message);
    }

    /**
     * @param string $message
     */
    public static function warning($message)
    {
        self::alert(self::TYPE_WARNING, $message);
    }

    /**
     * @param string $message
     */
    public static function success($message)
    {
        self::alert(self::TYPE_SUCCESS, $message);
    }

    /**
     * @param string $message
     */
    public static function info($message)
    {
        self::alert(self::TYPE_INFO, $message);
    }
}
