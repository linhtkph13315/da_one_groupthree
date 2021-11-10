<?php

namespace app\Http\Controllers;

use app\Http\Controller;
use app\Products;

class HomeController extends Controller
{
    protected Products $products;

    public function __construct()
    {
        $this->products = new Products();
    }

    public function index()
    {
        $data = $this->products->listProduct();

        view('welcome', [
            'products' => $data
        ]);
    }
}