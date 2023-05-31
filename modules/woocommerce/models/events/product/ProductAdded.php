<?php

namespace notify_events\modules\woocommerce\models\events\product;

use ErrorException;
use notify_events\modules\woocommerce\tags\Product;
use notify_events\modules\woocommerce\models\Event;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\User;
use WP_Post;

/**
 * Class ProductAdded
 * @package notify_events\modules\woocommerce\models\events\product
 */
class ProductAdded extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Product Added', WPNE);
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

        if ($post->post_type != 'product') {
            return;
        }

        $author = get_userdata($post->post_author);
        $editor = get_userdata(get_post_meta($post->ID, '_edit_last', true));

        $product = wc_get_product($post);

        $tags = array_merge(
            Product::values($product),
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
        $this->subject = __('[[site-name]] Product [product-name] is added', WPNE);
        $this->message = __('Product "<a href="[product-url]">[product-name]</a>" on "[site-name]" is added', WPNE);

        parent::__construct($post);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('Product', WPNE)     => Product::labels(),
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
            Product::preview(),
            User::preview('author-'),
            User::preview('editor-'),
            parent::tag_preview()
        );
    }
}
