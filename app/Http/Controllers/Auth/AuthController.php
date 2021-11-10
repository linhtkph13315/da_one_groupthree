<?php

namespace app\Http\Controllers\Auth;

use app\Services\Request;
use app\Users;

class AuthController
{
    protected Users $users;

    public function __construct()
    {
        $this->users = new Users();
    }

    public function getAuth()
    {
        $auth_id = session_get('SESSION_AUTH') ?? '';
        if (empty($auth_id)) {
            return false;
        }
        return $this->users->findUserByID($auth_id);
    }

    public function logout()
    {
        if (isset($_SESSION['SESSION_AUTH'])) {
            session_remove('SESSION_AUTH');
            redirect();
        }
    }

    public static function isLogin()
    {
        if (isset($_SESSION['SESSION_AUTH'])) {
            redirect();
        }
    }
}