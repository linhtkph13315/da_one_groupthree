<?php

namespace app\Services;

class Route
{
    public Request $request;
    public static array $routes = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function get(string $path, $callback, $role = '')
    {
        self::resolve($path, 'GET', $callback, $role);
    }

    public static function post(string $path, $callback, $role = '')
    {
        self::resolve($path, 'POST', $callback, $role);
    }

    public static function auth()
    {
        Route::get('/account/login', 'Auth\LoginController@login');
        Route::post('/account/login', 'Auth\LoginController@login');

        Route::get('/account/register', 'Auth\RegisterController@register');
        Route::post('/account/register', 'Auth\RegisterController@register');

        Route::get('/account/verify/{token}', 'Auth\RegisterController@verify');
        Route::post('/account/verify/{token}', 'Auth\RegisterController@verify');

        Route::get('/account/logout', 'Auth\AuthController@logout');

        Route::get('/account/forgot-password', 'Auth\ResetController@forgotPassword');
        Route::post('/account/forgot-password', 'Auth\ResetController@forgotPassword');

        Route::get('/account/password/reset/{token}', 'Auth\ResetController@resetPassword');
        Route::post('/account/password/reset/{token}', 'Auth\ResetController@resetPassword');
    }

    public static function resolve(string $path, string $method, $callback, $role)
    {
        preg_match_all('/\{(.+?)\}/', $path, $params); // tìm kiếm xem pth có chứa param không /a/{param}
        if ($params) {
            $path = preg_replace('/\{(.+?)\}/', '(.+?)', $path); // nếu có thì thay đổi nó thành (.+)
        }

        $path = str_replace('/', '\/', $path); // đổi dấu / thành \/

        $route = [
            'path' => $path,
            'method' => $method,
            'callback' => $callback,
            'params' => $params[1], // giá trị của params sau khi đã tách {...}
            'role' => $role 
        ]; // tạo mảng route
        array_push(self::$routes, $route); // thêm mảng route và biến routes
    }

    public function map()
    {
        $method = $this->request->method(); // lấy method trang hiện tại
        $path = $this->request->path(); // lấy path trang hiện tại
        foreach (self::$routes as $route) {
            if ($route['method'] == $method) { // kiểm tra xem method trong danh sách có bằng method trang hiện tại không
                $reg = '/^' . $route['path'] . '$/';
                if (preg_match($reg, $path, $params)) { // kiểm tra xem path có params không
                    array_shift($params); // xoá phần tử đầu mảng params
                    $params[] = new Request();
                    $this->call_action_route($route['callback'], $params, $route['role']);
                    return;
                }
            }
        }
        error_page();
    }

    private function call_action_route($callback, $params, $roles)
    {
        $flag = false;
        if (!empty($roles)) { // kiểm tra xem route có sử dụng quyền hay không
            $role_arr = explode('|', $roles); // tách danh sách quyền nếu có
            foreach ($role_arr as $role) {
                if (empty(auth['role'])) { // người dùng chưa có quyền hay chưa đăng nhập thì bỏ qua
                    continue;
                }
                if (strtolower(auth['role']) !== strtolower($role)) { // nếu quyền của người dùng không có trong mảng chỉ định của route thì bỏ qua
                    continue;
                }
                $flag = true; // nếu có thì gán $flag = true
            }
        } else {
            $flag = true; // nếu không có quyền thì ai cũng có thể truy cập
        }

        if ($flag) { // nếu true
            if (is_callable($callback)) { // nếu là hàm
                call_user_func_array($callback, $params); // tạo và sử dụng hàm
                return;
            }

            if (is_string($callback)) { // nếu là chuỗi
                $callback = explode('@', $callback); // tách mảng bằng @
                $controller_name = 'app\\Http\\Controllers\\' . $callback[0]; //đường dẫn của hàm
                $controller = new $controller_name(); // khởi tạo hàm
                call_user_func_array([$controller, $callback[1]], $params); // sử dụng hàm
                return;
            }
        } else {
            error_page();
        }
        return;
    }
}