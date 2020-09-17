<?php

namespace notify_events\modules\wordpress\models\events\post;

use ErrorException;
use notify_events\tags\Common;
use notify_events\tags\Post;
use notify_events\tags\User;
use notify_events\modules\wordpress\models\Event;
use WP_Post;

/**
 * Class PostAdded
 * @package notify_events\modules\wordpress\models\events\post
 */
class PostAdded extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Post Added', WPNE);
    }

    public static function register()
    {
        add_action('wp_insert_post', static::class . '::handle', 10, 3);
    }

    /**
     * @param int     $post_id
     * @param WP_Post $post
     * @param bool    $update
     * @throws ErrorException
     */
    public static function handle($post_id, $post, $update)
    {
        if ($update) {
            return;
        }

        if ($post->post_type != 'post') {
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

        static::execute($tags);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('Post added "[post-title]" on [site-name]', WPNE);
        $this->message = __("Post by [author-login] <a href=\"mailto:[author-email]»>[author-email]</a>\non [post-date] \nsee at <a href=\"[post-permalink]\">[post-permalink]></a>", WPNE);

        parent::__construct($post);
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
}