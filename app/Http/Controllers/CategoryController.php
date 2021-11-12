<?php

namespace app\Http\Controllers;

use app\Categories;

class CategoryController
{
    protected Categories $categories;

    public function __construct()
    {
        $this->categories = new Categories();
    }

    public function index($slug)
    {
        $products = $this->categories->getCategoryBySlug($slug);
        if (empty($products)) {
            error_page();
        }
        view('category', [
            'cate_title' => $this->categories->getCategoryTitle($slug),
            'products' => $products
        ]);
    }
}