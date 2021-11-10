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
        $category = $this->categories->getCategoryBySlug($slug);
        if (empty($category)) {
            error_page();
        }
        
        view('category', ['title' => $category['cate_name']]);
    }
}