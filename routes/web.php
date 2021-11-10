<?php
use app\Services\Route;

// Home
Route::get('/', 'HomeController@index'); // trang chủ

// Category
Route::get('/danh-muc-{category}', 'CategoryController@index'); // danh mục

// Product
Route::get('/{cate}/{brand}-{slug}', 'ProductController@details'); // trang chi tiết sản phẩm

// Admin
Route::get('/admin', 'Admin\DashboardController@index');

// Admin product
Route::get('/admin/product', 'Admin\ProductController@index');
Route::get('/admin/product/create', 'Admin\ProductController@create');
Route::post('/admin/product/create', 'Admin\ProductController@create');
Route::get('/admin/product/update/{id}', 'Admin\ProductController@update');
Route::post('/admin/product/update/{id}', 'Admin\ProductController@update');
Route::get('/admin/product/delete/{id}', 'Admin\ProductController@delete');

// Auth
Route::auth();