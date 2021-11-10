<?php

namespace app;

class Products
{
    public function listProduct() : array
    {
        return [
            [
                'product_id' => 1,
                'title' => 'San pham 1',
                'slug' => 'san-pham-1',
                'brand_slug' => 'oppo',
                'cate_slug' => 'dien-thoai',
                'cate_name' => 'Dien thoai'
            ],
            [
                'product_id' => 2,
                'title' => 'San pham 2',
                'slug' => 'san-pham-2',
                'brand_slug' => 'samsung',
                'cate_slug' => 'tablet',
                'cate_name' => 'Tablet'
            ],
            [
                'product_id' => 3,
                'title' => 'San pham 3',
                'slug' => 'san-pham-3',
                'brand_slug' => 'iphone',
                'cate_slug' => 'dien-thoai',
                'cate_name' => 'Dien thoai'
            ]
        ];
    }

    public function getProductDetails($cate, $brand, $slug) : array
    {
        $data = $this->listProduct();

        $result = [];
        foreach ($data as $value) {
            if ($value['cate_slug'] == $cate && $value['brand_slug'] == $brand && $value['slug'] == $slug) {
                $result = $value;
            }
        }

        return $result;
    }

    public function getProductByID($id) : array
    {
        $data = $this->listProduct();

        $result = [];
        foreach ($data as $value) {
            if ($value['product_id'] == $id) {
                $result = $value;
            }
        }

        return $result;
    }
}