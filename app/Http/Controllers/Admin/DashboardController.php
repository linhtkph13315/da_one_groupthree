<?php

namespace app\Http\Controllers\Admin;

class DashboardController
{
    public function __construct()
    {
    }

    public function index()
    {
        view('admin.dashboard', ['name' => 'ADMIN']);
    }
}