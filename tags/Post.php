<?php

namespace notify_events\tags;

use WP_Post;

/**
 * Class Post
 * @package notify_events\tags
 */
class Post
{
    /**
     * @return string[]
     */
    public static function labels()
    {
        return [
            'post-id'        => __('Post ID', WPNE),
            'post-type'      => __('Post Type', WPNE),
            'post-status'    => __('Post Status', WPNE),
            'post-title'     => __('Post Title', WPNE),
            'post-permalink' => __('Post URL', WPNE),
            'post-category'  => __('Post Category', WPNE),
            'post-excerpt'   => __('Post Excerpt', WPNE),
            'post-date'      => __('Post Date', WPNE),
        ];
    }

    /**
     * @param WP_Post $post
     * @return string[]
     */
    public static function values($post)
    {
        return [
            'post-id'        => $post->ID,
            'post-type'      => get_post_type_object($post->post_type)->label,
            'post-status'    => get_post_status_object($post->post_status)->label,
            'post-title'     => $post->post_title,
            'post-permalink' => get_post_permalink($post),
            'post-category'  => get_category($post->post_category)->name,
            'post-excerpt'   => $post->post_excerpt,
            'post-date'      => $post->post_date,
        ];
    }

    /**
     * @return string[]
     */
    public static function preview()
    {
        return [
            'post-id'        => 1,
            'post-type'      => __('Post', WPNE),
            'post-status'    => __('Publish', WPNE),
            'post-title'     => __('My First Post', WPNE),
            'post-permalink' => __('http://example.com', WPNE),
            'post-category'  => __('Category', WPNE),
            'post-excerpt'   => __('My First Post', WPNE),
            'post-date'      => date('Y-m-d H:i:s'),
        ];
    }
}