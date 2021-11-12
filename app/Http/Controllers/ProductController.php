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
        $product_default = $this->products->getProductDetails($cate_parent, $cate_child, $product_slug);
        
        if (empty($product_default)) {
            errors_page();
        }
        if ($product_default['is_variant'] === 1) {
            $product_variant = $this->products->getProductDetailsVariant($cate_parent, $cate_child, $product_slug);
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
            if (empty($product_variant)) {
                errors_page();
            }
        } 
        
        view('product-details', [
            'product_default' => $product_default,
            'product_variant' => $product_variant,
        ]);
    }
}