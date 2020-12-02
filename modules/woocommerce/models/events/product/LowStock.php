<?php

namespace notify_events\modules\woocommerce\models\events\product;

use ErrorException;
use notify_events\modules\woocommerce\models\Event;
use notify_events\modules\woocommerce\tags\Product;
use notify_events\modules\wordpress\tags\Common;
use WC_Product;

/**
 * Class LowStock
 * @package notify_events\modules\woocommerce\models\events\product
 */
class LowStock extends Event
{
    /**
     * @inheritDoc
     */
    public static function event_title()
    {
        return __('Low Stock', WPNE);
    }

    public static function register()
    {
        add_action('woocommerce_low_stock', static::class . '::handle');
    }

    /**
     * @param WC_Product $product
     * @throws ErrorException
     */
    public static function handle(WC_Product $product)
    {
        $tags = array_merge(
            Product::values($product),
            Common::values()
        );

        static::execute($tags);
    }

    /**
     * @inheritDoc
     */
    public function __construct($post = null)
    {
        $this->subject = __('[[site-name]] Product [product-name] is running out of stock', WPNE);
        $this->message = __('Product "<a href="[product-url]">[product-name]</a>" on "[site-name]" is running out of stock', WPNE);

        parent::__construct($post);
    }

    /**
     * @inheritDoc
     */
    public function tag_labels()
    {
        return array_merge_recursive([
            __('Product', WPNE) => Product::labels(),
        ], parent::tag_labels());
    }

    /**
     * @inheritDoc
     */
    public function tag_preview()
    {
        return array_merge(
            Product::preview(),
            parent::tag_preview()
        );
    }
}