<?php

namespace app;

use database\DB;

class Products
{
    public function listProduct()
    {
        return DB::table('products P')->select("concat(C.category_name, ' ', P.product_name) as product_full_name",
            'P.*', 'B.brand_name',' B.brand_slug', 'C.category_name as cate_child_name', 
            'C.category_slug as cate_child_slug', 'CParent.category_name as cate_parent_name', 'CParent.category_slug as cate_parent_slug')
        ->leftJoin('brands B', 'B.brand_id', '=', 'P.brand_id')
        ->leftJoin('categories C','C.category_id', '=', 'P.category_id')
        ->leftJoin('categories CParent','C.parent_id', '=', 'CParent.category_id')->execute()->get();
    }

    public function getProductDetailsVariant($cate_parent, $cate_child, $product_slug)
    {
        $product = [];
        $data = DB::table('products P')->select("concat(C.category_name, ' ', P.product_name) as product_full_name",
            'P.*', 'PV.product_variant_name','PV.product_variant_slug','PV.product_variant_price',
            'PV.product_variant_discount','PV.product_variant_image', 'B.brand_name',' B.brand_slug', 'C.category_name as cate_child_name', 
            'C.category_slug as cate_child_slug', 'CParent.category_name as cate_parent_name', 'CParent.category_slug as cate_parent_slug')
        ->leftJoin('product_variants PV', 'PV.product_id', '=', 'P.product_id')
        ->leftJoin('brands B', 'B.brand_id', '=', 'P.brand_id')
        ->leftJoin('categories C','C.category_id', '=', 'P.category_id')
        ->leftJoin('categories CParent','C.parent_id', '=', 'CParent.category_id')
        ->where('CParent.category_slug', '=', $cate_parent)
        ->where("concat(C.category_slug, '-', P.product_slug)", '=', ($cate_child .'-'. $product_slug))
        ->execute();    
        if (count($data->get()) > 1) {
            $product = $data->get();
        } else {
            $product[] = $data->first();
        }
        return $product;
    }

    public function getProductDetailsConfiguration($cate_parent, $cate_child, $product_slug)
    {
        return DB::table('products P')->select('PC.*')
        ->rightJoin('product_configuration PC', 'PC.product_id', '=', 'P.product_id')
        ->leftJoin('categories C','C.category_id', '=', 'P.category_id')
        ->leftJoin('categories CParent','C.parent_id', '=', 'CParent.category_id')
        ->where('CParent.category_slug', '=', $cate_parent)
        ->where( "concat(C.category_slug, '-', P.product_slug)", '=', ($cate_child .'-'. $product_slug))
        ->execute()->first();   
    }

    public function getProductDetails($cate_parent, $cate_child, $product_slug)
    {
        return DB::table('products P')->select('P.*', "concat(C.category_name, ' ', P.product_name) as product_full_name", 
            "concat(C.category_slug, '-', P.product_slug) as product_full_slug", 
            'C.category_name as cate_child_name', 'C.category_slug as cate_child_slug',
            'CParent.category_name as cate_parent_name', 'CParent.category_slug as cate_parent_slug')
        ->leftJoin('brands B', 'B.brand_id', '=', 'P.brand_id')
        ->leftJoin('categories C','C.category_id', '=', 'P.category_id')
        ->leftJoin('categories CParent','CParent.category_id', '=', 'C.parent_id')
        ->where('CParent.category_slug', '=', $cate_parent)
        ->where( "concat(C.category_slug, '-', P.product_slug)", '=', ($cate_child.'-'.$product_slug))
        ->execute()->first();
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