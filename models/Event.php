<?php

namespace notify_events\models;

use ErrorException;
use notify_events\helpers\Badge;
use notify_events\modules\wordpress\tags\Common;
use notify_events\php\Message;
use WP_Post;

/**
 * Class Event
 * @package notify_events\models
 *
 * @property int     $id
 * @property boolean $enabled
 * @property string  $title
 * @property string  $event_class
 * @property int     $channel_id
 * @property string  $subject
 * @property string  $message
 * @property string  $priority
 */
abstract class Event extends PostModel implements EventInterface
{
    /**
     * @inheritDoc
     */
    public static function post_type()
    {
        return 'wpne_event';
    }

    /**
     * @return bool
     */
    public static function default_view()
    {
        return true;
    }

    /**
     * @return Module
     */
    public static function module()
    {
        return Core::instance()->module_get(static::module_name());
    }

    /**
     * @inheritDoc
     */
    public static function fields()
    {
        return array_merge(parent::fields(), [
            'event_class',
            'enabled',
            'channel_id',
            'subject',
            'message',
            'priority',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function field_assign()
    {
        return array_merge(parent::field_assign(), [
            'message' => 'post_content',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function rules()
    {
        return array_merge(parent::rules(), [
            'enabled' => [
                ['boolean'],
            ],
            'channel_id' => [
                ['required'],
                ['each', 'rules' => [
                    ['int'],
                    ['exists', 'model' => Channel::class],
                ]],
            ],
            'subject' => [
                ['required'],
                ['wp_unslash'],
                ['strip_tags'],
                ['trim'],
                ['string', 'max' => 255],
                ['template'],
            ],
            'message' => [
                ['required'],
                ['wp_unslash'],
                ['strip_tags', '<b><i><a>'],
                ['trim'],
                ['string', 'max' => 4096],
                ['template'],
            ],
            'priority' => [
                ['required'],
                ['in', 'range' => [
                    Message::PRIORITY_HIGHEST,
                    Message::PRIORITY_HIGH,
                    Message::PRIORITY_NORMAL,
                    Message::PRIORITY_LOW,
                    Message::PRIORITY_LOWEST,
                ]],
            ],
        ]);
    }

    /**
     * @param WP_Post $post
     * @return static
     */
    public static function instantiate($post)
    {
        $event_class = get_post_meta($post->ID, '_wpne_event_class', true);

        if (class_exists($event_class)) {
            return new $event_class($post);
        } else {
            return null;
        }
    }

    /**
     * @param array $tags
     * @param array $args
     * @throws ErrorException
     */
    protected static function execute($tags, $args = [])
    {
        $defaults = [
            'meta_query' => [
                [
                    'key'   => '_wpne_event_class',
                    'value' => static::class,
                ],
                [
                    'key'   => '_wpne_enabled',
                    'value' => 1,
                ],
            ],
        ];

        $args = array_merge_recursive($defaults, $args);

        file_put_contents('wc_search.json', json_encode($args, 320) . PHP_EOL . PHP_EOL, FILE_APPEND);

        $events = parent::find($args);

        foreach ($events as $event) {
            if (!$event->channel_id) {
                continue;
            }

            $subject  = $event->prepare_template($event->subject, $tags);
            $message  = $event->prepare_template($event->message, $tags);
            $message  = nl2br($message);
            $priority = $event->priority;

            $msg = new Message($message, $subject, $priority);

            foreach ($event->get_channels() as $channel) {
                $msg->send($channel->token);
            }
        }
    }

    /**
     * Event constructor.
     * @param WP_Post|null $post
     */
    public function __construct($post = null)
    {
        $this->title       = $this::module()->module_title() . ' / ' . static::event_title();
        $this->enabled     = true;
        $this->event_class = static::class;
        $this->priority    = Message::PRIORITY_NORMAL;

        parent::__construct($post);
    }

    /**
     * @return string[]
     */
    public function tag_labels()
    {
        return [
            __('Common', WPNE) => Common::labels(),
        ];
    }

    /**
     * @return string[]
     */
    public function tag_preview()
    {
        return Common::preview();
    }

    /**
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function preview(&$subject, &$message)
    {
        if (!$this->validate()) {
            return false;
        }

        $tags = $this->tag_preview();

        $subject = $this->prepare_template($this->subject, $tags);
        $message = $this->prepare_template($this->message, $tags);
        $message = nl2br($message);

        return true;
    }

    /**
     * @param string   $template
     * @param string[] $tags
     * @return string
     */
    protected function prepare_template($template, $tags)
    {
        if (preg_match_all('#\[([a-z0-9-]+)\]#i', $template, $matches) === false) {
            return $template;
        }

        $used_tags = array_unique($matches[1]);

        foreach ($used_tags as $tag) {
            $template = str_replace('[' . $tag . ']', $tags[$tag], $template);
        }

        return $template;
    }

    /**
     * @return Channel[]
     */
    public function get_channels()
    {
        return Channel::find(['post__in' => (array)$this->channel_id]);
    }

    /**
     * @return array
     */
    public function priority_labels()
    {
        return [
            Message::PRIORITY_HIGHEST => __('Highest', WPNE),
            Message::PRIORITY_HIGH    => __('High', WPNE),
            Message::PRIORITY_NORMAL  => __('Normal', WPNE),
            Message::PRIORITY_LOW     => __('Low', WPNE),
            Message::PRIORITY_LOWEST  => __('Lowest', WPNE),
        ];
    }

    /**
     * @return array
     */
    public function priority_badges()
    {
        $labels = $this->priority_labels();

        return [
            Message::PRIORITY_HIGHEST => Badge::danger($labels[Message::PRIORITY_HIGHEST]),
            Message::PRIORITY_HIGH    => Badge::warning($labels[Message::PRIORITY_HIGH]),
            Message::PRIORITY_NORMAL  => Badge::info($labels[Message::PRIORITY_NORMAL]),
            Message::PRIORITY_LOW     => Badge::primary($labels[Message::PRIORITY_LOW]),
            Message::PRIORITY_LOWEST  => Badge::primary($labels[Message::PRIORITY_LOWEST]),
        ];
    }

    /**
     * @param string $attribute
     * @param string $value
     * @param array  $params
     * @return mixed
     */
    protected function rule_template($attribute, $value, $params)
    {
        if (preg_match_all('#\[([a-z0-9-]*)\]#i', $value, $matches) !== false) {
            $tags = static::tag_labels();
            $tags = call_user_func_array('array_merge', $tags);
            $tags = array_keys($tags);

            $used_tags    = array_unique($matches[1]);
            $invalid_tags = array_diff($used_tags, $tags);

            if (!empty($invalid_tags)) {
                foreach ($invalid_tags as $invalid_tag) {
                    $message = sprintf(array_key_exists('message_tag', $params) ? $params['message_tag'] : __('Invalid tag: [%s]', WPNE), $invalid_tag);

                    $this->add_error($attribute, $message);
                }
            }
        } else {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Invalid value', WPNE);

            $this->add_error($attribute, $message);
        }

        return $value;
    }
}
