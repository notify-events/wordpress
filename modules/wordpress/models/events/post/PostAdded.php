<?php

namespace notify_events\modules\wordpress\models\events\post;

use ErrorException;
use notify_events\modules\wordpress\models\Event;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\Post;
use notify_events\modules\wordpress\tags\User;
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
        // transition_post_status позволяет точно определить первое ручное сохранение:
        // - auto-draft → draft: WP 3.6+, открытие редактора создаёт auto-draft, пользователь сохраняет черновик
        // - new → draft: WP < 3.6, пост создаётся только в момент первого сохранения
        add_action('transition_post_status', static::class . '::handle', 10, 3);
    }

    /**
     * @param string  $new_status
     * @param string  $old_status
     * @param WP_Post $post
     * @throws ErrorException
     */
    public static function handle($new_status, $old_status, $post)
    {
        if ($new_status !== 'draft' || !in_array($old_status, ['auto-draft', 'new'], true)) {
            return;
        }

        if ($post->post_type !== 'post') {
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