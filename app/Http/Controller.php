<?php

namespace app\Http;

class Controller
{
    public function __construct()
    {
    }

    public function isLogin()
    {
        if (empty(auth)) {
            redirect('account.login');
        }
    }
}