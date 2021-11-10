<?php

namespace app\Services;

use app\Http\Controllers\Auth\AuthController;
use storage\Session;
use app\Helpers\Helper;

class App
{
    public Session $session;
    public Request $request;
    public Route $route;
    public Template $template;
    public AuthController $auth;
    public Helper $helper;
    public static App $app;

    public function __construct()
    {
        $this->session = new Session();
        $this->template = new Template(VIEW_DIR);
        $this->request = new Request();
        $this->helper = new Helper();
        $this->auth = new AuthController();
        $this->route = new Route($this->request);
        self::$app = $this;
    }

    public function run()
    {
        $this->route->map();
    }
}