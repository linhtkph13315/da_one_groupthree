<?php

namespace app\Http\Controllers\Admin;

use app\Categories;

class OptionController
{
    protected Categories $categories;

    public function __construct()
    {
        $this->categories = new Categories();
    }

    public function getMenuClient()
    {
        return $this->categories->getMenuData();
    }
}