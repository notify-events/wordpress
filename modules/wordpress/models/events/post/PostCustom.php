<?php

namespace notify_events\modules\wordpress\models\events\post;

use ErrorException;
use notify_events\modules\wordpress\models\Event;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\Post;
use notify_events\modules\wordpress\tags\User;
use WP_Post;

/**
 * Class PostCustom
 * @package notify_events\modules\wordpress\models\events\post
 *
 * @property int    $id
 * @property string $title
 * @property int    $channel_id
 * @property string $post_type
 * @property string $post_status
 */
class PostCustom extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Post Custom Event', WPNE);
    }

    public static function register()
    {
        add_action('transition_post_status', static::class . '::handle', 10, 3);
    }

    /**
     * @param string $new_status
     * @param string $old_status
     * @param WP_Post $post
     * @throws ErrorException
     */
    public static function handle($new_status, $old_status, $post)
    {
        if ($new_status == $old_status) {
            return;
        }

        $author = get_userdata($post->post_author);
        $editor = get_userdata(get_post_meta($post->ID, '_edit_last', true));

        $tags = array_merge(
            Post::values($post),
            User::values($author, 'author-'),
            User::values($editor, 'editor-'),
            Common::values()
        );

        static::execute($tags, [
            'meta_query' => [
                [
                    'key'     => '_wpne_post_type',
                    'value'   => serialize($post->post_type),
                    'compare' => 'LIKE'
                ],
                [
                    'key'     => '_wpne_post_status',
                    'value'   => serialize($post->post_status),
                    'compare' => 'LIKE'
                ],
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function default_view()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function fields()
    {
        return array_merge(parent::fields(), [
            'post_type',
            'post_status',
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function rules()
    {
        return array_merge(parent::rules(), [
            'post_type' => [
                ['required'],
                ['each', 'rules' => [
                    ['string'],
                    ['in', 'range' => array_keys(self::post_type_list())],
                ]],
            ],
            'post_status' => [
                ['required'],
                ['each', 'rules' => [
                    ['string'],
                    ['in', 'range' => array_keys(self::post_status_list())],
                ]],
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('Post', WPNE)        => Post::labels(),
            __('Author', WPNE)      => User::labels('author-'),
            __('Last Editor', WPNE) => User::labels('editor-'),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            Post::preview(),
            User::preview('author-'),
            User::preview('editor-'),
            parent::tag_preview()
        );
    }

    /**
     * @return string[]|array
     */
    public static function post_type_list()
    {
        return get_post_types([
            'public' => true,
        ]);
    }

    /**
     * @return string[]|array
     */
    public static function post_status_list()
    {
        return get_post_stati([
            'internal' => false,
        ]);
    }
}
