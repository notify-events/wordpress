<?php

namespace notify_events\modules\woocommerce\models\events\product;

use ErrorException;
use notify_events\modules\woocommerce\tags\Product;
use notify_events\modules\woocommerce\models\Event;
use notify_events\modules\wordpress\tags\Common;
use notify_events\modules\wordpress\tags\User;
use WP_Post;

/**
 * Class ProductApproved
 * @package notify_events\modules\woocommerce\models\events\product
 */
class ProductApproved extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Product Approved', WPNE);
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
        $this->subject = __('[[site-name]] Product [product-name] is approved', WPNE);
        $this->message = __('Product "<a href="[product-url]">[product-name]</a>" on "[site-name]" is approved', WPNE);

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
