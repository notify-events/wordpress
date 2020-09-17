<?php

namespace notify_events\tags;

use WP_Comment;

/**
 * Class Post
 * @package notify_events\tags
 */
class Comment
{
    /**
     * @return string[]
     */
    public static function labels()
    {
        return [
            'comment-id'      => __('Comment ID', WPNE),
            'comment-content' => __('Comment Content', WPNE),
            'comment-type'    => __('Comment Type', WPNE),
            'comment-status'  => __('Comment Status', WPNE),
            'comment-date'    => __('Comment Date', WPNE),
        ];
    }

    /**
     * @param WP_Comment $comment
     * @return string[]
     */
    public static function values($comment)
    {
        return [
            'comment-id'      => $comment->comment_ID,
            'comment-content' => $comment->comment_content,
            'comment-type'    => $comment->comment_type,
            'comment-status'  => wp_get_comment_status($comment->comment_ID),
            'comment-date'    => $comment->comment_date,
        ];
    }

    /**
     * @return string[]
     */
    public static function preview()
    {
        return [
            'comment-id'      => 1,
            'comment-content' => __('My best comment', WPNE),
            'comment-type'    => __('comment', WPNE),
            'comment-status'  => __('approved', WPNE),
            'comment-date'    => date('Y-m-d H:i:s'),
        ];
    }
}