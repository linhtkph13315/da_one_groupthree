<?php

namespace app\Http\Controllers;

use app\Products;

class ProductController
{
    protected Products $products;

    public function __construct()
    {
        $this->products = new Products();
    }

    public function details($cate, $brand, $slug)
    {
        $product = $this->products->getProductDetails($cate, $brand, $slug);
        if (empty($product)) {
            error_page();
        }

        view('product-details', [
            'product' => $product,
        ]);
    }
}