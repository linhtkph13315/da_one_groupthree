<?php

namespace app;

use database\DB;

class Categories
{
    public function getCategoryBySlug($slug) 
    {
        return DB::table('products P')->select("concat(C.category_name, ' ', P.product_name) as product_full_name",
            'P.*', 'PC.*', 'B.brand_name',' B.brand_slug', 'C.category_name as cate_child_name', 
            'C.category_slug as cate_child_slug', 'CParent.category_name as cate_parent_name', 'CParent.category_slug as cate_parent_slug')
            ->leftJoin('product_configuration PC', 'PC.product_id', '=', 'P.product_id')
            ->leftJoin('brands B', 'B.brand_id', '=', 'P.brand_id')
            ->leftJoin('categories C','C.category_id', '=', 'P.category_id')
            ->leftJoin('categories CParent','C.parent_id', '=', 'CParent.category_id')
            ->where('CParent.category_slug', '=', $slug)
            ->execute()->get();
    }

    public function getCategoryTitle($slug) 
    {
        $data = DB::table('categories')->select('*')->where('category_slug', '=', $slug)->execute()->first();
        return $data['category_name'];
    }

    function getMenuData() 
    {
        return DB::table('categories')->select('*')->where('is_menu', '=', 1)->execute()->get();
    }
}