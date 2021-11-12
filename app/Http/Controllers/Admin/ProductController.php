<?php

namespace app\Http\Controllers\Admin;

use app\Products;
use app\Services\Request;

class ProductController
{
    protected Products $products;

    public function __construct()
    {
        $this->products = new Products();
    }

    public function index()
    {
        view('admin.products.list', [
            'products' => $this->products->listProduct()
        ]);
    }

    public function create(Request $request)
    {
        $validate = [];
        if ($request->post()) {
            $title = $request->input('title');
            $image = $request->file('image');
            $validate = $request->validate([
                'title' => 'required'
            ], [
                'title.required' => 'Vui long dien title'
            ]);

            if ($request->hasFile('image') == false) {
                $validate['image'][] = 'Vui long nhap anh';
            }

            if (empty($validate)) {
                $image_upload = upload_image($image, 'product');
                session_set('message', 'Thêm thành công '.$image_upload);
                redirect('admin.product');
            }
        }
        view('admin.products.create', [
            'errors' => $validate
        ]);
    }

    public function update($id, Request $request)
    {
        $result = $this->products->getProductByID($id);
        if (empty($result)) {
            error_page();
        }
        $validate = [];
        if ($request->post()) {
            $title = $request->input('title');
            $image = $request->file('image');

            $validate = $request->validate([
                'title' => 'required'
            ], [
                'title.required' => 'Vui long dien title'
            ]);

            if (empty($validate)) {
                $image_upload = 'IMG cũ.jpg';
                if ($request->hasFile('image')) {
                    $image_upload = upload_image($image, 'product');
                }
                session_set('message', 'Update thành công '. $image_upload);
                redirect('admin.product');
            }
        }
        view('admin.products.update', [
            'product' => $result,
            'errors' => $validate
        ]);
    }

    public function delete($id)
    {
        session_set('message', 'Xoá thành công');
        redirect('admin.product');
    }


    public function configuration($id)
    {
        view('admin.products.configuration');
    }

    public function variant()
    {
        view('admin.products.variants.list');
    }

    public function variantCreate()
    {
        view('admin.products.variants.create');
    }

    public function variantUpdate(Request $request)
    {
        view('admin.products.variants.update');
    }

    public function variantDelete(Request $request)
    {
        session_set('message', 'Xoá thành công');
        redirect('admin.product.variant?pid='.$request->input('pid'));
    }
}