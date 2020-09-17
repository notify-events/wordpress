<?php

namespace notify_events\modules\wordpress\models\events\post;

use ErrorException;
use notify_events\tags\Common;
use notify_events\tags\Post;
use notify_events\tags\User;
use notify_events\modules\wordpress\models\Event;
use WP_Post;

/**
 * Class PostApproved
 * @package notify_events\modules\wordpress\models\events\post
 */
class PostApproved extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Post Approved', WPNE);
    }

    public static function register()
    {
        add_action('pending_to_publish', static::class . '::handle', 10);
    }

    /**
     * @param WP_Post $post
     * @throws ErrorException
     */
    public static function handle($post)
    {
        if ($post->post_type != 'post') {
            return;
        }

        $author    = get_userdata($post->post_author);
        $editor    = get_userdata(get_post_meta($post->ID, '_edit_last', true));
        $publisher = get_userdata(get_current_user_id());

        $tags = array_merge(
            Post::values($post),
            User::values($author, 'author-'),
            User::values($editor, 'editor-'),
            User::values($publisher, 'publisher-'),
            Common::values()
        );

        static::execute($tags);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('Post approved "[post-title]" on [site-name]', WPNE);
        $this->message = __("Post by [editor-login] <a href=\"mailto:[editor-email]\">[editor-email]</a>\non [post-date]\nsee at <a href=\"[post-permalink]\">[post-permalink]></a>", WPNE);

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
            __('Publisher', WPNE)   => User::labels('publisher-'),
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
            User::preview('publisher-'),
            parent::tag_preview()
        );
    }
}