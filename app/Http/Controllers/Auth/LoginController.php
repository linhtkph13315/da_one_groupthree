<?php

namespace app\Http\Controllers\Auth;

use app\Services\Request;
use app\Users;

class LoginController
{
    protected Users $users;

    public function __construct()
    {
        $this->users = new Users();
        AuthController::isLogin();
    }

    public function login(Request $request)
    {
        $validate = [];
        $message = '';
        if ($request->post()) {
            $input = $request->body();

            $validate = $request->validate([
                'username' => 'required',
                'password' => 'required|min:6|max:32',
            ]);

            if (empty($validate))
            {
                $result = $this->users->loginWithEmailOrUsername($input['username']);
                if (empty($result))
                {
                    $message = 'Tài khoản không tồn tại';
                }
                else if ($result['is_verify'] == 0)
                {
                    $message = 'Tài khoản chưa được kích hoạt';
                }
                else if ($result['is_active'] == 0)
                {
                    $message = 'Tài khoản đã bị khoá';
                }
                else if ($result['username'] == $input['username'] || $result['email'] == $input['username'])
                {
                    $verify_password = password_verify($input['password'], $result['password']);
                    if ($result['password'] == $verify_password)
                    {
                        session_set('SESSION_AUTH', $result['user_id']);
                        session_set('message','Đăng nhập thành công');
                        redirect('/');
                    }
                    else
                    {
                        $message = 'Mật khẩu không chính xác';
                    }
                }
            }
        }
        view('auth.login', [
            'errors' => $validate,
            'message' => $message
        ]);
    }
}