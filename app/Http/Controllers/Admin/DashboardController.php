<?php

namespace app\Http\Controllers\Admin;

use app\Http\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        view('admin.dashboard', ['name' => 'ADMIN']);
    }
}