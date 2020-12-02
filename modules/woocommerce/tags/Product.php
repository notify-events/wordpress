<?php

namespace notify_events\modules\woocommerce\tags;

use WC_Product;

class Product
{
    /**
     * @param bool $include_quantity
     * @return array
     */
    public static function labels($include_quantity = false)
    {
        $labels = [
            'product-id'             => __('ID', WPNE),
            'product-url'            => __('URL', WPNE),
            'product-name'           => __('Name', WPNE),
            'product-sku'            => __('SKU', WPNE),
            'product-stock-quantity' => __('Stock Quantity', WPNE),
            'product-stock-status'   => __('Stock Status', WPNE),
        ];

        if ($include_quantity) {
            $labels['product-quantity'] = __('Quantity', WPNE);
        }

        return $labels;
    }

    /**
     * @param WC_Product $product
     * @param int|null   $quantity
     * @return array
     */
    public static function values($product, $quantity = null)
    {
        $statuses = wc_get_product_stock_status_options();

        $values = [
            'product-id'             => $product->get_id(),
            'product-url'            => $product->get_permalink(),
            'product-name'           => $product->get_name(),
            'product-sku'            => $product->get_sku(),
            'product-stock-quantity' => $product->get_stock_quantity(),
            'product-stock-status'   => $statuses[$product->get_stock_status()],
        ];

        if ($quantity !== null) {
            $values['product-quantity'] = $quantity;
        }

        return $values;
    }

    /**
     * @param bool $include_quantity
     * @return array
     */
    public static function preview($include_quantity = false)
    {
        $statuses = wc_get_product_stock_status_options();

        $preview = [
            'product-id'             => 1,
            'product-url'            => '',
            'product-name'           => 'Test Product',
            'product-sku'            => 'test-product',
            'product-stock-quantity' => 12,
            'product-stock-status'   => $statuses['instock'],
        ];

        if ($include_quantity) {
            $preview['product-quantity'] = 3;
        }

        return $preview;
    }
}