<?php

namespace app\Http\Controllers;

use app\Products;
use app\Services\Request;

class ProductController
{
    protected Products $products;

    public function __construct()
    {
        $this->products = new Products();
    }

    public function details($cate_parent, $cate_child, $product_slug, Request $request)
    {
        $product_variant = [];
        $product_configuration = [];
        $product_default = $this->products->getProductDetails($cate_parent, $cate_child, $product_slug);
        if (empty($product_default)) {
            error_page();
        }
        if ($product_default['is_variant'] === 1) {
            $product_variant = $this->products->getProductDetailsVariant($cate_parent, $cate_child, $product_slug);
            $product_configuration = $this->products->getProductDetailsConfiguration($cate_parent, $cate_child, $product_slug);
            if (empty($product_variant)) {
                error_page();
            }
            $color = $request->input('color');
            if (empty($color)) {
                $product_default = $product_variant[0];
            } else {
                foreach ($product_variant as $item) {
                    if ($item['product_variant_slug'] == $color) {
                        $product_default = $item;
                    }
                }
            } 
        }
        view('product-details', [
            'product_default' => $product_default,
            'product_variant' => $product_variant,
            'product_configuration' => $product_configuration
        ]);
    }
}