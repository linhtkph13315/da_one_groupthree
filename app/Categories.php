<?php

namespace app;

class Categories
{
    public function getCategoryBySlug($slug) : array
    {
        $data = [
            [
                'cate_slug' => 'dien-thoai',
                'cate_name' => 'Dien thoai'
            ],
            [
                'cate_slug' => 'tablet',
                'cate_name' => 'Tablet'
            ]
        ];

        $result = [];
        foreach ($data as $value) {
            if ($value['cate_slug'] == $slug) {
                $result = $value;
            }
        }

        return $result;
    }
}