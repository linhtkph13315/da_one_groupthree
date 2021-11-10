<?php

namespace app\Services;

class Request extends Validation
{
    public function path() : string
    {
        $app_folder = "/".APP_FOLDER."/"; // thư mục project
        $app_folder = strtolower($app_folder);
        $path = $_SERVER['REQUEST_URI'] ?? '/'; // lấy url của trang hiện tại, nếu trống trả về /
        $path = str_replace($app_folder, '/', $path); // xoá bỏ thư mục project trong url
        $position = strpos($path, '?'); // kiểm tra xong có chứa query string không
        if ($position === false) {
            return $path; // nếu không sẽ chạy luôn path
        }
        return substr($path, 0, $position); // nếu có thì trích xuất path trên url chứa query string mà chạy nó
    }

    public function method() : string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']); // lấy method
    }

    public function get() : string
    {
        return $this->method() === 'GET'; // kiểm tra method có phải là get đúng true sai false
    }

    public function post() : string
    {
        return $this->method() === 'POST'; // kiểm tra method có phải là post đúng true sai false
    }

    public function input($name)
    {
        return $this->body()[$name] ?? ''; 
    }

    public function file($name)
    {
        return $_FILES[$name];
    }

    public function hasFile($name) : bool
    {
        if ($_FILES[$name]['size'] > 0) {
            return true;
        }
        return false;
    }

    public function body() : array
    {
        $body = [];
        if ($this->method() === 'GET')
        {
            foreach ($_GET as $key => $value)
            {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS); // tìm kiếm giá trị của input và gán vào mảng theo key
            }
        }
        if ($this->method() === 'POST')
        {
            foreach ($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); // tìm kiếm giá trị của input và gán vào mảng theo key
            }
        }
        return $body;
    }
}