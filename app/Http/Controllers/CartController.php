<?php

namespace app\Http\Controllers;

class CartController
{
    public function __construct()
    {
    }

    public function index()
    {
        view('cart');
    }
}