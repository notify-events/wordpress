<?php

namespace notify_events\modules\wordpress\models\events\comment;

use ErrorException;
use notify_events\modules\wordpress\models\Event;
use notify_events\modules\wordpress\tags\Comment;
use notify_events\modules\wordpress\tags\CommentAuthor;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\Post;
use notify_events\modules\wordpress\tags\User;
use WP_Comment;

/**
 * Class CommentAdded
 * @package notify_events\modules\wordpress\models\events\comment
 */
class CommentAdded extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Comment Added', WPNE);
    }

    public static function register()
    {
        add_action('wp_insert_comment', static::class . '::handle', 10, 2);
    }

    /**
     * @param int        $comment_id
     * @param WP_Comment $comment
     * @throws ErrorException
     */
    public static function handle($comment_id, $comment)
    {
        $post        = get_post($comment->comment_post_ID);
        $post_author = get_userdata($post->post_author);

        $tags = array_merge(
            Comment::values($comment),
            CommentAuthor::values($comment),
            Post::values($post),
            User::values($post_author, 'post-author-'),
            Common::values()
        );

        static::execute($tags);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('Comment added to post "[post-title]" on [site-name]', WPNE);
        $this->message = __("[comment-content]\nComment by <a href=\"mailto:[author-email]\">[author-name]</a>\non [comment-date]\nsee at <a href=\"[post-permalink]\">[post-permalink]</a>", WPNE);

        parent::__construct($post);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('Comment', WPNE)     => Comment::labels(),
            __('Author', WPNE)      => CommentAuthor::labels(),
            __('Post', WPNE)        => Post::labels(),
            __('Post Author', WPNE) => User::labels('post-author-'),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            Comment::preview(),
            CommentAuthor::preview(),
            Post::preview(),
            User::preview('post-author-'),
            parent::tag_preview()
        );
    }
}