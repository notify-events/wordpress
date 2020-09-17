<?php

namespace notify_events\tags;

use WP_Comment;

/**
 * Class CommentAuthor
 * @package notify_events\tags
 */
class CommentAuthor
{
    /**
     * @return string[]
     */
    public static function labels()
    {
        return [
            'author-name'  => __('Author Name', WPNE),
            'author-ip'    => __('Author IP', WPNE),
            'author-email' => __('Author E-Mail', WPNE),
            'author-url'   => __('Author URL', WPNE),
            'author-agent' => __('Author User Agent', WPNE),
        ];
    }

    /**
     * @param WP_Comment $comment
     * @return array
     */
    public static function values($comment)
    {
        return [
            'author-name'  => $comment->comment_author,
            'author-ip'    => $comment->comment_author_IP,
            'author-email' => $comment->comment_author_email,
            'author-url'   => $comment->comment_author_url,
            'author-agent' => $comment->comment_agent,
        ];
    }

    /**
     * @return string[]
     */
    public static function preview()
    {
        return [
            'author-name'  => 'Example',
            'author-ip'    => '127.0.0.1',
            'author-email' => 'mail@example.com',
            'author-url'   => 'http://example.com',
            'author-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36e',
        ];
    }
}